<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tp_supporting_image}}".
 *
 * @property int $id
 * @property int $assessment_id
 * @property string $image_file
 * @property string|null $image_type
 * @property string|null $description
 * @property int|null $sequence
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class TpSupportingImage extends ActiveRecord
{
    const TYPE_LESSON_PLAN = 'lesson_plan';
    const TYPE_SCHEME_OF_WORK = 'scheme_of_work';
    const TYPE_CLASSROOM = 'classroom';
    const TYPE_RESOURCES = 'resources';
    const TYPE_OTHER = 'other';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tp_supporting_image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['assessment_id', 'image_file'], 'required'],
            [['assessment_id', 'sequence'], 'integer'],
            [['description'], 'string'],
            [['image_file'], 'string', 'max' => 255],
            [['image_type'], 'in', 'range' => [self::TYPE_LESSON_PLAN, self::TYPE_SCHEME_OF_WORK, self::TYPE_CLASSROOM, self::TYPE_RESOURCES, self::TYPE_OTHER]],
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
            'image_file' => 'Image File',
            'image_type' => 'Image Type',
            'description' => 'Description',
            'sequence' => 'Sequence',
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
     * Get image type label
     */
    public static function getImageTypeLabel($type)
    {
        $labels = [
            self::TYPE_LESSON_PLAN => 'Lesson Plan',
            self::TYPE_SCHEME_OF_WORK => 'Scheme of Work',
            self::TYPE_CLASSROOM => 'Classroom Environment',
            self::TYPE_RESOURCES => 'Teaching Resources',
            self::TYPE_OTHER => 'Other',
        ];
        return $labels[$type] ?? $type;
    }
}
