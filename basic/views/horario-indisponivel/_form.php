<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HorarioIndisponivel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="horario-indisponivel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'data_inicio')->widget(\janisto\timepicker\TimePicker::className(), [
        'language' => 'pt',
        'mode' => 'datetime',
        'clientOptions' => [
            'dateFormat' => 'dd/mm/yy',
            'timeFormat' => 'HH:mm:ss',
            'showSecond' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'data_fim')->widget(\janisto\timepicker\TimePicker::className(), [
        'language' => 'pt',
        'mode' => 'datetime',
        'clientOptions' => [
            'dateFormat' => 'dd/mm/yy',
            'timeFormat' => 'HH:mm:ss',
            'showSecond' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'motivo')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>