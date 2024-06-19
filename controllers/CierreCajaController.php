<?php

namespace app\controllers;
       
//clases        
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
use app\models\CierreCaja;
use app\models\CierreCajaSearch;
use app\models\UsuarioDetalle;
use app\models\FacturaVentaPunto;
use app\models\Remisiones;

/**
 * CierreCajaController implements the CRUD actions for CierreCaja model.
 */
class CierreCajaController extends Controller
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
     * Lists all CierreCaja models.
     * @return mixed
     */
    public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',110])->all()){
                $form = new \app\models\FiltroBusquedaCierreCaja();
                $numero_cierre = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $conPunto = \app\models\PuntoVenta::find()->where(['=','id_punto', Yii::$app->user->identity->id_punto])->one();
                $accesoToken = $conPunto->id_punto;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_cierre = Html::encode($form->numero_cierre);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = CierreCaja::find()
                                ->andFilterWhere(['between', 'fecha_inicio', $fecha_inicio , $fecha_corte])
                                ->andFilterWhere(['=', 'numero_cierre', $numero_cierre])
                                 ->andWhere(['=', 'id_punto', $accesoToken]);
                        $table = $table->orderBy('id_cierre DESC');
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
                            $this->actionExcelconsultaCierreCaja($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = CierreCaja::find()->Where(['=', 'id_punto', $conPunto->id_punto])
                            ->orderBy('id_cierre DESC');
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
                            $this->actionExcelconsultaCierreCaja($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'conPunto' => $conPunto,
                            'accesoToken' => $accesoToken,
                    
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //CONSULTA DE CIERRES DE CAJA
      public function actionSearch_cierre_caja() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',111])->all()){
                $form = new \app\models\FiltroBusquedaCierreCaja();
                $numero_cierre = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $punto_venta = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_cierre = Html::encode($form->numero_cierre);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                         $punto_venta = Html::encode($form->punto_venta);
                        $table = CierreCaja::find()
                                ->andFilterWhere(['between', 'fecha_inicio', $fecha_inicio , $fecha_corte])
                                ->andFilterWhere(['=', 'numero_cierre', $numero_cierre])
                                 ->andFilterWhere(['=', 'id_punto', $punto_venta]);
                        $table = $table->orderBy('id_cierre DESC');
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
                            $this->actionExcelconsultaCierreCaja($tableexcel);
                        }
                        
                    } else {
                        $form->getErrors();
                    }
                    
                } else {
                    $table = CierreCaja::find()->orderBy('id_cierre DESC');
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
                            $this->actionExcelconsultaCierreCaja($tableexcel);
                    }
                    
                }
                $to = $count->count();
                return $this->render('search_cierre_caja', [
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
     * Displays a single CierreCaja model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $accesoToken)
    {
        $conrecibofactura = \app\models\CierreCajaFactura::find()->where(['=','id_cierre', $id])->all();
        $conreciboremision = \app\models\CierreCajaRemision::find()->where(['=','id_cierre', $id])->all();
        if (Yii::$app->request->post()) {
            if (isset($_POST["eliminartodo"])) {
                if (isset($_POST["listado_eliminar"])) {
                    foreach ($_POST["listado_eliminar"] as $intCodigo) {
                        try {
                            $eliminar = \app\models\CierreCajaFactura::findOne($intCodigo);
                            $eliminar->delete();
                            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                            $this->redirect(["cierre-caja/view", 'id' => $id, 'accesoToken' => $accesoToken]);
                        } catch (IntegrityException $e) {

                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');
                        } catch (\Exception $e) {
                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');

                        }
                    }
                } else {
                    Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar al menos un registro.');
                }    
             }
        }  
        //proceso para eliminar cierre de caja de remision
        if (Yii::$app->request->post()) {
            if (isset($_POST["eliminar_todo_remision"])) {
                if (isset($_POST["listado_eliminar_remision"])) {
                    foreach ($_POST["listado_eliminar_remision"] as $intCodigo) {
                        try {
                            $eliminar = \app\models\CierreCajaRemision::findOne($intCodigo);
                            $eliminar->delete();
                            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                            $this->redirect(["cierre-caja/view", 'id' => $id, 'accesoToken' => $accesoToken]);
                        } catch (IntegrityException $e) {

                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');
                        } catch (\Exception $e) {
                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');

                        }
                    }
                } else {
                    Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar al menos un registro.');
                }    
             }
        }  
        return $this->render('view', [
            'model' => $this->findModel($id),
            'accesoToken' => $accesoToken,
            'id' => $id,
            'conrecibofactura' => $conrecibofactura,
            'conreciboremision' => $conreciboremision,
        ]);
    }
    
    //VISTA PARA VER LA CIERRES DE CAJA
    public function actionView_search($id) {
       $conrecibofactura = \app\models\CierreCajaFactura::find()->where(['=','id_cierre', $id])->all();
       $conreciboremision = \app\models\CierreCajaRemision::find()->where(['=','id_cierre', $id])->all(); 
       return $this->render('view_search', [
            'model' => $this->findModel($id),
            'id' => $id,
            'conrecibofactura' => $conrecibofactura,
            'conreciboremision' => $conreciboremision,
        ]); 
    }

    /**
     * Creates a new CierreCaja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($accesoToken)
    {
        $fecha_dia_actual = date('Y-m-d');
        $facturas = FacturaVentaPunto::find()->where(['=','fecha_inicio', $fecha_dia_actual])->andWhere(['=','id_punto', $accesoToken])->all(); // cargo las facturas
        $remisiones = Remisiones::find()->where(['=','fecha_inicio', $fecha_dia_actual])->andWhere(['=','id_punto', $accesoToken])->andWhere(['=','expedir_factura', 0])->all(); // cargo las remisiones  
        $total_remision = 0;
        $total_factura = 0;
       
        foreach ($facturas as $factura){
            $total_factura += $factura->total_factura;
        }
        foreach ($remisiones as $remision):
            $total_remision += $remision->total_remision; 
        endforeach;
        if(count($facturas)> 0 || count($remisiones)> 0){
            $table = new CierreCaja();
            $table->id_punto = $accesoToken;
            $table->fecha_inicio = $fecha_dia_actual;
            $table->fecha_corte = $fecha_dia_actual;
            $table->total_remision = $total_remision;      
            $table->total_factura = $total_factura;
            $table->total_cierre_caja = $total_remision + $total_factura;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save();
            $model = CierreCaja::find()->orderBy('id_cierre DESC')->limit(1)->one();
            return $this->redirect(['view', 'id' => $model->id_cierre, 'accesoToken' => $accesoToken]);
        }else{
            Yii::$app->getSession()->setFlash('warning', 'No hay ventas realizadas el dia de hoy en este punto de venta.');
            $this->redirect(["cierre-caja/index"]);
        }    
    }

    // IMPORTAR RECIBO DE CAJA DE LA FACTURAS
    public function actionCargar_recibo_factura($id, $accesoToken, $fecha_inicio) {
        
        $cierre = CierreCaja::findOne($id);
        $ReciboFactura = \app\models\ReciboCajaPuntoVenta::find()->where(['=','fecha_recibo', $fecha_inicio])
                                                                 ->andWhere(['=','id_punto', $accesoToken])->andWhere(['>','id_factura', 0])->all(); 
        if(count($ReciboFactura)> 0){
            $efectivo = 0;
            $transferencia = 0;
            foreach ($ReciboFactura as $recibofactura):
                $formaP = \app\models\FormaPago::findOne($recibofactura->id_forma_pago);
                $table = new \app\models\CierreCajaFactura();
                $table->id_cierre = $id;
                $table->id_recibo = $recibofactura->id_recibo;
                $table->id_factura = $recibofactura->id_factura;
                $table->valor_pago = $recibofactura->valor_abono;
                $table->user_name = Yii::$app->user->identity->username;
                $table->save();
                if($formaP->abreviatura == 'TR'){
                    $transferencia += $recibofactura->valor_abono;
                }else{
                    $efectivo += $recibofactura->valor_abono;
                }
            endforeach;
            $cierre->total_transacion_factura = $transferencia;
            $cierre->total_efectivo_factura = $efectivo;
            $cierre->save();
             return $this->redirect(['view', 'id' => $id, 'accesoToken' => $accesoToken]);
        }else{
              Yii::$app->getSession()->setFlash('warning', 'No hay recibos de caja realizados el dia de hoy.');
               return $this->redirect(['view', 'id' => $id, 'accesoToken' => $accesoToken]);
        }
     
    }
    
    //  // IMPORTAR RECIBO DE CAJA DE LA REMISION
    public function actionCargar_recibo_remision($id, $accesoToken, $fecha_inicio) {
        
        $cierre = CierreCaja::findOne($id);
        $ReciboRemision = \app\models\ReciboCajaPuntoVenta::find()->where(['=','fecha_recibo', $fecha_inicio])
                                                                 ->andWhere(['=','id_punto', $accesoToken])->andWhere(['>','id_remision', 0])->all(); 
        if(count($ReciboRemision)> 0){
            $efectivo = 0;
            $transferencia = 0;
            foreach ($ReciboRemision as $remision):
                $formaP = \app\models\FormaPago::findOne($remision->id_forma_pago);
                $table = new \app\models\CierreCajaRemision();
                $table->id_cierre = $id;
                $table->id_recibo = $remision->id_recibo;
                $table->id_remision = $remision->id_remision;
                $table->valor_pago = $remision->valor_abono;
                $table->user_name = Yii::$app->user->identity->username;
                $table->save();
                if($formaP->abreviatura == 'TR'){
                    $transferencia += $remision->valor_abono;
                }else{
                    $efectivo += $remision->valor_abono;
                }
            endforeach;
            $cierre->total_transacion_remision = $transferencia;
            $cierre->total_efectivo_remision = $efectivo;
            $cierre->save();
             return $this->redirect(['view', 'id' => $id, 'accesoToken' => $accesoToken]);
        }else{
              Yii::$app->getSession()->setFlash('warning', 'No hay recibos de caja realizados el dia de hoy.');
               return $this->redirect(['view', 'id' => $id, 'accesoToken' => $accesoToken]);
        }
     
    }
    
    //autorizar el proceso
    public function actionAutorizado($id, $accesoToken) {
        $model = CierreCaja::findOne($id);
        $remision = \app\models\CierreCajaRemision::find()->where(['=','id_cierre', $id])->one(); 
        $factura = \app\models\CierreCajaFactura::find()->where(['=','id_cierre', $id])->one(); 
        if(!$remision){
              Yii::$app->getSession()->setFlash('warning', 'No ha cargado los recibos de caja del proceso de remisiones. Favor valide esta informacion. ');
        }
        if(!$factura){
              Yii::$app->getSession()->setFlash('info', 'No ha cargado los recibos de caja del proceso de facturas. Favor valide esta informacion. ');
        }     
       
        if($model->autorizado == 0){
            $model->autorizado = 1;
            $model->save();
             return $this->redirect(['view', 'id' => $id, 'accesoToken' => $accesoToken]);
        }else{
           $model->autorizado = 0;
            $model->save(); 
             return $this->redirect(['view', 'id' => $id, 'accesoToken' => $accesoToken]);
        }
    }
    
    //PEMRITE CERRAR LA CAJA Y GENERAR CONSECUTIVO
    public function actionCerrar_caja_punto($id, $accesoToken) {
        
        $consecutivo = \app\models\Consecutivos::findOne(20);
        $caja = CierreCaja::findOne($id);
        $caja->numero_cierre = $consecutivo->numero_inicial + 1;
        $caja->proceso_cerrado = 1;
        $caja->save();
        $consecutivo->numero_inicial = $caja->numero_cierre;
        $consecutivo->save();
        return $this->redirect(['view', 'id' => $id, 'accesoToken' => $accesoToken]);
    }
    /**
     * Finds the CierreCaja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CierreCaja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CierreCaja::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //PROCESO QUE EXPORTA EL CIERRE DE CAJA
     public function actionExcelconsultaCierreCaja($tableexcel) { // EXPORTAR RECIBOS DE CAJA DE FACTURAS                
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
                    ->setCellValue('B1', 'NRO CIERRE')
                    ->setCellValue('C1', 'PUNTO DE VENTA')
                    ->setCellValue('D1', 'DESDE')
                    ->setCellValue('E1', 'HASTA')
                    ->setCellValue('F1', 'TOTAL TRANSACION FACTURA')
                    ->setCellValue('G1', 'TOTAL EFECTIVO FACTURA')
                    ->setCellValue('H1', 'TOTAL FACTURA')
                    ->setCellValue('I1', 'TOTAL TRANSACION EFECTIVO')
                    ->setCellValue('J1', 'TOTAL EFECTIVO EFECTIVO')
                    ->setCellValue('K1', 'TOTAL REMISION')
                    ->setCellValue('L1', 'TOTAL CAJA')
                    ->setCellValue('M1', 'USER NAME')
                    ->setCellValue('N1', 'FECHA HORA CARGA');
                    
        $i = 2;

        foreach ($tableexcel     as $val) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_cierre)
                    ->setCellValue('B' . $i, $val->numero_cierre)
                    ->setCellValue('C' . $i, $val->punto->nombre_punto)
                    ->setCellValue('D' . $i, $val->fecha_corte)
                    ->setCellValue('E' . $i, $val->fecha_corte )
                    ->setCellValue('F' . $i, $val->total_transacion_factura)
                    ->setCellValue('G' . $i, $val->total_efectivo_factura)
                    ->setCellValue('H' . $i, $val->total_factura)
                    ->setCellValue('I' . $i, $val->total_transacion_remision)
                    ->setCellValue('J' . $i, $val->total_efectivo_remision)
                    ->setCellValue('K' . $i, $val->total_remision)
                    ->setCellValue('L' . $i, $val->total_cierre_caja)
                    ->setCellValue('M' . $i, $val->user_name)
                    ->setCellValue('N' . $i, $val->fecha_hora_registro);
                   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Detalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cierre_Caja_facturas.xlsx"');
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
    
    //PROCESO QUE EXPORTA EL DETALLE DEL RECIBO DE FACTURAS
     public function actionExcel_recibo_facturas($id) { // EXPORTAR RECIBOS DE CAJA DE FACTURAS                
       $detalle = \app\models\CierreCajaFactura::find()->where(['=', 'id_cierre', $id])->all();  
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
                    ->setCellValue('A1', 'NRO CIERRE')
                    ->setCellValue('B1', 'PUNTO DE VENTA')
                    ->setCellValue('C1', 'DESDE')
                    ->setCellValue('D1', 'HASTA')
                    ->setCellValue('E1', 'TOTAL FACTURA')
                    ->setCellValue('F1', 'USER NAME')
                    ->setCellValue('G1', 'FACTURA')
                    ->setCellValue('H1', 'NRO RECIBO')
                    ->setCellValue('I1', 'VALOR RECIBO')
                    ->setCellValue('J1', 'FECHA HORA CARGA');
                    
        $i = 2;

        foreach ($detalle as $val) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->cierreCaja->numero_cierre)
                    ->setCellValue('B' . $i, $val->cierreCaja->punto->nombre_punto)
                    ->setCellValue('C' . $i, $val->cierreCaja->fecha_inicio)
                    ->setCellValue('D' . $i, $val->cierreCaja->fecha_corte)
                    ->setCellValue('E' . $i, $val->cierreCaja->total_factura )
                    ->setCellValue('F' . $i, $val->cierreCaja->user_name)
                    ->setCellValue('G' . $i, $val->factura->numero_factura)
                    ->setCellValue('H' . $i, $val->recibo->numero_recibo)
                    ->setCellValue('I' . $i, $val->valor_pago)
                    ->setCellValue('J' . $i, $val->fecha_hora_carga);
                   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Detalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cierre_Caja_facturas.xlsx"');
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
    
    //PROCESO QUE EXPORTA EL DETALLE DEL RECIBO DE REMISIONES
     public function actionExcel_recibo_remision($id) { // EXPORTAR RECIBOS DE CAJA DE FACTURAS                
       $detalle = \app\models\CierreCajaRemision::find()->where(['=', 'id_cierre', $id])->all();  
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
                    ->setCellValue('A1', 'NRO CIERRE')
                    ->setCellValue('B1', 'PUNTO DE VENTA')
                    ->setCellValue('C1', 'DESDE')
                    ->setCellValue('D1', 'HASTA')
                    ->setCellValue('E1', 'TOTAL REMISION')
                    ->setCellValue('F1', 'USER NAME')
                    ->setCellValue('G1', 'REMISION')
                    ->setCellValue('H1', 'NRO RECIBO')
                    ->setCellValue('I1', 'VALOR RECIBO')
                    ->setCellValue('J1', 'FECHA HORA CARGA');
                    
        $i = 2;

        foreach ($detalle as $val) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->cierreCaja->numero_cierre)
                    ->setCellValue('B' . $i, $val->cierreCaja->punto->nombre_punto)
                    ->setCellValue('C' . $i, $val->cierreCaja->fecha_inicio)
                    ->setCellValue('D' . $i, $val->cierreCaja->fecha_corte)
                    ->setCellValue('E' . $i, $val->cierreCaja->total_remision )
                    ->setCellValue('F' . $i, $val->cierreCaja->user_name)
                    ->setCellValue('G' . $i, $val->remision->numero_remision)
                    ->setCellValue('H' . $i, $val->recibo->numero_recibo)
                    ->setCellValue('I' . $i, $val->valor_pago)
                    ->setCellValue('J' . $i, $val->fecha_hora_carga);
                   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Detalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cierre_Caja_remisiones.xlsx"');
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
