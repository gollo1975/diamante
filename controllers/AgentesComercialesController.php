<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use Codeception\Lib\HelperModule;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
//models
use app\models\AgentesComerciales;
use app\models\AgentesComercialesSearch;
use app\models\UsuarioDetalle;
use app\models\Departamentos;
use app\models\Municipios;
use app\models\Cargos;
use app\models\FiltroBusquedaAgentes;
use app\models\AgenteComercialClientes;


/**
 * AgentesComercialesController implements the CRUD actions for AgentesComerciales model.
 */
class AgentesComercialesController extends Controller
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
     * Lists all AgentesComerciales models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',30])->all()){
                $form = new FiltroBusquedaAgentes();
                $documento = null;
                $cargo = null;
                $estado = null;
                $nombre_completo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        $cargo = Html::encode($form->cargo);
                        $estado = Html::encode($form->estado);
                        $table = AgentesComerciales::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'nombre_completo', $nombre_completo])
                                ->andFilterWhere(['=', 'estado', $estado])
                                ->andFilterWhere(['=', 'id_cargo', $cargo]);
                        $table = $table->orderBy('id_agente DESC');
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
                            $this->actionExcelconsultaAgentes($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = AgentesComerciales::find()
                            ->orderBy('id_agente desc');
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
                            $this->actionExcelconsultaAgentes($tableexcel);
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
    //PROCESO QUE CONSULTA
     public function actionSearch_consulta_agentes($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',31])->all()){
                $form = new FiltroBusquedaAgentes();
                $documento = null;
                $cargo = null;
                $estado = null;
                $nombre_completo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        $cargo = Html::encode($form->cargo);
                        $estado = Html::encode($form->estado);
                        $table = AgentesComerciales::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'nombre_completo', $nombre_completo])
                                ->andFilterWhere(['=', 'estado', $estado])
                                ->andFilterWhere(['=', 'id_cargo', $cargo]);
                        $table = $table->orderBy('id_agente DESC');
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
                            $this->actionExcelconsultaAgentes($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = AgentesComerciales::find()
                            ->orderBy('id_agente desc');
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
                            $this->actionExcelconsultaAgentes($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_consulta_agentes', [
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
     * Displays a single AgentesComerciales model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $listo_cliente = \app\models\Clientes::find()->where(['=','id_agente', $id])->orderBy('nombre_completo DESC');
        $tableexcel = $listo_cliente->all();
        $count = clone $listo_cliente;
        $pages = new Pagination([
                    'pageSize' => 10,
                    'totalCount' => $count->count(),
        ]);
         $listo_cliente = $listo_cliente
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'listo_cliente' => $listo_cliente,
            'pagination' => $pages,
           
        ]);
    }

    /**
     * Creates a new AgentesComerciales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AgentesComerciales();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $table = new AgentesComerciales();
            $dv = Html::encode($_POST["dv"]);
            $table->id_tipo_documento = $model->id_tipo_documento;
            $table->nit_cedula = $model->nit_cedula;
            $table->dv = $dv;
            $table->primer_nombre = $model->primer_nombre;
            $table->segundo_nombre = $model->segundo_nombre;
            $table->primer_apellido = $model->primer_apellido;
            $table->segundo_apellido = $model->segundo_apellido;
            $table->nombre_completo = strtoupper($model->primer_nombre .' '. $model->segundo_nombre . ' '. $model->primer_apellido .' '. $model->segundo_apellido);
            $table->direccion = $model->direccion;
            $table->email_agente = $model->email_agente;
            $table->celular_agente = $model->celular_agente;
            $table->estado = $model->estado;
            $table->codigo_departamento = $model->codigo_departamento;
            $table->codigo_municipio = $model->codigo_municipio;
            $table->id_cargo = $model->id_cargo;
            $table->user_name = Yii::$app->user->identity->username;
            $table->gestion_diaria = $model->gestion_diaria;
            $table->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AgentesComerciales model.
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
            $model->nombre_completo = strtoupper($model->primer_nombre .' '. $model->segundo_nombre . ' '. $model->primer_apellido .' '. $model->segundo_apellido);
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    

    /**
     * Finds the AgentesComerciales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgentesComerciales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AgentesComerciales::findOne($id)) !== null) {
            return $model; 
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //PROCESOS DE EXCEL
    public function actionExcelconsultaAgentes($tableexcel) {                
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
                               
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO DOCUMENTO')
                    ->setCellValue('C1', 'NIT/CEDULA')
                    ->setCellValue('D1', 'DV')
                    ->setCellValue('E1', 'AGENTE COMERCIAL')
                    ->setCellValue('F1', 'DIRECCION')
                    ->setCellValue('G1', 'CELULAR')
                    ->setCellValue('H1', 'EMAIL')
                    ->setCellValue('I1', 'DEPARTAMENTO')
                    ->setCellValue('J1', 'MUNICIPIO')
                    ->setCellValue('K1', 'CARGO')
                    ->setCellValue('L1', 'USER NAME')
                    ->setCellValue('M1', 'ACTIVO')
                    ->setCellValue('N1', 'FECHA REGISTRO');
               
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_agente)
                    ->setCellValue('B' . $i, $val->tipoDocumento->tipo_documento)
                    ->setCellValue('C' . $i, $val->nit_cedula)
                    ->setCellValue('D' . $i, $val->dv)
                    ->setCellValue('E' . $i, $val->nombre_completo)
                    ->setCellValue('F' . $i, $val->direccion)
                    ->setCellValue('G' . $i, $val->celular_agente)
                    ->setCellValue('H' . $i, $val->email_agente)
                    ->setCellValue('I' . $i, $val->codigoDepartamento->departamento)
                    ->setCellValue('J' . $i, $val->codigoMunicipio->municipio)
                    ->setCellValue('K' . $i, $val->cargo->nombre_cargo)
                    ->setCellValue('L' . $i, $val->user_name)
                    ->setCellValue('M' . $i, $val->estadoRegistro)
                    ->setCellValue('N' . $i, $val->fecha_registro);
                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Agentes_comerciales.xlsx"');
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
    
  //EXPORTA LOS CLIENTES DE CADA VENDEDOR
    public function actionConsultaclientes($id) {
        $listo_cliente = \app\models\Clientes::find()->where(['=','id_agente', $id])->orderBy('nombre_completo DESC')->all();
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
                               
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO DOCUMENTO')
                    ->setCellValue('C1', 'NIT/CEDULA')
                    ->setCellValue('D1', 'DV')
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'DIRECCION')
                    ->setCellValue('G1', 'CELULAR')
                    ->setCellValue('H1', 'TELEFONO')
                    ->setCellValue('I1', 'EMAIL')
                    ->setCellValue('J1', 'DEPARTAMENTO')
                    ->setCellValue('K1', 'MUNICIPIO')
                    ->setCellValue('L1', "TIPO REGIMEN")
                    ->setCellValue('M1', 'FORMA DE PAGO')
                    ->setCellValue('N1', 'PLAZO')
                    ->setCellValue('O1', 'AUTORETENEDOR')
                    ->setCellValue('P1', 'NATURALEZA')
                    ->setCellValue('Q1', 'TIPO SOCIEDAD')
                    ->setCellValue('R1', 'USER CREAR')
                    ->setCellValue('S1', 'USER EDITAR')
                    ->setCellValue('T1', 'FECHA CREACION')
                    ->setCellValue('U1', 'ACTIVO')
                    ->setCellValue('V1', 'CUPO ASIGNADO');
               
        $i = 2;
        
        foreach ($listo_cliente as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_cliente)
                    ->setCellValue('B' . $i, $val->tipoDocumento->tipo_documento)
                    ->setCellValue('C' . $i, $val->nit_cedula)
                    ->setCellValue('D' . $i, $val->dv)
                    ->setCellValue('E' . $i, $val->nombre_completo)
                    ->setCellValue('F' . $i, $val->direccion)
                    ->setCellValue('G' . $i, $val->celular)
                    ->setCellValue('H' . $i, $val->telefono)
                    ->setCellValue('I' . $i, $val->email_cliente)
                    ->setCellValue('J' . $i, $val->codigoDepartamento->departamento)
                    ->setCellValue('K' . $i, $val->codigoMunicipio->municipio)
                    ->setCellValue('L' . $i, $val->tipoRegimen)
                    ->setCellValue('M' . $i, $val->formaPago)
                    ->setCellValue('N' . $i, $val->plazo)
                    ->setCellValue('O' . $i, $val->autoretenedorVenta)
                    ->setCellValue('P' . $i, $val->naturaleza->naturaleza)
                    ->setCellValue('Q' . $i, $val->tipoSociedad)
                    ->setCellValue('R' . $i, $val->user_name)
                    ->setCellValue('S' . $i, $val->user_name_editar)
                    ->setCellValue('T' . $i, $val->fecha_creacion)
                    ->setCellValue('U' . $i, $val->estadoCliente)
                    ->setCellValue('V' . $i, $val->cupo_asignado);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Listado_Clientes.xlsx"');
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
