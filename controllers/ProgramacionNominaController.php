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
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 140])->all()) {
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

    //COMPROBANTES DE NOMINAS
    public function actionSearch_comprobante_nomina() {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 150])->all()) {
                $form = new \app\models\FormFiltroComprobantePagoNomina();
                $id_grupo_pago = null;
                $id_tipo_nomina = null;
                $id_empleado = null;
                $cedula_empleado = null;
                $fecha_desde = null;
                $fecha_hasta = null;
                $anio = null;
          
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $id_tipo_nomina = Html::encode($form->id_tipo_nomina);
                        $id_empleado = Html::encode($form->id_empleado);
                        $cedula_empleado = Html::encode($form->cedula_empleado);
                        $fecha_desde = Html::encode($form->fecha_desde);
                        $fecha_hasta = Html::encode($form->fecha_hasta);
                        $anio = Html::encode($form->anio);
                        $table = ProgramacionNomina::find()
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['=', 'id_tipo_nomina', $id_tipo_nomina])
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])
                                ->andFilterWhere(['=', 'cedula_empleado', $cedula_empleado])
                                ->andFilterWhere(['=', 'anio', $anio])
                                ->andFilterWhere(['between', 'fecha_desde', $fecha_desde, $fecha_hasta]);
                        $table = $table->orderBy('id_programacion DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $modelo = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_programacion DESC']);
                            $this->actionExcelconsultaPago($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = ProgramacionNomina::find()
                             ->orderBy('id_programacion DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                 
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $modelo = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        //$table = $table->all();
                        $this->actionExcelconsultaPago($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_pago_nomina', [
                            'modelo' => $modelo,
                            'form' => $form,
                            'pagination' => $pages, 'nombre_empleado' => $id_empleado, 
                            'fecha_inicio' => $fecha_desde, 'fecha_corte' => $fecha_hasta,
                            'grupo_pago' => $id_grupo_pago,'tipo_nomina' => $id_tipo_nomina,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }
    
    //COMPROBANTES DE NOMINAS
    public function actionSearch_comprobante_cesantias() {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 151])->all()) {
                $form = new \app\models\FormFiltroComprobantePagoNomina();
                $id_grupo_pago = null;
                $id_empleado = null;
                $cedula_empleado = null;
                $anio = null;
          
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $id_empleado = Html::encode($form->id_empleado);
                        $cedula_empleado = Html::encode($form->cedula_empleado);
                        $anio = Html::encode($form->anio);
                        $table = \app\models\InteresesCesantia::find()
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])
                                ->andFilterWhere(['=', 'documento', $cedula_empleado])
                                ->andFilterWhere(['=', 'anio', $anio]);
                        $table = $table->orderBy('id_interes DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $modelo = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_interes DESC']);
                            $this->actionExcelconsultaPagoIntereses($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = \app\models\InteresesCesantia::find()
                             ->orderBy('id_interes DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                 
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $modelo = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        //$table = $table->all();
                        $this->actionExcelconsultaPagoIntereses($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_pago_cesantias', [
                            'modelo' => $modelo,
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
    public function actionView($id, $id_grupo_pago, $fecha_desde, $fecha_hasta) {
        $model = PeriodoPagoNomina::findOne($id);
        $intereses = \app\models\InteresesCesantia::find()->where(['=','id_periodo_pago_nomina', $id])->orderBy('id_programacion ASC')->all();
        $detalles = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->orderBy('id_programacion ASC')->all();
        $incapacidad = \app\models\Incapacidad::find()->where(['=', 'id_grupo_pago', $id_grupo_pago])
                        ->andWhere(['<=', 'fecha_inicio', $fecha_hasta])
                        ->andWhere(['>=', 'fecha_final', $fecha_desde])
                        ->orderBy('identificacion ASC')->all();
        $licencia = \app\models\Licencia::find()->where(['=', 'id_grupo_pago', $id_grupo_pago])
                        ->andWhere(['<=', 'fecha_desde', $fecha_hasta])
                        ->andWhere(['>=', 'fecha_hasta', $fecha_desde])
                        ->orderBy('identificacion ASC')->all();
        $novedad_tiempo = \app\models\NovedadTiempoExtra::find()->where(['=', 'id_periodo_pago_nomina', $id])->orderBy('id_empleado ASC')->all();
        $credito_empleado = \app\models\Credito::find()->where(['<=', 'fecha_inicio', $fecha_hasta])
                        ->andWhere(['=', 'estado_credito', 0])
                        ->andWhere(['=', 'estado_periodo', 0])
                        ->andWhere(['>', 'saldo_credito', 0])
                        ->andWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                        ->orderBy('id_empleado DESC')->all();
        return $this->render('view', [
                    'detalles' => $detalles,
                    'model' => $model,
                    'incapacidad' => $incapacidad,
                    'licencia' => $licencia,
                    'novedad_tiempo' => $novedad_tiempo,
                    'credito_empleado' => $credito_empleado,
                    'intereses' => $intereses,
        ]);
    }
    
    //VISUALIZA EL DETALLE DE LA COLILLA
    public function actionDetallepagonomina($id_programacion)
    {
        $model = ProgramacionNomina::findOne($id_programacion);
        return $this->render('detalle_pago_nomina', [
                    'id_programacion' => $id_programacion,
                    'model' => $model,        
        ]); 
    }
    
    //PERMITE CARGAR LOS EMPLEADOS PARA NOMINA, PRIMAS Y CESANTIAS
    public function actionCargar($id, $id_grupo_pago, $fecha_desde, $fecha_hasta, $tipo_nomina) {
        $model = PeriodoPagoNomina::findOne($id);
        $registros = 0;
        $configuracion_salario = \app\models\ConfiguracionSalario::find()->where(['=', 'estado', 1])->one();
        if($tipo_nomina == 1){
            $registros = \app\models\Contratos::find()
                    ->where(['=', 'id_grupo_pago', $model->id_grupo_pago])
                    ->andWhere(['<=', 'fecha_inicio', $model->fecha_hasta])
                    ->andWhere(['>=', 'fecha_final', $model->fecha_desde])
                    ->andWhere(['<','ultimo_pago_nomina', $model->fecha_hasta])
                    ->all();
        }else{
            if($tipo_nomina == 2){
                $registros = \app\models\Contratos::find()
                        ->where(['=', 'id_grupo_pago', $model->id_grupo_pago])
                        ->andWhere(['<=', 'fecha_inicio', $model->fecha_hasta])
                        ->andWhere(['=', 'contrato_activo', 0])
                        ->andWhere(['<','ultima_pago_prima', $model->fecha_hasta])
                        ->all();
            }else{
                if($tipo_nomina == 3){
                    $registros = \app\models\Contratos::find()
                        ->where(['=', 'id_grupo_pago', $model->id_grupo_pago])
                        ->andWhere(['<=', 'fecha_inicio', $model->fecha_hasta])
                        ->andWhere(['=', 'contrato_activo', 0])
                        ->andWhere(['<','ultima_pago_cesantia', $model->fecha_hasta])
                        ->all();
                }
            }
        }    
        $registroscargados = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->all();
        $cont = 0;
        if($registros == 0){
            Yii::$app->getSession()->setFlash('warning', 'Este grupo de pago a la fecha no tiene empleados con contratos activos!');
        }else{
            foreach ($registros as $val) {
                if (!ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->andWhere(['=', 'id_contrato', $val->id_contrato])->one()) {
                    $anioCesantia = date('Y', strtotime($model->fecha_hasta));
                    
                    $table = new ProgramacionNomina();
                    $table->id_grupo_pago = $model->id_grupo_pago;
                    $table->id_periodo_pago_nomina = $id;
                    $table->id_tipo_nomina = $tipo_nomina;
                    $table->id_contrato = $val->id_contrato;
                    $table->id_empleado = $val->id_empleado;
                    $table->cedula_empleado = $val->empleado->nit_cedula;
                    $table->salario_contrato = $val->salario;
                    $table->fecha_inicio_contrato = $val->fecha_inicio;
                    $table->anio = $anioCesantia;
                    if($val->tiempo->abreviatura == 'MT'){
                        $table->salario_medio_tiempo = $val->salario;
                    }
                    if ($val->contrato_activo == 1) {
                        $table->fecha_final_contrato = $val->fecha_final;
                    } 
                    
                    if($val->id_tipo_contrato == 2 && $val->fecha_final >= $fecha_desde && $val->fecha_final <= $fecha_hasta){
                      $table->fecha_final_contrato = $val->fecha_final;     
                    }
                    if($tipo_nomina == 1 ){
                       /* $vacacion = \app\models\Vacaciones::find()->where(['=','documento', $val->nit_cedula])
                                                                  ->andWhere(['>=','fecha_desde_disfrute', $fecha_desde])
                                                                  ->orderBy('id_vacacion ASC')->one();
                        if ($vacacion){
                             $table->fecha_inicio_vacacion = $vacacion->fecha_desde_disfrute;
                             $table->fecha_final_vacacion = $vacacion->fecha_hasta_disfrute;
                        }
                        $vacacion = \app\models\Vacaciones::find()->where(['=','documento', $val->nit_cedula])
                                                                  ->andWhere(['<=','fecha_hasta_disfrute', $fecha_hasta])
                                                                  ->andWhere(['>','fecha_hasta_disfrute', $fecha_desde])
                                                                  ->orderBy('id_vacacion ASC')->one();
                        if ($vacacion){
                             $table->fecha_inicio_vacacion = $vacacion->fecha_desde_disfrute;
                             $table->fecha_final_vacacion = $vacacion->fecha_hasta_disfrute;
                        }*/
                        
                    }
                    $table->fecha_desde = $model->fecha_desde;
                    $table->fecha_hasta = $model->fecha_hasta;
                    $table->fecha_ultima_prima= $val->ultima_pago_prima;
                    $table->fecha_ultima_cesantia = $val->ultima_pago_cesantia;
                    $table->fecha_ultima_vacacion = $val->ultima_pago_vacacion;
                    $table->fecha_real_corte = $model->fecha_real_corte;
                    $table->dias_pago = $model->dias_periodo;
                    $table->user_name = Yii::$app->user->identity->username;
                    $tiempo = \app\models\TiempoServicio::find()->where(['=', 'id_tiempo', $val->id_tiempo])->one();
                    $table->factor_dia = $tiempo->horas_dia;
                    if ($table->factor_dia < $tiempo->horas_dia) {
                        $table->salario_medio_tiempo = $configuracion_salario->salario_minimo_actual;
                    }
                    $table->save(false);
                    $cont = $cont + 1;
                    $model->cantidad_empleado = $cont;

                }
            }       $model->save(false);
        }    
       if ($registros == 0) {
            $this->redirect(["programacion-nomina/view", 'id' => $id,
                'id_grupo_pago' => $id_grupo_pago,
                'fecha_desde' => $fecha_desde,
                'fecha_hasta' => $fecha_hasta,
            ]);
        } else {

            $this->redirect(["programacion-nomina/view", 'id' => $id,
                'id_grupo_pago' => $id_grupo_pago,
                'fecha_desde' => $fecha_desde,
                'fecha_hasta' => $fecha_hasta,
            ]);
        }
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
   
//* SEGUNDO PROCESO DEL BOTON GENERAR DESCUENTOS*/
    public function actionGenerar_devengados($id, $id_grupo_pago, $fecha_desde, $fecha_hasta, $tipo_nomina)
    {
        if($tipo_nomina == 1)//PROCESA REGISTROS DE NOMINA
        { 
            $listado_nomina = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->all();
            $salarios = \app\models\ConceptoSalarios::find()->where(['=', 'inicio_nomina', 1])->one();
            $empresa = \app\models\MatriculaEmpresa::findOne(1);
            $salario_transporte = \app\models\ConceptoSalarios::find()->where(['=', 'auxilio_transporte', 1])->one();
            $configuracion_salarios = \app\models\ConfiguracionSalario::find()->where(['=', 'estado', 1])->one();
            $auxilio = $configuracion_salarios->auxilio_transporte_actual;
            $jornada = $empresa->horas_jornada_laboral;
            $codigo_salario = $salarios->codigo_salario;
            $total_dias = 0;        
            $codigo_transporte = $salario_transporte->codigo_salario;
            foreach ($listado_nomina as $val):
                $total_dias = $this->salario($val, $codigo_salario, $id_grupo_pago, $jornada);
                $this->Auxiliotransporte($val, $codigo_transporte, $total_dias, $auxilio, $fecha_desde, $fecha_hasta, $id_grupo_pago);
            endforeach;
            
            //PROCESO QUE ADICIONA CONCEPTOS POR FECHA
            $adicion_fecha = \app\models\PagoAdicionalPermanente::find()->where(['=', 'fecha_corte', $fecha_hasta])
                    ->andWhere(['=', 'estado_registro', 0])
                    ->andWhere(['=', 'estado_periodo', 0])
                    ->andWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                    ->andWhere(['=','aplicar_prima', 0])
                    ->all();
            if (count($adicion_fecha) > 0) {
                foreach ($adicion_fecha as $adicionfecha) {
                    $this->Moduloadicionfecha($fecha_desde, $fecha_hasta, $adicionfecha, $id, $id_grupo_pago);
                }
            }
            
            //PROCESO QUE SUBE LAS PAGOS PERMANENTES
            $grupo_pago = \app\models\GrupoPago::findone($id_grupo_pago);
            $adicion_permanente = \app\models\PagoAdicionalPermanente::find()->where(['=', 'permanente', 1])
                            ->andWhere(['=', 'estado_registro', 0])
                            ->andWhere(['=', 'estado_periodo', 0])
                            ->andWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                            ->all();
            if(count($adicion_permanente) > 0){
                foreach ($adicion_permanente as $adicionpermanente) {
                   $this->Moduloadicionpermanente($fecha_desde, $fecha_hasta, $adicionpermanente, $id, $grupo_pago);
                }
            }
            
            //PROCESO QUE PERMITE SUBIR HORAS EXTRAS
            $novedad_tiempo_extra = \app\models\NovedadTiempoExtra::find()->where(['=', 'id_periodo_pago_nomina', $id])->andWhere(['>', 'nro_horas', 0])->orderBy('id_empleado DESC')->all();
            if (count($novedad_tiempo_extra) > 0) {
                foreach ($novedad_tiempo_extra as $tiempo_extra) {
                   $this->Novedadtiempoextra($tiempo_extra, $id, $fecha_desde, $fecha_hasta, $id_grupo_pago);
                }
            }
            
            // codigo que valida las incapacidades del mismo periodo
            $incapacidad = \app\models\Incapacidad::find()->where(['=', 'id_grupo_pago', $id_grupo_pago])
                    ->andWhere(['<=', 'fecha_inicio', $fecha_hasta])
                    ->andWhere(['>=', 'fecha_final', $fecha_desde])
                    ->all();
            if (count($incapacidad) > 0) {
                foreach ($incapacidad as $valor_incapacidad) {
                  $this->ModuloIncapacidad($fecha_desde, $fecha_hasta, $valor_incapacidad, $id, $id_grupo_pago);
                }
            }
            
            //PROCESO PARA INGRESAR LOS CREDITOS
               $creditosempleado = \app\models\Credito::find()->where(['<=', 'fecha_inicio', $fecha_hasta])
                                ->andWhere(['=', 'estado_credito', 0])
                                ->andWhere(['=', 'estado_periodo', 0])
                                ->andWhere(['>', 'saldo_credito', 0])
                                ->andWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andWhere(['=','id_tipo_pago', $tipo_nomina])
                                ->orderBy('id_empleado DESC')->all();
                if (count($creditosempleado) > 0){
                    foreach ($creditosempleado as $credito) {
                       $this->Modulocredito($fecha_desde, $fecha_hasta, $credito, $id, $id_grupo_pago);
                    }
                }
                //PROCESO QUE CARGA LAS LICENCIAS
                $licencias = \app\models\Licencia::find()->where(['=', 'id_grupo_pago', $id_grupo_pago])
                        ->andWhere(['<=', 'fecha_desde', $fecha_hasta])
                        ->andWhere(['>=', 'fecha_hasta', $fecha_desde])
                        ->all();
                $contLicencia = count($licencias);
                if (count($licencias) > 0) {
                    foreach ($licencias as $valor_licencia) {
                       $this->ModuloLicencias($fecha_desde, $fecha_hasta, $valor_licencia, $id, $id_grupo_pago);
                    }
                }
                //PROCESO QUE CIERRA LOS DEVENGADOS Y GENERA EL SEGUNDO PROCESO
                $nomina = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
                foreach ($nomina as $valores):
                    $valores->estado_generado = 1;
                    $valores->save();
                endforeach;

        }else{ 
            if($tipo_nomina == 2){ //COMIENZA EL PROCESO DE PRIMAS SEMESTRALES
                $listado_nomina = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
                $periodo = PeriodoPagoNomina::findOne($id);
                $configuracion_prima = \app\models\ConfiguracionPrestaciones::findOne(1);
                $year = 0;
                $year = ($year==NULL)? date('Y'):$year;
                if (($year%4 == 0 && $year%100 != 0) || $year%400 == 0 ){ //PROCESO QUE VALIDE SI EL AÑO ES VISCIESTO
                       $ano = 1;
                }else{
                       $ano = 2;
                }  

                foreach ($listado_nomina as $key => $prima) {

                   //inicializar el contrato
                    $ibc_anterior = 0; $dias_promedio = 0; $auxilio = 0; $salario_promedio = 0; $pago_prima = 0; $adicion_salario = 0;
                    $contrato = \app\models\Contratos::findOne($prima->id_contrato);
                    $total_dias = $this->CrearPrimaSemestral($prima, $ano);

                    //DIAS FALTANTES
                    $dias_faltante = 0;
                    $hasta = strtotime($fecha_hasta);
                    $fecha_corte_nomina = strtotime($contrato->ultimo_pago_nomina);
                    $diferencia_segundos = $hasta - $fecha_corte_nomina;
                    $dias_faltante = $diferencia_segundos / 86400;

                    //BUSCAR EL ACUMULADO DE PRESTACIONES
                    $vector_nomina = ProgramacionNomina::find()->where(['=','id_empleado', $prima->id_empleado])
                                                               ->andWhere(['>=','fecha_desde', $prima->fecha_desde])
                                                               ->andWhere(['=','id_tipo_nomina', 1])->all();
                    $total_ibc = 0;
                    foreach ($vector_nomina as $val) {
                        $total_ibc = $total_ibc + $val->ibc_prestacional; 
                    }

                    //se adiciona segun la fecha de corte de prima
                    $adicion_salario = ($contrato->salario / 30) * $dias_faltante;

                    //trae acumulados
                    if ($contrato->ibp_prima_inicial > 0){
                        
                        $salario_promedio = round(($total_ibc + $contrato->ibp_prima_inicial + $contrato->ibp_recargo_nocturno + $adicion_salario) / $total_dias) * 30;
                    }else{
                      $salario_promedio = round((($total_ibc + $adicion_salario ) / $total_dias) * 30);
                    }

                    // valide si tiene aplica auxilio de transporte
                    $vecto_auxilio = \app\models\ConfiguracionSalario::find()->where(['=','estado', 1])->one();
                    if($contrato->aplica_auxilio_transporte == 1){
                        if($contrato->id_tipo_salario == 1){
                            $pago_prima = round((($contrato->salario + $vecto_auxilio->auxilio_transporte_actual) * $total_dias)/360);
                        }else {
                            $pago_prima = round((($salario_promedio + $vecto_auxilio->auxilio_transporte_actual) * $total_dias)/360); 
                        }

                    }else{
                        if($contrato->id_tipo_salario == 1){
                            $pago_prima = round((($contrato->salario) * $total_dias)/360);
                        }else {
                           $pago_prima = round((($salario_promedio) * $total_dias)/360); 
                        }
                    }
                    //creacion del registro
                    $concepto_salario = \app\models\ConceptoSalarios::find()->where(['=','concepto_prima', 1])->one();
                    $detalle_nomina = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $prima->id_programacion])
                                                                             ->andWhere(['=', 'codigo_salario', $concepto_salario->codigo_salario])
                                                                             ->all();
                    if(!$detalle_nomina){
                        $table = new \app\models\ProgramacionNominaDetalle();
                        $table->id_programacion = $prima->id_programacion;
                        $table->codigo_salario = $concepto_salario->codigo_salario;
                        $table->dias_reales = $total_dias;
                        $table->fecha_desde = $fecha_desde;
                        $table->fecha_hasta = $fecha_hasta;
                        $table->vlr_devengado = $pago_prima;
                        $table->id_periodo_pago_nomina = $id;
                        $table->id_grupo_pago = $prima->id_grupo_pago;
                        $table->save(false);
                        // actualiza la tabla de programacion de nomina
                        $prima->dias_pago = $periodo->dias_periodo;
                        $prima->dia_real_pagado = $total_dias;
                        $prima->total_devengado = $pago_prima;
                        $prima->salario_promedio = $salario_promedio;
                        $prima->dias_ausentes = 0;
                        $prima->estado_generado = 1;
                        $prima->save(false);
                        //actualizar tabla contrato
                        $contrato->ibp_prima_inicial = 0;
                        $contrato->save();
                    }

                }
            }else{   //COMIENZA EL PROCESO DE CESANTIAS  
                $listado_nomina = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
                $periodo = PeriodoPagoNomina::findOne($id);
                $configuracion_prima = \app\models\ConfiguracionPrestaciones::findOne(2);
                $year = 0;
                $year = ($year==NULL)? date('Y'):$year;
                if (($year%4 == 0 && $year%100 != 0) || $year%400 == 0 ){ //PROCESO QUE VALIDE SI EL AÑO ES VISCIESTO
                       $ano = 1;
                }else{
                       $ano = 2;
                }  

                foreach ($listado_nomina as $key => $cesantias) {

                   //inicializar el contrato
                    $ibc_anterior = 0; $dias_reales = 0; $auxilio = 0; $salario_promedio = 0; $pago_cesantia = 0; $total_dia_ausente = 0;
                    $contrato = \app\models\Contratos::findOne($cesantias->id_contrato);
                    $total_dias = $this->CrearCesantias($cesantias, $ano);

                    //BUSCAR EL ACUMULADO DE PRESTACIONES
                    $vector_nomina = ProgramacionNomina::find()->where(['=','id_empleado', $cesantias->id_empleado])
                                                               ->andWhere(['>=','fecha_desde', $cesantias->fecha_desde])
                                                               ->andWhere(['=','id_tipo_nomina', 1])->all();
                    
                    //BUSCA LOS DIAS DE AUSENCIA
                    $dias_ausentes = \app\models\Licencia::find()->where(['=','id_empleado', $cesantias->id_empleado])->andWhere(['>=','fecha_desde', $fecha_desde])->all();
                                                                
                    foreach ($dias_ausentes as $key => $val) {
                        if($val->codigo_licencia == 1){
                            $total_dia_ausente += $val->dias_licencia;
                        }else{
                            if($val->codigo_licencia == 2){
                               $total_dia_ausente += $val->dias_licencia; 
                            }
                        }
                        
                    }
                    
                    //INICIALIZA EL PAGO DE CESANTIAS
                    $total_ibc = 0;
                    foreach ($vector_nomina as $valores) {
                        
                        $total_ibc += $valores->ibc_prestacional;
                    }
                    //VALIDAR SE APLICA DIAS DE AUSENCIA EN LAS CESANTIAS
                    $aplica_ausencia = \app\models\ConfiguracionPrestaciones::findOne(2);
                    if($aplica_ausencia->aplicar_ausentismo == 1){
                        $dias_reales = $total_dias - $total_dia_ausente;
                    }else{
                        $dias_reales = $total_dias;
                    }
                    
                    
                    ///BUSCA ACUMULADO DE CESANTIAS ANTERIORES
                    if ($contrato->ibp_cesantia_inicial > 0){
                        $salario_promedio = round((($total_ibc + $contrato->ibp_cesantia_inicial + $contrato->ibp_recargo_nocturno) / $total_dias) * 30);
                    }else{
                       $salario_promedio = round(($total_ibc / $total_dias) * 30);
                    }
                    
                    // valide si aplica auxilio de transporte
                    $vecto_auxilio = \app\models\ConfiguracionSalario::find()->where(['=','estado', 1])->one();
                    if($contrato->aplica_auxilio_transporte == 1){
                        if($contrato->id_tipo_salario == 1){
                            $pago_cesantia = round((($contrato->salario + $vecto_auxilio->auxilio_transporte_actual) * $dias_reales)/360);
                        }else {
                            $pago_cesantia = round((($salario_promedio + $vecto_auxilio->auxilio_transporte_actual) * $dias_reales)/360); 
                        }

                    }else{
                        if($contrato->id_tipo_salario == 1){
                            $pago_cesantia = round((($contrato->salario) * $dias_reales)/360);
                        }else {
                           $pago_cesantia = round((($salario_promedio) * $dias_reales)/360); 
                        }
                    }
                    
                    //GRABA LA INFORMACION DE LAS CESANTIAS
                    $concepto_salario = \app\models\ConceptoSalarios::find()->where(['=','concepto_cesantias', 1])->one();
                    $detalle_nomina = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $cesantias->id_programacion])
                                                                             ->andWhere(['=', 'codigo_salario', $concepto_salario->codigo_salario])
                                                                             ->all();
                    if(!$detalle_nomina){
                        $table = new \app\models\ProgramacionNominaDetalle();
                        $table->id_programacion = $cesantias->id_programacion;
                        $table->codigo_salario = $concepto_salario->codigo_salario;
                        $table->dias_reales = $dias_reales;
                        $table->dias = $total_dias;
                        $table->fecha_desde = $fecha_desde;
                        $table->fecha_hasta = $fecha_hasta;
                        $table->vlr_devengado = $pago_cesantia;
                        $table->id_periodo_pago_nomina = $id;
                        $table->id_grupo_pago = $cesantias->id_grupo_pago;
                        $table->save(false);
                        // actualiza la tabla de programacion de nomina
                        $cesantias->dias_pago = $total_dias;
                        $cesantias->dia_real_pagado = $dias_reales;
                        $cesantias->total_devengado = $pago_cesantia;
                        $cesantias->salario_promedio = $salario_promedio;
                        $cesantias->dias_ausentes = $total_dia_ausente;
                        $cesantias->estado_generado = 1;
                        $cesantias->save(false);
                        //actualizar tabla contrato
                        $contrato->ibp_cesantia_inicial = 0;
                        $contrato->ibp_recargo_nocturno = 0;
                        $contrato->save();
                        $this->GenerarIntereses($cesantias, $salario_promedio, $pago_cesantia, $fecha_desde, $fecha_hasta, $dias_reales);
                    }
                    
                }//termina el para de cesantias    
            }
        }
        return $this->redirect(['programacion-nomina/view', 'id' => $id , 'id_grupo_pago' => $id_grupo_pago, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta ]);
        
    }
    protected function GenerarIntereses($cesantias, $salario_promedio, $pago_cesantia, $fecha_desde, $fecha_hasta, $dias_reales) {
        $concepto_salario = \app\models\ConceptoSalarios::find()->where(['=','intereses', 1])->one();
        $porcentaje = 0;
        $porcentaje = ($dias_reales * 12) / 360;
        $anioIntereses = date('Y', strtotime($fecha_hasta));
        $table = new \app\models\InteresesCesantia();
        $table->id_programacion = $cesantias->id_programacion;
        $table->id_grupo_pago = $cesantias->id_grupo_pago;
        $table->id_periodo_pago_nomina = $cesantias->id_periodo_pago_nomina;
        $table->id_tipo_nomina = 6;
        $table->id_contrato = $cesantias->id_contrato;
        $table->id_empleado = $cesantias->id_empleado;
        $table->documento = $cesantias->cedula_empleado;
        $table->inicio_contrato = $cesantias->fecha_inicio_contrato;
        $table->salario_promedio = $salario_promedio;
        $table->valor_cesantias = $pago_cesantia;
        $table->fecha_inicio = $fecha_desde;
        $table->fecha_corte = $fecha_hasta;
        $table->anio = $anioIntereses;
        $table->dias_generados = $dias_reales;
        $table->valor_intereses = round(($pago_cesantia * $porcentaje)/100);
        $table->porcentaje = $porcentaje;
        $table->codigo_salario = $concepto_salario->codigo_salario;
        $table->user_name = Yii::$app->user->identity->username;
        $table->save(false);
    }
    
    //PROCEESO QUE GENERA LOS DIAS DE LAS CESANTIS
     protected function CrearPrimaSemestral($prima, $ano)
    {
        $mesInicio = 0;
        $anioTerminacion = 0;
        $mesTerminacion = 0;
        $anioInicio = 0;
        $diaTerminacion = 0;
        $diaInicio = 0;
        if($prima->fecha_inicio_contrato <= $prima->fecha_ultima_prima){
            $fecha = date($prima->fecha_desde);
        }else{
           $fecha = date($prima->fecha_inicio_contrato);
        } 
       
        $fecha_inicio_dias = strtotime('0 day', strtotime($fecha));
        $fecha_inicio_dias = date('Y-m-d', $fecha_inicio_dias);
        //codigo de fechas
        $fecha_inicio = $fecha_inicio_dias;
        $fecha_termino = $prima->fecha_hasta;
        $diaTerminacion = substr($fecha_termino, 8, 8);
        $mesTerminacion = substr($fecha_termino, 5, 2);
        $anioTerminacion = substr($fecha_termino, 0, 4);
        $diaInicio = substr($fecha_inicio, 8, 8);
        $mesInicio = substr($fecha_inicio, 5, 2);
        $anioInicio = substr($fecha_inicio, 0, 4);
        
        $febrero = 0;
        $mes = $mesInicio-1;
        if($mes == 2){
            if($ano == 1){
              $febrero = 29;
            }else{
              $febrero = 28;
            }
        }else if($mes <= 7){
            if($mes==0){
             $febrero = 31;
            }else if($mes%2==0){
                 $febrero = 30;
                }else{
                   $febrero = 31;
                }
        }else if($mes > 7){
              if($mes%2==0){
                  $febrero = 31;
              }else{
                  $febrero = 30;
              }
        }
        if(($anioInicio > $anioTerminacion) || ($anioInicio == $anioTerminacion && $mesInicio > $mesTerminacion) || 
            ($anioInicio == $anioTerminacion && $mesInicio == $mesTerminacion && $diaInicio > $diaTerminacion)){
                //mensaje
        }else{
            if($mesInicio <= $mesTerminacion){
                $anios = $anioTerminacion - $anioInicio;
                if($diaInicio <= $diaTerminacion){
                    $meses = $mesTerminacion - $mesInicio;
                    $dies = $diaTerminacion - $diaInicio;
                }else{
                    if($mesTerminacion == $mesInicio){
                       $anios = $anios - 1;
                    }
                    $meses = ($mesTerminacion - $mesInicio - 1 + 12) % 12;
                    $dies = $febrero-($diaInicio - $diaTerminacion);
                }
            }else{
                $anios = $anioTerminacion - $anioInicio - 1;
                if($diaInicio > $diaTerminacion){
                    $meses = $mesTerminacion - $mesInicio -1 +12;
                    $dies = $febrero - ($diaInicio-$diaTerminacion);
                }else{
                    $meses = $mesTerminacion - $mesInicio + 12;
                    $dies = $diaTerminacion - $diaInicio;
                }
            }
           $total_dias = (($anios * 360) + ($meses * 30)+ ($dies +1));
        }
         return ($total_dias);
       
    }
    
    //PROCEESO QUE GENERA LOS DIAS DE PRIMAS
     protected function CrearCesantias($cesantias, $ano)
    {
        $mesInicio = 0;
        $anioTerminacion = 0;
        $mesTerminacion = 0;
        $anioInicio = 0;
        $diaTerminacion = 0;
        $diaInicio = 0;
        if($cesantias->fecha_inicio_contrato <= $cesantias->fecha_ultima_cesantia){
            $fecha = date($cesantias->fecha_desde);
        }else{
           $fecha = date($cesantias->fecha_inicio_contrato);
        } 
       
        $fecha_inicio_dias = strtotime('0 day', strtotime($fecha));
        $fecha_inicio_dias = date('Y-m-d', $fecha_inicio_dias);
        //codigo de fechas
        $fecha_inicio = $fecha_inicio_dias;
        $fecha_termino = $cesantias->fecha_hasta;
        $diaTerminacion = substr($fecha_termino, 8, 8);
        $mesTerminacion = substr($fecha_termino, 5, 2);
        $anioTerminacion = substr($fecha_termino, 0, 4);
        $diaInicio = substr($fecha_inicio, 8, 8);
        $mesInicio = substr($fecha_inicio, 5, 2);
        $anioInicio = substr($fecha_inicio, 0, 4);
        
        $febrero = 0;
        $mes = $mesInicio-1;
        if($mes == 2){
            if($ano == 1){
              $febrero = 29;
            }else{
              $febrero = 28;
            }
        }else if($mes <= 7){
            if($mes==0){
             $febrero = 31;
            }else if($mes%2==0){
                 $febrero = 30;
                }else{
                   $febrero = 31;
                }
        }else if($mes > 7){
              if($mes%2==0){
                  $febrero = 31;
              }else{
                  $febrero = 30;
              }
        }
        if(($anioInicio > $anioTerminacion) || ($anioInicio == $anioTerminacion && $mesInicio > $mesTerminacion) || 
            ($anioInicio == $anioTerminacion && $mesInicio == $mesTerminacion && $diaInicio > $diaTerminacion)){
                //mensaje
        }else{
            if($mesInicio <= $mesTerminacion){
                $anios = $anioTerminacion - $anioInicio;
                if($diaInicio <= $diaTerminacion){
                    $meses = $mesTerminacion - $mesInicio;
                    $dies = $diaTerminacion - $diaInicio;
                }else{
                    if($mesTerminacion == $mesInicio){
                       $anios = $anios - 1;
                    }
                    $meses = ($mesTerminacion - $mesInicio - 1 + 12) % 12;
                    $dies = $febrero-($diaInicio - $diaTerminacion);
                }
            }else{
                $anios = $anioTerminacion - $anioInicio - 1;
                if($diaInicio > $diaTerminacion){
                    $meses = $mesTerminacion - $mesInicio -1 +12;
                    $dies = $febrero - ($diaInicio-$diaTerminacion);
                }else{
                    $meses = $mesTerminacion - $mesInicio + 12;
                    $dies = $diaTerminacion - $diaInicio;
                }
            }
           $total_dias = (($anios * 360) + ($meses * 30)+ ($dies +1));
        }
         return ($total_dias);
       
    }
    
    
    //PROCES QUE GENERA EL SALARIO BASICO
    protected function salario($val, $codigo_salario, $id_grupo_pago, $jornada) {
        $prognomdetalle = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $val->id_programacion])->andWhere(['=', 'codigo_salario', $codigo_salario])->one();
        $sw = 0;
        if (!$prognomdetalle) { 
            $table = new \app\models\ProgramacionNominaDetalle();
            $table->id_programacion = $val->id_programacion;
            $table->salario_basico = $val->salario_contrato;
            $table->id_periodo_pago_nomina = $val->id_periodo_pago_nomina;
            if ($val->contrato->tiempo->abreviatura == 'TC') {
                $table->vlr_hora = $val->salario_contrato / $jornada;
                $table->vlr_dia = $val->salario_contrato / 30;
                $table->porcentaje = 100;
            } else {
                if ($val->salario_contrato == $val->salario_medio_tiempo) {
                    $Vlr_dia_medio_tiempo = 0;
                    $sw = 1;
                    $jornada2 = $jornada / 2;
                    $table->vlr_hora = $val->salario_contrato / $jornada2;
                    $table->vlr_dia = $val->salario_contrato / 30;
                    $Vlr_dia_medio_tiempo = $val->salario_medio_tiempo / 30;
                    $table->porcentaje = 50;
                } else {
                    $table->vlr_hora = $val->salario_contrato / $jornada;
                    $table->vlr_dia = $val->salario_contrato / 30;
                }
            }
            $table->codigo_salario = $codigo_salario;
            $table->id_grupo_pago = $id_grupo_pago;
            $contrato = \app\models\Contratos::find()->where(['=', 'id_contrato', $val->id_contrato])->one();
            $fecha_inicio_contrato = strtotime(date($val->fecha_inicio_contrato, time()));
            $fecha_desde = strtotime($val->fecha_desde);
            $fecha_hasta = strtotime($val->fecha_hasta);
            if ($fecha_inicio_contrato < $fecha_desde) {
                if ($val->fecha_final_contrato != '') {
                    $total_dias = 0;
                    $total_dias = round((strtotime($val->fecha_final_contrato) - strtotime($val->fecha_desde)) / 86400) + 1;
                    $table->dias = $total_dias;
                    $table->dias_reales = $total_dias;
                    $table->dias_salario = $total_dias;
                    $table->horas_periodo = $total_dias * $val->factor_dia;
                    $table->horas_periodo_reales = $total_dias * $val->factor_dia;
                    $table->vlr_devengado = round($table->vlr_hora * $table->horas_periodo);
                    $table->fecha_desde = $val->fecha_desde;
                    $table->fecha_hasta = $val->fecha_final_contrato;
                    if ($sw == 1) {
                        $table->vlr_ibc_medio_tiempo = round($Vlr_dia_medio_tiempo * $total_dias);
                    }
                } else {
                        $total_dias = round((strtotime($val->fecha_hasta) - strtotime($val->fecha_desde)) / 86400) + 1;
                        //codigo de febrero
                        $mesFebrero = 0;
                        $diaFebrero = 0;
                        $mesFebrero = substr($val->fecha_hasta, 5, 2);
                        $diaFebrero = substr($val->fecha_hasta, 8, 8);
                        if($mesFebrero == 02){
                            if($diaFebrero == 28){
                                $total_dias = $total_dias + 2;
                            }else{
                                 if($diaFebrero == 29){
                                    $total_dias = $total_dias + 1;
                                 }
                            }
                        }
                        $table->dias = $total_dias;
                        $table->dias_reales = $total_dias;
                        $table->dias_salario = $total_dias;
                        $table->horas_periodo = $total_dias * $val->factor_dia;
                        $table->horas_periodo_reales = $total_dias * $val->factor_dia;
                        $table->vlr_devengado = round($table->vlr_hora * $table->horas_periodo);
                        $table->fecha_desde = $val->fecha_desde;
                        $table->fecha_hasta = $val->fecha_hasta;
                        if ($sw == 1) {
                            $table->vlr_ibc_medio_tiempo = round($Vlr_dia_medio_tiempo * $total_dias);
                        }
                }
            } else {
                if ($val->fecha_final_contrato != '') {
                    $total_dias = strtotime($val->fecha_final_contrato) - strtotime($val->fecha_inicio_contrato);
                   $total_dias = round($total_dias / 86400) + 1;
                    $table->dias = $total_dias;
                    $table->dias_reales = $total_dias;
                    $table->dias_salario = $total_dias;
                    $table->horas_periodo = $total_dias * $val->factor_dia;
                    $table->horas_periodo_reales = $total_dias * $val->factor_dia;
                    $table->vlr_devengado = round($table->vlr_hora * $table->horas_periodo);
                    $table->fecha_desde = $val->fecha_inicio_contrato;
                    $table->fecha_hasta = $val->fecha_final_contrato;
                    if ($sw == 1) {
                        $table->vlr_ibc_medio_tiempo = round($Vlr_dia_medio_tiempo * $total_dias);
                    }
                } else {
                    $total_dias = strtotime($val->fecha_hasta) - strtotime($val->fecha_inicio_contrato);
                    $total_dias = round($total_dias / 86400) + 1;
                    //codigo para febrero
                    $mesFebrero = 0;
                    $diaFebrero = 0;
                    $mesFebrero = substr($val->fecha_hasta, 5, 2);
                    $diaFebrero = substr($val->fecha_hasta, 8, 8);
                    if($mesFebrero == 02){
                        if($diaFebrero == 28){
                            $total_dias = $total_dias + 2;
                        }else{
                             if($diaFebrero == 29){
                                $total_dias = $total_dias + 1;
                             }
                        }
                    }
                    $table->dias = $total_dias;
                    $table->dias_reales = $total_dias;
                    $table->dias_salario = $total_dias;
                    $table->horas_periodo = $total_dias * $val->factor_dia;
                    $table->horas_periodo_reales = $total_dias * $val->factor_dia;
                    $table->vlr_devengado = round($table->vlr_hora * $table->horas_periodo);
                    $table->fecha_desde = $val->fecha_inicio_contrato;
                    $table->fecha_hasta = $val->fecha_hasta;
                    $sw = 0;
                    if($sw == 1) {
                        $table->vlr_ibc_medio_tiempo = round($Vlr_dia_medio_tiempo * $total_dias);
                    }
                }
            }
           $table->insert(false);
           $val->dia_real_pagado = $table->dias_reales;
           $val->save(false);
            return ($total_dias);
        }
    }
    
    //PROCESO QUE GENERA EL AUXILIO DE TRANSPORTE
    protected function Auxiliotransporte($val, $codigo_transporte, $total_dias, $auxilio, $fecha_hasta, $fecha_desde, $id_grupo_pago) {
        
        $prognomdetalle = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $val->id_programacion])->andWhere(['=', 'codigo_salario', $codigo_transporte])->one();
        if (!$prognomdetalle) {
            $detalle = new \app\models\ProgramacionNominaDetalle();
            $contrato = \app\models\Contratos::find()->where(['=', 'id_contrato', $val->id_contrato])->one();
            if ($contrato->aplica_auxilio_transporte == 1) {
                $detalle->id_programacion = $val->id_programacion;
                $detalle->id_periodo_pago_nomina = $val->id_periodo_pago_nomina;
                $detalle->codigo_salario = $codigo_transporte;
                $vlr_dia_auxilio = $auxilio / 30;
                $detalle->dias_transporte = $total_dias;
                $detalle->auxilio_transporte = round($total_dias * $vlr_dia_auxilio);
                $detalle->fecha_desde = $fecha_hasta;
                $detalle->fecha_hasta = $fecha_desde;
                $detalle->dias_reales = $total_dias;
                $detalle->vlr_dia = $vlr_dia_auxilio;
                $detalle->id_grupo_pago = $id_grupo_pago;
                $detalle->save(false);
            }
        }
    }
   
    //PROCESO QUE CARGA LOS ADICIONALES POR FECHA O PERMANENTES
    protected function Moduloadicionfecha($fecha_desde, $fecha_hasta, $adicionfecha, $id, $id_grupo_pago) {
        $contador = 0;
        $concepto_sal = \app\models\ConceptoSalarios::find()->where(['=', 'codigo_salario', $adicionfecha->codigo_salario])->one();
        $nonima = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->andWhere(['=', 'id_empleado', $adicionfecha->id_empleado])->one();
        if($nonima){
            $detalle = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $nonima->id_programacion])->andWhere(['=', 'codigo_salario', $adicionfecha->codigo_salario])->one();
            if (!$detalle) {
                $detalleadicionpago = new \app\models\ProgramacionNominaDetalle();
                $detalleadicionpago->id_programacion = $nonima->id_programacion;
                $detalleadicionpago->codigo_salario = $adicionfecha->codigo_salario;
                $detalleadicionpago->id_periodo_pago_nomina = $id;
                $detalleadicionpago->fecha_desde = $fecha_desde;
                $detalleadicionpago->fecha_hasta = $fecha_hasta;
                $detalleadicionpago->id_grupo_pago = $id_grupo_pago;
                if ($adicionfecha->tipo_adicion == 1) {
                    if ($concepto_sal->prestacional == 1) {
                        $detalleadicionpago->vlr_devengado = $adicionfecha->valor_adicion;
                    } else {
                        $detalleadicionpago->vlr_devengado_no_prestacional = $adicionfecha->valor_adicion;
                        $detalleadicionpago->vlr_devengado = $adicionfecha->valor_adicion;
                    }
                } else {
                    $detalleadicionpago->vlr_deduccion = $adicionfecha->valor_adicion;
                    $detalleadicionpago->deduccion = $adicionfecha->valor_adicion;
                }
                $detalleadicionpago->save(false);
            }
        }    
    }
    
    //PROCESOS DE CARGA LOS PAGOS PERMANENTES
    protected function Moduloadicionpermanente($fecha_desde, $fecha_hasta, $adicionpermanente, $id, $grupo_pago) {
        $contador = 0;
        $contador_permanente = 0;
        $concepto_sal = \app\models\ConceptoSalarios::find()->where(['=', 'codigo_salario', $adicionpermanente->codigo_salario])->one();
        $nonima = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->andWhere(['=', 'id_empleado', $adicionpermanente->id_empleado])->one();
        if($nonima){
            $programacion = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $nonima->id_programacion])->andWhere(['=', 'codigo_salario', $adicionpermanente->codigo_salario])->one();
            if (!$programacion) {
                $detalleapago = new \app\models\ProgramacionNominaDetalle();
                $detalleapago->id_programacion = $nonima->id_programacion;
                $detalleapago->codigo_salario = $adicionpermanente->codigo_salario;
                $detalleapago->id_periodo_pago_nomina = $id;
                $detalleapago->fecha_desde = $fecha_desde;
                $detalleapago->fecha_hasta = $fecha_hasta;
                $detalleapago->id_grupo_pago = $grupo_pago->id_grupo_pago;
                $periodo_pago = \app\models\PeriodoPago::find()->where(['=', 'id_periodo_pago', $grupo_pago->id_periodo_pago])->one();
                if ($adicionpermanente->tipo_adicion == 1) {
                    if ($adicionpermanente->aplicar_dia_laborado == 1) {
                        $dias = $periodo_pago->dias;
                        $calculo = $adicionpermanente->valor_adicion / $dias;
                        $detalle = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nonima->id_programacion])
                                                                               ->andWhere(['=','codigo_salario', 1])->one();
                        if($detalle){
                            $total_pagado = round($calculo * $detalle->dias_reales);
                        }else{
                            $total_pagado = round($calculo * $periodo_pago->dias);
                        }
                        if ($concepto_sal->prestacional == 1) {
                            $detalleapago->vlr_devengado = $total_pagado;
                        } else {
                           $detalleapago->vlr_devengado_no_prestacional = $total_pagado;
                           $detalleapago->vlr_devengado = $total_pagado;
                        }
                    } else {
                        if ($concepto_sal->prestacional == 1) {
                            $detalleapago->vlr_devengado = $adicionpermanente->valor_adicion;

                        } else {
                            $detalleapago->vlr_devengado_no_prestacional = $adicionpermanente->valor_adicion;
                            $detalleapago->vlr_devengado = $adicionpermanente->valor_adicion;
                        }
                    }
                } else {
                    $detalleapago->vlr_deduccion = $adicionpermanente->valor_adicion;
                    $detalleapago->deduccion = $adicionpermanente->valor_adicion;
                }
                $detalleapago->save(false);
            }
        }    
    }
    
    //PROCESO QUE CARGAS LAS HORAS EXTRAS
    protected function Novedadtiempoextra($tiempo_extra, $id, $fecha_desde, $fecha_hasta, $id_grupo_pago) {
        $contador = 0;
        $contador_recargo = 0;
        $programacion_nonima = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->one();
        $prognomdetalle = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $tiempo_extra->id_programacion])->andWhere(['=', 'codigo_salario', $tiempo_extra->codigo_salario])->one();
        if (!$prognomdetalle) {
            $detalle = new \app\models\ProgramacionNominaDetalle();
            $detalle->id_programacion = $tiempo_extra->id_programacion;
            $detalle->codigo_salario = $tiempo_extra->codigo_salario;
            $detalle->vlr_hora = $tiempo_extra->vlr_hora;
            $detalle->id_periodo_pago_nomina = $id;
            $detalle->horas_periodo_reales = $tiempo_extra->nro_horas;
            $detalle->salario_basico = $tiempo_extra->salario_contrato;
            $detalle->vlr_devengado = round($tiempo_extra->total_novedad);
            $detalle->valor_tiempo_extra = round($tiempo_extra->total_novedad);
            $detalle->fecha_desde = $fecha_desde;
            $detalle->fecha_hasta = $fecha_hasta;
            $detalle->porcentaje = $tiempo_extra->porcentaje;
            $detalle->id_grupo_pago = $id_grupo_pago;
            $detalle->id_novedad = $tiempo_extra->id_novedad;
            $detalle->save(false);
        }
    }
    
    //PROCESO QUE CARGA LAS INCAPACIDADES DEL PERIODO
    protected function ModuloIncapacidad($fecha_desde, $fecha_hasta, $valor_incapacidad, $id, $id_grupo_pago) {
        $contador = 0;
        $pro_nonima = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->andWhere(['=', 'id_empleado', $valor_incapacidad->id_empleado])->one();
        if($pro_nonima){
            $tipo_incapacidad = \app\models\ConfiguracionIncapacidad::find()->where(['=', 'codigo_incapacidad', $valor_incapacidad->codigo_incapacidad])->one();
            $prognomdetalle = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $pro_nonima->id_programacion])
                    ->andWhere(['=', 'codigo_salario', $tipo_incapacidad->codigo_salario])
                    ->andWhere(['=', 'id_incapacidad', $valor_incapacidad->id_incapacidad])
                    ->one();
            if (!$prognomdetalle) {
                $detalleIncapacidad = new \app\models\ProgramacionNominaDetalle();
                $detalleIncapacidad->id_programacion = $pro_nonima->id_programacion;
                $detalleIncapacidad->codigo_salario = $tipo_incapacidad->codigo_salario;
                $detalleIncapacidad->salario_basico = $valor_incapacidad->salario;
                $detalleIncapacidad->vlr_dia = $valor_incapacidad->vlr_hora * $pro_nonima->factor_dia;
                $detalleIncapacidad->vlr_hora = $valor_incapacidad->vlr_hora;
                $detalleIncapacidad->id_incapacidad = $valor_incapacidad->id_incapacidad;
                $detalleIncapacidad->fecha_desde = $valor_incapacidad->fecha_inicio;
                $detalleIncapacidad->fecha_hasta = $valor_incapacidad->fecha_final;
                $detalleIncapacidad->vlr_incapacidad = round($valor_incapacidad->valor_dia * $valor_incapacidad->dias_incapacidad);
                $detalleIncapacidad->nro_horas_incapacidad = round($valor_incapacidad->dias_incapacidad * $pro_nonima->factor_dia);
                $detalleIncapacidad->horas_periodo = $valor_incapacidad->dias_incapacidad * $pro_nonima->factor_dia;
                $detalleIncapacidad->horas_periodo_reales = $valor_incapacidad->dias_incapacidad * $pro_nonima->factor_dia;
                $detalleIncapacidad->dias = $valor_incapacidad->dias_incapacidad;
                $detalleIncapacidad->dias_reales = $valor_incapacidad->dias_incapacidad;
                $detalleIncapacidad->dias_incapacidad_descontar = $valor_incapacidad->dias_incapacidad;
                $detalleIncapacidad->id_periodo_pago_nomina = $id;
                $detalleIncapacidad->dias_descontar_transporte = $valor_incapacidad->dias_incapacidad;
                $detalleIncapacidad->porcentaje = $valor_incapacidad->porcentaje_pago;
                $detalleIncapacidad->id_grupo_pago =  $id_grupo_pago;
                if ($valor_incapacidad->pagar_empleado == 1) {
                    $detalleIncapacidad->vlr_devengado = $detalleIncapacidad->vlr_incapacidad;
                    $detalleIncapacidad->vlr_ajuste_incapacidad = $valor_incapacidad->ibc_total_incapacidad -  $detalleIncapacidad->vlr_devengado ;
                }else{
                    $detalleIncapacidad->vlr_ajuste_incapacidad = $valor_incapacidad->ibc_total_incapacidad;
                }
                $detalleIncapacidad->insert(false);
            }
        }    
    }
    
    //PROCESO QUE CARGA LOS CREDITOS
    protected function Modulocredito($fecha_desde, $fecha_hasta, $credito, $id, $id_grupo_pago) {
        $programacion_nonima = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->andWhere(['=', 'id_empleado', $credito->id_empleado])->one();
        if($programacion_nonima){
            $tipo_credito = \app\models\ConfiguracionCredito::find()->where(['=', 'codigo_credito', $credito->codigo_credito])->one();
            $tipo_pago = \app\models\TipoPagoCredito::find()->where(['=', 'id_tipo_pago', $credito->id_tipo_pago])->one();
            $prognomdetalle = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $programacion_nonima->id_programacion])
                    ->andWhere(['=', 'codigo_salario', $tipo_credito->codigo_salario])
                    ->andWhere(['=', 'id_credito', $credito->id_credito])
                    ->one();
            if (!$prognomdetalle) {
                $detallecredito = new \app\models\ProgramacionNominaDetalle();
                if ($tipo_pago->id_tipo_pago == $credito->id_tipo_pago) {
                    $detallecredito->id_programacion = $programacion_nonima->id_programacion;
                    $detallecredito->codigo_salario = $tipo_credito->codigo_salario;
                    $detallecredito->id_periodo_pago_nomina = $id;
                    $detallecredito->vlr_deduccion = $credito->valor_cuota;
                    $detallecredito->deduccion = $credito->valor_cuota;
                    $detallecredito->fecha_desde = $fecha_desde;
                    $detallecredito->fecha_hasta = $fecha_hasta;
                    $detallecredito->id_credito = $credito->id_credito;
                    $detallecredito->id_grupo_pago = $id_grupo_pago;
                    $detallecredito->save(false);
                }
            }
       }    
    }
    
    //codigo que valide las licencias
    protected function ModuloLicencias($fecha_desde, $fecha_hasta, $valor_licencia, $id, $id_grupo_pago) {
        $contador = 0;
        $pro_nonima = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->andWhere(['=', 'id_empleado', $valor_licencia->id_empleado])->one();
        if($pro_nonima){
            $empresa = \app\models\MatriculaEmpresa::findOne(1);
            $jornada2 = $empresa->horas_jornada_laboral / 2;
            $tipo_licencia = \app\models\ConfiguracionLicencia::find()->where(['=', 'codigo_licencia', $valor_licencia->codigo_licencia])->one();
            $prognomdetalle = \app\models\ProgramacionNominaDetalle::find()->where(['=', 'id_programacion', $pro_nonima->id_programacion])
                    ->andWhere(['=', 'codigo_salario', $tipo_licencia->codigo_salario])
                    ->andWhere(['=', 'id_licencia', $valor_licencia->id_licencia_pk])
                    ->one();
            if (!$prognomdetalle) {
                $detalleLicencia = new \app\models\ProgramacionNominaDetalle();
                $detalleLicencia->id_programacion = $pro_nonima->id_programacion;
                $detalleLicencia->codigo_salario = $tipo_licencia->codigo_salario;
                $detalleLicencia->salario_basico = $valor_licencia->salario;
                $detalleLicencia->porcentaje = $tipo_licencia->porcentaje;
                $detalleLicencia->id_grupo_pago = $id_grupo_pago;
                $detalleLicencia->vlr_dia = $valor_licencia->salario / 30;
                if ($pro_nonima->contrato->tiempo->abreviatura == 'TC') {
                    $detalleLicencia->vlr_hora = $valor_licencia->salario / $empresa->horas_jornada_laboral;
                } else {
                    $detalleLicencia->vlr_hora = $valor_licencia->salario / $jornada2;
                }

                $detalleLicencia->fecha_desde = $valor_licencia->fecha_desde;
                $detalleLicencia->fecha_hasta = $valor_licencia->fecha_hasta;
                $detalleLicencia->id_licencia = $valor_licencia->id_licencia_pk;
                //codigo para calcular los dias
                $fecha_final_licencia = strtotime($valor_licencia->fecha_hasta);
                $fecha_inicio_licencia = strtotime($valor_licencia->fecha_desde);
                $fecha_desde = strtotime($fecha_desde);
                $fecha_hasta = strtotime($fecha_hasta);

                if ($fecha_inicio_licencia < $fecha_desde) {
                    if ($fecha_final_licencia >= $fecha_hasta) {
                        $total_dias = ($fecha_hasta) - $fecha_desde;
                        $total_dias = round($total_dias / 86400) + 1;
                    } else {
                        $total_dias = ($fecha_final_licencia) - $fecha_desde;
                        $total_dias = round($total_dias / 86400) + 1;
                    }
                    $detalleLicencia->dias = $total_dias;
                    $detalleLicencia->dias_reales = $total_dias;
                    $detalleLicencia->horas_periodo = $total_dias * $pro_nonima->factor_dia;
                    $detalleLicencia->horas_periodo_reales = $total_dias * $pro_nonima->factor_dia;
                    $detalleLicencia->id_periodo_pago_nomina = $id;
                    $detalleLicencia->nro_horas = $total_dias * $pro_nonima->factor_dia;
                    $detalleLicencia->dias_licencia_descontar = $total_dias;
                    if ($valor_licencia->pagar_empleado == 1) {
                        $detalleLicencia->vlr_devengado = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                        $detalleLicencia->vlr_licencia = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                    } else {
                        $detalleLicencia->vlr_licencia = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                        $detalleLicencia->vlr_devengado = 0;
                        $detalleLicencia->vlr_licencia_no_pagada = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                    }
                    if ($valor_licencia->afecta_transporte == 1) {
                        $detalleLicencia->dias_descontar_transporte = $total_dias;
                    }
                } else {
                    if ($fecha_final_licencia <= $fecha_hasta) {
                        $total_dias = $fecha_final_licencia - $fecha_inicio_licencia;
                        $total_dias = round($total_dias / 86400) + 1;
                        $detalleLicencia->dias = $total_dias;
                        $detalleLicencia->dias_reales = $total_dias;
                        $detalleLicencia->horas_periodo = $total_dias * $pro_nonima->factor_dia;
                        $detalleLicencia->horas_periodo_reales = $total_dias * $pro_nonima->factor_dia;
                        $detalleLicencia->id_periodo_pago_nomina = $id;
                        $detalleLicencia->nro_horas = $total_dias * $pro_nonima->factor_dia;
                        $detalleLicencia->dias_licencia_descontar = $total_dias;
                        if ($valor_licencia->pagar_empleado == 1) {
                            $detalleLicencia->vlr_devengado = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                            $detalleLicencia->vlr_licencia = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                        } else {
                            $detalleLicencia->vlr_licencia = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                            $detalleLicencia->vlr_devengado = 0;
                            $detalleLicencia->vlr_licencia_no_pagada = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                        }
                        if ($valor_licencia->afecta_transporte == 1) {
                            $detalleLicencia->dias_descontar_transporte = $total_dias;
                        }
                    } else {
                        $total_dias = $fecha_hasta - $fecha_inicio_licencia;
                        $total_dias = round($total_dias / 86400) + 1;
                        $detalleLicencia->dias = $total_dias;
                        $detalleLicencia->dias_reales = $total_dias;
                        $detalleLicencia->horas_periodo = $total_dias * $pro_nonima->factor_dia;
                        $detalleLicencia->horas_periodo_reales = $total_dias * $pro_nonima->factor_dia;
                        $detalleLicencia->id_periodo_pago_nomina = $id;
                        $detalleLicencia->nro_horas = $total_dias * $pro_nonima->factor_dia;
                        $detalleLicencia->dias_licencia_descontar = $total_dias;
                        $detalleLicencia->vlr_devengado = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                        if ($valor_licencia->pagar_empleado == 1) {
                            $detalleLicencia->vlr_devengado = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                            $detalleLicencia->vlr_licencia = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                        } else {
                            $detalleLicencia->vlr_licencia = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                            $detalleLicencia->vlr_devengado = 0;
                            $detalleLicencia->vlr_licencia_no_pagada = round($detalleLicencia->vlr_hora * $detalleLicencia->horas_periodo);
                        }
                        if ($valor_licencia->afecta_transporte == 1) {
                            $detalleLicencia->dias_descontar_transporte = $total_dias;
                        }
                    }
                }
                $detalleLicencia->insert(false);
                //codigo que actualiza el IBP
            }
        }    
    }
    
    //PROCESO QUE VALIDE LAS DEDUCCIONES // SEGUNDO PROCESO//////********
    /************************/
    public function actionGenerar_descuentos($id, $id_grupo_pago, $fecha_desde, $fecha_hasta, $tipo_nomina) {
       if($tipo_nomina == 1){
           
       //PROCESO QUE BUSCAR LAS LICENCIAS PARA DESCONTAR DIAS
            $buscarLicencia = \app\models\ProgramacionNominaDetalle::find()->where(['<>','id_licencia', ''])
                                                                          ->andWhere(['=','id_periodo_pago_nomina', $id])->orderBy('id_programacion ASC')->all();
            if(count($buscarLicencia) > 0){
                $auxiliar = 0;
                foreach ($buscarLicencia as $licencias):
                    $contar = 0;
                    if($auxiliar <> $licencias->id_programacion){
                        $Consulta = \app\models\ProgramacionNominaDetalle::find()->where (['=','id_programacion', $licencias->id_programacion])->andwhere(['<>','id_licencia', ''])->all();
                        foreach ($Consulta as $resultado):
                            $contar += $resultado->dias_licencia_descontar;
                            $id_programacion = $resultado->id_programacion;
                        endforeach;
                        $this->DescontarDiasLicencias($contar, $id_programacion);
                        $auxiliar = $licencias->id_programacion;
                    }else{
                        $auxiliar = $licencias->id_programacion;
                    }    
                endforeach;
            }    
            
            //PROCESO QUE BUSCAR LAS INCAPACIDADES PARA DESCONTAR DIAS
            $buscarIncapacidad = \app\models\ProgramacionNominaDetalle::find()->where(['<>','id_incapacidad', ''])
                                                                          ->andWhere(['=','id_periodo_pago_nomina', $id])->orderBy('id_programacion ASC')->all();
           if(count($buscarIncapacidad)> 0){
                $auxiliar = 0;
                foreach ($buscarIncapacidad as $incapacidades):
                    $contar = 0;
                    if($auxiliar <> $incapacidades->id_programacion){
                        $Consulta = \app\models\ProgramacionNominaDetalle::find()->where (['=','id_programacion', $incapacidades->id_programacion])->andwhere(['<>','id_incapacidad', ''])->all();
                        foreach ($Consulta as $resultado):
                            $contar += $resultado->dias_incapacidad_descontar;
                            $id_programacion = $resultado->id_programacion;
                        endforeach;
                        $this->DescontarDiasIncapacidades($contar, $id_programacion);
                        $auxiliar = $incapacidades->id_programacion;
                    }else{
                        $auxiliar = $incapacidades->id_programacion;
                    }    
                endforeach;
            }
            //PROCESO QUE TOTALIZA RESULTADOS
            $nomina = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
            foreach ($nomina as $nominas):
                //PERMITE ACUMULAR LOS DEVENGADOS PRESTACIONA
                $salarios = \app\models\ConceptoSalarios::find()->where(['=','ingreso_base_cotizacion', 1])->all();
                $sumar = 0;
                foreach ($salarios as $salario):
                    $detalle = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])
                                                                            ->andWhere(['=','codigo_salario', $salario->codigo_salario])->one();
                    if($detalle){
                        $sumar += $detalle->vlr_devengado + $detalle->vlr_licencia;
                    }
                endforeach;
                $nominas->horas_pago = round($nominas->dia_real_pagado * $nominas->factor_dia); 
                $nominas->ibc_prestacional = $sumar;
                $nominas->save();
                
               //PERMITE ACUMULAR LOS DEVENGADOS POR RECARGOS NOCTURNOS  
                $recargoNocturno = \app\models\ConceptoSalarios::find()->where(['=','recargo_nocturno', 1])->all();
                $recargo = 0;
                foreach ($recargoNocturno as $recargoN):
                    $detalleR = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])
                                                                            ->andWhere(['=','codigo_salario', $recargoN->codigo_salario])->one();
                    if($detalleR){
                        $recargo += $detalleR->valor_tiempo_extra;
                    }
                endforeach;
                $nominas->total_recargo = $recargo;
                $nominas->save();
                
                //PROCESO QUE BUSCA LOS DEVENGADOS
                $detallenomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])->all();
                $contar = 0; $auxilio = 0; $noprestacional = 0;
                $licencia = 0; $incapacidad = 0; $tiempo_extra = 0;
                foreach ($detallenomina as $key => $detallenominas):
                    $contar += $detallenominas->vlr_devengado + $detallenominas->auxilio_transporte;
                    $auxilio += $detallenominas->auxilio_transporte; 
                    $licencia += $detallenominas->vlr_licencia;
                    $incapacidad += $detallenominas->vlr_incapacidad;
                    $noprestacional += $detallenominas->vlr_devengado_no_prestacional;
                    $tiempo_extra += $detallenominas->valor_tiempo_extra;
                endforeach;
                $nominas->total_devengado = $contar;
                $nominas->total_auxilio_transporte = $auxilio;
                $nominas->ibc_no_prestacional = $noprestacional;
                $nominas->total_licencia = $licencia;
                $nominas->total_incapacidad = $incapacidad;
                $nominas->total_tiempo_extra = $tiempo_extra;
                $nominas->save();    
            endforeach;
            
            // PROCESO QUE HACE EL DESCUENTOS DE SALUD Y PENSION Y FONDO DE SOLIDARIDA
            $nomina = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
            foreach ($nomina as $key => $nominas):
                //PROCESO QUE CARGA LA SALUD
                $configuracionEps = \app\models\ConfiguracionEps::findOne($nominas->contrato->id_configuracion_eps);
                $salud = \app\models\ConceptoSalarios::find()->where(['=','concepto_salud', 1])->one();
                $detalleNomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])
                                                                              ->andWhere(['=','codigo_salario', $salud->codigo_salario])->one();
                if(!$detalleNomina){
                    if($configuracionEps->id_configuracion_eps == 1){
                        $table = new \app\models\ProgramacionNominaDetalle();
                        $table->id_programacion = $nominas->id_programacion;
                        $table->codigo_salario = $salud->codigo_salario;
                        $table->porcentaje = $configuracionEps->porcentaje_empleado_eps;
                        $table->fecha_desde = $fecha_desde;
                        $table->fecha_hasta = $fecha_hasta;
                        $table->id_periodo_pago_nomina = $id;
                        $table->vlr_deduccion = round(($nominas->ibc_prestacional * $configuracionEps->porcentaje_empleado_eps) / 100);
                        $table->descuento_salud = $table->vlr_deduccion;
                        $table->id_grupo_pago = $id_grupo_pago;
                        $table->save();
                    }    
                }
                //PROCESO DE PENSION
                $configuracionPension = \app\models\ConfiguracionPension::findOne($nominas->contrato->id_configuracion_pension);
                $pension = \app\models\ConceptoSalarios::find()->where(['=','concepto_pension', 1])->one();
                $detalleNominaP = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])
                                                                              ->andWhere(['=','codigo_salario', $pension->codigo_salario])->one();
                if(!$detalleNominaP){
                    if($configuracionEps->id_configuracion_eps == 4){
                    }else{
                        if($configuracionPension->id_configuracion_pension == 1 || $configuracionPension->id_configuracion_pension == 3 ){
                           $table = new \app\models\ProgramacionNominaDetalle();
                           $table->id_programacion = $nominas->id_programacion;
                           $table->codigo_salario = $pension->codigo_salario;
                           $table->porcentaje = $configuracionPension->porcentaje_empleado;
                           $table->vlr_deduccion = round(($nominas->ibc_prestacional * $configuracionPension->porcentaje_empleado) / 100); 
                           $table->fecha_desde = $fecha_desde;
                           $table->fecha_hasta = $fecha_hasta;
                           $table->id_periodo_pago_nomina = $id;
                           $table->descuento_pension = $table->vlr_deduccion;
                           $table->id_grupo_pago = $id_grupo_pago;
                           $table->save();
                        }
                    }    
                }
                
                //PROCESO DEL FONDO DE SOLIDARIDA PENSIONAL
                $grupoPago = \app\models\GrupoPago::findOne($id_grupo_pago);
                if($grupoPago->dias_pago == 7){
                   $salarioPromedio = $nominas->total_devengado * 4;   
                }else{
                    if($grupoPago->dias_pago == 10){
                        $salarioPromedio = $nominas->total_devengado * 3;
                    }else{
                        if($grupoPago->dias_pago == 14){
                            $salarioPromedio = $nominas->total_devengado * 2;
                        }else{
                            if($grupoPago->dias_pago == 15){
                               $salarioPromedio = $nominas->total_devengado * 2; 
                            }else{
                                $salarioPromedio = $nominas->total_devengado;
                            }
                        }    
                    }
                }
                $fondoSolidaridad = \app\models\FondoSolidaridadPensional::find()->one();
                $Fsp = \app\models\ConceptoSalarios::find()->where(['=','fsp', 1])->one();
                $detalleFsp = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])
                                                                              ->andWhere(['=','codigo_salario', $Fsp->codigo_salario])->one();
                if(!$detalleFsp){
                    if($salarioPromedio >= $fondoSolidaridad->rango1 && $salarioPromedio < $fondoSolidaridad->rango2){
                       $table = new \app\models\ProgramacionNominaDetalle();
                        $table->id_programacion = $nominas->id_programacion;
                        $table->codigo_salario = $Fsp->codigo_salario;
                        $table->porcentaje = $fondoSolidaridad->porcentaje;
                        $table->vlr_deduccion = round(($nominas->total_devengado * $fondoSolidaridad->porcentaje) / 100); 
                        $table->fecha_desde = $fecha_desde;
                        $table->fecha_hasta = $fecha_hasta;
                        $table->id_periodo_pago_nomina = $id;
                        $table->descuento_fondo_solidaridad = $table->vlr_deduccion;
                        $table->id_grupo_pago = $id_grupo_pago;
                        $table->save();
                    }
                }
                //TOTAL DESCUENTOS Y SALDOS DE PAGOS
                $buscar_Descto = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])->all();
                $sumarD = 0;
                foreach ($buscar_Descto as $key => $buscar):
                    $sumarD += $buscar->vlr_deduccion;
                endforeach;
                $nominas->total_deduccion = $sumarD;
                $nominas->save();
            endforeach;
            //PROCESO QUE CIERRA Y ACTUALIZA
            $nominaTotal = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
            $valor = 0;
            foreach ($nominaTotal as $key => $nominaT) {
                $nominaT->total_pagar = $nominaT->total_devengado - $nominaT->total_deduccion;
                $nominaT->estado_liquidado = 1;
                $nominaT->save();
            }
            
       }else{
            if($tipo_nomina == 2){ ///PROCESO QUE APLICA DESCUENTOS AL PAGO DE PRIMAS
                $nomina_prima = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
                foreach ($nomina_prima as $key => $nominas) {
                     //BUSCAR EN EL MODULO DE CREDITO
                    $creditos = \app\models\Credito::find()->where(['=','id_empleado', $nominas->id_empleado])->andWhere(['>','saldo_credito', 0])
                                                           ->andWhere(['=','estado_periodo', 0])->andWhere(['=','aplicar_prima', 1])->all();
                    if(count($creditos)> 0){
                        foreach ($creditos as $credito) {
                            $buscar = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])
                                                       ->andWhere(['=','codigo_salario', $credito->codigoCredito->codigo_salario])->one();
                            if(!$buscar){
                                $saldo = 0; $cuotas = 0; $tcuota = 0;
                                $cuotas = $credito->numero_cuota_actual + 1;
                                $tcuota = $credito->numero_cuotas - $cuotas;
                                $saldo =  $credito->saldo_credito - $credito->valor_aplicar;      
                                $table = new \app\models\ProgramacionNominaDetalle();
                                $table->id_programacion = $nominas->id_programacion;
                                $table->codigo_salario = $credito->codigoCredito->codigo_salario;
                                $table->fecha_desde = $fecha_desde;
                                $table->fecha_hasta = $fecha_hasta;
                                $table->vlr_deduccion = $credito->valor_aplicar;
                                $table->deduccion = $credito->valor_aplicar;
                                $table->id_credito = $credito->id_credito;
                                $table->id_periodo_pago_nomina = $id;
                                $table->id_grupo_pago = $credito->id_grupo_pago;
                                $table->save();
                                //INSETAR EL ABONO
                                $table2 = new \app\models\AbonoCredito();
                                $table2->id_credito = $credito->id_credito;
                                $table2->id_tipo_pago = 2;
                                $table2->valor_abono = $credito->valor_aplicar;
                                $table2->saldo = $saldo;
                                $table2->cuota_pendiente = $tcuota;
                                $table2->fecha_abono = date('Y-m-d');
                                $table2->observacion = 'Deduccion por primas';
                                $table2->user_name = Yii::$app->user->identity->username;
                                $table2->save(false);
                                //actualizar saldos
                                $credito->numero_cuota_actual = $cuotas;
                                $credito->saldo_credito = $saldo;
                                $credito->save(false);
                            }
                        }
                    }
                    
                    //PROCESO QUE ADICIONA  O DESCUENTA CONCEPTOS POR FECHA EN LA PRIMA
                    $adicion_fecha = \app\models\PagoAdicionalPermanente::find()->where(['=', 'fecha_corte', $fecha_hasta])
                            ->andWhere(['=', 'estado_registro', 0])
                            ->andWhere(['=', 'estado_periodo', 0])
                            ->andWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                            ->andWhere(['=','aplicar_prima', 1])->andWhere(['=','id_empleado', $nominas->id_empleado])
                            ->all();
                    if (count($adicion_fecha) > 0) {
                        foreach ($adicion_fecha as $adicionfecha) {
                            $buscar = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])
                                                       ->andWhere(['=','codigo_salario', $adicionfecha->codigo_salario])->one();
                            if (!$buscar) {
                                $table = new \app\models\ProgramacionNominaDetalle();
                                $table->id_programacion = $nominas->id_programacion;
                                $table->codigo_salario = $adicionfecha->codigo_salario;
                                $table->id_periodo_pago_nomina = $id;
                                $table->fecha_desde = $fecha_desde;
                                $table->fecha_hasta = $fecha_hasta;
                                $table->id_grupo_pago = $id_grupo_pago;
                                if ($adicionfecha->tipo_adicion == 1) {
                                    if ($adicionfecha->codigoSalario->prestacional == 1) {
                                        $table->vlr_devengado = $adicionfecha->valor_adicion;
                                    } else {
                                        $table->vlr_devengado_no_prestacional = $adicionfecha->valor_adicion;
                                        $table->vlr_devengado = $adicionfecha->valor_adicion;
                                    }
                                } else {
                                    $table->vlr_deduccion = $adicionfecha->valor_adicion;
                                    $table->deduccion = $adicionfecha->valor_adicion;
                                }
                                $table->save(false);
                            }
                        }
                    }
                    
                    //PROCESO QUE ACTUALIZA LOS SALDOS
                    $detalleNomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])->all();
                    $deduccion = 0; $devengado = 0;
                    foreach ($detalleNomina as $detalle) {
                        $deduccion += $detalle->vlr_deduccion;
                        $devengado += $detalle->vlr_devengado;
                    }
                    $nominas->total_devengado = $devengado;
                    $nominas->total_deduccion = $deduccion;
                    $nominas->total_pagar = $devengado - $deduccion;
                    $nominas->estado_liquidado = 1;
                    $nominas->save();
                }//TERMINA EL VECTOR DE PROGRAMACION DE PRIMAS
                
            }else{ //COMIENZA EL PROCESO DE CESANTIAS
                $nomina_cesantia = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
                foreach ($nomina_cesantia as $key => $nominas) {
                    
                    //PROCESO QUE ADICIONA  O DESCUENTA CONCEPTOS POR FECHA EN LA CESANTIA
                    $adicion_fecha = \app\models\PagoAdicionalPermanente::find()->where(['=', 'fecha_corte', $fecha_hasta])
                            ->andWhere(['=', 'estado_registro', 0])
                            ->andWhere(['=', 'estado_periodo', 0])
                            ->andWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                            ->andWhere(['=','aplicar_cesantias', 1])->andWhere(['=','id_empleado', $nominas->id_empleado])
                            ->all();
                    if (count($adicion_fecha) > 0) {
                        foreach ($adicion_fecha as $adicionfecha) {
                            $buscar = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])
                                                       ->andWhere(['=','codigo_salario', $adicionfecha->codigo_salario])->one();
                            if (!$buscar) {
                                $table = new \app\models\ProgramacionNominaDetalle();
                                $table->id_programacion = $nominas->id_programacion;
                                $table->codigo_salario = $adicionfecha->codigo_salario;
                                $table->id_periodo_pago_nomina = $id;
                                $table->fecha_desde = $fecha_desde;
                                $table->fecha_hasta = $fecha_hasta;
                                $table->id_grupo_pago = $id_grupo_pago;
                                if ($adicionfecha->tipo_adicion == 1) {
                                    if ($adicionfecha->codigoSalario->prestacional == 1) {
                                        $table->vlr_devengado = $adicionfecha->valor_adicion;
                                    } else {
                                        $table->vlr_devengado_no_prestacional = $adicionfecha->valor_adicion;
                                        $table->vlr_devengado = $adicionfecha->valor_adicion;
                                    }
                                } else {
                                    $table->vlr_deduccion = $adicionfecha->valor_adicion;
                                    $table->deduccion = $adicionfecha->valor_adicion;
                                }
                                $table->save(false);
                            }
                        }
                    }
                    
                    //PROCESO QUE ACTUALIZA LOS SALDOS
                    $detalleNomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])->all();
                    $deduccion = 0; $devengado = 0;
                    foreach ($detalleNomina as $detalle) {
                        $deduccion += $detalle->vlr_deduccion;
                        $devengado += $detalle->vlr_devengado;
                    }
                    $nominas->total_devengado = $devengado;
                    $nominas->total_deduccion = $deduccion;
                    $nominas->total_pagar = $devengado - $deduccion;
                    $nominas->estado_liquidado = 1;
                    $nominas->save();
                }
            } //TERMINA EL PROCESO DE CESANTIAS   
       } 
       return $this->redirect(['programacion-nomina/view', 'id' => $id , 'id_grupo_pago' => $id_grupo_pago, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta ]);
    }
    
    //PROCESO QUE PERMITE DESCONTAR LICENCIAS
    protected function DescontarDiasLicencias($contar, $id_programacion) {
        $detalleTransporte = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $id_programacion])
                                                                          ->andWhere(['>','dias_transporte', 0])->andWhere(['=','aplico_dias_licencia', 0])->one();
        $nomina = ProgramacionNomina::findOne($id_programacion);
        if($detalleTransporte){
           $detalleTransporte->dias_reales = $detalleTransporte->dias_reales - $contar;
           $detalleTransporte->dias_transporte = $detalleTransporte->dias_reales; 
           $detalleTransporte->auxilio_transporte = round($detalleTransporte->dias_transporte * $detalleTransporte->vlr_dia);
           $detalleTransporte->aplico_dias_licencia = 1;
           $detalleTransporte->save();
           $nomina->dia_real_pagado = $detalleTransporte->dias_reales;
           $nomina->save();
        }
        $detalleSalario = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $id_programacion])
                                                                       ->andWhere(['=','codigo_salario', 1])->andWhere(['=','aplico_dias_licencia', 0])->one();
        $nomina = ProgramacionNomina::findOne($id_programacion);
        if($detalleSalario){
            $detalleSalario->dias_reales = $detalleSalario->dias_reales - $contar;
            $detalleSalario->dias_salario = $detalleSalario->dias_reales;
            $detalleSalario->vlr_devengado = round($detalleSalario->vlr_dia * $detalleSalario->dias_reales);
            $detalleSalario->aplico_dias_licencia = 1;
            $detalleSalario->horas_periodo_reales = round($nomina->dia_real_pagado * $nomina->factor_dia);
            $detalleSalario->save();
            $nomina->dia_real_pagado = $detalleSalario->dias_reales;
            $nomina->horas_pago = round($nomina->dia_real_pagado * $nomina->factor_dia); 
            $nomina->save();
        }
    }
    
    //PROCESO QUE RESTA LOS DIAS DE LAS INCAPACIDADES
    protected function DescontarDiasIncapacidades($contar, $id_programacion) {
        $detalleTransporte = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $id_programacion])
                                                                          ->andWhere(['>','dias_transporte', 0])->andWhere(['=','aplico_dias_incapacidad', 0])->one();
        $nomina = ProgramacionNomina::findOne($id_programacion);
        if($detalleTransporte){
           $detalleTransporte->dias_reales = $detalleTransporte->dias_reales - $contar;
           $detalleTransporte->dias_transporte = $detalleTransporte->dias_reales; 
           $detalleTransporte->auxilio_transporte = round($detalleTransporte->dias_transporte * $detalleTransporte->vlr_dia);
           $detalleTransporte->aplico_dias_incapacidad = 1;
           $detalleTransporte->save(false);
           $nomina->dia_real_pagado = $detalleTransporte->dias_reales;
           $nomina->horas_pago = round($nomina->dia_real_pagado * $nomina->factor_dia);
           $nomina->save(false);
        }
        $detalleSalario = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $id_programacion])
                                                                       ->andWhere(['=','codigo_salario', 1])->andWhere(['=','aplico_dias_incapacidad', 0])->one();
          $nomina = ProgramacionNomina::findOne($id_programacion);
        if($detalleSalario){
            $detalleSalario->dias_reales = $detalleSalario->dias_reales - $contar;
            $detalleSalario->dias_salario = $detalleSalario->dias_reales;
            $detalleSalario->vlr_devengado = round( $detalleSalario->vlr_dia * $detalleSalario->dias_reales);
            $detalleSalario->aplico_dias_incapacidad = 1;
            $detalleSalario->horas_periodo_reales = round($nomina->dia_real_pagado * $nomina->factor_dia);
            $detalleSalario->save();
            $nomina->dia_real_pagado = $detalleSalario->dias_reales;
            $nomina->horas_pago = round($nomina->dia_real_pagado * $nomina->factor_dia); 
            $nomina->save(false);
        }
      
    }
    //FIN PROCESO SEGUNDO BOTON
    
    //INICIA PROCESO DEL TERCER BOTON APLICAR PAGOS
    public function actionAplicar_pagos_nomina($id, $id_grupo_pago, $fecha_desde, $fecha_hasta, $tipo_nomina)
    {
        if($tipo_nomina == 1){ //PROCESO DE NOMINA
            
            /*PROCESO DE SALDOS DE CREDITOS*/
            $grupo_pago = \app\models\GrupoPago::findOne($id_grupo_pago);
            $periodo = PeriodoPagoNomina::findOne($id);
            $detalleNomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_periodo_pago_nomina', $id])
                                                                         ->andWhere(['<>','id_credito', ''])->all();
            if(count($detalleNomina) > 0){
                foreach ($detalleNomina as $detalles) {
                    $id_credito = $detalles->id_credito;
                    $this->ActualizaSaldosCreditos($detalles, $id_credito);
                }//termina el foreach
            } 
            //FIN PROCESO DE CREDITO
            
            /*CICLO QUE RECOGE LAS ACTUALIZACIONES PRESTACIONALES*/
            $Nomina = \app\models\ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
            foreach ($Nomina as $key => $nominas) {
                $detalle_nomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $nominas->id_programacion])->all();
                $prestacional = 0; $no_prestacional = 0;
                $devengados = 0; $auxilio_transporte = 0; $deduccion = 0;
                foreach ($detalle_nomina as $detalle) {
                    if($detalle->codigoSalario->devengado_deduccion == 1){
                       if($detalle->codigoSalario->ingreso_base_prestacional == 1){
                           if($detalle->vlr_devengado <> ''){
                                $prestacional += $detalle->vlr_devengado;
                           }else{
                                $prestacional += $detalle->vlr_devengado + $detalle->vlr_licencia;
                           }      
                       }else{
                           $no_prestacional += $detalle->vlr_devengado_no_prestacional;
                       }
                       $devengados += $detalle->vlr_devengado + $detalle->auxilio_transporte;
                       $auxilio_transporte = $detalle->auxilio_transporte;
                    } else {
                       $deduccion += $detalle->vlr_deduccion;
                    }   
                }
                $nominas->ibc_prestacional = $prestacional;
                $nominas->ibc_no_prestacional = $no_prestacional;
                $nominas->total_auxilio_transporte = $auxilio_transporte;
                $nominas->total_devengado = $devengados;
                $nominas->total_deduccion = $deduccion;
                $nominas->total_pagar = $devengados - $deduccion;
                $nominas->estado_cerrado = 1;
                $nominas->save(false);
                
                //ACTUALIZA EL CONTRATO
                $contrato = \app\models\Contratos::findOne($nominas->id_contrato);
                $contrato->ultimo_pago_nomina = $fecha_hasta;
                $contrato->save();
                
                //ACTUALIZA EL GRUPO
                $grupo_pago->ultimo_pago_nomina = $fecha_hasta;
                $grupo_pago->estado = 1;
                $grupo_pago->save();
                
                //CIERRA EL PERIODO
                $periodo->estado_periodo = 1;
                $periodo->save();
                
                //GENERA EL CONSECUTIVO
                $this->GenerarConsecutivoNomina($nominas);
                
            }
            
        } else {
            if($tipo_nomina == 2){ ///PROCESO DE PRIMAS 
                 $pagoPrima = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
                 foreach ($pagoPrima as $key => $prima) {
                     //actualiza contratos
                    $contrato = \app\models\Contratos::findOne($prima->id_contrato);
                    $contrato->ultima_pago_prima = $fecha_hasta;
                    $contrato->save();
                    //actualiza consecutivo
                    $this->GenerarConsecutivoPrima($prima);
                    //CIERRE EL PROCESO DE PRIMAS
                    $prima->estado_cerrado = 1;
                    $prima->save();
                    
                 }
                 //se actualiza el grupo
                $grupoPago = \app\models\GrupoPago::findOne($id_grupo_pago);
                $grupoPago->ultimo_pago_prima = $fecha_hasta;
                $grupoPago->save();
                //se actualiza el periodo
                $periodo = PeriodoPagoNomina::findOne($id);
                $periodo->estado_periodo = 1;
                $periodo->save();
                 
            }else{ //CIERRA EL PROCESO DE CESANTIAS Y LOS INTERESES
                $pagoPrima = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
                foreach ($pagoPrima as $key => $cesantias) {
                     //actualiza contratos
                    $contrato = \app\models\Contratos::findOne($cesantias->id_contrato);
                    $contrato->ultima_pago_cesantia = $fecha_hasta;
                    $contrato->save();
                    //actualiza consecutivo
                    $this->GenerarConsecutivoCesantias($cesantias);
                    //CIERRE EL PROCESO DE PRIMAS
                    $cesantias->estado_cerrado = 1;
                    $cesantias->save();
                    
                }
                 //se actualiza el grupo
                $grupoPago = \app\models\GrupoPago::findOne($id_grupo_pago);
                $grupoPago->ultimo_pago_cesantia = $fecha_hasta;
                $grupoPago->save();
                //se actualiza el periodo
                $periodo = PeriodoPagoNomina::findOne($id);
                $periodo->estado_periodo = 1;
                $periodo->save();
            }
        }
       return $this->redirect(['programacion-nomina/view', 'id' => $id , 'id_grupo_pago' => $id_grupo_pago, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta ]);
    }
    
    //GENERA EL CONSECUTIVO NOMINAS
    protected function GenerarConsecutivoNomina($nominas) {
        $codigo = \app\models\Consecutivos::findOne(23);
        $consecutivo = $codigo->numero_inicial + 1;
        $nominas->nro_pago = $consecutivo;
        $nominas->save();
        $codigo->numero_inicial = $consecutivo;
        $codigo->save();
    }
    
    //GENERA EL CONSECUTIVO PRIMAS
    protected function GenerarConsecutivoCesantias($cesantias) {
        $codigo = \app\models\Consecutivos::findOne(27);
        $consecutivo = $codigo->numero_inicial + 1;
        $cesantias->nro_pago = $consecutivo;
        $cesantias->save();
        $codigo->numero_inicial = $consecutivo;
        $codigo->save();
    }
    
    //GENERA EL CONSECUTIVO PRIMAS
    protected function GenerarConsecutivoCesartias($prima) {
        $codigo = \app\models\Consecutivos::findOne(26);
        $consecutivo = $codigo->numero_inicial + 1;
        $prima->nro_pago = $consecutivo;
        $prima->save();
        $codigo->numero_inicial = $consecutivo;
        $codigo->save();
    }
    
    //PROCESO QUE ACTUALIZA SALDOS DE CREDITOS
    protected function ActualizaSaldosCreditos($detalles, $id_credito) {
        $credito = \app\models\Credito::findOne($id_credito);
        if($credito){
            $nro_cuotas = $credito->numero_cuotas;
            $table = new \app\models\AbonoCredito();
            $table->id_credito = $id_credito;
            if($credito->id_tipo_pago == 1){
                $table->observacion = 'Deduccion por nomina';
                $table->id_tipo_pago = 1;
            }else{
                if($credito->id_tipo_pago == 2){
                    $table->observacion = 'Deduccion por pago de primas';
                    $table->id_tipo_pago = 2;
                }else{
                    $table->observacion = 'Deduccion por pago de cesantias';
                    $table->id_tipo_pago = 3;
                }  
            }    
            $table->valor_abono = $detalles->vlr_deduccion;
            $table->saldo = $credito->saldo_credito - $detalles->vlr_deduccion;
            $table->valor_abono = $detalles->vlr_deduccion;
            $table->fecha_abono = date('Y-m-d'); 
            $table->cuota_pendiente = (($nro_cuotas) - ($credito->numero_cuota_actual + 1));
            $table->user_name = Yii::$app->user->identity->username;
            $table->save(false);
            $credito->saldo_credito = $table->saldo;
            $credito->numero_cuota_actual += 1;
            if($credito->saldo_credito <= 0){
                $credito->estado_credito = 1;
                $credito->estado_periodo = 1;
            }
            $credito->save(false);
        }//fin ciclo
    }
    /**
     * Deletes an existing ProgramacionNomina model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEliminar_empleado($id, $id_grupo_pago, $fecha_desde, $fecha_hasta)
    {
       $nomina = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
            foreach ($nomina as $dato) {
                try {
                    $dato->delete();
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
                    $this->redirect(["programacion-nomina/view", 'id' => $id, 'id_grupo_pago' => $id_grupo_pago, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta]);
                } catch (IntegrityException $e) {
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos de la nómina');
                } catch (\Exception $e) {
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos');
                }
            }
            return $this->redirect(['programacion-nomina/view', 'id' => $id, 'id_grupo_pago' =>$id_grupo_pago, 'fecha_desde' =>$fecha_desde, 'fecha_hasta' => $fecha_hasta]);
    }    
    
    //PROCESO QUE ELIMINA TODO
    public function actionEliminar_todo($id, $id_grupo_pago, $fecha_desde, $fecha_hasta)
    {
       $nomina = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->all();
            foreach ($nomina as $dato) {
                $detalle = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $dato->id_programacion])->all();
                foreach ($detalle as $val){
                    try {
                        $val->delete();
                        Yii::$app->getSession()->setFlash('success', 'Se eliminaron todos los registros.');
                    } catch (IntegrityException $e) {
                        Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos de la nómina');
                    } catch (\Exception $e) {
                        Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos');
                    }
                } 
                try {
                
                } catch (IntegrityException $e) {
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos de la nómina');
                } catch (\Exception $e) {
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos');
                }
            }
            return $this->redirect(['programacion-nomina/view', 'id' => $id, 'id_grupo_pago' =>$id_grupo_pago, 'fecha_desde' =>$fecha_desde, 'fecha_hasta' => $fecha_hasta]);
        }   
        
    //ERROR DE LAS NOVEDADES
    public function actionNovedadeserror($id, $id_grupo_pago, $fecha_desde, $fecha_hasta) {
        Yii::$app->getSession()->setFlash('error', 'Debe de cargar  los empleados en la nomina para generar las novedades!');
        return $this->redirect(['view',
                    'id' => $id,
                    'id_grupo_pago' => $id_grupo_pago,
                    'fecha_desde' => $fecha_desde,
                    'fecha_hasta' => $fecha_hasta,
        ]);
    }
    
    //PERMITE VISUALIZAR LA NOMINA
    public function actionVernomina($id_programacion, $id_empleado, $id_grupo_pago, $id_periodo_pago_nomina)
    {
        $model = new \app\models\FormSoportePagoNomina();
       
        $detalle_nomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $id_programacion])->orderBy('vlr_deduccion ASC')->all();
        
        if (Yii::$app->request->get("id_programacion")) {
           $nomina = ProgramacionNomina::find()->where(['=','id_programacion', $id_programacion])->one();            
            if ($nomina) {                                
                $model->id_programacion = $id_programacion;
                $model->id_empleado = $nomina->id_empleado;
                $model->cedula_empleado = $nomina->cedula_empleado;
                $model->salario_contrato = $nomina->salario_contrato;
                $model->promedio = $nomina->ibc_prestacional;
                $model->nro_pago = $nomina->nro_pago;
                $model->fecha_desde = $nomina->fecha_desde;
                $model->fecha_hasta = $nomina->fecha_hasta;
                $model->dias_pago = $nomina->dias_pago;
                $model->dia_real_pagado = $nomina->dia_real_pagado;
                $model->total_devengado = $nomina->total_devengado;
                $model->total_deduccion = $nomina->total_deduccion;
                $model->id_contrato = $nomina->id_contrato;
                $model->total_pagar = $nomina->total_pagar;
                $model->fecha_inicio_contrato = $nomina->fecha_inicio_contrato;
                $model->id_periodo_pago_nomina= $nomina->id_periodo_pago_nomina;
                $model->dias_ausentes = $nomina->dias_ausentes;
                $model->usuariosistema = $nomina->user_name;
                $model->fecha_creacion = $nomina->fecha_creacion;
            }
        }
       return $this->renderAjax('vernominapago',[
               'model' => $model,
               'detalle_nomina' => $detalle_nomina,
               'id' => $id_programacion,
               'id_empleado' => $id_empleado,
               'id_grupo_pago' => $id_grupo_pago,
               'id_periodo_pago_nomina' => $id_periodo_pago_nomina,    
                  
               ]);
    }
        
    //PROCESO QUE PERMITE EDITAR LA COLILLA
    public function actionView_colilla_pagonomina($id, $id_programacion) {
        $detalle = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $id_programacion])->all();
        $model = ProgramacionNomina::findOne($id_programacion);
       
        return $this->render('form_editar_colilla_pago', [
            'id_programacion' => $id_programacion,
            'detalle' => $detalle,
            'model' => $model,
            'id' => $id,
            ]);
        
    }
    //EDITAR COLILLA
    public function actionEditar_colilla_pagonomina($id_detalle, $id_programacion, $id) {
        $model = new \app\models\ModeloEditarColilla();
        $table = \app\models\ProgramacionNominaDetalle::findOne($id_detalle);
        if ($model->load(Yii::$app->request->post())){
            if (isset($_POST["actualizar_conceptos"])) {
                if($table->codigoSalario->auxilio_transporte == 1){
                    $table->auxilio_transporte = $model->devengado;
                }else{
                    $table->vlr_devengado = $model->devengado;
                }
                $table->vlr_deduccion = $model->deduccion;
                
                $table->save(false);
                return $this->redirect(["programacion-nomina/view_colilla_pagonomina",'id' => $id, 'id_programacion' => $id_programacion]); 
            }
            
        }
       return $this->renderAjax('editar_colilla', [
            'model' => $model,  
            'table' => $table,
      
        ]);      
    }
    
    //AGREGAR ITEMS A LA COLILLA DE PAGO
     //EDITAR COLILLA
    public function actionAgregar_item_colilla($id_programacion, $id, $fecha_desde, $fecha_hasta) {
        $model = new \app\models\ModeloEditarColilla();
        
        if ($model->load(Yii::$app->request->post())){
            if (isset($_POST["agregar_conceptos"])) {
                if($model->codigo_salario <> ''){
                    $nomina = ProgramacionNomina::findOne($id_programacion);
                    $salario = \app\models\ConceptoSalarios::findOne($model->codigo_salario);
                    $table = new \app\models\ProgramacionNominaDetalle();
                    $table->id_programacion = $id_programacion;
                    $table->codigo_salario = $model->codigo_salario;
                    $table->fecha_desde = $fecha_hasta;
                    $table->fecha_hasta = $fecha_hasta;
                    $table->id_periodo_pago_nomina = $id;
                    $table->id_grupo_pago = $nomina->id_grupo_pago;
                    if($salario->debito_credito == 1){
                       $table->vlr_devengado = $model->devengado;   
                       if($salario->prestacional == 0){
                           $table->valor_devengado_no_prestacional = $model->devengado;
                       }
                    }else{
                       $table->vlr_deduccion = $model->deduccion;
                    }
                    $table->save(false);
                    return $this->redirect(["programacion-nomina/view_colilla_pagonomina",'id' => $id, 'id_programacion' => $id_programacion]);
                }else{
                    Yii::$app->getSession()->setFlash('error', 'Debe de seleccionar un concepto de salario para agrega la informacion.');
                    return $this->redirect(["programacion-nomina/view_colilla_pagonomina",'id' => $id, 'id_programacion' => $id_programacion]);
                }
            }
            
        }
       return $this->renderAjax('agregar_items_colilla', [
            'model' => $model,  
            'id' => $id,
      
        ]);      
    }
    
    //ACTUALIZAR LA COLILLA
    public function actionActualizar_colilla($id, $id_programacion) {
        $nomina = ProgramacionNomina::findOne($id_programacion);
        $detalle_nomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $id_programacion])->all();
        $devengados = 0; $deduccion = 0; $prestacional = 0; $no_prestacional = 0; $auxilio_transporte = 0;
        foreach ($detalle_nomina as $detalle) {
            if($detalle->codigoSalario->devengado_deduccion == 1){
                if($detalle->codigoSalario->ingreso_base_prestacional == 1){
                    $prestacional += $detalle->vlr_devengado + $detalle->vlr_licencia;
                }else{
                    $no_prestacional += $detalle->vlr_devengado_no_prestacional;
                }
                $devengados += $detalle->vlr_devengado + $detalle->auxilio_transporte;
                $auxilio_transporte = $detalle->auxilio_transporte;
            } else {
                $deduccion += $detalle->vlr_deduccion;
            }
        }
        $nomina->ibc_prestacional = $prestacional;
        $nomina->ibc_no_prestacional = $no_prestacional;
        $nomina->total_deduccion = $deduccion;
        $nomina->total_devengado = $devengados;
        $nomina->total_auxilio_transporte = $auxilio_transporte;
        $nomina->total_pagar = $devengados - $deduccion;
        $nomina->save();
        return $this->redirect(['view_colilla_pagonomina', 
            'id' => $id,
            'id_programacion' => $id_programacion,
        ]);
        
    }
    
    //ELIMINAR CONCEPTOS DE SALARIOA
    public function actionEliminar_concepto_salario($id, $id_programacion, $id_detalle)
    {
        $detalle = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_detalle', $id_detalle])->one();
            try {
                $detalle->delete();
                Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
                $this->redirect(["programacion-nomina/view_colilla_pagonomina", 'id' => $id, 'id_programacion' => $id_programacion]);
            } catch (IntegrityException $e) {
                Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos de la nómina');
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos');
            }
            
            return $this->redirect(['programacion-nomina/view_colilla_pagonomina', 'id' => $id, 'id_programacion' => $id_programacion]);
    }    
     
    //PROCESO DE NOMINA ELECTRONICA
     //PROCESO QUE CARGA EL LISTADO DE NOMINA ELECTRONICA
    public function actionDocumento_electronico($token = 0) {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 152])->all()) {
                $form = new \app\models\FormFiltroBuscarNomina();
                $desde = null;
                $hasta = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $table = \app\models\PeriodoNominaElectronica::find()
                                ->andFilterWhere(['between', 'fecha_inicio_periodo', $desde, $hasta]);
                        $table = $table->orderBy('id_periodo_electronico DESC');
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
                    $table = \app\models\PeriodoNominaElectronica::find()->orderBy('id_periodo_electronico DESC');
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
                //$to = $count->count();
                return $this->render('crear_periodo_nomina_electronica', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }
    
    //PERMITE CREAR EL PERIODO DE PAGO
    public function actionCrear_nuevo_documento() {
        $model = new \app\models\FormCostoGastoEmpresa();
         if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()){
                if (isset($_POST["crear_periodo"])) {
                    if($model->tipo_nomina <> ''){
                        $table = new \app\models\PeriodoNominaElectronica();
                        $table->fecha_inicio_periodo = $model->fecha_inicio;
                        $table->fecha_corte_periodo = $model->fecha_corte;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->type_document_id = $model->tipo_nomina;
                        $table->nota = 'Nomina del ' . $model->fecha_inicio . ' al ' . $model->fecha_corte . '.';
                        $table->save();
                        return $this->redirect(["documento_electronico"]); 
                    }else{
                        Yii::$app->getSession()->setFlash('error','Debe de seleccionar el tipo de nomina a crear. ');
                        return $this->redirect(['documento_electronico']);
                    }    
                }
            } 
         }
        return $this->renderAjax('crear_nuevo_periodo', [
            'model' => $model,       
        ]);    
    }
    
    //CARGAR EMPLEADOS PARA NOMINA
    public function actionCargar_empleados_nomina($id_periodo, $fecha_inicio, $fecha_corte)
    {
        $nomina = ProgramacionNomina::find()->where(['=','documento_generado', 0])->orderBy('id_empleado ASC')->all();
        $periodo = \app\models\PeriodoNominaElectronica::findOne($id_periodo);
        $auxiliar = 0;
        $contador = 0;
        if(count($nomina) > 0){
            foreach ($nomina as $key => $items) {
                if($items->id_empleado <> $auxiliar){
                    $contador += 1; $total_dias = 0;
                    $totales = ProgramacionNomina::find()->where(['=','id_empleado', $items->id_empleado])->andWhere(['=','documento_generado', 0])->all();
                    $tDevengado = 0; $tDeduccion = 0; $tPagar = 0;
                    foreach ($totales as $key => $total) {
                        $total->documento_generado = 1;
                        $total_dias += $total->dia_real_pagado;
                        $total->save();
                    }
                    $table = new \app\models\NominaElectronica();
                    $table->id_periodo_pago = $items->periodoPagoNomina->periodoPago->id_periodo_pago;
                    $table->id_tipo_nomina = $items->id_tipo_nomina;
                    $table->id_contrato = $items->id_contrato;
                    $table->id_empleado = $items->id_empleado;
                    $table->codigo_documento = $items->empleado->tipoDocumento->codigo_interface_nomina;
                    $table->id_periodo_electronico = $id_periodo;
                    $table->id_grupo_pago = $items->id_grupo_pago;
                    $table->documento_empleado = $items->cedula_empleado;
                    $table->primer_nombre = $items->empleado->nombre1;
                    $table->segundo_nombre = $items->empleado->nombre2;
                    $table->primer_apellido = $items->empleado->apellido1;
                    $table->segundo_apellido = $items->empleado->apellido2;
                    $table->nombre_completo = $items->empleado->nombre_completo;
                    $table->email_empleado = $items->empleado->email_empleado;
                    $table->salario_contrato = $items->salario_contrato;
                    $table->type_worker_id = $items->contrato->tipoCotizante->codigo_api_nomina;
                    $table->sub_type_worker_id = $items->contrato->subtipoCotizante->codigo_api_nomina;
                    $table->codigo_municipio = $items->empleado->codigoMunicipioResidencia->codigo_municipio;
                    $table->direccion_empleado = $items->empleado->direccion;
                    $table->codigo_forma_pago = $items->empleado->formaPago->codigo_api_nomina;
                    $table->nombre_banco = $items->empleado->banco->entidad;
                    if($items->empleado->tipo_cuenta == 'S'){
                         $table->nombre_cuenta = 'Ahorro';
                    }else{
                        $table->nombre_cuenta = 'Corriente';
                    }
                    $table->dias_trabajados = $total_dias;
                    $table->numero_cuenta = $items->empleado->numero_cuenta;
                    $table->fecha_inicio_nomina = $fecha_inicio;
                    $table->fecha_final_nomina = $fecha_corte;
                    $table->fecha_inicio_contrato = $items->fecha_inicio_contrato;
                    $table->fecha_terminacion_contrato = $items->fecha_final_contrato;
                    $table->fecha_envio_nomina = date('Y-m-d');
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $auxiliar =  $items->id_empleado;
                    $periodo->cantidad_empleados = $contador;
                    $periodo->save();
                }else{
                   $auxiliar =  $items->id_empleado;
                }    
            
            }
            return $this->redirect(["documento_electronico"]); 
        }else{
            Yii::$app->getSession()->setFlash('info','No existen empleado con nominas pendientes para procesar.');
             return $this->redirect(["documento_electronico"]); 
        }    
        
    }
    
    //VISTA DE EMPLEADOS CON DOCUMENTOS ELECTRONICOS PARA GENERAR EL DETALLE
    public function actionVista_empleados($id_periodo, $token) {
        $form = new \app\models\FormFiltroDocumentoElectronico();
        $documento = null;
        $empleado = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $documento = Html::encode($form->documento);
                $empleado = Html::encode($form->empleado);
                $table = \app\models\NominaElectronica::find()
                         ->andFilterWhere(['=','documento_empleado', $documento])
                         ->andFilterWhere(['like','nombre_completo', $empleado])
                        ->andWhere(['=','id_periodo_electronico', $id_periodo]);
                $table = $table->orderBy('id_nomina_electronica ASC');
                $tableexcel = $table->all();
                $count = clone $table;
                $to = $count->count();
                $pages = new Pagination([
                    'pageSize' => 40,
                    'totalCount' => $count->count()
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
                if(isset($_POST['excel'])){
                    //$table = $table->all();
                   $this->actionExcelconsultaDocumentos($tableexcel);
                }
            }
        }else{
           $table = \app\models\NominaElectronica::find()->where(['=','id_periodo_electronico', $id_periodo])->orderBy('id_nomina_electronica ASC');
                $tableexcel = $table->all();
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 40,
                    'totalCount' => $count->count(),
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
                if(isset($_POST['excel'])){
                    //$table = $table->all();
                    $this->actionExcelconsultaDocumentos($tableexcel);
                }  
        }
        if (isset($_POST["crear_documento_electronico"])) { ////entra al ciclo cuando presiona el boton crear documentos
            if (isset($_POST["documento_electronico"])) {
                $intIndice = 0;
                $contador = 0;
                foreach ($_POST["documento_electronico"] as $intCodigo) { //vector que cargar cada items
                    $contador += 1;
                    $conRegistro = \app\models\NominaElectronica::find()->where(['=','id_nomina_electronica', $intCodigo])->andWhere(['=','generado_detalle', 0])->one();//array que busca el empleado
                    if($conRegistro){
                        $buscarNomina = ProgramacionNomina::find()->where(['=','id_empleado', $conRegistro->id_empleado])->andWhere(['=','documento_detalle_generado', 0])->all();
                        foreach ($buscarNomina as $key => $datos) {

                            $detalle_nomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $datos->id_programacion])->all();
                            foreach ($detalle_nomina as $key => $detalle) {
                                $buscar = \app\models\NominaElectronicaDetalle::find()->where(['=','codigo_salario', $detalle->codigo_salario])->andWhere(['=','id_periodo_electronico', $id_periodo])
                                                                                      ->andWhere(['=','id_empleado', $conRegistro->id_empleado])->one();
                                
                                if(!$buscar){
                                    $table = new \app\models\NominaElectronicaDetalle();
                                    $table->id_nomina_electronica = $intCodigo;
                                    $table->codigo_salario = $detalle->codigo_salario;
                                    $table->id_empleado = $conRegistro->id_empleado;
                                    $table->descripcion = $detalle->codigoSalario->nombre_concepto;
                                    $table->devengado_deduccion = $detalle->codigoSalario->devengado_deduccion;
                                    $table->fecha_inicio = $conRegistro->fecha_inicio_nomina;
                                    $table->fecha_final = $conRegistro->fecha_final_nomina;
                                    if($table->devengado_deduccion == 1){ //ingresos del empleado
                                        if ($detalle->codigoSalario->id_agrupado == 1){ //salario basico
                                            $conRegistro->dias_trabajados = $detalle->dias_reales;
                                            $table->devengado = $detalle->vlr_devengado;
                                            $table->total_dias = $detalle->dias_reales;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 2){ //auxilio de transporte
                                          $table->total_dias = $detalle->dias_reales;
                                          $table->auxilio_transporte = $detalle->auxilio_transporte; 
                                          $table->devengado = $detalle->auxilio_transporte; 
                                          
                                        }elseif ($detalle->codigoSalario->id_agrupado == 3){ //horas extras diurnas ordinaria
                                            $table->devengado = $detalle->vlr_devengado;
                                            $table->porcentaje = $detalle->codigoSalario->porcentaje_tiempo_extra;
                                            $table->total_dias =$detalle->nro_horas; 
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 9){ //incapacidades
                                            $codigo_incapacidad = \app\models\Incapacidad::findOne($detalle->id_incapacidad);
                                            $table->valor_pago_incapacidad = $detalle->vlr_devengado;
                                            $table->devengado = $detalle->vlr_devengado;
                                            $table->dias_incapacidad = $detalle->dias_reales;
                                            $table->total_dias = $detalle->dias_reales;
                                            $table->inicio_incapacidad = $detalle->fecha_desde;
                                            $table->final_incapacidad = $detalle->fecha_hasta;
                                            $table->codigo_incapacidad = $codigo_incapacidad->codigo_incapacidad;
                                            $table->porcentaje = $detalle->porcentaje;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 10 || $detalle->codigoSalario->id_agrupado == 8){ //licencias remuneradas y maternidad
                                            $table->valor_pago_licencia = $detalle->vlr_devengado;
                                            $table->devengado = $detalle->vlr_devengado;
                                            $table->dias_licencia = $detalle->dias_reales;
                                             $table->total_dias = $detalle->dias_reales;
                                            $table->inicio_licencia = $detalle->fecha_desde;
                                            $table->final_licencia = $detalle->fecha_hasta;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 11){ //primas
                                            $table->valor_pago_prima = $detalle->vlr_devengado;
                                            $table->devengado = $detalle->vlr_devengado;
                                            $table->dias_prima = $detalle->dias_reales;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 12){ //cesantias
                                            $table->valor_pago_cesantias = $detalle->vlr_devengado;
                                            $table->dias_cesantias = $detalle->dias_reales;
                                            $table->devengado = $detalle->vlr_devengado;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 13){ //cesantias
                                            $table->valor_pago_intereses = $detalle->vlr_devengado;
                                            $table->devengado = $detalle->vlr_devengado;  
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 16 || $detalle->codigoSalario->id_agrupado == 15 || $detalle->codigoSalario->id_agrupado == 18){ //bonificacion no salaria y comisiones y reintegro
                                            $table->devengado = $detalle->vlr_devengado;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 19){ //REINTEGRO EMPLEADO
                                            $table->devengado = $detalle->vlr_devengado;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 20){ //VACACIONES
                                            $table->devengado = $detalle->vlr_devengado;
                                            $table->total_dias = $detalle->dias_reales;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 21){ //licencias NO remuneradas
                                            $table->total_dias = $detalle->dias_reales;
                                            $table->dias_licencia_noremuneradas = $detalle->dias_reales;
                                            $table->inicio_licencia = $detalle->fecha_desde;
                                            $table->final_licencia = $detalle->fecha_hasta;
                                        }
                                    }else{// DEDUCCIONES DEL EMPLEADO
                                        if($detalle->codigoSalario->id_agrupado == 4){ //FONDO DE PENSION
                                            $table->porcentaje = $detalle->codigoSalario->porcentaje; 
                                            $table->deduccion_pension = $detalle->vlr_deduccion;
                                            $table->deduccion = $detalle->vlr_deduccion;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 5){ //FONDO DE EPS
                                            $table->porcentaje = $detalle->codigoSalario->porcentaje; 
                                            $table->deduccion_eps = $detalle->vlr_deduccion;
                                            $table->deduccion = $detalle->vlr_deduccion;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 6){ //FONDO solidarida
                                                    $table->porcentaje = $detalle->porcentaje; 
                                                    $table->deduccion_fondo_solidaridad = $detalle->vlr_deduccion;
                                                    
                                        }elseif ($detalle->codigoSalario->id_agrupado == 7 || $detalle->codigoSalario->id_agrupado == 17) { //otras deducciones del empleado y prestamos empresa
                                            $table->deduccion= $detalle->vlr_deduccion;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 14) { //libranzas y bancos
                                            $table->deduccion= $detalle->vlr_deduccion; 
                                        }

                                    }
                                    $table->id_agrupado = $detalle->codigoSalario->id_agrupado;
                                    $table->id_periodo_electronico = $id_periodo;
                                    $table->save(false);
                                    $conRegistro->save(false);
                               }else{// Acumula informacion si el registro esta en la base de datos
                                    if($buscar->devengado_deduccion == 1){ //DEVENGADO DEL TRABAJADO
                                        if ($detalle->codigoSalario->id_agrupado == 1){ //salario basico
                                            $buscar->devengado += $detalle->vlr_devengado;
                                            $buscar->total_dias += $detalle->dias_reales;
                                            $conRegistro->dias_trabajados += $detalle->dias_reales;
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 2){ //auxilio de transporte
                                          $buscar->total_dias += $detalle->dias_reales;
                                          $buscar->auxilio_transporte += $detalle->auxilio_transporte; 
                                          $buscar->devengado += $detalle->auxilio_transporte; 
                                       
                                        }elseif ($detalle->codigoSalario->id_agrupado == 9){ //incapacidades
                                            $table = new \app\models\NominaElectronicaDetalle();
                                            $codigo_incapacidad = Incapacidad::findOne($detalle->id_incapacidad);
                                            $table->valor_pago_incapacidad = $detalle->vlr_devengado;
                                            $table->dias_incapacidad = $detalle->dias_reales;
                                            $table->total_dias = $detalle->dias_reales;
                                            $table->inicio_incapacidad = $detalle->fecha_desde;
                                            $table->final_incapacidad = $detalle->fecha_hasta;
                                            $table->id_incapacidad = $detalle->id_incapacidad;
                                            $table->codigo_incapacidad = $codigo_incapacidad->codigo_incapacidad;
                                            $table->porcentaje = $detalle->porcentaje;
                                            $table->save(false);
                                        }elseif ($detalle->codigoSalario->id_agrupado == 10 || $detalle->codigoSalario->id_agrupado == 8){ //licencias remuneradas
                                            $table = new \app\models\NominaElectronicaDetalle();
                                            $table->valor_pago_licencia = $detalle->vlr_devengado;
                                            $table->dias_licencia = $detalle->dias_reales;
                                            $table->total_dias = $detalle->dias_reales;
                                            $table->inicio_licencia = $detalle->fecha_desde;
                                            $table->final_licencia = $detalle->fecha_hasta;
                                            $table->save(false);
                                        
                                        }elseif ($detalle->codigoSalario->id_agrupado == 16 || $detalle->codigoSalario->id_agrupado == 15 || $detalle->codigoSalario->id_agrupado == 18 || $detalle->codigoSalario->id_agrupado == 19){ //bonificaciones y comisiones
                                            $buscar->devengado += $detalle->vlr_devengado;
                                            $buscar->total_dias += $detalle->dias_reales;
                                        }elseif ($detalle->codigoSalario->id_agrupado == 20 ){
                                            $buscar->devengado += $detalle->vlr_devengado;
                                                
                                        }elseif ($detalle->codigoSalario->id_agrupado == 21){ //licencias NO remuneradas
                                            $table = new \app\models\NominaElectronicaDetalle();
                                            $table->dias_licencia_noremuneradas = $detalle->dias_reales;
                                            $table->total_dias = $detalle->dias_reales;
                                            $table->inicio_licencia = $detalle->fecha_desde;
                                            $table->final_licencia = $detalle->fecha_hasta;
                                            $table->save(false);
                                        }    
                                    }else{ // acumulado de deducciones
                                        if($detalle->codigoSalario->id_agrupado == 4){ //FONDO DE PENSION
                                            $buscar->deduccion_pension += $detalle->vlr_deduccion;  
                                            $buscar->deduccion += $detalle->vlr_deduccion;
                                        }elseif ($detalle->codigoSalario->id_agrupado == 5){ //FONDO DE EPS
                                            $buscar->deduccion_eps += $detalle->vlr_deduccion;  
                                            $buscar->deduccion += $detalle->vlr_deduccion;
                                        }elseif ($detalle->codigoSalario->id_agrupado == 6){ //FONDO solidarida
                                            $buscar->deduccion_fondo_solidaridad += $detalle->vlr_deduccion;
                                            $buscar->deduccion += $detalle->vlr_deduccion;
                                        }elseif ($detalle->codigoSalario->id_agrupado == 14){ //descuentos y libranzas
                                            $buscar->deduccion += $detalle->vlr_deduccion; 
                                            
                                        }elseif ($detalle->codigoSalario->id_agrupado == 7 || $detalle->codigoSalario->id_agrupado == 17){ //otras deducciones del empleado y prestamos empresa
                                            $buscar->deduccion += $detalle->vlr_deduccion; 
                                         
                                        }elseif ($detalle->codigoSalario->id_agrupado == 14) { //libranzas y bancos
                                            $table->deduccion += $detalle->vlr_deduccion; 
                                        }
                                        
                                    }
                                    $buscar->save(false);
                                    $conRegistro->save(false);
                               }
                            }
                        //cierre en programacion turnos
                        $datos->documento_detalle_generado = 1;
                        $datos->save(false);    
                        }
                        //cierra en nomina electronica
                        $conRegistro->generado_detalle = 1;
                        $conRegistro->save(false);
                        
                    }else{
                        $conRegistro = \app\models\NominaElectronica::findOne($intCodigo);
                        Yii::$app->getSession()->setFlash('info','El empleado ('.$conRegistro->nombre_completo.'), ya se le genero el detalle de la Nomina para enviarlo a la DIAN.');
                        return $this->redirect(['vista_empleados','id_periodo' => $id_periodo, 'token' => $token]); 
                    }    
                }
                Yii::$app->getSession()->setFlash('success','Se procesaron ('.$contador.') registros para el documento electrónica de nomina.');
                return $this->redirect(['vista_empleados','id_periodo' => $id_periodo ,'token' => $token]);
            }else{
                Yii::$app->getSession()->setFlash('error','Debe de seleccionar al menos un registro. ');
            }
        }    
        return $this->render('importar_detalle_nomina', [
            'model' => $model, 
            'id_periodo' => $id_periodo,
            'form' => $form,
            'pagination' => $pages,
            'token' => $token,
        ]);    
    }
    
   //IMPRIMIR DOCUMENTOS
    public function actionImprimir_colilla_pago($id) {
        $model = ProgramacionNomina::findOne($id);
        return $this->render('../formatos/nomina/colilla_pago', [
            'model' => $model,
        ]);
    }
    
    //VISTA DEL DETALLE DEL DOCUMENTO ELECTRONICO
    public function actionDetalle_documento_electronico($id_nomina, $id_periodo, $token) 
    {
        $model = \app\models\NominaElectronica::findOne($id_nomina);
        $detalle_documento = \app\models\NominaElectronicaDetalle::find()->where(['=','id_nomina_electronica', $id_nomina])->orderBy('devengado_deduccion ASC')->all();     
        return $this->render('view_detalle_documento_electronico', [
            'model' => $model, 
            'id_nomina' => $id_nomina,
            'id_periodo' => $id_periodo,
            'detalle_documento' => $detalle_documento,
            'token' => $token,
        ]);    
        
    }
    
    //CERRAR PERIODO DE NOMINA ELECTRONICA
    public function actionCerrar_periodo_nomina($id_periodo) {
        $sw = 0;
        $periodo = \app\models\PeriodoNominaElectronica::findOne($id_periodo);
        $documentos = \app\models\NominaElectronica::find()->where(['=','id_periodo_electronico', $id_periodo])->all();
        foreach ($documentos as $key => $validar) {
            if($validar->generado_detalle == 0){
               Yii::$app->getSession()->setFlash('error','El periodo no se puede cerrar porque hay nominas que no se han validado. Consulte con el administrador. ');
               return $this->redirect(['documento_electronico']);
            }
        }
        $this->AcumularTotalesNominaElectronica($id_periodo);
        $this->GranTotalNominaElectronica($id_periodo);
        $this->GenerarConsecutivos($id_periodo);
        $periodo->cerrar_proceso = 1;
        $periodo->save();
        return $this->redirect(['documento_electronico']);
    }
    
     //PROCESO DE ACUMULA LOS TOTALES 
    protected function AcumularTotalesNominaElectronica($id_periodo) {
        $documento = \app\models\NominaElectronica::find()->where(['=','id_periodo_electronico', $id_periodo])->all();
        $devengado = 0; $deduccion = 0;
        foreach ($documento as $key => $datos) {
            $detalles = \app\models\NominaElectronicaDetalle::find()->where(['=','id_empleado', $datos->id_empleado])->andWhere(['=','id_periodo_electronico', $id_periodo])->all();
            if(count($detalles) > 0){
                foreach ($detalles as $key => $val) {
                     if($val->devengado_deduccion == 1){
                         $devengado += $val->devengado;
                     }else{
                         $deduccion += $val->deduccion;
                     }
                }
                $datos->total_devengado = $devengado;
                $datos->total_deduccion = $deduccion;
                $datos->total_pagar = $devengado - $deduccion;
                $datos->save();
                $devengado = 0; $deduccion = 0;
            }
        }
    }
    
    //PROCESO QUE TOLIZA EL VALOR DE LA NOMINA DEL MES
    protected function GranTotalNominaElectronica($id_periodo)
    {
        $periodo = \app\models\PeriodoNominaElectronica::findOne($id_periodo);
        $nomina = \app\models\NominaElectronica::find()->where(['=','id_periodo_electronico', $id_periodo])->all(); 
        $total = 0; $devengado = 0; $deduccion = 0;
        foreach ($nomina as $key => $val) {
            $devengado += $val->total_devengado;
            $deduccion += $val->total_deduccion;
            $total += $val->total_pagar;
        }
        $periodo->total_nomina = $total;
        $periodo->devengado_nomina = $devengado;
        $periodo->deduccion_nomina = $deduccion;
        $periodo->save();
    }
    
    //GENERAR CONSECUTIVOS
    protected function GenerarConsecutivos($id_periodo)
    {
       $nomina = \app\models\NominaElectronica::find()->where(['=','id_periodo_electronico', $id_periodo])->all();
       $documento_electronico = \app\models\DocumentoElectronico::findOne(6);
       $numero = \app\models\Consecutivos::findOne(28);
       foreach ($nomina as $key => $validar)
       {
           $codigo = $numero->numero_inicial + 1;
           $validar->numero_nomina_electronica = $codigo;
           $validar->consecutivo = $documento_electronico->sigla;
           $validar->save();
           $numero->numero_inicial = $codigo;
           $numero->save();
           
       }
    }
            
      //PROCESO QUE CARGA EL LISTADO DE NOMINA ELECTRONICA
    public function actionListar_nomina_electronica($token = 1) {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 151])->all()) {
                $form = new \app\models\FormFiltroDocumentoElectronico();
                $documento = null;
                $empleado = null;
                $grupo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $empleado = Html::encode($form->empleado);
                        $grupo = Html::encode($form->grupo);
                        $table = \app\models\NominaElectronica::find()
                                ->andFilterWhere(['like', 'nombre_completo', $empleado])
                                ->andFilterWhere(['=', 'documento_empleado', $documento])
                                ->andFilterWhere(['=', 'id_grupo_pago', $grupo])
                                ->andWhere(['>', 'numero_nomina_electronica', 0])
                                ->andWhere(['=', 'exportado_nomina', 0]);
                        $table = $table->orderBy('numero_nomina_electronica ASC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 30,
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
                    $table = \app\models\NominaElectronica::find()->Where(['>', 'numero_nomina_electronica', 0])
                                                                  ->andWhere(['=', 'exportado_nomina', 0])
                                                                  ->orderBy('numero_nomina_electronica ASC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 30,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }   
                //PROCESO QUE ENVIA LA NOMINA ELECTRONICA
                if (isset($_POST["enviar_documento_electronico"])) { ////entra al ciclo cuando presiona el boton crear documentos
                    if (isset($_POST["documento_electronico_dian"])) {
                        $intIndice = 0;
                        $contador = 0;
                        foreach ($_POST["documento_electronico_dian"] as $intCodigo) { //vector que cargar cada items
                            $documento = \app\models\NominaElectronica::findOne($intCodigo);// vector del empleado
                            if($documento)
                            {
                                $periodo = \app\models\PeriodoNominaElectronica::findOne($documento->id_periodo_electronico);
                                $total_devengado = intval($documento->total_devengado, 0) . '.00';
                                $total_deduccion = intval($documento->total_deduccion, 0) . '.00';
                                $tipo_nomina_enviada = $periodo->type_document_id;
                                $fecha_ingreso_empleado = $documento->fecha_inicio_contrato;
                                $fecha_inicio_nomina = $documento->fecha_inicio_nomina;
                                $fecha_corte_nomina = $documento->fecha_final_nomina;
                                $dias_trabajados = $documento->dias_trabajados;
                                $fecha_emision_nomina = date('Y-m-d');
                                $fecha_retiro_empleado = $documento->fecha_terminacion_contrato;
                                $codigo_empleado = $documento->id_empleado;
                                $consecutivo = $documento->numero_nomina_electronica;
                                $codigo_periodo_pago = $documento->periodoPago->codigo_api_nomina;// es el codigo del periodo de pago
                                $nota = $periodo->nota;
                                $type_worker_id = $documento->contrato->tipoCotizante->codigo_api_nomina;
                                $sub_type_worker_id = $documento->contrato->subtipoCotizante->codigo_api_nomina;
                                $tipo_documento_empleado = $documento->empleado->tipoDocumento->codigo_interface_nomina;
                                $codigo_municipio = $documento->empleado->codigoMunicipioResidencia->codigo_api_nomina;
                                $tipo_contrato_empleado = $documento->contrato->tipoContrato->codigo_api_enlace;
                                $documento_empleado =  $documento->documento_empleado;
                                $primer_apellido = $documento->primer_apellido;
                                $segundo_apellido = $documento->segundo_apellido;
                                $primer_nombre = $documento->primer_nombre;
                                $segundo_nombre = $documento->segundo_nombre;
                                $direccion_empleado = $documento->direccion_empleado;
                                $salario_empleado = intval($documento->salario_contrato, 0) . '.00';
                                $email_empleado = $documento->email_empleado;
                                $forma_pago = $documento->empleado->formaPago->codigo_api_nomina;
                                $nombre_banco = $documento->empleado->banco->entidad;
                                $tipo = $documento->empleado->tipo_cuenta;
                                $altoriesgo = $documento->contrato->id_configuracion_pension;
                                $tipo_salario = $documento->contrato->tipoSalario->id_tipo_salario;
                                $eps_type_law_deductions_id = $documento->contrato->configuracionEps->codigo_api_nomina;
                                $pension_type_law_deductions_id = $documento->contrato->configuracionPension->codigo_api_nomina;
                                if($tipo_salario === 3){
                                    $salario_integral = true;
                                }else{
                                    $salario_integral = false;
                                }
                                if($altoriesgo == 3){
                                    $alto_riesgo = true;
                                }else{
                                    $alto_riesgo = false;
                                }
                                if($tipo == 'S'){
                                    $tipo_cuenta_bancaria = 'Ahorro';
                                }else{
                                    $tipo_cuenta_bancaria = 'Corriente';
                                }
                                $numero_cuenta_bancaria = $documento->empleado->numero_cuenta;
                                // Configurar cURL
                                $curl = curl_init();
                                $API_KEY = Yii::$app->params['API_KEY_DESARROLLO']; //api_key de desarrollo
                              //  $API_KEY = Yii::$app->params['API_KEY_PRODUCCION']; //api_key de produccion
                                $dataBody = [
                                    "novelty" => [
                                        "novelty" => false,
                                        "uuidnov" => ""  
                                    ],

                                    "period" => [
                                        "admision_date" => "$fecha_ingreso_empleado",
                                        "settlement_start_date" => "$fecha_inicio_nomina",
                                        "settlement_end_date" => "$fecha_corte_nomina",
                                        "worked_time" => "$dias_trabajados",
                                        "retirement_date" => "$fecha_retiro_empleado",
                                        "issue_date" => "$fecha_emision_nomina"
                                    ],

                                    "sendmail" => false,
                                    "sendmailtome" => false,
                                    "worker_code" => "$codigo_empleado",
                                    "prefix" => "NI", // Siempre debe ser NI para nómina individual, NIAD para ajuste
                                    "consecutive" => $consecutivo,
                                    "type_document_id" => "$tipo_nomina_enviada",
                                    "payroll_period_id" => $codigo_periodo_pago,
                                    "notes" => "$nota",
                                    "worker" => [
                                        "type_worker_id" => $type_worker_id,
                                        "sub_type_worker_id" => $sub_type_worker_id,
                                        "payroll_type_document_identification_id" => $tipo_documento_empleado,
                                        "municipality_id" => $codigo_municipio,
                                        "type_contract_id" => $tipo_contrato_empleado,
                                        "high_risk_pension" => $alto_riesgo, //false => si es bajo riesgo y true => si alto riesgo
                                        "identification_number" => $documento_empleado,
                                        "surname" => "$primer_apellido",
                                        "second_surname" => "$segundo_apellido",
                                        "first_name" => "$primer_nombre",
                                        "middle_name" => "$segundo_nombre",
                                        "address" => "$direccion_empleado",
                                        "integral_salarary" => $salario_integral,//si esl TRUE->es verdadero, False => false
                                        "salary" => $salario_empleado,
                                        "email" => "$email_empleado"
                                    ],

                                    "payment" => [ //datos del banco
                                        "payment_method_id" => "$forma_pago",
                                        "bank_name" => "$nombre_banco",
                                        "account_type" => "$tipo_cuenta_bancaria",
                                        "account_number" => "$numero_cuenta_bancaria"
                                    ],

                                    "payment_dates" => [
                                        [
                                            "payment_date" => "$fecha_corte_nomina" // son las fechas de pagos 
                                        ]

                                    ],
                                    "accrued" => [],   //se inicia el vector
                                    "deductions" => [] //inicio el vector de deduccion
                                    
                                ]; 
                               
                               //CICLO QUE SUBE LOS DETALLES DE LA COLILLA
                                $detallesPago = \app\models\NominaElectronicaDetalle::find()->where(['=','id_nomina_electronica', $documento->id_nomina_electronica])->orderBy('id_agrupado ASC')->all();
                                foreach ($detallesPago as $key => $detalle) 
                                {
                                    $deduccion_pension = intval($detalle->deduccion_pension, 0) . '.00';
                                    $deduccion_eps = intval($detalle->deduccion_eps, 0) . '.00';
                                    $valor_pago_incapacidad = intval($detalle->valor_pago_incapacidad, 0) . '.00';
                                    $valor_pago_licencia = intval($detalle->valor_pago_licencia, 0) . '.00';
                                    $deduccion_fondo_solidaridad = intval($detalle->deduccion_fondo_solidaridad, 0) . '.00';
                                    $devengado = intval($detalle->devengado, 0) . '.00';
                                    $auxilio_transporte = intval($detalle->auxilio_transporte, 0) . '.00';
                                    $valor_pago_prima = intval($detalle->valor_pago_prima, 0) . '.00';
                                    $valor_pago_cesantias = intval($detalle->valor_pago_cesantias, 0) . '.00';
                                    $deducciones = intval($detalle->deduccion, 0) . '.00';
                                    $pago_intereses_cesantias = intval($detalle->valor_pago_intereses, 0) . '.00';
                                    
                                    if($detalle->codigo_incapacidad <> ''){
                                        $tipo_incapacidad = $detalle->configuracionIncapacidad->codigo_api_nomina;
                                    }else{
                                        $tipo_incapacidad = 0;
                                    }   
                                    
                                    //DEVENGADOS
                                    if($detalle->id_agrupado == 1){ //salario basico
                                        $dataBody["accrued"]["worked_days"] = $detalle->total_dias;
                                        $dataBody["accrued"]["salary"] = $devengado;
                                    }elseif ($detalle->id_agrupado == 2){ //auxilio de transporte
			                $dataBody["accrued"]["transportation_allowance"] =  $auxilio_transporte;
                                    }elseif ($detalle->id_agrupado == 3){ //horas extras diurnas
                                        if(!isset($dataBody["accrued"]['HEDs'])){
                                            $dataBody["accrued"]['HEDs'] = [];
                                        }
                                        $dataBody["accrued"]['HEDs'][] = [
                                            "start_time" => "2024-12-16T10:00:00",
                                            "start_date" => "2024-12-16T10:00:00",
                                            "end_time" => "2024-12-16T10:00:00",
                                            "end_date" => "2024-12-16T10:00:00",
                                            "quantity" => "2",
                                            "percentage" => 1,
                                            "payment" => "27500"  
                                        ]; 
                                    }elseif ($detalle->id_agrupado == 9){ // incapacidades
                                        if(!isset($dataBody["accrued"]['work_disabilities'])){
                                              $dataBody["accrued"]['work_disabilities'] = [];
                                        }
                                        $dataBody["accrued"]['work_disabilities'][] = [ 
                                            "start_date" => "$detalle->inicio_incapacidad",
                                            "end_date" => "$detalle->final_incapacidad",
                                            "quantity" => "$detalle->dias_incapacidad",
                                            "type" => "$tipo_incapacidad",
                                            "payment" => $valor_pago_incapacidad

                                        ];
                                            
                                    }elseif($detalle->id_agrupado == 10){ //icencias de maternida
                                        if(!isset($dataBody["accrued"]['maternity_leave'])){
                                            $dataBody["accrued"]['maternity_leave'] = [];
                                        }
                                        $dataBody["accrued"]['maternity_leave'][] = [
                                            "start_date" => "$detalle->inicio_licencia",
                                            "end_date" => "$detalle->final_licencia",
                                            "quantity" => "$detalle->dias_licencia",
                                            "payment" => $valor_pago_licencia
                                            
                                        ];
                                    }elseif ($detalle->id_agrupado == 8){ //LICENCIAS REMUNERADAS
                                        if(!isset($dataBody["accrued"]['paid_leave'])){
                                             $dataBody["accrued"]['paid_leave'] = [];
                                        }
                                        $dataBody["accrued"]['paid_leave'][] = [
                                            "start_date" => "$detalle->inicio_licencia",
                                            "end_date" => "$detalle->final_licencia",
                                            "quantity" => "$detalle->dias_licencia",
                                            "payment" => $valor_pago_licencia
                                               
                                        ];
                                        
                                    }elseif ($detalle->id_agrupado == 11){ //PRIMAS DE SERVICIO
                                        $dataBody["accrued"]['service_bonus'] = [
                                            [
                                               "quantity" => "$detalle->dias_prima",
                                               "payment" => $valor_pago_prima,
                                               "paymentNS" => 0
                                            ],
                                        ];  
                                    }elseif ($detalle->id_agrupado == 12){ //CESANTIAS
                                        $dataBody["accrued"]['severance'] = [
                                            [
                                               "payment" => $valor_pago_cesantias,
                                               "percentage" => 12,
                                               "interest_payment" => 0
                                            ],
                                        ];
                                    }elseif ($detalle->id_agrupado == 13){ //INTERESES A CESANTIAS
                                        $dataBody["accrued"]['severance'] = [
                                            [
                                               "payment" => 0,
                                               "percentage" => 0,
                                               "interest_payment" => $pago_intereses_cesantias
                                            ],
                                        ];    
                                    }elseif ($detalle->id_agrupado == 16){ //BONIFICACIONES
                                       if(!isset($dataBody["accrued"]['bonuses'])){
                                           $dataBody["accrued"]['bonuses'] = [];
                                       }
                                       $dataBody["accrued"]['bonuses'][] =  [
                                             
                                               "no_salary_bonus" => $devengado,
                                        ];
                                    }elseif ($detalle->id_agrupado == 19){ //BONIFICACIONES PRESTACIONES
                                       if(!isset($dataBody["accrued"]['bonuses'])){
                                           $dataBody["accrued"]['bonuses'] = [];
                                       }
                                       $dataBody["accrued"]['bonuses'][] =  [
                                               "salary_bonus" => $devengado,
                                               "no_salary_bonus" => 0,
                                        ];   
                                    }elseif ($detalle->id_agrupado == 15){ //comisiones  
                                        $dataBody["accrued"]["commissions"] = [
                                            [    
                                                "commission" => $devengado,
                                            ],
                                        ]; 
                                     
                                    }elseif ($detalle->id_agrupado == 20){ //VACACIONES
                                        if(!isset($dataBody["accrued"]['paid_vacation'])){ 
                                            $dataBody["accrued"]['paid_vacation'] = [];
                                        }
                                        $dataBody["accrued"]['paid_vacation'][] = [
                                            "quantity" => "$detalle->total_dias",
                                            "payment" => "$detalle->devengado"
                                        ];    
                                        
                                    }elseif ($detalle->id_agrupado == 21){ //LICENCIAS NO REMUNERADAS
                                        if(!isset($dataBody["accrued"]['non_paid_leave'])){ //si no existes lo declaracion vacio
                                            $dataBody["accrued"]['non_paid_leave'] = [];
                                        }
                                        $dataBody["accrued"]['non_paid_leave'][] = [
                                            "start_date" => "$detalle->inicio_licencia",
                                            "end_date" => "$detalle->final_licencia",
                                            "quantity" => "$detalle->dias_licencia_noremuneradas"
                                        ];

                                    }elseif ($detalle->id_agrupado == 18){//REINTEGRO O REEMBOLSO
                                        $dataBody["accrued"]["refund"] = $devengado;    
                                    }   
                                    
                                    $dataBody["accrued"]['accrued_total'] = $total_devengado;
                                    //FIN CONCEPTOS DEVENGADOS
                                    
                                    //INICIO DEDUCCIONES
                                    if($detalle->id_agrupado == 4){ //pension
                                        $dataBody["deductions"]["pension_type_law_deductions_id"] = $pension_type_law_deductions_id;
                                        $dataBody["deductions"]["pension_deduction"] = $deduccion_pension;
                                        
                                    }elseif ($detalle->id_agrupado == 5){ //salud
                                        $dataBody["deductions"]["eps_type_law_deductions_id"] = $eps_type_law_deductions_id;
                                        $dataBody["deductions"]["eps_deduction"] = $deduccion_eps;
                                        
                                    }elseif ($detalle->id_agrupado == 6){ //fondo de solidarida
                                        $dataBody["deductions"]["voluntary_pension"] = $deduccion_fondo_solidaridad; 
                                    }elseif ($detalle->id_agrupado == 7){ // prestamos empresa y otras deducciones
                                        if(!isset($dataBody["deductions"]['other_deductions'])){
                                          $dataBody["deductions"]["other_deductions"] = [];  
                                        }
                                        $dataBody["deductions"]['other_deductions'][] = [
                                            "other_deduction" => $deducciones, 
                                        ];    
                                        
                                    }elseif ($detalle->id_agrupado == 14){ // Libranzas prestamo
                                        if(!isset($dataBody["deductions"]['orders'])){
                                          $dataBody["deductions"]["orders"] = [];  
                                        }
                                        $dataBody["deductions"]['orders'][] = [
                                            "description" => "$detalle->descripcion", 
                                            "deduction" => $deducciones 
                                        ];
                                        
                                    }elseif ($detalle->id_agrupado == 17){//prestamo empresa
                                        $dataBody["deductions"]["debt"] = $deducciones;
                                    }
                                    $dataBody["deductions"]['deductions_total'] = $total_deduccion;
                                    
                                    
                                }//CIERRA EL PARA DEL DETALLE DEL PAGO 
                                
                                $dataBody = json_encode($dataBody);
                                var_dump($dataBody);
                                
                              /*  //   //EJECUTA EL DATABODY 
                                curl_setopt_array($curl, [
                                    CURLOPT_URL => "https://begranda.com/equilibrium2/public/api-nomina/payroll?key=$API_KEY",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $dataBody
                                ]);
                               
                                $response = curl_exec($curl);
                                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                if (curl_errno($curl)) {
                                   throw new Exception(curl_error($curl));
                                }
                                curl_close($curl);
                                $data = json_decode($response, true);
                                Yii::info("Respuesta completa de la API desde Begranda: $response", __METHOD__);
                                // Verificar errores de conexión o códigos HTTP inesperados
                                if ($response === false || $httpCode !== 200) {
                                    $error = $response === false ? curl_error($curl) : "HTTP $httpCode";
                                    Yii::$app->getSession()->setFlash('error', 'Hubo un problema al comunicarse con la DIAN. Intenta reenviar más tarde.');
                                    Yii::error("Error en la solicitud CURL: $error", __METHOD__);
                                    return $this->redirect(['programacion-nomina/listar_nomina_electronica']);
                                }
                                if(isset($data) && isset($data['add']['ResponseDian']) && $data['add']['ResponseDian']['Envelope']['Body']['SendNominaSyncResponse']['SendNominaSyncResult']['IsValid'] == "true"){
                                    if (isset($data['add']['cune'])) {
                                        $cune = $data['add']['cune'];
                                        $documento->cune = $cune;
                                        $documento->fecha_envio_begranda = date("Y-m-d H:i:s");
                                        $documento->fecha_recepcion_dian = date("Y-m-d H:i:s");
                                        $qrstr = $data['add']['QRStr'];
                                        $documento->qrstr = $qrstr;
                                        $documento->exportado_nomina = 1;
                                        $documento->save(false);
                                        $contador += 1;                               
                                    }    
                               }else{
                                   $errors = [];
					// Documento no procesado por la DIAN
					if(isset($data["errors"])){ // Control Errores Begranda
						$errors = $data["errors"];
					}else if(isset($data['ResponseDian'])){ // Control de Errores DIAN
						$errors = $data['ResponseDian']['Envelope']['Body']['SendNominaSyncResponse']['SendNominaSyncResult']['ErrorMessage'];
					}else{
                                            $errorMessage = isset($data['message']) ? $data['message'] : 'Error desconocido';
                                            // Mostrar el mensaje específico de la API
                                            Yii::$app->getSession()->setFlash('error', "No se pudo enviar el documento electronico. Error: $errorMessage.");
                                            Yii::error("Error al reenviar documento de nomina No ($consecutivo): " . print_r($data, true), __METHOD__);
                                          
                                        }
                               }*/
                            } 
                            //Cierre la confirmacion de chequeo de registro que se van a envir.
                            
                        }//CIERRA EL PROCESO PARA
                        
                        Yii::$app->getSession()->setFlash('success','Se enviaron ('.$contador.') registros a la DIAN para el proceso de nomina electronica.');
                       // return $this->redirect(['programacion-nomina/listar_nomina_electronica']);
                    }else{
                        Yii::$app->getSession()->setFlash('error','Debe de seleccionar el registro para enviar a la DIAN. ');
                    }
                }    
                return $this->render('listar_documentos_electronicos', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
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
    
    ///PROCESO DE EXCEL
    public function actionExcelpago($id) {
        $nomina = ProgramacionNomina::find()->where(['=','id_periodo_pago_nomina', $id])->orderBy('id_programacion DESC')->all();
         $objPHPExcel = new \PHPExcel();
         $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NRO PAGO')
                    ->setCellValue('B1', 'GRUPO PAGO')
                    ->setCellValue('C1', 'TIPO PAGO')
                    ->setCellValue('D1', 'PERIODO PAGO')
                    ->setCellValue('E1', 'NRO CONTRATO')
                    ->setCellValue('F1', 'DOCUMENTO')
                    ->setCellValue('G1', 'EMPLEADO')   
                    ->setCellValue('H1', 'FECHA INICIO')
                    ->setCellValue('I1', 'FECHA CORTE')
                    ->setCellValue('J1', 'TOTAL DEVENGADO')
                    ->setCellValue('K1', 'TOTAL DEDUCCION')
                    ->setCellValue('L1', 'NETO PAGAR')
                    ->setCellValue('M1', 'IBP');
        $i = 2;
        
        foreach ($nomina as $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->nro_pago)
                    ->setCellValue('B' . $i, $val->grupoPago->grupo_pago)
                    ->setCellValue('C' . $i, $val->tipoNomina->tipo_pago)
                    ->setCellValue('D' . $i, $id)
                    ->setCellValue('E' . $i, $val->id_contrato)
                    ->setCellValue('F' . $i, $val->cedula_empleado)                    
                    ->setCellValue('G' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('H' . $i, $val->fecha_desde)
                    ->setCellValue('I' . $i, $val->fecha_hasta)
                    ->setCellValue('J' . $i, round($val->total_devengado,0))
                    ->setCellValue('K' . $i, round($val->total_deduccion,0))
                    ->setCellValue('L' . $i, round($val->total_pagar,0))
                     ->setCellValue('M' . $i, round($val->ibc_prestacional,0));
            $i++;
        }
        $j = $i + 1;
               
        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Nomina_general.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    } 
    
    public function actionExceldetallepago($id) {
         $detalle = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_periodo_pago_nomina', $id])->orderBy('id_programacion DESC , codigo_salario ASC')->all();
         $objPHPExcel = new \PHPExcel();
         $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                                   
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID_PROGRAMACION')
                    ->setCellValue('B1', 'PERIODO PAGO')
                    ->setCellValue('C1', 'TIPO PAGO')
                    ->setCellValue('D1', 'GRUPO PAGO')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'DESDE')
                    ->setCellValue('G1', 'HASTA')
                    ->setCellValue('H1', 'CONCEPTO') 
                    ->setCellValue('I1', 'DIAS REALES') 
                    ->setCellValue('J1', 'DEVENGADO')
                    ->setCellValue('K1', 'DEDUCCION')
                    ->setCellValue('L1', 'DIAS INCAPACITADO')
                    ->setCellValue('M1', 'DIAS LICENCIA');
                                    
        $i = 2;
       
        foreach ($detalle as $val) {
            $concepto = \app\models\ConceptoSalarios::find()->where(['=','codigo_salario', $val->codigo_salario])->one();   
            if($concepto->auxilio_transporte == 1){
                $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A' . $i, $val->id_programacion)
                     ->setCellValue('B' . $i, $id)
                     ->setCellValue('C' . $i, $val->programacion->tipoNomina->tipo_pago)
                     ->setCellValue('D' . $i, $val->programacion->grupoPago->grupo_pago)    
                     ->setCellValue('E' . $i, $val->programacion->empleado->nombre_completo)
                     ->setCellValue('F' . $i, $val->fecha_desde)
                     ->setCellValue('G' . $i, $val->fecha_hasta)
                     ->setCellValue('H' . $i, $val->codigoSalario->nombre_concepto) 
                     ->setCellValue('I' . $i, $val->dias_reales)    
                     ->setCellValue('J' . $i, round($val->auxilio_transporte,0))
                     ->setCellValue('K' . $i, round($val->vlr_deduccion,0))
                     ->setCellValue('L' . $i, $val->dias_incapacidad_descontar)
                     ->setCellValue('M' . $i, $val->dias_licencia_descontar);
                $i++;
            }else{
               $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A' . $i, $val->id_programacion)
                     ->setCellValue('B' . $i, $id)
                     ->setCellValue('C' . $i, $val->programacion->tipoNomina->tipo_pago)  
                     ->setCellValue('D' . $i, $val->programacion->grupoPago->grupo_pago)  
                     ->setCellValue('E' . $i, $val->programacion->empleado->nombre_completo)
                     ->setCellValue('F' . $i, $val->fecha_desde)
                     ->setCellValue('G' . $i, $val->fecha_hasta)
                     ->setCellValue('H' . $i, $val->codigoSalario->nombre_concepto) 
                     ->setCellValue('I' . $i, $val->dias_reales)  
                     ->setCellValue('j' . $i, round($val->vlr_devengado,0))
                     ->setCellValue('k' . $i, round($val->vlr_deduccion,0))
                     ->setCellValue('l' . $i, $val->dias_incapacidad_descontar)
                     ->setCellValue('M' . $i, $val->dias_licencia_descontar);   
                $i++; 
            }    
        }
        $k = $i + 1;
               
        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Nomina_detalle.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    } 
    
    //CONSULA LOS DETALLES DE NOMINA
    public function actionDetalle_nomina($empleado, $fecha_inicio, $fecha_corte, $grupo_pago, $tipo_nomina){
       if($empleado && $fecha_inicio && $fecha_corte && $tipo_nomina){ //busca los detalles del empleado con un rango de fechas
           $vector = ProgramacionNomina::find()->where(['=','id_empleado', $empleado])->andWhere(['between','fecha_desde', $fecha_inicio, $fecha_corte])
                                               ->andWhere(['=','id_tipo_nomina', $tipo_nomina])->orderBy('id_programacion DESC')->all();
       }elseif ($grupo_pago && $fecha_inicio && $fecha_corte && $tipo_nomina) { //busca los detalles por meido del grupo de pago
            $vector = ProgramacionNomina::find()->where(['=','id_grupo_pago', $grupo_pago])->andWhere(['between','fecha_desde', $fecha_inicio, $fecha_corte])
                                               ->andWhere(['=','id_tipo_nomina', $tipo_nomina])->orderBy('id_programacion DESC')->all();
       }elseif ($empleado && $fecha_inicio && $fecha_corte && $tipo_nomina){
            $vector = ProgramacionNomina::find()->where(['=','id_empleado', $empleado])->andWhere(['between','fecha_desde', $fecha_inicio, $fecha_corte])
                                               ->andWhere(['=','id_tipo_nomina', $tipo_nomina])->orderBy('id_programacion DESC')->all();
       }elseif ($grupo_pago && $fecha_inicio && $fecha_corte && $tipo_nomina){
            $vector = ProgramacionNomina::find()->where(['=','id_grupo_pago', $grupo_pago])->andWhere(['between','fecha_desde', $fecha_inicio, $fecha_corte])
                                              ->andWhere(['=','id_tipo_nomina', $tipo_nomina])->orderBy('id_programacion DESC')->all();
       }elseif ($empleado && $fecha_inicio && $fecha_corte){
            $vector = ProgramacionNomina::find()->where(['=','id_empleado', $empleado])->andWhere(['between','fecha_desde', $fecha_inicio, $fecha_corte])
                                              ->orderBy('id_programacion DESC')->all();
       }elseif ($grupo_pago && $fecha_inicio && $fecha_corte){
            $vector = ProgramacionNomina::find()->where(['=','id_grupo_pago', $grupo_pago])->andWhere(['between','fecha_desde', $fecha_inicio, $fecha_corte])
                                               ->orderBy('id_programacion DESC')->all();
       }elseif ($empleado){
            $vector = ProgramacionNomina::find()->where(['=','id_empleado', $empleado])->orderBy('id_programacion DESC')->all();
       }elseif ($grupo_pago){
            $vector = ProgramacionNomina::find()->where(['=','id_grupo_pago', $grupo_pago])->orderBy('id_programacion DESC')->all();
       }elseif ($tipo_nomina){
           $vector = ProgramacionNomina::find()->where(['=','id_tipo_nomina', $tipo_nomina])->orderBy('id_programacion DESC')->all();
       }
       $objPHPExcel = new \PHPExcel();
         $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'NRO PAGO')
                    ->setCellValue('C1', 'PERIODO PAGO')
                    ->setCellValue('D1', 'TIPO PAGO')
                    ->setCellValue('E1', 'GRUPO PAGO')
                    ->setCellValue('F1', 'NRO CONTRATO')
                    ->setCellValue('G1', 'DOCUMENTO')
                    ->setCellValue('H1', 'EMPLEADO')   
                    ->setCellValue('I1', 'FECHA INICIO')
                    ->setCellValue('J1', 'FECHA CORTE')
                    ->setCellValue('K1', 'SALARIO')
                    ->setCellValue('L1', 'CODIDO SALARIO')
                    ->setCellValue('M1', 'CONCEPTO DE SALARIO')
                    ->setCellValue('N1', 'DIAS DE PAGO')
                    ->setCellValue('O1', 'DEVENGADO')
                    ->setCellValue('P1', 'DEDUCCION');
                  
        
        $i = 2;
        
        foreach ($vector as $val) {
            $vector_detalle = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_programacion', $val->id_programacion])->all();
            foreach ($vector_detalle as $key => $detalle) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_programacion)
                        ->setCellValue('B' . $i, $val->nro_pago)
                        ->setCellValue('C' . $i, $val->id_periodo_pago_nomina)
                        ->setCellValue('D' . $i, $val->tipoNomina->tipo_pago)
                        ->setCellValue('E' . $i, $val->grupoPago->grupo_pago)
                        ->setCellValue('F' . $i, $val->id_contrato)                    
                        ->setCellValue('G' . $i, $val->cedula_empleado)
                        ->setCellValue('H' . $i, $val->empleado->nombre_completo)
                        ->setCellValue('I' . $i, $val->fecha_desde)
                        ->setCellValue('J' . $i, $val->fecha_hasta)
                        ->setCellValue('K' . $i, $val->salario_contrato)
                        ->setCellValue('L' . $i, $detalle->codigo_salario)
                        ->setCellValue('M' . $i, $detalle->codigoSalario->nombre_concepto)
                        ->setCellValue('N' . $i, $detalle->dias_reales);
                        if($detalle->codigo_salario == 20){
                            $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('O' . $i, $detalle->auxilio_transporte)
                            ->setCellValue('P' . $i, $detalle->vlr_deduccion);
                        }else{
                            $objPHPExcel->setActiveSheetIndex(0)
                           ->setCellValue('O' . $i, $detalle->vlr_devengado) 
                             ->setCellValue('P' . $i, $detalle->vlr_deduccion);
                        }    
                       

                $i++;
            } 
            $i = $i;
        }
        
               
        $objPHPExcel->getActiveSheet()->setTitle('Listados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Detalle_nomina.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
    
    public function actionExcelconsultapago($tableexcel) {
         $objPHPExcel = new \PHPExcel();
         $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
         $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'NRO PAGO')
                    ->setCellValue('C1', 'PERIODO PAGO')
                    ->setCellValue('D1', 'TIPO PAGO')
                    ->setCellValue('E1', 'GRUPO PAGO')
                    ->setCellValue('F1', 'NRO CONTRATO')
                    ->setCellValue('G1', 'DOCUMENTO')
                    ->setCellValue('H1', 'EMPLEADO')   
                    ->setCellValue('I1', 'FECHA INICIO')
                    ->setCellValue('J1', 'FECHA CORTE')
                    ->setCellValue('K1', 'SALARIO')
                    ->setCellValue('L1', 'TOTAL DEVENGADO')
                    ->setCellValue('M1', 'TOTAL DEDUCCION')
                    ->setCellValue('N1', 'NETO PAGAR')
                    ->setCellValue('O1', 'IBP');
        $i = 2;
        
        foreach ($tableexcel as $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_programacion)
                    ->setCellValue('B' . $i, $val->nro_pago)
                    ->setCellValue('C' . $i, $val->id_periodo_pago_nomina)
                    ->setCellValue('D' . $i, $val->tipoNomina->tipo_pago)
                    ->setCellValue('E' . $i, $val->grupoPago->grupo_pago)
                    ->setCellValue('F' . $i, $val->id_contrato)                    
                    ->setCellValue('G' . $i, $val->cedula_empleado)
                    ->setCellValue('H' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('I' . $i, $val->fecha_desde)
                    ->setCellValue('J' . $i, $val->fecha_hasta)
                    ->setCellValue('K' . $i, round($val->salario_contrato,0))
                    ->setCellValue('L' . $i, round($val->total_devengado,0))
                    ->setCellValue('M' . $i, round($val->total_deduccion,0))
                    ->setCellValue('N' . $i, round($val->total_pagar,0))
                    ->setCellValue('O' . $i, round($val->ibc_prestacional,0));
                   
            $i++;
        }
        $j = $i + 1;
               
        $objPHPExcel->getActiveSheet()->setTitle('Nominas_pagadas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Nomina general.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    } 
    
    //pago de intereses
     public function actionExcelconsultaPagoIntereses($tableexcel) {
        
         $objPHPExcel = new \PHPExcel();
         $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NRO')
                    ->setCellValue('B1', 'GRUPO PAGO')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'EMPLEADO')   
                    ->setCellValue('E1', 'FECHA INICIO')
                    ->setCellValue('F1', 'FECHA CORTE')
                    ->setCellValue('G1', 'TOTAL CESANTIAS')
                    ->setCellValue('H1', 'TOTAL INTERESES')
                    ->setCellValue('I1', '% PAGO');
        $i = 2;
        
        foreach ($tableexcel as $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_interes)
                    ->setCellValue('B' . $i, $val->grupoPago->grupo_pago)
                    ->setCellValue('C' . $i, $val->documento)                    
                    ->setCellValue('D' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('E' . $i, $val->fecha_inicio)
                    ->setCellValue('F' . $i, $val->fecha_corte)
                    ->setCellValue('G' . $i, $val->valor_cesantias)
                    ->setCellValue('H' . $i, $val->valor_intereses)
                    ->setCellValue('I' . $i, $val->porcentaje);
            $i++;
        }
                       
        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Intereses.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    } 
}
