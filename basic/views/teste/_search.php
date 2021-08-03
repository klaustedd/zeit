<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ImplantacaoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="implantacao-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'data') ?>

    <?= $form->field($model, 'responsavel') ?>

    <?= $form->field($model, 'telefone') ?>

    <?= $form->field($model, 'cadastrante_id') ?>

    <?php // echo $form->field($model, 'atendente_id') ?>

    <?php // echo $form->field($model, 'email_responsavel') ?>

    <?php // echo $form->field($model, 'celular') ?>

    <?php // echo $form->field($model, 'razao_social') ?>

    <?php // echo $form->field($model, 'cnpj') ?>

    <?php // echo $form->field($model, 'comentario') ?>

    <?php // echo $form->field($model, 'estado_implantacao_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
