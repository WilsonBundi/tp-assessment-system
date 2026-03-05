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
                <?= $form->field($model, 'assessment_date')->textInput([
                    'placeholder' => 'dd/mm/yyyy',
                    'class' => 'form-control date-mask',
                    'style' => 'margin-top: 8px;'
                ])->label(false) ?>
            </div>
        </div>

        <div class="section" style="background: white; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h3 style="color: #0066cc; border-bottom: 2px solid #0066cc; padding-bottom: 10px;">Rubric Assessment (Update Scores and Comments)</h3>

            <table class="table table-bordered" style="margin-top: 20px; border-collapse: collapse;">
                <thead style="background-color: #E8F4F8;">
                    <tr>
                        <th style="border: 1px solid #bbb; padding: 12px; text-align: left; font-weight: 600; width: 30%;">Competence Area</th>
                        <th style="border: 1px solid #bbb; padding: 12px; text-align: center; font-weight: 600; width: 8%;">BE</th>
                        <th style="border: 1px solid #bbb; padding: 12px; text-align: center; font-weight: 600; width: 8%;">AE</th>
                        <th style="border: 1px solid #bbb; padding: 12px; text-align: center; font-weight: 600; width: 8%;">ME</th>
                        <th style="border: 1px solid #bbb; padding: 12px; text-align: center; font-weight: 600; width: 8%;">EE</th>
                        <th style="border: 1px solid #bbb; padding: 12px; text-align: left; font-weight: 600; width: 38%;">REMARKS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rubricAreas as $area): ?>
                        <tr>
                            <td style="border: 1px solid #bbb; padding: 12px; vertical-align: top;">
                                <strong><?= $area->area_name ?></strong><br>
                                <small style="color: #666;"><?= $area->description ?></small>
                            </td>
                            <td style="border: 1px solid #bbb; padding: 12px; text-align: center;">
                                <input type="checkbox" class="score-checkbox" data-score="1" data-area-id="<?= $area->id ?>" style="cursor: pointer; width: 20px; height: 20px;" <?= ($model->scores[$area->id] ?? 0) == 2.5 ? 'checked' : '' ?>>
                            </td>
                            <td style="border: 1px solid #bbb; padding: 12px; text-align: center;">
                                <input type="checkbox" class="score-checkbox" data-score="2" data-area-id="<?= $area->id ?>" style="cursor: pointer; width: 20px; height: 20px;" <?= ($model->scores[$area->id] ?? 0) == 5 ? 'checked' : '' ?>>
                            </td>
                            <td style="border: 1px solid #bbb; padding: 12px; text-align: center;">
                                <input type="checkbox" class="score-checkbox" data-score="3" data-area-id="<?= $area->id ?>" style="cursor: pointer; width: 20px; height: 20px;" <?= ($model->scores[$area->id] ?? 0) == 7.5 ? 'checked' : '' ?>>
                            </td>
                            <td style="border: 1px solid #bbb; padding: 12px; text-align: center;">
                                <input type="checkbox" class="score-checkbox" data-score="4" data-area-id="<?= $area->id ?>" style="cursor: pointer; width: 20px; height: 20px;" <?= ($model->scores[$area->id] ?? 0) == 10 ? 'checked' : '' ?>>
                            </td>
                            <td style="border: 1px solid #bbb; padding: 12px;">
                                <?= Html::textarea("AssessmentForm[remarks][{$area->id}]", 
                                    $model->remarks[$area->id] ?? '', [
                                    'class' => 'form-control',
                                    'rows' => 2,
                                    'placeholder' => 'Enter remarks',
                                    'style' => 'font-size: 0.9rem;'
                                ]) ?>
                                <?= Html::hiddenInput("AssessmentForm[scores][{$area->id}]", $model->scores[$area->id] ?? 0, ['class' => 'score-input', 'data-area-id' => $area->id]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="color: #666; font-size: 0.9rem; margin-top: 10px;"><strong>Note:</strong> BE = Below Expectations | AE = Approaching Expectations | ME = Meets Expectations | EE = Exceeds Expectations</p>
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

<script>
document.querySelectorAll('.score-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const areaId = this.dataset.areaId;
        const score = this.dataset.score;
        
        // Uncheck other checkboxes for this area
        document.querySelectorAll(`.score-checkbox[data-area-id="${areaId}"]`).forEach(cb => {
            if (cb !== this) cb.checked = false;
        });
        
        // Update hidden input with score (1-4 mapped to 10, 7.5, 5, 2.5)
        const input = document.querySelector(`.score-input[data-area-id="${areaId}"]`);
        if (input && this.checked) {
            const scoreMap = {1: 2.5, 2: 5, 3: 7.5, 4: 10};
            input.value = scoreMap[score];
        } else if (input) {
            input.value = 0;
        }
    });
});
</script>

<?php
$script = <<<JS
if (window.jQuery && $.fn.inputmask) {
    $('.date-mask').inputmask('99/99/9999');
}
JS;
$this->registerJs($script);
?>
