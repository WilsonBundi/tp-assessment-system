<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tp_rubric_area}}`.
 */
class m260303_120002_create_tp_rubric_area_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tp_rubric_area}}', [
            'id' => $this->primaryKey(),
            'area_code' => $this->string(20)->notNull()->unique(),
            'area_name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'max_score' => $this->integer()->defaultValue(10),
            'sequence' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tp_rubric_area}}');
    }
}
