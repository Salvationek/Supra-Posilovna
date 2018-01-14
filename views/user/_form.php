<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$themes_cl = ArrayHelper::toArray($themes, [
            'app\models\Theme' => [
                    'value' => 'tid',
                    'description',
            ]
    ]);
$themes_cl = ArrayHelper::index($themes_cl, 'value');
$themes_cl = ArrayHelper::getColumn($themes_cl, 'description');


?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tid') ->dropDownList($themes_cl) ?>

    <div class="form-group">
        <?= Html::submitButton('Uložit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
