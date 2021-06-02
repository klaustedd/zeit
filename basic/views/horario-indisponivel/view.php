<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\HorarioIndisponivel */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Horários Indisponíveis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="horario-indisponivel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem certeza que quer deletar essa indisponibilidade?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label'  => 'Data Inicio',
                'value'  => DateTime::createFromFormat('Y-m-d H:i:s', $model->data_inicio)->format('d/m/Y H:i:s'),
            ],
            [
                'label'  => 'Data Fim',
                'value'  => DateTime::createFromFormat('Y-m-d H:i:s', $model->data_fim)->format('d/m/Y H:i:s'),
            ],
            'motivo',
        ],
    ]) ?>

</div>