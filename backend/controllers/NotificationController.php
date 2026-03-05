<?php

namespace backend\controllers;

use common\models\TpNotification;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * NotificationController handles notification management
 */
class NotificationController extends Controller
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
                        'actions' => ['index', 'view', 'mark-read', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * List notifications for current user
     */
    public function actionIndex()
    {
        $query = TpNotification::find()->where(['user_id' => Yii::$app->user->id]);

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
     * View notification
     */
    public function actionView($id)
    {
        $notification = $this->findNotification($id);

        // Mark as read
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return $this->render('view', [
            'notification' => $notification,
            'assessment' => $notification->assessment,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function actionMarkRead($id)
    {
        $notification = $this->findNotification($id);
        $notification->markAsRead();

        if (Yii::$app->request->isAjax) {
            return json_encode(['status' => 'success']);
        }

        return $this->redirect(['index']);
    }

    /**
     * Delete notification
     */
    public function actionDelete($id)
    {
        $notification = $this->findNotification($id);
        $notification->delete();

        Yii::$app->session->setFlash('success', 'Notification deleted');
        return $this->redirect(['index']);
    }

    /**
     * Get unread count (AJAX)
     */
    public function actionGetUnreadCount()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $count = TpNotification::find()
            ->where(['user_id' => Yii::$app->user->id, 'is_read' => false])
            ->count();

        return ['unread_count' => $count];
    }

    /**
     * Find notification model
     */
    protected function findNotification($id)
    {
        $notification = TpNotification::findOne([
            'id' => $id,
            'user_id' => Yii::$app->user->id,
        ]);

        if (!$notification) {
            throw new NotFoundHttpException('Notification not found');
        }

        return $notification;
    }
}
