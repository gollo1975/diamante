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
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaOrdenEnsamble;



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
                $grupo = null; $tipo_proceso = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_ensamble = Html::encode($form->numero_ensamble);
                        $numero_lote = Html::encode($form->numero_lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $orden = Html::encode($form->orden);
                        $grupo = Html::encode($form->grupo);
                        $table = OrdenEnsambleProducto::find()
                                    ->andFilterWhere(['=', 'numero_orden_ensamble', $numero_ensamble])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_orden_produccion', $orden])
                                    ->andFilterWhere(['=', 'numero_lote', $numero_lote])
                                    ->andFilterWhere(['=', 'id_grupo', $grupo]);
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

    /**
     * Displays a single OrdenEnsambleProducto model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $conPresentacion = \app\models\OrdenEnsambleProductoDetalle::find()->where(['=','id_ensamble', $id])->all();
        $orden_ensamble = OrdenEnsambleProducto::findOne($id);
        $conMateriales = \app\models\OrdenEnsambleProductoEmpaque::find(['=','id_ensamble', $id])->all();
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
                    return $this->redirect(['view','id' =>$id, 'token' => $token]);
                }
            }
        }  
        //actualizar unidades empacadas
         if (Yii::$app->request->post()) {
           if(isset($_POST["actualizar_material_empaque"])){
                if(isset($_POST["listado_empaque"])){
                    $intIndice = 0;
                    foreach ($_POST["listado_empaque"] as $intCodigo):
                        $table = \app\models\OrdenEnsambleProductoEmpaque::findOne($intCodigo);
                        $table->unidades_devolucion = $_POST["unidades_devolucion"][$intIndice];
                        $table->unidades_averias = $_POST["unidades_averias"][$intIndice];
                        $table->unidades_sala_tecnica = $_POST["unidades_sala_tecnica"][$intIndice];
                        $table->unidades_muestra_retencion = $_POST["unidades_muestra_retencion"][$intIndice];
                        $table->save();
                        $intIndice++;
                    endforeach;
                   // $this->TotalUnidadesLote($orden_ensamble);
                   return $this->redirect(['view','id' =>$id, 'token' => $token]);
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
                            $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' => $token]);
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
    
    //PROCESO QUE SUMA Y RESTA UNIDADES DE ENVASE
    protected function SumarRestarEmpaque($id) {
        $detalle  = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->all();
        $suma = 0; $suma1 = 0;
        foreach ($detalle as $detalles):
           
        endforeach;
    }
    
    //PROCESO QUE CARGA LAS PRESENTACIONES DEL PRODUCTO QUE ESTAN EN UNA ORDE DE PRODUCCION
    public function actionCargar_nuevamente_items($id, $token, $id_orden_produccion) {
        
        $detalle_orden = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id_orden_produccion])->all();
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
        return $this->redirect(['/orden-ensamble-producto/view','id' => $id, 'token' => $token]);
    }
    

    /**
     * Deletes an existing OrdenEnsambleProducto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   //ELIMINAR DETALLE DE LA ORDEN DE ENSAMBLE
     public function actionEliminar_detalle_ensamble($id, $id_detalle, $token)
    {                                
        $dato = \app\models\OrdenEnsambleProductoDetalle::findOne($id_detalle);
        $dato->delete();
        return $this->redirect(['view','id' => $id, 'token' => $token]);     
    }

    //AUTORIZAR Y DESAUTORIZAR UNA ORDEN DE ENSAMBLE
      public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        if ($model->autorizado == 0){  
            $model->autorizado = 1;            
            $model->update();
            $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token]);  
        }else{
            $model->autorizado = 0;            
            $model->update();
            $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token]);      
        }    
    }
    
    //GENERA LA ORDEN Y CIERRA EN PROCESO
    public function actionGenerar_orden_ensamble($id, $token) {
        $orden = OrdenEnsambleProducto::findOne($id);
        $detalle_empaque = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->all();
        $sw = 0; $suma1; $suma2 = 0; 
        foreach ($detalle_empaque as $empaque):
            if($empaque->alerta == 'FALTA'){
               $sw = 1;
               $suma1 = $empaque->unidades_devolucion + $empaque->unidades_averias;
               $empaque->unidades_utilizadas -= $suma1;
               $empaque->unidades_reales = $empaque->unidades_utilizadas;
               $empaque->save(false);
               $suma2 = $empaque->unidades_sala_tecnica + $empaque->unidades_muestra_retencion;
               $empaque->unidades_reales -= $suma2;
               $empaque->save(false);
            }else{
              $suma1 = $empaque->unidades_devolucion + $empaque->unidades_averias;
               $empaque->unidades_utilizadas -= $suma1;
               $empaque->unidades_reales = $empaque->unidades_utilizadas;
               $empaque->save(false);
               $suma2 = $empaque->unidades_sala_tecnica + $empaque->unidades_muestra_retencion;
               $empaque->unidades_reales -= $suma2;
               $empaque->save(false);
            }
        endforeach;
        if($sw == 1){
            Yii::$app->getSession()->setFlash('warning', 'No se puede generar la orden de ensamble porque los materiales de empaque estan incompletos. Validar con el administrador.'); 
            $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token]); 
             
        }else{
            //generar consecutivo
            $consecutivo = \app\models\Consecutivos::findOne(12);
            $orden->numero_orden_ensamble = $consecutivo->numero_inicial + 1;
            $orden->cerrar_orden_ensamble = 1;
            $orden->save();
            $consecutivo->numero_inicial = $orden->numero_orden_ensamble;
            $consecutivo->save();
            $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token]); 
        }
    }
    
    //PROCESO QUE CIERRA EN SU TOTALIDAD LA ORDEN DE ENSAMBLE
    public function actionCerrar_orden_ensamble($id, $token) {
        $orden = OrdenEnsambleProducto::findOne($id);
        $detalle_empaque = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])->all();
        $sw = 0;
        foreach ($detalle_empaque as $detalle):
            echo $detalle->unidades_reales;
            if($orden->total_unidades <> $detalle->unidades_reales){
               echo $sw = 1;
            }
        endforeach;
        if($sw == 1){
            Yii::$app->getSession()->setFlash('warning', 'No se puede CERRAR la orden de ensamble porque las UNIDADES REALES no son iguales en el proceso. Favor corregir las unidades por la primer vista.'); 
            $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' =>$token]); 
        }    
    }
    
    //BUSCAR MATERIA PRIMA PARA EL PRODUCTO
    public function actionBuscar_material_empaque($id, $token, $id_solicitud){
        $operacion = \app\models\MateriaPrimas::find()->where(['>','stock', 0])->andWhere(['=','id_solicitud', 2])->orderBy('materia_prima ASC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                    $operacion = \app\models\MateriaPrimas::find()
                            ->where(['like','materia_prima',$q])
                            ->orwhere(['=','codigo_materia_prima',$q])
                            ->andWhere(['>','stock', 0])
                            ->andWhere(['=','id_solicitud', 2]);
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
            $table = \app\models\MateriaPrimas::find()->where(['>','stock', 0])->andWhere(['=','id_solicitud', 2])->orderBy('materia_prima ASC');
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
         if (isset($_POST["guardar_material_empaque"])) {
            if(isset($_POST["nuevo_material_empaque"])){
                foreach ($_POST["nuevo_material_empaque"] as $intCodigo) {
                    //consulta para no duplicar
                    $registro = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $id])
                                                                   ->andWhere(['=','id_materia_prima', $intCodigo])->one();
                    if(!$registro){
                        $orden = OrdenEnsambleProducto::findOne($id);
                        $materia = \app\models\MateriaPrimas::findOne($intCodigo);
                        $table = new \app\models\OrdenEnsambleProductoEmpaque();
                        $table->id_ensamble = $id;
                        $table->id_materia_prima = $intCodigo;
                        $table->unidades_solicitadas =  $orden->total_unidades;
                        $table->unidades_utilizadas =  $orden->total_unidades;
                        $table->unidades_reales =  $orden->total_unidades;
                        $table->user_name =  Yii::$app->user->identity->username;
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
                }
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('importar_material_empaque', [
            'operacion' => $operacion,            
            'pagination' => $pages,
            'id' => $id,
            'form' => $form,
            'token' => $token,
            'id_solicitud' => $id_solicitud,
        ]);
    }
    
    //SUBIR RESPONASBLE AL PROCESO
    //PERMITE TERMINAR DE VALIDAD LA AUDITORIA Y SUBE LA CONTINUIDAD DEL PROCESO
    
    public function actionSubir_responsable($id, $token) {
        $model = new \app\models\FormModeloSubirAuditoria();
        $ensamble = \app\models\OrdenEnsambleProducto::findOne($id);
       
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["subir_responsable"])) { 
                $ensamble->responsable = $model->responsable;
                $ensamble->peso_neto = $model->peso_neto;
                $ensamble->observacion = $model->observacion;
                $ensamble->fecha_hora_cierre = date('Y-m-d H:i:s' );
                $ensamble->save(false);
                $this->redirect(["orden-ensamble-producto/view", 'id' => $id, 'token' => $token]);
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
}