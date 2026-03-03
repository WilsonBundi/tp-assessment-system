<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tp_notification}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $assessment_id
 * @property string|null $notification_type
 * @property string|null $title
 * @property string|null $message
 * @property bool $is_read
 * @property string|null $read_at
 * @property string|null $sent_at
 * @property string|null $created_at
 */
class TpNotification extends ActiveRecord
{
    const TYPE_SUBMISSION = 'submission';
    const TYPE_REVIEW = 'review';
    const TYPE_VALIDATION = 'validation';
    const TYPE_FEEDBACK = 'feedback';
    const TYPE_REJECTION = 'rejection';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tp_notification}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'assessment_id'], 'integer'],
            [['title', 'message'], 'string'],
            [['notification_type'], 'string', 'max' => 50],
            [['is_read'], 'boolean'],
            [['read_at', 'sent_at', 'created_at'], 'safe'],
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
            'assessment_id' => 'Assessment ID',
            'notification_type' => 'Notification Type',
            'title' => 'Title',
            'message' => 'Message',
            'is_read' => 'Is Read',
            'read_at' => 'Read At',
            'sent_at' => 'Sent At',
            'created_at' => 'Created At',
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
                $this->sent_at = date('Y-m-d H:i:s');
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
     * Get assessment
     */
    public function getAssessment()
    {
        return $this->hasOne(TpAssessment::class, ['id' => 'assessment_id']);
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->is_read = true;
        $this->read_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    /**
     * Create and send notification
     */
    public static function notify($userId, $title, $message, $type = self::TYPE_FEEDBACK, $assessmentId = null)
    {
        $notification = new self();
        $notification->user_id = $userId;
        $notification->assessment_id = $assessmentId;
        $notification->notification_type = $type;
        $notification->title = $title;
        $notification->message = $message;
        return $notification->save();
    }

    /**
     * Get notification type label
     */
    public static function getTypeLabel($type)
    {
        $labels = [
            self::TYPE_SUBMISSION => 'Assessment Submitted',
            self::TYPE_REVIEW => 'Assessment Under Review',
            self::TYPE_VALIDATION => 'Assessment Validated',
            self::TYPE_FEEDBACK => 'Feedback Available',
            self::TYPE_REJECTION => 'Assessment Rejected',
        ];
        return $labels[$type] ?? $type;
    }
}
