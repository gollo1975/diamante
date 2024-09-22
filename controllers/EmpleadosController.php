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
use app\models\Empleados;
use app\models\UsuarioDetalle;

/**
 * EmpleadosController implements the CRUD actions for Empleados model.
 */
class EmpleadosController extends Controller
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
     * Lists all Empleados models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',115])->all()){
                $form = new \app\models\FiltroEmpleados();
                $documento = null;
                $empleado = null;
                $estado = null;
                $tipo_empleado = null;
                $desde = null;
                $hasta = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $empleado = Html::encode($form->empleado);
                        $estado = Html::encode($form->estado);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $tipo_empleado = Html::encode($form->tipo_empleado);
                        $table = Empleados::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'nombre_completo', $empleado])
                                ->andFilterWhere(['between', 'fecha_ingreso', $desde, $desde])
                                ->andFilterWhere(['=', 'tipo_empleado', $tipo_empleado])
                                ->andFilterWhere(['=', 'estado', $estado]);
                        $table = $table->orderBy('id_empleado DESC');
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
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelClientes($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                }else{
                  
                    $table = Empleados::find()->orderBy('id_empleado DESC');
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
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelEmpleados($tableexcel);
                    }
                } 
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
     * Displays a single Empleados model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token, 
        ]);
    }

    /**
     * Creates a new Empleados model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Empleados();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())){
            $buscar = Empleados::find()->where(['=','nit_cedula', $model->nit_cedula])->one();
            if(!$buscar){
                $model->save();
                $dv = Html::encode($_POST["dv"]);
                $model->user_name = Yii::$app->user->identity->username;
                $model->nombre_completo = strtoupper($model->nombre1. ' ' .$model->nombre2. ' '. $model->apellido1. ' '. $model->apellido2);
                $model->dv = $dv;
                $model->save();
                return $this->redirect(['index']); 
            }else{
                Yii::$app->getSession()->setFlash('error', 'Este documento YA esta creado con otro empleado. Valide la informacion'); 
                 
            }
        }

        return $this->render('create', [
            'model' => $model,
            'sw' => 0,
        ]);
    }

    /**
     * Updates an existing Empleados model.
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
            $dv = Html::encode($_POST["dv"]);
            $model->dv = $dv;
            $model->user_name_editado = Yii::$app->user->identity->username;
             $model->nombre_completo = strtoupper($model->nombre1. ' ' .$model->nombre2. ' '. $model->apellido1. ' '. $model->apellido2);
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'sw' => 1,
        ]);
    }

    /**
     * Deletes an existing Empleados model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Empleados model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Empleados the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Empleados::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
