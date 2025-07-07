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
use app\models\EntregaMateriales;
use app\models\UsuarioDetalle;
use app\models\EntregaMaterialesDetalle;
use app\models\SolicitudMateriales;



/**
 * EntregaMaterialesController implements the CRUD actions for EntregaMateriales model.
 */
class EntregaMaterialesController extends Controller
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
     * Lists all EntregaMateriales models.
     * @return mixed
     */
     public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',99])->all()){
                $form = new \app\models\FiltroBusquedaSolicitudMateriales();
                $numero_solicitud = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $numero_entrega = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_solicitud = Html::encode($form->numero_solicitud);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $numero_entrega = Html::encode($form->numero_entrega);
                        $table = EntregaMateriales::find()
                                    ->andFilterWhere(['=', 'numero_entrega', $numero_entrega])
                                    ->andFilterWhere(['between', 'fecha_despacho', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'codigo', $numero_solicitud]);
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
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_entrega  DESC']);
                            $this->actionExcelConsultaEntrega($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = EntregaMateriales::find()
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
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaEntrega($tableexcel);
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
     * Displays a single EntregaMateriales model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id,$token)
    {
        $detalle_solicitud = EntregaMaterialesDetalle::find()->where(['=','id_entrega', $id])->all();
        $validar_inventario = EntregaMaterialesDetalle::find()->where(['=','id_entrega', $id])->andWhere(['=','validar_linea_materia_prima', 0])->all();
        if (Yii::$app->request->post()) {
            if(isset($_POST["actualizar_cantidad"])){
                if(isset($_POST["listado_materiales"])){
                    $intIndice = 0; $cantidad = 0;
                    foreach ($_POST["listado_materiales"] as $intCodigo):
                        $table = \app\models\EntregaMaterialesDetalle::findOne($intCodigo);
                        $cantidad = $_POST["unidades_despachadas"][$intIndice];
                        if($cantidad <= $table->unidades_solicitadas){
                            $table->unidades_despachadas = $_POST["unidades_despachadas"][$intIndice];
                            $materia = \app\models\MateriaPrimas::findOne($table->id_materia_prima);
                            $table->save(false);
                            $intIndice++;
                        }else{
                            Yii::$app->getSession()->setFlash('warning', 'La cantidad a entregar NO puede ser mayor que la cantidad solicitada. Valide la informacion!');
                        }    
                    endforeach;
                   return $this->redirect(['view','id' =>$id, 'token' => $token]);
                }
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_solicitud' => $detalle_solicitud,
            'validar_inventario' => $validar_inventario,
        ]);
    }
    
  

      //SE AUTORIZA O DESAUTORIZA EL PRODUCTO
    public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        $entrega = \app\models\EntregaMaterialesDetalle::find()->where(['=','id_entrega', $model->id_entrega])->all();
        foreach ($entrega as $valor) {
            if ($valor->unidades_despachadas <= 0) {
               Yii::$app->getSession()->setFlash('error', 'El campo de UNIDADES DESPACHADAS no puede ser vacio o igual a 0');
               return $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' => $token]);
            }
        }
        if ($model->autorizado == 0){  
            $model->autorizado = 1;
            $model->update();
            $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' =>$token]); 
        } else{
                $model->autorizado = 0;
                $model->update();
                $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' =>$token]);  
        }    
    }
    
    //PERMITE SUBIR LAS OBSERVACIONES DE LA ENTREGA
    
    public function actionCrear_observacion($id, $token) {
        $model = new \app\models\FormModeloSubirAuditoria();
        $ensamble = \app\models\EntregaMateriales::findOne($id);
       
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["subir_observacion"])) { 
                $ensamble->observacion = $model->observacion;
                $ensamble->save(false);
                $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' => $token]);
            }    
        }
         if (Yii::$app->request->get()) {
            $model->observacion = $ensamble->observacion;
         }
        return $this->renderAjax('subir_observacion', [
            'model' => $model,
            'id' => $id,
        ]);
    }
  
     //CIERRA EL PROCESO DE SOLICTUD
    public function actionCerrar_solicitud($id, $token) {
        //proceso de generar consecutivo
        $lista = \app\models\Consecutivos::findOne(15);
        $solicitud = EntregaMateriales::findOne($id); 
        $orden = SolicitudMateriales::findOne($solicitud->codigo);
        $solicitud->numero_entrega = $lista->numero_inicial + 1;
        $solicitud->cerrar_solicitud = 1;
        $solicitud->fecha_despacho = date('Y-m-d');
        $solicitud->save(false);
        $orden->despachado = 1;
        $orden->save(false);
        $lista->numero_inicial = $solicitud->numero_entrega;
        $lista->save(false);
        $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' =>$token]);  
    }
    
     //DESCARGAR MATERIAL DE EMPAQUE
    public function actionDescargar_material_empaque($id, $token)
    {
        $model = \app\models\EntregaMateriales::findOne($id);
        $detalle = \app\models\EntregaMaterialesDetalle::find()->where(['=','id_entrega', $id])->andWhere(['=','validar_linea_materia_prima', 0])->all();
        $con = 0; $stock = 0;
        foreach ($detalle as $val) {
            $materia = \app\models\MateriaPrimas::findOne($val->id_materia_prima);
            if($materia){
                $stock = $materia->stock - $val->unidades_despachadas;
                $id_materia = $val->id_materia_prima;
                if($stock >= 0){
                    $con++;
                    $materia->stock = $stock;
                    $materia->salida_materia_prima += $val->unidades_despachadas;
                    $materia->save(false);
                    //actualiza la linea de inventario
                    $val->validar_linea_materia_prima = 1;
                    $val->save(false);
                    //guarda la bitagora
                    $table = new \app\models\BitacoraMateriasPrimas();
                    $table->id_materia_prima = $id_materia;
                    $table->cantidad = $val->unidades_despachadas;
                    $table->fecha_salida = date('Y-m-d');
                    $table->fecha_hora_salida = date('Y-m-d H:i:s');
                    $table->user_name = Yii::$app->user->identity->username;
                    if($model->solicitud->id_orden_produccion !== null){
                       $table->descripcion_salida = 'Salida de material de empaque para ordenes de produccion';
                       $table->id_orden_produccion = $model->solicitud->id_orden_produccion;

                    }else{
                        $table->descripcion_salida = 'Salida de material de empaque para entrega de kits';
                        $table->id_entrega_kits = $model->solicitud->id_entrega_kits;
                    }
                    $table->save(false);
                    $this->SumarTotalesMateria($id_materia);
                }    
            }
        }
        if(count($detalle)> 0){
            $model->descargar_material_empaque = 1;
            $model->save();
        }    
        Yii::$app->getSession()->setFlash('success', 'Se enviaron al modulo de inventario de materias primas ('.$con.') registros para ser descargados. Se validaron exitosamente.');
        return $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' =>$token]);  
    }
    
    //PROCESO QUE ACTUALIZA SALDOS
    protected function SumarTotalesMateria($id_materia) {
        $materia = \app\models\MateriaPrimas::findOne($id_materia);
        $total = 0; $iva = 0;
         if ($materia->valor_unidad !== 0){
             $total = $materia->valor_unidad * $materia->stock;     
             $iva = ($total * $materia->porcentaje_iva)/100;
             $materia->valor_iva = round($iva);
             $materia->subtotal = $total;
             $materia->total_materia_prima = $total + $iva;
             $materia->save(false);
         }
         
        
    }
    
       //REPORTES
    public function actionImprimir_entrega_materiales($id) {
        $model = EntregaMateriales::findOne($id);
        return $this->render('../formatos/reporte_entrega_materiales', [
            'model' => $model,
        ]);
    }
    

    /**
     * Finds the EntregaMateriales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EntregaMateriales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntregaMateriales::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //EXCEL QUE EXPORTA TODAS LAS ENTREGA
      //exceles
    public function actionExcelConsultaEntrega($tableexcel) {
        
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
       
                               
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'NRO SOLICITUD')
                    ->setCellValue('C1', 'NRO ENTREGA')
                    ->setCellValue('D1', 'FECHA DESPACHO')
                    ->setCellValue('E1', 'FECHA HORA REGISTRO')
                    ->setCellValue('F1', 'U. SOLICITADAS')
                    ->setCellValue('G1', 'USER NANE');
                   
               
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_entrega)
                    ->setCellValue('B' . $i, $val->solicitud->numero_solicitud)
                    ->setCellValue('C' . $i, $val->numero_entrega)
                    ->setCellValue('D' . $i, $val->fecha_despacho )
                    ->setCellValue('E' . $i, $val->fecha_hora_registro)
                    ->setCellValue('F' . $i, $val->unidades_solicitadas)
                    ->setCellValue('G' . $i, $val->user_name);
                   
                 
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Entregas.xlsx"');
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
    
    //EXCEL QUE EXPORTA EL DETALLE DE LA ENTREGA
     public function actionExcel_detalle_materiales($id) {
        
        $detalle = EntregaMaterialesDetalle::find()->where(['=','id_entrega', $id])->all();
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
       
                               
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'NRO SOLICITUD')
                    ->setCellValue('C1', 'NRO ENTREGA')
                    ->setCellValue('D1', 'FECHA DESPACHO')
                    ->setCellValue('E1', 'FECHA HORA REGISTRO')
                    ->setCellValue('F1', 'CODIGO')
                    ->setCellValue('G1', 'NOMBRE DEL MATERIAL')
                    ->setCellValue('H1', 'CODIGO PRESENTACION')
                    ->setCellValue('I1', 'NOMBRE DE LA PRESENTACION')
                    ->setCellValue('J1', "NRO ORDEN")
                    ->setCellValue('K1', 'NRO SOLICITUD KITS')
                    ->setCellValue('L1', 'U. SOLICITADAS')
                    ->setCellValue('M1', 'U. DESPACHADAS')
                    ->setCellValue('N1', 'USER NANE');
                   
               
        $i = 2;
        
        foreach ($detalle as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->entrega->id_entrega)
                    ->setCellValue('B' . $i, $val->entrega->solicitud->numero_solicitud)
                    ->setCellValue('C' . $i, $val->entrega->numero_entrega)
                    ->setCellValue('D' . $i, $val->entrega->fecha_despacho )
                    ->setCellValue('E' . $i, $val->entrega->fecha_hora_registro)
                    ->setCellValue('G' . $i, $val->materiales);
                    if($val->id_orden_produccion !== null){
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('H' . $i, $val->ordenProductos->codigo_producto)
                        ->setCellValue('I' . $i, $val->ordenProductos->descripcion)
                        ->setCellValue('J' . $i, $val->entrega->solicitud->ordenProduccion->numero_orden)
                        ->setCellValue('K' . $i, 'NO FOUNT');  
                    }else{
                        $entrega = \app\models\EntregaSolicitudKitsDetalle::findOne($val->id_detalle_entrega);
                        $solicitudArmado = \app\models\SolicitudArmadoKitsDetalle::findOne($entrega->id_detalle);
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('H' . $i, $solicitudArmado->inventario->codigo_producto)
                        ->setCellValue('I' . $i, $solicitudArmado->inventario->presentacion->descripcion)
                        ->setCellValue('J' . $i, 'NO FOUNT')    
                        ->setCellValue('K' . $i, $solicitudArmado->solicitudArmado->numero_solicitud) ;
                         
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('L' . $i, $val->unidades_solicitadas)
                    ->setCellValue('M' . $i, $val->unidades_despachadas)
                    ->setCellValue('N' . $i, $val->entrega->user_name);
                   
                 
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Detalle_entrega_materiales.xlsx"');
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
        
    
    //EXPORTAR MATERIAL EN CADA HOJA
    public function actionDetalle_materiales_hoja($id)
    {
        $detalle = EntregaMaterialesDetalle::find()->where(['=', 'id_entrega', $id])->all();
        $objPHPExcel = new \PHPExcel();

        // Establecer propiedades del documento
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
        ->setLastModifiedBy("EMPRESA")
        ->setTitle("Detalle de Entrega de Materiales")
        ->setSubject("Detalle de Entrega de Materiales por Presentación")
        ->setDescription("Documento de Excel generado por PHP para el detalle de entrega de materiales, separado por presentación.")
        ->setKeywords("excel php entrega materiales presentacion")
        ->setCategory("Reporte");

        // --- Agrupar los datos por presentación ---
        $groupedData = [];
        foreach ($detalle as $val) {
            $presentationName = '';
            $productCode = ''; // Para almacenar el código del producto para las columnas F y H

            // Determinar el nombre de la presentación y el código del producto
            if ($val->id_orden_produccion !== null) {
                // Si es una orden de producción
                $presentationName = $val->ordenProductos->descripcion;
                $productCode = $val->ordenProductos->codigo_producto;
            } else {
                // Si es un kit, buscar la información a través de las relaciones
                $entrega = \app\models\EntregaSolicitudKitsDetalle::findOne($val->id_detalle_entrega);
                $solicitudArmado = null;
                if ($entrega) {
                    $solicitudArmado = \app\models\SolicitudArmadoKitsDetalle::findOne($entrega->id_detalle);
                }

                if ($solicitudArmado && $solicitudArmado->inventario && $solicitudArmado->inventario->presentacion) {
                    $presentationName = $solicitudArmado->inventario->presentacion->descripcion;
                    $productCode = $solicitudArmado->inventario->codigo_producto;
                } else {
                    // Fallback si los datos de presentación no se encuentran para los kits
                    $presentationName = 'Sin Presentacion (Kits)';
                    $productCode = 'N/A';
                }
            }

            // Limpiar el nombre de la presentación para usarlo como título de la hoja (máx. 31 caracteres, sin caracteres inválidos)
            $sanitizedPresentationName = preg_replace('/[\\\\\/:\*\?\[\]]/', '', $presentationName);
            $sanitizedPresentationName = substr($sanitizedPresentationName, 0, 31); // Máximo 31 caracteres para el nombre de la hoja

            if (!isset($groupedData[$sanitizedPresentationName])) {
                $groupedData[$sanitizedPresentationName] = [];
            }
            // Almacenar el objeto de detalle y el código del producto juntos
            $groupedData[$sanitizedPresentationName][] = ['data' => $val, 'productCode' => $productCode];
        }

        // --- Generar hojas de cálculo para cada presentación ---

        // Eliminar la hoja predeterminada creada por PHPExcel si existe, para empezar limpio.
        if ($objPHPExcel->getSheetCount() > 0) {
            $objPHPExcel->removeSheetByIndex(0);
        }

        $sheetIndex = 0;
    if (empty($groupedData)) {
        // Si no hay datos agrupados, crear una hoja por defecto "Sin Datos"
        $objPHPExcel->createSheet(0);
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Sin Datos');

        // Configurar estilos y dimensiones de columna para la hoja "Sin Datos"
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

        // Añadir encabezados a la hoja "Sin Datos"
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'NRO SOLICITUD')
            ->setCellValue('C1', 'NRO ENTREGA')
            ->setCellValue('D1', 'FECHA DESPACHO')
            ->setCellValue('E1', 'FECHA HORA REGISTRO')
            ->setCellValue('F1', 'CODIGO')
            ->setCellValue('G1', 'NOMBRE DEL MATERIAL')
            ->setCellValue('H1', 'CODIGO PRESENTACION')
            ->setCellValue('I1', 'NOMBRE DE LA PRESENTACION')
            ->setCellValue('J1', "NRO ORDEN")
            ->setCellValue('K1', 'NRO SOLICITUD KITS')
            ->setCellValue('L1', 'U. SOLICITADAS')
            ->setCellValue('M1', 'U. DESPACHADAS')
            ->setCellValue('N1', 'USER NANE');

    } else {
        // Iterar sobre los datos agrupados para crear una hoja por cada presentación
        foreach ($groupedData as $presentationName => $items) {
            $objPHPExcel->createSheet($sheetIndex); // Crear una nueva hoja en el índice actual
            $objPHPExcel->setActiveSheetIndex($sheetIndex);
            $objPHPExcel->getActiveSheet()->setTitle($presentationName); // Usar el nombre de presentación sanitizado

            // Configurar estilos y dimensiones de columna para la hoja actual
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true); // Añadida dimensión para la columna N

            // Añadir encabezados a la hoja actual
            $objPHPExcel->setActiveSheetIndex($sheetIndex)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'NRO SOLICITUD')
                ->setCellValue('C1', 'NRO ENTREGA')
                ->setCellValue('D1', 'FECHA DESPACHO')
                ->setCellValue('E1', 'FECHA HORA REGISTRO')
                ->setCellValue('F1', 'CODIGO') // Código del producto/material
                ->setCellValue('G1', 'NOMBRE DEL MATERIAL') // Nombre del material/producto
                ->setCellValue('H1', 'CODIGO PRESENTACION') // Código de la presentación (o el mismo código de producto si no hay uno específico)
                ->setCellValue('I1', 'NOMBRE DE LA PRESENTACION') // Nombre de la presentación
                ->setCellValue('J1', "NRO ORDEN")
                ->setCellValue('K1', 'NRO SOLICITUD KITS')
                ->setCellValue('L1', 'U. SOLICITADAS')
                ->setCellValue('M1', 'U. DESPACHADAS')
                ->setCellValue('N1', 'USER NANE');

            $i = 2; // Iniciar los datos desde la fila 2 (después de los encabezados)
            foreach ($items as $itemData) {
                $val = $itemData['data'];
                $productCode = $itemData['productCode']; // Código del producto obtenido durante el agrupamiento

                $objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('A' . $i, $val->entrega->id_entrega)
                    ->setCellValue('B' . $i, $val->entrega->solicitud->numero_solicitud)
                    ->setCellValue('C' . $i, $val->entrega->numero_entrega)
                    ->setCellValue('D' . $i, $val->entrega->fecha_despacho)
                    ->setCellValue('E' . $i, $val->entrega->fecha_hora_registro)
                    ->setCellValue('F' . $i, $productCode) // Se llena la columna F con el código del producto
                    ->setCellValue('G' . $i, $val->materiales); // Nombre del material

                if ($val->id_orden_produccion !== null) {
                    $objPHPExcel->setActiveSheetIndex($sheetIndex)
                        ->setCellValue('H' . $i, $val->ordenProductos->codigo_producto) // Código de la presentación (original)
                        ->setCellValue('I' . $i, $val->ordenProductos->descripcion) // Nombre de la presentación (original)
                        ->setCellValue('J' . $i, $val->entrega->solicitud->ordenProduccion->numero_orden)
                        ->setCellValue('K' . $i, 'NO FOUND'); // Valor original
                } else {
                    // Para los kits, se vuelve a buscar la información para esta fila específica
                    // (considerar optimizar si el rendimiento es crítico, pasando más datos en $groupedData)
                    $entrega = \app\models\EntregaSolicitudKitsDetalle::findOne($val->id_detalle_entrega);
                    $solicitudArmado = null;
                    if ($entrega) {
                        $solicitudArmado = \app\models\SolicitudArmadoKitsDetalle::findOne($entrega->id_detalle);
                    }

                    if ($solicitudArmado && $solicitudArmado->inventario && $solicitudArmado->inventario->presentacion) {
                        $objPHPExcel->setActiveSheetIndex($sheetIndex)
                            ->setCellValue('H' . $i, $solicitudArmado->inventario->codigo_producto) // Código de la presentación (original)
                            ->setCellValue('I' . $i, $solicitudArmado->inventario->presentacion->descripcion) // Nombre de la presentación (original)
                            ->setCellValue('J' . $i, 'NO FOUND') // Valor original
                            ->setCellValue('K' . $i, $solicitudArmado->solicitudArmado->numero_solicitud);
                    } else {
                        // Fallback si faltan datos del kit
                        $objPHPExcel->setActiveSheetIndex($sheetIndex)
                            ->setCellValue('H' . $i, 'N/A')
                            ->setCellValue('I' . $i, 'N/A')
                            ->setCellValue('J' . $i, 'NO FOUND')
                            ->setCellValue('K' . $i, 'N/A');
                    }
                }
                $objPHPExcel->setActiveSheetIndex($sheetIndex)
                    ->setCellValue('L' . $i, $val->unidades_solicitadas)
                    ->setCellValue('M' . $i, $val->unidades_despachadas)
                    ->setCellValue('N' . $i, $val->entrega->user_name);

                $i++;
            }
            $sheetIndex++;
        }
    }


    // Establecer la hoja activa de nuevo a la primera (índice 0) para que se muestre al abrir el archivo
    if ($objPHPExcel->getSheetCount() > 0) {
        $objPHPExcel->setActiveSheetIndex(0);
    }

    // Redirigir la salida al navegador del cliente (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Detalle_entrega_materiales.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1'); // Para IE 9
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Fecha en el pasado
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // Siempre modificado
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save('php://output');
    exit;
    }
}
