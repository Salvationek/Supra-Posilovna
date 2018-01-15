<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */
namespace app\models;

use Yii;

/**
 * Model reprezentující záznam tabulky reservation. Jde o potomka ActiveRecord.
 *
 * @property string $date datum rezervace
 * @property int $quarter počet čtvrthodin od začátku dne
 * @property string $riid předmět rezervace
 * @property string $uid identifikátor uživatele
 * @property string $note poznámka k rezervaci
 *
 * @property ReservationItem $ri
 * @property User $u
 */
class Reservation extends \yii\db\ActiveRecord
{
    /**
     * Statická metoda volaná z frameworku. Díky této metodě framework ví, jak se jmenuje tabulka, se kterou třída pracuje.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'reservation';
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
            [['date', 'quarter', 'riid', 'uid'], 'required'],
            [['date'], 'safe'],
            [['quarter', 'riid', 'uid'], 'integer'],
            [['note'], 'string', 'max' => 255],
            [['date', 'quarter', 'riid', 'uid'], 'unique', 'targetAttribute' => ['date', 'quarter', 'riid', 'uid']],
            [['riid'], 'exist', 'skipOnError' => true, 'targetClass' => ReservationItem::className(), 'targetAttribute' => ['riid' => 'riid']],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'uid']],
        ];
    }

    /**
     * Metoda vrací popisky pro případné zobrazení ve view. Metoda je volaná frameworkem automaticky
     * v případě potřeby. V našem případě se zobrazují v rezervačním systému.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'date' => 'datum rezervace',
            'quarter' => 'počet čtvrthodin od začátku dne',
            'riid' => 'předmět rezervace',
            'uid' => 'identifikátor uživatele',
            'note' => 'poznámka k rezervaci',
            'username' => 'rezervující',
            'description' => 'místnost',
            'quartertime' => 'čas rezervace',
        ];
    }

    /**
     * Metoda vrací připravené Query pro konkrétní rezervační položku. Snadno se tak můžeme dotázat například na popisek položky.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRi()
    {
        return $this->hasOne(ReservationItem::className(), ['riid' => 'riid']);
    }

    /**
     * Metoda vrací připravené Query pro konkrétního uživatele. Snadno se tak můžeme dotázat například na jméno uživatele.
     *
     * @return \yii\db\ActiveQuery
     */

    public function getU()
    {
        return $this->hasOne(User::className(), ['uid' => 'uid']);
    }

    /**
     * Metoda zjistí uživatelské jméno z aktuální rezervace.
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->u->username;
    }

    /**
     * Metoda zjistí jméno místnosti z aktuální rezervace.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->ri->description;
    }

    /**
     * Převede libovolný identifikátor čtvrthodiny na čas.
     * @param $quarters
     * @return false|int
     */
    public static function quartersToTime($quarters)
    {
        return mktime(0,$quarters*15, 0);
    }

    /**
     * Převede identifikátor čtvrthodiny v objektu na čas.
     * @return false|int
     */
    public function getQuarterTime()
    {
        return self::quartersToTime($this->quarter);
    }

    /**
     * Metoda hledá v databázi všechny rezervace na zadané datum.
     * @param $date Datum rezervace
     * @return static[]|null
     */
    public static function findByDate($date)
    {
        return self::findAll(
            [
                'date' => $date,
            ]
        );
    }
}

