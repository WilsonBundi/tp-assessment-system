<?php

use yii\helpers\Html;
use common\models\TpRubricArea;
use common\models\TpAssessment;

$this->title = 'Assessment Details - ' . $assessment->student->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Assessments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Helper function for attainment level color
function getAttainmentClass($level) {
    $map = ['BE' => 'danger', 'AE' => 'warning', 'ME' => 'success', 'EE' => 'info'];
    return $map[$level] ?? 'default';
}

// Helper function for performance color
function getPerformanceClass($level) {
    $map = ['BE' => 'danger', 'AE' => 'warning', 'ME' => 'success', 'EE' => 'info'];
    return $map[$level] ?? 'default';
}

// Helper function for status color
function getStatusClass($status) {
    $map = ['draft' => 'warning', 'submitted' => 'info', 'reviewed' => 'primary', 'validated' => 'success', 'rejected' => 'danger'];
    return $map[$status] ?? 'default';
}
?>

<div class="assessment-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <!-- Student Information -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Student Information</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th width="40%">Registration Number</th>
                            <td><?= $assessment->student->registration_number ?></td>
                        </tr>
                        <tr>
                            <th>Full Name</th>
                            <td><?= $assessment->student->full_name ?></td>
                        </tr>
                        <tr>
                            <th>School</th>
                            <td><?= $assessment->student->school ?></td>
                        </tr>
                        <tr>
                            <th>Zone</th>
                            <td><?= $assessment->student->zone ?></td>
                        </tr>
                        <tr>
                            <th>Subject/Learning Area</th>
                            <td><?= $assessment->student->learning_area_subject ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Assessment Scores -->
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Assessment Scores</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Competence Area</th>
                                <th width="12%">Score</th>
                                <th width="18%">Attainment</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scores as $score): ?>
                                <tr>
                                    <td><strong><?= $score->rubricArea->area_name ?></strong></td>
                                    <td><?= $score->score ?>/10</td>
                                    <td>
                                        <span class="label label-<?= $this->getAttainmentClass($score->attainment_level) ?>">
                                            <?= TpRubricArea::getAttainmentLevelLabel($score->attainment_level) ?>
                                        </span>
                                    </td>
                                    <td><?= $score->remarks ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div style="background: #f9f9f9; padding: 15px; margin-top: 15px; border-radius: 5px;">
                        <strong>Total Score:</strong> <?= $assessment->total_score ?>/120<br/>
                        <strong>Overall Performance:</strong> 
                        <span class="label label-<?= $this->getPerformanceClass($assessment->overall_performance) ?>" style="font-size: 14px;">
                            <?= TpAssessment::getPerformanceLabel($assessment->overall_performance) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Supervisor Remarks -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Supervisor Remarks</h3>
                </div>
                <div class="panel-body">
                    <?= nl2br(Html::encode($assessment->supervisor_remarks)) ?>
                </div>
            </div>

            <!-- Supporting Images -->
            <?php if (!empty($images)): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Supporting Evidence (<?= count($images) ?> image<?= count($images) != 1 ? 's' : '' ?>)</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php foreach ($images as $image): ?>
                        <div class="col-md-4" style="margin-bottom: 15px;">
                            <a href="<?= Yii::$app->request->baseUrl ?>/uploads/assessments/<?= $image->image_file ?>" target="_blank">
                                <img src="<?= Yii::$app->request->baseUrl ?>/uploads/assessments/<?= $image->image_file ?>" 
                                     style="max-width: 100%; border-radius: 5px; border: 1px solid #ddd;">
                            </a>
                            <p style="font-size: 12px; text-align: center; margin-top: 5px;">
                                <?= $image->description ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Assessment Status</h3>
                </div>
                <div class="panel-body">
                    <p>
                        <strong>Status:</strong><br/>
                        <span class="label label-<?= $this->getStatusClass($assessment->status) ?>" style="font-size: 14px;">
                            <?= TpAssessment::getStatusLabel($assessment->status) ?>
                        </span>
                    </p>

                    <p>
                        <strong>Assessment Date:</strong><br/>
                        <?= Yii::$app->formatter->asDate($assessment->assessment_date) ?>
                    </p>

                    <p>
                        <strong>Supervisor:</strong><br/>
                        <?= $assessment->supervisor->name ?> (<?= $assessment->supervisor->tp_assigned_code ?>)
                    </p>

                    <p>
                        <strong>Submitted:</strong><br/>
                        <?= $assessment->submitted_at ? Yii::$app->formatter->asDateTime($assessment->submitted_at) : 'Not yet submitted' ?>
                    </p>

                    <?php if ($assessment->is_validated): ?>
                    <p>
                        <strong>Validated By:</strong><br/>
                        <?= $assessment->validator ? $assessment->validator->name : 'N/A' ?><br/>
                        <small><?= Yii::$app->formatter->asDateTime($assessment->validated_at) ?></small>
                    </p>
                    <?php endif; ?>

                    <div style="margin-top: 15px; border-top: 1px solid #ddd; padding-top: 15px;">
                        <?php if ($assessment->status === 'draft'): ?>
                            <?= Html::a('Edit', ['edit', 'id' => $assessment->id], ['class' => 'btn btn-primary btn-block']) ?>
                            <?= Html::a('Submit', ['submit', 'id' => $assessment->id], ['class' => 'btn btn-success btn-block', 'data' => ['confirm' => 'Submit this assessment?']]) ?>
                        <?php endif; ?>

                        <?php if ($assessment->status === 'submitted' && Yii::$app->user->can('tp_review_assessment')): ?>
                            <?= Html::a('Review', ['review', 'id' => $assessment->id], ['class' => 'btn btn-info btn-block']) ?>
                            <?= Html::a('Validate', ['validate', 'id' => $assessment->id], ['class' => 'btn btn-success btn-block', 'data' => ['confirm' => 'Validate this assessment?']]) ?>
                            <?= Html::a('Reject', ['reject', 'id' => $assessment->id], ['class' => 'btn btn-danger btn-block', 'data' => ['confirm' => 'Reject this assessment?']]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss('
    .label-be { background-color: #d9534f; }
    .label-ae { background-color: #f0ad4e; }
    .label-me { background-color: #5cb85c; }
    .label-ee { background-color: #5bc0de; }
');
?>
