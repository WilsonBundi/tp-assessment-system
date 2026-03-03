<?php

use yii\helpers\Html;

$this->title = 'Assessment Report - ' . $report->assessment->student->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Helper function for performance color
function getPerformanceClass($level) {
    $map = ['BE' => 'danger', 'AE' => 'warning', 'ME' => 'success', 'EE' => 'info'];
    return $map[$level] ?? 'default';
}
?>

<div class="report-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $report->getReportTypeLabel($report->report_type) ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tr>
                            <th width="40%">Attribute</th>
                            <th>Value</th>
                        </tr>
                        <tr>
                            <td><strong>Assessment ID</strong></td>
                            <td><?= $report->assessment_id ?></td>
                        </tr>
                        <tr>
                            <td><strong>Student</strong></td>
                            <td><?= $assessment->student->full_name ?> (<?= $assessment->student->registration_number ?>)</td>
                        </tr>
                        <tr>
                            <td><strong>Supervisor</strong></td>
                            <td><?= $assessment->supervisor->name ?></td>
                        </tr>
                        <tr>
                            <td><strong>Assessment Date</strong></td>
                            <td><?= Yii::$app->formatter->asDate($assessment->assessment_date) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total Score</strong></td>
                            <td><?= $assessment->total_score ?>/120</td>
                        </tr>
                        <tr>
                            <td><strong>Overall Performance</strong></td>
                            <td>
                                <span class="label label-<?= $this->getPerformanceClass($assessment->overall_performance) ?>">
                                    <?= \common\models\TpAssessment::getPerformanceLabel($assessment->overall_performance) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Report Generated</strong></td>
                            <td><?= Yii::$app->formatter->asDateTime($report->generated_at) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Last Downloaded</strong></td>
                            <td><?= $report->downloaded_at ? Yii::$app->formatter->asDateTime($report->downloaded_at) : 'Never' ?></td>
                        </tr>
                    </table>

                    <div style="margin-top: 20px;">
                        <?= Html::a('Download PDF', ['download', 'id' => $report->id], ['class' => 'btn btn-success btn-lg']) ?>
                        <?= Html::a('Back to Reports', ['index'], ['class' => 'btn btn-default btn-lg']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
