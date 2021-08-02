<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Implantacao */

$this->title = 'Cadastrar Implantação ';
$this->params['breadcrumbs'][] = ['label' => 'Teste', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teste-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'estadoImplantacao' => $estadoImplantacao,
        'data' => $data,
        'cadastrante' => $cadastrante,
        'horarios' => $horarios
    ]) ?>

</div>