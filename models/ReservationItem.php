<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reservation_item".
 *
 * @property string $riid
 * @property string $description
 *
 * @property Reservation[] $reservations
 */
class ReservationItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reservation_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'riid' => 'Riid',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReservations()
    {
        return $this->hasMany(Reservation::className(), ['riid' => 'riid']);
    }
}
