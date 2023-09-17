<?php

namespace app\controllers;

use yii;
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
use app\models\PresupuestoEmpresarial;
use app\models\PresupuestoEmpresarialSearch;
use app\models\UsuarioDetalle;
use app\models\AreaEmpresa;
use app\models\PresupuestoMensual;
use app\models\PresupuestoMensualDetalle;
use app\models\Clientes;
use app\models\Pedidos;

/**
 * PresupuestoEmpresarialController implements the CRUD actions for PresupuestoEmpresarial model.
 */
class PresupuestoEmpresarialController extends Controller
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
     * Lists all PresupuestoEmpresarial models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',40])->all()){
                $searchModel = new PresupuestoEmpresarialSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            } 
        }else{
            return $this->redirect(['site/login']);
        }
    }
//PRESUPUESTO MENSUAL
    public function actionPresupuesto_mensual($token = 0) {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 38])->all()) {
                $form = new \app\models\FiltroBusquedaCitas();
                $desde = null;
                $hasta = null;
                $presupuesto = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $presupuesto = Html::encode($form->presupuesto);
                        $table = PresupuestoMensual::find()
                                    ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])
                                    ->andFilterWhere(['=', 'id_presupuesto', $presupuesto]);
                        $table = $table->orderBy('fecha_inicio DESC');
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
                        if (isset($_POST['excel'])) {
                            $this->actionExcelPresupuestoMensual($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                     $table = PresupuestoMensual::find()->orderBy('fecha_inicio DESC');
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
                    if (isset($_POST['excel'])) {
                        $this->actionExcelPresupuestoMensual($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('presupuesto_mensual', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }
    
    //CONSULTA DE PRESUPUESTO POR AREA
     public function actionSearch_presupuesto_area($token = 1) {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 46])->all()) {
                $form = new \app\models\FiltroBusquedaCitas();
                $desde = null;
                $hasta = null;
                $presupuesto = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $presupuesto = Html::encode($form->presupuesto);
                        $table = PresupuestoMensual::find()
                                    ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])
                                    ->andFilterWhere(['=', 'id_presupuesto', $presupuesto])
                                    ->andWhere(['=','cerrado', 1]);
                        $table = $table->orderBy('fecha_inicio DESC');
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
                        if (isset($_POST['excel'])) {
                            $this->actionExcelPresupuestoMensual($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                     $table = PresupuestoMensual::find()->Where(['=','cerrado', 1])->orderBy('fecha_inicio DESC');
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
                    if (isset($_POST['excel'])) {
                        $this->actionExcelPresupuestoMensual($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_presupuesto_area', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }
    /**
     * Displays a single PresupuestoEmpresarial model.
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
    
    public function actionView_cliente($desde, $hasta, $id_presupuesto,$id, $cerrado, $token) {
        $model = PresupuestoMensual::findOne($id);
        if($token == 0){
            if($cerrado == 0){
                $this->BuscarClientePedido($desde, $hasta, $id_presupuesto, $id);
            }    
        }    
        $detalle = PresupuestoMensualDetalle::find()->where(['=','id_mensual', $id])->all();
        return $this->render('view_cliente', [
            'model' => $model,
            'detalle' => $detalle,
            'cerrado' => $cerrado,
            'desde' => $desde,
            'hasta' => $hasta,
            'id' => $id,
            'token' => $token,
        ]);
    }
    protected function BuscarClientePedido($desde, $hasta,$id_presupuesto, $id) {
        
        if($id_presupuesto == 1){// //presupuesto comercial (numero 1)
            $mensual = PresupuestoMensual::findOne($id);
            $cliente = Clientes::find()->where(['=','estado_cliente', 0])->andWhere(['>','cupo_asignado', 0])
                                       ->andWhere(['>','presupuesto_comercial', 0])->orderBy('nombre_completo DESC')->all();
            if(count($cliente) > 0){
                $totalPresupuesto = 0;
                foreach ($cliente as $clientes):
                    $pedido = Pedidos::find()->where(['between','fecha_proceso', $desde, $hasta])->andWhere(['=','id_cliente', $clientes->id_cliente])
                                            ->andWhere(['=','presupuesto', 1])->all();
                    if(count($pedido) > 0){
                        $suma = 0;
                        foreach ($pedido as $pedidos):
                           $suma += $pedidos->valor_presupuesto;
                        endforeach;   
                        $con = PresupuestoMensualDetalle::find()->where(['=','id_cliente', $clientes->id_cliente])->andWhere(['=','id_mensual', $id])->one();
                        if(!$con){
                            $table = new PresupuestoMensualDetalle();
                            $table->id_mensual = $id;
                            $table->id_cliente = $clientes->id_cliente;
                            $table->gasto_mensual = $suma;
                            $table->presupuesto_asignado = $clientes->presupuesto_comercial;
                            $table->save();
                        }     
                    }
                    $totalPresupuesto += $clientes->presupuesto_comercial;
                endforeach;
                $detalle = PresupuestoMensualDetalle::find()->where(['=','id_mensual', $id])->all();
                $con = 0; $total = 0;
                foreach ($detalle as $detalles):
                    $con += 1;
                    $total += $detalles->gasto_mensual;
                endforeach;
                $mensual->total_registro = $con;
                $mensual->valor_gastado = $total;
                $mensual->presupuesto_mensual = round($totalPresupuesto /12);
               $mensual->porcentaje = round((($total / $mensual->presupuesto_mensual)*100),2);
                $mensual->save();
            }else{
                Yii::$app->getSession()->setFlash('warning', 'No existen clientes que se les halla asignado presupuesto comercial o estan inactivos. Validar con el administrador.');
                 return $this->redirect(['presupuesto_mensual']);
            }    
        }else{
            Yii::$app->getSession()->setFlash('info', 'Este proceso esta en desarrollo.');
            return $this->redirect(['presupuesto-empresarial/presupuesto_mensual']);
        }    
    }
    /**
     * Creates a new PresupuestoEmpresarial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PresupuestoEmpresarial();
        $sw = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $model->user_name = Yii::$app->user->identity->username;
             $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'sw'=> $sw,
        ]);
    }
     public function actionCrear_fechas()
    {
        $model = new PresupuestoMensual();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $model->user_name = Yii::$app->user->identity->username;
             $model->save();
            return $this->redirect(['presupuesto_mensual']);
        }

        return $this->render('_form_crear_fecha', [
            'model' => $model,
        ]);
    }
   
    //acutalizar regisgtro mensuales
     public function actionUpdate_mensual($id_mensual)
    {
        $model = PresupuestoMensual::findOne($id_mensual);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['presupuesto_mensual']);
        }
       
            return $this->render('_form_crear_fecha', [
                'model' => $model,
            ]);
        }
    /**
     * Updates an existing PresupuestoEmpresarial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $sw = 1)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        if($model->anio_cerrado == 0){
            return $this->render('update', [
                'model' => $model,
                'sw' =>$sw,
            ]);
        }else{
           Yii::$app->getSession()->setFlash('warning', 'Este proceso ya esta cerrado por presupuesto. Consulte el administrador.'); 
           $this->redirect(["presupuesto-empresarial/index"]);
        }    
    }

    /**
     * Deletes an existing PresupuestoEmpresarial model.
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
            $this->redirect(["presupuesto-empresarial/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["presupuesto-empresarial/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
            $this->redirect(["presupuesto-empresarial/index"]);
        }
    }

    //autorizado
    public function actionAutorizado($desde, $hasta,$id, $cerrado, $id_presupuesto) {
        $mensual = PresupuestoMensual::findOne($id);
        if($mensual->autorizado == 0){
            $mensual->autorizado = 1;
            $mensual->save();
        }else{
            $mensual->autorizado = 0;
            $mensual->save();
        }
         $this->redirect(["presupuesto-empresarial/view_cliente",'desde'=>$desde, 'hasta' => $hasta, 'id' =>$id, 'cerrado'=>$cerrado, 'id_presupuesto' => $id_presupuesto]);
    }
    //CERRAR EL MES
    public function actionCerrar_mes($id) {
        $mensual = PresupuestoMensual::findOne($id);
        if($mensual->cerrado == 0){
            $mensual->cerrado = 1;
            $mensual->save();
            $this->redirect(['presupuesto_mensual']);    
        }
        
    }
    public function actionImprimir_cierre_mensual($id) {
       $model = PresupuestoMensual::findOne($id);
        return $this->render('../formatos/reporte_presupuesto_mensual', [
            'model' => $model,
        ]);
       
    }
    
    //CERRAR PRESUPUESTO ANUAL
    
    public function actionCerrar_anio($id, $desde, $hasta) {
        $presupuesto = PresupuestoEmpresarial::findOne($id);
        $mensual = PresupuestoMensual::find()->where(['=','id_presupuesto', $id])->andWhere(['between','fecha_inicio', $desde, $hasta])->all();
        $total =0;
        foreach ($mensual as $meses):
            $total += $meses->valor_gastado;
        endforeach;
        $presupuesto->valor_gastado = $total;
        $presupuesto->estado = 1;
        $presupuesto->anio_cerrado = 1;
        $presupuesto->save();
        $this->redirect(["presupuesto-empresarial/view",'id' =>$id]);
    }
    /**
     * Finds the PresupuestoEmpresarial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PresupuestoEmpresarial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PresupuestoEmpresarial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //PROCESOS DE EXCEL
    // CONSULTA DE PRESUPUESTO MENSUAL
    public function actionExcelPresupuestoMensual($tableexcel) {                
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO PRESUPUESTO')
                    ->setCellValue('C1', 'DESDE')
                    ->setCellValue('D1', 'HASTA')
                    ->setCellValue('E1', 'FECHA CREACION')
                    ->setCellValue('F1', 'TOTAL REGISTRO')
                    ->setCellValue('G1', 'VR. GASTADO')
                    ->setCellValue('H1', 'AUTORIZADO')
                    ->setCellValue('I1', 'CERRADO')
                    ->setCellValue('J1', 'USER NAME')    
                    ->setCellValue('K1', 'OBSERVACION');
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_presupuesto)
                    ->setCellValue('B' . $i, $val->presupuesto->descripcion)
                    ->setCellValue('C' . $i, $val->fecha_inicio)
                    ->setCellValue('D' . $i, $val->fecha_corte)
                    ->setCellValue('E' . $i, $val->fecha_creacion)
                    ->setCellValue('F' . $i, $val->total_registro)
                    ->setCellValue('G' . $i, $val->valor_gastado)
                    ->setCellValue('H' . $i, $val->autorizadoMes)
                    ->setCellValue('I' . $i, $val->cerradoMes)
                    ->setCellValue('J' . $i, $val->user_name)
                    ->setCellValue('K' . $i, $val->observacion);
                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Detalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Presupuesto_mensual.xlsx"');
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
