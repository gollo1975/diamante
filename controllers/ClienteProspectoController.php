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
use app\models\FiltroBusquedaCitaProspecto;
use app\models\ProspectoCitas;


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
                                ->andFilterWhere(['=', 'id_tipo_visita', $tipo_visita])
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
   
    //CONSULTA DE CITAS PROSPECTOS
     public function actionSearch_cita_prospecto() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',62])->all()){
                $form = new FiltroBusquedaCitaProspecto();
                $prospecto = null;
                $tipo_visita = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $vendedor = null;
                $model = null;
                $pages = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $prospecto = Html::encode($form->prospecto);
                        $tipo_visita = Html::encode($form->tipo_visita);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $vendedor = Html::encode($form->vendedor);
                        $table = ProspectoCitas::find()
                                ->andFilterWhere(['=', 'id_prospecto', $prospecto])
                                ->andFilterWhere(['=', 'id_agente', $vendedor])
                                ->andFilterWhere(['=', 'id_tipo_visita', $tipo_visita])
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
                } 
                return $this->render('search_cita_prospecto', [
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
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->save(false);
                        $this->redirect(["cliente-prospecto/index"]);
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
    
    ///PROCESO QUE CREA LA GESTION DE LAS CITAS DEL PROSPECTO
    public function actionGestion_cita_prospecto($id) {

        $model = new \app\models\ModeloGestionComercial();
        $table = \app\models\ProspectoCitas::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["gestion_comercial"])) {
                    $table->cumplida = $model->cumplida;
                    $table->fecha_hora_informe = date('Y-m-d h:i:s');
                    $table->descripcion_gestion = $model->observacion;
                    $table->tipo_visita = $model->tipo_visita;
                    $table->save(false);
                    $this->redirect(["cliente-prospecto/listado_cita_prospecto"]);
                }
            } else {
                $model->getErrors();
            }
        }
        if ($model->load(Yii::$app->request->get())) {
            $model->cumplida = $table->cumplida;
            $model->tipo_visita = $table->tipo_visita;
            $model->observacion = $table->descripcion_gestion;
        }
        return $this->renderAjax('gestion_cita_prospecto', [
                    'model' => $model,
                    'id' => $id,
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
    
     public function actionExcelconsultaCitaProspecto($tableexcel) {
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
                ->setCellValue('A1', 'No PROGRAMACION')
                ->setCellValue('B1', 'DOCUMENTO')
                ->setCellValue('C1', 'PROSPECTO')
                ->setCellValue('D1', 'MUNICIPIO')
                ->setCellValue('E1', 'TIPO VISITA')
                ->setCellValue('F1', 'HORA VISITA')
                ->setCellValue('G1', 'FECHA PROGRAMADA')
                ->setCellValue('H1', 'MOTIVO')
                ->setCellValue('I1', 'USER NAME')
                ->setCellValue('J1', 'CITA CUMPLIDA')
                ->setCellValue('K1', 'FECHA INFORME')
                ->setCellValue('L1', 'INFORME')
                ->setCellValue('M1', 'AGENTE COMERCIAL')
                ->setCellValue('N1', 'MEDIO VISITA');
        $i = 2;

        foreach ($tableexcel as $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_cita_prospecto)
                    ->setCellValue('B' . $i, $val->prospecto->nit_cedula)
                    ->setCellValue('C' . $i, $val->prospecto->nombre_completo)
                    ->setCellValue('D' . $i, $val->prospecto->codigoMunicipio->municipio)
                    ->setCellValue('E' . $i, $val->tipoVisita->nombre_visita)
                    ->setCellValue('F' . $i, $val->hora_cita)
                    ->setCellValue('G' . $i, $val->fecha_cita)
                    ->setCellValue('H' . $i, $val->nota)
                    ->setCellValue('I' . $i, $val->user_name)
                    ->setCellValue('J' . $i, $val->citaCumplida)
                    ->setCellValue('K' . $i, $val->fecha_hora_informe)
                    ->setCellValue('L' . $i, $val->descripcion_gestion)
                    ->setCellValue('M' . $i, $val->agenteCita->nombre_completo)
                    ->setCellValue('N' . $i, $val->visitaCliente);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Citas_prospectos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
}
