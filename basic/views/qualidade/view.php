<?php

use app\models\Usuario;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Implantacao */

$this->title = $model->razao_social;
$this->params['breadcrumbs'][] = ['label' => 'Qualidade', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="implantacao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (
            !Usuario::isRole(['Vendedor'], Yii::$app->user->identity) ||
            (Usuario::isRole(['Vendedor'], Yii::$app->user->identity) && $model->cadastrante_id == Yii::$app->user->identity->id)
        ) { ?>
            <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Quer mesmo apagar essa implantação?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        //'vez',
        'attributes' => [
            'id',
            'vez',
            'data',
            'responsavel',
            'telefone',
            'nome',
            [
                'label' => 'Cadastrante',
                'value' => $model->cadastrante->nome,
            ],
            [
                'label' => 'Atendente',
                'value' => $model->atendente != null ? $model->atendente->nome : "",
            ],
            'email_responsavel:email',
            'celular',
            'razao_social',
            'cnpj',
            'cota_xml',
            'cota_bipagem',
            'cota_ged',
            'comentario:ntext',
            [
                'label' => 'Estado Atual',
                'value' => $model->estadoImplantacao->nome,
            ]
        ],
    ]) ?>

</div>