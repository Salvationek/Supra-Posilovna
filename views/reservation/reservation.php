<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ReservationForm */
/* @var $form ActiveForm */


    $code_list = ArrayHelper::toArray($reservation_item, [
            'app\models\ReservationItem' => [
                    'riid',
                    'description',
            ]
    ]);
$code_list = ArrayHelper::index($code_list, 'riid');
$code_list = ArrayHelper::getColumn($code_list, 'description');
    array_unshift($code_list, null);

    $time = [];
    for ($h = 7; $h < 22; $h++)
    {
        for ($q = 0; $q < 4; $q++) {
            $quarter = ($h * 4) + $q;
            $time[$quarter] = date('H:i', mktime(0,$quarter * 15, 0));
        }
    }

?>

<div class="reservation-reservation">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($reservation, 'date')->widget(
            DatePicker::className(), [
            'name' => 'date',
            'options' => ['placeholder' => 'Vyberte datum ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ]); ?>
        <?= $form->field($reservation, 'quarter_from') ->dropDownList($time) ?>
        <?= $form->field($reservation, 'quarter_to') ->dropDownList($time) ?>
        <?= $form->field($reservation, 'riid' ) ->dropDownList($code_list) ?>
        <?= $form->field($reservation, 'uid') -> hiddenInput() -> label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- reservation-reservation -->
