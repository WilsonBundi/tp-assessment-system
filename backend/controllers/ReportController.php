<?php

namespace backend\controllers;

use common\models\TpAssessment;
use common\models\TpReport;
use common\models\TpAuditLog;
use common\services\ReportGenerator;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * ReportController handles TP assessment report operations
 */
class ReportController extends Controller
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
                        'actions' => ['index', 'view', 'download'],
                        'allow' => true,
                        'roles' => ['tp_supervisor', 'tp_coordinator', 'tp_office', 'tp_department_chair'],
                    ],
                    [
                        'actions' => ['generate'],
                        'allow' => true,
                        'roles' => ['tp_office', 'tp_department_chair'],
                    ],
                ],
            ],
        ];
    }

    /**
     * List all reports
     */
    public function actionIndex()
    {
        $query = TpReport::find();

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
     * View report
     */
    public function actionView($id)
    {
        $report = $this->findReport($id);

        return $this->render('view', [
            'report' => $report,
            'assessment' => $report->assessment,
        ]);
    }

    /**
     * Download report
     */
    public function actionDownload($id)
    {
        $report = $this->findReport($id);

        // Log download action
        TpAuditLog::logAction(
            TpAuditLog::ACTION_DOWNLOAD,
            'report',
            $report->id,
            "Report {$report->report_type} downloaded for assessment {$report->assessment_id}"
        );

        $filePath = Yii::getAlias('@common/web/uploads/reports/') . $report->report_file;

        if (file_exists($filePath)) {
            // Update downloaded timestamp
            $report->downloaded_at = date('Y-m-d H:i:s');
            $report->save();

            return Yii::$app->response->sendFile($filePath, $report->report_file);
        } else {
            throw new NotFoundHttpException('Report file not found');
        }
    }

    /**
     * Generate reports for assessment
     */
    public function actionGenerate($assessmentId)
    {
        $assessment = TpAssessment::findOne($assessmentId);

        if (!$assessment) {
            throw new NotFoundHttpException('Assessment not found');
        }

        // Check if reports already exist
        $existingReports = TpReport::find()
            ->where(['assessment_id' => $assessmentId])
            ->count();

        if ($existingReports > 0) {
            Yii::$app->session->setFlash('warning', 'Reports already generated for this assessment');
            return $this->redirect(['index']);
        }

        try {
            $reports = ReportGenerator::generateReports($assessment);
            
            Yii::$app->session->setFlash('success', 'Reports generated successfully');
            
            return $this->redirect(['index']);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Failed to generate reports: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Find report model
     */
    protected function findReport($id)
    {
        $report = TpReport::findOne($id);

        if (!$report) {
            throw new NotFoundHttpException('Report not found');
        }

        return $report;
    }
}
