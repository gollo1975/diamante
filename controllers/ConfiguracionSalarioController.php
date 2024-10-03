<?php

namespace app\controllers;

use Yii;
use app\models\ConfiguracionSalario;
use app\models\ConfiguracionSalarioSearch;
use app\models\UsuarioDetalle;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfiguracionSalarioController implements the CRUD actions for ConfiguracionSalario model.
 */
class ConfiguracionSalarioController extends Controller
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
     * Lists all ConfiguracionSalario models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConfiguracionSalarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConfiguracionSalario model.
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
     * Creates a new ConfiguracionSalario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
      public function actionCreate()
    {
        $model = new \app\models\FormConfiguracionSalario();
        $confi = ConfiguracionSalario::find()->where(['=','estado', 1])->one();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {           
            if ($model->validate()) {
                $contrato = \app\models\Contratos::find()->where(['=','contrato_activo', 0])->all();
                $sw = 0;
                foreach ($contrato as $validar){
                    if($validar->ultimo_pago_nomina == $confi->fecha_cierre){
                        if($validar->ultima_pago_prima == $confi->fecha_cierre){
                            if($validar->ultima_pago_cesantia == $confi->fecha_cierre){
                                $sw = 1;
                                if($validar->salario < $model->salario_minimo_actual){
                                    $cambio_salario = new \app\models\CambioSalario();
                                    $cambio_salario->salario_anterior = $validar->salario;
                                    $cambio_salario->nuevo_salario = $model->salario_minimo_actual;
                                    $cambio_salario->fecha_aplicacion = $model->fecha_aplicacion;
                                    $cambio_salario->user_name = Yii::$app->user->identity->username;
                                    $cambio_salario->id_contrato = $validar->id_contrato;
                                    $cambio_salario->observacion = 'Actualizacion salario minimo';
                                    $cambio_salario->id_formato_contenido = 4;
                                    $cambio_salario->save(false);
                                    $validar->salario = $model->salario_minimo_actual;
                                    $validar->save(false);
                                }
                            }else{
                                 Yii::$app->getSession()->setFlash('error', 'Error en el proceso de cesantia. Los pagos de las cesantias se deben de cerrar  para todos los grupos al : ' . $confi->fecha_cierre.'.');
                            }
                        }else{
                           Yii::$app->getSession()->setFlash('error', 'Error en el proceso de prima. Los pagos de prima semestral se deben de cerrar para todos los grupos al : ' . $confi->fecha_cierre.'.');
                        }
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'Error en el proceso de la nomina. Los pagos de Nomina se deben de cerrar al ' . $confi->fecha_cierre.'.');
                    }
                }
                if($sw == 1){
                    //proceso que guarda el nuevo salario
                    $table = new ConfiguracionSalario();
                    $table->salario_minimo_actual = $model->salario_minimo_actual;
                    $table->auxilio_transporte_actual = $model->auxilio_transporte_actual;
                    $table->anio = $model->anio;
                    $table->estado = $model->estado;
                    $table->fecha_cierre = $model->fecha_cierre;
                    $table->fecha_aplicacion = $model->fecha_aplicacion;
                    $table->salario_minimo_anterior = $confi->salario_minimo_actual;
                    $table->auxilio_transporte_anterior = $confi->auxilio_transporte_actual;
                    $table->salario_incapacidad = round($model->salario_minimo_actual * $confi->porcentaje_incremento);
                    $table->porcentaje_incremento = $confi->porcentaje_incremento;
                    $table->user_name = Yii::$app->user->identity->username; 
                    $table->save(false);
                    $confi->estado = 0;
                    $confi->save(false);
                    return $this->redirect(["configuracion-salario/index"]);
                }
            } else {
                $model->getErrors();    
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ConfiguracionSalario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_salario]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ConfiguracionSalario model.
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
            $this->redirect(["configuracion-salario/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["configuracion-salario/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
            $this->redirect(["grupo-pago/index"]);
        }
    }

    /**
     * Finds the ConfiguracionSalario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConfiguracionSalario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfiguracionSalario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
