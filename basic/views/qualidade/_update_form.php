<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Implantacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="implantacao-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'validateOnChange' => false,
        'validateOnBlur' => false
    ]); ?>

    <?= $form->field($model, 'responsavel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'atendente_id')->dropDownList(
        $atendentes,
        ['prompt' => 'Selecione...']
    ) ?>

    <?= $form->field($model, 'email_responsavel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'razao_social')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj')->textInput() ?>

    <?= $form->field($model, 'cota_xml')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'cota_ged')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'cota_bipagem')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'comentario')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'estado_implantacao_id')->dropDownList(
        $estadoQualidade
    ) ?>

    <?= $form->field($model, 'data')->textInput(['type' => 'date']) ?>



    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>