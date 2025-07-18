<?php

namespace app\controllers;

//clases
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
use app\models\OrdenEnsambleProducto;
use app\models\OrdenEnsambleProductoSearch;
use app\models\OrdenEnsambleProductoEmpaque;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaOrdenEnsamble;
use app\models\OrdenEnsambleAuditoria;
use app\models\OrdenEnsambleAuditoriaDetalle;




/**
 * OrdenEnsambleProductoController implements the CRUD actions for OrdenEnsambleProducto model.
 */
class OrdenEnsambleProductoController extends Controller
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
     * Lists all OrdenEnsambleProducto models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',93])->all()){
                $form = new FiltroBusquedaOrdenEnsamble();
                $numero_ensamble = null;
                $numero_lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $orden = null;
                $producto = null; $tipo_proceso = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_ensamble = Html::encode($form->numero_ensamble);
                        $numero_lote = Html::encode($form->numero_lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $orden = Html::encode($form->orden);
                        $producto = Html::encode($form->producto);
                        $table = OrdenEnsambleProducto::find()
                                    ->andFilterWhere(['=', 'numero_orden_ensamble', $numero_ensamble])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_orden_produccion', $orden])
                                    ->andFilterWhere(['=', 'numero_lote', $numero_lote])
                                    ->andFilterWhere(['=', 'id_producto', $producto]);
                        $table = $table->orderBy('id_ensamble DESC');
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
                            $check = isset($_REQUEST['id_ensamble  DESC']);
                            $this->actionExcelConsultaOrdenEnsamble($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = OrdenEnsambleProducto::find()
                            ->orderBy('id_ensamble DESC');
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
                        $this->actionExcelConsultaOrdenEnsamble($tableexcel);
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
    
    //INDEX QUE MUESTRAS TODAS LA AUDITORIAS REALIZADAS A LAS OE
    public function actionIndex_auditoria_ensamble() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',96])->all()){
                $form = new \app\models\FiltroBusquedaAuditorias();
                $numero_auditoria = null;
                $numero_orden = null; $numero_lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $grupo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_auditoria = Html::encode($form->numero_auditoria);
                        $numero_orden = Html::encode($form->numero_orden);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $grupo = Html::encode($form->grupo);
                         $numero_lote = Html::encode($form->numero_lote);
                        $table = \app\models\OrdenEnsambleAuditoria::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero_orden])
                                    ->andFilterWhere(['between', 'fecha_analisis', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'numero_auditoria', $numero_auditoria])
                                    ->andFilterWhere(['=', 'numero_lote', $numero_lote])
                                    ->andFilterWhere(['=', 'id_grupo', $grupo]);
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
                            $this->actionExcelConsultaAuditoria($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = \app\models\OrdenEnsambleAuditoria::find()
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
                return $this->render('index_auditoria_orden', [
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
    
    //PROCESO QUE PERMITE DESCARGAR EL MATERIAL DE EMPAQUE
     public function actionIndex_descargar_inventario() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',112])->all()){
                $form = new FiltroBusquedaOrdenEnsamble();
                $numero_ensamble = null;
                $numero_lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $orden = null;
                $producto = null; $tipo_proceso = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_ensamble = Html::encode($form->numero_ensamble);
                        $numero_lote = Html::encode($form->numero_lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $orden = Html::encode($form->orden);
                        $producto = Html::encode($form->producto);
                        $table = \app\models\OrdenProduccion::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero_ensamble])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_orden_produccion', $orden])
                                    ->andFilterWhere(['=', 'id_producto', $producto])
                                    ->andFilterWhere(['=', 'numero_lote', $numero_lote])
                                    ->andWhere(['=', 'orden_cerrada_ensamble', 1])
                                    ->andWhere(['=', 'exportar_inventario', 0]);
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
                    $table = \app\models\OrdenProduccion::find()
                                         ->Where(['=', 'orden_cerrada_ensamble', 1])
                                         ->andWhere(['=', 'exportar_inventario', 0])
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
                $to = $count->count();
                return $this->render('index_descargar', [
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
     * Displays a single OrdenEnsambleProducto model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token, $sw)
    {
        $conPresentacion = \app\models\OrdenEnsambleProductoDetalle::find()->where(['=','id_ensamble', $id])->all();
        $orden_ensamble = OrdenEnsambleProducto::findOne($id);
        $conMateriales = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->orderBy('id DESC')->all();
        $conTerminadas = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->andWhere(['=','importado', 0])->orderBy('id DESC')->all();
        //actualizar listado de presentacion producto
        if (Yii::$app->request->post()) {
            if(isset($_POST["actualizar_listado_presentacion"])){
                if(isset($_POST["listado_presentacion"])){
                    $intIndice = 0;
                    foreach ($_POST["listado_presentacion"] as $intCodigo):
                        $table = \app\models\OrdenEnsambleProductoDetalle::findOne($intCodigo);
                        $table->cantidad_real = $_POST["cantidad_real"][$intIndice];
                        $table->save(false);
                        $intIndice++;
                    endforeach;
                    $this->TotalUnidadesLote($orden_ensamble);
                    return $this->redirect(['view','id' =>$id, 'token' => $token, 'sw' => $sw]);
                }
            }
        }  
        //actualizar unidades empacadas
         if (Yii::$app->request->post()) {
           if(isset($_POST["actualizar_material_empaque"])){
                if(isset($_POST["listado_empaque"])){
                    $intIndice = 0;
                    $total_envasada = 0;
                    $total_empacadas = 0;
                     foreach ($_POST["listado_empaque"] as $intCodigo):
                        $table = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id', $intCodigo])->andWhere(['=','importado', 0])->one();
                        if($table){                    
                            $table->unidades_devolucion = $_POST["unidades_devolucion"][$intIndice];
                            $table->unidades_averias = $_POST["unidades_averias"][$intIndice];
                            $total_envasada = $table->unidades_devolucion + $table->unidades_averias;
                            $table->unidades_utilizadas = $table->unidades_solicitadas - $total_envasada;
                            //retenidas
                            $table->unidades_sala_tecnica = $_POST["unidades_sala_tecnica"][$intIndice];
                            $table->unidades_muestra_retencion = $_POST["unidades_muestra_retencion"][$intIndice];
                            $total_empacadas = $table->unidades_sala_tecnica + $table->unidades_muestra_retencion;
                            $table->unidades_reales = $table->unidades_utilizadas - $total_empacadas;
                            $table->save();
                            $intIndice++;
                        }else{
                            $intIndice++;
                        }    
                    endforeach;
                   // $this->TotalUnidadesLote($orden_ensamble);
                   return $this->redirect(['view','id' =>$id, 'token' => $token, 'sw' => $sw]);
                }
            }
            
         }
        //ELIMINAR DETALLE DE MATERIAL DE EMPAQUE
        if (Yii::$app->request->post()) {
            if (isset($_POST["eliminar_todo_empaque"])) {
                if (isset($_POST["listado_unidades"])) {
                    foreach ($_POST["listado_unidades"] as $intCodigo) {
                         try {
                            $eliminar = \app\models\OrdenEnsambleProductoEmpaque::findOne($intCodigo);
                            $eliminar->delete();
                            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                            $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' => $token, 'sw' => $sw]);
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
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' =>$token,
            'conPresentacion' => $conPresentacion,
            'conMateriales' => $conMateriales,
            'sw' => $sw,
            'conTerminadas' => $conTerminadas,
           
        ]);
    }
    
    //VISTA QUE MUESTRA EL DETALLE DE PRODUCCION
    public function actionView_detalle_descarga($id_orden) {
        $modelo = \app\models\OrdenProduccion::findOne($id_orden);
        $detalle = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id_orden])->all(); 
        return $this->render('view_detalle_descarga', [
            'modelo' => $modelo,
            'detalle' => $detalle,
            ]);
    }
    
    
    //vista que muestra el materail de empaque
    public function actionView_lineas_empaque($id_ensamble) {
        $conMateriales = OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id_ensamble])->orderBy('linea_exportada_inventario ASC')->all();
        if (Yii::$app->request->post()) {
            if(isset($_POST["descargar_material_empaque"])){
                if(isset($_POST["listado_unidades"])){
                    $cantidad = 0; $intIndice = 0; $con = 0; $total = 0;
                    foreach ($_POST["listado_unidades"] as $intCodigo):
                        $item = OrdenEnsambleProductoEmpaque::findOne($intCodigo);
                        if($materia = \app\models\MateriaPrimas::findOne($item->id_materia_prima)){
                           $cantidad = $item->unidades_averias + $item->unidades_utilizadas;   
                           $total = $materia->stock - $cantidad;
                            if($total >= 0 ){
                                $materia->stock = $total;
                                $materia->save();
                                $item->linea_exportada_inventario = 1;
                                $item->save();
                                $this->ActualizarCostoMaterialEmpaque($materia);
                                $intIndice++;
                                $con++;
                           }else{
                              Yii::$app->getSession()->setFlash('error', 'El material de empaque (' . $materia->materia_prima .') tiene saldo negativo en el Inventario. Valide la informacion.');
                           }    
                        }else{
                            $intIndice++;
                        }
                    endforeach;
                    $conMateriales = OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id_ensamble])->orderBy('linea_exportada_inventario ASC')->all();
                    Yii::$app->getSession()->setFlash('success', 'Se exportaron (' . $con .') registros al modulo de inventario de material de empaque con exito.');
                    return $this->redirect(['view_lineas_empaque', 'id_ensamble' => $id_ensamble]);
                }
            }
        }    
      return $this->render('view_detalle_empaque', [
            'model' => $this->findModel($id_ensamble),
            'conMateriales' => $conMateriales,
        ]);   
    }
    
    //CERRAR LIENA DE EMPAQUE
    public function actionCerrar_linea_empacada($id, $token, $sw, $id_producto) {
        $empaque = OrdenEnsambleProductoEmpaque::findOne($id_producto);
        if($empaque){
            $empaque->importado = 1;
            $empaque->save();
            return $this->redirect(['view','id' =>$id, 'token' => $token, 'sw' => $sw]);
        }else{
            Yii::$app->getSession()->setFlash('error', 'Registro No encontrado en el sistema.');
            return $this->redirect(['view','id' =>$id, 'token' => $token, 'sw' => $sw]);
        }
        
    }
    
    //VISTA QUE MUESTRA EL PROCESO DE LA SEGUNDA AUDITORIA
    public function actionView_auditoria($id_auditoria) {
        $model = \app\models\OrdenEnsambleAuditoria::findOne($id_auditoria);
        $conDetalles = \app\models\OrdenEnsambleAuditoriaDetalle::find()->where(['=','id_auditoria', $id_auditoria])->all();
        //ACTUALIZA LOS REGISTGROS DEL DETALLA DE LA AUDITORIA
        if (Yii::$app->request->post()) {
            if(isset($_POST["actualizar_listado_analisis"])){
                if(isset($_POST["listado_analisis"])){
                    $intIndice = 0;
                    foreach ($_POST["listado_analisis"] as $intCodigo):
                        $table = \app\models\OrdenEnsambleAuditoriaDetalle::findOne($intCodigo);
                        $table->resultado = $_POST["resultado"][$intIndice];
                        $table->save(false);
                        $intIndice++;
                    endforeach;
                    return $this->redirect(['view_auditoria','id_auditoria' =>$id_auditoria]);
                }
            }    
        }   
        if (Yii::$app->request->post()) {
            if (isset($_POST["eliminar_todo_auditoria"])) {
                if (isset($_POST["listado_eliminar"])) {
                    foreach ($_POST["listado_eliminar"] as $intCodigo) {
                        try {
                            $eliminar = \app\models\OrdenEnsambleAuditoriaDetalle::findOne($intCodigo);
                            $eliminar->delete();
                            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                            $this->redirect(["orden-ensamble-producto/view_auditoria", 'id_auditoria' => $id_auditoria]);
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
        return $this->render('view_auditoria', [
            'model' => $model,
            'conDetalles' => $conDetalles,
        ]);
    }   
    
    //PROCESO QUE ACTUALIZA LAS UNIDAS PROYECTAS
    protected function TotalUnidadesLote($orden_ensamble) {
        $detalles = \app\models\OrdenEnsambleProductoDetalle::find()->where(['=','id_ensamble', $orden_ensamble->id_ensamble])->all();
        $contador = 0;
        foreach ($detalles as $detalle):
            $contador += $detalle->cantidad_real;
        endforeach;
        $orden_ensamble->total_unidades = $contador;
        $orden_ensamble->save();
    }
    
    //PROCESO QUE CARGA LAS PRESENTACIONES DEL PRODUCTO QUE ESTAN EN UNA ORDE DE PRODUCCION
    public function actionCargar_nuevamente_items($id, $token, $id_orden_produccion,$sw) {
        
        $detalle_orden = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id_orden_produccion])->andWhere(['=','orden_ensamble_creado', 0])->all();
        foreach ($detalle_orden as $detalle):
            $table = \app\models\OrdenEnsambleProductoDetalle::find()->where(['=','id_ensamble', $id])->andWhere(['=','id_detalle', $detalle->id_detalle])->one();
            if(!$table){
                $resultado = new \app\models\OrdenEnsambleProductoDetalle();
                $resultado->id_ensamble = $id;
                $resultado->id_detalle = $detalle->id_detalle;
                $resultado->codigo_producto = $detalle->codigo_producto;
                $resultado->nombre_producto = $detalle->descripcion;
                $resultado->cantidad_proyectada = $detalle->cantidad;
                $resultado->cantidad_real = $detalle->cantidad_real;
                $resultado->save(false);
            }    
        endforeach;
        return $this->redirect(['/orden-ensamble-producto/view','id' => $id, 'token' => $token, 'sw' => $sw]);
    }
    

    /**
     * Deletes an existing OrdenEnsambleProducto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   //ELIMINAR DETALLE DE LA ORDEN DE ENSAMBLE
     public function actionEliminar_detalle_ensamble($id, $id_detalle, $token, $sw)
    {                                
         
        try {
            $dato = \app\models\OrdenEnsambleProductoDetalle::findOne($id_detalle);
            $dato->delete();
            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
             return $this->redirect(['orden-ensamble-producto/view','id' => $id, 'token' => $token ,'sw' => $sw]); 
        } catch (IntegrityException $e) {
             Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
              return $this->redirect(['orden-ensamble-producto/view','id' => $id, 'token' => $token ,'sw' => $sw]); 
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
             return $this->redirect(['orden-ensamble-producto/view','id' => $id, 'token' => $token ,'sw' => $sw]); 
        }
    }

    //AUTORIZAR Y DESAUTORIZAR UNA ORDEN DE ENSAMBLE
      public function actionAutorizado($id, $token, $sw) {
        $model = $this->findModel($id);
        $presentacion = \app\models\OrdenEnsambleProductoDetalle::find()->where(['=','id_ensamble', $id])->andWhere(['<>','porcentaje_rendimiento', ''])->all();
        $empacadas = OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->one();
        if(!$empacadas){
           Yii::$app->getSession()->setFlash('error', 'No se puede autorizar el proceso porque NO hay material del empaque seleccionado.'); 
            $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]); 
        }else{
            if(count($presentacion) > 0){
                $total = OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->all();
                foreach ($total as $val) {
                    if($val->importado == 0){
                         Yii::$app->getSession()->setFlash('error', 'Debe de cerrar las lineas de empaque para autorizar el proceso.');
                         return $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]);    
                    }
                }
                if ($model->autorizado == 0){  
                    $model->autorizado = 1;            
                    $model->update();
                    return$this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]);  
                }else{
                    $model->autorizado = 0;            
                    $model->update();
                    return $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]);      
                }    
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Falta completar el porcentaje de rendimiendo que esta en la vista numero 1.'); 
                $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]); 
            }    
        }    
    }
    
    //GENERA LA ORDEN Y CIERRA EN PROCESO
    public function actionGenerar_orden_ensamble($id, $token, $sw) {
        $orden = OrdenEnsambleProducto::findOne($id);
        $detalle_empaque = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->all();
        $sw = 0; $suma1; $suma2 = 0; 
        if($orden->responsable == null && $orden->peso_neto == null && $orden->observacion == NULL){
            Yii::$app->getSession()->setFlash('info', 'Debe de subir la siguiente informacion: RESPOSAMBLE del proceso, el PESOS NETO y las OBSERVACIONES de la orden de ensamble.'); 
            $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]); 
        }else{
            if(count($detalle_empaque) >  0){
                foreach ($detalle_empaque as $empaque):
                    if($empaque->alerta == 'FALTA'){
                       $sw = 1;
                    }
                endforeach;
                if($sw == 1){
                    Yii::$app->getSession()->setFlash('warning', 'No se puede generar la orden de ensamble porque los materiales de empaque estan incompletos. Validar con el administrador.'); 
                    $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]); 

                 }else{
                    //generar consecutivo
                    $consecutivo = \app\models\Consecutivos::findOne(12);
                    $orden->numero_orden_ensamble = $consecutivo->numero_inicial + 1;
                    $orden->cerrar_orden_ensamble = 1;
                    $orden->save();
                    $consecutivo->numero_inicial = $orden->numero_orden_ensamble;
                    $consecutivo->save();
                    $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]); 
                 }    
            }else{
                Yii::$app->getSession()->setFlash('warning', 'No existe material de empaque seleccionado en esta ORDEN DE ENSAMBLE. Favor validar la informacion.'); 
                $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]);  
            }  
        }
        
    }
    
    //PROCESO QUE CIERRA EN SU TOTALIDAD LA ORDEN DE ENSAMBLE
    public function actionCerrar_orden_ensamble($id, $token, $sw) {
        $orden = OrdenEnsambleProducto::findOne($id);
        $sw = 0;
        $suma = 0;
        $detalle_ensamble = \app\models\OrdenEnsambleProductoDetalle::find()->where(['=','id_ensamble', $id])->andWhere(['<>','porcentaje_rendimiento', ''])->all();
        foreach ($detalle_ensamble as $ensamble):
            $presentacion = \app\models\OrdenProduccionProductos::findOne ($ensamble->id_detalle);
            if($presentacion){
                $presentacion->orden_ensamble_creado = 1;
                $presentacion->save();
            }
            $suma += $ensamble->cantidad_real;
            $orden->total_unidades = $suma;
            $orden->save();
        endforeach;
        
        $orden->cerrar_proceso = 1;
        $orden->save();
       return $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' => $sw]);
           
    }
    
    //BUSCAR MATERIA PRIMA PARA EL PRODUCTO
    public function actionBuscar_material_empaque($id, $token, $sw, $id_detalle){
             
        $orden = OrdenEnsambleProducto::findOne($id);
        $buscarP = \app\models\OrdenProduccionProductos::findOne($id_detalle);
        $registro = \app\models\EntregaMaterialesDetalle::find()->where(['=','id_detalle',$buscarP->id_detalle])
                                                                ->andWhere(['=','id_orden_produccion', $buscarP->id_orden_produccion])
                                                                ->orderBy('id_materia_prima DESC')->all();
        if(count($registro) > 0){
            foreach ($registro as $val) {
                 
                if(!\app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_materia_prima', $val->id_materia_prima])
                                                                  ->andWhere(['=','id_ensamble', $id])->andWhere(['=','id_presentacion', $buscarP->id_presentacion])->one()){
                    $ConMaterial = \app\models\EntregaMaterialesDetalle::find()->where(['=','id_materia_prima', $val->id_materia_prima])->andWhere(['=','id_detalle', $id_detalle])->all();
                    $contar = 0;
                    foreach ($ConMaterial as $valores){
                        $contar += $valores->unidades_despachadas;
                    }
                    $table = new \app\models\OrdenEnsambleProductoEmpaque();
                    $table->id_ensamble = $id;
                    $table->id_presentacion = $buscarP->id_presentacion;
                    $table->id_materia_prima = $val->id_materia_prima;
                    $table->unidades_solicitadas =  $contar;
                    $table->unidades_utilizadas =  $buscarP->cantidad;
                    $table->unidades_reales =  $contar;
                    $table->user_name =  Yii::$app->user->identity->username;
                    $materia = \app\models\MateriaPrimas::findOne($val->id_materia_prima);
                    if($orden->total_unidades <= $materia->stock){
                        $table->alerta = 'OK';
                    }else{
                      $table->alerta = 'FALTA';
                      $suma = 0;
                      $suma = $orden->total_unidades - $materia->stock;
                      $table->stock = $suma;
                    }
                     $table->save(false);
                }                           
            }//fin para
            return $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' => $token, 'sw' => $sw]);
        }else{
            Yii::$app->getSession()->setFlash('info', 'No existe material de empaque solicitado para esta presentacion de producto. Valide la informacion.!');
            return $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' => $token, 'sw' => $sw]);
        }
    }
    
    //SUBIR RESPONASBLE AL PROCESO
    //PERMITE TERMINAR DE VALIDAD LA AUDITORIA Y SUBE LA CONTINUIDAD DEL PROCESO
    
    public function actionSubir_responsable($id, $token, $sw) {
        $model = new \app\models\FormModeloSubirAuditoria();
        $ensamble = \app\models\OrdenEnsambleProducto::findOne($id);
       
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["subir_responsable"])) { 
                $ensamble->responsable = $model->responsable;
                $ensamble->peso_neto = $model->peso_neto;
                $ensamble->observacion = $model->observacion;
                $ensamble->fecha_hora_cierre = date('Y-m-d H:i:s' );
                $ensamble->save(false);
                $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' => $token, 'sw' => $sw]);
            }    
        }
         if (Yii::$app->request->get()) {
            $model->responsable= $ensamble->responsable; 
            $model->peso_neto = $ensamble->peso_neto;
            $model->observacion = $ensamble->observacion;
         }
        return $this->renderAjax('subir_responsable', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
  
    //modificar cantidades produccion
    public function actionModificar_cantidades($id, $token, $detalle,  $codigo, $sw) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = \app\models\OrdenEnsambleProductoDetalle::findOne($detalle);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["actualizar_unidades"])) { 
                $cantidad = 0;
                $variable = number_format((($model->cantidad_real / $table->cantidad_proyectada)*100),2);
                $cantidad = $model->cantidad_real;
                $table->cantidad_real = $model->cantidad_real;
                $table->porcentaje_rendimiento = $variable;
                $table->save();
                $orden_ensamble = OrdenEnsambleProducto::findOne($id); 
               // $this->TotalUnidadesLote($orden_ensamble);
                $this->CambiarCantidadOrdenProduccion($codigo, $cantidad);
                $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token, 'sw' =>$sw]);
            }    
        }
         if (Yii::$app->request->get()) {
            $model->cantidad_real = $table->cantidad_real;
        }
        return $this->renderAjax('subir_cantidades', [
            'model' => $model,
            'token' => $token,
            'detalle' => $detalle,
            'id' => $id,
            'codigo' => $codigo,
            'sw' =>$sw,
        ]);
    }
    
    //actualizar rendimiento y cantidades reales
    public function actionActualizar_unidades_rendimiento($id, $token, $id_presentacion, $sw, $codigo, $id_detalle) {
        //validar unidades utilizads
        
        $u_utilizadas = OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->andWhere(['=','id_presentacion', $id_presentacion])->all();
        $primer_valor = null; // Inicializamos una variable para guardar el primer valor
        foreach ($u_utilizadas as $utilizadas) {
            if ($primer_valor === null) {
                // Asignamos el primer valor encontrado
                $primer_valor = $utilizadas->unidades_utilizadas;
            } else {
                // Comparamos con el primer valor. Si son diferentes, mostramos el mensaje.
                if ($primer_valor !== $utilizadas->unidades_utilizadas) {
                    Yii::$app->getSession()->setFlash('error', 'No se puede actualizar el porcentaje de rendimiento del producto porque las unidades envasadas deben de ser iguales. Favor valide la información.');
                    return $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' => $token, 'sw' => $sw]);
                }
            }
        }
        //validamos que las aunidades reales esten iguales
        $primer_valor_unidades = null;
        foreach ($u_utilizadas as $utilizadas) {
            if ($primer_valor_unidades === null) {
                // Asignamos el primer valor encontrado
                $primer_valor_unidades = $utilizadas->unidades_reales;
            } else {
                // Comparamos con el primer valor. Si son diferentes, mostramos el mensaje.
                if ($primer_valor_unidades !== $utilizadas->unidades_reales) {
                    Yii::$app->getSession()->setFlash('warning', 'No se puede actualizar las unidades reales porque las cantidades deben de ser iguales en todas las lineas. Favor valide la información.');
                    return $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' => $token, 'sw' => $sw]);
                }else{
                    
                }
            }
        }
        //actualiza unidades reales y rendimiento
        $orden = \app\models\OrdenEnsambleProductoDetalle::findOne($codigo);
        $total = 0;
        $total = ''.number_format(($primer_valor / $orden->cantidad_proyectada) * 100, 2);
        $orden->cantidad_real = $primer_valor_unidades;
        $orden->porcentaje_rendimiento = $total;
        $orden->save();
        //actualiza unidaes totales reales
        $ordenTotal = \app\models\OrdenEnsambleProductoDetalle::find()->where(['=','id_ensamble', $id])->andWhere(['<>','porcentaje_rendimiento', 'null'])->all();
        $cantidad = 0;
        foreach ($ordenTotal as $val) {
            $cantidad += $val->cantidad_real;
            $val->cantidad_real = $cantidad;
            $val->save();
        }
        //actuliza la orden de produccion
        $orden_ensamble = OrdenEnsambleProducto::findOne($id);
        $orden_ensamble->total_unidades = $cantidad;
        $orden_ensamble->save(false);
        $this->CambiarCantidadOrdenProduccion($id_detalle, $cantidad);
       return $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' => $token, 'sw' => $sw]);
        

    }
    
    //actualiza la orden de produccion en su cantidad
    protected function CambiarCantidadOrdenProduccion($id_detalle, $cantidad) {
        $producto = \app\models\OrdenProduccionProductos::find()->where(['=','id_detalle', $id_detalle])->one();
        $producto->cantidad_real = $cantidad;
        $producto->save();
        $orden = \app\models\OrdenProduccion::findOne($producto->id_orden_produccion);
        $detalles = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $orden->id_orden_produccion])->all();
        $suma = 0;
        foreach ($detalles as $detalle):
            $suma += $detalle->cantidad_real;
        endforeach;       
        $orden->unidades = $suma;
        $orden->save();
    }
    //CARGA SEGUNDA AUDITORIA
    
    public function actionSegunda_auditoria($id, $id_grupo, $id_producto) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',95])->all()){
                $conSearch = OrdenEnsambleAuditoria::find()->where(['=','id_ensamble', $id])->one();
                if($conSearch){
                    Yii::$app->getSession()->setFlash('warning', 'Esta orden de ensamble se encuentra en un proceso de auditoria. Favor valide la información.');
                    $this->redirect(["orden-ensamble-producto/index"]);
                }else{        
                    //proceso que crea al encabezado de la auditoria
                    $orden_ensamble = OrdenEnsambleProducto::find()->where(['=','id_ensamble', $id])->one();
                    $table = new \app\models\OrdenEnsambleAuditoria();
                    $table->numero_orden = $orden_ensamble->numero_orden_ensamble;
                    $table->numero_lote = $orden_ensamble->numero_lote;
                    $table->id_ensamble = $id;
                    $table->id_etapa = 2;
                    $table->etapa = $orden_ensamble->etapa->concepto;
                    $table->id_grupo = $id_grupo;
                     $table->id_producto =  $id_producto;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save();
                    //PROCESO PARA INSERTAR LOS DATOS DE LA AUDITORIA
                    $auditoria = \app\models\OrdenEnsambleAuditoria::find()->orderBy('id_auditoria DESC')->limit(1)->one(); //para conseguir el ID
                    $concepto = \app\models\ConfiguracionProductoProceso::find()->where(['=','id_etapa', $orden_ensamble->id_etapa])->andWhere(['=','id_producto', $id_producto])->all();
                    foreach ($concepto as $detalle):
                        $registro = new \app\models\OrdenEnsambleAuditoriaDetalle();
                        $registro->id_auditoria = $auditoria->id_auditoria;
                        $registro->id_analisis = $detalle->id_analisis;
                        $registro->id_especificacion = $detalle->id_especificacion;
                        $registro->resultado = $detalle->resultado;
                        $registro->save(false);
                    endforeach;
                    $this->redirect(["orden-ensamble-producto/view_auditoria", 'id_auditoria' => $auditoria->id_auditoria]);
                }    
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //PERMITE TERMINAR DE VALIDAD LA AUDITORIA Y SUBE LA CONTINUIDAD DEL PROCESO
    public function actionAprobar_auditoria($id_auditoria) {
        $model = new \app\models\FormModeloSubirAuditoria();
        $auditoria = \app\models\OrdenEnsambleAuditoria::findOne($id_auditoria);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["subir_informacion"])) { 
                $auditoria->id_forma = $model->cosmetica;
                $auditoria->condiciones_analisis = $model->condiciones;
                $auditoria->observacion = $model->observacion;
                $auditoria->fecha_analisis = date('Y-m-d');
                $auditoria->save(false);
                $this->redirect(["orden-ensamble-producto/view_auditoria", 'id_auditoria' => $id_auditoria]);
            }    
        }
         if (Yii::$app->request->get()) {
            $model->cosmetica = $auditoria->id_forma; 
            $model->condiciones = $auditoria->condiciones_analisis;
            $model->observacion = $auditoria->observacion;
         }
        return $this->renderAjax('subir_conceptos_auditoria', [
            'model' => $model,
            'id_auditoria' => $id_auditoria,
        ]);
    }
    
    //CARGAR ITEMS DE AUDITORIA AL DETALLE 
    public function actionCargar_items_auditoria($id_grupo, $id_etapa, $id_auditoria){
        $configuracion = \app\models\ConfiguracionProductoProceso::find()->where(['=','id_grupo', $id_grupo])->andWhere(['=','id_etapa', $id_etapa])->all();
        foreach ($configuracion as $resultado):
            $table = new \app\models\OrdenEnsambleAuditoriaDetalle();
            $table->id_auditoria = $id_auditoria;
            $table->id_analisis = $resultado->id_analisis;
            $table->id_especificacion = $resultado->id_especificacion;
            $table->resultado = $resultado->resultado;
            $table->save(false);
        endforeach;
        return $this->redirect(['orden-ensamble-producto/view_auditoria','id_auditoria' => $id_auditoria]);
    }
    
    //CERRAR AUDITORIA
    public function actionCerrar_auditoria($id_auditoria, $orden_produccion, $orden_ensamble) {
        //proceso que genera consecutivo
        $orden_ensamble = OrdenEnsambleProducto::findOne($orden_ensamble);
        $ordenProduccion = \app\models\OrdenProduccion::findOne($orden_produccion);
        $lista = \app\models\Consecutivos::findOne(13);
        $auditoria = \app\models\OrdenEnsambleAuditoria::findOne($id_auditoria);
        $sw = 0;
        if($auditoria->id_forma == null && $auditoria->condiciones_analisis == 0){
             Yii::$app->getSession()->setFlash('warning', 'Debe de APROBAR, la FORMA COSMETICA, CONDICIONES DE ANALISIS  y una breve OBSERVACION.'); 
             $this->redirect(["orden-ensamble-producto/view_auditoria", 'id_auditoria' => $id_auditoria]); 
        }else{
            $detalle = OrdenEnsambleAuditoriaDetalle::find()->where(['=','id_auditoria', $id_auditoria])->all();
            foreach ($detalle as $detalles):
                if($detalles->resultado == ''){
                    $sw = 1;
                }
            endforeach;
            if($sw == 1){
                Yii::$app->getSession()->setFlash('info', 'En CAMPO RESULTADO no puede ser VACIO. Valide la informacion.'); 
                $this->redirect(["orden-ensamble-producto/view_auditoria", 'id_auditoria' => $id_auditoria]); 
            }else{
                $auditoria->numero_auditoria = $lista->numero_inicial + 1;
                $auditoria->cerrar_auditoria = 1;
                $auditoria->save(false);
                $lista->numero_inicial = $auditoria->numero_auditoria;
                $lista->save(false);
                $ordenProduccion->producto_aprobado = 1;
                $ordenProduccion->save();
                $orden_ensamble->proceso_auditado = 1;
                $orden_ensamble->save();
                $this->redirect(["orden-ensamble-producto/view_auditoria", 'id_auditoria' => $id_auditoria]); 
            }    
        }    
    }
   
    //PERMITE EXPORTAR TODOS LOS PRODUCTOS APROBADOS
        public function actionExportar_producto_inventario($id_orden) {
        $ordenP = \app\models\OrdenProduccion::findOne($id_orden);
        $detalle_producto = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id_orden])->all();
        if(count($detalle_producto) <= 0){
            Yii::$app->getSession()->setFlash('error', 'No hay productos para descargar al modulod de inventario. Favor valide la informacion.'); 
            return $this->redirect(["orden-ensamble-producto/index_descargar_inventario"]);
        }else {
            $contar = 0;
            foreach ($detalle_producto as $detalles):
                $inventario = \app\models\InventarioProductos::find()->where(['=','codigo_producto', $detalles->codigo_producto])->one();
                if($inventario){ //SI EL PRODUCTO YA EXISTE
                    $contar++;
                    $inventario->unidades_entradas += $detalles->cantidad_real;
                    $inventario->stock_unidades +=  $detalles->cantidad_real;
                    $inventario->fecha_proceso = $ordenP->fecha_proceso;
                    $inventario->fecha_vencimiento = $detalles->fecha_vencimiento;
                    $inventario->id_detalle = $detalles->id_detalle;
                    $inventario->inventario_inicial = 1;
                    $inventario->save(false);
                    //guarda en la table de productos
                    $detalles->importado = 1;
                    $detalles->id_inventario = $inventario->id_inventario;
                    $detalles->save();
                }else{ // SI EL PRODUCTO NO EXISTE
                    $contar++;
                    $proveedor = \app\models\Proveedor::find()->where(['=','predeterminado', 1])->one();
                    $table = new \app\models\InventarioProductos();
                    $table->codigo_producto = $detalles->codigo_producto;
                    $table->nombre_producto = $detalles->presentacionProducto->descripcion;
                    $table->descripcion_producto = $detalles->presentacionProducto->descripcion;
                    $table->unidades_entradas = $detalles->cantidad_real;
                    $table->stock_unidades = $detalles->cantidad_real;
                    $table->id_grupo = $ordenP->id_grupo;
                    $table->id_producto = $detalles->ordenProduccion->id_producto;
                    $table->id_detalle = $detalles->id_detalle;
                    $table->aplica_iva = $detalles->aplica_iva;
                    $table->porcentaje_iva = $detalles->porcentaje_iva;
                    $table->fecha_proceso = $ordenP->fecha_proceso;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->codigo_ean = $detalles->codigo_producto;
                    $table->inventario_inicial = 1;
                    if($proveedor){
                       $table->id_proveedor = $proveedor->id_proveedor; 
                    }
                    $table->id_presentacion = $detalles->id_presentacion;
                    $table->activar_producto_venta = 1;
                    $table->save(false);
                    $detalles->importado = 1;
                    $detalles->save();
                }  
            endforeach;
            $ordenP->exportar_inventario = 1;
            $ordenP->save(false);
            Yii::$app->getSession()->setFlash('success', 'Se importaron (' .$contar.') registros al modulo de producto terminado con EXITO.'); 
            return $this->redirect(["orden-ensamble-producto/index_descargar_inventario"]);
        }    
    }
    
    ///SIMULADOR DE MATERIA DE EMPAQUE PARA LA ORDEN DE ENSAMBLE
    public function actionSimulador_material_empaque($id, $token, $sw) {
        $empaque = \app\models\MateriaPrimas::find()->where(['=','id_solicitud', 2])->orderBy('materia_prima ASC')->all();
        $orden = OrdenEnsambleProducto::findOne($id);
        return $this->render('simulador_material_empaque', [
            'id' => $id,
            'token' => $token,
            'empaque' => $empaque,
            'orden' => $orden,
            'sw' => $sw,
        ]);
    }
    
     //PROCESO QUE DESCARGA EL MATERIAL DE EMPAQUE DE LA ORDEN DE ENSAMBLE
    public function actionExportar_material_empaque($id, $id_orden_produccion) {
        $orden = OrdenEnsambleProducto::findOne($id);
        $detalle = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->andWhere(['=','importado', 0])->all();
        if(count($detalle) > 0){
            $con = 0;
            foreach ($detalle as $detalles):
                $materia = \app\models\MateriaPrimas::findOne($detalles->id_materia_prima);
                if ($materia){
                    if($materia->aplica_inventario == 1){
                        $materia->stock -= $detalles->unidades_utilizadas;
                        $materia->save(false);
                        $detalles->importado = 1;
                        $detalles->save(false);
                        $this->ActualizarCostoMaterialEmpaque($materia);
                        $con += 1;
                    }
                }
                $orden->exportar_material_empaque = 1;
                $orden->save(false);
            endforeach;
            Yii::$app->getSession()->setFlash('success', 'Se exportaron  ('.$con.') registros al modulo de materias primas con EXITO.');
            $this->redirect(["orden-ensamble-producto/index_descargar_inventario"]);
        }else{
            Yii::$app->getSession()->setFlash('info', 'No hay registros para exportar.'); 
            $this->redirect(["orden-ensamble-producto/index_descargar_inventario"]);
           
        }    
        
    }
    //ACTUALIZA EL COSTO DEL INVENTARIO DE MATERIAL DE EMPAQUE
    
    protected function ActualizarCostoMaterialEmpaque($materia) {
        $iva = 0; $subtotal = 0;
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
    
    //CERRAR ORDEN DE ENSAMBLE UNA VEZ DESCARGADO EL MATERIAL DE EMPAQUE
    public function actionCerrar_orden_ensamlbe($id) {
        $detalle = OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->andwhere(['=','linea_exportada_inventario', 0])->all();
        if(count($detalle) > 0){
          Yii::$app->getSession()->setFlash('error', 'Falta lineas de empaque para enviar al modulo de materias primas. Revise la informacion.'); 
          return $this->redirect(["orden-ensamble-producto/view_lineas_empaque",'id_ensamble' => $id]);
        }else{
            $orden = OrdenEnsambleProducto::findOne($id);
            $orden->exportar_material_empaque = 1;
            $orden->save();
            return $this->redirect(["orden-ensamble-producto/index_descargar_inventario"]);
        }
        
        
    }
    
    //REPORTES
    public function actionImprimir_auditoria_orden($id_auditoria) {
        $model = OrdenEnsambleAuditoria::findOne($id_auditoria);
        return $this->render('../formatos/reporte_auditoria_terminado', [
            'model' => $model,
        ]);
    }
    
    public function actionImprimir_orden_ensamble($id) {
        $model = OrdenEnsambleProducto::findOne($id);
        return $this->render('../formatos/reporte_orden_ensamble', [
            'model' => $model,
        ]);
    }
    
    /**
     * Finds the OrdenEnsambleProducto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrdenEnsambleProducto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrdenEnsambleProducto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //EXCELES
    
     public function actionExcelConsultaAuditoria($tableexcel) {                
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
                    ->setCellValue('B1', 'NUMERO AUDITORIA')
                    ->setCellValue('C1', 'NUMERO ORDEN ENSAMBLE')
                    ->setCellValue('D1', 'GRUPO')
                    ->setCellValue('E1', 'NUMERO LOTE')
                    ->setCellValue('F1', 'FORMA COSMETICA')
                    ->setCellValue('G1', 'NUMERO ENSAMBLE')
                    ->setCellValue('H1', 'COND. ANALISIS')
                    ->setCellValue('I1', 'OBSERVACION')
                    ->setCellValue('J1', 'FECHA ANALISIS')
                    ->setCellValue('K1', 'FECHA PROCESO')
                    ->setCellValue('L1', 'USUARIO')
                    ->setCellValue('M1', 'ANALISIS')
                    ->setCellValue('N1', 'ESPECIFICACIONES')
                    ->setCellValue('O1', 'RESULTADO');
                    
            $i = 2;
        
        foreach ($tableexcel as $val) {
            $detalle = OrdenEnsambleAuditoriaDetalle::find()->where(['=','id_auditoria', $val->id_auditoria])->all();
            foreach ($detalle as $detalles){
                                  
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_auditoria)
                        ->setCellValue('B' . $i, $val->numero_auditoria)
                        ->setCellValue('C' . $i, $val->numero_orden)
                        ->setCellValue('D' . $i, $val->grupo->nombre_grupo)
                        ->setCellValue('E' . $i, $val->numero_lote)
                        ->setCellValue('F' . $i, $val->forma->concepto)
                        ->setCellValue('G' . $i, $val->ensamble->numero_orden_ensamble)
                        ->setCellValue('H' . $i, $val->condicionAnalisis)
                        ->setCellValue('I' . $i, $val->observacion)
                        ->setCellValue('J' . $i, $val->fecha_analisis)
                        ->setCellValue('K' . $i, $val->fecha_proceso)
                        ->setCellValue('L' . $i, $val->user_name)
                        ->setCellValue('M' . $i, $detalles->analisis->concepto)
                        ->setCellValue('N' . $i, $detalles->especificacion->concepto)
                        ->setCellValue('O' . $i, $detalles->resultado);
                $i++;
            }
            $i = $i;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado_auditorias');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Listado_Auditorias.xlsx"');
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
