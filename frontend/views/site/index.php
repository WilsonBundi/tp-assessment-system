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
            <?php endif; ?>
        </div>
    </div>

    <div class="body-content">
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fa fa-clipboard-list fa-3x text-primary mb-3"></i>
                        <h2 class="card-title">12 Competence Areas</h2>
                        <p class="card-text">Assessment covers professional records, lesson planning, content knowledge, pedagogical strategies, classroom management, and more.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fa fa-chart-bar fa-3x text-success mb-3"></i>
                        <h2 class="card-title">Automatic Scoring</h2>
                        <p class="card-text">Scores are automatically calculated and mapped to performance levels: Below Expectations, Approaching, Meets, or Exceeds.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fa fa-file-pdf fa-3x text-danger mb-3"></i>
                        <h2 class="card-title">Dual Reports</h2>
                        <p class="card-text">Generate two report versions: Student Copy (without marks) and Office Copy (with detailed scores).</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fa fa-image fa-3x text-info mb-3"></i>
                        <h2 class="card-title">Evidence Upload</h2>
                        <p class="card-text">Submit up to 5 supporting images as evidence (lesson plans, classroom photos, student work samples).</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fa fa-check-circle fa-3x text-warning mb-3"></i>
                        <h2 class="card-title">Validation Workflow</h2>
                        <p class="card-text">Assessments go through a structured workflow: Draft → Submitted → Reviewed → Validated → Final.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fa fa-bell fa-3x text-danger mb-3"></i>
                        <h2 class="card-title">Real-time Notifications</h2>
                        <p class="card-text">Stay updated with real-time notifications on assessment submissions, reviews, and approvals.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">Getting Started</h4>
            <p>If you're a <strong>Student or Lecturer</strong>, please <?= Html::a('login', ['/site/login']) ?> or <?= Html::a('register', ['/site/signup']) ?> to access the assessment portal.</p>
            <hr>
            <p class="mb-0">For <strong>supervisors and coordinators</strong>, access the <?= Html::a('backend system', 'http://localhost:3000', ['target' => '_blank']) ?> to create and review assessments.</p>
        </div>
    </div>
</div>

<style>
.fa { margin-right: 8px; }
</style>

