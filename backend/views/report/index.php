<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Assessment Reports';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>All generated assessment reports are listed below.</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'label' => 'Report ID',
                'width' => '80px',
            ],
            [
                'attribute' => 'assessment_id',
                'label' => 'Assessment ID',
                'width' => '100px',
            ],
            [
                'attribute' => 'report_type',
                'label' => 'Report Type',
                'value' => function($model) {
                    return $model->getReportTypeLabel($model->report_type);
                },
                'width' => '120px',
            ],
            [
                'attribute' => 'report_file',
                'label' => 'File',
            ],
            [
                'attribute' => 'generated_at',
                'label' => 'Generated',
                'format' => 'dateTime',
                'width' => '150px',
            ],
            [
                'attribute' => 'downloaded_at',
                'label' => 'Last Downloaded',
                'value' => function($model) {
                    return $model->downloaded_at ? Yii::$app->formatter->asDateTime($model->downloaded_at) : '-';
                },
                'width' => '150px',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'width' => '200px',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-info']);
                    },
                    'download' => function($url, $model) {
                        return Html::a('Download', ['download', 'id' => $model->id], ['class' => 'btn btn-sm btn-success']);
                    },
                    'update' => function($url, $model) {
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
