<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tp_audit_log}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $action
 * @property string|null $entity_type
 * @property int|null $entity_id
 * @property string|null $description
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string|null $ip_address
 * @property string|null $created_at
 */
class TpAuditLog extends ActiveRecord
{
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_LOGIN = 'login';
    const ACTION_SUBMIT = 'submit';
    const ACTION_VALIDATE = 'validate';
    const ACTION_REJECT = 'reject';
    const ACTION_DOWNLOAD = 'download';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tp_audit_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'entity_id'], 'integer'],
            [['description'], 'string'],
            [['old_values', 'new_values'], 'safe'],
            [['action', 'entity_type', 'ip_address'], 'string', 'max' => 255],
            [['created_at'], 'safe'],
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
            'action' => 'Action',
            'entity_type' => 'Entity Type',
            'entity_id' => 'Entity ID',
            'description' => 'Description',
            'old_values' => 'Old Values',
            'new_values' => 'New Values',
            'ip_address' => 'IP Address',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = date('Y-m-d H:i:s');
            if (empty($this->ip_address)) {
                $this->ip_address = Yii::$app->request->userIP;
            }
            return true;
        }
        return false;
    }

    /**
     * Get user
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Log an action
     */
    public static function logAction($action, $entityType, $entityId, $description = '', $oldValues = [], $newValues = [])
    {
        $log = new self();
        $log->user_id = Yii::$app->user->id ?? null;
        $log->action = $action;
        $log->entity_type = $entityType;
        $log->entity_id = $entityId;
        $log->description = $description;
        $log->old_values = !empty($oldValues) ? json_encode($oldValues) : null;
        $log->new_values = !empty($newValues) ? json_encode($newValues) : null;
        $log->ip_address = Yii::$app->request->userIP;
        return $log->save();
    }
}
