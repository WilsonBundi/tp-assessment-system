<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tp_rubric_area}}".
 *
 * @property int $id
 * @property string $area_code
 * @property string $area_name
 * @property string|null $description
 * @property int $max_score
 * @property int|null $sequence
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class TpRubricArea extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tp_rubric_area}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area_code', 'area_name'], 'required'],
            [['area_code'], 'unique'],
            [['description'], 'string'],
            [['max_score', 'sequence'], 'integer'],
            [['area_code'], 'string', 'max' => 20],
            [['area_name'], 'string', 'max' => 255],
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
            'area_code' => 'Area Code',
            'area_name' => 'Area Name',
            'description' => 'Description',
            'max_score' => 'Max Score',
            'sequence' => 'Sequence',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Get scores for this rubric area
     */
    public function getScores()
    {
        return $this->hasMany(TpAssessmentScore::class, ['rubric_area_id' => 'id']);
    }

    /**
     * Get attainment level based on score
     */
    public static function getAttainmentLevel($score)
    {
        if ($score >= 8) {
            return 'EE'; // Exceeds Expectations
        } elseif ($score >= 6) {
            return 'ME'; // Meets Expectations
        } elseif ($score >= 4) {
            return 'AE'; // Approaching Expectations
        } else {
            return 'BE'; // Below Expectations
        }
    }

    /**
     * Get attainment level label
     */
    public static function getAttainmentLevelLabel($level)
    {
        $labels = [
            'BE' => 'Below Expectations',
            'AE' => 'Approaching Expectations',
            'ME' => 'Meets Expectations',
            'EE' => 'Exceeds Expectations',
        ];
        return $labels[$level] ?? $level;
    }
}
