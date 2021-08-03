<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImplantacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Qualidade';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="qualidade-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $JSDayClick = "
        function(date) {
            var myDate = new Date(date);
            if (myDate.getUTCDay() != 6 && myDate.getUTCDay() != 0) {
                window.location.href ='".Url::base()."/qualidade/create?data='+date.format();
            }
        }"
    ?>

    <?php
    $EventClick = "
        function(eventClickInfo) {
            if (eventClickInfo.id != null) {
                window.location.href ='".Url::base()."/qualidade/view?id='+eventClickInfo.id;
            }
        }"
    ?>

    <?= yii2fullcalendar\yii2fullcalendar::widget([
        'events' => $eventos,
        'clientOptions' => [
            'businessHours' => [
                'daysOfWeek' => [1, 2, 3, 4, 5]
            ],
            /*'validRange' => [
                'start' => '2000-01-01'//date("Y-m-d")
            ],*/
            'dayClick' => new \yii\web\JsExpression($JSDayClick),
            'eventClick' => new \yii\web\JsExpression($EventClick)
        ],
        'options' => [
            'lang' => 'pt',
            'businessHours' => [
                'daysOfWeek' => [1, 2, 3, 4, 5]
            ]
        ]
    ]);
    ?>

</div>