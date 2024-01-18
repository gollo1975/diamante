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
use app\models\OrdenProduccion;
use app\models\OrdenProduccionSearch;
use app\models\UsuarioDetalle;
use app\models\GrupoProducto;
use app\models\OrdenProduccionProductos;
use app\models\FiltroBusquedaOrdenProduccion;
use app\models\InventarioProductos;
use app\models\MateriaPrimas;
use app\models\FormModeloBuscar;
use app\models\OrdenProduccionMateriaPrima;
use app\models\ModelCrearPrecios;
/**
 * OrdenProduccionController implements the CRUD actions for OrdenProduccion model.
 */
class OrdenProduccionController extends Controller
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
     * Lists all OrdenProduccion models.
     * @return mixed
     */
     public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',18])->all()){
                $form = new FiltroBusquedaOrdenProduccion();
                $numero = null;
                $lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $almacen = null;
                $autorizado = null;
                $grupo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $lote = Html::encode($form->lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $almacen = Html::encode($form->almacen);
                        $grupo = Html::encode($form->grupo);
                        $autorizado = Html::encode($form->autorizado);
                        $table = OrdenProduccion::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_almacen', $almacen])
                                    ->andFilterWhere(['=', 'autorizado', $autorizado])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'id_grupo', $grupo]);
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
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_orden_produccion  DESC']);
                            $this->actionExcelConsultaOrdenProduccion($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = OrdenProduccion::find()
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
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaOrdenProduccion($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
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
    
    //CONSULTA DE ORDEN DE PRODUCCION
    public function actionSearch_consulta_orden($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',23])->all()){
                $form = new FiltroBusquedaOrdenProduccion();
                $numero = null;
                $lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $almacen = null;
                $autorizado = null;
                $grupo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $lote = Html::encode($form->lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $almacen = Html::encode($form->almacen);
                        $grupo = Html::encode($form->grupo);
                        $autorizado = Html::encode($form->autorizado);
                        $table = OrdenProduccion::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_almacen', $almacen])
                                    ->andFilterWhere(['=', 'autorizado', $autorizado])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'id_grupo', $grupo])
                                    ->andWhere(['=','cerrar_orden', 1]); 
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
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_orden_produccion  DESC']);
                            $this->actionExcelConsultaOrdenProduccion($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = OrdenProduccion::find()
                            ->Where(['=','cerrar_orden', 1])
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
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaOrdenProduccion($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_consulta_orden', [
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
    
    // VISTA DE REGLAS Y DESCUENTOS
    public function actionView_regla_descuento($id){
        $model = InventarioProductos::findOne($id);
        $regla_punto = \app\models\InventarioReglaDescuento::find()->where(['=','id_inventario', $id])->all();
        $regla_distribuidor = \app\models\ReglaDescuentoDistribuidor::find()->where(['=','id_inventario', $id])->all();
        return $this->render('view_crear_regla_descuento', [
                            'model' => $model,
                            'regla_punto' => $regla_punto,
                            'regla_distribuidor' => $regla_distribuidor,
                            'id' => $id,
        ]);
    }
    
    //CREAR PRECIOS DE VENTA
    public function actionCrear_precio_venta() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',27])->all()){
                $form = new ModelCrearPrecios();
                $codigo= null;
                $producto = null;
                $grupo = null;
                $sw  = 0;
                $model = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $grupo = Html::encode($form->grupo);
                        $producto = Html::encode($form->producto);
                        $table = InventarioProductos::find()
                                        ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                        ->andFilterWhere(['=', 'id_grupo', $grupo])
                                        ->andFilterWhere(['like', 'nombre_producto', $producto])
                                        ->andWhere(['>','stock_unidades', 0])->all(); 
                            $model = $table;
                            $sw = 1;
                     } else {
                        $form->getErrors();
                    }
                }
                return $this->render('crearpreciosventa', [
                            'model' => $model,
                            'form' => $form,
                            'sw' => $sw,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //PERMITE CREAR LOS PRECIOS DE VENTA PARA CADA PRODUCTO PARA VENDER AL POR MAYOR
    public function actionCrearprecioventaproducto($id) {
       $model = InventarioProductos::findOne($id);
       $listado_precio = \app\models\InventarioPrecioVenta::find()->where(['=','id_inventario', $id])->all();
       if(isset($_POST["actualizar_precio_venta"])){
            if(isset($_POST["detalle_precios"])){
                $intIndice = 0;
                foreach ($_POST["detalle_precios"] as $intCodigo):
                    $table = \app\models\InventarioPrecioVenta::findOne($intCodigo);
                    $table->precio_venta_publico = $_POST["precio_venta_publico"]["$intIndice"];
                    $table->id_posicion = $_POST["posicion"]["$intIndice"];
                    $table->iva_incluido = $_POST["iva_incluido"]["$intIndice"];
                    $table->user_name_editado = Yii::$app->user->identity->username;
                    $table->fecha_editado = date('Y-m-d');
                    $table->save(false);
                    $intIndice++;
                endforeach;
                return $this->redirect(['crearprecioventaproducto','id' =>$id]);
            }
        }    
        return $this->render('view_precio_venta', [
                            'model' => $model,
                             'listado_precio' => $listado_precio,
                ]);
    }
    
    //PERMITE CREAR LOS PRECIOS DE VENTA PARA CADA PRODUCTO PARA VENDER AL POR MAYOR
     public function actionCrear_reglas_descuento_punto($id) {
        $model = new \app\models\InventarioReglaDescuento();
        $table = InventarioProductos::findOne($id);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name = Yii::$app->user->identity->username;
            $model->id_inventario = $id;
            $model->save();
            $table->aplica_descuento = 1;
            $table->save();
            return $this->redirect(['crear_precio_venta']);
        }
        return $this->render('view_regla_descuento', [
               'model' => $model,
               'table' => $table,
               ]);
    }
    
    //PROCESO QUE CREA EL NUEVO PRECIO
    public function actionNuevo_precio_venta($id) {
        $model = new \app\models\FormModeloAsignarPrecioVenta();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["crear_precio"])) {
                    if($model->nuevo_precio > 0){
                        $table = new \app\models\InventarioPrecioVenta();
                        $table->id_inventario = $id;
                        $table->precio_venta_publico = $model->nuevo_precio;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->save(false);
                        $this->redirect(["orden-produccion/crearprecioventaproducto", 'id' => $id]);
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'No se asignó ninugun precio de venta a público. Ingrese nuevamente.'); 
                        $this->redirect(["orden-produccion/crearprecioventaproducto", 'id' => $id]);
                    }    
                }    
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('new_precio_venta', [
            'model' => $model,
            'id' => $id,
        ]);
    }    
      
   
    //VISTA DE LA ORDEN DE PRODUCCIO
    public function actionView($id, $token)
    {
        $detalle_orden = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id])->all();
        $detalle_materia = OrdenProduccionMateriaPrima::find()->where(['=','id_orden_produccion', $id])->all();
        if(isset($_POST["actualizamateriaprima"])){
            if(isset($_POST["listado_materia"])){
                $intIndice = 0;
                foreach ($_POST["listado_materia"] as $intCodigo):
                    $table = OrdenProduccionMateriaPrima::findOne($intCodigo);
                    $materia = MateriaPrimas::findOne($table->id_materia_prima);
                    if($_POST["cantidad_materia"]["$intIndice"] > $materia->stock){
                       Yii::$app->getSession()->setFlash('warning', 'No hay STOCK para estas unidades. Favor revisar el stock de materias primas.');
                        $table->cantidad = 0;
                    }else{
                       $table->cantidad = $_POST["cantidad_materia"]["$intIndice"];    
                    }
                    $table->save(false);
                     $this->ActualizarLineaMateria($id);
                    $intIndice++;
                endforeach;
                $this->TotalMateriaPrima($id);
                return $this->redirect(['view','id' =>$id, 'token' => $token]);
            }
        }    
        if(isset($_POST["actualizaregistro"])){
            if(isset($_POST["listado_producto"])){
                $intIndice = 0;
                foreach ($_POST["listado_producto"] as $intCodigo):
                    $table = OrdenProduccionProductos::findOne($intCodigo);
                    $table->descripcion = $_POST["descripcion"]["$intIndice"];
                    $table->cantidad = $_POST["cantidad_producto"]["$intIndice"];
                    $table->id_medida_producto = $_POST["tipo_medida"]["$intIndice"];
                    $table->porcentaje_iva = $_POST["porcentaje_iva"]["$intIndice"];
                    $table->save(false);
                    $intIndice++;
                endforeach;
                $this->TotalUnidadesLote($id);
                return $this->redirect(['view','id' =>$id, 'token' => $token]);
            }
        }    
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_orden' => $detalle_orden,
            'detalle_materia' => $detalle_materia,
        ]);
    }
    //PROCESO QUE SUBE EL TOTAL DE LA MATERIA PRIMA
    
     protected function TotalMateriaPrima($id) {
        $orden = OrdenProduccion::findOne($id); 
        $detalle = OrdenProduccionMateriaPrima::find()->where(['=','id_orden_produccion', $id])->all();
        $iva= 0; $subtotal = 0; $total =0;
        foreach ($detalle as $detalles):
            $iva += $detalles->valor_iva;
            $subtotal += $detalles->subtotal;
            $total += $detalles->total; 
        endforeach;
        $orden->subtotal = $subtotal;
        $orden->iva = $iva;
        $orden->total_orden = $total;
        $orden->save();
    }
    
    //PROCESO QUE TOTALIZA CADA LINEA DEL DETALLE
    protected function ActualizarLineaMateria($id) {
        $detalle = OrdenProduccionMateriaPrima::find()->where(['=','id_orden_produccion', $id])->all();
        foreach ($detalle as $detalles):
            $iva = 0;
            $iva = round(($detalles->cantidad * $detalles->valor_unitario)* $detalles->porcentaje_iva)/100;
            $detalles->valor_iva = $iva;
            $detalles->subtotal = round($detalles->cantidad * $detalles->valor_unitario);
            $detalles->total =  $detalles->subtotal + $iva;
            $detalles->save();
        endforeach;
    }
    
    //contador de unidades productos
    protected function TotalUnidadesLote($id) {
        $orden = OrdenProduccion::findOne($id);
        $detalle = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id])->all();
        $contador = 0;
        foreach ($detalle as $detalles):
            $contador += $detalles->cantidad;
        endforeach;
        $orden->unidades = $contador;
        $orden->save();
    }

    /**
     * Creates a new OrdenProduccion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrdenProduccion();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $token = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name = Yii::$app->user->identity->username;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrdenProduccion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        $detalle = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id])->all();
        if ($model->autorizado == 0){  
            $sw = 0;
            foreach ($detalle as $detalles):
                if($detalles->codigo_producto == NULL){
                    $sw = 1;
                }
            endforeach;
            if($sw == 1){
                $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
                Yii::$app->getSession()->setFlash('warning', 'Para autorizar la orden de producción debe de crear los codigos a cada producto.'); 
            }else{
                if(count($detalle) > 0){
                    $model->autorizado = 1;            
                    $model->update();
                    $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);  
                }else{
                    $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
                    Yii::$app->getSession()->setFlash('warning', 'No se puede autorizar la orden de produccion porque no tiene productos asociados.'); 
                }    
            }
            
        } else{
                $model->autorizado = 0;
                $model->update();
                $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);  
        }    
    }
    
    //generar orden produccion
    public function actionGenerarorden($id, $token) {
        //proceso de generar consecutivo
        $lista = \app\models\Consecutivos::findOne(3);
        $solicitud = OrdenProduccion::findOne($id);
        $solicitud->numero_orden = $lista->numero_inicial + 1;
        $solicitud->save(false);
        $lista->numero_inicial = $solicitud->numero_orden;
        $lista->save(false);
        $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);  
    }
    
    //crear codigo productos
    public function actionCrearcodigoproducto($id, $token) {
        //proceso de generar consecutivo
        $lista = \app\models\Consecutivos::findOne(4);
        $listado = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id])->all(); 
        foreach ($listado as $listados):
            if($listados->codigo_producto == NULL){
                $listados->codigo_producto = $lista->numero_inicial + 1;
                $listados->save();
                $lista->numero_inicial = $listados->codigo_producto;
                $lista->save(false);
            }
        endforeach;
        $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);  
    }
    //eliminar detalle de materia prima
     public function actionEliminarmateria($id,$detalle, $token)
    {                                
        $detalle = OrdenProduccionMateriaPrima::findOne($detalle);
        $detalle->delete();
        //$this->TotalUnidadesLote($id);
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
        
    //eliminar detalles de creacion de producto
    
     public function actionEliminar($id,$detalle, $token)
    {                                
        $detalle = \app\models\OrdenProduccionProductos::findOne($detalle);
        $detalle->delete();
        $this->TotalUnidadesLote($id);
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
    //ELIMINAR DETALLE DEL PRECIO DE VENTA
     public function actionEliminar_detalle($id,$detalle)
    {                                
        $dato = \app\models\InventarioPrecioVenta::findOne($detalle);
        $dato->delete();
        $this->redirect(["crearprecioventaproducto",'id' => $id]);        
    }
    
    //crear producto nuevo
    public function actionCrearproducto($id, $grupo, $token) {
        
        $model = new \app\models\PresentacionProducto();
        $orden = OrdenProduccion::findOne($id);
        $presentacion = \app\models\PresentacionProducto::find()->where(['=','id_grupo', $grupo])->all() ;
        if (Yii::$app->request->post()) {
            if (isset($_POST["listadopresentacion"])) {
                $intIndice = 0;
                if (isset($_POST["listado"])) {
                    foreach ($_POST["listado"] as $intCodigo):
                       $detalle = \app\models\PresentacionProducto::find()->where(['=','id_presentacion', $intCodigo])->one();
                       $table = new OrdenProduccionProductos();
                       $table->id_orden_produccion = $id;
                       $table->descripcion = $detalle->descripcion;
                       $table->id_medida_producto = $orden->grupo->id_medida_producto;
                       $table->user_name = Yii::$app->user->identity->username;
                       $table->save(false);
                       $intIndice++;
                    endforeach;
                    return $this->redirect(['view','id' => $id, 'token' => $token]);
                }
            }
         }
         return $this->renderAjax('crearproductos', [
            'model' => $model,       
            'id' => $id,
            'token' => $token,
             'presentacion' => $presentacion,
        ]);      
    }
    // CODIGO QUE GENERA EL LOTE
    public function actionGenerarlote($id, $token, $fecha_actual) {
        $numero = 0;
        $mes = substr($fecha_actual, 5, 2);
        $anio = substr($fecha_actual, 2, 2);
        $orden = OrdenProduccion::findOne($id);
        $detalle = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id])->all();
        if ($orden->numero_lote == 0){
            $valor = 0;
            $valor = round($orden->subtotal / $orden->unidades);
            $numero = $mes.$anio.$orden->numero_orden;
            $orden->numero_lote = $numero;
            $orden->cerrar_orden = 1;
            $orden->costo_unitario = $valor;
            $orden->save();
            foreach ($detalle as $detalles):
                $detalles->cerrar_linea  = 1;
                $detalles->numero_lote = $numero;
                $detalles->costo_unitario = $valor;
                $detalles->save();
            endforeach;
            return $this->redirect(['view','id' => $id, 'token' => $token]);
        }else{
            $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
            Yii::$app->getSession()->setFlash('warning', 'El numero de lote para esta orden de produccion ya esta creado.');  
        }
    }
    //BUSCA PRODUCTO DEL INVENTARIO
    public function actionBuscarproducto($id, $token, $grupo){
        $operacion = InventarioProductos::find()->where(['=','id_grupo', $grupo])->orderBy('nombre_producto ASC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        $mensaje = '';
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                    $operacion = InventarioProductos::find()
                            ->where(['like','nombre_producto',$q])
                            ->orwhere(['=','codigo_producto',$q])
                            ->andWhere(['=','id_grupo', $grupo]);
                    $operacion = $operacion->orderBy('nombre_producto ASC');                    
                    $count = clone $operacion;
                    $to = $count->count();
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count()
                    ]);
                    $operacion = $operacion
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            $table = InventarioProductos::find()->where(['=','id_grupo', $grupo])->orderBy('nombre_producto ASC');
            $tableexcel = $table->all();
            $count = clone $table;
            $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
            ]);
             $operacion = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        //PROCESO DE GUARDAR
         if (isset($_POST["guardarproductos"])) {
            if(isset($_POST["nuevo_productos"])){
                $intIndice = 0;
                foreach ($_POST["nuevo_productos"] as $intCodigo) {
                    $item = InventarioProductos::findOne($intCodigo);
                    $table = new OrdenProduccionProductos();
                    $table->id_orden_produccion = $id;
                    $table->codigo_producto = $item->codigo_producto;
                    $table->descripcion = $item->nombre_producto;   
                    $table->costo_unitario = $item->costo_unitario; 
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->id_medida_producto = $item->grupo->id_medida_producto;
                    $table->aplica_iva = $item->aplica_iva;
                    $table->porcentaje_iva = $item->porcentaje_iva;
                    $table->id_inventario = $intCodigo;
                    $table->save(false);
                }
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('importarproductos', [
            'operacion' => $operacion,            
            'mensaje' => $mensaje,
            'pagination' => $pages,
            'id' => $id,
            'form' => $form,
            'token' => $token,
            'grupo' => $grupo,

        ]);
    }
    
    //BUSCAR MATERIA PRIMA PARA EL PRODUCTO
     public function actionBuscarmateriaprima($id, $token){
        $operacion = MateriaPrimas::find()->where(['>','stock', 0])->orderBy('materia_prima ASC')->all();
        $form = new FormModeloBuscar();
        $q = null;
        $mensaje = '';
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                    $operacion = MateriaPrimas::find()
                            ->where(['like','materia_prima',$q])
                            ->orwhere(['=','codigo_materia_prima',$q])
                            ->andWhere(['>','stock', 0]);
                    $operacion = $operacion->orderBy('materia_prima ASC');                    
                    $count = clone $operacion;
                    $to = $count->count();
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count()
                    ]);
                    $operacion = $operacion
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            $table = MateriaPrimas::find()->where(['>','stock', 0])->orderBy('materia_prima ASC');
            $tableexcel = $table->all();
            $count = clone $table;
            $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
            ]);
             $operacion = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        //PROCESO DE GUARDAR
         if (isset($_POST["guardarmateriaprima"])) {
            if(isset($_POST["nuevo_materia_prima"])){
                foreach ($_POST["nuevo_materia_prima"] as $intCodigo) {
                    //consulta para no duplicar
                    $registro = OrdenProduccionMateriaPrima::find()->where(['=','id_orden_produccion', $id])
                                                                   ->andWhere(['=','id_materia_prima', $intCodigo])->one();
                    if(!$registro){
                        $materia = MateriaPrimas::findOne($intCodigo);
                        $table = new OrdenProduccionMateriaPrima();
                        $table->id_orden_produccion = $id;
                        $table->id_materia_prima = $intCodigo;
                        $table->valor_unitario = $materia->valor_unidad;
                        $table->porcentaje_iva = $materia->porcentaje_iva;
                        $table->save(false);
                    }    
                }
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('importarmateriaprima', [
            'operacion' => $operacion,            
            'mensaje' => $mensaje,
            'pagination' => $pages,
            'id' => $id,
            'form' => $form,
            'token' => $token,

        ]);
    }
    
    //modificar cantidades produccion
    public function actionModificarcantidades($id, $token, $detalle) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = OrdenProduccionProductos::findOne($detalle);
       
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["actualizarcantidades"])) { 
                $table->cantidad = $model->cantidades;
                $table->fecha_vencimiento = $model->fecha;
                $table->save(false);
                $this->TotalUnidadesLote($id);
                $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
            }    
        }
         if (Yii::$app->request->get()) {
            $model->cantidades = $table->cantidad;
            $model->fecha = $table->fecha_vencimiento;
         }
        return $this->renderAjax('cambiarcantidades', [
            'model' => $model,
            'token' => $token,
            'detalle' => $detalle,
            'id' => $id,
        ]);
    }
    
    //PERMITE CREAR EL PRECIO UNICO PARA VENTA AL DEPTA Y DISTRIBUIDOR
    public function actionCrear_precio_unico($id) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = InventarioProductos::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["crear_precio_unico"])) {
                if($model->tipo_precio == 2){//mayorista
                    $table->precio_mayorista = $model->nuevo_precio;
                }else{
                    $table->precio_deptal = $model->nuevo_precio;
                }    
            $table->save(false);
            $this->redirect(["orden-produccion/crear_precio_venta", 'id' => $id]);
            }
        }
        return $this->renderAjax('_crear_precio_punto_distribuidor', [
            'model' => $model,
            'id' => $id,
        ]);
    }
    
    //CREAR LA REGLA PARA DISTRIBIDOR
    public function actionCrear_regla_punto($id, $sw = 0) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $inventario = InventarioProductos::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["regla_distribuidor"])) {
                    $table = new \app\models\InventarioReglaDescuento();
                    $table->id_inventario = $id;
                    $table->fecha_inicio =  $model->fecha_inicio;
                    $table->fecha_final = $model->fecha_final;
                    $table->nuevo_valor = $model->nuevo_valor;
                    $table->tipo_descuento = $model->tipo_descuento;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $inventario->aplica_descuento = 1;
                    $inventario->save();
                    $this->redirect(["orden-produccion/view_regla_descuento", 'id' => $id]);
                }
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('_form_editar_descuento', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    // PERMITE MODIFICAR LA REGLA COMERCIAL O DESCUENTOS DEL PUNTO DE VENTA
    public function actionEditar_regla_punto($id , $sw = 1) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $table = InventarioProductos::findOne($id);
        $regla = \app\models\InventarioReglaDescuento::find()->where(['=','id_inventario', $id])->one();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["regla_distribuidor"])) {
                   $regla->fecha_inicio =  $model->fecha_inicio;
                   $regla->fecha_final = $model->fecha_final;
                   $regla->nuevo_valor = $model->nuevo_valor;
                   $regla->tipo_descuento = $model->tipo_descuento;
                   $regla->estado_regla = $model->estado;
                   $regla->save();
                   if($model->estado == 1){
                        $table->aplica_descuento = 0;
                        $table->save();
                    }else{
                        $table->aplica_descuento = 1;
                        $table->save();
                    }     
                   $this->redirect(["orden-produccion/view_regla_descuento", 'id' => $id]);
                }
            }else{
                $model->getErrors();
            }    
        }
        if (Yii::$app->request->get()) {
            $model->fecha_inicio = $regla->fecha_inicio;
            $model->fecha_final = $regla->fecha_final;
            $model->nuevo_valor = $regla->nuevo_valor;
            $model->tipo_descuento = $regla->tipo_descuento;
            $model->estado = $regla->estado_regla;
        }
        return $this->renderAjax('_form_editar_descuento', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //CREAR LA REGLA PARA DISTRIBIDOR
    public function actionCrear_regla_distribuidor($id, $sw = 0) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $inventario = InventarioProductos::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["regla_distribuidor"])) {
                    $table = new \app\models\ReglaDescuentoDistribuidor();
                    $table->id_inventario = $id;
                    $table->fecha_inicio =  $model->fecha_inicio;
                    $table->fecha_final = $model->fecha_final;
                    $table->nuevo_valor = $model->nuevo_valor;
                    $table->tipo_descuento = $model->tipo_descuento;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $inventario->aplica_descuento_distribuidor = 1;
                    $inventario->save();
                    $this->redirect(["orden-produccion/view_regla_descuento", 'id' => $id]);
                }
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('_form_editar_descuento', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //EDITAR LA REGLA COMERCIAL DE DESCUENTO PARA DISTRIBUIDORES
    public function actionEditar_regla_distribuidor($id, $sw = 1) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $table = InventarioProductos::findOne($id);
        $regla = \app\models\ReglaDescuentoDistribuidor::find()->where(['=','id_inventario', $id])->one();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["regla_distribuidor"])) {
                   $regla->fecha_inicio =  $model->fecha_inicio;
                   $regla->fecha_final = $model->fecha_final;
                   $regla->nuevo_valor = $model->nuevo_valor;
                   $regla->tipo_descuento = $model->tipo_descuento;
                   $regla->estado_regla = $model->estado;
                   $regla->save(false);
                   if($model->estado == 1){
                        $table->aplica_descuento_distribuidor = 0;
                        $table->save();
                    }else{
                        $table->aplica_descuento_distribuidor = 1;
                        $table->save();
                    }     
                   $this->redirect(["orden-produccion/view_regla_descuento",'id' => $id]);
                }
            }else{
                $model->getErrors();
            }    
        }
        if (Yii::$app->request->get()) {
            $model->fecha_inicio = $regla->fecha_inicio;
            $model->fecha_final = $regla->fecha_final;
            $model->nuevo_valor = $regla->nuevo_valor;
            $model->tipo_descuento = $regla->tipo_descuento;
            $model->estado = $regla->estado_regla;
        }
        return $this->renderAjax('_form_editar_descuento', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //PERMITE EXPORTAR LA ORDEN DE PRODUCCION A INVENTARIOS
    public function actionExportarinventarios($id, $token, $grupo) {
        $orden = OrdenProduccion::findOne($id);
        $detalle = OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id])->andWhere(['=','importado', 0])->all();
        if($orden->tipo_orden == 0){ //reprogramacion de productos
            $auxiliar = 0;
            if(count($detalle) > 0){
                foreach ($detalle as $detalles):
                    $auxiliar = $detalles->id_inventario;
                    $inventario = InventarioProductos::find()->where(['=','id_inventario', $detalles->id_inventario])->one();
                    if($inventario){
                        $inventario->unidades_entradas += $detalles->cantidad;
                        $inventario->costo_unitario =  $detalles->costo_unitario;
                        $inventario->stock_unidades +=  $detalles->cantidad;
                        $inventario->fecha_proceso = $orden->fecha_proceso;
                        $inventario->fecha_vencimiento = $detalles->fecha_vencimiento;
                        $inventario->id_detalle = $detalles->id_detalle;
                        $inventario->save();
                        $detalles->importado = 1;
                        $detalles->save();
                        $orden->exportar_inventario = 1;
                        $orden->save();
                        $this->ActualizarSaldoTotales($auxiliar);
                    }
                endforeach;
                 $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
            }else{
                $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
                Yii::$app->getSession()->setFlash('warning', 'Los productos que se crearon en esta orden de produccion ya fueron importados al modulo de inventario.'); 
            }    
        }else{
            if(count($detalle) > 0){
                $proveedor = \app\models\Proveedor::find()->where(['=','predeterminado', 1])->one();
                foreach ($detalle as $detalles):
                    $auxiliar = 0;
                    $table = new InventarioProductos();
                    $table->codigo_producto = $detalles->codigo_producto;
                    $table->nombre_producto = $detalles->descripcion;
                    $table->descripcion_producto = $detalles->descripcion;
                    $table->costo_unitario = $detalles->costo_unitario;
                    $table->unidades_entradas = $detalles->cantidad;
                    $table->stock_unidades = $detalles->cantidad;
                    $table->id_grupo = $grupo;
                    $table->id_detalle = $detalles->id_detalle;
                    $table->aplica_iva = $detalles->aplica_iva;
                    $table->porcentaje_iva = $detalles->porcentaje_iva;
                    $table->fecha_vencimiento = $detalles->fecha_vencimiento;
                    $table->fecha_proceso = $orden->fecha_proceso;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->codigo_ean = $detalles->codigo_producto;
                    $table->id_proveedor = $proveedor->id_proveedor;
                    $table->save(false);
                    $indice = InventarioProductos::find()->orderBy('id_inventario DESC')->one();
                    $detalles->importado = 1;
                    $detalles->id_inventario = $indice->id_inventario;
                    $detalles->save();
                    $orden->exportar_inventario = 1;
                    $orden->save();
                    $registro = InventarioProductos::find()->orderBy('id_inventario DESC')->one();
                    $auxiliar = $registro->id_inventario;
                    $this->ActualizarSaldoTotales($auxiliar);
               endforeach;
                $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
            }else{
               $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
                Yii::$app->getSession()->setFlash('warning', 'Los productos que se crearon en esta orden de produccion ya fueron importados al modulo de inventario.');  
            }    
        }
    }
    //PROCESO QUE DESCARGA LA MATERIA PRIMA
    public function actionDescargarmateriaprima($id, $token) {
        $orden = OrdenProduccion::findOne($id);
        $detalle = OrdenProduccionMateriaPrima::find()->where(['=','id_orden_produccion', $id])->andWhere(['=','importado', 0])->all();
        if(count($detalle) > 0){
            foreach ($detalle as $detalles):
                $materia = MateriaPrimas::findOne($detalles->id_materia_prima);
                if ($materia){
                    if($materia->aplica_inventario == 1){
                        $materia->stock_salida += $detalles->cantidad;
                        $materia->stock -= $detalles->cantidad;
                        $materia->save(false);
                        $detalles->importado = 1;
                        $detalles->save();
                        $this->ActualizarCostoMateriaPrima($materia);
                    }
                }
                $orden->exportar_materia_prima = 1;
                $orden->save(false);
            endforeach;
            $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
        }else{
            $orden->exportar_materia_prima = 1;
            $orden->save(false);
            $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
            Yii::$app->getSession()->setFlash('warning', 'Las materias primas que se utilizaron en esta orden de produccion ya se importaron.'); 
        }    
        
    }
    //ACTUALIZA EL COSTO DEL INVENTARIO DE MATERIAS PRIMAS
    
    protected function ActualizarCostoMateriaPrima($materia) {
        $iva = 0; $subtotal = 0;
        $iva = round((($materia->total_cantidad * $materia->stock)* $materia->porcentaje_iva)/100);
        $subtotal = round($materia->stock * $materia->valor_unidad);
        $materia->valor_iva = $iva;
        $materia->subtotal = $subtotal;
        $materia->total_materia_prima = $subtotal + $iva;
        $materia->save(false);
    }
        
    //PROCESO QUE TOTALIZA EL INVENTARIO
    protected function ActualizarSaldoTotales($auxiliar) {
        $iva = 0;
        $inventario = InventarioProductos::findOne($auxiliar);
        $inventario->subtotal = round($inventario->stock_unidades * $inventario->costo_unitario);
        $iva = round(($inventario->stock_unidades * $inventario->costo_unitario)* $inventario->porcentaje_iva/100);
        $inventario->valor_iva = $iva;
        $inventario->total_inventario = $inventario->subtotal + $iva;
        $inventario->save();
    }
    /**
     * Finds the OrdenProduccion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrdenProduccion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrdenProduccion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //REPORTES
    public function actionImprimirordenproduccion($id) {
        $model = OrdenProduccion::findOne($id);
        return $this->render('../formatos/reporteordenproduccion', [
            'model' => $model,
        ]);
    }
    
    //EXCELES
    
     public function actionExcelconsultaOrdenProduccion($tableexcel) {                
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
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'ALMACEN')
                    ->setCellValue('D1', 'GRUPO')
                    ->setCellValue('E1', 'TIPO ORDEN')
                    ->setCellValue('F1', 'NUMERO LOTE')
                    ->setCellValue('G1', 'FECHA PROCESO')
                    ->setCellValue('H1', 'FECHA ENTREGA')
                    ->setCellValue('I1', 'FECHA REGISTRO')
                    ->setCellValue('J1', 'CANTIDAD')
                    ->setCellValue('K1', 'C. UNITARIO')
                    ->setCellValue('L1', 'SUBTOTAL')
                    ->setCellValue('M1', 'IMPUESTO')
                    ->setCellValue('N1', 'TOTAL ORDEN')
                    ->setCellValue('O1', 'USER NAME')
                    ->setCellValue('P1', 'AUTORIZADO')
                    ->setCellValue('Q1', 'ORDEN CERRADA')
                    ->setCellValue('R1', 'RESPONSABLE')
                    ->setCellValue('S1', 'OBSERVACION');
            $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_orden_produccion)
                    ->setCellValue('B' . $i, $val->numero_orden)
                    ->setCellValue('C' . $i, $val->almacen->almacen)
                    ->setCellValue('D' . $i, $val->grupo->nombre_grupo);
                    if($val->tipo_orden == 0){
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('E' . $i, 'REPROGRAMACION');        
                    }else{
                       $objPHPExcel->setActiveSheetIndex(0)
                       ->setCellValue('E' . $i, 'PRODUCTO NUEVO');        
                    }     
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F' . $i, $val->numero_lote)
                    ->setCellValue('G' . $i, $val->fecha_proceso)
                    ->setCellValue('H' . $i, $val->fecha_entrega)
                    ->setCellValue('I' . $i, $val->fecha_registro)
                    ->setCellValue('J' . $i, $val->unidades)
                    ->setCellValue('K' . $i, $val->costo_unitario)
                    ->setCellValue('L' . $i, $val->subtotal)
                    ->setCellValue('M' . $i, $val->iva)
                    ->setCellValue('N' . $i, $val->total_orden)
                    ->setCellValue('O' . $i, $val->user_name)
                    ->setCellValue('P' . $i, $val->autorizadoOrden)
                    ->setCellValue('Q' . $i, $val->cerrarOrden)
                    ->setCellValue('R' . $i, $val->responsable)
                    ->setCellValue('S' . $i, $val->observacion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Ordenes_prodccion');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Orden_produccion.xlsx"');
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
