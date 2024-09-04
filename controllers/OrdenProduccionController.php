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
use app\models\ModelCrearPrecios;
use app\models\OrdenProduccionFaseInicial;

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
                $grupo = null; $tipo_proceso = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $lote = Html::encode($form->lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $almacen = Html::encode($form->almacen);
                        $grupo = Html::encode($form->grupo);
                        $tipo_proceso = Html::encode($form->tipo_proceso);
                        $autorizado = Html::encode($form->autorizado);
                        $table = OrdenProduccion::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_almacen', $almacen])
                                    ->andFilterWhere(['=', 'autorizado', $autorizado])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'id_proceso_produccion', $tipo_proceso])
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
    
    //INDEX QUE MUESTRAS TODAS LA AUDITORIAS REALIZADAS
    public function actionIndex_resultado_auditoria() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',92])->all()){
                $form = new \app\models\FiltroBusquedaAuditorias();
                $numero_auditoria = null;
                $numero_orden = null; $numero_lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $etapa = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_auditoria = Html::encode($form->numero_auditoria);
                        $numero_orden = Html::encode($form->numero_orden);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $etapa = Html::encode($form->etapa);
                         $numero_lote = Html::encode($form->numero_lote);
                        $table = \app\models\OrdenProduccionAuditoriaFabricacion::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero_orden])
                                    ->andFilterWhere(['between', 'fecha_creacion', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'numero_auditoria', $numero_auditoria])
                                    ->andFilterWhere(['=', 'numero_lote', $numero_lote])
                                    ->andFilterWhere(['=', 'id_etapa', $etapa]);
                        $table = $table->orderBy('id_auditoria DESC');
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
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_auditoria  DESC']);
                            $this->actionExcelConsultaAuditorias($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = \app\models\OrdenProduccionAuditoriaFabricacion::find()
                            ->orderBy('id_auditoria DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaAuditoria($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index_auditorias', [
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
    
    //PROCESO QUE SE ENCARGA DE APROBAR LAS ORDENES DE PRODUCCION
    public function actionIndex_ordenes_produccion() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',91])->all()){
                $form = new FiltroBusquedaOrdenProduccion();
                $numero = null;
                $lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $almacen = null;
                $autorizado = null;
                $grupo = null; $tipo_proceso = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $lote = Html::encode($form->lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $almacen = Html::encode($form->almacen);
                        $grupo = Html::encode($form->grupo);
                        $tipo_proceso = Html::encode($form->tipo_proceso);
                        $autorizado = Html::encode($form->autorizado);
                        $table = OrdenProduccion::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_almacen', $almacen])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'id_proceso_produccion', $tipo_proceso])
                                    ->andFilterWhere(['=', 'id_grupo', $grupo])
                                    ->andWhere(['=', 'cerrar_orden', 1])
                                    ->andWhere(['=', 'orden_cerrada_ensamble', 0])
                                    ->andWhere(['=', 'producto_aprobado', 0]);
                        $table = $table->orderBy('id_orden_produccion DESC');
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
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = OrdenProduccion::find()->Where(['=', 'cerrar_orden', 1])
                                                    ->andWhere(['=', 'producto_aprobado', 0])
                                                    ->andWhere(['=', 'orden_cerrada_ensamble', 0])
                                                    ->orderBy('id_orden_produccion DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    
                }
                $to = $count->count();
                return $this->render('index_aprobacion_ordenes', [
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
                $grupo = null; $tipo_proceso = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $lote = Html::encode($form->lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $almacen = Html::encode($form->almacen);
                        $grupo = Html::encode($form->grupo);
                        $tipo_proceso = Html::encode($form->tipo_proceso);
                        $autorizado = Html::encode($form->autorizado);
                        $table = OrdenProduccion::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_almacen', $almacen])
                                    ->andFilterWhere(['=', 'autorizado', $autorizado])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'id_grupo', $grupo])
                                    ->andFilterWhere(['=', 'id_proceso_produccion', $tipo_proceso])
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
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $grupo = Html::encode($form->grupo);
                        $producto = Html::encode($form->producto);
                        $table = InventarioProductos::find()
                                        ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                        ->andFilterWhere(['=', 'id_grupo', $grupo])
                                        ->andFilterWhere(['like', 'nombre_producto', $producto])
                                        ->andWhere(['>','stock_unidades', 0]); 
                        $table = $table->orderBy('id_inventario DESC');
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
                }else{
                    $table = InventarioProductos::find()
                            ->Where(['>','stock_unidades', 0])
                            ->orderBy('id_inventario DESC');
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
                return $this->render('crearpreciosventa', [
                            'model' => $model,
                            'form' => $form,
                            'sw' => $sw,
                            'pagination' => $pages,
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
        $fase_inicial = OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion', $id])->orderBy('id_fase ASC')->all();
        if (Yii::$app->request->post()) {
            if (isset($_POST["eliminartodo"])) {
                if (isset($_POST["listado_eliminar"])) {
                    foreach ($_POST["listado_eliminar"] as $intCodigo) {
                        try {
                            $eliminar = OrdenProduccionFaseInicial::findOne($intCodigo);
                            $eliminar->delete();
                            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                            $this->redirect(["orden-produccion/view", 'id' => $id, 'token' => $token]);
                        } catch (IntegrityException $e) {

                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');
                        } catch (\Exception $e) {
                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');

                        }
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Debe seleccionar al menos un registro.');
                }    
             }
        }    
        //ACTUALIZA LAS FASES DEL PRODUCTO
        if(isset($_POST["actualizaregistro"])){
            if(isset($_POST["listado_fase"])){
                $intIndice = 0;
                foreach ($_POST["listado_fase"] as $intCodigo):
                    $table = OrdenProduccionFaseInicial::findOne($intCodigo);
                    $table->porcentaje_aplicacion = $_POST["porcentaje_aplicacion"][$intIndice];
                    $table->cantidad_gramos = $_POST["cantidad_gramos"][$intIndice];
                    $table->save(false);
                    $intIndice++;
                endforeach;
                $this->ActualizarExistenciaMateriaPrima($id);
                return $this->redirect(['view','id' =>$id, 'token' => $token]);
            }
        }
        //ACTUALIZA LAS PRODUCTOS DEL DETALLE DE LA OP.
        if (Yii::$app->request->post()) {
            if(isset($_POST["actualizar_detalle_producto"])){
                if(isset($_POST["listado_producto"])){
                    $intIndice = 0;
                    foreach ($_POST["listado_producto"] as $intCodigo):
                        $table = OrdenProduccionProductos::findOne($intCodigo);
                        $table->cantidad = $_POST["cantidad_producto"][$intIndice];
                        $table->cantidad_real = $_POST["cantidad_producto"][$intIndice];
                        $table->save(false);
                        $intIndice++;
                    endforeach;
                    $this->TotalUnidadesLote($id);
                    return $this->redirect(['view','id' =>$id, 'token' => $token]);
                }
            }  
        }    
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_orden' => $detalle_orden,
            'fase_inicial' => $fase_inicial,
        ]);
    }
       
    //VISTA QUE MUESTRA LOS RESULTADOS DE LA AUDITORIA
    
    public function actionView_auditoria_orden_produccion($id_auditoria){
        $model = \app\models\OrdenProduccionAuditoriaFabricacion::findOne($id_auditoria);
        $conConcepto = \app\models\OrdenProduccionAuditoriaFabricacionDetalle::find()->where(['=','id_auditoria', $id_auditoria])->all();
        //ACTUALIZA LOS REGISTGROS DEL DETALLA DE LA AUDITORIA
        if (Yii::$app->request->post()) {
            if(isset($_POST["actualizar_listado_analisis"])){
                if(isset($_POST["listado_analisis"])){
                    $intIndice = 0;
                    foreach ($_POST["listado_analisis"] as $intCodigo):
                        $table = \app\models\OrdenProduccionAuditoriaFabricacionDetalle::findOne($intCodigo);
                        $table->resultado = $_POST["resultado"][$intIndice];
                        $table->save(false);
                        $intIndice++;
                    endforeach;
                    return $this->redirect(['view_auditoria_orden_produccion','id_auditoria' =>$id_auditoria]);
                }
            }    
        }    
        if (Yii::$app->request->post()) {
            if (isset($_POST["eliminar_todo_auditoria"])) {
                if (isset($_POST["listado_eliminar"])) {
                    foreach ($_POST["listado_eliminar"] as $intCodigo) {
                        try {
                            $eliminar = \app\models\OrdenProduccionAuditoriaFabricacionDetalle::findOne($intCodigo);
                            $eliminar->delete();
                            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                            $this->redirect(["orden-produccion/view_auditoria_orden_produccion", 'id_auditoria' => $id_auditoria]);
                        } catch (IntegrityException $e) {

                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');
                        } catch (\Exception $e) {
                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');

                        }
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Debe seleccionar al menos un registro.');
                }    
             }
        }         
        return $this->render('view_auditoria_orden_produccion', [
            'conConcepto' => $conConcepto,
            'id_auditoria' => $id_auditoria,
            'model' => $model,
        ]);
    }
    
     //VALIDAR EXISTENCIAS AL ACTUALIZAR EL ARCHIVO DE CONFIGURACION DEL PRODUCTO
     protected function ActualizarExistenciaMateriaPrima($id) {
         $fases = OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion', $id])->orderBy('id_fase ASC')->all();
         foreach ($fases as $fase):
            $materia = MateriaPrimas::findOne($fase->id_materia_prima);
            if($materia){           
                if($fase->cantidad_gramos <= $materia->stock_gramos){
                    $fase->cumple_existencia = 'OK';
                    $fase->save();
                }else{
                    $fase->cumple_existencia = 'FALTA';
                    $total =  $fase->cantidad_gramos - $materia->stock_gramos ;
                    $fase->cantidad_faltante = $total;
                    $fase->save();
                }
             }
         endforeach;
     }
    
    //IMPORTAR FASE INICIAL
    public function actionImportar_fase_inicial($id_grupo, $id, $token) {
        $conFaseinicial = \app\models\ConfiguracionProducto::find()->where(['=','id_grupo', $id_grupo])->all();
        $model = OrdenProduccion::findOne($id);
        if(count($conFaseinicial) > 0){
            foreach ($conFaseinicial as $primerafase):
                $conFase = OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion', $id])->andWhere(['=','id', $primerafase->id])->one();
                if(!$conFase){
                    $table = new OrdenProduccionFaseInicial ();
                    $table->id_orden_produccion = $id;
                    $table->id = $primerafase->id;
                    $table->id_materia_prima = $primerafase->id_materia_prima;
                    $table->id_grupo = $id_grupo;
                    $table->id_fase = $primerafase->id_fase;
                    $table->porcentaje_aplicacion = $primerafase->porcentaje_aplicacion;
                    $totales = ($model->tamano_lote * $primerafase->porcentaje_aplicacion)/100;
                    $table->cantidad_gramos= $totales;
                    $table->codigo_homologacion = $primerafase->codigo_homologacion;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save ();
                }    
            endforeach;
            return $this->redirect(['orden-produccion/view','id' => $id, 'token' => $token]);
        }else{
            Yii::$app->getSession()->setFlash('error', 'Este GRUPO NO tiene configurado la materia prima, la face inicial y la fase final. Validar la informacion.'); 
            return $this->redirect(['orden-produccion/view','id' => $id, 'token' => $token]);
        }    
    }
    
    //CARGAR AUDITORIA A UNA ORDEN DE PRODUCCION YA LISTA EN EL PROCESO DE GRANEL O FABRICACION
    public function actionCargar_concepto_auditoria($id, $id_grupo) {
        if(\app\models\OrdenProduccionAuditoriaFabricacion::find()->where(['=','id_orden_produccion', $id])->one()){ 
            Yii::$app->getSession()->setFlash('warning', 'Esta orden de produccion cuenta con un proceso de auditoria que no se ha cerrado. Validar la información'); 
            return $this->redirect(['orden-produccion/index_ordenes_produccion']);
        }else{      
            $orden = OrdenProduccion::findOne($id);
            $etapa = \app\models\EtapasAuditoria::findOne(1);
            $table = new \app\models\OrdenProduccionAuditoriaFabricacion();
            $table->id_orden_produccion= $id;
            $table->numero_orden = $orden->numero_orden;
            $table->numero_lote = $orden->numero_lote;
            $table->id_grupo = $id_grupo;
            $table->id_etapa = 1;
            $table->etapa =$etapa->concepto;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save();
            $model = \app\models\OrdenProduccionAuditoriaFabricacion::find()->orderBy('id_auditoria DESC')->limit(1)->one();
            //proceso del detalle de la auditoria
            $configuracion = \app\models\ConfiguracionProductoProceso::find()->where(['=','id_etapa', 1])->andWhere(['=','id_grupo', $id_grupo])->all();
            foreach ($configuracion as $resultado):
                $grabar = new \app\models\OrdenProduccionAuditoriaFabricacionDetalle ();
                $grabar->id_auditoria = $model->id_auditoria;
                $grabar->id_analisis = $resultado->id_analisis;
                $grabar->id_especificacion = $resultado->id_especificacion;
                $grabar->resultado = $resultado->resultado;
                $grabar->save();
            endforeach;
             return $this->redirect(['orden-produccion/view_auditoria_orden_produccion','id_auditoria' => $model->id_auditoria]);
        }    
       
    } 
    
    //CARGAR ITEMS DE AUDITORIA AL DETALLE SE ESTAN ELIMINADOS
    public function actionCargar_items_auditoria($id_grupo, $id_etapa, $id_auditoria){
        $configuracion = \app\models\ConfiguracionProductoProceso::find()->where(['=','id_grupo', $id_grupo])->andWhere(['=','id_etapa', $id_etapa])->all();
        foreach ($configuracion as $resultado):
            $table = new \app\models\OrdenProduccionAuditoriaFabricacionDetalle();
            $table->id_auditoria = $id_auditoria;
            $table->id_analisis = $resultado->id_analisis;
            $table->id_especificacion = $resultado->id_especificacion;
            $table->resultado = $resultado->resultado;
            $table->save ();
        endforeach;
        return $this->redirect(['orden-produccion/view_auditoria_orden_produccion','id_auditoria' => $id_auditoria]);
    }
    
    //PROCESO QUE REGENERA LA FORMULA DE PRODUCCION DEL PRODUCTO
    public function actionRegenerar_formula($id, $token, $id_grupo){
        $orden = OrdenProduccion::findOne($id);
        $formulaFase = OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion', $id])->andWhere(['=','id_grupo', $id_grupo])->all();
        $valor = 0;
        foreach ($formulaFase as $fase):
            $valor = round(($orden->tamano_lote * $fase->porcentaje_aplicacion)/100);
            $fase->cantidad_gramos = $valor;
            $fase->save ();
        endforeach;
         return $this->redirect(['orden-produccion/view','id' => $id, 'token' => $token]);
    }
    
    //PROCESO QUE SUBE EL TOTAL DE LA MATERIA PRIMA
    
     protected function TotalMateriaPrima($id) {
        $orden = OrdenProduccion::findOne($id); 
        $detalle = OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion', $id])->all();
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
        $detalle = OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion', $id])->all();
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
        $model->fecha_proceso = date('Y-m-d');
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
            'sw' => 0,
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
        $ConProducto = \app\models\Productos::find()->Where(['=','id_grupo', $model->id_grupo])->orderBy('nombre_producto ASC')->all(); 
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'sw' => 1,
            'ConProducto' => ArrayHelper::map($ConProducto, "id_producto", "nombre_producto"),
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
                if($detalles->cantidad == 0){
                    $sw = 2;
                }
            endforeach;
            if($sw == 1){
                 Yii::$app->getSession()->setFlash('warning', 'Para autorizar la orden de producción debe de crear los codigos a cada producto.'); 
                 $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
            }else{
                if($sw == 2){
                    Yii::$app->getSession()->setFlash('warning', 'Debe de ingresar las unidades proyectadas.'); 
                    $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
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
            }
            
        } else{
                $model->autorizado = 0;
                $model->update();
                $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);  
        }    
    }
    
    //generar orden produccion
    public function actionGenerarorden($id, $token, $id_grupo) {
        
        //proceso que busca si esta ok la fase inicia
        $fase = OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion', $id])->andWhere(['=','id_grupo', $id_grupo])->all();
        $sw = 0;
        $producto = OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id])->all();
        if(count($fase) > 0){
            foreach ($producto as $detalle):
                if($detalle->fecha_vencimiento == null){
                   $sw = 1;    
                }
            endforeach;
            if($sw == 1){
                Yii::$app->getSession()->setFlash('error', 'Debe de ingresar la fecha de vencimiento del lote creado. Favor hacerlo por la opcion de modificar.'); 
                $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);  
            }else{
                foreach ($fase as $fases):
                    if($fases->cantidad_faltante <> 0){
                       $sw = 2;    
                    }
                endforeach;     
                if ($sw == 2){
                   Yii::$app->getSession()->setFlash('warning', 'No se puede generar la ORDEN DE PRODUCCION porque en la fase inicial o final no hay suficiente materia prima para cumplir con el proceso.'); 
                   $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);   
                }else{
                    //proceso de generar consecutivo
                    $solicitud = OrdenProduccion::findOne($id);
                    if($solicitud->id_proceso_produccion == 1){
                         $lista = \app\models\Consecutivos::findOne(21);
                    }else{
                        if($solicitud->id_proceso_produccion == 2 ){
                             $lista = \app\models\Consecutivos::findOne(3);
                        }else{
                             $lista = \app\models\Consecutivos::findOne(22);
                        }
                    }
                   
                    $solicitud->numero_orden = $lista->numero_inicial + 1;
                    $solicitud->save(false);
                    $lista->numero_inicial = $solicitud->numero_orden;
                    $lista->save(false);
                    $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]); 
                }
            }
        }else{
            Yii::$app->getSession()->setFlash('warning', 'No se puede generar la ORDEN DE PRODUCCION porque no se descargado la materia prima para la fabricacion. Validar la informacion.'); 
            $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);   
        }   
     
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
    
     //eliminar detalle de las fases
     public function actionEliminarmateria($id, $detalle, $token)
    {                                
        $detalle = OrdenProduccionFaseInicial::findOne($detalle);
        $detalle->delete();
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
    
    //ELIMINAR DETALLE DE LA AUDITORIA DE ORDENES DE PRODUCCION
     public function actionEliminar_detalle_auditoria($id_auditoria, $detalle)
    {                                
        $dato = \app\models\OrdenProduccionAuditoriaFabricacionDetalle::findOne($detalle);
        $dato->delete();
        $this->redirect(["view_auditoria_orden_produccion",'id_auditoria' => $id_auditoria]);        
    }
    
    //BUSCA TODOS LAS PRESENTACIONES DEL PRODUCTO PERO QUE NO ESTEN CREADOS EN EL INVENTARIO
    public function actionCrearproducto($id, $grupo, $token) {
        
        $model = new \app\models\PresentacionProducto();
        $orden = OrdenProduccion::findOne($id);
        $presentacion = \app\models\PresentacionProducto::find()->where(['=','id_grupo', $grupo])->all() ;
        if (Yii::$app->request->post()) {
            if (isset($_POST["listadopresentacion"])) {
                $intIndice = 0;
                if (isset($_POST["listado"])) {
                    foreach ($_POST["listado"] as $intCodigo):
                       $conIva = \app\models\ConfiguracionIva::findOne(1);
                       $detalle = \app\models\PresentacionProducto::find()->where(['=','id_presentacion', $intCodigo])->one();
                       $table = new OrdenProduccionProductos();
                       $table->id_orden_produccion = $id;
                       $table->id_presentacion = $detalle->id_presentacion;
                       $table->descripcion = $detalle->descripcion;
                       $table->id_medida_producto = $detalle->id_medida_producto;
                       $table->porcentaje_iva = $conIva->valor_iva;
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
    public function actionBuscar_materia_prima($id, $token, $id_grupo, $id_solicitud){
        $operacion = MateriaPrimas::find()->where(['=','id_solicitud', $id_solicitud])->andWhere(['>','stock_gramos', 0])->orderBy('materia_prima DESC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                    $operacion = MateriaPrimas::find()
                            ->where(['like','materia_prima',$q])
                            ->orwhere(['=','codigo_materia_prima',$q])
                            ->andWhere(['=','id_solicitud', $id_solicitud]);
                    $operacion = $operacion->orderBy('materia_prima DESC');                    
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
            $table = MateriaPrimas::find()->where(['=','id_solicitud', $id_solicitud])->andWhere(['>','stock_gramos', 0])->orderBy('materia_prima DESC');
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
                $intIndice = 0;
                foreach ($_POST["nuevo_materia_prima"] as $intCodigo) {
                    $registro = OrdenProduccionFaseInicial::find()->where(['=','id_materia_prima', $intCodigo])->andWhere(['=','id_orden_produccion', $id])->one();
                    if(!$registro){
                        $item = MateriaPrimas::findOne($intCodigo);
                        $table = new OrdenProduccionFaseInicial();
                        $table->id_orden_produccion = $id;
                        $table->id_materia_prima = $intCodigo;
                        $table->id_grupo = $id_grupo;
                        $table->id_fase = $id_solicitud;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->fecha_registro = date('Y-m-d');
                        $table->save(false);
                    }    
                }
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('importarproductos', [
            'operacion' => $operacion,            
            'pagination' => $pages,
            'id' => $id,
            'form' => $form,
            'token' => $token,
            'id_grupo' => $id_grupo,
            'id_solicitud' => $id_solicitud

        ]);
    }
    
    //PERMITE TERMINAR DE VALIDAD LA AUDITORIA Y SUBE LA CONTINUIDAD DEL PROCESO
    
    public function actionAprobar_orden_produccion($id_auditoria) {
        $model = new \app\models\FormModeloSubirAuditoria();
        $auditoria = \app\models\OrdenProduccionAuditoriaFabricacion::findOne($id_auditoria);
       
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["subir_informacion"])) { 
                $auditoria->continua = $model->continua;
                $auditoria->condicion_analisis = $model->condiciones;
                $auditoria->observacion = $model->observacion;
                $auditoria->save(false);
                $this->redirect(["orden-produccion/view_auditoria_orden_produccion", 'id_auditoria' => $id_auditoria]);
            }    
        }
         if (Yii::$app->request->get()) {
            $model->continua = $auditoria->continua; 
            $model->condiciones = $auditoria->condicion_analisis;
            $model->observacion = $auditoria->observacion;
         }
        return $this->renderAjax('subir_conceptos_auditoria', [
            'model' => $model,
            'id_auditoria' => $id_auditoria,
        ]);
    }
    
    //EDITAR LINEA DE MATERIA PRIMA MANUAL
     //modificar cantidades produccion
    public function actionEditar_linea_materia($id, $token, $id_detalle) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = OrdenProduccionFaseInicial::findOne($id_detalle);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["cambiar_fase"])) { 
                $valor = 0;
                $orden = OrdenProduccion::findOne($id);
                $table->id_fase = $model->tipo_precio;
                $table->porcentaje_aplicacion = $model->porcentaje_aplicacion;
                $valor = round(($orden->tamano_lote * $model->porcentaje_aplicacion)/100);
                $table->cantidad_gramos = $valor;
                $table->save(false);
                $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
            }    
        }
        if (Yii::$app->request->get()) {
            $model->tipo_precio = $table->id_fase; 
             $model->porcentaje_aplicacion = $table->porcentaje_aplicacion;
         }
        return $this->renderAjax('cambiar_fase_materia', [
            'model' => $model,
            'id_detalle' => $id_detalle,
            'id' => $id,
        ]);
    }
    
    //modificar cantidades produccion
    public function actionModificarcantidades($id, $token, $detalle) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = OrdenProduccionProductos::findOne($detalle);
        $orden = OrdenProduccion::findOne($id);
       
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["actualizarcantidades"])) { 
                $table->cantidad = $model->cantidades;
                $table->cantidad_real = $model->cantidad_real;
                $table->fecha_vencimiento = $model->fecha;
                $table->save(false);
                $orden->unidades = $model->cantidad_real;
                $orden->tamano_lote = $model->tamano_lote;
                $orden->save();
            //    $this->TotalUnidadesLote($id);
                $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
            }    
        }
         if (Yii::$app->request->get()) {
            $model->tamano_lote = $orden->tamano_lote; 
            $model->cantidades = $table->cantidad;
            $model->cantidad_real = $table->cantidad_real;
            $model->fecha = $table->fecha_vencimiento;
         }
        return $this->renderAjax('cambiarcantidades', [
            'model' => $model,
            'token' => $token,
            'detalle' => $detalle,
            'id' => $id,
        ]);
    }
    
     //CAMBIA EL ESTADO DE SEGUIR EL PROCESO DE ORDEN DE EMSAMBLE CUANDO NO PASA LA AUDITORIA
    public function actionModificar_estado_orden($id) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $orden = OrdenProduccion::findOne($id);
       
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["actualizar_cambio"])) { 
                $orden->seguir_proceso_ensamble = $model->estado;
                $orden->fecha_cambio = $model->fecha;
                $orden->save(false);
                $this->redirect(["orden-produccion/index"]);
            }    
        }
         if (Yii::$app->request->get()) {
            $model->estado = $orden->seguir_proceso_ensamble; 
            $model->fecha = $orden->fecha_cambio;
        }
        return $this->renderAjax('cambiar_proceso_auditoria', [
            'model' => $model,
            'id' => $id,
        ]);
    }
    
    //CONSULTAR INVENTARIO DE MATERIA PRIMA EN LA ORDEN DE PRODUCCION
    public function actionSearch_inventario($id, $token, $detalle) {
        //$model = new \app\models\FormModeloSearchMateria();
        return $this->renderAjax('search_inventario_materia_prima', [
            'detalle' => $detalle,
            'token' => $token,
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
    
    //CREAR LA REGLA PARA DISTRIBUIDOR
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
    
 
    //PROCESO QUE DESCARGA LA MATERIA PRIMA DE LA ORDEN DE PRODUCCION
    public function actionExportar_materia_prima($id, $token) {
        $orden = OrdenProduccion::findOne($id);
        $detalle = OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion', $id])->andWhere(['=','importado', 0])->all();
        if(count($detalle) > 0){
            $valor = 0; $variable = 0;
            foreach ($detalle as $detalles):
                $materia = MateriaPrimas::findOne($detalles->id_materia_prima);
                if ($materia){
                    if($materia->aplica_inventario == 1){
                        if($materia->convertir_gramos == 1){
                            $valor = 1;
                            $materia->stock_gramos = $materia->stock_gramos - $detalles->cantidad_gramos;
                            $variable = round($materia->stock_gramos /1000);
                            $materia->stock = ''.number_format($variable, 2);
                            $materia->save(false);
                            $detalles->importado = 1;
                            $detalles->save();
                            $this->ActualizarCostoMateriaPrima($materia, $valor);
                        }    
                    }
                }
                $orden->exportar_materia_prima = 1;
                $orden->save(false);
            endforeach;
            $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
        }else{
            $this->redirect(["orden-produccion/view", 'id' => $id, 'token' =>$token]);
            Yii::$app->getSession()->setFlash('info', 'No hay registros para exportar.'); 
        }    
        
    }
    //ACTUALIZA EL COSTO DEL INVENTARIO DE MATERIAS PRIMAS
    
    protected function ActualizarCostoMateriaPrima($materia, $valor) {
        $iva = 0; $subtotal = 0;
        if($valor == 1){
            $subtotal = round($materia->stock * $materia->valor_unidad);
            if($materia->aplica_iva == 1){
                $iva = round(($subtotal * $materia->porcentaje_iva)/100);
            }else{
               $iva = 0;                
            }    
            $materia->valor_iva = $iva;
            $materia->subtotal = $subtotal;
            $materia->total_materia_prima = $subtotal + $iva;
            $materia->save(false);
        }    
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
    
    //CERRAR AUDITORIA
    public function actionCerrar_auditoria($id_auditoria, $orden) {
        //proceso que genera consecutivo
        $ordenProduccion = OrdenProduccion::findOne($orden);
        $lista = \app\models\Consecutivos::findOne(11);
        $auditoria = \app\models\OrdenProduccionAuditoriaFabricacion::findOne($id_auditoria);
        $detalle_auditoria = \app\models\OrdenProduccionAuditoriaFabricacionDetalle::find()->where(['=','id_auditoria', $id_auditoria])->all();
        $sw = 0;
        if($auditoria->continua == 0 && $auditoria->condicion_analisis == 0){
            Yii::$app->getSession()->setFlash('warning', 'Favor ingresar las observaciones y los conceptos del proceso de auditoria.'); 
            $this->redirect(["orden-produccion/view_auditoria_orden_produccion", 'id_auditoria' => $id_auditoria]); 
        }else{
            foreach ($detalle_auditoria as $detalles):
                if($detalles->resultado == ''){
                    $sw = 1;
                }
            endforeach;
            if($sw == 1){
                Yii::$app->getSession()->setFlash('warning', 'El campo RESULTADO  NO puede ser vacio. Valide la informacion.'); 
                $this->redirect(["orden-produccion/view_auditoria_orden_produccion", 'id_auditoria' => $id_auditoria]); 
            }else{    
                $auditoria->numero_auditoria = $lista->numero_inicial + 1;
                $auditoria->cerrar_auditoria = 1;
                $auditoria->fecha_cierre = date('Y-m-d');
                $auditoria->save(false);
                $lista->numero_inicial = $auditoria->numero_auditoria;
                $lista->save(false);
                $ordenProduccion->proceso_auditado = 1;
                $ordenProduccion->save();
                if($auditoria->continua == 1){
                    $ordenProduccion->seguir_proceso_ensamble = 1;
                    $ordenProduccion->save();
                }
                $this->redirect(["orden-produccion/view_auditoria_orden_produccion", 'id_auditoria' => $id_auditoria]);  
            }    
        }    
    
    }
    
    //PROCESO QUE GENERA LA ORDEN DE ENSAMBLE 
    public function actionGenerar_orden_ensamble($id, $id_grupo) {
        $orden_produccion = OrdenProduccion::findOne($id);
        $detalle = \app\models\OrdenEnsambleProducto::find()->where(['=','id_orden_produccion', $id])->one();
        $sw = 0;
        $detalle_orden = OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id])->all();
        $detalle_orden_ensamble = \app\models\OrdenEnsambleProducto::find()->where(['=','id_orden_produccion', $id])->all();
        if(count($detalle_orden_ensamble ) < count($detalle_orden) ){
            if($detalle){
                $sw = 1;
            }
            //proceso de insertar
            $table = new \app\models\OrdenEnsambleProducto();
            $table->id_orden_produccion = $id;
            $table->id_grupo = $id_grupo;
            $table->numero_lote = $orden_produccion->numero_lote;
            $table->id_etapa = 2;
            $table->fecha_proceso = date('Y-m-d');
            $table->user_name = Yii::$app->user->identity->username;
            $table->save();     
            $ensamble = \app\models\OrdenEnsambleProducto::find()->orderBy('id_ensamble DESC')->limit(1)->one();
            //proceso del detalle de la orden de ensamble
            $detalle_orden = OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id])->andWhere(['=','orden_ensamble_creado', 0])->all();
            foreach ($detalle_orden as $detalle):
                $resultado = new \app\models\OrdenEnsambleProductoDetalle ();
                $resultado->id_ensamble = $ensamble->id_ensamble;
                $resultado->id_detalle = $detalle->id_detalle;
                $resultado->codigo_producto = $detalle->codigo_producto;
                $resultado->nombre_producto = $detalle->descripcion;
                $resultado->cantidad_proyectada = $detalle->cantidad;
                $resultado->cantidad_real = $detalle->cantidad_real;
               $resultado->save(false);
            endforeach;
            $id = $ensamble->id_ensamble;
            $token = 0;
            return $this->redirect(['/orden-ensamble-producto/view','id' => $id, 'token' => $token, 'sw' => $sw]);
        }else{
            Yii::$app->getSession()->setFlash('warning', 'Esta orden de produccion NO tiene mas registros de producto para crearles ORDENES DE ENSAMBLE.'); 
            $this->redirect(["orden-produccion/index_ordenes_produccion"]); 
        }    
    }
    
    //BUSCA PRODUCTO DEL INVENTARIO PARA REPROGRAMARLO
    public function actionBuscar_producto_inventario($id, $token, $grupo){
        $operacion = InventarioProductos::find()->where(['=','id_grupo', $grupo])->orderBy('nombre_producto DESC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                    $operacion = InventarioProductos::find()
                            ->where(['like','nombre_producto',$q])
                            ->orwhere(['=','codigo_producto',$q])
                            ->andWhere(['=','id_grupo', $grupo]);
                    $operacion = $operacion->orderBy('nombre_producto DESC');                    
                    $count = clone $operacion;
                    $to = $count->count();
                    $pages = new Pagination([
                        'pageSize' => 10,
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
            $table = InventarioProductos::find()->where(['=','id_grupo', $grupo])->orderBy('nombre_producto DESC');
            $tableexcel = $table->all();
            $count = clone $table;
            $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
            ]);
             $operacion = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        //PROCESO DE GUARDAR
         if (isset($_POST["guardarproducto"])) {
            if(isset($_POST["nuevo_producto"])){
                $intIndice = 0;
                foreach ($_POST["nuevo_producto"] as $intCodigo) {
                    $registro = OrdenProduccionProductos::find()->where(['=','id_inventario', $intCodigo])->andWhere(['=','id_orden_produccion', $id])->one();
                    if(!$registro){
                        $item = InventarioProductos::findOne($intCodigo);
                        $table = new OrdenProduccionProductos();
                        $table->id_orden_produccion = $id;
                        $table->id_presentacion = $item->id_presentacion;
                        $table->id_inventario = $intCodigo;
                        $table->codigo_producto = $item->codigo_producto;
                        $table->descripcion = $item->nombre_producto;
                        $table->id_medida_producto = $item->presentacion->id_medida_producto;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->save(false);
                    }    
                }
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('importar_producto_inventario', [
            'operacion' => $operacion,            
            'pagination' => $pages,
            'id' => $id,
            'form' => $form,
            'token' => $token,
            'grupo' => $grupo,
        ]);
    }
    
    ///SIMULADOR DE MATERIA PRIMA PARA LA ORDEN DE PRODUCCION
    public function actionSimulador_materia_prima($id, $token, $grupo) {
        $conFaseinicial = \app\models\ConfiguracionProducto::find()->where(['=','id_grupo', $grupo])->orderBy('id_fase ASC')->all();
        $orden = OrdenProduccion::findOne($id);
        return $this->render('simulador_inventario', [
            'id' => $id,
            'token' => $token,
            'grupo' => $grupo,
            'conFaseinicial' => $conFaseinicial,
            'orden' => $orden,
        ]);
    }
    //permite cerrar las ordens de produccion sin hacerle ordenes de ensamble
    public function actionCerrar_orden_produccion($id) {
        $orden = OrdenProduccion::findOne($id);
        $orden->orden_cerrada_ensamble = 1;
        $orden->save();
        return $this->redirect(['index_ordenes_produccion']);
    }
    
    //PROCESO QUE GENERA LA SOLICITUD DE MATERIAL DE EMPAQUE
    
    public function actionCrear_solicitud_empaque($id_orden, $token) {
        if(\app\models\SolicitudMateriales::find()->where(['=','id_orden_produccion', $id_orden])->one()){
             Yii::$app->getSession()->setFlash('warning', 'Esta orden de producción ya tiene generada la solicitud de MATERIAL DE EMPAQUE. Ver (Produccion->Movimiento->Solicitud materiales).'); 
            $this->redirect(["view", 'id' => $id_orden, 'token' => $token]); 
        }else{
            $orden = OrdenProduccion::findOne($id_orden);
            $tipo = \app\models\TipoSolicitud::findOne(2);
            $table = new \app\models\SolicitudMateriales();
            $table->id_orden_produccion = $id_orden;
            $table->id_solicitud = $tipo->id_solicitud;
            $table->id_grupo = $orden->id_grupo;
            $table->unidades = $orden->unidades;
            $table->numero_lote = $orden->numero_lote;
            $table->numero_orden_produccion = $orden->numero_orden;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save(false);
            $nuevo_registro = \app\models\SolicitudMateriales::find()->orderBy('codigo DESC')->one();
            $id = $nuevo_registro->codigo;
            return $this->redirect(['solicitud-materiales/view', 'id' => $id, 'token' => $token]);
        }    

    }
    
     
    //PROCESO QUE CARGA LOS PRODUCTOS DE CADA GRUPO
    public function actionCargarproducto($id){
        $rows = \app\models\Productos::find()->where(['=','id_grupo', $id])
                                              ->orderBy('nombre_producto desc')->all();

        echo "<option value='' required>Seleccione el producto...</option>";
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id_producto' required>$row->nombre_producto</option>";
            }
        }
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
    
    public function actionImprimir_informe_auditoria($id_auditoria) {
        $model = \app\models\OrdenProduccionAuditoriaFabricacion::findOne($id_auditoria);
        return $this->render('../formatos/reporte_auditoria_granel', [
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                               
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
                    ->setCellValue('S1', 'OBSERVACION')
                    ->setCellValue('T1', 'TIPO PROCESO');
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
                    ->setCellValue('S' . $i, $val->observacion)
                    ->setCellValue('T' . $i, $val->tipoProceso->nombre_proceso);
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
