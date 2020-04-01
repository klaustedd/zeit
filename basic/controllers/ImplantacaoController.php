<?php

namespace app\controllers;

use app\models\EstadoImplantacao;
use Yii;
use app\models\Implantacao;
use app\models\ImplantacaoSearch;
use app\models\Usuario;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\rbac\BaseManager;
use yii\web\ForbiddenHttpException;

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
                        'actions' => ['index', 'view', 'update', 'delete', 'create'],
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
        $model = new Implantacao();

        if ($model->load(Yii::$app->request->post())) {

            $model->estado_implantacao_id = EstadoImplantacao::find()->where(['nome' => 'Pendente'])->one()->id;
            $model->data = $model->data . ' ' . $model->hora;

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

        if (
            !Usuario::isRole(['Vendedor'], Yii::$app->user->identity) ||
            (Usuario::isRole(['Vendedor'], Yii::$app->user->identity) && $model->cadastrante_id == Yii::$app->user->identity->id)
        ) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }

            return $this->render('update', [
                'model' => $model,
                'estadoImplantacao' => EstadoImplantacao::allAsMap(),
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
        if (($model = Implantacao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
