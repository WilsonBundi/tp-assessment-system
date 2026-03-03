<?php

namespace common\services;

use common\models\TpNotification;
use common\models\TpAssessment;
use common\models\TpLecturer;
use common\models\User;
use Yii;
use yii\mail\MailerInterface;

/**
 * Service for sending notifications and email alerts
 */
class NotificationService
{
    /**
     * Send assessment submitted notification
     */
    public static function notifyAssessmentSubmitted(TpAssessment $assessment)
    {
        $assessment->loadRelations(['supervisor', 'student']);

        // Notify supervisor
        $message = "Your assessment for {$assessment->student->full_name} has been submitted successfully. "
                 . "Total Score: {$assessment->total_score}/120";

        TpNotification::notify(
            $assessment->supervisor->user_id,
            'Assessment Submitted',
            $message,
            TpNotification::TYPE_SUBMISSION,
            $assessment->id
        );

        // Notify student
        $studentUser = User::find()
            ->where(['like', 'email', $assessment->student->registration_number])
            ->one();

        if ($studentUser) {
            $studentMessage = "Your Teaching Practice assessment has been submitted. "
                            . "Check back for feedback.";

            TpNotification::notify(
                $studentUser->id,
                'Your Assessment Has Been Submitted',
                $studentMessage,
                TpNotification::TYPE_SUBMISSION,
                $assessment->id
            );

            self::sendEmail(
                $studentUser->email,
                'Teaching Practice Assessment Submitted',
                $studentMessage
            );
        }

        // Notify TP Office
        $tpOfficeUsers = TpLecturer::find()
            ->where(['role' => TpLecturer::ROLE_TP_OFFICE])
            ->all();

        foreach ($tpOfficeUsers as $tpOffice) {
            $officeMessage = "New assessment submitted: {$assessment->student->full_name} "
                           . "supervised by {$assessment->supervisor->name}";

            TpNotification::notify(
                $tpOffice->user_id,
                'New Assessment Submitted',
                $officeMessage,
                TpNotification::TYPE_SUBMISSION,
                $assessment->id
            );
        }
    }

    /**
     * Send assessment validated notification
     */
    public static function notifyAssessmentValidated(TpAssessment $assessment)
    {
        $assessment->loadRelations(['supervisor', 'student']);

        // Notify supervisor
        $message = "Assessment for {$assessment->student->full_name} has been validated. "
                 . "Overall Performance: " . TpAssessment::getPerformanceLabel($assessment->overall_performance);

        TpNotification::notify(
            $assessment->supervisor->user_id,
            'Assessment Validated',
            $message,
            TpNotification::TYPE_VALIDATION,
            $assessment->id
        );

        self::sendEmail(
            User::findOne($assessment->supervisor->user_id)->email,
            'Assessment Validated',
            $message
        );

        // Notify student 
        $studentUser = User::find()
            ->where(['like', 'email', $assessment->student->registration_number])
            ->one();

        if ($studentUser) {
            $studentMessage = "Your Teaching Practice assessment has been validated. "
                            . "Your attainment level is: " 
                            . TpAssessment::getPerformanceLabel($assessment->overall_performance);

            TpNotification::notify(
                $studentUser->id,
                'Your Assessment Has Been Validated',
                $studentMessage,
                TpNotification::TYPE_FEEDBACK,
                $assessment->id
            );

            self::sendEmail(
                $studentUser->email,
                'Your Assessment Has Been Validated',
                $studentMessage
            );
        }
    }

    /**
     * Send assessment rejected notification
     */
    public static function notifyAssessmentRejected(TpAssessment $assessment)
    {
        $assessment->loadRelations(['supervisor', 'student']);

        $message = "Assessment for {$assessment->student->full_name} has been rejected and requires revision.";

        TpNotification::notify(
            $assessment->supervisor->user_id,
            'Assessment Rejected',
            $message,
            TpNotification::TYPE_REJECTION,
            $assessment->id
        );

        self::sendEmail(
            User::findOne($assessment->supervisor->user_id)->email,
            'Assessment Rejected - Revision Required',
            $message
        );
    }

    /**
     * Send email notification
     */
    public static function sendEmail($to, $subject, $body)
    {
        try {
            Yii::$app->mailer->compose()
                ->setTo($to)
                ->setFrom(Yii::$app->params['senderEmail'] ?? 'noreply@university.edu')
                ->setSubject($subject)
                ->setTextBody($body)
                ->send();

            return true;
        } catch (\Exception $e) {
            Yii::warning("Failed to send email to {$to}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unread notifications count for user
     */
    public static function getUnreadCount($userId)
    {
        return TpNotification::find()
            ->where(['user_id' => $userId, 'is_read' => false])
            ->count();
    }

    /**
     * Get recent notifications for user
     */
    public static function getRecentNotifications($userId, $limit = 10)
    {
        return TpNotification::find()
            ->where(['user_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($limit)
            ->all();
    }
}
