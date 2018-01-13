<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 13.01.2018
 * Time: 17:54
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



    public function rules()
    {
        return [
            [['date', 'quarter_from', 'quarter_to', 'riid', 'uid'], 'required'],
            [['date'], 'date', 'format' => 'yyyy-mm-dd'],
            [['quarter_from', 'quarter_to', 'riid', 'uid'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'date' => 'Datum rezervace',
            'quarter_from' => 'Od',
            'quarter_to' => 'Do',
            'riid' => 'Místnost',
        ];
    }

    public function persist()
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