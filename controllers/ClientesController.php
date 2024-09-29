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
use app\models\Clientes;
use app\models\ClientesSearch;
use app\models\UsuarioDetalle;
use app\models\OrdenProduccion;
use app\models\PosicionPrecio;
use app\models\FiltroBusquedaProveedor;
use app\models\Municipios;
use app\models\Departamentos;
use app\models\ClienteAnotaciones;
use app\models\ClientesContactos;

/**
 * ClientesController implements the CRUD actions for Clientes model.
 */
class ClientesController extends Controller
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
     * Lists all Clientes models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',32])->all()){
                $form = new FiltroBusquedaProveedor();
                $nitcedula = null;
                $nombre_completo = null;
                $activo = null;
                $vendedor = null;
                $tipo_cliente = null;
                $model = null;
                $pages = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $nitcedula = Html::encode($form->nitcedula);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        $vendedor = Html::encode($form->vendedor);
                        $activo = Html::encode($form->activo);
                        $tipo_cliente = Html::encode($form->tipo_cliente);
                        $table = Clientes::find()
                                ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                ->andFilterWhere(['=', 'estado_cliente', $activo])
                                ->andFilterWhere(['=', 'id_agente', $vendedor])
                                ->andFilterWhere(['=', 'id_tipo_cliente', $tipo_cliente])
                                ->andFilterWhere(['like', 'nombre_completo', $nombre_completo]);
                        $table = $table->orderBy('id_cliente DESC');
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
                            $this->actionExcelconsultaClientes($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                }else{
                    $table = Clientes::find()
                            ->orderBy('id_cliente desc');
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
                            $this->actionExcelconsultaClientes($tableexcel);
                    }
                }
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
    //CONSULTA DE CLIENTES
    
    public function actionSearch_consulta_clientes($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',33])->all()){
                $form = new FiltroBusquedaProveedor();
                $nitcedula = null;
                $nombre_completo = null;
                $activo = null;
                $vendedor = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $nitcedula = Html::encode($form->nitcedula);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        $vendedor = Html::encode($form->vendedor);
                        $activo = Html::encode($form->activo);
                        $table = Clientes::find()
                                ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                ->andFilterWhere(['=', 'estado_cliente', $activo])
                                ->andFilterWhere(['=', 'id_agente', $vendedor])
                                ->andFilterWhere(['like', 'nombre_completo', $nombre_completo]);
                        $table = $table->orderBy('id_cliente DESC');
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
                            $this->actionExcelconsultaClientes($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = Clientes::find()
                            ->orderBy('id_cliente desc');
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
                            $this->actionExcelconsultaClientes($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_consulta_clientes', [
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
     * Displays a single Clientes model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $cupo = \app\models\ClienteCupoComercial::find()->where(['=','id_cliente', $id])->orderBy('id_cupo DESC')->all();
        $anotacion = ClienteAnotaciones::find()->where(['=','id_cliente', $id])->orderBy('id_anotacion DESC')->all();
        $Concontacto = ClientesContactos::find()->where(['=','id_cliente', $id])->all();
        if(isset($_POST["actualizarcupo"])){
            if(isset($_POST["listado_cupo"])){
                $intIndice = 0;
                foreach ($_POST["listado_cupo"] as $intCodigo):
                    $table = \app\models\ClienteCupoComercial::find()->where(['=','id_cupo', $intCodigo])->andWhere(['=','estado_registro', 0])->one();
                    if($table){
                        $table->valor_cupo = $_POST["valor_cupo"]["$intIndice"];
                        $table->estado_registro = $_POST["estado_registro"]["$intIndice"];
                        $table->save(false);
                        if($table->estado_registro == 1){
                            $cliente = Clientes::findOne($id);
                            $cliente->cupo_asignado = 0;
                            $cliente->update();
                        }else{
                            $cliente = Clientes::findOne($id);
                           $cliente->cupo_asignado =  $table->valor_cupo;
                            $cliente->update();
                        }    
                    }    
                    $intIndice++;
                endforeach;
                return $this->redirect(['view','id' =>$id, 'token' => $token]);
            }
        }    
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token'=> $token,
            'cupo' => $cupo,
            'anotacion' => $anotacion,
            'Concontacto' => $Concontacto,
        ]);
    }

    /**
     * Creates a new Clientes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Clientes();
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $dv = Html::encode($_POST["dv"]);
                $table = $this->findModel($model->id_cliente);
                $table->user_name = Yii::$app->user->identity->username;
                $table->dv = $dv;
                if ($model->id_tipo_documento == 1 || $model->id_tipo_documento == 2 ) {
                    $table->nombre_completo = strtoupper($model->primer_nombre . " " . $model->segundo_nombre . " " . $model->primer_apellido . " " . $model->segundo_apellido);
                    $table->razon_social = null;
                 } else {
                     $table->nombre_completo = strtoupper($model->razon_social); 
                     $table->primer_nombre = null;
                     $table->segundo_nombre = null;
                     $table->primer_apellido = null;
                     $table->segundo_apellido = null;
                 }
                $table->save(false);
                return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Clientes model.
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
        $municipio = Municipios::find()->Where(['=', 'codigo_departamento', $model->codigo_departamento])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $dv = Html::encode($_POST["dv"]);
                $table = $this->findModel($id);
                $table->user_name_editar = Yii::$app->user->identity->username;
                $table->dv = $dv;
                $table->fecha_editado = date('Y-m-d');
                if ($model->id_tipo_documento == 1 || $model->id_tipo_documento == 2 ) {
                    $table->nombre_completo = strtoupper($model->primer_nombre . " " . $model->segundo_nombre . " " . $model->primer_apellido . " " . $model->segundo_apellido);
                    $table->razon_social = null;
                 } else {
                     $table->nombre_completo = strtoupper($model->razon_social); 
                     $table->primer_nombre = null;
                     $table->segundo_nombre = null;
                     $table->primer_apellido = null;
                     $table->segundo_apellido = null;
                 }
                $table->save(false);
                return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
            'municipio' => ArrayHelper::map($municipio, "codigo_municipio", "municipio"),
        ]);
    }

    public function actionNuevo_cupo_cliente($id, $token) {
        $model = new \app\models\FormModeloCrearCupo();
        
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["crear_cupo"])) {
                    $table = new \app\models\ClienteCupoComercial();
                    $cliente = Clientes::findOne($id);
                    $table->id_cliente = $id;
                    $table->valor_cupo = $model->nuevo_cupo;
                    $table->descripcion = $model->descripcion;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $cliente->cupo_asignado = $model->nuevo_cupo;
                    $cliente->save();
                    $this->redirect(["clientes/view", 'id' => $id,'token' => $token]);
                }  
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('new_cupo_cliente', [
            'model' => $model,
            'id' => $id,
            'token' => $token,
        ]);
    } 
    
    public function actionAnotacion_cliente($id, $token) {
        $model = new \app\models\FormModeloCrearAnotacion();
        
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["crear_anotacion"])) {
                    $table = new \app\models\ClienteAnotaciones();
                    $table->id_cliente = $id;
                    $table->anotacion = $model->anotacion;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $this->redirect(["clientes/view", 'id' => $id,'token' => $token]);
                }  
            }else{
                $model->getErrors();
            }    
        }
          return $this->renderAjax('new_anotacion', [
            'model' => $model,
            'id' => $id,
            'token' => $token,
        ]);
    }    
        //parametros de cliente
        
    public function actionParametro_cliente($id)
    {
        $model = new \app\models\ModeloParametroCliente();
        $table = Clientes::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["parametro_cliente"])) {
                $table->estado_cliente = $model->activo;
                $table->aplicar_venta_mora = $model->aplicar_venta_mora;
                $table->presupuesto_comercial = $model->presupuesto;
                $table->save(false);
                $this->redirect(["clientes/index"]);
            }
        }
        if (Yii::$app->request->get()) {
            $model->aplicar_venta_mora = $table->aplicar_venta_mora;
            $model->presupuesto = $table->presupuesto_comercial;
            $model->activo = $table->estado_cliente;
        }
        return $this->renderAjax('parametros', [
                    'model' => $model,
        ]);
    }
    
    //CREA UN NUEVO CONTACTO DEL CLIENTE
    public function actionNew_contacto($id, $token) {

        $model = new \app\models\FormModeloContactoCliente();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nuevo_contacto_cliente"])) {
                    $table = new ClientesContactos();
                    $table->id_cliente = $id;
                    $table->nombres = strtoupper($model->nombres);
                    $table->apellidos = strtoupper($model->apellidos);
                    $table->celular = $model->celular;
                    $table->email = $model->email;
                    $table->id_cargo = $model->cargo;
                    $table->fecha_nacimiento = $model->fecha_nacimiento;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $this->redirect(["clientes/view", 'id' => $id,'token' => $token]);
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->renderAjax('form_nuevo_contacto', [
                    'model' => $model,
                    'id' => $id,
                    'token' => $token,
        ]);
    }
    //EDITAR CONTACTO
    public function actionEditar_contacto($id, $token, $detalle) {
        
        $model = new \app\models\FormModeloContactoCliente();
        $table = ClientesContactos::findOne($detalle);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nuevo_contacto_cliente"])) {
                    $table->nombres = strtoupper($model->nombres);
                    $table->apellidos = strtoupper($model->apellidos);
                    $table->celular = $model->celular;
                    $table->email = $model->email;
                    $table->id_cargo = $model->cargo;
                    $table->fecha_nacimiento = $model->fecha_nacimiento;
                    $table->save(false);
                    $this->redirect(["clientes/view", 'id' => $id,'token' => $token]);
                }
            } else {
                $model->getErrors();
            }
        }
        if (Yii::$app->request->get()) {
            $model->nombres = $table->nombres;
            $model->apellidos = $table->apellidos;
            $model->celular = $table->celular;
            $model->email = $table->email;
            $model->fecha_nacimiento = $table->fecha_nacimiento;
            $model->cargo = $table->id_cargo;
        }
        return $this->renderAjax('form_nuevo_contacto', [
                    'model' => $model,
                    'id' => $id,
                    'token' => $token,
        ]);
    }
     
    /**
     * Finds the Clientes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Clientes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Clientes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //EXPORTA LOS CLIENTES DE CADA VENDEDOR
    public function actionExcelconsultaClientes($tableexcel) {
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
                               
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO DOCUMENTO')
                    ->setCellValue('C1', 'NIT/CEDULA')
                    ->setCellValue('D1', 'DV')
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'DIRECCION')
                    ->setCellValue('G1', 'CELULAR')
                    ->setCellValue('H1', 'TELEFONO')
                    ->setCellValue('I1', 'EMAIL')
                    ->setCellValue('J1', 'DEPARTAMENTO')
                    ->setCellValue('K1', 'MUNICIPIO')
                    ->setCellValue('L1', "TIPO REGIMEN")
                    ->setCellValue('M1', 'FORMA DE PAGO')
                    ->setCellValue('N1', 'PLAZO')
                    ->setCellValue('O1', 'AUTORETENEDOR')
                    ->setCellValue('P1', 'NATURALEZA')
                    ->setCellValue('Q1', 'TIPO SOCIEDAD')
                    ->setCellValue('R1', 'USER CREAR')
                    ->setCellValue('S1', 'USER EDITAR')
                    ->setCellValue('T1', 'FECHA CREACION')
                    ->setCellValue('U1', 'ACTIVO')
                    ->setCellValue('V1', 'CUPO ASIGNADO')
                     ->setCellValue('W1', 'AGENTE COMERCIAL');
               
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_cliente)
                    ->setCellValue('B' . $i, $val->tipoDocumento->tipo_documento)
                    ->setCellValue('C' . $i, $val->nit_cedula)
                    ->setCellValue('D' . $i, $val->dv)
                    ->setCellValue('E' . $i, $val->nombre_completo)
                    ->setCellValue('F' . $i, $val->direccion)
                    ->setCellValue('G' . $i, $val->celular)
                    ->setCellValue('H' . $i, $val->telefono)
                    ->setCellValue('I' . $i, $val->email_cliente)
                    ->setCellValue('J' . $i, $val->codigoDepartamento->departamento)
                    ->setCellValue('K' . $i, $val->codigoMunicipio->municipio)
                    ->setCellValue('L' . $i, $val->tipoRegimen)
                    ->setCellValue('M' . $i, $val->formaPago)
                    ->setCellValue('N' . $i, $val->plazo)
                    ->setCellValue('O' . $i, $val->autoretenedorVenta)
                    ->setCellValue('P' . $i, $val->naturaleza->naturaleza)
                    ->setCellValue('Q' . $i, $val->tipoSociedad)
                    ->setCellValue('R' . $i, $val->user_name)
                    ->setCellValue('S' . $i, $val->user_name_editar)
                    ->setCellValue('T' . $i, $val->fecha_creacion)
                    ->setCellValue('U' . $i, $val->estadoCliente)
                    ->setCellValue('V' . $i, $val->cupo_asignado)
                    ->setCellValue('W' . $i, $val->agenteComercial->nombre_completo);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Listado_Clientes.xlsx"');
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
