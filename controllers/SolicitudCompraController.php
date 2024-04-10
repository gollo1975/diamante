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

//models
use app\models\SolicitudCompraDetalles;
use app\models\SolicitudCompra;
use app\models\UsuarioDetalle;
use app\models\Items;
use app\models\FiltroBusquedaSolicitudCompra;
use app\models\FormModeloBuscar;

/**
 * SolicitudCompraController implements the CRUD actions for SolicitudCompra model.
 */
class SolicitudCompraController extends Controller
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
     * Lists all SolicitudCompra models.
     * @return mixed
     */
     public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',10])->all()){
                $form = new FiltroBusquedaSolicitudCompra();
                $codigo = null;
                $solicitud = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $area = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $solicitud = Html::encode($form->solicitud);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $area = Html::encode($form->area);
                        $table = SolicitudCompra::find()
                                    ->andFilterWhere(['=', 'numero_solicitud', $codigo])
                                    ->andFilterWhere(['>=', 'fecha_entrega', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_entrega', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_solicitud', $solicitud])
                                    ->andFilterWhere(['=', 'id_area', $area]);
                        $table = $table->orderBy('id_solicitud_compra DESC');
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
                            $check = isset($_REQUEST['id_solicitud_compra  DESC']);
                            $this->actionExcelConsultaSolicitudCompra($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = SolicitudCompra::find()
                            ->orderBy('id_solicitud_compra DESC');
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
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaSolicitudCompra($tableexcel);
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
    
    //CONSULTA  DE SOLICITUDES
    
     public function actionSearch_consulta_solicitud_compra($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',28])->all()){
                $form = new FiltroBusquedaSolicitudCompra();
                $codigo = null;
                $solicitud = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $area = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $solicitud = Html::encode($form->solicitud);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $area = Html::encode($form->area);
                        $table = SolicitudCompra::find()
                                    ->andFilterWhere(['=', 'numero_solicitud', $codigo])
                                    ->andFilterWhere(['>=', 'fecha_entrega', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_entrega', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_solicitud', $solicitud])
                                    ->andFilterWhere(['=', 'id_area', $area])
                                    ->andWhere(['>','numero_solicitud', 0]);
                        $table = $table->orderBy('id_solicitud_compra DESC');
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
                            $check = isset($_REQUEST['id_solicitud_compra  DESC']);
                            $this->actionExcelConsultaSolicitudCompra($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = SolicitudCompra::find()
                            ->where(['>','numero_solicitud', 0])
                            ->orderBy('id_solicitud_compra DESC');
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
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaSolicitudCompra($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_consulta_solicitud_compra', [
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
     * Displays a single SolicitudCompra model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle_compras = SolicitudCompraDetalles::find()->where(['=','id_solicitud_compra', $id])->all();
        if(isset($_POST["actualizaregistro"])){
            if(isset($_POST["detalle_compra"])){
                $intIndice = 0;
                $auxiliar = 0;
                $iva = 0 ;
                foreach ($_POST["detalle_compra"] as $intCodigo):
                    $table = SolicitudCompraDetalles::findOne($intCodigo);
                    $table->cantidad = $_POST["cantidad"]["$intIndice"];
                    $table->valor = $_POST["valor"]["$intIndice"];
                    $auxiliar =  $table->cantidad * $table->valor;
                    $table->subtotal = $auxiliar;
                    $iva = round(($auxiliar * $table->porcentaje_iva)/100);
                    $table->valor_iva = $iva;
                    $table->total_solicitud = $iva + $auxiliar;
                    $table->save(false);
                    $auxiliar = 0;
                    $iva = 0;   
                    $intIndice++;
                endforeach;
                $this->ActualizarLineas($id);
                return $this->redirect(['view','id' =>$id, 'token' => $token]);
            }
            
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_compras' => $detalle_compras,
        ]);
    }
    //subproceso
    protected function ActualizarLineas($id){
        $solicitud = SolicitudCompra::findOne($id);
        $detalle = SolicitudCompraDetalles::find()->where(['=','id_solicitud_compra', $id])->all();
        $subtotal = 0;
        $impuesto = 0;
        $total = 0;
        foreach ($detalle as $detalles):
              $subtotal += $detalles->subtotal;
              $impuesto += $detalles->valor_iva;
              $total += $detalles->total_solicitud;
        endforeach;
        $solicitud->subtotal = $subtotal;
        $solicitud->total_impuesto = $impuesto;
        $solicitud->total = $total;
        $solicitud->save(false);
    }

    
    /**
     * Creates a new SolicitudCompra model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SolicitudCompra();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $token = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name = Yii::$app->user->identity->username;
            $model->update();
            return $this->redirect(['view','id' =>$model->id_solicitud_compra, 'token' => $token]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SolicitudCompra model.
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
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

      /**
     * Finds the SolicitudCompra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SolicitudCompra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    
    // proceso que busca los nuevos items
    
    public function actionCrearitems($id, $token, $id_solicitud)
    {
        $operacion = \app\models\Items::find()->where(['=','id_solicitud', $id_solicitud])->orderBy('descripcion ASC')->all();
        $form = new FormModeloBuscar();
        $q = null;
        $mensaje = '';
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                    $operacion = Items::find()
                            ->where(['like','descripcion',$q])
                            ->orwhere(['=','id_items',$q])
                            ->andWhere(['=','id_solicitud', $id_solicitud]);
                    $operacion = $operacion->orderBy('descripcion ASC');                    
                    $count = clone $operacion;
                    $to = $count->count();
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count()
                    ]);
                    $operacion = $operacion
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            $table = Items::find()->where(['=','id_solicitud', $id_solicitud])->orderBy('descripcion ASC');
            $tableexcel = $table->all();
            $count = clone $table;
            $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
            ]);
             $operacion = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        //PROCESO DE GUARDAR
         if (isset($_POST["guardaritems"])) {
            if(isset($_POST["detalle_items"])){
                $intIndice = 0;
                foreach ($_POST["detalle_items"] as $intCodigo) {
                    $ingreso = SolicitudCompraDetalles::find()
                            ->where(['=', 'id_items', $intCodigo])
                            ->andWhere(['=', 'id_solicitud_compra', $id])
                            ->all();
                    $reg = count($ingreso);
                    if ($reg == 0) {
                        $item = Items::findOne($intCodigo);
                        $table = new SolicitudCompraDetalles();
                        $table->id_items = $intCodigo;
                        $table->id_solicitud_compra = $id;
                        $table->porcentaje_iva = $item->iva->valor_iva;
                        $table->save(false);
                    }
                }
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('crearnuevositems', [
            'operacion' => $operacion,            
            'mensaje' => $mensaje,
            'pagination' => $pages,
            'id' => $id,
            'form' => $form,
            'token' => $token,
            'id_solicitud' => $id_solicitud,

        ]);
    }
    //PROCESO QUE ELIMINA
    public function actionEliminar($id,$detalle, $token)
    {                                
        $detalle = SolicitudCompraDetalles::findOne($detalle);
        $detalle->delete();
        $this->ActualizarLineas($id);
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
    
    public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        if ($model->autorizado == 0) {                        
                $model->autorizado = 1;            
               $model->update();
               $this->redirect(["solicitud-compra/view", 'id' => $id, 'token' =>$token]);  

        } else{
                $model->autorizado = 0;
                $model->update();
                $this->redirect(["solicitud-compra/view", 'id' => $id, 'token' =>$token]);  
        }    
    }
    
    //CIIERA EL PROCESO
    public function actionCerrarsolicitud($id, $token) {
        //proceso de generar consecutivo
        $lista = \app\models\Consecutivos::findOne(1);
        $solicitud = SolicitudCompra::findOne($id);
        $solicitud->numero_solicitud = $lista->numero_inicial + 1;
        $solicitud->save(false);
        $lista->numero_inicial = $solicitud->numero_solicitud;
        $lista->save(false);
        $this->redirect(["solicitud-compra/view", 'id' => $id, 'token' =>$token]);  
    }
    //IMPRESIONES
    public function actionImprimirsolicitud($id) {
        $model = SolicitudCompra::findOne($id);
        return $this->render('../formatos/reportesolicitudcompra', [
            'model' => $model,
        ]);
    }
    
    protected function findModel($id)
    {
        if (($model = SolicitudCompra::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //proceso de excel
    
    public function actionExcelconsultaSolicitudCompra($tableexcel) {                
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO SOLICITUD')
                    ->setCellValue('C1', 'AREA')
                    ->setCellValue('D1', 'SOPORTE')
                    ->setCellValue('E1', 'FECHA PROCESO')
                    ->setCellValue('F1', 'FECHA CREACION')
                    ->setCellValue('G1', 'USER NAME')
                    ->setCellValue('H1', 'NUMERO')
                    ->setCellValue('I1', 'AUTORIZADO')
                    ->setCellValue('J1', 'SUBTOTAL')
                    ->setCellValue('K1', 'IVA')
                    ->setCellValue('L1', 'TOTAL')
                    ->setCellValue('M1', 'OBSERVACION');
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_solicitud_compra)
                    ->setCellValue('B' . $i, $val->solicitud->descripcion)
                    ->setCellValue('C' . $i, $val->area->descripcion)
                    ->setCellValue('D' . $i, $val->documento_soporte)
                    ->setCellValue('E' . $i, $val->fecha_entrega)
                    ->setCellValue('F' . $i, $val->fecha_creacion)
                    ->setCellValue('G' . $i, $val->user_name)
                    ->setCellValue('H' . $i, $val->numero_solicitud)
                    ->setCellValue('I' . $i, $val->autorizadoCompra)
                    ->setCellValue('J' . $i, $val->subtotal)
                    ->setCellValue('K' . $i, $val->total_impuesto)
                    ->setCellValue('L' . $i, $val->total)
                    ->setCellValue('M' . $i, $val->observacion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Solicitud_Compra.xlsx"');
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
