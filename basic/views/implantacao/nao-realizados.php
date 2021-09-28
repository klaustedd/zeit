<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImplantacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Busca Avançada';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="implantacao-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        //'model' => $model,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'razao_social',
            'data',
            [
                'attribute' =>
                'estado_implantacao_id',
                'value' => 'estado_implantacao_id',
                'filter' => Html::activeDropDownList($searchModel, 'estado_implantacao_id', $estadoImplantacao, ['class' => 'form-control', 'prompt' => 'Selecione um Estado'])
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>