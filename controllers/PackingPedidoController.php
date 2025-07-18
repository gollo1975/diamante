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
use app\models\PackingPedido;
use app\models\PackingPedidoSearch;
use app\models\PackingPedidoDetalle;
use app\models\UsuarioDetalle;


/**
 * PackingPedidoController implements the CRUD actions for PackingPedido model.
 */
class PackingPedidoController extends Controller
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
     * Lists all PackingPedido models.
     * @return mixed
     */
     public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',146])->all()){
                $form = new \app\models\FiltroBusquedaPacking();
                $numero_pedido = null;
                $numero_packing = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $cliente =null; $transportadora = null; $numero_guia = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $cliente = Html::encode($form->cliente);
                        $numero_pedido = Html::encode($form->numero_pedido);
                        $numero_packing = Html::encode($form->numero_packing);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $transportadora = Html::encode($form->transportadora);
                        $numero_guia = Html::encode($form->numero_guia);
                        $table = PackingPedido::find()
                                    ->andFilterWhere(['between', 'fecha_packing', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'numero_pedido', $numero_pedido])
                                    ->andFilterWhere(['=', 'numero_packing', $numero_packing])
                                    ->andFilterWhere(['=', 'numero_guia', $numero_guia])
                                    ->andFilterWhere(['=', 'id_transportadora', $transportadora])
                                    ->andFilterWhere(['like', 'cliente', $cliente]);
                        $table = $table->orderBy('id_packing DESC');
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
                            $check = isset($_REQUEST['id_packing  DESC']);
                            $this->actionExcelPacking($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                }else{
                    
                    $table = PackingPedido::find()->orderBy('id_packing DESC');  
                                         
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
                        $check = isset($_REQUEST['id_packing  DESC']);
                        $this->actionExcelPacking($tableexcel);
                    }  
                }   
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
     * Displays a single PackingPedido model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $detalle = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $id])->orderBy('numero_caja ASC')->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detalle' => $detalle,
        ]);
    }

    /**
     * Creates a new PackingPedido model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAutorizado($id)
    {
         $model = $this->findModel($id);
        if($model->autorizado == 0){
            $this->TotalizarUnidades($id);
            $model->autorizado = 1;
            $model->save();
        }else{
            $model->autorizado = 0;
            $model->save();
        }
        return $this->redirect(['packing-pedido/view','id' => $id]);
    }
    
    //proceso que totaliza
    protected function TotalizarUnidades($id) {
        $detalle = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $id])->orderBy('numero_caja ASC')->all();
        $model = $this->findModel($id);
        $contar_unidades = 0; $contar_caja = 0; $auxiliar = 0;
        foreach ($detalle as $key => $detalles) {
            $contar_unidades += $detalles->cantidad_despachada;
            if($auxiliar <> $detalles->numero_caja){
                $contar_caja += 1;
                $auxiliar = $detalles->numero_caja;
            }else{
                $auxiliar = $detalles->numero_caja;
            }    
        }
        $model->total_cajas = $contar_caja;
        $model->total_unidades_packing = $contar_unidades;
        $model->save();
    }
    
    //SUBIR TRANSPORTADORA
    //MEDIO DE PAGO
    public function actionAdicionar_transportadora($id)
    {
        $model = new \app\models\ModeloDocumento();
        if ($model->load(Yii::$app->request->post())){
            if (isset($_POST["adicionar_transportadora"])){
                if($model->transportadora !== ''){
                    $table = PackingPedido::findOne($id);
                    $table->id_transportadora = $model->transportadora;
                    $table->save(false);
                    return $this->redirect(["view",'id' => $id]); 
                }else{
                    Yii::$app->getSession()->setFlash('warning', 'Debe de seleccionar una transportadora de la lista.');
                   return $this->redirect(["view",'id' => $id]);  
                }    
            }  
        }
        return $this->renderAjax('form_adicionar_transportadora', [
            'model' => $model,
        ]);
    }   
    
    //CERRAR EL EL PACKING
    public function actionCerrar_packing_pedido($id) {
        $model = $this->findModel($id);
        $detalle = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $id])->orderBy('numero_caja DESC')->all();
        $sw = 0; $aux = 0;
        foreach ($detalle as $key => $detalles) {
            if($detalles->cantidad_despachada <= 0){
                $sw = 1;
            }
            if($detalles->numero_guia == ''){
                $aux = 1;
            }
        }
        if($sw == 0 && $aux == 0){    
            //generar consecutivo
             $dato = \app\models\Consecutivos::findOne(24);
             $codigo = $dato->numero_inicial + 1;
             $model->numero_packing = $codigo;
             $model->cerrado_proceso = 1;
             $model->estado_packing = 1;
             $model->save();
             $dato->numero_inicial = $codigo;
             $dato->save();
             return $this->redirect(['packing-pedido/view','id' => $id]);
        }else{
            if($sw == 1){
                Yii::$app->getSession()->setFlash('error', 'Hay cajas vacias en el PACKING, favor eliminarlas o llenarlas.');
                return $this->redirect(['packing-pedido/view','id' => $id]);
            }else{
                 Yii::$app->getSession()->setFlash('error', 'Debe de subir el numero de la guia a cada caja para el packing.');
                return $this->redirect(['packing-pedido/view','id' => $id]);
            }    
        }     
         
    }
    
    //CREAR CAJA PARA EL PAKING
    public function actionCrear_caja_packing($id) {
        $model = $this->findModel($id);
        $detalle = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $id])->orderBy('numero_caja DESC')->one();
       
        $table = new \app\models\PackingPedidoDetalle();
        $table->id_packing = $id;
        if($detalle){
            $table->cantidad_porcaja = $model->unidades_caja;
            $table->numero_caja = $detalle->numero_caja + 1;
        }else{
            $table->cantidad_porcaja = $model->unidades_caja;
            $table->numero_caja = 1;
        }
        $table->save();
        $actualizarCaja = PackingPedidoDetalle::find()->where(['=','id_packing', $id])->all();
        $total = count($actualizarCaja);
        $model->total_cajas = $total;
        $model->save(false);
        return $this->redirect(['packing-pedido/view','id' => $id]);
    }

       
     //ALMACENAR PRODUCTOS EN CAJA
    public function actionAlmacenar_producto_caja($id,  $id_caja) {
        $model = new \app\models\ModeloDocumento(); 
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["empacar_producto"])) {
                    if($model->cantidad_despachada > 0){
                        $table = \app\models\PackingPedidoDetalle::findOne($id_caja) ;
                        
                        $table->cantidad_despachada = $model->cantidad_despachada;
                        $table->save(false);
                        return $this->redirect(['packing-pedido/view', 'id' => $id]);
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'Este campo no puede ser vacio, debe de ingreso al menos 1 unidad.');
                        return $this->redirect(['packing-pedido/view', 'id' => $id]);
                    }    
                }
            }else{
              $model->getErrors();  
            }
         }
         $table = \app\models\PackingPedidoDetalle::findOne($id_caja) ;
         if (Yii::$app->request->get()) {
            $model->cantidad_despachada = $table->cantidad_despachada;
         }    
         return $this->renderAjax('/almacenamiento-producto/form_almacenar_caja', [
                    'model' => $model,
                    
                ]);
    }
    
    //PERMITE SUBIR LA GUIA DEL PROVEEDOR AL PACKING
    public function actionSubir_guia_proveedor($id) {
        $model = new \app\models\ModeloDocumento(); 
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["subir_guia"])) {
                    if($model->numero_guia !== ''){
                        $packin = PackingPedido::findOne($id);
                        $table = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $id])->all() ;
                        foreach ($table as $key => $val) {
                            $val->numero_guia = $model->numero_guia;
                            $val->save();
                        }
                        $packin->numero_guia = $model->numero_guia;
                        $packin->save();
                         return $this->redirect(['packing-pedido/view', 'id' => $id]);
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'Este campo no puede ser vacion, Favor ingresar al menos un caracter.');
                        return $this->redirect(['packing-pedido/view', 'id' => $id]);
                    }    
                }
            }else{
              $model->getErrors();  
            }
        }
        return $this->renderAjax('/packing-pedido/form_subir_guia_provider', [
                    'model' => $model]);
    }
    
     //PERMITE SUBIR LA GUIA DEL PROVEEDOR AL PACKING
    public function actionSubir_guia_proveedor_individual($id, $id_detalle) {
        $model = new \app\models\ModeloDocumento(); 
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["subir_guia"])) {
                    if($model->numero_guia !== ''){
                        $table = \app\models\PackingPedidoDetalle::findOne($id_detalle) ;
                        $table->numero_guia = strtoupper($model->numero_guia);
                        $table->save();
                        return $this->redirect(['packing-pedido/view', 'id' => $id]);
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'Este campo no puede ser vacion, Favor ingresar al menos un caracter.');
                        return $this->redirect(['packing-pedido/view', 'id' => $id]);
                    }    
                }
            }else{
              $model->getErrors();  
            }
        }
         $table = \app\models\PackingPedidoDetalle::findOne($id_detalle);
         if (Yii::$app->request->get()) {
            $model->numero_guia = $table->numero_guia;
         }    
        return $this->renderAjax('/packing-pedido/form_subir_guia_provider', [
                    'model' => $model]);
    }
    
    /**
     * Deletes an existing PackingPedido model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionEliminar_caja($id_detalle, $id)
    {
        
         try {
            $dato = \app\models\PackingPedidoDetalle::findOne($id_detalle);
            $dato->delete();
            $this->TotalizarUnidades($id);
            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
            $this->redirect(["packing-pedido/view",'id' => $id]);
        } catch (IntegrityException $e) {
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
            $this->redirect(["packing-pedido/view",'id' => $id]);
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
            $this->redirect(["packing-pedido/view",'id' => $id]);
        }
    }

    /**
     * Finds the PackingPedido model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PackingPedido the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PackingPedido::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //impresiones
    //IMPRESIONES
    public function actionImprimir_packing($id) {
        $model = PackingPedido::findOne($id);
            return $this->render('../formatos/reporte_packing_pedido', [
                'model' => $model,
            ]);
        
            
    }
    
    public function actionExcelPacking($tableexcel) {
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
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'No PEDIDO')
                    ->setCellValue('C1', 'No PACKIN')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'F. CREACION')
                    ->setCellValue('F1', 'F. PACKIN')
                    ->setCellValue('G1', 'UNIDADES')
                    ->setCellValue('H1', 'T. CAJAS')
                    ->setCellValue('I1', 'No GUIA')
                    ->setCellValue('J1', 'TRANSPORTADORA');
        $i = 2;
        
        foreach ($tableexcel as $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $val->id_packing)
                ->setCellValue('B' . $i, $val->numero_pedido)
                ->setCellValue('C' . $i, $val->numero_packing)
                ->setCellValue('D' . $i, $val->cliente)
                ->setCellValue('E' . $i, $val->fecha_creacion)
                ->setCellValue('F' . $i, $val->fecha_packing)
                ->setCellValue('G' . $i, $val->total_unidades_packing)
                ->setCellValue('H' . $i, $val->total_cajas)
                ->setCellValue('I' . $i, $val->numero_guia)
                ->setCellValue('J' . $i, $val->transportadora->razon_social);
              
        $i++;
             
        }

        $objPHPExcel->getActiveSheet()->setTitle('Packing');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Packing.xlsx"');
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
