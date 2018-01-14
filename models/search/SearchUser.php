<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * Model pro filtraci uživatelů založený na třídě User. Používá se v UserControlleru pro výpis uživatelů.
 */
class SearchUser extends User
{
    /**
     * Metoda vygenerovaná modulem GII. Vrací validační parametry pro vstupní pole filtru.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['uid', 'active'], 'integer'],
            [['username', 'email', 'auth_key', 'password', 'access_token'], 'safe'],
        ];
    }

    /**
     * Metoda vygenerovaná modulem GII. Obchází scénáře základní třídy User.
     *
     * @return array
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Metoda která prohledává Usery v databázi dle parametrů.
     * Zadáme parametr, v databázi se tento parametr vyhledává, následně se vypíší všechny uživatelé, kteří se shodují s hledaným parametrem.
     *
     * @param array $params Pole parametrů, které metoda použije pro tvorbu Query
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        /**
         * Vyfiltruje parametry z GRIDu - uid a active
         */
        $query->andFilterWhere([
            'uid' => $this->uid,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'access_token', $this->access_token]);

        return $dataProvider;
    }
}
