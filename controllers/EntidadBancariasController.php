<?php

namespace app\controllers;

use Yii;
use app\models\EntidadBancarias;
use app\models\EntidadBancariasSearch;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaBancos;
use app\models\Municipios;
use app\models\Departamentos;
//clases
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

/**
 * EntidadBancariasController implements the CRUD actions for EntidadBancarias model.
 */
class EntidadBancariasController extends Controller
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
     * Lists all EntidadBancarias models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',6])->all()){
                $form = new FiltroBusquedaBancos();
                $codigo_banco = null;
                $banco = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo_banco = Html::encode($form->codigo_banco);
                        $banco = Html::encode($form->banco);
                        $table = EntidadBancarias::find()
                            ->andFilterWhere(['=', 'codigo_banco', $codigo_banco])
                            ->andFilterWhere(['like', 'entidad_bancaria', $banco]);
                        $table = $table->orderBy('entidad_bancaria DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 10,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['entidad_bancaria  DESC']);
                            $this->actionExcelConsultaBancos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = EntidadBancarias::find()
                            ->orderBy('entidad_bancaria desc');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaBancos($tableexcel);
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

    /**
     * Displays a single EntidadBancarias model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
        ]);
    }

    /**
     * Creates a new EntidadBancarias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EntidadBancarias();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $dv = Html::encode($_POST["dv"]);
            $empresa = \app\models\MatriculaEmpresa::findOne(1);
            $table = new EntidadBancarias();
            $table->codigo_banco = $model->codigo_banco;
            $table->id_tipo_documento = $model->id_tipo_documento;
            $table->nit_cedula = $model->nit_cedula;
            $table->dv = $dv;
            $table->entidad_bancaria = $model->entidad_bancaria;
            $table->direccion_banco = $model->direccion_banco;
            $table->telefono_banco = $model->telefono_banco;
            $table->codigo_departamento = $model->codigo_departamento;
            $table->codigo_municipio = $model->codigo_municipio;
            $table->tipo_producto = $model->tipo_producto;
            $table->producto = $model->producto;
            $table->convenio_nomina = $model->convenio_nomina;
            $table->convenio_proveedor = $model->convenio_proveedor;
            $table->convenio_empresa = $model->convenio_empresa;
            $table->user_name = Yii::$app->user->identity->username;
            $table->validador_digitos = $model->validador_digitos;
            $table->nit_empresa = $empresa->nit_empresa;
            $table->estado_registro = 0;
            $table->codigo_interfaz = $model->codigo_interfaz;
            $table->save(false);
            return $this->redirect(['index']);
  
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EntidadBancarias model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new EntidadBancarias();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $dv = Html::encode($_POST["dv"]);
            $table = EntidadBancarias::findOne($id);
            $table->id_tipo_documento = $model->id_tipo_documento;
            $table->nit_cedula = $model->nit_cedula;
            $table->dv = $dv;
            $table->entidad_bancaria = $model->entidad_bancaria;
            $table->direccion_banco = $model->direccion_banco;
            $table->telefono_banco = $model->telefono_banco;
            $table->codigo_departamento = $model->codigo_departamento;
            $table->codigo_municipio = $model->codigo_municipio;
            $table->tipo_producto = $model->tipo_producto;
            $table->producto = $model->producto;
            $table->convenio_nomina = $model->convenio_nomina;
            $table->convenio_proveedor = $model->convenio_proveedor;
            $table->convenio_empresa = $model->convenio_empresa;
            $table->validador_digitos = $model->validador_digitos;
            $table->codigo_interfaz = $model->codigo_interfaz;
            $table->save(false);
            return $this->redirect(['index']);
        }
        if (Yii::$app->request->get("id")) {
            $table = EntidadBancarias::find()->where(['codigo_banco' => $id])->one();
            $municipio = Municipios::find()->Where(['=', 'codigo_departamento', $table->codigo_departamento])->all();
            $municipio = ArrayHelper::map($municipio, "codigo_municipio", "municipio");
            if ($table) {
                $model->codigo_banco = $table->codigo_banco;
                $model->id_tipo_documento = $table->id_tipo_documento ;
                $model->nit_cedula = $table->nit_cedula;
                $model->dv = $table->dv;
                $model->entidad_bancaria =  $table->entidad_bancaria;
                $model->direccion_banco = $table->direccion_banco;
                $model->telefono_banco = $table->telefono_banco;
                $model->codigo_departamento =  $table->codigo_departamento;
                $model->codigo_municipio = $table->codigo_municipio;
                $model->tipo_producto = $table->tipo_producto;
                $model->producto = $table->producto;
                $model->convenio_nomina = $table->convenio_nomina;
                $model->convenio_proveedor = $table->convenio_proveedor;
                $model->convenio_empresa = $table->convenio_empresa;
                $model->validador_digitos = $table->validador_digitos;
                $model->codigo_interfaz = $table->codigo_interfaz;
            }else{
                 return $this->redirect(["entidad-bancarias/index"]);
            }
        }    
        return $this->render('update', [
            'model' => $model,
            'municipio' => $municipio,
        ]);
    }

    /**
     * Deletes an existing EntidadBancarias model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EntidadBancarias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntidadBancarias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntidadBancarias::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //PROCESOS DE EXCEL
     public function actionExcelconsultaBancos($tableexcel) {                
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
                                            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO DOCUMENTO')
                    ->setCellValue('C1', 'NIT/CEDULA')
                    ->setCellValue('D1', 'DV')
                    ->setCellValue('E1', 'NOMBRE BANCO')
                    ->setCellValue('F1', 'DIRECCION')
                    ->setCellValue('G1', 'TELEFONO')
                    ->setCellValue('H1', 'DEPAMENTOFONO')
                    ->setCellValue('I1', 'MUNICIPIO')
                    ->setCellValue('J1', 'TIPO CUENTA')
                    ->setCellValue('K1', 'PRODUCTO')
                    ->setCellValue('L1', 'CONVENIO NOMINA')
                    ->setCellValue('M1', 'CONVENIO PROVEEDOR')
                    ->setCellValue('N1', 'CONVENIO EMPRESA')
                    ->setCellValue('O1', 'ACTIVO')
                    ->setCellValue('P1', 'USER NAME')
                    ->setCellValue('Q1', 'CODIGO INTERFAZ')
                    ->setCellValue('R1', 'DIGITOS')
                    ->setCellValue('S1', 'FECHA CREACION');
                    
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->codigo_banco)
                    ->setCellValue('B' . $i, $val->tipoDocumento->tipo_documento)
                    ->setCellValue('C' . $i, $val->nit_cedula)
                    ->setCellValue('D' . $i, $val->dv)
                    ->setCellValue('E' . $i, $val->entidad_bancaria)
                    ->setCellValue('F' . $i, $val->direccion_banco)
                    ->setCellValue('G' . $i, $val->telefono_banco)
                    ->setCellValue('H' . $i, $val->departamento->departamento)
                    ->setCellValue('I' . $i, $val->municipio->municipio)
                    ->setCellValue('J' . $i, $val->tipoCuenta)
                    ->setCellValue('K' . $i, $val->producto)
                    ->setCellValue('L' . $i, $val->convenioNomina)
                    ->setCellValue('M' . $i, $val->convenioProveedor)
                    ->setCellValue('N' . $i, $val->convenioEmpresa)
                    ->setCellValue('O' . $i, $val->estadoRegistro)
                    ->setCellValue('P' . $i, $val->user_name)
                    ->setCellValue('Q' . $i, $val->codigo_interfaz)
                    ->setCellValue('R' . $i, $val->validador_digitos)
                    ->setCellValue('S' . $i, $val->fecha_creacion);
                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Entidades');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Bancos.xlsx"');
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
