<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tp_report}}`.
 */
class m260303_120006_create_tp_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tp_report}}', [
            'id' => $this->primaryKey(),
            'assessment_id' => $this->integer()->notNull(),
            'report_type' => $this->string(50)->notNull(), // student, office
            'report_title' => $this->string(255),
            'report_file' => $this->string(255),
            'file_type' => $this->string(20), // pdf, docx
            'generated_at' => $this->dateTime(),
            'downloaded_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createIndex('idx_tp_report_assessment', '{{%tp_report}}', 'assessment_id');
        $this->createIndex('idx_tp_report_type', '{{%tp_report}}', 'report_type');

        $this->addForeignKey(
            'fk_tp_report_assessment',
            '{{%tp_report}}',
            'assessment_id',
            '{{%tp_assessment}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_tp_report_assessment', '{{%tp_report}}');
        $this->dropTable('{{%tp_report}}');
    }
}
