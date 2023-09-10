<?php

namespace app\controllers;

use yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use Codeception\Lib\HelperModule;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
//models
use app\models\PresupuestoEmpresarial;
use app\models\PresupuestoEmpresarialSearch;
use app\models\UsuarioDetalle;
use app\models\AreaEmpresa;
use app\models\PresupuestoMensual;
use app\models\PresupuestoMensualDetalle;
use app\models\Clientes;
use app\models\Pedidos;

/**
 * PresupuestoEmpresarialController implements the CRUD actions for PresupuestoEmpresarial model.
 */
class PresupuestoEmpresarialController extends Controller
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
     * Lists all PresupuestoEmpresarial models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',40])->all()){
                $searchModel = new PresupuestoEmpresarialSearch();
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
//PRESUPUESTO MENSUAL
    public function actionPresupuesto_mensual() {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 38])->all()) {
                $form = new \app\models\FiltroBusquedaCitas();
                $desde = null;
                $hasta = null;
                $presupuesto = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $presupuesto = Html::encode($form->presupuesto);
                        $table = PresupuestoMensual::find()
                                    ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])
                                    ->andFilterWhere(['=', 'id_presupuesto', $presupuesto]);
                        $table = $table->orderBy('fecha_inicio DESC');
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
                            $this->actionExcelconsultaPresupuesto($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                     $table = PresupuestoMensual::find()->orderBy('fecha_inicio DESC');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        $this->actionExcelconsultaPresupuesto($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('presupuesto_mensual', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }
    /**
     * Displays a single PresupuestoEmpresarial model.
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
    
    public function actionView_cliente($desde, $hasta, $id_presupuesto,$id, $cerrado) {
        $model = PresupuestoMensual::findOne($id);
        if($cerrado == 0){
            $this->BuscarClientePedido($desde, $hasta, $id_presupuesto, $id);
        }    
        $detalle = PresupuestoMensualDetalle::find()->where(['=','id_mensual', $id])->all();
        return $this->render('view_cliente', [
            'model' => $model,
            'detalle' => $detalle,
            'cerrado' => $cerrado,
            'desde' => $desde,
            'hasta' => $hasta,
            'id' => $id,
        ]);
    }
    protected function BuscarClientePedido($desde, $hasta,$id_presupuesto, $id) {
        
        if($id_presupuesto == 1){// //presupuesto comercial (numero 1)
            $mensual = PresupuestoMensual::findOne($id);
            $cliente = Clientes::find()->where(['=','estado_cliente', 0])->andWhere(['>','cupo_asignado', 0])
                                       ->andWhere(['>','presupuesto_comercial', 0])->orderBy('nombre_completo DESC')->all();
            if(count($cliente) > 0){
                foreach ($cliente as $clientes):
                    $pedido = Pedidos::find()->where(['between','fecha_proceso', $desde, $hasta])->andWhere(['=','id_cliente', $clientes->id_cliente])
                                            ->andWhere(['=','presupuesto', 1])->all();
                    if(count($pedido) > 0){
                        $suma = 0;
                        foreach ($pedido as $pedidos):
                           $suma += $pedidos->valor_presupuesto;
                        endforeach;   
                        $con = PresupuestoMensualDetalle::find()->where(['=','id_cliente', $clientes->id_cliente])->andWhere(['=','id_mensual', $id])->one();
                        if(!$con){
                            $table = new PresupuestoMensualDetalle();
                            $table->id_mensual = $id;
                            $table->id_cliente = $clientes->id_cliente;
                            $table->gasto_mensual = $suma;
                            $table->presupuesto_asignado = $clientes->presupuesto_comercial;
                            $table->save();
                        }     
                    }
                endforeach;
                $detalle = PresupuestoMensualDetalle::find()->where(['=','id_mensual', $id])->all();
                $con = 0; $total = 0;
                foreach ($detalle as $detalles):
                    $con += 1;
                    $total += $detalles->gasto_mensual;
                endforeach;
                $mensual->total_registro = $con;
                $mensual->valor_gastado = $total;
                $mensual->save();
            }else{
                Yii::$app->getSession()->setFlash('warning', 'No existen clientes que se les halla asignado presupuesto comercial o estan inactivos. Validar con el administrador.');
                 return $this->redirect(['presupuesto_mensual']);
            }    
        }else{
            Yii::$app->getSession()->setFlash('info', 'Este proceso esta en desarrollo.');
            return $this->redirect(['presupuesto-empresarial/presupuesto_mensual']);
        }    
    }
    /**
     * Creates a new PresupuestoEmpresarial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PresupuestoEmpresarial();
        $sw = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $model->user_name = Yii::$app->user->identity->username;
             $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'sw'=> $sw,
        ]);
    }
     public function actionCrear_fechas()
    {
        $model = new PresupuestoMensual();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $model->user_name = Yii::$app->user->identity->username;
             $model->save();
            return $this->redirect(['presupuesto_mensual']);
        }

        return $this->render('_form_crear_fecha', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PresupuestoEmpresarial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $sw = 1)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
            'sw' =>$sw,
        ]);
    }

    /**
     * Deletes an existing PresupuestoEmpresarial model.
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
            $this->redirect(["presupuesto-empresarial/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["presupuesto-empresarial/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
            $this->redirect(["presupuesto-empresarial/index"]);
        }
    }

    //autorizado
    public function actionAutorizado($desde, $hasta,$id, $cerrado, $id_presupuesto) {
        $mensual = PresupuestoMensual::findOne($id);
        if($mensual->autorizado == 0){
            $mensual->autorizado = 1;
            $mensual->save();
        }else{
            $mensual->autorizado = 0;
            $mensual->save();
        }
         $this->redirect(["presupuesto-empresarial/view_cliente",'desde'=>$desde, 'hasta' => $hasta, 'id' =>$id, 'cerrado'=>$cerrado, 'id_presupuesto' => $id_presupuesto]);
    }
    /**
     * Finds the PresupuestoEmpresarial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PresupuestoEmpresarial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PresupuestoEmpresarial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
