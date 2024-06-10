<?php

namespace app\controllers;

//clases
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

//,models

use app\models\ReciboCajaPuntoVenta;
use app\models\ReciboCajaPuntoVentaSearch;
use app\models\EntidadBancarias;
use app\models\FormaPago;
use app\models\TipoReciboCaja;
use app\models\UsuarioDetalle;

/**
 * ReciboCajaPuntoVentaController implements the CRUD actions for ReciboCajaPuntoVenta model.
 */
class ReciboCajaPuntoVentaController extends Controller
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

    /**
     * Lists all ReciboCajaPuntoVenta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReciboCajaPuntoVentaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReciboCajaPuntoVenta model.
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
     * Creates a new ReciboCajaPuntoVenta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReciboCajaPuntoVenta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_recibo]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ReciboCajaPuntoVenta model.
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

    //PERMITE CREAR EL NUEVO RECIBO DE CAJA  FACTURA DE PUNTO
    public function actionCrear_recibo_caja_factura($id_factura_punto, $accesoToken) {

        $model = new \app\models\FormModeloNuevoReciboPunto();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nuevo_recibo_pago_factura"])) {
                    $factura = \app\models\FacturaVentaPunto::find()->Where(['=', 'id_factura', $id_factura_punto])->one();
                    if($model->valor_pago == $factura->total_factura){
                        $table = new ReciboCajaPuntoVenta();
                        $table->id_factura = $id_factura_punto;
                        $table->id_tipo = $model->tipo_recibo;
                        $table->id_punto = $accesoToken;
                        $table->codigo_banco = $model->banco;
                        $table->id_forma_pago = $model->forma_pago;
                        $table->fecha_recibo = date('Y-m-d');
                        $table->valor_abono = $model->valor_pago;
                        $table->valor_saldo = $factura->total_factura - $model->valor_pago;
                        $table->numero_transacion = $model->numero_transacion;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->save();
                        $factura->estado_factura = 2;
                        $factura->saldo_factura =  $table->valor_saldo;
                        $factura->save();
                        $nroRecibo = ReciboCajaPuntoVenta::find()->orderBy('id_recibo DESC')->limit(1)->one();
                        //proceso de generar consecutivo
                        $consecutivo = \app\models\Consecutivos::findOne(18);
                        $recibo = ReciboCajaPuntoVenta::findOne($nroRecibo->id_recibo);
                        $recibo->numero_recibo = $consecutivo->numero_inicial + 1;
                        $recibo->save();
                        $consecutivo->numero_inicial = $recibo->numero_recibo;
                        $consecutivo->save();
                        $this->redirect(["/factura-venta-punto/view", 'id_factura_punto' => $id_factura_punto, 'accesoToken' => $accesoToken]);
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'El valor a cancelar es DIFERENTE al valor de la factura. Validar nuevamente la informacion.');
                        $this->redirect(["/factura-venta-punto/view", 'id_factura_punto' => $id_factura_punto, 'accesoToken' => $accesoToken]);
                    }    
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->renderAjax('/recibo-caja-punto-venta/form_nuevo_recibo_factura', [
            'model' => $model,
        ]);
    }
    
     //PERMITE CREAR EL NUEVO RECIBO DE CAJA  REMISION PUNTO DE VENTA
    public function actionCrear_recibo_caja_remision($id, $accesoToken) {

        $model = new \app\models\FormModeloNuevoReciboPunto();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nuevo_recibo_pago_remision"])) {
                    $remision = \app\models\Remisiones::find()->Where(['=', 'id_remision', $id])->one();
                    if($model->valor_pago == $remision->total_remision){
                        $table = new ReciboCajaPuntoVenta();
                        $table->id_remision = $id;
                        $table->id_tipo = $model->tipo_recibo;
                        $table->id_punto = $accesoToken;
                        $table->codigo_banco = $model->banco;
                        $table->id_forma_pago = $model->forma_pago;
                        $table->fecha_recibo = date('Y-m-d');
                        $table->valor_abono = $model->valor_pago;
                        $table->valor_saldo = $remision->total_remision - $model->valor_pago;
                        $table->numero_transacion = $model->numero_transacion;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->save();
                        $remision->estado_remision = 1;
                        $remision->save();
                        $nroRecibo = ReciboCajaPuntoVenta::find()->orderBy('id_recibo DESC')->limit(1)->one();
                        //proceso de generar consecutivo
                        $consecutivo = \app\models\Consecutivos::findOne(19);
                        $recibo = ReciboCajaPuntoVenta::findOne($nroRecibo->id_recibo);
                        $recibo->numero_recibo = $consecutivo->numero_inicial + 1;
                        $recibo->save();
                        $consecutivo->numero_inicial = $recibo->numero_recibo;
                        $consecutivo->save();
                        $this->redirect(["/remisiones/view", 'id' => $id, 'accesoToken' => $accesoToken]);
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'El valor a cancelar es DIFERENTE al valor de la REMISION. Validar nuevamente la informacion.');
                        $this->redirect(["/remisiones/view", 'id' => $id, 'accesoToken' => $accesoToken]);
                    }    
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->renderAjax('/recibo-caja-punto-venta/form_nuevo_recibo_remision', [
            'model' => $model,
        ]);
    }
    
    
    /**
     * Finds the ReciboCajaPuntoVenta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReciboCajaPuntoVenta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReciboCajaPuntoVenta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
