<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//models
use app\models\Items;
use app\models\ItemsSearch;
use app\models\UsuarioDetalle;
use app\models\MedidaMateriaPrima;
/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
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
     * Lists all Items models.
     * @return mixed
     */
     public function actionIndex()
    {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',8])->all()){
                $searchModel = new ItemsSearch();
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

    /**
     * Displays a single Items model.
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

    /**
     * Creates a new Items model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Items();

        if ($model->load(Yii::$app->request->post())){
            $buscar = Items::find()->where(['=','codigo', $model->codigo])->one();
            if($buscar){
                Yii::$app->getSession()->setFlash('error', 'Este CODIGO ya se encuentra registrado en los INSUMOS DE COMPRA.');  
            }else{
                $model->save();
                $model->user_name = Yii::$app->user->identity->username; 
                $model->save();
                if($model->codificar == 1){
                    $table = new \app\models\MateriaPrimas();
                    $table->codigo_materia_prima = $model->codigo;
                    $table->materia_prima = $model->descripcion;
                    $table->descripcion = $model->descripcion;
                    $table->id_medida = $model->id_medida;
                    $table->id_solicitud = $model->id_solicitud;
                    $table->valor_unidad = 0;
                    if($model->id_iva <> 0){
                        $table->aplica_iva = 1;
                    }
                    $table->porcentaje_iva = $model->iva->valor_iva;
                    $table->convertir_gramos = $model->convertir_gramo;
                    $table->fecha_entrada = date('Y-m-d');
                    $table->usuario_creador = Yii::$app->user->identity->username;
                    $table->aplica_inventario = $model->aplica_inventario;
                    $table->inventario_inicial = $model->inventario_inicial;
                    $table->codigo_ean = $model->codigo;
                    $table->save();
                    return $this->redirect(['index']);
                }else{
                    return $this->redirect(['index']);    
                }
            }
        }
 

       // $model->codificar = 1;
        return $this->render('create', [
            'model' => $model,
            'sw' => 0,
        ]);
    }

    /**
     * Updates an existing Items model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $table = \app\models\MateriaPrimas::find()->where(['=','codigo_materia_prima', $model->codigo])->one();
            if($table){
                if($model->codificar == 1){
                    $table->codigo_materia_prima = $model->codigo;
                    $table->materia_prima = $model->descripcion;
                    $table->descripcion = $model->descripcion;
                    $table->id_medida = $model->id_medida;
                    $table->id_solicitud = $model->id_solicitud;
                    $table->valor_unidad = 0;
                    if($model->id_iva <> 0){
                        $table->aplica_iva = 1;
                    }
                    $table->porcentaje_iva = $model->iva->valor_iva;
                    $table->convertir_gramos = $model->convertir_gramo;
                    $table->fecha_entrada = date('Y-m-d');
                    $table->usuario_creador = Yii::$app->user->identity->username;
                    $table->usuario_editado = Yii::$app->user->identity->username;
                    $table->aplica_inventario = $model->aplica_inventario;
                    $table->inventario_inicial = $model->inventario_inicial;
                    $table->codigo_ean = $model->codigo;
                    $table->save();
                    return $this->redirect(['index']);
                }
            }else{   
                Yii::$app->getSession()->setFlash('error', 'Este CODIGO NO se encuentra registrado en el proceso de MATERIAS PRIMAS.');  
                return $this->redirect(['index']);
            }    
        }    

        return $this->render('update', [
            'model' => $model,
            'sw' => 1,
        ]);
    }

    /**
     * Deletes an existing Items model.
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
            $this->redirect(["items/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["items/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
            $this->redirect(["items/index"]);
        }
    }
    
    /**
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
