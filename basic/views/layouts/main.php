<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Usuario;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                Usuario::isRole(['Administrador'], Yii::$app->user->identity) ||  Usuario::isRole(['Agente de Suporte'], Yii::$app->user->identity) ? ['label' => 'Qualidade', 'url' => ['/qualidade']] : '',
                !Yii::$app->user->isGuest ? ['label' => 'Implantações', 'url' => ['/implantacao']] : '',
                Usuario::isRole(['Administrador'], Yii::$app->user->identity) ? ['label' => 'Funções', 'url' => ['/funcao']] : '',
                Usuario::isRole(['Administrador'], Yii::$app->user->identity) ? ['label' => 'Usuários', 'url' => ['/usuario']] : '',
                Usuario::isRole(['Administrador'], Yii::$app->user->identity) ? ['label' => 'Estados de Implantação', 'url' => ['/estado-implantacao']] : '',
                Usuario::isRole(['Administrador'], Yii::$app->user->identity) ? ['label' => 'Horários Disponíveis', 'url' => ['/horario-disponivel']] : '',
                Usuario::isRole(['Administrador'], Yii::$app->user->identity) ? ['label' => 'Horários Indisponíveis', 'url' => ['/horario-indisponivel']] : '',
                Yii::$app->user->isGuest ? (['label' => 'Login', 'url' => ['/site/login']]) : ('<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->nome . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>')
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Klaus Fiscal LTDA <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>