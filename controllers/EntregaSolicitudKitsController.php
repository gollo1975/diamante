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
///models
use app\models\EntregaSolicitudKits;
use app\models\EntregaSolicitudKitsSearch;
use app\models\UsuarioDetalle;

/**
 * EntregaSolicitudKitsController implements the CRUD actions for EntregaSolicitudKits model.
 */
class EntregaSolicitudKitsController extends Controller
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
     * Lists all EntregaSolicitudKits models.
     * @return mixed
     */
   public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',168])->all()){
                $form = new \app\models\FiltroBusquedaKits();
                $presentacion = null;
                $solicitud = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $presentacion = Html::encode($form->presentacion);
                        $solicitud = Html::encode($form->solicitud);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = EntregaSolicitudKits::find()
                                    ->andFilterWhere(['=', 'id_solicitud', $solicitud])
                                    ->andFilterWhere(['between', 'fecha_solicitud', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_presentacion', $presentacion]);
                        $table = $table->orderBy('id_entrega_kits DESC');
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
                    $table = EntregaSolicitudKits::find()->orderBy('id_entrega_kits DESC');
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
     * Displays a single EntregaSolicitudKits model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle = \app\models\EntregaSolicitudKitsDetalle::find()->where(['=','id_entrega_kits', $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle' => $detalle,
        ]);
    }

    /**
     * Creates a new EntregaSolicitudKits model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionImportar_solicitud() {
        $model = new \app\models\ModeloImportarSolicitud();
        $solicitud = \app\models\SolicitudArmadoKits::find()->where(['=','entregado', 0])->all();
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST["enviar_documento"])){
                if(isset($_POST["nueva_solicitud"])){
                    foreach ($_POST["nueva_solicitud"] as $intCodigo){
                        if($model->cantidad_entregada <> null && $model->tipo_entrega > 0){
                            $dato = \app\models\SolicitudArmadoKits::findOne($intCodigo);
                            if($dato){
                                if($model->cantidad_entregada <= $dato->saldo_cantidad_solicitada){
                                    $detalle = \app\models\SolicitudArmadoKitsDetalle::find()->where(['=','id_solicitud_armado', $intCodigo])->all();
                                    foreach ($detalle as $val) {
                                        $table = new EntregaSolicitudKits();
                                        $table->id_solicitud = $model->tipo_solicitud;
                                        $table->id_presentacion = $dato->id_presentacion;
                                        $table->id_solicitud_armado = $dato->id_solicitud_armado;
                                        $table->fecha_solicitud = date('Y-m-d');
                                        $model->fecha_hora_proceso = date('Y-m-d H:i:s');
                                        $table->cantidad_despachada = $model->cantidad_entregada;
                                        $table->cantidad_despachada_saldo = $model->cantidad_entregada;
                                        $table->user_name = Yii::$app->user->identity->username;
                                        $table->save(false);
                                        $codigo = $dato->id_solicitud_armado;
                                        $consecutivo = EntregaSolicitudKits::find()->orderBy('id_entrega_kits DESC')->one();
                                        $id = $consecutivo->id_entrega_kits;
                                        $unidades = $model->cantidad_entregada;
                                        $this->DetalleEntrega($codigo, $id, $unidades);
                                        $this->SumarCantidades($id);
                                        return $this->redirect(["view",'id' => $id, 'token' =>0]);
                                    }
                                    
                                }else{
                                    Yii::$app->getSession()->setFlash('error', 'Las cantidades entregas nos mayores que las cantidades solicitadas'); 
                                    return $this->redirect(["index"]);
                                }
                            }    

                        } else {
                            Yii::$app->getSession()->setFlash('error', 'Campos vacios. Vuelva a intentarlo.'); 
                            return $this->redirect(["index"]);
                        }
                    }
                    return $this->redirect(['index']);       
                }    
            }
         }
         return $this->renderAjax('_form', [
            'model' => $model,
            'solicitud' => $solicitud,
            
        ]);  
    }
    
    //proceso del detalle
    protected function DetalleEntrega($codigo, $id, $unidades) {
        $detalle = \app\models\SolicitudArmadoKitsDetalle::find()->where(['=','id_solicitud_armado', $codigo])->all();  
        foreach ($detalle as $val) {
            $table = new \app\models\EntregaSolicitudKitsDetalle();
            $table->id_entrega_kits = $id;
            $table->id_detalle = $val->id_detalle;
            $table->cantidad_solicitada = $val->cantidad_solicitada;
            $table->cantidad_despachada = $unidades;
            $table->save(false);
        }
    }
    
    //PROCESO QUE SUMA LAS UNIDADES
    protected function SumarCantidades($id) {
        $model = EntregaSolicitudKits::findOne($id);
        $table = \app\models\EntregaSolicitudKitsDetalle::find()->where(['=','id_entrega_kits', $id])->all();
        $total = 0;
        foreach ($table as $val) {
            $total += $val->cantidad_despachada;
        }
        $model->total_unidades_entregadas = $total;
        $model->save();
    }

    /**
     * Updates an existing EntregaSolicitudKits model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_entrega_kits,'token' => 0]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EntregaSolicitudKits model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
            $this->redirect(["entrega-solicitud-kits/index"]);
        } catch (IntegrityException $e) {
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene procesos asociados.');
            $this->redirect(["entrega-solicitud-kits/index"]);
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene procesos asociados.');
             $this->redirect(["entrega-solicitud-kits/index"]);            

        }
    }
    
     //ELIMINAR DETALLE DEL DESPACHOS
     public function actionEliminar_detalle($id,$id_detalle, $token)
    {                                
        $dato = \app\models\EntregaSolicitudKitsDetalle::findOne($id_detalle);
        $dato->delete();
        $this->SumarCantidades($id);
        return $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
    
    
    //REGENEAR ARCHIVO
    public function actionRegenerar_formula($id, $token, $id_solicitud) {
        $model = EntregaSolicitudKits::findOne($id);
        $detalle = \app\models\SolicitudArmadoKitsDetalle::find()->where(['=','id_solicitud_armado', $id_solicitud])->all();
        
        if (count($detalle) > 0){
            foreach ($detalle as $val) {
                $buscar = \app\models\EntregaSolicitudKitsDetalle::find()->where(['=','id_entrega_kits', $id])->andWhere(['=','id_detalle', $val->id_detalle])->one();
                if(!$buscar){
                    $table = new \app\models\EntregaSolicitudKitsDetalle();
                    $table->id_entrega_kits = $id;
                    $table->id_detalle = $val->id_detalle;
                    $table->cantidad_solicitada = $val->cantidad_solicitada;
                    $table->cantidad_despachada = $model->cantidad_despachada;
                    $table->save(false);
                }    
            }
            $this->SumarCantidades($id);
            return $this->redirect(['view','id' => $id, 'token' => $token]);
        }else{
            Yii::$app->getSession()->setFlash('error', 'La solicitud de kits No: ' .$id_solicitud . ', NO tiene productos relaccionados. Valide la informacion.');
            return $this->redirect(['view','id' => $id, 'token' => $token]);
        }
            
    }

    //AUTORIZAR EL PROCESO
    public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        if(\app\models\EntregaSolicitudKitsDetalle::find()->where(['=','id_entrega_kits', $id])->one()){
            if ($model->autorizado == 0){  
                $model->autorizado = 1;
                $model->save();
                return $this->redirect(["entrega-solicitud-kits/view", 'id' => $id, 'token' =>$token]); 
            } else{
                $model->autorizado = 0;
                $model->save();
                return $this->redirect(["entrega-solicitud-kits/view", 'id' => $id, 'token' =>$token]); 
            }                  
            
        }else{
            Yii::$app->getSession()->setFlash('error', 'Debe descargar los producto para la entrega.'); 
            return $this->redirect(["entrega-solicitud-kits/view", 'id' => $id, 'token' => $token]); 
        }    
    }
    
    //CIERRA EL PROCESO DE SOLICTUD
    public function actionCerrar_solicitud($id, $token) {
        //proceso de generar consecutivo
        $detalle = \app\models\EntregaSolicitudKitsDetalle::find()->where(['=','id_entrega_kits', $id])->all();
        foreach ($detalle as $val) {
            if($val->unidades_faltante <> 0){
                Yii::$app->getSession()->setFlash('error', 'Debe de validar todas las lineas en estado (OK) para garantizar que si esten las unidades completas a despachar.'); 
                return $this->redirect(["entrega-solicitud-kits/view", 'id' => $id, 'token' => $token]); 
            }
        }
        $lista = \app\models\Consecutivos::findOne(33);
        $model = EntregaSolicitudKits::findOne($id); 
        $suma = $model->cantidad_despachada;
        //buscamos la solicitud
        $solicitud = \app\models\SolicitudArmadoKits::findOne($model->id_solicitud_armado);
        $total = $solicitud->saldo_cantidad_solicitada - $suma;
        if($total <= 0){
            $solicitud->entregado = 1;
        }
        $solicitud->saldo_cantidad_solicitada = $total;
        $solicitud->save();
        //genera consecutivo
        $model->numero_entrega = $lista->numero_inicial + 1;
        $model->proceso_cerrado = 1;
        $model->fecha_hora_cierre = date('Y-m-d H:i:s');
        $model->save();
        //actualiza consecutivo
        $lista->numero_inicial = $model->numero_entrega;
        $lista->save();
        return  $this->redirect(["entrega-solicitud-kits/view", 'id' => $id, 'token' =>$token]);  
    }
    
    
    //PROCESO QUE PERMITE SUBIR LAS UNIDADES A DESPACHAR 
    public function actionCantidad_despachada($id, $id_detalle, $id_inventario,$token){
        $model = new \app\models\ModeloDocumento(); 
        $detalle  = \app\models\EntregaSolicitudKitsDetalle::findOne($id_detalle);
        $almacenamiento = \app\models\AlmacenamientoProductoDetalles::find()
                                                                  ->where(['=','id_inventario', $id_inventario])
                                                                   ->andWhere(['>','cantidad', 0])->orderBy('fecha_vencimiento ASC')->all();
        
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
            if ($model->load(Yii::$app->request->post())) {
                if(isset($_POST["cantidaddespachada"])){
                    if($model->cantidad_despachada <= $detalle->unidades_faltante){ 
                        if(isset($_POST["seleccione_item"])){
                            $valor = 0 ;
                            foreach ($_POST["seleccione_item"] as $intCodigo):
                                $cantidad = 0; $sobrante = 0; $restar = 0;
                                $base = \app\models\AlmacenamientoProductoDetalles::findOne($intCodigo);
                                if($base->cantidad >= $model->cantidad_despachada){
                                    if($base->cantidad <= $model->cantidad_despachada){
                                        $cantidad = $base->cantidad;
                                        $restar =  $model->cantidad_despachada -  $cantidad;
                                        $base->cantidad = $restar;
                                        $base->save(false);
                                        $id_rack = $base->id_rack;
                                        $unidades = $model->cantidad_despachada;
                                        $sobrante = $detalle->cantidad_despachada - $model->cantidad_despachada; 
                                        //detalle
                                        $detalle->unidades_faltante = $sobrante;
                                        $detalle->numero_lote = $base->numero_lote;
                                        $detalle->save(false);
                                        $this->ActualizarUnidadesRack($id_rack, $unidades);
                                    }else{
                                        if($model->cantidad_despachada <= $base->cantidad){
                                           $cantidad = $base->cantidad;
                                           $restar =  $cantidad - $model->cantidad_despachada;
                                           $sobrante = $detalle->unidades_faltante - $model->cantidad_despachada;
                                           $base->cantidad = $restar;
                                           $base->save(false);
                                           $id_rack = $base->id_rack;
                                           $unidades = $model->cantidad_despachada;                                          
                                           //dettale
                                           $detalle->unidades_faltante = $sobrante;
                                           $detalle->numero_lote = $base->numero_lote;
                                           $detalle->save(false);
                                           $this->ActualizarUnidadesRack($id_rack, $unidades);
                                        } else{
                                            Yii::$app->getSession()->setFlash('error', 'La cantidad despachada es mayor que hay la cantidad que hay en RACK. Valide la informacion.');
                                            return $this->redirect(['cantidad_despachada', 'id' => $id, 'id_detalle' => $id_detalle, 'id_inventario' => $id_inventario,'token' => $token]); 
                                        }    
                                    }
                                }else{
                                    Yii::$app->getSession()->setFlash('error', 'La cantidad despachada es mayor que hay la cantidad que hay en RACK. Valide la informacion.');
                                    return $this->redirect(['cantidad_despachada', 'id' => $id, 'id_detalle' => $id_detalle, 'id_inventario' => $id_inventario,'token' => $token]); 
                                }
                                
                                if($sobrante <> 0){
                                    return $this->redirect(['cantidad_despachada', 'id' => $id, 'id_detalle' => $id_detalle, 'id_inventario' => $id_inventario,'token' => $token]); 
                                }else{
                                   return $this->redirect(['view', 'id' => $id, 'token' => $token]); 
                                }    
                              endforeach;
                        }else{
                              Yii::$app->getSession()->setFlash('error', 'Debe se chequear el RACK o medio de almacenamiento para descargar el prodcuto del inventario.');
                              return $this->redirect(['cantidad_despachada', 'id' => $id, 'id_detalle' => $id_detalle, 'id_inventario' => $id_inventario,'token' => $token]);

                        }     
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'La cantidad de unidades despachas NO pueden ser mayor que las unidades solicitadas. Valide esta informacion.');
                        return $this->redirect(['cantidad_despachada', 'id' => $id, 'id_detalle' => $id_detalle, 'id_inventario' => $id_inventario,'token' => $token]);
                    }  
                }
            }
            if (Yii::$app->request->get()) {
               $model->cantidad_solicitadas = $detalle->unidades_faltante;
            }
        
        return $this->render('_form_cantidad_despachada', [
                        'model' => $model,
                        'almacenamiento' => $almacenamiento,
                        'id' => $id,
                        'id_inventario' => $id_inventario,
                        'detalle' => $detalle,
                        'token' => $token,
                      
        ]); 
        
    }
    
      
    //ACTUALIZAR UNIDADES RACK
    protected function ActualizarUnidadesRack($id_rack, $unidades) {
        $rack = \app\models\TipoRack::findOne($id_rack);
        $suma = 0;
        $suma = $rack->capacidad_actual - $unidades;
        $rack->capacidad_actual = $suma;
        $rack->save();
    }
    
    //REPORTE O IMPRESIONES
    public function actionImprimir_solicitud_kits($id)
    {
        $model = $this->findModel($id);
        return $this->render('../formatos/reporte_entrega_kits',[
           'model' => $model, 
        ]);
        
    }
    
    
    /**
     * Finds the EntregaSolicitudKits model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EntregaSolicitudKits the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntregaSolicitudKits::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
