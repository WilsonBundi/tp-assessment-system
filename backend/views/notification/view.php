<?php

use yii\helpers\Html;

$this->title = $notification->title;
$this->params['breadcrumbs'][] = ['label' => 'Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="notification-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-<?= $notification->is_read ? 'default' : 'info' ?>">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= $notification->title ?>
                <span class="label <?= $notification->is_read ? 'label-default' : 'label-primary' ?>" style="float: right;">
                    <?= $notification->is_read ? 'Read' : 'Unread' ?>
                </span>
            </h3>
        </div>
        <div class="panel-body">
            <p><?= nl2br(Html::encode($notification->message)) ?></p>

            <hr/>

            <p>
                <strong>Type:</strong> <?= \common\models\TpNotification::getTypeLabel($notification->notification_type) ?><br/>
                <strong>Received:</strong> <?= Yii::$app->formatter->asDateTime($notification->created_at) ?><br/>
                <?php if ($notification->read_at): ?>
                    <strong>Read:</strong> <?= Yii::$app->formatter->asDateTime($notification->read_at) ?><br/>
                <?php endif; ?>
            </p>

            <?php if ($notification->assessment): ?>
            <div style="background: #f9f9f9; padding: 15px; margin-top: 15px; border-radius: 5px;">
                <strong>Related Assessment:</strong><br/>
                Student: <?= $notification->assessment->student->full_name ?><br/>
                Status: <span class="label label-info"><?= \common\models\TpAssessment::getStatusLabel($notification->assessment->status) ?></span><br/>
                <?= Html::a('View Assessment', ['assessment/view', 'id' => $notification->assessment->id], ['class' => 'btn btn-sm btn-primary', 'style' => 'margin-top: 10px;']) ?>
            </div>
            <?php endif; ?>

            <div style="margin-top: 20px;">
                <?= Html::a('Back to Notifications', ['index'], ['class' => 'btn btn-default']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $notification->id], ['class' => 'btn btn-danger', 'data' => ['confirm' => 'Delete this notification?']]) ?>
            </div>
        </div>
    </div>
</div>
