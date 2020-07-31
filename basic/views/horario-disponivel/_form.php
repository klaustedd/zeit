<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HorarioDisponivel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="horario-disponivel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'horario')->widget(\janisto\timepicker\TimePicker::className(), [
        //'language' => 'fi',
        'mode' => 'time',
        'clientOptions' => [
            'timeFormat' => 'HH:mm:ss',
            'showSecond' => true,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>