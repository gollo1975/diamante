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
use app\models\FacturaVenta;
use app\models\FacturaVentaSearch;
use app\models\UsuarioDetalle;
use app\models\Clientes;
use app\models\AgentesComerciales;
use app\models\FiltroBusquedaPedidos;
use app\models\Pedidos;
use app\models\FacturaVentaDetalle;
/**
 * FacturaVentaController implements the CRUD actions for FacturaVenta model.
 */
class FacturaVentaController extends Controller
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
     * Lists all FacturaVenta models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',52])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; $saldo = null; $numero_factura = null;
               if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $saldo = Html::encode($form->saldo);
                        $numero_factura = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                            ->andFilterWhere(['=', 'nit_cedula', $documento])
                            ->andFilterWhere(['=', 'id_cliente', $cliente])
                            ->andFilterWhere(['between','fecha_inicio', $fecha_inicio, $fecha_corte])
                            ->andFilterWhere(['=','numero_factura', $numero_factura])
                            ->andFilterWhere(['>', 'saldo_factura', $saldo])     
                           ->andFilterWhere(['=','id_agente', $vendedores]);
                        $table = $table->orderBy('id_factura DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                    ->all();
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVenta($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = FacturaVenta::find()->orderBy('id_factura DESC');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVenta($tableexcel);
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

    //PROCESO QUE CREA LA FACTURA DE VENTA
    
     public function actionCrear_factura() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',51])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; 
               if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $table = Pedidos::find()
                            ->andFilterWhere(['=', 'documento', $documento])
                            ->andFilterWhere(['=', 'id_cliente', $cliente])
                            ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                            ->andWhere(['=','pedido_validado', 1])
                            ->andWhere(['=','facturado', 0])    
                           ->andFilterWhere(['=','id_agente', $vendedores]);
                        $table = $table->orderBy('id_pedido DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                    ->all();
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaPedidos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = Pedidos::find()->Where(['=','pedido_validado', 1])
                                            ->andWhere(['=','facturado', 0])->orderBy('id_pedido DESC');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaPedidos($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('crear_factura_venta', [
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
     * Displays a single FacturaVenta model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detalle_factura' => $detalle_factura,
            'token' => $token,
        ]);
    }

    /**
     * Creates a new FacturaVenta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FacturaVenta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_factura]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FacturaVenta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $token)
    {
        $model = $this->findModel($id);
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name_editado = Yii::$app->user->identity->username;
            $model->fecha_editada = date('Y-m-d');
            $model->save(false);
            $this->DescuentoFactura($id);
            $this->ActualizarConceptosTributarios($id);
            return $this->redirect(['view', 'id' => $model->id_factura, 'token'=> $token]);
        }

        return $this->render('update', [
            'model' => $model,
            'id' => $id,
            'token' => $token,
        ]);
    }
    //PROCESO QUE PERMITE PONER EL DECUENTO A LA FACTURA
    
    protected function DescuentoFactura($id) {
        $factura = FacturaVenta::findOne($id);
        $dato = 0;
        if($factura->porcentaje_descuento > 0){
              $dato = round($factura->valor_bruto * $factura->porcentaje_descuento)/100; 
              $factura->subtotal_factura = $factura->valor_bruto - $dato;
              $factura->descuento = $dato;
              $factura->impuesto = round($factura->subtotal_factura * $factura->porcentaje_iva)/100;
              $factura->total_factura = $factura->subtotal_factura +  $factura->impuesto;
              $factura->saldo_factura = $factura->total_factura;
              $factura->save(false);
        }else{
            $dato = 0;
            $factura->subtotal_factura = $factura->valor_bruto;
            $factura->descuento = $dato;
            $factura->impuesto = $factura->impuesto;
            $factura->total_factura = $factura->total_factura ;
            $factura->saldo_factura = $factura->total_factura;
            $factura->save(false);
        }      
    }

    /**
     * Deletes an existing FacturaVenta model.
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
    //CREAR FACTURA DESDE PEDIDO
    public function actionImportar_pedido_factura($id_pedido, $token = 0) {
        if($factura = FacturaVenta::find()->where(['=','id_pedido', $id_pedido])->one()){
            Yii::$app->getSession()->setFlash('warning', 'Este pedido esta en proceso de facturacion. Consulte con el administrador.'); 
            return $this->redirect(["factura-venta/crear_factura"]);
        }else{
           $pedido = Pedidos::find()->where(['=','id_pedido', $id_pedido])->one();
            $tipo_factura = \app\models\TipoFacturaVenta::findOne(1);
            $resolucion = \app\models\ResolucionDian::find()->where(['=','estado_resolucion', 0])->one();
            $iva = \app\models\ConfiguracionIva::findOne(1);
            $empresa = \app\models\MatriculaEmpresa::findOne(1);
            $fecha_actual = date('Y-m-d');
            $table = new FacturaVenta();
            $table->id_pedido = $id_pedido;
            $table->id_cliente = $pedido->id_cliente;
            $table->id_agente = $pedido->id_agente;
            $table->id_tipo_factura = $tipo_factura->id_tipo_factura;
            $table->nit_cedula = $pedido->documento;
            $table->dv = $pedido->dv;
            $table->cliente = $pedido->cliente;
            $table->direccion = $pedido->clientePedido->direccion;
            $table->telefono_cliente = $pedido->clientePedido->celular;
            $table->numero_resolucion = $resolucion->numero_resolucion;
            $table->desde = $resolucion->desde;
            $table->hasta = $resolucion->hasta;
            $table->consecutivo = $resolucion->consecutivo;
            $table->fecha_inicio = $fecha_actual;
            $dias = $pedido->clientePedido->plazo;
            $table->fecha_vencimiento = date("Y-m-d",strtotime($fecha_actual."+".$dias."days")); 
            $table->fecha_generada = $fecha_actual;
            $table->porcentaje_iva = $iva->valor_iva;
            if($pedido->clientePedido->autoretenedor == 1){
                $table->porcentaje_rete_iva = $empresa->porcentaje_reteiva;
            }else{
                $table->porcentaje_rete_iva = 0;
            }
            if($empresa->sugiere_retencion == 0){
               if($pedido->clientePedido->tipo_regimen == 1){
                    $table->porcentaje_rete_fuente = $tipo_factura->porcentaje_retencion; 
                }else{
                    $table->porcentaje_rete_fuente = 0; 
                }
            }else{
                $table->porcentaje_rete_fuente = 0; 
            }
            $table->forma_pago = $pedido->clientePedido->forma_pago;        
            $table->plazo_pago = $pedido->clientePedido->plazo;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save();
            $model = FacturaVenta::find()->orderBy('id_factura DESC')->one();
            $id = $model->id_factura;
            $this->CrearDetalleFactura($id_pedido, $id);
            $this->ActualizarSaldosTotales($id);
            $this->ActualizarConceptosTributarios($id);
            return $this->redirect(["factura-venta/view", 'id' => $id,'token' => $token]);
        }
                
                
    }
  //PROCESO QUE QUE TOTALIZA SALDOS
    protected function CrearDetalleFactura($id_pedido, $id) {
        $detalle_pedido = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id_pedido])->all();
        foreach ($detalle_pedido as $detalle):
            $base = new FacturaVentaDetalle();
            $base->id_factura = $id;
            $base->id_inventario = $detalle->id_inventario;
            $base->codigo_producto = $detalle->inventario->codigo_producto;
            $base->producto = $detalle->inventario->nombre_producto;
            $base->cantidad = $detalle->cantidad;
            $base->valor_unitario = $detalle->valor_unitario;
            $base->subtotal = $detalle->subtotal;
            $base->impuesto = $detalle->impuesto;
            $base->total_linea = $detalle->total_linea;
            $base->save(false);
        endforeach;
    }
    ///PROCESO QUE SUMA LOS TOTALES
    protected function ActualizarSaldosTotales($id) {
        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
        $factura = FacturaVenta::findOne($id);
        $subtotal = 0; $impuesto = 0; $total = 0;
        foreach ($detalle_factura as $detalle):
            $subtotal += $detalle->subtotal;
            $impuesto += $detalle->impuesto;
            $total += $detalle->total_linea;
        endforeach;
        $factura->valor_bruto = $subtotal;
        $factura->subtotal_factura = $subtotal;
        $factura-> impuesto= $impuesto;
        $factura->total_factura = $total;
        $factura->saldo_factura = $total;
        $factura->valor_retencion = 0;
        $factura->valor_reteiva = 0;
        $factura->save(false);
    }
    //PROCESO QUE TOTALIZA LOS CONCEPTOS TRIBUTARIOS
    protected function ActualizarConceptosTributarios($id) {
        $factura = FacturaVenta::findOne($id);
        $tipo_factura = \app\models\TipoFacturaVenta::findOne(1);
        $reteiva = 0; $retecion = 0;
        $reteiva = round($factura->impuesto * $factura->porcentaje_rete_iva)/100; 
        if($factura->subtotal_factura > $tipo_factura->base_retencion){
           $retecion = round($factura->subtotal_factura * $factura->porcentaje_rete_fuente)/100;     
        }else{
           $retecion = 0; 
        }
        $factura->valor_retencion = $retecion;
        $factura->valor_reteiva = $reteiva;
        $factura->total_factura = round(($factura->subtotal_factura + $factura->impuesto) - ($factura->valor_retencion + $factura->valor_reteiva));
        $factura->saldo_factura = $factura->total_factura;
        $factura->save(false);
    }
    
    //REGERAR FACTURA
    public function actionRegenerar_factura($id, $token) {
        $this->ActualizarSaldosTotales($id);
        $this->ActualizarConceptosTributarios($id);
        $this->redirect(["view", 'id' => $id,'token' => $token]);
    }
    //PROCESO QUE AUTORIZADO O DESAUTORIZA
    public function actionAutorizado($id, $token) {
        $factura = FacturaVenta::findOne($id);
            if($factura->autorizado == 0){
                $factura->autorizado = 1;
            }else{
                $factura->autorizado = 0;
            }
            $factura->save();
            $this->redirect(["view", 'id' => $id,'token' => $token]);
    }
    //CREAR EL CONSECUTIVO DEL FACTURA DE VENTA
     public function actionGenerar_factura($id, $id_pedido, $token) {
        //proceso de generar consecutivo
         $pedido = Pedidos::findOne($id_pedido);
        $consecutivo = \app\models\Consecutivos::findOne(6);
        $factura = FacturaVenta::findOne($id);
        $factura->numero_factura = $consecutivo->numero_inicial + 1;
        $factura->save(false);
        $consecutivo->numero_inicial = $factura->numero_factura;
        $consecutivo->save(false);
        $pedido->facturado = 1;
        $pedido->save();
        $this->redirect(["view", 'id' => $id, 'token' => $token]);  
    }
    /**
     * Finds the FacturaVenta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FacturaVenta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FacturaVenta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //IMPRESIONES
    public function actionImprimir_factura_venta($id, $token) {
        $model = FacturaVenta::findOne($id);
        return $this->render('../formatos/reporte_factura_venta', [
            'model' => $model,
        ]);
    }
    //proceso de excel
    //PERMITE EXPORTAR A EXCEL EL PRESUPUESTO DE CADA PEDIDO 
    public function actionExcelconsultaPedidos($tableexcel) {                
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
                        ->setCellValue('B1', 'No PEDIDO')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'FECHA PEDIDO')
                        ->setCellValue('F1', 'CANTIDAD')
                        ->setCellValue('G1', 'SUBTOTAL')
                        ->setCellValue('H1', 'IVA')
                        ->setCellValue('I1', 'TOTAL')
                        ->setCellValue('J1', 'VENDEDOR')    
                        ->setCellValue('K1', 'USER NAME')
                        ->setCellValue('L1', 'AUTORIZADO')
                        ->setCellValue('M1', 'CERRADO')
                        ->setCellValue('N1', 'FACTURADO')
                        ->setCellValue('O1', 'APLICA PRESUPUESTO')
                        ->setCellValue('P1', 'VALOR PRESUPUESTO');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_pedido)
                        ->setCellValue('B' . $i, $val->numero_pedido)
                        ->setCellValue('C' . $i, $val->documento)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->fecha_proceso)
                        ->setCellValue('F' . $i, $val->cantidad)
                        ->setCellValue('G' . $i, $val->subtotal)
                        ->setCellValue('H' . $i, $val->impuesto)
                        ->setCellValue('I' . $i, $val->gran_total)
                        ->setCellValue('J' . $i, $val->agentePedido->nombre_completo)
                        ->setCellValue('K' . $i, $val->usuario)
                        ->setCellValue('L' . $i, $val->autorizadoPedido)
                        ->setCellValue('M' . $i, $val->pedidoAbierto)
                        ->setCellValue('N' . $i, $val->pedidoFacturado)
                        ->setCellValue('O' . $i, $val->presupuestoPedido)
                        ->setCellValue('P' . $i, $val->valor_presupuesto);
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Pedidos.xlsx"');
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
    //PROCESO QUE EXPORTA FACTURAS
     //PERMITE EXPORTAR A EXCEL EL PRESUPUESTO DE CADA PEDIDO 
        public function actionExcelFacturaVenta($tableexcel) {                
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

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'No FACTURA')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'VENDEDOR')
                        ->setCellValue('F1', 'TIPO FACTURA')
                        ->setCellValue('G1', 'No PEDIDO')
                        ->setCellValue('H1', 'FECHA INICIO')
                        ->setCellValue('I1', 'FECHA VENCIMIENTO')
                        ->setCellValue('J1', 'F. ENVIADA DIAN')    
                        ->setCellValue('K1', 'FORMA PAGO')
                        ->setCellValue('L1', 'PLAZO')
                        ->setCellValue('M1', 'VR. BRUTO')
                        ->setCellValue('N1', 'DESCUENTO')
                        ->setCellValue('O1', 'SUBTOTAL')
                        ->setCellValue('P1', 'IVA')
                        ->setCellValue('Q1', 'RETENCION')
                        ->setCellValue('R1', 'RETE IVA')
                        ->setCellValue('S1', 'TOTAL PAGAR')
                        ->setCellValue('T1', 'SALDO FACTURA')
                        ->setCellValue('U1', '% IVA')
                        ->setCellValue('V1', '% RETENCION')
                        ->setCellValue('W1', '% RETE IVA')
                        ->setCellValue('X1', '% DESCUENTO')
                        ->setCellValue('Y1', 'USER CREADOR')
                        ->setCellValue('Z1', 'USER EDITADO')
                        ->setCellValue('AA1', 'F. EDITADO')
                        ->setCellValue('AB1', 'AUTORIZADO')
                        ->setCellValue('AC1', 'OBSERVACION');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_factura)
                        ->setCellValue('B' . $i, $val->numero_factura)
                        ->setCellValue('C' . $i, $val->nit_cedula)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->agenteFactura->nombre_completo)
                        ->setCellValue('F' . $i, $val->tipoFactura->descripcion)
                        ->setCellValue('G' . $i, $val->pedido->numero_pedido)
                        ->setCellValue('H' . $i, $val->fecha_inicio)
                        ->setCellValue('I' . $i, $val->fecha_vencimiento)
                        ->setCellValue('J' . $i, $val->fecha_enviada)
                        ->setCellValue('K' . $i, $val->formaPago)
                        ->setCellValue('L' . $i, $val->plazo_pago)
                        ->setCellValue('M' . $i, $val->valor_bruto)
                        ->setCellValue('N' . $i, $val->descuento)
                        ->setCellValue('O' . $i, $val->subtotal_factura)
                        ->setCellValue('P' . $i, $val->impuesto)
                        ->setCellValue('Q' . $i, $val->valor_retencion)
                        ->setCellValue('R' . $i, $val->valor_reteiva)
                        ->setCellValue('S' . $i, $val->total_factura)
                        ->setCellValue('T' . $i, $val->saldo_factura)
                        ->setCellValue('U' . $i, $val->porcentaje_iva)
                        ->setCellValue('V' . $i, $val->porcentaje_rete_fuente)
                        ->setCellValue('W' . $i, $val->porcentaje_rete_iva)
                        ->setCellValue('X' . $i, $val->porcentaje_descuento)
                        ->setCellValue('Y' . $i, $val->user_name)
                        ->setCellValue('Z' . $i, $val->user_name_editado)
                        ->setCellValue('AA' . $i, $val->fecha_editada)
                        ->setCellValue('AB' . $i, $val->autorizadofactura)
                        ->setCellValue('AC' . $i, $val->observacion);
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Factura_venta.xlsx"');
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
