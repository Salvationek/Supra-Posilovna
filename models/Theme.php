<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */

namespace app\models;

use Yii;

/**
 * Model pro tabulku "theme" v databázi.
 *
 * @property int $tid Identifikátor téma.
 * @property string $value Proměnná, která má v sobě název cílového css souboru.
 * @property string $description Popis tématu.
 *
 * @property User[] $users
 */
class Theme extends \yii\db\ActiveRecord
{
    /**
     * Statická metoda volaná z frameworku. Díky této metodě framework ví, jak se jmenuje tabulka, se kterou třída pracuje.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'theme';
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
            [['value'], 'required'],
            [['value', 'description'], 'string', 'max' => 1024],
        ];
    }

    /**
     * Metoda vrací popisky pro případné zobrazení. Metoda je volaná frameworkem automaticky
     * v případě potřeby. V našem případě popisky nevyužíváme.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'tid' => 'Tid',
            'value' => 'Value',
            'description' => 'Description',
        ];
    }
}
