<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tp_lecturer}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $tp_assigned_code
 * @property string|null $telephone_number
 * @property string|null $payroll_number
 * @property string|null $zone
 * @property string $role
 * @property bool $is_active
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class TpLecturer extends ActiveRecord
{
    const ROLE_SUPERVISOR = 'supervisor';
    const ROLE_COORDINATOR = 'zone_coordinator';
    const ROLE_TP_OFFICE = 'tp_office';
    const ROLE_DEPARTMENT_CHAIR = 'department_chair';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tp_lecturer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'tp_assigned_code', 'role'], 'required'],
            [['user_id'], 'integer'],
            [['tp_assigned_code'], 'unique'],
            [['name', 'telephone_number', 'payroll_number', 'zone'], 'string', 'max' => 255],
            [['role'], 'in', 'range' => [self::ROLE_SUPERVISOR, self::ROLE_COORDINATOR, self::ROLE_TP_OFFICE, self::ROLE_DEPARTMENT_CHAIR]],
            [['is_active'], 'boolean'],
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
            'user_id' => 'User ID',
            'name' => 'Name',
            'tp_assigned_code' => 'TP Assigned Code',
            'telephone_number' => 'Telephone Number',
            'payroll_number' => 'Payroll Number',
            'zone' => 'Zone',
            'role' => 'Role',
            'is_active' => 'Is Active',
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
     * Get user relation
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Get assessments conducted by this supervisor
     */
    public function getAssessments()
    {
        return $this->hasMany(TpAssessment::class, ['supervisor_id' => 'id']);
    }

    /**
     * Get role label
     */
    public static function getRoleLabel($role)
    {
        $labels = [
            self::ROLE_SUPERVISOR => 'Supervisor/Lecturer',
            self::ROLE_COORDINATOR => 'Zone Coordinator',
            self::ROLE_TP_OFFICE => 'TP Office',
            self::ROLE_DEPARTMENT_CHAIR => 'Department Chair',
        ];
        return $labels[$role] ?? $role;
    }
}
