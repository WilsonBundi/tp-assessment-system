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
    public $student_input; // allow typing registration number or name
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
            [['assessment_date'], 'required'],
            // at least one of student_id or student_input must be provided
            ['student_input', 'required', 'when' => function($model) {
                return empty($model->student_id);
            }, 'whenClient' => "function (attribute, value) { return $('#assessmentform-student_id').val() == ''; }"],
            [['student_id'], 'integer'],
            [['student_id'], 'exist', 'targetClass' => TpStudent::class, 'targetAttribute' => 'id'],
            [['student_input'], 'string'],
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

        // resolve student by id or typed input
        if ($this->student_id) {
            $assessment->student_id = $this->student_id;
        } elseif (!empty($this->student_input)) {
            // try to find by registration number
            $input = trim($this->student_input);
            $student = TpStudent::find()->where(['registration_number' => $input])->one();
            if (!$student) {
                // attempt parse "reg - name"
                if (strpos($input, '-') !== false) {
                    list($reg, $name) = array_map('trim', explode('-', $input, 2));
                    $student = new TpStudent();
                    $student->registration_number = $reg;
                    $student->full_name = $name;
                    $student->save(false);
                } else {
                    $student = new TpStudent();
                    $student->registration_number = $input;
                    $student->full_name = $input;
                    $student->save(false);
                }
            }
            $assessment->student_id = $student->id;
            // remember id for later use (zone assignment and potential re-use)
            $this->student_id = $student->id;
        }
        $assessment->supervisor_id = $supervisorId;
        $assessment->assessment_date = $this->assessment_date;
        $assessment->supervisor_remarks = $this->supervisor_remarks;
        
        // determine zone from the saved student record (in case id was set above)
        $stu = TpStudent::findOne($assessment->student_id);
        if ($stu) {
            $assessment->zone_id = $stu->zone;
        }

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
        $this->student_input = $assessment->student ? ($assessment->student->registration_number . ' - ' . $assessment->student->full_name) : null;
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
