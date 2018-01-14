<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */

namespace app\controllers;

use app\models\Theme;
use app\models\UserForm;
use Yii;
use app\models\User;
use app\models\search\SearchUser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilte;
use yii\filters\AccessControl;

/**
 * Controller pro administraci uživatelů. Obsažené akce souvisí se zobrazením a operacemi nad uživateli.
 * Většina akcí vyžaduje přihlášeného administrátora.
 *
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */
class UserController extends Controller
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
                        'actions' => ['index','create', 'update', 'delete','view'],
                        'roles' => ['admin']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'delete', 'view'],
                        'roles' => ['uzivatel'],
                        'matchCallback' => function ($rule, $action) {
                            $id = Yii::$app->request->get('id');
                            if (Yii::$app->user->id ==  $id) {
                                return true;
                            }
                            return false;
                        }
                    ],
                ]
            ],
        ];
    }

    /**
     * Metoda, která vyrenderuje seznam všech zaregistrovaných uživatelů v databázi a jejich informace.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Metoda, která slouží k zobrazení konkrétních informací o uživateli.
     * @param string $id Identifikátor uživatele.
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Metoda Create, která vytvoří nového uživatele v databázi.
     * Po úspěšném zapsání do databáze přijde zpráva o úspěchu a přesměruje nás to na informace o nově založeném uživateli.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveUser()){
                Yii::$app->session->setFlash('success', "Uživatel úspěšně vytvořen.");
                return $this->redirect(['view', 'id' => $model->user->uid]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Metoda, která pozměnuje již existující uživatele.
     * Pokud je uložení dat úspěšné přesměruje zpět na informaci o konkrétním uživateli a vypíše zprávu o úspěšné úpravě.
     * Při neúspěšném uložení dat přijde zpráva o chybě.
     * @param string $id Identifikátor uživatele.
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new UserForm($id);
        $themes = Theme::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveUser()){
                Yii::$app->session->setFlash('success', "Uživatel úspěšně upraven.");
                return $this->redirect(['view', 'id' => $model->uid]);
            }
            else {
                Yii::$app->session->setFlash('danger', "Při ukládání uživatele nastala chyba.");
            }
        }

        return $this->render('update', [
            'model' => $model,
            'themes' => $themes
        ]);
    }

    /**
     * Metoda, která smaže informace o uživateli z databáze.
     * Pokud je smázání uživatele z databáze úspěšné přesměruje zpět na index uživatelů.
     * @param string $id Identifikátor uživatele.
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Najde v databázi uživatele podle identifikátoru uživatele.
     * Pokud není uživatel nalezen přijde zpráva, že požadovaná stránka neexistuje.
     * @param string $id Identifikátor uživatele.
     * @return Vrátí informace o uživateli.
     * @throws NotFoundHttpException Když není uživatel nalezen.
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
