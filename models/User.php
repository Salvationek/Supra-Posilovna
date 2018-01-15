<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\Theme;
use Yii;

/**
 * Třída reprezentuje jednotlivé záznamy tabulky user. Obsahuje filtrační a vyhledávací metody nad touto tabulkou.
 * Stará se o kryptování uživatelského hesla. Ke kryptování hesel a jejich validaci používá základní třídu yii\base\Security.
 * Jde o potomka ActiveRecord, je tedy využitelná na všech místech aplikace, kde se jeho přítomnost vyžaduje.
 * @package app\models
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Statická metoda volaná z frameworku. Díky této metodě framework ví, jak se jmenuje tabulka, se kterou třída pracuje.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'user';
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
            [['username', 'password'], 'required'],
            ['email', 'email']
        ];
    }

    /**
     * Metoda vrací popisky pro případné zobrazení ve view. Metoda je volaná frameworkem automaticky
     * v případě potřeby. V našem případě tuto vlastnost obstarávají modely formulářů. Jediné místo, kde se tedy tyto
     * popisky využijí, je GridView pro výpis uživatelů.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Identifikátor uživatele',
            'email' => 'Emailová adresa',
            'username' => 'Uživatelské jméno',
            'active' => 'Aktivní',
            'password' => 'Heslo',
            'auth_key' => 'Autorizační klíč',
            'access_token' => 'Přístupový token',
        ];
    }

    /**
     * Metoda vrací ActiveRecord reprezentující nalezeného uživatele. Je členem interface IdentityInterface,
     * který tato třída implementuje.
     *
     * @param integer $id Identifikátor uživatele
     *
     * @return ActiveRecord|null
     */
    public static function findIdentity($id)
    {
        return self::findOne(['uid' => $id]);
    }

    /**
     * Metoda hledá uživatele podle access tokenu. Tuto funkcionalitu nevyužíváme, nicméně bylo nutné ji implementovat,
     * jelikož je členem interface IdentityInterface, který tato třída implementuje.
     *
     * @param mixed $token hledaný token
     * @param mixed $type typ tokenu (záleží na implementaci)
     *
     * @return IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Metoda hledá uživatele podle jeho username.
     *
     * @param string $username Uživatelské jméno
     * @return ActiveRecord|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * Vrací user ID, podle kterého dokážeme identifikovat konkrétního uživatele. Díky vlastnostem frameworku lze
     * snadno z venčí přistupovat k jeho hodnotě. Stačí objektu zavolat propertu "id" ($objekt->id) a dojde k zavolání
     * této metody.
     * Je členem interface IdentityInterface, který tato třída implementuje.
     * @return string|int uid Identifikátor uživatele.
     */
    public function getId()
    {
        return $this->uid;
    }

    /**
     * Vrací klíč, který lze použít ke kontrole platnosti daného identifikačního čísla.
     * Klíč by měl být jedinečný pro každého uživatele a měl by být trvalý
     * aby bylo možné ověřit platnost totožnosti uživatele.
     * Prostor těchto klíčů by měl být dostatečně velký, aby porazil případné útoky na identitu.
     * Je zapotřebí, pokud je aktivována funkce [[User::enableAutoLogin]]
     * Je členem interface IdentityInterface, který tato třída implementuje.
     *
     * @return string klíč, který slouží ke kontrole platnosti daného identifikačního čísla.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Ověří zadaný autorizační klíč.
     * Je členem interface IdentityInterface, který tato třída implementuje.
     *
     * @param string $authKey zadaný klíč auth
     * @return bool vrací zda je daný klíč auth platný
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Metoda použije heslo předané parametrem v kryptovací funkci a jako salt použije uložený hash. Pokud se výsledek
     * shoduje s puvodním hashem, heslo je správné.
     *
     * @see User::setPassword
     * @param string $password Heslo k validaci.
     * @return bool True nebo False podle toho jestli heslo sedí.
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }


    /**
     * Metoda setPassword zakryptuje heslo pomocí bcrypt za pomoci náhodného saltu a výsledek uloží do třídní proměnné password.
     * Níže jsou uvedené funkce, které framework využívá ke kryptování hesel.
     * @see http://php.net/manual/en/function.crypt.php
     * @see http://php.net/manual/en/function.password-hash.php
     * @param $password Heslo
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Metoda vrací identifikátor téma.
     * @return \yii\db\ActiveQuery
     */
    public function getTheme()
    {
        return $this->hasOne(Theme::className(), ['tid' => 'tid']);
    }
}