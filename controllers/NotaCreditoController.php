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
//MODELS
use app\models\NotaCredito;
use app\models\NotaCreditoSearch;
use app\models\FacturaVenta;
use app\models\UsuarioDetalle;
use app\models\Clientes;
use app\models\NotaCreditoDetalle;
/**


/**
 * NotaCreditoController implements the CRUD actions for NotaCredito model.
 */
class NotaCreditoController extends Controller
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
    /* index 
     */
     public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',58])->all()){
                $form = new \app\models\FiltroBusquedaNota();
                $cliente = null;
                $numero = null;
                $motivo = null;
                $desde = null;
                $hasta = null;
                $documento = null;
                $factura = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $cliente = Html::encode($form->cliente);
                        $documento = Html::encode($form->documento);
                        $motivo = Html::encode($form->motivo);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);  
                        $factura = Html::encode($form->factura);
                        $table = NotaCredito::find()
                                        ->andFilterWhere(['=', 'id_cliente', $cliente])
                                        ->andFilterWhere(['=', 'nit_cedula', $documento])
                                        ->andFilterWhere(['=', 'numero_nota_credito', $numero])
                                        ->andFilterWhere(['=', 'numero_factura', $factura])
                                        ->andFilterWhere(['=', 'id_motivo', $motivo])
                                        ->andFilterWhere(['between', 'fecha_nota_credito', $desde, $hasta]);
                         
                        $table = $table->orderBy('id_nota DESC');
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
                            $this->actionExcelNotaCredito($tableexcel);
                        }
                        
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = NotaCredito::find()->orderBy('id_nota DESC');  
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
                            $this->actionExcelNotaCredito($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
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
     * Lists all NotaCredito models.
     * @return mixed
     */
     public function actionListado_factura() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',57])->all()){
                $form = new \app\models\FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; $numero_factura = null;
                $model = null;
                $pages = null;
               if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $numero_factura = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                            ->andFilterWhere(['=', 'nit_cedula', $documento])
                            ->andFilterWhere(['=', 'id_cliente', $cliente])
                            ->andFilterWhere(['between','fecha_inicio', $fecha_inicio, $fecha_corte])
                            ->andFilterWhere(['=','numero_factura', $numero_factura])
                            ->andFilterWhere(['=','id_agente', $vendedores])
                            ->andWhere(['>','saldo_factura', 0])
                            ->andWhere(['<>','estado_factura', 3]);
                        $table = $table->orderBy('id_factura DESC');
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
                }
                return $this->render('listado_factura', [
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
     * Displays a single NotaCredito model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $detalle_nota = NotaCreditoDetalle::find()->where(['=','id_nota', $id])->limit (2)->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detalle_nota' => $detalle_nota,
        ]);
    }

    /**
     * Creates a new NotaCredito model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCrear_nota_credito($id_factura) {
        if($nota = NotaCredito::find()->where(['=','id_factura', $id_factura])->orderBy('id_factura DESC')->one()){
            Yii::$app->getSession()->setFlash('warning', 'Esta factura esta en proceso de nota credito. Consulte con el administrador.'); 
            return $this->redirect(["nota-credito/listado_factura"]);
        }else{
            $factura = FacturaVenta::findOne($id_factura);
            $tipo_factura = \app\models\TipoFacturaVenta::findOne(3);
            $empresa = \app\models\MatriculaEmpresa::findOne(1);
            $fecha_actual = date('Y-m-d');
            $table = new NotaCredito();
            $table->id_cliente = $factura->id_cliente;
            $table->nit_cedula = $factura->nit_cedula;
            $table->cliente = $factura->cliente;
            $table->id_factura = $id_factura;
            $table->numero_factura = $factura->numero_factura;
            $table->id_tipo_factura = $tipo_factura->id_tipo_factura;
            $table->fecha_factura = $factura->fecha_inicio;
            $table->fecha_nota_credito = $fecha_actual;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save();
            $model = NotaCredito::find()->orderBy('id_nota DESC')->one();
            $id = $model->id_nota;
            return $this->redirect(["nota-credito/view", 'id' => $id]);
        }
    }
    
    //proceso que trae los detalle de la factura
    public function actionListar_detalle_factura($id, $id_factura) {
        $detalle_factura = \app\models\FacturaVentaDetalle::find()->where(['=','id_factura', $id_factura])->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        $nombre = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                $nombre = Html::encode($form->nombre);       
                if($q == ''){
                    $conSql = \app\models\FacturaVentaDetalle::find()
                            ->Where(['like','producto', $nombre])
                            ->andwhere(['=','id_factura', $id_factura]);
                }else{
                    $conSql = \app\models\FacturaVentaDetalle::find()
                        ->where(['=','codigo_producto', $q])
                        ->andwhere(['=','id_factura', $id_factura]);
                }    
                $conSql = $conSql->orderBy('producto ASC');  
                $count = clone $conSql;
                $to = $count->count();
                $pages = new Pagination([
                    'pageSize' => 10,
                    'totalCount' => $count->count()
                ]);
                $variable = $conSql
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            $detalle_factura = \app\models\FacturaVentaDetalle::find()->where(['=','id_factura', $id_factura]);
            $tableexcel = $detalle_factura->all();
            $count = clone $detalle_factura;
            $pages = new Pagination([
                        'pageSize' => 6,
                        'totalCount' => $count->count(),
            ]);
             $variable = $detalle_factura
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
         if (isset($_POST["devolucion_productos"])) {
            if(isset($_POST["devolucion_factura_detalle"])){
                 $intIndice = 0;
                foreach ($_POST["devolucion_factura_detalle"] as $intCodigo):
                    //consulta para no duplicar
                    $factura_detalle = \app\models\FacturaVentaDetalle::findOne($intCodigo);
                    $nota = NotaCreditoDetalle::find()->where(['=','id_nota', $id])
                                                                   ->andWhere(['=','id_inventario', $factura_detalle->id_inventario])->one();
                    if(!$nota){
                        $table = new NotaCreditoDetalle();
                        $table->id_nota = $id;
                        $table->id_inventario = $factura_detalle->id_inventario;
                        $table->codigo_producto = $factura_detalle->codigo_producto;
                        $table->producto = $factura_detalle->producto;
                        $table->cantidad = $factura_detalle->cantidad;
                        $table->valor_unitario = $factura_detalle->valor_unitario;  
                        $table->subtotal = $factura_detalle->subtotal;
                        $table->impuesto = $factura_detalle->impuesto;
                        $table->total_linea = $factura_detalle->total_linea;
                        $table->save(false);
                        $this->ActualizarLineaDetalleNota($id, $id_factura);
                      //  $this->TotalPresupuestoPedido($id, $sw);
                    }    
                    $intIndice ++;
                endforeach;
                return $this->redirect(['view','id' => $id]);
            }
        }
        return $this->render('listado_detalle_factura', [ 
            'id' => $id,
            'id_factura' => $id_factura,
            'variable' => $variable,
            'form' => $form,
            'pagination' => $pages,
            ]);
    }
   //proceso que actualiza totales
   protected function ActualizarLineaDetalleNota($id, $id_factura) {
       $factura = FacturaVenta::findOne($id_factura);
       $nota = NotaCredito::findOne($id);
       $detalle_nota = NotaCreditoDetalle::find()->where(['=','id_nota', $id])->all();
       $subtotal = 0; $impuesto = 0; $retencion = 0; $rete_iva = 0; $total = 0;
       foreach ($detalle_nota as $detalle):
           $subtotal += $detalle->subtotal;
           $impuesto += $detalle->impuesto;
       endforeach;
       $retencion = round($subtotal * $factura->porcentaje_rete_fuente)/100; 
       $rete_iva = round($impuesto * $factura->porcentaje_rete_iva)/100;
       $total = (($subtotal+$impuesto) - ($retencion + $rete_iva));
       $nota->valor_bruto = $subtotal;
       $nota->impuesto = $impuesto;
       $nota->retencion = $retencion;
       $nota->rete_iva = $rete_iva;
       $nota->valor_total_devolucion = $total;
       $nota->valor_devolucion = $total;
       $nota->nuevo_saldo = $factura->saldo_factura - $total;
       $nota->save(false);
       if($nota->nuevo_saldo < 0){
           Yii::$app->getSession()->setFlash('error', 'La nota credito tiene saldo en rojos. El valor de la nota credito es mayor que el saldo de la factura.');
       }
   }
   //eliminar detalle de la nota credito
     public function actionEliminar_detalle($id,$detalle, $id_factura)
    {                                
        $detalle = NotaCreditoDetalle::findOne($detalle);
        $detalle->delete();
        $this->ActualizarLineaDetalleNota($id, $id_factura);
        $this->redirect(["view",'id' => $id]);        
    }
    //PROCESO QUE EDITA LA LIENA DEL DETALLE
    public function actionEditar_linea_nota($id, $detalle, $id_factura) {
        $model = new \app\models\FormModeloEditarCantidad();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["editar_cantidad_linea"])) {
                    $nota_detalle = NotaCreditoDetalle::findOne($detalle);
                    $nota_detalle->cantidad = $model->cantidad;
                    $nota_detalle->save(false);
                    $this->ActualizarSubtotal($detalle, $id_factura);
                    $this->ActualizarLineaDetalleNota($id, $id_factura);
                    $this->redirect(["nota-credito/view", 'id' => $id]);
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->renderAjax('form_editar_cantidad', [
                    'model' => $model,
                    'id' => $id,
                   
        ]);
    }
    //proceso que actualiza valor unitario x cantidad
    protected function ActualizarSubtotal($detalle, $id_factura) {
        $nota_detalle = NotaCreditoDetalle::findOne($detalle);
        $factura = FacturaVenta::findOne($id_factura);
        $subtotal = 0; $total = 0; $porcentaje = 0; $iva = 0;
        $total = round($nota_detalle->cantidad * $nota_detalle->valor_unitario);
        $porcentaje = $factura->porcentaje_iva /100;
        $iva = round($total * $porcentaje);
        $nota_detalle->total_linea = $total;
        $nota_detalle->impuesto = $iva;
        $nota_detalle->subtotal = $total - $iva;
        $nota_detalle->save();
    }
    //PROCESO QUE EDITA LA NOTA
    public function actionEditar_nota($id) {
        $model = new \app\models\FormModeloEditarCantidad();
        $table = NotaCredito::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["editar_nota_credito"])) {
                    $nota = NotaCredito::findOne($id);
                    $nota->id_motivo = $model->motivo;
                    $nota->observacion = $model->observacion;
                    $nota->save(false);
                    $this->redirect(["nota-credito/view", 'id' => $id]);
                }
            } else {
                $model->getErrors();
            }
        }
         if (Yii::$app->request->get()) {
            $model->motivo = $table->id_motivo;
            $model->observacion = $table->observacion;
         }
        return $this->renderAjax('form_editar_nota', [
                    'model' => $model,
                    'id' => $id,
                   
        ]);
    }
    //proceso que se autoriza el documento
    public function actionAutorizado($id, $id_factura) {
        $model = NotaCredito::findOne($id);
        $factura = FacturaVenta::findOne($id_factura);
        if($model->valor_total_devolucion <= $factura->saldo_factura){
            if($model->autorizado == 0){
                $model->autorizado = 1;
                $model->save();
                 $this->redirect(["nota-credito/view", 'id' => $id]);
            } else{
                $model->autorizado = 0;
                $model->save();
                $this->redirect(["nota-credito/view", 'id' => $id]);
            }
        }else{
            Yii::$app->getSession()->setFlash('error', 'La nota credito tiene saldo en rojos. El valor de la nota credito es mayor que el saldo de la factura.'); 
            $this->redirect(["nota-credito/view", 'id' => $id]);
        }
    }
    //PROCESO QUE GENERA EL NUMERO DE LA NOTA CREDITO Y CARGA LAS UNIDADES AL INVENTARIO
    public function actionGenerar_nota_credito($id, $id_factura) {
        $model = NotaCredito::findOne($id);
        //proceso que actualiza saldos de facturas
        $this->ActualizarSaldoFacturas($id, $id_factura);
        //proceso de generar consecutivo
        $consecutivo = \app\models\Consecutivos::findOne(8);
        $model->numero_nota_credito = $consecutivo->numero_inicial + 1;
        $model->cerrar_nota = 1;
        $model->save(false);
        $consecutivo->numero_inicial = $model->numero_nota_credito;
        $consecutivo->save(false);
        $this->redirect(["view", 'id' => $id]);  
    }
    //SALDO DE FACTURAS
    protected function ActualizarSaldoFacturas($id, $id_factura) {
        $factura = FacturaVenta::findOne($id_factura);
        $model = NotaCredito::findOne($id);
        $saldo = 0;    
        $saldo = $factura->saldo_factura - $model->valor_total_devolucion;
        $factura->saldo_factura = $saldo;
        $factura->estado_factura = 4;
        $factura->save(false);
    }
    /**
     * Finds the NotaCredito model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NotaCredito the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NotaCredito::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //PROCESO QUE EXPORTA FACTURAS
    public function actionExcelNotaCredito($tableexcel){          
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

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'No NOTA')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'MOTIVO DIAN')
                        ->setCellValue('F1', 'CUFE FACTURA')
                        ->setCellValue('G1', 'No FACTURA')
                        ->setCellValue('H1', 'FECHA FACTURA')
                        ->setCellValue('I1', 'TIPO DOCUMENTO')    
                        ->setCellValue('J1', 'FECHA NOTA CREDITO')
                        ->setCellValue('K1', 'FECHA ENVIADA DIAN')
                        ->setCellValue('L1', 'VR. SUBTOTAL')
                        ->setCellValue('M1', 'IVA')
                        ->setCellValue('N1', 'RETENCION')
                        ->setCellValue('O1', 'RETE IVA')
                        ->setCellValue('P1', 'TOTAL NOTA CREDITO')
                        ->setCellValue('Q1', 'SALDO FACTURA')
                        ->setCellValue('R1', 'USER CREADOR')
                        ->setCellValue('S1', 'AUTORIZADO')
                        ->setCellValue('T1', 'CERRADO')
                        ->setCellValue('U1', 'OBSERVACION');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_nota)
                        ->setCellValue('B' . $i, $val->numero_nota_credito)
                        ->setCellValue('C' . $i, $val->nit_cedula)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->motivo->concepto)
                        ->setCellValue('F' . $i, $val->cufe_factura)
                        ->setCellValue('G' . $i, $val->numero_factura)
                        ->setCellValue('H' . $i, $val->fecha_factura)
                        ->setCellValue('I' . $i, $val->tipoFactura->descripcion)
                        ->setCellValue('J' . $i, $val->fecha_nota_credito)
                        ->setCellValue('K' . $i, $val->fecha_enviada)
                        ->setCellValue('L' . $i, $val->valor_bruto)
                        ->setCellValue('M' . $i, $val->impuesto)
                        ->setCellValue('N' . $i, $val->retencion)
                        ->setCellValue('O' . $i, $val->rete_iva)
                        ->setCellValue('P' . $i, $val->valor_total_devolucion)
                        ->setCellValue('Q' . $i, $val->nuevo_saldo)
                        ->setCellValue('R' . $i, $val->user_name)
                        ->setCellValue('S' . $i, $val->autorizadoNota)
                        ->setCellValue('T' . $i, $val->cerrarNota)
                        ->setCellValue('U' . $i, $val->observacion);
                        
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Notas_credito.xlsx"');
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
