<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tp_assessment}}".
 *
 * @property int $id
 * @property int $student_id
 * @property int $supervisor_id
 * @property string|null $zone_id
 * @property string|null $assessment_date
 * @property string $status
 * @property int|null $total_score
 * @property string|null $overall_performance
 * @property string|null $supervisor_remarks
 * @property string|null $coordinator_remarks
 * @property bool $is_validated
 * @property int|null $validated_by
 * @property string|null $validated_at
 * @property string|null $submitted_at
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property TpSupportingImage[] $supportingImages
 */
class TpAssessment extends ActiveRecord
{
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_VALIDATED = 'validated';
    const STATUS_REJECTED = 'rejected';

    const PERFORMANCE_BE = 'BE';
    const PERFORMANCE_AE = 'AE';
    const PERFORMANCE_ME = 'ME';
    const PERFORMANCE_EE = 'EE';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tp_assessment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'supervisor_id'], 'required'],
            [['student_id', 'supervisor_id', 'validated_by', 'total_score'], 'integer'],
            [['supervisor_remarks', 'coordinator_remarks'], 'string'],
            [['assessment_date', 'validated_at', 'submitted_at', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_SUBMITTED, self::STATUS_REVIEWED, self::STATUS_VALIDATED, self::STATUS_REJECTED]],
            [['zone_id', 'overall_performance'], 'string', 'max' => 50],
            [['is_validated'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Assessment ID',
            'student_id' => 'Student',
            'supervisor_id' => 'Supervisor',
            'zone_id' => 'Zone',
            'assessment_date' => 'Assessment Date',
            'status' => 'Status',
            'total_score' => 'Total Score',
            'overall_performance' => 'Overall Performance',
            'supervisor_remarks' => 'Supervisor Remarks',
            'coordinator_remarks' => 'Coordinator Remarks',
            'is_validated' => 'Is Validated',
            'validated_by' => 'Validated By',
            'validated_at' => 'Validated At',
            'submitted_at' => 'Submitted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->assessment_date = $this->assessment_date ?? date('Y-m-d');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    /**
     * Get student
     */
    public function getStudent()
    {
        return $this->hasOne(TpStudent::class, ['id' => 'student_id']);
    }

    /**
     * Get supervisor
     */
    public function getSupervisor()
    {
        return $this->hasOne(TpLecturer::class, ['id' => 'supervisor_id']);
    }

    /**
     * Get validator (zone coordinator)
     */
    public function getValidator()
    {
        return $this->hasOne(TpLecturer::class, ['id' => 'validated_by']);
    }

    /**
     * Get assessment scores
     */
    public function getScores()
    {
        return $this->hasMany(TpAssessmentScore::class, ['assessment_id' => 'id'])
            ->joinWith('rubricArea')
            ->orderBy(['tp_rubric_area.sequence' => SORT_ASC]);
    }

    /**
     * Get supporting images (alias for images)
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupportingImages()
    {
        return $this->hasMany(TpSupportingImage::class, ['assessment_id' => 'id']);
    }

    /**
     * @deprecated use getSupportingImages() instead. This method remains for
     * backward compatibility and will be removed in a future release.
     */
    public function getImages()
    {
        return $this->getSupportingImages();
    }

    /**
     * Get reports
     */
    public function getReports()
    {
        return $this->hasMany(TpReport::class, ['assessment_id' => 'id']);
    }

    /**
     * Calculate total score from all rubric areas
     */
    public function calculateTotalScore()
    {
        $scores = TpAssessmentScore::find()
            ->where(['assessment_id' => $this->id])
            ->sum('score') ?? 0;
        return $scores;
    }

    /**
     * Determine overall performance level
     */
    public function determineOverallPerformance()
    {
        $total = $this->total_score ?? $this->calculateTotalScore();
        $maxPossible = 120; // 12 areas × 10 marks

        $percentage = ($total / $maxPossible) * 100;

        if ($percentage >= 80) {
            return self::PERFORMANCE_EE;
        } elseif ($percentage >= 60) {
            return self::PERFORMANCE_ME;
        } elseif ($percentage >= 40) {
            return self::PERFORMANCE_AE;
        } else {
            return self::PERFORMANCE_BE;
        }
    }

    /**
     * Get performance label
     */
    public static function getPerformanceLabel($performance)
    {
        $labels = [
            self::PERFORMANCE_BE => 'Below Expectations',
            self::PERFORMANCE_AE => 'Approaching Expectations',
            self::PERFORMANCE_ME => 'Meets Expectations',
            self::PERFORMANCE_EE => 'Exceeds Expectations',
        ];
        return $labels[$performance] ?? $performance;
    }

    /**
     * Get status label
     */
    public static function getStatusLabel($status)
    {
        $labels = [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_REVIEWED => 'Reviewed',
            self::STATUS_VALIDATED => 'Validated',
            self::STATUS_REJECTED => 'Rejected',
        ];
        return $labels[$status] ?? $status;
    }
}
