<?php

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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reservation';
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @return \yii\db\ActiveQuery
     */
    public function getRi()
    {
        return $this->hasOne(ReservationItem::className(), ['riid' => 'riid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['uid' => 'uid']);
    }

    public function getUsername()
    {
        return $this->u->username;
    }

    public function getDescription()
    {
        return $this->ri->description;
    }

    public static function quartersToTime($quarters)
    {
        return mktime(0,$quarters*15, 0);
    }

    public function getQuarterTime()
    {
        return self::quartersToTime($this->quarter);
    }

    public static function findByDate($date)
    {
        return self::findAll(
            [
                'date' => $date,
            ]
        );
    }
}

