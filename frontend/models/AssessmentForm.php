<?php

namespace frontend\models;

use common\models\TpAssessment;
use common\models\TpAssessmentScore;
use common\models\TpStudent;
use common\models\TpRubricArea;
use common\models\TpSupportingImage;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * AssessmentForm model for creating and updating TP assessments.
 */
class AssessmentForm extends Model
{
    public $id;
    public $student_id;
    public $assessment_date;
    public $supervisor_remarks;
    public $supervising_files;
    public $scores = [];
    public $remarks = [];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'assessment_date'], 'required'],
            [['student_id'], 'integer'],
            [['student_id'], 'exist', 'targetClass' => TpStudent::class, 'targetAttribute' => 'id'],
            [['assessment_date'], 'date', 'format' => 'php:Y-m-d'],
            [['supervisor_remarks'], 'string'],
            [['supervising_files'], 'file', 'maxFiles' => 5, 'extensions' => 'jpg,jpeg,png,pdf'],
            [['scores'], 'safe'],
            [['remarks'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'student_id' => 'Student',
            'assessment_date' => 'Assessment Date',
            'supervisor_remarks' => 'Supervisor Remarks',
            'supervising_files' => 'Supporting Images (max 5)',
        ];
    }

    /**
     * Save assessment and related data
     */
    public function saveAssessment($supervisorId)
    {
        if (!$this->validate()) {
            return false;
        }

        $assessment = $this->id 
            ? TpAssessment::findOne($this->id)
            : new TpAssessment();

        $assessment->student_id = $this->student_id;
        $assessment->supervisor_id = $supervisorId;
        $assessment->assessment_date = $this->assessment_date;
        $assessment->supervisor_remarks = $this->supervisor_remarks;
        
        $student = TpStudent::findOne($this->student_id);
        $assessment->zone_id = $student->zone;

        if (!$assessment->save()) {
            $this->addError('general', 'Failed to save assessment');
            return false;
        }

        // Save scores
        $rubricAreas = TpRubricArea::find()->orderBy(['sequence' => SORT_ASC])->all();
        
        foreach ($rubricAreas as $area) {
            $score = isset($this->scores[$area->id]) ? (int)$this->scores[$area->id] : 0;
            $remark = $this->remarks[$area->id] ?? '';

            $assessmentScore = TpAssessmentScore::find()
                ->where(['assessment_id' => $assessment->id, 'rubric_area_id' => $area->id])
                ->one() ?? new TpAssessmentScore();

            $assessmentScore->assessment_id = $assessment->id;
            $assessmentScore->rubric_area_id = $area->id;
            $assessmentScore->score = $score;
            $assessmentScore->remarks = $remark;

            if (!$assessmentScore->save()) {
                $this->addError('general', "Failed to save score for {$area->area_name}");
                return false;
            }
        }

        // Calculate and update total score
        $totalScore = $assessment->calculateTotalScore();
        $assessment->total_score = $totalScore;
        $assessment->overall_performance = $assessment->determineOverallPerformance();
        
        if (!$assessment->update(['total_score', 'overall_performance'])) {
            $this->addError('general', 'Failed to update assessment scores');
            return false;
        }

        // Handle file uploads
        $uploadedFiles = UploadedFile::getInstances($this, 'supervising_files');
        foreach ($uploadedFiles as $file) {
            $filename = time() . '_' . uniqid() . '.' . $file->extension;
            $uploadPath = Yii::getAlias('@frontend/web/uploads/assessments/');
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($file->saveAs($uploadPath . $filename)) {
                $image = new TpSupportingImage();
                $image->assessment_id = $assessment->id;
                $image->image_file = $filename;
                $image->image_type = TpSupportingImage::TYPE_OTHER;
                $image->save();
            }
        }

        return $assessment;
    }

    /**
     * Load assessment data
     */
    public function loadFromAssessment(TpAssessment $assessment)
    {
        $this->id = $assessment->id;
        $this->student_id = $assessment->student_id;
        $this->assessment_date = $assessment->assessment_date;
        $this->supervisor_remarks = $assessment->supervisor_remarks;

        // Load scores
        $scores = TpAssessmentScore::find()
            ->where(['assessment_id' => $assessment->id])
            ->all();

        foreach ($scores as $score) {
            $this->scores[$score->rubric_area_id] = $score->score;
            $this->remarks[$score->rubric_area_id] = $score->remarks;
        }
    }
}
