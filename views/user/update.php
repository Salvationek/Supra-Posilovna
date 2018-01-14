<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Upravit uživatele';
$this->params['breadcrumbs'][] = ['label' => 'Uživatelé', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->uid]];
$this->params['breadcrumbs'][] = 'Upravit';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'themes' => $themes,
    ]) ?>

</div>
