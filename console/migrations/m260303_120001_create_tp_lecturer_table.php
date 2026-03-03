<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tp_lecturer}}`.
 */
class m260303_120001_create_tp_lecturer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tp_lecturer}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
            'tp_assigned_code' => $this->string(50)->notNull()->unique(),
            'telephone_number' => $this->string(20),
            'payroll_number' => $this->string(50),
            'zone' => $this->string(50),
            'role' => $this->string(50), // supervisor, zone_coordinator, tp_office, department_chair
            'is_active' => $this->boolean()->defaultValue(true),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createIndex('idx_tp_lecturer_user_id', '{{%tp_lecturer}}', 'user_id');
        $this->createIndex('idx_tp_lecturer_zone', '{{%tp_lecturer}}', 'zone');
        $this->createIndex('idx_tp_lecturer_role', '{{%tp_lecturer}}', 'role');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tp_lecturer}}');
    }
}
