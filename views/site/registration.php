<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Registrovat nového uživatele';
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\models\RegistrationForm */
/* @var $form ActiveForm */
?>
<div class="site-registration">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'email')-> input('email') ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Registrovat', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-registration -->
