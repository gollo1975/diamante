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
use app\models\OrdenCompra;
use app\models\OrdenCompraDetalle;
use app\models\UsuarioDetalle;
use app\models\Items;
use app\models\FiltroBusquedaOrdenCompra;
use app\models\FormModeloBuscar;
/**
 * OrdenCompraController implements the CRUD actions for OrdenCompra model.
 */
class OrdenCompraController extends Controller
{
    

    /**
     * Lists all OrdenCompra models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',11])->all()){
                $form = new FiltroBusquedaOrdenCompra();
                $numero = null;
                $solicitud = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $proveedor = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $solicitud = Html::encode($form->solicitud);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $proveedor = Html::encode($form->proveedor);
                        $table = OrdenCompra::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero])
                                    ->andFilterWhere(['>=', 'fecha_creacion', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_creacion', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_tipo_orden', $solicitud])
                                    ->andFilterWhere(['=', 'id_proveedor', $proveedor]);
                        $table = $table->orderBy('id_orden_compra DESC');
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
                            $check = isset($_REQUEST['id_orden_compra  DESC']);
                            $this->actionExcelConsultaOrdenCompra($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = OrdenCompra::find()
                            ->orderBy('id_orden_compra DESC');
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
                        $this->actionExcelConsultaOrdenCompra($tableexcel);
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
    
    //proceso de auditoria
    public function actionIndex_auditar_compras($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',84])->all()){
                $form = new FiltroBusquedaOrdenCompra();
                $numero = null;
                $solicitud = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $proveedor = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $solicitud = Html::encode($form->solicitud);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $proveedor = Html::encode($form->proveedor);
                        $table = OrdenCompra::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero])
                                    ->andFilterWhere(['>=', 'fecha_creacion', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_creacion', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_tipo_orden', $solicitud])
                                    ->andFilterWhere(['=', 'id_proveedor', $proveedor])
                                    ->andWhere(['=', 'auditada', 0]) ;
                        $table = $table->orderBy('id_orden_compra DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 10,
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
                    $table = OrdenCompra::find()->Where(['=', 'auditada', 0])
                            ->orderBy('id_orden_compra DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }
                $to = $count->count();
                return $this->render('index_auditoria', [
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
    //PROCESO DE CONSULTA DE ORDENES
    
      public function actionSearch_consulta_orden_compra($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',85])->all()){
                $form = new FiltroBusquedaOrdenCompra();
                $numero = null;
                $solicitud = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $proveedor = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $solicitud = Html::encode($form->solicitud);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $proveedor = Html::encode($form->proveedor);
                        $table = OrdenCompra::find()
                                    ->andFilterWhere(['=', 'numero_orden', $numero])
                                    ->andFilterWhere(['>=', 'fecha_creacion', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_creacion', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_tipo_orden', $solicitud])
                                    ->andFilterWhere(['=', 'id_proveedor', $proveedor])
                                    ->andWhere(['>','numero_orden', 0]);
                        $table = $table->orderBy('id_orden_compra DESC');
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
                            $check = isset($_REQUEST['id_orden_compra  DESC']);
                            $this->actionExcelConsultaOrdenCompra($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = OrdenCompra::find()
                            ->where(['>','numero_orden', 0])
                            ->orderBy('id_orden_compra DESC');
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
                        $this->actionExcelConsultaOrdenCompra($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_consulta_orden_compra', [
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
     * Displays a single OrdenCompra model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle_compras = OrdenCompraDetalle::find()->where(['=','id_orden_compra', $id])->orderBy('id_detalle DESC')->all();
        if(isset($_POST["actualizaregistro"])){
            if(isset($_POST["detalle_compra"])){
                $intIndice = 0;
                $auxiliar = 0;
                $iva = 0 ;
                foreach ($_POST["detalle_compra"] as $intCodigo):
                    $table = OrdenCompraDetalle::findOne($intCodigo);
                    $table->cantidad = $_POST["cantidad"]["$intIndice"];
                    $table->valor = $_POST["valor"]["$intIndice"];
                    $auxiliar =  $table->cantidad * $table->valor;
                    $table->subtotal = $auxiliar;
                    $iva = round(($auxiliar * $table->porcentaje)/100);
                    $table->valor_iva = $iva;
                    $table->total_orden = $iva + $auxiliar;
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

    /**
     * Creates a new OrdenCompra model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_solicitud)
    {
        $model = new OrdenCompra();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $token = 0;
        $solicitud = \app\models\SolicitudCompra::findOne($id_solicitud);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $model->user_name = Yii::$app->user->identity->username;
            $orden = \app\models\TipoOrdenCompra::findOne($model->id_tipo_orden);
            $model->descripcion = $orden->descripcion_orden;
            $model->abreviatura = $orden->abreviatura;
            $model->numero_solicitud = $solicitud->numero_solicitud;
            $model->id_solicitud_compra = $id_solicitud;
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id_orden_compra, 'token' => $token]);
        }
        $model->numero_solicitud = $solicitud->numero_solicitud;
        $model->fecha_creacion = date('Y-m-d');
        return $this->render('create', [
            
            'model' => $model,
            'token' => $token,
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * Updates an existing OrdenCompra model.
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
            $orden = \app\models\TipoOrdenCompra::findOne($model->id_tipo_orden);
            $model->descripcion = $orden->descripcion_orden;
            $model->abreviatura = $orden->abreviatura;
            $model->update();
            return $this->redirect(['view','id' => $id, 'token' => 0]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
// proceso de importar
    public function actionImportarsolicitud($id, $token, $id_solicitud)
    
    {
        if($registro = \app\models\SolicitudCompra::find()->where(['=','id_solicitud', $id_solicitud])->andWhere(['=','importado', 0])->one()){
            $solicitud = \app\models\SolicitudCompra::find()->where(['=','importado', 0])->andWhere(['=','id_solicitud', $id_solicitud])->orderBy('id_solicitud ASC')->all();
            $form = new FormModeloBuscar();
            $q = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    $q = Html::encode($form->q);                                
                        $solicitud = \app\models\SolicitudCompra::find()
                                ->where(['=','id_solicitud', $q])
                                ->andwhere(['=','importado', 0]);
                        $solicitud = $solicitud->orderBy('id_solicitud_compra ASC');                    
                        $count = clone $solicitud;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 10,
                            'totalCount' => $count->count()
                        ]);
                        $operacion = $solicitud
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();         
                } else {
                    $form->getErrors();
                }                    
            }else{
                $table = \app\models\SolicitudCompra::find()->where(['=','importado', 0])->andWhere(['=','id_solicitud', $id_solicitud])->orderBy('id_solicitud ASC');
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
             if (isset($_POST["enviarsolicitudcompras"])) {
                if(isset($_POST["solicitud_compras"])){
                    $intIndice = 0;
                    foreach ($_POST["solicitud_compras"] as $intCodigo) {
                        $listado = \app\models\SolicitudCompraDetalles::find()->where(['=','id_solicitud_compra', $intCodigo])->all();
                        foreach ($listado as $listados){
                            $table = new OrdenCompraDetalle();
                            $table->id_items = $listados->id_items;
                            $table->id_orden_compra = $id;
                            $table->id_solicitud_compra = $intCodigo;
                            $table->porcentaje = $listados->porcentaje_iva;
                            $table->cantidad = $listados->cantidad;
                            $table->valor = $listados->valor;
                            $table->valor_iva = $listados->valor_iva;
                            $table->subtotal = $listados->subtotal;
                            $table->total_orden = $listados->total_solicitud;        
                            $table->save(false);
                        }
                    }
                    $this->ActualizarLineas($id);
                    return $this->redirect(['view','id' => $id, 'token' => $token]);
                }
            }
            return $this->render('importar_solicitud_compras', [
                'operacion' => $operacion,            
                'pagination' => $pages,
                'id' => $id,
                'form' => $form,
                'token' => $token,
                'id_solicitud' => $id_solicitud,

            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', 'El tipo de orden que selecciono NO tiene solicitudes de compras programadas.  ');
            return $this->redirect(["orden-compra/view", 'id' => $id, 'token' => $token]);
        }   
    }
    //subproceso que actualiza
     protected function ActualizarLineas($id){
        $solicitud = OrdenCompra::findOne($id);
        $detalle = OrdenCompraDetalle::find()->where(['=','id_orden_compra', $id])->all();
        $subtotal = 0;
        $impuesto = 0;
        $total = 0;
        foreach ($detalle as $detalles):
              $subtotal += $detalles->subtotal;
              $impuesto += $detalles->valor_iva;
              $total += $detalles->total_orden;
        endforeach;
        $solicitud->subtotal = $subtotal;
        $solicitud->impuesto = $impuesto;
        $solicitud->total_orden = $total;
        $solicitud->save(false);
    }
    
     public function actionEliminar($id,$detalle, $token)
    {                                
        $detalle = OrdenCompraDetalle::findOne($detalle);
        $detalle->delete();
        $this->ActualizarLineas($id);
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
    
     public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        if ($model->autorizado == 0) {                        
                $model->autorizado = 1;            
               $model->update();
               $this->redirect(["orden-compra/view", 'id' => $id, 'token' =>$token]);  

        } else{
                $model->autorizado = 0;
                $model->update();
                $this->redirect(["orden-compra/view", 'id' => $id, 'token' =>$token]);  
        }    
    }
    
    public function actionCerrarsolicitud($id, $token) {
        //proceso de generar consecutivo
        $lista = \app\models\Consecutivos::findOne(2);
        $solicitud = OrdenCompra::findOne($id);
        $detalle_compra = OrdenCompraDetalle::find()->where(['=','id_orden_compra', $solicitud->id_orden_compra])->one();
        $orden_solicitud = \app\models\SolicitudCompra::findOne($detalle_compra->id_solicitud_compra);
        $solicitud->numero_orden = $lista->numero_inicial + 1;
        $solicitud->save(false);
        $lista->numero_inicial = $solicitud->numero_orden;
        $lista->save(false);
        $orden_solicitud->importado = 1;
        $orden_solicitud->save(false);
        $this->redirect(["orden-compra/view", 'id' => $id, 'token' =>$token]);  
    }
    /**
     * Finds the OrdenCompra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrdenCompra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    //IMPRESIONES
    public function actionImprimirordencompra($id) {
        $model = OrdenCompra::findOne($id);
        return $this->render('../formatos/reporteordencompra', [
            'model' => $model,
        ]);
    }
    
    //PROCESO DE AUDITORIA DE ORDEN DE COMPRA
    
    public function actionProceso_auditoria($id, $token =0) {
        $model = OrdenCompra::findOne($id);
        $table = new \app\models\AuditoriaCompras();
        $table->id_orden_compra = $model->id_orden_compra;
        $table->id_tipo_orden = $model->id_tipo_orden;
        $table->id_proveedor = $model->id_proveedor;
        $table->fecha_proceso_compra = $model->fecha_proceso;
        $table->fecha_auditoria = date('Y-m-d');
        $table->numero_orden = $model->numero_orden;
        $table->user_name = Yii::$app->user->identity->username;
        $table->save(false);
        //PROCESO DE INSERCION AL DETALLE
        $numero = \app\models\AuditoriaCompras::find()->orderBy('id_auditoria DESC')->limit (1)->one();
        $modelo = OrdenCompraDetalle::find()->where(['=','id_orden_compra', $id])->all();
        foreach ($modelo as $detalle):
            $registro = new \app\models\AuditoriaCompraDetalles();
            $registro->id_items = $detalle->id_items;
            $items = Items::findOne($detalle->id_items);
            $registro->nombre_producto = $items->descripcion;
            $registro->cantidad = $detalle->cantidad;
            $registro->valor_unitario = $detalle->valor;
            $id = $numero->id_auditoria;
            $registro->id_auditoria = $numero->id_auditoria;
            $registro->save(false);
        endforeach;
         return $this->redirect(["auditoria-compras/view", 'id' => $id,'token' => $token]);
    }
    protected function findModel($id)
    {
        if (($model = OrdenCompra::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //exceles
    
    public function actionExcelconsultaOrdenCompra($tableexcel) {                
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
                    ->setCellValue('B1', 'TIPO ORDEN')
                    ->setCellValue('C1', 'PROVEEDOR')
                    ->setCellValue('D1', 'SOPORTE')
                    ->setCellValue('E1', 'FECHA CREACION')
                    ->setCellValue('F1', 'FECHA REGISTRO')
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
                    ->setCellValue('A' . $i, $val->id_orden_compra)
                    ->setCellValue('B' . $i, $val->tipoOrden->descripcion_orden)
                    ->setCellValue('C' . $i, $val->proveedor->nombre_completo)
                    ->setCellValue('D' . $i, $val->numero_solicitud)
                    ->setCellValue('E' . $i, $val->fecha_creacion)
                    ->setCellValue('F' . $i, $val->fecha_proceso)
                    ->setCellValue('G' . $i, $val->user_name)
                    ->setCellValue('H' . $i, $val->numero_orden)
                    ->setCellValue('I' . $i, $val->autorizadoCompra)
                    ->setCellValue('J' . $i, $val->subtotal)
                    ->setCellValue('K' . $i, $val->impuesto)
                    ->setCellValue('L' . $i, $val->total_orden)
                    ->setCellValue('M' . $i, $val->observacion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Orden_Compra.xlsx"');
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
