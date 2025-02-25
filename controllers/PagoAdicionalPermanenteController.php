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
use app\models\PagoAdicionalPermanente;
use app\models\PagoAdicionalPermanenteSearch;
use app\models\UsuarioDetalle;
use app\models\Contratos;

/**
 * PagoAdicionalPermanenteController implements the CRUD actions for PagoAdicionalPermanente model.
 */
class PagoAdicionalPermanenteController extends Controller
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
     * Lists all PagoAdicionalPermanente models.
     * @return mixed
     */
    public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',148])->all()){
               // $pagoadicional = PagoAdicionalPermanente::find()->all();
                $form = new \app\models\FormFiltroConsultaAdicionPermanente();
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
                        $table = PagoAdicionalPermanente::find()
                                ->andFilterWhere(['=','id_empleado',$id_empleado])
                                ->andFilterWhere(['=', 'id_grupo_pago', $id_grupo_pago])
                                ->andFilterWhere(['=', 'tipo_adicion', $tipoadicion])
                                ->andFilterWhere(['=', 'codigo_salario', $codigo_salario])
                                ->andwhere(['=','permanente', 1])
                                ->orderBy('id_pago_permanente desc');
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
                                $this->actionExcelconsulta($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = PagoAdicionalPermanente::find()
                        ->where(['=','permanente', 1])
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
                if(isset($_POST['activar_periodo'])){                            
                    if(isset($_REQUEST['id_pago_permanente'])){                            
                        $intIndice = 0;
                        foreach ($_POST["id_pago_permanente"] as $intCodigo) {
                            if ($_POST["id_pago_permanente"][$intIndice]) {                                
                               echo $id_pago_permanente = $_POST["id_pago_permanente"][$intIndice];
                                $this->ActivarPeriodo($id_pago_permanente);
                            }
                            $intIndice++;
                        }
                    }
                    $this->redirect(["pago-adicional-permanente/index"]);
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
   
   //PROCESOS DE ACTIVACION Y DESACTIVACION
    protected function ActivarPeriodoRegistro($id_pago_permanente) {        
        $adicionalPago = PagoAdicionalPermanente::findOne($id_pago_permanente);
        $adicionalPago->estado_registro = 0;
        $adicionalPago->save(false);
    }
    
    protected function ActivarPeriodo($id_pago_permanente) {        
        $adicionalPago = PagoAdicionalPermanente::findOne($id_pago_permanente);
        $adicionalPago->estado_periodo = 0;
        $adicionalPago->save(false);
    }
    
    protected function DesactivarPeriodo($id_pago_permanente) {        
        $adicionalPago = PagoAdicionalPermanente::findOne($id_pago_permanente);
        $adicionalPago->estado_periodo = 1;
        $adicionalPago->save(false);
    }
    
    protected function DesactivarPeriodoRegistro($id_pago_permanente) {        
        $adicionalPago = PagoAdicionalPermanente::findOne($id_pago_permanente);
        $adicionalPago->estado_registro = 1;
        $adicionalPago->save(false);
    }


    /**
     * Displays a single PagoAdicionalPermanente model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PagoAdicionalPermanente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PagoAdicionalPermanente();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_pago_permanente]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PagoAdicionalPermanente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_pago_permanente]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PagoAdicionalPermanente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PagoAdicionalPermanente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PagoAdicionalPermanente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PagoAdicionalPermanente::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
