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
//models
use app\models\AlmacenamientoProducto;
use app\models\AlmacenamientoProductoSearch;
use app\models\UsuarioDetalle;
use app\models\OrdenProduccion;
use app\models\AlmacenamientoProductoDetalles;
use app\models\TipoRack;
use app\models\Pedidos;
use app\models\PedidoDetalles;
use app\models\PedidoPresupuestoComercial;
use app\models\EntradaProductoTerminado;


/**
 * AlmacenamientoProductoController implements the CRUD actions for AlmacenamientoProducto model.
 */
class AlmacenamientoProductoController extends Controller
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
     * Lists all AlmacenamientoProducto models.
     * @return mixed
     */
    //INDEX DE CONSULTA
    public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',73])->all()){
                $form = new \app\models\FiltroBusquedaAlmacenamiento();
                $codigo = null;
                $lote = null;
                $rack = null;
                $piso = null;
                $posicion = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $producto = null;
                $model = null;
                $pages = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $producto = Html::encode($form->producto);
                        $piso = Html::encode($form->piso);
                        $posicion = Html::encode($form->posicion);
                        $lote = Html::encode($form->lote);
                        $rack = Html::encode($form->rack);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = AlmacenamientoProductoDetalles::find()
                                    ->andFilterWhere(['between', 'fecha_almacenamiento', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['like', 'producto', $producto])
                                    ->andFilterWhere(['=', 'id_piso', $piso])
                                    ->andfilterWhere(['=', 'id_rack', $rack])
                                    ->andfilterWhere(['=', 'id_posicion', $posicion]);
                        $table = $table->orderBy('id DESC');
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
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id  DESC']);
                            $this->actionExcelAlmacenamiento($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } 
                return $this->render('index', [
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
    
    //MOVER POSICION DE ALAMCENAMIENTO
     //INDEX DE CONSULTA
    public function actionMover_posiciones() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',77])->all()){
                $form = new \app\models\FiltroBusquedaAlmacenamiento();
                $codigo = null;
                $lote = null;
                $rack = null;
                $piso = null;
                $posicion = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $producto = null;
                $model = null;
                $pages = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $producto = Html::encode($form->producto);
                        $piso = Html::encode($form->piso);
                        $posicion = Html::encode($form->posicion);
                        $lote = Html::encode($form->lote);
                        $rack = Html::encode($form->rack);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = AlmacenamientoProductoDetalles::find()
                                    ->andFilterWhere(['between', 'fecha_almacenamiento', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['like', 'producto', $producto])
                                    ->andFilterWhere(['=', 'id_piso', $piso])
                                    ->andfilterWhere(['=', 'id_rack', $rack])
                                    ->andfilterWhere(['=', 'id_posicion', $posicion]);
                        $table = $table->orderBy('id DESC');
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
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id  DESC']);
                            $this->actionExcelAlmacenamiento($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } 
                return $this->render('mover_posicion', [
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
    
    //PROCESO QUE LISTA O SEPARA EL PRODUCTO QUE CONTIENE EL PEDIDO
    public function actionListar_pedidos() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',74])->all()){
                $form = new \app\models\FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; $numero_pedido = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $numero_pedido = Html::encode($form->numero_pedido);
                        $table = \app\models\Pedidos::find()
                            ->andFilterWhere(['=', 'documento', $documento])
                            ->andFilterWhere(['=', 'id_cliente', $cliente])
                            ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                            ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                            ->andFilterWhere(['=','id_agente', $vendedores])
                            ->andWhere(['=','cerrar_pedido', 1])
                            ->andWhere(['=', 'pedido_anulado', 0])
                            ->andWhere(['=', 'facturado', 0]);
                        $table = $table->orderBy('id_pedido DESC');
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
                            $this->actionExcelconsultaPedidos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                    
                }else{
                    $table = \app\models\Pedidos::find()->Where(['=','cerrar_pedido', 1])
                            ->andWhere(['=', 'facturado', 0])
                            ->andWhere(['=', 'pedido_anulado', 0])
                            ->orderBy('id_pedido DESC');   
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
                        $this->actionExcelconsultaPedidos($tableexcel); 
                    }
                } 
                return $this->render('listar_pedidos', [
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
    //PROCESO QUE CARGA LAS OP
    public function actionCargar_orden_produccion() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',72])->all()){
                $form = new \app\models\FiltroBusquedaAlmacenamiento();
                $orden = null;
                $lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $orden = Html::encode($form->orden);
                        $lote = Html::encode($form->lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = OrdenProduccion::find()
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'numero_orden', $orden])
                                    ->andWhere(['=', 'producto_almacenado', 0])
                                    ->andWhere(['=', 'producto_aprobado', 1]);
                        $table = $table->orderBy('id_orden_produccion DESC');
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
                    $table = OrdenProduccion::find()->Where(['=', 'producto_almacenado', 0])
                                                    ->andWhere(['=', 'producto_aprobado', 1])
                                                    ->orderBy('id_orden_produccion DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }
                return $this->render('cargar_ordenes', [
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
    
    // PROCESO QUE CARGA LAS ENTRADAS DE PRODUCTOS PARA ALMACENAR
    public function actionCargar_entrada_producto() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',75])->all()){
                $form = new \app\models\FiltroBusquedaAlmacenamiento();
                $tipo_entrada = null;
                $proveedor = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $tipo_entrada = Html::encode($form->tipo_entrada);
                        $proveedor = Html::encode($form->proveedor);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = EntradaProductoTerminado::find()
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_proveedor', $proveedor])
                                    ->andFilterWhere(['=','tipo_entrada', $tipo_entrada])
                                    ->andWhere(['=', 'enviar_materia_prima', 1])
                                    ->andWhere(['=', 'producto_almacenado', 0]);
                        $table = $table->orderBy('id_entrada DESC');
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
                    $table = EntradaProductoTerminado::find()->Where(['=', 'enviar_materia_prima', 1])->andWhere(['=', 'producto_almacenado', 0])
                                                    ->orderBy('id_entrada DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }
                return $this->render('cargar_entrada_producto', [
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
    
    //CONSULTA ALMACENAIMIENTO DESDE LA ENTRADA DE PRODUCTOS
    public function actionSearch_almacenamiento_entrada() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',76])->all()){
                $form = new \app\models\FiltroBusquedaAlmacenamiento();
                $codigo = null;
                $lote = null;
                $rack = null;
                $piso = null;
                $posicion = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $producto = null;
                $model = null;
                $pages = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $producto = Html::encode($form->producto);
                        $piso = Html::encode($form->piso);
                        $posicion = Html::encode($form->posicion);
                        $lote = Html::encode($form->lote);
                        $rack = Html::encode($form->rack);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = \app\models\AlmacenamientoProductoEntradaDetalles::find()
                                    ->andFilterWhere(['between', 'fecha_almacenamiento', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['like', 'producto', $producto])
                                    ->andFilterWhere(['=', 'id_piso', $piso])
                                    ->andfilterWhere(['=', 'id_rack', $rack])
                                    ->andfilterWhere(['=', 'id_posicion', $posicion]);
                        $table = $table->orderBy('id DESC');
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
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id  DESC']);
                            $this->actionExcelAlmacenamientoEntrada($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } 
                return $this->render('search_almacenamiento_entrada', [
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
    
    //CONSULTA DE PEDIDOS LISTAD POR LOGISTICA
    public function actionSearch_pedidos_listados() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',79])->all()){
                $form = new \app\models\FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; $numero_pedido = null;
                $model = null;
                $pages = null;
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
                            ->andFilterWhere(['between','fecha_cierre_alistamiento', $fecha_inicio, $fecha_corte])
                            ->andFilterWhere(['=','numero_pedido', $numero_pedido])
                            ->andFilterWhere(['=','id_agente', $vendedores])
                            ->andWhere(['=','pedido_validado', 1]);
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
                    
                }
                return $this->render('pedidos_listados', [
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
    
    //VISTA DE ALMACENAMIENTO
    public function actionView_almacenamiento($id_orden, $token)
    {
        $detalle = AlmacenamientoProducto::find()->where(['=','id_orden_produccion', $id_orden])->all();
        $conAlmacenado = AlmacenamientoProductoDetalles::find()->where(['=','id_orden_produccion', $id_orden])->all();
        $model = OrdenProduccion::findOne($id_orden);
        return $this->render('view_almacenamiento', [
            'detalle' => $detalle,
            'id_orden' => $id_orden,
            'model' => $model,
            'conAlmacenado' => $conAlmacenado,
            'token' =>$token,
        ]);
    }
    
    //
     //VISTA DE ALMACENAMIENTO DE ENTRADAS
    public function actionView_almacenamiento_entrada($id_orden, $token)
    {
        $detalle = \app\models\AlmacenamientoProductoEntrada::find()->where(['=','id_entrada', $id_orden])->all();
        $conAlmacenado = \app\models\AlmacenamientoProductoEntradaDetalles::find()->where(['=','id_entrada', $id_orden])->all();
        $model = EntradaProductoTerminado::findOne($id_orden);
        return $this->render('view_almacenamiento_entrada', [
            'detalle' => $detalle,
            'id_orden' => $id_orden,
            'model' => $model,
            'conAlmacenado' => $conAlmacenado,
            'token' =>$token,
        ]);
    }
    
    //VISTA QUE LISTAS LOS PEDIDOS
    public function actionView_listar($id_pedido){
        $model = Pedidos::findOne($id_pedido);
        $pedido_detalle = PedidoDetalles::find()->where(['=','id_pedido', $id_pedido])->all();  
        $pedido_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id_pedido])->all();
        if(isset($_POST["regenerar_linea"])){
            if(isset($_POST["numero_linea"])){
                foreach ($_POST["numero_linea"] as $intCodigo){
                    $table = PedidoDetalles::findOne($intCodigo);
                    $table->cantidad = $table->cantidad_despachada;
                    $table->regenerar_linea = 0;
                    $table->save();
                    $this->ActualizarLineaDetallePedido($intCodigo);
                }
                $this->ActualizarTotalesPedido($id_pedido);
                return $this->redirect(['view_listar', 'id_pedido' => $id_pedido]);
            }
        }
        return $this->render('view_listar', [
            'model' => $model,
            'pedido_detalle' => $pedido_detalle,
            'pedido_presupuesto' => $pedido_presupuesto,
        ]);
    }
    
    //VISTA DE MOVER POSICIONES
    public function actionView_posiciones($id_posicion) {
        $model = AlmacenamientoProductoDetalles::findOne($id_posicion);
        $posiciones = \app\models\PosicionAlmacenamiento::find()->where(['=','id', $id_posicion])->orderBy('id_movimiento DESC')->all();
        return $this->render('view_mover_posiciones', [
            'model' => $model,
            'posiciones' => $posiciones,
        ]); 
    }
    
    // VISTA QUE LISTA LOS PEDIDOS LISTADOS
    public function actionView_pedido_listado($id) {
        
        $model = Pedidos::findOne($id);
        $detalle_pedido = PedidoDetalles::find()->where(['=','id_pedido', $id])->All();
        $detalle_presupuesto = PedidoPresupuestoComercial::find()->where(['=','id_pedido', $id])->All();
        return $this->render('view_pedidos_listados', [
            'model' => $model,   
            'detalle_pedido' => $detalle_pedido,
            'detalle_presupuesto' => $detalle_presupuesto,
        ]); 
    }


    /// PROCESO QUE ACTUALIZA PRECIOS DE SUBTOTALES
    protected function ActualizarLineaDetallePedido($intCodigo) {
        $table = PedidoDetalles::findOne($intCodigo);
        $inventario = \app\models\InventarioProductos::findOne($table->id_inventario);
        $subtotal = 0; $iva = 0; $total = 0; $porcentaje = 0; 
        if($inventario->aplica_iva == 0){
            $porcentaje = ''.number_format($inventario->porcentaje_iva /100, 2);
            $total = round($table->cantidad * $table->valor_unitario); 
            $iva = round($total  * $porcentaje);
            $subtotal  = $total - $iva;
            $table->subtotal = $subtotal;
            $table->impuesto = $iva;
            $table->total_linea = $total;
            $table->save();       
        }
    }
    
    //ACTUALIZA LOS SUBTOTALES
    protected function ActualizarTotalesPedido($id_pedido) {
        $subtotal = 0; $impuesto = 0; $total = 0; $cantidad = 0;
        $model = Pedidos::findOne($id_pedido);
        $detalle = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id_pedido])->all();
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
     
    }
    
   //PROCESO QUE PERMITE SUBIR LAS UNIDADES A DESPACHAR 
    public function actionCantidad_despachada($id_pedido, $id_detalle, $sw){
        $model = new \app\models\ModeloDocumento(); 
        if($sw == 0){
           $detalle = PedidoDetalles::findOne($id_detalle); 
        }else{
            $detalle = PedidoPresupuestoComercial::findOne($id_detalle);
        }
        
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST["cantidaddespachada"])){
                if($model->cantidad_despachada <= $detalle->cantidad){ 
                   if(isset($_POST["seleccione_item"])){
                       $valor = 0 ;
                        foreach ($_POST["seleccione_item"] as $intCodigo):
                            $cantidad = 0; $sobrante = 0; $restar = 0; $unidad_inventario = 0;
                            $base = AlmacenamientoProductoDetalles::findOne($intCodigo);
                            if($base->cantidad < $model->cantidad_despachada){
                                $cantidad = $base->cantidad;
                                $sobrante = $model->cantidad_despachada - $cantidad;
                                $restar = $cantidad - ($model->cantidad_despachada - $sobrante);
                                $base->cantidad = $restar;
                                $valor = $sobrante;
                                $unidad_inventario = $restar;
                            }else{
                                if($valor > 0){
                                    $cantidad = $base->cantidad - $valor;
                                    $base->cantidad = $cantidad;
                                    $valor = 0;
                                    $unidad_inventario = $valor;
                                }else{
                                    $cantidad = $base->cantidad - $model->cantidad_despachada;
                                    $base->cantidad = $cantidad;
                                    $unidad_inventario = $model->cantidad_despachada;
                                }   
                            }
                            $base->save(false);
                            $id_rack = $base->id_rack;
                            $unidades = $unidad_inventario;
                            $this->ActualizarUnidadesRack($id_rack, $unidades);
                        endforeach;
                        $detalle->cantidad_despachada = $model->cantidad_despachada;
                        $detalle->historico_cantidad_vendida = $detalle->cantidad;
                        $detalle->linea_validada = 1;
                        $detalle->numero_lote = $base->numero_lote;
                        $detalle->fecha_alistamiento = date('Y-m-d');
                        if($detalle->cantidad <> $model->cantidad_despachada){
                            $detalle->regenerar_linea = 1;
                        }else{
                           $detalle->regenerar_linea = 0; 
                        }
                        $detalle->save(false);
                        return $this->redirect(['view_listar', 'id_pedido' => $id_pedido]);
                   }else{
                       Yii::$app->getSession()->setFlash('info', 'Debe se chequear el RACK para descargar el inventario.');
                        return $this->redirect(['view_listar', 'id_pedido' => $id_pedido]);
                   }
                }else{
                    Yii::$app->getSession()->setFlash('warning', 'La cantidad de unidades despachas NO pueden ser mayor que las unidades vendidas.');
                    return $this->redirect(['view_listar', 'id_pedido' => $id_pedido]);
                }  
            }
        }
        if (Yii::$app->request->get()) {
           $model->cantidad_vendida = $detalle->cantidad;
        }
        return $this->render('_form_cantidad_despachada', [
                        'model' => $model,
                        'id_pedido' => $id_pedido,
                        'detalle' => $detalle,
                        'sw' => $sw,
        ]); 
        
    }
    
    //ACTUALIZAR UNIDADES RACK
    protected function ActualizarUnidadesRack($id_rack, $unidades) {
        $rack = TipoRack::findOne($id_rack);
        $suma = 0;
        $suma = $rack->capacidad_actual - $unidades;
        $rack->capacidad_actual = $suma;
        $rack->save();
    }
    
    //CREAR DOCUMENTO
    public function actionSubir_documento($id, $id_orden,$token, $sw) {
        $model = new \app\models\ModeloDocumento(); 
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nuevo_documento"])) {
                    if($sw == 0){
                        $table = \app\models\AlmacenamientoProducto::findOne($id) ;
                        $table->id_documento = $model->documento;
                        $table->save();
                        return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden, 'token' =>$token,]);
                    }else{
                        $table = \app\models\AlmacenamientoProductoEntrada::findOne($id) ;
                        $table->id_documento = $model->documento;
                        $table->save();
                        return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden, 'token' =>$token,]);
                    }
                   
                }
            }else{
              $model->getErrors();  
            }
         }
         return $this->renderAjax('form_subir_almacenamiento', [
                    'model' => $model,
                    'id' => $id,
                    'id_orden' => $id_orden, 
        ]);
    }
    
    //CAMBIAR DE POSICION
     public function actionCambiar_posicion($id_posicion, $sw) {
        $model = new \app\models\ModeloDocumento(); 
        if($sw == 0){
            $table = \app\models\AlmacenamientoProductoDetalles::findOne($id_posicion) ;
            $posicion_anterior = $table->posicion->posicion;
        }else{
            $table = \app\models\AlmacenamientoProductoEntradaDetalles::findOne($id_posicion) ;
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nueva_posicion"])) {
                    if($model->posicion <> ''){
                        if($sw == 0){
                            $table = \app\models\AlmacenamientoProductoDetalles::findOne($id_posicion) ;
                            $modelo = new \app\models\PosicionAlmacenamiento();
                            $modelo->id_piso = $table->id_piso;
                            $modelo->id_rack = $table->id_rack;
                            $modelo->id_posicion = $table->id_posicion;
                            $modelo->id_posicion_nueva = $model->posicion;
                            $modelo->codigo = $table->codigo_producto;
                            $modelo->producto = $table->producto;
                            $modelo->fecha_proceso = date('Y-m-d');
                            $modelo->user_name = Yii::$app->user->identity->username;
                            $modelo->id = $id_posicion;
                            $modelo->save();
                            $table->id_posicion = $model->posicion;
                            $table->save();
                            return $this->redirect(['mover_posiciones']);
                        }else{
                            $table = \app\models\AlmacenamientoProductoEntradaDetalles::findOne($id_posicion) ;
                            $table->id_posicion = $model->posicion;
                            $table->save();
                            return $this->redirect(['mover_posicion_entrada']);
                        }
                    }else{
                         Yii::$app->getSession()->setFlash('info', 'Debe se seleccionar una posicion para el cambio de almacenamiento.');
                        return $this->redirect(['mover_posiciones']);  
                       
                    }    
                }
            }else{
              $model->getErrors();  
            }
         }
         if (Yii::$app->request->get()) {
            $model->posicion = $table->id_posicion;
         }    
         return $this->renderAjax('form_mover_posicion', [
                    'model' => $model,
                    'id_posicion' => $id_posicion, 
                    'posicion_anterior' => $posicion_anterior,
        ]);
    }
    
    //CAMBIAR O MOVER DE RACK Y POSICION
    public function actionCambiar_almacenamiento_rack($id_rack) {
        $model = new \app\models\ModeloMoverPosicionRack();
        $conRacks = AlmacenamientoProductoDetalles::find()->where(['=','id_rack', $id_rack])->all(); 
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST["cambiar_posicion"])){
                if(isset($_POST["seleccione_item"])){
                    if($model->nuevo_rack <> ''){
                        foreach ($_POST["seleccione_item"] as $intCodigo):
                            $valor = 0;
                            $descontar = 0;
                            $table = AlmacenamientoProductoDetalles::findOne($intCodigo);
                            $Racks = TipoRack::findOne($model->nuevo_rack);
                            $rackSaliente = TipoRack::findOne($table->id_rack);
                            if($Racks->controlar_capacidad == 1){ //controla el stock en el rack
                                $valor = $table->cantidad + $Racks->capacidad_actual;
                                if($valor <= $Racks->capacidad_instalada){
                                    $cambio = new \app\models\PosicionAlmacenamiento();
                                    $cambio->id_piso = $table->id_piso;
                                    $cambio->id_rack = $table->id_rack;
                                    $cambio->id_rack_nuevo = $model->nuevo_rack;
                                    $cambio->id_posicion = $table->id_posicion;
                                    $cambio->id_posicion_nueva = $model->nueva_posicion;
                                    $cambio->codigo = $table->codigo_producto;
                                    $cambio->producto = $table->producto;
                                    $cambio->cantidad = $table->cantidad;
                                    $cambio->fecha_proceso = date('Y-m-d');
                                    $cambio->user_name = Yii::$app->user->identity->username;
                                    $cambio->id = $intCodigo;
                                    $cambio->save(); 
                                    //actualiza rack
                                    $Racks->capacidad_actual = $valor;
                                    $Racks->save();
                                    //descuenta del rack saliente
                                    $descontar = $rackSaliente->capacidad_actual - $table->cantidad;
                                    $rackSaliente->capacidad_actual = $descontar;
                                    $rackSaliente->save();
                                    //hace el cambio
                                    $table->id_rack = $model->nuevo_rack;
                                    if($model->nueva_posicion <> ''){
                                        $table->id_posicion = $model->nueva_posicion;
                                    }
                                    $table->save();
                                }
                            }else{
                                $valor = $table->cantidad + $Racks->capacidad_actual;
                                $cambio = new \app\models\PosicionAlmacenamiento();
                                $cambio->id_piso = $table->id_piso;    
                                $cambio->id_rack = $table->id_rack;
                                $cambio->id_rack_nuevo = $model->nuevo_rack;
                                $cambio->id_posicion = $table->id_posicion;
                                $cambio->id_posicion_nueva = $model->nueva_posicion;
                                $cambio->codigo = $table->codigo_producto;
                                $cambio->producto = $table->producto;
                                $cambio->cantidad = $table->cantidad;
                                $cambio->fecha_proceso = date('Y-m-d');
                                $cambio->user_name = Yii::$app->user->identity->username;
                                $cambio->id = $intCodigo;
                                $cambio->save(); 
                                //actualiza rack
                                $Racks->capacidad_actual = $valor;
                                $Racks->save();
                                //descuenta del rack saliente
                                $descontar = $rackSaliente->capacidad_actual - $table->cantidad;
                                $rackSaliente->capacidad_actual = $descontar;
                                $rackSaliente->save();
                                //hace el cambio
                                $table->id_rack = $model->nuevo_rack;
                                if($model->nueva_posicion <> ''){
                                    $table->id_posicion = $model->nueva_posicion;
                                }
                                $table->save();
                            }
                        endforeach;
                        return $this->redirect(['mover_posiciones']); 
                    }else{
                       Yii::$app->getSession()->setFlash('warning','Debe de seleccionar el NUEVO RACK para hacer el cambio.');
                       return $this->redirect(['cambiar_almacenamiento_rack', 'id_rack' => $id_rack]); 
                    }    
                    
                }else{
                    Yii::$app->getSession()->setFlash('info','Debe de seleccionar un registro de la tabla para procesar el cambio.');
                    return $this->redirect(['cambiar_almacenamiento_rack', 'id_rack' => $id_rack]);
                }
            }
        }    
        return $this->render('cambiar_rack', [
                'model' =>$model,
                'conRacks' => $conRacks,
                'id_rack' => $id_rack,
        ]);
    }
    
    //proceso que llena los rack dependiendo el piso
    public function actionLlenaracks($id){
        $rows = TipoRack::find()->where(['=','id_piso', $id])
                                ->andWhere(['=','estado', 0])->all();

        echo "<option value='' required>Seleccione el rack...</option>";
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id_rack' required>$row->descripcion</option>";
            }
        }
    }
    
    //ENVIAR UNIDADES AL RACK
    public function actionCrear_almacenamiento($id_orden, $id, $token) {
        $model = new \app\models\ModeloEnviarUnidadesRack();
        $racks = TipoRack::find()->where(['=','estado', 0])->all();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if(isset($_POST["crear_almacenamiento"])){
                    $total = 0; $cant = 0; $id_rack = 0; $capacidad = 0; $actual = 0; $Capacidad_requerida = 0;
                    $conProducto = AlmacenamientoProducto::findOne($id);
                    if($model->cantidad <= $conProducto->unidades_producidas){
                        if($conProducto->unidades_almacenadas == 0){
                            $tipo_rack = TipoRack::findOne($model->rack);
                            if($tipo_rack->controlar_capacidad == 1){
                                $capacidad = $tipo_rack->capacidad_instalada;
                                $actual = $tipo_rack->capacidad_actual + $model->cantidad;
                                $Capacidad_requerida = $capacidad - $tipo_rack->capacidad_actual;
                                if($actual <= $capacidad){
                                    $table = new AlmacenamientoProductoDetalles();
                                    $table->id_almacenamiento = $id;
                                    $table->id_orden_produccion = $id_orden;
                                    $table->id_rack = $model->rack;
                                    $table->id_piso = $model->piso;
                                    $table->id_posicion = $model->posicion; 
                                    $table->cantidad = $model->cantidad;
                                    $table->id_inventario = $conProducto->id_inventario;
                                    $table->codigo_producto = $conProducto->codigo_producto;
                                    $table->producto = $conProducto->nombre_producto;
                                    $table->numero_lote = $conProducto->numero_lote;
                                    $table->fecha_almacenamiento = $conProducto->fecha_almacenamiento;
                                    $table->fecha_proceso_lote = $conProducto->ordenProduccion->fecha_proceso;
                                    $table->save(false);
                                    $cant = $model->cantidad;
                                    $id_rack = $model->rack;
                                    $this->ActualizarUnidadesAlmacenadas($id, $id_orden);
                                    $this->SumarUnidadesRack($id_rack, $cant);
                                    return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden, 'token' =>$token]);  
                                }else{
                                    Yii::$app->getSession()->setFlash('warning', 'El RACK seleccionado tiene un cupo de almacenamiento de ('.$tipo_rack->capacidad_instalada.') unidades. Solo tiene capacidad para almacenar ('.$Capacidad_requerida.') unidades.!');
                                    return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden, 'token' =>$token]); 
                                }
                            }else{
                                $table = new AlmacenamientoProductoDetalles();
                                $table->id_almacenamiento = $id;
                                $table->id_orden_produccion = $id_orden;
                                $table->id_rack = $model->rack;
                                $table->id_piso = $model->piso;
                                $table->id_posicion = $model->posicion;     
                                $table->cantidad = $model->cantidad;
                                $table->id_inventario = $conProducto->id_inventario;
                                $table->codigo_producto = $conProducto->codigo_producto;
                                $table->producto = $conProducto->nombre_producto;
                                $table->numero_lote = $conProducto->numero_lote;
                                $table->fecha_almacenamiento = $conProducto->fecha_almacenamiento;
                                $table->fecha_proceso_lote = $conProducto->ordenProduccion->fecha_proceso;
                                $table->save(false);
                                $cant = $model->cantidad;
                                $id_rack = $model->rack;
                                $this->ActualizarUnidadesAlmacenadas($id, $id_orden);
                                $this->SumarUnidadesRack($id_rack, $cant);
                                return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden, 'token' =>$token]); 
                            }    
                        }else{
                            $total = $conProducto->unidades_faltantes;
                            if($model->cantidad <= $total){
                                $tipo_rack = TipoRack::findOne($model->rack);
                                if($tipo_rack->controlar_capacidad == 1){
                                    $capacidad = $tipo_rack->capacidad_instalada;
                                    $actual = $tipo_rack->capacidad_actual + $model->cantidad;
                                    $Capacidad_requerida = $capacidad - $tipo_rack->capacidad_actual;
                                    if($actual <= $capacidad){
                                        $table = new AlmacenamientoProductoDetalles();
                                        $table->id_almacenamiento = $id;
                                        $table->id_orden_produccion = $id_orden;
                                        $table->id_rack = $model->rack;
                                        $table->id_piso = $model->piso;
                                        $table->id_posicion = $model->posicion; 
                                        $table->cantidad = $model->cantidad;
                                         $table->id_inventario = $conProducto->id_inventario;
                                        $table->codigo_producto = $conProducto->codigo_producto;
                                        $table->producto = $conProducto->nombre_producto;
                                        $table->numero_lote = $conProducto->numero_lote;
                                        $table->fecha_almacenamiento = $conProducto->fecha_almacenamiento;
                                        $table->fecha_proceso_lote = $conProducto->ordenProduccion->fecha_proceso;
                                        $table->save(false);
                                        $cant = $model->cantidad;
                                        $id_rack = $model->rack;
                                        $this->ActualizarUnidadesAlmacenadas($id, $id_orden);
                                        $this->SumarUnidadesRack($id_rack, $cant);
                                        return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden, 'token' =>$token]); 
                                    }else{
                                        Yii::$app->getSession()->setFlash('warning', 'El RACK seleccionado tiene un cupo de almacenamiento de ('.$tipo_rack->capacidad_instalada.') unidades. Solo tiene capacidad para almacenar ('.$Capacidad_requerida.') unidades.!');
                                        return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden, 'token' =>$token]); 
                                    } 
                                }else{
                                    $table = new AlmacenamientoProductoDetalles();
                                    $table->id_almacenamiento = $id;
                                    $table->id_orden_produccion = $id_orden;
                                    $table->id_rack = $model->rack;
                                    $table->id_piso = $model->piso;
                                    $table->id_posicion = $model->posicion; 
                                    $table->cantidad = $model->cantidad;
                                     $table->id_inventario = $conProducto->id_inventario;
                                    $table->codigo_producto = $conProducto->codigo_producto;
                                    $table->producto = $conProducto->nombre_producto;
                                    $table->numero_lote = $conProducto->numero_lote;
                                    $table->fecha_almacenamiento = $conProducto->fecha_almacenamiento;
                                    $table->fecha_proceso_lote = $conProducto->ordenProduccion->fecha_proceso;
                                    $table->save(false);
                                    $cant = $model->cantidad;
                                    $id_rack = $model->rack;
                                    $this->ActualizarUnidadesAlmacenadas($id, $id_orden);
                                    $this->SumarUnidadesRack($id_rack, $cant);
                                    return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden, 'token' =>$token]);  
                                }    
                            }else{
                                Yii::$app->getSession()->setFlash('info', 'Las unidades que se van a ALMACENAR son mayores con las unidades PRODUCIDAS.!');
                                return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden, 'token' =>$token]); 
                            }
                        }    
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'Las unidades ENVIADAS son mayores que las unidades RESTANTES.!');
                        return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden, 'token' =>$token]); 
                    }    
                }
            }else{
                $model->getErrors();
            }
        }
        $conDato = AlmacenamientoProducto::findOne($id);
        if($conDato->id_documento == null){
             Yii::$app->getSession()->setFlash('warning', 'Debe de  crear primero el DOCUMENTO de almacenamiento.');
             return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden,'token' =>$token]); 
        }else{
            return $this->renderAjax('_enviar_unidades_almacenamiento', [
                        'model' => $model,
                        'id' => $id,
                        'id_orden' => $id_orden, 
                        'token' =>$token,
                        'tipo_rack' => ArrayHelper::map($racks, "id_rack", "tiporack"),
            ]);
        }    
    }
    
    //ENVIAR UNIDADES AL RACK DE ENTRADAS
     public function actionCrear_almacenamiento_entradas($id_orden, $id, $token) {
        $model = new \app\models\ModeloEnviarUnidadesRack();
        $racks = TipoRack::find()->where(['=','estado', 0])->all();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if(isset($_POST["crear_almacenamiento"])){
                    $total = 0; $cant = 0; $id_rack = 0; $capacidad = 0; $actual = 0; $Capacidad_requerida = 0;
                    $conProducto = \app\models\AlmacenamientoProductoEntrada::findOne($id);
                    if($model->cantidad <= $conProducto->unidad_producidas){
                        if($conProducto->unidades_almacenadas == 0){
                            $tipo_rack = TipoRack::findOne($model->rack);
                            if($tipo_rack->controlar_capacidad == 1){
                                $capacidad = $tipo_rack->capacidad_instalada;
                                $actual = $tipo_rack->capacidad_actual + $model->cantidad;
                                $Capacidad_requerida = $capacidad - $tipo_rack->capacidad_actual;
                                if($actual <= $capacidad){
                                    $table = new \app\models\AlmacenamientoProductoEntradaDetalles();
                                    $table->id_almacenamiento = $id;
                                    $table->id_entrada = $id_orden;
                                    $table->id_rack = $model->rack;
                                    $table->id_piso = $model->piso;
                                    $table->id_posicion = $model->posicion; 
                                    $table->cantidad = $model->cantidad;
                                    $table->id_inventario = $conProducto->id_inventario;
                                    $table->codigo_producto = $conProducto->codigo_producto;
                                    $table->producto = $conProducto->nombre_producto;
                                    $table->numero_lote = $conProducto->numero_soporte;
                                    $table->fecha_almacenamiento = $conProducto->fecha_almacenamiento;
                                    $table->save(false);
                                    $cant = $model->cantidad;
                                    $id_rack = $model->rack;
                                    $this->ActualizarUnidadesAlmacenadasEntradas($id, $id_orden);
                                    $this->SumarUnidadesRack($id_rack, $cant);
                                    return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden, 'token' =>$token]);  
                                }else{
                                    Yii::$app->getSession()->setFlash('warning', 'El RACK seleccionado tiene un cupo de almacenamiento de ('.$tipo_rack->capacidad_instalada.') unidades. Solo tiene capacidad para almacenar ('.$Capacidad_requerida.') unidades.!');
                                    return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden, 'token' =>$token]); 
                                }
                            }else{
                                $table = new \app\models\AlmacenamientoProductoEntradaDetalles();
                                $table->id_almacenamiento = $id;
                                $table->id_entrada = $id_orden;
                                $table->id_rack = $model->rack;
                                $table->id_piso = $model->piso;
                                $table->id_posicion = $model->posicion;     
                                $table->cantidad = $model->cantidad;
                                $table->id_inventario = $conProducto->id_inventario;
                                $table->codigo_producto = $conProducto->codigo_producto;
                                $table->producto = $conProducto->nombre_producto;
                                $table->numero_lote = $conProducto->numero_soporte;
                                $table->fecha_almacenamiento = $conProducto->fecha_almacenamiento;
                                $table->save(false);
                                $cant = $model->cantidad;
                                $id_rack = $model->rack;
                                $this->ActualizarUnidadesAlmacenadasEntradas($id, $id_orden);
                                $this->SumarUnidadesRack($id_rack, $cant);
                                return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden, 'token' =>$token]); 
                            }    
                        }else{
                            $total = $conProducto->unidades_faltantes;
                            if($model->cantidad <= $total){
                                $tipo_rack = TipoRack::findOne($model->rack);
                                if($tipo_rack->controlar_capacidad == 1){
                                    $capacidad = $tipo_rack->capacidad_instalada;
                                    $actual = $tipo_rack->capacidad_actual + $model->cantidad;
                                    $Capacidad_requerida = $capacidad - $tipo_rack->capacidad_actual;
                                    if($actual <= $capacidad){
                                        $table = new \app\models\AlmacenamientoProductoEntradaDetalles();
                                        $table->id_almacenamiento = $id;
                                        $table->id_entrada = $id_orden;
                                        $table->id_rack = $model->rack;
                                        $table->id_piso = $model->piso;
                                        $table->id_posicion = $model->posicion; 
                                        $table->cantidad = $model->cantidad;
                                         $table->id_inventario = $conProducto->id_inventario;
                                        $table->codigo_producto = $conProducto->codigo_producto;
                                        $table->producto = $conProducto->nombre_producto;
                                        $table->numero_lote = $conProducto->numero_soporte;
                                        $table->fecha_almacenamiento = $conProducto->fecha_almacenamiento;
                                        $table->save(false);
                                        $cant = $model->cantidad;
                                        $id_rack = $model->rack;
                                        $this->ActualizarUnidadesAlmacenadasEntradas($id, $id_orden);
                                        $this->SumarUnidadesRack($id_rack, $cant);
                                        return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden, 'token' =>$token]); 
                                    }else{
                                        Yii::$app->getSession()->setFlash('warning', 'El RACK seleccionado tiene un cupo de almacenamiento de ('.$tipo_rack->capacidad_instalada.') unidades. Solo tiene capacidad para almacenar ('.$Capacidad_requerida.') unidades.!');
                                        return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden, 'token' =>$token]); 
                                    } 
                                }else{
                                    $table = new \app\models\AlmacenamientoProductoEntradaDetalles();
                                    $table->id_almacenamiento = $id;
                                    $table->id_entrada = $id_orden;
                                    $table->id_rack = $model->rack;
                                    $table->id_piso = $model->piso;
                                    $table->id_posicion = $model->posicion; 
                                    $table->cantidad = $model->cantidad;
                                     $table->id_inventario = $conProducto->id_inventario;
                                    $table->codigo_producto = $conProducto->codigo_producto;
                                    $table->producto = $conProducto->nombre_producto;
                                    $table->numero_lote = $conProducto->numero_soporte;
                                    $table->fecha_almacenamiento = $conProducto->fecha_almacenamiento;
                                    $table->save(false);
                                    $cant = $model->cantidad;
                                    $id_rack = $model->rack;
                                    $this->ActualizarUnidadesAlmacenadasEntradas($id, $id_orden);
                                    $this->SumarUnidadesRack($id_rack, $cant);
                                    return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden, 'token' =>$token]);  
                                }    
                            }else{
                                Yii::$app->getSession()->setFlash('info', 'Las unidades que se van a ALMACENAR son mayores con las unidades PRODUCIDAS.!');
                                return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden, 'token' =>$token]); 
                            }
                        }    
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'Las unidades ENVIADAS son mayores que las unidades RESTANTES.!');
                        return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden, 'token' =>$token]); 
                    }    
                }
            }else{
                $model->getErrors();
            }
        }
        $conDato = \app\models\AlmacenamientoProductoEntrada::findOne($id);
        if($conDato->id_documento == null){
             Yii::$app->getSession()->setFlash('warning', 'Debe de  crear primero el DOCUMENTO de almacenamiento.');
             return $this->redirect(['view_almacenamiento_entrada', 'id_orden' => $id_orden,'token' =>$token]); 
        }else{
            return $this->renderAjax('_enviar_unidades_almacenamiento', [
                        'model' => $model,
                        'id' => $id,
                        'id_orden' => $id_orden, 
                        'token' =>$token,
                        'tipo_rack' => ArrayHelper::map($racks, "id_rack", "tiporack"),
            ]);
        }    
    }
    
    //PROCESO QUE ACUTLIZA UNIDADES DE ORDEN DE PRODUCCION
    protected function ActualizarUnidadesAlmacenadas($id, $id_orden) {
        $almacenamiento = \app\models\AlmacenamientoProducto::findOne($id);
        $detalle = \app\models\AlmacenamientoProductoDetalles::find()->where(['=','id_almacenamiento', $id])->all();
        $suma = 0;
        foreach ($detalle as $detalles):
            $suma += $detalles->cantidad;    
        endforeach;
        $almacenamiento->unidades_almacenadas = $suma;
        $almacenamiento->unidades_faltantes = $almacenamiento->unidades_producidas - $suma;
        $almacenamiento->save();
    }
    
     //PROCESO QUE ACTULIZA UNIDADES DE ENTRADAS
    protected function ActualizarUnidadesAlmacenadasEntradas($id, $id_orden) {
        $almacenamiento = \app\models\AlmacenamientoProductoEntrada::findOne($id);
        $detalle = \app\models\AlmacenamientoProductoEntradaDetalles::find()->where(['=','id_almacenamiento', $id])->all();
        $suma = 0;
        foreach ($detalle as $detalles):
            $suma += $detalles->cantidad;    
        endforeach;
        $almacenamiento->unidades_almacenadas = $suma;
        $almacenamiento->unidades_faltantes = $almacenamiento->unidad_producidas - $suma;
        $almacenamiento->save();
    }
    //RELACION DE PISOS CON RACKS
    
     public function actionPiso_rack($id){
        $rows = \app\models\TipoRack::find()->where(['=','id_piso', $id])
                                               ->andWhere(['=','estado', 0])->all();

        echo "<option value='' required>Seleccione el rack...</option>";
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id_rack' required>$row->numero_rack - $row->descripcion 'Stock' $row->capacidad_actual</option>";
            }
        }
    }
    ///PROCESO QUE SUMA  LAS UNIDADES EN CADA RACK
    protected function SumarUnidadesRack($id_rack, $cant) {
        $rack = \app\models\TipoRack::findOne($id_rack);
        $rack->capacidad_actual =  $rack->capacidad_actual +  $cant;
        $rack->save(false);
    }
    
    /**
     * Creates a new AlmacenamientoProducto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   
    ///PROCESO QUE CARGA EL PROCESO PARA ALMACENAR.
    public function actionEnviar_lote_almacenar($id_orden, $sw) {
        if($sw == 0){
            $lotes = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id_orden])->all();
        }else{
            $lotes = \app\models\EntradaProductoTerminadoDetalle::find()->where(['=','id_entrada', $id_orden])->all();
        }    
        $con = 0;
        foreach ($lotes as $detalle):
            if($sw == 0){ // proceso que almacena ordenes de produccion
                $dato = \app\models\InventarioProductos::find()->where(['=','codigo_producto', $detalle->codigo_producto])->one();
                $table = new AlmacenamientoProducto();
                $table->id_orden_produccion = $id_orden;
                $table->id_inventario = $dato->id_inventario;
                $table->codigo_producto = $detalle->codigo_producto;
                $table->nombre_producto = $detalle->descripcion;
                $table->unidades_producidas = $detalle->cantidad;
                $table->fecha_almacenamiento = date('Y-m-d');
                $table->numero_lote = $detalle->numero_lote;
                $table->user_name = Yii::$app->user->identity->username;
                $table->save(false);
                $con += 1;
            }else{ //proceso que almacena entradas de producto
                $dato = \app\models\InventarioProductos::find()->where(['=','id_inventario', $detalle->id_inventario])->one();
                $table = new \app\models\AlmacenamientoProductoEntrada();
                $table->id_entrada = $id_orden;
                $table->numero_soporte = $detalle->entrada->numero_soporte;
                $table->id_inventario = $dato->id_inventario;
                $table->codigo_producto = $detalle->codigo_producto;
                $table->nombre_producto = $dato->nombre_producto;
                $table->unidad_producidas = $detalle->cantidad;
                $table->fecha_almacenamiento = date('Y-m-d');
                $table->user_name = Yii::$app->user->identity->username;
                $table->save(false);
                $con += 1;
            }    
        endforeach;
        if($sw == 0){
            Yii::$app->getSession()->setFlash('success', 'Se exporto (' . $con.') lotes de la orden de produccion No ('. $id_orden.') al modulo de almacenamiento con éxito.');
            return $this->redirect(['cargar_orden_produccion']);
        }else{
            Yii::$app->getSession()->setFlash('success', 'Se exporto (' . $con.') lineas de la entrada No ('. $id_orden.') al modulo de almacenamiento con éxito.');
            return $this->redirect(['cargar_entrada_producto']);
        }    
    }
    
   
    /**
     * Deletes an existing AlmacenamientoProducto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    //ELIMINAR ALMACENAMIENTO
      public function actionEliminar_detalle_almacenamiento($id_orden, $detalle, $token, $sw)
    {                                
        if($sw == 0){
            $conBuscar = AlmacenamientoProducto::findOne($detalle);
        } else{
            $conBuscar = \app\models\AlmacenamientoProductoEntrada::findOne($detalle);
        }
        $conBuscar->delete();
        if($sw == 0){
            return $this->redirect(["view_almacenamiento", 'id_orden' => $id_orden, 'token' =>$token]);        
        }else{
            return $this->redirect(["view_almacenamiento_entrada", 'id_orden' => $id_orden, 'token' =>$token]);        
        }
        
    }
    
    //ELIMINAR ALMACENAMIENTO RACKS
      public function actionEliminar_items_rack($id_orden, $id_detalle, $token, $sw)
    {                                
        if($sw == 0){
            $conBuscar = AlmacenamientoProductoDetalles::findOne($id_detalle);
        }else{
            $conBuscar = \app\models\AlmacenamientoProductoEntradaDetalles::findOne($id_detalle);
        }
        $conBuscar->delete();
        $codigo = $conBuscar->id_almacenamiento;
        $rack = $conBuscar->id_rack;
        $cantidades = $conBuscar->cantidad;
        $this->ActualizarUnidadesEliminadas($codigo, $sw);
        $this->DescontarUnidadesRack($rack, $cantidades);
        if($sw == 0){
            return $this->redirect(["view_almacenamiento", 'id_orden' => $id_orden, 'token' =>$token]);        
        }else{
            return $this->redirect(["view_almacenamiento_entrada", 'id_orden' => $id_orden, 'token' =>$token]);        
        }    
    }
    
    //PROCESO QUE ACTUALIZA UNIDADES ALMACENADAS CUANDO SE ELIMINA
    protected function ActualizarUnidadesEliminadas($codigo, $sw) {
        if($sw == 0){
            $almacenamiento = AlmacenamientoProducto::findOne($codigo);
            $detalle = AlmacenamientoProductoDetalles::find()->where(['=','id_almacenamiento', $codigo])->all();
        }else{
            $almacenamiento = \app\models\AlmacenamientoProductoEntrada::findOne($codigo);
            $detalle = \app\models\AlmacenamientoProductoEntradaDetalles::find()->where(['=','id_almacenamiento', $codigo])->all();
        }
        $suma = 0; $total = 0;
        foreach ($detalle as $detalles):
            $suma += $detalles->cantidad;    
        endforeach;
        if($sw == 0){
             $total = $almacenamiento->unidades_producidas;
            $almacenamiento->unidades_faltantes = $total - $suma;
            $almacenamiento->unidades_almacenadas = $suma;
        }else{
            $total = $almacenamiento->unidad_producidas;
            $almacenamiento->unidades_faltantes = $total - $suma;
            $almacenamiento->unidades_almacenadas = $suma;
        }    
        $almacenamiento->save();
    }
    
    //PROCESO QUE DESCUENTA LAS UNIDADES EL RACK CUANDO SE ELIMINA
    protected function DescontarUnidadesRack($rack, $cantidades) {
        $tipo_rac = \app\models\TipoRack::findOne($rack);
        $tipo_rac->capacidad_actual = $tipo_rac->capacidad_actual - $cantidades;
        $tipo_rac->save();
    }

    //PROCES QUE CIERRA LA ORDEN PRODUCCION
    public function actionCerrar_orden_produccion($id_orden) {
        $orden = OrdenProduccion::findOne($id_orden);
        $orden->producto_almacenado = 1;
        $orden->save(false);
        return $this->redirect(["cargar_orden_produccion"]);
    }
    
    //PROCES QUE CIERRA LA ORDEN PRODUCCION
    public function actionCerrar_entrada_producto($id_orden) {
        $orden = EntradaProductoTerminado::findOne($id_orden);
        $orden->producto_almacenado = 1;
        $orden->save(false);
        return $this->redirect(["cargar_entrada_producto"]);
    }
    
    // CERRAR PEDIDO PARA FACTURACION
    public function actionPedido_validado_facturacion($id_pedido) {
        $pedido = Pedidos::findOne($id_pedido);
        $pedido->pedido_validado = 1;
        $pedido->fecha_cierre_alistamiento = date('Y-m-d');
        $pedido->save();
        return $this->redirect(["listar_pedidos"]);
    }
    /**
     * Finds the AlmacenamientoProducto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AlmacenamientoProducto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AlmacenamientoProducto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
     public function actionExcelAlmacenamiento($tableexcel) {                
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
                    ->setCellValue('A1', 'ID ALMACENAMIENTO')
                    ->setCellValue('B1', 'TIPO DOCUMENTO')
                    ->setCellValue('C1', 'NRO PISO')
                    ->setCellValue('D1', 'NRO RACK')
                    ->setCellValue('E1', 'CAPACIDAD')
                    ->setCellValue('F1', 'U. ALMACENADAS')
                    ->setCellValue('G1', 'STOCK')
                    ->setCellValue('H1', 'POSICION')
                    ->setCellValue('I1', 'OP')
                    ->setCellValue('J1', 'CODIGO PRODUCTO')
                    ->setCellValue('K1', 'NOMBRE PRODUCTO')
                    ->setCellValue('L1', 'NRO LOTE')
                    ->setCellValue('M1', 'FECHA ALMACENAMIENTO')
                    ->setCellValue('N1', 'USER NAME');
                   
        $i = 2;
        
        foreach ($tableexcel as $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $val->id_almacenamiento)
                ->setCellValue('B' . $i, $val->almacenamiento->documento->concepto)
                ->setCellValue('C' . $i, $val->piso->descripcion)
                ->setCellValue('D' . $i, $val->rack->descripcion)
                ->setCellValue('E' . $i, $val->rack->capacidad_instalada)
                ->setCellValue('F' . $i, $val->rack->capacidad_actual)
                ->setCellValue('G' . $i, $val->cantidad)
                ->setCellValue('H' . $i, $val->posicion->posicion)
                ->setCellValue('I' . $i, $val->ordenProduccion->numero_orden)
                ->setCellValue('J' . $i, $val->codigo_producto)
                ->setCellValue('K' . $i, $val->producto)
                ->setCellValue('L' . $i, $val->numero_lote)
                ->setCellValue('M' . $i, $val->fecha_almacenamiento)
                ->setCellValue('N' . $i, $val->almacenamiento->user_name);
        $i++;
             
        }

        $objPHPExcel->getActiveSheet()->setTitle('Almacenamiento');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Producto_almacenado.xlsx"');
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
    
    //PERMITE EXPORTAR A EXCEL LOS PEDIDOS ALISTADOS 
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'No PEDIDO')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'FECHA PEDIDO')
                        ->setCellValue('F1', 'FECHA ENTREGA')
                        ->setCellValue('G1', 'FECHA VALIDADO')
                        ->setCellValue('H1', 'CANTIDAD')
                        ->setCellValue('I1', 'SUBTOTAL')
                        ->setCellValue('J1', 'IVA')
                        ->setCellValue('K1', 'TOTAL')
                        ->setCellValue('L1', 'VENDEDOR')    
                        ->setCellValue('M1', 'USER NAME')
                        ->setCellValue('N1', 'AUTORIZADO')
                        ->setCellValue('O1', 'CERRADO')
                        ->setCellValue('P1', 'FACTURADO')
                        ->setCellValue('Q1', 'APLICA PRESUPUESTO')
                        ->setCellValue('R1', 'VALOR PRESUPUESTO');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_pedido)
                        ->setCellValue('B' . $i, $val->numero_pedido)
                        ->setCellValue('C' . $i, $val->documento)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->fecha_proceso)
                        ->setCellValue('F' . $i, $val->fecha_entrega)
                        ->setCellValue('G' . $i, $val->fecha_cierre_alistamiento)
                        ->setCellValue('H' . $i, $val->cantidad)
                        ->setCellValue('I' . $i, $val->subtotal)
                        ->setCellValue('J' . $i, $val->impuesto)
                        ->setCellValue('K' . $i, $val->gran_total)
                        ->setCellValue('L' . $i, $val->agentePedido->nombre_completo)
                        ->setCellValue('M' . $i, $val->usuario)
                        ->setCellValue('N' . $i, $val->autorizadoPedido)
                        ->setCellValue('O' . $i, $val->pedidoAbierto)
                        ->setCellValue('P' . $i, $val->pedidoFacturado)
                        ->setCellValue('Q' . $i, $val->presupuestoPedido)
                        ->setCellValue('R' . $i, $val->valor_presupuesto);
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Pedidos_listados.xlsx"');
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
