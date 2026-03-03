<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tp_audit_log}}`.
 */
class m260303_120007_create_tp_audit_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tp_audit_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'action' => $this->string(100),
            'entity_type' => $this->string(50), // assessment, report, user, etc
            'entity_id' => $this->integer(),
            'description' => $this->text(),
            'old_values' => $this->json(),
            'new_values' => $this->json(),
            'ip_address' => $this->string(45),
            'created_at' => $this->dateTime(),
        ]);

        $this->createIndex('idx_tp_audit_log_user', '{{%tp_audit_log}}', 'user_id');
        $this->createIndex('idx_tp_audit_log_entity', '{{%tp_audit_log}}', ['entity_type', 'entity_id']);
        $this->createIndex('idx_tp_audit_log_created', '{{%tp_audit_log}}', 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tp_audit_log}}');
    }
}
