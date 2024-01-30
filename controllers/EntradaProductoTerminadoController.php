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
use app\models\EntradaProductoTerminado;
use app\models\EntradaProductoTerminadoSearch;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaEntradaMateria;
use app\models\OrdenCompraDetalle;
use app\models\OrdenCompra;
use app\models\InventarioProductos;
use app\models\EntradaProductoTerminadoDetalle;

/**
 * EntradaProductoTerminadoController implements the CRUD actions for EntradaProductoTerminado model.
 */
class EntradaProductoTerminadoController extends Controller
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
     * Lists all EntradaProductoTerminado models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',69])->all()){
                $form = new FiltroBusquedaEntradaMateria();
                $id_entrada= null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $proveedor = null;
                $orden = null;
                $tipo_entrada = null;       
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $id_entrada = Html::encode($form->id_entrada);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $proveedor = Html::encode($form->proveedor);
                        $orden = Html::encode($form->orden);
                        $tipo_entrada = Html::encode($form->tipo_entrada);
                        $table = EntradaProductoTerminado::find()
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_orden_compra', $orden])
                                    ->andFilterWhere(['=', 'tipo_entrada', $tipo_entrada])
                                    ->andFilterWhere(['=', 'id_proveedor', $proveedor]);
                        $table = $table->orderBy('id_entrada DESC');
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
                            $check = isset($_REQUEST['id_entrada  DESC']);
                            $this->actionExcelConsultaEntrada($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = EntradaProductoTerminado::find()
                            ->orderBy('id_entrada DESC');
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
                        $this->actionExcelConsultaEntrada($tableexcel);
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
     * Displays a single EntradaProductoTerminado model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $inventario = InventarioProductos::find()->orderBy('nombre_producto ASC')->all();
        $models = new \app\models\ModeloEntradaProducto();
        $detalle_entrada = EntradaProductoTerminadoDetalle::find()->where(['=','id_entrada', $id])->all();
        //proceso que actualizar
        if(isset($_POST["actualizarlineas"])){
            if(isset($_POST["detalle_entrada"])){
                $intIndice = 0;
                $auxiliar = 0;
                $iva = 0 ;
                foreach ($_POST["detalle_entrada"] as $intCodigo):
                    $table = EntradaProductoTerminadoDetalle::findOne($intCodigo);
                    $table->id_inventario= $_POST["id_inventario"]["$intIndice"];
                    $table->cantidad = $_POST["cantidad"]["$intIndice"];
                    $table->actualizar_precio = $_POST["actualizar_precio"]["$intIndice"];
                    $table->porcentaje_iva = $_POST["porcentaje_iva"]["$intIndice"];
                    $table->fecha_vencimiento = $_POST["fecha_vcto"]["$intIndice"];
                    $table->valor_unitario = $_POST["valor_unitario"]["$intIndice"];
                    $auxiliar =  $table->cantidad * $table->valor_unitario;
                    $iva = round(($auxiliar * $table->porcentaje_iva)/100);
                    $table->total_iva = $iva;
                    $table->subtotal = $auxiliar;
                    $table->total_entrada = $iva + $auxiliar;
                    $table->save(false);
                    $auxiliar = 0;
                    $iva = 0;   
                    $intIndice++;
                endforeach;
                $this->ActualizarLineas($id);
                return $this->redirect(['view','id' =>$id, 'token' => $token]);
            }
            
        }
     
       // proceso manual
        
        if(isset($_POST["agregar_linea"])){
            $table = new EntradaProductoTerminadoDetalle();
            $table->id_inventario = $models->codigo_producto;
            $table->cantidad = $models->cantidad;
            $table->save(false);
            return $this->redirect(['view','id' =>$id, 'token' => $token]);
        }    
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_entrada' => $detalle_entrada,
            'inventario' => ArrayHelper::map($inventario, "id_inventario", "inventario"),
            'models' => $models,
        ]);
    }

    /**
     * Creates a new EntradaProductoTerminado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($sw)
    {
        $model = new EntradaProductoTerminado();
        $ordenes = \app\models\OrdenCompra::find()->orderBy('id_orden_compra desc')->all(); 
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name_crear= Yii::$app->user->identity->username;
            $model->update();
            $token = 0;
            if($sw == 0){
                return $this->redirect(['view', 'id' => $model->id_entrada, 'token'=> $token]);
            }else{
                return $this->redirect(['codigo_barra_ingreso', 'id' => $model->id_entrada]);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'sw' => $sw,
            'ordenes' => ArrayHelper::map($ordenes, "id_orden_compra", "descripcion"),
        ]);
    }
    //importar lineas de la orden de compra
    public function actionImportardetallecompra($id, $id_orden, $token, $proveedor)
    {                                
        $orden_compra = OrdenCompra::find()->where(['=','id_proveedor' , $proveedor])->andWhere(['=','importado', 0])->one();
        if($orden_compra){
            $detalle_compra = OrdenCompraDetalle::find()->where(['=','id_orden_compra', $orden_compra->id_orden_compra])->all();
            foreach ( $detalle_compra as $detalle_compras):
                    $table = new EntradaProductoTerminadoDetalle();
                    $table->id_entrada = $id;
                    $table->fecha_vencimiento = date('Y-m-d');
                    $table->porcentaje_iva = $detalle_compras->porcentaje;
                    $table->cantidad = $detalle_compras->cantidad;
                    $table->valor_unitario = $detalle_compras->valor;
                    $table->insert();
            endforeach;
            $this->redirect(["view",'id' => $id, 'token' => $token]);  
        }else{
            Yii::$app->getSession()->setFlash('warning', 'El proveedor NO tiene ORDENES DE COMPRA programdas para entregar.');
             $this->redirect(["view",'id' => $id, 'token' => $token]);  
        }
            
        
             
    } 
    
    /**
     * Updates an existing EntradaProductoTerminado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $sw)
    {
        $model = $this->findModel($id);
        $ordenes = \app\models\OrdenCompra::find()->where(['=','abreviatura', 'IPT'])->orderBy('id_orden_compra desc')->all(); 
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name_edit= Yii::$app->user->identity->username;
            if($sw == 1){
                $model->id_orden_compra = null;
            }
            $model->update(false);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'ordenes' => ArrayHelper::map($ordenes, "id_orden_compra", "descripcion"),
            'sw' => $sw,
        ]);
    }
     
    //NUEVA LINEA
    public function actionNuevalinea($id, $token) {
        $table = new EntradaProductoTerminadoDetalle();
        $table->id_entrada = $id;
        $table->fecha_vencimiento = date('Y-m-d');
        $table->insert();
        return $this->redirect(['view', 'id' => $id, 'token' => $token]);
    }
    //AUTORIZAR ENTRADA
     public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        if ($model->autorizado == 0) {                        
                $model->autorizado = 1;            
               $model->update();
               $this->redirect(["entrada-producto-terminado/view", 'id' => $id, 'token' =>$token]);  

        } else{
                $model->autorizado = 0;
                $model->update();
                $this->redirect(["entrada-producto-terminado/view", 'id' => $id, 'token' =>$token]);  
        }    
    }
    
     //AUTORIZAR ENTRADA SIN OC
     public function actionAutorizado_sinoc($id) {
        $model = $this->findModel($id);
        if ($model->autorizado == 0) {                        
                $model->autorizado = 1;            
               $model->update();
               $this->redirect(["entrada-producto-terminado/codigo_barra_ingreso", 'id' => $id]);  

        } else{
                $model->autorizado = 0;
                $model->update();
                $this->redirect(["entrada-producto-terminado/codigo_barra_ingreso", 'id' => $id]);  
        }    
    }
    
    /**
     * Deletes an existing EntradaProductoTerminado model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     //ELIMINAR DETALLES  
    public function actionEliminar($id,$detalle, $token)
    {                                
        $detalles = EntradaProductoTerminadoDetalle::findOne($detalle);
        $detalles->delete();
        $this->ActualizarLineas($id);
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    } 
    //ELIMINAR DETALLES  
    public function actionEliminar_manual($id, $detalle_manual)
    {                                
        $detalle = EntradaProductoTerminadoDetalle::findOne($detalle_manual);
        $detalle->delete();
        $this->ActualizarLineas($id);
        $this->redirect(["codigo_barra_ingreso",'id' => $id]);        
    } 
    
    //actualizar inventario
     public function actionEnviarinventario($id, $token , $id_compra) {
        $model = $this->findModel($id);
        $orden = OrdenCompra::find()->where(['=','id_orden_compra', $id_compra])->one();
        $detalle = EntradaProductoTerminadoDetalle::find()->where(['=','id_entrada', $id])->all(); // carga el detalle
        $codigo = 0;
        foreach ($detalle as $detalles):
            $inventario = InventarioProductos::find()->where(['=','id_inventario', $detalles->id_inventario])->one();
            if($inventario){
                $codigo = $inventario->id_inventario;
                $inventario->fecha_vencimiento = $detalles->fecha_vencimiento;
                if($detalles->actualizar_precio == 1){
                   $inventario->costo_unitario  = $detalles->valor_unitario;
                   $inventario->unidades_entradas += $detalles->cantidad; 
                   $inventario->stock_unidades += $detalles->cantidad;
                } else {
                   $inventario->unidades_entradas += $detalles->cantidad;   
                   $inventario->stock_unidades += $detalles->cantidad;
                } 
                $inventario->save(false);
                $this->ActualizarCostoInventario($codigo);
            }
        endforeach;
        $model->enviar_materia_prima = 1;
        $model->save();
        $orden->importado = 1;
        $orden->save();
        $this->redirect(["entrada-producto-terminado/view", 'id' => $id, 'token' =>$token]);
    }
    //ACTUALIZAR INVENTARIO SIN OC
    
    //actualizar inventario
     public function actionActualizar_inventario($id) {
        $model = $this->findModel($id);
        $detalle = EntradaProductoTerminadoDetalle::find()->where(['=','id_entrada', $id])->all(); // carga el detalle
        $codigo = 0;
        foreach ($detalle as $detalles):
            $inventario = InventarioProductos::find()->where(['=','id_inventario', $detalles->id_inventario])->one();
            if($inventario){
                $codigo = $inventario->id_inventario;
                $inventario->fecha_vencimiento = $detalles->fecha_vencimiento;
                if($detalles->actualizar_precio == 1){
                   $inventario->costo_unitario  = $detalles->valor_unitario;
                   $inventario->unidades_entradas += $detalles->cantidad; 
                   $inventario->stock_unidades += $detalles->cantidad;
                } else {
                   $inventario->unidades_entradas += $detalles->cantidad;   
                   $inventario->stock_unidades += $detalles->cantidad;
                } 
                $inventario->save(false);
                $this->ActualizarCostoInventario($codigo);
            }
        endforeach;
        $model->enviar_materia_prima = 1;
        $model->save();
        $this->redirect(["entrada-producto-terminado/codigo_barra_ingreso", 'id' => $id]);
    }
    
   //proceso para multiplicar inventario
    protected function ActualizarCostoInventario($codigo) {
        $iva = 0; $subtotal = 0;
        $inventario = InventarioProductos::find()->where(['=','id_inventario', $codigo])->one();
        $subtotal = round($inventario->stock_unidades * $inventario->costo_unitario);
        $iva = round(($subtotal * $inventario->porcentaje_iva)/100);
        $inventario->subtotal = $subtotal;
        $inventario->valor_iva = $iva;
        $inventario->total_inventario = $subtotal + $iva;
        $inventario->save(false);
    }
    
    
    //proceso que suma los totales
    protected function ActualizarLineas($id) {
        $entrada = EntradaProductoTerminado::findOne($id);
        $detalle = EntradaProductoTerminadoDetalle::find()->where(['=','id_entrada', $id])->all();
        $subtotal = 0; $iva = 0; $total = 0;
        foreach ($detalle as $detalles):
            $subtotal += $detalles->subtotal;
            $iva += $detalles->total_iva;
            $total += $detalles->total_entrada;
        endforeach;
        $entrada->subtotal = $subtotal;
        $entrada->impuesto = $iva;
        $entrada->total_salida = $total;
        $entrada->save(false);
    }
    
    //proceso que lleva el combo con las ordenes de cada proveedor
    public function actionOrdencompra($id){
        $rows = \app\models\OrdenCompra::find()->where(['=','id_proveedor', $id])
                                               ->andWhere(['=','importado', 0])
                                               ->andWhere(['=','abreviatura', 'IPT'])->orderBy('descripcion desc')->all();

        echo "<option value='' required>Seleccione una orden...</option>";
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id_orden_compra' required>$row->descripcion</option>";
            }
        }
    }
    
    //PROCESO QUE INGRESA CON CODIGO DE BARRAS
    public function actionCodigo_barra_ingreso($id) {
        $form = new \app\models\ModeloEntradaProducto();
        $model = EntradaProductoTerminadoDetalle::find()->where(['=','id_entrada', $id])->all();
        $codigo_producto = 0;
        if ($form->load(Yii::$app->request->get())) {
            $codigo_producto = Html::encode($form->codigo_producto);
            if ($codigo_producto > 0) {
                $table = InventarioProductos::find()->Where(['=','codigo_producto', $codigo_producto])->one();
                if($table){
                    $conDato = EntradaProductoTerminadoDetalle::find()->where(['=','codigo_producto', $codigo_producto])
                                                                      ->andWhere(['=','id_entrada', $id])->one();
                    if(!$conDato){
                        $entrada = new EntradaProductoTerminadoDetalle();
                        $entrada->id_entrada = $id;
                        $entrada->id_inventario = $table->id_inventario;
                        $entrada->codigo_producto = $codigo_producto;
                        $entrada->fecha_vencimiento = date('Y-m-d');
                        $entrada->porcentaje_iva = $table->porcentaje_iva;
                        $entrada->valor_unitario = $table->costo_unitario;
                        $entrada->save(false);
                        $model = EntradaProductoTerminadoDetalle::find()->where(['=','id_entrada', $id])->all(); 
                        $this->redirect(["entrada-producto-terminado/codigo_barra_ingreso",'id' => $id]);
                        if (isset($_POST['excel'])) {
                                $check = isset($_REQUEST['id_entrada  DESC']);
                                $this->actionExcelConsultaEntrada($tableexcel);
                        }
                    }else{
                        Yii::$app->getSession()->setFlash('success', 'El código digitado ya se en cuentra agregado a esta entrada.');
                     return $this->redirect(['codigo_barra_ingreso','id' =>$id]);
                    }    
                }else{
                     Yii::$app->getSession()->setFlash('info', 'El código del producto no se encuentra en el sistema.');
                     return $this->redirect(['codigo_barra_ingreso','id' =>$id]);
                }
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe digitar codigo del producto a buscar.');
                return $this->redirect(['codigo_barra_ingreso','id' =>$id]);
            }    
        }
        if(isset($_POST["actualizarlineas"])){
            if(isset($_POST["detalle_entrada"])){
                $intIndice = 0;
                $auxiliar = 0;
                $iva = 0 ;
                foreach ($_POST["detalle_entrada"] as $intCodigo):
                    $table = EntradaProductoTerminadoDetalle::findOne($intCodigo);
                    $table->actualizar_precio = $_POST["actualizar_precio"]["$intIndice"];
                    $table->cantidad = $_POST["cantidad"]["$intIndice"];
                    $table->fecha_vencimiento = $_POST["fecha_vcto"]["$intIndice"];
                    if($_POST["actualizar_precio"]["$intIndice"] == 1){
                       $table->valor_unitario = $_POST["valor_unitario"]["$intIndice"];
                       $auxiliar =  $table->cantidad * $table->valor_unitario;
                       $iva = round(($auxiliar * $table->porcentaje_iva)/100);
                    }else{
                       $auxiliar =  $table->cantidad * $table->valor_unitario;
                       $iva = round(($auxiliar * $table->porcentaje_iva)/100);
                    }
                    $table->total_iva = $iva;
                    $table->subtotal = $auxiliar;
                    $table->total_entrada = $iva + $auxiliar;
                    $table->save(false);
                    $auxiliar = 0;
                    $iva = 0;   
                    $intIndice++;
                endforeach;
                $this->ActualizarLineas($id);
                return $this->redirect(['codigo_barra_ingreso','id' =>$id]);
            }
            
        }
        return $this->render('_form_codigo_barras', [
                    'model' => $model,
                    'form' => $form,
                    'id' => $id,
        ]);
        
    }
    //IMPRESIONES
    public function actionImprimir_entrada_producto($id) {
        $model = EntradaProductoTerminado::findOne($id);
        return $this->render('../formatos/reporte_entrada_producto', [
            'model' => $model,
        ]);
    }
    /**
     * Finds the EntradaProductoTerminado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EntradaProductoTerminado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntradaProductoTerminado::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
     //EXCELES
    
    public function actionExcelconsultaEntrada($tableexcel) {                
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'PROVEEDOR')
                    ->setCellValue('C1', 'TIPO ORDEN')
                    ->setCellValue('D1', 'DOCUMENTO')
                    ->setCellValue('E1', 'FECHA ENTRADA')
                    ->setCellValue('F1', 'FECHA REGISTRO')
                    ->setCellValue('G1', 'CODIGO PRODUCTO')
                    ->setCellValue('H1', 'PRODUCTO')
                    ->setCellValue('I1', 'FECHA VCTO')
                    ->setCellValue('J1', 'ACT. PRECIO')
                    ->setCellValue('K1', 'CANT. ENTRADAS')
                    ->setCellValue('L1', 'VR. UNITARIO')
                    ->setCellValue('M1', 'SUBTOTAL')
                    ->setCellValue('N1', 'IVA')
                    ->setCellValue('O1', 'TOTAL')
                    ->setCellValue('P1', 'AUTORIZADO')
                    ->setCellValue('Q1', 'ENVIADO')
                    ->setCellValue('R1', 'USER NAME CREADOR')
                    ->setCellValue('S1', 'USER NAME EDITADO')
                    ->setCellValue('T1', 'OBSERVACION');
        $i = 2;
        
        foreach ($tableexcel as $val) {
            $detalle = EntradaProductoTerminadoDetalle::find()->where(['=','id_entrada', $val->id_entrada])->all();
            foreach ($detalle as $detalles){
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_entrada)
                        ->setCellValue('B' . $i, $val->proveedor->nombre_completo);
                        if($val->id_orden_compra == null){
                            $objPHPExcel->setActiveSheetIndex(0) 
                            ->setCellValue('C' . $i, 'NO FOUND');        
                        }else {
                             $objPHPExcel->setActiveSheetIndex(0) 
                             ->setCellValue('C' . $i, $val->ordenCompra->tipoOrden->descripcion_orden);  
                        }
                        $objPHPExcel->setActiveSheetIndex(0) 
                        ->setCellValue('D' . $i, $val->numero_soporte)
                        ->setCellValue('E' . $i, $val->fecha_proceso)
                        ->setCellValue('F' . $i, $val->fecha_registro)
                        ->setCellValue('G' . $i, $detalles->inventario->codigo_producto)
                        ->setCellValue('H' . $i, $detalles->inventario->nombre_producto)
                        ->setCellValue('I' . $i, $detalles->fecha_vencimiento)
                        ->setCellValue('J' . $i, $detalles->actualizarPrecio)
                        ->setCellValue('K' . $i, $detalles->cantidad)
                        ->setCellValue('L' . $i, $detalles->valor_unitario)
                        ->setCellValue('M' . $i, $detalles->subtotal)
                        ->setCellValue('N' . $i, $detalles->total_iva)
                        ->setCellValue('O' . $i, $detalles->total_entrada)
                        ->setCellValue('P' . $i, $val->autorizadoCompra)
                        ->setCellValue('Q' . $i, $val->enviarMateria)
                        ->setCellValue('R' . $i, $val->user_name_crear)
                        ->setCellValue('S' . $i, $val->user_name_edit)
                        ->setCellValue('T' . $i, $val->observacion);
                $i++;
            }    
        }

        $objPHPExcel->getActiveSheet()->setTitle('Entradas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Entrada_Producto_Terminado.xlsx"');
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
