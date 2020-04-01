<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EstadoImplantacao */

$this->title = 'Cadastrar Estado de Implantação';
$this->params['breadcrumbs'][] = ['label' => 'Estados de Implantacão', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estado-implantacao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
