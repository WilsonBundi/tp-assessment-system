<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'My Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="notification-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'label' => 'Notification',
                'value' => function($model) {
                    $label = $model->is_read ? 'label-default' : 'label-primary';
                    return '<span class="label ' . $label . '">' . Html::encode($model->title) . '</span>';
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'message',
                'label' => 'Message',
            ],
            [
                'attribute' => 'notification_type',
                'label' => 'Type',
                'value' => function($model) {
                    return \common\models\TpNotification::getTypeLabel($model->notification_type);
                },
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Received',
                'format' => 'dateTime'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-info']);
                    },
                    'update' => function($url, $model) {
                        return '';
                    },
                    'delete' => function($url, $model) {
                        return Html::a('Delete', ['delete', 'id' => $model->id], ['class' => 'btn btn-sm btn-danger', 'data' => ['confirm' => 'Delete notification?']]);
                    }
                ]
            ],
        ],
    ]) ?>
</div>
