<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Html;
use yii\data\Pagination;
use yii\bootstrap\Modal;

use app\models\EstudiosEmpleados;
use app\models\UsuarioDetalle;

/**
 * EstudiosEmpleadosController implements the CRUD actions for EstudiosEmpleados model.
 */
class EstudiosEmpleadosController extends Controller
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
     * Lists all EstudiosEmpleados models.
     * @return mixed
     */
    public function actionIndex($token = 0)
    {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 91])->all()) {
                $form = new \app\models\FormFiltroEstudios();
                $id_empleado = null;
                $id_tipo_estudio = null;
                 $documento = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $id_empleado = Html::encode($form->id_empleado);
                        $documento = Html::encode($form->documento);
                        $id_tipo_estudio = Html::encode($form->id_tipo_estudio);
                        $table = EstudiosEmpleados::find()
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])
                                ->andFilterWhere(['=', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_profesion', $id_tipo_estudio]);
                        $table = $table->orderBy('id DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 12,
                            'totalCount' => $count->count()
                        ]);
                        $modelo = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id DESC']);
                            $this->actionExcelconsultaEstudio($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = EstudiosEmpleados::find()
                             ->orderBy('id DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 12,
                        'totalCount' => $count->count(),
                    ]);
                    $modelo = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        //$table = $table->all();
                        $this->actionExcelconsultaEstudio($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'modelo' => $modelo,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }

    //consulta de estudios
     public function actionIndex_search($token = 1)
    {
        if (Yii::$app->user->identity) {
            if (UsuarioDetalle::find()->where(['=', 'codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=', 'id_permiso', 91])->all()) {
                $form = new \app\models\FormFiltroEstudios();
                $id_empleado = null;
                $id_tipo_estudio = null;
                 $documento = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $id_empleado = Html::encode($form->id_empleado);
                        $documento = Html::encode($form->documento);
                        $id_tipo_estudio = Html::encode($form->id_tipo_estudio);
                        $table = EstudiosEmpleados::find()
                                ->andFilterWhere(['=', 'id_empleado', $id_empleado])
                                ->andFilterWhere(['=', 'documento', $documento])
                                ->andFilterWhere(['=', 'id_profesion', $id_tipo_estudio]);
                        $table = $table->orderBy('id DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 15,
                            'totalCount' => $count->count()
                        ]);
                        $modelo = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id DESC']);
                            $this->actionExcelconsultaEstudio($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = EstudiosEmpleados::find()
                             ->orderBy('id DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $modelo = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        //$table = $table->all();
                        $this->actionExcelconsultaEstudio($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index_search', [
                            'modelo' => $modelo,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                ]);
            } else {
                return $this->redirect(['site/sinpermiso']);
            }
        } else {
            return $this->redirect(['site/login']);
        }
    }
    
    /**
     * Displays a single EstudiosEmpleados model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
             'token' => $token,
        ]);
    }

    /**
     * Creates a new EstudiosEmpleados model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EstudiosEmpleados();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $empleado = \app\models\Empleados::findOne($model->id_empleado);
            $model->user_name = Yii::$app->user->identity->username;
            $model->documento = $empleado->nit_cedula;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EstudiosEmpleados model.
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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EstudiosEmpleados model.
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
     * Finds the EstudiosEmpleados model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EstudiosEmpleados the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EstudiosEmpleados::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
      public function actionExcelconsultaEstudio($tableexcel) {                
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
                    ->setCellValue('A1', 'Id')
                    ->setCellValue('B1', 'Documento')
                    ->setCellValue('C1', 'Empleado')
                    ->setCellValue('D1', 'Tipo estudio')
                    ->setCellValue('E1', 'Institucion')
                    ->setCellValue('F1', 'Titulo obtenido')
                    ->setCellValue('G1', 'Año cursado')                    
                    ->setCellValue('H1', 'Fecha inicio')
                    ->setCellValue('I1', 'Fecha Final')
                    ->setCellValue('J1', 'Graduado')
                    ->setCellValue('K1', 'Registro')
                    ->setCellValue('L1', 'Validar vencimiento')
                    ->setCellValue('M1', 'Usuario')
                    ->setCellValue('N1', 'Observacion');
        $i = 2  ;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A' . $i, $val->id)
                    ->setCellValue('B' . $i, $val->documento)
                    ->setCellValue('C' . $i, $val->empleado->nombre_completo)
                    ->setCellValue('D' . $i, $val->profesion->profesion)
                    ->setCellValue('E' . $i, $val->institucion_educativa)
                    ->setCellValue('F' . $i, $val->titulo_obtenido)                    
                    ->setCellValue('G' . $i, $val->anio_cursado)
                    ->setCellValue('H' . $i, $val->fecha_inicio)
                    ->setCellValue('I' . $i, $val->fecha_terminacion)
                    ->setCellValue('J' . $i, $val->graduadoEstudio)
                    ->setCellValue('K' . $i, $val->registro)
                    ->setCellValue('L' . $i, $val->validarEstudio)
                    ->setCellValue('M' . $i, $val->user_name)
                    ->setCellValue('N' . $i, $val->observacion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Estudios');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Estudios.xlsx"');
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
