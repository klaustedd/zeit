<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImplantacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Realizados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="implantacao-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cadastrar Usuario', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'razao_social',
            'cnpj',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>