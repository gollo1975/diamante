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

    /**
     * Displays a single ProgramacionNomina model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $id_grupo_pago, $fecha_desde, $fecha_hasta) {
        $model = PeriodoPagoNomina::findOne($id);
       // $intereses = InteresesCesantia::find()->where(['=','id_periodo_pago_nomina', $id])->orderBy('id_programacion ASC')->all();
        $detalles = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->orderBy('id_programacion ASC')->all();
        $incapacidad = \app\models\Incapacidad::find()->where(['=', 'id_grupo_pago', $id_grupo_pago])
                        ->andWhere(['<=', 'fecha_inicio', $fecha_hasta])
                        ->andWhere(['>=', 'fecha_final', $fecha_desde])
                        ->orderBy('identificacion ASC')->all();
        $licencia = \app\models\Licencia::find()->where(['=', 'id_grupo_pago', $id_grupo_pago])
                        ->andWhere(['<=', 'fecha_desde', $fecha_hasta])
                        ->andWhere(['>=', 'fecha_hasta', $fecha_desde])
                        ->orderBy('identificacion ASC')->all();
        //$novedad_tiempo = NovedadTiempoExtra::find()->where(['=', 'id_periodo_pago_nomina', $id])->orderBy('id_empleado ASC')->all();
        $credito_empleado = \app\models\Credito::find()->where(['<=', 'fecha_inicio', $fecha_hasta])
                        ->andWhere(['=', 'estado_credito', 1])
                        ->andWhere(['=', 'estado_periodo', 1])
                        ->andWhere(['>', 'saldo_credito', 0])
                        ->andWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                        ->orderBy('id_empleado DESC')->all();
        
        return $this->render('view', [
                    'detalles' => $detalles,
                    'model' => $model,
                    'incapacidad' => $incapacidad,
                    'licencia' => $licencia,
                   // 'novedad_tiempo' => $novedad_tiempo,
                    'credito_empleado' => $credito_empleado,
                  //  'intereses' => $intereses,
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
                        ->andWhere(['=', 'contrato_activo', 1])
                        ->andWhere(['<','ultima_prima', $model->fecha_hasta])
                        ->all();
            }else{
                if($tipo_nomina == 3){
                    $registros = \app\models\Contratos::find()
                        ->where(['=', 'id_grupo_pago', $model->id_grupo_pago])
                        ->andWhere(['<=', 'fecha_inicio', $model->fecha_hasta])
                        ->andWhere(['=', 'contrato_activo', 1])
                        ->andWhere(['<','ultima_cesantia', $model->fecha_hasta])
                        ->all();
                }
            }
        }    
     //   $registroscargados = ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->all();
        $cont = 0;
        if($registros == 0){
            Yii::$app->getSession()->setFlash('warning', 'Este grupo de pago a la fecha no tiene empleados con contratos activos!');
        }else{
            foreach ($registros as $val) {
                if (!ProgramacionNomina::find()->where(['=', 'id_periodo_pago_nomina', $id])->andWhere(['=', 'id_contrato', $val->id_contrato])->one()) {
                    $table = new ProgramacionNomina();
                    $table->id_grupo_pago = $model->id_grupo_pago;
                    $table->id_periodo_pago_nomina = $id;
                    $table->id_tipo_nomina = $tipo_nomina;
                    $table->id_contrato = $val->id_contrato;
                    $table->id_empleado = $val->id_empleado;
                    $table->cedula_empleado = $val->empleado->nit_cedula;
                    $table->salario_contrato = $val->salario;
                    $table->fecha_inicio_contrato = $val->fecha_inicio;
                    if ($val->contrato_activo == 1) {
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
            }        $model->save(false);
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

    public function actionGenerar_devengados($id, $id_grupo_pago, $fecha_desde, $fecha_hasta, $tipo_nomina)
    {
        if($tipo_nomina == 1){ //PROCESA REGISTROS DE NOMINA
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
                ///sigue aca
                
                
                

        }else{
            //PROCESO PARA PRIMAS
        }
        return $this->redirect(['programacion-nomina/view', 'id' => $id , 'id_grupo_pago' => $id_grupo_pago, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta ]);
        
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
            if ($val->factor_dia == 8) {
                $table->vlr_hora = $val->salario_contrato / $jornada;
                $table->vlr_dia = $val->salario_contrato / 30;
                $table->porcentaje = 100;
            } else {
                if ($val->salario_contrato <= $val->salario_medio_tiempo) {
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
                $dato->delete();
                } catch (IntegrityException $e) {
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos de la nómina');
                } catch (\Exception $e) {
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar la programacion de nomina, tiene registros asociados en otros procesos');
                }
            }
            return $this->redirect(['programacion-nomina/view', 'id' => $id, 'id_grupo_pago' =>$id_grupo_pago, 'fecha_desde' =>$fecha_desde, 'fecha_hasta' => $fecha_hasta]);
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
