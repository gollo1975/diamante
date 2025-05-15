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
use app\models\GrupoProducto;
use app\models\GrupoProductoSearch;
use app\models\UsuarioDetalle;

/**
 * GrupoProductoController implements the CRUD actions for GrupoProducto model.
 */
class GrupoProductoController extends Controller
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
     * Lists all GrupoProducto models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',13])->all()){
                $searchModel = new GrupoProductoSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            } 
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    //PROCESO QUE MUSTRAS TODOS LOS PRODUCTOS O GRUPOS CREADOS
    public function actionIndex_producto_configuracion($sw) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',90])->all()){
                $form = new \app\models\FiltroBusquedaGrupo();
                $grupo = null;
                $nombre = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $grupo = Html::encode($form->grupo);
                        $nombre = Html::encode($form->nombre);
                        $table = \app\models\Productos::find()
                                    ->andFilterWhere(['=', 'id_grupo', $grupo])
                                    ->andFilterWhere(['like', 'nombre_producto', $nombre]);
                        $table = $table->orderBy('id_producto DESC');
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
                            $check = isset($_REQUEST['id_producto  DESC']);
                            $this->actionExcelConsultaProducto($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = \app\models\Productos::find()
                            ->orderBy('id_producto desc');
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
                    if (isset($_POST['excel'])) {
                        $this->actionExcelConsultaProducto($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index_producto_configuracion', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'sw' => $sw,
                        ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }

    /**
     * Displays a single GrupoProducto model.
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
    
    //VISTA QUE ENVIA LA INFORMACION PARA CONFIGURAR EL PRODUCTO
    public function actionView_configuracion($sw, $id_producto) 
    {
        $configuracion = \app\models\ConfiguracionProducto::find()->where(['=','id_producto', $id_producto])->orderBy('id_fase ASC')->all();
        $model = \app\models\Productos::findOne($id_producto);
        if(isset($_POST["actualizamateriaprima"])){
            if(isset($_POST["listado_materia"])){
                $intIndice = 0;
                foreach ($_POST["listado_materia"] as $intCodigo):
                    $table = \app\models\ConfiguracionProducto::findOne($intCodigo);
                    $table->id_fase = $_POST["fase"][$intIndice];    
                    $table->porcentaje_aplicacion = $_POST["porcentaje_aplicacion"][$intIndice];
                    $table->codigo_homologacion = $_POST["codigo_homologacion"][$intIndice];
                    $table->save(false);
                    $intIndice++;
                endforeach;
                return $this->redirect(['view_configuracion', 'sw' => $sw, 'id_producto' => $id_producto]);
            }
        }    
        return $this->render('view_configuracion', [
            'model' => $model,
            'configuracion' => $configuracion,
            'sw' => $sw, 
            'id_producto' => $id_producto,
        ]);
    }
    
     //VISTA QUE ENVIA LA INFORMACION PARA CONFIGURAR EK ANALISIS DE AUDITORIA
    public function actionView_analisis($id_grupo, $sw, $id_producto) 
    {
        $analisis = \app\models\ConfiguracionProductoProceso::find()->where(['=','id_producto', $id_producto])->orderBy('id_analisis ASC')->all();
        $model = \app\models\Productos::findOne($id_producto);
        if(isset($_POST["actualizalineas"])){
            if(isset($_POST["listado_analisis_cargados"])){
                $intIndice = 0;
                foreach ($_POST["listado_analisis_cargados"] as $intCodigo):
                    $table = \app\models\ConfiguracionProductoProceso::findOne($intCodigo);
                    $table->id_etapa = $_POST["etapa"][$intIndice];    
                    $table->id_especificacion = $_POST["especificaciones"][$intIndice];
                    $table->resultado = $_POST["resultado"][$intIndice];
                    $table->save(false);
                    $intIndice++;
                endforeach;
                return $this->redirect(['view_analisis','id_grupo' =>$id_grupo, 'sw' => $sw, 'id_producto' =>$id_producto]);
            }
        }    
        return $this->render('view_analisis', [
            'model' => $model,
            'analisis' => $analisis,
            'sw' => $sw,  
            'id_producto' => $id_producto,
        ]);
    }
    
    //BUSCAR MATERIA PRIMA PARA EL PRODUCTO
     public function actionBuscarmateriaprima($id_grupo, $sw, $id_producto){
        $operacion = \app\models\MateriaPrimas::find()->where(['=','id_solicitud', 1])->orderBy('materia_prima ASC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
       
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);    
                $operacion = \app\models\MateriaPrimas::find()
                        ->andFilterWhere(['like','materia_prima', $q])
                        ->orFilterWhere(['=','codigo_materia_prima', $q])
                        ->andWhere(['=','id_solicitud', 1])
                        ->orderBy('materia_prima ASC')->all();                    
            } else {
                $form->getErrors();
            }                    
        }else{
            $operacion = \app\models\MateriaPrimas::find()->where(['=','id_solicitud', 1])->orderBy('materia_prima ASC')->all();
        }
        //PROCESO DE GUARDAR
         if (isset($_POST["guardarmateriaprima"])) {
            if(isset($_POST["nuevo_materia_prima"])){
                foreach ($_POST["nuevo_materia_prima"] as $intCodigo) {
                    $materia = \app\models\MateriaPrimas::findOne($intCodigo);
                    $table = new \app\models\ConfiguracionProducto();
                    $table->id_grupo = $id_grupo;
                    $table->id_producto = $id_producto;
                    $table->id_materia_prima = $intCodigo;
                    $table->codigo_materia =  $materia->codigo_materia_prima;
                    $table->nombre_materia_prima = $materia->materia_prima;
                    $table->id_fase = 1;
                    $table->user_name =  Yii::$app->user->identity->username;
                    $table->save(false);
                      
                }
                return $this->redirect(['view_configuracion','id_producto' => $id_producto, 'sw' => $sw]);
            }
        }
        return $this->render('importar_materia_prima', [
            'operacion' => $operacion,            
            'id_grupo' => $id_grupo,
            'form' => $form,
            'sw' => $sw,
            'id_producto' => $id_producto,
        ]);
    }

    //BUSCAR CONCEPTOS DE ANALISIS PARA AGREGAR AL PROCESO
     public function actionBuscar_concepto_analisis($id_grupo, $sw, $id_producto){
        $operacion = \app\models\ConceptoAnalisis::find()->orderBy('id_etapa ASC')->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        $etapa = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);  
                $etapa = Html::encode($form->etapa); 
                    $operacion = \app\models\ConceptoAnalisis::find()
                            ->where(['like','concepto',$q])
                            ->orwhere(['=','id_analisis',$q])
                            ->andFilterWhere(['=','id_etapa',$etapa]);
                    $operacion = $operacion->orderBy('id_etapa ASC');                    
                    $count = clone $operacion;
                    $to = $count->count();
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count()
                    ]);
                    $operacion = $operacion
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            $table = \app\models\ConceptoAnalisis::find()->orderBy('id_etapa ASC');
            $tableexcel = $table->all();
            $count = clone $table;
            $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
            ]);
             $operacion = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
        //PROCESO DE GUARDAR
         if (isset($_POST["guardarnuevoconcepto"])) {
            if(isset($_POST["nuevo_concepto"])){
                foreach ($_POST["nuevo_concepto"] as $intCodigo) {
                    //consulta para no duplicar
                    $registro = \app\models\ConfiguracionProductoProceso::find()->where(['=','id_producto', $id_producto])
                                                                   ->andWhere(['=','id_analisis', $intCodigo])->one();
                    if(!$registro){
                        $materia = \app\models\ConceptoAnalisis::findOne($intCodigo);
                        $table = new \app\models\ConfiguracionProductoProceso();
                        $table->id_grupo = $id_grupo;
                        $table->id_analisis = $intCodigo;
                        $table->id_producto = $id_producto;
                        $table->id_etapa = $materia->id_etapa;
                        $table->user_name =  Yii::$app->user->identity->username;
                        $table->save(false);
                    }    
                }
                return $this->redirect(['view_analisis','id_grupo' => $id_grupo, 'sw' => $sw,'id_producto' => $id_producto]);
            }
        }
        return $this->render('importar_concepto_analisis', [
            'operacion' => $operacion,            
            'pagination' => $pages,
            'id_grupo' => $id_grupo,
            'form' => $form,
            'sw' => $sw,
            'id_producto' => $id_producto,
        ]);
    }

    
    /**
     * Creates a new GrupoProducto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GrupoProducto();

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
     * Updates an existing GrupoProducto model.
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
     * Deletes an existing GrupoProducto model.
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
            $this->redirect(["grupo-producto/index"]);
        } catch (IntegrityException $e) {
            $this->redirect(["grupo-producto/index"]);
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociados en otros procesos');
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, esta asociado en otros procesos');
            $this->redirect(["grupo-producto/index"]);
        }
    }
    
    
    //ELIMINAR ITEM DEL ANALISIS
    public function actionEliminarmateria($detalle, $sw, $id_producto) {
        
        if (Yii::$app->request->post()) {
          
            if ((int) $detalle) {
                try {
                    \app\models\ConfiguracionProducto::deleteAll("id=:id", [":id" => $detalle]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    return $this->redirect(["view_configuracion", 'sw' => $sw, 'id_producto' => $id_producto]);  
                } catch (IntegrityException $e) {
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, esta asociado en otros procesos');
                    return $this->redirect(["view_configuracion", 'sw' => $sw, 'id_producto' => $id_producto]);                    

                } catch (\Exception $e) {

                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, esta asociado en otros procesos');
                    return $this->redirect(["view_configuracion", 'sw' => $sw, 'id_producto' => $id_producto]);                     

                }
            } else {
                // echo "Ha ocurrido un error al eliminar el registros, redireccionando ...";
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("grupo-producto/view_configuracion") . "'>";
            }
        }
    }
    
    
    //PERMITE ELIMINAR LOS ITEMS DE ANALISIS
     //ELIMINAR ITEM DEL ANALISIS
    public function actionEliminar_analisis($id_grupo, $id_proceso, $sw, $id_producto) {
        
        if (Yii::$app->request->post()) {
          
            if ((int) $id_proceso) {
                try {
                    \app\models\ConfiguracionProductoProceso::deleteAll("id_proceso=:id_proceso", [":id_proceso" => $id_proceso]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    return $this->redirect(["view_analisis", 'sw' => $sw, 'id_grupo' => $id_grupo, 'id_producto' => $id_producto]);  
                } catch (IntegrityException $e) {
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, esta asociado en otros procesos');
                    return $this->redirect(["view_analisis", 'id_grupo' => $id_grupo, 'sw' => $sw, 'id_producto' => $id_producto]);                    

                } catch (\Exception $e) {

                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar este registro, esta asociado en otros procesos');
                    return $this->redirect(["view_analisis", 'sw' => $sw, 'id_grupo' => $id_grupo, 'id_producto' => $id_producto]);                     

                }
            } else {
                // echo "Ha ocurrido un error al eliminar el registros, redireccionando ...";
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("grupo-producto/view_analisis") . "'>";
            }
        } 
    }
    
    /**
     * Finds the GrupoProducto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GrupoProducto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GrupoProducto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
     //EXCEL QUE PERMITE ESPORTAR LOS CREDITOS
    public function actionExcelConsultaProducto($tableexcel) {                
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
     
         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'NOMBRE PRODUCTO')
                    ->setCellValue('C1', 'NOMBRE DEL GRUPO')
                    ->setCellValue('D1', 'MARCA')
                    ->setCellValue('E1', 'ENTRADAS') 
                    ->setCellValue('F1', 'SALIDA')
                    ->setCellValue('G1', 'SALDOS')
                    ->setCellValue('H1', 'USER NAME') ;                   
                   
        $i = 2  ;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_producto)
                    ->setCellValue('B' . $i, $val->nombre_producto)
                    ->setCellValue('C' . $i, $val->grupo->nombre_grupo)
                    ->setCellValue('D' . $i, $val->marca->marca)
                    ->setCellValue('E' . $i, $val->entradas)
                    ->setCellValue('F' . $i, $val->salidas)
                    ->setCellValue('G' . $i, $val->saldo_unidades)                    
                    ->setCellValue('H' . $i, $val->user_name);
                  
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Productos.xlsx"');
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
