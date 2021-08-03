<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Implantacao */

$this->title = 'Atualizar Implantação: ' . $model->razao_social;
$this->params['breadcrumbs'][] = ['label' => 'Qualidade', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->razao_social, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="implantacao-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update_form', [
        'model' => $model,
        'atendentes' => $atendentes,
        'estadoQualidade' => $estadoQualidade
        
    ]) ?>

</div>
