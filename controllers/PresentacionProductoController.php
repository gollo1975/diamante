<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\Html;
//models
use app\models\PresentacionProducto;
use app\models\PresentacionProductoSearch;
use app\models\UsuarioDetalle;


/**
 * PresentacionProductoController implements the CRUD actions for PresentacionProducto model.
 */
class PresentacionProductoController extends Controller
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
     * Lists all PresentacionProducto models.
     * @return mixed
     */
    public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',16])->all()){
                $form = new \app\models\FormConsultaPresentacion();
                $grupo = null;
                $producto = null;
                $presentacion = null; $orden = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {                        
                        $grupo = Html::encode($form->grupo);
                        $producto = Html::encode($form->producto);
                        $presentacion = Html::encode($form->presentacion);
                        $orden = Html::encode($form->orden);
                        $table = PresentacionProducto::find()
                                ->andFilterWhere(['=', 'id_grupo', $grupo])                                                                                              
                                ->andFilterWhere(['=', 'id_producto', $producto])
                                ->andFilterWhere(['like','descripcion', $presentacion]);
                        if ($orden) {
                           $table = $table->orderBy($orden);
                        }
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
                                $check = isset($_REQUEST['id_presentacion DESC']);
                                $this->actionExcelconsultaPresentacion($tableexcel);
                            }
                } else {
                        $form->getErrors();
                }                    
            } else {
                $table = PresentacionProducto::find()
                        ->orderBy('id_presentacion DESC');
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
                    $this->actionExcelconsultaPresentacion($tableexcel);
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

    /**
     * Displays a single PresentacionProducto model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $listadoEmpaque = \app\models\ConfiguracionMaterialEmpaque::find()->where(['=','id_presentacion', $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'listadoEmpaque' => $listadoEmpaque,
        ]);
    }

    /**
     * Creates a new PresentacionProducto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PresentacionProducto();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $producto = \app\models\Productos::findOne($model->id_producto);
            $model->id_grupo = $producto->id_grupo;
            $model->user_name = Yii::$app->user->identity->username;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PresentacionProducto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $producto = \app\models\Productos::findOne($model->id_producto);
            $model->id_grupo = $producto->id_grupo;
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PresentacionProducto model.
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
            $this->redirect(["presentacion-producto/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["presentacion-producto/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociado en otros procesos');
            $this->redirect(["presentacion-producto/index"]);
        }
    }
    
    //eliminando detalles
     public function actionEliminar_detalles($id, $id_detalle)
    {
        try {
            $dato = \app\models\ConfiguracionMaterialEmpaque::findOne($id_detalle);
            $dato->delete();
            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
            $this->SumarItems($id);
            $this->redirect(["presentacion-producto/view",'id' => $id]);
            
        } catch (IntegrityException $e) {
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
            $this->redirect(["presentacion-producto/view",'id' => $id]);
            
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociado en otros procesos');
           $this->redirect(["presentacion-producto/view",'id' => $id]);
        }
    }
    //ELIMINAR PRESENTACION
     public function actionEliminar_linea($id) {
        
        if (Yii::$app->request->post()) {
            $registro = PresentacionProducto::findOne($id);
            if ((int) $id) {
                try {
                    PresentacionProducto::deleteAll("id_presentacion=:id_presentacion", [":id_presentacion" => $id]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    $this->redirect(["presentacion-producto/index"]);
                } catch (IntegrityException $e) {
                   
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro Nro: ' .$registro->id_presentacion .', tiene registros asociados en otros procesos');
                    return $this->redirect(["presentacion-producto/index"]);
                } catch (\Exception $e) {
                    
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro Nro: ' .$registro->id_presentacion .', tiene registros asociados en otros procesos');
                    return $this->redirect(["presentacion-producto/index"]);
                }
            } else {
                // echo "Ha ocurrido un error al eliminar el registros, redireccionando ...";
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("presentacion-producto/index") . "'>";
            }
        } else {
            return $this->redirect(["presentacion-producto/index"]);
        }
    }
    
    //IMPORTAR MATERIAL DE EMPAQUE
    //BUSCAR MATERIA PRIMA PARA EL PRODUCTO
     public function actionBuscar_material_empaque($id){
       
        $operacion = \app\models\MateriaPrimas::find()->where(['=','id_solicitud', 2])->orderBy('materia_prima ASC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
       
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);    
                $operacion = \app\models\MateriaPrimas::find()
                        ->andFilterWhere(['like','materia_prima', $q])
                        ->orFilterWhere(['=','codigo_materia_prima', $q])
                        ->andWhere(['=','id_solicitud', 2])
                        ->orderBy('materia_prima ASC')->all();                    
            } else {
                $form->getErrors();
            }                    
        }else{
            $operacion = \app\models\MateriaPrimas::find()->where(['=','id_solicitud', 2])->orderBy('materia_prima ASC')->all();
        }
        //PROCESO DE GUARDAR
         if (isset($_POST["guardarmateriaprima"])) {
            if(isset($_POST["nuevo_materia_prima"])){
                foreach ($_POST["nuevo_materia_prima"] as $intCodigo) {
                    //consulta para no duplicar
                    $registro = \app\models\ConfiguracionMaterialEmpaque::find()->where(['=','id_presentacion', $id])
                                                                   ->andWhere(['=','id_materia_prima', $intCodigo])->one();
                    if(!$registro){
                        $materia = \app\models\MateriaPrimas::findOne($intCodigo);
                        $table = new \app\models\ConfiguracionMaterialEmpaque();
                        $table->id_materia_prima = $intCodigo;
                        $table->codigo_material =  $materia->codigo_materia_prima;
                        $table->id_presentacion = $id;
                        $table->user_name =  Yii::$app->user->identity->username;
                        $table->save(false);
                        $this->SumarItems($id);
                    }    
                }
                return $this->redirect(['view','id' => $id]);
            }
        }
        return $this->render('importar_material_empaque', [
            'operacion' => $operacion,            
            'form' => $form,
            'id' => $id,
        ]);
    }
    
    //SUMA LOS ITEMS DE MATERIA DE EMPAQUE
    protected function SumarItems($id) {
        $modelo = PresentacionProducto::findOne($id);
        $cantidad = \app\models\ConfiguracionMaterialEmpaque::find()->where(['=','id_presentacion', $id])->all();
        $modelo->total_item = count($cantidad);
        $modelo->save();
    }

    /**
     * Finds the PresentacionProducto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PresentacionProducto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PresentacionProducto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //excelles
     public function actionExcelconsultaPresentacion($tableexcel) {                
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
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'PRESENTACION')
                    ->setCellValue('C1', 'PRODUCTO')
                    ->setCellValue('D1', 'GRUPO')
                    ->setCellValue('E1', 'MEDIDA')
                    ->setCellValue('F1', 'FECHA CREACION')
                    ->setCellValue('G1', 'USER NAME') ;
        $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_presentacion)
                    ->setCellValue('B' . $i, $val->descripcion)
                    ->setCellValue('C' . $i, $val->producto->nombre_producto)
                    ->setCellValue('D' . $i, $val->grupo->nombre_grupo)
                    ->setCellValue('E' . $i, $val->medidaProducto->descripcion)
                    ->setCellValue('F' . $i, $val->fecha_registro)
                    ->setCellValue('G' . $i, $val->user_name);
                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PresentacionProducto.xlsx"');
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
