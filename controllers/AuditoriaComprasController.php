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
//models
use app\models\AuditoriaCompras;
use app\models\AuditoriaCompraDetalles;
use app\models\AuditoriaComprasSearch;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaCompraAuditada;

/**
 * AuditoriaComprasController implements the CRUD actions for AuditoriaCompras model.
 */
class AuditoriaComprasController extends Controller
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
     * Lists all AuditoriaCompras models.
     * @return mixed
     */
     public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',84])->all()){
                $form = new FiltroBusquedaCompraAuditada();
                $numero = null;
                $tipo = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $proveedor = 0;
                $tipo_busqueda = 0;
                $nuevo_proveedor = 0;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $tipo = Html::encode($form->tipo);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $proveedor = Html::encode($form->proveedor);
                        $nuevo_proveedor = $proveedor;
                        $tipo_busqueda = Html::encode($form->tipo_busqueda);
                        $table = AuditoriaCompras::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero])
                                    ->andFilterWhere(['>=', 'fecha_auditoria', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_auditoria', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_tipo_orden', $tipo])
                                    ->andFilterWhere(['=', 'id_proveedor', $proveedor]);
                        $table = $table->orderBy('id_auditoria DESC');
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
                            $check = isset($_REQUEST['id_auditoria  DESC']);
                            $this->actionExcelAuditoriaCompra($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = AuditoriaCompras::find()
                            ->orderBy('id_auditoria DESC');
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
                        $this->actionExcelAuditoriaCompra($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                            'tipo_busqueda' => $tipo_busqueda,
                            'fecha_inicio' => $fecha_inicio,
                            'fecha_corte' => $fecha_corte,
                            'nuevo_proveedor' => $nuevo_proveedor,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }

    /**
     * Displays a single AuditoriaCompras model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle_compras = \app\models\AuditoriaCompraDetalles::find()->where(['=','id_auditoria', $id])->all();
        if(isset($_POST["actualizarauditoria"])){
            if(isset($_POST["detalle_compra"])){
                $intIndice = 0;
                $suma = 0;
                foreach ($_POST["detalle_compra"] as $intCodigo):
                    $table = \app\models\AuditoriaCompraDetalles::findOne($intCodigo);
                    $table->nueva_cantidad = $_POST["nueva_cantidad"]["$intIndice"];
                    $table->nuevo_valor = $_POST["nuevo_valor"]["$intIndice"];
                    $table->estado_producto = $_POST["estado"]["$intIndice"];
                    $table->nota = $_POST["nota"]["$intIndice"];
                    $suma = ($_POST["nueva_cantidad"]["$intIndice"] - $table->cantidad);
                    if($suma == 0){
                        $table->comentario = 'Exacto';
                    }else{
                       if($suma < 0){    
                            $table->comentario = 'Falta';
                       }else{
                           $table->comentario = 'Sobra';
                       }     
                    }
                    $table->entrada_salida = $suma;
                    $table->save(false);
                    $intIndice++;
                endforeach;
                $detalle_compras = \app\models\AuditoriaCompraDetalles::find()->where(['=','id_auditoria', $id])->all();
               return $this->redirect(['view','id' =>$id, 'token' => $token, 'detalle_compras' => $detalle_compras]);
            }
            
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_compras' => $detalle_compras,
        ]);
    }

    /**
     * Creates a new AuditoriaCompras model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuditoriaCompras();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_auditoria]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    //SUBIR DOCUMENTO FACTURA
    //PROCESO QUE VALIDA SI EL PROVEEDOR TIENE VALIDADO LOS REQUISITOS
    public function actionSubir_documento_factura($id, $token) {
        $model = new \app\models\ModelValidarRequisitos();
         if ($model->load(Yii::$app->request->post())) {
               if ($model->validate()){
                    if (isset($_POST["subirdocumento"])) {
                        $table = $this->findModel($id);
                        $table->numero_factura = $model->documento;
                        $table->save();
                        return $this->redirect(['view','id' => $id, 'token' => $token]);
                    }
               } 
         }
        return $this->renderAjax('subir_documento_factura', [
            'model' => $model,       
            'id' => $id,
            'token' => $token,
        ]);    
    }

    /**
     * Updates an existing AuditoriaCompras model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_auditoria]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AuditoriaCompras model.
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
    
    //CERRAR AUDITORIA
    
    public function actionCerrarauditoria($id, $token, $id_orden) {
        $model = $this->findModel($id);
        if($model->numero_factura == null){
            Yii::$app->getSession()->setFlash('error', 'Debe de ingresar el numero de la factura de compra o documento equivalente para cerrar el proceso de auditoria.');
            return $this->redirect(['view','id' =>$id, 'token' => $token]);
        }else{
            $orden = \app\models\OrdenCompra::findOne($id_orden);
            $model->cerrar_auditoria = 1;
            $model->save();
            $orden->auditada = 1;
            $orden->save();
          return $this->redirect(['view','id' =>$id, 'token' => $token]);
        }  
    }
    
    //IMPRESIONES
    public function actionImprimir_auditoria_compra($id) {
        $model = AuditoriaCompras::findOne($id);
        return $this->render('../formatos/reporte_auditoria_compra', [
            'model' => $model,
        ]);
    }
    /**
     * Finds the AuditoriaCompras model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuditoriaCompras the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuditoriaCompras::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //ecxceles
    public function actionExcelAuditoriaCompra($tableexcel) {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO ORDEN')
                    ->setCellValue('C1', 'PROVEEDOR')
                    ->setCellValue('D1', 'FACTURA')
                    ->setCellValue('E1', 'FECHA AUDITORIA')
                    ->setCellValue('F1', 'FECHA SOLICITUD COMPRA')
                    ->setCellValue('G1', 'USER NAME')
                    ->setCellValue('H1', 'NUMERO ORDEN')
                    ->setCellValue('I1', 'CERRADO');
                 
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_auditoria)
                    ->setCellValue('B' . $i, $val->tipoOrden->descripcion_orden)
                    ->setCellValue('C' . $i, $val->proveedor->nombre_completo)
                    ->setCellValue('D' . $i, $val->numero_factura)
                    ->setCellValue('E' . $i, $val->fecha_auditoria)
                    ->setCellValue('F' . $i, $val->fecha_proceso_compra)
                    ->setCellValue('G' . $i, $val->user_name)
                    ->setCellValue('H' . $i, $val->numero_orden)
                    ->setCellValue('I' . $i, $val->cerrarAuditoria);
                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Auditoria_compras.xlsx"');
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
    
    //DETALLES
     public function actionExceldetalleauditoria($fecha_inicio, $fecha_corte, $nuevo_proveedor) {
        if($fecha_inicio == '' && $fecha_corte == ''){
            $auditoria = AuditoriaCompras::find()->where(['=','id_proveedor', $nuevo_proveedor])->all();
        }else{
            $auditoria = AuditoriaCompras::find()->where(['between','fecha_auditoria', $fecha_inicio, $fecha_corte ])->all();
        }
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
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO ORDEN')
                    ->setCellValue('C1', 'PROVEEDOR')
                    ->setCellValue('D1', 'FACTURA')
                    ->setCellValue('E1', 'FECHA AUDITORIA')
                    ->setCellValue('F1', 'FECHA SOLICITUD COMPRA')
                    ->setCellValue('G1', 'USER NAME')
                    ->setCellValue('H1', 'NUMERO ORDEN')
                    ->setCellValue('I1', 'CERRADO')
                    ->setCellValue('J1', 'CODIGO')
                    ->setCellValue('K1', 'PRODUCTO')
                    ->setCellValue('L1', 'CANT. SOLICITADA')
                    ->setCellValue('M1', 'CANT. ENTRADA')
                    ->setCellValue('N1', 'VL. UNITARIO')
                    ->setCellValue('O1', 'VL. COMPRA')
                    ->setCellValue('P1', 'E/S')
                    ->setCellValue('Q1', 'NOTA')
                    ->setCellValue('R1', 'ESTADO PRODUCTO')
                    ->setCellValue('S1', 'OBSERVACION AUDITOR');
                 
        $i = 2;
        
        foreach ($auditoria as $val) {
             $detalles = AuditoriaCompraDetalles::find()->where(['=','id_auditoria', $val->id_auditoria])->all();
            
             foreach ($detalles as $detalle){
                
                 $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_auditoria)
                    ->setCellValue('B' . $i, $val->tipoOrden->descripcion_orden)
                    ->setCellValue('C' . $i, $val->proveedor->nombre_completo)
                    ->setCellValue('D' . $i, $val->numero_factura)
                    ->setCellValue('E' . $i, $val->fecha_auditoria)
                    ->setCellValue('F' . $i, $val->fecha_proceso_compra)
                    ->setCellValue('G' . $i, $val->user_name)
                    ->setCellValue('H' . $i, $val->numero_orden)
                    ->setCellValue('I' . $i, $val->cerrarAuditoria)
                    ->setCellValue('J' . $i, $detalle->id_items)
                    ->setCellValue('K' . $i, $detalle->nombre_producto)
                    ->setCellValue('L' . $i, $detalle->cantidad)
                    ->setCellValue('M' . $i, $detalle->nueva_cantidad)
                    ->setCellValue('N' . $i, $detalle->valor_unitario)
                    ->setCellValue('O' . $i, $detalle->nuevo_valor)
                    ->setCellValue('P' . $i, $detalle->entrada_salida)
                    ->setCellValue('Q' . $i, $detalle->comentario)
                    ->setCellValue('R' . $i, $detalle->estadoProducto)
                    ->setCellValue('S' . $i, $detalle->nota);     
                         
                    
            $i++;
            }
          $i = $i;  
           
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Auditoria_compras.xlsx"');
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
