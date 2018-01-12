<?php

namespace app\controllers;

use app\models\Reservation;
use Yii;

class ReservationController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        $model = Reservation::find();
        return $this->render('list', ['model' => $model]);
    }

    public function actionView()
    {
        return $this->render('view');
    }

    public function actionDelete($date, $quarter, $riid, $uid)
    {
        $this->findModel($date, $quarter, $riid, $uid)->delete();
        Yii::$app->session->setFlash('success', "Rezervace úspěšně smazána.");;
        return $this->redirect(['reservation/list']);
    }

    protected function findModel($date, $quarter, $riid, $uid)
    {
        if ($model = Reservation::findOne(['date' => $date, 'quarter' => $quarter, 'riid' => $riid, 'uid' => $uid])) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
