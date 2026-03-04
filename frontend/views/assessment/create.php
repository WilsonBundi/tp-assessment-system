<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\TpAssessment;

$this->title = 'Create Assessment';
$this->params['breadcrumbs'][] = ['label' => 'Assessments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="assessment-create" style="background: white; padding: 30px; border-radius: 5px;">

    <!-- Official Header -->
    <div style="text-align: center; margin-bottom: 30px; border-bottom: 3px solid #3498DB; padding-bottom: 20px;">
        <h2 style="margin: 0; color: #2c3e50; font-weight: 700;">UNIVERSITY OF NAIROBI</h2>
        <h3 style="margin: 5px 0; color: #34495e;">FACULTY OF EDUCATION</h3>
        <h2 style="margin: 10px 0; color: #3498DB; font-weight: 700;">TEACHING PRACTICE ASSESSMENT REPORT</h2>
    </div>

    <?php $form = ActiveForm::begin(['id' => 'assessment-form']); ?>

    <!-- Student Details Section -->
    <div style="background: #f0f8ff; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #3498DB;">
        <h4 style="color: #2874A6; margin-bottom: 15px; font-weight: 700;">STUDENT-TEACHER DETAILS</h4>
        
        <!-- Row 1: Name and Registration -->
        <div class="row">
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">NAME:</label><br>
                    <input type="text" class="form-control" id="student-name" placeholder="Student name" readonly style="margin-top: 8px; background-color: #f5f5f5;">
                </div>
            </div>
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">REGISTRATION NUMBER:</label><br>
                    <input type="text" class="form-control" id="student-reg" placeholder="Student registration" readonly style="margin-top: 8px; background-color: #f5f5f5;">
                </div>
            </div>
        </div>

        <!-- Row 2: School and Form/Grade -->
        <div class="row">
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">SCHOOL:</label><br>
                    <input type="text" class="form-control" id="student-school" placeholder="TP School name" readonly style="margin-top: 8px; background-color: #f5f5f5;">
                </div>
            </div>
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">FORM/GRADE:</label><br>
                    <input type="text" class="form-control" placeholder="Form or Grade" style="margin-top: 8px;">
                </div>
            </div>
        </div>

        <!-- Row 3: Assessment Date and Time -->
        <div class="row">
            <div class="col-md-4">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">DATE OF ASSESSMENT:</label><br>
                    <?= $form->field($model, 'assessment_date')->input('date', ['style' => 'margin-top: 8px;'])->label(false) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">TIME FROM:</label><br>
                    <input type="time" class="form-control" placeholder="Start time" style="margin-top: 8px;">
                </div>
            </div>
            <div class="col-md-4">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">TIME TO:</label><br>
                    <input type="time" class="form-control" placeholder="End time" style="margin-top: 8px;">
                </div>
            </div>
        </div>

        <!-- Row 4: Learning Area, Zone -->
        <div class="row">
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">LEARNING AREA (SUBJECT):</label><br>
                    <input type="text" class="form-control" placeholder="e.g., Mathematics, English" style="margin-top: 8px;">
                </div>
            </div>
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">ZONE:</label><br>
                    <input type="text" class="form-control" placeholder="TP Zone" style="margin-top: 8px;">
                </div>
            </div>
        </div>

        <!-- Row 5: Strand and Sub-Strand -->
        <div class="row">
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">STRAND (TOPIC):</label><br>
                    <input type="text" class="form-control" placeholder="Lesson topic" style="margin-top: 8px;">
                </div>
            </div>
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">SUB-STRAND (SUB-TOPIC):</label><br>
                    <input type="text" class="form-control" placeholder="Sub-topic details" style="margin-top: 8px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Student Selection Dropdown -->
    <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 2px solid #3498DB;">
        <h5 style="color: #2874A6; margin-bottom: 10px; font-weight: 700;">📋 SELECT STUDENT-TEACHER</h5>
        <div class="row">
            <div class="col-md-12">
                <label style="font-weight: 600; color: #333;">Choose Student or Type to Search:</label><br>
                <select class="form-control" id="student-select" style="padding: 10px; font-size: 1rem; margin-top: 8px;">
                    <option value="">-- Select a student or type below --</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?= $student->id ?>" data-name="<?= Html::encode($student->full_name) ?>" data-reg="<?= Html::encode($student->registration_number) ?>" data-school="<?= Html::encode($student->school ?? 'N/A') ?>">
                            <?= Html::encode($student->registration_number . ' - ' . $student->full_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small style="color: #666; display: block; margin-top: 8px;">💡 Tip: You can also type a new registration number or name below to add a new student.</small>
                <?= Html::activeHiddenInput($model, 'student_id') ?>
            </div>
        </div>
    </div>

    <!-- OR - Type New Student -->
    <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
        <p style="color: #666; margin: 0; font-weight: 600;">OR</p>
    </div>

    <!-- Type New Student -->
    <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 2px solid #95a5a6;">
        <h5 style="color: #34495e; margin-bottom: 10px; font-weight: 700;">➕ ADD NEW STUDENT</h5>
        <?php $listId = 'student-list'; ?>
        <label style="font-weight: 600; color: #333;">Student Registration/Name:</label><br>
        <?= Html::textInput('AssessmentForm[student_input]', $model->student_input, [
            'class' => 'form-control', 
            'list' => $listId, 
            'id' => 'student-input',
            'placeholder' => 'Type registration number or student name',
            'style' => 'padding: 10px; font-size: 1rem; margin-top: 8px;'
        ]) ?>
        <datalist id="<?= $listId ?>">
            <?php foreach ($students as $student): ?>
                <option value="<?= Html::encode($student->registration_number . ' - ' . $student->full_name) ?>"></option>
            <?php endforeach; ?>
        </datalist>
        <small style="color: #666; display: block; margin-top: 8px;">🆕 This will create a new student record if the entry doesn't exist.</small>
    </div>

    <script>
    // Handle SELECT dropdown
    document.getElementById('student-select').addEventListener('change', function(e) {
        const studentId = this.value;
        if (studentId) {
            const option = this.options[this.selectedIndex];
            const name = option.dataset.name;
            const reg = option.dataset.reg;
            const school = option.dataset.school;
            
            document.querySelector('[name="AssessmentForm[student_id]"]').value = studentId;
            document.getElementById('student-name').value = name;
            document.getElementById('student-reg').value = reg;
            document.getElementById('student-school').value = school;
            document.getElementById('student-input').value = reg + ' - ' + name;
        } else {
            document.querySelector('[name="AssessmentForm[student_id]"]').value = '';
            document.getElementById('student-name').value = '';
            document.getElementById('student-reg').value = '';
            document.getElementById('student-school').value = '';
            document.getElementById('student-input').value = '';
        }
    });
    
    // Handle TYPE input
    document.getElementById('student-input').addEventListener('input', function(e) {
        var val = e.target.value;
        var matched = false;
        <?php foreach ($students as $student): ?>
            if (val === '<?= addslashes($student->registration_number . ' - ' . $student->full_name) ?>') {
                document.querySelector('[name="AssessmentForm[student_id]"]').value = '<?= $student->id ?>';
                document.getElementById('student-name').value = '<?= addslashes($student->full_name) ?>';
                document.getElementById('student-reg').value = '<?= addslashes($student->registration_number) ?>';
                document.getElementById('student-school').value = '<?= addslashes($student->school ?? 'N/A') ?>';
                document.getElementById('student-select').value = '<?= $student->id ?>';
                matched = true;
            }
        <?php endforeach; ?>
        if (!matched) {
            document.querySelector('[name="AssessmentForm[student_id]"]').value = '';
            document.getElementById('student-select').value = '';
        }
    });
    </script>

    <!-- Rubric Assessment Section -->
    <div style="margin-bottom: 20px;">
        <h4 style="color: #2874A6; margin-bottom: 15px; font-weight: 700; border-bottom: 2px solid #3498DB; padding-bottom: 10px;">COMPETENCE AREAS ASSESSMENT (12 AREAS)</h4>

        <table class="table table-bordered" style="margin-top: 15px; border-collapse: collapse;">
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
                            <input type="checkbox" class="score-checkbox" data-score="1" data-area-id="<?= $area->id ?>" style="cursor: pointer; width: 20px; height: 20px;">
                        </td>
                        <td style="border: 1px solid #bbb; padding: 12px; text-align: center;">
                            <input type="checkbox" class="score-checkbox" data-score="2" data-area-id="<?= $area->id ?>" style="cursor: pointer; width: 20px; height: 20px;">
                        </td>
                        <td style="border: 1px solid #bbb; padding: 12px; text-align: center;">
                            <input type="checkbox" class="score-checkbox" data-score="3" data-area-id="<?= $area->id ?>" style="cursor: pointer; width: 20px; height: 20px;">
                        </td>
                        <td style="border: 1px solid #bbb; padding: 12px; text-align: center;">
                            <input type="checkbox" class="score-checkbox" data-score="4" data-area-id="<?= $area->id ?>" style="cursor: pointer; width: 20px; height: 20px;">
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

    <!-- Supervisor Comments -->
    <div style="background: #f0f8ff; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #3498DB;">
        <h4 style="color: #2874A6; margin-bottom: 15px; font-weight: 700;">SUPERVISOR REMARKS</h4>
        <?= $form->field($model, 'supervisor_remarks')->textarea(['rows' => 4, 'placeholder' => 'Enter overall supervisor remarks'])->label(false) ?>
    </div>

    <!-- Supporting Evidence -->
    <div style="background: #f0f8ff; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #3498DB;">
        <h4 style="color: #2874A6; margin-bottom: 15px; font-weight: 700;">SUPPORTING EVIDENCE</h4>
        <?= $form->field($model, 'supervising_files')->fileInput(['multiple' => true, 'accept' => 'image/*,application/pdf']) ?>
        <p style="color: #666; font-size: 0.9rem;">Upload up to 5 supporting images or PDFs (lesson plans, classroom photos, student work samples, etc.)</p>
    </div>

    <!-- Submit Button -->
    <div class="form-group" style="margin-top: 30px;">
        <?= Html::submitButton('Save and Submit Assessment', [
            'class' => 'btn', 
            'style' => 'background-color: #3498DB; color: white; font-weight: 600; padding: 12px 30px; font-size: 1rem; border: none; border-radius: 5px; cursor: pointer; transition: all 0.3s;'
        ]) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn', 'style' => 'background-color: #95a5a6; color: white; font-weight: 600; padding: 12px 30px; font-size: 1rem; border: none; border-radius: 5px; margin-left: 10px; transition: all 0.3s;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

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

<style>
.form-control {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px 12px;
}

.form-control:focus {
    border-color: #3498DB;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
</style>