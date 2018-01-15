<?php
use yii\helpers\Html;
use app\models\Reservation;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;
$this->title = 'Rezervační systém';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>

<?php

    $reservations_index = ArrayHelper::toArray($reservation);
    $reservations_index = ArrayHelper::index($reservations_index,null,['riid','quarter', 'uid']);

    $hours = [];
    for ($h = 7; $h < 22; $h++)
    {
        $hour = [
            'id' => $h,
            'title' => date('H', mktime($h,0, 0)),
            'quarters' => []
        ];

        for ($q = 0; $q < 4; $q++) {
            $hour['quarters'][] = [
                'id' => ($h * 4) + $q,
                'qno' => $q,
                'title' => date('i', mktime(0,$q * 15, 0)),
            ];
        }

        $hours[] = $hour;
    }

    echo Html::beginTag('div', ['class' => 'table-responsive']);
    echo Html::beginTag('table',['class' => 'table']);

        // hlavicka tabulky
        echo Html::beginTag('thead');

            // hlavicka hodin
            echo Html::beginTag('tr');
                echo Html::tag('th','hodina');
                foreach ($hours as $hour)
                {
                    echo Html::tag('th', $hour['title'],['colspan' => 4]);
                }
            echo Html::endTag('tr');

            // hlavicka ctvrthodin
            echo Html::beginTag('tr');
                echo Html::tag('th','minuta');
                foreach ($hours as $hour)
                {
                    foreach ($hour['quarters'] as $quarter) {
                        echo Html::tag('th', $quarter['title']);
                    }
                }
            echo Html::endTag('tr');

        echo Html::endTag('thead');

    // telo tabulky
    echo Html::beginTag('tbody');

        foreach ($reservation_item as $item)
        {
            echo Html::beginTag('tr');
                // hlavicka s popisem rezervacniho predmetu
                echo Html::tag('th', $item->description);

                // bunky rezervaci
                foreach ($hours as $hour)
                {
                    foreach ($hour['quarters'] as $quarter) {
                        $value = null;

                        if (isset($reservations_index[$item->riid])) {
                            if (isset($reservations_index[$item->riid][$quarter['id']])) {
                                $value = $reservations_index[$item->riid][$quarter['id']];
                            }
                        }

                        $hasCurrentUserReservation = (isset($value[Yii::$app->user->id]));
                        $hasOtherUserReservation = (!$hasCurrentUserReservation && count($value) > 0);

                        $quarterId =$quarter['id'];
                        $riid = $item->riid;
                        $uid = Yii::$app->user->id;

                        $td_class = ['class' => ($hasCurrentUserReservation) ? 'success' : (($hasOtherUserReservation) ? 'danger' : null)];
                        $td_onclick = [];

                        if (isset($uid)) {
                            $td_onclick = ['onClick' => "reserve('$date' ,$quarterId, $riid, $uid)"];
                        }

                        echo Html::tag('td', '', [
                                ArrayHelper::merge($td_class, $td_class)
                        ]);
                    }
                }
            echo Html::endTag('tr');
        }

        echo Html::endTag('tbody');

    echo Html::endTag('table');
    echo Html::endTag('div');

    $this->registerJs("
        function reserve(date, quarterId, riid, uid){
            window.location.href = 'index.php?r=reservation%2Freservation&date='+date+'&quarter='+quarterId+'&riid='+riid+'&uid='+uid;
        }
    ",
        View::POS_HEAD);

?>