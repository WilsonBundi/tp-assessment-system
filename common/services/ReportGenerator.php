<?php

namespace common\services;

use common\models\TpAssessment;
use common\models\TpReport;
use common\models\TpRubricArea;
use common\models\TpAuditLog;
use Yii;
use yii\base\BaseObject;

/**
 * Service class for generating TP Assessment Reports
 */
class ReportGenerator extends BaseObject
{
    /**
     * Generate both student and office reports
     */
    public static function generateReports(TpAssessment $assessment)
    {
        $studentReport = self::generateStudentReport($assessment);
        $officeReport = self::generateOfficeReport($assessment);

        return [
            'student' => $studentReport,
            'office' => $officeReport,
        ];
    }

    /**
     * Generate student report (without marks)
     */
    public static function generateStudentReport(TpAssessment $assessment)
    {
        $reportData = self::prepareStudentReportData($assessment);
        $fileName = self::generateReportFileName($assessment, 'student');
        
        $html = self::renderStudentReportHTML($reportData);
        $path = self::saveReportPDF($html, $fileName);

        // Save report record
        $report = new TpReport();
        $report->assessment_id = $assessment->id;
        $report->report_type = TpReport::TYPE_STUDENT;
        $report->report_file = $fileName;
        $report->file_type = TpReport::FILE_TYPE_PDF;
        $report->save();

        TpAuditLog::logAction(
            TpAuditLog::ACTION_CREATE,
            'report',
            $report->id,
            "Student report generated for assessment {$assessment->id}"
        );

        return [
            'report' => $report,
            'path' => $path,
        ];
    }

    /**
     * Generate office report (with marks)
     */
    public static function generateOfficeReport(TpAssessment $assessment)
    {
        $reportData = self::prepareOfficeReportData($assessment);
        $fileName = self::generateReportFileName($assessment, 'office');

        $html = self::renderOfficeReportHTML($reportData);
        $path = self::saveReportPDF($html, $fileName);

        // Save report record
        $report = new TpReport();
        $report->assessment_id = $assessment->id;
        $report->report_type = TpReport::TYPE_OFFICE;
        $report->report_file = $fileName;
        $report->file_type = TpReport::FILE_TYPE_PDF;
        $report->save();

        TpAuditLog::logAction(
            TpAuditLog::ACTION_CREATE,
            'report',
            $report->id,
            "Office report generated for assessment {$assessment->id}"
        );

        return [
            'report' => $report,
            'path' => $path,
        ];
    }

    /**
     * Prepare data for student report
     */
    private static function prepareStudentReportData(TpAssessment $assessment)
    {
        $assessment->loadRelations(['student', 'supervisor']);
        $scores = $assessment->getScores()->all();

        return [
            'assessment' => $assessment,
            'student' => $assessment->student,
            'supervisor' => $assessment->supervisor,
            'scores' => $scores,
            'showMarks' => false,
        ];
    }

    /**
     * Prepare data for office report
     */
    private static function prepareOfficeReportData(TpAssessment $assessment)
    {
        $assessment->loadRelations(['student', 'supervisor']);
        $scores = $assessment->getScores()->all();

        return [
            'assessment' => $assessment,
            'student' => $assessment->student,
            'supervisor' => $assessment->supervisor,
            'scores' => $scores,
            'showMarks' => true,
        ];
    }

    /**
     * Generate report file name
     */
    private static function generateReportFileName($assessment, $type)
    {
        $student = $assessment->student;
        $timestamp = date('YmdHis');
        return sprintf(
            'TP_Report_%s_%s_%s.pdf',
            $student->registration_number,
            $type,
            $timestamp
        );
    }

    /**
     * Render HTML for student report
     */
    private static function renderStudentReportHTML($data)
    {
        $assessment = $data['assessment'];
        $student = $data['student'];
        $supervisor = $data['supervisor'];
        $scores = $data['scores'];
        $showMarks = $data['showMarks'];

        ob_start();
        ?>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Teaching Practice Assessment Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .section { margin-bottom: 20px; }
                .section-title { background-color: #f0f0f0; padding: 8px 0; font-weight: bold; border-left: 4px solid #0066cc; padding-left: 10px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                th { background-color: #f0f0f0; font-weight: bold; }
                .attainment-be { background-color: #ffcccc; }
                .attainment-ae { background-color: #ffeecc; }
                .attainment-me { background-color: #ccffcc; }
                .attainment-ee { background-color: #ccffff; }
                .total-row { font-weight: bold; background-color: #e0e0e0; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Teaching Practice Assessment Report</h1>
                <p>Date: <?php echo date('d/m/Y'); ?></p>
            </div>

            <div class="section">
                <div class="section-title">Student Information</div>
                <table>
                    <tr>
                        <td><strong>Registration Number:</strong></td>
                        <td><?php echo $student->registration_number; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Student Name:</strong></td>
                        <td><?php echo $student->full_name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>School:</strong></td>
                        <td><?php echo $student->school; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Zone:</strong></td>
                        <td><?php echo $student->zone; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Subject:</strong></td>
                        <td><?php echo $student->learning_area_subject; ?></td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Supervisor Information</div>
                <table>
                    <tr>
                        <td><strong>Supervisor Name:</strong></td>
                        <td><?php echo $supervisor->name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>TP Code:</strong></td>
                        <td><?php echo $supervisor->tp_assigned_code; ?></td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Assessment Results</div>
                <table>
                    <thead>
                        <tr>
                            <th>Competence Area</th>
                            <?php if ($showMarks): ?>
                                <th>Score (out of 10)</th>
                            <?php endif; ?>
                            <th>Attainment Level</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scores as $score): ?>
                            <tr class="attainment-<?php echo strtolower($score->attainment_level); ?>">
                                <td><?php echo $score->rubricArea->area_name; ?></td>
                                <?php if ($showMarks): ?>
                                    <td><?php echo $score->score; ?></td>
                                <?php endif; ?>
                                <td><?php echo TpRubricArea::getAttainmentLevelLabel($score->attainment_level); ?></td>
                                <td><?php echo $score->remarks; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="<?php echo $showMarks ? 3 : 2; ?>">Overall Performance</td>
                            <td><?php echo TpAssessment::getPerformanceLabel($assessment->overall_performance); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Supervisor Remarks</div>
                <p><?php echo nl2br($assessment->supervisor_remarks); ?></p>
            </div>

        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Render HTML for office report (same structure, includes marks)
     */
    private static function renderOfficeReportHTML($data)
    {
        return self::renderStudentReportHTML($data);
    }

    /**
     * Save report as PDF
     */
    private static function saveReportPDF($html, $fileName)
    {
        $uploadPath = Yii::getAlias('@common/web/uploads/reports/');
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filePath = $uploadPath . $fileName;

        // Using simple approach - could be enhanced with mPDF or TCPDF
        file_put_html($html, $filePath);

        return $filePath;
    }
}

/**
 * Helper function to save HTML as PDF (basic implementation)
 * For production, use mPDF or TCPDF library
 */
function file_put_html($html, $filepath)
{
    // This is a placeholder. In production, integrate with mPDF:
    // $mpdf = new \Mpdf\Mpdf();
    // $mpdf->WriteHTML($html);
    // $mpdf->Output($filepath, 'F');
    
    file_put_contents($filepath . '.html', $html);
}
