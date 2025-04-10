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
//model

use app\models\EntregaDotacion;
use app\models\EntregaDotacionSearch;
use app\models\UsuarioDetalle;
use app\models\Empleados;

/**
 * EntregaDotacionController implements the CRUD actions for EntregaDotacion model.
 */
class EntregaDotacionController extends Controller
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
     * Lists all EntregaDotacion models.
     * @return mixed
     */
   public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',156])->all()){
                $form = new \app\models\FormConsultaDotacion();
                $empleado = null;
                $desde = null; $hasta = null;
                $tipo_dotacion = null; $numero = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $empleado = Html::encode($form->empleado);
                        $tipo_dotacion = Html::encode($form->tipo_dotacion);
                        $numero = Html::encode($form->numero);
                        $table = EntregaDotacion::find()
                                ->andFilterWhere(['=', 'id_empleado', $empleado])                                                                                              
                                ->andFilterWhere(['=', 'id_tipo_dotacion', $tipo_dotacion])
                                ->andFilterWhere(['=','numero_entrega', $numero])
                                ->andFilterWhere(['between','fecha_entrega', $desde, $hasta]);
                        $table = $table->orderBy('id_entrega DESC');
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
                                $check = isset($_REQUEST['id_entrega DESC']);
                                $this->actionExcelconsultaDotacion($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = EntregaDotacion::find()
                        ->orderBy('id_entrega DESC');
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
                if(isset($_POST['excel'])){
                    //$table = $table->all();
                    $this->actionExcelconsultaDotacion($tableexcel);
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
   
   //consulta de dotaciones
    public function actionSearch_dotaciones($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',157])->all()){
                $form = new \app\models\FormConsultaDotacion();
                $empleado = null;
                $desde = null; $hasta = null;
                $tipo_dotacion = null; $numero = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $empleado = Html::encode($form->empleado);
                        $tipo_dotacion = Html::encode($form->tipo_dotacion);
                        $numero = Html::encode($form->numero);
                        $table = EntregaDotacion::find()
                                ->andFilterWhere(['=', 'id_empleado', $empleado])                                                                                              
                                ->andFilterWhere(['=', 'id_tipo_dotacion', $tipo_dotacion])
                                ->andFilterWhere(['=','numero_entrega', $numero])
                                ->andFilterWhere(['between','fecha_entrega', $desde, $hasta])
                                ->andWhere(['=','cerrado', 1]);
                        $table = $table->orderBy('id_entrega DESC');
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
                                $check = isset($_REQUEST['id_entrega DESC']);
                                $this->actionExcelconsultaDotacion($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = EntregaDotacion::find()->Where(['=','cerrado', 1])
                        ->orderBy('id_entrega DESC');
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
                if(isset($_POST['excel'])){
                    //$table = $table->all();
                    $this->actionExcelconsultaDotacion($tableexcel);
                }
            }
            $to = $count->count();
            return $this->render('index_dotaciones', [
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
     * Displays a single EntregaDotacion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $model = EntregaDotacion::findOne($id);
        if($model->tipo_proceso == 0){
            
            if (Yii::$app->request->post()) {
                if(isset($_POST["actualizar_detalle_producto"])){
                    if(isset($_POST['detalle_dotacion'])){
                        $intIndice = 0;
                        foreach ($_POST["detalle_dotacion"] as $intCodigo){
                            $dato = \app\models\EntregaDotacionDetalles::findOne($intCodigo);
                            $dato->cantidad = $_POST["cantidad"][$intIndice];
                            $dato->talla = strtoupper($_POST["talla"][$intIndice]);
                            $dato->save();
                            $intIndice++;        
                        }
                        $this->ActualizaCantidades($id);
                        return $this->redirect(['view','id' => $id, 'token' => $token]);
                    }

                }
            }
            $totalSalidas = \app\models\EntregaDotacionDetalles::find()->where(['=','id_entrega', $id])->all();
            return $this->render('view', [
                'model' => $model,
                'token' => $token,
                 'totalSalidas' => $totalSalidas,
            ]);
        }else{
          
            $model = EntregaDotacion::findOne($id);
            if (Yii::$app->request->post()) {
                
                if(isset($_POST["devolucion_detalle_producto"])){
                    if(isset($_POST['detalle_devolucion'])){
                        $intIndice = 0;
                        foreach ($_POST["detalle_devolucion"] as $intCodigo){
                            $dato = \app\models\EntregaDotacionDetalles::findOne($intCodigo);
                            $dato->cantidad = $_POST["cantidad"][$intIndice];
                            $dato->talla = strtoupper($_POST["talla"][$intIndice]);
                            $dato->save();
                            $intIndice++;        
                        }
                        $this->ActualizaCantidades($id);
                        return $this->redirect(['view','id' => $id, 'token' => $token]);
                    }

                }
            }
            $totalSalidas = \app\models\EntregaDotacionDetalles::find()->where(['=','id_entrega', $id])->all();
            return $this->render('view', [
                'model' => $model,
                'token' => $token,
                'totalSalidas' => $totalSalidas,
            ]);
        }    
    }
    
    protected function ActualizaCantidades($id) {
        $model = EntregaDotacion::findOne($id);
        $detalle = \app\models\EntregaDotacionDetalles::find()->where(['=','id_entrega', $id])->all();
        $total = 0;
        foreach ($detalle as $valores) {
            $total += $valores->cantidad;
        }
        $model->cantidad = $total;
        $model->save();
    }

    /**
     * Creates a new EntregaDotacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($token)
    {
        $model = new EntregaDotacion();
      
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name = Yii::$app->user->identity->username;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id_entrega,'token' => $token]);
        }

        return $this->render('create', [
            'model' => $model,
            'token' => $token,
           
        ]);
    }

    /**
     * Updates an existing EntregaDotacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    //DESCARGAR SALIDA
    
    public function actionDescargar($id, $token) {
        $model = EntregaDotacion::findOne($id);
        $buscar = EntregaDotacion::find()->Where(['=','id_empleado', $model->id_empleado])->andWhere(['=','tipo_proceso', 0])
                                            ->andWhere(['=','devuelto', 0])->one();   
        if($buscar){
             $totalSalidas = \app\models\EntregaDotacionDetalles::find()->where(['=','id_entrega', $buscar->id_entrega])->all();
       
            foreach ($totalSalidas as $val) {
                $detalle = \app\models\EntregaDotacionDetalles::find()->where(['=','id_entrega', $id])->andWhere(['=','id_inventario', $val->id_inventario])->one();
                if(!$detalle){
                    $table = new \app\models\EntregaDotacionDetalles();
                    $table->id_entrega = $id;
                    $table->id_inventario = $val->id_inventario;
                    $table->cantidad = $val->cantidad;
                    $table->talla = $val->talla;
                    $table->save();
                }    
            }
             return $this->redirect(['view',  'id' => $id, 'token' => $token]);
        }else{
             Yii::$app->getSession()->setFlash('info','No existe dotaciones para devolver por parte del empleado. '); 
             return $this->redirect(['view',  'id' => $id, 'token' => $token]);
        }    
        
    }
    /**
     * Deletes an existing EntregaDotacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     //eliminar detalle de las fases
     public function actionEliminar_linea_devolucion($id, $detalle, $token)
    {                                
        $table = \app\models\EntregaDotacionDetalles::findOne($detalle);
        $table->delete();
        $this->ActualizaCantidades($id);
        return $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
    
     //eliminar detalle de la devolucion
     public function actionEliminar_linea_entrega($id, $detalle, $token)
    {                                
        $table = \app\models\EntregaDotacionDetalles::findOne($detalle);
        $table->delete();
        $this->ActualizaCantidades($id);
        return $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
    
    //buscar producto del modulo de inventario
    public function actionBuscar_producto_inventario($id, $token) {
        $operacion = \app\models\InventarioProductos::find()->where(['=','venta_publico', 1])
                                                            ->andWhere(['=','activar_producto_venta', 0])
                                                            ->andWhere(['>','stock_unidades', 0])->orderBy('nombre_producto DESC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                    $operacion = \app\models\InventarioProductos::find()
                            ->where(['like','nombre_producto',$q])
                            ->orwhere(['=','codigo_producto',$q])
                            ->andWhere(['=','venta_publico', 1])
                            ->andWhere(['=','activar_producto_venta', 0])
                            ->andWhere(['>','stock_unidades', 0]);
                    $operacion = $operacion->orderBy('nombre_producto DESC');                    
                    $count = clone $operacion;
                    $to = $count->count();
                    $pages = new Pagination([
                        'pageSize' => 10,
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
            $table = \app\models\InventarioProductos::find()->where(['=','venta_publico', 1])
                                                            ->andWhere(['=','activar_producto_venta', 0])
                                                            ->andWhere(['>','stock_unidades', 0])
                                                            ->orderBy('nombre_producto DESC');
            $tableexcel = $table->all();
            $count = clone $table;
            $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
            ]);
             $operacion = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        //PROCESO DE GUARDAR
         if (isset($_POST["guardarproducto"])) {
            if(isset($_POST["nuevo_producto"])){
                $intIndice = 0;
                foreach ($_POST["nuevo_producto"] as $intCodigo) {
                    $registro = \app\models\EntregaDotacionDetalles::find()->where(['=','id_inventario', $intCodigo])->andWhere(['=','id_entrega', $id])->one();
                    if(!$registro){
                        $table = new \app\models\EntregaDotacionDetalles();
                        $table->id_entrega = $id;
                        $table->id_inventario = $intCodigo;
                        $table->save(false);
                    }    
                }
                return $this->redirect(['view','id' => $id, 'token' => $token]);
            }
        }
        return $this->render('importar_producto_inventario', [
            'operacion' => $operacion,            
            'pagination' => $pages,
            'id' => $id,
            'form' => $form,
            'token' => $token,
        ]);
    }

    
    //PROCESO QUE AUTORIZA Y DESAUTORIZA
    public function actionAutorizado($id, $token) {
        $model = EntregaDotacion::findOne($id);
        $linea = \app\models\EntregaDotacionDetalles::find()->where(['=','id_entrega', $id])->all();
        if(count($linea) > 0){
            if($model->autorizado == 0){
               $model->autorizado = 1;
               $model->save();
            }else{
                $model->autorizado = 0;
                $model->save();
            }
             return $this->redirect(["entrega-dotacion/view", 'id' => $id, 'token' => $token]);
        }else{
            Yii::$app->getSession()->setFlash('warning', 'No hay detalle de la entrega de dotacion. Revisa la informacion.'); 
            return $this->redirect(["entrega-dotacion/view", 'id' => $id, 'token' => $token]);
        }
    }
    
    //PERMITE GENERAR EL CONSECUTIVO
    public function actionGenerar_entrega($id, $token) {
         $model = EntregaDotacion::findOne($id);
          //genera consecutivo
         $codigo = \app\models\Consecutivos::findOne(30);
         $model->numero_entrega = $codigo->numero_inicial + 1;
         $model->cerrado = 1;
         if($model->tipo_proceso == 1){
            $buscar = EntregaDotacion::find()->Where(['=','id_empleado', $model->id_empleado])->andWhere(['=','tipo_proceso', 0])
                                            ->andWhere(['=','devuelto', 0])->one(); 
            if($buscar){
                $buscar->devuelto = 1;
                $buscar->save();
            }
            $model->devuelto = 1;
            $model->save();
              
         }else{
             $model->save();
         }
         
         //actualiza consecutivo
         $codigo->numero_inicial = $model->numero_entrega;
         $codigo->save();
         return $this->redirect(["entrega-dotacion/view", 'id' => $id, 'token' => $token]); 
    }
    
    //descargar inventarios
    public function actionDescargar_inventarios($id, $token) {
        $model = EntregaDotacion::findOne($id);
        $detalle = \app\models\EntregaDotacionDetalles::find()->where(['=','id_entrega', $id])->all();
        $con = 0;
        foreach ($detalle as $detalles) {
            $inventario = \app\models\InventarioProductos::findOne($detalles->id_inventario);
            if($inventario){
                if($inventario->aplica_inventario == 0){
                    $con += 1;
                    $inventario->stock_unidades -= $detalles->cantidad;
                    $inventario->save();
                }    
            }
        }
        $model->descargar_inventario = 1;
        $model->save();
        Yii::$app->getSession()->setFlash('success', 'Se envio al modulo de inventario (' . $con. ') registros que contienen la Orden de entrega No (' .$model->numero_entrega . ').'); 
        return $this->redirect(["entrega-dotacion/view", 'id' => $id, 'token' => $token]); 
    }
    
      public function actionImprimir_formato($id) {
        $model = EntregaDotacion::findOne($id);
        return $this->render('../formatos/reporte_entrega_dotacion', [
            'model' => $model,
        ]);
    }
    
    /**
     * Finds the EntregaDotacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EntregaDotacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntregaDotacion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
     public function actionExcelconsultaDotacion($tableexcel) {                
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
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'TIPO DOTACION')
                    ->setCellValue('E1', 'TIPO PROCESO')
                    ->setCellValue('F1', 'FECHA ENTREGA')
                    ->setCellValue('G1', 'FECHA HORA PROCESO')
                    ->setCellValue('H1', 'CANTIDAD')
                    ->setCellValue('I1', 'NUMERO ENTREGA')
                    ->setCellValue('J1', 'USER NAME')
                    ->setCellValue('K1', 'DES. DE INVENTARIO');
            $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_entrega)
                    ->setCellValue('B' . $i, $val->empleado->nit_cedula)
                    ->setCellValue('C' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('D' . $i, $val->tipoDotacion->descripcion)
                    ->setCellValue('E' . $i, $val->tipoProceso)
                    ->setCellValue('F' . $i, $val->fecha_entrega)
                    ->setCellValue('G' . $i, $val->fecha_hora_registro)
                    ->setCellValue('H' . $i, $val->cantidad)
                    ->setCellValue('I' . $i, $val->numero_entrega)
                    ->setCellValue('J' . $i, $val->user_name)
                    ->setCellValue('K' . $i, $val->descargoInventario);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Entrega_devolucion_dotacion.xlsx"');
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
