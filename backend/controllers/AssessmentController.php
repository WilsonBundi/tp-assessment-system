<?php

namespace backend\controllers;

use common\models\TpAssessment;
use common\models\TpStudent;
use common\models\TpLecturer;
use common\models\TpRubricArea;
use common\models\TpAuditLog;
use common\models\TpNotification;
use frontend\models\AssessmentForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * AssessmentController handles TP assessment operations
 */
class AssessmentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'edit', 'submit', 'my-assessments'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['review', 'validate', 'reject'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * List all assessments
     */
    public function actionIndex()
    {
        $query = TpAssessment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Create new assessment
     */
    public function actionCreate()
    {
        $model = new AssessmentForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $supervisor = TpLecturer::findOne(['user_id' => Yii::$app->user->id]);
            
            if (!$supervisor) {
                Yii::$app->session->setFlash('error', 'Supervisor record not found');
                return $this->redirect(['index']);
            }

            $assessment = $model->saveAssessment($supervisor->id);
            
            if ($assessment) {
                // Log action
                TpAuditLog::logAction(
                    TpAuditLog::ACTION_CREATE,
                    'assessment',
                    $assessment->id,
                    "Assessment created for student {$assessment->student_id}"
                );

                Yii::$app->session->setFlash('success', 'Assessment created successfully');
                return $this->redirect(['view', 'id' => $assessment->id]);
            }
        }

        // Load available students
        $students = TpStudent::find()->all();

        return $this->render('create', [
            'model' => $model,
            'students' => $students,
            'rubricAreas' => TpRubricArea::find()->orderBy(['sequence' => SORT_ASC])->all(),
        ]);
    }

    /**
     * View assessment
     */
    public function actionView($id)
    {
        $assessment = $this->findAssessment($id);
        $scores = $assessment->getScores()->all();

        return $this->render('view', [
            'assessment' => $assessment,
            'scores' => $scores,
            'images' => $assessment->getImages()->all(),
        ]);
    }

    /**
     * Edit assessment
     */
    public function actionEdit($id)
    {
        $assessment = $this->findAssessment($id);
        
        if ($assessment->status !== TpAssessment::STATUS_DRAFT && $assessment->status !== TpAssessment::STATUS_SUBMITTED) {
            throw new \yii\web\ForbiddenHttpException('This assessment cannot be edited');
        }

        $model = new AssessmentForm();
        $model->loadFromAssessment($assessment);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $oldValues = [
                'assessment_date' => $assessment->assessment_date,
                'supervisor_remarks' => $assessment->supervisor_remarks,
            ];

            $supervisor = TpLecturer::findOne(['user_id' => Yii::$app->user->id]);
            $assessment = $model->saveAssessment($supervisor->id);

            if ($assessment) {
                // Log action
                TpAuditLog::logAction(
                    TpAuditLog::ACTION_UPDATE,
                    'assessment',
                    $assessment->id,
                    "Assessment updated",
                    $oldValues,
                    [
                        'assessment_date' => $assessment->assessment_date,
                        'supervisor_remarks' => $assessment->supervisor_remarks,
                    ]
                );

                Yii::$app->session->setFlash('success', 'Assessment updated successfully');
                return $this->redirect(['view', 'id' => $assessment->id]);
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'assessment' => $assessment,
            'rubricAreas' => TpRubricArea::find()->orderBy(['sequence' => SORT_ASC])->all(),
        ]);
    }

    /**
     * Submit assessment
     */
    public function actionSubmit($id)
    {
        $assessment = $this->findAssessment($id);

        if ($assessment->status !== TpAssessment::STATUS_DRAFT) {
            throw new \yii\web\ForbiddenHttpException('Only draft assessments can be submitted');
        }

        $assessment->status = TpAssessment::STATUS_SUBMITTED;
        $assessment->submitted_at = date('Y-m-d H:i:s');

        if ($assessment->save()) {
            // Log action
            TpAuditLog::logAction(
                TpAuditLog::ACTION_SUBMIT,
                'assessment',
                $assessment->id,
                'Assessment submitted'
            );

            // Notify Zone Coordinator
            TpNotification::notify(
                $assessment->supervisor_id,
                'Assessment Submitted',
                "Assessment for {$assessment->student->full_name} has been submitted",
                TpNotification::TYPE_SUBMISSION,
                $assessment->id
            );

            Yii::$app->session->setFlash('success', 'Assessment submitted successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to submit assessment');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Review assessment (Zone Coordinator)
     */
    public function actionReview($id)
    {
        $assessment = $this->findAssessment($id);

        if ($assessment->status !== TpAssessment::STATUS_SUBMITTED) {
            throw new \yii\web\ForbiddenHttpException('Only submitted assessments can be reviewed');
        }

        $assessment->status = TpAssessment::STATUS_REVIEWED;

        if ($assessment->save()) {
            TpAuditLog::logAction(
                TpAuditLog::ACTION_UPDATE,
                'assessment',
                $assessment->id,
                'Assessment reviewed'
            );

            Yii::$app->session->setFlash('success', 'Assessment marked as reviewed');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Validate assessment (Zone Coordinator/Department Chair)
     */
    public function actionValidate($id)
    {
        $assessment = $this->findAssessment($id);

        if (!in_array($assessment->status, [TpAssessment::STATUS_SUBMITTED, TpAssessment::STATUS_REVIEWED])) {
            throw new \yii\web\ForbiddenHttpException('Assessment cannot be validated at this stage');
        }

        $assessment->status = TpAssessment::STATUS_VALIDATED;
        $assessment->is_validated = true;
        $assessment->validated_by = Yii::$app->user->id;
        $assessment->validated_at = date('Y-m-d H:i:s');

        if ($assessment->save()) {
            TpAuditLog::logAction(
                TpAuditLog::ACTION_VALIDATE,
                'assessment',
                $assessment->id,
                'Assessment validated'
            );

            // Notify supervisor and student
            TpNotification::notify(
                $assessment->supervisor_id,
                'Assessment Validated',
                "Assessment for {$assessment->student->full_name} has been validated",
                TpNotification::TYPE_VALIDATION,
                $assessment->id
            );

            Yii::$app->session->setFlash('success', 'Assessment validated successfully');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Reject assessment
     */
    public function actionReject($id)
    {
        $assessment = $this->findAssessment($id);

        if ($assessment->is_validated) {
            throw new \yii\web\ForbiddenHttpException('Validated assessments cannot be rejected');
        }

        $assessment->status = TpAssessment::STATUS_REJECTED;

        if ($assessment->save()) {
            TpAuditLog::logAction(
                TpAuditLog::ACTION_REJECT,
                'assessment',
                $assessment->id,
                'Assessment rejected'
            );

            TpNotification::notify(
                $assessment->supervisor_id,
                'Assessment Rejected',
                "Assessment for {$assessment->student->full_name} has been rejected",
                TpNotification::TYPE_REJECTION,
                $assessment->id
            );

            Yii::$app->session->setFlash('success', 'Assessment rejected');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * My assessments (for supervisors)
     */
    public function actionMyAssessments()
    {
        $supervisor = TpLecturer::findOne(['user_id' => Yii::$app->user->id]);

        $query = TpAssessment::find()->where(['supervisor_id' => $supervisor->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        return $this->render('my-assessments', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Find assessment model
     */
    protected function findAssessment($id)
    {
        $assessment = TpAssessment::findOne($id);

        if (!$assessment) {
            throw new NotFoundHttpException('Assessment not found');
        }

        return $assessment;
    }
}
