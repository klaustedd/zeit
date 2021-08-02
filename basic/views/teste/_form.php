<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Implantacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="implantacao-form">

    <?php $form = ActiveForm::begin(); ?>

    <div id="data-agenda">
        <?= "Agendamento de implantação para: <span>" . Yii::$app->formatter->format($data, 'date') . "</span>" ?>
    </div>
    <?= Html::error($model, 'data', ['class' => 'error-summary-nopd']); ?>

    <?= $form->field($model, 'hora')->dropDownList(
        $horarios
    ) ?>

    <?= $form->field($model, 'responsavel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cadastrante_id')->dropDownList(
        $cadastrante,
        ['readonly' => true]
    ) ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email_responsavel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'razao_social')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cota_xml')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'cota_ged')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'cota_bipagem')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'vez')->textInput(['type' => 'number', 'min' => '0', 'max' => '7']) ?>

    <?= $form->field($model, 'comentario')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'data')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>