<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */

namespace app\controllers;

use app\models\RegistrationForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

/**
 * Controller pro základní funkcionalitu webu. Většina obsažených akcí nevyžaduje přihlášení.
 *
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */
class SiteController extends Controller
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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Implementace virtuální metody yii\base\Controller, která se stará o spouštění externích akcí. V našem případě implementuje třídu pro správné vykreslení chyb.
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Metoda index, která vyrenderuje domovskou stránku webu.
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Metoda pro přihlášení uživatelů. Porovná zadaného uživatele se záznamy v DB. V případě shody s DB uživatele zaloguje.
     * Uživatelské heslo je kryptováno standartními metodami Yii2 frameworku. Bližší informace v User::login().
     * Po uspěšném přihlášení uživatele přesměruje na homepage.
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', "Uživatel úspěšně přihlášen.");
            return $this->redirect(['site/index']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Metoda odhlásí přihlášeného uživatele a přesměruje ho na domovskou stránku.
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Metoda vytvoří model registračního formuláře, který následně uloží nebo zobrazí.
     * V případě ukládání načte data z postu requestu a následně uloží do databáze. Zobrazí uživateli zprávu o úspěchu či neúspěchu a přesměruje ho na login.
     * V případě zobrazení vyrenderuje registrační formulář.
     * Formulář validuje podle pravidel nastavených v metodě SiteController::behaviors. V případě chyb zbarví hodnoty, které nesplňují podmínky.
     *
     * @return Response|string
     */

    public function actionRegistration() {
        $model = new RegistrationForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveUser()){
                Yii::$app->session->setFlash('success', "Uživatel úspěšně zaregistrován.");
                return $this->redirect(['login', 'username' => $model->user->username]);
            }
        }

        return $this->render('registration', [
            'model' => $model,
        ]);
    }
}