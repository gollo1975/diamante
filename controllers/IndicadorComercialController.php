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
//models
use app\models\IndicadorComercial;
use app\models\IndicadorComercialSearch;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaCitas;
use app\models\IndicadorComercialVendedores;
use app\models\ProgramacionCitas;
use app\models\ProgramacionCitaDetalles;
use app\models\IndicadorComercialClientes;
use app\models\Clientes;
use app\models\AgentesComerciales;


/**
 * IndicadorComercialController implements the CRUD actions for IndicadorComercial model.
 */
class IndicadorComercialController extends Controller
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
     * Lists all IndicadorComercial models.
     * @return mixed
     */
   // GENERAR EL INDICADOR COMERCIAL
     public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',44])->all()){
                $form = new FiltroBusquedaCitas();
                $desde = null;
                $hasta = null;
                $anio = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $anio = Html::encode($form->anocierre);
                        $table = IndicadorComercial::find()
                                    ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])
                                    ->andFilterWhere(['=', 'anocierre', $anio]);
                        $table = $table->orderBy('id_indicador DESC');
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
                            $this->actionExcelconsultaIndicador($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = IndicadorComercial::find()->orderBy('id_indicador DESC');
                    
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
                            $this->actionExcelconsultaIndicador($tableexcel);
                    }
                }
                $to = $count->count();
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

    //CONSULTA INDICADOR COMERCIAL
    public function actionSearch_indicador_comercial($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',42])->all()){
                $form = new FiltroBusquedaCitas();
                $desde = null;
                $hasta = null;
                $anio = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $anio =  Html::encode($form->anocierre);
                        $table = IndicadorComercial::find()
                                    ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])
                                    ->andFilterWhere(['=', 'anocierre', $anio]);
                        $table = $table->orderBy('id_indicador DESC');
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
                            $this->actionExcelconsultaIndicador($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = IndicadorComercial::find()->orderBy('id_indicador DESC');
                    
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
                            $this->actionExcelconsultaIndicador($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_indicador_comercial', [
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
    
    //CONSULTA DE INDICADOR DE VENDEDOR- GRAFICA
     public function actionSearch_indicador_vendedor() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',43])->all()){
                $form = new FiltroBusquedaCitas();
                $desde = null;
                $hasta = null;
                $documento = null;
                $agente = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                if($tokenAcceso == 3){
                    $agenteCo = AgentesComerciales::find()->where(['=', 'nit_cedula', Yii::$app->user->identity->username])->one();
                    $agente = $agenteCo->id_agente;
                }
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $documento =  Html::encode($form->documento);
                        $agente  = Html::encode($form->agente);
                        if($tokenAcceso == 3){
                            $table = IndicadorComercialVendedores::find()
                                    ->Where(['between', 'desde', $desde, $hasta])
                                    ->andWhere(['=', 'id_agente', $agente]);
                        }else{
                            $table = IndicadorComercialVendedores::find()
                                    ->andFilterWhere(['between', 'desde', $desde, $hasta])
                                    ->andFilterWhere(['like', 'documento', $documento])
                                    ->andFilterWhere(['like', 'agente', $agente]);
                        }    
                        $table = $table->orderBy('desde DESC');
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
                            $this->actionExcelconsultaVendedor($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    if($tokenAcceso == 3){
                         $table = IndicadorComercialVendedores::find()->Where(['=', 'id_agente', $agente])->orderBy('desde DESC');
                    }else{
                       $table = IndicadorComercialVendedores::find()->orderBy('agente ASC');
                    }   
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
                            $this->actionExcelconsultaVendedor($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_indicador_vendedor', [
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
     * Displays a single IndicadorComercial model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $desde, $hasta, $token)
    {
        $vendedores = IndicadorComercialVendedores::find()->where(['=','id_indicador', $id])->orderBy('agente ASC')->all();
        $clientes = IndicadorComercialClientes::find()->where(['=','id_indicador', $id])->all();
        //INDICADOR DE VENDEDORES
        if (isset($_POST["generar_registros"])) {
            if (isset($_POST["agente_comercial"])) {
                $intIndice = 0;
                foreach ($_POST["agente_comercial"] as $intCodigo):
                    $indicador = IndicadorComercialVendedores::find()->where(['=','id', $intCodigo])->one();
                    $buscarProgramacion = ProgramacionCitas::find()->where(['between','fecha_inicio', $desde, $hasta])
                                                                   ->andWhere(['=','id_agente', $indicador->id_agente])->all();
                    $conCitas =0; $conNoVisitas = 0; $con = 0;
                    $conVistas = 0; $conPorcentaje = 0;
                    foreach ($buscarProgramacion as $buscar):
                        $conCitas += $buscar->total_citas;                       
                        $conVistas += $buscar->visitas_cumplidas;
                        $conNoVisitas += $buscar->visitas_no_cumplidas;
                        $conPorcentaje += $buscar->porcentaje_eficiencia;
                        $con += 1;
                    endforeach;
                    $indicador->total_visitas = $conCitas;
                    $indicador->total_realizadas = $conVistas;
                    $indicador->total_no_realizadas = $conNoVisitas ;
                    $indicador->total_porcentaje = round($conPorcentaje / $con);
                    $indicador->desde = $desde;
                    $indicador->hasta = $hasta;
                    $indicador->save(false);   
                endforeach;  
                $this->TotalIndicadorvendedor($desde, $hasta, $id);
               $this->redirect(["indicador-comercial/view", 'id' => $id, 'desde' => $desde, 'hasta' => $hasta, 'token' => $token]); 
            }
        }   
        if (isset($_POST["procesar_indicador"])) {
            if (isset($_POST["cliente_comercial"])) {
                $intIndice = 0;
                foreach ($_POST["cliente_comercial"] as $intCodigo):
                    $indicador = IndicadorComercialClientes::find()->where(['=','id_detalle', $intCodigo])->one();
                    $buscarProgramacion = ProgramacionCitaDetalles::find()->where(['between','desde', $desde, $hasta])
                                                                   ->andWhere(['=','id_cliente', $indicador->id_cliente])->all();
                    $conCitas =0; $conNoVisitas = 0; $con = 0;
                    $conVistas = 0; $conPorcentaje = 0;
                    foreach ($buscarProgramacion as $buscar):
                        if($buscar->cumplida == 0){
                           $conNoVisitas += 1;    
                        }else{
                            $conVistas += 1;
                        }
                        $conCitas += 1;
                    endforeach;
                    $indicador->total_visitas = $conCitas;
                    $indicador->visita_real = $conVistas;
                    $indicador->visita_no_real = $conNoVisitas;
                    $indicador->porcentaje = round(($conVistas * 100)/$conCitas,2);
                    $indicador->save(false);   
                endforeach;  
               // $this->TotalIndicadorvendedor($desde, $hasta, $id);
               $this->redirect(["indicador-comercial/view", 'id' => $id, 'desde' => $desde, 'hasta' => $hasta, 'token' => $token]); 
            }
        }   
        return $this->render('view', [
            'model' => $this->findModel($id),
            'vendedores' => $vendedores,
            'clientes' => $clientes,
            'token' => $token,
        ]);
    }
   //PROCESO QUE TOTALIZA LOS INDICADORES
   protected function TotalIndicadorvendedor($desde, $hasta, $id)
    {
       $conIndicador = IndicadorComercial::findOne($id);   
       $indicador = IndicadorComercialVendedores::find()->where(['=','desde', $desde])->andWhere(['=','hasta',  $hasta])->orderBy('agente ASC')->all();
        $conCitas =0; $conNoVisitas = 0; 
        $conVistas = 0; $conPorcentaje = 0;
       foreach ($indicador as $buscar):
            $conCitas += $buscar->total_visitas;                       
            $conVistas += $buscar->total_realizadas;
            $conNoVisitas += $buscar->total_no_realizadas;
            $conPorcentaje += $buscar->total_porcentaje;
       endforeach;
        $conIndicador->total_citas = $conCitas;
        $conIndicador->total_citas_reales = $conVistas;
        $conIndicador->total_citas_no_reales = $conNoVisitas ;
        $conIndicador->total_porcentaje = round($conPorcentaje / $conIndicador->total_registros);
        $conIndicador->save();
   }
    
    //vista grafica
    public function actionView_grafica_vendedor($id, $id_agente, $desde, $hasta) {
        $vendedores = IndicadorComercialVendedores::find()->where(['=','id', $id])->one();
        $clientes = IndicadorComercialClientes::find()->where(['=','id_agente', $id_agente])->andWhere(['between','desde', $desde, $hasta])->all();  
       
        return $this->render('vendedor_grafica', [
           
            'vendedores' => $vendedores,
            'clientes' => $clientes,
            'desde' => $desde,
            'hasta' => $hasta,
            'id_agente' => $id_agente,
           
        ]);
    }
   /**
     * Creates a new IndicadorComercial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCrear_cierre_mes()
    {
        $model = new \app\models\FormModeloCrearCita();
        if ($model->load(Yii::$app->request->post())) {
                if (isset($_POST["crear_cita_cliente"])) {
                    if($model->desde <> (NULL) && $model->hasta <> (NULL)){
                        $citas = IndicadorComercial::find()->where(['=','fecha_inicio', $model->desde])->andwhere(['=','fecha_cierre', $model->hasta])->one();
                        if(!$citas){
                            $table = new IndicadorComercial();
                            $table->fecha_inicio = $model->desde;
                            $table->fecha_cierre = $model->hasta;
                             $table->anocierre = $model->anocierre;
                            $table->user_name = Yii::$app->user->identity->username;
                            $table->save(false);
                            $this->redirect(["indicador-comercial/index"]); 
                        }else{
                             Yii::$app->getSession()->setFlash('warning', 'Las fechas digitadas ya exites en el sistemas. Ingrese nuevamente.'); 
                            $this->redirect(["indicador-comercial/index"]);
                        }
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'Debe digitar las fechas para crear el cierre comercial. Ingrese nuevamente.'); 
                        $this->redirect(["indicador-comercial/index"]);
                    }

                } 
            }
           return $this->renderAjax('crear_cierre_mes', [
            'model' => $model,
            ]);
    }
    //CARGAR VENDEDORES QUE HICIERON GESTION COMERCIAL
    public function actionCarga_vendedores($id, $desde, $hasta) {
        $indicador = IndicadorComercial::findOne($id);
        $programacion = \app\models\ProgramacionCitas::find()->where(['between', 'fecha_inicio', $desde, $hasta])->andWhere(['=','proceso_cerrado', 1])->orderBy('id_agente')->all();
        $auxiliar = 0; $cont = 0;
        if(count($programacion) > 0){
            foreach ($programacion as $dato):
                   if($auxiliar <> $dato->id_agente){
                       $table = new IndicadorComercialVendedores();
                       $table->id_agente = $dato->id_agente;
                       $table->documento = $dato->agente->nit_cedula;
                       $table->agente = $dato->agente->nombre_completo;
                       $table->id_indicador = $id;
                       $table->save();
                       $auxiliar = $dato->id_agente;
                       $cont += 1;
                   }               
            endforeach;
            $indicador->total_registros = $cont;
            $indicador->save();
            Yii::$app->getSession()->setFlash('info', 'Se cargaron (' .$cont.') agentes comerciales para el proceso de indicadores de gestión.');
             $this->redirect(["indicador-comercial/index"]);
        }else{
             Yii::$app->getSession()->setFlash('warning', 'No existen registros en este rango de fechas.');
             $this->redirect(["indicador-comercial/index"]);   
        }     
    }
    //CARGAR CLIENTES
    public function actionIndicador_clientes($id, $desde, $hasta, $agente, $token) {
        $consul = IndicadorComercialClientes::find()->where(['=','id_agente', $agente])->andWhere(['=','id_indicador', $id])->one();
        if(!$consul){
            $cliente = \app\models\Clientes::find()->where(['=','id_agente', $agente])->andWhere(['=','estado_cliente', 0])->orderBy('nombre_completo')->all();
            $cont = 0;
            foreach ($cliente as $clientes):
                $detalle = ProgramacionCitaDetalles::find()->where(['=','id_cliente', $clientes->id_cliente])->andWhere(['between','desde', $desde, $hasta])->all();
                $auxiliar = 0; 
                foreach ($detalle as $detalles):
                    if($auxiliar <> $detalles->id_cliente){
                       $table = new IndicadorComercialClientes();
                       $table->id_cliente = $detalles->id_cliente;
                       $table->id_indicador = $id;
                       $table->id_agente = $agente;
                       $table->desde = $desde;
                       $table->hasta = $hasta;
                       $table->save();
                       $auxiliar = $detalles->id_cliente;    
                    }    
                endforeach;
                  $cont += 1;
            endforeach;
             Yii::$app->getSession()->setFlash('info', 'Se cargaron (' .$cont.') clinetes para el proceso de indicadores de gestión.');
            $this->redirect(["indicador-comercial/view", 'id' =>$id, 'desde' => $desde, 'hasta' => $hasta, 'token' => $token]);
        }else{
            Yii::$app->getSession()->setFlash('warning', 'Ya se cargaron los clientes de este vendedor.');
            $this->redirect(["indicador-comercial/view", 'id' =>$id, 'desde' => $desde, 'hasta' => $hasta, 'token' => $token]);
        }
    }
    public function actionCerrar_indicador($id) {
        $indicador = IndicadorComercial::findOne($id);
        $indicador->estado_indicador = 1;
        $indicador->save();
        $this->redirect(["indicador-comercial/index"]);
    } 

    /**
     * Finds the IndicadorComercial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IndicadorComercial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IndicadorComercial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //EXCEL DE INDICADOR DE VENDEDORES
    
    //MUESTRAS TODOS LOS VENDEDORS CON SU GESTION COMERCIAL
     public function actionExcelconsultaVendedor($tableexcel) {                
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
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'VENDEDOR')
                    ->setCellValue('D1', 'V. PROGRAMADAS')
                    ->setCellValue('E1', 'V. REALIZADAS')
                    ->setCellValue('F1', 'V. NO REALIZADAS')
                    ->setCellValue('G1', 'EFICIENCIA')
                    ->setCellValue('H1', 'FECHA_HORA')
                    ->setCellValue('I1', 'DESDE')    
                    ->setCellValue('J1', 'HASTA');
                    
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id)
                    ->setCellValue('B' . $i, $val->documento)
                    ->setCellValue('C' . $i, $val->agente)
                    ->setCellValue('D' . $i, $val->total_visitas)
                    ->setCellValue('E' . $i, $val->total_realizadas)
                    ->setCellValue('F' . $i, $val->total_no_realizadas)
                    ->setCellValue('G' . $i, $val->total_porcentaje)
                    ->setCellValue('H' . $i, $val->fecha_hora)
                    ->setCellValue('I' . $i, $val->desde)
                    ->setCellValue('J' . $i, $val->hasta);
               
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Indicado_vendedor.xlsx"');
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
    //EXCEL QUE PERMITE EXPORTAR LOS INDICADORES POR VENDEDOR
    public function actionExcel_indicador_vendedores($id, $sw) {                
        if($sw == 0){
            $detalle  = IndicadorComercialVendedores::find()->where(['=','id_indicador', $id])->all(); 
        }else{
            $detalle  = IndicadorComercialClientes::find()->where(['=','id_indicador', $id])->all(); 
        }    
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        if($sw == 0){
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
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'NO INDICADOR')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'AGENTE COMERCIAL')
                        ->setCellValue('E1', 'DESDE')
                        ->setCellValue('F1', 'HASTA')
                        ->setCellValue('G1', 'TOTAL VISITAS')
                        ->setCellValue('H1', 'No VISITAS')
                        ->setCellValue('I1', 'No VISITAS NO REALES')
                        ->setCellValue('J1', '% EFICIENCIA')
                        ->setCellValue('K1', 'FECHA_REGISTRO')
                        ->setCellValue('L1', 'USER NAME');
            $i = 2;
        }else{
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
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'NO INDICADOR')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'AGENTE COMERCIAL')
                        ->setCellValue('F1', 'DESDE')
                        ->setCellValue('G1', 'HASTA')
                        ->setCellValue('H1', 'TOTAL VISITAS')
                        ->setCellValue('I1', 'No VISITAS')
                        ->setCellValue('J1', 'No VISITAS NO REALES')
                        ->setCellValue('K1', '% EFICIENCIA')
                        ->setCellValue('L1', 'FECHA_REGISTRO')
                        ->setCellValue('M1', 'USER NAME');
            $i = 2;
        }    
        
        foreach ($detalle as $val) {
            if($sw == 0){                      
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id)
                        ->setCellValue('B' . $i, $id)
                        ->setCellValue('C' . $i, $val->documento)
                        ->setCellValue('D' . $i, $val->agente)
                        ->setCellValue('E' . $i, $val->indicador->fecha_inicio)
                        ->setCellValue('F' . $i, $val->indicador->fecha_cierre)
                        ->setCellValue('G' . $i, $val->total_visitas)
                        ->setCellValue('H' . $i, $val->total_realizadas)
                        ->setCellValue('I' . $i, $val->total_no_realizadas)
                        ->setCellValue('J' . $i, $val->total_porcentaje)
                        ->setCellValue('K' . $i, $val->fecha_hora)
                        ->setCellValue('L' . $i, $val->indicador->user_name);

                $i++;
            }else{
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_detalle)
                        ->setCellValue('B' . $i, $id)
                        ->setCellValue('C' . $i, $val->cliente->nit_cedula)
                        ->setCellValue('D' . $i, $val->cliente->nombre_completo)
                        ->setCellValue('E' . $i, $val->agente->nombre_completo)
                        ->setCellValue('F' . $i, $val->indicador->fecha_inicio)
                        ->setCellValue('G' . $i, $val->indicador->fecha_cierre)
                        ->setCellValue('H' . $i, $val->total_visitas)
                        ->setCellValue('I' . $i, $val->visita_real)
                        ->setCellValue('J' . $i, $val->visita_no_real)
                        ->setCellValue('K' . $i, $val->porcentaje)
                        ->setCellValue('L' . $i, $val->fecha_hora)
                        ->setCellValue('M' . $i, $val->indicador->user_name);

                $i++;
            }    
        }

        $objPHPExcel->getActiveSheet()->setTitle('Indicadores');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Indicador_de_Gestion.xlsx"');
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
