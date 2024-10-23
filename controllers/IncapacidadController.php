<?php

namespace app\controllers;

use Yii;
use app\models\Incapacidad;
use app\models\IncapacidadSearch;
use app\models\GrupoPago;
use app\models\UsuarioDetalle;
use app\models\Licencia;
use app\models\TiempoServicio;
//clases
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use Codeception\Lib\HelperModule;

/**
 * IncapacidadController implements the CRUD actions for Incapacidad model.
 */
class IncapacidadController extends Controller
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
     * Lists all Incapacidad models.
     * @return mixed
     */
   public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',131])->all()){
                $form = new \app\models\FormFiltroIncapacidad();
                $id_empleado = null;
                $numero_incapacidad = null;
                $id_grupo_pago = null;
                $codigo_incapacidad = null; 
                $fecha_inicio = null;
                $fecha_final = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $id_empleado = Html::encode($form->id_empleado);
                        $numero_incapacidad = Html::encode($form->numero_incapacidad);
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $codigo_incapacidad = Html::encode($form->codigo_incapacidad);
                        $fecha_inicio  = Html::encode($form->fecha_inicio);
                        $fecha_final = Html::encode($form->fecha_final);
                        $table = Incapacidad::find()
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['=', 'codigo_incapacidad', $codigo_incapacidad])
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])                                                                                              
                                ->andFilterWhere(['=', 'numero_incapacidad', $numero_incapacidad])
                                ->andFilterWhere(['between', 'fecha_inicio', $fecha_inicio, $fecha_final]);

                        $table = $table->orderBy('id_incapacidad DESC');
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
                                $check = isset($_REQUEST['id_incapacidad DESC']);
                                $this->actionExcelIncapacidad($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = Incapacidad::find()
                        ->orderBy('id_incapacidad DESC');
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
                if(isset($_POST['excel'])){
                    //$table = $table->all();
                    $this->actionExcelIncapacidad($tableexcel);
                }
            }
            $to = $count->count();
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
            ]);
        }else{
             return $this->redirect(['site/sinpermiso']);
        }     
        }else{
           return $this->redirect(['site/login']);
        }
   }

    /**
     * Displays a single Incapacidad model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $registros = \app\models\SeguimientoIncapacidad::find()->where(['=','id_incapacidad', $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'registros' => $registros,
        ]);
    }

    /**
     * Creates a new Incapacidad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionNuevo() {   
        $model = new \app\models\FormIncapacidad();
               
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
         $empleado = \app\models\Empleados::find()->all();
         $configuracionsalario = \app\models\ConfiguracionSalario::find()->where(['=','estado', 1])->one();
        if ($model->load(Yii::$app->request->post())) {           
            if ($model->validate()) {
                 $configuracionincapacidad  = \app\models\ConfiguracionIncapacidad::find()->where(['codigo_incapacidad'=>$model->codigo_incapacidad])->one();
                $codigo =  $configuracionincapacidad->codigo;
                //inicio de grabado
                if($empleado){
                    $empleado = \app\models\Empleados::find()->where(['id_empleado'=>$model->id_empleado])->one();
                    $contrato = \app\models\Contratos::find()->where(['=','id_empleado',$model->id_empleado])->andWhere(['=','contrato_activo', 0])->one();
                    $empresa = \app\models\MatriculaEmpresa::findOne(1);
                    if($contrato->salario <= $configuracionsalario->salario_incapacidad){
                       $auxiliar = $configuracionsalario->salario_minimo_actual / $empresa->horas_jornada_laboral; 
                       $vlr_dia = ($configuracionsalario->salario_minimo_actual/30);
                    }else{
                           $auxiliar = $contrato->salario / $empresa->horas_jornada_laboral; 
                           $vlr_dia = (($contrato->salario / 30) * $configuracionincapacidad->porcentaje)/100;
                    }
                    $diagnostico = \app\models\DiagnosticoIncapacidad::find()->where(['=','id_codigo',$model->id_codigo])->one();
                    $fecha_contrato = date($contrato->fecha_inicio);
                    $fecha_inicio_inca = date($model->fecha_inicio);
                    $fecha_inicio_incapacidad = date($model->fecha_inicio);
                    $fecha_final_incapacidad = date($model->fecha_final);
                    //termina
                    if($fecha_contrato > $fecha_inicio_incapacidad){
                         Yii::$app->getSession()->setFlash('error', 'Error de digitalización, La fecha de inicio de la incapacidad No puede ser inferior a la fecha de inicio del contrato.');
                    }else{
                        if($fecha_inicio_inca > $fecha_final_incapacidad){
                             Yii::$app->getSession()->setFlash('error', 'Error de fechas, La fecha de inicio de la incapacidad No puede ser mayor que la fecha final de la licencia');          
                        }else{   
                            $licencia = \app\models\Licencia::find()->where(['=','id_empleado', $model->id_empleado])->andWhere([])->all();
                            $contLice = count($licencia);
                            if($contLice < 0){
                            }else{
                                foreach ($licencia as $val):
                                    $fecha_inicio_licencia = date($val->fecha_desde);
                                    $fecha_final_licencia = date($val->fecha_hasta);
                                    if($fecha_inicio_licencia == $fecha_inicio_incapacidad){
                                       Yii::$app->getSession()->setFlash('error', 'Error de fechas: la fecha de inicio de esta incapacidad conincide con al fecha de inicio de una licencia');          
                                    }
                                    if($fecha_final_incapacidad == $fecha_final_licencia){
                                         Yii::$app->getSession()->setFlash('error', 'Error de fechas: La fecha de final de esta incapacidad conincide con al fecha de fecha final de una licencia');          
                                    }
                                endforeach;  
                                $incapacidad_creadas = Incapacidad::find()->where(['=','id_empleado', $model->id_empleado])->all();
                                 foreach ($incapacidad_creadas as $inca):
                                    $fecha_inicio_inca = strtotime($inca->fecha_inicio);
                                    $fecha_final_inca = strtotime($inca->fecha_final);
                                    if($fecha_final_inca == $fecha_final_incapacidad){
                                         Yii::$app->getSession()->setFlash('error', 'Error de fechas: La fecha de final de esta incapacidad conincide con al fecha de fecha final de la incapacidad Nro: '. $inca->id_incapacidad.'');          
                                    }
                                    if ($fecha_inicio_incapacidad == $fecha_final_inca){
                                        Yii::$app->getSession()->setFlash('error', 'Error de fechas: La fecha de inicio de la incapacidad no puede ser igual a la fecha final de incapacidad Nro: '. $inca->id_incapacidad.''); 
                                    }
                                endforeach;  
                                $incapacidad = Incapacidad::find()->where(['=','id_empleado', $model->id_empleado])
                                                                ->andwhere(['=','fecha_inicio', $model->fecha_inicio])
                                                                ->andwhere(['=','fecha_final', $model->fecha_final])->one();
                                if($incapacidad){
                                    Yii::$app->getSession()->setFlash('error', 'Error de fechas: Existe una incapacidad creada con el mismo rango de fecha para este empleado.');          
                                }else{ 
                                    
                                        $table = new Incapacidad();
                                        $table->codigo_incapacidad = $model->codigo_incapacidad;
                                        $table->id_empleado = $model->id_empleado;
                                        $table->id_codigo = $model->id_codigo;
                                        $table->numero_incapacidad = $model->numero_incapacidad;
                                        $table->nombre_medico = $model->nombre_medico;
                                        $table->fecha_inicio = $model->fecha_inicio;
                                        $table->fecha_final = $model->fecha_final;
                                        $table->fecha_documento_fisico = $model->fecha_documento_fisico;
                                        $table->fecha_aplicacion = $model->fecha_aplicacion;
                                        $table->transcripcion = $model->transcripcion;
                                        $table->cobrar_administradora = $model->cobrar_administradora;
                                        $table->aplicar_adicional = $model->aplicar_adicional;
                                        $table->user_name = Yii::$app->user->identity->username;
                                        if($table->aplicar_adicional){
                                            $table->estado_incapacidad_adicional = 1;
                                        }
                                        $table->pagar_empleado = $model->pagar_empleado;
                                        $table->prorroga = $model->prorroga;
                                        $table->fecha_inicio_empresa = $model->fecha_inicio;
                                        $table->fecha_final_empresa = $model->fecha_final;
                                        $table->fecha_inicio_administradora = $model->fecha_inicio;
                                        $table->fecha_final_administradora = $model->fecha_final;
                                        $table->observacion = $model->observacion;
                                        $table->identificacion = $empleado->nit_cedula;
                                        $table->id_contrato = $contrato->id_contrato;
                                        $table->id_grupo_pago = $contrato->id_grupo_pago;
                                        $table->salario_mes_anterior = $contrato->salario;
                                        $table->salario = $contrato->salario;
                                        $table->id_entidad_salud = $contrato->id_entidad_salud;
                                        $table->codigo_diagnostico = $diagnostico->codigo_diagnostico;
                                        $total = strtotime($model->fecha_final ) - strtotime($model->fecha_inicio);
                                        $table->dias_incapacidad = round($total / 86400)+1; 
                                        $table->dias_acumulados =  $table->dias_incapacidad;
                                        $table->valor_dia = $vlr_dia;
                                        $dias = round($total/ 86400)+1;
                                        
                                        //codigo que valide si el contrato es medio tiempo
                                       $tiempo_servicio = TiempoServicio::find()->all(); 
                                       $contador = 0;
                                       $incapacidad = 0;
                                       foreach ($tiempo_servicio  as $tiempo):
                                            if($contrato->id_tiempo == 2){
                                                $contador = 1;
                                                $incapacidad = $tiempo->pago_incapacidad_general;
                                                $incapacidad_laboral= $tiempo->pago_incapacidad_laboral;
                                            }else{
                                                $incapacidad = $tiempo->pago_incapacidad_general;
                                                $incapacidad_laboral= $tiempo->pago_incapacidad_laboral;
                                            }
                                                
                                       endforeach;
                                        if($contador == 1){                                       
                                            if($codigo == 1 ){
                                                if($incapacidad != 100){
                                                    if($dias > 2){
                                                        $table->vlr_liquidado = $dias * $vlr_dia;    
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = round($dias - 2);  
                                                        $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                        $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                        $table->dias_administradora = $table->dias_cobro_eps;
                                                        $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                        $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);

                                                    }else{
                                                        $table->vlr_liquidado = round($dias * $vlr_dia); 
                                                        $table->vlr_hora =  $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = 0;  
                                                        $table->vlr_cobro_administradora = 0;
                                                        $table->vlr_saldo_administradora = 0;
                                                        $table->dias_administradora = 0;
                                                        $table->dias_empresa = $dias;
                                                        $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    }
                                                }else{
                                                    if($dias > 2){
                                                        $table->vlr_liquidado = round($dias * $vlr_dia);    
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = round($dias - 2);  
                                                        $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                        $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                        $table->dias_administradora = $table->dias_cobro_eps;
                                                        $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                        $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);

                                                    }else{
                                                       $table->vlr_liquidado = round($dias * $vlr_dia); 
                                                        $table->vlr_hora =  $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = 0;  
                                                        $table->vlr_cobro_administradora = 0;
                                                        $table->vlr_saldo_administradora = 0;
                                                        $table->dias_administradora = 0;
                                                        $table->dias_empresa = $dias;
                                                        $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    }
                                                }
                                            }//termina incapacidad general
                                            // codigo para calculo de incapacidades laborales.
                                            if($codigo == 2 ){
                                                if($incapacidad_laboral != 100){
                                                     $vlr_dia = ($configuracionsalario->salario_minimo_actual/30);
                                                    if($dias > 1){
                                                        $table->vlr_liquidado = round($dias * $vlr_dia);    
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = round($dias - 1);  
                                                        $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                        $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                        $table->dias_administradora = $table->dias_cobro_eps;
                                                        $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                        $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);
                                                     }else{
                                                        $table->vlr_liquidado = round($dias * $vlr_dia); 
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = 0;  
                                                        $table->vlr_cobro_administradora = 0;
                                                        $table->vlr_saldo_administradora = 0;
                                                        $table->dias_administradora = 0;
                                                        $table->dias_empresa = $dias;
                                                        $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    }
                                                }else{
                                                     $vlr_dia = ($configuracionsalario->salario_minimo_actual/30);
                                                    if($dias > 1){
                                                        $table->vlr_liquidado = $dias * $vlr_dia;    
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = round($dias - 1);  
                                                        $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                        $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                        $table->dias_administradora = $table->dias_cobro_eps;
                                                        $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                        $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);

                                                    }else{
                                                       $table->vlr_liquidado = round($dias * $vlr_dia); 
                                                        $table->vlr_hora =  $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = 0;  
                                                        $table->vlr_cobro_administradora = 0;
                                                        $table->vlr_saldo_administradora = 0;
                                                        $table->dias_administradora = 0;
                                                        $table->dias_empresa = $dias;
                                                        $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    }
                                                }    
                                            }  
                                        }else{   
                                            if($codigo == 1 ){
                                                if($dias > 2){
                                                   $table->vlr_liquidado = round($dias * $vlr_dia);    
                                                    $table->vlr_hora = $auxiliar;
                                                    $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                    $table->dias_cobro_eps = round($dias - 2);  
                                                    $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                    $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                    $table->dias_administradora = $table->dias_cobro_eps;
                                                    $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                    $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);
                                                    $table->ibc_total_incapacidad = round($vlr_dia * $dias);

                                                }else{
                                                    $table->vlr_liquidado = round($dias * $vlr_dia); 
                                                    $table->vlr_hora =  $auxiliar;
                                                    $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                    $table->dias_cobro_eps = 0;  
                                                    $table->vlr_cobro_administradora = 0;
                                                    $table->vlr_saldo_administradora = 0;
                                                    $table->dias_administradora = 0;
                                                    $table->dias_empresa = $dias;
                                                    $table->vlr_pago_empresa = round(($dias * $vlr_dia) * $incapacidad)/100;
                                                    $table->ibc_total_incapacidad = round(($contrato->salario / 30) * $dias);
                                                }
                                             
                                            }//termina incapacidad general
                                            // codigo para calculo de incapacidades laborales.
                                            if($codigo == 2 ){
                                                $vlr_dia = $contrato->salario / 30;
                                                if($dias > 1){
                                                    $table->vlr_liquidado = round($dias * $vlr_dia);    
                                                    $table->vlr_hora = $auxiliar;
                                                    $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                    $table->dias_cobro_eps = round($dias - 1);  
                                                    $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                    $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                    $table->dias_administradora = $table->dias_cobro_eps;
                                                    $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                    $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);
                                                    $table->ibc_total_incapacidad = round($vlr_dia * $dias);
                                                 }else{
                                                    $table->vlr_liquidado = round($dias * $vlr_dia); 
                                                    $table->vlr_hora = $auxiliar;
                                                    $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                    $table->dias_cobro_eps = 0;  
                                                    $table->vlr_cobro_administradora = 0;
                                                    $table->vlr_saldo_administradora = 0;
                                                    $table->dias_administradora = 0;
                                                    $table->dias_empresa = $dias;
                                                    $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    $table->ibc_total_incapacidad = round($vlr_dia * $dias);
                                                }
                                            }  
                                        } 
                                    }//termina calculo de incapacidades
                                   
                                    $table->save(false); 
                                    return $this->redirect(["incapacidad/index"]);
                                }    
                            }
                        }
                    }    
                }else {
                    Yii::$app->getSession()->setFlash('error', 'No existe el documento del empleado.');
                }
        }else{
           $model->getErrors();
        }
        return $this->render('form', [
                 'model' => $model,
                  ]);
    }

    /**
     * Updates an existing Incapacidad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionUpdate($id)
    {
        $model = new \app\models\FormIncapacidad();
      
       if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
         
        if ($model->load(Yii::$app->request->post())) {  
            if($model->validate()){
                
                $empleado = \app\models\Empleados::find()->where(['id_empleado'=>$model->id_empleado])->one();
                $contrato = \app\models\Contratos::find()->where(['=','id_empleado',$model->id_empleado])->andWhere(['=','contrato_activo', 0])->one();
                $empresa = \app\models\MatriculaEmpresa::findOne(1);
                $configuracionsalario = \app\models\ConfiguracionSalario::find()->where(['=','estado', 1])->one();
                $configuracionincapacidad  = \app\models\ConfiguracionIncapacidad::find()->where(['codigo_incapacidad' =>$model->codigo_incapacidad])->one();
                if($contrato->salario <= $configuracionsalario->salario_incapacidad){
                       $auxiliar = $configuracionsalario->salario_minimo_actual / $empresa->horas_jornada_laboral; 
                       $vlr_dia = ($configuracionsalario->salario_minimo_actual/30);
                }else{
                       $auxiliar = $contrato->salario / $empresa->horas_jornada_laboral; 
                       $vlr_dia = (($contrato->salario / 30) * $configuracionincapacidad->porcentaje)/100;
                }
                $diagnostico = \app\models\DiagnosticoIncapacidad::find()->where(['=','id_codigo',$model->id_codigo])->one();
                $detalle_nomina = \app\models\ProgramacionNominaDetalle::find()->where(['=','id_incapacidad', $id])->one();
                $codigo =  $configuracionincapacidad->codigo;
                $fecha_contrato = date($contrato->fecha_inicio);
                $fecha_inicio_inca = date($model->fecha_inicio);
                $fecha_inicio_incapacidad = date($model->fecha_inicio);
                $fecha_final_incapacidad = date($model->fecha_final);
                if($detalle_nomina){
                   Yii::$app->getSession()->setFlash('error', 'Error: la incapacidad no se puede modificar porque esta relacionada en el proceso de nomina!');           
                }else{
                    if($fecha_contrato > $fecha_inicio_incapacidad){
                         Yii::$app->getSession()->setFlash('error', 'Error de digitalización, La fecha de inicio de la licencia No puede ser inferior a la fecha de inicio del contrato.');
                    }else{
                        if($fecha_inicio_inca > $fecha_final_incapacidad){
                             Yii::$app->getSession()->setFlash('error', 'Error de fechas, La fecha de inicio de la incapacidad No puede ser mayor que la fecha final de la licencia');          
                        }else{  
                            $licencia = \app\models\Licencia::find()->where(['=','id_empleado', $model->id_empleado])->all();
                            $contLice = count($licencia);
                            if($contLice < 0){
                            }else{
                                 foreach ($licencia as $val):
                                    $fecha_inicio_licencia = date($val->fecha_desde);
                                    $fecha_final_licencia = date($val->fecha_hasta);
                                    if($fecha_inicio_licencia == ($fecha_inicio_incapacidad)){
                                       Yii::$app->getSession()->setFlash('error', 'Error de fechas: la fecha de inicio de esta incapacidad conincide con al fecha de inicio de una licencia');          
                                    }
                                    if($fecha_final_incapacidad == $fecha_final_licencia){
                                         Yii::$app->getSession()->setFlash('error', 'Error de fechas: La fecha de final de esta incapacidad conincide con al fecha de fecha final de una licencia');          
                                    }
                                endforeach;  
                                    $table = Incapacidad::find()->where(['id_incapacidad'=>$id])->one();
                                    if ($table) { 
                                        $table->codigo_incapacidad = $model->codigo_incapacidad;
                                        $table->id_empleado = $model->id_empleado;
                                        $table->id_codigo = $model->id_codigo;
                                        $table->numero_incapacidad = $model->numero_incapacidad;
                                        $table->nombre_medico = $model->nombre_medico;
                                        $table->fecha_inicio = $model->fecha_inicio;
                                        $table->fecha_final = $model->fecha_final;
                                        $table->fecha_documento_fisico = $model->fecha_documento_fisico;
                                        $table->fecha_aplicacion = $model->fecha_aplicacion;
                                        $table->transcripcion = $model->transcripcion;
                                        $table->cobrar_administradora = $model->cobrar_administradora;
                                        $table->aplicar_adicional = $model->aplicar_adicional;
                                        $table->pagar_empleado = $model->pagar_empleado;
                                        $table->prorroga = $model->prorroga;
                                        $table->fecha_inicio_empresa = $model->fecha_inicio;
                                        $table->fecha_final_empresa = $model->fecha_final;
                                        $table->fecha_inicio_administradora = $model->fecha_inicio;
                                        $table->fecha_final_administradora = $model->fecha_final;
                                        $table->user_name_editado = Yii::$app->user->identity->username;    
                                        $table->observacion = $model->observacion;
                                        $table->identificacion = $empleado->nit_cedula;
                                        $table->id_contrato = $contrato->id_contrato;
                                        $table->id_grupo_pago = $contrato->id_grupo_pago;
                                        $table->salario_mes_anterior = $contrato->salario;
                                        $table->salario = $contrato->salario;
                                        $table->id_entidad_salud = $contrato->id_entidad_salud;
                                        $table->codigo_diagnostico = $diagnostico->codigo_diagnostico;
                                        $total = strtotime($model->fecha_final ) - strtotime($model->fecha_inicio);
                                        $table->dias_incapacidad = round($total / 86400)+1; 
                                        $table->dias_acumulados =  $table->dias_incapacidad;
                                        $table->valor_dia = $vlr_dia;
                                        $dias = round($total/ 86400)+1;
                                        if($table->aplicar_adicional){
                                            $table->estado_incapacidad_adicional = 1;
                                        }else{
                                            $table->estado_incapacidad_adicional = 0;
                                        }
                                        $tiempo_servicio = TiempoServicio::find()->all(); 
                                        $contador = 0;
                                        $incapacidad = 0;
                                        foreach ($tiempo_servicio  as $tiempo):
                                            if($contrato->id_tiempo == 2){
                                                $contador = 1;
                                                $incapacidad = $tiempo->pago_incapacidad_general;
                                                $incapacidad_laboral= $tiempo->pago_incapacidad_laboral;
                                            }else{
                                                $incapacidad = $tiempo->pago_incapacidad_general;
                                                $incapacidad_laboral= $tiempo->pago_incapacidad_laboral;
                                            }   
                                        endforeach;
                                        if($contador == 1){                                       
                                            //incapacidad general
                                            if($codigo == 1 ){
                                                if($incapacidad != 100){
                                                    if($dias > 2){
                                                        $table->vlr_liquidado = $dias * $vlr_dia;    
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = round($dias - 2);  
                                                        $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                        $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                        $table->dias_administradora = $table->dias_cobro_eps;
                                                        $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                        $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);

                                                    }else{
                                                        $table->vlr_liquidado = round(($dias * $vlr_dia) * $incapacidad)/100; 
                                                        $table->vlr_hora =  $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = 0;  
                                                        $table->vlr_cobro_administradora = 0;
                                                        $table->vlr_saldo_administradora = 0;
                                                        $table->dias_administradora = 0;
                                                        $table->dias_empresa = $dias;
                                                        $table->vlr_pago_empresa = round(($dias * $vlr_dia) * $incapacidad)/100;
                                                    }
                                                }else{
                                                    if($dias > 2){
                                                        $table->vlr_liquidado = $dias * $vlr_dia;    
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = round($dias - 2);  
                                                        $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                        $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                        $table->dias_administradora = $table->dias_cobro_eps;
                                                        $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                        $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);

                                                    }else{
                                                        $table->vlr_liquidado = $dias * $vlr_dia; 
                                                        $table->vlr_hora =  $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = 0;  
                                                        $table->vlr_cobro_administradora = 0;
                                                        $table->vlr_saldo_administradora = 0;
                                                        $table->dias_administradora = 0;
                                                        $table->dias_empresa = $dias;
                                                        $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    }
                                                }
                                            }//termina incapacidad general
                                            // codigo para calculo de incapacidades laborales.
                                            if($codigo == 2 ){
                                                if($incapacidad_laboral != 100){
                                                    if($dias > 1){
                                                        $vlr_dia = ($contrato->salario/30);
                                                        $table->vlr_liquidado = $dias * $vlr_dia;    
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = round($dias - 1);  
                                                        $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                        $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                        $table->dias_administradora = $table->dias_cobro_eps;
                                                        $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                        $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);
                                                     }else{
                                                        $vlr_dia = ($contrato->salario/30);
                                                        $table->vlr_liquidado = round($dias * $vlr_dia); 
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = 0;  
                                                        $table->vlr_cobro_administradora = 0;
                                                        $table->vlr_saldo_administradora = 0;
                                                        $table->dias_administradora = 0;
                                                        $table->dias_empresa = $dias;
                                                        $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    }
                                                }else{
                                                    $vlr_dia = ($salario_minimo_actual/30);
                                                    if($dias > 1){
                                                        $table->vlr_liquidado = $dias * $vlr_dia;    
                                                        $table->vlr_hora = $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = round($dias - 1);  
                                                        $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                        $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                        $table->dias_administradora = $table->dias_cobro_eps;
                                                        $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                        $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);

                                                    }else{
                                                       $table->vlr_liquidado = $dias * $vlr_dia; 
                                                        $table->vlr_hora =  $auxiliar;
                                                        $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                        $table->dias_cobro_eps = 0;  
                                                        $table->vlr_cobro_administradora = 0;
                                                        $table->vlr_saldo_administradora = 0;
                                                        $table->dias_administradora = 0;
                                                        $table->dias_empresa = $dias;
                                                        $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    }
                                                }    
                                            }  
                                        }else{   
                                            if($codigo == 1 ){
                                                if($dias > 2){
                                                   $table->vlr_liquidado = $dias * $vlr_dia;    
                                                    $table->vlr_hora = $auxiliar;
                                                    $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                    $table->dias_cobro_eps = round($dias - 2);  
                                                    $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                    $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                    $table->dias_administradora = $table->dias_cobro_eps;
                                                    $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                    $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);
                                                    $table->ibc_total_incapacidad = round(($contrato->salario / 30)* ($dias));

                                                }else{
                                                    $table->vlr_liquidado = $dias * $vlr_dia; 
                                                    $table->vlr_hora =  $auxiliar;
                                                    $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                    $table->dias_cobro_eps = 0;  
                                                    $table->vlr_cobro_administradora = 0;
                                                    $table->vlr_saldo_administradora = 0;
                                                    $table->dias_administradora = 0;
                                                    $table->dias_empresa = $dias;
                                                    $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    $table->ibc_total_incapacidad = round($vlr_dia * $dias);
                                                }
                                             
                                            }//termina incapacidad general
                                            // codigo para calculo de incapacidades laborales.
                                            if($codigo == 2 ){
                                                 $vlr_dia = ($contrato->salario / 30);
                                                if($dias > 1){
                                                    $table->vlr_liquidado = $dias * $vlr_dia;    
                                                    $table->vlr_hora = $auxiliar;
                                                    $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                    $table->dias_cobro_eps = round($dias - 1);  
                                                    $table->vlr_cobro_administradora = round($table->dias_cobro_eps * $vlr_dia);
                                                    $table->vlr_saldo_administradora = $table->vlr_cobro_administradora;
                                                    $table->dias_administradora = $table->dias_cobro_eps;
                                                    $table->dias_empresa = $dias - $table->dias_cobro_eps;
                                                    $table->vlr_pago_empresa = round($table->dias_empresa * $vlr_dia);
                                                    $table->ibc_total_incapacidad = round(($contrato->salario / 30)* ($dias));
                                                 }else{
                                                    $table->vlr_liquidado = round($dias * $vlr_dia); 
                                                    $table->vlr_hora = $auxiliar;
                                                    $table->porcentaje_pago =  $configuracionincapacidad->porcentaje;
                                                    $table->dias_cobro_eps = 0;  
                                                    $table->vlr_cobro_administradora = 0;
                                                    $table->vlr_saldo_administradora = 0;
                                                    $table->dias_administradora = 0;
                                                    $table->dias_empresa = $dias;
                                                    $table->vlr_pago_empresa = round($dias * $vlr_dia);
                                                    $table->ibc_total_incapacidad = round(($contrato->salario / 30)* ($dias));
                                                }
                                            }  
                                        } 
                                       $table->save(false); 
                                       return $this->redirect(['index']);                    
                                    }//termina el ciclo de la entrada de la table
                            }//valida si este empleado tiene licencias    
                        }//valida que la fecha de inicio se mayor que la fecha final de la incapacidad    
                    }//valida que no ingresen incapacidades menores al ingreso del contrato    
                }//termina el ciclo que valide si esta relacionado en nomina.
            }else{
                $model->getErrors();
            }  
            //termina el validate
                       
        }
        if (Yii::$app->request->get("id")) {
              
                 $table = Incapacidad::find()->where(['id_incapacidad' => $id])->one();            
                if ($table) {     
                    $model->codigo_incapacidad = $table->codigo_incapacidad;
                    $model->id_empleado = $table->id_empleado;
                    $model->id_codigo = $table->id_codigo;
                    $model->numero_incapacidad = $table->numero_incapacidad;
                    $model->nombre_medico = $table->nombre_medico;
                    $model->fecha_inicio = $table->fecha_inicio;
                    $model->fecha_final =  $table->fecha_final;
                    $model->fecha_documento_fisico = $table->fecha_documento_fisico;
                    $model->fecha_aplicacion = $table->fecha_aplicacion;
                    $model->observacion = $table->observacion;
                    $model->transcripcion = $table->transcripcion;
                    $model->pagar_empleado = $table->pagar_empleado;
                    $model->cobrar_administradora = $table->cobrar_administradora;
                    $model->aplicar_adicional = $table->aplicar_adicional;
                    $model->prorroga = $table->prorroga;
                }else{
                     return $this->redirect(['index']);
                }
        } else {
                return $this->redirect(['index']);    
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    // crear nuevo seguimiento a la incapacidad
    public function actionNuevoseguimiento($id_incapacidad)
    {
        $model = new \app\models\FormIncapacidadSeguimiento();
        $incapacidades = Incapacidad::find()->all();         
        if ($model->load(Yii::$app->request->post())) {
            $incapacidad = Incapacidad::find()->where(['=','id_incapacidad',$model->id_incapacidad])->one();
            if (!$incapacidad){
                $table = new \app\models\SeguimientoIncapacidad();
                $table->id_incapacidad = $id_incapacidad;
                $table->nota = $model->nota;
                $table->user_name = Yii::$app->user->identity->username;   
                $table->save(false);
                $this->redirect(["incapacidad/view", 'id' => $id_incapacidad]);
            }else{                
                Yii::$app->getSession()->setFlash('error', 'El Número de la incapacidad no existe!');
            }
        }
        return $this->render('_formnuevoseguimiento', [
            'model' => $model,
          // '$incapacidades' => ArrayHelper::map($incapacidades, "id_incapacidad", ""),
            'id' => $id_incapacidad
        ]);
    }
    
    //editar el segumiento a la incapacidad
    public function actionEditarseguimiento($id_seguimiento, $id_incapacidad) {
       
        $model = new \app\models\FormIncapacidadSeguimiento();
        $incapacidad = Incapacidad::find()->all();        
        $seguimiento = \app\models\SeguimientoIncapacidad::findOne($id_seguimiento);
        if ($model->load(Yii::$app->request->post())) {                        
            $seguimiento->nota = $model->nota;
            $seguimiento->save(false);                                      
            return $this->redirect(['incapacidad/view','id' => $seguimiento->id_incapacidad]);
        }
        if (Yii::$app->request->get("id_seguimiento")) {
            $table = \app\models\SeguimientoIncapacidad::find()->where(['id_seguimiento' => $id_seguimiento])->one();
            if ($table) {
                $model->nota = $table->nota;
            }    
        }
        return $this->render('_formnuevoseguimiento', [
            'model' => $model,
            'id' => $id_incapacidad,
           ]);         
    } 
    
    /**
     * Deletes an existing Incapacidad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEliminar($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
            $this->redirect(["incapacidad/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["incapacidad/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
            $this->redirect(["incapacidad/index"]);
        }
    }

    //clase de fecha
    public function getFechaActual() {
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaActual =  $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        return $fechaActual;
    }
    
    /**
     * Finds the Incapacidad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Incapacidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Incapacidad::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
     public function actionExcelIncapacidad($tableexcel) {                
        $objPHPExcel = new \PHPExcel();
        // Set document properties
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true); 
        
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                    ->setCellValue('B1', 'Documento')
                    ->setCellValue('C1', 'Empleado')
                    ->setCellValue('D1', 'Grupo pago')
                    ->setCellValue('E1', 'Tipo incapacidad')
                    ->setCellValue('F1', 'Numero incapacidad')
                    ->setCellValue('G1', 'Cod. Diagnostico') 
                    ->setCellValue('H1', 'Diagnostico') 
                    ->setCellValue('I1', 'Nombre medico')
                    ->setCellValue('J1', 'F. Inicio')
                    ->setCellValue('K1', 'F. Final')
                    ->setCellValue('L1', 'Salario')
                    ->setCellValue('M1', 'Salario anterior')
                    ->setCellValue('N1', 'Dias incapacidad')
                    ->setCellValue('O1', 'Dia empresa')
                    ->setCellValue('P1', 'Dia administradora')
                    ->setCellValue('Q1', 'Vlr liquidado')
                    ->setCellValue('R1', 'Vlr cobro administradora')
                    ->setCellValue('S1', 'Pagar empleado')
                    ->setCellValue('T1', 'Prorroga')
                    ->setCellValue('U1', 'Transcripcion')
                    ->setCellValue('V1', 'Cobrar administradora')
                    ->setCellValue('W1', 'Pago empresa')
                    ->setCellValue('X1', 'Vlr hora')
                    ->setCellValue('Y1', 'Usuario')
                    ->setCellValue('Z1', 'Usuario editado')
                    ->setCellValue('AA1', 'F. Proceso')
                    ->setCellValue('AB1', 'Observacion');
                   
        $i = 2  ;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A' . $i, $val->id_incapacidad)
                    ->setCellValue('B' . $i, $val->identificacion)
                    ->setCellValue('C' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('D' . $i, $val->grupoPago->grupo_pago)
                    ->setCellValue('E' . $i, $val->codigoIncapacidad->nombre)
                    ->setCellValue('F' . $i, $val->numero_incapacidad)                    
                    ->setCellValue('G' . $i, $val->codigo_diagnostico)
                    ->setCellValue('H' . $i, $val->codigo->diagnostico)
                    ->setCellValue('I' . $i, $val->nombre_medico)
                    ->setCellValue('J' . $i, $val->fecha_inicio)
                    ->setCellValue('K' . $i, $val->fecha_final)
                    ->setCellValue('L' . $i, $val->salario)
                    ->setCellValue('M' . $i, $val->salario_mes_anterior)
                    ->setCellValue('N' . $i, $val->dias_incapacidad)
                    ->setCellValue('O' . $i, $val->dias_empresa)
                    ->setCellValue('P' . $i, $val->dias_administradora)
                    ->setCellValue('Q' . $i, $val->vlr_liquidado)
                    ->setCellValue('R' . $i, $val->vlr_cobro_administradora)
                    ->setCellValue('S' . $i, $val->pagarempleado)
                    ->setCellValue('T' . $i, $val->prorrogaIncapacidad)
                    ->setCellValue('U' . $i, $val->transcripcionincapacidad)
                    ->setCellValue('V' . $i, $val->vlr_saldo_administradora)
                    ->setCellValue('W' . $i, $val->vlr_pago_empresa)
                    ->setCellValue('X' . $i, $val->vlr_hora)
                    ->setCellValue('Y' . $i, $val->user_name)
                    ->setCellValue('Z' . $i, $val->user_name_editado)
                    ->setCellValue('AA' . $i, $val->fecha_creacion)
                    ->setCellValue('AB' . $i, $val->observacion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Incapacidades');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Incapacidades.xlsx"');
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
