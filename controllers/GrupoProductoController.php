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
use app\models\GrupoProducto;
use app\models\GrupoProductoSearch;
use app\models\UsuarioDetalle;

/**
 * GrupoProductoController implements the CRUD actions for GrupoProducto model.
 */
class GrupoProductoController extends Controller
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
     * Lists all GrupoProducto models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',13])->all()){
                $searchModel = new GrupoProductoSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            } 
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //PROCESO QUE MUSTRAS TODOS LOS PRODUCTOS O GRUPOS CREADOS
    public function actionIndex_producto_configuracion($sw) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',90])->all()){
                $form = new \app\models\FiltroBusquedaGrupo();
                $grupo = null;
                $nombre = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $grupo = Html::encode($form->grupo);
                        $nombre = Html::encode($form->nombre);
                        $table = GrupoProducto::find()
                                    ->andFilterWhere(['=', 'id_grupo', $grupo])
                                    ->andFilterWhere(['like', 'nombre_grupo', $nombre]);
                        $table = $table->orderBy('id_grupo DESC');
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
                            $check = isset($_REQUEST['id_grupo  DESC']);
                            $this->actionExcelConsultaGrupo($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = GrupoProducto::find()
                            ->orderBy('id_grupo desc');
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
                        $this->actionExcelConsultaGrupo($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index_producto_configuracion', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'sw' => $sw,
                        ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }

    /**
     * Displays a single GrupoProducto model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    //VISTA QUE ENVIA LA INFORMACION PARA CONFIGURAR EL PRODUCTO
    public function actionView_configuracion($id_grupo, $sw) 
    {
        $configuracion = \app\models\ConfiguracionProducto::find()->where(['=','id_grupo', $id_grupo])->orderBy('id_fase ASC')->all();
        if(isset($_POST["actualizamateriaprima"])){
            if(isset($_POST["listado_materia"])){
                $intIndice = 0;
                foreach ($_POST["listado_materia"] as $intCodigo):
                    $table = \app\models\ConfiguracionProducto::findOne($intCodigo);
                    $table->id_fase = $_POST["fase"][$intIndice];    
                    $table->porcentaje_aplicacion = $_POST["porcentaje_aplicacion"][$intIndice];
                    $table->codigo_homologacion = $_POST["codigo_homologacion"][$intIndice];
                    $table->save(false);
                    $intIndice++;
                endforeach;
                return $this->redirect(['view_configuracion','id_grupo' =>$id_grupo, 'sw' => $sw]);
            }
        }    
        return $this->render('view_configuracion', [
            'model' => $this->findModel($id_grupo),
            'configuracion' => $configuracion,
            'sw' => $sw,    
        ]);
    }
    
     //VISTA QUE ENVIA LA INFORMACION PARA CONFIGURAR EK ANALISIS DE AUDITORIA
    public function actionView_analisis($id_grupo, $sw) 
    {
        $analisis = \app\models\ConfiguracionProductoProceso::find()->where(['=','id_grupo', $id_grupo])->orderBy('id_analisis ASC')->all();
        if(isset($_POST["actualizalineas"])){
            if(isset($_POST["listado_analisis_cargados"])){
                $intIndice = 0;
                foreach ($_POST["listado_analisis_cargados"] as $intCodigo):
                    $table = \app\models\ConfiguracionProductoProceso::findOne($intCodigo);
                    $table->id_etapa = $_POST["etapa"][$intIndice];    
                    $table->id_especificacion = $_POST["especificaciones"][$intIndice];
                    $table->resultado = $_POST["resultado"][$intIndice];
                    $table->save(false);
                    $intIndice++;
                endforeach;
                return $this->redirect(['view_analisis','id_grupo' =>$id_grupo, 'sw' => $sw]);
            }
        }    
        return $this->render('view_analisis', [
            'model' => $this->findModel($id_grupo),
            'analisis' => $analisis,
            'sw' => $sw,    
        ]);
    }
    
    //BUSCAR MATERIA PRIMA PARA EL PRODUCTO
     public function actionBuscarmateriaprima($id_grupo, $sw){
        $operacion = \app\models\MateriaPrimas::find()->where(['>','stock', 0])->andWhere(['=','id_solicitud', 1])->orderBy('materia_prima ASC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                    $operacion = \app\models\MateriaPrimas::find()
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
            $table = \app\models\MateriaPrimas::find()->where(['>','stock', 0])->andWhere(['=','id_solicitud', 1])->orderBy('materia_prima ASC');
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
                    $registro = \app\models\ConfiguracionProducto::find()->where(['=','id_grupo', $id_grupo])
                                                                   ->andWhere(['=','id_materia_prima', $intCodigo])->one();
                    if(!$registro){
                        $materia = \app\models\MateriaPrimas::findOne($intCodigo);
                        $table = new \app\models\ConfiguracionProducto();
                        $table->id_grupo = $id_grupo;
                        $table->id_materia_prima = $intCodigo;
                        $table->codigo_materia =  $materia->codigo_materia_prima;
                        $table->nombre_materia_prima = $materia->materia_prima;
                        $table->id_fase = 1;
                        $table->user_name =  Yii::$app->user->identity->username;
                        $table->save(false);
                    }    
                }
                return $this->redirect(['view_configuracion','id_grupo' => $id_grupo, 'sw' => $sw]);
            }
        }
        return $this->render('importar_materia_prima', [
            'operacion' => $operacion,            
            'pagination' => $pages,
            'id_grupo' => $id_grupo,
            'form' => $form,
            'sw' => $sw,
        ]);
    }

    //BUSCAR CONCEPTOS DE ANALISIS PARA AGREGAR AL PROCESO
     public function actionBuscar_concepto_analisis($id_grupo, $sw){
        $operacion = \app\models\ConceptoAnalisis::find()->orderBy('id_etapa ASC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        $etapa = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);  
                $etapa = Html::encode($form->etapa); 
                    $operacion = \app\models\ConceptoAnalisis::find()
                            ->where(['like','concepto',$q])
                            ->orwhere(['=','id_analisis',$q])
                            ->andFilterWhere(['=','id_etapa',$etapa]);
                    $operacion = $operacion->orderBy('concepto ASC');                    
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
            $table = \app\models\ConceptoAnalisis::find()->orderBy('id_etapa ASC');
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
         if (isset($_POST["guardarnuevoconcepto"])) {
            if(isset($_POST["nuevo_concepto"])){
                foreach ($_POST["nuevo_concepto"] as $intCodigo) {
                    //consulta para no duplicar
                    $registro = \app\models\ConfiguracionProductoProceso::find()->where(['=','id_grupo', $id_grupo])
                                                                   ->andWhere(['=','id_analisis', $intCodigo])->one();
                    if(!$registro){
                        $materia = \app\models\ConceptoAnalisis::findOne($intCodigo);
                        $table = new \app\models\ConfiguracionProductoProceso();
                        $table->id_grupo = $id_grupo;
                        $table->id_analisis = $intCodigo;
                        $table->id_etapa = $materia->id_etapa;
                        $table->user_name =  Yii::$app->user->identity->username;
                        $table->save(false);
                    }    
                }
                return $this->redirect(['view_analisis','id_grupo' => $id_grupo, 'sw' => $sw]);
            }
        }
        return $this->render('importar_concepto_analisis', [
            'operacion' => $operacion,            
            'pagination' => $pages,
            'id_grupo' => $id_grupo,
            'form' => $form,
            'sw' => $sw,
        ]);
    }

    
    /**
     * Creates a new GrupoProducto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GrupoProducto();

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
     * Updates an existing GrupoProducto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GrupoProducto model.
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
            $this->redirect(["grupo-producto/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["grupo-producto/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociado en otros procesos');
            $this->redirect(["grupo-producto/index"]);
        }
    }
    
    //eliminar detalle de materia prima
     public function actionEliminarmateria($id_grupo,$detalle, $sw)
    {                                
        $detalles = \app\models\ConfiguracionProducto::findOne($detalle);
        $detalles->delete();
        $this->redirect(["view_configuracion",'id_grupo' => $id_grupo, 'sw' => $sw]);        
    }
    //PERMITE ELIMINAR LOS ITEMS DE ANALISIS
     public function actionEliminar_analisis($id_grupo, $id_proceso, $sw)
    {                                
        $detalles = \app\models\ConfiguracionProductoProceso::findOne($id_proceso);
        $detalles->delete();
        $this->redirect(["view_analisis",'id_grupo' => $id_grupo, 'sw' => $sw]);        
    }
    
    /**
     * Finds the GrupoProducto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GrupoProducto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GrupoProducto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
