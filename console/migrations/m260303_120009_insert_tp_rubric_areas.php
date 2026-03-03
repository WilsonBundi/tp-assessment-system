<?php

use yii\db\Migration;

/**
 * Inserts default rubric areas for TP Assessment System.
 */
class m260303_120009_insert_tp_rubric_areas extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(
            '{{%tp_rubric_area}}',
            ['area_code', 'area_name', 'description', 'max_score', 'sequence'],
            [
                ['RA1', 'Professional Records', 'Maintains accurate and organized professional documentation and learner records', 10, 1],
                ['RA2', 'Lesson Planning', 'Develops comprehensive and well-structured lesson plans aligned with curriculum standards', 10, 2],
                ['RA3', 'Introduction', 'Effectively introduces lessons with clear learning objectives and engagement strategies', 10, 3],
                ['RA4', 'Content Knowledge', 'Demonstrates thorough understanding of subject matter and communicates it accurately', 10, 4],
                ['RA5', 'Pedagogical Strategies', 'Employs varied and effective teaching methods suited to learner needs and abilities', 10, 5],
                ['RA6', 'Instructional Resources', 'Selects and utilizes appropriate teaching and learning materials effectively', 10, 6],
                ['RA7', 'Assessment', 'Implements formative and summative assessment strategies to monitor learner progress', 10, 7],
                ['RA8', 'Classroom Management', 'Maintains a positive, orderly classroom environment conducive to learning', 10, 8],
                ['RA9', 'Closure', 'Effectively concludes lessons with summary, reflection, and reinforcement of key concepts', 10, 9],
                ['RA10', 'Professionalism', 'Demonstrates professional conduct, ethics, and commitment to continuous improvement', 10, 10],
                ['RA11', 'Learner Engagement', 'Promotes active participation and creates opportunities for all learners to engage', 10, 11],
                ['RA12', 'Inclusivity and Differentiation', 'Addresses diverse learner needs and provides differentiated instruction', 10, 12],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%tp_rubric_area}}');
    }
}
