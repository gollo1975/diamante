<?php

namespace app\controllers;

use Yii;
use app\models\Licencia;
use app\models\LicenciaSearch;
use app\models\Empleados;
use app\models\Contratos;
use app\models\ConfiguracionLicencia;
use app\models\UsuarioDetalle;

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
 * LicenciaController implements the CRUD actions for Licencia model.
 */
class LicenciaController extends Controller
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
     * Lists all Licencia models.
     * @return mixed
     */
     public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',132])->all()){
                $form = new \app\models\FormFiltroLicencia();
                $id_empleado = null;
                $id_grupo_pago = null;
                $codigo_licencia = null; 
                $fecha_inicio = null;
                $fecha_final = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $id_empleado = Html::encode($form->id_empleado);
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $codigo_licencia = Html::encode($form->codigo_licencia);
                        $fecha_desde  = Html::encode($form->fecha_desde);
                        $fecha_hasta = Html::encode($form->fecha_hasta);
                         $identificacion = Html::encode($form->identificacion);
                        $table = Licencia::find()
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['=', 'codigo_licencia', $codigo_licencia])
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])                                                                                              
                                ->andFilterWhere(['=', 'identificacion', $identificacion])                                                                                              
                                ->andFilterWhere(['between', 'fecha_desde', $fecha_desde, $fecha_hasta])
                                ->orderBy('id_licencia_pk desc');

                        $table = $table->orderBy('id_licencia_pk desc');
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
                                $check = isset($_REQUEST['id_licencia_pk desc']);
                                $this->actionExcelLicencias($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = Licencia::find()
                        ->orderBy('id_licencia_pk desc');
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
                    $this->actionExcelLicencias($tableexcel);
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
     * Displays a single Licencia model.
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
     * Creates a new Licencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate() {   
      
      $model = new \app\models\FormLicencia();        
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
         $sw = 0;
         $empleado = Empleados::find()->all(); 
         $configuracionlicencia  = ConfiguracionLicencia::find()->where(['codigo_licencia'=>$model->codigo_licencia])->one();
        if ($model->load(Yii::$app->request->post())) {           
            if ($model->validate()) {
                //inicio de grabado
                if($empleado){
                    $empleado = Empleados::find()->where(['id_empleado'=>$model->id_empleado])->one();
                    $contrato = Contratos::find()->where(['=','id_empleado',$model->id_empleado])->andWhere(['=','contrato_activo', 0])->one();
                    ///codigo para validar fechas
                    $fecha_contrato = strtotime(date($contrato->fecha_inicio, time()));
                   $fecha_inicio_lice = strtotime(date($model->fecha_desde, time()));
                   $fecha_inicio_licencia = strtotime($model->fecha_desde);
                   $fecha_final_licencia = strtotime(date($model->fecha_hasta, time()));
                    if($fecha_contrato > $fecha_inicio_licencia){
                        Yii::$app->getSession()->setFlash('error', 'La fecha de inicio de la licencia No puede ser inferior a la fecha de inicio del contrato.');           
                    }else{
                        if($fecha_final_licencia < $fecha_inicio_lice){
                            Yii::$app->getSession()->setFlash('error', 'La fecha de inicio de la licencia es igual a la fecha final.');           
                        }else{   
                            $incapacidad = \app\models\Incapacidad::find()->where(['=','id_empleado', $model->id_empleado])->all();
                            $contIncap = count($incapacidad);
                            if($contIncap < 0){
                            }else{
                                foreach ($incapacidad as $val):
                                   $fecha_inicio_incapacidad = strtotime($val->fecha_inicio);
                                   $fecha_final_incapacidad = strtotime($val->fecha_final);
                                    if($fecha_inicio_lice == $fecha_inicio_incapacidad){
                                      Yii::$app->getSession()->setFlash('error', 'Este empleado tiene una incapacidad en el mismo rango de fechas. Favor valide la informacion.');           
                                      $sw = 1;
                                    }
                                endforeach;  
                                //codigo de licencias
                                $licencia_vector = Licencia::find()->where(['=','id_empleado', $model->id_empleado])->all();
                                $contLice = count($licencia_vector);
                                if($contLice > 0){
                                    foreach ($licencia_vector as $licencia_cr):
                                        $fecha_inicio_licencia_creada = strtotime($licencia_cr->fecha_desde);
                                        $fecha_final_licencia_creada = strtotime($licencia_cr->fecha_hasta);
                                        if($fecha_inicio_licencia == $fecha_final_licencia_creada){
                                           Yii::$app->getSession()->setFlash('error', 'Error de fechas: la fecha de inicio de esta licencia conincide con al fecha final de la lciencia Nro: '. $licencia_cr->id_licencia_pk. '');          
                                           $sw = 1;
                                        }
                                       
                                    endforeach;
                                }  
                                if($sw == 0){
                                    $licencia = Licencia::find()->where(['=','id_empleado', $model->id_empleado])
                                                                ->andwhere(['=','fecha_desde', $model->fecha_desde])
                                                                ->andwhere(['=','fecha_hasta', $model->fecha_hasta])->one();
                                    if($licencia){
                                        Yii::$app->getSession()->setFlash('error', 'Error de fechas: Existe una licencia creada con el mismo rango de fecha para este empleado.');          
                                    }else{    
                                        $table = new Licencia();
                                        $table->codigo_licencia= $model->codigo_licencia;
                                        $table->id_empleado = $model->id_empleado;
                                        $table->fecha_desde = $model->fecha_desde;
                                        $table->fecha_hasta = $model->fecha_hasta;
                                        $table->fecha_aplicacion = $model->fecha_aplicacion;
                                        $total = strtotime($model->fecha_hasta ) - strtotime($model->fecha_desde);
                                        $table->dias_licencia = round($total / 86400)+1; 
                                        $dias = round($total/ 86400)+1;
                                        $table->afecta_transporte = $model->afecta_transporte;
                                        $table->cobrar_administradora = $model->cobrar_administradora;
                                        $table->aplicar_adicional = $model->aplicar_adicional;
                                        $table->pagar_empleado = $model->pagar_empleado;
                                        $table->pagar_parafiscal = $model->pagar_parafiscal;
                                        $table->pagar_arl = $model->pagar_arl;
                                        $table->observacion = $model->observacion;
                                        $table->identificacion = $empleado->nit_cedula;
                                        $table->id_contrato = $contrato->id_contrato;
                                        $table->id_grupo_pago = $contrato->id_grupo_pago;
                                        $table->salario = $contrato->salario;
                                        $table->vlr_licencia = ($contrato->salario / 30)* $dias; 
                                        $table->user_name = Yii::$app->user->identity->username;
                                        if($configuracionlicencia->codigo == 1){
                                           $table->vlr_pagar_administradora = (($contrato->salario / 30)* $dias); 
                                        }   
                                        $table->save(false); 
                                        return $this->redirect(["licencia/index"]);
                                    } 
                                }    
                            }    
                        }    
                    }    
                } else {
                    Yii::$app->getSession()->setFlash('error', 'No existe el documento del empleado.');
                }
            }else {
                $model->getErrors();
            }
        }
            return $this->render('_form', [
                 'model' => $model,
             ]);
    }

    /**
     * Updates an existing Licencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   public function actionUpdate($id)
    {
      $model = new \app\models\FormLicencia();
      $contador = 0;
       if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
      
        if ($model->load(Yii::$app->request->post())) {            
                $table = Licencia::find()->where(['id_licencia_pk'=>$id])->one();
                 $configuracionlicencia  = ConfiguracionLicencia::find()->where(['codigo_licencia'=>$model->codigo_licencia])->one();
                 $table = Licencia::find()->where(['id_licencia_pk'=>$id])->one();
                if ($table) {
                    $empleado = Empleados::find()->where(['id_empleado'=>$model->id_empleado])->one();
                    $contrato = Contratos::find()->where(['=','id_empleado',$model->id_empleado])->andWhere(['=','contrato_activo', 0])->one();
                    $fecha_contrato = strtotime(date($contrato->fecha_inicio, time()));
                    $fecha_inicio_lice = strtotime(date($model->fecha_desde, time()));
                    $fecha_inicio_licencia = strtotime($model->fecha_desde);
                    $fecha_final_licencia = strtotime($model->fecha_hasta);
                   if($fecha_contrato > $fecha_inicio_licencia){
                        $mensaje  = " Error al digitalizar, La fecha de inicio de la licencia No puede ser inferior a la fecha de inicio del contrato";          
                   }else{
                        if($fecha_inicio_lice > $fecha_final_licencia){
                             $mensaje  = " Error de fechas, La fecha de inicio de la licencia No puede ser mayor que la fecha final de la licencia";          
                        }else{   
                            $incapacidad = \app\models\Incapacidad::find()->where(['=','id_empleado', $model->id_empleado])->all();
                            $contIncap = count($incapacidad);
                            if($contIncap > 0){
                                foreach ($incapacidad as $val):
                                    $fecha_inicio_incapacidad = strtotime($val->fecha_inicio);
                                    $fecha_final_incapacidad = strtotime($val->fecha_final);
                                    if($fecha_inicio_incapacidad == ($fecha_inicio_licencia)){
                                       $mensaje  = " Error, Este empleado tiene una incapacidad en el mismo rango de fechas";           
                                    }
                                    if($fecha_final_licencia == ($fecha_final_incapacidad)){
                                           $mensaje  = " Error, Este empleado tiene una incapacidad en el mismo rango de fechas..."; 
                                    }
                                    if ($fecha_inicio_licencia <= $fecha_final_incapacidad ){
                                               $mensaje  = " Error, Este empleado tiene una incapacidad en el mismo rango de fechas, el Nro de la incapacida es : .$val->numero_incapacidad."; 
                                    }else{
                                         $table->codigo_licencia= $model->codigo_licencia;
                                        $table->id_empleado = $model->id_empleado;
                                        $table->fecha_desde = $model->fecha_desde;
                                        $table->fecha_hasta = $model->fecha_hasta;
                                        $table->fecha_aplicacion = $model->fecha_aplicacion;
                                        $total = strtotime($model->fecha_hasta ) - strtotime($model->fecha_desde);
                                        $table->dias_licencia = round($total / 86400)+1; 
                                        $dias = round($total/ 86400)+1;
                                        $table->afecta_transporte = $model->afecta_transporte;
                                        $table->cobrar_administradora = $model->cobrar_administradora;
                                        $table->aplicar_adicional = $model->aplicar_adicional;
                                        $table->pagar_empleado = $model->pagar_empleado;
                                        $table->pagar_parafiscal = $model->pagar_parafiscal;
                                        $table->pagar_arl = $model->pagar_arl;
                                        $table->user_name = Yii::$app->user->identity->username;    
                                        $table->observacion = $model->observacion;
                                        $table->identificacion = $empleado->nit_cedula;
                                        $table->id_contrato = $contrato->id_contrato;
                                        $table->id_grupo_pago = $contrato->id_grupo_pago;
                                        $table->salario = $contrato->salario;
                                        $table->vlr_licencia = ($contrato->salario / 30)* $dias; 
                                        if($configuracionlicencia->codigo == 1){
                                           $table->vlr_pagar_administradora = (($contrato->salario / 30)* $dias); 
                                        }   
                                        $table->save(false);
                                        return $this->redirect(['index']);
                                    }
                                        
                                endforeach;                
                            }else{    
                                $table->codigo_licencia= $model->codigo_licencia;
                                $table->id_empleado = $model->id_empleado;
                                $table->fecha_desde = $model->fecha_desde;
                                $table->fecha_hasta = $model->fecha_hasta;
                                $table->fecha_aplicacion = $model->fecha_aplicacion;
                                $total = strtotime($model->fecha_hasta ) - strtotime($model->fecha_desde);
                                $table->dias_licencia = round($total / 86400)+1; 
                                $dias = round($total/ 86400)+1;
                                $table->afecta_transporte = $model->afecta_transporte;
                                $table->cobrar_administradora = $model->cobrar_administradora;
                                $table->aplicar_adicional = $model->aplicar_adicional;
                                $table->pagar_empleado = $model->pagar_empleado;
                                $table->pagar_parafiscal = $model->pagar_parafiscal;
                                $table->pagar_arl = $model->pagar_arl;
                                $table->user_name = Yii::$app->user->identity->username;    
                                $table->observacion = $model->observacion;
                                $table->identificacion = $empleado->nit_cedula;
                                $table->id_contrato = $contrato->id_contrato;
                                $table->id_grupo_pago = $contrato->id_grupo_pago;
                                $table->salario = $contrato->salario;
                                $table->vlr_licencia = ($contrato->salario / 30)* $dias; 
                                if($configuracionlicencia->codigo == 1){
                                   $table->vlr_pagar_administradora = (($contrato->salario / 30)* $dias); 
                                }   
                                $table->save(false);
                               return $this->redirect(['index']);
                                      
                            }    
                        }
                    }    
                }
        }
        if (Yii::$app->request->get("id")) {
              
                 $table = Licencia::find()->where(['id_licencia_pk' => $id])->one();            
                 if ($table) {     
                    $model->codigo_licencia = $table->codigo_licencia;
                    $model->id_empleado = $table->id_empleado;
                    $model->fecha_desde = $table->fecha_desde;
                    $model->fecha_hasta = $table->fecha_hasta;
                    $model->fecha_aplicacion = $table->fecha_aplicacion;
                    $model->afecta_transporte = $table->afecta_transporte;
                    $model->cobrar_administradora =  $table->cobrar_administradora;
                    $model->aplicar_adicional = $table->aplicar_adicional;
                    $model->pagar_empleado = $table->pagar_empleado;
                    $model->pagar_arl = $table->pagar_arl;
                    $model->pagar_parafiscal = $table->pagar_parafiscal;
                    $model->observacion = $table->observacion;
                }else{
                     return $this->redirect(['index']);
                }
        } else {
                return $this->redirect(['index']);    
        }
        return $this->render('update', [
            'model' => $model, 'id'=>$id,
        ]);
    }

    /**
     * Deletes an existing Licencia model.
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
            $this->redirect(["licencia/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["licencia/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
            $this->redirect(["licencia/index"]);
        }
    }

    /**
     * Finds the Licencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Licencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
     //clase de fecha
    public function getFechaActual() {
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaActual =  $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        return $fechaActual;
    }
    
    protected function findModel($id)
    {
        if (($model = Licencia::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
     public function actionExcelLicencias($tableexcel) {                
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
                               
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Documento')
                    ->setCellValue('B1', 'Empleado')
                    ->setCellValue('C1', 'Grupo pago')
                    ->setCellValue('D1', 'Tipo licencia')
                    ->setCellValue('E1', 'F. Inicio')
                    ->setCellValue('F1', 'F. Final')
                    ->setCellValue('G1', 'F. Proceso')
                    ->setCellValue('H1', 'Dias licencia')
                    ->setCellValue('I1', 'Valor administradora')
                    ->setCellValue('J1', 'Valor licencia')
                    ->setCellValue('K1', 'Salario licencia')
                    ->setCellValue('L1', 'User name');
                   
        $i = 2  ;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->identificacion)
                    ->setCellValue('B' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('C' . $i, $val->grupoPago->grupo_pago)
                    ->setCellValue('D' . $i, $val->codigoLicencia->concepto)   
                    ->setCellValue('E' . $i, $val->fecha_desde)
                    ->setCellValue('F' . $i, $val->fecha_hasta)
                    ->setCellValue('G' . $i, $val->fecha_proceso)
                    ->setCellValue('H' . $i, $val->dias_licencia)
                    ->setCellValue('I' . $i, $val->vlr_pagar_administradora)
                    ->setCellValue('J' . $i, $val->vlr_licencia)
                    ->setCellValue('K' . $i, $val->salario)
                    ->setCellValue('L' . $i, $val->user_name);
                 
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="licencias.xlsx"');
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
