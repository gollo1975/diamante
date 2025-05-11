<?php

namespace app\controllers;

use Yii;
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



//models
use app\models\Contratos;
use app\models\ContratosSearch;
use app\models\UsuarioDetalle;


/**
 * ContratosController implements the CRUD actions for Contratos model.
 */
class ContratosController extends Controller
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
     * Lists all Contratos models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',126])->all()){
                $form = new \app\models\FiltroContratos();
                $tipo_contrato = null;
                $empleado = null;
                $estado = null;
                $grupo_pago = null;
                $desde = null;
                $hasta = null;
                $eps = null;
                $pension = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $tipo_contrato = Html::encode($form->tipo_contrato);
                        $empleado = Html::encode($form->empleado);
                        $estado = Html::encode($form->estado);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $grupo_pago = Html::encode($form->grupo_pago);
                        $eps = Html::encode($form->eps);
                        $pension = Html::encode($form->pension);
                        $table = Contratos::find()
                                ->andFilterWhere(['=', 'id_tipo_contrato', $tipo_contrato])
                                ->andFilterWhere(['=', 'id_empleado', $empleado])
                                ->andFilterWhere(['between', 'fecha_inicio', $desde, $desde])
                                ->andFilterWhere(['=', 'id_grupo_pago', $grupo_pago])
                                ->andFilterWhere(['=', 'contrato_activo', $estado])
                                ->andFilterWhere(['=', 'id_entidad_salud', $eps])
                                ->andFilterWhere(['=', 'id_entidad_pension', $pension]);
                        $table = $table->orderBy('id_contrato DESC');
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
                            $this->actionExcelContratos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                }else{
                  
                    $table = Contratos::find()->orderBy('id_contrato DESC');
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
                            $this->actionExcelContratos($tableexcel);
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

    //PARAMETROS DEL CONTRATO
    public function actionParametro_contrato()
    {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',127])->all()){
               $form = new \app\models\FormFiltroContrato();
                $identificacion = null;
                $id_grupo_pago = null;
                $id_empleado = null;
                $id_tiempo = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $identificacion = Html::encode($form->identificacion);
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $id_empleado = Html::encode($form->id_empleado);
                        $id_tiempo = Html::encode($form->id_tiempo);
                        $table = Contratos::find()
                                ->andFilterWhere(['like', 'nit_cedula', $identificacion])
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])
                                ->andFilterWhere(['=', 'id_tiempo', $id_tiempo])
                                ->andFilterWhere(['=', 'contrato_activo', 0])
                                ->orderBy('id_contrato desc');
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
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = Contratos::find()
                            ->where(['=','contrato_activo', 0])
                            ->orderBy('id_contrato desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }
                $to = $count->count();
                return $this->render('parametrocontrato', [
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
     * Displays a single Contratos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
       $cambio_salario = \app\models\CambioSalario::find()->where(['=','id_contrato', $id])->orderBy('id_cambio_salario DESC')->all();
       $adicion_salario = \app\models\PagoAdicionSalario::find()->where(['=','id_contrato', $id])->orderBy('id_pago_adicion DESC')->all();
       $prorrogas = \app\models\ProrrogaContrato::find()->where(['=','id_contrato', $id])->orderBy('id_prorroga_contrato DESC')->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'cambio_salario' => $cambio_salario,
            'adicion_salario' => $adicion_salario,
            'prorrogas' => $prorrogas,
        ]);
    }
    
    public function actionViewparameters($id)
    {
    $cambioeps = \app\models\CambioEps::find()->where(['=','id_contrato', $id])->orderBy('id_cambio DESC')->all();  
    $cambiopension = \app\models\CambioPension::find()->where(['=','id_contrato', $id])->orderBy('id_cambio DESC')->all();
       if(Yii::$app->request->post())
        {
            $intIndice = 0;
            if (isset($_POST["seleccion"])) {
                foreach ($_POST["seleccion"] as $intCodigo)
                {
                   // $abono = Credito::findOne($intCodigo);                    
                    //if(CambioSalario::deleteAll("id_cambio_salario=:id_cambio_salario", [":id_cambio_salario" => $intCodigo]))
                    //{                        
                    //} 
                }
                 return $this->redirect(['contrato/viewParameters', 'id' => $id]);
            }
        }

        return $this->render('viewParameters', [
            'model' => $this->findModel($id),
            'id' => $id,
            'cambioeps' => $cambioeps,
            'cambiopension' => $cambiopension,
            ]);
    }

    /**
     * Creates a new Contratos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_empleado )
    {
        $model = new Contratos();
        $sw = 0;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())){
            $empleado = \app\models\Empleados::findOne($id_empleado);
            $grupo = \app\models\GrupoPago::findOne($model->id_grupo_pago);
            $tipoContrato = \app\models\TipoContrato::findOne($model->id_tipo_contrato);
            if($model->salario > $grupo->limite_devengado){
                Yii::$app->getSession()->setFlash('error', 'Error de salario: El salario del empleado es mayor al permitido en este GRUPO DE PAGO, favor consulte con el administrador');
            }else{
                if($tipoContrato->prefijo == 'CAI'){
                    $model->fecha_final = '2099-12-30';
                }     
                if($model->fecha_final == ''){
                    Yii::$app->getSession()->setFlash('warning', 'Debe digitar la fecha final del contrato de trabajo.');
                }else{
                    $fecha_inicio_contrato = strtotime($model->fecha_inicio);
                    $fecha_ultima_nomina = strtotime($grupo->ultimo_pago_nomina);
                    if($fecha_inicio_contrato < $fecha_ultima_nomina){
                        Yii::$app->getSession()->setFlash('error', 'Error de fechas: La fecha de ingreso de este empleado es menor a la ultima fecha ('.$grupo->ultimo_pago_nomina.') de pago de la nomina. Favor revisar la fecha de inicio de contrato!');
                    }else{
                        if($tipoContrato->prefijo == 'CAI'){
                           $model->fecha_final = '2099-12-30';
                        }else{
                            $empleado->fecha_retiro = $model->fecha_final;
                            if($tipoContrato->prefijo == 'CF'){
                                $model->fecha_final = $model->fecha_final;
                            }else{
                                $model->fecha_final = $model->fecha_final; 
                            }    
                        }
                        if($tipoContrato->prorroga == 1){
                            $total_dias = strtotime($model->fecha_final ) - strtotime($model->fecha_inicio);
                            $model->dias_contrato = round($total_dias / 86400)+1; 
                            // formula que resta 31 dias
                            $fecha = date($model->fecha_final);
                            $date_dato = strtotime('-31 day', strtotime($fecha));
                            $date_dato = date('Y-m-d', $date_dato);
                            $model->fecha_preaviso = $date_dato;
                            
                        } 
                        $model->id_empleado = $id_empleado;
                        $model->nit_cedula = $empleado->nit_cedula;
                        $model->save();
                        $model->ultimo_pago_nomina = $grupo->ultimo_pago_nomina;
                        $model->ultima_pago_prima = $grupo->ultimo_pago_prima;
                        $model->ultima_pago_cesantia = $grupo->ultimo_pago_cesantia;
                        $model->ultima_pago_vacacion = $model->fecha_inicio;
                        $model->user_name = Yii::$app->user->identity->username;
                        $model->save();
                        $empleado->fecha_ingreso = $model->fecha_inicio;
                        $empleado->estado = 0;
                        $empleado->save();
                        return $this->redirect(['index']);
                    }    
                }
            }    
        }

        return $this->render('create', [
            'model' => $model,
            'sw' => $sw,
        ]);
    }

    /**
     * Updates an existing Contratos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $sw)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }     
        if ($model->load(Yii::$app->request->post())) {
            if($sw == 0){
                $empleado = \app\models\Empleados::findOne($id_empleado);
            }else{
                $empleado = \app\models\Empleados::findOne($model->id_empleado);
            }
            
            $grupo = \app\models\GrupoPago::findOne($model->id_grupo_pago);
            $tipoContrato = \app\models\TipoContrato::findOne($model->id_tipo_contrato);
            if($model->salario > $grupo->limite_devengado){
                Yii::$app->getSession()->setFlash('error', 'Error de salario: El salario del empleado es mayor al permitido en este GRUPO DE PAGO, favor consulte con el administrador');
                return $this->redirect(['update', 'model' => $model, 'sw' => $sw, 'id' => $id]);
            }else{
                if($tipoContrato->prefijo == 'CAI'){
                    $model->fecha_final = '2099-12-30';
                }     
                if($model->fecha_final == ''){
                    Yii::$app->getSession()->setFlash('warning', 'Debe digitar la fecha final del contrato de trabajo.');
                    return $this->redirect(['update', 'model' => $model, 'sw' => $sw, 'id' => $id]);
                }else{
                    $fecha_inicio_contrato = strtotime($model->fecha_inicio);
                    $fecha_ultima_nomina = strtotime($grupo->ultimo_pago_nomina);
                    if($fecha_inicio_contrato < $fecha_ultima_nomina){
                        Yii::$app->getSession()->setFlash('error', 'Error de fechas: La fecha de ingreso de este empleado es menor a la ultima fecha ('.$grupo->ultimo_pago_nomina.') de pago de la nomina. Favor revisar la fecha de inicio de contrato!');
                        return $this->redirect(['update', 'model' => $model, 'sw' => $sw, 'id' => $id]);
                    }else{
                        if($tipoContrato->prefijo == 'CAI'){
                           $model->fecha_final = '2099-12-30';
                        }else{
                            $empleado->fecha_retiro = $model->fecha_final;
                            if($tipoContrato->prefijo == 'CF'){
                                $model->fecha_final = $model->fecha_final;
                            }else{
                                $model->fecha_final = $model->fecha_final; 
                            }    
                        }
                        if($tipoContrato->prorroga == 1){
                            $total_dias = strtotime($model->fecha_final ) - strtotime($model->fecha_inicio);
                            $model->dias_contrato = round($total_dias / 86400)+1; 
                            // formula que resta 31 dias
                            $fecha = date($model->fecha_final);
                            $date_dato = strtotime('-31 day', strtotime($fecha));
                            $date_dato = date('Y-m-d', $date_dato);
                            $model->fecha_preaviso = $date_dato;
                        }else{
                            $model->dias_contrato = 0;
                            $model->fecha_preaviso = '';
                        } 
                        $model->ultimo_pago_nomina = $grupo->ultimo_pago_nomina;
                        $model->ultima_pago_prima = $grupo->ultimo_pago_prima;
                        $model->ultima_pago_cesantia = $grupo->ultimo_pago_cesantia;
                        $model->ultima_pago_vacacion = $model->fecha_inicio;
                        $model->user_name_editado = Yii::$app->user->identity->username;
                        $model->save();
                        $empleado->fecha_ingreso = $model->fecha_inicio;
                        $empleado->fecha_retiro = $model->fecha_final;
                        $empleado->estado = 0;
                        $empleado->save(false);
                        return $this->redirect(['index']);
                    }    
                }
            }    
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'sw' => $sw,
        ]);
    }

    //CAMBIO DE SALARIO
    public function actionNuevo_cambio_salario($id, $token)
    { 
        $model = new \app\models\CambioSalario();
        $contrato = Contratos::find()->where(['=','id_contrato',$id])->one();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {           
            if ($model->validate()) {
               if ($contrato){
                   $fecha_nomina = strtotime(date($contrato->ultimo_pago_nomina, time()));
                   $fecha_aplicacion = strtotime($model->fecha_aplicacion);
                   if ($fecha_aplicacion > $fecha_nomina) {
                        $table = new \app\models\CambioSalario();
                        $table->nuevo_salario = $model->nuevo_salario;
                        $table->fecha_aplicacion = $model->fecha_aplicacion;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->id_contrato = $id;
                        $table->observacion = $model->observacion;
                        $table->id_formato_contenido = $model->id_formato_contenido;
                        $table->save();
                        $contrato->salario = $table->nuevo_salario;
                        $contrato->save();
                        $this->redirect(["contratos/view", 'id' => $id, 'token' => $token]);  
                    } else {
                       try {
                            Yii::$app->getSession()->setFlash('error', 'No se puede cambiar el salario, la fecha de aplicacion es menor al ultimo periodo de pago de la nómina (' . $contrato->ultimo_pago_nomina . ').');
                        } catch (Exception $ex) {
                            Yii::$app->getSession()->setFlash('error', 'No se puede cambiar el salario, la fecha de aplicacion es menor al periodo de pago de la nómina (' . $contrato->ultimo_pago_nomina . ').');
                        }
                    }
                }else{                
                    Yii::$app->getSession()->setFlash('error', 'El Número del contrato no existe!');
                }
            }else{
                 $model->getErrors();
            }    
        }
       return $this->render('_formnuevocambiosalario', [
            'model' => $model,
            'contrato' => $contrato,
            'id' => $id,
           'token' => $token,
         
        ]);
    }

    //ADICION AL SALARIO
     public function actionNueva_adicion_contrato($id, $token)
     { 
        $modeloadicion = new \app\models\PagoAdicionSalario();
        $contrato = Contratos::find()->where(['=','id_contrato',$id])->one();

        if ($modeloadicion->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modeloadicion);
        }
        if ($modeloadicion->load(Yii::$app->request->post())) {           
            if ($modeloadicion->validate()) {
               if ($contrato){
                    $table = new \app\models\PagoAdicionSalario();
                    $table->id_contrato = $id;
                    $table->id_formato_contenido = $modeloadicion->id_formato_contenido; 
                    $table->valor_adicion = $modeloadicion->valor_adicion; 
                    $table->fecha_aplicacion = $modeloadicion->fecha_aplicacion; 
                    $table->user_name = Yii::$app->user->identity->username; 
                    $table->codigo_salario = $modeloadicion->codigo_salario; 
                    $table->insert(false);
                    $this->redirect(["contratos/view", 'id' => $id, 'token' => $token]);  
                }else{                
                    Yii::$app->getSession()->setFlash('error', 'El Número del contrato no existe!');
                }
            }else{
                 $modeloadicion->getErrors();
            }    
        }
            return $this->render('_formnuevaadicionsalario', [
                'modeloadicion' => $modeloadicion,
                'contrato' => $contrato,
                'id' => $id,
                'token' => $token,
            ]);
        
    }
    
    //PERMI MODIFICAR LA ADICION
    public function actionEditar_pago_adicion($id_pago_adicion, $id, $token)
    {
       $modeloadicion = new \app\models\PagoAdicionSalario();
       if ($modeloadicion->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modeloadicion);
        }
      
        if ($modeloadicion->load(Yii::$app->request->post())) {            
            $table = \app\models\PagoAdicionSalario::find()->where(['id_pago_adicion'=>$id_pago_adicion])->one();
            $table->id_formato_contenido = $modeloadicion->id_formato_contenido;
            $table->valor_adicion = $modeloadicion->valor_adicion;
            $table->fecha_aplicacion = $modeloadicion->fecha_aplicacion;
            $table->codigo_salario = $modeloadicion->codigo_salario;
            $table->save(false);
            return $this->redirect(["contratos/view",'id' => $id, 'token' => $token]);  

        }
        if (Yii::$app->request->get('id_pago_adicion')) {
           $table = \app\models\PagoAdicionSalario::find()->where(['id_pago_adicion'=>$id_pago_adicion])->one();           
            if ($table) {                                
                $modeloadicion->id_formato_contenido = $table->id_formato_contenido;
                $modeloadicion->valor_adicion = $table->valor_adicion;
                $modeloadicion->fecha_aplicacion = $table->fecha_aplicacion;
                 $modeloadicion->codigo_salario = $table->codigo_salario;
            } 
        }
    
        return $this->render('_formnuevaadicionsalario', [
                'modeloadicion' => $modeloadicion,
                'id_pago_adicion' => $id_pago_adicion,
                'id' => $id,
                'token' => $token,
            ]);
    }
    
    //PERMITE CREAR LA PRORROGA AL CONTRATO
     public function actionNueva_prorroga($id, $token)
     { 
        $modeloprorroga = new \app\models\ProrrogaContrato();

        $contrato = Contratos::find()->where(['=','id_contrato',$id])->one();
        if ($modeloprorroga->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modeloprorroga);
        }
        if ($modeloprorroga->load(Yii::$app->request->post())) {           
            if ($modeloprorroga->validate()) {
               if ($contrato){
                  
                    $table = new \app\models\ProrrogaContrato();
                    $table->id_contrato = $id;
                    $table->id_formato_contenido = $modeloprorroga->id_formato_contenido;
                    $table->fecha_desde = $modeloprorroga->fecha_nueva_renovacion; 
                    $dias = $contrato->dias_contrato;
                    //codigo que cargas los dias
                    $fecha = date($table->fecha_desde);
                    $date_hasta = strtotime( '+'.($dias).' day', strtotime($fecha)-1);
                    $date_hasta = date('Y-m-d', $date_hasta);
                    //fin
                    $table->fecha_hasta = $date_hasta;
                    $table->fecha_ultima_contrato = $modeloprorroga->fecha_ultima_contrato;
                    //codigo
                    $fecha = date($table->fecha_ultima_contrato);
                    $date_dato = strtotime('+1 day', strtotime($fecha));
                    $date_dato = date('Y-m-d', $date_dato);
                    // fin codigo
                    $table->fecha_nueva_renovacion = $date_dato;
                    //codigo
                    $fecha = date($table->fecha_hasta);
                    $fecha_preaviso = strtotime('-31 day', strtotime($fecha));
                    $fecha_preaviso = date('Y-m-d', $fecha_preaviso);
                    //fin codigo
                    $table->fecha_preaviso = $fecha_preaviso;
                    $table->dias_preaviso = 30;
                    $table->dias_contratados = $dias;
                    $table->user_name = Yii::$app->user->identity->username; 
                    $table->insert(false);
                    $contrato->fecha_final = $table->fecha_hasta;
                    $contrato->fecha_preaviso =  $table->fecha_preaviso;
                    $contrato->update();
                    $this->redirect(["contratos/view", 'id' => $id, 'token' => $token]);  
                }else{                
                    Yii::$app->getSession()->setFlash('error', 'El Número del contrato no existe!');
                }
            }else{
                 $modeloprorroga->getErrors();
            }    
        }
         if (Yii::$app->request->get("id")) {
            $table = Contratos::find()->where(['id_contrato' => $id])->one();            
            if ($table) {                                
                $modeloprorroga->fecha_desde = $table->fecha_inicio;
                $modeloprorroga->fecha_ultima_contrato = $table->fecha_final;
                // formula que resta 31 dias
                $fecha = date($table->fecha_final);
                $date_dato = strtotime('+1 day', strtotime($fecha));
                $date_dato = date('Y-m-d', $date_dato);
                $modeloprorroga->fecha_nueva_renovacion = $date_dato;
               
            } else {
                return $this->redirect(["contratos/view", 'id' => $id, 'token' => $token]);
            }
        } else {
            return $this->redirect(["contratos/view", 'id' => $id, 'token' => $token]);
        }
        $prorroga_contrato = \app\models\ProrrogaContrato::find()->where(['=','id_contrato', $id])->orderBy('id_prorroga_contrato DESC')->all();
        $tipo = \app\models\TipoContrato::find()->where(['=','id_tipo_contrato', $table->id_tipo_contrato])->one();
        $contador = count($prorroga_contrato);
        if(($contador < $tipo->numero_prorrogas) && ($tipo->id_tipo_contrato == $table->id_tipo_contrato)){
            return $this->render('_formnuevaprorroga', [
                'modeloprorroga' => $modeloprorroga,
                'contrato' => $contrato,
                'id' => $id,
                'token' => $token,
            ]);
        }else{
            $this->redirect(["contratos/view", 'id' => $id]);  
        }    
    }
    
    //PROCESO QUE GENERA LA PRORROGA A UN AÑO
    
     public function actionNueva_prorroga_ano($id, $token)
     { 
        $modeloprorroga = new \app\models\ProrrogaContrato();

        $contrato = Contratos::find()->where(['=','id_contrato',$id])->one();
        if ($modeloprorroga->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modeloprorroga);
        }
        if ($modeloprorroga->load(Yii::$app->request->post())) {           
            if ($modeloprorroga->validate()) {
               if ($contrato){
                  
                        $table = new \app\models\ProrrogaContrato();
                        $table->id_contrato = $id;
                        $table->id_formato_contenido = $modeloprorroga->id_formato_contenido;
                        $table->fecha_desde = $modeloprorroga->fecha_nueva_renovacion; 
                        //codigo
                        $fecha = date($table->fecha_desde);
                        $date_hasta = strtotime( '+365 day', strtotime($fecha)-1);
                        $date_hasta = date('Y-m-d', $date_hasta);
                        //fin
                        $table->fecha_hasta = $date_hasta;
                        $table->fecha_ultima_contrato = $modeloprorroga->fecha_ultima_contrato;
                        //codigi
                        $fecha = date($table->fecha_ultima_contrato);
                        $date_dato = strtotime('+1 day', strtotime($fecha));
                        $date_dato = date('Y-m-d', $date_dato);
                        // fin codigo
                        $table->fecha_nueva_renovacion = $date_dato;
                        //codigo
                        $fecha = date($table->fecha_hasta);
                        $fecha_preaviso = strtotime('-31 day', strtotime($fecha));
                        $fecha_preaviso = date('Y-m-d', $fecha_preaviso);
                        //fin codigo
                        $table->fecha_preaviso = $fecha_preaviso;
                        $table->dias_preaviso = 30;
                        $table->dias_contratados = 365;
                        $table->user_name = Yii::$app->user->identity->username; 
                        $table->insert(false);
                        $contrato->fecha_final = $table->fecha_hasta;
                        $contrato->fecha_preaviso =  $table->fecha_preaviso;
                        $contrato->dias_contrato = 365;
                        $contrato->update();
                        $this->redirect(["contratos/view", 'id' => $id, 'token' => $token]);  
                }else{                
                    Yii::$app->getSession()->setFlash('error', 'El Número del contrato no existe!');
                }
            }else{
                 $modeloprorroga->getErrors();
            }    
        }
         if (Yii::$app->request->get("id")) {
            $table = Contratos::find()->where(['id_contrato' => $id])->one();            
            if ($table) {                                
                $modeloprorroga->fecha_desde = $table->fecha_inicio;
                $modeloprorroga->fecha_ultima_contrato = $table->fecha_final;
                // formula que resta 31 dias
                $fecha = date($table->fecha_final);
                $date_dato = strtotime('+1 day', strtotime($fecha));
                $date_dato = date('Y-m-d', $date_dato);
                $modeloprorroga->fecha_nueva_renovacion = $date_dato;
               
            } else {
                return $this->redirect(["contratos/view", 'id' => $id, 'token' => $token]);
            }
        } else {
            return $this->redirect(["contratos/view", 'id' => $id, 'token' => $token]);
        }
       
            return $this->render('_formnuevaprorroga', [
                'modeloprorroga' => $modeloprorroga,
                'contrato' => $contrato,
                'id' => $id,
                'token' => $token,
            ]);
        
    }
    
   //parameter del contrato, permite subir los devengados
    public function actionAcumulado_devengado($id) {                
        $model = new \app\models\FormParametroContrato();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {            
            if ($model->validate()) {
                $archivo = Contratos::findOne($id);
                if (isset($_POST["actualizar"])) { 
                    $archivo->ibp_prima_inicial = $model->ibp_prima_inicial;
                    $archivo->ibp_cesantia_inicial = $model->ibp_cesantia_inicial;
                    $archivo->ibp_recargo_nocturno = $model->ibp_recargo_nocturno;
                    $archivo->ultima_pago_prima = $model->ultima_prima;
                    $archivo->ultima_pago_cesantia = $model->ultima_cesantia;
                    $archivo->ultima_pago_vacacion = $model->ultima_vacacion;
                    $archivo->ultimo_pago_nomina = $model->ultimo_pago;
                    $archivo->user_name_editado = Yii::$app->user->identity->username; 
                    $archivo->save(false);
                    $this->redirect(["contratos/viewparameters", 'id' => $id]);                                                     
                }
            }
        }
        if (Yii::$app->request->get("id")) {
            $table = Contratos::find()->where(['id_contrato' => $id])->one();            
            if ($table) {                                
                $model->id_contrato = $table->id_contrato;                
                $model->fecha_inicio = $table->fecha_inicio; 
                $model->fecha_final = $table->fecha_final;
                $model->ultima_prima = $table->ultima_pago_prima;
                $model->ultima_cesantia = $table->ultima_pago_cesantia;
                $model->ultima_vacacion = $table->ultima_pago_vacacion;
                $model->ultimo_pago = $table->ultimo_pago_nomina;
                $model->ibp_cesantia_inicial = $table->ibp_cesantia_inicial;
                $model->ibp_prima_inicial = $table->ibp_prima_inicial;
                $model->ibp_recargo_nocturno = $table->ibp_recargo_nocturno;
                
            }
            
        }
        
        return $this->renderAjax('_acumulardevengado', ['model' => $model, 'id' => $id]);
    }
    
    //PERMITE VER DEL DETALLE DEL CONTRATO
    public function actionDetalle_contrato($id_contrato) {
       $modelo = Contratos::findOne($id_contrato);
       return $this->renderAjax('_detalle_contrato', ['modelo' => $modelo, 'id_contrato' => $id_contrato]);
    }
    
     //PERMITE CAMBIAR DE EPS EN E CONTRATO
    public function actionCambioeps($id)
     { 
        $model = new \app\models\CambioEps();
        $contrato = Contratos::find()->where(['=','id_contrato',$id])->one();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) { 
               if ($contrato){
                   if($contrato->id_entidad_salud ==  $model->id_entidad_salud_nueva){
                         try{
                            Yii::$app->getSession()->setFlash('error', 'Debes de seleccionar una nueva eps para el cambio.!');
                         } catch (Exception $ex) {
                            Yii::$app->getSession()->setFlash('error', 'Debes de seleccionar una nueva eps para el cambio!');
                         }
                   }else{      
                        $table = new \app\models\CambioEps();
                        $table->id_contrato = $id;
                        $table->id_entidad_salud_anterior = $contrato->id_entidad_salud; 
                        $table->id_entidad_salud_nueva = $model->id_entidad_salud_nueva;
                        $table->user_name = Yii::$app->user->identity->username; 
                        $table->observacion = $model->observacion;
                        $table->fecha_cambio = date('Y-m-d');
                        $table->save(false);
                        $contrato->id_entidad_salud = $table->id_entidad_salud_nueva;
                        $contrato->save();
                        $this->redirect(["contratos/viewparameters", 'id' => $id]);    
                   }     
                }else{                
                    Yii::$app->getSession()->setFlash('error', 'El Número del contrato no existe!');
                }
            
        }else{
           $model->id_entidad_salud_anterior =  $contrato->id_entidad_salud;
           $model->id_contrato =  $id;
        }
       return $this->render('_formcambioeps', [
            'model' => $model,
            'contrato' => $contrato,
            'id' => $id,
         
        ]);
    }
    
    public function actionCambiopension($id)
     { 
        $model = new \app\models\CambioPension();
        $contrato = Contratos::find()->where(['=','id_contrato',$id])->one();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {    
           
            if ($model->validate()) {
               if ($contrato){
                   if($contrato->id_entidad_pension ==  $model->id_entidad_pension_nueva){
                         try{
                            Yii::$app->getSession()->setFlash('error', 'Debes de seleccionar una nueva entidad de pension para el cambio!');
                         } catch (Exception $ex) {
                            Yii::$app->getSession()->setFlash('error', 'Debes de seleccionar una nueva entidad de pension para el cambioo!');
                         }
                   }else{      
                        $table = new \app\models\CambioPension();
                        $table->id_contrato = $id;
                        $table->id_entidad_pension_anterior = $contrato->id_entidad_pension; 
                        $table->id_entidad_pension_nueva = $model->id_entidad_pension_nueva;
                        $table->user_name = Yii::$app->user->identity->username; 
                        $table->observacion = $model->observacion;
                        $table->fecha_cambio = date('Y-m-d');
                        $table->insert();
                        $contrato->id_entidad_pension = $table->id_entidad_pension_nueva;
                        $contrato->update();
                        $this->redirect(["contratos/viewparameters", 'id' => $id]);    
                   }     
                }else{                
                    Yii::$app->getSession()->setFlash('error', 'El Número del contrato no existe!');
                }
            }else{
                 $model->getErrors();
            }    
        }else{
           $model->id_entidad_pension_anterior =  $contrato->id_entidad_pension;
           $model->id_contrato =  $id;
        }
       return $this->render('_formcambiopension', [
            'model' => $model,
            'contrato' => $contrato,
            'id' => $id,
         
        ]);
    }
    
    //CERRAR CONTRATO Y GENERA PRESTACIONES
    public function actionCerrar_contrato_trabajo($id, $token) {
        $model = new \app\models\FormCerrarContrato();
       
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {    
           
            if ($model->validate()) {
                $contrato = Contratos::findOne($id);
                if (isset($_POST["cerrar_contrato"])) {  
                    $contrato->fecha_final = $model->fecha_final;
                    $contrato->id_motivo_terminacion = $model->id_motivo_terminacion;
                    $contrato->contrato_activo = 1;
                    $contrato->observacion = $model->observacion;
                    $contrato->generar_liquidacion = 1;
                    $contrato->user_name_editado = Yii::$app->user->identity->username;
                    $contrato->save(false);
                    $empleado = \app\models\Empleados::findOne($contrato->id_empleado);
                    $empleado->estado = 1;
                    $empleado->fecha_retiro = $model->fecha_final;
                    $empleado->save(false);
                    if($model->generar_liquidacion == 1){
                        $table = new \app\models\PrestacionesSociales();  
                        $table->id_empleado = $contrato->id_empleado;
                        $table->id_contrato = $id;
                        $table->documento = $contrato->nit_cedula;
                        $table->id_grupo_pago = $contrato->id_grupo_pago;
                        $table->fecha_inicio_contrato = $contrato->fecha_inicio;
                        $table->fecha_termino_contrato = $model->fecha_final;
                        $table->ultimo_pago_prima = $contrato->ultima_pago_prima;
                        $table->ultimo_pago_cesantias = $contrato->ultima_pago_cesantia;
                        $table->ultimo_pago_vacaciones = $contrato->ultima_pago_vacacion;
                        $table->observacion = $model->observacion;
                        $table->salario = $contrato->salario;
                        $table->fecha_creacion = date('Y-m-d');
                        $table->usuariosistema = Yii::$app->user->identity->username;
                        $table->insert(false);
                    }
                   return $this->redirect(["contratos/view", 'id' => $id, 'token' => $token]);                                                     
                }
            }
        }
        if (Yii::$app->request->get("id")) {
            $table = Contratos::find()->where(['id_contrato' => $id])->one();            
            if ($table) {                                
                $model->id_contrato = $table->id_contrato;                
            }
        }
        
        return $this->renderAjax('closed_contrato_laboral', ['model' => $model, 'id' => $id, 'token' => $token]);
    }
    
    //ACTIVAR NUEVAMENTE CONTRATO
    public function actionAbrir_contrato_laboral($id, $token)
    {
      $prestacion = \app\models\PrestacionesSociales::find()->where(['=','id_contrato', $id])->one();
      if($prestacion){
         Yii::$app->getSession()->setFlash('error', 'Este contrato no se puede abrir porque tiene prestaciones sociales asociadas.!');  
         return $this->redirect(["contratos/view",'id' => $id, 'token' => $token]); 
      }else{
          $contrato = Contratos::findOne($id);
          $contrato->contrato_activo = 0;
          $contrato->id_motivo_terminacion = 3;
          $contrato->generar_liquidacion = 0;
          $contrato->observacion = 'Se abrio el contrato nuavamente';
          $contrato->save(false);
          $empleado = \app\models\Empleados::findOne($contrato->id_empleado);
          $empleado->estado = 0;
          $empleado->save();
          return $this->redirect(["contratos/view",'id' => $id, 'token' => $token]); 
      }   
    }        
    
    //IMPRESIONES
     public function actionImprimir_contrato_laboral($id)
    {
        return $this->render('../formatos/nomina/contrato_laboral', [
            'model' => $this->findModel($id),
            
        ]);
    }
    
    
    /**
     * Finds the Contratos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contratos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contratos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //PROCESOS DE EXPORTACION
     public function actionExcelContratos($tableexcel) {                
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
                              
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'NRO CONTRATO')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'TIPO CONTRATO')
                    ->setCellValue('E1', 'TIEMPO SERVICIO')
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', 'DESCRIPCION')                    
                    ->setCellValue('H1', 'FECHA INICIO')
                    ->setCellValue('I1', 'FECHA TERMINACION')
                    ->setCellValue('J1', 'TIPO SALARIO')
                    ->setCellValue('K1', 'SALARIO')
                    ->setCellValue('L1', 'APLICA TRANSPORTE')
                    ->setCellValue('M1', 'EPS')
                    ->setCellValue('N1', 'PENSION')
                    ->setCellValue('O1', 'CAJA DE COMPENSACION')
                    ->setCellValue('P1', 'NIVEL ARL')
                    ->setCellValue('Q1', 'MUNICIPIO LABORAL')
                    ->setCellValue('R1', 'MUNICIPIO CONTRATADO')
                    ->setCellValue('S1', 'CENTRO DE TRABAJO')
                    ->setCellValue('T1', 'GRUPO DE PAGO')
                    ->setCellValue('U1', 'FECHA PREAVISO')
                    ->setCellValue('V1', 'DIAS CONTRATOS')
                    ->setCellValue('W1', 'USER NAME INGRESO')
                    ->setCellValue('X1', 'FECHA REGISTRO')
                    ->setCellValue('Y1', 'USER NAME EDITADO')
                    ->setCellValue('Z1', 'FECHA HORA EDITADO')
                    ->setCellValue('AA1', 'FUNCIONES');
                   
        $i = 2  ;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_contrato)
                    ->setCellValue('B' . $i, $val->nit_cedula)
                    ->setCellValue('C' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('D' . $i, $val->tipoContrato->contrato)
                    ->setCellValue('E' . $i, $val->tiempo->tiempo_servicio)
                    ->setCellValue('F' . $i, $val->cargo->nombre_cargo)
                    ->setCellValue('G' . $i, $val->descripcion)                    
                    ->setCellValue('H' . $i, $val->fecha_inicio)
                    ->setCellValue('I' . $i, $val->fecha_final)
                    ->setCellValue('J' . $i, $val->tipoSalario->descripcion)
                    ->setCellValue('K' . $i, $val->salario)
                    ->setCellValue('L' . $i, $val->aplicaAuxilio)
                    ->setCellValue('M' . $i, $val->entidadSalud->entidad_salud)
                    ->setCellValue('N' . $i, $val->entidadPension->entidad)
                    ->setCellValue('O' . $i, $val->cajaCompensacion->caja)
                    ->setCellValue('P' . $i, $val->arl->descripcion)
                    ->setCellValue('Q' . $i, $val->codigoMunicipioLaboral->municipio)
                    ->setCellValue('R' . $i, $val->codigoMunicipioContratado->municipio)
                    ->setCellValue('S' . $i, $val->centroTrabajo->centro_trabajo)
                    ->setCellValue('T' . $i, $val->grupoPago->grupo_pago)
                    ->setCellValue('U' . $i, $val->fecha_preaviso)
                    ->setCellValue('V' . $i, $val->dias_contrato)
                    ->setCellValue('W' . $i, $val->user_name)
                    ->setCellValue('X' . $i, $val->fecha_hora_registro)
                    ->setCellValue('Y' . $i, $val->user_name_editado)
                    ->setCellValue('Z' . $i, $val->fecha_hora_editado)
                    ->setCellValue('AA' . $i, $val->funciones);
                   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Contratos.xlsx"');
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
