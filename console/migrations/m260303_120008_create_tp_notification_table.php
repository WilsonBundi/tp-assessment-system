<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tp_notification}}`.
 */
class m260303_120008_create_tp_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tp_notification}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'assessment_id' => $this->integer(),
            'notification_type' => $this->string(50), // submission, review, validation, feedback
            'title' => $this->string(255),
            'message' => $this->text(),
            'is_read' => $this->boolean()->defaultValue(false),
            'read_at' => $this->dateTime(),
            'sent_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
        ]);

        $this->createIndex('idx_tp_notification_user', '{{%tp_notification}}', 'user_id');
        $this->createIndex('idx_tp_notification_assessment', '{{%tp_notification}}', 'assessment_id');
        $this->createIndex('idx_tp_notification_is_read', '{{%tp_notification}}', 'is_read');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tp_notification}}');
    }
}
