<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create New Assessment';
$this->params['breadcrumbs'][] = ['label' => 'Assessments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="assessment-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="assessment-form-wrapper" style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
        <?php $form = ActiveForm::begin(['id' => 'assessment-form']); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'student_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map($students, 'id', function($student) {
                        return $student->registration_number . ' - ' . $student->full_name;
                    }),
                    ['prompt' => 'Select Student']
                ) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'assessment_date')->input('date') ?>
            </div>
        </div>

        <div class="section" style="background: white; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h3 style="color: #0066cc; border-bottom: 2px solid #0066cc; padding-bottom: 10px;">Rubric Assessment (12 Competence Areas)</h3>

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
                                    'data-area-id' => $area->id
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
            <?= $form->field($model, 'supervising_files')->fileInput(['multiple' => true, 'accept' => 'image/*,application/pdf']) ?>
            <p class="help-block">Upload up to 5 supporting images (lesson plan, classroom, resources, etc.)</p>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save Assessment', ['class' => 'btn btn-primary btn-lg', 'style' => 'margin-right: 10px;']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default btn-lg']) ?>
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

.rubric-section {
    background: white;
    padding: 15px;
    margin: 10px 0;
    border-left: 4px solid #0066cc;
}
</style>