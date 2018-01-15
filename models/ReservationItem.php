<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */

namespace app\models;

use Yii;

/**
 * Toto je model pro tabulku "reservation_item" v databázi.
 *
 * @property string $riid Identifikátor místnosti.
 * @property string $description Název místnosti.
 *
 * @property Reservation[] $reservations
 */
class ReservationItem extends \yii\db\ActiveRecord
{
    /**
     * Statická metoda volaná z frameworku. Díky této metodě framework ví, jak se jmenuje tabulka, se kterou třída pracuje.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'reservation_item';
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
            [['description'], 'required'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * Metoda vrací popisky pro případné zobrazení. Metoda je volaná frameworkem automaticky
     * v případě potřeby. V našem případě tyto popisky nevyužíváme.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'riid' => 'Riid',
            'description' => 'Description',
        ];
    }
}
