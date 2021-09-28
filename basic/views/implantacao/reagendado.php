<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImplantacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Implantações';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="implantacao-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <script>
        function caracteres(numero, quantidade) {
            if (("" + numero).length == 1) {
                return "0" + numero;
            } else {
                return numero;
            }
        }
    </script>
    <?php
    $EventClick = "
        function(eventClickInfo) {
            if (eventClickInfo.id != null) {
                var a = new Date(eventClickInfo.start);
                
                window.location.href ='" . Url::base() . "/implantacao/reagenda?id='+eventClickInfo.id+" . "'&data='+(a.getFullYear())+'-'+caracteres((a.getMonth()+1),2)+'-'+(a.getDate())+' '+caracteres((a.getHours()+3),2)+':00:00';
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
                'start' => date("Y-m-d")
            ],*/
            //'dayClick' => new \yii\web\JsExpression($JSDayClick),
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