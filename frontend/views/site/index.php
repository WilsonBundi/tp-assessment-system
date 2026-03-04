<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\bootstrap5\Nav;

$this->title = 'Teaching Practice Assessment System';
?>
<div class="site-index">
    <!-- Hero Section -->
    <div class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 80px 20px; text-align: center; color: white;">
        <div class="container">
            <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 15px;">Teaching Practice Assessment System</h1>
            <p style="font-size: 1.3rem; font-weight: 300; margin-bottom: 40px;">Professional Assessment Platform for Teacher Supervisors</p>
            
            <?php if (Yii::$app->user->isGuest): ?>
                <div>
                    <?= Html::a('Login to Assessment Portal', ['/site/login'], ['class' => 'btn btn-light btn-lg', 'style' => 'padding: 15px 40px; font-size: 1.1rem; font-weight: 600;']) ?>
                </div>
            <?php else: ?>
                <div>
                    <p style="font-size: 1.1rem; margin-bottom: 25px;">Welcome, <?= Html::encode(Yii::$app->user->identity->username) ?>!</p>
                    <p>
                        <?= Html::a('Create Assessment', ['/assessment/create'], ['class' => 'btn btn-success btn-lg me-2', 'style' => 'padding: 12px 35px;']) ?>
                        <?= Html::a('My Assessments', ['/assessment/index'], ['class' => 'btn btn-info btn-lg', 'style' => 'padding: 12px 35px;']) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Instructions Section (visible only to guests) -->
    <?php if (Yii::$app->user->isGuest): ?>
    <div class="body-content" style="background-color: #f8f9fa; padding: 60px 20px;">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto;">
                <h2 style="text-align: center; margin-bottom: 40px; font-weight: 700; color: #333;">How to Get Started</h2>
                
                <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <ol style="line-height: 1.8; font-size: 1.05rem;">
                        <li style="margin-bottom: 20px;">
                            <strong style="color: #667eea;">Account Provisioning</strong><br>
                            <span style="color: #666;">Your supervisor account is provisioned by the system administrator. You do not self-register. The administrator will link your existing payroll number to your user account and provide you with a temporary password for initial access.</span>
                        </li>
                        <li style="margin-bottom: 20px;">
                            <strong style="color: #667eea;">Authentication</strong><br>
                            <span style="color: #666;">Log in using your assigned payroll number as the username. Password resets are managed by the administrator.</span>
                        </li>
                        <li style="margin-bottom: 20px;">
                            <strong style="color: #667eea;">Create Assessments</strong><br>
                            <span style="color: #666;">Once logged in, use the Assessments menu to create new entries or modify drafts. Select or type a student, enter assessment date, score each competence area (0–10), add remarks, and upload up to five supporting images.</span>
                        </li>
                        <li style="margin-bottom: 20px;">
                            <strong style="color: #667eea;">Submit for Review</strong><br>
                            <span style="color: #666;">Submit your assessment to the zone coordinator for validation. Your drafts remain editable until the coordinator validates them.</span>
                        </li>
                        <li>
                            <strong style="color: #667eea;">Track Status</strong><br>
                            <span style="color: #666;">Receive email notifications when your submissions are reviewed or validated. Students are passive users—they receive email notifications and do not log in to the system.</span>
                        </li>
                    </ol>
                </div>

                <!-- Note Section -->
                <div style="background-color: #e7f3ff; border-left: 4px solid #667eea; padding: 20px; border-radius: 5px; margin-top: 30px;">
                    <p style="margin-bottom: 10px; color: #333;">
                        <strong>📌 Important Note:</strong>
                    </p>
                    <p style="color: #555; margin: 0;">
                        Students are passive users. They are not required to register or log in. Their profiles are automatically created when you enter assessments, and they receive notifications via email.
                    </p>
                </div>

                <!-- Coordinator Link -->
                <div style="background-color: #f0f0f0; padding: 20px; border-radius: 5px; margin-top: 20px; text-align: center;">
                    <p style="color: #555; margin: 0;">
                        <strong>Coordinators and TP office staff:</strong> Use the 
                        <?= Html::a('backend system', 'http://localhost:3000', ['target' => '_blank', 'style' => 'color: #667eea; text-decoration: none; font-weight: 600;']) ?>
                        for assessment review, validation, and report generation.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    .site-index {
        background-color: #ffffff;
    }
    
    .btn-light {
        transition: all 0.3s ease;
    }
    
    .btn-light:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>

