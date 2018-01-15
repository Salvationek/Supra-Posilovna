<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */
namespace app\models;

use Yii;

/**
 * This is the model class for table "reservation".
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
     * Metoda vrací identifikátor místnosti.
     * @return \yii\db\ActiveQuery
     */
    public function getRi()
    {
        return $this->hasOne(ReservationItem::className(), ['riid' => 'riid']);
    }

    /**
     * Metoda, která vrací identifikátor uživatele.
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['uid' => 'uid']);
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->u->username;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->ri->description;
    }

    /**
     * @param $quarters
     * @return false|int
     */
    public static function quartersToTime($quarters)
    {
        return mktime(0,$quarters*15, 0);
    }

    /**
     * @return false|int
     */
    public function getQuarterTime()
    {
        return self::quartersToTime($this->quarter);
    }

    /**
     * @param $date
     * @return static[]
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

