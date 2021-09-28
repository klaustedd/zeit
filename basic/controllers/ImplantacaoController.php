<?php

namespace app\controllers;

use app\controllers\TesteController;
use app\models\EstadoImplantacao;
use app\models\EstadoQualidade;
use app\models\HorarioDisponivel;
use app\models\HorarioIndisponivel;
use Yii;
use app\models\Implantacao;
use app\models\Qualidade;
use app\models\ImplantacaoSearch;
use app\models\Usuario;
use DateTime;
use Event;
use Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\rbac\BaseManager;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

/**
 * ImplantacaoController implements the CRUD actions for Implantacao model.
 */
class ImplantacaoController extends Controller
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
                        'actions' => [
                            'index', 'view', 'update', 'delete', 'create',
                            'nao-realizados', 'realizados', 'reagendado', 'reagenda',
                            'buscar'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
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
        $implantacoes = Implantacao::find()->all();

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
     * Lists all Implantacao models.
     * @return mixed
     */
    public function actionReagendado($id)
    {
        $eventos = [];
        $implantacoes = Implantacao::find()->all();

        $horarios = HorarioDisponivel::find()->all();

        $hoje = new DateTime();

        $dia = $hoje->format("Y-m-d");

        $a = 0;

        $livres = [];
        $ids = [];


        //Do que conta só os 30 primeiros dias 
        do {
            //For que passa por cada horarios disponivel
            foreach ($horarios as $horario) {
                ////var_dump("<br>");
                if ($hoje->format("w") != "6" & $hoje->format("w") != "0") {
                    $livres[] = $dia . " " . $horario->horario;
                }

                ////var_dump($dia . " " . $horario->horario);
            }
            ////var_dump("<br>");
            $hoje->modify('+1 day');
            $dia = $hoje->format("Y-m-d");
            $a++;
        } while ($a != 90);

        $b = 0;

        $livres = array_unique($livres);

        $a = 0;

        $d = 0;

        foreach ($livres as $livre) {
            foreach ($implantacoes as $implantacao) {

                if ($livre == $implantacao->data) {
                    unset($livres[$a]);
                }
            }
            $a++;
        }

        $a = 0;

        foreach ($livres as $livre) {
            $evento = new \yii2fullcalendar\models\Event();
            $evento->id = $id;
            $evento->title = "Horário vago";
            $evento->start = $livre;
            $evento->backgroundColor = "#ccc";
            $eventos[] = $evento;

            $a++;
        }

        return $this->render('reagendado', [
            'eventos' => $eventos,
        ]);
    }

    /**
     * Reagenda uma implantação.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReagenda($id, $data)
    {

        $model = Implantacao::findOne($id);
        $model->hora = $model->data;
        $model->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Reagendada'])->one()->id;

        if ($model->save()) {

            $modelQualidade = new Implantacao();

            $modelQualidade->data = $data;
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
            $modelQualidade->cota_xml = 0;
            $modelQualidade->cota_bipagem = 0;
            $modelQualidade->cota_ged = 0;
            $modelQualidade->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Pendente'])->one()->id;

            //var_dump("<pre>");
            //var_dump($modelQualidade);
            //var_dump($modelQualidade->validate());
            //var_dump("</pre>");
            //die();

            if ($modelQualidade->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
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
     * Cria uma nova Implantacao
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Implantacao();

        /*var_dump(Usuario::isRole(['Vendedor'], Yii::$app->user->identity));
        die();*/


        if ($model->load(Yii::$app->request->post())) {

            if (
                Usuario::isRole(['Vendedor'], Yii::$app->user->identity)
            ) {
                $model->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Realizada'])->one()->id;
                //$model->hora = '00:00:00';
                $model->data = $model->data . ' ' . '00:00:00';
            } else {
                $model->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Pendente'])->one()->id;
                $model->data = $model->data . ' ' . $model->hora;
            }


            //Caso de quando sera a implementação
            Yii::$app->mailer->compose('@app/mail/layouts/nova-implementacao', [
                "nomeResponsavel" => $model->responsavel,
                "dataRetorno" =>  $model->data,
                'imageFileName' => './basic/imagens/logo_cd.png'
            ])
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo($model->email_responsavel)
                ->setSubject("Implantação Realizada!")
                ->send();

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Define os valores padrões
        $data = Yii::$app->getRequest()->getQueryParam('data');
        $model->cadastrante_id = Yii::$app->user->identity->id;
        $model->data = $data;

        $horarios = Implantacao::getHorasDisponiveis($data);

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
        ////var_dump('<br><br><br><br><br><br><br>');

        if (
            !Usuario::isRole(['Vendedor'], Yii::$app->user->identity) ||
            (Usuario::isRole(['Vendedor'], Yii::$app->user->identity) && $model->cadastrante_id == Yii::$app->user->identity->id)
        ) {
            if ($model->load(Yii::$app->request->post())) {

                if ("Realizada" == EstadoImplantacao::allAsMap()[$model->estado_implantacao_id]) {

                    if ($model->data == '') {
                        //var_dump('<pre>');
                        //var_dump($model->oldAttributes['data']);
                        $model->data = $model->oldAttributes['data'];
                        //var_dump('</pre>');
                        //var_dump($model->data);
                    }

                    $date = new DateTime($model->data);
                    $date->modify('+1 day');
                    $data = $date->format('w');
                    if ($data == "0") {
                        $date->modify('+1 day');
                    }
                    if ($data == "6") {
                        $date->modify('+2 day');
                    }
                    $data = $date->format('d/m/Y');


                    //Caso de quando foi feita a implementação
                    Yii::$app->mailer->compose('@app/mail/layouts/implantacao-realizada', [
                        "nomeResponsavel" => $model->responsavel,
                        "dataRetorno" => $data
                    ])
                        ->setFrom(Yii::$app->params['senderEmail'])
                        ->setTo($model->email_responsavel)
                        ->setSubject("Implantação Realizada!")
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
                        $modelQualidade->vez = 0;
                        //$modelQualidade->nome = 0;
                        $modelQualidade->cota_xml = 0;
                        $modelQualidade->cota_bipagem = 0;
                        $modelQualidade->cota_ged = 0;
                        $modelQualidade->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Pendente'])->one()->id;

                        // //var_dump('<pre>');
                        /*//var_dump($modelQualidade);
                                //var_dump('<br><br>');
                                //var_dump($modelQualidade->validate());*/
                        // //var_dump('</pre>');

                        if ($modelQualidade->save()) {


                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }
                    //}
                } else if ("Reagendada" == EstadoImplantacao::allAsMap()[$model->estado_implantacao_id]) {

                    $model->data = $model->data . ' ' . $model->hora;

                    $dataReagendada = $model->data;
                    $model->data = $model->oldAttributes['data'];


                    //Caso de quando sera a implementação
                    Yii::$app->mailer->compose('@app/mail/layouts/nova-implementacao', [
                        "nomeResponsavel" => $model->responsavel,
                        "dataRetorno" =>  $model->data,
                        'imageFileName' => './basic/imagens/logo_cd.png'
                    ])
                        ->setFrom(Yii::$app->params['senderEmail'])
                        ->setTo($model->email_responsavel)
                        ->setSubject("Implantação Realizada!")
                        ->send();

                    if ($model->save()) {
                        $modelQualidade = new Implantacao();

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
                        $modelQualidade->cota_xml = 0;
                        $modelQualidade->cota_bipagem = 0;
                        $modelQualidade->cota_ged = 0;
                        $modelQualidade->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Pendente'])->one()->id;

                        //var_dump($modelQualidade->validate());


                        if ($modelQualidade->save()) {
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }
                } else if ("Não Reagendada" == EstadoImplantacao::allAsMap()[$model->estado_implantacao_id]) {

                    if ($model->data == '') {
                        $model->data = $model->oldAttributes['data'];
                    }

                    //Caso de quando sera a implementação
                    Yii::$app->mailer->compose('@app/mail/layouts/nao-reagendada', [
                        "nomeResponsavel" => $model->responsavel,
                        "dataRetorno" =>  $model->data,
                        'imageFileName' => './basic/imagens/logo_cd.png'
                    ])
                        ->setFrom(Yii::$app->params['senderEmail'])
                        ->setTo($model->email_responsavel)
                        ->setSubject("Implantação Realizada!")
                        ->send();

                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    if ($model->data == '') {
                        //var_dump('<pre>');
                        //var_dump($model->oldAttributes['data']);
                        $model->data = $model->oldAttributes['data'];
                        //var_dump('</pre>');
                        //var_dump($model->data);
                    }
                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
                /*if ($model->save()) {
                    '
                    return $this->redirect(['view', 'id' => $model->id]);
                }*/
            }

            //rhaast
            $data = Yii::$app->getRequest()->getQueryParam('data');
            $horarios = Implantacao::getHorasDisponiveis($data);

            return $this->render('update', [
                'model' => $model,
                'estadoImplantacao' => EstadoImplantacao::allAsMap(),
                'atendentes' => Usuario::getAllSuporteAsMap(),
                'horarios' => $horarios
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

    public function actionNaoRealizados()
    {
        $searchModel = new ImplantacaoSearch();
        $dataProvider = $searchModel->searchPerdido(Yii::$app->request->queryParams);

        $model = new Implantacao();

        if (
            Yii::$app->request->post()
        ) {
            $estadoRequerido = EstadoImplantacao::find()->where(['nome' => 'Realizada'])->one();
            $estadoReagendada = EstadoImplantacao::find()->where(['nome' => 'Reagendada'])->one();
            $query = new ActiveDataProvider([
                'query' => Implantacao::find()->where(['<>', 'estado_implantacao_id', $estadoRequerido->id])->andwhere(['<>', 'estado_implantacao_id', $estadoReagendada->id])->andwhere(['<', 'data', date('Y-m-d')])->orderBy('data ASC'),
            ]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);


            return $this->render('realizados', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'estadoImplantacao' => EstadoImplantacao::allAsMap(),

            ]);
        }

        return $this->render('realizados', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'estadoImplantacao' => EstadoImplantacao::allAsMap(),
        ]);
    }

    public function actionBuscar()
    {

        $searchModel = new ImplantacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Implantacao();

        return $this->render('realizados', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'estadoImplantacao' => EstadoImplantacao::allAsMap(),
        ]);
    }

    public function actionRealizados()
    {
        $searchModel = new ImplantacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Implantacao();

        if (
            Yii::$app->request->post()
        ) {
            if (Yii::$app->request->post()["Implantacao"]["data"] == "") {
                $query = Implantacao::find()->orderBy('data desc')->where(['=', 'estado_implantacao_id', Yii::$app->request->post()["Implantacao"]["estado_implantacao_id"]]);
            } else {
                $query = Implantacao::find()->orderBy('data desc')->where(['>', 'data', Yii::$app->request->post()["Implantacao"]["data"]])->where(['=', 'estado_implantacao_id', Yii::$app->request->post()["Implantacao"]["estado_implantacao_id"]]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            var_dump(Yii::$app->request->post()["Implantacao"]);

            //die();

            return $this->render('realizados', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'estadoImplantacao' => EstadoImplantacao::allAsMap(),

            ]);
        }

        return $this->render('realizados', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'estadoImplantacao' => EstadoImplantacao::allAsMap(),
        ]);
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

        if (($model = Implantacao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
