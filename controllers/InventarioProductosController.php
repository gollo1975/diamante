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
use app\models\InventarioProductos;
use app\models\InventarioProductosSearch;
use app\models\UsuarioDetalle;
use app\models\FiltroBusquedaInventario;
use app\models\OrdenProduccion;
use app\models\OrdenProduccionProductos;


/**
 * InventarioProductosController implements the CRUD actions for InventarioProductos model.
 */
class InventarioProductosController extends Controller
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
     * Lists all InventarioProductos models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',19])->all()){
                $form = new FiltroBusquedaInventario();
                $codigo = null;
                $inventario_inicial = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $grupo = null;
                $producto = null;
                $busqueda_vcto = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $inventario_inicial = Html::encode($form->inventario_inicial);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $producto = Html::encode($form->producto);
                        $grupo = Html::encode($form->grupo);
                        $busqueda_vcto = Html::encode($form->busqueda_vcto);
                        if ($busqueda_vcto == 0){
                            $table = InventarioProductos::find()
                                        ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                        ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                        ->andFilterWhere(['like', 'nombre_producto', $producto])
                                        ->andFilterWhere(['=', 'inventario_inicial', $inventario_inicial])
                                        ->andFilterWhere(['=', 'id_grupo', $grupo]);
                        }else{
                            $table = InventarioProductos::find()
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['between', 'fecha_vencimiento', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['like', 'nombre_producto', $producto])
                                    ->andFilterWhere(['=', 'inventario_inicial', $inventario_inicial])
                                    ->andFilterWhere(['=', 'id_grupo', $grupo]);
                        }    
                        $table = $table->orderBy('id_inventario DESC');
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
                            $check = isset($_REQUEST['id_inventario  DESC']);
                            $this->actionExcelConsultaInventario($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = InventarioProductos::find()
                            ->orderBy('id_inventario DESC');
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
                        $this->actionExcelConsultaInventario($tableexcel);
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

    //CONSULTA DE INVENTARIO
     public function actionSearch_consulta_inventario($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',20])->all()){
                $form = new FiltroBusquedaInventario();
                $codigo = null;
                $inventario_inicial = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $grupo = null;
                $producto = null;
                $busqueda_vcto = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $inventario_inicial = Html::encode($form->inventario_inicial);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $producto = Html::encode($form->producto);
                        $grupo = Html::encode($form->grupo);
                        $busqueda_vcto = Html::encode($form->busqueda_vcto);
                        if ($busqueda_vcto == 0){
                            $table = InventarioProductos::find()
                                        ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                        ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                        ->andFilterWhere(['like', 'nombre_producto', $producto])
                                        ->andFilterWhere(['=', 'inventario_inicial', $inventario_inicial])
                                        ->andFilterWhere(['=', 'id_grupo', $grupo]);
                        }else{
                            $table = InventarioProductos::find()
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['between', 'fecha_vencimiento', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['like', 'nombre_producto', $producto])
                                    ->andFilterWhere(['=', 'inventario_inicial', $inventario_inicial])
                                    ->andFilterWhere(['=', 'id_grupo', $grupo]);
                        }    
                        $table = $table->orderBy('id_inventario DESC');
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
                            $check = isset($_REQUEST['id_inventario  DESC']);
                            $this->actionExcelConsultaInventario($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = InventarioProductos::find()
                            ->orderBy('id_inventario DESC');
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
                        $this->actionExcelConsultaInventario($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_consulta_inventario', [
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
    //REGLA COMERCIAL DE PRODUCTO
    
    public function actionRegla_comercial() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',53])->all()){
                $form = new FiltroBusquedaInventario();
                $codigo = null;
                $inventario_inicial = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $grupo = null;
                $producto = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $inventario_inicial = Html::encode($form->inventario_inicial);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $producto = Html::encode($form->producto);
                        $grupo = Html::encode($form->grupo);
                        $table = InventarioProductos::find()
                                ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                ->andFilterWhere(['like', 'nombre_producto', $producto])
                                ->andFilterWhere(['=', 'inventario_inicial', $inventario_inicial])
                                ->andFilterWhere(['=', 'id_grupo', $grupo])
                                ->andWhere(['=', 'aplica_regla_comercial', 1]);
                        
                        $table = $table->orderBy('id_inventario DESC');
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
                            $check = isset($_REQUEST['id_inventario  DESC']);
                            $this->actionExcelConsultaInventario($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = InventarioProductos::find()->andWhere(['=', 'aplica_regla_comercial', 1])
                            ->orderBy('id_inventario DESC');
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
                        $this->actionExcelConsultaInventario($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_regla_comercial', [
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
     * Displays a single InventarioProductos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $model =  $this->findModel($id);
        $table = OrdenProduccion::find()->where(['=','id_grupo', $model->id_grupo]);
        $tableexcel = $table->all();
        $count = clone $table;
        $pages = new Pagination([
            'pageSize' => 5,
            'totalCount' => $count->count(),
        ]);
        $detalle_entrada = $table
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        $to = $count->count();
        return $this->render('view', [
            'model' => $model,
            'token' => $token,
            'detalle_entrada' => $detalle_entrada,
            'pagination' => $pages,
        ]);
    }
    //VISTA PARA SUBIR ARCHIOS DE IMAGEN
      public function actionView_archivo($id, $token)
    {
        $model =  $this->findModel($id);
        return $this->render('view_archivo', [
            'model' => $model,
            'token' => $token,
            ]);
    }
    
    //vista de la regla
       public function actionView_regla($id)
    {
        $regla_comercial = \app\models\ProductoReglaComercial::find()->where(['=','id_inventario', $id])->orderBy('id_regla DESC')->all();
        $model =  $this->findModel($id);
        if(isset($_POST["actualizaregla"])){
            if(isset($_POST["listado_regla"])){
                $intIndice = 0;
                foreach ($_POST["listado_regla"] as $intCodigo):
                    $table = \app\models\ProductoReglaComercial::find()->where(['=','id_regla', $intCodigo])->one();
                    $table->limite_venta = $_POST["limite_venta"]["$intIndice"];
                    $table->limite_presupuesto = $_POST["limite_presupuesto"]["$intIndice"];
                    $table->estado_regla = $_POST["estado_regla"]["$intIndice"];
                    $table->save(false);
                    $intIndice++;
                endforeach;
                return $this->redirect(['view_regla','id' =>$id]);
            }
        }   
        return $this->render('view_regla', [
            'model' => $model,
            'regla_comercial' => $regla_comercial,
            ]);
    }
   // nuevo regla comercial
    public function actionNueva_regla_producto($id) {
        $model = new \app\models\FormModeloNuevaRegla();
        
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["crear_regla_comercial"])) {
                    $table = new \app\models\ProductoReglaComercial();
                    $table->id_inventario = $id;
                    $table->limite_venta = $model->limite_venta;
                    $table->limite_presupuesto = $model->limite_presupuesto;
                    $table->fecha_cierre = $model->fecha_cierre;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $this->redirect(["inventario-productos/view_regla", 'id' => $id]);
                }  
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('new_regla_comercial', [
            'model' => $model,
            'id' => $id,
        ]);
    } 
    
    /**
     * Creates a new InventarioProductos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($IdToken = 0)
    {
        $model = new InventarioProductos();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $presentacion = \app\models\PresentacionProducto::findOne($model->id_presentacion);
            if($presentacion){
                $model->user_name = Yii::$app->user->identity->username;
                $model->codigo_ean = $model->codigo_producto;
                $model->nombre_producto = $presentacion->descripcion;
                $model->stock_unidades = $model->unidades_entradas;
                $model->save();
                $id = $model->id_inventario;
                $this->ActualizarTotalesProducto($id);
            }    
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'IdToken' => $IdToken,
        ]);
    }
   //PROCESO QUE ACTUALIZA LOS PRECIOS DEL PRODUCTO
   protected function ActualizarTotalesProducto($id) {
       $inventario = InventarioProductos::findOne($id);
       $subtotal =0;
       $impuesto = 0;
       $total = 0;
       $subtotal = $inventario->unidades_entradas *$inventario->costo_unitario;
       if($inventario->aplica_iva == 0){
          $impuesto = round($subtotal * $inventario->porcentaje_iva)/100;    
       }else{
           $impuesto = 0;
       }
       $inventario->subtotal = $subtotal;
       $inventario->valor_iva = $impuesto;
       $inventario->total_inventario = $subtotal + $impuesto;
       $inventario->save();
       }
    
    /**
     * Updates an existing InventarioProductos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $IdToken = 1)
    {
        $model = $this->findModel($id);
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $presentacion = \app\models\PresentacionProducto::findOne($model->id_presentacion);
            if($presentacion){
                $model->codigo_ean = $model->codigo_producto;
                $model->nombre_producto = $presentacion->descripcion;
                $model->stock_unidades = $model->unidades_entradas;
                $model->save();
                $this->ActualizarTotalesProducto($id);
            }    
            return $this->redirect(['index']);
        }
        
        return $this->render('update', [
            'model' => $model,
            'IdToken' => $IdToken,
        ]);
    }

    //PROCESO QUE BUSCA LAS PRESENTACIONES DEL PRODUCTO
     public function actionPresentacion($id){
        $rows = \app\models\PresentacionProducto::find()->where(['=','id_grupo', $id])->orderBy('descripcion desc')->all();

        echo "<option value='' required>Seleccione...</option>";
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id_presentacion' required>$row->descripcion</option>";
            }
        }
    }
    // ASIGNAR PARAMETROS A LOS PRODUCTOS PARA EL PRESUPUESTO
    public function actionAsignar_producto_presupuesto() {
       if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',37])->all()){
                $form = new \app\models\FormModeloBuscar();
                $q = null;
                $nombre = null;
                $parametros = InventarioProductos::find()->where(['=','aplica_presupuesto', 1])->all();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $q = Html::encode($form->q);
                        $nombre = Html::encode($form->nombre);
                        if ($q == ''){
                            $table = InventarioProductos::find()
                                ->Where(['like','nombre_producto', $nombre])
                                ->andwhere(['=','venta_publico', 0])
                                ->andWhere(['=','aplica_presupuesto', 0])    
                                ->andwhere(['>','stock_unidades', 0]);
                        }else{
                            $table = InventarioProductos::find()
                                ->where(['=','codigo_producto', $q])
                                ->andwhere(['=','venta_publico', 0])
                                ->andWhere(['=','aplica_presupuesto', 0]) 
                                ->andwhere(['>','stock_unidades', 0]);
                        } 
                        $table = $table->orderBy('nombre_producto ASC');  
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
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = InventarioProductos::find()->where(['>','stock_unidades', 0])->andWhere(['=','venta_publico', 0])->andWhere(['=','aplica_presupuesto', 0]) 
                            ->orderBy('nombre_producto ASC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }
                ////PROCESO QUE ADICION PRODUCTOS AL PRESUPUESTO COMERCILA
                if(isset($_POST["cargar_producto"])){
                    if(isset($_POST["nuevo_producto_presupuesto"])){
                        foreach ($_POST["nuevo_producto_presupuesto"] as $intCodigo):
                            $table = InventarioProductos::findOne($intCodigo);
                            $table->aplica_presupuesto = 1;
                            $table->save(false);
                        endforeach;
                        $parametros = InventarioProductos::find()->where(['=','aplica_presupuesto', 1])->all();
                        return $this->redirect(['asignar_producto_presupuesto', 'parametros' => $parametros]);
                    }
                }    
                //PROCESO QUE REVERSA LOS PRODUCTOS QUE NO HACEN PARTE DEL PRESUPUESTO COMERCILA
                if(isset($_POST["liberar_producto"])){
                    if(isset($_POST["quitar_producto"])){
                        foreach ($_POST["quitar_producto"] as $intCodigo):
                            $table = InventarioProductos::findOne($intCodigo);
                            $table->aplica_presupuesto = 0;
                            $table->save(false);
                        endforeach;
                        $parametros = InventarioProductos::find()->where(['=','aplica_presupuesto', 1])->all();
                        return $this->redirect(['asignar_producto_presupuesto', 'parametros' => $parametros]);
                    }
                }    
                $to = $count->count();
                return $this->render('parametro_producto', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'parametros' => $parametros,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //PROCESO QUE VALIDA LA SUBIDA DE ARCHIVOS
     public function actionValidador_imagen($token = 0) {
       if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',37])->all()){
                $form = new \app\models\FormModeloBuscar();
                $q = null;
                $nombre = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $q = Html::encode($form->q);
                        $nombre = Html::encode($form->nombre);
                        if ($q == ''){
                            $table = InventarioProductos::find()
                                ->Where(['like','nombre_producto', $nombre])
                                ->andwhere(['=','venta_publico', 0])
                                ->andwhere(['>','stock_unidades', 0]);
                        }else{
                            $table = InventarioProductos::find()
                                ->where(['=','codigo_producto', $q])
                                ->andwhere(['=','venta_publico', 0])
                                ->andwhere(['>','stock_unidades', 0]);
                        } 
                        $table = $table->orderBy('nombre_producto ASC');  
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 6,
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
                    $table = InventarioProductos::find()->where(['>','stock_unidades', 0])->andWhere(['=','venta_publico', 0]) 
                            ->orderBy('nombre_producto ASC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 6,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }

                $to = $count->count();
                return $this->render('validador_archivo_producto', [
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
     * Finds the InventarioProductos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InventarioProductos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InventarioProductos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //EXCELES
    
    public function actionExcelconsultaInventario($tableexcel) {                
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
                               
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'PRODUCTO')
                    ->setCellValue('D1', 'DESCRIPCION')
                    ->setCellValue('E1', 'FECHA PROCESO')
                    ->setCellValue('F1', 'FECHA VCTO')
                    ->setCellValue('G1', 'APLICA INVENTARIO')
                    ->setCellValue('H1', 'INVENTARIO INICIAL')
                    ->setCellValue('I1', 'No LOTE')
                    ->setCellValue('J1', 'UNIDADES ENTRADAS')
                    ->setCellValue('K1', 'STOCK')
                    ->setCellValue('L1', 'C. UNITARIO')
                    ->setCellValue('M1', 'SUBTOTAL')
                    ->setCellValue('N1', 'IMPUESTO')
                    ->setCellValue('O1', 'VALOR TOTAL')
                    ->setCellValue('P1', 'USER NAME')
                    ->setCellValue('Q1', 'FECHA_ CREACION')
                    ->setCellValue('R1', 'CODIGO EAN')
                     ->setCellValue('S1', 'GRUPO')
                    ->setCellValue('T1', 'CLASIFICACION');
            $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_inventario)
                    ->setCellValue('B' . $i, $val->codigo_producto)
                    ->setCellValue('C' . $i, $val->nombre_producto)
                    ->setCellValue('D' . $i, $val->descripcion_producto)
                    ->setCellValue('E' . $i, $val->fecha_proceso)
                    ->setCellValue('F' . $i, $val->fecha_vencimiento)
                    ->setCellValue('G' . $i, $val->aplicaInventario)
                    ->setCellValue('H' . $i, $val->inventarioInicial);
                    if($val->id_detalle == NULL){
                         $objPHPExcel->setActiveSheetIndex(0)
                       ->setCellValue('I' . $i, 'NO FOUND'); 
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('I' . $i, $val->detalle->numero_lote);
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('J' . $i, $val->unidades_entradas)
                    ->setCellValue('K' . $i, $val->stock_unidades)
                    ->setCellValue('L' . $i, $val->costo_unitario)
                    ->setCellValue('M' . $i, $val->subtotal)
                    ->setCellValue('N' . $i, $val->valor_iva)
                    ->setCellValue('O' . $i, $val->total_inventario)
                    ->setCellValue('P' . $i, $val->user_name)
                    ->setCellValue('Q' . $i, $val->fecha_creacion)
                    ->setCellValue('R' . $i, $val->codigo_ean)
                    ->setCellValue('S' . $i, $val->grupo->nombre_grupo)
                    ->setCellValue('T' . $i, $val->grupo->clasificacionInventario->descripcion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('inventario');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Inventario_productos.xlsx"');
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
