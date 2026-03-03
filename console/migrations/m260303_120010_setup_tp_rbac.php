<?php

use yii\db\Migration;

/**
 * Sets up RBAC authorization items for TP Assessment System.
 */
class m260303_120010_setup_tp_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create permissions
        $viewDashboard = $auth->createPermission('tp_view_dashboard');
        $viewDashboard->description = 'View TP Dashboard';
        $auth->add($viewDashboard);

        $createAssessment = $auth->createPermission('tp_create_assessment');
        $createAssessment->description = 'Create new assessment';
        $auth->add($createAssessment);

        $editAssessment = $auth->createPermission('tp_edit_assessment');
        $editAssessment->description = 'Edit assessment';
        $auth->add($editAssessment);

        $submitAssessment = $auth->createPermission('tp_submit_assessment');
        $submitAssessment->description = 'Submit assessment';
        $auth->add($submitAssessment);

        $viewAssessment = $auth->createPermission('tp_view_assessment');
        $viewAssessment->description = 'View assessment';
        $auth->add($viewAssessment);

        $reviewAssessment = $auth->createPermission('tp_review_assessment');
        $reviewAssessment->description = 'Review assessment (Zone Coordinator only)';
        $auth->add($reviewAssessment);

        $validateAssessment = $auth->createPermission('tp_validate_assessment');
        $validateAssessment->description = 'Validate assessment';
        $auth->add($validateAssessment);

        $rejectAssessment = $auth->createPermission('tp_reject_assessment');
        $rejectAssessment->description = 'Reject assessment';
        $auth->add($rejectAssessment);

        $viewReport = $auth->createPermission('tp_view_report');
        $viewReport->description = 'View assessment report';
        $auth->add($viewReport);

        $downloadReport = $auth->createPermission('tp_download_report');
        $downloadReport->description = 'Download assessment report';
        $auth->add($downloadReport);

        $manageStudents = $auth->createPermission('tp_manage_students');
        $manageStudents->description = 'Manage student records';
        $auth->add($manageStudents);

        $manageLecturers = $auth->createPermission('tp_manage_lecturers');
        $manageLecturers->description = 'Manage lecturer records';
        $auth->add($manageLecturers);

        $viewAuditLog = $auth->createPermission('tp_view_audit_log');
        $viewAuditLog->description = 'View audit logs';
        $auth->add($viewAuditLog);

        $viewAnalytics = $auth->createPermission('tp_view_analytics');
        $viewAnalytics->description = 'View analytics and reports';
        $auth->add($viewAnalytics);

        // Create roles
        $supervisor = $auth->createRole('tp_supervisor');
        $supervisor->description = 'Teaching Practice Supervisor';
        $auth->add($supervisor);

        $coordinator = $auth->createRole('tp_coordinator');
        $coordinator->description = 'Zone Coordinator';
        $auth->add($coordinator);

        $tpOffice = $auth->createRole('tp_office');
        $tpOffice->description = 'TP Office';
        $auth->add($tpOffice);

        $departmentChair = $auth->createRole('tp_department_chair');
        $departmentChair->description = 'Department Chair';
        $auth->add($departmentChair);

        // Assign permissions to roles
        // Supervisor role
        $auth->addChild($supervisor, $viewDashboard);
        $auth->addChild($supervisor, $createAssessment);
        $auth->addChild($supervisor, $editAssessment);
        $auth->addChild($supervisor, $submitAssessment);
        $auth->addChild($supervisor, $viewAssessment);
        $auth->addChild($supervisor, $viewReport);
        $auth->addChild($supervisor, $downloadReport);

        // Zone Coordinator role (inherits supervisor permissions + review/validate)
        $auth->addChild($coordinator, $supervisor);
        $auth->addChild($coordinator, $reviewAssessment);
        $auth->addChild($coordinator, $validateAssessment);
        $auth->addChild($coordinator, $rejectAssessment);
        $auth->addChild($coordinator, $viewAuditLog);
        $auth->addChild($coordinator, $viewAnalytics);

        // TP Office role
        $auth->addChild($tpOffice, $viewDashboard);
        $auth->addChild($tpOffice, $viewAssessment);
        $auth->addChild($tpOffice, $viewReport);
        $auth->addChild($tpOffice, $downloadReport);
        $auth->addChild($tpOffice, $manageStudents);
        $auth->addChild($tpOffice, $manageLecturers);
        $auth->addChild($tpOffice, $viewAuditLog);
        $auth->addChild($tpOffice, $viewAnalytics);

        // Department Chair role
        $auth->addChild($departmentChair, $coordinator);
        $auth->addChild($departmentChair, $manageLecturers);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAllRoles();
        $auth->removeAllPermissions();
    }
}
