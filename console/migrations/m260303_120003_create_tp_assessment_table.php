<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tp_assessment}}`.
 */
class m260303_120003_create_tp_assessment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tp_assessment}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'supervisor_id' => $this->integer()->notNull(),
            'zone_id' => $this->string(50),
            'assessment_date' => $this->date(),
            'status' => $this->string(50)->defaultValue('draft'), // draft, submitted, reviewed, validated, rejected
            'total_score' => $this->integer(),
            'overall_performance' => $this->string(50), // BE, AE, ME, EE
            'supervisor_remarks' => $this->text(),
            'coordinator_remarks' => $this->text(),
            'is_validated' => $this->boolean()->defaultValue(false),
            'validated_by' => $this->integer(),
            'validated_at' => $this->dateTime(),
            'submitted_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createIndex('idx_tp_assessment_student', '{{%tp_assessment}}', 'student_id');
        $this->createIndex('idx_tp_assessment_supervisor', '{{%tp_assessment}}', 'supervisor_id');
        $this->createIndex('idx_tp_assessment_status', '{{%tp_assessment}}', 'status');
        $this->createIndex('idx_tp_assessment_zone', '{{%tp_assessment}}', 'zone_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tp_assessment}}');
    }
}
