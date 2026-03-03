<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tp_student}}`.
 */
class m260303_120000_create_tp_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tp_student}}', [
            'id' => $this->primaryKey(),
            'registration_number' => $this->string(50)->notNull()->unique(),
            'full_name' => $this->string(255)->notNull(),
            'school' => $this->string(100),
            'zone' => $this->string(50),
            'class_form' => $this->string(50),
            'learning_area_subject' => $this->string(100),
            'pathway' => $this->string(50), // CBC/Other
            'email' => $this->string(255),
            'phone' => $this->string(20),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createIndex('idx_tp_student_registration_number', '{{%tp_student}}', 'registration_number');
        $this->createIndex('idx_tp_student_zone', '{{%tp_student}}', 'zone');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tp_student}}');
    }
}
