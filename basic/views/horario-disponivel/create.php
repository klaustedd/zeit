<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HorarioDisponivel */

$this->title = 'Cadastrar Horário Disponível';
$this->params['breadcrumbs'][] = ['label' => 'Horários Disponíveis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="horario-disponivel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>