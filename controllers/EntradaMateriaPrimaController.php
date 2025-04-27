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
use app\models\EntradaMateriaPrima;
use app\models\EntradaMateriaPrimaSearch;
use app\models\EntradaMateriaPrimaDetalle;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaEntradaMateria;
use app\models\OrdenCompraDetalle;
use app\models\OrdenCompra;
use app\models\MateriaPrimas;


/**
 * EntradaMateriaPrimaController implements the CRUD actions for EntradaMateriaPrima model.
 */
class EntradaMateriaPrimaController extends Controller
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
     * Lists all EntradaMateriaPrima models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',12])->all()){
                
                $form = new FiltroBusquedaEntradaMateria();
                $id_entrada= null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $proveedor = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $id_entrada = Html::encode($form->id_entrada);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $proveedor = Html::encode($form->proveedor);
                        $table = EntradaMateriaPrima::find()
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_orden_compra', $id_entrada])
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
                    $table = EntradaMateriaPrima::find()
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
    
  // PROCESO DE CONSULTA DE ENTRADAS DE MATERIAS PRIMAS
     public function actionSearch_consulta_entradas($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',22])->all()){
                
                $form = new FiltroBusquedaEntradaMateria();
                $id_entrada= null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $proveedor = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $id_entrada = Html::encode($form->id_entrada);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $proveedor = Html::encode($form->proveedor);
                        $table = EntradaMateriaPrima::find()
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_orden_compra', $id_entrada])
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
                    $table = EntradaMateriaPrima::find()
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
                return $this->render('search_consulta_entradas', [
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
     * Displays a single EntradaMateriaPrima model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token, $sw = 0)
    {
        $detalle_entrada = \app\models\EntradaMateriaPrimaDetalle::find()->where(['=','id_entrada', $id])->orderBy('id_detalle DESC')->all();
        if (count($detalle_entrada) > 0){
            $sw = 1;
        }else{
            $sw = 0;
        }
        $materiaprima = \app\models\MateriaPrimas::find()->orderBy('materia_prima ASC')->all();
        if(isset($_POST["actualizarlineas"])){
            if(isset($_POST["detalle_entrada"])){
                $intIndice = 0;
                $auxiliar = 0;
                $iva = 0 ;
                foreach ($_POST["detalle_entrada"] as $intCodigo):
                    $table = EntradaMateriaPrimaDetalle::findOne($intCodigo);
                    $table->id_materia_prima = $_POST["id_materia_prima"]["$intIndice"];
                    $table->cantidad = $_POST["cantidad"]["$intIndice"];
                    $table->actualizar_precio = $_POST["actualizar_precio"]["$intIndice"];
                    $table->porcentaje_iva = $_POST["porcentaje_iva"]["$intIndice"];
                    $table->fecha_vencimiento = $_POST["fecha_vcto"]["$intIndice"];
                    $table->valor_unitario = $_POST["valor_unitario"]["$intIndice"];
                    $table->numero_lote = strtoupper($_POST["numero_lote"]["$intIndice"]);
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
                return $this->redirect(['view','id' =>$id, 'token' => $token, 'sw' => $sw]);
            }
            
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token'=> $token,
            'sw' => $sw,
            'detalle_entrada' => $detalle_entrada,
            'materiaprima' => ArrayHelper::map($materiaprima, "id_materia_prima", "materiasPrimas"),
        ]);
    }
    //proceso que suma los totales
    protected function ActualizarLineas($id) {
        $entrada = EntradaMateriaPrima::findOne($id);
        $detalle = EntradaMateriaPrimaDetalle::find()->where(['=','id_entrada', $id])->all();
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

    /**
     * Creates a new EntradaMateriaPrima model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EntradaMateriaPrima();
        $ordenes = \app\models\OrdenCompra::find()->orderBy('id_orden_compra desc')->all(); 
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $token = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name_crear= Yii::$app->user->identity->username;
            $model->update();
            return $this->redirect(['view', 'id' => $model->id_entrada, 'token'=> $token]);
        }
        $model->fecha_proceso = date('Y-m-d');
        return $this->render('create', [
            'model' => $model,
             'ordenes' => ArrayHelper::map($ordenes, "id_orden_compra", "descripcion"),
        ]);
    }
    
    //PROCESO DE ORDEN DE COMPRAS
    
     public function actionOrdencompra($id){
        $rows = \app\models\OrdenCompra::find()->where(['=','id_proveedor', $id])
                                               ->andWhere(['=','importado', 0])
                                               ->andWhere(['=','auditada', 1])->orderBy('descripcion desc')->all();

        echo "<option value='' required>Seleccione una orden...</option>";
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id_orden_compra' required>Tipo orden: $row->descripcion  Nro orden: $row->id_orden_compra</option>";
            }
        }
    }
    /**
     * Updates an existing EntradaMateriaPrima model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $ordenes = \app\models\OrdenCompra::find()->orderBy('id_orden_compra desc')->all(); 
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $model->user_name_edit= Yii::$app->user->identity->username;
            $model->update();
            return $this->redirect(['index']);
        }
         if (Yii::$app->request->get("id")) {
            $table = EntradaMateriaPrima::findOne($id);
            $orden_compra = \app\models\OrdenCompra::find()->where(['=','id_proveedor', $table->id_proveedor])
                                               ->andWhere(['=','importado', 0])
                                               ->andWhere(['=','auditada', 1])->orderBy('descripcion desc')->all();
            $orden_compra = ArrayHelper::map($orden_compra, "id_orden_compra", "OrdenCompraCompleto");
            $model->id_proveedor = $table->id_proveedor;
            $model->id_orden_compra = $table->id_orden_compra;
            $model->fecha_proceso = $table->fecha_proceso;
            $model->numero_soporte = $table->numero_soporte;
            $model->observacion = $table->observacion;
         }

        return $this->render('update', [
            'model' => $model,
            'orden_compra' => $orden_compra,
            
            
        ]);
    }
    //NUEVA LINEA
    public function actionNuevalinea($id, $token) {
        $table = new EntradaMateriaPrimaDetalle();
        $table->id_entrada = $id;
        $table->fecha_vencimiento = date('Y-m-d');
        $table->insert();
        return $this->redirect(['view', 'id' => $id, 'token' => $token]);
      }
   
    //ACTUALIZAR LINEA
    public function actionImportardetallecompra($sw = 0, $id, $id_orden, $token, $proveedor)
    {                                
        $orden_compra = OrdenCompra::find()->where(['=','id_proveedor' , $proveedor])->andWhere(['=','id_orden_compra', $id_orden])
                                                                                     ->andWhere(['=','auditada', 1])->andWhere(['=','importado', 0])->one();
        if($orden_compra){
            $detalle_compra = OrdenCompraDetalle::find()->where(['=','id_orden_compra', $orden_compra->id_orden_compra])->all();
            foreach ( $detalle_compra as $detalle_compras):
                    $materiaPrima = MateriaPrimas::find()->where(['=','codigo_materia_prima', $detalle_compras->items->codigo])->one();
                    if($materiaPrima){
                        $table = new EntradaMateriaPrimaDetalle();
                        $table->id_entrada = $id;
                       $table->id_materia_prima = $materiaPrima->id_materia_prima;
                        $table->fecha_vencimiento = date('Y-m-d');
                        $table->porcentaje_iva = $detalle_compras->porcentaje;
                        $table->cantidad = $detalle_compras->cantidad;
                        $table->valor_unitario = $detalle_compras->valor;
                        $table->save(); 
                    }else{
                       Yii::$app->getSession()->setFlash('warning', 'El CODIGO No ' .$detalle_compras->items->codigo. ' que se quiere enviar al inventario No esta codificado en MATERIAS PRIMAS.'); 
                    }
                    
            endforeach;
            $sw = 1;
            $this->redirect(["view",'id' => $id, 'token' => $token, 'sw' => $sw,]);  
        }else{
             Yii::$app->getSession()->setFlash('warning', 'El proveedor NO tiene ORDENES DE COMPRAS programadas para entregar y/o la orden de compra no ha llegado o NO se AUDITADO.');
             $this->redirect(["view",'id' => $id, 'token' => $token, 'sw' => $sw,]); 
        }    
            
    } 
      
    //ELIMINAR DETALLES  
    public function actionEliminar($id,$detalle, $token)
    {                                
        $detalle = EntradaMateriaPrimaDetalle::findOne($detalle);
        $detalle->delete();
        $this->ActualizarLineas($id);
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    } 

     public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        $detalle = EntradaMateriaPrimaDetalle::find()->where(['=','id_entrada', $id])->all();
        $sw = 0;
        foreach ($detalle as $val){
            if($val->id_materia_prima == ''){
                $sw = 1;
                Yii::$app->getSession()->setFlash('warning', 'Debe de seleccionar el codigo de la materia prima para asociarlo a la entrada y luego presiona el boton actualizar.');
                return $this->redirect(["view",'id' => $id, 'token' => $token]); 
            }
        }
        if($sw == 0){
            if ($model->autorizado == 0) {                        
                    $model->autorizado = 1;            
                   $model->update();
                   $this->redirect(["entrada-materia-prima/view", 'id' => $id, 'token' =>$token]);  

            } else{
                    $model->autorizado = 0;
                    $model->update();
                    $this->redirect(["entrada-materia-prima/view", 'id' => $id, 'token' =>$token]);  
            } 
        }    
    }
    
    public function actionEnviarmateriales($id, $token , $id_compra) {
        $model = $this->findModel($id);
        $orden = OrdenCompra::find()->where(['=','id_orden_compra', $id_compra])->one();
        $detalle = EntradaMateriaPrimaDetalle::find()->where(['=','id_entrada', $id])->all(); // carga el detalle
        
        foreach ($detalle as $detalles):
            $materia = MateriaPrimas::find()->where(['=','id_materia_prima', $detalles->id_materia_prima])->one();
            if($materia){
                $codigo = $materia->id_materia_prima;
                $materia->fecha_vencimiento = $detalles->fecha_vencimiento;
                if($detalles->actualizar_precio == 1){
                   $materia->valor_unidad = $detalles->valor_unitario;
                   $materia->total_cantidad += $detalles->cantidad; 
                   $materia->stock += $detalles->cantidad;
                   if($materia->convertir_gramos == 1){
                       $materia->stock_gramos = round($materia->stock * 1000);
                   }
                } else {
                   $materia->total_cantidad += $detalles->cantidad;   
                   $materia->stock += $detalles->cantidad;
                   if($materia->convertir_gramos == 1){
                       $materia->stock_gramos = round($materia->stock * 1000);
                   }
                } 
                  $materia->save(false);
                  $codigo = $detalles->id_materia_prima;
                  $this->ActualizarCostoMateriaPrima($codigo);
            }
        endforeach;
        $model->enviar_materia_prima = 1;
        $model->save();
        $orden->importado = 1;
        $orden->save();
        $this->redirect(["entrada-materia-prima/view", 'id' => $id, 'token' =>$token]);
    }
    //proceso para multiplicar inventario
    protected function ActualizarCostoMateriaPrima($codigo) {
        $iva = 0; $subtotal = 0; $cant = 0;
        $materia = MateriaPrimas::find()->where(['=','id_materia_prima', $codigo])->one();
        $iva = round((($materia->total_cantidad * $materia->stock)* $materia->porcentaje_iva)/100);
        $subtotal = round($materia->stock * $materia->valor_unidad);
        if($materia->convertir_gramos == 1){
            $cant = $materia->stock * 1000;
            $materia->stock_gramos = $cant;
        }
        $materia->valor_iva = $iva;
        $materia->subtotal = $subtotal;
        $materia->total_materia_prima = $subtotal + $iva;
        $materia->save(false);
    }
    /**
     * Finds the EntradaMateriaPrima model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EntradaMateriaPrima the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntradaMateriaPrima::findOne($id)) !== null) {
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
                    ->setCellValue('D1', 'SOPORTE')
                    ->setCellValue('E1', 'FECHA ENTRADA')
                    ->setCellValue('F1', 'FECHA REGISTRO')
                    ->setCellValue('G1', 'USER NAME CREADOR')
                    ->setCellValue('H1', 'USER NAME EDITADO')
                    ->setCellValue('I1', 'AUTORIZADO')
                    ->setCellValue('J1', 'ENVIADO')
                    ->setCellValue('K1', 'SUBTOTAL')
                    ->setCellValue('L1', 'IVA')
                    ->setCellValue('M1', 'TOTAL')
                    ->setCellValue('N1', 'MATERIA PRIMA')
                    ->setCellValue('O1', 'FECHA VCTO')
                    ->setCellValue('P1', 'CANTIDAD')
                    ->setCellValue('Q1', 'VR. UNITARIO')
                    ->setCellValue('R1', 'SUBTOTAL')
                    ->setCellValue('S1', 'IVA')
                    ->setCellValue('T1', 'TOTAL LINEA')
                    ;
        $i = 2;
        
        foreach ($tableexcel as $val) {
            $detalles = EntradaMateriaPrimaDetalle::find()->where(['=','id_entrada', $val->id_entrada])->all();
            foreach ($detalles as $detalle){                     
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_entrada)
                        ->setCellValue('B' . $i, $val->proveedor->nombre_completo)
                        ->setCellValue('C' . $i, $val->ordenCompra->tipoOrden->descripcion_orden)
                        ->setCellValue('D' . $i, $val->numero_soporte)
                        ->setCellValue('E' . $i, $val->fecha_proceso)
                        ->setCellValue('F' . $i, $val->fecha_registro)
                        ->setCellValue('G' . $i, $val->user_name_crear)
                        ->setCellValue('H' . $i, $val->user_name_edit)
                        ->setCellValue('I' . $i, $val->autorizadoCompra)
                        ->setCellValue('J' . $i, $val->enviarMateria)
                        ->setCellValue('K' . $i, $val->subtotal)
                        ->setCellValue('L' . $i, $val->impuesto)
                        ->setCellValue('M' . $i, $val->total_salida)
                        ->setCellValue('N' . $i, $detalle->materiaPrima->materia_prima)
                        ->setCellValue('O' . $i, $detalle->fecha_vencimiento)
                        ->setCellValue('P' . $i, $detalle->cantidad)
                        ->setCellValue('Q' . $i, $detalle->valor_unitario)
                        ->setCellValue('R' . $i, $detalle->subtotal)
                        ->setCellValue('S' . $i, $detalle->total_iva)
                        ->setCellValue('T' . $i, $detalle->total_entrada)
                        ;
                $i++;
            }
            $i = $i;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Entradas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Entrada_Materias.xlsx"');
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
