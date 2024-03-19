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
use app\models\ProveedorEstudios;
use app\models\ProveedorEstudiosSearch;
use app\models\UsuarioDetalle;


/**
 * ProveedorEstudiosController implements the CRUD actions for ProveedorEstudios model.
 */
class ProveedorEstudiosController extends Controller
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
     * Lists all ProveedorEstudios models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',81])->all()){
                $form = new \app\models\FiltroBusquedaProveedor();
                $nitcedula = null;
                $nombre_completo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $nitcedula = Html::encode($form->nitcedula);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        $table = ProveedorEstudios::find()
                                ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                ->andFilterWhere(['like', 'nombre_completo', $nombre_completo]);
                        $table = $table->orderBy('id_estudio DESC');
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
                            $this->actionExcelconsultaProveedor($tableexcel);
                    }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = ProveedorEstudios::find()
                            ->orderBy('id_estudio desc');
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
                            $this->actionExcelconsultaProveedor($tableexcel);
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

    //APROBAR ESTUDIOS
     public function actionAprobar_estudios() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',82])->all()){
                $table = ProveedorEstudios::find()->where(['=','proceso_cerrado', 0])->andWhere(['=','aprobado', 0])
                        ->orderBy('id_estudio desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 10,
                    'totalCount' => $count->count(),
                ]);
                $tableexcel = $table->all();
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
                $to = $count->count();
                return $this->render('index_aprobar_estudio', [
                            'model' => $model,
                            'pagination' => $pages,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //APROBAR PROVEEDOR
    //PROCESO QUE EDITA LA CITA CON EL CLIENTE
    public function actionAprobar_proveedor($id) {
        $model = new \app\models\ModelAprobarProveedor();
        $table = ProveedorEstudios::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["aprobarproveedor"])) {
                $table->aprobado = $model->aprobado;
                $table->proceso_cerrado = $model->cerrar;
                $table->observacion = $model->nota;
                $table->save(false);
                $this->redirect(["proveedor-estudios/aprobar_estudios"]);
            }
        }
        if (Yii::$app->request->get()) {
            $model->aprobado = $table->aprobado;
            $model->cerrar = $table->proceso_cerrado;
            $model->nota = $table->observacion;
        }
        return $this->renderAjax('form_aprobar_proveedor', [
                    'model' => $model,
                    'id' => $id,
        ]);
    }
    /**
     * Displays a single ProveedorEstudios model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionView($id, $token)
    {
         $model = $this->findModel($id);
         $sw = 0;
         $empresa = \app\models\MatriculaEmpresa::findOne(1);
        if (Yii::$app->request->post()) {//INICIO PROCESO DE ELIMINAR
                if (isset($_POST["eliminardetalles"])) {
                    if (isset($_POST["registroeliminar"])) {
                        foreach ($_POST["registroeliminar"] as $intCodigo) {
                            try {
                                $eliminar = \app\models\ProveedorEstudioDetalles::findOne($intCodigo);
                                $eliminar->delete();
                                Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
                                 $listado_documento = \app\models\ProveedorEstudioDetalles::find()->where(['=','id_estudio', $id])->orderBy('requisito ASC')->all();
                                $this->redirect(["proveedor-estudios/view", 'id' => $id, 'token' => $token, 'listado_documento' => $listado_documento]);
                            } catch (IntegrityException $e) {

                                Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');
                            } catch (\Exception $e) {
                                Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');

                            }
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Debe seleccionar al menos un registro.');
                    }    
                 }
        }//TERMINA PROCESO
         if(isset($_POST["requisitos"])){// inicia proceso
            if(isset($_POST["listado"])){
                $intIndice = 0;
                foreach ($_POST["listado"] as $intCodigo):
                    $table = \app\models\ProveedorEstudioDetalles::findOne($intCodigo);
                    $table->aplica = $_POST["aplica_requisito"][$intIndice];    
                    $table->documento_fisico = $_POST["documento_fisico"][$intIndice];
                    $table->cumplio = $_POST["cumple"][$intIndice];
                    $table->observacion = $_POST["observacion"][$intIndice];
                    if($table->cumplio == 1){
                        $table->validado = 1;
                    }else{
                        $table->validado = 0;
                    }
                    $table->save(false);
                    $intIndice++;
                endforeach;
                $registros = \app\models\ProveedorEstudioDetalles::find()->where(['=','id_estudio', $id])->andWhere(['=','validado', 1])->all();
                $total = 0;
                foreach ($registros as $valor):
                    $total += $valor->porcentaje;
                endforeach;
                $model->total_porcentaje = $total;
                $model->validado = 1;
                if($total >= $empresa->calificacion_proveedor){
                    $model->aprobado = 1; 
                    $model->proceso_cerrado = 1;
                    $model->observacion = 'El proveedor cumple con todos los documento y requisitos';
                            
                }else{
                    $model->aprobado = 0; 
                }              
                $model->save();
                $listado_documento = \app\models\ProveedorEstudioDetalles::find()->where(['=','id_estudio', $id])->orderBy('requisito ASC')->all();
                return $this->redirect(['view','id' =>$id, 'token' => $token, 'model' => $model, 'listado_documento' => $listado_documento]);
            }
        } // termina
        
         $listado_documento = \app\models\ProveedorEstudioDetalles::find()->where(['=','id_estudio', $id])->orderBy('requisito ASC')->all();
        return $this->render('view', [
            'model' => $model,
            'token' => $token,
            'listado_documento' => $listado_documento,
        ]);
    }
    
    //PROCESO QUE CARGA LOS DOCUMENTOS DE EVALUACION
    
    public function actionCargar_requisitos($id, $token) {
        $listado = \app\models\ListadoRequisitos::find()->where(['=','aplica_proveedor', 1])->all();
        $listado_documento = \app\models\ProveedorEstudioDetalles::find()->where(['=','id_estudio', $id])->all();
        if(count($listado_documento) <= 0){
            foreach ($listado as $lista):
                $table = new \app\models\ProveedorEstudioDetalles();
                $table->id_estudio = $id;
                $table->id_requisito = $lista->id_requisito;
                $table->requisito = $lista->concepto;
                $table->porcentaje = $lista->porcentaje;
                $table->aplica = $lista->aplica_requisito;
                $table->insert();
            endforeach;
            return $this->redirect(['view', 'id' => $id, 'token' => $token]);
        }    
    }

    /**
     * Creates a new ProveedorEstudios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ProveedorEstudios();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $dv = Html::encode($_POST["dv"]);
            $table = new ProveedorEstudios();
            $table->id_tipo_documento = $model->id_tipo_documento;
            $table->nit_cedula = $model->nit_cedula;
            $table->dv = $dv;
            $table->razon_social = $model->razon_social;
            $table->primer_nombre = $model->primer_nombre;
            $table->segundo_nombre =  $model->segundo_nombre;
            $table->primer_apellido = $model->primer_apellido;
            $table->segundo_apellido =  $model->segundo_apellido;
            $table->user_name = Yii::$app->user->identity->username;
            $table->observacion = $model->observacion;
            if ($model->id_tipo_documento == 1 || $model->id_tipo_documento == 2 ) {
               $table->nombre_completo = strtoupper($model->primer_nombre . " " . $model->segundo_nombre . " " . $model->primer_apellido . " " . $model->segundo_apellido);
               $table->razon_social = null;
            } else {
                $table->nombre_completo = strtoupper($table->razon_social); 
                $table->primer_nombre = null;
                $table->segundo_nombre = null;
                $table->primer_apellido = null;
                $table->segundo_apellido = null;
            }
            $table->save(false);
            return $this->redirect(['index']);

        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing ProveedorEstudios model.
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

        if ($model->load(Yii::$app->request->post())) {
            $dv = Html::encode($_POST["dv"]);
            $table = ProveedorEstudios::find()->where(['id_estudio' => $id])->one();
            if ($table) {
                $table->id_tipo_documento = $model->id_tipo_documento;
                $table->razon_social = $model->razon_social;
                $table->primer_nombre = $model->primer_nombre;
                $table->segundo_nombre =  $model->segundo_nombre;
                $table->primer_apellido = $model->primer_apellido;
                $table->segundo_apellido =  $model->segundo_apellido;
                $table->observacion = $model->observacion;
                if ($model->id_tipo_documento == 1 || $model->id_tipo_documento == 2 ) {
                   $table->nombre_completo = strtoupper($model->primer_nombre . " " . $model->segundo_nombre . " " . $model->primer_apellido . " " . $model->segundo_apellido);
                   $table->razon_social = null;
                } else {
                    $table->nombre_completo = strtoupper($table->razon_social); 
                    $table->primer_nombre = null;
                    $table->segundo_nombre = null;
                    $table->primer_apellido = null;
                    $table->segundo_apellido = null;
                }
                $table->save(false);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProveedorEstudios model.
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
     * Finds the ProveedorEstudios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProveedorEstudios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProveedorEstudios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
     //IMPRESIONES
    public function actionImprimir_estudio($id,$token) {
        $model = ProveedorEstudios::findOne($id);
        return $this->render('../formatos/reporte_proveedor_estudio', [
            'model' => $model,
        ]);
    }
     public function actionExcelconsultaProveedor($tableexcel) {                

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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'PROVEEDOR')
                    ->setCellValue('D1', 'VALIDADO')
                    ->setCellValue('E1', 'APROBADO')
                    ->setCellValue('F1', 'TOTAL PORCENTAJE')
                    ->setCellValue('G1', 'NOMBRE DEL REQUISITO')
                    ->setCellValue('H1', 'CALIFICACION')
                    ->setCellValue('I1', 'APLICA')
                    ->setCellValue('J1', 'DOCUMENTO FISICO')
                    ->setCellValue('K1', 'VALIDADO')
                    ->setCellValue('L1', 'CUMPLIO')
                    ->setCellValue('M1', 'OBSERVACION')
                    ->setCellValue('N1', 'USER NAME')
                    ->setCellValue('O1', 'FECHA REGISTRO');
                ;
        $i = 2;

        foreach ($tableexcel as $val) {
            $detalle  = \app\models\ProveedorEstudioDetalles::find()->where(['=','id_estudio', $val->id_estudio])->all();
            foreach ($detalle as $detalles){
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_estudio)
                        ->setCellValue('B' . $i, $val->nit_cedula)
                        ->setCellValue('C' . $i, $val->nombre_completo)
                        ->setCellValue('D' . $i, $val->validadoEstudio)
                        ->setCellValue('E' . $i, $val->aprobadoEstudio)
                        ->setCellValue('F' . $i, $val->total_porcentaje)
                        ->setCellValue('G' . $i, $detalles->requisito)
                        ->setCellValue('H' . $i, $detalles->porcentaje)
                        ->setCellValue('I' . $i, $detalles->aplicaEstudio)
                        ->setCellValue('J' . $i, $detalles->documentoFisico)
                        ->setCellValue('K' . $i, $detalles->validadoEstudio)
                        ->setCellValue('L' . $i, $detalles->cumpleRequisito)
                        ->setCellValue('M' . $i, $detalles->observacion)
                        ->setCellValue('N' . $i, $detalles->user_name)
                        ->setCellValue('O' . $i, $detalles->fecha_registro);
                $i++;
            }   
           
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Estudio_provedor.xlsx"');
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
