<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Model pro formulář, který slouží k přihlášení uživatelů.
 *
 * @property User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * Metoda vrací validační pravidla pro případnou validaci načítaných parametrů. Metoda je volaná frameworkem automaticky
     * při načítání parametrů.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Metoda vrací popisky pro případné zobrazení v loginu. Metoda je volaná frameworkem automaticky
     * v případě potřeby. V našem případě se zobrazují v přihlašovacím formuláři.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Uživatelské jméno',
            'password' => 'Heslo',
            'rememberMe' => 'Zapamatuj si mě',
        ];
    }

    /**
     * Metoda použije heslo předané parametrem v kryptovací funkci a jako salt použije uložený hash. Pokud se výsledek
     * shoduje s puvodním hashem, heslo je správné.
     *
     * @param string $attribute Již zvalidovaný atribut.
     * @param array $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Špatné uživatelské jméno nebo heslo.');
            }
        }
    }

    /**
     * Přihlásí uživatele pomocí odpovídajícího uživatelského jména a hesla.
     * @return bool Buď je uživatel úspěšně přihlášen, nebo ne.
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Metoda, která hledá uživatele v databázi podle uživatelského jména. Jako uživatelské jméno použije aktuálně vyplněné jméno.
     *
     * @return User|null
     */
    private function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
