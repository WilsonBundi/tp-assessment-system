<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Edit Assessment - ' . $assessment->student->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Assessments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'View', 'url' => ['view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>

<div class="assessment-edit">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <strong>Note:</strong> Once an assessment is validated, it cannot be edited. Make all necessary changes before validation.
    </div>

    <div class="assessment-form-wrapper" style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
        <?php $form = ActiveForm::begin(['id' => 'assessment-form']); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Student</label>
                    <p class="form-control-static"><?= $assessment->student->full_name ?> (<?= $assessment->student->registration_number ?>)</p>
                </div>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'assessment_date')->input('date') ?>
            </div>
        </div>

        <div class="section" style="background: white; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h3 style="color: #0066cc; border-bottom: 2px solid #0066cc; padding-bottom: 10px;">Rubric Assessment (Update Scores and Comments)</h3>

            <table class="table table-bordered" style="margin-top: 20px;">
                <thead style="background-color: #f0f0f0;">
                    <tr>
                        <th width="35%">Competence Area</th>
                        <th width="15%">Score (0-10)</th>
                        <th width="50%">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rubricAreas as $area): ?>
                        <tr>
                            <td>
                                <strong><?= $area->area_name ?></strong><br/>
                                <small><?= $area->description ?></small>
                            </td>
                            <td>
                                <?= Html::input('number', "AssessmentForm[scores][{$area->id}]", 
                                    $model->scores[$area->id] ?? '', [
                                    'class' => 'form-control score-input',
                                    'min' => 0,
                                    'max' => 10,
                                    'placeholder' => '0-10',
                                    'required' => true,
                                ]) ?>
                            </td>
                            <td>
                                <?= Html::textarea("AssessmentForm[remarks][{$area->id}]", 
                                    $model->remarks[$area->id] ?? '', [
                                    'class' => 'form-control',
                                    'rows' => 2,
                                    'placeholder' => 'Comments for this area'
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'supervisor_remarks')->textarea(['rows' => 4]) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Update Assessment', ['class' => 'btn btn-primary btn-lg', 'style' => 'margin-right: 10px;']) ?>
            <?= Html::a('Cancel', ['view', 'id' => $assessment->id], ['class' => 'btn btn-default btn-lg']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<style>
.score-input {
    text-align: center;
    font-weight: bold;
    font-size: 18px;
}
</style>
