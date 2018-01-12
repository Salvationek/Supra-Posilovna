<?php
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;

$this->title = 'Seznam rezervací';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?php
$dataProvider = new ActiveDataProvider([
    'query' => $model,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'date:date',
            [
                    'attribute' => 'quartertime',
                    'format' => ['time', 'php:H:i'],
            ],
            'username',
            'description',
            [
                    'class' => ActionColumn::className(),
                'template' => '{delete}',
            ]
        ]
    ]

)
?>