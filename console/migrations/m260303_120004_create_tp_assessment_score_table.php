<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tp_assessment_score}}`.
 */
class m260303_120004_create_tp_assessment_score_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tp_assessment_score}}', [
            'id' => $this->primaryKey(),
            'assessment_id' => $this->integer()->notNull(),
            'rubric_area_id' => $this->integer()->notNull(),
            'score' => $this->integer(),
            'attainment_level' => $this->string(50), // BE, AE, ME, EE
            'remarks' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createIndex('idx_tp_assessment_score_assessment', '{{%tp_assessment_score}}', 'assessment_id');
        $this->createIndex('idx_tp_assessment_score_rubric', '{{%tp_assessment_score}}', 'rubric_area_id');

        $this->addForeignKey(
            'fk_tp_assessment_score_assessment',
            '{{%tp_assessment_score}}',
            'assessment_id',
            '{{%tp_assessment}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_tp_assessment_score_rubric',
            '{{%tp_assessment_score}}',
            'rubric_area_id',
            '{{%tp_rubric_area}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_tp_assessment_score_assessment', '{{%tp_assessment_score}}');
        $this->dropForeignKey('fk_tp_assessment_score_rubric', '{{%tp_assessment_score}}');
        $this->dropTable('{{%tp_assessment_score}}');
    }
}
