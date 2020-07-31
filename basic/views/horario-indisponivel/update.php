<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HorarioIndisponivel */

$this->title = 'Atualizar Horário Indisponível: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Horários Indisponíveis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="horario-indisponivel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>