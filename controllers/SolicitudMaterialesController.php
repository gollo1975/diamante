<?php

namespace app\controllers;

//clases
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

use app\models\SolicitudMateriales;
use app\models\UsuarioDetalle;
use app\models\MateriaPrimas;
use app\models\TipoSolicitud;
use app\models\GrupoProducto;


/**
 * SolicitudMaterialesController implements the CRUD actions for SolicitudMateriales model.
 */
class SolicitudMaterialesController extends Controller
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
     * Lists all SolicitudMateriales models.
     * @return mixed
     */
   public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',98])->all()){
                $form = new \app\models\FiltroBusquedaSolicitudMateriales();
                $numero_solicitud = null;
                $numero_lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $orden = null;
                $grupo = null; $tipo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_solicitud = Html::encode($form->numero_solicitud);
                        $numero_lote = Html::encode($form->numero_lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $orden = Html::encode($form->orden);
                        $grupo = Html::encode($form->grupo);
                        $tipo = Html::encode($form->tipo);
                        $table = SolicitudMateriales::find()
                                    ->andFilterWhere(['=', 'numero_solicitud', $numero_solicitud])
                                    ->andFilterWhere(['between', 'fecha_cierre', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_orden_produccion', $orden])
                                    ->andFilterWhere(['=', 'numero_lote', $numero_lote])
                                    ->andFilterWhere(['=', 'id_grupo', $grupo])
                                ->andFilterWhere(['=', 'id_solicitud', $tipo]);
                        $table = $table->orderBy('codigo DESC');
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
                            $check = isset($_REQUEST['codigo  DESC']);
                            $this->actionExcelConsultaSolicitud($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = SolicitudMateriales::find()
                            ->orderBy('codigo DESC');
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
                        $this->actionExcelConsultaSolicitud($tableexcel);
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
     * Displays a single SolicitudMateriales model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle_solicitud = \app\models\SolicitudMaterialesDetalle::find()->where(['=','codigo', $id])->all();
        if (Yii::$app->request->post()) {
            if(isset($_POST["actualizar_cantidad"])){
                if(isset($_POST["listado_materiales"])){
                    $intIndice = 0;
                    foreach ($_POST["listado_materiales"] as $intCodigo):
                        $table = \app\models\SolicitudMaterialesDetalle::findOne($intCodigo);
                        $table->unidades_requeridas = $_POST["unidades_requeridas"][$intIndice];
                        $table->save();
                        $intIndice++;
                    endforeach;
                    return $this->redirect(['view','id' =>$id, 'token' => $token]);
                }
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' =>$token,
            'detalle_solicitud' => $detalle_solicitud,
        ]);
    }

    /**
     * Creates a new SolicitudMateriales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',100])->all()){
                $model = new SolicitudMateriales();
                $tipoSolicitud = TipoSolicitud::find()->where(['=','aplica_materia_prima', 1])->orderBy ('descripcion ASC')->all();
                $grupo = GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all();
                $ordenProduccion = \app\models\OrdenEnsambleProducto::find()->where(['=','exportar_material_empaque', 0])->orderBy('id_orden_produccion DESC')->all();
                if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    $orden = \app\models\OrdenProduccion::findOne($model->id_orden_produccion);
                    $model->id_grupo = $orden->id_grupo;
                    $model->numero_lote = $orden->numero_lote;
                    $model->numero_orden_produccion = $orden->numero_orden;
                    $model->unidades = $orden->unidades;
                    $model->user_name = Yii::$app->user->identity->username;
                    $model->observacion = $model->observacion;
                    $model->save();
                    return $this->redirect(['view', 'id' => $model->codigo, 'token' =>0]);
                }

                return $this->render('create', [
                    'model' => $model,
                    'tipoSolicitud' => ArrayHelper::map($tipoSolicitud, 'id_solicitud', 'descripcion'),
                    'ordenProduccion' => ArrayHelper::map($ordenProduccion, 'id_orden_produccion', 'OrdenEnsambleConsulta'),
                    'grupo' => ArrayHelper::map($grupo, 'id_grupo', 'nombre_grupo'),
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']); 
            }   
        }else{
            return $this->redirect(['site/login']);
        }    
                
    }

    /**
     * Updates an existing SolicitudMateriales model.
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
        $tipoSolicitud = TipoSolicitud::find()->where(['=','aplica_materia_prima', 1])->orderBy ('descripcion ASC')->all();
        $grupo = GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all();
        $ordenProduccion = \app\models\OrdenEnsambleProducto::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'tipoSolicitud' => ArrayHelper::map($tipoSolicitud, 'id_solicitud', 'descripcion'),
            'ordenProduccion' => ArrayHelper::map($ordenProduccion, 'id_orden_produccion', 'OrdenEnsambleConsulta'),
            'grupo' => ArrayHelper::map($grupo, 'id_grupo', 'nombre_grupo'),
        ]);
    }
     //BUSCAR MATERIA PRIMAS
    public function actionBuscar_materia_prima($id, $token, $id_solicitud){
        $operacion = MateriaPrimas::find()->where(['=','id_solicitud', $id_solicitud])->orderBy('materia_prima DESC')->all();
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
            $table = MateriaPrimas::find()->where(['=','id_solicitud', $id_solicitud])->orderBy('materia_prima DESC');
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
                    $registro = \app\models\SolicitudMaterialesDetalle::find()->where(['=','id_materia_prima', $intCodigo])->andWhere(['=','codigo', $id])->one();
                    if(!$registro){
                        $item = MateriaPrimas::findOne($intCodigo);
                        $ordenSolicitud = SolicitudMateriales::findOne($id); 
                        $table = new \app\models\SolicitudMaterialesDetalle();
                        $table->codigo = $id;
                        $table->id_materia_prima = $intCodigo;
                        $table->codigo_materia = $item->codigo_materia_prima;
                        $table->materiales = $item->materia_prima;
                        $table->unidades_lote = $ordenSolicitud->unidades;
                        $table->save();
                    }    
                }
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('search_materia_prima', [
            'operacion' => $operacion,            
            'pagination' => $pages,
            'id' => $id,
            'form' => $form,
            'token' => $token,
            'id_solicitud' => $id_solicitud

        ]);
    }

    /**
     * Deletes an existing SolicitudMateriales model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   //ELIMINAR DETALLE DEL PRECIO DE VENTA
     public function actionEliminar_detalle($id,$id_detalle, $token)
    {                                
        $dato = \app\models\SolicitudMaterialesDetalle::findOne($id_detalle);
        $dato->delete();
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
     //SE AUTORIZA O DESAUTORIZA EL PRODUCTO
    public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        if(\app\models\SolicitudMaterialesDetalle::find()->where(['=','codigo', $id])->one()){
            if ($model->autorizado == 0){  
                $model->autorizado = 1;
                $model->update();
                $this->redirect(["solicitud-materiales/view", 'id' => $id, 'token' =>$token]); 
            } else{
                    $model->autorizado = 0;
                    $model->update();
                    $this->redirect(["solicitud-materiales/view", 'id' => $id, 'token' =>$token]);  
            }    
        }else{
            Yii::$app->getSession()->setFlash('error', 'Debe se seleccionar el material de empaque para generar la solictud.'); 
            $this->redirect(["solicitud-materiales/view", 'id' => $id, 'token' => $token]); 
        }    
    }
    
    //CIERRA EL PROCESO DE SOLICTUD
    public function actionCerrar_solicitud($id, $token) {
        //proceso de generar consecutivo
        $lista = \app\models\Consecutivos::findOne(14);
        $solicitud = SolicitudMateriales::findOne($id); 
        $solicitud->numero_solicitud = $lista->numero_inicial + 1;
        $solicitud->cerrar_solicitud = 1;
        $solicitud->fecha_cierre = date('Y-m-d');
        $solicitud->save();
        $lista->numero_inicial = $solicitud->numero_solicitud;
        $lista->save();
        $this->redirect(["solicitud-materiales/view", 'id' => $id, 'token' =>$token]);  
    }
    
     //REPORTES
    public function actionImprimir_solicitud_materiales($id) {
        $model = SolicitudMateriales::findOne($id);
        return $this->render('../formatos/reporte_solicitud_materiales', [
            'model' => $model,
        ]);
    }
    
    //GENERAR DESPACHO DE MATERIALES
    public function actionGenerar_despacho_material($id, $token) {
        
        
        $solicitud = SolicitudMateriales::findOne($id);
        $detalle = \app\models\SolicitudMaterialesDetalle::find()->where(['=','codigo', $id])->all();
        $contador = 0; $sw = 0;
        foreach ($detalle as $val):
            $contador += $val->unidades_requeridas;
            $sw += 1;

        endforeach;
        $contador = $contador/$sw;
        //insertar proceso
        $table = new \app\models\EntregaMateriales();
        $table->codigo = $solicitud->codigo;
        $table->unidades_solicitadas = $contador;
        $table->user_name = Yii::$app->user->identity->username;
        $table->save();
        $entrega = \app\models\EntregaMateriales::find()->orderBy('id_entrega DESC')->one();
        //INSERTA DETALLE
        foreach ($detalle as $val):
            $modelo = new \app\models\EntregaMaterialesDetalle();
            $modelo->id_entrega = $entrega->id_entrega;
            $modelo->id_materia_prima = $val->id_materia_prima;
            $modelo->codigo_materia = $val->codigo_materia;
            $modelo->materiales = $val->materiales;
            $modelo->unidades_solicitadas = $val->unidades_requeridas;
            $modelo->save();       
        endforeach;
        $this->redirect(["entrega-materiales/view", 'id' => $entrega->id_entrega, 'token' =>$token]); 
           
    }
    
    /**
     * Finds the SolicitudMateriales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SolicitudMateriales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SolicitudMateriales::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
