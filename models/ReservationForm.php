<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */


namespace app\models;


use yii\base\Model;
use yii\db\Exception;
use Yii;

class ReservationForm extends Model
{
    public $date;
    public $quarter_from;
    public $quarter_to;
    public $riid;
    public $uid;
    public $note;


    /**
     * Metoda vrací validační pravidla pro případnou validaci načítaných parametrů. Metoda je volaná frameworkem automaticky
     * při načítání parametrů.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['date', 'quarter_from', 'quarter_to', 'riid', 'uid'], 'required'],
            [['date'], 'date', 'format' => 'yyyy-mm-dd'],
            [['quarter_from', 'quarter_to', 'riid', 'uid'], 'integer'],
        ];
    }

    /**
     * Metoda vrací popisky pro případné zobrazení v reservation. Metoda je volaná frameworkem automaticky
     * v případě potřeby. V našem případě se zobrazují v rezervačním formuláři.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'date' => 'Datum rezervace',
            'quarter_from' => 'Od',
            'quarter_to' => 'Do',
            'riid' => 'Místnost',
        ];
    }

    /**
     * Metoda validuje všechna pole, poté uloží odpovídající rozsah čtvrthodin do databáze. Pro každou čtvrthodinu jeden záznam.
     * V případě jakékoli chyby vrací false.
     *
     * @return bool
     */
    public function saveReservation()
    {
        if ($this->validate()){
            $values = [];
            for($quarter= $this->quarter_from; $quarter< $this->quarter_to; $quarter++){
                $values[] = [$this->date, $quarter, $this->riid, $this->uid];
            }
            try
            {
                Yii::$app->db->createCommand()->batchInsert('reservation', [
                    'date', 'quarter', 'riid', 'uid',
                ], $values)->execute();
                return true;
            } catch (Exception $e){
                return false;
            }
        } else {
            return false;
        }
    }
}