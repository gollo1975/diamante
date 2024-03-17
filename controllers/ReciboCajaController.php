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
use app\models\ReciboCajaDetalles;
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
   //index de los procesos
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',56])->all()){
                $form = new FiltroBusquedaRecibo();
                $cliente = null;
                $numero = null;
                $banco = null;
                $tipo_recibo = null;
                $desde = null;
                $hasta = null;
                $recibo_detalle = 0;
                $tokenAcceso = Yii::$app->user->identity->role;
                $vendedor = AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                if ($vendedor){
                    $agente = $vendedor->id_agente;
                }else{
                    $agente = 0;
                }
                
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $cliente = Html::encode($form->cliente);
                        $banco = Html::encode($form->banco);
                        $tipo_recibo = Html::encode($form->tipo_recibo);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);  
                        $recibo_detalle = Html::encode($form->recibo_detalle);
                        if($tokenAcceso == 3){
                            $table = ReciboCaja::find()
                                        ->andFilterWhere(['like', 'cliente', $cliente])
                                        ->andFilterWhere(['=', 'numero_recibo', $numero])
                                        ->andFilterWhere(['=', 'codigo_banco', $banco])
                                        ->andFilterWhere(['=', 'id_tipo', $tipo_recibo])
                                        ->andFilterWhere(['between', 'fecha_pago', $desde, $hasta])
                                        ->andWhere(['=','user_name', $vendedor->nit_cedula]);
                        }else{
                            $table = ReciboCaja::find()
                                        ->andFilterWhere(['like', 'cliente', $cliente])
                                        ->andFilterWhere(['=', 'numero_recibo', $numero])
                                        ->andFilterWhere(['=', 'codigo_banco', $banco])
                                        ->andFilterWhere(['=', 'id_tipo', $tipo_recibo])
                                        ->andFilterWhere(['between', 'fecha_pago', $desde, $hasta]);
                        }    
                        $table = $table->orderBy('id_recibo DESC');
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
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelRecibos($tableexcel);
                        }
                        
                    } else {
                        $form->getErrors();
                    }
                } else {
                     if($tokenAcceso == 3){  
                        $table = ReciboCaja::find()->Where(['=','user_name', $vendedor->nit_cedula])->orderBy('id_recibo DESC');   
                     }else{
                         $table = ReciboCaja::find()->orderBy('id_recibo DESC');  
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
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelRecibos($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
                        'token' => $token,
                        'tokenAcceso' => $tokenAcceso,
                        'agente' => $agente,        
                        'recibo_detalle' => $recibo_detalle,
                        'desde' => $desde,
                        'hasta' => $hasta,
                           
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
   //LISTA TODOS LOS CLIENTES CON CARTERA PARA CADA VENDEDOR
    public function actionCargar_cartera() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',55])->all()){
                $form = new FiltroBusquedaRecibo();
                $documento= null;
                $cliente = null;
                $vendedores = null;
                $desde = null;
                $hasta = null;
                $numero = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                $vendedor = AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $vendedores = Html::encode($form->vendedores);
                        $numero = Html::encode($form->numero);
                        if($tokenAcceso == 3){
                            $table = FacturaVenta::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'cliente', $cliente])
                                ->andWhere(['>', 'saldo_factura', 0])
                                ->andWhere(['=', 'autorizado', 1])
                                ->andWhere(['>', 'numero_factura', 0])
                                ->andWhere(['=','id_agente', $vendedor->id_agente]);
                        }else{
                            $table = FacturaVenta::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'cliente', $cliente])
                                ->andFilterWhere(['=', 'id_agente', $vendedores])
                                ->andFilterWhere(['=', 'numero_factura', $numero])  
                                ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])      
                                ->andWhere(['>', 'saldo_factura', 0])
                                ->andWhere(['=', 'autorizado', 1])
                                ->andWhere(['>', 'numero_factura', 0]);
                        }    
                        $table = $table->orderBy('id_factura DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
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
                            ->orderBy('id_factura ASC');
                    }else{
                        $table = FacturaVenta::find()->Where(['>', 'saldo_factura', 0])
                            ->andWhere(['=', 'autorizado', 1])
                            ->andWhere(['>', 'numero_factura', 0])
                            ->orderBy('id_factura ASC');
                    }    
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }
                $to = $count->count();
                if($tokenAcceso == 3){
                    return $this->render('cargar_cliente_cartera', [
                                'model' => $model,
                                'form' => $form,
                                'pagination' => $pages,
                                'vendedor' => $vendedor,
                                'tokenAcceso' => $tokenAcceso,
                    ]);
                }else{ 
                   return $this->render('cargar_cliente_cartera_admon', [
                                'model' => $model,
                                'form' => $form,
                                'pagination' => $pages,
                                'tokenAcceso' => $tokenAcceso,
                    ]); 
                }   
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
     public function actionView($id, $token)
    {
        $detalle_recibo = ReciboCajaDetalles::find()->where(['=','id_recibo', $id])->all();
          if(isset($_POST["actualizarecibo"])){
            if(isset($_POST["actualizar_recibo_caja"])){
                $intIndice = 0;
                $auxiliar = 0;
                foreach ($_POST["actualizar_recibo_caja"] as $intCodigo):
                    $table = ReciboCajaDetalles::findOne($intCodigo);
                    $auxiliar = $table->saldo_factura; 
                    if($_POST["abono_factura"]["$intIndice"] > $auxiliar){
                        Yii::$app->getSession()->setFlash('warning', 'El valor del abono no puede ser mayor que el saldo.'); 
                    }else{    
                        $table->abono_factura = $_POST["abono_factura"]["$intIndice"];
                        $table->save(false);
                    }    
                    $intIndice++;
                endforeach;
               return $this->redirect(['view','id' =>$id, 'token' => $token]);
            }
            
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_recibo' => $detalle_recibo,
        ]);
    }
    public function actionView_cliente($id, $token, $tokenAcceso)
    {
        $detalle_recibo = ReciboCajaDetalles::find()->where(['=','id_recibo', $id])->all();
        if(isset($_POST["actualizasaldorecibo"])){
            if(isset($_POST["actualizar_saldo"])){
                $intIndice = 0;
                $auxiliar = 0;
                foreach ($_POST["actualizar_saldo"] as $intCodigo):
                    $table = ReciboCajaDetalles::findOne($intCodigo);
                    $auxiliar = $table->saldo_factura; 
                    if($_POST["abono_factura"]["$intIndice"] > $auxiliar){
                        Yii::$app->getSession()->setFlash('warning', 'El valor del abono no puede ser mayor que el saldo.'); 
                    }else{    
                        $table->abono_factura = $_POST["abono_factura"]["$intIndice"];
                        $table->save(false);
                    }    
                    $intIndice++;
                endforeach;
               return $this->redirect(['view_cliente','id' =>$id, 'token' => $token, 'tokenAcceso' => $tokenAcceso]);
            }
            
        }
        return $this->render('view_cliente', [
            'model' => $this->findModel($id),
            'token' => $token,
            'tokenAcceso' => $tokenAcceso,
            'detalle_recibo' => $detalle_recibo,
            'id' => $id,
        ]);
    }
    //PERMITE CREAR EL NUEVO RECIBO DE CAJA
    public function actionCrear_nuevo_recibo($id_cliente, $tokenAcceso) {

        $model = new \app\models\FormModeloNuevoRecibo();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nuevo_recibo_pago"])) {
                    $cliente = Clientes::find()->Where(['=', 'id_cliente', $id_cliente])->one();
                    $table = new ReciboCaja();
                    $table->id_cliente = $id_cliente;
                    $table->cliente = $cliente->nombre_completo;
                    $table->direccion_cliente = $cliente->direccion;
                    $table->id_tipo = $model->tipo_recibo;
                    $table->fecha_pago = $model->fecha_pago;
                    $table->codigo_municipio = $cliente->codigo_municipio;
                    $table->codigo_banco = $model->banco;
                    $table->observacion = $model->observacion;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $recibo = ReciboCaja::find()->orderBy('id_recibo DESC')->one();
                    $this->redirect(["recibo-caja/view_cliente", 'id' => $recibo->id_recibo, 'tokenAcceso' => $tokenAcceso]);
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->renderAjax('form_nuevo_recibo', [
                    'model' => $model,
                    'id_cliente' => $id_cliente,

                    'tokenAcceso' => $tokenAcceso,
        ]);
    }
    
    //proceso que busca las facturas del cliente
    public function actionBuscar_facturas($id, $id_cliente, $token, $tokenAcceso) {
        $facturas = FacturaVenta::find()->where(['=','id_cliente', $id_cliente])
                                        ->andWhere(['>','saldo_factura', 0])
                                        ->andWhere(['>','numero_factura', 0])->orderBy('id_factura ASC')->all();
        $model = ReciboCaja::findOne($id);
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                $conSql = FacturaVenta::find()
                        ->where(['like','numero_factura', $q])
                        ->andwhere(['=','id_cliente', $id_cliente])
                        ->andWhere(['>','saldo_factura', 0])
                        ->andWhere(['>','numero_factura', 0]);
                
                $conSql = $conSql->orderBy('id_factura ASC');  
                $count = clone $conSql;
                $to = $count->count();
                $pages = new Pagination([
                    'pageSize' => 10 ,
                    'totalCount' => $count->count()
                ]);
                $factura = $conSql
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            $facturas = FacturaVenta::find()->where(['=','id_cliente', $id_cliente])
                                        ->andWhere(['>','saldo_factura', 0])
                                        ->andWhere(['>','numero_factura', 0])->orderBy('id_factura ASC');
            $tableexcel = $facturas->all();
            $count = clone $facturas;
            $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
            ]);
             $factura = $facturas
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        if (isset($_POST["enviar_factura"])) {
            if(isset($_POST["pago_factura"])){
                $intIndice = 0;
                foreach ($_POST["pago_factura"] as $intCodigo) {
                    $conFactura = FacturaVenta::find()->where(['=','id_factura', $intCodigo])->one();
                    $detalle = ReciboCajaDetalles::find()
                            ->where(['=', 'id_factura', $intCodigo])
                            ->andWhere(['=', 'id_recibo', $id])
                            ->all();
                    if(count($detalle)== 0){
                        $table = new ReciboCajaDetalles();
                        $table->id_recibo = $id;
                        $table->id_factura = $intCodigo;
                        $table->numero_factura= $conFactura->numero_factura;
                        $table->retencion= $conFactura->valor_retencion;
                        $table->reteiva= $conFactura->valor_reteiva;
                        $table->saldo_factura = $conFactura->saldo_factura;
                        $table->fecha_pago = $model->fecha_pago;
                        $table->save(false);
                    }    
                     $intIndice++;
                }
                return $this->redirect(['view_cliente','id' => $id, 'token' => $token, 'tokenAcceso' => $tokenAcceso]);
            }
        }
        return $this->render('listado_facturas', [ 
            'id' => $id,
            'model' => $model,
            'factura' => $factura,
            'form' => $form,
            'pagination' => $pages,
            'token' => $token,
            'tokenAcceso' => $tokenAcceso,
            'id_cliente' => $id_cliente,
        ]);
    }
    
    public function actionBuscar_facturas_admon($id, $id_cliente, $token) {
        $facturas = FacturaVenta::find()->where(['=','id_cliente', $id_cliente])
                                        ->andWhere(['>','saldo_factura', 0])
                                        ->andWhere(['>','numero_factura', 0])->orderBy('id_factura ASC')->all();
        $model = ReciboCaja::findOne($id);
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                $conSql = FacturaVenta::find()
                        ->where(['like','numero_factura', $q])
                        ->andwhere(['=','id_cliente', $id_cliente])
                        ->andWhere(['>','saldo_factura', 0])
                        ->andWhere(['>','numero_factura', 0]);
                
                $conSql = $conSql->orderBy('id_factura ASC');  
                $count = clone $conSql;
                $to = $count->count();
                $pages = new Pagination([
                    'pageSize' => 10 ,
                    'totalCount' => $count->count()
                ]);
                $factura = $conSql
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            $facturas = FacturaVenta::find()->where(['=','id_cliente', $id_cliente])
                                        ->andWhere(['>','saldo_factura', 0])
                                        ->andWhere(['>','numero_factura', 0])->orderBy('id_factura ASC');
            $tableexcel = $facturas->all();
            $count = clone $facturas;
            $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
            ]);
             $factura = $facturas
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        if (isset($_POST["enviar_factura_admon"])) {
            if(isset($_POST["pago_factura"])){
                $intIndice = 0;
                foreach ($_POST["pago_factura"] as $intCodigo) {
                    $conFactura = FacturaVenta::find()->where(['=','id_factura', $intCodigo])->one();
                    $detalle = ReciboCajaDetalles::find()
                            ->where(['=', 'id_factura', $intCodigo])
                            ->andWhere(['=', 'id_recibo', $id])
                            ->all();
                    if(count($detalle)== 0){
                        $table = new ReciboCajaDetalles();
                        $table->id_recibo = $id;
                        $table->id_factura = $intCodigo;
                        $table->numero_factura= $conFactura->numero_factura;
                        $table->retencion= $conFactura->valor_retencion;
                        $table->reteiva= $conFactura->valor_reteiva;
                        $table->saldo_factura = $conFactura->saldo_factura;
                        $table->save(false);
                    }    
                     $intIndice++;
                }
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('listado_facturas_admon', [ 
            'id' => $id,
            'model' => $model,
            'factura' => $factura,
            'form' => $form,
            'pagination' => $pages,
            'token' => $token,
            'id_cliente' => $id_cliente,
        ]);
    }
   

    /**
     * Updates an existing ReciboCaja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate_cliente($id, $agente)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $cliente = Clientes::findOne($model->id_cliente);
            $model->cliente = $cliente->nombre_completo;
            $model->direccion_cliente = $cliente->direccion;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update_cliente', [
            'model' => $model,
            'agente' => $agente,
        ]);
    }

    /**
     * Deletes an existing ReciboCaja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   //eliminar la factura del recibo de caja
        public function actionEliminar_factura($id,$detalle, $token)
       {                                
           $detalle = ReciboCajaDetalles::findOne($detalle);
           $detalle->delete();
           $this->redirect(["view",'id' => $id, 'token' => $token]);        
       }
       public function actionEliminar_factura_cliente($id,$detalle, $token, $tokenAcceso)
       {                                
           $detalle = ReciboCajaDetalles::findOne($detalle);
           $detalle->delete();
           $this->redirect(["view_cliente",'id' => $id, 'token' => $token, 'tokenAcceso' => $tokenAcceso]);        
       }
    
    //proceso que autoriza y desautoriza
    public function actionAutorizado($id, $token, $tokenAcceso) {
         $model = $this->findModel($id);
         if($model->autorizado == 0){
             $model->autorizado = 1;
             $model->save();
         }else{
             $model->autorizado = 0;
             $model->save();
         }
          return $this->redirect(['view_cliente','id' => $id, 'token' => $token, 'tokenAcceso' => $tokenAcceso]);
    }
    
     public function actionAutorizado_admon($id, $token) {
         $model = $this->findModel($id);
         if($model->autorizado == 0){
             $model->autorizado = 1;
             $model->save();
         }else{
             $model->autorizado = 0;
             $model->save();
         }
          return $this->redirect(['view','id' => $id, 'token' => $token]);
    }
    //CREAR EL CONSECUTIVO DEL RECIBO DE PAGO
     public function actionGenerar_recibo_pago($id, $token, $tokenAcceso) {
        //proceso que actuliza saldos
        $this->ActualizarSaldoFacturas($id);
        //proceso de generar consecutivo
        $consecutivo = \app\models\Consecutivos::findOne(7);
        $recibo = ReciboCaja::findOne($id);
        $recibo->numero_recibo = $consecutivo->numero_inicial + 1;
        $recibo->recibo_cerrado = 1;
        $recibo->save(false);
        $consecutivo->numero_inicial = $recibo->numero_recibo;
        $consecutivo->save(false);
        $this->redirect(["view_cliente", 'id' => $id, 'token' => $token, 'tokenAcceso'=> $tokenAcceso]);  
    }
    public function actionGenerar_recibo_pago_admon($id, $token) {
        //proceso que actuliza saldos
        $this->ActualizarSaldoFacturas($id);
        //proceso de generar consecutivo
        $consecutivo = \app\models\Consecutivos::findOne(7);
        $recibo = ReciboCaja::findOne($id);
        $recibo->numero_recibo = $consecutivo->numero_inicial + 1;
        $recibo->recibo_cerrado = 1;
        $recibo->save(false);
        $consecutivo->numero_inicial = $recibo->numero_recibo;
        $consecutivo->save(false);
        $this->redirect(["view", 'id' => $id, 'token' => $token]);  
    }
    //PROCESO QUE ACTUALIZA LOS SALDOS DE LA FACTURA
    protected function ActualizarSaldoFacturas($id) {
        $detalle_recibo = ReciboCajaDetalles::find()->where(['=','id_recibo', $id])->all();
        $recibo = ReciboCaja::findOne($id);
        $contar = 0; 
         foreach ($detalle_recibo as $detalle):
            $saldo = 0; 
            $factura = FacturaVenta::find()->where(['=','id_factura', $detalle->id_factura])->one();
            if($factura){
                $saldo = $detalle->saldo_factura;
                $factura->saldo_factura = $saldo - $detalle->abono_factura;
                if($factura->saldo_factura <= 0){
                    $factura->estado_factura = 2;
                }else{
                    $factura->estado_factura = 1;
                }
                $factura->save(false);
                $contar += $detalle->abono_factura;
                $detalle->saldo_factura = $factura->saldo_factura;
                $detalle->save(false);
             }
         endforeach;
         $recibo->valor_pago = $contar;
         $recibo->save();
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
    
    //IMPRESIONES
    public function actionImprimir_recibo_caja($id) {
        $model = ReciboCaja::findOne($id);
        return $this->render('../formatos/reporte_recibo_caja', [
            'model' => $model,
        ]);
    }
    
    //exportaciones a excel
     public function actionExcelRecibos($tableexcel) { // EXPORTAR PEDIDOS                
            $objPHPExcel = new \PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("EMPRESA")
                ->setLastModifiedBy("EMPRESA")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
            $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'NRO RECIBO')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'MUNICIPIO')
                        ->setCellValue('F1', 'TIPO RECIBO')
                        ->setCellValue('G1', 'FECHA PAGO')
                        ->setCellValue('H1', 'FECHA PROCESO')
                        ->setCellValue('I1', 'BANCO')
                        ->setCellValue('J1', 'VALOR PAGO')
                        ->setCellValue('K1', 'AUTORIZADO')    
                        ->setCellValue('L1', 'USER NAME')
                        ->setCellValue('M1', 'CERRADO')
                        ->setCellValue('N1', 'OBSERVACION');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_recibo)
                        ->setCellValue('B' . $i, $val->numero_recibo)
                        ->setCellValue('C' . $i, $val->clienteRecibo->nit_cedula)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->clienteRecibo->codigoMunicipio->municipio )
                        ->setCellValue('F' . $i, $val->tipo->concepto)
                        ->setCellValue('G' . $i, $val->fecha_pago)
                        ->setCellValue('H' . $i, $val->fecha_proceso)
                        ->setCellValue('I' . $i, $val->codigoBanco->entidad_bancaria)
                        ->setCellValue('J' . $i, $val->valor_pago)
                        ->setCellValue('K' . $i, $val->autorizadoRecibo)
                        ->setCellValue('L' . $i, $val->user_name)
                        ->setCellValue('M' . $i, $val->reciboCerrado)
                        ->setCellValue('N' . $i, $val->observacion);
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Recibos_caja.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->save('php://output');
            exit;
        }
    
    //PROCESO QUE EXPORTA EL DETALLE DEL RECIBO
     public function actionExcel_recibo_detalle($desde, $hasta) { // EXPORTAR PEDIDOS                
       $detalle = ReciboCajaDetalles::find()->where(['between', 'fecha_pago', $desde, $hasta])->all();  
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NRO RECIBO')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'CLIENTE')
                    ->setCellValue('D1', 'MUNICIPIO')
                    ->setCellValue('E1', 'TIPO RECIBO')
                    ->setCellValue('F1', 'FECHA PAGO')
                    ->setCellValue('G1', 'FECHA PROCESO')
                    ->setCellValue('H1', 'BANCO')
                    ->setCellValue('I1', 'VALOR PAGO')
                    ->setCellValue('J1', 'SALDO FACTURA')
                    ->setCellValue('K1', 'NRO FACTURA')
                    ->setCellValue('L1', 'AUTORIZADO')    
                    ->setCellValue('M1', 'USER NAME')
                    ->setCellValue('N1', 'CERRADO')
                    ->setCellValue('O1', 'OBSERVACION');
        $i = 2;

        foreach ($detalle as $val) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->recibo->numero_recibo)
                    ->setCellValue('B' . $i, $val->facturaRecibo->nit_cedula)
                    ->setCellValue('C' . $i, $val->facturaRecibo->cliente)
                    ->setCellValue('D' . $i, $val->recibo->clienteRecibo->codigoMunicipio->municipio)
                    ->setCellValue('E' . $i, $val->recibo->tipo->concepto )
                    ->setCellValue('F' . $i, $val->fecha_pago)
                    ->setCellValue('G' . $i, $val->fecha_registro)
                    ->setCellValue('H' . $i, $val->recibo->codigoBanco->entidad_bancaria)
                    ->setCellValue('I' . $i, $val->abono_factura)
                    ->setCellValue('J' . $i, $val->saldo_factura)
                    ->setCellValue('K' . $i, $val->facturaRecibo->numero_factura)
                    ->setCellValue('L' . $i, $val->recibo->autorizadoRecibo)
                    ->setCellValue('M' . $i, $val->recibo->user_name)
                    ->setCellValue('N' . $i, $val->recibo->reciboCerrado)
                    ->setCellValue('O' . $i, $val->recibo->observacion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Detalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Recibos_caja_detalle.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }    
        
}
