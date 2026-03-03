<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'My Assessments';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="assessment-my-assessments">
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
                'width' => '80px',
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
                    return $model->total_score ? $model->total_score . '/120' : 'N/A';
                },
                'width' => '80px',
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
                'format' => 'html',
                'width' => '100px',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i> View', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-info']);
                    },
                    'update' => function($url, $model) {
                        if ($model->status === 'draft' || $model->status === 'submitted') {
                            return Html::a('<i class="glyphicon glyphicon-pencil"></i> Edit', ['edit', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']);
                        }
                        return '';
                    },
                    'delete' => function($url, $model) {
                        return '';
                    }
                ]
            ],
        ],
    ]) ?>
</div>
