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

use app\models\DevolucionProductos;
use app\models\DevolucionProductosSearch;
use app\models\UsuarioDetalle;
use app\models\InventarioProductos;
use app\models\TipoDevolucionProductos;
use app\models\Clientes;
use app\models\DevolucionProductoDetalle;

/**
 * DevolucionProductosController implements the CRUD actions for DevolucionProductos model.
 */
class DevolucionProductosController extends Controller
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
     * Lists all DevolucionProductos models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',64])->all()){
                $form = new \app\models\FiltroBusquedaDevolucion();
                $numero = null;
                $cliente = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $cliente = Html::encode($form->cliente);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = DevolucionProductos::find()
                                    ->andFilterWhere(['=', 'numero_devolucion', $numero])
                                    ->andFilterWhere(['between', 'fecha_devolucion', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_cliente', $cliente]);
                        $table = $table->orderBy('id_devolucion DESC');
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
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = DevolucionProductos::find()->orderBy('id_devolucion DESC');
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
  
  //consulta de devoluciones
    public function actionSearch_consulta_devolucion($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',65])->all()){
                $form = new \app\models\FiltroBusquedaDevolucion();
                $numero = null;
                $cliente = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $seleccion = 0;
                $sw = 0;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $cliente = Html::encode($form->cliente);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $seleccion = Html::encode($form->seleccion);
                        $table = DevolucionProductos::find()
                                    ->andFilterWhere(['=', 'numero_devolucion', $numero])
                                    ->andFilterWhere(['between', 'fecha_devolucion', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_cliente', $cliente])
                                    ->andWhere(['>', 'numero_devolucion', 0]);
                        $table = $table->orderBy('id_devolucion DESC');
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
                        if ($seleccion == 0){
                            if (isset($_POST['excel'])) {
                                $check = isset($_REQUEST['id_devolucion  DESC']);
                                $this->actionExcelDevolucion($tableexcel);
                            }
                        }else{
                           if (isset($_POST['excel'])) {
                                $check = isset($_REQUEST['id_devolucion  DESC']);
                                $this->actionExcel_devolucion_detalle($tableexcel);
                            } 
                        }    
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = DevolucionProductos::find()->Where(['>', 'numero_devolucion', 0])->orderBy('id_devolucion DESC');
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
                        $this->actionExcelDevolucion($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_consulta_devolucion', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                            'seleccion' => $seleccion,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }

    /**
     * Displays a single DevolucionProductos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle_devolucion = \app\models\DevolucionProductoDetalle::find()->where(['=','id_devolucion', $id])->all(); 
        if(isset($_POST["actualizacantidades"])){
            if(isset($_POST["actualizar_cantidades"])){
                $intIndice = 0; $cantDevolucion = 0; $cantAveria = 0; $total = 0;
                foreach ($_POST["actualizar_cantidades"] as $intCodigo):  
                    $table = DevolucionProductoDetalle::findOne($intCodigo);
                    $cantDevolucion = $_POST["cantidad_inventario"]["$intIndice"];
                    $cantAveria = $_POST["cantidad_averias"]["$intIndice"];
                    $total = $cantAveria + $cantDevolucion;
                    if($total <= $table->cantidad){
                        $table->cantidad_devolver = $cantDevolucion;
                        $table->cantidad_averias = $cantAveria;
                        $table->id_tipo_devolucion = $_POST["tipo_devolucion"]["$intIndice"];
                        $table->save(false);
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'En el producto ' .$table->nombre_producto . ', la cantidad de unidades a devolver es mayor que las cantidades entregadas.');
                    } 
                   $intIndice++; 
                endforeach;
                $this->CalcularUnidades($id);
                return $this->redirect(['view','id' =>$id, 'token' => $token]);
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detalle_devolucion' => $detalle_devolucion,
            'token' => $token,
        ]);
    }
    //PROCESO QUE SUMA LAS UNIDADES
    protected function CalcularUnidades($id) {
        $model = DevolucionProductos::findOne($id);
        $detalle_devolucion = \app\models\DevolucionProductoDetalle::find()->where(['=','id_devolucion', $id])->all(); 
        $conAveria = 0; $conInventario = 0;
        foreach ($detalle_devolucion as $detalle):
            $conInventario += $detalle->cantidad_devolver; 
            $conAveria += $detalle->cantidad_averias;        
        endforeach;
        $model->cantidad_inventario = $conInventario;
        $model->cantidad_averias = $conAveria;
        $model->save();
    }

    /**
     * Creates a new DevolucionProductos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DevolucionProductos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_devolucion]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DevolucionProductos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_devolucion]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DevolucionProductos model.
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
    //PROCESO QUE AUTORIZA LA DEVOLUCION
     public function actionAutorizado($id, $token) {
         $model = $this->findModel($id);
         if($model->autorizado == 0){
             $model->autorizado = 1;
             $model->save();
         }else{
             $model->autorizado = 0;
             $model->save();
         }
          return $this->redirect(['view','id' => $id, 'token' => $token]);
    }
    
    //PROCESO QUE GENERA LA DEVOLUCION
     public function actionGenerar_devolucion_inventario($id, $token) {
        //proceso que actuliza saldos en inventario
        $this->SaldoInventario($id);
        //proceso de generar consecutivo
        $consecutivo = \app\models\Consecutivos::findOne(9);
        $devolucion = DevolucionProductos::findOne($id);
        $devolucion->numero_devolucion = $consecutivo->numero_inicial + 1;
        $devolucion->save(false);
        $consecutivo->numero_inicial = $devolucion->numero_devolucion;
        $consecutivo->save(false);
        $this->redirect(["view", 'id' => $id, 'token' => $token]);  
    }
   //PROCESO QUE ACTUALIZAD SALDOS EN INVENTARIO
   protected function SaldoInventario($id) {
       $detalle_nota = DevolucionProductoDetalle::find()->where(['=','id_devolucion', $id])->all();
       foreach ($detalle_nota as $detalle):
            $codigo = $detalle->id_inventario;
            if($inventario = InventarioProductos::findOne($detalle->id_inventario)){
                 $inventario->stock_unidades += $detalle->cantidad_devolver; 
                 $inventario->save(false);
                 $this->ActualizarTotalesProducto($codigo);
            }
       endforeach;
   }
   protected function ActualizarTotalesProducto($codigo) {
       $inventario = InventarioProductos::findOne($codigo);
       $subtotal =0;
       $impuesto = 0;
       $total = 0;
       $subtotal = $inventario->stock_unidades * $inventario->costo_unitario;
       if($inventario->aplica_iva == 0){
          $impuesto = round($subtotal * $inventario->porcentaje_iva)/100;    
       }else{
           $impuesto = 0;
       }
       $inventario->subtotal = $subtotal;
       $inventario->valor_iva = $impuesto;
       $inventario->total_inventario = $subtotal + $impuesto;
       $inventario->save(false);
       }
     
    //IMPRESIONES
    public function actionImprimir_devolucion_producto($id) {
        $model = DevolucionProductos::findOne($id);
        return $this->render('../formatos/reporte_devolucion_producto', [
            'model' => $model,
        ]);
    }   
    /**
     * Finds the DevolucionProductos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DevolucionProductos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DevolucionProductos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //EXPORTACIONES}
    public function actionExcelDevolucion($tableexcel) {
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
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'No DEVOLUCION')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'FECHA DEVOUCION')
                    ->setCellValue('F1', 'FECHA REGISTRO')
                    ->setCellValue('G1', 'No NOTA CREDITO')
                    ->setCellValue('H1', 'CANT. INVENTARIO')
                    ->setCellValue('I1', 'CANT. AVERIAS')
                    ->setCellValue('J1', 'AUTORIZADO')
                    ->setCellValue('K1', 'USER NAME')
                    ->setCellValue('L1', 'OBSERVACION');
        $i = 2;

        foreach ($tableexcel as $val) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_devolucion)
                    ->setCellValue('B' . $i, $val->numero_devolucion)
                    ->setCellValue('C' . $i, $val->cliente->nit_cedula)
                    ->setCellValue('D' . $i, $val->cliente->nombre_completo)
                    ->setCellValue('E' . $i, $val->fecha_devolucion)
                    ->setCellValue('F' . $i, $val->fecha_registro)
                    ->setCellValue('G' . $i, $val->nota->numero_nota_credito)
                    ->setCellValue('H' . $i, $val->cantidad_inventario)
                    ->setCellValue('I' . $i, $val->cantidad_averias)
                    ->setCellValue('J' . $i, $val->autorizadoProceso)
                    ->setCellValue('K' . $i, $val->user_name)
                    ->setCellValue('L' . $i, $val->observacion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Devolucion_productos.xlsx"');
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
    //EXCEL DETALLE DEVOLUCION
    public function actionExcel_devolucion_detalle($tableexcel) {
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'No DEVOLUCION')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'FECHA DEVOUCION')
                    ->setCellValue('F1', 'FECHA REGISTRO')
                    ->setCellValue('G1', 'No NOTA CREDITO')
                    ->setCellValue('H1', 'TOTAL UNIDADES')
                    ->setCellValue('I1', 'CANT. INVENTARIO')
                    ->setCellValue('J1', 'CANT. AVERIAS')
                    ->setCellValue('K1', 'CODIGO')
                    ->setCellValue('L1', 'PRODUCTO')
                    ->setCellValue('M1', 'TIPO DEVOUCION')
                    ->setCellValue('N1', 'AUTORIZADO')
                    ->setCellValue('O1', 'USER NAME')
                    ->setCellValue('P1', 'OBSERVACION');
        $i = 2;

        foreach ($tableexcel as $devolucion) {
            $detalle = DevolucionProductoDetalle::find()->where(['=','id_devolucion', $devolucion->id_devolucion])->all();
            foreach ($detalle as $val){
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_devolucion)
                    ->setCellValue('B' . $i, $devolucion->numero_devolucion)
                    ->setCellValue('C' . $i, $devolucion->cliente->nit_cedula)
                    ->setCellValue('D' . $i, $devolucion->cliente->nombre_completo)
                    ->setCellValue('E' . $i, $devolucion->fecha_devolucion)
                    ->setCellValue('F' . $i, $devolucion->fecha_registro)
                    ->setCellValue('G' . $i, $devolucion->nota->numero_nota_credito)
                    ->setCellValue('H' . $i, $val->cantidad)     
                    ->setCellValue('I' . $i, $val->cantidad_devolver)
                    ->setCellValue('J' . $i, $val->cantidad_averias)
                    ->setCellValue('K' . $i, $val->codigo_producto)
                    ->setCellValue('L' . $i, $val->nombre_producto)
                    ->setCellValue('M' . $i, $val->tipoDevolucion->concepto)     
                    ->setCellValue('N' . $i, $devolucion->autorizadoProceso)
                    ->setCellValue('O' . $i, $devolucion->user_name)
                    ->setCellValue('P' . $i, $devolucion->observacion);
                $i++;
           }
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Devolucion_productos_detalle.xlsx"');
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
