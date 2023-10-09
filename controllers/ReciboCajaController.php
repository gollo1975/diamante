<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use Codeception\Lib\HelperModule;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
//models
use app\models\ReciboCaja;
use app\models\ReciboCajaSearch;
use app\models\Municipios;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaRecibo;
use app\models\AgentesComerciales;
use app\models\FacturaVenta;
use app\models\Clientes;

/**
 * ReciboCajaController implements the CRUD actions for ReciboCaja model.
 */
class ReciboCajaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

   //LISTA TODOS LOS CLIENTES CON CARTERA PARA CADA VENDEDOR
    public function actionCargar_cartera() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',55])->all()){
                $form = new FiltroBusquedaRecibo();
                $documento= null;
                $cliente = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                $vendedor = AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        if($tokenAcceso == 3){
                            $table = FacturaVenta::find()
                                    ->andFilterWhere(['=', 'nit_cedula', $documento])
                                    ->andFilterWhere(['like', 'cliente', $cliente])
                                    ->andWhere(['>', 'saldo_factura', 0])
                                    ->andWhere(['=', 'autorizado', 1])
                                    ->andWhere(['>', 'numero_factura', 0])
                                    ->andWhere(['=','id_agente', $vendedor->id_agente]);
                        }else{
                            $table = Clientes::find()
                                ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                ->andFilterWhere(['like', 'nombre_completo', $nombre_completo])
                                ->andWhere(['=','id_agente', $vendedor->id_agente])    
                                ->andWhere(['=', 'estado_cliente', 0])
                                ->andWhere(['=', 'id_tipo_cliente', 3])
                                ->orWhere(['=', 'id_tipo_cliente', 2])
                                ->orWhere(['=', 'id_tipo_cliente', 4]);    
                        }    
                        $table = $table->orderBy('cliente ASC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 15,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        
                    } else {
                        $form->getErrors();
                    }
                } else {
                    if($tokenAcceso == 3){
                        $table = FacturaVenta::find()->Where(['>', 'saldo_factura', 0])
                                    ->andWhere(['=', 'autorizado', 1])
                                    ->andWhere(['>', 'numero_factura', 0])
                                    ->andWhere(['=','id_agente', $vendedor->id_agente])    
                                    ->orderBy('cliente ASC');
                    }else{
                        $table = Clientes::find()->where(['=','estado_cliente', 0])
                            ->andWhere(['=','id_agente', $vendedor->id_agente])
                            ->andWhere(['=', 'id_tipo_cliente', 2])    
                            ->orWhere(['=', 'id_tipo_cliente', 3]) 
                            ->orWhere(['=', 'id_tipo_cliente', 4])      
                            ->orderBy('nombre_completo ASC');   
                    }
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }
                $to = $count->count();
                return $this->render('cargar_cliente_cartera', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'vendedor' => $vendedor,
                            'tokenAcceso' => $tokenAcceso,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }

    /**
     * Displays a single ReciboCaja model.
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
    //PERMITE CREAR EL NUEVO RECIBO DE CAJA
    public function actionCrear_nuevo_recibo($id_cliente) {

        $model = new \app\models\FormModeloNuevoRecibo();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nuevo_recibo_pago"])) {
                    $cliente = Clientes::find()->Where(['=', 'id_cliente', $id_cliente])->one();
                    $table = new ReciboCaja();
                    $table->id_cliente = $id_cliente;
                    $table->id_tipo = $model->tipo_recibo;
                    $table->fecha_pago = $model->fecha_pago;
                    $table->codigo_municipio = $cliente->codigo_municipio;
                    $table->codigo_banco = $model->banco;
                    $table->observacion = $model->observacion;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $recibo = ReciboCaja::find()->orderBy('id_recibo DESC')->one();
                    $this->redirect(["recibo-caja/view", 'id' => $recibo->id_recibo]);
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->renderAjax('form_nuevo_recibo', [
                    'model' => $model,
                    'id_cliente' => $id_cliente,
                    //'agenteToken' => $agenteToken,
                    //'tokenAcceso' => $tokenAcceso,
        ]);
    }
    /**
     * Creates a new ReciboCaja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReciboCaja();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_recibo]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ReciboCaja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_recibo]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ReciboCaja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ReciboCaja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReciboCaja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReciboCaja::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
