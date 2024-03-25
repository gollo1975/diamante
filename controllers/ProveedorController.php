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
use yii\db\Expression;
use yii\db\Query;
use yii\db\Command;

//model
use app\models\Proveedor;
use app\models\ProveedorSearch;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaProveedor;
use app\models\Municipios;
use app\models\Departamentos;
use app\models\EntidadBancarias;
use app\models\NaturalezaSociedad;
use app\models\ListadoRequisitos;
/**
 * ProveedorController implements the CRUD actions for Proveedor model.
 */
class ProveedorController extends Controller
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
     * Lists all Proveedor models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',5])->all()){
                $form = new FiltroBusquedaProveedor();
                $nitcedula = null;
                $nombre_completo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $nitcedula = Html::encode($form->nitcedula);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        $table = Proveedor::find()
                                ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                ->andFilterWhere(['like', 'nombre_completo', $nombre_completo]);
                        $table = $table->orderBy('id_proveedor DESC');
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
                            $this->actionExcelconsultaProveedor($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = Proveedor::find()
                            ->orderBy('id_proveedor desc');
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
                            $this->actionExcelconsultaProveedor($tableexcel);
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
    
    //CONSULTAS DE PROVEEDOR
      public function actionSearch_consulta_proveedor($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',26])->all()){
                $form = new FiltroBusquedaProveedor();
                $nitcedula = null;
                $nombre_completo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $nitcedula = Html::encode($form->nitcedula);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        $table = Proveedor::find()
                                ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                ->andFilterWhere(['like', 'nombre_completo', $nombre_completo]);
                        $table = $table->orderBy('id_proveedor DESC');
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
                            $this->actionExcelconsultaProveedor($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = Proveedor::find()
                            ->orderBy('id_proveedor desc');
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
                            $this->actionExcelconsultaProveedor($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_consulta_proveedor', [
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
     * Displays a single Proveedor model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $listado = ListadoRequisitos::find()->where(['=','aplica_proveedor', 1])->orderBy('concepto ASC')->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'listado' => $listado,
        ]);
    }

    /**
     * Creates a new Proveedor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Proveedor();
        $msg = 0;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $dv = Html::encode($_POST["dv"]);
            $banco = EntidadBancarias::findOne($model->codigo_banco);
            $variable = 0;
            $variable = strlen($model->producto);
            if($variable == $banco->validador_digitos){
                $empresa = \app\models\MatriculaEmpresa::findOne(1);
                $table = new Proveedor();
                $table->id_tipo_documento = $model->id_tipo_documento;
                $table->nit_cedula = $model->nit_cedula;
                $table->dv = $dv;
                $table->razon_social = $model->razon_social;
                $table->primer_nombre = $model->primer_nombre;
                $table->segundo_nombre =  $model->segundo_nombre;
                $table->primer_apellido = $model->primer_apellido;
                $table->segundo_apellido =  $model->segundo_apellido;
                $table->direccion = $model->direccion;
                $table->email = $model->email;
                $table->telefono = $model->telefono;
                $table->celular = $model->celular;
                $table->codigo_departamento = $model->codigo_departamento;
                $table->codigo_municipio = $model->codigo_municipio;
                $table->nombre_contacto = $model->nombre_contacto;
                $table->celular_contacto = $model->celular_contacto;
                $table->forma_pago = $model->forma_pago;
                $table->plazo = $model->plazo;
                $table->autoretenedor = $model->autoretenedor;
                $table->tipo_regimen = $model->tipo_regimen;
                $table->id_naturaleza = $model->id_naturaleza;
                $table->tipo_sociedad = $model->tipo_sociedad;
                $table->codigo_banco = $model->codigo_banco;
                $table->tipo_cuenta = $model->tipo_cuenta;
                $table->producto = $model->producto;
                $table->tipo_transacion = $model->tipo_transacion;
                 $table->predeterminado = $model->predeterminado;
                $table->user_name = Yii::$app->user->identity->username;
                $table->id_empresa = $empresa->nit_empresa;
                $table->observacion = $model->observacion;
                if ($model->id_tipo_documento == 1 || $model->id_tipo_documento == 2 ) {
                   $table->nombre_completo = strtoupper($model->primer_nombre . " " . $model->segundo_nombre . " " . $model->primer_apellido . " " . $model->segundo_apellido);
                   $table->razon_social = null;
                } else {
                    $table->nombre_completo = strtoupper($table->razon_social); 
                    $table->primer_nombre = null;
                    $table->segundo_nombre = null;
                    $table->primer_apellido = null;
                    $table->segundo_apellido = null;
                }
                $table->save(false);
               return $this->redirect(['index']);
            }else{
                $msg = 1;
               // $this->redirect(["proveedor/create", 'msg' => $msg]);
            }   
        }
        return $this->render('create', ['model' => $model, 'msg' => $msg]);
    }

    /**
     * Updates an existing Proveedor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $msg)
    {
        $model = new Proveedor();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $dv = Html::encode($_POST["dv"]);
            $table = Proveedor::find()->where(['id_proveedor' => $id])->one();
            $banco = EntidadBancarias::findOne($model->codigo_banco);
            $variable = 0;
            $variable = strlen($model->producto);
            if($variable == $banco->validador_digitos){
                if ($table) {
                    $table->id_tipo_documento = $model->id_tipo_documento;
                    $table->razon_social = $model->razon_social;
                    $table->primer_nombre = $model->primer_nombre;
                    $table->segundo_nombre =  $model->segundo_nombre;
                    $table->primer_apellido = $model->primer_apellido;
                    $table->segundo_apellido =  $model->segundo_apellido;
                    $table->direccion = $model->direccion;
                    $table->email = $model->email;
                    $table->telefono = $model->telefono;
                    $table->celular = $model->celular;
                    $table->codigo_departamento = $model->codigo_departamento;
                    $table->codigo_municipio = $model->codigo_municipio;
                    $table->nombre_contacto = $model->nombre_contacto;
                    $table->celular_contacto = $model->celular_contacto;
                    $table->forma_pago = $model->forma_pago;
                    $table->plazo = $model->plazo;
                    $table->autoretenedor = $model->autoretenedor;
                    $table->tipo_regimen = $model->tipo_regimen;
                    $table->id_naturaleza = $model->id_naturaleza;
                    $table->tipo_sociedad = $model->tipo_sociedad;
                    $table->codigo_banco = $model->codigo_banco;
                    $table->tipo_cuenta = $model->tipo_cuenta;
                    $table->producto = $model->producto;
                    $table->tipo_transacion = $model->tipo_transacion;
                    $table->predeterminado = $model->predeterminado;
                    $table->observacion = $model->observacion;
                    if ($model->id_tipo_documento == 1 || $model->id_tipo_documento == 2 ) {
                       $table->nombre_completo = strtoupper($model->primer_nombre . " " . $model->segundo_nombre . " " . $model->primer_apellido . " " . $model->segundo_apellido);
                       $table->razon_social = null;
                    } else {
                        $table->nombre_completo = strtoupper($table->razon_social); 
                        $table->primer_nombre = null;
                        $table->segundo_nombre = null;
                        $table->primer_apellido = null;
                        $table->segundo_apellido = null;
                    }
                    $table->save(false);
                    return $this->redirect(['index']);
                }
            }else{
                $msg = 1;
                Yii::$app->getSession()->setFlash('warning', 'Debe de llenar todos los campos del proveedor para procesar la informacion.');
                return $this->redirect(["proveedor/index", 'msg' => $msg, 'id' => $id]);
            }

        }
        if (Yii::$app->request->get("id")) {
            $table = Proveedor::find()->where(['id_proveedor' => $id])->one();
            $municipio = Municipios::find()->Where(['=', 'codigo_departamento', $table->codigo_departamento])->all();
            $municipio = ArrayHelper::map($municipio, "codigo_municipio", "municipio");
            if ($table) {
                $model->id_tipo_documento = $table->id_tipo_documento;
                $model->nit_cedula = $table->nit_cedula;
                $model->dv = $table->dv;
                $model->razon_social = $table->razon_social;
                $model->primer_nombre = $table->primer_nombre;
                $model->segundo_nombre = $table->segundo_nombre;
                $model->primer_apellido = $table->primer_apellido;
                $model->segundo_apellido = $table->segundo_apellido;
                $model->direccion = $table->direccion;
                $model->telefono = $table->telefono;
                $model->celular = $table->celular;
                $model->email = $table->email;
                $model->codigo_departamento = $table->codigo_departamento;
                $model->codigo_municipio = $table->codigo_municipio;
                $model->nombre_contacto = $table->nombre_contacto;
                $model->celular_contacto = $table->celular_contacto;
                $model->forma_pago = $table->forma_pago;
                $model->plazo = $table->plazo;
                $model->tipo_regimen = $table->tipo_regimen;
                $model->autoretenedor = $table->autoretenedor;
                $model->id_naturaleza = $table->id_naturaleza;
                $model->tipo_sociedad = $table->tipo_sociedad;
                $model->codigo_banco = $table->codigo_banco;
                $model->tipo_cuenta = $table->tipo_cuenta;
                $model->producto = $table->producto;
                $model->tipo_transacion = $table->tipo_transacion;
                $model->predeterminado = $table->predeterminado;
                $model->observacion = $table->observacion;

            } else {
                return $this->redirect(["proveedor/index"]);
            }
        } else {
            return $this->redirect(["proveedor/index"]);
        }
    return $this->render('update', [
            'model' => $model,
            'msg' => $msg,
            'municipio' => $municipio,
         ]);
    }
    
    //PROCESO QUE VALIDA SI EL PROVEEDOR TIENE VALIDADO LOS REQUISITOS
    public function actionValidar_requisitos() {
        $modelo = new \app\models\ModelValidarRequisitos();
         if ($modelo->load(Yii::$app->request->post())) {
               if ($modelo->validate()){
                    if (isset($_POST["validar"])) {
                        $sqlConsulta = \app\models\ProveedorEstudios::find()->where(['=','nit_cedula', $modelo->documento])->andWhere(['=','aprobado', 1])->one(); 
                        if($sqlConsulta){
                            $empresa = \app\models\MatriculaEmpresa::findOne(1);
                            $archivo = \app\models\ProveedorEstudios::findOne($sqlConsulta->id_estudio);
                            $table = new Proveedor();
                            $table->id_tipo_documento = $archivo->id_tipo_documento;
                            $table->nit_cedula = $archivo->nit_cedula;
                            $table->dv = $archivo->dv;
                            $table->primer_nombre = $archivo->primer_nombre;
                            $table->segundo_nombre = $archivo->segundo_nombre;
                            $table->primer_apellido = $archivo->primer_apellido;
                            $table->segundo_apellido = $archivo->segundo_apellido;
                            $table->razon_social = $archivo->razon_social;
                            if ($archivo->id_tipo_documento == 1 || $archivo->id_tipo_documento == 2 ) {
                                $table->nombre_completo = strtoupper($archivo->primer_nombre . " " . $archivo->segundo_nombre . " " . $archivo->primer_apellido . " " . $archivo->segundo_apellido);
                                $table->razon_social = null;
                            } else {
                                 $table->nombre_completo = strtoupper($table->razon_social); 
                                 $table->primer_nombre = null;
                                 $table->segundo_nombre = null;
                                 $table->primer_apellido = null;
                                 $table->segundo_apellido = null;
                            }
                            $table->codigo_departamento = $empresa->codigo_departamento;
                            $table->codigo_municipio = $empresa->codigo_municipio;
                            $table->id_naturaleza = 1;
                            $table->tipo_regimen = 1;
                            $table->user_name = Yii::$app->user->identity->username;
                            $table->save(false);
                            return $this->redirect(['index']);
                        
                        }else{
                          Yii::$app->getSession()->setFlash('warning', 'Este proveedor NO tiene los requisitos validados ni aprobados en sistema. Consulte con el administrador');  
                          return $this->redirect(['index']);
                        }    
                    }
               } 
         }
        return $this->renderAjax('validar_requisitos', [
            'modelo' => $modelo,       
        ]);    
    }
      
    /**
     * Finds the Proveedor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Proveedor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Proveedor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionMunicipio($id) {
        $rows = Municipios::find()->where(['codigo_departamento' => $id])->all();

        echo "<option required>Seleccione...</option>";
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                echo "<option value='$row->codigo_municipio' required>$row->municipio</option>";
            }
        }
    }
    
    //PROCESO DE EXCEL
    
     public function actionExcelconsultaProveedor($tableexcel) {                
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
                               
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO DOCUMENTO')
                    ->setCellValue('C1', 'NIT/CEDULA')
                    ->setCellValue('D1', 'DV')
                    ->setCellValue('E1', 'PROVEEDOR')
                    ->setCellValue('F1', 'DIRECCION')
                    ->setCellValue('G1', 'CELULAR')
                    ->setCellValue('H1', 'TELEFONO')
                    ->setCellValue('I1', 'EMAIL')
                    ->setCellValue('J1', 'DEPARTAMENTO')
                    ->setCellValue('K1', 'MUNICIPIO')
                    ->setCellValue('L1', 'CONTACTO')
                    ->setCellValue('M1', 'CELULAR CONTACTO')
                    ->setCellValue('N1', 'TIPO REGIMEN')
                    ->setCellValue('O1', 'FORMA PAGO')
                    ->setCellValue('P1', 'PLAZO')
                    ->setCellValue('Q1', 'AUTORETENEDOR')
                    ->setCellValue('R1', 'NATURALEZA')
                    ->setCellValue('S1', 'TIPO SOCIEDAD')
                    ->setCellValue('T1', 'BANCO')
                    ->setCellValue('U1', 'TIPO CUENTA')
                    ->setCellValue('V1', 'PRODUCTO')
                    ->setCellValue('W1', 'TIPO TRANSACION')
                    ->setCellValue('X1', 'USER NAME')
                    ->setCellValue('Y1', 'FECHA CREACION')
                    ->setCellValue('Z1', 'OBSERVACION');
               
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_proveedor)
                    ->setCellValue('B' . $i, $val->tipoDocumento->tipo_documento)
                    ->setCellValue('C' . $i, $val->nit_cedula)
                    ->setCellValue('D' . $i, $val->dv)
                    ->setCellValue('E' . $i, $val->nombre_completo)
                    ->setCellValue('F' . $i, $val->direccion)
                    ->setCellValue('G' . $i, $val->celular)
                    ->setCellValue('H' . $i, $val->telefono)
                    ->setCellValue('I' . $i, $val->email)
                    ->setCellValue('J' . $i, $val->codigoDepartamento->departamento)
                    ->setCellValue('K' . $i, $val->codigoMunicipio->municipio)
                    ->setCellValue('L' . $i, $val->nombre_contacto)
                    ->setCellValue('M' . $i, $val->celular_contacto)
                    ->setCellValue('N' . $i, $val->tipoRegimen)
                    ->setCellValue('O' . $i, $val->formaPago)
                    ->setCellValue('P' . $i, $val->plazo)
                    ->setCellValue('Q' . $i, $val->autoretenedorVenta)
                    ->setCellValue('R' . $i, $val->naturaleza->naturaleza)
                    ->setCellValue('S' . $i, $val->tipoSociedad);
                    if($val->codigo_banco  == null){
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('T' . $i, 'REGISTRO NO ENCONTRADO');
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('T' . $i, $val->codigoBanco->entidad_bancaria);
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('U' . $i, $val->tipoCuenta)
                    ->setCellValue('V' . $i, $val->producto)
                    ->setCellValue('X' . $i, $val->tipoTransacion)
                    ->setCellValue('X' . $i, $val->user_name)
                    ->setCellValue('Y' . $i, $val->fecha_creacion)
                    ->setCellValue('Z' . $i, $val->observacion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Proveedores.xlsx"');
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
