<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
//modes
use app\models\TipoRack;
use app\models\TipoRackSearch;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaTipoRack;
/**
 * TipoRackController implements the CRUD actions for TipoRack model.
 */
class TipoRackController extends Controller
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
     * Lists all TipoRack models.
     * @return mixed
     */
      public function actionIndex($token=0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso', 71])->all()){
                $form = new \app\models\FiltroBusquedaTipoRack();
                $numero = null;
                $descripcion = null;
                $estado = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $descripcion = Html::encode($form->descripcion);
                        $estado = Html::encode($form->estado);
                        $table = TipoRack::find()
                                ->andFilterWhere(['=', 'numero_rack', $numero])
                                ->andFilterWhere(['like', 'descripcion', $descripcion])
                                ->andFilterWhere(['=', 'estado', $estado]);
                        $table = $table->orderBy('id_rack ASC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 10,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                    ->all();
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaRack($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = TipoRack::find()
                            ->orderBy('id_rack ASC');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaRack($tableexcel);
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

    /**
     * Displays a single TipoRack model.
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
     * Creates a new TipoRack model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($sw = 0)
    {
        $model = new TipoRack();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $table =  new TipoRack();
            $table->descripcion = $model->descripcion;
            $table->capacidad_instalada = $model->capacidad_instalada;
            $table->medidas = $model->medidas;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'sw' => $sw,
        ]);
    }

    /**
     * Updates an existing TipoRack model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be foundmed
     */
    public function actionUpdate($id, $sw = 1)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
       if ($model->load(Yii::$app->request->post())) {
            $table = TipoRack::findOne($id);
            $table->descripcion = $model->descripcion;
            $table->medidas = $model->medidas;
            $table->capacidad_instalada = $model->capacidad_instalada;
            if($model->estado == 0){ 
                $table->estado = $model->estado;
                $table->save(false);
                return $this->redirect(['index']);
            }else{
               
                if($table->capacidad_actual <= 0){
                    $table->estado = $model->estado;
                    $table->save(false);
                    return $this->redirect(['index']);
                }else{
                    Yii::$app->getSession()->setFlash('info', 'No se puede INACTIVAR  este RACK porque tiene unidades almacenadas.');
                    return $this->redirect(['index']);
                }
            }    
            
        }

        return $this->render('update', [
            'model' => $model,
            'sw' => $sw,
        ]);
        
    }

    /**
     * Deletes an existing TipoRack model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionGenerar_numero($id) {
        $model = TipoRack::findOne($id);
        $consecutivo = \app\models\Consecutivos::findOne(10); 
        $model->numero_rack = $consecutivo->numero_inicial + 1;
        $model->save();
        $consecutivo->numero_inicial = $model->numero_rack;
        $consecutivo->save();
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the TipoRack model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TipoRack the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TipoRack::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionExcelconsultaRack($tableexcel) {                
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
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'NUMERO RACK')
                    ->setCellValue('C1', 'DESCRIPCION')
                    ->setCellValue('D1', 'MEDIDAS')
                    ->setCellValue('E1', 'CAPACIDAD INSTALADA')
                    ->setCellValue('F1', 'CAPACIDAD ACTUAL')
                    ->setCellValue('G1', 'USER NAME')
                    ->setCellValue('H1', 'ACTIVO')
                    ->setCellValue('I1', 'FECHA_REGISTRO');
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_rack)
                    ->setCellValue('B' . $i, $val->numero_rack)
                    ->setCellValue('C' . $i, $val->descripcion)
                    ->setCellValue('D' . $i, $val->medidas)
                    ->setCellValue('E' . $i, $val->capacidad_instalada)
                    ->setCellValue('F' . $i, $val->capacidad_actual)
                    ->setCellValue('G' . $i, $val->user_name)
                    ->setCellValue('H' . $i, $val->estadoActivo)
                    ->setCellValue('I' . $i, $val->fecha_registro);
                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Listados_racks.xlsx"');
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
