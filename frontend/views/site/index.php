<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\bootstrap5\Nav;

$this->title = 'Teaching Practice Assessment System';
?>
<div class="site-index">
    <div class="p-5 mb-4 bg-gradient rounded-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container-fluid py-5 text-center text-white">
            <h1 class="display-4">Teaching Practice Assessment System</h1>
            <p class="fs-5 fw-light">Digital Assessment Platform for Teacher Trainees and Supervisors</p>
            <?php if (Yii::$app->user->isGuest): ?>
                <p>
                    <?= Html::a('Login', ['/site/login'], ['class' => 'btn btn-light btn-lg me-2']) ?>
                    <?= Html::a('Register', ['/site/signup'], ['class' => 'btn btn-outline-light btn-lg']) ?>
                </p>
            <?php else: ?>
                <p class="fs-6">Welcome back, <?= Html::encode(Yii::$app->user->identity->username) ?>!</p>
                <p>
                    <?= Html::a('Create Assessment', ['/assessment/create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('My Assessments', ['/assessment/index'], ['class' => 'btn btn-info']) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="body-content">
        <div class="px-3">
            <h2>Supervisor Account Provisioning and System Access</h2>
            <ol>
                <li><strong>Account provisioning by administrator.</strong> Supervisor accounts are provisioned by the system administrator rather than through self-registration. The administrator links your existing payroll number to your user account and issues a temporary password for initial access.</li>
                <li><strong>Authentication using payroll number.</strong> Access the portal and log in using the assigned payroll number as credentials. Password resets are managed by the administrator.</li>
                <li><strong>Create or edit assessments.</strong> Use the <em>Assessments</em> menu to add new entries or modify drafts. Select or type a student, enter scores, remarks, and upload up to five supporting images.</li>
                <li><strong>Submit for review.</strong> Submissions go to the zone coordinator for validation. Drafts remain editable until validated.</li>
                <li><strong>Track status.</strong> Notifications are emailed to you and the student; students do not log in and receive updates passively.</li>
            </ol>
            <p class="mt-3"><strong>Note:</strong> Students are passive users. They do not register or log in; their profiles are added when assessments are entered, and they simply receive email notifications.</p>
            <p class="mt-3">Coordinators and TP office staff should use the <a href="http://localhost:3000" target="_blank">backend system</a> for review, validation, and report generation.</p>
        </div>
    </div>
</div>

<style>
.fa { margin-right: 8px; }
</style>

