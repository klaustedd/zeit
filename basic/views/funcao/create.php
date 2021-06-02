<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Funcao */

$this->title = 'Cadastrar Função';
$this->params['breadcrumbs'][] = ['label' => 'Funcoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="funcao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
