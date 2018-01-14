<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */

namespace app\controllers;

use app\models\Reservation;
use app\models\ReservationForm;
use app\models\ReservationItem;
use Yii;
use yii\filters\AccessControl;

/**
 * Controller pro rezervační systém. Obsažené akce souvisí se zobrazením a operacemi nad rezervacemi.
 * Většina akcí vyžaduje přihlášeného uživatele.
 *
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */
class ReservationController extends \yii\web\Controller
{

    /**
     * Metoda behaviors povolí vstup na konkretní stránky pro přidělené role uživatelů. Je volaná frameworkem před voláním konkrétní akce.
     * Jde o poděděnou metodu z předka \yii\base\Component.
     * @return array
     */
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


    /**
     * Metoda list vyrenderuje seznam všech rezervací s jejich informacemi.
     * @return string
     */
    public function actionList()
    {
        $model = Reservation::find();
        return $this->render('list', ['model' => $model]);
    }

    /**
     * Metoda view vyrenderuje rezervační tabulku pro jeden den. V případě, že akce nedostane přesné datum, generuje pohled na aktuální den.
     * @param null $date
     * @return string
     */
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

    /**
     * Metoda vytvoří model rezervačního formuláře, který následně uloží nebo zobrazí.
     * V případě ukládání načte data z requestu a následně uloží do databáze. Zobrazí uživateli zprávu o úspěchu či neúspěchu a přesměruje ho do pohledu na rezervační tabulku.
     * V případě zobrazení získá data z databáze o místnostech a předá je spolu s modelem rezervačního formuláře do pohledu, který formulář renderuje.
     * @param string|Date $date Datum rezervace.
     * @param integer $quarter Pořadí čtvrthodiny od začátku dne.
     * @param integer $riid Identifikátor místnosti.
     * @param integer $uid Identifikátor uživatele.
     * @return string|\yii\web\Response
     */
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

    /**
     * Metoda smaže všechny záznamy v databázi o rezervaci na základě data, čtvrthodiny, místnosti a uživatele.
     * @param string|Date $date Datum rezervace.
     * @param integer $quarter Pořadí čtvrthodiny od začátku dne.
     * @param integer $riid Identifikátor místnosti.
     * @param integer $uid Identifikátor uživatele.
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($date, $quarter, $riid, $uid)
    {
        $this->findModel($date, $quarter, $riid, $uid)->delete();
        Yii::$app->session->setFlash('success', "Rezervace úspěšně smazána.");;
        return $this->redirect(['reservation/list']);
    }

    /**
     * Metoda hledá v databázi rezervaci na základě data, čtvrthodiny, místnosti a uživatele.
     * @param string|Date $date Datum rezervace.
     * @param integer $quarter Pořadí čtvrthodiny od začátku dne.
     * @param integer $riid Identifikátor místnosti.
     * @param integer $uid Identifikátor uživatele.
     * @return null|Reservation Vrací instanci objektu Reservation.
     */
    protected function findModel($date, $quarter, $riid, $uid)
    {
        if ($model = Reservation::findOne(['date' => $date, 'quarter' => $quarter, 'riid' => $riid, 'uid' => $uid])) {
            return $model;
        }
    }
}
