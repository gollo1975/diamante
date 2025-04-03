<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\Html;
//models
use app\models\PrestacionesSociales;
use app\models\PrestacionesSocialesSearch;
use app\models\UsuarioDetalle;
use app\models\Credito;
use app\models\ConceptoSalarios;


/**
 * PrestacionesSocialesController implements the CRUD actions for PrestacionesSociales model.
 */
class PrestacionesSocialesController extends Controller
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
     * Lists all PrestacionesSociales models.
     * @return mixed
     */
     public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',154    ])->all()){
                $form = new \app\models\FormFiltroPrestaciones();
                $documento = null;
                $id_grupo_pago = null;
                $id_empleado = null; $desde = null;
                $pagina = 2; $hasta = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $id_empleado = Html::encode($form->id_empleado);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $table = PrestacionesSociales::find()
                                ->andFilterWhere(['like', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['between', 'fecha_creacion', $desde, $hasta])
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado]);
                        $table = $table->orderBy('id_prestacion DESC');
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
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelPrestaciones($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = PrestacionesSociales::find()
                            ->orderBy('id_prestacion desc');
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
                     if(isset($_POST['excel'])){                    
                            $this->actionExcelPrestaciones($tableexcel);
                        }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'pagina' => $pagina,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    /**
     * Lists all PrestacionesSociales models.
     * @return mixed
     */
     public function actionSearch_prestaciones($pagina = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',155])->all()){
                $form = new \app\models\FormFiltroPrestaciones();
                $documento = null;
                $id_grupo_pago = null;
                $id_empleado = null; $desde = null;
                $hasta = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $id_empleado = Html::encode($form->id_empleado);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $table = PrestacionesSociales::find()
                                ->andFilterWhere(['like', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['between', 'fecha_creacion', $desde, $hasta])
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])
                                ->andWhere(['=', 'estado_cerrado', 1]);
                        $table = $table->orderBy('id_prestacion DESC');
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
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelPrestaciones($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = PrestacionesSociales::find()->Where(['=', 'estado_cerrado', 1])
                            ->orderBy('id_prestacion desc');
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
                     if(isset($_POST['excel'])){                    
                            $this->actionExcelPrestaciones($tableexcel);
                        }
                }
                $to = $count->count();
                return $this->render('search_prestaciones', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'pagina' => $pagina,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }


    /**
     * Displays a single PrestacionesSociales model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $pagina)
    {
        $model = PrestacionesSociales::findOne($id);
        $detalle_prestacion = \app\models\PrestacionesSocialesDetalle::find()->where(['=','id_prestacion', $id])->all();
        $adicion_prestacion = \app\models\PrestacionesSocialesAdicion::find()->where(['=','id_prestacion', $id])->andWhere(['=','tipo_adicion', 1])->all();
        $descuento_prestacion = \app\models\PrestacionesSocialesAdicion::find()->where(['=','id_prestacion', $id])->andWhere(['=','tipo_adicion', 2])->all();
        $descuento_credito = \app\models\PrestacionesSocialesCreditos::find()->where(['=','id_prestacion', $id])->all();
        if (Yii::$app->request->post()) {
            if (isset($_POST["crear_prestaciones"])){
                if (isset($_POST["id_detalle"])) {
                    $year = 0;
                    $year = ($year == NULL)? date('Y'):$year;
                    if (($year%4 == 0 && $year%100 != 0) || $year%400 == 0 ){ //PROCESO QUE VALIDE SI EL AÑO ES VISCIESTO
                           $ano = 1;
                    }else{
                           $ano = 2;
                    } 
                    $intIndice = 0;
                    foreach ($_POST["id_detalle"] as $intCodigo) {
                       $modelo = \app\models\PrestacionesSocialesDetalle::findOne($intCodigo);
                       if($modelo->abreviatura == 'P'){
                          $total_dias = $this->CrearDias($modelo, $ano);
                          $this->AcumuladoPrimas($modelo, $model, $total_dias);
                       }
                       if($modelo->abreviatura == 'C'){
                          $total_dias = $this->CrearDias($modelo, $ano);
                          $this->AcumuladoCesantias($modelo, $model, $total_dias);
                       }
                       if($modelo->abreviatura == 'V'){
                          $total_dias = $this->CrearDias($modelo, $ano);
                          $this->AcumuladoVacacion($modelo, $model, $total_dias);
                       }
                       $intIndice++;
                    }
                    return $this->redirect(['view', 'model' => $model, 'id' => $id,  'pagina' =>$pagina,
                            'detalle_prestacion' => $detalle_prestacion, 'adicion_prestacion' => $adicion_prestacion,
                            'descuento_prestacion' => $descuento_prestacion,  'descuento_credito' => $descuento_credito,
                        ]);
                } else {
                    Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar al menos un registro para ejecutar el proceso.');
                }
            } 
        }
        return $this->render('view', [
            'model' => $model,
            'id' => $id,
            'pagina' =>$pagina,
            'detalle_prestacion' => $detalle_prestacion,
            'adicion_prestacion' => $adicion_prestacion,
            'descuento_prestacion' => $descuento_prestacion,
            'descuento_credito' => $descuento_credito,
        ]);
    }
    
      //PROCEESO QUE GENERA LOS DIAS DE CESANTIAS
     protected function CrearDias($modelo, $ano)
    {
        $mesInicio = 0;
        $anioTerminacion = 0;
        $mesTerminacion = 0;
        $anioInicio = 0;
        $diaTerminacion = 0;
        $diaInicio = 0;
        ///variables
        $fecha = date($modelo->fecha_inicio);
        $fecha_inicio_dias = strtotime('0 day', strtotime($fecha));
        $fecha_inicio_dias = date('Y-m-d', $fecha_inicio_dias);
        //codigo de fechas
        $fecha_inicio = $fecha_inicio_dias;
        $fecha_termino = $modelo->fecha_final;
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
           $total_dias = (($anios * 360) + ($meses * 30)+ ($dies + 1));
        }
         return ($total_dias);
       
    }
    
    //proceo para prima
    protected function AcumuladoPrimas($modelo, $model, $total_dias) {
        if($total_dias > 0){
            $contrato = \app\models\Contratos::findOne($model->id_contrato);
            $vector_nomina = \app\models\ProgramacionNomina::find()->where(['=','id_empleado', $model->id_empleado])
                                                               ->andWhere(['>=','fecha_desde', $model->ultimo_pago_prima])
                                                               ->andWhere(['=','id_tipo_nomina', 1])->all();
            
            //para recorrer el vector
            $acumulado = 0; $salario_promedio = 0; $valor_pago = 0;
            foreach ($vector_nomina as $valores) {
                $acumulado += $valores->ibc_prestacional;
            }
            //valida el auxilio
           
            
            if($acumulado > 0){
                if($contrato->fecha_inicio <> $contrato->fecha_final){
                    if ($contrato->id_tipo_salario == 1){
                        $salario_promedio = $contrato->salario;
                    }else{
                        $salario_promedio = round($acumulado / $total_dias)*30;
                    }
                }else{
                    $salario_promedio = $contrato->salario;
                }
            }else{
                $salario_promedio = $contrato->salario;
            }    
                
           
            //auxilio
           
            if($modelo->abreviatura == 'P'){ //primas
                $concepto = \app\models\ConfiguracionPrestaciones::findOne(1);
                $auxilio = \app\models\ConfiguracionSalario::find()->where(['=','estado', 1])->one();
                if ($contrato->aplica_auxilio_transporte == 1){
                     $valor_pago = round((($salario_promedio + $auxilio->auxilio_transporte_actual) * $total_dias) / 360);
                }else{
                    $valor_pago = round(($salario_promedio * $total_dias) / 360);
                }
            }
            $modelo->nro_dias = $total_dias;
            $modelo->dias_ausentes = 0;
            $modelo->salario_promedio_prima = $salario_promedio;
            $modelo->auxilio_transporte = $auxilio->auxilio_transporte_actual;
            $modelo->total_dias = $total_dias;
            $modelo->valor_pagar = $valor_pago;
            $modelo->save();
            $model->valor_pago_primas = $valor_pago;
            $model->save();
           
        }//fin para
        
    }
    
    //proceso para cesantias e intereses
    protected function AcumuladoCesantias($modelo, $model, $total_dias) {
        if($total_dias > 0){
            $contrato = \app\models\Contratos::findOne($model->id_contrato);
            $vector_nomina = \app\models\ProgramacionNomina::find()->where(['=','id_empleado', $model->id_empleado])
                                                               ->andWhere(['>=','fecha_desde', $model->ultimo_pago_cesantias])
                                                               ->andWhere(['=','id_tipo_nomina', 1])->all();
            
            //para recorrer el vector
            $acumulado = 0; $salario_promedio = 0; $valor_pago = 0; $dias_ausentes = 0; $total_dia_real;
            foreach ($vector_nomina as $valores) {
                $acumulado += $valores->ibc_prestacional;
            }
            if($acumulado > 0){
                if($contrato->fecha_inicio <> $contrato->fecha_final){
                    if ($contrato->id_tipo_salario == 1){
                        $salario_promedio = $contrato->salario;
                    }else{
                        $salario_promedio = round($acumulado / $total_dias)*30;
                    }
                }else{
                    $salario_promedio = $contrato->salario;
                }
            }else{
                $salario_promedio = $contrato->salario;
            } 
           
            //auxilio
            $concepto = \app\models\ConfiguracionPrestaciones::findOne(2);
            $auxilio = \app\models\ConfiguracionSalario::find()->where(['=','estado', 1])->one();
            
            //dias ausentes
            if($concepto->aplicar_ausentismo == 1){
                $bucarLicencia = \app\models\Licencia::find()->where(['>=','fecha_desde', $model->ultimo_pago_cesantias])
                                                             ->andWhere(['=','id_empleado', $model->id_empleado])
                                                             ->andWhere(['=','codigo_licencia', 2])->all();
                foreach ($bucarLicencia as $val) {
                    $dias_ausentes += $val->dias_licencia;
                }
            }else{
                $dias_ausentes = 0;
            }
            $total_dia_real = $total_dias - $dias_ausentes;
            
            if ($contrato->aplica_auxilio_transporte == 1){
                 $valor_pago = round((($salario_promedio + $auxilio->auxilio_transporte_actual) * $total_dia_real) / 360);
            }else{
                $valor_pago = round(($salario_promedio * $total_dia_real) / 360);
            }
           
            $modelo->nro_dias = $total_dias;
            $modelo->dias_ausentes = $dias_ausentes;
            $modelo->salario_promedio_prima = $salario_promedio;
            $modelo->auxilio_transporte = $auxilio->auxilio_transporte_actual;
            $modelo->total_dias = $total_dia_real;
            $modelo->valor_pagar = $valor_pago;
            $modelo->save();
            $model->valor_pago_cesantias = $valor_pago;
            $model->save();
            
            //datos de los intereses
            $porcentaje = 0; $interes = 0;
            $porcentaje = ($total_dia_real * 12)/360;
            $interes = round(($valor_pago * $porcentaje)/100);
            $linea = \app\models\PrestacionesSocialesDetalle::find()->where(['=','id_prestacion', $model->id_prestacion])->andWhere(['=','abreviatura', 'I'])->one();
            $linea->nro_dias = $total_dia_real;
            $linea->dias_ausentes = $dias_ausentes;
            $linea->salario_promedio_prima = $valor_pago;
            $linea->auxilio_transporte = 0;
            $linea->total_dias = $total_dia_real;
            $linea->valor_pagar = $interes;
            $linea->save();
            
        }//fin para
        
    }
    
    //proceso de vacaciones
    protected function AcumuladoVacacion($modelo, $model, $total_dias) {
        if($total_dias > 0){
            $contrato = \app\models\Contratos::findOne($model->id_contrato);
            $vector_nomina = \app\models\ProgramacionNomina::find()->where(['=','id_empleado', $model->id_empleado])
                                                               ->andWhere(['>=','fecha_desde', $model->ultimo_pago_vacaciones])
                                                               ->andWhere(['=','id_tipo_nomina', 1])->all();
            
            //para recorrer el vector
            $acumulado = 0; $salario_promedio = 0; $valor_pago = 0; $ibc_recargo = 0;
            foreach ($vector_nomina as $valores) {
                $acumulado += $valores->total_recargo;
            }
            $ibc_recargo = $acumulado / $total_dias;
            $concepto = \app\models\ConfiguracionPrestaciones::findOne(4);
            if ($concepto->aplica_recargo_vacacion == 1){
                $salario_promedio = ($contrato->salario + $ibc_recargo) ;
            }else{
                $salario_promedio = $contrato->salario;
            }
           ///guarda la informaicon
            $valor_pago = round(($salario_promedio * $total_dias)/ 720);
            $modelo->nro_dias = $total_dias;
            $modelo->dias_ausentes = 0;
            $modelo->salario_promedio_prima = $salario_promedio;
            $modelo->auxilio_transporte = 0;
            $modelo->total_dias = $total_dias;
            $modelo->valor_pagar = $valor_pago;
            $modelo->save();
            $model->valor_pago_vacaciones = $valor_pago;
            $model->save();
           
        }//fin para
        
    }

    //adiciona de salario
    public function actionAdicionsalario($id, $pagina)
    {
        $model = new \app\models\FormAdicionPrestaciones();        
        $tipo_adicion = 1;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {            
           if ($model->validate()) {
                $table = new \app\models\PrestacionesSocialesAdicion();
                $table->id_prestacion = $id;
                $table->codigo_salario = $model->codigo_salario;
                $table->tipo_adicion = $tipo_adicion;
                $table->valor_adicion = $model->valor_adicion;
                $table->observacion = $model->observacion;
                $table->usuariosistema = Yii::$app->user->identity->username;
                $table->insert();
                $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);
            } else {
                $model->getErrors();
            }
        }
        return $this->render('_form_adicion', ['model' => $model, 'id' => $id, 'tipo_adicion' => $tipo_adicion, 'pagina' => $pagina]);
        
    }
    
    /**
     * Creates a new PrestacionesSociales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGenerar_conceptos($id, $pagina)
    {
        //tablas de configuracion
        $model = PrestacionesSociales::find()->where(['=','id_prestacion', $id])->one();
        $primas = \app\models\ConfiguracionPrestaciones::findOne(1);
        $concepto_prima = ConceptoSalarios::find()->where(['=','concepto_prima', 1])->one();
        $cesantias = \app\models\ConfiguracionPrestaciones::findOne(2);
        $concepto_cesantia = ConceptoSalarios::find()->where(['=','concepto_cesantias', 1])->one();
        $vacaciones = \app\models\ConfiguracionPrestaciones::findOne(4);
        $intereses = \app\models\ConfiguracionPrestaciones::findOne(3);
        $concepto_vacacion = ConceptoSalarios::find()->where(['=','concepto_vacacion', 1])->one();
        $contrato_trabajo = \app\models\Contratos::find()->where(['=','id_contrato', $model->id_contrato])->one();
       
        //PROCESO PARA PRIMA
     
        if($primas->codigo_salario ==  $concepto_prima->codigo_salario){
            $detalle = \app\models\PrestacionesSocialesDetalle::find()->where(['=','codigo_salario', $primas->codigo_salario])
                                                                      ->andWhere(['=','id_prestacion', $id])->one();
            if(!$detalle){
                $table = new \app\models\PrestacionesSocialesDetalle();
                $table->id_prestacion = $id;
                $table->codigo_salario = $concepto_prima->codigo_salario;
                if($contrato_trabajo->fecha_inicio > $contrato_trabajo->ultima_pago_prima){
                    $table->fecha_inicio = $contrato_trabajo->fecha_inicio; 
                }else{
                   $table->fecha_inicio = $model->ultimo_pago_prima;
                }
                $table->fecha_final = $model->fecha_termino_contrato;
                $table->abreviatura = 'P';
                $table->save(false);
            }
                
           
        }
        
        //PROCESO PARA CESANTIAS
        if($cesantias->codigo_salario ==  $concepto_cesantia->codigo_salario){
            $detalle = \app\models\PrestacionesSocialesDetalle::find()->where(['=','codigo_salario', $cesantias->codigo_salario])
                                                                      ->andWhere(['=','id_prestacion', $id])->one();
            if(!$detalle){
                $table = new \app\models\PrestacionesSocialesDetalle();
                $table->id_prestacion = $id;
                $table->codigo_salario = $concepto_cesantia->codigo_salario;
                if($contrato_trabajo->fecha_inicio > $contrato_trabajo->ultima_pago_cesantia){
                    $table->fecha_inicio = $contrato_trabajo->fecha_inicio; 
                }else{
                    $table->fecha_inicio = $model->ultimo_pago_cesantias;
                }    
                $table->fecha_final = $model->fecha_termino_contrato;
                $table->abreviatura = 'C';
                $table->save(false);
                
                $table2 = new \app\models\PrestacionesSocialesDetalle();
                $table2->id_prestacion = $id;
                $table2->codigo_salario = $intereses->codigo_salario;
                if($contrato_trabajo->fecha_inicio > $contrato_trabajo->ultima_pago_cesantia){
                    $table2->fecha_inicio = $contrato_trabajo->fecha_inicio; 
                }else{
                    $table2->fecha_inicio = $model->ultimo_pago_cesantias;
                }    
                $table2->fecha_final = $model->fecha_termino_contrato;
                $table2->abreviatura = 'I';
                $table2->save(false);
            }
          
   
        }
        //PROCESO PARA VACACIONES
        if($vacaciones->codigo_salario ==  $concepto_vacacion->codigo_salario){
             $vaca = \app\models\PrestacionesSocialesDetalle::find()->where(['=','codigo_salario', $vacaciones->codigo_salario])
                                                                      ->andWhere(['=','id_prestacion', $id])->one();
            if(!$vaca){
                $contrato = \app\models\Contratos::findOne($model->id_contrato);
                $table = new \app\models\PrestacionesSocialesDetalle();
                $table->id_prestacion = $id;
                $table->codigo_salario = $concepto_vacacion->codigo_salario;
                $table->fecha_inicio = $contrato_trabajo->ultima_pago_vacacion; 
                $table->fecha_final = $model->fecha_termino_contrato;
                $table->abreviatura = 'V';
                $table->save(false);
            }
            
        }
        $model->estado_generado = 1;
        $model->save(false);
         
        $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'model' => $model, 'pagina' => $pagina]);
       
       
    }
  
     
    
    /**
     * Updates an existing PrestacionesSociales model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $id_adicion, $tipo_adicion, $pagina)
    {
        $model = new \app\models\FormAdicionPrestaciones();
        $table = \app\models\PrestacionesSocialesAdicion::find()->where(['id_adicion' => $id_adicion])->one(); 
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {            
            $table = \app\models\PrestacionesSocialesAdicion::find()->where(['id_adicion'=>$id_adicion])->one();
            if ($table) {
                $table->codigo_salario = $model->codigo_salario;
                $table->valor_adicion = $model->valor_adicion;
                $table->observacion = $model->observacion;
                $table->save(false);
               return $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);
            }
        }
        if (Yii::$app->request->get("id_adicion")) {
              
                           
                if ($table) {     
                    $model->codigo_salario = $table->codigo_salario;
                    $model->tipo_adicion = $table->tipo_adicion;
                    $model->valor_adicion = $table->valor_adicion;
                    $model->observacion =  $table->observacion;
                }else{
                     return $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina,]);
                }
        } else {
                 return $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina,]); 
        }
        return $this->render('update', [
            'model' => $model, 'id' => $id, 'tipo_adicion'=>$tipo_adicion, 'pagina' => $pagina, 'table' => $table
        ]);
    }
    
    //permite subir los descuento a las prestaciones sociales
    public function actionDescuento($id, $pagina)
    {
        $model = new \app\models\FormAdicionPrestaciones();        
        $tipo_adicion = 2;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {            
           if ($model->validate()) {
                $table = new \app\models\PrestacionesSocialesAdicion();
                $table->id_prestacion = $id;
                $table->codigo_salario = $model->codigo_salario;
                $table->tipo_adicion = $tipo_adicion;
                $table->valor_adicion = $model->valor_adicion;
                $table->observacion = $model->observacion;
                $table->usuariosistema = Yii::$app->user->identity->username;
                $table->insert();
                $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);
            } else {
                $model->getErrors();
            }
        }
        return $this->render('_form_adicion', ['model' => $model, 'id' => $id, 'tipo_adicion' => $tipo_adicion, 'pagina' => $pagina]);
        
    }
    
    //DESCUENTOS SI TIENE CREDITOS PARA SER DESCARGADO DE LAS PRESTACIONES
     public function actionDescuentocredito($id_empleado, $id, $pagina)
    {
        $credito = Credito::find()->where(['=','id_empleado', $id_empleado])->andWhere(['>','saldo_credito', 0])->all();
        
        if (isset($_POST["idcredito"])) {
                $intIndice = 0;
                foreach ($_POST["idcredito"] as $intCodigo) {
                    $table = new \app\models\PrestacionesSocialesCreditos();
                    $credito_consulta = Credito::find()->where(['id_credito' => $intCodigo])->one();
                    $detalle_credito = \app\models\PrestacionesSocialesCreditos::find()
                        ->where(['=', 'id_prestacion', $id])
                        ->andWhere(['=', 'id_credito', $credito_consulta->id_credito])
                        ->all();
                   
                    if (!$detalle_credito) {
                        $table->id_credito = $credito_consulta->id_credito;
                        $table->id_prestacion = $id;
                        $concepto = \app\models\ConfiguracionCredito::find()->where(['=','codigo_credito', $credito_consulta->codigo_credito])->one();
                        $table->codigo_salario = $concepto->codigo_salario;
                        $table->valor_credito = $credito_consulta->valor_credito;
                        $table->saldo_credito = $credito_consulta->saldo_credito;
                        $table->deduccion = $credito_consulta->saldo_credito;
                        $table->estado_cerrado = 0;
                        $table->fecha_inicio = $credito_consulta->fecha_inicio;
                        $table->usuariosistema = Yii::$app->user->identity->username;
                        $table->save();                                                
                    }
                }
                $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);
        }
        return $this->render('_consultacredito', [
            'credito' => $credito, 
            'id_empleado' => $id_empleado,
            'id' => $id,
            'pagina' => $pagina,
        ]);
    }
    
    //PROCESO QUE EDITA EL CREDITO EN LAS PRESTACIONES
    public function actionEditarcredito($id_credito, $id, $pagina)
    {
        $model = new \app\models\FormDeduccionCredito();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {            
            if ($model->validate()) {
                $credito = \app\models\PrestacionesSocialesCreditos::findOne($id_credito);
                if (isset($_POST["actualizar"])) {  
                        $credito->deduccion = $model->deduccion;
                        $credito->save(false);
                        $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);                                                     
                }
            }
        }
        if (Yii::$app->request->get("id")) {
            $table = \app\models\PrestacionesSocialesCreditos::find()->where(['id' => $id_credito])->one();            
            if ($table) {                                
                $model->id_credito = $table->id_credito;                
            }
        }
        
        return $this->renderAjax('_editarcredito', ['model' => $model, 'id' => $id, 'pagina' => $pagina]);
    }
    
    public function actionDesgenerar($id, $pagina)
    {
      $model = PrestacionesSociales::find()->where(['=','id_prestacion', $id])->one();
      $model->estado_generado = 0;
      $model->save(false);
     $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina,
          'model' => $model,
          ]);
    }
    
    //PROCESO QUE DESGENERA EL APLCADO
     public function actionDesgeneraraplicar($id, $pagina)
    {
      $model = PrestacionesSociales::find()->where(['=','id_prestacion', $id])->one();
      $model->estado_aplicado = 0;
      $model->save(false);
     $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina,
          'model' => $model,
          ]);
    }
    
     // contralador que aplica los pagos, saldos y netos a pagar
     public function actionAplicarpagos($id, $pagina)
     {
        $model_prestacion = PrestacionesSociales::findOne($id);
        $model = \app\models\PrestacionesSocialesDetalle::find()->where(['=','id_prestacion', $id])->orderBy('codigo_salario ASC')->all();
        $model_credito = \app\models\PrestacionesSocialesCreditos::find()->where(['=','id_prestacion', $id])->all();
        $model_adicion = \app\models\PrestacionesSocialesAdicion::find()->where(['=','id_prestacion', $id])->andWhere(['=','tipo_adicion', 1])->all();
        $model_descuento = \app\models\PrestacionesSocialesAdicion::find()->where(['=','id_prestacion', $id])->andWhere(['=','tipo_adicion', 2])->all();
        $total_prestacion = 0;
        $total_deduccion_credito = 0;
        $total_adicion = 0;
        $deduccion_descuento = 0;
        //codigo que actualiza los fechas
        foreach ($model as $actualizar):
            if($actualizar->abreviatura == 'P'){
                $model_prestacion->dias_primas = $actualizar->total_dias;
                $model_prestacion->ibp_prima = $actualizar->salario_promedio_prima;
                $model_prestacion->dias_ausencia_prima = $actualizar->dias_ausentes;
                $model_prestacion->save(false);
            }
            if($actualizar->abreviatura == 'C'){
                $model_prestacion->dias_cesantias = $actualizar->total_dias;
                $model_prestacion->ibp_cesantias = $actualizar->salario_promedio_prima;
                $model_prestacion->dias_ausencia_cesantias = $actualizar->dias_ausentes;
                $model_prestacion->save(false);
            }
            if($actualizar->abreviatura == 'I'){
                $model_prestacion->interes_cesantia = $actualizar->valor_pagar;
                $model_prestacion->save(false);
            }
            if($actualizar->abreviatura == 'V'){
                $model_prestacion->dias_vacaciones = $actualizar->total_dias;
                $model_prestacion->ibp_vacaciones = $actualizar->salario_promedio_prima;
                $model_prestacion->dias_ausencia_vacaciones = $actualizar->dias_ausentes;
                $model_prestacion->save(false);
            }
            
        endforeach;
        
         //calcula todas la prestaciones
        foreach ($model as $calcular):
             $total_prestacion +=  $calcular->valor_pagar;
        endforeach;
        
         //calculos los creditos
        foreach ($model_credito as $calcular_credito):
             $total_deduccion_credito +=  $calcular_credito->deduccion;
        endforeach;
        //calculos las adiciones
        foreach ($model_adicion as $calcular_adicion):
            $total_adicion +=  $calcular_adicion->valor_adicion;
        endforeach;
         //calculos descuentos
        foreach ($model_descuento as $calcular_descuento):
            $deduccion_descuento +=  $calcular_descuento->valor_adicion;
        endforeach;
        
        //codigo que actualizada
        $model_prestacion->total_devengado = $total_prestacion + $model_prestacion->total_indemnizacion + $total_adicion;
        $model_prestacion->total_deduccion = $total_deduccion_credito + $deduccion_descuento;
        $model_prestacion->total_pagar = $model_prestacion->total_devengado - $model_prestacion->total_deduccion;
        $model_prestacion->estado_aplicado = 1;
        $model_prestacion->save(false);
        $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);  
     }

    /**
     * Deletes an existing PrestacionesSociales model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEliminar($id) {
        if (Yii::$app->request->post()) {
            $prestacion = PrestacionesSociales::findOne($id);
            if ((int) $id) {
                try {
                    PrestacionesSociales::deleteAll("id_prestacion=:id_prestacion", [":id_prestacion" => $id]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    $this->redirect(["prestaciones-sociales/index"]);
                } catch (IntegrityException $e) {
                    $this->redirect(["prestaciones-sociales/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, las prestación Nro ' . $prestacion->id_prestacion . ' tiene registros asociados en otros procesos');
                } catch (\Exception $e) {

                    $this->redirect(["prestaciones-sociales/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, las prestación Nro  ' . $prestacion->id_prestacion . ' tiene registros asociados en otros procesos');
                }
            } else {
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("prestaciones-sociales/index") . "'>";
            }
        } else {
            return $this->redirect(["prestaciones-sociales/index"]);
        }
    }
    
    public function actionEliminar_detalle_prestacion($id_detalle,$id, $pagina) {
        if (Yii::$app->request->post()) {
            if ((int) $id_detalle) {
                try {
                    \app\models\PrestacionesSocialesDetalle::deleteAll("id=:id", [":id" => $id_detalle]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    $this->ActualizacionTotalesPrestacion($id);
                    return $this->redirect(["prestaciones-sociales/view",'id' => $id , 'pagina' => $pagina]);
                } catch (IntegrityException $e) {
                    
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, tiene registros asociados en otros procesos');
                    return $this->redirect(["prestaciones-sociales/view",'id' => $id , 'pagina' => $pagina]);
                } catch (\Exception $e) {
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, tiene registros asociados en otros procesos');
                     return $this->redirect(["prestaciones-sociales/view",'id' => $id , 'pagina' => $pagina]);
                }
            } else {
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute(["prestaciones-sociales/view",'id' => $id , 'pagina' => $pagina]) . "'>";
            }
        } else {
            return $this->redirect(["prestaciones-sociales/view",'id' => $id , 'pagina' => $pagina]);
        }
    }
    
    protected function ActualizacionTotalesPrestacion($id) {
        $model_prestacion = PrestacionesSociales::findOne($id);
        $model = \app\models\PrestacionesSocialesDetalle::find()->where(['=','id_prestacion', $id])->orderBy('codigo_salario ASC')->all();
        $model_credito = \app\models\PrestacionesSocialesCreditos::find()->where(['=','id_prestacion', $id])->all();
        $model_adicion = \app\models\PrestacionesSocialesAdicion::find()->where(['=','id_prestacion', $id])->andWhere(['=','tipo_adicion', 1])->all();
        $model_descuento = \app\models\PrestacionesSocialesAdicion::find()->where(['=','id_prestacion', $id])->andWhere(['=','tipo_adicion', 2])->all();
        $total_prestacion = 0;
        $total_deduccion_credito = 0;
        $total_adicion = 0;
        $deduccion_descuento = 0;
        //codigo que actualiza los fechas
        foreach ($model as $actualizar):
            if($actualizar->abreviatura == 'P'){
                $model_prestacion->dias_primas = $actualizar->total_dias;
                $model_prestacion->ibp_prima = $actualizar->salario_promedio_prima;
                $model_prestacion->dias_ausencia_prima = $actualizar->dias_ausentes;
                $model_prestacion->save(false);
            }
            if($actualizar->abreviatura == 'C'){
                $model_prestacion->dias_cesantias = $actualizar->total_dias;
                $model_prestacion->ibp_cesantias = $actualizar->salario_promedio_prima;
                $model_prestacion->dias_ausencia_cesantias = $actualizar->dias_ausentes;
                $model_prestacion->save(false);
            }
            if($actualizar->abreviatura == 'I'){
                $model_prestacion->interes_cesantia = $actualizar->valor_pagar;
                $model_prestacion->save(false);
            }
            if($actualizar->abreviatura == 'V'){
                $model_prestacion->dias_vacaciones = $actualizar->total_dias;
                $model_prestacion->ibp_vacaciones = $actualizar->salario_promedio_prima;
                $model_prestacion->dias_ausencia_vacaciones = $actualizar->dias_ausentes;
                $model_prestacion->save(false);
            }
            
        endforeach;
        
         //calcula todas la prestaciones
        foreach ($model as $calcular):
             $total_prestacion +=  $calcular->valor_pagar;
        endforeach;
        
         //calculos los creditos
        foreach ($model_credito as $calcular_credito):
             $total_deduccion_credito +=  $calcular_credito->deduccion;
        endforeach;
        //calculos las adiciones
        foreach ($model_adicion as $calcular_adicion):
            $total_adicion +=  $calcular_adicion->valor_adicion;
        endforeach;
         //calculos descuentos
        foreach ($model_descuento as $calcular_descuento):
            $deduccion_descuento +=  $calcular_descuento->valor_adicion;
        endforeach;
        
        //codigo que actualizada
        $model_prestacion->total_devengado = $total_prestacion + $model_prestacion->total_indemnizacion + $total_adicion;
        $model_prestacion->total_deduccion = $total_deduccion_credito + $deduccion_descuento;
        $model_prestacion->total_pagar = $model_prestacion->total_devengado - $model_prestacion->total_deduccion;
        $model_prestacion->save(false);
        
    }
    
    //PEMITE ELIMINAR EL REGISTRO DE LA ADICION
    public function actionEliminaradicion($id, $id_adicion, $pagina) {
        if (Yii::$app->request->post()) {
            $adicion = \app\models\PrestacionesSocialesAdicion::findOne($id_adicion);
            if ((int) $id_adicion) {
                try {
                    \app\models\PrestacionesSocialesAdicion::deleteAll("id_adicion=:id_adicion", [":id_adicion" => $id_adicion]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);
                } catch (IntegrityException $e) {
                    $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, esta asociado a otro proceso');
                } catch (\Exception $e) {

                   $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, esta asociado a otro proceso');
                }
            } else {
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute(["prestaciones-sociales/view,'id' => $id, 'pagina' => $pagina"]) . "'>";
            }
        } else {
            return $this->redirect(["prestaciones-sociales/view",'id'=>$id, 'pagina' => $pagina]);
        }
    }

    //PERMITE CREAR CONSECUTIVO Y CERRAR LAS PRESTACIONES
    public function actionCerrarprestacion($id, $pagina)
    {
        //este codigo salda los creditos y hace el abono
        $credito = \app\models\PrestacionesSocialesCreditos::find()->where(['=','id_prestacion', $id])->andWhere(['=','estado_cerrado', 0])->all();
        $total = count($credito);
        if($total > 0){
            foreach ($credito as $creditoprestacion){
                $abono = new \app\models\AbonoCredito();
                $abono->id_credito = $creditoprestacion->id_credito;
                $abono->id_tipo_pago = 4;
                $abono->valor_abono = $creditoprestacion->deduccion;
                $abono->saldo = $creditoprestacion->saldo_credito - $abono->valor_abono;
                $abono->cuota_pendiente = 0;
                $abono->observacion = 'Deducción por prestaciones';
                $abono->user_name = Yii::$app->user->identity->username;
                $abono->insert(false);
                $credito_actualizar = Credito::findOne($creditoprestacion->id_credito);
                $credito_actualizar->saldo_credito = $abono->saldo;
                $credito_actualizar->estado_credito = 0;
                $credito_actualizar->observacion = 'Se cancelo por prestaciones';
                $credito_actualizar->save(false);
                $creditoprestacion->estado_cerrado = 1;
                $creditoprestacion->save(false);
            }
        }
        
        // este codigo genera el consecutivo de las prestaciones.   
        $modelo = PrestacionesSociales::findOne($id);
        $consecutivo = \app\models\Consecutivos::findOne(29);
        $consecutivo->numero_inicial++;
        $consecutivo->save(false);
        $modelo->nro_pago = $consecutivo->numero_inicial;
        $modelo->estado_cerrado = 1;
        $modelo->save(false);
        
        //codigo que actualiza el contrato
        $contrato = \app\models\Contratos::find()->where(['=','id_contrato', $modelo->id_contrato])->one();
        $contrato->ultima_pago_cesantia = $modelo->fecha_termino_contrato;
        $contrato->ultima_pago_vacacion = $modelo->fecha_termino_contrato;
        $contrato->ultima_pago_prima = $modelo->fecha_termino_contrato;
        $contrato->ibp_cesantia_inicial = 0;
        $contrato->ibp_prima_inicial = 0;
        $contrato->ibp_recargo_nocturno = 0;
        $contrato->save(false);
        
        $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);  
    }    
    
    //EDITAR LINEAS DE PRESTACIONES
    public function actionEditarconcepto($id, $id_adicion, $codigo, $pagina)
    {
        $model = new \app\models\FormParametroPrestaciones();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {            
            $table = \app\models\PrestacionesSocialesDetalle::find()->where(['id_prestacion' =>$id])->andWhere(['=','codigo_salario', $codigo])->one();
            if ($table) {
                $valor_pagar = 0;
                $interes = 0;
                $sw = 0;
                $prestacion = \app\models\ConfiguracionPrestaciones::find()->where(['=','codigo_salario', $codigo])->one();
             
                if($prestacion->id_prestacion == 1){
                    $valor_pagar = round((($model->salario_promedio_prima + $model->auxilio_transporte)* $model->total_dias)/360);
                }
                if($prestacion->id_prestacion == 2){
                    $valor_pagar = round((($model->salario_promedio_prima + $model->auxilio_transporte)* $model->total_dias)/360);
                    $porcentaje = (12 * $model->nro_dias)/360;
                    $interes = round(($valor_pagar * $porcentaje)/100);
                    $sw = 1;
                }
               
                if($prestacion->id_prestacion == 4){
                   $valor_pagar = round(($model->salario_promedio_prima * $model->total_dias)/720);
                }
                $table->nro_dias = $model->nro_dias;
                $table->dias_ausentes = $model->dias_ausentes;
                $table->salario_promedio_prima = $model->salario_promedio_prima;
                $table->total_dias = $model->total_dias;
                $table->auxilio_transporte = $model->auxilio_transporte;
                $table->valor_pagar = $valor_pagar;
                $table->save(false);
                if($sw == 1){
                    $intereses = \app\models\PrestacionesSocialesDetalle::find()->where(['id_prestacion' =>$id])->andWhere(['=','abreviatura', 'I'])->one();
                    $intereses->nro_dias = $model->nro_dias;
                    $intereses->dias_ausentes = $model->dias_ausentes;
                    $intereses->salario_promedio_prima = $valor_pagar;
                    $intereses->total_dias = $model->total_dias;
                    $intereses->auxilio_transporte = 0;
                    $intereses->valor_pagar = $interes;
                    $intereses->save(false);
                }
               
                return $this->redirect(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]);
            }
        }
        if (Yii::$app->request->get("id_adicion")) {
            $table = \app\models\PrestacionesSocialesDetalle::find()->where(['id' => $id_adicion])->one();            
            if ($table) {     
                $model->nro_dias = $table->nro_dias;
                $model->dias_ausentes = $table->dias_ausentes;
                $model->salario_promedio_prima = $table->salario_promedio_prima;
                $model->total_dias =  $table->total_dias;
                $model->auxilio_transporte =  $table->auxilio_transporte;
            }
        }
       return $this->render('_editarprestaciones', [
            'model' => $model, 'id' => $id, 'pagina' => $pagina,  
        ]);
    }
    
    //PROCESO DE IMPRESIONES
        
     public function actionImprimir($id)
    {
                                
        return $this->render('../formatos/prestacionesSociales', [
            'model' => $this->findModel($id),
            
        ]);
    }
    
    /**
     * Finds the PrestacionesSociales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrestacionesSociales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrestacionesSociales::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //EXCELES
       public function actionExcelPrestaciones($tableexcel) {
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
                            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'No PRESTACION')
                    ->setCellValue('C1', 'CEDULA')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CARGO')
                    ->setCellValue('F1', 'GRUPO PAGO')
                    ->setCellValue('G1', 'NRO CONTRATO')
                    ->setCellValue('H1', 'F. INICIO')
                    ->setCellValue('I1', 'F. RETIRO')   
                    ->setCellValue('J1', 'F. CREACION')
                    ->setCellValue('K1', 'ULT. PAGO PRIMA')
                    ->setCellValue('L1', 'SALARIO')
                    ->setCellValue('M1', 'INTERESES')
                    ->setCellValue('N1', 'DIAS PRIMAS')
                    ->setCellValue('O1', 'VL. PRIMAS')
                    ->setCellValue('P1', 'DIAS CESANTIAS')
                    ->setCellValue('Q1', 'VL. CESANTIAS')
                    ->setCellValue('R1', 'DIAS VACACIONES')
                    ->setCellValue('S1', 'VR. VACACIONES')
                    ->setCellValue('T1', 'T. INDEMNIZACION')
                    ->setCellValue('U1', 'T.DEVENGADO')
                    ->setCellValue('V1', 'T. DEDUCCION')
                    ->setCellValue('W1', 'T. PAGAR')
                    ->setCellValue('X1', 'USER NAME');
        $i = 2;
        
        foreach ($tableexcel as $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_prestacion)
                    ->setCellValue('B' . $i, $val->nro_pago)
                    ->setCellValue('C' . $i, $val->documento)
                    ->setCellValue('D' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('E' . $i, $val->contrato->cargo->nombre_cargo)
                    ->setCellValue('F' . $i, $val->grupoPago->grupo_pago)                    
                    ->setCellValue('G' . $i, $val->id_contrato)
                    ->setCellValue('H' . $i, $val->fecha_inicio_contrato)
                    ->setCellValue('I' . $i, $val->fecha_termino_contrato)
                    ->setCellValue('J' . $i, $val->fecha_creacion)
                    ->setCellValue('K' . $i, $val->ultimo_pago_prima)
                    ->setCellValue('L' . $i, round($val->salario,0))
                    ->setCellValue('M' . $i, round($val->interes_cesantia,0))
                    ->setCellValue('N' . $i, $val->dias_primas)
                    ->setCellValue('O' . $i, round($val->valor_pago_primas,0))
                    ->setCellValue('P' . $i, $val->dias_cesantias)
                    ->setCellValue('Q' . $i, round($val->valor_pago_cesantias,0))
                    ->setCellValue('R' . $i, $val->dias_vacaciones)
                    ->setCellValue('S' . $i, round($val->valor_pago_vacaciones,0))
                    ->setCellValue('T' . $i, round($val->total_indemnizacion,0))
                    ->setCellValue('U' . $i, round($val->total_devengado,0))
                    ->setCellValue('V' . $i, round($val->total_deduccion,0))
                    ->setCellValue('W' . $i, round($val->total_pagar,0))
                    ->setCellValue('X' . $i, $val->usuariosistema);
                
                   
            $i++;
        }
        $j = $i + 1;
               
        $objPHPExcel->getActiveSheet()->setTitle('Listados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Prestaciones.xlsx"');
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
