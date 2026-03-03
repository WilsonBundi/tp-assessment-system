<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tp_supporting_image}}`.
 */
class m260303_120005_create_tp_supporting_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tp_supporting_image}}', [
            'id' => $this->primaryKey(),
            'assessment_id' => $this->integer()->notNull(),
            'image_file' => $this->string(255)->notNull(),
            'image_type' => $this->string(100), // lesson plan, scheme of work, classroom, resources, other
            'description' => $this->text(),
            'sequence' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createIndex('idx_tp_supporting_image_assessment', '{{%tp_supporting_image}}', 'assessment_id');

        $this->addForeignKey(
            'fk_tp_supporting_image_assessment',
            '{{%tp_supporting_image}}',
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
        $this->dropForeignKey('fk_tp_supporting_image_assessment', '{{%tp_supporting_image}}');
        $this->dropTable('{{%tp_supporting_image}}');
    }
}
