<?php

namespace app\controllers;

use app\models\EstadoImplantacao;
use app\models\EstadoQualidade;
use app\models\HorarioIndisponivel;
use Yii;
use app\models\Qualidade;
use app\models\ImplantacaoSearch;
use app\models\Usuario;
use DateTime;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\rbac\BaseManager;
use yii\web\ForbiddenHttpException;
use yii\base\Model;
use yii\web\HttpException;

/**
 * ImplantacaoController implements the CRUD actions for Implantacao model.
 */
class QualidadeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'update', 'delete', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Usuario::isRole(['Administrador'], Yii::$app->user->identity) || Usuario::isRole(['Agente de Suporte'], Yii::$app->user->identity);
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Implantacao models.
     * @return mixed
     */
    public function actionIndex()
    {
        $eventos = [];
        $implantacoes = Qualidade::find()->all();

        foreach ($implantacoes as $implantacao) {
            $evento = new \yii2fullcalendar\models\Event();
            $evento->id = $implantacao->id;
            $evento->title = $implantacao->razao_social;
            $evento->start = $implantacao->data;
            $evento->backgroundColor = $implantacao->estadoImplantacao->cor;
            $eventos[] = $evento;
        }

        $horariosIndisponiveis = HorarioIndisponivel::find()->all();

        foreach ($horariosIndisponiveis as $horarioIndisponivel) {
            $evento = new \yii2fullcalendar\models\Event();
            $evento->title = $horarioIndisponivel->motivo;
            $evento->start = $horarioIndisponivel->data_inicio;
            $evento->end = $horarioIndisponivel->data_fim;
            $evento->backgroundColor = "#aeafaf";
            $eventos[] = $evento;
        }

        return $this->render('index', [
            'eventos' => $eventos,
        ]);
    }

    /**
     * Displays a single Implantacao model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Implantacao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Qualidade();

        if ($model->load(Yii::$app->request->post())) {

            $model->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Pendente'])->one()->id;
            $model->data = $model->data . ' ' . $model->hora;

            echo '<br><br><br><br><pre>';
            //var_dump($model);
            var_dump($model->vez);
            echo '</pre>';

            if ($model->save()) {

                echo '<pre>';
                //var_dump($model);
                echo '</pre>';

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Define os valores padrões
        $data = Yii::$app->getRequest()->getQueryParam('data');
        $model->cadastrante_id = Yii::$app->user->identity->id;
        $model->data = $data;

        $horarios = Qualidade::getHorasDisponiveis($data);
        return $this->render('create', [
            'model' => $model,
            'estadoImplantacao' => EstadoImplantacao::allAsMap(),
            'data' => $data,
            'cadastrante' => [Yii::$app->user->identity->id => Yii::$app->user->identity->nome],
            'horarios' => $horarios

        ]);
    }

    /**
     * Updates an existing Implantacao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->hora = $model->data;

        $vez = 0;

        if (
            !Usuario::isRole(['Vendedor'], Yii::$app->user->identity) ||
            (Usuario::isRole(['Vendedor'], Yii::$app->user->identity) && $model->cadastrante_id == Yii::$app->user->identity->id)
        ) {
            if ($model->load(Yii::$app->request->post())) {

                var_dump("<br><br><br><br>");

                if ("Realizada" == EstadoImplantacao::allAsMap()[$model->estado_implantacao_id]) {
                    //$model->data = '2020-06-29';

                    if ($model->data == '') {
                        var_dump('<pre>');
                        var_dump($model->oldAttributes['data']);
                        $model->data = $model->oldAttributes['data'];
                        var_dump('</pre>');
                        var_dump($model->data);
                    }

                    $date = new DateTime($model->data);
                    var_dump($date->format('d/m/Y w') . '<br>');
                    if ($model->vez == 0) {
                        $date->modify('+7 day');
                        $vez = 1;
                    } else if ($model->vez == 1) {
                        $date->modify('+8 day');
                        $vez = 2;
                    } else if ($model->vez == 2) {
                        $date->modify('+15 day');
                        $vez = 3;
                    } else if ($model->vez == 3) {
                        $date->modify('+30 day');
                        $vez = 4;
                    } else if ($model->vez == 4) {
                        $date->modify('+30 day');
                        $vez = 5;
                    } else {
                        if ($model->save()) {
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }

                    $data = $date->format('w');
                    if ($data == "0") {
                        $date->modify('+1 day');
                    } else if ($data == "6") {
                        $date->modify('+2 day');
                    }
                    $data = $date->format('d/m/Y w');

                    var_dump($data);

                    $data = $date->format('d/m/Y');

                    try {
                        Yii::$app->mailer->compose('@app/mail/layouts/acompanhamento', [
                            "nomeResponsavel" => $model->responsavel,
                            "dataRetorno" => $data
                        ])
                            ->setFrom(Yii::$app->params['senderEmail'])
                            ->setTo($model->email_responsavel)
                            ->setSubject("Nossa próxima reunião")
                            ->send();
                        //if ($mail->send()) {
                        if ($model->save()) {
                            $modelQualidade = new Qualidade();
                            $data = $date->format('Y-m-d');
                            $modelQualidade->data = $data . ' 00:00:00';
                            $modelQualidade->hora = $model->data;
                            $modelQualidade->responsavel = $model->responsavel;
                            $modelQualidade->telefone = $model->telefone;
                            $modelQualidade->cadastrante_id = $model->cadastrante_id;
                            $modelQualidade->atendente_id = $model->atendente_id;
                            $modelQualidade->email_responsavel = $model->email_responsavel;
                            $modelQualidade->celular = $model->celular;
                            $modelQualidade->razao_social = $model->razao_social;
                            $modelQualidade->cnpj = $model->cnpj;
                            $modelQualidade->comentario = $model->comentario;
                            $modelQualidade->vez = $vez;
                            $modelQualidade->cota_xml = 0;
                            $modelQualidade->cota_bipagem = 0;
                            $modelQualidade->cota_ged = 0;
                            $modelQualidade->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Pendente'])->one()->id;

                            var_dump('<pre>');
                            //var_dump($modelQualidade);
                            //var_dump('<br><br>');
                            var_dump($modelQualidade->validate());
                            var_dump('</pre>');

                            if ($modelQualidade->save()) {
                                return $this->redirect(['view', 'id' => $model->id]);
                            }
                        }
                        //}
                    } catch (Exception $e) {
                        throw new HttpException($e);
                    }
                } else if ("Reagendada" == EstadoImplantacao::allAsMap()[$model->estado_implantacao_id]) {

                    $model->data = $model->data . ' 00:00:00';

                    $dataReagendada = $model->data;

                    var_dump('<pre>');
                    var_dump($model->oldAttributes['data']);
                    $model->data = $model->oldAttributes['data'];
                    var_dump('</pre>');
                    var_dump('' . $model->data . '<br>');
                    var_dump('' . $dataReagendada);

                    if ($model->save()) {
                        $modelQualidade = new Qualidade();

                        $modelQualidade->data = $dataReagendada;
                        $modelQualidade->hora = $model->data;
                        $modelQualidade->responsavel = $model->responsavel;
                        $modelQualidade->telefone = $model->telefone;
                        $modelQualidade->cadastrante_id = $model->cadastrante_id;
                        $modelQualidade->atendente_id = $model->atendente_id;
                        $modelQualidade->email_responsavel = $model->email_responsavel;
                        $modelQualidade->celular = $model->celular;
                        $modelQualidade->razao_social = $model->razao_social;
                        $modelQualidade->cnpj = $model->cnpj;
                        $modelQualidade->comentario = $model->comentario;
                        $modelQualidade->vez = $vez;
                        $modelQualidade->cota_xml = 0;
                        $modelQualidade->cota_bipagem = 0;
                        $modelQualidade->cota_ged = 0;
                        $modelQualidade->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Pendente'])->one()->id;

                        var_dump($modelQualidade->validate());


                        if ($modelQualidade->save()) {
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }

                    /*if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }*/


                    //if ($model->save()) {
                    //return $this->redirect(['view', 'id' => $model->id]);
                    //}
                } else {

                    if ($model->data == '') {
                        var_dump('<pre>');
                        var_dump($model->oldAttributes['data']);
                        $model->data = $model->oldAttributes['data'];
                        var_dump('</pre>');
                        var_dump($model->data);
                    }

                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }

                /*if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }*/
            }

            return $this->render('update', [
                'model' => $model,
                'estadoQualidade' => EstadoImplantacao::allAsMap(),
                'atendentes' => Usuario::getAllSuporteAsMap()
            ]);
        } else {
            throw new ForbiddenHttpException("Você não pode alterar essa implantação.");
        }
    }

    /**
     * Deletes an existing Implantacao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (
            !Usuario::isRole(['Vendedor'], Yii::$app->user->identity) ||
            (Usuario::isRole(['Vendedor'], Yii::$app->user->identity) && $model->cadastrante_id == Yii::$app->user->identity->id)
        ) {
            $model->delete();
            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException("Você não pode excluir essa implantação.");
        }
    }

    /**
     * Finds the Implantacao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Implantacao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Qualidade::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
