<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Horários Indisponíveis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="horario-indisponivel-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cadastrar Horário Indisponível', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label'  => 'Data Inicio',
                'value'  => function ($model) {
                    return DateTime::createFromFormat('Y-m-d H:i:s', $model->data_inicio)->format('d/m/Y H:i:s');
                }
            ],
            [
                'label'  => 'Data Fim',
                'value'  => function ($model) {
                    return DateTime::createFromFormat('Y-m-d H:i:s', $model->data_fim)->format('d/m/Y H:i:s');
                }
            ],
            'motivo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>