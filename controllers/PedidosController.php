<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\ActiveQuery;
use yii\base\Model;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use Codeception\Lib\HelperModule;
use yii\db\Expression;
use yii\db\Query;
use yii\db\Command;

//models
use app\models\Pedidos;
use app\models\PedidosSearch;
use app\models\UsuarioDetalle;
use app\models\PedidoDetalles;
use app\models\FiltroBusquedaProveedor;
use app\models\Clientes;
use app\models\AgentesComerciales;
use app\models\FiltroBusquedaPedidos;
use app\models\InventarioProductos;
use app\models\FormModeloBuscar;
use app\models\PedidoPresupuestoComercial;
use app\models\FacturaVenta;

/**
 * PedidosController implements the CRUD actions for Pedidos model.
 */
class PedidosController extends Controller
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
     * Lists all Pedidos models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',36])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $pedido_cerrado = null;
                $vendedores = null; $numero_pedido = null;
                $pedido_anulado = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                $tokenAgente = Yii::$app->user->identity->username; 
                $presupuesto = null;
                $vendedor = AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                $empresa = \app\models\MatriculaEmpresa::findOne(1);
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $pedido_cerrado = Html::encode($form->pedido_cerrado);
                        $numero_pedido = Html::encode($form->numero_pedido);
                        //$pedido_anulado = Html::encode($form->pedido_anulado);
                        $presupuesto = Html::encode($form->presupuesto);
                        if($tokenAcceso == 3){
                            $table = Pedidos::find()
                                    ->andFilterWhere(['=', 'documento', $documento])
                                    ->andFilterWhere(['=', 'id_cliente', $cliente])
                                    ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=','cerrar_pedido', $pedido_cerrado])
                                    ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                                    ->andFilterWhere(['=','presupuesto', $presupuesto])
                                    ->andWhere(['=','pedido_liberado', 0])
                                    ->andWhere(['=','id_agente', $vendedor->id_agente]);
                                  
                        }else{
                            $table = Pedidos::find()
                                    ->andFilterWhere(['=', 'documento', $documento])
                                    ->andFilterWhere(['=', 'id_cliente', $cliente])
                                    ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=','cerrar_pedido', $pedido_cerrado])
                                    ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                                    ->andFilterWhere(['=','presupuesto', $presupuesto])
                                    ->andFilterWhere(['=','id_agente', $vendedores])
                                    ->andWhere(['=','pedido_liberado', 0]);
                                   
                        }    
                        $table = $table->orderBy('id_pedido DESC');
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
                            $this->actionExcelconsultaPedidos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    if($tokenAcceso == 3){
                        $table = Pedidos::find()->Where(['=','id_agente', $vendedor->id_agente])
                                               ->andWhere(['=','pedido_liberado', 0])
                                               ->andWhere(['=','pedido_anulado', 0])
                                               ->orderBy('id_pedido DESC');
                    }
                    
                    if($tokenAcceso == 2 || $tokenAcceso == 1){
                       $table = Pedidos::find()->Where(['=','facturado', 0])
                                                ->andWhere(['=','pedido_anulado', 0])->andWhere(['=','pedido_liberado', 0])
                                                ->orderBy('id_pedido DESC');
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
                            $this->actionExcelconsultaPedidos($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                            'tokenAcceso' => $tokenAcceso,
                            'tokenAgente' =>$tokenAgente,
                            'empresa' => $empresa,
                          
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //DESABASTECIMIENTO DEL PRODUCTO
    public function actionSearch_desabastecimiento($token = 4) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',144])->all()){
                $table = PedidoDetalles::find()->Where(['<','cantidad_faltante', 0])
                                               ->orderBy('id_inventario DESC');
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
                        $this->actionExcelconsultaDesabastecimiento($tableexcel);
                }

                   return $this->render('search_desabastecimiento', [
                        'model' => $model,
                        'pagination' => $pages,
                        'token' => $token,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //PEDIDOS PARA VALIDAR EL INVENTARIO Y ENVIAR A LOGISTICA
    public function actionPedidoslistos() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',141])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $pedido_cerrado = null;
                $vendedores = null; $numero_pedido = null;
                $pedido_anulado = null;
                $presupuesto = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $pedido_cerrado = Html::encode($form->pedido_cerrado);
                        $numero_pedido = Html::encode($form->numero_pedido);
                        $pedido_anulado = Html::encode($form->pedido_anulado);
                        $presupuesto = Html::encode($form->presupuesto);
                        $table = Pedidos::find()
                                ->andFilterWhere(['=', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_cliente', $cliente])
                                ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                                ->andFilterWhere(['=','cerrar_pedido', $pedido_cerrado])
                                ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                                ->andFilterWhere(['=','presupuesto', $presupuesto])
                                ->andFilterWhere(['=','pedido_anulado', $pedido_anulado])
                                ->andFilterWhere(['=','id_agente', $vendedores])
                                ->andWhere(['=','pedido_liberado', 1])
                                ->andWhere(['=','facturado', 0]);
                        $table = $table->orderBy('id_pedido DESC');
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
                            $this->actionExcelconsultaPedidos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                     $table = Pedidos::find()->Where(['=','facturado', 0])->andWhere(['=','pedido_liberado', 1])->orderBy('id_pedido DESC');
                                 
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
                            $this->actionExcelconsultaPedidos($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('pedido_liberado_inventario', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                         
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //CREAR PEDIDOS A CLIENTES
     public function actionListado_clientes() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',35])->all()){
                $form = new FiltroBusquedaProveedor();
                $nitcedula = null;
                $nombre_completo = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                $vendedor = AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $nitcedula = Html::encode($form->nitcedula);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        if($tokenAcceso == 3){
                            $table = Clientes::find()
                                    ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                    ->andFilterWhere(['like', 'nombre_completo', $nombre_completo])
                                    ->andWhere(['=', 'estado_cliente', 0])
                                    ->andWhere(['=','id_agente', $vendedor->id_agente]);
                        }
                        if($tokenAcceso == 1 || $tokenAcceso == 2){
                            $table = Clientes::find()
                                ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                ->andFilterWhere(['like', 'nombre_completo', $nombre_completo])
                                ->andWhere(['=','id_agente', $vendedor->id_agente])    
                                ->andWhere(['=', 'estado_cliente', 0]);
                                 
                        }    
                        $table = $table->orderBy('nombre_completo ASC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 10,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                    ->all();
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaClientes($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    if($tokenAcceso == 3){
                        $table = Clientes::find()->where(['=','estado_cliente', 0])
                            ->andWhere(['=','id_agente', $vendedor->id_agente])
                            ->orderBy('nombre_completo ASC');
                    }
                    if($tokenAcceso == 1 || $tokenAcceso == 2){
                        $table = Clientes::find()->where(['=','estado_cliente', 0])
                            ->andWhere(['=','id_agente', $vendedor->id_agente])
                            ->orderBy('nombre_completo ASC');   
                    }
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaClientes($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('listado_clientes', [
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
    //CONSULA DE PEDIDOS
    public function actionSearch_pedidos($token = 2) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',47])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $facturado = null; $pedido_cerrado = null;
                $vendedores = null; $numero_pedido = null;
                $presupuesto = null;
                $model = null;
                $pages = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                $documento_vendedor = AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $facturado = Html::encode($form->facturado);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $pedido_cerrado = Html::encode($form->pedido_cerrado);
                        $numero_pedido = Html::encode($form->numero_pedido);
                        $presupuesto = Html::encode($form->presupuesto);
                        if($tokenAcceso == 2 || $tokenAcceso == 1 ){ 
                            $table = Pedidos::find()
                                ->andFilterWhere(['=', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_cliente', $cliente])
                                ->andFilterWhere(['=', 'facturado', $facturado])
                                ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                                ->andFilterWhere(['=','cerrar_pedido', $pedido_cerrado])
                                ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                                 ->andFilterWhere(['=','presupuesto', $presupuesto])
                                ->andFilterWhere(['=','id_agente', $vendedores])
                                 ->andWhere(['=','autorizado', 1]) ;
                        }else{
                           $table = Pedidos::find()
                                ->andFilterWhere(['=', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_cliente', $cliente])
                                ->andFilterWhere(['=', 'facturado', $facturado])
                                ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                                ->andFilterWhere(['=','cerrar_pedido', $pedido_cerrado])
                                ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                                ->andFilterWhere(['=','presupuesto', $presupuesto])
                                ->andWhere(['=','autorizado', 1])
                                ->andWhere(['=','id_agente', $documento_vendedor->id_agente]); 
                        }    
                        $table = $table->orderBy('id_pedido DESC');
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
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaPedidos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } 
                return $this->render('search_pedidos', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                            'tokenAcceso' => $tokenAcceso,
                            'documento_vendedor' => $documento_vendedor,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //permite mostrar los pedidos por vendedor
    public function actionSearch_pedido_vendedor($token = 3) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',143])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $facturado = null; $pedido_cerrado = null;
                $vendedores = null; $numero_pedido = null;
                $presupuesto = null;
                $model = null;
                $pages = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                $documento_vendedor = AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $facturado = Html::encode($form->facturado);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $pedido_cerrado = Html::encode($form->pedido_cerrado);
                        $numero_pedido = Html::encode($form->numero_pedido);
                        $presupuesto = Html::encode($form->presupuesto);
                        $table = Pedidos::find()
                                ->andFilterWhere(['=', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_cliente', $cliente])
                                ->andFilterWhere(['=', 'facturado', $facturado])
                                ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                                ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                                ->andFilterWhere(['=','presupuesto', $presupuesto])
                                ->andWhere(['=','cerrar_pedido', 1])
                                ->andWhere(['=','id_agente', $documento_vendedor->id_agente]); 
                            
                        $table = $table->orderBy('id_pedido DESC');
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
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaPedidos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } 
                return $this->render('search_pedido_vendedor', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                            'tokenAcceso' => $tokenAcceso,
                            'documento_vendedor' => $documento_vendedor,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    // MAESTRO CONSULTA DE VENTAS, PEDIDOS, CLIENTES IA
    public function actionSearch_maestro_pedidos() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',80])->all()){
                $form = new \app\models\ModelBusquedaAvanzada();
                $hasta = null;
                $desde = null;
                $busqueda = null; 
                $model = null;
                $pages = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $busqueda = Html::encode($form->busqueda);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        if($busqueda <> null && $desde <> null && $hasta <> null){
                            if($busqueda == 1){
                                $query =new Query();
                                $table = FacturaVenta::find()->select([new Expression('SUM(subtotal_factura) as subtotal_factura, cliente, nit_cedula, total_factura,id_agente'), 'id_cliente'])
                                            ->where(['between','fecha_inicio', $desde, $hasta])
                                            ->groupBy('id_cliente')
                                            ->orderBy('subtotal_factura DESC')
                                            ->limit (1)
                                            ->all();       
                            }else{
                                if($busqueda == 2){
                                    $query =new Query();
                                    $table = FacturaVenta::find()->select([new Expression('SUM(subtotal_factura) as subtotal_factura, cliente, nit_cedula, total_factura,id_agente,id_cliente'), 'id_agente'])
                                                ->where(['between','fecha_inicio', $desde, $hasta])
                                                ->groupBy('id_agente')
                                                ->orderBy('subtotal_factura ASC')
                                                ->limit (1)
                                                ->all();       
                                }else{
                                    $query =new Query();
                                    $table = \app\models\FacturaVentaDetalle::find()->select([new Expression('SUM(cantidad) as cantidad, id_inventario, codigo_producto, producto'), 'id_inventario'])
                                                ->where(['between','fecha_venta', $desde, $hasta])
                                                ->groupBy('id_inventario')
                                                ->orderBy('cantidad DESC')
                                                ->limit (1)
                                                ->all();  
                                }   
                            } 
                            $model = $table;
                        }else{
                            Yii::$app->getSession()->setFlash('info', 'Debe de seleccionar el tipo de busqueda y las fechas. Favor validar la informacion.'); 
                            return $this->redirect(['search_maestro_pedidos']);
                        }
                    } else {
                        $form->getErrors();
                    }
                } 
                return $this->render('search_maestro_detalle', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'desde' => $desde,
                            'hasta' => $hasta,
                            'busqueda' => $busqueda,
                           
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //ANULAR PEDIDO
    public function actionAnular_pedidos() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',48])->all()){
                $model = null;
                $pages = null;
                $table = Pedidos::find()->Where(['=','autorizado', 1])->andWhere(['=','cerrar_pedido', 1])
                                       ->andWhere(['=','pedido_anulado', 0])
                                       ->andWhere(['=','pedido_virtual', 0])
                                       ->andWhere(['=','facturado', 0])->orderBy('id_pedido DESC');
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
                        $this->actionExcelconsultaPedidos($tableexcel);
                }
                 return $this->render('anular_pedidos', [
                        'model' => $model,
                        'pagination' => $pages,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }           
        }else{
            return $this->redirect(['site/login']);
        }
      }
    
    //PROCESO QUE CREA NUEVO PEDIDO
    public function actionCrear_nuevo_pedido($id, $tipo_pedido) {
        //valide cupo
        $cliente = Clientes::find()->where(['=','id_cliente', $id])->one();
        if($cliente->formaPago->codigo_api !== 4){
            $contar = 0;
            $fecha_actual = date('Y-m-d');
            if($cliente->presupuesto_comercial > 0){
                $factura_mora = FacturaVenta::find()->where(['=','id_cliente', $id])->andWhere(['>','saldo_factura', 0])
                                                ->andWhere(['<','fecha_vencimiento', $fecha_actual])->orderBy('id_factura ASC')->one();
                
                if(!$factura_mora || $cliente->aplicar_venta_mora == 1){
                    $tipoPedidoGenerado = $cliente->tipoCliente->abreviatura;
                    $table = new Pedidos();
                    $table->id_cliente = $id;
                    $table->documento = $cliente->nit_cedula;
                    $table->dv = $cliente->dv;
                    $table->cliente = $cliente->nombre_completo;
                    $table->usuario = Yii::$app->user->identity->username;
                    $table->fecha_proceso = date('Y-m-d');
                    $table->id_agente = $cliente->id_agente;
                    if($tipoPedidoGenerado == 'I'){
                            $table->tipo_pedido = 2;
                    }else{
                            $table->tipo_pedido = 1;
                    }
                    if($tipo_pedido == 0){
                        $table->liberado_inventario = 1;
                    }   
                    $table->save(false);
                    return $this->redirect(['/pedidos/index']);
                }else{
                    Yii::$app->getSession()->setFlash('error', 'El cliente '.$cliente->nombre_completo.' Se encuentra en mora con la factura No '. $factura_mora->numero_factura .' por un valor de ( $'.number_format($factura_mora->saldo_factura).'). Favor contactar a cartera.'); 
                    return $this->redirect(['listado_clientes']);
                }    
            }else{
                Yii::$app->getSession()->setFlash('warning', 'El cliente '.$cliente->nombre_completo.' NO tiene presupuesto comercial. Favor contactar a cartera.'); 
                return $this->redirect(['listado_clientes']);
            }
           
       }else{
            if($cliente->cupo_asignado > 0){
                $contar = 0;
                $fecha_actual = date('Y-m-d');
                $facturas = FacturaVenta::find()->where(['=','id_cliente', $id])->andWhere(['>','saldo_factura', 0])->all();
                foreach ($facturas as $factura):
                   $contar += $factura->saldo_factura;
                endforeach;
                if($cliente->cupo_asignado >= $contar ){
                    $factura_mora = FacturaVenta::find()->where(['=','id_cliente', $id])->andWhere(['>','saldo_factura', 0])
                                                    ->andWhere(['<','fecha_vencimiento', $fecha_actual])->orderBy('id_factura ASC')->one();

                    if(!$factura_mora || $cliente->aplicar_venta_mora == 1){
                        $tipoPedidoGenerado = $cliente->tipoCliente->abreviatura;
                        $table = new Pedidos();
                        $table->id_cliente = $id;
                        $table->documento = $cliente->nit_cedula;
                        $table->dv = $cliente->dv;
                        $table->cliente = $cliente->nombre_completo;
                        $table->usuario = Yii::$app->user->identity->username;
                        $table->fecha_proceso = date('Y-m-d');
                        if($tipoPedidoGenerado == 'I'){
                            $table->tipo_pedido = 2;
                        }else{
                            $table->tipo_pedido = 1;
                        }
                        $table->id_agente = $cliente->id_agente;
                        if($tipo_pedido == 0){
                           $table->liberado_inventario = 1;
                        } 
                        $table->save();
                        return $this->redirect(['/pedidos/index']);
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'El cliente '.$cliente->nombre_completo.' Se encuentra en mora con la factura No '. $factura_mora->numero_factura .' por un valor de ( $'.number_format($factura_mora->saldo_factura).'). Favor contactar a cartera.'); 
                        return $this->redirect(['listado_clientes']);
                    } 
                }else{
                    Yii::$app->getSession()->setFlash('warning', 'El cliente '.$cliente->nombre_completo.' NO tiene mas cupo para hacer pedidos. Favor contactar a cartera.'); 
                    return $this->redirect(['listado_clientes']);
                }        
           }else{
               Yii::$app->getSession()->setFlash('warning', 'El cliente NO TIENE cupo asignado. Contactar al administrador del proceso.'); 
               return $this->redirect(['listado_clientes']);
           }
       }
    }
    
    public function actionPedido_virtual() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',36])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; $numero_pedido = null;
                $pedido_anulado = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $numero_pedido = Html::encode($form->numero_pedido);
                        $table = Pedidos::find()
                                ->andFilterWhere(['=', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_cliente', $cliente])
                                ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                                ->andFilterWhere(['=','id_agente', $vendedores])
                                ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                                ->andWhere(['=','pedido_anulado', 0])
                                ->andWhere(['=','pedido_virtual', 1])
                                ->andWhere(['=','cerrar_pedido', 1])
                                 ->andWhere(['=','facturado', 0]);
                        $table = $table->orderBy('id_pedido DESC');
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
                            $this->actionExcelconsultaPedidos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = Pedidos::find()->Where(['=','cerrar_pedido', 1])
                                            ->andWhere(['=','pedido_anulado', 0])
                                            ->andWhere(['=','pedido_virtual', 1])
                                             ->andWhere(['=','facturado', 0])->orderBy('id_pedido DESC');
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
                            $this->actionExcelconsultaPedidos($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('pedido_virtual', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    /**
     * Displays a single Pedidos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token = 0)
    {
        $pedido_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        $model = Pedidos::findOne($id);
        $detalle_pedido = PedidoDetalles::find()->where(['=','id_pedido', $id])->all();
        $cliente = Clientes::find()->where(['=','id_cliente', $model->id_cliente])->one();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_pedido' => $detalle_pedido,
            'pedido_presupuesto' => $pedido_presupuesto,
            'cliente' => $cliente,
        ]);   
    }
    
    //VISTA QUE PERMITE MOSTRAR LOS PEDIDOS VIRTUALES
    public function actionView_pedido_virtual($id, $idToken) {
        $pedido_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        $detalle_pedido = PedidoDetalles::find()->where(['=','id_pedido', $id])->all();
        return $this->render('view_pedido_virtual', [
            'model' => $this->findModel($id),
            'detalle_pedido' => $detalle_pedido,
            'pedido_presupuesto' => $pedido_presupuesto,
            'idToken' => $idToken,
        ]);     
    }
    
    //VISTA DE ANULAR PEDIDO Y DETALLES DEL PRESUPUESTO
    public function actionView_anular($id, $pedido_virtual) {
        $pedido_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        $model = Pedidos::findOne($id);
        $detalle_pedido = PedidoDetalles::find()->where(['=','id_pedido', $id])->all(); 
        //PROCESO QUE ELIMINAR EL PRESUPUESTO
        if (isset($_POST["eliminar_presupuesto"])) {
            if (isset($_POST["detalle_presupuesto"])) {
                $intIndice = 0;
                $detalle = 0;
                foreach ($_POST["detalle_presupuesto"] as $intCodigo) {
                    $detalle = $intCodigo;
                    $presupuesto = PedidoPresupuestoComercial::findOne($intCodigo);
                    $this->DevolucionProductosPresupuesto($id, $detalle, $pedido_virtual);
                    $presupuesto->registro_eliminado = 1;
                    $presupuesto->save();
                    $this->redirect(["view_anular",'id' => $id, 'pedido_virtual' => $pedido_virtual]); 
                }       
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe de seleccionar los registros a eliminar.'); 
                return $this->redirect(['view_anular','id' => $id, 'pedido_virtual' => $pedido_virtual]);
            } 
        }    
        //PROCESO QUE ELIMINAR DETALLES DE PEDIDO
        if (isset($_POST["eliminar_pedido"])) {
            if (isset($_POST["detalle_pedido"])) {
                $intIndice = 0;
                $detalle = 0;
                foreach ($_POST["detalle_pedido"] as $intCodigo) {
                    $eliminar = PedidoDetalles::findOne($intCodigo);
                    $detalle = $intCodigo;
                    $this->DevolucionProductosInventario($id, $detalle, $pedido_virtual);
                    $detalle = $eliminar->id_inventario;
                    $this->ActualizarTotalesProducto($detalle);
                    $eliminar->registro_eliminado = 1;
                    $eliminar->save(false);
                    $this->redirect(["view_anular",'id' => $id, 'pedido_virtual' => $pedido_virtual]); 
                }       
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe de seleccionar los registros a eliminar.'); 
                return $this->redirect(['view_anular','id' => $id, 'pedido_virtual' => $pedido_virtual]);
            } 
        }    
        
        return $this->render('view_anulado', [
            'model' => $this->findModel($id),
            'detalle_pedido' => $detalle_pedido,
            'pedido_presupuesto' => $pedido_presupuesto,
            'pedido_virtual' => $pedido_virtual,
         
        ]);   
    }
    
    //VISTA QUE MUESTRA EL DETALLE DE LOS PEDIDOS QUE LES FALTA UNIDADES
    public function actionView_desabastecimiento($id_inventario)
    {
        $listado = PedidoDetalles::find()->where(['=','id_inventario', $id_inventario])->andWhere(['<','cantidad_faltante', 0])->all();
        $model = InventarioProductos::findOne($id_inventario);
        return $this->render('view_desabastecimiento' ,[
            'id_inventario' => $id_inventario,
            'listado' => $listado,
            'model' => $model,
            ]);
    }
    
   //PROCESO QUE EDITA EL CLIENTE
     public function actionEditarcliente($id, $tokenAcceso) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = Pedidos::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["editarcliente"])) { 
                $conCliente = Clientes::find()->where(['=','id_cliente', $model->cliente])->one();
                $empresa = \app\models\MatriculaEmpresa::findOne(1);
                if ($conCliente->formaPago->codigo_api !== 4){
                    $table->id_cliente = $model->cliente;
                    $table->documento = $conCliente->nit_cedula;
                    $table->dv = $conCliente->dv;
                    $table->cliente = $conCliente->nombre_completo;
                   
                    $table->tipo_pedido = $model->tipopedido;
                    if($empresa->inventario_enlinea == 0){
                        $table->pedido_virtual = $model->pedido_virtual;
                        if($model->pedido_virtual == 1 ){
                            $table->liberado_inventario = 0;
                        }else{
                            $table->liberado_inventario = 1;
                        }
                       
                    }  
                     $table->save(false);
                    $this->redirect(["pedidos/index"]);
                }else{
                    if ($conCliente->cupo_asignado > 0){
                        $table->id_cliente = $model->cliente;
                        $table->documento = $conCliente->nit_cedula;
                        $table->dv = $conCliente->dv;
                        $table->cliente = $conCliente->nombre_completo;
                        $table->tipo_pedido = $model->tipopedido;
                        if($empresa->inventario_enlinea == 0){
                            $table->pedido_virtual = $model->pedido_virtual;                        
                            if ($model->pedido_virtual == 1) {
                                $table->liberado_inventario = 0;
                            } else {
                                $table->liberado_inventario = 1;
                            }
                        }    
                        $table->save(false);
                        $this->redirect(["pedidos/index"]);
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'Este cliente se le vende a credito y no tiene asignado un CUPO DE CREDITO. Validar la informacion con cartera.'); 
                        return $this->redirect(['index']);
                    }
                } 
            }    
        }
         if (Yii::$app->request->get()) {
            $model->cliente = $table->id_cliente;
            $model->pedido_virtual = $table->pedido_virtual;
            $model->tipopedido = $table->tipo_pedido;
         }
        return $this->renderAjax('editarcliente', [
            'model' => $model,
            'id' => $id,
            'tokenAcceso' => $tokenAcceso,
            'agente_comercial' => $table->id_agente,
        ]);
    }
    
    //PROCESO QUE MUESTRAS EL LISTADO DE INVENTARIO ACTIVO y PERITE PEDIDO VIRTUAL Y PEDIDO EN LINEA
    public function actionAdicionar_productos($id, $tokenAcceso, $token, $pedido_virtual, $tipo_pedido) {
        $pedido_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        $model = Pedidos::findOne($id);
        $cliente = Clientes::find()->where(['=','id_cliente', $model->id_cliente])->one();
        $detalle_pedido = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id])->all();
        $form = new FormModeloBuscar();
        $q = null;
        $nombre = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                $nombre = Html::encode($form->nombre); 
                if($pedido_virtual == 0){
                    if($q == ''){
                        $conSql = InventarioProductos::find()
                                ->Where(['like','nombre_producto', $nombre])
                                ->andwhere(['=','venta_publico', 0])
                                ->andwhere(['>','stock_unidades', 0])
                                ->andWhere(['=','activar_producto_venta', 1]);
                    }else{
                        $conSql = InventarioProductos::find()
                            ->where(['=','codigo_producto', $q])
                            ->andwhere(['=','venta_publico', 0])
                            ->andwhere(['>','stock_unidades', 0])
                            ->andWhere(['=','activar_producto_venta', 1]);
                    }    
                }else{
                    if($q == ''){
                        $conSql = InventarioProductos::find()
                                ->Where(['like','nombre_producto', $nombre])
                                ->andwhere(['=','venta_publico', 0])
                                ->andwhere(['<=','stock_unidades', 0])
                                ->andWhere(['=','activar_producto_venta', 1]);
                    }else{
                        $conSql = InventarioProductos::find()
                            ->where(['=','codigo_producto', $q])
                            ->andwhere(['=','venta_publico', 0])
                            ->andwhere(['<=','stock_unidades', 0])
                            ->andWhere(['=','activar_producto_venta', 1]);
                    }    
                }    
                $conSql = $conSql->orderBy('nombre_producto ASC');  
                $count = clone $conSql;
                $to = $count->count();
                $pages = new Pagination([
                    'pageSize' => 7,
                    'totalCount' => $count->count()
                ]);
                $variable = $conSql
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            if($pedido_virtual == 0){
                $inventario = InventarioProductos::find()->where(['=','venta_publico', 0])
                                                 ->andWhere(['>','stock_unidades', 0])
                                                 ->andWhere(['=','activar_producto_venta', 1])->orderBy('nombre_producto ASC');
            }else{
                $inventario = InventarioProductos::find()->where(['=','venta_publico', 0])
                                                 ->andWhere(['<=','stock_unidades', 0])
                                                 ->andWhere(['=','activar_producto_venta', 1])->orderBy('nombre_producto ASC');  
            }    
            $tableexcel = $inventario->all();
            $count = clone $inventario;
            $pages = new Pagination([
                        'pageSize' => 7,
                        'totalCount' => $count->count(),
            ]);
             $variable = $inventario
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        if (isset($_POST["cargar_producto"])) {
            if(isset($_POST["nuevo_producto"])){
                $intIndice = 0;
                foreach ($_POST["nuevo_producto"] as $intCodigo) {
                    $ingreso = \app\models\PedidoDetalles::find()
                            ->where(['=', 'id_inventario', $intCodigo])
                            ->andWhere(['=', 'id_pedido', $id])
                            ->all();
                    $reg = count($ingreso);
                    if($reg == 0){
                        $dato = 0;
                        $dato = $model->clientePedido->id_posicion;
                        if($_POST["cantidad_productos"]["$intIndice"] > 0){
                            $precio = \app\models\InventarioPrecioVenta::find()->where(['=','id_posicion', $dato])
                                                           ->andWhere(['=','id_inventario', $intCodigo])->one();
                            if($precio){
                                $valor = 0; $datos = 0;
                                $producto = InventarioProductos::findOne($intCodigo);
                                $valor = $producto->stock_unidades;
                                if($pedido_virtual == 0){
                                    if($_POST["cantidad_productos"]["$intIndice"] <= $valor ){
                                        $table = new \app\models\PedidoDetalles();
                                        $table->id_pedido = $id;
                                        $table->id_inventario = $intCodigo;
                                        $table->cantidad = $_POST["cantidad_productos"]["$intIndice"];
                                        $table->user_name = Yii::$app->user->identity->username;
                                        $table->cargar_existencias = 1;
                                        $table->save(false);
                                        $datos = $intCodigo;
                                        $pedido = Pedidos::findOne($id);
                                        $pedido->liberado_inventario = 1;
                                        $pedido->save();
                                        $this->ActualizarInventarioPrecio($datos, $id, $token, $pedido_virtual, $tipo_pedido);
                                        $this->ActualizarTotalesPedido($id);
                                    }else{
                                        Yii::$app->getSession()->setFlash('error', 'Las unidades vendidas es mayor que el STOCK de inventarios. Favor validar las cantidades.');
                                        return $this->redirect(['adicionar_productos','id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' =>$token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                                    }  
                                }else{
                                    $table = new \app\models\PedidoDetalles();
                                    $table->id_pedido = $id;
                                    $table->id_inventario = $intCodigo;
                                    $table->cantidad = $_POST["cantidad_productos"]["$intIndice"];
                                    $table->user_name = Yii::$app->user->identity->username;
                                    $table->save(false);
                                    $datos = $intCodigo;
                                    $this->ActualizarInventarioPrecio($datos, $id, $token, $pedido_virtual, $tipo_pedido);
                                    $this->ActualizarTotalesPedido($id); 
                                }    
                            }else{
                                Yii::$app->getSession()->setFlash('warning', 'El producto no tiene precio de venta al publico. Contactar al administrador.');
                                return $this->redirect(['adicionar_productos','id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' =>$token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                            }
                        }    
                    }    
                     $intIndice++;
                }
                return $this->redirect(['adicionar_productos','id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
            }
        }
        return $this->render('listado_productos', [ 
            'id' => $id,
            'variable' => $variable,
            'tokenAcceso' => $tokenAcceso,
            'model' => $model,
            'form' => $form,
             'pagination' => $pages,
            'detalle_pedido' => $detalle_pedido,
            'token' => $token,
            'pedido_presupuesto' => $pedido_presupuesto,
            'cliente' => $cliente,
            'pedido_virtual' => $pedido_virtual,
            'tipo_pedido' => $tipo_pedido,
        ]);
    }
    
    //PROCESO QUE MUESTRAS EL LISTADO DE INVENTARIO ACTIVO y PERITE PEDIDO VIRTUAL Y PEDIDO EN LINEA
    public function actionAdicionar_producto_pedido($id, $tokenAcceso, $token, $pedido_virtual, $tipo_pedido) {
        $pedido_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        $model = Pedidos::findOne($id);
        $cliente = Clientes::find()->where(['=','id_cliente', $model->id_cliente])->one();
        $detalle_pedido = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id])->all();
        $form = new FormModeloBuscar();
        $q = null;
        $nombre = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                $nombre = Html::encode($form->nombre); 
                if($q == ''){
                    $conSql = InventarioProductos::find()
                            ->Where(['like','nombre_producto', $nombre])
                            ->andwhere(['=','venta_publico', 0])
                            ->andWhere(['=','activar_producto_venta', 1]);
                }else{
                    $conSql = InventarioProductos::find()
                        ->where(['=','codigo_producto', $q])
                        ->andwhere(['=','venta_publico', 0])
                        ->andWhere(['=','activar_producto_venta', 1]);
                }    
                 $conSql = $conSql->orderBy('nombre_producto ASC');  
                $count = clone $conSql;
                $to = $count->count();
                $pages = new Pagination([
                    'pageSize' => 7,
                    'totalCount' => $count->count()
                ]);
                $variable = $conSql
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            $inventario = InventarioProductos::find()->where(['=','venta_publico', 0])
                                                     ->andWhere(['=','activar_producto_venta', 1])->orderBy('nombre_producto ASC');
            $tableexcel = $inventario->all();
            $count = clone $inventario;
            $pages = new Pagination([
                        'pageSize' => 7,
                        'totalCount' => $count->count(),
            ]);
             $variable = $inventario
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        if (isset($_POST["cargar_producto"])) {
            if(isset($_POST["nuevo_producto"])){
                $intIndice = 0;
                foreach ($_POST["nuevo_producto"] as $intCodigo) {
                    $ingreso = \app\models\PedidoDetalles::find()
                            ->where(['=', 'id_inventario', $intCodigo])
                            ->andWhere(['=', 'id_pedido', $id])
                            ->all();
                    $reg = count($ingreso);
                    if($reg == 0){
                        $dato = 0;
                        $dato = $model->clientePedido->id_posicion;
                        if($_POST["cantidad_productos"]["$intIndice"] > 0){
                            $precio = \app\models\InventarioPrecioVenta::find()->where(['=','id_posicion', $dato])
                                                           ->andWhere(['=','id_inventario', $intCodigo])->one();
                            if($precio){
                                $valor = 0; $datos = 0;
                                $producto = InventarioProductos::findOne($intCodigo);
                                $table = new \app\models\PedidoDetalles();
                                $table->id_pedido = $id;
                                $table->id_inventario = $intCodigo;
                                $table->cantidad = $_POST["cantidad_productos"]["$intIndice"];
                                $table->user_name = Yii::$app->user->identity->username;
                                $table->cargar_existencias = 1;
                                $table->venta_condicionado = $model->tipoPedido->codigo_interface;
                                $table->save(false);
                                $datos = $intCodigo;
                                $this->ActualizarInventarioPrecio($datos, $id, $token, $pedido_virtual, $tipo_pedido);
                                $this->ActualizarTotalesPedido($id);
                            }else{
                                Yii::$app->getSession()->setFlash('warning', 'El producto no tiene precio de venta al publico. Contactar al administrador.');
                                return $this->redirect(['adicionar_producto_pedido','id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' =>$token, 'pedido_virtual' => $pedido_virtual,'tipo_pedido' => $tipo_pedido]);
                            }
                        }    
                    }    
                     $intIndice++;
                }
                return $this->redirect(['adicionar_producto_pedido','id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
            }
        }
        return $this->render('listado_productos', [ 
            'id' => $id,
            'variable' => $variable,
            'tokenAcceso' => $tokenAcceso,
            'model' => $model,
            'form' => $form,
             'pagination' => $pages,
            'detalle_pedido' => $detalle_pedido,
            'token' => $token,
            'pedido_presupuesto' => $pedido_presupuesto,
            'cliente' => $cliente,
            'pedido_virtual' => $pedido_virtual,
            'tipo_pedido' => $tipo_pedido,
        ]);
    }

    //ADICIONAR PRODUCTOS A PRESUPUESTO A TRAVES DE LA REGLA DEL PRODUCTO
    public function actionCrear_regla_pedido($id, $tokenAcceso, $token, $sw, $id_inventario, $id_cliente, $pedido_virtual, $tipo_pedido) {
        //consulta para no duplicar
        $cliente = Clientes::findOne($id_cliente);
        if($cliente->presupuesto_comercial == 0 ){
            Yii::$app->getSession()->setFlash('info', 'El cliente '.$cliente->nombre_completo.' NO tiene presupuesto comercial asignado. Contactar al representante de ventas');  
            if($tipo_pedido == 0){
                return $this->redirect(['adicionar_productos','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual,'tipo_pedido' => $tipo_pedido]); 
            }else{
               return $this->redirect(['adicionar_producto_pedido','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual,'tipo_pedido' => $tipo_pedido]);     
            }    
        }else{    
            $model = Pedidos::findOne($id);
            $pedido_detalle = PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['=','id_inventario', $id_inventario])->one(); //permite buscar la cantidad de unidades
            $registro = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])
                                                           ->andWhere(['=','id_inventario', $id_inventario])->one();
            if(!$registro){
                $cantidad = 0;
                $regla = \app\models\ProductoReglaComercial::find()->where(['=','estado_regla', 0])->andWhere(['=','id_inventario', $id_inventario])->one();
                if ($pedido_detalle->cantidad % $regla->limite_venta == 0){
                    $cantidad =  ($pedido_detalle->cantidad * $regla->limite_presupuesto)/$regla->limite_venta;    
                }else{
                    $cantidad = floor(($pedido_detalle->cantidad * $regla->limite_presupuesto)/$regla->limite_venta);     
                }
                $producto = InventarioProductos::findOne($id_inventario);
                $presupuesto = \app\models\PresupuestoEmpresarial::findOne(1);
                if($presupuesto){
                    $table = new PedidoPresupuestoComercial();
                    $table->id_pedido = $id;
                    $table->id_inventario = $id_inventario;
                    $table->id_presupuesto = $presupuesto->id_presupuesto;
                    $table->cantidad = $cantidad;
                    $table->venta_condicionado = 'B';
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->fecha_registro = date('Y-m-d');
                    if($model->pedido_virtual == 0){
                        $table->cargar_existencias = 1;
                    }
                    $table->save(false);
                    $datos = $id_inventario;
                    $token = 0;
                    $this->ActualizarInventarioPrecio($datos, $id, $token, $pedido_virtual, $tipo_pedido);
                    $this->TotalPresupuestoPedido($id, $sw);
                    if($tipo_pedido == 0){
                        return $this->redirect(['adicionar_productos','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }else{
                        return $this->redirect(['adicionar_producto_pedido','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }    
                }else{
                    Yii::$app->getSession()->setFlash('warning', 'Para aplicar la regla comercial, se deben de crear el  presupuesto comercial.');
                    if($tipo_pedido == 0){
                        return $this->redirect(['adicionar_productos','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                    }else{
                        return $this->redirect(['adicionar_producto_pedido','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                    }    
                }    
            } else{
                Yii::$app->getSession()->setFlash('info', 'Este producto ya esta ingresado en el presupuesto comercial.');
                if($tipo_pedido == 0){
                    return $this->redirect(['adicionar_productos','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                }else{
                    return $this->redirect(['adicionar_producto_pedido','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                }  
            }   
        }    
    }
    
    //PROCESO QUE INCORPORA PRESUPUESTO AL PEDID
    public function actionAdicionar_presupuesto($id, $token, $sw, $tokenAcceso, $pedido_virtual, $tipo_pedido) {
        $model = Pedidos::findOne($id);
        if($tipo_pedido == 0){
            $inventario = InventarioProductos::find()->where(['=','venta_publico', 0])
                                                     ->andWhere(['>','stock_unidades', 0])->andWhere(['=','aplica_presupuesto', 1])
                                                     ->andWhere(['=','activar_producto_venta', 1])->orderBy('nombre_producto ASC')->all();
        }else{
            $inventario = InventarioProductos::find()->where(['=','venta_publico', 0])
                                                     ->andWhere(['=','aplica_presupuesto', 1])
                                                     ->andWhere(['=','activar_producto_venta', 1])->orderBy('nombre_producto ASC')->all();
        }    
        $form = new FormModeloBuscar();
        $q = null;
        $nombre = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                $nombre = Html::encode($form->nombre);  
                if($tipo_pedido == 0){
                    if($q == ''){
                        $conSql = InventarioProductos::find()
                                ->Where(['like','nombre_producto', $nombre])
                                ->andwhere(['=','venta_publico', 0])
                                ->andwhere(['=','aplica_presupuesto', 1])
                                ->andwhere(['>','stock_unidades', 0]);
                    }else{
                        $conSql = InventarioProductos::find()
                            ->where(['=','codigo_producto', $q])
                            ->andwhere(['=','venta_publico', 0])
                            ->andwhere(['=','aplica_presupuesto', 1])    
                            ->andwhere(['>','stock_unidades', 0]);
                    } 
                }else{
                    if($q == ''){
                        $conSql = InventarioProductos::find()
                                ->Where(['like','nombre_producto', $nombre])
                                ->andwhere(['=','venta_publico', 0])
                                ->andwhere(['=','aplica_presupuesto', 1]);
                               
                    }else{
                        $conSql = InventarioProductos::find()
                            ->where(['=','codigo_producto', $q])
                            ->andwhere(['=','venta_publico', 0])
                            ->andwhere(['=','aplica_presupuesto', 1]);    
                    }    
                }    
                $conSql = $conSql->orderBy('nombre_producto ASC');  
                $count = clone $conSql;
                $to = $count->count();
                $pages = new Pagination([
                    'pageSize' => 6,
                    'totalCount' => $count->count()
                ]);
                $variable = $conSql
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            if($tipo_pedido == 0){
                $inventario = InventarioProductos::find()->where(['=','venta_publico', 0])
                                                 ->andWhere(['>','stock_unidades', 0])->andWhere(['=','aplica_presupuesto', 1])
                                                 ->andWhere(['=','activar_producto_venta', 1])
                                                 ->orderBy('nombre_producto ASC');
            }else{
                 $inventario = InventarioProductos::find()->where(['=','venta_publico', 0])
                                                 ->andWhere(['=','aplica_presupuesto', 1])
                                                 ->andWhere(['=','activar_producto_venta', 1])
                                                 ->orderBy('nombre_producto ASC');
            }    
            $tableexcel = $inventario->all();
            $count = clone $inventario;
            $pages = new Pagination([
                        'pageSize' => 6,
                        'totalCount' => $count->count(),
            ]);
             $variable = $inventario
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        if (isset($_POST["importar_producto_presupuesto"])) {
            if(isset($_POST["nuevo_producto_presupueso"])){
                 $intIndice = 0;
                foreach ($_POST["nuevo_producto_presupueso"] as $intCodigo):
                    //consulta para no duplicar
                    $registro = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])
                                                                   ->andWhere(['=','id_inventario', $intCodigo])->one();
                    if(!$registro){
                        $dato = 0;
                        $dato = $model->clientePedido->id_posicion;
                        if($_POST["cantidades"]["$intIndice"] > 0){
                            $precio = \app\models\InventarioPrecioVenta::find()->where(['=','id_posicion', $dato])
                                                           ->andWhere(['=','id_inventario', $intCodigo])->one();
                            if($precio){
                                $valor = 0;
                                $producto = InventarioProductos::findOne($intCodigo);
                                $valor = $producto->stock_unidades;
                                if($tipo_pedido == 0){
                                    if($_POST["cantidades"]["$intIndice"] <= $valor ){
                                        $presupuesto = \app\models\PresupuestoEmpresarial::findOne(1);
                                        $table = new PedidoPresupuestoComercial();
                                        $table->id_pedido = $id;
                                        $table->id_inventario = $intCodigo;
                                        $table->id_presupuesto = $presupuesto->id_presupuesto;
                                        $table->cantidad = $_POST["cantidades"]["$intIndice"];
                                        $table->user_name = Yii::$app->user->identity->username;
                                        $table->venta_condicionado = 'B';
                                        $table->fecha_registro = date('Y-m-d');
                                        $table->save(false);
                                        $datos = 0;
                                        $datos = $intCodigo;
                                        $token = 0;
                                        $this->ActualizarInventarioPrecio($datos, $id, $token, $pedido_virtual, $tipo_pedido);
                                        $this->TotalPresupuestoPedido($id, $sw);
                                    }else{
                                        Yii::$app->getSession()->setFlash('error', 'Las unidades vendidas es mayor que el STOCK de inventarios. Favor validar las cantidades.');
                                        return $this->redirect(['adicionar_productos','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual]);
                                    }  
                                }else{
                                    $presupuesto = \app\models\PresupuestoEmpresarial::findOne(1);
                                    $table = new PedidoPresupuestoComercial();
                                    $table->id_pedido = $id;
                                    $table->id_inventario = $intCodigo;
                                    $table->venta_condicionado = 'B';
                                    $table->id_presupuesto = $presupuesto->id_presupuesto;
                                    $table->cantidad = $_POST["cantidades"]["$intIndice"];
                                    $table->user_name = Yii::$app->user->identity->username;
                                    $table->fecha_registro = date('Y-m-d');
                                    $table->save(false);
                                    $datos = 0;
                                    $datos = $intCodigo;
                                    $token = 0;
                                    $this->ActualizarInventarioPrecio($datos, $id, $token, $pedido_virtual, $tipo_pedido);
                                    $this->TotalPresupuestoPedido($id, $sw);
                                }    
                            }else{
                                Yii::$app->getSession()->setFlash('warning', 'El producto no tiene precio de venta al publico. Contactar al administrador.');
                                if($tipo_pedido == 0){
                                    return $this->redirect(['adicionar_productos','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                                }else{
                                    return $this->redirect(['adicionar_producto_pedido','id' => $id, 'token' => $token, 'tokenAcceso' =>$tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                                }    
                            }
                        }    
                    }    
                    $intIndice ++;
                endforeach;
                if($tipo_pedido == 0){
                    return $this->redirect(['adicionar_productos','id' => $id, 'token' => 1, 'tokenAcceso' =>$tokenAcceso, 'sw' => $sw, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                }else{
                    return $this->redirect(['adicionar_producto_pedido','id' => $id, 'token' => 1, 'tokenAcceso' =>$tokenAcceso, 'sw' => $sw, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                }    
            }
        }
        return $this->render('listado_productos_presupuesto', [ 
            'id' => $id,
            'variable' => $variable,
            'token' => $token,
            'model' => $model,
            'form' => $form,
            'pagination' => $pages,
            'tokenAcceso' => $tokenAcceso,
            'sw' => $sw,
            'pedido_virtual' => $pedido_virtual,
            'tipo_pedido' => $tipo_pedido,
            ]);
    }
   
    //TOTALIZA EL TOTAL DEL PRESUPUESTO COMERCIAL DE CADA PEDIO
    protected function TotalPresupuestoPedido($id, $sw) {
        $total = 0;
        $pedido = Pedidos::findOne($id);
        $cliente = Clientes::find()->where(['=','id_cliente', $pedido->id_cliente])->one();
        $detalle = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        foreach ($detalle as $detalles):
            $total += $detalles->subtotal;
        endforeach;
        $pedido->valor_presupuesto = $total;
        $pedido->descuento_comercial = $total;
        $pedido->save();
        if($sw == 0 ){
            if($pedido->valor_presupuesto > 0){
               $pedido->presupuesto = 1;         
            }
        }else{
            if($pedido->valor_presupuesto == 0){
               $pedido->presupuesto = 0;
            }   
        }
        $pedido->save(false);
      
    }
        
    //PROCESO QUE ACTUALIA INVENTARIO Y PRECIOS
    protected function ActualizarInventarioPrecio($datos, $id, $token, $pedido_virtual, $tipo_pedido) {
        $auxiliar = 0; $porcentaje = 0;
        $subtotal = 0; $impuesto = 0; $precio_venta = 0; $formula_iva = 0;
        $pedido = Pedidos::findOne($id);
        $cliente = Clientes::findOne($pedido->id_cliente);
        $inventario = InventarioProductos::find()->where(['=','id_inventario', $datos])->one();
        if($token == 1){
            $detalle_pedido = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['=','id_inventario', $datos])->one();
        }else{
            $detalle_pedido = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->andWhere(['=','id_inventario', $datos])->one();
        }
        if($inventario->aplica_inventario == 0){
            if($pedido_virtual == 0){
                 $auxiliar = $inventario->stock_unidades - $detalle_pedido->cantidad;  
            }else{
                 $auxiliar = $inventario->stock_unidades;  
            }     
        }else{
            $auxiliar = $inventario->stock_unidades;
        }
        if($tipo_pedido == 0){
            $inventario->stock_unidades = $auxiliar;
        }    
        $inventario->subtotal = round($inventario->costo_unitario * $inventario->stock_unidades);
        $inventario->valor_iva = round($inventario->subtotal  * $inventario->porcentaje_iva)/100;
        $inventario->total_inventario = round($inventario->subtotal  + $inventario->valor_iva);
        $inventario->save(false);
        //actualiza precios
        $precio = \app\models\InventarioPrecioVenta::find()->where(['=','id_posicion', $cliente->id_posicion])
                                                           ->andWhere(['=','id_inventario', $datos])->one();
        if($precio){
            $formula_iva = ($inventario->porcentaje_iva / 100) + 1 ;
            $detalle_pedido->valor_unitario = $precio->precio_venta_publico / $formula_iva;
            $subtotal = round($detalle_pedido->valor_unitario * $detalle_pedido->cantidad);
            if($inventario->aplica_iva == 0){
                if($precio->iva_incluido == 0){
                   $detalle_pedido->subtotal = $subtotal; 
                    $detalle_pedido->impuesto = round(($subtotal * $inventario->porcentaje_iva)/100);                
                   $detalle_pedido->total_linea = round($detalle_pedido->impuesto +  $subtotal);
                }else{
                    $porcentaje = $inventario->porcentaje_iva;
                    $impuesto = round(($subtotal * $porcentaje)/100);
                    $subtotal = $subtotal;
                    $detalle_pedido->subtotal = $subtotal;
                    $detalle_pedido->impuesto = $impuesto; 
                    $detalle_pedido->total_linea = round($impuesto +  $subtotal);
                }
            }else{
                $detalle_pedido->subtotal = $subtotal; 
                $detalle_pedido->impuesto = 0;                
                $detalle_pedido->total_linea = $subtotal;
            }  

        }else{
            Yii::$app->getSession()->setFlash('warning', 'El producto no tiene precio de venta al publico. Contactar al administrador.'); 
        }
       $detalle_pedido->save(false);
    }
    
    //ACTUALIZA LOS SUBTOTALES
    protected function ActualizarTotalesPedido($id) {
        $subtotal = 0; $impuesto = 0; $total = 0; $cantidad = 0;
        $cupo = 0;
        $model = $this->findModel($id);
        $detalle = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id])->all();
        $cliente = Clientes::find()->where(['=','id_cliente', $model->id_cliente])->one();
        foreach ($detalle as $detalles):
            $subtotal += $detalles->subtotal;    
            $impuesto += $detalles->impuesto;
            $total += $detalles->total_linea;
            $cantidad += $detalles->cantidad;
        endforeach;
        $model->cantidad = $cantidad;
        $model->valor_bruto = $subtotal;
        $model->subtotal = $subtotal;    
        $model->impuesto = $impuesto;
        $model->gran_total = $total;
        $model->save(false);
        $cupo = $model->clientePedido->cupo_asignado;
        if($cliente->formaPago->codigo_api == 4){
            if($total > $cupo){
                $cupo = '$'.number_format($cupo,0);
                Yii::$app->getSession()->setFlash('error', 'El cupo asignado para este cliente es: ('. $cupo. '), este no alcanza a cubrir la totalida del pedido. Revisar las cantidades a vender.'); 
            }
        }    
    }
    
    //ACTUALIZA PEDIDO AGRUPADO
    
  
    
    
    //PROCESO QUE AUTORIZADO O DESAUTORIZA
    public function actionAutorizado($id, $tokenAcceso, $token, $id_cliente, $pedido_virtual, $tipo_pedido) {
        $pedido = Pedidos::findOne($id);
        echo $only_presupuesto = $pedido->tipo_pedido;
        $cliente = Clientes::find()->where(['=','id_cliente', $pedido->id_cliente])->one();
        if($only_presupuesto == 0 && $pedido->tipo_pedido == 1 || $pedido->tipo_pedido == 1){
            
            if($cliente->formaPago->codigo_api == 4){
                if($pedido->clientePedido->cupo_asignado > $pedido->gran_total){
                    if($pedido->autorizado == 0){
                        $pedido->autorizado = 1;
                    }else{
                        $pedido->autorizado = 0;
                    }
                    $pedido->save();
                    if($tipo_pedido == 0){
                        $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }else{
                        $this->redirect(["adicionar_producto_pedido", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }
                }else{
                   Yii::$app->getSession()->setFlash('warning', 'El cupo asignado para este cliente es: ('. ''.number_format($pedido->clientePedido->cupo_asignado,0). '), este no alcanza a cubrir la totalida del pedido. Revisar las cantidades a vender.');  
                   if($tipo_pedido == 0){
                        $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }else{
                        $this->redirect(["adicionar_producto_pedido", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }
                }    
            }else{
               if($pedido->autorizado == 0){
                        $pedido->autorizado = 1;
                }else{
                    $pedido->autorizado = 0;
                }
                $pedido->save();
                if($tipo_pedido == 0){
                        $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                }else{
                    $this->redirect(["adicionar_producto_pedido", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                }
            }  
            if($tipo_pedido == 0){
                return $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
            }else{
                return $this->redirect(["adicionar_producto_pedido", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
            }
               
        }else{
            
            if($cliente->formaPago->codigo_api == 4){
                if($pedido->clientePedido->cupo_asignado > $pedido->gran_total){
                    if($pedido->autorizado == 0){
                        $pedido->autorizado = 1;
                    }else{
                        $pedido->autorizado = 0;
                    }
                    $pedido->save();
                    if($tipo_pedido == 0){
                        $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }else{
                        $this->redirect(["adicionar_producto_pedido", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }
                }else{
                   Yii::$app->getSession()->setFlash('warning', 'El cupo asignado para este cliente es: ('. ''.number_format($pedido->clientePedido->cupo_asignado,0). '), este no alcanza a cubrir la totalida del pedido. Revisar las cantidades a vender.');  
                   if($tipo_pedido == 0){
                        $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }else{
                        $this->redirect(["adicionar_producto_pedido", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                    }
                }    
            }else{
               if($pedido->autorizado == 0){
                        $pedido->autorizado = 1;
                }else{
                    $pedido->autorizado = 0;
                }
                $pedido->save();
                if($tipo_pedido == 0){
                        $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                }else{
                    $this->redirect(["adicionar_producto_pedido", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
                }
            }  
            if($tipo_pedido == 0){
                return $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
            }else{
                return $this->redirect(["adicionar_producto_pedido", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);
            }
        }    
    }
   
   //CREAR EL CONSECUTIVO DEL PEDIDO
    public function actionCrear_pedido_cliente($id, $tokenAcceso, $token, $pedido_virtual, $tipo_pedido) {
        //proceso de generar consecutivo
        $consecutivo = \app\models\Consecutivos::findOne(5);
        $pedido = Pedidos::findOne($id);
        $pedido->numero_pedido = $consecutivo->numero_inicial + 1;
        $pedido->save(false);
        $consecutivo->numero_inicial = $pedido->numero_pedido;
        $consecutivo->save(false);
        if($tipo_pedido == 0){
            return $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso,'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);  
        }else{
            return $this->redirect(["adicionar_producto_pedido", 'id' => $id, 'tokenAcceso' =>$tokenAcceso,'token' => $token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);  
        }
        
    }
    
    //LISTAR FACTURAS POR CLIENTE
    public function actionListado_facturas($desde, $hasta, $id_cliente, $busqueda, $id_agente) {
        if($busqueda == 1){
            $model = Clientes::findOne($id_cliente);
            $facturas = FacturaVenta::find()->where(['=','id_cliente', $id_cliente])
                                            ->andWhere(['between','fecha_inicio', $desde, $hasta])
                                            ->orderBy('cliente ASC')->all(); 
            $pedidos = Pedidos::find()->where(['=','id_cliente', $id_cliente])
                                            ->andWhere(['between','fecha_proceso', $desde, $hasta])
                                            ->orderBy('cliente ASC')->all(); 
        }else{
            $model = AgentesComerciales::findOne($id_agente);
            $facturas = FacturaVenta::find()->where(['=','id_agente', $id_agente])
                                            ->andWhere(['between','fecha_inicio', $desde, $hasta])
                                            ->orderBy('cliente ASC')->all(); 
            $pedidos = Pedidos::find()->where(['=','id_agente', $id_agente])
                                            ->andWhere(['between','fecha_proceso', $desde, $hasta])
                                            ->orderBy('cliente ASC')->all();
        }    
        return $this->render('view_listado_facturas', [
            'model' =>$model,
            'facturas' => $facturas,
            'desde' => $desde,
            'hasta' =>$hasta,
            'busqueda' => $busqueda,
            'pedidos' => $pedidos,
           
        ]);   
    }
    /**
     * Deletes an existing Pedidos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    //ELIMINAR DETALLES DEL PEDIDO
      public function actionEliminar_detalle($id,$detalle, $tokenAcceso, $token, $pedido_virtual, $tipo_pedido)
    {                                
        $detalle = \app\models\PedidoDetalles::findOne($detalle);
        if($tipo_pedido == 0){
            $this->DevolucionProductosInventario($id, $detalle, $pedido_virtual);
            $this->ActualizarTotalesProducto($detalle);
            $detalle->delete();
            $this->ActualizarTotalesPedido($id);
            $this->redirect(["adicionar_productos",'id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' =>$token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);        
        }else{
             $detalle->delete();
             $this->ActualizarTotalesPedido($id);
             $this->redirect(["adicionar_producto_pedido",'id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' =>$token, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]);        
        }    
    }
    
   
   //ELIMINAR DETALLES DEL PRESUPUESTO
      public function actionEliminar_detalle_presupuesto($id,$detalle, $token, $sw, $tokenAcceso, $pedido_virtual, $tipo_pedido) 
    {                                
        $detalles = PedidoPresupuestoComercial::findOne($detalle);
        if($tipo_pedido == 0){
            $this->DevolucionProductosPresupuesto($id, $detalle, $pedido_virtual);
            $detalles->delete();
            $this->SumarPresupuesto($detalle, $id);
            $this->redirect(["adicionar_productos",'id' => $id, 'token' => $token, 'tokenAcceso'=> $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' =>$tipo_pedido]);        
        }else{
            $detalles->delete();
            $this->SumarPresupuesto($detalle, $id);
            $this->SaldarCampoDescuento($id);
            $this->redirect(["adicionar_producto_pedido",'id' => $id, 'token' => $token, 'tokenAcceso'=> $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' =>$tipo_pedido]);        
        }    
    }
    
     protected function SaldarCampoDescuento($id) {
        $presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        $pedido = Pedidos::findOne($id);
        $total = 0;
        foreach ($presupuesto as $valor) {
            $total += $valor->subtotal;
        }
        $pedido->descuento_comercial = $total;
        $pedido->save(false);
    }
    
    
    protected function SumarPresupuesto($detalle, $id) {
        $suma = 0;
        $pedido = Pedidos::findOne($id);
        $detalle_pres = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        foreach ($detalle_pres as $detalles):
            $suma += $detalles->total_linea;
        endforeach;
        $pedido->valor_presupuesto = $suma;
        $pedido->save(false);
    }
   
    //PROCESO QUE REINTEGRA LAS UNIDADES AL INVENTARIO CUANDO SE ELIMINA
    protected function DevolucionProductosInventario($id, $detalle, $pedido_virtual) {
        $auxiliar = 0; $valor = 0;
        $detalles = \app\models\PedidoDetalles::findOne($detalle);
        $detalles->id_inventario;
        $inventario = InventarioProductos::find()->where(['=','id_inventario', $detalles->id_inventario])->one();
        $auxiliar = $detalles->cantidad;
        $valor = $inventario->stock_unidades;
        if($pedido_virtual == 0){
            $inventario->stock_unidades = $valor + $auxiliar;
        }else{
            $inventario->stock_unidades = $valor;
        }    
        $inventario->save(false);
    }
   
    //DEVUCION PRODUCTO PRESUPUESTO
     protected function DevolucionProductosPresupuesto($id, $detalle, $pedido_virtual) {
        $auxiliar = 0; $valor = 0;
        $detalles = PedidoPresupuestoComercial::findOne($detalle);
        $detalles->id_inventario;
        $inventario = InventarioProductos::find()->where(['=','id_inventario', $detalles->id_inventario])->one();
        $auxiliar = $detalles->cantidad;
        $valor = $inventario->stock_unidades;
        if($pedido_virtual == 0){
            $inventario->stock_unidades = $valor + $auxiliar;
        }else{
            $inventario->stock_unidades = $valor;
        }    
        $inventario->save(false);
    }
    
    //PROCSO QUE ACTUALIZAR LOS TODAS DE INVENTARIO
    public function ActualizarTotalesProducto($detalle) {
        $subtotal = 0; $iva = 0; $total = 0;
        $inventario = InventarioProductos::findOne($detalle);
        $subtotal = round($inventario->costo_unitario * $inventario->stock_unidades);
        $iva = round($subtotal * $inventario->porcentaje_iva/100);
        $total = $subtotal + $iva;
        $inventario->subtotal = $subtotal;
        $inventario->valor_iva = $iva;
        $inventario->total_inventario = $total;
        $inventario->save(false);
    }
    
    //PROCESO QUE CIERRA EL PEDIDO
    public function actionCerrar_pedido($id, $token, $tokenAcceso, $pedido_virtual, $tipo_pedido) {
        $suma = 0;
        $pedido = Pedidos::findOne($id);
        $cliente = Clientes::findOne($pedido->id_cliente);
        $suma = $cliente->gasto_presupuesto_comercial;
        if($tipo_pedido == 0){
            if($tipo_pedido == 0){
                $cliente->gasto_presupuesto_comercial = $suma + $pedido->valor_presupuesto;
                $cliente->save();
                $pedido->cerrar_pedido = 1;
                $pedido->pedido_liberado = 1;
                $pedido->save(false);
               return $this->redirect(["adicionar_productos",'id' => $id, 'token' => $token,'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
            }else{
                $cliente->gasto_presupuesto_comercial = $suma + $pedido->valor_presupuesto;
                $cliente->save();
                $pedido->cerrar_pedido = 1;
                $pedido->save(false);
                return $this->redirect(["adicionar_producto_pedido",'id' => $id, 'token' => $token,'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
            } 
        }else{
            $presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
            if(count($presupuesto) > 0){
                foreach ($presupuesto as $key => $lineas) {
                    $table = new PedidoDetalles();
                    $table->id_pedido = $id;
                    $table->id_inventario = $lineas->id_inventario;
                    $table->cantidad = $lineas->cantidad;
                    $table->valor_unitario = $lineas->valor_unitario;
                    $table->subtotal = $lineas->subtotal;
                    $table->impuesto = $lineas->impuesto;
                    $table->total_linea = $lineas->total_linea;
                    $table->user_name = $lineas->user_name;
                    $table->venta_condicionado = 'B';
                    $table->cargar_existencias = $lineas->cargar_existencias;
                    $table->save();
                    $this->ActualizarTotalesPedidoAgrupado($id);
                }
                if($tipo_pedido == 0){
                    $cliente->gasto_presupuesto_comercial = $suma + $pedido->valor_presupuesto;
                    $cliente->save();
                    $pedido->cerrar_pedido = 1;
                    $pedido->pedido_liberado = 1;
                    $pedido->save(false);
                    return $this->redirect(["adicionar_productos",'id' => $id, 'token' => $token,'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                }else{
                    $cliente->gasto_presupuesto_comercial = $suma + $pedido->valor_presupuesto;
                    $cliente->save(false);
                    $pedido->cerrar_pedido = 1;
                    $pedido->save(false);
                    return $this->redirect(["adicionar_producto_pedido",'id' => $id, 'token' => $token,'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                }  
            }else{
                if($tipo_pedido == 0){
                    $cliente->gasto_presupuesto_comercial = $suma + $pedido->valor_presupuesto;
                    $cliente->save();
                    $pedido->cerrar_pedido = 1;
                    $pedido->pedido_liberado = 1;
                    $pedido->save(false);
                    return $this->redirect(["adicionar_productos",'id' => $id, 'token' => $token,'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                }else{
                    $cliente->gasto_presupuesto_comercial = $suma + $pedido->valor_presupuesto;
                    $cliente->save();
                    $pedido->cerrar_pedido = 1;
                    $pedido->save(false);
                   return $this->redirect(["adicionar_producto_pedido",'id' => $id, 'token' => $token,'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                }  
            }  
        }
    }
    
      protected function ActualizarTotalesPedidoAgrupado($id) {
        $subtotal = 0; $impuesto = 0; $total = 0; $cantidad = 0; $cantidad2 = 0;
        $cupo = 0; $descuento_comercial = 0;
        $subtotal_descontable = 0; $impuesto_descontable = 0;
        $model = $this->findModel($id);
        $empresa = \app\models\MatriculaEmpresa::findOne(1);
        $detalle = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id])->all();
        $cliente = Clientes::find()->where(['=','id_cliente', $model->id_cliente])->one();
        foreach ( $detalle as $detalles):
            if($detalles->venta_condicionado !== 'B'){ //ENTRA SI NOS SON BONIFICABLES
                $subtotal += $detalles->subtotal;    
                $impuesto += $detalles->impuesto;
                $total += $detalles->total_linea;
                $cantidad += $detalles->cantidad;
            }else{ ///SUMA LOS VALORES DE BONIFICABLE
                $cantidad2 += $detalles->cantidad;
                $subtotal_descontable += $detalles->subtotal;
                $impuesto_descontable += $detalles->impuesto;
            }
        endforeach;
        //totaliza
        $model->cantidad = $cantidad + $cantidad2;
        $model->valor_bruto = $subtotal + $subtotal_descontable;
        if($model->valor_bruto <= 0){
            $model->subtotal = $model->descuento_comercial; 
            $model->impuesto = 0;
            $model->gran_total = 0;
        }else{
            $model->subtotal = $model->valor_bruto - $model->descuento_comercial;    
            $model->impuesto = $impuesto;
            $model->gran_total = round($model->subtotal + $model->impuesto);
        }    
        $model->save(false);
        $cupo = $model->clientePedido->cupo_asignado;
        if($cliente->formaPago->codigo_api == 4){
            if($total > $cupo){
                $cupo = '$'.number_format($cupo,0);
                Yii::$app->getSession()->setFlash('error', 'El cupo asignado para este cliente es: ('. $cupo. '), este no alcanza a cubrir la totalida del pedido. Revisar las cantidades a vender.'); 
            }
        }    
    }
    
    //PERMITE CREAR LAS OBSERVACIONES DE PEDIDO
    public function actionCrear_observacion($id, $token, $tokenAcceso, $pedido_virtual, $tipo_pedido) {
         $model = new FormModeloBuscar();
         $pedido = Pedidos::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["crear_observaciones"])) {
                $table = Pedidos::findOne($id);
                $table->observacion = $model->observacion;
                $table->fecha_entrega = $model->fecha_entrega;
                $table->save(false);
                if($tipo_pedido == 0){
                   return $this->redirect(["pedidos/adicionar_productos",'id' => $id, 'token' => $token,'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                }else{
                   return $this->redirect(["pedidos/adicionar_producto_pedido",'id' => $id, 'token' => $token,'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $pedido_virtual, 'tipo_pedido' => $tipo_pedido]); 
                }
            }
        }
        if (Yii::$app->request->get()) {
            $model->observacion= $pedido->observacion;
            $model->fecha_entrega= $pedido->fecha_entrega;
        }
        
       return $this->renderAjax('crear_observaciones', [
            'model' => $model,       
            'id' => $id,
           'token' => $token,
           'tokenAcceso' => $tokenAcceso,
           'pedido_virtual' => $pedido_virtual,
           'tipo_pedido' => $tipo_pedido,
            
        ]);      
    }
    
    //ANULAR EL PEDIDO EN SU TOTALIDAD
    public function actionAnular_pedido_total($id, $pedido_virtual) {
        $pedido = Pedidos::findOne($id);
        $cliente = Clientes::find()->where(['=','id_cliente', $pedido->id_cliente])->one();
        $pedido_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->andWhere(['=','registro_eliminado', 1])->all();
        $pedido_detalle = PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['=','registro_eliminado', 1])->all();
        $sumar_presupuesto = 0;
        $sumar_detalle = 0;
        foreach ($pedido_detalle as $detalle):
            $sumar_detalle += $detalle->total_linea;
        endforeach;
        foreach ($pedido_presupuesto as $presupueso):
            $sumar_presupuesto += $presupueso->total_linea;
        endforeach;
        $cliente->gasto_presupuesto_comercial = $cliente->gasto_presupuesto_comercial - $sumar_presupuesto;
        $cliente->save();
        $pedido->valor_eliminado_pedido = $sumar_detalle;
        $pedido->valor_eliminado_presupuesto = $sumar_presupuesto;
        $pedido->pedido_anulado = 1;
        $pedido->save();
        return $this->redirect(['view_anular','id' => $id, 'pedido_virtual' => $pedido_virtual]);
        
    }
    //ACTUALZAR SALDOS
     public function actionActualizar_saldos($id, $pedido_virtual) {
        $pedido = Pedidos::findOne($id);
        $cliente = Clientes::find()->where(['=','id_cliente', $pedido->id_cliente])->one();
        $pedido_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->andWhere(['=','registro_eliminado', 0])->all();
        $pedido_detalle = PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['=','registro_eliminado', 0])->all();
        $this->ActualizarSaldoEliminado($pedido, $cliente, $pedido_presupuesto, $pedido_detalle, $id);
        $this->ActualizarSaldoPresupuestoEiminado($pedido, $cliente, $pedido_presupuesto, $pedido_detalle, $id);
        return $this->redirect(['view_anular','id' => $id, 'pedido_virtual' => $pedido_virtual]);
    }
    //SUBPROCESO DE PEDIDO DETALLE
    protected function ActualizarSaldoEliminado($pedido, $cliente, $pedido_presupuesto, $pedido_detalle, $id) {
        $pedido_pre = PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['=','registro_eliminado', 1])->all();
        //proceso de pedido detalle
        if(count($pedido_detalle) > 0){
            $subtotal = 0; $iva = 0; $total = 0; 
            foreach ($pedido_detalle as $detalle):
                $subtotal += $detalle->subtotal;
                $iva += $detalle->impuesto;
                $total += $detalle->total_linea;
            endforeach;
            $pedido->subtotal = $subtotal;
            $pedido->impuesto = $iva;
            $pedido->gran_total = $total;
            $pedido->save();
        }    
        if(count($pedido_pre) > 0){
            $suma = 0;
            foreach ($pedido_pre as $eliminado):
                $suma += $eliminado->total_linea;
            endforeach;
            $pedido->valor_eliminado_pedido = $suma;
            $pedido->save();
        }else{
           $pedido->valor_eliminado_pedido = 0;
           $pedido->save(); 
        }
       
    }
    
    //ACTUALIZA EL PRSUPUESTO
    
     protected function ActualizarSaldoPresupuestoEiminado($pedido, $cliente, $pedido_presupuesto, $pedido_detalle, $id) {
        $pedido_pre = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->andWhere(['=','registro_eliminado', 1])->all();
        //proceso de pedido detalle
        if(count($pedido_pre) > 0){
            $suma = 0;
            foreach ($pedido_pre as $eliminado):
                $suma += $eliminado->total_linea;
            endforeach;
            $pedido->valor_eliminado_presupuesto = $suma;
            $pedido->save();
        }else{
           $pedido->valor_eliminado_presupuesto = 0;
           $pedido->save(); 
        }
         $total = 0; 
        if(count($pedido_presupuesto) > 0){
            $total = 0; 
            foreach ($pedido_presupuesto as $detalle):
                $total += $detalle->total_linea;
            endforeach;
            $pedido->valor_presupuesto = $total;
            $pedido->save();
        }    
        $cliente->gasto_presupuesto_comercial = $cliente->gasto_presupuesto_comercial - $total;
        $cliente->save();
       
    }
    
    //PROCESO QUE PERMITE BUSCAR INVENTARIO
    public function actionSearch_inventario_pedido($id, $id_inventario, $idToken, $sw) {
        $inventario = InventarioProductos::findOne($id_inventario);
        if($sw == 0){
            $detalle = PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['=','id_inventario', $id_inventario])->one();
        }else{
           $detalle = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->andWhere(['=','id_inventario', $id_inventario])->one();
        }
       if($inventario->stock_unidades > 0){
           $detalle->consultado = 1;
           $detalle->save();
            Yii::$app->getSession()->setFlash('info', 'Este producto tiene ('.$inventario->stock_unidades.') unidades en existencia en el módulo de inventario.');   
            return $this->redirect(['view_pedido_virtual', 'id' => $id, 'idToken' => 1]);
        }else{
              Yii::$app->getSession()->setFlash('warning', 'La presentacion del producto '.$inventario->nombre_producto.', NO tiene existencia en el módulo de inventario. Contactar al departamento de producción.');   
              return $this->redirect(['view_pedido_virtual', 'id' => $id, 'idToken' =>$idToken]);
        }
    }
    
    //PROCESO QUE VALIDA LINEA POR LINE EL DETALLE DEL PEDIDO
    public function actionValidar_lineas_pedido($id)
    {
        $pedido= Pedidos::findOne($id);
        $detalle_PM = PedidoDetalles::find()->where(['=','id_pedido', $pedido->id_pedido])->andWhere(['<>','venta_condicionado', 'B'])->all();
        $sw = 0; $cantidad = 0;
        if(count($detalle_PM) > 0){
            foreach ($detalle_PM as $key => $detalle):
                $inventario = InventarioProductos::findOne ($detalle->id_inventario);
                $cantidad =  $inventario->stock_unidades - $detalle->cantidad;
                if($cantidad < 0){
                    $detalle->cantidad_faltante = $cantidad;
                    $detalle->save();
               }else{
                    $detalle->cantidad_faltante = 0;
                    $detalle->save();       
               }
            endforeach;
        } 
        $detalle_B = PedidoDetalles::find()->where(['=','id_pedido', $pedido->id_pedido])->andWhere(['=','venta_condicionado', 'B'])->all(); 
        if(count($detalle_PM) > 0){
            foreach ($detalle_B as $key => $detalle):
                $inventario = InventarioProductos::findOne ($detalle->id_inventario);
                $cantidad =  $inventario->stock_unidades - $detalle->cantidad;
                if($cantidad < 0){
                    $detalle->cantidad_faltante = $cantidad;
                    $detalle->save();
               }else{
                    $detalle->cantidad_faltante = 0;
                    $detalle->save();       
               }
            endforeach;
        }
        $detalle_pedido = PedidoDetalles::find()->where(['=','id_pedido', $pedido->id_pedido])->all(); 
        foreach ($detalle_pedido as $valores) {
            if($valores->cantidad_faltante < 0){
                Yii::$app->getSession()->setFlash('error', 'No se puede despachar el pedido No '. $pedido->numero_pedido .' porque NO hay existencias para cubrir la totalidad de las referencias.');   
                return $this->redirect(['pedidos/index']);
            }
        }
        $pedido->liberado_inventario = 1;
        $pedido->save();
        Yii::$app->getSession()->setFlash('success', 'Se validaron todos las referencias del pre-pedido comercial No '. $pedido->numero_pedido .'. Puede descargara el inventario del modulo.');   
        return $this->redirect(['pedidos/index']);
        
       
    }  
        
    
    //PROCESO QUE ACTUALIZA
    protected function CumplePedido($id , $sw) {
       $detalle_pedido = PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['=','venta_condicionado', 0])->all();
       foreach ($detalle_pedido as $key => $detalle):
           if($detalle->cantidad_faltante < 0){
               $sw = 1;
           }
       endforeach;
       return $sw;
    }
    
    //PROCESO QUE VALIDA LINEA POR LINE EL PRESUPUESTO COMERCIAL
    public function actionValidar_lineas_presupuesto($id)
    {
        $pedido= Pedidos::findOne($id);
        $detalle_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $pedido->id_pedido])->all();
        $sw = 0; $cantidad = 0;
        foreach ($detalle_presupuesto as $key => $detalle):
            $inventario = InventarioProductos::findOne ($detalle->id_inventario);
            echo $cantidad =  $inventario->stock_unidades - $detalle->cantidad;
            if($cantidad < 0){
                $detalle->cantidad_faltante = $cantidad;
                $detalle->save(false);
           }else{
                $detalle->cantidad_faltante = 0;
                $detalle->save(false);       
           }
        endforeach;
        $sw = $this->CumplePresupuesto($id, $sw);
        if($sw == 0){
            $pedido->liberado_inventario_presupuesto = 1;
            $pedido->save();
            Yii::$app->getSession()->setFlash('success', 'Se validaron todas la referencia del presupuesto comercial No '. $pedido->numero_pedido .'. Puede descargara el inventario del modulo.');   
        }else{
            Yii::$app->getSession()->setFlash('error', 'No se puede despachar el presupuesto comercial porque NO hay existencias para cubrir la totalidad del pedido No (' .$pedido->numero_pedido. ').');   
        }   
        return $this->redirect(['pedidos/index']);
    }
    
    //proceso que actualiza el presupuesto
    protected function CumplePresupuesto($id , $sw) {
       $detalle_pedido = PedidoPresupuestoComercial::findAll(['id_pedido' => $id]);  
       foreach ($detalle_pedido as $key => $detalle):
           if($detalle->cantidad_faltante < 0){
               $sw = 1;
           }
       endforeach;
       return $sw;
    }
    
    //PROCESO QUE DESCARGAR LAS UNIDADES VENDIDAS DE PEDIDOS
    public function actionValidar_linea_inventario($id) {
        $pedido = Pedidos::findOne($id);
        $detalle_pedido = PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['<>','venta_condicionado', 'B'])->all();
        $sumaPedido = 0;
        if(count($detalle_pedido) > 0){
            
            foreach ($detalle_pedido as $key => $detalle) :
                $inventario = InventarioProductos::findOne ($detalle->id_inventario);
                $inventario->stock_unidades -= $detalle->cantidad;
                $inventario->save();
                $sumaPedido += 1;
            endforeach;
            $pedido->detalle_pedido_descargado_inventario = 1;
            $pedido->save();
            Yii::$app->getSession()->setFlash('success', 'Se descargo al modulo de inventario ('.$sumaPedido. ') referencias vendidas en el pedido No ('. $pedido->numero_pedido .').');   
            return $this->redirect(['pedidos/index']);
        }else{
            Yii::$app->getSession()->setFlash('walning', 'Este proceso es validad por el presupuesto comercial.');   
            $pedido->detalle_pedido_descargado_inventario = 1;
            $pedido->save();
            return $this->redirect(['pedidos/index']);
        }    
    }
    
    //PROCESO QUE DESCARGAR LAS UNIDADES VENDIDAS DEL PRESUPUESTO COMERCIAL
    public function actionDescargar_inventario_presupuesto($id) {
        $pedido = Pedidos::findOne($id);
        $detalle_pedido = PedidoPresupuestoComercial::findAll(['id_pedido' => $pedido->id_pedido]);
        $sumaPedido = 0;
        foreach ($detalle_pedido as $key => $detalle) :
            $inventario = InventarioProductos::findOne ($detalle->id_inventario);
            $inventario->stock_unidades -= $detalle->cantidad;
            $inventario->save();
            $sumaPedido += 1;
        endforeach;
        $pedido->presupuesto_descargado_inventario = 1;
        $pedido->save();
        Yii::$app->getSession()->setFlash('success', 'Se descargo al modulo de inventario ('.$sumaPedido. ') referencias vendidas en el presupuesto comercial No ('. $pedido->numero_pedido .').');   
        return $this->redirect(['pedidos/index']);
    }
    
    //CERRAR O LIBERAR PRE-PEDIDO
    public function actionLiberar_pedido($id) {
        $pedido = Pedidos::findOne($id);
        $pedido->pedido_liberado = 1;
        $pedido->save();
        return $this->redirect(['pedidos/index']);
    }
        
    //PROCESO QUE INTEGRA LAS EXISTENCIAS AL PEDIDO VIRTUAL
    public function actionCargar_inventario_pedido($id, $id_inventario, $idToken, $pedido) {
        $inventario = InventarioProductos::findOne($id_inventario);
        $empresa = \app\models\MatriculaEmpresa::findOne(1);
        if($pedido == 0){
            $detalle_pedido = PedidoDetalles::find()->where(['=','id_inventario', $id_inventario])->andWhere(['=','id_pedido', $id])->one();
        }else{
          $detalle_pedido = PedidoPresupuestoComercial::find()->where(['=','id_inventario', $id_inventario])->andWhere(['=','id_pedido', $id])->one();
        }
      
        if($empresa->aplica_inventario_incompleto == 0){ //PERMITE SUBIR LAS POCAS EXISTENCIA QUE HAY EN BODEGA
                $detalle_pedido->cargar_existencias = 1;
                $detalle_pedido->cantidad = $inventario->stock_unidades;
                $detalle_pedido->save();
                //actualiza inventario
                $inventario->stock_unidades -= $inventario->stock_unidades;
                $inventario->save();
                $this->ActualizarLineaPedidoVirtual($detalle_pedido); 
                return $this->redirect(['view_pedido_virtual', 'id' => $id, 'idToken' =>$idToken]);
        }else{
            if($inventario->stock_unidades >= $detalle_pedido->cantidad){
                $detalle_pedido->cargar_existencias = 1;
                $detalle_pedido->save();
                //actualiza inventario
                $inventario->stock_unidades -= $detalle_pedido->cantidad;
                $inventario->save();
                return $this->redirect(['view_pedido_virtual', 'id' => $id, 'idToken' =>$idToken]);
            }else{
                Yii::$app->getSession()->setFlash('error', 'Las existencias que hay en bodega son menores que las cantidades vendidas. Pedir autorización para cargar estas unidades.');   
                return $this->redirect(['view_pedido_virtual', 'id' => $id, 'idToken' =>$idToken]); 
            }
            
        }    
    }
    
    //PROCESO QUE ACTULIZA DESPUES DE DESCARGAR INVENTARIO
    protected function ActualizarLineaPedidoVirtual($detalle_pedido) {
        $total = 0;
        $subtotal = 0; $porcentaje = 0;
        $impuesto = 0;
        $total = $detalle_pedido->cantidad * $detalle_pedido->valor_unitario;
        if($detalle_pedido->inventario->aplica_iva == 0){
            $porcentaje = ($detalle_pedido->inventario->porcentaje_iva) / 100;
            $impuesto = round($total * $porcentaje);
        }else{
            $porcentaje = 0;
            $impuesto = 0;
        }
        $detalle_pedido->subtotal = $total - $impuesto;
        $detalle_pedido->impuesto = $impuesto;
        $detalle_pedido->total_linea = $total;
        $detalle_pedido->save();

    }
    
    //LIBERAR PEDIDO VIRTUAL
    public function actionLiberar_pedido_virtual($id,$idToken) {
        $model = Pedidos::findOne($id);
        $empresa = \app\models\MatriculaEmpresa::findOne(1);
        if($empresa->aplica_inventario_incompleto == 0){
           $model->liberado_inventario = 1;
           $model->save();
           $this->TotalPedidoVirtual($id, $model);
           return $this->redirect(['view_pedido_virtual', 'id' => $id, 'idToken' =>$idToken]); 
        }else{
            $detalle = PedidoDetalles::find()->where(['=','id_pedido', $id])->all();
            $detalleP = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
            $sw = 0;
            foreach ($detalle as $detalles):
                if($detalles->cargar_existencias == 0){
                    $sw = 1;
                }
            endforeach;
            if($sw == 0){
                 $sw1 = 0;
                foreach ($detalleP as $valor):
                    if($valor->cargar_existencias == 0){
                        $sw1 = 1;
                    }
                endforeach;    
                if($sw1 == 0){
                    $model->liberado_inventario = 1;
                    $model->save();
                    $this->TotalPedidoVirtual($id, $model);
                    return $this->redirect(['view_pedido_virtual', 'id' => $id, 'idToken' =>$idToken]); 
                }else{
                     Yii::$app->getSession()->setFlash('warning', 'El presupuesto comercial NO esta completo, ni las unidades vendidas han sido validadas. Revise la información');   
                    return $this->redirect(['view_pedido_virtual', 'id' => $id, 'idToken' =>$idToken]);  
                }
            }else{
                Yii::$app->getSession()->setFlash('error', 'El pedido virtual no esta completo, ni las unidades vendidas han sido validadas. Revise la información');   
                return $this->redirect(['view_pedido_virtual', 'id' => $id, 'idToken' =>$idToken]);  
            }
        }
    }
    
    //PROCESO QUE TOTALIZA EL PEDIDO Y PRESUPUESTO
    protected function TotalPedidoVirtual($id, $model) {
        $detalle = PedidoDetalles::find()->where(['=','id_pedido', $id])->all();
        $subtotal = 0;
        $impuesto = 0;
        $total = 0;
        foreach ($detalle as $detalles):
            $subtotal += $detalles->subtotal;
            $impuesto += $detalles->impuesto;
            $total += $detalles->total_linea;
        endforeach;
        $model->subtotal = $subtotal;
        $model->impuesto = $impuesto;
        $model->gran_total = $total;
        $model->save();
        // proceso que actualiza el presupuesto
        $presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        $subtotal = 0;
        $impuesto = 0;
        $total = 0;
        foreach ($presupuesto as $valor):
            $subtotal += $valor->subtotal;
            $impuesto += $valor->impuesto;
            $total += $valor->total_linea;
        endforeach;
        $model->valor_presupuesto = $subtotal + $impuesto + $total;
        $model->save();
    }
    //REPORTES
    public function actionImprimir_pedido($id) {
        $model = Pedidos::findOne($id);
        return $this->render('../formatos/reporte_pedido_cliente', [
            'model' => $model,
        ]);
    }
    //reporte de presupuesto
    public function actionImprimir_presupuesto($id) {
        $model = Pedidos::findOne($id);
        return $this->render('../formatos/reporte_pedido_presupuesto', [
            'model' => $model,
        ]);
    }
    /**
     * Finds the Pedidos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pedidos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pedidos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //PERMITE EXPORTAR A EXCEL EL PRESUPUESTO DE CADA PEDIDO 
    public function actionExcelconsultaPedidos($tableexcel) {                
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'No PEDIDO')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'FECHA PEDIDO')
                        ->setCellValue('F1', 'FECHA ENTREGA')
                        ->setCellValue('G1', 'CANTIDAD')
                        ->setCellValue('H1', 'SUBTOTAL')
                        ->setCellValue('I1', 'IVA')
                        ->setCellValue('J1', 'TOTAL')
                        ->setCellValue('K1', 'VENDEDOR')    
                        ->setCellValue('L1', 'USER NAME')
                        ->setCellValue('M1', 'AUTORIZADO')
                        ->setCellValue('N1', 'CERRADO')
                        ->setCellValue('O1', 'FACTURADO')
                        ->setCellValue('P1', 'APLICA PRESUPUESTO')
                        ->setCellValue('Q1', 'VALOR PRESUPUESTO');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_pedido)
                        ->setCellValue('B' . $i, $val->numero_pedido)
                        ->setCellValue('C' . $i, $val->documento)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->fecha_proceso)
                        ->setCellValue('F' . $i, $val->fecha_entrega)
                        ->setCellValue('G' . $i, $val->cantidad)
                        ->setCellValue('H' . $i, $val->subtotal)
                        ->setCellValue('I' . $i, $val->impuesto)
                        ->setCellValue('J' . $i, $val->gran_total)
                        ->setCellValue('K' . $i, $val->agentePedido->nombre_completo)
                        ->setCellValue('L' . $i, $val->usuario)
                        ->setCellValue('M' . $i, $val->autorizadoPedido)
                        ->setCellValue('N' . $i, $val->pedidoAbierto)
                        ->setCellValue('O' . $i, $val->pedidoFacturado)
                        ->setCellValue('P' . $i, $val->presupuestoPedido)
                        ->setCellValue('Q' . $i, $val->valor_presupuesto);
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Pedidos.xlsx"');
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
    
    //EXCELEES // PERMITE EXPORTAR A EXCEL EL PEDIDO
    public function actionExcel_pedido($id) {                
        $detalle  = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id])->all(); 
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'No PEDIDO')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'FECHA PEDIDO')
                    ->setCellValue('F1', 'FECHA ENTREGA')
                    ->setCellValue('G1', 'CODIGO PRODUCTO')
                    ->setCellValue('H1', 'PRODUCTO')
                    ->setCellValue('I1', 'CANTIDAD')
                    ->setCellValue('J1', 'VR. UNIT.')
                    ->setCellValue('K1', 'SUBTOTAL')
                    ->setCellValue('L1', 'IVA')
                    ->setCellValue('M1', 'TOTAL')
                    ->setCellValue('N1', 'USER NAME')
                    ->setCellValue('O1', 'FECHA REGISTRO')
                    ->setCellValue('P1', 'LINEA VALIDADA')
                    ->setCellValue('Q1', 'HISTORICO CANTIDAD VENDIDA')
                    ->setCellValue('R1', 'CANTIDAD DESPACHADA')
                    ->setCellValue('S1', 'FECHA VALIDADO')
                    ->setCellValue('T1', 'NUMERO LOTE')
                    ->setCellValue('U1', 'TIPO VENTA');
                ;
        $i = 2;
        
        foreach ($detalle as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_pedido)
                    ->setCellValue('B' . $i, $val->pedido->numero_pedido)
                    ->setCellValue('C' . $i, $val->pedido->documento)
                    ->setCellValue('D' . $i, $val->pedido->cliente)
                    ->setCellValue('E' . $i, $val->pedido->fecha_proceso)
                    ->setCellValue('F' . $i, $val->pedido->fecha_entrega)
                    ->setCellValue('G' . $i, $val->inventario->codigo_producto)
                    ->setCellValue('H' . $i, $val->inventario->nombre_producto)
                    ->setCellValue('I' . $i, $val->cantidad)
                    ->setCellValue('J' . $i, $val->valor_unitario)
                    ->setCellValue('K' . $i, $val->subtotal)
                    ->setCellValue('L' . $i, $val->impuesto)
                    ->setCellValue('M' . $i, $val->total_linea)
                    ->setCellValue('N' . $i, $val->user_name)
                    ->setCellValue('O' . $i, $val->fecha_registro)
                    ->setCellValue('P' . $i, $val->lineaValidada)
                    ->setCellValue('Q' . $i, $val->historico_cantidad_vendida)
                    ->setCellValue('R' . $i, $val->cantidad_despachada)
                    ->setCellValue('S' . $i, $val->fecha_alistamiento)
                    ->setCellValue('T' . $i, $val->numero_lote)
                    ->setCellValue('U' . $i, $val->ventaCondicionado);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pedidos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Orden_pedido.xlsx"');
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
    
    //PERMITE EXPORTAR A EXCEL EL PRESUPUESTO DE CADA PEDIDO 
    public function actionExcel_pedido_presupuesto($id) {   
        $pedido = Pedidos::findOne($id);
        $detalle  = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all(); 
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'No PEDIDO')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'FECHA PEDIDO')
                    ->setCellValue('F1', 'CODIGO PRODUCTO')
                    ->setCellValue('G1', 'PRODUCTO')
                    ->setCellValue('H1', 'CANTIDAD')
                    ->setCellValue('I1', 'VR. UNIT.')
                    ->setCellValue('J1', 'SUBTOTAL')
                    ->setCellValue('K1', 'IVA')
                    ->setCellValue('L1', 'TOTAL')
                    ->setCellValue('M1', 'USER NAME')
                    ->setCellValue('N1', 'FECHA REGISTRO')
                    ->setCellValue('O1', 'FECHA ENTREGA')
                    ->setCellValue('P1', 'FECHA VALIDADO')
                    ->setCellValue('Q1', 'CANTIDAD DESPACHADA')
                    ->setCellValue('R1', 'CANTIDAD VENDIDA')
                    ->setCellValue('S1', 'NUMERO LOTE');
        $i = 2;
        
        foreach ($detalle as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_pedido)
                    ->setCellValue('B' . $i, $val->pedido->numero_pedido)
                    ->setCellValue('C' . $i, $val->pedido->documento)
                    ->setCellValue('D' . $i, $val->pedido->cliente)
                    ->setCellValue('E' . $i, $val->pedido->fecha_proceso)
                    ->setCellValue('F' . $i, $val->inventario->codigo_producto)
                    ->setCellValue('G' . $i, $val->inventario->nombre_producto)
                    ->setCellValue('H' . $i, $val->cantidad)
                    ->setCellValue('I' . $i, $val->valor_unitario)
                    ->setCellValue('J' . $i, $val->subtotal)
                    ->setCellValue('K' . $i, $val->impuesto)
                    ->setCellValue('L' . $i, $val->total_linea)
                    ->setCellValue('M' . $i, $val->user_name)
                    ->setCellValue('N' . $i, $val->fecha_registro)
                    ->setCellValue('O' . $i, $val->pedido->fecha_entrega)
                    ->setCellValue('P' . $i, $val->fecha_alistamiento)
                    ->setCellValue('Q' . $i, $val->cantidad_despachada)
                    ->setCellValue('R' . $i, $val->historico_cantidad_vendida)
                    ->setCellValue('S' . $i, $val->numero_lote);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Presupuesto');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Presupuesto_pedido.xlsx"');
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
    
    public function actionExcelconsultaDesabastecimiento($tableexcel)
    {
        $objPHPExcel = new \PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("EMPRESA")
                ->setLastModifiedBy("Abastecimiento")
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
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'REFERENCIA')
                        ->setCellValue('B1', 'PRESENTACION PRODUCTO')
                        ->setCellValue('C1', 'PRODUCTO')
                        ->setCellValue('D1', 'GRUPO')
                        ->setCellValue('E1', 'UNIDADES VENDIDAS')
                        ->setCellValue('F1', 'UNIDADES FALTANTES');
            $i = 2;
            $contar = 0;
            $sumaVentas = 0;
            $auxiliar = 0;
            foreach ($tableexcel as $val) {
               
                 if($auxiliar <> $val->id_inventario){
                     $datos = PedidoDetalles::find()->where(['=','id_inventario', $val->id_inventario])->andWhere(['<','cantidad_faltante', 0])->all(); 
                       if(count($datos) > 0){
                            $contar = 0;  
                            $sumaVentas = 0;
                            foreach ($datos as $key => $dato) {
                               $contar += $dato->cantidad_faltante;
                               $sumaVentas += $dato->cantidad;
                            }
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, $val->inventario->codigo_producto)
                                ->setCellValue('B' . $i, $val->inventario->nombre_producto)
                                ->setCellValue('C' . $i, $val->inventario->producto->nombre_producto)
                                ->setCellValue('D' . $i, $val->inventario->producto->grupo->nombre_grupo)
                                ->setCellValue('E' . $i, $sumaVentas)
                                ->setCellValue('F' . $i, $contar);
                               $i++;
                       }
                       $auxiliar = $val->id_inventario;
                 }else{
                     $auxiliar = $val->id_inventario;
                 }       
            
            }
            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Abastecimiento.xlsx"');
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
