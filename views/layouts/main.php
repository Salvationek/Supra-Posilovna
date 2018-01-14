<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
<?php
    $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/favicon.png']);
    $this->head();
?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Úvod', 'url' => ['/site/index']],
            ['label' => 'PhpDoc', 'url' => 'doc'],
            ['label' => 'Uživatelé', 'url' => ['/user/index'], 'visible' => Yii::$app->user->can('admin')],
            ['label' => 'Registrace', 'url' => ['/site/registration'], 'visible' => Yii::$app->user->isGuest],
            ['label' => 'Správa rezervací', 'url' => ['/reservation/list'], 'visible' => Yii::$app->user->can('admin')],
            ['label' => 'Upravit účet', 'url' => ['/user/update', 'id' => Yii::$app->user->id], 'visible' => !Yii::$app->user->isGuest],
            ['label' => 'Rezervační systém', 'url' => ['/reservation/view']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Přihlásit', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Odhlásit (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container mainblock">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
