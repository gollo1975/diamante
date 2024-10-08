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

use app\models\ProgramacionNomina;
use app\models\UsuarioDetalle;
use app\models\PeriodoPagoNomina;



/**
 * ProgramacionNominaController implements the CRUD actions for ProgramacionNomina model.
 */
class ProgramacionNominaController extends Controller
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
     * Lists all ProgramacionNomina models.
     * @return mixed
     */
    public function actionIndex() {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 135])->all()) {
                $form = new \app\models\FormFiltroConsultaPeriodoPago();
                $id_grupo_pago = null;
                $id_periodo_pago = null;
                $id_tipo_nomina = null;
                $estado_periodo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $id_periodo_pago = Html::encode($form->id_periodo_pago);
                        $id_tipo_nomina = Html::encode($form->id_tipo_nomina);
                        $estado_periodo = Html::encode($form->estado_periodo);
                        $table = PeriodoPagoNomina::find()
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['=', 'id_periodo_pago', $id_periodo_pago])
                                ->andFilterWhere(['=', 'id_tipo_nomina', $id_tipo_nomina])
                                ->andFilterWhere(['=', 'estado_periodo', $estado_periodo]);
                        $table = $table->orderBy('id_periodo_pago_nomina DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_periodo_pago_nomina DESC']);
                            $this->actionExcelConsultaPeriodo($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = PeriodoPagoNomina::find()
                            ->where(['=', 'estado_periodo', 0])
                            ->orderBy('id_periodo_pago_nomina DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        //$table = $table->all();
                        $this->actionExcelConsultaPeriodo($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
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
     * Displays a single ProgramacionNomina model.
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
    //PERMITE EDITAR EL PERIODO
     public function actionEditar($id) {
        $validar = PeriodoPagoNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->one();
        $model = new PeriodoPagoNomina();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if (ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->all() or $validar->estado_periodo == 1) {
            Yii::$app->getSession()->setFlash('warning', 'No se puede modificar la información, tiene detalles asociados');
        } else {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                       $periodo = \app\models\PeriodoPago::find()->where(['=', 'id_periodo_pago', $model->id_periodo_pago])->one();
                        if ($periodo->dias == $model->dias_periodo) {
                            $table = PeriodoPagoNomina::find()->where(['id_periodo_pago_nomina' => $id])->one();
                            $table->id_grupo_pago = $model->id_grupo_pago;
                            $table->id_periodo_pago = $model->id_periodo_pago;
                            $table->id_tipo_nomina = $model->id_tipo_nomina;
                            $table->fecha_desde = $model->fecha_desde;
                            $table->fecha_hasta = $model->fecha_hasta;
                            $table->fecha_real_corte = $table->fecha_hasta;
                            $table->dias_periodo = $model->dias_periodo;
                            if ($table->save(false)) {
                                $this->redirect(["programacion-nomina/index"]);
                            }
                        } else {
                            Yii::$app->getSession()->setFlash('error', 'Debe de ingresar los dias que corresponda a cada periodo de pago. Favor validar el periodo');
                        }
                    
                } else {
                    $model->getErrors();
                }
            }
        }
        if (Yii::$app->request->get("id")) {
            $table = PeriodoPagoNomina::find()->where(['id_periodo_pago_nomina' => $id])->one();
            if ($table) {
                $model->id_tipo_nomina = $table->id_tipo_nomina;
                $model->id_periodo_pago = $table->id_periodo_pago;
                $model->id_grupo_pago = $table->id_grupo_pago;
                $model->dias_periodo = $table->dias_periodo;
                $model->fecha_desde = $table->fecha_desde;
                $model->fecha_hasta = $table->fecha_hasta;
            } else {
                return $this->redirect(["programacion-nomina/index"]);
            }
        } else {
            return $this->redirect(["programacion-nomina/index"]);
        }
       return $this->render("form", ["model" => $model]);
    }

    //PERMITE CREAR UN PERIODO DE NOMINA MANUAL
    public function actionNuevo() {
        $model = new PeriodoPagoNomina();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $Consulta = \app\models\GrupoPago::findOne($model->id_grupo_pago);
                $Pago = \app\models\PeriodoPago::findOne($model->id_periodo_pago);
                if($Consulta->dias_pago == $model->dias_periodo){
                    if($Pago->dias == $model->dias_periodo){
                        $table = new PeriodoPagoNomina();
                        $table->id_grupo_pago = $model->id_grupo_pago;
                        $table->id_periodo_pago = $model->id_periodo_pago;
                        $table->id_tipo_nomina = $model->id_tipo_nomina;
                        $table->fecha_desde = $model->fecha_desde;
                        $table->fecha_hasta = $model->fecha_hasta;
                        $table->fecha_real_corte = $table->fecha_hasta;
                        $table->estado_periodo = 0;
                        $table->dias_periodo = $model->dias_periodo;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->save(false);
                        $this->redirect(["programacion-nomina/index"]);
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'El periodo de pago que selecciono No coincide con el Grupo de pago. Favor validar el periodo');
                    }    
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Los dias del periodo deden de ser iguales a los dias de pago. Favor validar el periodo');
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->render('form', ['model' => $model]);
    }
    
    /**
     * Creates a new ProgramacionNomina model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProgramacionNomina();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_programacion]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProgramacionNomina model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_programacion]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProgramacionNomina model.
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
     * Finds the ProgramacionNomina model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProgramacionNomina the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProgramacionNomina::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
