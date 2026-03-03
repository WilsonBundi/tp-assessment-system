<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tp_student}}".
 *
 * @property int $id
 * @property string $registration_number
 * @property string $full_name
 * @property string|null $school
 * @property string|null $zone
 * @property string|null $class_form
 * @property string|null $learning_area_subject
 * @property string|null $pathway
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class TpStudent extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tp_student}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['registration_number', 'full_name'], 'required'],
            [['registration_number'], 'unique'],
            [['registration_number', 'school', 'zone', 'class_form', 'learning_area_subject', 'email', 'phone'], 'string', 'max' => 255],
            [['full_name'], 'string', 'max' => 255],
            [['pathway'], 'string', 'max' => 50],
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
            'registration_number' => 'Registration Number',
            'full_name' => 'Full Name',
            'school' => 'School',
            'zone' => 'Zone',
            'class_form' => 'Class/Form',
            'learning_area_subject' => 'Learning Area/Subject',
            'pathway' => 'Pathway',
            'email' => 'Email',
            'phone' => 'Phone',
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
     * Get assessments for this student
     */
    public function getAssessments()
    {
        return $this->hasMany(TpAssessment::class, ['student_id' => 'id']);
    }
}
