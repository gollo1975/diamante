<?php

namespace app\controllers;

use Yii;
use app\models\MateriaPrimas;
use app\models\MateriaPrimasSearch;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaMateriaPrima;
//clases
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use Codeception\Lib\HelperModule;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;

/**
 * MateriaPrimasController implements the CRUD actions for MateriaPrimas model.
 */
class MateriaPrimasController extends Controller
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
     * Lists all MateriaPrimas models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',4])->all()){
                $form = new FiltroBusquedaMateriaPrima();
                $codigo = null;
                $materia_prima = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $medida = null;
                $codigo_barra = null;
                $aplica_inventario = null;
                $busqueda_vcto = null;
                $tipo_solicitud = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $materia_prima = Html::encode($form->materia_prima);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $medida = Html::encode($form->medida);
                        $codigo_barra = Html::encode($form->codigo_barra);
                        $tipo_solicitud= Html::encode($form->tipo_solicitud);
                        $aplica_inventario = Html::encode($form->aplica_inventario);
                        $busqueda_vcto = Html::encode($form->busqueda_vcto);
                        if ($busqueda_vcto == 0){
                            $table = MateriaPrimas::find()
                                    ->andFilterWhere(['=', 'codigo_materia_prima', $codigo])
                                    ->andFilterWhere(['like', 'materia_prima', $materia_prima])
                                    ->andFilterWhere(['>=', 'fecha_entrada', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_entrada', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_medida', $medida])
                                    ->andFilterWhere(['=', 'codigo_ean', $codigo_barra])
                                    ->andFilterWhere(['=', 'id_solicitud', $tipo_solicitud])
                                    ->andFilterWhere(['=', 'aplica_inventario', $aplica_inventario]);
                        }else{
                            $table = MateriaPrimas::find()
                                    ->andFilterWhere(['=', 'codigo_materia_prima', $codigo])
                                    ->andFilterWhere(['like', 'materia_prima', $materia_prima])
                                    ->andFilterWhere(['>=', 'fecha_vencimiento', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_vencimiento', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_medida', $medida])
                                    ->andFilterWhere(['=', 'codigo_ean', $codigo_barra])
                                    ->andFilterWhere(['=', 'id_solicitud', $tipo_solicitud])
                                    ->andFilterWhere(['=', 'aplica_inventario', $aplica_inventario]);
                        }    
                        $table = $table->orderBy('id_materia_prima DESC');
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
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_materia_prima  DESC']);
                            $this->actionExcelConsultaMateria($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = MateriaPrimas::find()
                            ->orderBy('id_materia_prima desc');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaMateria($tableexcel);
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
    
    //PROCESO DE CONSULTA DE MATERIAS PRIMAS
     public function actionSearch_consulta_materias($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',21])->all()){
                $form = new FiltroBusquedaMateriaPrima();
                $codigo = null;
                $materia_prima = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $medida = null;
                $codigo_barra = null;
                $aplica_inventario = null;
                $busqueda_vcto = null;
                $tipo_solicitud = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $materia_prima = Html::encode($form->materia_prima);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $medida = Html::encode($form->medida);
                        $codigo_barra = Html::encode($form->codigo_barra);
                        $aplica_inventario = Html::encode($form->aplica_inventario);
                        $tipo_solicitud= Html::encode($form->tipo_solicitud);
                        $busqueda_vcto = Html::encode($form->busqueda_vcto);
                        if ($busqueda_vcto == 0){
                            $table = MateriaPrimas::find()
                                    ->andFilterWhere(['=', 'codigo_materia_prima', $codigo])
                                    ->andFilterWhere(['like', 'materia_prima', $materia_prima])
                                    ->andFilterWhere(['>=', 'fecha_entrada', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_entrada', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_medida', $medida])
                                    ->andFilterWhere(['=', 'codigo_ean', $codigo_barra])
                                     ->andFilterWhere(['=', 'id_solicitud', $tipo_solicitud])
                                    ->andFilterWhere(['=', 'aplica_inventario', $aplica_inventario]);
                        }else{
                            $table = MateriaPrimas::find()
                                    ->andFilterWhere(['=', 'codigo_materia_prima', $codigo])
                                    ->andFilterWhere(['like', 'materia_prima', $materia_prima])
                                    ->andFilterWhere(['>=', 'fecha_vencimiento', $fecha_inicio])
                                    ->andFilterWhere(['<=', 'fecha_vencimiento', $fecha_corte])
                                    ->andFilterWhere(['=', 'id_medida', $medida])
                                    ->andFilterWhere(['=', 'codigo_ean', $codigo_barra])
                                     ->andFilterWhere(['=', 'id_solicitud', $tipo_solicitud])
                                    ->andFilterWhere(['=', 'aplica_inventario', $aplica_inventario]);
                        }    
                        $table = $table->orderBy('id_materia_prima DESC');
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
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_materia_prima  DESC']);
                            $this->actionExcelConsultaMateria($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = MateriaPrimas::find()
                            ->orderBy('id_materia_prima desc');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaMateria($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_consulta_materias', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                            'tableexcel' => $tableexcel,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    /**
     * Displays a single MateriaPrimas model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $table = \app\models\EntradaMateriaPrimaDetalle::find()->where(['=','id_materia_prima', $id])->orderBy('id_detalle DESC');
        $tableexcel = $table->all();
        $count = clone $table;
        $pages = new Pagination([
            'pageSize' => 5,
            'totalCount' => $count->count(),
        ]);
        $detalle_entrada = $table
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        $to = $count->count();
       return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_entrada' => $detalle_entrada,
            'pagination' => $pages,
        ]);
    }

    /**
     * Creates a new MateriaPrimas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   public function actionCreate()
    {
        $model = new MateriaPrimas();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if($model->total_cantidad == null){
                     Yii::$app->getSession()->setFlash('warning', 'El campo CANTIDAD debe de ser igual a cero o mayor.');
                }else{
                    if(MateriaPrimas::find()->where(['=','codigo_materia_prima', $model->codigo_materia_prima])->one()){
                        Yii::$app->getSession()->setFlash('error', 'Este codigo ya se encuentra codificado con otra materia prima. Validar la informacion nuevamente.');   
                    }else{
                        $sumar = 0;
                        $table = new MateriaPrimas();
                        $table->codigo_materia_prima = $model->codigo_materia_prima;
                        $table->materia_prima = $model->materia_prima;
                        $table->fecha_entrada = $model->fecha_entrada;
                        $table->fecha_vencimiento = $model->fecha_vencimiento;
                        $table->id_medida = $model->id_medida;
                        $table->aplica_inventario = $model->aplica_inventario;
                        $table->aplica_iva = $model->aplica_iva;
                        $table->stock= $model->total_cantidad;
                        $table->porcentaje_iva = $model->porcentaje_iva;
                        $table->total_cantidad = $model->total_cantidad; 
                        $table->valor_unidad = $model->valor_unidad;                    
                        if($table->aplica_iva == 1){
                            $sumar = round((($table->valor_unidad *   $table->stock)* $table->porcentaje_iva)/100);
                            $table->valor_iva = $sumar;
                            $table->total_materia_prima = round(($table->valor_unidad *   $table->stock) + $sumar);
                        }else{
                            $sumar = round(($table->valor_unidad *   $table->stock));
                            $table->valor_iva = 0;
                            $table->total_materia_prima = round($sumar);
                         }
                        $table->usuario_creador = Yii::$app->user->identity->username;
                        $table->usuario_editado = Yii::$app->user->identity->username;
                        $table->descripcion = $model->descripcion;
                        $table->codigo_ean = $model->codigo_materia_prima;
                        $table->inventario_inicial = $model->inventario_inicial;
                        $table->id_solicitud = $model->id_solicitud;
                        $table->convertir_gramos = $model->convertir_gramos;
                        $table->save(false);
                        if($model->convertir_gramos == 1){
                           $materia = MateriaPrimas::find()->orderBy('id_materia_prima DESC')->one();
                           $this->ConvertiGramosCantidad($materia);
                        }
                        return $this->redirect(['index']);
                    }    
                }   
            }else{
                $model->getErrors();
            }    
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    //PROCESO QUE CONVIERTE LAS CNTIDADES A GRAMO
    protected function ConvertiGramosCantidad($materia) {
        $numero = 0;
        $numero = $materia->stock * 1000;
        $materia->stock_gramos = $numero;
        $materia->save();
    }
    
    
    /**
     * Updates an existing MateriaPrimas model.
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
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if($model->total_cantidad == null){
                     Yii::$app->getSession()->setFlash('warning', 'El campo CANTIDAD debe de ser igual a cero o mayor.');
                }else{
                    $table = MateriaPrimas::findOne($id);
                    $table->codigo_materia_prima = $model->codigo_materia_prima;
                    $table->materia_prima = $model->materia_prima;
                    $table->fecha_entrada = $model->fecha_entrada;
                    $table->fecha_vencimiento = $model->fecha_vencimiento;
                    $table->id_medida = $model->id_medida;
                    $table->aplica_inventario = $model->aplica_inventario;
                    $table->aplica_iva = $model->aplica_iva;
                    $table->stock = $model->total_cantidad;
                    $table->total_cantidad = $model->total_cantidad;
                    $table->porcentaje_iva = $model->porcentaje_iva;
                    $table->total_cantidad = $model->total_cantidad; 
                    $table->valor_unidad = $model->valor_unidad;                    
                    if($table->aplica_iva == 1){
                        $sumar = round((($table->valor_unidad *   $table->stock)* $table->porcentaje_iva)/100);
                        $table->valor_iva = $sumar;
                        $table->total_materia_prima = round(($table->valor_unidad *   $table->stock) + $sumar);
                    }else{
                        $sumar = round(($table->valor_unidad *   $table->stock));
                        $table->valor_iva = 0;
                        $table->total_materia_prima = round($sumar);
                     }
                    $table->usuario_editado = Yii::$app->user->identity->username;
                    $table->descripcion = $model->descripcion;
                    $table->codigo_ean = $model->codigo_materia_prima;
                    $table->inventario_inicial = $model->inventario_inicial;
                    $table->id_solicitud = $model->id_solicitud;
                    $table->convertir_gramos = $model->convertir_gramos;
                    $table->save(false);
                    if($model->convertir_gramos == 1){
                           $materia = $this->findModel($id);
                           $this->ConvertiGramosCantidad($materia);
                        }
                    return $this->redirect(['index']);
                }     
                
            }else{
             $model->getErrors();      
            }
        }
        if (Yii::$app->request->get("id")) {
            $table = MateriaPrimas::find()->where(['id_materia_prima' =>$id])->one();
            $model->codigo_materia_prima = $table->codigo_materia_prima;
            $model->materia_prima = $table->materia_prima;
            $model->fecha_entrada = $table->fecha_entrada;
            $model->fecha_vencimiento = $table->fecha_vencimiento;
            $model->id_medida = $table->id_medida;
            $model->valor_unidad =  $table->valor_unidad;
            $model->aplica_iva = $table->aplica_iva;
            $model->porcentaje_iva =  $table->porcentaje_iva;
            $model->total_cantidad = $table->total_cantidad;       
            $model->aplica_inventario = $table->aplica_inventario;
            $model->descripcion =  $table->descripcion;
            $model->inventario_inicial = $table->inventario_inicial;
            $model->id_solicitud = $table->id_solicitud;
            $model->convertir_gramos = $table->convertir_gramos;
        }
        return $this->render('update', [
            'model' => $model,

        ]);
    }

    /**
     * Finds the MateriaPrimas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MateriaPrimas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MateriaPrimas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //PROCESO DE EXCEL 
     public function actionExcelconsultaMateria($tableexcel) {                
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
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'MATERIA PRIMA')
                    ->setCellValue('D1', 'MEDIDA')
                    ->setCellValue('E1', 'FECHA ENTRADA')
                    ->setCellValue('F1', 'FECHA VCTO')
                    ->setCellValue('G1', 'APLICA INVENTARIO')
                    ->setCellValue('H1', 'UNIDADES')
                    ->setCellValue('I1', 'STOCK')
                    ->setCellValue('J1', 'APLICA IVA')
                    ->setCellValue('K1', 'PORCENTAJE')
                    ->setCellValue('L1', 'VL. UNIDAD')
                    ->setCellValue('M1', 'CANTIDAD')
                    ->setCellValue('N1', 'VL. IVA')
                    ->setCellValue('O1', 'TOTAL VALOR')
                    ->setCellValue('P1', 'USER CREADOR')
                    ->setCellValue('Q1', 'USER EDITADO')
                    ->setCellValue('R1', 'DESCRIPCION')
                    ->setCellValue('S1', 'INV. INICIAL')
                    ->setCellValue('T1', 'CLASIFICACION');
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_materia_prima)
                    ->setCellValue('B' . $i, $val->codigo_materia_prima)
                    ->setCellValue('C' . $i, $val->materia_prima)
                    ->setCellValue('D' . $i, $val->medida->descripcion)
                    ->setCellValue('E' . $i, $val->fecha_entrada)
                    ->setCellValue('F' . $i, $val->fecha_vencimiento)
                    ->setCellValue('G' . $i, $val->aplicaInventario)
                    ->setCellValue('H' . $i, $val->total_cantidad)
                    ->setCellValue('I' . $i, $val->stock)
                    ->setCellValue('J' . $i, $val->aplicaIva)
                    ->setCellValue('K' . $i, $val->porcentaje_iva)
                    ->setCellValue('L' . $i, $val->valor_unidad)
                    ->setCellValue('M' . $i, $val->total_cantidad)
                    ->setCellValue('N' . $i, $val->valor_iva)
                    ->setCellValue('O' . $i, $val->total_materia_prima)
                    ->setCellValue('P' . $i, $val->usuario_creador)
                    ->setCellValue('Q' . $i, $val->usuario_editado)
                    ->setCellValue('R' . $i, $val->descripcion)
                    ->setCellValue('S' . $i, $val->inventarioInicial)
                    ->setCellValue('T' . $i, $val->tipoSolicitud->descripcion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('detalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Materias_prima.xlsx"');
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
