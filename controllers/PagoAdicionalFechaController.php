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
use app\models\PagoAdicionalFecha;
use app\models\PagoAdicionalFechaSearch;
use app\models\UsuarioDetalle;

/**
 * PagoAdicionalFechaController implements the CRUD actions for PagoAdicionalFecha model.
 */
class PagoAdicionalFechaController extends Controller
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
     * Lists all PagoAdicionalFecha models.
     * @return mixed
     */
     public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',139])->all()){
                $form = new \app\models\FormFiltroPagoFecha();
                $estado_proceso = null;
                $fecha_corte = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $estado_proceso = Html::encode($form->estado_proceso);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = PagoAdicionalFecha::find()
                                ->andFilterWhere(['=','estado_registro', $estado_proceso])
                                ->andFilterWhere(['=','fecha_corte', $fecha_corte]);
                        $table = $table->orderBy('id_pago_fecha DESC');
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
                                $check = isset($_REQUEST['id_pago_fecha DESC']);
                                $this->actionExcelconsulta($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = PagoAdicionalFecha::find()
                                                ->orderBy('id_pago_fecha DESC');
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
                    $this->actionExcelconsulta($tableexcel);
                }
                if(isset($_POST['activar_periodo_registro'])){                            
                    if(isset($_REQUEST['id_pago_permanente'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_pago_permanente"] as $intCodigo) {
                            if ($_POST["id_pago_permanente"][$intIndice]) {                                
                                $id_pago_permanente = $_POST["id_pago_permanente"][$intIndice];
                                $this->ActivarPeriodoRegistro($id_pago_permanente);
                            }
                            $intIndice++;
                        }
                    }
                    $this->redirect(["pago-adicional-permanente/index"]);
                }
                if(isset($_POST['desactivar_periodo_registro'])){                            
                    if(isset($_REQUEST['id_pago_permanente'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_pago_permanente"] as $intCodigo) {
                            if ($_POST["id_pago_permanente"][$intIndice]) {                                
                                $id_pago_permanente = $_POST["id_pago_permanente"][$intIndice];
                                $this->DesactivarPeriodoRegistro($id_pago_permanente);
                            }
                            $intIndice++;
                        }
                    }
                    $this->redirect(["pago-adicional-permanente/index"]);
                }
            }
            $to = $count->count();
            return $this->render('index', [
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

   
    //PROCESOS PRIVADOS PARA ACTUALIZAR Y DESACTUALIZAR REGISTROS
    protected function ActivarPeriodoRegistro($id_pago_permanente) {   
        $adicionalPago = \app\models\PagoAdicionalPermanente::findOne($id_pago_permanente);
        $adicionalPago->estado_registro = 0;
        $adicionalPago->save(false);
    }
    
    protected function ActivarPeriodo($id_pago_permanente) {        
        $adicionalPago = \app\models\PagoAdicionalPermanente::findOne($id_pago_permanente);
        $adicionalPago->estado_periodo = 0;
        $adicionalPago->save(false);
    }
    
    protected function DesactivarPeriodo($id_pago_permanente) {        
        $adicionalPago = \app\models\PagoAdicionalPermanente::findOne($id_pago_permanente);
        $adicionalPago->estado_periodo = 1;
        $adicionalPago->save(false);
    }
    
    protected function DesactivarPeriodoRegistro($id_pago_permanente) {        
        $adicionalPago = \app\models\PagoAdicionalPermanente::findOne($id_pago_permanente);
        $adicionalPago->estado_registro = 1;
        $adicionalPago->save(false);
    }
    /**
     * Displays a single PagoAdicionalFecha model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $fecha_corte) {
        $fechacorte = PagoAdicionalFecha::findOne($id);
        $id= $fechacorte->id_pago_fecha;
        $estado_proceso= $fechacorte->estado_registro;
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso', 139])->all()){
                $form = new \app\models\FormFiltroAdicionPermanente();
                $id_grupo_pago = null;
                $id_empleado = null; 
                $codigo_salario = null;
                $tipoadicion = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $id_grupo_pago = Html::encode($form->id_grupo_pago);
                        $id_empleado = Html::encode($form->id_empleado);
                        $codigo_salario = Html::encode($form->codigo_salario);
                        $tipoadicion = Html::encode($form->tipo_adicion);
                        $table = \app\models\PagoAdicionalPermanente::find()
                                ->andFilterWhere(['=','id_empleado',$id_empleado])
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['=', 'tipo_adicion', $tipoadicion])
                                ->andFilterWhere(['=', 'codigo_salario', $codigo_salario])
                                ->andWhere(['=','permanente', 0])
                                ->andWhere(['=','id_pago_fecha', $id])
                                ->andFilterWhere(['=','fecha_corte',$fechacorte->fecha_corte]);
                        $table = $table->orderBy('id_pago_permanente DESC');
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
                                $check = isset($_REQUEST['id_pago_permanente DESC']);
                                $this->actionExcelconsultaPagos($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = \app\models\PagoAdicionalPermanente::find()
                        ->where(['=','permanente', 0])
                        ->andWhere(['=','fecha_corte', $fechacorte->fecha_corte])
                        ->andWhere(['=','id_pago_fecha', $id])
                        ->orderBy('id_pago_permanente DESC');
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
                    $this->actionExcelconsultaPagos($tableexcel);
                }
                if(isset($_POST['activar_periodo_registro'])){                            
                    if(isset($_REQUEST['id_pago_permanente'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_pago_permanente"] as $intCodigo) {
                            if ($_POST["id_pago_permanente"][$intIndice]) {                                
                                
                                $id_pago_permanente = $_POST["id_pago_permanente"][$intIndice];
                                $this->ActivarPeriodoRegistro($id_pago_permanente);
                            }
                            $intIndice++;
                        }
                    }

                    $this->redirect(["pago-adicional-fecha/view",'id'=>$id, 'fecha_corte' => $fecha_corte]);
                }
                if(isset($_POST['desactivar_periodo_registro'])){                            
                    if(isset($_REQUEST['id_pago_permanente'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_pago_permanente"] as $intCodigo) {
                            if ($_POST["id_pago_permanente"][$intIndice]) {                                
                                $id_pago_permanente = $_POST["id_pago_permanente"][$intIndice];
                                $this->DesactivarPeriodoRegistro($id_pago_permanente);
                            }
                            $intIndice++;
                        }
                    }
                    $this->redirect(["pago-adicional-fecha/view",'id'=>$id, 'fecha_corte' => $fecha_corte]);
                }
                if(isset($_POST['activar_periodo'])){                            
                    if(isset($_REQUEST['id_pago_permanente'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_pago_permanente"] as $intCodigo) {
                            if ($_POST["id_pago_permanente"][$intIndice]) {                                
                                $id_pago_permanente = $_POST["id_pago_permanente"][$intIndice];
                                $this->ActivarPeriodo($id_pago_permanente);
                            }
                            $intIndice++;
                        }
                    }
                    $this->redirect(["pago-adicional-fecha/view",'id'=>$id, 'fecha_corte' => $fecha_corte]);
                }
                if(isset($_POST['desactivar_periodo'])){   
                    if(isset($_REQUEST['id_pago_permanente'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_pago_permanente"] as $intCodigo) {
                            if ($_POST["id_pago_permanente"][$intIndice]) {                                
                                $id_pago_permanente = $_POST["id_pago_permanente"][$intIndice];
                                $this->DesactivarPeriodo($id_pago_permanente);
                            }
                            $intIndice++;
                        }
                    }
                    $this->redirect(["pago-adicional-fecha/view",'id'=>$id, 'fecha_corte' => $fecha_corte]);
                }
            }
            $to = $count->count();
            return $this->render('view', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
                        'id' => $id,
                        'estado_proceso'=>$estado_proceso,
                        'fecha_corte' => $fecha_corte,
                        'fechacorte' => $fechacorte,
            ]);
            
        }else{
             return $this->redirect(['site/sinpermiso']);
        }     
        }else{
           return $this->redirect(['site/login']);
        }
   }
   
    //VISTA QUE MUESTRE EL DETALLE DEL REGISTRO
    public function actionVista($id_pago_permanente,$tipoadicion, $fecha_corte) {
        $model = \app\models\PagoAdicionalPermanente::find()->Where(['=', 'id_pago_permanente', $id_pago_permanente])->one();
        $id = $model->id_pago_fecha;  
         return $this->render('vista', [
            'model' => $model, 'id'=>$id, 'tipoadicion'=>$tipoadicion, 'fecha_corte' => $fecha_corte,
        ]);
    }  
   
    //CREA ADICION AL PAGO DE CONCEPTOS QUE SUMAN
    public function actionCreateadicion($id, $fecha_corte) {        
        $model = new \app\models\FormAdicionPermanente();        
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {            
            if ($model->validate()) {
                $table = new \app\models\PagoAdicionalPermanente();
                $table->id_empleado = $model->id_empleado;
                $table->codigo_salario = $model->codigo_salario;
                $table->tipo_adicion = 1;
                $table->valor_adicion = $model->valor_adicion;
                $table->permanente = 0;
                $table->aplicar_dia_laborado = $model->aplicar_dia_laborado;
                $table->aplicar_prima = $model->aplicar_prima;
                $table->aplicar_cesantias = $model->aplicar_cesantias;
                $table->detalle = $model->detalle;
                $table->user_name = Yii::$app->user->identity->username;
                $contrato = \app\models\Contratos::find()->where(['=','id_empleado', $model->id_empleado])->andWhere(['=','contrato_activo', 0])->one();
                $table->id_contrato = $contrato->id_contrato;
                $table->id_grupo_pago = $contrato->id_grupo_pago;
                $pagofecha = PagoAdicionalFecha::find()->where(['=','id_pago_fecha', $id])->one();
                $table->id_pago_fecha = $pagofecha->id_pago_fecha;
                $table->fecha_corte = $pagofecha->fecha_corte;
                if ($table->save(false)) {
                    $this->redirect(["pago-adicional-fecha/view", 'id' =>$id, 'fecha_corte' => $fecha_corte]);
                } else {
                    $msg = "error";
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->render('_formadicion', ['model' => $model, 'id'=> $id, 'fecha_corte' => $fecha_corte]);
    }
    
    //PERMITE CREAR LOS DESCUENTOS O CONCEPTOS QUE RESTAN
    public function actionCreatedescuento($id, $fecha_corte) {        
        $model = new \app\models\FormAdicionPermanente();        
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {            
            if ($model->validate()) {
                $table = new \app\models\PagoAdicionalPermanente();
                $table->id_empleado = $model->id_empleado;
                $table->codigo_salario = $model->codigo_salario;
                $table->valor_adicion = $model->valor_adicion;
                $table->aplicar_dia_laborado = $model->aplicar_dia_laborado;
                $table->aplicar_prima = $model->aplicar_prima;
                $table->aplicar_cesantias = $model->aplicar_cesantias;
                $table->detalle = $model->detalle;
                $table->user_name = Yii::$app->user->identity->username;
                $contrato = \app\models\Contratos::find()->where(['=','id_empleado', $model->id_empleado])->andWhere(['=','contrato_activo', 0])->one();
                $table->id_contrato = $contrato->id_contrato;
                $table->id_grupo_pago = $contrato->id_grupo_pago;
                $pagofecha = PagoAdicionalFecha::find()->where(['=','id_pago_fecha', $id])->one();
                $table->id_pago_fecha = $pagofecha->id_pago_fecha;
                $table->fecha_corte = $pagofecha->fecha_corte;
                if ($table->save(false)) {
                    $this->redirect(["pago-adicional-fecha/view", 'id'=> $id, 'fecha_corte' => $fecha_corte]);
                } else {
                    $msg = "error";
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->render('_formdescuento', ['model' => $model, 'id'=> $id, 'fecha_corte' => $fecha_corte]);
    }
    
    /**
     * Creates a new PagoAdicionalFecha model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PagoAdicionalFecha();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name = Yii::$app->user->identity->username;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PagoAdicionalFecha model.
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
    
    //permite modificar las adiciones y descuento de la tabla adicionpagopermanente
     public function actionUpdatevista($id_pago_permanente, $tipoadicion, $fecha_corte)
    {
        $model = new \app\models\FormAdicionPermanente();
       if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
      
        if ($model->load(Yii::$app->request->post())) {            
                $table = \app\models\PagoAdicionalPermanente::find()->where(['id_pago_permanente'=>$id_pago_permanente])->one();
                $id = $table->id_pago_fecha;
                if ($table) {
                    $table->codigo_salario = $model->codigo_salario;
                    $table->valor_adicion = $model->valor_adicion;
                    $table->aplicar_dia_laborado = $model->aplicar_dia_laborado;
                    $table->aplicar_prima = $model->aplicar_prima;
                    $table->aplicar_cesantias = $model->aplicar_cesantias;
                    $table->detalle = $model->detalle;
                    if($table->id_empleado != $model->id_empleado ){
                        $contrato = \app\models\Contratos::find()->where(['=','id_empleado',$model->id_empleado])->andWhere(['=','contrato_activo', 0])->one();
                        $table->id_empleado = $model->id_empleado;
                        $table->id_contrato = $contrato->id_contrato;
                        $table->id_grupo_pago = $contrato->id_grupo_pago;  
                    }    
                   $table->save(false);
                   return $this->redirect(['view','id'=>$id,'id_pago_permanente'=>$id_pago_permanente, 'fecha_corte' => $fecha_corte]); 
                }
        }
        if (Yii::$app->request->get("id_pago_permanente")) {
              
                 $table = \app\models\PagoAdicionalPermanente::find()->where(['id_pago_permanente' => $id_pago_permanente])->one();     
                   $id = $table->id_pago_fecha;
                if ($table) {     
                    $model->id_empleado = $table->id_empleado;
                    $model->codigo_salario = $table->codigo_salario;
                    $model->valor_adicion = $table->valor_adicion;
                    $model->aplicar_dia_laborado = $table->aplicar_dia_laborado;
                    $model->aplicar_prima = $table->aplicar_prima;
                    $model->aplicar_cesantias = $table->aplicar_cesantias;
                    $model->detalle =  $table->detalle;
                }else{
                       return $this->redirect(['view','id'=>$id,'id_pago_permanente'=>$id_pago_permanente, 'fecha_corte' => $fecha_corte]); 
                }
        } else {
                  return $this->redirect(['view','id'=>$id,'id_pago_permanente'=>$id_pago_permanente, 'fecha_corte' => $fecha_corte]);   
        }
        return $this->render('updatevista', [
            'model' => $model, 'id'=>$id, 'tipoadicion'=>$tipoadicion, 'fecha_corte' => $fecha_corte, 
        ]);
    }

    /**
     * Deletes an existing PagoAdicionalFecha model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    //codigo que elimina el adicional de la vista
     public function actionEliminaradicional($id_pago_permanente, $fecha_corte) {
        if (Yii::$app->request->post()) {
            $pagoadicional = \app\models\PagoAdicionalPermanente::findOne($id_pago_permanente);
            $id = $pagoadicional->id_pago_fecha;
            if ((int) $id_pago_permanente) {
                try {
                    \app\models\PagoAdicionalPermanente::deleteAll("id_pago_permanente=:id_pago_permanente", [":id_pago_permanente" => $id_pago_permanente]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    $this->redirect(["pago-adicional-fecha/view" , 'id'=>$id, 'fecha_corte' => $fecha_corte]);
                } catch (IntegrityException $e) {
                    $this->redirect(["pago-adicional-fecha/view" ,'id'=>$id, 'fecha_corte' => $fecha_corte]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el Id Nro: ' . $pagoadicional->id_pago_permanente . ', tiene registros asociados en otros procesos');
                } catch (\Exception $e) {

                    $this->redirect(["pago-adicional-fecha/view", 'id'=>$id, 'fecha_corte' => $fecha_corte]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el Id Nro:  ' . $pagoadicional->id_pago_permanente . ', tiene registros asociados en otros procesos');
                }
            } else {
                // echo "Ha ocurrido un error al eliminar el registros, redireccionando ...";
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("pago-adicional-fecha/view, 'id'=>$id, 'fecha_corte' => $fecha_corte") . "'>";
            }
        } else {
            return $this->redirect(["pago-adicional-fecha/view", 'id'=>$id, 'fecha_corte' => $fecha_corte]);
        }
    }
    
    //PROCESO QUE ELIMINA EL REGISTRO MAESTRO DEL PAGO ADICIONAL
    public function actionEliminar($id) 
    {
        if (Yii::$app->request->post()) {
            $pagoadicional = PagoAdicionalFecha::findOne($id);
            if ((int) $id) {
                try {
                    PagoAdicionalFecha::deleteAll("id_pago_fecha=:id_pago_fecha", [":id_pago_fecha" => $id]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    $this->redirect(["pago-adicional-fecha/index"]);
                } catch (IntegrityException $e) {
                    $this->redirect(["pago-adicional-fecha/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el Id Nro: ' . $pagoadicional->id_pago_fecha . ', tiene registros asociados en otros procesos');
                } catch (\Exception $e) {

                    $this->redirect(["pago-adicional-fecha/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el Id Nro:  ' . $pagoadicional->id_pago_fecha . ', tiene registros asociados en otros procesos');
                }
            } else {
                // echo "Ha ocurrido un error al eliminar el registros, redireccionando ...";
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("pago-adicional-fecha/index") . "'>";
            }
        } else {
            return $this->redirect(["pago-adicional-fecha/index"]);
        }
    }
    
    //PROCESO QUE CIERRA EL REGISTRO EN SU TOTALIDAD
    public function actionCerrar_proceso($id) {
        if($conRegistro = PagoAdicionalFecha::findOne($id)){
            $conRegistro->estado_registro = 1;
            $conRegistro->save();
            return $this->redirect(['pago-adicional-fecha/index']);
        }else{
            return $this->redirect(['pago-adicional-fecha/index']);
        }
    }
    
    /**
     * Finds the PagoAdicionalFecha model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PagoAdicionalFecha the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PagoAdicionalFecha::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //EXPORTAR INFORMACION EN EXCEL
    public function actionExcelconsultaPagos($tableexcel) {                
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
                    ->setCellValue('A1', 'Id')
                    ->setCellValue('B1', 'Empleado')
                    ->setCellValue('C1', 'Concepto')
                    ->setCellValue('D1', 'Contrato')
                    ->setCellValue('E1', 'Grupo de Pago')                    
                    ->setCellValue('F1', 'Tipo Adición')
                    ->setCellValue('G1', 'Valor Adición')
                    ->setCellValue('H1', 'Permanente')
                    ->setCellValue('I1', 'Aplicar Día Laborado')
                    ->setCellValue('J1', 'Aplicar Prima')
                    ->setCellValue('K1', 'Aplicar Cesantia')
                    ->setCellValue('L1', 'fecha_corte')
                    ->setCellValue('M1', 'Usuario');
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_pago_permanente)
                    ->setCellValue('B' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('C' . $i, $val->codigoSalario->nombre_concepto)
                    ->setCellValue('D' . $i, $val->id_contrato)
                    ->setCellValue('E' . $i, $val->grupoPago->grupo_pago)                    
                    ->setCellValue('F' . $i, $val->DebitoCredito)
                    ->setCellValue('G' . $i, $val->valor_adicion)
                    ->setCellValue('H' . $i, $val->permanentes)
                    ->setCellValue('I' . $i, $val->aplicarDiaLaborado)
                    ->setCellValue('J' . $i, $val->aplicarPrima)
                    ->setCellValue('K' . $i, $val->aplicarCesantia)
                    ->setCellValue('L' . $i, $val->fecha_corte)
                    ->setCellValue('M' . $i, $val->user_name);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="adicional_al_pago.xlsx"');
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
