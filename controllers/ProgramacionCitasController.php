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
use kartik\date\DatePicker;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPExcel;
//models
use app\models\ProgramacionCitas;
use app\models\ProgramacionCitasSearch;
use app\models\ProgramacionCitaDetalles;
use app\models\TipoVisitaComercial;
use app\models\UsuarioDetalle;
use app\models\AgentesComerciales;
use app\models\Clientes;

/**
 * ProgramacionCitasController implements the CRUD actions for ProgramacionCitas model.
 */
class ProgramacionCitasController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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
     * Lists all ProgramacionCitas models.
     * @return mixed
     */
    public function actionIndex() {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 38])->all()) {
                $form = new \app\models\FiltroBusquedaCitas();
                $desde = null;
                $hasta = null;
                $proceso = null;
                $valor = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                $vendedor = AgentesComerciales::find()->where(['=', 'nit_cedula', Yii::$app->user->identity->username])->one();
                if ($vendedor) {
                    $agente = $vendedor->id_agente;
                } else {
                    $agente = 0;
                }
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $proceso = Html::encode($form->proceso);
                        $hasta = Html::encode($form->hasta);
                        $valor = Html::encode($form->vendedor);
                        if ($vendedor) {
                            $table = ProgramacionCitas::find()
                                    ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])
                                    ->andFilterWhere(['=', 'proceso_cerrado', $proceso])
                                    ->Where(['=', 'id_agente', $vendedor->id_agente]);
                        } else {
                            $table = ProgramacionCitas::find()
                                    ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])
                                    ->andFilterWhere(['=', 'proceso_cerrado', $proceso])
                                    ->andFilterWhere(['=', 'id_agente', $valor]);
                        }
                        $table = $table->orderBy('id_programacion DESC');
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
                            $this->actionExcelconsultaCitas($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    if ($vendedor) {
                        $table = ProgramacionCitas::find()->Where(['=', 'id_agente', $vendedor->id_agente])->andWhere(['=', 'proceso_cerrado', 0])
                                ->orderBy('id_programacion DESC');
                    } else {
                        $table = ProgramacionCitas::find()->Where(['=', 'proceso_cerrado', 0])->orderBy('id_programacion DESC');
                    }
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
                    if (isset($_POST['excel'])) {
                        $this->actionExcelconsultaCitas($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'tokenAcceso' => $tokenAcceso,
                            'agente' => $agente,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }

    //PROCESO QUE MIDE LA GESTION COMERCIAL
    public function actionGestion_comercial() {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 39])->all()) {
                $form = new \app\models\FiltroBusquedaCitas();
                $desde = null;
                $hasta = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                $vendedor = AgentesComerciales::find()->where(['=', 'nit_cedula', Yii::$app->user->identity->username])->one();
                if ($vendedor) {
                    $agente = $vendedor->id_agente;
                } else {
                    $agente = 0;
                }
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $table = ProgramacionCitas::find()
                                ->Where(['between', 'fecha_inicio', $desde, $hasta])
                                ->andWhere(['=', 'id_agente', $agente]);
                        $table = $table->orderBy('id_programacion DESC');
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
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = ProgramacionCitas::find()->Where(['=', 'id_agente', $agente])
                            ->orderBy('id_programacion DESC');
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
                }
                $to = $count->count();
                return $this->render('gestion_comercial', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'tokenAcceso' => $tokenAcceso,
                            'agente' => $agente,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }

    //CONSULTAS DE PROGRAMACION DE CITA
    public function actionSearch_programacion_citas() {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 41])->all()) {
                $form = new \app\models\FiltroBusquedaCitas();
                $desde = null;
                $hasta = null;
                $proceso = null;
                $vendedor = null;
                $tokenAcceso = Yii::$app->user->identity->role;
                if($tokenAcceso == 3){
                    $agenteCo = AgentesComerciales::find()->where(['=', 'nit_cedula', Yii::$app->user->identity->username])->one();
                   $agente = $agenteCo->id_agente;
                }
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        $proceso = Html::encode($form->proceso);
                        $vendedor = Html::encode($form->vendedor);
                       if($tokenAcceso == 3){
                           $table = ProgramacionCitas::find()
                                ->Where(['between', 'fecha_inicio', $desde, $hasta])
                                ->andWhere(['=', 'id_agente', $agente])
                                ->andWhere(['=', 'proceso_cerrado', 1]);
                       }else{
                            $table = ProgramacionCitas::find()
                                ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])
                                ->andFilterWhere(['=', 'id_agente', $vendedor])
                                ->andFilterWhere(['=', 'proceso_cerrado', $proceso]);
                       } 
                        $table = $table->orderBy('id_programacion DESC');
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
                            $this->actionExcelconsultaCitas($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    if($tokenAcceso == 3){
                        $table = ProgramacionCitas::find()->Where(['=', 'id_agente', $agente])
                                                         ->andWhere(['=', 'proceso_cerrado', 1])->orderBy('id_programacion DESC');
                    }else{
                        $table = ProgramacionCitas::find()->orderBy('id_programacion DESC');
                    }    
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
                    if (isset($_POST['excel'])) {
                            $this->actionExcelconsultaCitas($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_programacion_citas', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'tokenAcceso' => $tokenAcceso,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }
    
    //PROCESO DE LA VISTA
    public function actionView($id, $agenteToken, $tokenAcceso) {
        $detalle_visita = ProgramacionCitaDetalles::find()->where(['=', 'id_programacion', $id])->orderBy('hora_visita, desde ASC')->all();

        if (Yii::$app->request->post()) {
            if (isset($_POST["eliminar_todo"])) {
                if (isset($_POST["listado_citas"])) {
                    foreach ($_POST["listado_citas"] as $intCodigo) {
                        try {
                            $eliminar = ProgramacionCitaDetalles::findOne($intCodigo);
                            $eliminar->delete();
                            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                            $this->redirect(["programacion-citas/view", 'id' => $id, 'agenteToken' => $agenteToken,'tokenAcceso' => $tokenAcceso]);
                        } catch (IntegrityException $e) {

                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');
                        } catch (\Exception $e) {
                            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el detalle, tiene registros asociados en otros procesos');

                        }
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Debe seleccionar al menos un registro.');
                }    
             }
        }    
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'detalle_visita' => $detalle_visita,
                    'agenteToken' => $agenteToken,
                    'tokenAcceso' => $tokenAcceso,
        ]);
    }

    //VISTA DE LA CONSULTA DE PROGRAMACION
    public function actionView_search_programacion($id) {
        $detalle_visita = ProgramacionCitaDetalles::find()->where(['=', 'id_programacion', $id])->orderBy('hora_visita, desde ASC')->all();
        return $this->render('view_consulta_programacion', [
                    'model' => $this->findModel($id),
                    'detalle_visita' => $detalle_visita,
        ]);
    }

    //LISTADO DE CITAS PARA LA GESTION COMERCIAL
    public function actionListados_citas($id) {
        $listado_citas = ProgramacionCitaDetalles::find()->Where(['=', 'id_programacion', $id])->all();
        return $this->render('listado_citas', [
                    'model' => $this->findModel($id),
                    'id' => $id,
                    'listado_citas' => $listado_citas,
        ]);
    }

    ///PROCESO QUE CREA NUEVA GESTION COMERCIAL DE CADA CITA
    public function actionCrear_gestion_comercial($id, $detalle) {

        $model = new \app\models\ModeloGestionComercial();
        $table = ProgramacionCitaDetalles::findOne($detalle);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["gestion_comercial"])) {
                    $table->cumplida = $model->cumplida;
                    $table->fecha_informe = date('Y-m-d h:i:s');
                    $table->descripcion_gestion = $model->observacion;
                    $table->tipo_visita = $model->tipo_visita;
                    $table->save(false);
                    $this->redirect(["programacion-citas/listados_citas", 'id' => $id]);
                }
            } else {
                $model->getErrors();
            }
        }
        if (Yii::$app->request->get($id)) {
            $model->cumplida = $table->cumplida;
            $model->tipo_visita = $table->tipo_visita;
            $model->observacion = $table->descripcion_gestion;
        }
        return $this->renderAjax('form_nueva_gestion_comercial', [
                    'model' => $model,
                    'id' => $id,
        ]);
    }

    ///PROCESO QUE CREA NUEVA CITA PARA EL CLIENTE
    public function actionCrear_nueva_cita($id, $agenteToken, $tokenAcceso) {

        $model = new \app\models\FormModeloNuevaCitaCliente();
        $programacion = ProgramacionCitas::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nueva_cita_cliente"])) {
                    if (strtotime($model->fecha_cita) < strtotime($programacion->fecha_inicio)){
                        Yii::$app->getSession()->setFlash('warning', 'Lo siento, la fecha de la cita es menor que la fecha de inicio de la programacion. Intente cambiar la fecha de la cita.  ');
                        return $this->redirect(["programacion-citas/view", 'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso, 'id' => $id]); 
                    }else{    
                        $conCita = ProgramacionCitaDetalles::find()->Where(['=', 'hora_visita', $model->hora_visita])
                                        ->andWhere(['=','fecha_cita_comercial', $model->fecha_cita])
                                        ->andWhere(['=', 'id_programacion', $id])->one();
                        if (!$conCita) {
                            $table = new ProgramacionCitaDetalles();
                            $table->id_programacion = $id;
                            $table->id_cliente = $model->cliente;
                            $table->id_tipo_visita = $model->tipo_visita;
                            $table->hora_visita = $model->hora_visita;
                            $table->hora_visita_historial = $model->hora_visita;
                            $table->nota = $model->nota;
                            $table->desde = $programacion->fecha_inicio;
                            $table->hasta = $programacion->fecha_final;
                            $table->fecha_cita_comercial = $model->fecha_cita;
                            $table->fecha_cita_comercial_historial = $model->fecha_cita; 
                            $table->save(false);
                            $this->SumarCitasCliente($id);
                            return $this->redirect(["programacion-citas/view", 'id' => $id, 'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso]);
                        } else {
                            Yii::$app->getSession()->setFlash('warning', 'Lo siento, hay una cita a la misma hora. Intente cambiar la hora de la cita.  ');
                            return $this->redirect(["programacion-citas/view", 'id' => $id, 'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso]);
                        }
                    }    
                }
            } else {
                $model->getErrors();
            }
        }
        return $this->renderAjax('form_nueva_cita', [
                    'model' => $model,
                    'id' => $id,
                    'agenteToken' => $agenteToken,
                    'tokenAcceso' => $tokenAcceso,
            
        ]);
    }

    //PROCESO QUE EDITA LA CITA CON EL CLIENTE
    public function actionEditar_cita_cliente($id, $agenteToken, $tokenAcceso, $detalle) {
        $model = new \app\models\FormModeloNuevaCitaCliente();
        $table = ProgramacionCitaDetalles::findOne($detalle);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["nueva_cita_cliente"])) {
                if (strtotime($model->fecha_cita) < strtotime($table->desde)){
                    Yii::$app->getSession()->setFlash('warning', 'Lo siento, la fecha de la cita es menor que la fecha de inicio de la programacion. Intente cambiar la fecha de la cita.  ');
                    return $this->redirect(["programacion-citas/view", 'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso, 'id' => $id]); 
                }else{
                   $table->id_cliente = $model->cliente;
                    $table->id_tipo_visita = $model->tipo_visita;
                    $table->hora_visita = $model->hora_visita;
                    $table->nota = $model->nota;
                    $table->fecha_cita_comercial = $model->fecha_cita;
                    $table->save(false);
                    return $this->redirect(["programacion-citas/view", 'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso, 'id' => $id]); 
                }
            }
        }
        if (Yii::$app->request->get()) {
            $model->cliente = $table->id_cliente;
            $model->tipo_visita = $table->id_tipo_visita;
            $model->hora_visita = $table->hora_visita;
            $model->nota = $table->nota;
            $model->fecha_cita = $table->fecha_cita_comercial;
        }
        return $this->renderAjax('form_nueva_cita', [
                    'model' => $model,
                    'id' => $id,
                    'agenteToken' => $agenteToken,
                    'tokenAcceso' => $tokenAcceso,
        ]);
    }

    //proceso para contar las citas
    protected function SumarCitasCliente($id) {
        $programacion = ProgramacionCitas::findOne($id);
        $detalle = ProgramacionCitaDetalles::find()->where(['=', 'id_programacion', $id])->all();
        $suma = 0;
        foreach ($detalle as $detalles):
            $suma += 1;
        endforeach;
        $programacion->total_citas = $suma;
        $programacion->save();
    }

    //CREA EL ARCHIVO MAESTRO DE LA PROGRAMACION
    public function actionCrearcita($agente, $sw) {
        $model = new \app\models\FormModeloCrearCita();
        $cita = ProgramacionCitas::find()->where(['=', 'id_agente', $agente])->orderBy('id_programacion DESC')->all();
        $dato = count($cita);
        if (count($cita) > 0) {
            $vendedor = AgentesComerciales::findOne($agente);
            $cita = ProgramacionCitas::find()->where(['=', 'id_agente', $agente])->orderBy('id_programacion DESC')->one();
            $valor = $cita->proceso_cerrado;
            $diaria = $vendedor->gestion_diaria;
            if ($valor == 0 && $diaria == 0) {
                $valor = 1;
            } else {
                $valor = 0;
            }
        } else {
            $valor = 0;
        }
        if ($valor == 0) {
            if ($model->load(Yii::$app->request->post())) {
                if (isset($_POST["crear_cita_cliente"])) {
                    if ($model->desde <> (NULL) && $model->hasta <> (NULL)) {
                        $citas = ProgramacionCitas::find()->where(['=', 'fecha_inicio', $model->desde])->andwhere(['=', 'fecha_final', $model->hasta])
                                        ->andWhere(['=', 'id_agente', $agente])->one();
                        if (!$citas) {
                            $table = new ProgramacionCitas();
                            $table->id_agente = $agente;
                            $table->fecha_inicio = $model->desde;
                            $table->fecha_final = $model->hasta;
                            $table->user_name = Yii::$app->user->identity->username;
                            if($sw == 1){
                                $table->id_agente = $model->vendedor;
                            }
                            $table->save(false);
                            $this->redirect(["programacion-citas/index"]);
                        } else {
                            Yii::$app->getSession()->setFlash('warning', 'Ya existe una programacion de citas con las misma fechas. Ingrese nuevamente.');
                            return $this->redirect(["programacion-citas/index"]);
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('warning', 'Debe de digitar las fechas de inicio para la programacion de clientes. Ingrese nuevamente.');
                        return $this->redirect(["programacion-citas/index"]);
                    }
                }
            }
            return $this->renderAjax('crear_cita', [
                        'model' => $model,
                        'agente' => $agente,
                        'sw' => $sw,
            ]);
        } else {
            Yii::$app->getSession()->setFlash('info', 'Debe de cerrar la programación del dia anterior y lo vuelve a intentar.');
            return $this->redirect(["programacion-citas/index"]);
        }
    }

    //proceso que edita la programacion de cistas
    public function actionEditar_cita($agente, $id,$sw) {
        $model = new \app\models\FormModeloCrearCita();
        $table = ProgramacionCitas::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["crear_cita_cliente"])) {
                if ($model->desde <> (NULL) && $model->hasta <> (NULL)) {
                    $table->fecha_inicio = $model->desde;
                    $table->fecha_final = $model->hasta;
                    $table->save(false);
                    return $this->redirect(["programacion-citas/index"]);
                } else {
                    Yii::$app->getSession()->setFlash('warning', 'Debe de digitar las fechas de inicio para la programacion de clientes. Ingrese nuevamente.');
                    return $this->redirect(["programacion-citas/index"]);
                }
            }
        }
        if (Yii::$app->request->get()) {
            $model->desde = $table->fecha_inicio;
            $model->hasta = $table->fecha_final;
        }
        return $this->renderAjax('crear_cita', [
                    'model' => $model,
                    'agente' => $agente,
                    'sw' => $sw,
        ]);
    }
    
    //SUBIR ARCHIVOS DESDE EXCEL
    public function actionCargar_programacion_excel($id, $tokenAcceso, $agenteToken) {
        $model = new \app\models\FormSubirArchivoExcel();
        $agente = ProgramacionCitas::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->fileProgramacion = UploadedFile::getInstance($model, 'fileProgramacion');
           if ($model->upload()) {
               //asignamos el archivo a una variable
               $fileProgramacion = 'files/'. $model->fileProgramacion;
               $excel = \PHPExcel_IOFactory::load($fileProgramacion);
               $excel->setActiveSheetIndex(0);
               //obtener el numero de filas
               $numeroFila = $excel->setActiveSheetIndex(0)->getHighestRow();
               //creamos un vector para recorrer las fila
               $contador = 0;
               for($i=2; $i <= $numeroFila; $i++):
                   //se asigan las variables
                   $id_programacion = $excel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();;    
                   $id_cliente = $excel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
                   $id_tipo_visita = $excel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
                   $hora_visita = $excel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
                   $nota = $excel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
                   $fecha_cita_comercial = $excel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
                   //VALIDAR QUE SI EXITAS EL CODIGO DE PROGRAMACION
                   if($id_programacion == $id){
                        $fecha_inicio = strtotime($agente->fecha_inicio);
                        $fecha_final = strtotime($agente->fecha_final);
                        if(strtotime($fecha_cita_comercial) >= $fecha_inicio && strtotime($fecha_cita_comercial) <= $fecha_final  ){
                            ///inserta en la bd.
                            $table = new ProgramacionCitaDetalles();
                            $table->id_programacion = $id_programacion;    
                            $table->id_cliente = $id_cliente;
                            $table->id_tipo_visita = $id_tipo_visita;
                            $table->hora_visita = $hora_visita;
                            $table->nota = $nota;
                            $table->desde = $agente->fecha_inicio;
                            $table->hasta = $agente->fecha_final;
                            $table->fecha_cita_comercial = $fecha_cita_comercial;
                            $table->save(false);
                            $contador += 1;
                        }else{
                            Yii::$app->getSession()->setFlash('error', 'La fecha de la cita es menor a la fecha de inicio de la programacion. Favor valide la informacion y vuelvalo a importar.'); 
                        }
                   }else{
                        Yii::$app->getSession()->setFlash('error', 'El archivo tiene un error en el CODIGO DE PROGRMACION. Favor valide la informacion y vuelvalo a importar.'); 
                   }    
               endfor;
               Yii::$app->getSession()->setFlash('success', 'Se importaron ('.$contador.') registros de la programacion de citas del vendedor ('.$agente->agente->nombre_completo.').');  
               return $this->redirect(["programacion-citas/view",'id' => $id, 'tokenAcceso' => $tokenAcceso, 'agenteToken' => $agenteToken]);
               
                    
           }else{
               Yii::$app->getSession()->setFlash('error', 'Error al subir el archivo de programacion de citas.');   
           }
        }   
        return $this->render('subir_archivo_excel', [
                    'model' => $model,
                    'id' => $id,
                    'tokenAcceso' => $tokenAcceso,
                    'agenteToken' => $agenteToken,
        ]);
    }
            
            
            //    $i = 0;
            /*   foreach ($model->file as $linea):
                    $cantidad_registros = count($model->file);
                   $cantidad_regist_agregados =  ($cantidad_registros - 1);
                 /*   if ($i != 0) {
                        $datos = explode(";", $linea);
                        $id_programacion        = !empty($datos[0])  ? ($datos[0]) : '';
                        $id_cliente             = !empty($datos[1])  ? ($datos[1]) : '';
                        $id_tipo_visita         = !empty($datos[2])  ? ($datos[2]) : '';
                        $hora_visita            = !empty($datos[3])  ? ($datos[3]) : '';
                        $nota                   = !empty($datos[4])  ? ($datos[4]) : '';
                        $desde                  = !empty($datos[5])  ? ($datos[5]) : '';
                        $hasta                  = !empty($datos[6])  ? ($datos[6]) : '';
                        $fecha_cita_comercial   = !empty($datos[7])  ? ($datos[7]) : '';
                        //se genera la inserccion
                        $table = new ProgramacionCitaDetalles();
                        $table->id_programacion = $id_programacion;
                        $table->id_cliente = $id_cliente;
                        $table->id_tipo_visita = $id_tipo_visita;
                        $table->hora_visita = $hora_visita;
                        $table->nota = $nota;
                        $table->desde = $desde;
                        $table->hasta = $hasta;
                        $table->fecha_cita_comercial = $fecha_cita_comercial;
                    }
                endforeach;*/
              //  $this->redirect(["programacion-citas/view",'id' => $id, 'tokenAcceso' => $tokenAcceso, 'agenteToken' => $agenteToken]);
            
            
        

    public function actionEliminar_detalle($id, $detalle, $agenteToken, $tokenAcceso) {
        $detalle = ProgramacionCitaDetalles::findOne($detalle);
        $detalle->delete();
        $this->SumarCitasCliente($id);
        $this->redirect(["view", 'id' => $id, 'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso]);
    }

    //PROCESO QUE CIERRA LA PROGRAMACION
    public function actionCerrar_programacion($id) {
        $model = ProgramacionCitas::findOne($id);
        $cita = ProgramacionCitaDetalles::find()->where(['=', 'id_programacion', $id])->all();
        $cont = 0;
        $resta = 0;
        $suma = 0;
        $porcentaje = 0;
        foreach ($cita as $citas):
            $cont += 1;
            if ($citas->cumplida == 0) {
                $resta += 1;
            } else {
                $suma += 1;
            }
        endforeach;
        $model->visitas_cumplidas = $suma;
        $model->visitas_no_cumplidas = $resta;
        $model->porcentaje_eficiencia = round(100 * $suma) / $cont;
        $model->proceso_cerrado = 1;
        $model->total_citas = $cont;
        $model->save(false);
        $this->redirect(["listados_citas", 'id' => $id]);
    }

    /**
     * Finds the ProgramacionCitas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProgramacionCitas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ProgramacionCitas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //EXCELES
    //consulta de citas
    
     public function actionExcelconsultaCitas($tableexcel) {                
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
        
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'No PROGRAMACION')
                ->setCellValue('B1', 'DOCUMENTO')
                ->setCellValue('C1', 'VENDEDOR')
                ->setCellValue('D1', 'DESDE')
                ->setCellValue('E1', 'HASTA')
                ->setCellValue('F1', 'V. PROGRAMADA')
                ->setCellValue('G1', 'V. CUMPLIDAS')
                ->setCellValue('H1', 'V. NO CUMPLIDAS')
                ->setCellValue('I1', 'EFICIENCIA')
                ->setCellValue('J1', 'CERRADO');
        $i = 2;

        foreach ($tableexcel as $val) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_programacion)
                    ->setCellValue('B' . $i, $val->agente->nit_cedula)
                    ->setCellValue('C' . $i, $val->agente->nombre_completo)
                    ->setCellValue('D' . $i, $val->fecha_inicio)
                    ->setCellValue('E' . $i, $val->fecha_final)
                    ->setCellValue('F' . $i, $val->total_citas)
                    ->setCellValue('G' . $i, $val->visitas_cumplidas)
                    ->setCellValue('H' . $i, $val->visitas_no_cumplidas)
                    ->setCellValue('I' . $i, $val->porcentaje_eficiencia)
                    ->setCellValue('J' . $i, $val->procesoCerrado);
                   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Citas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Programacion_citas.xlsx"');
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
    
    public function actionExcel_citas($id) {
        $detalle = ProgramacionCitaDetalles::find()->where(['=', 'id_programacion', $id])->all();
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
        
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'No PROGRAMACION')
                ->setCellValue('B1', 'DOCUMENTO')
                ->setCellValue('C1', 'CLIENTE')
                ->setCellValue('D1', 'MUNICIPIO')
                ->setCellValue('E1', 'TIPO VISITA')
                ->setCellValue('F1', 'HORA VISITA')
                ->setCellValue('G1', 'HORA VISITA HISTORIAL')
                ->setCellValue('H1', 'FECHA PROGRAMADA') 
                ->setCellValue('I1', 'FECHA PROGRAMADA HISTORIAL')
                ->setCellValue('J1', 'MOTIVO')
                ->setCellValue('K1', 'USER NAME')
                ->setCellValue('L1', 'CITA CUMPLIDA')
                ->setCellValue('M1', 'FECHA INFORME')
                ->setCellValue('N1', 'OBSERVACIONES')
                ->setCellValue('O1', 'AGENTE COMERCIAL');
        $i = 2;

        foreach ($detalle as $val) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_programacion)
                    ->setCellValue('B' . $i, $val->cliente->nit_cedula)
                    ->setCellValue('C' . $i, $val->cliente->nombre_completo)
                    ->setCellValue('D' . $i, $val->cliente->codigoMunicipio->municipio)
                    ->setCellValue('E' . $i, $val->tipoVisita->nombre_visita)
                    ->setCellValue('F' . $i, $val->hora_visita)
                    ->setCellValue('G' . $i, $val->hora_visita_historial)
                    ->setCellValue('H' . $i, $val->fecha_cita_comercial)
                    ->setCellValue('I' . $i, $val->fecha_cita_comercial_historial)
                    ->setCellValue('J' . $i, $val->nota)
                    ->setCellValue('K' . $i, $val->programacion->user_name)
                    ->setCellValue('L' . $i, $val->citaCumplida)
                    ->setCellValue('M' . $i, $val->fecha_informe)
                    ->setCellValue('N' . $i, $val->descripcion_gestion)
                    ->setCellValue('O' . $i, $val->programacion->agente->nombre_completo);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Citas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Citas_cliente.xlsx"');
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
