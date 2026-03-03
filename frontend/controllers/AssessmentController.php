<?php

namespace frontend\controllers;

use common\models\TpAssessment;
use common\models\TpStudent;
use common\models\TpLecturer;
use common\models\TpRubricArea;
use common\models\TpNotification;
use frontend\models\AssessmentForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * Assessment controller for frontend users (supervisors etc.)
 */
class AssessmentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'edit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * List my assessments
     */
    public function actionIndex()
    {
        $query = TpAssessment::find()->where(['supervisor_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
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
        $students = TpStudent::find()->all();
        $rubricAreas = TpRubricArea::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            $result = $model->saveAssessment(Yii::$app->user->id);
            if ($result) {
                Yii::$app->session->setFlash('success', 'Assessment saved successfully.');
                return $this->redirect(['view', 'id' => $result->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'students' => $students,
            'rubricAreas' => $rubricAreas,
        ]);
    }

    /**
     * Edit existing assessment (only if draft)
     */
    public function actionEdit($id)
    {
        $assessment = $this->findModel($id);
        if ($assessment->supervisor_id !== Yii::$app->user->id || $assessment->status !== 'draft') {
            throw new NotFoundHttpException('You are not allowed to edit this assessment.');
        }

        $model = new AssessmentForm();
        $model->loadFromAssessment($assessment);
        $students = TpStudent::find()->all();
        $rubricAreas = TpRubricArea::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveAssessment(Yii::$app->user->id)) {
                Yii::$app->session->setFlash('success', 'Assessment updated successfully.');
                return $this->redirect(['view', 'id' => $assessment->id]);
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'students' => $students,
            'rubricAreas' => $rubricAreas,
        ]);
    }

    /**
     * View details
     */
    public function actionView($id)
    {
        $assessment = $this->findModel($id);
        if ($assessment->supervisor_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('Assessment not found');
        }

        $scores = $assessment->getScores()->all();
        $images = $assessment->supportingImages;

        return $this->render('view', [
            'assessment' => $assessment,
            'scores' => $scores,
            'images' => $images,
        ]);
    }

    /**
     * Common finder
     */
    protected function findModel($id)
    {
        $model = TpAssessment::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Assessment not found');
        }
        return $model;
    }
}
