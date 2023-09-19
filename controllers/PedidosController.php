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
                $facturado = null; $pedido_cerrado = null;
                $vendedores = null; $numero_pedido = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                $tokenAgente = Yii::$app->user->identity->username; 
                $presupuesto = null;
                $vendedor = AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
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
                        if($vendedor){
                            $table = Pedidos::find()
                                    ->andFilterWhere(['=', 'documento', $documento])
                                    ->andFilterWhere(['=', 'id_cliente', $cliente])
                                    ->andFilterWhere(['=', 'facturado', $facturado])
                                    ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=','cerrar_pedido', $pedido_cerrado])
                                    ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                                     ->andFilterWhere(['=','presupuesto', $presupuesto])
                                    ->andWhere(['=','autorizado', 1])
                                    ->andWhere(['=','id_agente', $vendedor->id_agente]);
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
                                    ->andFilterWhere(['=','id_agente', $vendedores]);
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
                    if($vendedor){
                        $table = Pedidos::find()->Where(['=','id_agente', $vendedor->id_agente])->orderBy('id_pedido DESC');
                    }else{
                        $table = Pedidos::find()->Where(['=','autorizado', 1])->orderBy('id_pedido DESC');
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
                $vendedor = AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $nitcedula = Html::encode($form->nitcedula);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        if($vendedor){
                            $table = Clientes::find()
                                    ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                    ->andFilterWhere(['like', 'nombre_completo', $nombre_completo])
                                    ->andWhere(['=', 'estado_cliente', 0])
                                    ->andWhere(['=','id_agente', $vendedor->id_agente]);
                        }else{
                            $table = Clientes::find()
                                    ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                    ->andFilterWhere(['like', 'nombre_completo', $nombre_completo])
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
                    if($vendedor){
                        $table = Clientes::find()->where(['=','estado_cliente', 0])
                            ->andWhere(['=','id_agente', $vendedor->id_agente])
                            ->orderBy('nombre_completo ASC');
                    }else{
                        $table = Clientes::find()->where(['=','estado_cliente', 0])
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
                $tokenAcceso = Yii::$app->user->identity->role;
                $tokenAgente = Yii::$app->user->identity->username; 
                $presupuesto = null;
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
                            ->andFilterWhere(['=','cerrar_pedido', $pedido_cerrado])
                            ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                             ->andFilterWhere(['=','presupuesto', $presupuesto])
                            ->andWhere(['=','autorizado', 1])
                            ->andFilterWhere(['=','id_agente', $vendedores]);
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
                } else {
                    $table = Pedidos::find()->Where(['=','autorizado', 1])->andWhere(['=','cerrar_pedido', 1])->orderBy('id_pedido DESC');
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
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaPedidos($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_pedidos', [
                            'model' => $model,
                            'form' => $form,
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
    
    //ANULAR PEDIDO
    public function actionAnular_pedidos() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',48])->all()){
                $table = Pedidos::find()->Where(['=','autorizado', 1])->andWhere(['=','cerrar_pedido', 1])
                                       ->andWhere(['=','pedido_anulado', 0])
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
            return $this->render('anular_pedidos', [
                        'model' => $model,
                        'pagination' => $pages,
            ]);
        }else{
            return $this->redirect(['site/login']);
        }
      }
    //PROCESO QUE CREA NUEVO PEDIDO
    public function actionCrear_nuevo_pedido($id) {
       //valide cupo
       $cliente = Clientes::find()->where(['=','id_cliente', $id])->andWhere(['>','cupo_asignado', 0])->one();
       if($cliente){
           $table = new Pedidos();
           $table->id_cliente = $id;
           $table->documento = $cliente->nit_cedula;
           $table->dv = $cliente->dv;
           $table->cliente = $cliente->nombre_completo;
           $table->usuario = Yii::$app->user->identity->username;
           $table->fecha_proceso = date('Y-m-d');
           $table->id_agente = $cliente->id_agente;
           $table->save();
           return $this->redirect(['/pedidos/index']);
       }else{
           Yii::$app->getSession()->setFlash('warning', 'El cliente NO TIENE cupo asignado.'); 
           return $this->redirect(['listado_clientes']);
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
            'pedido_presupuesto' => $pedido_presupuesto,
            'detalle_pedido' => $detalle_pedido,
            'cliente' => $cliente,
        ]);   
    }
    
    //VISTA DE ANULAR PEDIDO Y DETALLES DEL PRESUPUESTO
    public function actionView_anular($id) {
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
                    $this->DevolucionProductosPresupuesto($id, $detalle);
                    $presupuesto->registro_eliminado = 1;
                    $presupuesto->save();
                    $this->redirect(["view_anular",'id' => $id]); 
                }       
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe de seleccionar los registros a eliminar.'); 
                return $this->redirect(['view_anular','id' => $id]);
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
                    $this->DevolucionProductosInventario($id, $detalle);
                    $detalle = $eliminar->id_inventario;
                    $this->ActualizarTotalesProducto($detalle);
                    $eliminar->registro_eliminado = 1;
                    $eliminar->save(false);
                    $this->redirect(["view_anular",'id' => $id]); 
                }       
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe de seleccionar los registros a eliminar.'); 
                return $this->redirect(['view_anular','id' => $id]);
            } 
        }    
        
        return $this->render('view_anulado', [
            'model' => $this->findModel($id),
            'detalle_pedido' => $detalle_pedido,
            'pedido_presupuesto' => $pedido_presupuesto,
         
        ]);   
    }
    
   //PROCESO QUE EDITA EL CLIENTE
     public function actionEditarcliente($id, $tokenAcceso) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = Pedidos::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["editarcliente"])) { 
                $conCliente = Clientes::find()->where(['=','id_cliente', $model->cliente])->andWhere(['>','cupo_asignado', 0])->one();
                if ($conCliente){
                    $table->id_cliente = $model->cliente;
                    $table->documento = $conCliente->nit_cedula;
                    $table->dv = $conCliente->dv;
                    $table->cliente = $conCliente->nombre_completo;
                    $table->save(false);
                    $this->redirect(["pedidos/index"]);
                } else {
                   Yii::$app->getSession()->setFlash('warning', 'El cliente NO TIENE cupo asignado.'); 
                   return $this->redirect(['index']);
                }    
            }    
        }
         if (Yii::$app->request->get()) {
            $model->cliente = $table->id_cliente;
         }
        return $this->renderAjax('editarcliente', [
            'model' => $model,
            'id' => $id,
            'tokenAcceso' => $tokenAcceso,
            'agente_comercial' => $table->id_agente,
        ]);
    }
    
    //PROCESO QUE MUESTRAS EL LISTADO DE INVENTARIO ACTIVO
    public function actionAdicionar_productos($id, $tokenAcceso, $token) {
        $model = Pedidos::findOne($id);
        $detalle_pedido = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id])->all();
        $inventario = InventarioProductos::find()->where(['=','venta_publico', 0])
                                                 ->andWhere(['>','stock_unidades', 0])->orderBy('nombre_producto ASC')->all();
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
                            ->andwhere(['>','stock_unidades', 0]);
                }else{
                    $conSql = InventarioProductos::find()
                        ->where(['=','codigo_producto', $q])
                        ->andwhere(['=','venta_publico', 0])
                        ->andwhere(['>','stock_unidades', 0]);
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
                                                 ->andWhere(['>','stock_unidades', 0])->orderBy('nombre_producto ASC');
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
                                if($_POST["cantidad_productos"]["$intIndice"] <= $valor ){
                                    $table = new \app\models\PedidoDetalles();
                                    $table->id_pedido = $id;
                                    $table->id_inventario = $intCodigo;
                                    $table->cantidad = $_POST["cantidad_productos"]["$intIndice"];
                                    $table->user_name = Yii::$app->user->identity->username;
                                    $table->save(false);
                                    $datos = $intCodigo;
                                    $this->ActualizarInventarioPrecio($datos, $id, $token);
                                    $this->ActualizarTotalesPedido($id);
                                }else{
                                    Yii::$app->getSession()->setFlash('error', 'Las unidades vendidas es mayor que el STOCK de inventarios. Favor validar las cantidades.');
                                    return $this->redirect(['adicionar_productos','id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' =>$token]);
                                }    
                            }else{
                                Yii::$app->getSession()->setFlash('warning', 'El producto no tiene precio de venta al publico. Contactar al administrador.');
                                return $this->redirect(['adicionar_productos','id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' =>$token]);
                            }
                        }    
                    }    
                     $intIndice++;
                }
                return $this->redirect(['adicionar_productos','id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' => $token]);
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
        ]);
    }
    
    //PROCESO QUE INCORPORA PRESUPUESTO AL PEDID
    public function actionAdicionar_presupuesto($id, $token, $sw) {
        $model = Pedidos::findOne($id);
        $inventario = InventarioProductos::find()->where(['=','venta_publico', 0])
                                                 ->andWhere(['>','stock_unidades', 0])->andWhere(['=','aplica_presupuesto', 1])->orderBy('nombre_producto ASC')->all();
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
                            ->andwhere(['=','aplica_presupuesto', 1])
                            ->andwhere(['>','stock_unidades', 0]);
                }else{
                    $conSql = InventarioProductos::find()
                        ->where(['=','codigo_producto', $q])
                        ->andwhere(['=','venta_publico', 0])
                        ->andwhere(['=','aplica_presupuesto', 1])    
                        ->andwhere(['>','stock_unidades', 0]);
                }    
                $conSql = $conSql->orderBy('nombre_producto ASC');  
                $count = clone $conSql;
                $to = $count->count();
                $pages = new Pagination([
                    'pageSize' => 10,
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
                                                 ->andWhere(['>','stock_unidades', 0])->andWhere(['=','aplica_presupuesto', 1])->orderBy('nombre_producto ASC');
            $tableexcel = $inventario->all();
            $count = clone $inventario;
            $pages = new Pagination([
                        'pageSize' => 10,
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
                                if($_POST["cantidades"]["$intIndice"] <= $valor ){
                                    $presupuesto = \app\models\PresupuestoEmpresarial::findOne(1);
                                    $table = new PedidoPresupuestoComercial();
                                    $table->id_pedido = $id;
                                    $table->id_inventario = $intCodigo;
                                    $table->id_presupuesto = $presupuesto->id_presupuesto;
                                    $table->cantidad = $_POST["cantidades"]["$intIndice"];
                                    $table->user_name = Yii::$app->user->identity->username;
                                    $table->fecha_registro = date('Y-m-d');
                                    $table->save(false);
                                    $datos = 0;
                                    $datos = $intCodigo;
                                    $this->ActualizarInventarioPrecio($datos, $id, $token);
                                    $this->TotalPresupuestoPedido($id, $sw);
                                }else{
                                    Yii::$app->getSession()->setFlash('error', 'Las unidades vendidas es mayor que el STOCK de inventarios. Favor validar las cantidades.');
                                    return $this->redirect(['view','id' => $id, 'token' => $token]);
                                }    
                            }else{
                                Yii::$app->getSession()->setFlash('warning', 'El producto no tiene precio de venta al publico. Contactar al administrador.');
                                return $this->redirect(['adicionar_productos','id' => $id, 'token' => $token]);
                            }
                        }    
                    }    
                    $intIndice ++;
                endforeach;
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('listado_productos_presupuesto', [ 
            'id' => $id,
            'variable' => $variable,
            'token' => $token,
            'model' => $model,
            'form' => $form,
            'pagination' => $pages,
            ]);
    }
   
    //TOTALIZA EL TOTAL DEL PRESUPUESTO COMERCIAL DE CADA PEDIO
    protected function TotalPresupuestoPedido($id, $sw) {
        $total = 0;
        $pedido = Pedidos::findOne($id);
        $cliente = Clientes::find()->where(['=','id_cliente', $pedido->id_cliente])->one();
        $detalle = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        foreach ($detalle as $detalles):
            $total += $detalles->total_linea;
        endforeach;
        $pedido->valor_presupuesto = $total;
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
    protected function ActualizarInventarioPrecio($datos, $id, $token) {
        $auxiliar = 0; $porcentaje = 0;
        $subtotal = 0; $impuesto = 0;
        $pedido = Pedidos::findOne($id);
        $cliente = Clientes::findOne($pedido->id_cliente);
        $inventario = InventarioProductos::find()->where(['=','id_inventario', $datos])->one();
        if($token == 0){
            $detalle_pedido = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->andWhere(['=','id_inventario', $datos])->one();
        }else{
            $detalle_pedido = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['=','id_inventario', $datos])->one();
        }
        
        if($inventario->aplica_inventario === 0){
            $auxiliar = $inventario->stock_unidades - $detalle_pedido->cantidad;    
        }else{
            $auxiliar = $inventario->stock_unidades;
        }
        $inventario->stock_unidades = $auxiliar;
        $inventario->subtotal = round($inventario->costo_unitario * $inventario->stock_unidades);
        $inventario->valor_iva = round($inventario->subtotal  * $inventario->porcentaje_iva)/100;
        $inventario->total_inventario = round($inventario->subtotal  + $inventario->valor_iva);
        $inventario->save(false);
        //actualiza precios
        $precio = \app\models\InventarioPrecioVenta::find()->where(['=','id_posicion', $cliente->id_posicion])
                                                           ->andWhere(['=','id_inventario', $datos])->one();
        if($precio){
            $detalle_pedido->valor_unitario = $precio->precio_venta_publico;
            $subtotal = round($detalle_pedido->valor_unitario * $detalle_pedido->cantidad);
            if($inventario->aplica_iva == 0){
                if($precio->iva_incluido == 0){
                   $detalle_pedido->subtotal = $subtotal; 
                   $detalle_pedido->impuesto = round($subtotal * $inventario->porcentaje_iva)/100;                
                   $detalle_pedido->total_linea = round($detalle_pedido->impuesto +  $subtotal);
                }else{
                    $porcentaje = ''.number_format($inventario->porcentaje_iva/100, 2);
                    $impuesto = round($subtotal * $porcentaje);
                    $subtotal = $subtotal - $impuesto;
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
        foreach ( $detalle as $detalles):
            $subtotal += $detalles->subtotal;    
            $impuesto += $detalles->impuesto;
            $total += $detalles->total_linea;
            $cantidad += $detalles->cantidad;
        endforeach;
        $model->cantidad = $cantidad;
        $model->subtotal = $subtotal;    
        $model->impuesto = $impuesto;
        $model->gran_total = $total;
        $model->save(false);
        $cupo = $model->clientePedido->cupo_asignado;
        if($total > $cupo){
            $cupo = '$'.number_format($cupo,0);
            Yii::$app->getSession()->setFlash('error', 'El cupo asignado para este cliente es: ('. $cupo. '), este no alcanza a cubrir la totalida del pedido. Revisar las cantidades a vender.'); 
        }
    }
    //PROCESO QUE AUTORIZADO O DESAUTORIZA
    public function actionAutorizado($id, $tokenAcceso, $token) {
        $pedido = Pedidos::findOne($id);
        if($pedido->clientePedido->cupo_asignado > $pedido->gran_total){
            if($pedido->autorizado == 0){
                $pedido->autorizado = 1;
            }else{
                $pedido->autorizado = 0;
            }
            $pedido->save();
            $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token]);
        }else{
           Yii::$app->getSession()->setFlash('warning', 'El cupo asignado para este cliente es: ('. ''.number_format($pedido->clientePedido->cupo_asignado,0). '), este no alcanza a cubrir la totalida del pedido. Revisar las cantidades a vender.');  
          $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso, 'token' => $token]); 
        }    
    }
    //CREAR EL CONSECUTIVO DEL PEDIDO
     public function actionCrear_pedido_cliente($id, $tokenAcceso, $token) {
        //proceso de generar consecutivo
        $consecutivo = \app\models\Consecutivos::findOne(5);
        $pedido = Pedidos::findOne($id);
        $pedido->numero_pedido = $consecutivo->numero_inicial + 1;
        $pedido->save(false);
        $consecutivo->numero_inicial = $pedido->numero_pedido;
        $consecutivo->save(false);
        $this->redirect(["adicionar_productos", 'id' => $id, 'tokenAcceso' =>$tokenAcceso,'token' => $token]);  
    }
    
    /**
     * Deletes an existing Pedidos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    //ELIMINAR DETALLES DEL PEDIDO
      public function actionEliminar_detalle($id,$detalle, $tokenAcceso, $token)
    {                                
        $detalle = \app\models\PedidoDetalles::findOne($detalle);
        $this->DevolucionProductosInventario($id, $detalle);
        $this->ActualizarTotalesProducto($detalle);
        $detalle->delete();
        $this->ActualizarTotalesPedido($id);
        $this->redirect(["adicionar_productos",'id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' =>$token]);        
    }
    
   //ELIMINAR DETALLES DEL PRESUPUESTO
      public function actionEliminar_detalle_presupuesto($id,$detalle, $token, $sw) 
    {                                
        $detalle = PedidoPresupuestoComercial::findOne($detalle);
        $this->DevolucionProductosPresupuesto($id, $detalle);
        $detalle->delete();
        $this->SumarPresupuesto($detalle, $id);
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
    protected function SumarPresupuesto($detalle, $id) {
        $suma = 0;
        $pedido = Pedidos::findOne($id);
        $detalle = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->all();
        foreach ($detalle as $detalles):
            $suma += $detalles->total_linea;
        endforeach;
        $pedido->valor_presupuesto = $suma;
        $pedido->save(false);
    }
    //PROCESO QUE REINTEGRA LAS UNIDADES AL INVENTARIO CUANDO SE ELIMINA
    protected function DevolucionProductosInventario($id, $detalle) {
        $auxiliar = 0; $valor = 0;
        $detalles = \app\models\PedidoDetalles::findOne($detalle);
        $detalles->id_inventario;
        $inventario = InventarioProductos::find()->where(['=','id_inventario', $detalles->id_inventario])->one();
        $auxiliar = $detalles->cantidad;
        $valor = $inventario->stock_unidades;
        $inventario->stock_unidades = $valor + $auxiliar;
        $inventario->save(false);
    }
    //DEVUCION PRODUCTO PRESUPUESTO
     protected function DevolucionProductosPresupuesto($id, $detalle) {
        $auxiliar = 0; $valor = 0;
        $detalles = \app\models\PedidoPresupuestoComercial::findOne($detalle);
        $detalles->id_inventario;
        $inventario = InventarioProductos::find()->where(['=','id_inventario', $detalles->id_inventario])->one();
        $auxiliar = $detalles->cantidad;
        $valor = $inventario->stock_unidades;
        $inventario->stock_unidades = $valor + $auxiliar;
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
    public function actionCerrar_pedido($id, $token) {
        $suma = 0;
        $pedido = Pedidos::findOne($id);
        $cliente = Clientes::findOne($pedido->id_cliente);
        $suma = $cliente->gasto_presupuesto_comercial;
        $cliente->gasto_presupuesto_comercial = $suma + $pedido->valor_presupuesto;
        $cliente->save();
        $pedido->cerrar_pedido = 1;
        $pedido->save(false);
        $this->redirect(["view",'id' => $id, 'token' => $token]);    
    }
    
    public function actionCrear_observacion($id, $token, $tokenAcceso) {
         $model = new FormModeloBuscar();
         $pedido = Pedidos::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
                if (isset($_POST["crear_observaciones"])) {
                    $table = Pedidos::findOne($id);
                    $table->observacion = $model->observacion;
                    $table->save(false);
                    return $this->redirect(['adicionar_productos','id' => $id, 'token' => $token, 'tokenAcceso' => $tokenAcceso]);
                }
        }
        if (Yii::$app->request->get()) {
            $model->observacion= $pedido->observacion;
        }
        
       return $this->renderAjax('crear_observaciones', [
            'model' => $model,       
            'id' => $id,
           'token' => $token,
           'tokenAcceso' => $tokenAcceso,
            
        ]);      
    }
    
    //ANULAR EL PEDIDO EN SU TOTALIDAD
    public function actionAnular_pedido_total($id) {
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
        return $this->redirect(['view_anular','id' => $id]);
        
    }
    //ACTUALZAR SALDOS
     public function actionActualizar_saldos($id) {
        $pedido = Pedidos::findOne($id);
        $cliente = Clientes::find()->where(['=','id_cliente', $pedido->id_cliente])->one();
        $pedido_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->andWhere(['=','registro_eliminado', 0])->all();
        $pedido_detalle = PedidoDetalles::find()->where(['=','id_pedido', $id])->andWhere(['=','registro_eliminado', 0])->all();
        $this->ActualizarSaldoEliminado($pedido, $cliente, $pedido_presupuesto, $pedido_detalle, $id);
        $this->ActualizarSaldoPresupuestoEiminado($pedido, $cliente, $pedido_presupuesto, $pedido_detalle, $id);
        return $this->redirect(['view_anular','id' => $id]);
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'No PEDIDO')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'FECHA PEDIDO')
                    ->setCellValue('F1', 'CANTIDAD')
                    ->setCellValue('G1', 'SUBTOTAL')
                    ->setCellValue('H1', 'IVA')
                    ->setCellValue('I1', 'TOTAL')
                    ->setCellValue('J1', 'VENDEDOR')    
                    ->setCellValue('K1', 'USER NAME')
                    ->setCellValue('L1', 'AUTORIZADO')
                    ->setCellValue('M1', 'CERRADO')
                    ->setCellValue('N1', 'FACTURADO')
                    ->setCellValue('O1', 'APLICA PRESUPUESTO')
                    ->setCellValue('P1', 'VALOR PRESUPUESTO');
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_pedido)
                    ->setCellValue('B' . $i, $val->numero_pedido)
                    ->setCellValue('C' . $i, $val->documento)
                    ->setCellValue('D' . $i, $val->cliente)
                    ->setCellValue('E' . $i, $val->fecha_proceso)
                    ->setCellValue('F' . $i, $val->cantidad)
                    ->setCellValue('G' . $i, $val->subtotal)
                    ->setCellValue('H' . $i, $val->impuesto)
                    ->setCellValue('I' . $i, $val->gran_total)
                    ->setCellValue('J' . $i, $val->agentePedido->nombre_completo)
                    ->setCellValue('K' . $i, $val->usuario)
                    ->setCellValue('L' . $i, $val->autorizadoPedido)
                    ->setCellValue('M' . $i, $val->pedidoAbierto)
                    ->setCellValue('N' . $i, $val->pedidoFacturado)
                    ->setCellValue('O' . $i, $val->presupuestoPedido)
                    ->setCellValue('P' . $i, $val->valor_presupuesto);
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
                    ->setCellValue('N1', 'FECHA REGISTRO');
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
                    ->setCellValue('N' . $i, $val->fecha_registro);
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
                    ->setCellValue('N1', 'FECHA REGISTRO');
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
                    ->setCellValue('N' . $i, $val->fecha_registro);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Presupuesto');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Presupues_pedido.xlsx"');
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
