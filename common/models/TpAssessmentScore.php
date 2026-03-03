<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tp_assessment_score}}".
 *
 * @property int $id
 * @property int $assessment_id
 * @property int $rubric_area_id
 * @property int|null $score
 * @property string|null $attainment_level
 * @property string|null $remarks
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class TpAssessmentScore extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tp_assessment_score}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['assessment_id', 'rubric_area_id'], 'required'],
            [['assessment_id', 'rubric_area_id', 'score'], 'integer'],
            [['score'], 'integer', 'min' => 0, 'max' => 10],
            [['remarks'], 'string'],
            [['attainment_level'], 'string', 'max' => 50],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'assessment_id' => 'Assessment ID',
            'rubric_area_id' => 'Rubric Area ID',
            'score' => 'Score',
            'attainment_level' => 'Attainment Level',
            'remarks' => 'Remarks',
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
            // Auto-calculate attainment level based on score
            if ($this->score !== null) {
                $this->attainment_level = TpRubricArea::getAttainmentLevel($this->score);
            }
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    /**
     * Get assessment
     */
    public function getAssessment()
    {
        return $this->hasOne(TpAssessment::class, ['id' => 'assessment_id']);
    }

    /**
     * Get rubric area
     */
    public function getRubricArea()
    {
        return $this->hasOne(TpRubricArea::class, ['id' => 'rubric_area_id']);
    }
}
