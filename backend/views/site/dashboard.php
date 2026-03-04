<?php

use yii\helpers\Html;
use common\models\TpAssessment;
use common\models\TpNotification;

$this->title = 'TP Assessment Dashboard';

// Get counts for dashboard
$totalAssessments = TpAssessment::find()->count();
$draftAssessments = TpAssessment::find()->where(['status' => TpAssessment::STATUS_DRAFT])->count();
$submittedAssessments = TpAssessment::find()->where(['status' => TpAssessment::STATUS_SUBMITTED])->count();
$validatedAssessments = TpAssessment::find()->where(['status' => TpAssessment::STATUS_VALIDATED])->count();
$unreadNotifications = TpNotification::find()->where(['user_id' => \Yii::$app->user->id, 'is_read' => false])->count();
?>

<style>
  .dashboard {
    padding: 0;
  }

  .dashboard-header {
    background: linear-gradient(135deg, #2C5282 0%, #3B82F6 100%);
    color: white;
    padding: 50px 30px;
    margin: 0 -30px 30px -30px;
    border-radius: 0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
  }
  
  .dashboard-header h1 {
    font-size: 2.2rem;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.5px;
  }
  
  .dashboard-header p {
    font-size: 1rem;
    opacity: 0.9;
    margin-top: 8px;
    font-weight: 300;
  }

  /* Stats Row Styling */
  .row {
    margin-bottom: 30px;
  }

  .stat-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    border-left: 5px solid #3498DB;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-top: none;
  }
  
  .stat-card:hover {
    box-shadow: 0 8px 24px rgba(52, 152, 219, 0.12);
    transform: translateY(-3px);
  }
  
  .stat-card.total {
    border-left-color: #3B82F6;
  }
  
  .stat-card.draft {
    border-left-color: #F59E0B;
  }
  
  .stat-card.submitted {
    border-left-color: #8B5CF6;
  }
  
  .stat-card.validated {
    border-left-color: #10B981;
  }

  .stat-card h5 {
    color: #6B7280;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin: 0 0 15px 0;
  }
  
  .stat-card .stat-number {
    font-size: 2.8rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 5px;
  }
  
  .stat-card .stat-description {
    font-size: 0.9rem;
    color: #9CA3AF;
    margin-top: 5px;
  }
  
  .section-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  }
  
  .section-card h4 {
    color: #1F2937;
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0 0 25px 0;
    padding-bottom: 0;
    border-bottom: none;
  }
  
  .notification-box {
    background: linear-gradient(135deg, #EFF6FF 0%, #F0F9FF 100%);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    border-left: 4px solid #3B82F6;
    border-radius: 8px;
  }
  
  .notification-box.unread {
    border-left-color: #EF4444;
    background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
  }
  
  .quick-action-btn {
    display: block;
    width: 100%;
    padding: 14px 16px;
    margin-bottom: 10px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    text-align: left;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .quick-action-btn.primary {
    background-color: #3B82F6;
    color: white;
  }
  
  .quick-action-btn.primary:hover {
    background-color: #2563EB;
  }
  
  .quick-action-btn.secondary {
    background-color: #F3F4F6;
    color: #1F2937;
    border: 1px solid #E5E7EB;
  }
  
  .quick-action-btn.secondary:hover {
    background-color: #E5E7EB;
  }
  
  .features-box {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    margin: 30px 0 0 0;
    border-left: 5px solid #3B82F6;
  }
  
  .features-box h4 {
    color: #1F2937;
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0 0 20px 0;
  }
  
  .features-box ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .features-box li {
    padding: 12px 0 12px 30px;
    color: #374151;
    border-bottom: 1px solid #E5E7EB;
    position: relative;
    font-size: 0.95rem;
    line-height: 1.5;
  }
  
  .features-box li:last-child {
    border-bottom: none;
  }
  
  .features-box li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #10B981;
    font-weight: bold;
    font-size: 1rem;
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
                <h5>Total Assessments</h5>
                <div class="stat-number"><?= $totalAssessments ?></div>
                <div class="stat-description">All assessments in system</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card draft">
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
                <h5>Submitted Assessments</h5>
                <div class="stat-number"><?= $submittedAssessments ?></div>
                <div class="stat-description">Pending validation</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card validated">
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
                        <p style="margin: 0; color: #27AE60; font-weight: 600;">All notifications read. You're all caught up!</p>
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
        <h4>TP Assessment System Features</h4>
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
