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
use yii\db\Command;

//models
use app\models\Empleados;
use app\models\UsuarioDetalle;

/**
 * EmpleadosController implements the CRUD actions for Empleados model.
 */
class EmpleadosController extends Controller
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
     * Lists all Empleados models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',115])->all()){
                $form = new \app\models\FiltroEmpleados();
                $documento = null;
                $empleado = null;
                $estado = null;
                $tipo_empleado = null;
                $desde = null;
                $hasta = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $empleado = Html::encode($form->empleado);
                        $estado = Html::encode($form->estado);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $tipo_empleado = Html::encode($form->tipo_empleado);
                        $table = Empleados::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'nombre_completo', $empleado])
                                ->andFilterWhere(['between', 'fecha_ingreso', $desde, $desde])
                                ->andFilterWhere(['=', 'tipo_empleado', $tipo_empleado])
                                ->andFilterWhere(['=', 'estado', $estado]);
                        $table = $table->orderBy('id_empleado DESC');
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
                            $this->actionExcelEmpleados($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                }else{
                  
                    $table = Empleados::find()->orderBy('id_empleado DESC');
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
                            $this->actionExcelEmpleados($tableexcel);
                    }
                } 
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

    /**
     * Displays a single Empleados model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $contrato = \app\models\Contratos::find()->where(['id_empleado' => $id])->orderBy('id_contrato DESC')->all();
        $incapacidad = \app\models\Incapacidad::find()->where(['=','id_empleado', $id])->orderBy('id_incapacidad DESC')->all();
        $licencias = \app\models\Licencia::find()->where(['=','id_empleado', $id])->orderBy('id_licencia_pk DESC')->all();
        $creditos = \app\models\Credito::find()->where(['=','id_empleado', $id])->orderBy('id_credito DESC')->all();
        $estudios = \app\models\EstudiosEmpleados::find()->where(['=','id_empleado', $id])->orderBy('id DESC')->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token, 
            'contrato' => $contrato,
            'incapacidad' => $incapacidad,
            'licencias' => $licencias,
            'creditos' => $creditos,
            'estudios' => $estudios,
        ]);
    }

    /**
     * Creates a new Empleados model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Empleados();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())){
            $buscar = Empleados::find()->where(['=','nit_cedula', $model->nit_cedula])->one();
            if(!$buscar){
                $model->save();
                $dv = Html::encode($_POST["dv"]);
                $model->user_name = Yii::$app->user->identity->username;
                $model->nombre_completo = strtoupper($model->nombre1. ' ' .$model->nombre2. ' '. $model->apellido1. ' '. $model->apellido2);
                $model->dv = $dv;
                $model->save();
                return $this->redirect(['index']); 
            }else{
                Yii::$app->getSession()->setFlash('error', 'Este documento YA esta creado con otro empleado. Valide la informacion'); 
                 
            }
        }

        return $this->render('create', [
            'model' => $model,
            'sw' => 0,
        ]);
    }

    /**
     * Updates an existing Empleados model.
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
            $dv = Html::encode($_POST["dv"]);
            $model->dv = $dv;
            $model->user_name_editado = Yii::$app->user->identity->username;
             $model->nombre_completo = strtoupper($model->nombre1. ' ' .$model->nombre2. ' '. $model->apellido1. ' '. $model->apellido2);
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'sw' => 1,
        ]);
    }

    /**
     * Deletes an existing Empleados model.
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
     * Finds the Empleados model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Empleados the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Empleados::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
     public function actionExcelEmpleados($tableexcel) {                
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);

                              
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO EMPLEADO')
                    ->setCellValue('C1', 'TIPO DOCUMENTO')
                    ->setCellValue('D1', 'DOCUMENTO')
                    ->setCellValue('E1', 'NOMBRE 1')
                    ->setCellValue('F1', 'NOMBRE 2')
                    ->setCellValue('G1', 'APELLIDO 1')                    
                    ->setCellValue('H1', 'APELLIDO 2')
                    ->setCellValue('I1', 'FECHA EXPEDICION')
                    ->setCellValue('J1', 'CIUDAD EXPEDICION')
                    ->setCellValue('K1', 'DIRECCION')
                    ->setCellValue('L1', 'TELEFONO')
                    ->setCellValue('M1', 'CELULAR')
                    ->setCellValue('N1', 'EMAIL')
                    ->setCellValue('O1', 'DEPARTAMENTO')
                    ->setCellValue('P1', 'MUNICIPIO')
                    ->setCellValue('Q1', 'BARRIO')
                    ->setCellValue('R1', 'GENERO')
                    ->setCellValue('S1', 'ESTADO CIVIL')
                    ->setCellValue('T1', 'FECHA NACIMIENTO')
                    ->setCellValue('U1', 'CIUDAD NACIMIENTO')
                    ->setCellValue('V1', 'CONTRATO ACTIVO')
                    ->setCellValue('W1', 'FECHA INGRESO')
                    ->setCellValue('X1', 'FECHA RETIRO')
                    ->setCellValue('Y1', 'PADRE FAMILIA')
                    ->setCellValue('Z1', 'CABEZA HOGAR')
                    ->setCellValue('AA1', 'NIVEL ESTUDIO')
                    ->setCellValue('AB1', 'DISCAPACITADO')
                    ->setCellValue('AC1', 'BANCO')
                    ->setCellValue('AD1', 'TIPO CUENTA')
                    ->setCellValue('AE1', 'No CUENTA')
                    ->setCellValue('AF1', 'USUARIO CREACION')
                    ->setCellValue('AG1', 'USUARIO EDITADO')
                    ->setCellValue('AH1', 'OBSERVACION');
                   
        $i = 2  ;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_empleado)
                    ->setCellValue('B' . $i, $val->tipoEmpleado->descripcion)
                    ->setCellValue('C' . $i, $val->tipoDocumento->tipo_documento)
                    ->setCellValue('D' . $i, $val->nit_cedula)
                    ->setCellValue('E' . $i, $val->nombre1)
                    ->setCellValue('F' . $i, $val->nombre2)
                    ->setCellValue('G' . $i, $val->apellido1)                    
                    ->setCellValue('H' . $i, $val->apellido2)
                    ->setCellValue('I' . $i, $val->fecha_expedicion_documento)
                    ->setCellValue('J' . $i, $val->codigoMunicipioExpedicion->municipio)
                    ->setCellValue('K' . $i, $val->direccion)
                    ->setCellValue('L' . $i, $val->telefono)
                    ->setCellValue('M' . $i, $val->celular)
                    ->setCellValue('N' . $i, $val->email_empleado)
                    ->setCellValue('O' . $i, $val->codigoDepartamentoResidencia->departamento)
                    ->setCellValue('P' . $i, $val->codigoMunicipioExpedicion->municipio)
                    ->setCellValue('Q' . $i, $val->barrio)
                    ->setCellValue('R' . $i, $val->generoEmpleado)
                    ->setCellValue('S' . $i, $val->estadoCivil)
                    ->setCellValue('T' . $i, $val->fecha_nacimiento)
                    ->setCellValue('U' . $i, $val->codigoMunicipioNacimiento->municipio)
                    ->setCellValue('V' . $i, $val->estadoActivo)
                    ->setCellValue('W' . $i, $val->fecha_ingreso);
                   if($val->fecha_retiro == '2099-12-30'){
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('X' . $i, 'INDEFINIDO');
                   }else{
                         $objPHPExcel->setActiveSheetIndex(0)
                         ->setCellValue('X' . $i, $val->fecha_retiro);
                   }
                   $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('Y' . $i, $val->padreFamilia)
                    ->setCellValue('Z' . $i, $val->cabezaHogar);
                    if($val->id_profesion == ''){
                         $objPHPExcel->setActiveSheetIndex(0)
                         ->setCellValue('AA' . $i, 'NO HAY INFORMACION');
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('AA' . $i, $val->profesion->profesion);
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('AB' . $i, $val->discapacidadEmpleado)
                    ->setCellValue('AC' . $i, $val->banco->entidad)
                    ->setCellValue('AD' . $i, $val->tipocuenta)
                    ->setCellValue('AE' . $i, $val->numero_cuenta)
                    ->setCellValue('AF' . $i, $val->user_name)
                    ->setCellValue('AG' . $i, $val->user_name_editado)
                    ->setCellValue('AH' . $i, $val->observacion);
                   
                   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Empleados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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
