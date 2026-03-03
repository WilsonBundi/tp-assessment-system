<?php

use yii\helpers\Html;
use common\models\TpAssessment;
use common\models\TpNotification;

$this->title = 'TP Assessment Dashboard';
$this->params['breadcrumbs'][] = $this->title;

// Get counts for dashboard
$totalAssessments = TpAssessment::find()->count();
$draftAssessments = TpAssessment::find()->where(['status' => TpAssessment::STATUS_DRAFT])->count();
$submittedAssessments = TpAssessment::find()->where(['status' => TpAssessment::STATUS_SUBMITTED])->count();
$validatedAssessments = TpAssessment::find()->where(['status' => TpAssessment::STATUS_VALIDATED])->count();
$unreadNotifications = TpNotification::find()->where(['user_id' => \Yii::$app->user->id, 'is_read' => false])->count();
?>

<div class="dashboard">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading" style="background-color: #0066cc;">
                    <h3 class="panel-title">Total Assessments</h3>
                </div>
                <div class="panel-body" style="text-align: center;">
                    <h2 style="color: #0066cc; margin: 20px 0;"><?= $totalAssessments ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-warning">
                <div class="panel-heading" style="background-color: #f0ad4e;">
                    <h3 class="panel-title">Draft Assessments</h3>
                </div>
                <div class="panel-body" style="text-align: center;">
                    <h2 style="color: #f0ad4e; margin: 20px 0;"><?= $draftAssessments ?></h2>
                    <?= Html::a('View', ['assessment/index'], ['class' => 'btn btn-sm btn-warning']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading" style="background-color: #5bc0de;">
                    <h3 class="panel-title">Submitted Assessments</h3>
                </div>
                <div class="panel-body" style="text-align: center;">
                    <h2 style="color: #5bc0de; margin: 20px 0;"><?= $submittedAssessments ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-success">
                <div class="panel-heading" style="background-color: #5cb85c;">
                    <h3 class="panel-title">Validated Assessments</h3>
                </div>
                <div class="panel-body" style="text-align: center;">
                    <h2 style="color: #5cb85c; margin: 20px 0;"><?= $validatedAssessments ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Notifications
                        <?php if ($unreadNotifications > 0): ?>
                            <span class="badge" style="background-color: #d9534f;"><?= $unreadNotifications ?></span>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <?php if ($unreadNotifications > 0): ?>
                        <p>You have <strong><?= $unreadNotifications ?></strong> unread notification<?= $unreadNotifications != 1 ? 's' : '' ?>.</p>
                        <?= Html::a('View All Notifications', ['notification/index'], ['class' => 'btn btn-info']) ?>
                    <?php else: ?>
                        <p>No unread notifications.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Quick Actions</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Create New Assessment', ['assessment/create'], ['class' => 'list-group-item']) ?>
                        <?= Html::a('<i class="glyphicon glyphicon-list"></i> View All Assessments', ['assessment/index'], ['class' => 'list-group-item']) ?>
                        <?= Html::a('<i class="glyphicon glyphicon-file"></i> View Reports', ['report/index'], ['class' => 'list-group-item']) ?>
                        <?= Html::a('<i class="glyphicon glyphicon-bell"></i> View Notifications', ['notification/index'], ['class' => 'list-group-item']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="info-box" style="background: #ecf0f1; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #0066cc;">
                <h4>TP Assessment System Features</h4>
                <ul>
                    <li>Digital assessment entry with 12 competence areas</li>
                    <li>Automatic score calculation and performance level determination</li>
                    <li>Generate two distinct reports: Student Copy (without marks) and Office Copy (with marks)</li>
                    <li>Role-based access control for Supervisors, Zone Coordinators, TP Office, and Department Chairs</li>
                    <li>Real-time notifications and audit logging</li>
                    <li>Supporting evidence upload (up to 5 images per assessment)</li>
                    <li>Assessment validation workflow</li>
                </ul>
            </div>
        </div>
    </div>
</div>
