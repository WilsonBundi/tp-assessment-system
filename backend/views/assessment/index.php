<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Teaching Practice Assessments';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .assessment-index {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .assessment-index h1 {
        color: #2C3E50;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 25px;
        border-bottom: 3px solid #3498DB;
        padding-bottom: 15px;
    }
    
    .create-btn-container {
        margin-bottom: 25px;
    }
    
    .create-btn-container .btn-success {
        background-color: #27AE60;
        border-color: #27AE60;
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    
    .create-btn-container .btn-success:hover {
        background-color: #229954;
        border-color: #229954;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    }
    
    .grid-view {
        margin-top: 20px;
    }
    
    .grid-view table {
        border-collapse: collapse;
    }
    
    .grid-view th {
        background-color: #3498DB;
        color: white;
        border-top: 2px solid #2980B9;
        font-weight: 600;
        padding: 15px !important;
    }
    
    .grid-view td {
        padding: 12px 15px !important;
        border-bottom: 1px solid #ECF0F1;
    }
    
    .grid-view tbody tr:hover {
        background-color: #E8F4F8;
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-badge.draft {
        background-color: #FCF3CF;
        color: #856404;
    }
    
    .status-badge.submitted {
        background-color: #D1ECF1;
        color: #0C5460;
    }
    
    .status-badge.reviewed {
        background-color: #CFFBF6;
        color: #0F5448;
    }
    
    .status-badge.validated {
        background-color: #D4EDDA;
        color: #155724;
    }
    
    .status-badge.rejected {
        background-color: #F8D7DA;
        color: #721C24;
    }
    
    .grid-view .btn-sm {
        padding: 6px 12px;
        margin-right: 5px;
        border-radius: 4px;
    }
</style>

<div class="assessment-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="create-btn-container">
        <?= Html::a('✨ Create New Assessment', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'label' => 'ID',
                'headerOptions' => ['style' => 'width: 60px;'],
            ],
            [
                'attribute' => 'student_id',
                'label' => 'Student',
                'value' => function($model) {
                    return Html::encode($model->student->full_name) . '<br><small style="color: #999;">' . Html::encode($model->student->registration_number) . '</small>';
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'supervisor_id',
                'label' => 'Supervisor',
                'value' => function($model) {
                    return Html::encode($model->supervisor->name ?? 'N/A');
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
                }
            ],
            [
                'attribute' => 'status',
                'label' => 'Status',
                'value' => function($model) {
                    $statusLabels = [
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'reviewed' => 'Reviewed',
                        'validated' => 'Validated',
                        'rejected' => 'Rejected',
                    ];
                    $label = $statusLabels[$model->status] ?? ucfirst($model->status);
                    return '<span class="status-badge ' . $model->status . '">' . $label . '</span>';
                },
                'format' => 'html'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 150px;'],
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a('👁️ View', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-info']);
                    },
                    'update' => function($url, $model) {
                        if ($model->status === 'draft' || $model->status === 'submitted') {
                            return Html::a('Edit', ['edit', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']);
                        }
                        return '';
                    },
                    'delete' => function($url, $model) {
                        return '';
                    }
                ],
                'template' => '{view} {update}'
            ],
        ],
    ]) ?>
</div>
