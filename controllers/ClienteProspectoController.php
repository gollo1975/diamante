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
use app\models\ClienteProspecto;
use app\models\ClienteProspectoSearch;
use app\models\Clientes;
use app\models\UsuarioDetalle;
use app\models\Municipios;


/**
 * ClienteProspectoController implements the CRUD actions for ClienteProspecto model.
 */
class ClienteProspectoController extends Controller
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
     * Lists all ClienteProspecto models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',61])->all()){
                $form = new \app\models\FiltroBusquedaProveedor();
                $nitcedula = null;
                $nombre_completo = null;
                $agente = \app\models\AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                $agente = $agente->id_agente;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $nitcedula = Html::encode($form->nitcedula);
                        $nombre_completo = Html::encode($form->nombre_completo);
                        $table = ClienteProspecto::find()
                                ->andFilterWhere(['like', 'nit_cedula', $nitcedula])
                                ->andWhere(['=', 'id_agente', $agente])
                                ->andFilterWhere(['like', 'razon_social', $nombre_completo]);
                        $table = $table->orderBy('id_prospecto DESC');
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
                            $this->actionExcelconsultaProspecto($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = ClienteProspecto::find()->Where(['=', 'id_agente', $agente])
                            ->orderBy('id_prospecto DESC');
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
                            $this->actionExcelconsultaProspecto($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                            'agente' => $agente,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //LISTADO DE CITAS A PROSPECTOS
    public function actionListado_cita_prospecto() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',61])->all()){
                $form = new \app\models\FiltroBusquedaCitaProspecto();
                $prospecto = null;
                $tipo_visita = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $agente = \app\models\AgentesComerciales::find()->where(['=','nit_cedula', Yii::$app->user->identity->username])->one();
                $agente = $agente->id_agente;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $prospecto = Html::encode($form->prospecto);
                        $tipo_visita = Html::encode($form->tipo_visita);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = \app\models\ProspectoCitas::find()
                                ->andFilterWhere(['=', 'id_prospecto', $prospecto])
                                ->andWhere(['=', 'id_agente', $agente])
                                ->andFilterWhere(['=', 'tipo_visita', $tipo_visita])
                                ->andFilterWhere(['between', 'fecha_cita', $fecha_inicio, $fecha_corte]);
                        $table = $table->orderBy('id_cita_prospecto DESC');
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
                            $this->actionExcelconsultaCitaProspecto($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = \app\models\ProspectoCitas::find()->Where(['=', 'id_agente', $agente])
                            ->orderBy('id_cita_prospecto DESC');
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
                            $this->actionExcelconsultaCitaProspecto($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('listado_cita_prospecto', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'agente' => $agente,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }

    /**
     * Displays a single ClienteProspecto model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $cita_prospecto = \app\models\ProspectoCitas::find()->where(['=','id_prospecto', $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'cita_prospecto' => $cita_prospecto,
        ]);
    }

    /**
     * Creates a new ClienteProspecto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($agente)
    {
        $model = new ClienteProspecto();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $dv = Html::encode($_POST["dv"]);
            $table = $this->findModel($model->id_prospecto);
            $table->user_name = Yii::$app->user->identity->username;
            $table->dv = $dv;
            $table->id_agente = $agente;
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
            'agente' => $agente,
        ]);
    }

    /**
     * Updates an existing ClienteProspecto model.
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
        $municipios = Municipios::find()->Where(['=', 'codigo_departamento', $model->codigo_departamento])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $dv = Html::encode($_POST["dv"]);
                $table = $this->findModel($id);
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

        return $this->render('update', [
            'model' => $model,
            'municipios' => ArrayHelper::map($municipios, "codigo_municipio", "municipio"),
        ]);
    }
    
    //CREAR NUEVA CITA
     public function actionCrear_cita_prospecto($id, $agente) {

        $model = new \app\models\FormModeloNuevaCitaProspecto();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nueva_cita_prospecto"])) {
                    $conCita = \app\models\ProspectoCitas::find()->Where(['=', 'hora_cita', $model->hora_visita])
                                    ->andWhere(['=', 'fecha_cita', $model->fecha_cita])
                                    ->andWhere(['=', 'id_prospecto', $id])->one();
                    if (!$conCita) {
                        $table = new \app\models\ProspectoCitas();
                        $table->id_prospecto = $id;
                         $table->id_agente = $agente;
                        $table->id_tipo_visita = $model->tipo_visita;
                        $table->hora_cita = $model->hora_visita;
                        $table->fecha_cita = $model->fecha_cita;
                        $table->nota = $model->nota;
                        $table->save(false);
                        $this->redirect(["cliente-prospecto/listado_cita_prospecto"]);
                    } else {
                        Yii::$app->getSession()->setFlash('warning', 'Lo siento, hay una cita a la misma hora. Intente cambiar la hora de la cita.  ');
                        $this->redirect(["cliente-prospecto/index"]);
                    }
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->renderAjax('crear_nueva_cita', [
                    'model' => $model,
                    'id' => $id,
                    'agente' => $agente,
        ]);
    }
    /**
     * Deletes an existing ClienteProspecto model.
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
     * Finds the ClienteProspecto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClienteProspecto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClienteProspecto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
