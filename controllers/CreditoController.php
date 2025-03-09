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
use app\models\Empleados;
use app\models\TipoPagoCredito;
use app\models\UsuarioDetalle;
use app\models\Credito;
use app\models\FormConsultaCredito;
use app\models\FormCredito;
use app\models\Contratos;

/**
 * CreditoController implements the CRUD actions for Credito model.
 */
class CreditoController extends Controller
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
     * Lists all Credito models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',137])->all()){
                $form = new FormConsultaCredito();
                $id_empleado = null;
                $id_tipo_pago = null;
                $codigo_credito = null;
                $saldo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $id_empleado = Html::encode($form->id_empleado);
                        $id_tipo_pago = Html::encode($form->id_tipo_pago);
                        $codigo_credito = Html::encode($form->codigo_credito);
                        $saldo = Html::encode($form->saldo);
                        $table = Credito::find()
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])                                                                                              
                                ->andFilterWhere(['=', 'id_tipo_pago', $id_tipo_pago])
                                ->andFilterWhere(['=','codigo_credito', $codigo_credito]);
                        if ($saldo == 1){
                            $table = $table->andFilterWhere(['>', 'saldo_credito', $saldo]);
                        }    
                        $table = $table->orderBy('id_credito DESC');
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
                            if(isset($_POST['excel'])){                            
                                $check = isset($_REQUEST['id_credito DESC']);
                                $this->actionExcelconsultaCreditos($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = Credito::find()
                        ->orderBy('id_credito DESC');
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
                if(isset($_POST['excel'])){
                    //$table = $table->all();
                    $this->actionExcelconsultaCreditos($tableexcel);
                }
                if(isset($_POST['activar_periodo_registro'])){                            
                    if(isset($_REQUEST['id_credito'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_credito"] as $intCodigo) {
                            if ($_POST["id_credito"][$intIndice]) {                                
                                $id_credito = $_POST["id_credito"][$intIndice];
                                $this->ActivarPeriodoRegistro($id_credito);
                            }
                            $intIndice++;
                        }
                    }
                    $this->redirect(["credito/index"]);
                }
                if(isset($_POST['desactivar_periodo_registro'])){                            
                    if(isset($_REQUEST['id_credito'])){ 
                        $intIndice = 0;
                        foreach ($_POST["id_credito"] as $intCodigo) {
                            if ($_POST["id_credito"][$intIndice]) {                                
                                $id_credito = $_POST["id_credito"][$intIndice];
                                $this->DesactivarPeriodoRegistro($id_credito);
                            }
                            $intIndice++;
                        }
                    }
                   // $this->redirect(["credito/index"]);
                }
                if(isset($_POST['activar_periodo'])){                            
                    if(isset($_REQUEST['id_credito'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_credito"] as $intCodigo) {
                            if ($_POST["id_credito"][$intIndice]) {                                
                                $id_credito = $_POST["id_credito"][$intIndice];
                                $this->ActivarPeriodo($id_credito);
                            }
                            $intIndice++;
                        }
                    }
                    $this->redirect(["credito/index"]);
                }
                if(isset($_POST['desactivar_periodo'])){                            
                    if(isset($_REQUEST['id_credito'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_credito"] as $intCodigo) {
                            if ($_POST["id_credito"][$intIndice]) {                                
                                $id_credito = $_POST["id_credito"][$intIndice];
                                $this->DesactivarPeriodo($id_credito);
                            }
                            $intIndice++;
                        }
                    }
                    $this->redirect(["credito/index"]);
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
   
    //CONSULTA DE CREDITOS
   public function actionSearch_creditos($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',149])->all()){
                $form = new FormConsultaCredito();
                $id_empleado = null;
                $id_tipo_pago = null;
                $codigo_credito = null;
                $saldo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $id_empleado = Html::encode($form->id_empleado);
                        $id_tipo_pago = Html::encode($form->id_tipo_pago);
                        $codigo_credito = Html::encode($form->codigo_credito);
                        $saldo = Html::encode($form->saldo);
                        $table = Credito::find()
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])                                                                                              
                                ->andFilterWhere(['=', 'id_tipo_pago', $id_tipo_pago])
                                ->andFilterWhere(['=','codigo_credito', $codigo_credito]);
                        if ($saldo == 1){
                            $table = $table->andFilterWhere(['>', 'saldo_credito', $saldo]);
                        }    
                        $table = $table->orderBy('id_credito DESC');
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
                            if(isset($_POST['excel'])){                            
                                $check = isset($_REQUEST['id_credito DESC']);
                                $this->actionExcelconsultaCreditos($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = Credito::find()
                        ->orderBy('id_credito DESC');
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
                if(isset($_POST['excel'])){
                    //$table = $table->all();
                    $this->actionExcelconsultaCreditos($tableexcel);
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
   
    //CONSULTA DE ABONOS A CREDITOS
     public function actionSearch_abono_credito() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',138])->all()){
                $form = new FormConsultaCredito();
                $fecha_inicio = null;
                $fecha_corte = null;
                $codigo_credito = null;
                $numero_credito= null;
                $pages = null;
                $model = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $numero_credito = Html::encode($form->numero_credito);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $codigo_credito = Html::encode($form->codigo_credito);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = \app\models\ProgramacionNominaDetalle::find()
                                ->andFilterWhere(['=', 'id_credito', $numero_credito])                                                                                              
                                ->andFilterWhere(['between', 'fecha_hasta', $fecha_inicio, $fecha_corte])
                                ->andFilterWhere(['=','codigo_salario', $codigo_credito]);
                        $table = $table->orderBy('id_detalle DESC');
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
                                $check = isset($_REQUEST['id_detalle DESC']);
                                $this->actionExcelconsultaAbonos($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } 
            return $this->render('search_abono_credito', [
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
   
   //FUNCIONES PRIVADAS
   protected function ActivarPeriodoRegistro($id_credito) {        
        $aCredito = Credito::findOne($id_credito);
        $aCredito->estado_credito = 0;
        $aCredito->save(false);
    }
    protected function DesactivarPeriodoRegistro($id_credito) {        
        $aCredito = Credito::findOne($id_credito);
        $aCredito->estado_credito = 1;
        $aCredito->save(false);
    }  
    protected function ActivarPeriodo($id_credito) {        
        $aCredito = Credito::findOne($id_credito);
        $aCredito->estado_periodo = 0;
        $aCredito->save(false);
    }   
    protected function DesactivarPeriodo($id_credito) {        
        $aCredito = Credito::findOne($id_credito);
        $aCredito->estado_periodo = 1;
        $aCredito->save(false);
    }

    /**
     * Displays a single Credito model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id ,$token)
    {
      $abonos = \app\models\AbonoCredito::find()->where(['=','id_credito',$id])->orderBy('id_abono DESC')->all();
      $refinanciacion = \app\models\RefinanciarCredito::find()->where(['=','id_credito', $id])->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'abonos' => $abonos, 
            'refinanciacion'=> $refinanciacion,
            'id'=>$id,
            'token' => $token,
            
        ]);
    }
    
     ///REFINANCIAR CREDITO
    public function actionRefinanciar_credito($id_credito, $token)
    { 
        $model = new \app\models\RefinanciarCredito();
        $credito = Credito::find()->where(['=','id_credito', $id_credito])->one();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {           
            if ($model->validate()) {
                if ($credito){
                    $total = 0;
                    $table = new \app\models\RefinanciarCredito();
                    $table->id_credito = $id_credito;
                    $table->id_empleado= $credito->id_empleado;
                    $table->adicionar_valor = $model->adicionar_valor;
                    $table->nuevo_saldo = $credito->saldo_credito + $model->adicionar_valor;
                    $table->numero_cuotas = $model->numero_cuotas;
                    $table->numero_cuota_actual= $model->numero_cuota_actual;
                    $total = $table->nuevo_saldo / $model->numero_cuotas;
                    $table->valor_cuota = round($total);
                    $table->user_name= Yii::$app->user->identity->username;                    
                    $table->save(false);
                    $credito->saldo_credito = $table->nuevo_saldo;
                    $credito->numero_cuotas = $model->numero_cuotas;
                    $credito->numero_cuota_actual = $model->numero_cuota_actual;
                    $credito->valor_cuota = round($total);
                    $credito->save();
                    $this->redirect(["credito/view", 'id' => $id_credito, 'token' =>$token]);                    
                    
                }else{                
                    Yii::$app->getSession()->setFlash('error', 'El Número del credito no existe!');
                }
            }else{
                 $model->getErrors();
            }    
        }
        return $this->render('refinanciar_credito_empleado', [
            'model' => $model,
            'credito' => $credito,
            'token' =>$token,
        ]);
    }

    /**
     * Creates a new Credito model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FormCredito();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $empleado = Empleados::find()->all(); 
        if ($model->load(Yii::$app->request->post())) {           
            if ($model->validate()) {
                $contrato = Contratos::find()->where(['=','id_empleado', $model->id_empleado])->andWhere(['=','contrato_activo', 0])->one();
                if($empleado){
                     $fecha_ultimo_pago = strtotime($contrato->ultimo_pago_nomina);
                     $fecha_inicio = strtotime($model->fecha_inicio);
                    if ($fecha_ultimo_pago <= $fecha_inicio){
                        $table = new Credito();
                        $table->id_empleado = $model->id_empleado;
                        $table->codigo_credito = $model->codigo_credito;
                        $table->id_tipo_pago = $model->id_tipo_pago;
                        $table->valor_credito = $model->valor_credito;
                        $table->valor_cuota = $model->valor_cuota;
                        $table->numero_cuotas = $model->numero_cuotas;
                        $table->numero_cuota_actual = $model->numero_cuota_actual;
                        $table->validar_cuotas = $model->validar_cuotas;
                        $table->fecha_inicio = $model->fecha_inicio;
                        $table->seguro = $model->seguro;
                        $table->numero_libranza = $model->numero_libranza;
                        $table->saldo_credito = $model->valor_credito;
                        $table->aplicar_prima = $model->aplicar_prima;
                        $table->valor_aplicar = $model->valor_aplicar;
                        $table->observacion = $model->observacion;
                        $table->user_name = Yii::$app->user->identity->username; 
                        $table->id_grupo_pago = $contrato->id_grupo_pago;
                        $table->save(false);
                        return $this->redirect(["credito/index"]);  
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'La fecha de inicio del credito no puede ser menor que la ultima fecha de pago de la nómina o del contrato.');
                    }

                } else {
                    Yii::$app->getSession()->setFlash('error', 'No existe el documento del empleado.');    
                }
            }else{
            $model->getErrors();
            }
        } 
         return $this->render('_form', [
                 'model' => $model,
             ]);
    }

    /**
     * Updates an existing Credito model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new FormCredito();
       if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
      
        if ($model->load(Yii::$app->request->post())) {            
                $table = Credito::find()->where(['id_credito'=>$id])->one();
                if ($table){
                    $table->id_empleado = $model->id_empleado;
                    $table->codigo_credito = $model->codigo_credito;
                    $table->id_tipo_pago = $model->id_tipo_pago;
                    $table->valor_credito = $model->valor_credito;
                    $table->valor_cuota = $model->valor_cuota;
                    $table->numero_cuotas = $model->numero_cuotas;
                    $table->numero_cuota_actual = $model->numero_cuota_actual;
                    $table->validar_cuotas = $model->validar_cuotas;
                    $table->fecha_inicio = $model->fecha_inicio;
                    $table->seguro = $model->seguro;
                    $table->numero_libranza = $model->numero_libranza;
                    $table->saldo_credito = $model->valor_credito;
                    $table->aplicar_prima = $model->aplicar_prima;
                    $table->valor_aplicar = $model->valor_aplicar;
                    $table->observacion = $model->observacion;
                    $contrato = Contratos::find()->where(['=','id_empleado', $model->id_empleado])->andWhere(['=','contrato_activo', 0])->one();
                    $table->id_grupo_pago = $contrato->id_grupo_pago;
                    $table->save(false);
                     return $this->redirect(["credito/index"]);  
                }
        }
        if (Yii::$app->request->get("id")) {
            $table = credito::find()->where(['id_credito' => $id])->one();            
            if ($table) {     
               $model->id_empleado = $table->id_empleado;
               $model->codigo_credito = $table->codigo_credito;
               $model->id_tipo_pago = $table->id_tipo_pago;
               $model->valor_credito = $table->valor_credito;
               $model->valor_cuota = $table->valor_cuota;
               $model->numero_cuotas = $table->numero_cuotas;
               $model->numero_cuota_actual =  $table->numero_cuota_actual;
               $model->validar_cuotas =  $table->validar_cuotas;
               $model->fecha_inicio =  $table->fecha_inicio;
               $model->seguro =  $table->seguro;
               $model->numero_libranza =  $table->numero_libranza;
               $model->saldo_credito =  $table->saldo_credito;
               $model->aplicar_prima =  $table->aplicar_prima;
               $model->valor_aplicar =  $table->valor_aplicar;
           }else{
                return $this->redirect(['index']);
           }
        } else {
                return $this->redirect(['index']);    
        }
        return $this->render('update', [
            'model' => $model,
            'id'=>$id, 
        ]);
    }

    //CREAR LOS ABONOS A LOS CREDITOS
     public function actionNuevoabono($id_credito, $token)
    { 
        $model = new \app\models\FormAbonoCredito();
        $credito = Credito::find()->where(['=','id_credito', $id_credito])->one();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {           
            if ($model->validate()) {
                $nro = ($credito->numero_cuotas - $credito->numero_cuota_actual);
                $saldoC = $credito->saldo_credito - $model->valor_abono;
               if ($credito){
                   if ($model->valor_abono > $credito->saldo_credito){
                        Yii::$app->getSession()->setFlash('error', 'El valor del abono no puede ser mayor al saldo');
                    }else{
                        $table = new \app\models\AbonoCredito();
                        $table->id_credito = $id_credito;
                        $table->id_tipo_pago = $model->id_tipo_pago;
                        $table->valor_abono = $model->valor_abono;
                        $table->saldo = $saldoC;
                        $table->cuota_pendiente = ($nro - 1);
                        $credito->saldo_credito = $saldoC;
                        $table->observacion = $model->observacion;
                        $table->user_name = Yii::$app->user->identity->username; 
                        $table->fecha_abono = $model->fecha_abono;
                        $table->insert();
                        $credito_total = Credito::findOne($id_credito);
                        $credito_total->saldo_credito = $saldoC;
                        $credito_total->numero_cuota_actual = ($credito_total->numero_cuota_actual + 1);
                        $credito_total->update();
                        $this->redirect(["credito/view", 'id' => $id_credito, 'token' =>$token]);                    
                    }
                }else{                
                    Yii::$app->getSession()->setFlash('error', 'El Número del credito no existe!');
                }
            }else{
                 $model->getErrors();
            }    
        }
        return $this->render('_formabono', [
            'model' => $model,
            'credito' => $credito,
            'id_credito' =>$id_credito,
            'token' => $token,
        ]);
    }
    
    /**
     * Deletes an existing Credito model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEliminar($id) {
        
        if (Yii::$app->request->post()) {
            $credito = Credito::findOne($id);
            if ((int) $id) {
                try {
                    Credito::deleteAll("id_credito=:id_credito", [":id_credito" => $id]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    $this->redirect(["credito/index"]);
                } catch (IntegrityException $e) {
                    $this->redirect(["credito/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el credito Nro :' .$credito->id_credito .', tiene registros asociados en otros procesos');
                } catch (\Exception $e) {

                    $this->redirect(["credito/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el credito Nro: ' . $credito->id_credito . ', tiene registros asociados en otros procesos');
                }
            } else {
                // echo "Ha ocurrido un error al eliminar el registros, redireccionando ...";
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("credito/index") . "'>";
            }
        } else {
            return $this->redirect(["credito/index"]);
        }
    }

    /**
     * Finds the Credito model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Credito the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Credito::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //EXCEL QUE PERMITE ESPORTAR LOS CREDITOS
    public function actionExcelconsultaCreditos($tableexcel) {                
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
         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NRO CREDITO')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'TIPO CREDITO')
                    ->setCellValue('E1', 'TIPO DEDUCCION') 
                    ->setCellValue('F1', 'VR. CREDITO')
                    ->setCellValue('G1', 'VR. SALDO')
                    ->setCellValue('H1', 'VR. CUOTA')                    
                    ->setCellValue('I1', 'NRO DE CUOTAS')
                    ->setCellValue('J1', 'CUOTA ACTUAL')
                    ->setCellValue('K1', 'VALIDAR CUOTA')
                    ->setCellValue('L1', 'FECHA INICIO')
                    ->setCellValue('M1', 'USUARIO')
                    ->setCellValue('N1', 'OBSERVACION');
                   
        $i = 2  ;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_credito)
                    ->setCellValue('B' . $i, $val->empleado->nit_cedula)
                    ->setCellValue('C' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('D' . $i, $val->codigoCredito->nombre_credito)
                    ->setCellValue('E' . $i, $val->tipoPago->descripcion)
                    ->setCellValue('F' . $i, $val->valor_credito)
                    ->setCellValue('G' . $i, $val->saldo_credito)                    
                    ->setCellValue('H' . $i, $val->valor_cuota)
                    ->setCellValue('I' . $i, $val->numero_cuotas)
                    ->setCellValue('J' . $i, $val->numero_cuota_actual)
                    ->setCellValue('K' . $i, $val->validarcuota)
                    ->setCellValue('L' . $i, $val->fecha_inicio)
                    ->setCellValue('M' . $i, $val->user_name)
                    ->setCellValue('N' . $i, $val->observacion);
                  
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Creditos.xlsx"');
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
    
    //EXCEL QUE EXPORTA LOS ABONOS A CREDITOS
       public function actionExcelconsultaAbonos($tableexcel) {                
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
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'DOCUMENTO')
                    ->setCellValue('B1', 'EMPLEADO')
                    ->setCellValue('C1', 'TIPO DE CREDITO')
                    ->setCellValue('D1', 'FECHA DE CORTE')
                    ->setCellValue('E1', 'VR. ABONO');
        $i = 2  ;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->programacionNomina->cedula_empleado)
                    ->setCellValue('B' . $i, $val->programacionNomina->empleado->nombrecorto)
                    ->setCellValue('C' . $i, $val->codigoSalario->nombre_concepto)
                    ->setCellValue('D' . $i, $val->fecha_hasta)
                    ->setCellValue('E' . $i, $val->vlr_deduccion);                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Abonos_credito.xlsx"');
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
