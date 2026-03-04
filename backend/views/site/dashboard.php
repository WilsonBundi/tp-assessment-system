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

<style>
  .dashboard-header {
    background: linear-gradient(135deg, #3498DB 0%, #5DADE2 100%);
    color: white;
    padding: 40px 0;
    margin: -30px -30px 30px -30px;
    border-radius: 0;
  }
  
  .dashboard-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
  }
  
  .dashboard-header p {
    font-size: 1.1rem;
    opacity: 0.95;
    margin-top: 10px;
  }
  
  .stat-card {
    background: white;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-top: 4px solid #3498DB;
    transition: all 0.3s ease;
  }
  
  .stat-card:hover {
    box-shadow: 0 4px 16px rgba(52, 152, 219, 0.2);
    transform: translateY(-2px);
  }
  
  .stat-card.total {
    border-top-color: #3498DB;
  }
  
  .stat-card.draft {
    border-top-color: #F39C12;
  }
  
  .stat-card.submitted {
    border-top-color: #2980B9;
  }
  
  .stat-card.validated {
    border-top-color: #27AE60;
  }
  
  .stat-card-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
  }
  
  .stat-card h5 {
    color: #666;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0 0 10px 0;
  }
  
  .stat-card .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2C3E50;
  }
  
  .stat-card .stat-description {
    font-size: 0.85rem;
    color: #999;
    margin-top: 10px;
  }
  
  .section-card {
    background: white;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  
  .section-card h4 {
    color: #2C3E50;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #3498DB;
  }
  
  .notification-box {
    background: linear-gradient(135deg, #E8F4F8 0%, #F0F8FF 100%);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    border-left: 4px solid #3498DB;
  }
  
  .notification-box.unread {
    border-left-color: #E74C3C;
  }
  
  .quick-action-btn {
    display: block;
    width: 100%;
    padding: 15px;
    margin-bottom: 12px;
    border: none;
    border-radius: 5px;
    font-weight: 600;
    text-align: left;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .quick-action-btn.primary {
    background-color: #3498DB;
    color: white;
  }
  
  .quick-action-btn.primary:hover {
    background-color: #2980B9;
    transform: translateX(5px);
  }
  
  .quick-action-btn.secondary {
    background-color: #ECF0F1;
    color: #2C3E50;
  }
  
  .quick-action-btn.secondary:hover {
    background-color: #BDC3C7;
    transform: translateX(5px);
  }
  
  .features-box {
    background: linear-gradient(135deg, #F0F8FF 0%, #E8F4F8 100%);
    border-radius: 8px;
    padding: 30px;
    border-left: 5px solid #3498DB;
    margin-top: 30px;
  }
  
  .features-box h4 {
    color: #2874A6;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 20px;
  }
  
  .features-box ul {
    list-style: none;
    padding: 0;
  }
  
  .features-box li {
    padding: 12px 0;
    color: #34495E;
    border-bottom: 1px solid rgba(52, 73, 94, 0.1);
    padding-left: 30px;
    position: relative;
  }
  
  .features-box li:last-child {
    border-bottom: none;
  }
  
  .features-box li:before {
    content: "\2713";
    position: absolute;
    left: 0;
    color: #27AE60;
    font-weight: bold;
    font-size: 1.1rem;
  }
</style>

<div class="dashboard">
    <div class="dashboard-header">
        <h1>TP Assessment Dashboard</h1>
        <p>Monitor and manage teaching practice assessments in real-time</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card total">
                <div class="stat-card-icon"></div>
                <h5>Total Assessments</h5>
                <div class="stat-number"><?= $totalAssessments ?></div>
                <div class="stat-description">All assessments in system</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card draft">
                <div class="stat-card-icon"></div>
                <h5>Draft Assessments</h5>
                <div class="stat-number"><?= $draftAssessments ?></div>
                <div class="stat-description">Awaiting completion</div>
                <?php if ($draftAssessments > 0): ?>
                    <?= Html::a('View & Edit', ['assessment/index'], ['class' => 'btn btn-sm btn-warning', 'style' => 'margin-top: 10px;']) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card submitted">
                <div class="stat-card-icon"></div>
                <h5>Submitted Assessments</h5>
                <div class="stat-number"><?= $submittedAssessments ?></div>
                <div class="stat-description">Pending validation</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card validated">
                <div class="stat-card-icon"></div>
                <h5>Validated Assessments</h5>
                <div class="stat-number"><?= $validatedAssessments ?></div>
                <div class="stat-description">Completed assessments</div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="section-card">
                <h4>Notifications</h4>
                <?php if ($unreadNotifications > 0): ?>
                    <div class="notification-box unread">
                        <p style="margin: 0; font-weight: 600;">
                            You have <strong><?= $unreadNotifications ?></strong> unread notification<?= $unreadNotifications != 1 ? 's' : '' ?>
                        </p>
                        <?= Html::a('View All Notifications →', ['notification/index'], ['class' => 'btn btn-sm btn-info', 'style' => 'margin-top: 10px;']) ?>
                    </div>
                <?php else: ?>
                    <div class="notification-box">
                        <p style="margin: 0; color: #27AE60; font-weight: 600;">✓ All notifications read. You're all caught up!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="section-card">
                <h4>Quick Actions</h4>
                <a href="<?= \yii\helpers\Url::to(['assessment/create']) ?>" class="quick-action-btn primary">+ Create New Assessment</a>
                <a href="<?= \yii\helpers\Url::to(['assessment/index']) ?>" class="quick-action-btn secondary">View All Assessments</a>
                <a href="<?= \yii\helpers\Url::to(['report/index']) ?>" class="quick-action-btn secondary">Generate Reports</a>
                <a href="<?= \yii\helpers\Url::to(['notification/index']) ?>" class="quick-action-btn secondary">Manage Notifications</a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-box">
        <h4>✨ TP Assessment System Features</h4>
        <ul>
            <li>Digital assessment entry with 12 competence areas</li>
            <li>Automatic score calculation and performance level determination</li>
            <li>Generate two distinct reports: Student Copy (without marks) and Office Copy (with marks)</li>
            <li>Role-based access control for Supervisors, Zone Coordinators, TP Office, and Department Chairs</li>
            <li>Real-time notifications and audit logging</li>
            <li>Supporting evidence upload (up to 5 images per assessment)</li>
            <li>Assessment validation workflow with approval chain</li>
        </ul>
    </div>
</div>
