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
                    <input type="text" class="form-control" id="student-name" name="student-name" list="student-name-list" placeholder="Type or select student name" required style="margin-top: 8px;">
                    <datalist id="student-name-list">
                        <?php foreach ($students as $student): ?>
                            <option value="<?= Html::encode($student->full_name) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
            </div>
                    <div class="col-md-6">
                        <div style="margin-bottom: 15px;">
                            <label style="font-weight: 600; color: #333;">REGISTRATION NUMBER / SEARCH:</label><br>
                            <div style="position: relative; margin-top: 8px;">
                                <input type="text" class="form-control" id="student-input" name="AssessmentForm[student_input]" list="student-list" placeholder="Type registration number or student name, or use search 🔍" required style="padding-right: 45px; font-size: 1rem;">
                                <button type="button" id="search-btn-inline" class="btn" style="position: absolute; right: 2px; top: 50%; transform: translateY(-50%); background-color: #3498DB; color: white; font-weight: 600; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">🔍</button>
                                <datalist id="student-list">
                                    <?php foreach ($students as $student): ?>
                                        <option value="<?= Html::encode($student->registration_number . ' - ' . $student->full_name) ?>"></option>
                                    <?php endforeach; ?>
                                </datalist>
                                <?= Html::hiddenInput('AssessmentForm[registration]', '', ['id' => 'assessment-registration']) ?>
                                <select class="form-control" id="student-select" style="padding: 10px; font-size: 1rem; margin-top: 8px; display: none;">
                                    <option value="">-- Select a student --</option>
                                    <?php foreach ($students as $student): ?>
                                        <option value="<?= $student->id ?>" data-name="<?= Html::encode($student->full_name) ?>" data-reg="<?= Html::encode($student->registration_number) ?>" data-school="<?= Html::encode($student->school ?? 'N/A') ?>">
                                            <?= Html::encode($student->registration_number . ' - ' . $student->full_name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Row 2: School and Form/Grade -->
        <div class="row">
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #333;">SCHOOL:</label><br>
                    <input type="text" class="form-control" id="student-school" name="student-school" list="student-school-list" placeholder="Type or select school" required style="margin-top: 8px;">
                    <datalist id="student-school-list">
                        <?php foreach ($students as $student): ?>
                            <?php if (!empty($student->school)): ?>
                                <option value="<?= Html::encode($student->school) ?>"></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </datalist>
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



    <!-- Search Modal -->
    <div id="search-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 500px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
            <h3 style="color: #2874A6; margin-bottom: 15px; font-weight: 700;">Search Students</h3>
            <input type="text" id="search-input" class="form-control" placeholder="Enter registration number or name..." style="padding: 12px; font-size: 1rem; margin-bottom: 15px;">
            <div id="search-results" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                <p style="color: #999; text-align: center; padding: 20px;">Type to search...</p>
            </div>
            <div style="margin-top: 15px; text-align: right;">
                <button type="button" id="close-search-btn" class="btn" style="background-color: #95a5a6; color: white; font-weight: 600; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Close</button>
            </div>
        </div>
    </div>

    <?= Html::activeHiddenInput($model, 'student_id') ?>

    <script>
        // Bind students data
        const students = [
            <?php foreach ($students as $student): ?>
                {
                    id: '<?= $student->id ?>',
                    name: '<?= addslashes($student->full_name) ?>',
                    reg: '<?= addslashes($student->registration_number) ?>',
                    school: '<?= addslashes($student->school ?? 'N/A') ?>'
                },
            <?php endforeach; ?>
        ];
    
        // Auto-populate student fields when typing or selecting
        function autoPopulateStudent(id) {
            const student = students.find(s => s.id == id);
            if (student) {
                document.getElementById('student-name').value = student.name;
                document.getElementById('student-reg').value = student.reg;
                document.getElementById('student-school').value = student.school;
                document.querySelector('[name="AssessmentForm[student_id]"]').value = id;
                document.getElementById('student-input').value = student.reg + ' - ' + student.name;
            }
        }
    
        // Handle TYPE input (datalist autocomplete)
        document.getElementById('student-input').addEventListener('input', function(e) {
            const val = e.target.value.trim();
            let matched = false;
        
            for (let student of students) {
                if (val === (student.reg + ' - ' + student.name)) {
                    autoPopulateStudent(student.id);
                    matched = true;
                    break;
                }
            }
        
            if (!matched) {
                document.querySelector('[name="AssessmentForm[student_id]"]').value = '';
            }
        });

        // Handle NAME input: exact match to student name will populate
        document.getElementById('student-name').addEventListener('input', function(e) {
            const val = e.target.value.trim();
            for (let student of students) {
                if (val === student.name) {
                    autoPopulateStudent(student.id);
                    return;
                }
            }
            // no exact match
            document.querySelector('[name="AssessmentForm[student_id]"]').value = '';
        });

        // Handle REG input: exact match to reg or reg - name will populate
        document.getElementById('student-reg').addEventListener('input', function(e) {
            const val = e.target.value.trim();
            for (let student of students) {
                if (val === student.reg || val === (student.reg + ' - ' + student.name)) {
                    autoPopulateStudent(student.id);
                    return;
                }
            }
            document.querySelector('[name="AssessmentForm[student_id]"]').value = '';
        });

        // Handle SCHOOL input: if only one student matches this school, populate
        document.getElementById('student-school').addEventListener('input', function(e) {
            const val = e.target.value.trim();
            const matches = students.filter(s => s.school === val);
            if (matches.length === 1) {
                autoPopulateStudent(matches[0].id);
            } else {
                // don't set student id when multiple or zero matches
                document.querySelector('[name="AssessmentForm[student_id]"]').value = '';
            }
        });
    
        // Handle SEARCH button
        document.getElementById('search-btn-inline').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('search-modal').style.display = 'flex';
            document.getElementById('search-input').focus();
            document.getElementById('search-input').value = '';
            document.getElementById('search-results').innerHTML = '<p style="color: #999; text-align: center; padding: 20px;">Type to search...</p>';
        });
    
        document.getElementById('close-search-btn').addEventListener('click', function() {
            document.getElementById('search-modal').style.display = 'none';
        });
    
        // Handle SEARCH functionality in modal
        document.getElementById('search-input').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            const resultsDiv = document.getElementById('search-results');
        
            if (query.length === 0) {
                resultsDiv.innerHTML = '<p style="color: #999; text-align: center; padding: 20px;">Type to search...</p>';
                return;
            }
        
            const filtered = students.filter(s => 
                s.name.toLowerCase().includes(query) || 
                s.reg.toLowerCase().includes(query)
            );
        
            if (filtered.length === 0) {
                resultsDiv.innerHTML = '<p style="color: #999; text-align: center; padding: 20px;">No students found</p>';
                return;
            }
        
            let html = '';
            filtered.forEach(student => {
                html += `<div style="padding: 12px; border-bottom: 1px solid #ddd; cursor: pointer; transition: background 0.2s;" class="search-result-item" data-id="${student.id}" data-name="${student.name}" data-reg="${student.reg}" data-school="${student.school}">
                    <strong style="color: #2874A6;">${student.reg}</strong><br>
                    <span style="color: #333;">${student.name}</span><br>
                    <small style="color: #999;">${student.school}</small>
                </div>`;
            });
        
            resultsDiv.innerHTML = html;
            
            // Add click handlers to search results
            document.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', function() {
                    const id = this.dataset.id;
                    autoPopulateStudent(id);
                    document.getElementById('search-modal').style.display = 'none';
                });
                
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#e8f4f8';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'transparent';
                });
            });
        });

        // Form submit validation: ensure name, registration, school are present
        document.getElementById('assessment-form').addEventListener('submit', function(e) {
            const name = document.getElementById('student-name').value.trim();
            const input = document.getElementById('student-input').value.trim();
            const school = document.getElementById('student-school').value.trim();

            // Try parse registration from student-input if formatted as "REG - Name"
            let registration = '';
            if (input.includes(' - ')) {
                registration = input.split(' - ')[0].trim();
            }

            // If student_id is set (existing student), ensure registration taken from students list
            const studentId = document.querySelector('[name="AssessmentForm[student_id]"]').value;
            if (studentId) {
                const s = students.find(s => s.id == studentId);
                if (s) registration = s.reg;
            }

            // set hidden registration
            document.getElementById('assessment-registration').value = registration;

            if (!name || !registration || !school) {
                e.preventDefault();
                alert('Please ensure Student Name, Registration and School are all provided.');
                return false;
            }

            return true;
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
                // Bind students data
                const students = [
                    <?php foreach ($students as $student): ?>
                        {
                            id: '<?= $student->id ?>',
                            name: '<?= addslashes($student->full_name) ?>',
                            reg: '<?= addslashes($student->registration_number) ?>',
                            school: '<?= addslashes($student->school ?? 'N/A') ?>'
                        },
                    <?php endforeach; ?>
                ];
    
                // Auto-populate student fields when typing or selecting
                function autoPopulateStudent(id) {
                    const student = students.find(s => s.id == id);
                    if (student) {
                        document.getElementById('student-name').value = student.name;
                        document.getElementById('student-reg').value = student.reg;
                        document.getElementById('student-school').value = student.school;
                        document.querySelector('[name="AssessmentForm[student_id]"]').value = id;
                        document.getElementById('student-input').value = student.reg + ' - ' + student.name;
                    }
                }
    
                // Handle TYPE input (datalist autocomplete)
                document.getElementById('student-input').addEventListener('input', function(e) {
                    const val = e.target.value.trim();
                    let matched = false;
        
                    for (let student of students) {
                        if (val === (student.reg + ' - ' + student.name)) {
                            autoPopulateStudent(student.id);
                            matched = true;
                            break;
                        }
                    }
        
                    if (!matched) {
                        document.querySelector('[name="AssessmentForm[student_id]"]').value = '';
                    }
                });
    
                // Handle SEARCH button
                document.getElementById('search-btn-inline').addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('search-modal').style.display = 'flex';
                    document.getElementById('search-input').focus();
                    document.getElementById('search-input').value = '';
                    document.getElementById('search-results').innerHTML = '<p style="color: #999; text-align: center; padding: 20px;">Type to search...</p>';
                });
    
                document.getElementById('close-search-btn').addEventListener('click', function() {
                    document.getElementById('search-modal').style.display = 'none';
                });
    
                // Handle SEARCH functionality in modal
                document.getElementById('search-input').addEventListener('input', function(e) {
                    const query = e.target.value.toLowerCase().trim();
                    const resultsDiv = document.getElementById('search-results');
        
                    if (query.length === 0) {
                        resultsDiv.innerHTML = '<p style="color: #999; text-align: center; padding: 20px;">Type to search...</p>';
                        return;
                    }
        
                    const filtered = students.filter(s => 
                        s.name.toLowerCase().includes(query) || 
                        s.reg.toLowerCase().includes(query)
                    );
        
                    if (filtered.length === 0) {
                        resultsDiv.innerHTML = '<p style="color: #999; text-align: center; padding: 20px;">No students found</p>';
                        return;
                    }
        
                    let html = '';
                    filtered.forEach(student => {
                        html += `<div style="padding: 12px; border-bottom: 1px solid #ddd; cursor: pointer; transition: background 0.2s;" class="search-result-item" data-id="${student.id}" data-name="${student.name}" data-reg="${student.reg}" data-school="${student.school}">
                            <strong style="color: #2874A6;">${student.reg}</strong><br>
                            <span style="color: #333;">${student.name}</span><br>
                            <small style="color: #999;">${student.school}</small>
                        </div>`;
                    });
        
                    resultsDiv.innerHTML = html;
        
                    // Add click handlers to search results
                    document.querySelectorAll('.search-result-item').forEach(item => {
                        item.addEventListener('click', function() {
                            const id = this.dataset.id;
                            autoPopulateStudent(id);
                            document.getElementById('search-modal').style.display = 'none';
                        });
            
                        item.addEventListener('mouseenter', function() {
                            this.style.backgroundColor = '#e8f4f8';
                        });
            
                        item.addEventListener('mouseleave', function() {
                            this.style.backgroundColor = 'transparent';
                        });
                    });
                });
                </script>