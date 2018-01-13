<?php

namespace app\controllers;

use app\models\Reservation;
use app\models\ReservationForm;
use app\models\ReservationItem;
use Yii;
use yii\filters\AccessControl;

class ReservationController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['list', 'reservation','view'],
                        'roles' => ['admin']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view','reservation' ],
                        'roles' => ['uzivatel'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['?'],
                    ],
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        $model = Reservation::find();
        return $this->render('list', ['model' => $model]);
    }

    public function actionView($date = null)
    {
        if ($date === null)
        {
             $date = date('Y-m-d' );
        }
        $reservation = Reservation::findByDate($date);
        $reservation_item = ReservationItem::find()->all();
        return $this->render('view', [
            'date' => $date,
            'reservation' => $reservation,
            'reservation_item' => $reservation_item,
        ]);

    }

    public function actionReservation($date = null, $quarter = null, $riid = null, $uid = null)
    {
        $reservation = new ReservationForm();
        $reservation->date=(isset($date)? $date: date('Y-m-d'));
        $reservation->quarter_from=(isset($quarter)? $quarter: null);
        $reservation->quarter_to=(isset($quarter)? $quarter + 1: null);
        $reservation->riid=$riid;
        $reservation->uid=(isset($uid)? $uid: Yii::$app->user->id);
        if ($reservation->load(Yii::$app->request->post())) {
            if ($reservation->persist()) {
                Yii::$app->session->setFlash('success', "Rezervace úspěšně uložena");
            } else {
                Yii::$app->session->setFlash('danger', "Chyba při ukládání rezervace.");
            }
            return $this->redirect(['reservation/view']);
        } else {
            $reservation_item = ReservationItem::find()->all();
            return $this->render('reservation', [
                'reservation' => $reservation,
                'reservation_item' => $reservation_item,
            ]);
        }
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
