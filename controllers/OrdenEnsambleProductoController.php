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
    public function actionIndex()
    {
        $searchModel = new OrdenEnsambleProductoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
    
    //BUSCAR EL MATERIA DE EMPAQUE
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
                    $registro = \app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id', $id])
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
