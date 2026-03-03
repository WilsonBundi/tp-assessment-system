<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'My Teaching Practice Assessments';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="assessment-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div style="margin-bottom: 20px;">
        <?= Html::a('Create New Assessment', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'label' => 'Assessment ID',
            ],
            [
                'attribute' => 'student_id',
                'label' => 'Student',
                'value' => function($model) {
                    return $model->student->full_name . ' (' . $model->student->registration_number . ')';
                }
            ],
            [
                'attribute' => 'assessment_date',
                'label' => 'Assessment Date',
                'format' => 'date'
            ],
            [
                'attribute' => 'total_score',
                'label' => 'Score',
                'value' => function($model) {
                    return $model->total_score . '/120';
                }
            ],
            [
                'attribute' => 'status',
                'label' => 'Status',
                'value' => function($model) {
                    $statusClass = [
                        'draft' => 'label-warning',
                        'submitted' => 'label-info',
                        'reviewed' => 'label-primary',
                        'validated' => 'label-success',
                        'rejected' => 'label-danger',
                    ];
                    $class = $statusClass[$model->status] ?? 'label-default';
                    return '<span class="label ' . $class . '">' . $model->getStatusLabel($model->status) . '</span>';
                },
                'format' => 'html'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-info']);
                    },
                    'update' => function($url, $model) {
                        if ($model->status === 'draft' || $model->status === 'submitted') {
                            return Html::a('Edit', ['edit', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']);
                        }
                        return '';
                    }
                ]
            ],
        ],
    ]) ?>
</div>