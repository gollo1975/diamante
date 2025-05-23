<?php

namespace app\controllers;

use Yii;
use app\models\ConceptoSalarios;
use app\models\ConceptoSalariosSearch;
use app\models\UsuarioDetalle;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\Html;
/**
 * ConceptoSalariosController implements the CRUD actions for ConceptoSalarios model.
 */
class ConceptoSalariosController extends Controller
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
     * Lists all ConceptoSalarios models.
     * @return mixed
     */
     public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',133])->all()){
                $form = new \app\models\FormConceptoSalario();
                $codigo = null;
                $prestacional = null;
                $agrupado = null;
                $debito_credito = null;
                $concepto = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $codigo = Html::encode($form->codigo);
                        $prestacional = Html::encode($form->prestacional);
                        $agrupado = Html::encode($form->agrupado);
                        $debito_credito = Html::encode($form->debito_credito);
                        $concepto = Html::encode($form->concepto);
                        $table = ConceptoSalarios::find()
                                ->andFilterWhere(['=', 'codigo_salario', $codigo])                                                                                              
                                ->andFilterWhere(['=', 'prestacional', $prestacional])
                                ->andFilterWhere(['=','id_agrupado', $agrupado])
                                ->andFilterWhere(['like','nombre_concepto', $concepto])
                                ->andFilterWhere(['=','devengado_deduccion', $debito_credito]);
                         
                        $table = $table->orderBy('id_agrupado ASC , codigo_salario ASC');
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
                              //  $check = isset($_REQUEST['id_credito DESC']);
                                $this->actionExcelConceptoSalario($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = ConceptoSalarios::find()
                        ->orderBy('id_agrupado ASC , codigo_salario ASC');
                $tableexcel = $table->all();
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 15,
                    'totalCount' => $count->count(),
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
                if(isset($_POST['excel'])){
                    //$table = $table->all();
                    $this->actionExcelConceptoSalario($tableexcel);
                }
                
            }
            $to = $count->count();
            return $this->render('index', [
                        'model' => $model,
                        'pagination' => $pages,
                        'form' => $form,
            ]);
        }else{
             return $this->redirect(['site/sinpermiso']);
        }     
        }else{
           return $this->redirect(['site/login']);
        }
   }

    /**
     * Displays a single ConceptoSalarios model.
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
     * Creates a new ConceptoSalarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ConceptoSalarios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ConceptoSalarios model.
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

    /**
     * Deletes an existing ConceptoSalarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
            $this->redirect(["concepto-salarios/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["concepto-salarios/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
            $this->redirect(["concepto-salarios/index"]);
        }
    }
    
    

    /**
     * Finds the ConceptoSalarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConceptoSalarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConceptoSalarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
      //PROCESOS DE EXPORTACION
     public function actionExcelConceptoSalario($tableexcel) {                
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);           
 
                              
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Codigo')
                    ->setCellValue('B1', 'Nombre concepto')
                    ->setCellValue('C1', 'Compone salario')
                    ->setCellValue('D1', 'Inicio de nomina')
                    ->setCellValue('E1', 'Aplica porcentaje')
                    ->setCellValue('F1', 'Porcentaje')
                    ->setCellValue('G1', 'Porcentaje de horas')                    
                    ->setCellValue('H1', 'Prestacional')
                    ->setCellValue('I1', 'IBP')
                    ->setCellValue('J1', 'IBC')
                    ->setCellValue('K1', 'Debito/Credito')
                    ->setCellValue('L1', 'Adicion')
                    ->setCellValue('M1', 'Auxilio transporte')
                    ->setCellValue('N1', 'A. incapacidad')
                    ->setCellValue('O1', 'Pension')
                    ->setCellValue('P1', 'Salud')
                    ->setCellValue('Q1', 'Vacaciones')
                    ->setCellValue('R1', 'Provisiona vacaciones')
                    ->setCellValue('S1', 'A. Indemnizacion')
                    ->setCellValue('T1', 'Tipo adicion')
                    ->setCellValue('U1', 'R. nocturno')
                    ->setCellValue('V1', 'A. hora extra')
                    ->setCellValue('W1', 'A. comisiones')
                    ->setCellValue('X1', 'Licencia')
                    ->setCellValue('Y1', 'FSP')
                    ->setCellValue('Z1', 'A. prima')
                    ->setCellValue('AA1', 'A. cesantias')
                    ->setCellValue('AB1', 'A. intereses')
                    ->setCellValue('AC1', 'Devengado / Deduccion')
                    ->setCellValue('AD1', 'Nombre grupo');
                   
        $i = 2  ;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->codigo_salario)
                    ->setCellValue('B' . $i, $val->nombre_concepto)
                    ->setCellValue('C' . $i, $val->compone)
                    ->setCellValue('D' . $i, $val->inicionomina)
                    ->setCellValue('E' . $i, $val->aplicaPorcentaje)
                    ->setCellValue('F' . $i, $val->porcentaje)
                    ->setCellValue('G' . $i, $val->porcentaje_tiempo_extra)                    
                    ->setCellValue('H' . $i, $val->prestacional)
                    ->setCellValue('I' . $i, $val->ibpPrestacion)
                    ->setCellValue('J' . $i, $val->ibcCotizacion)
                    ->setCellValue('K' . $i, $val->debitocredito)
                    ->setCellValue('L' . $i, $val->adicion)
                    ->setCellValue('M' . $i, $val->auxilioTransporte)
                    ->setCellValue('N' . $i, $val->conceptoIncapacidad)
                    ->setCellValue('O' . $i, $val->conceptoPension)
                    ->setCellValue('P' . $i, $val->conceptoSalud)
                    ->setCellValue('Q' . $i, $val->conceptoVacacion)
                    ->setCellValue('R' . $i, $val->provisionaVacacion)
                    ->setCellValue('S' . $i, $val->provisionaIndemnizacion)
                    ->setCellValue('T' . $i, $val->tipoAdicion)
                    ->setCellValue('U' . $i, $val->recargoNocturno)
                    ->setCellValue('V' . $i, $val->horaExtra)
                    ->setCellValue('W' . $i, $val->comision)
                    ->setCellValue('X' . $i, $val->conceptolicencia)
                    ->setCellValue('Y' . $i, $val->fondoSP)
                    ->setCellValue('Z' . $i, $val->conceptoPrima)
                    ->setCellValue('AA' . $i, $val->conceptoCesantias)
                    ->setCellValue('AB' . $i, $val->conceptoIntereses)
                    ->setCellValue('AC' . $i, $val->devengadoDeduccion);
                    
                    if($val->id_agrupado <> null){
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('AD' . $i, $val->conceptoSalario->concepto);         
                    } else{
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('AD' . $i, 'NOT FOUNT');                         

                    }       
                   
                   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Concepto_Nominas.xlsx"');
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
