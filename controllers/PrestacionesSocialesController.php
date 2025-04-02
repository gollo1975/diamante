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
                $id_empleado = null;
                $pagina = 2;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $id_empleado = Html::encode($form->id_empleado);
                        $table = PrestacionesSociales::find()
                                ->andFilterWhere(['like', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])
                                ->orderBy('id_prestacion desc');
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
            
            if ($contrato->id_tipo_salario == 1){
                $salario_promedio = $contrato->salario;
            }else{
                $salario_promedio = round($acumulado / $total_dias)*30;
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
            
            if ($contrato->id_tipo_salario == 1){
                $salario_promedio = $contrato->salario;
            }else{
                $salario_promedio = round($acumulado / $total_dias)*30;
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
    public function actionGenerar_conceptos($id, $year = NULL, $pagina)
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
                $table->fecha_inicio = $model->ultimo_pago_prima;
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
                $table->fecha_inicio = $model->ultimo_pago_cesantias;
                $table->fecha_final = $model->fecha_termino_contrato;
                $table->abreviatura = 'C';
                $table->save(false);
                $table2 = new \app\models\PrestacionesSocialesDetalle();
                $table2->id_prestacion = $id;
                $table2->codigo_salario = $intereses->codigo_salario;
                $table2->fecha_inicio = $model->ultimo_pago_cesantias;
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
                $table->fecha_inicio = $contrato->ultima_pago_vacacion;
                $table->fecha_final = $model->fecha_termino_contrato;
                $table->abreviatura = 'V';
                $table->save(false);
            }
            
        }
        $model->estado_generado = 1;
        $model->save(false);
         
    $this->redirect(["prestaciones-sociales/view", 'id' => $id,
          'model' => $model,
           'pagina' => $pagina,
          ]);
       
       
    }
  
    
    
   
    
    /**
     * Updates an existing PrestacionesSociales model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_prestacion]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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
}
