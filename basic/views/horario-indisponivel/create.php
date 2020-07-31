<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HorarioIndisponivel */

$this->title = 'Cadastrar Horário Indisponível';
$this->params['breadcrumbs'][] = ['label' => 'Horários Indisponíveis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="horario-indisponivel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>