<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */


namespace app\models;


use yii\base\Model;
use yii\db\Exception;
use Yii;

class RegistrationForm extends Model
{
    public $user;
    public $uid;
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    public function __construct($uid = null) {
        if (isset($uid)) {
            $this->user = User::findOne($uid);
            $this->loadUser();
        } else {
            $this->user = new User();
        }
    }

    /**
     * Metoda vrací validační pravidla pro případnou validaci načítaných parametrů. Metoda je volaná frameworkem automaticky
     * při načítání parametrů.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'password', 'password_repeat'], 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User',
                'message' => 'Uživatel s tímto jménem již existuje!',
                'when' => function ($model, $attribute) {
                    return $model->username !== $this->user->username;
                }
            ],
            [['password'], 'string', 'min' => 6],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message' => 'Hesla se neshodují' ],
            [['email'], 'email']
        ];
    }

    /**
     * Metoda vrací popisky pro případné zobrazení v registration. Metoda je volaná frameworkem automaticky
     * v případě potřeby. V našem případě se zobrazují v registračním formuláři.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Identifikátor uživatele',
            'email' => 'Emailová adresa',
            'username' => 'Uživatelské jméno',
            'password' => 'Heslo',
            'password_repeat' => 'Kontrola hesla',
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function saveUser()
    {
        if ($this->validate()){
            try
            {
                $this->user->username = $this->username;
                $this->user->email = $this->email;
                $this->user->setPassword($this->password);
                if ($this->user->save(false)) {
                    $auth = Yii::$app->authManager;
                    $auth->revokeAll($this->user->uid);
                    $auth->assign($auth->getRole('uzivatel'), $this->user->uid);
                    return true;
                }
                else {
                    return false;
                }
            } catch (Exception $e){
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     *
     */
    public function loadUser() {
        $this->uid = $this->user->uid;
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->password = '';
        $this->password_repeat = '';
    }
}