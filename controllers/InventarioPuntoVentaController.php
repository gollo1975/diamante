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
//models

use app\models\InventarioPuntoVenta;
use app\models\UsuarioDetalle;


/**
 * InventarioPuntoVentaController implements the CRUD actions for InventarioPuntoVenta model.
 */
class InventarioPuntoVentaController extends Controller
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
     * Lists all InventarioPuntoVenta models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',19])->all()){
                $form = new \app\models\FiltroBusquedaInventarioPunto();
                $codigo = null;
                $inventario_inicial = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $punto_venta = null;
                $producto = null;
                $conPunto = \app\models\PuntoVenta::find()->orderBy('predeterminado DESC')->all();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $inventario_inicial = Html::encode($form->inventario_inicial);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $producto = Html::encode($form->producto);
                        $punto_venta = Html::encode($form->punto_venta);
                        $table = InventarioPuntoVenta::find()
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['like', 'nombre_producto', $producto])
                                    ->andFilterWhere(['=', 'inventario_inicial', $inventario_inicial])
                                    ->andFilterWhere(['=', 'id_punto', $punto_venta]);
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
                            $this->actionExcelInventarioPuntoVenta($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = InventarioPuntoVenta::find() ->orderBy('id_inventario DESC');
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
                        $this->actionExcelInventarioPuntoVenta($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombre_punto'),
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //CONSULTAR INVENTARIOS
     public function actionSearch_inventario($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',101])->all()){
                $form = new \app\models\FiltroBusquedaInventarioPunto();
                $codigo = null;
                $proveedor = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $punto_venta = null;
                $producto = null;
                $tokenAcceso = Yii::$app->user->identity->id_punto;
                $conPunto = \app\models\PuntoVenta::find()->orderBy('id_punto ASC')->all();
                $local = \app\models\PuntoVenta::findOne($tokenAcceso);
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $proveedor = Html::encode($form->proveedor);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $producto = Html::encode($form->producto);
                        $punto_venta = Html::encode($form->punto_venta);
                        if($tokenAcceso == 1){
                            $table = InventarioPuntoVenta::find()
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['like', 'nombre_producto', $producto])
                                    ->andFilterWhere(['=', 'id_proveedor', $proveedor])
                                    ->andFilterWhere(['=', 'id_punto', $punto_venta]);
                        }else{
                            $table = InventarioPuntoVenta::find()
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['like', 'nombre_producto', $producto])
                                    ->andWhere(['=', 'id_punto', $tokenAcceso]);
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
                            $this->actionExcelInventarioPuntoVenta($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    if($tokenAcceso == 1){
                        $table = InventarioPuntoVenta::find()->orderBy('id_inventario DESC');
                    }else{
                        $table = InventarioPuntoVenta::find()->andWhere(['=','id_punto', $tokenAcceso])->orderBy('id_inventario DESC');
                    }
                    
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
                        $this->actionExcelInventarioPuntoVenta($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_inventario', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'tokenAcceso' => $tokenAcceso,
                            'token' => $token,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombre_punto'),
                            'local' => $local,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //PERMITE BUSCAR POR REFERENCIAS
    
    public function actionSearch_referencias($token = 2) {
         if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',102])->all()){
                $form = new \app\models\ModeloEntradaProducto();
                $codigo_producto = 0;
                $model = null;
                $tokenAcceso = Yii::$app->user->identity->id_punto;
                if ($form->load(Yii::$app->request->get())) {
                    $codigo_producto = Html::encode($form->codigo_producto);
                    if ($codigo_producto > 0) {
                        $table = \app\models\InventarioPuntoVenta::find()->Where(['=','codigo_producto', $codigo_producto])->all();
                        if(count($table) > 0){
                            $model = $table;
                        }else{
                             Yii::$app->getSession()->setFlash('info', 'El código del producto que digito NO se encuentra en el sistema.');
                             return $this->redirect(['search_referencias']);
                        }
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'Debe digitar codigo del producto a buscar.');
                        return $this->redirect(['search_referencias']);
                    }    
                } 
            }else{
                return $this->redirect(['site/sinpermiso']); 
            }   
         }else{
             return $this->redirect(['site/login']);
         }    

        return $this->render('search_referencias', [
           'form'=> $form,
           'model' => $model,  
           'tokenAcceso' => $tokenAcceso,
            'token' => $token,
        ]);
        
    }
    /**
     * Displays a single InventarioPuntoVenta model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token, $codigo)
    {
        
        $talla_color = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
        $talla_color_cerrado= \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->andWhere(['=','cerrado', 1])->all();
        $confi = \app\models\MatriculaEmpresa::findOne(1);
        if(isset($_POST["actualizarlineas"])){
            if(isset($_POST["entrada_cantidad"])){
                $intIndice = 0;
                foreach ($_POST["entrada_cantidad"] as $intCodigo):
                    $detalle = \app\models\DetalleColorTalla::find()->where(['=','id_detalle', $intCodigo])->andWhere(['=','cerrado', 0])->one();
                    if($detalle){
                        if($_POST["cantidad"]["$intIndice"] > 0){
                            $table = \app\models\DetalleColorTalla::findOne($intCodigo);
                            if($codigo <> 0){
                                $unidad_entrada = $_POST["cantidad"][$intIndice]; //asigno variable
                                 var_dump($unidad_entrada);
                                $inventario = InventarioPuntoVenta::findOne($codigo);
                                if($unidad_entrada <= $inventario->stock_inventario){ //si hay stoxk
                                    $detalle->cantidad = $unidad_entrada;
                                    $detalle->stock_punto = $unidad_entrada;
                                    $detalle->save(false);
                                    $intIndice++;
                                }else{
                                    $intIndice++;
                                }
                            }else{    
                                $detalle->cantidad = $_POST["cantidad"][$intIndice];
                                $detalle->stock_punto = $_POST["cantidad"][$intIndice];
                                $detalle->save(false);
                                $intIndice++;
                            } 
                        }else{    
                           $intIndice++; 
                        }    
                    }else{
                        $intIndice++;
                    }   
                endforeach;
                    $this->ActualizarLineas($id);
                    $this->ActualizarTotalesProducto($id);
                return $this->redirect(['view','id' =>$id, 'token' => $token, 'codigo' => $codigo]);
            }
            
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'talla_color' => $talla_color,
            'talla_color_cerrado' => $talla_color_cerrado,
            'codigo' => $codigo, 
            'confi' => $confi,
            
        ]);
    }
    
    //VISTA DE BUSQUEDA DE INVENTIO
    public function actionView_search($token, $id, $tokenAcceso) {
        $talla_color = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
        $entrada_detalle = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','id_inventario', $id])->all();
        $item = \app\models\Documentodir::findOne(18);
        $imagenes = \app\models\DirectorioArchivos::find()->where(['=', 'codigo', $id])->andWhere(['=', 'numero', $item->codigodocumento])->all();
        return $this->render('view_search', [
            'model' => $this->findModel($id),
            'token' => $token,
            'talla_color' => $talla_color,
            'tokenAcceso' => $tokenAcceso,
            'entrada_detalle' => $entrada_detalle,
            'imagenes' => $imagenes,
        ]);
        
    }
    

     // VISTA  DESCUENTOS COMERCIALES
    public function actionView_descuentos_comerciales($id){
        $model = InventarioPuntoVenta::findOne($id);
        $regla_punto = \app\models\DescuentoPuntoVenta::find()->where(['=','id_inventario', $id])->all();
        $regla_distribuidor = \app\models\DescuentoDistribuidor::find()->where(['=','id_inventario', $id])->all();
        return $this->render('view_descuentos_comerciales_puntos', [
                            'model' => $model,
                           'regla_punto' => $regla_punto,
                            'regla_distribuidor' => $regla_distribuidor,
                            'id' => $id,
        ]);
    }
    
    //PROCESO QUE SUMA TODAAS LAS CANTIDAD
    protected function ActualizarLineas($id) {
        $inventario = InventarioPuntoVenta::findOne($id);
        $detalle = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
        $suma = 0;
        foreach ($detalle as $detalles):
            $suma += $detalles->cantidad; 
        endforeach;
        $inventario->stock_unidades =  $suma;
        $inventario->stock_inventario =  $suma;
        $inventario->save();
    }
    
    //CREAR LA REGLA PARA DISTRIBIDOR
    public function actionCrear_descuento_mayorista($id, $sw = 0) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $inventario = InventarioPuntoVenta::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["descuento_distribuidor"])) {
                    $table = new \app\models\DescuentoDistribuidor();
                    $table->id_inventario = $id;
                    $table->fecha_inicio =  $model->fecha_inicio;
                    $table->fecha_final = $model->fecha_final;
                    $table->nuevo_valor = $model->nuevo_valor;
                    $table->tipo_descuento = $model->tipo_descuento;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $inventario->aplica_descuento_distribuidor = 1;
                    $inventario->save();
                    $this->redirect(["inventario-punto-venta/view_descuentos_comerciales", 'id' => $id]);
                }
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('_form_descuento_mayorista', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //EDITAR DESCUENTO COMERCIAL MAYORISTA
    public function actionEditar_descuento_mayorista($id, $sw = 1) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $table = InventarioPuntoVenta::findOne($id);
        $regla = \app\models\DescuentoDistribuidor::find()->where(['=','id_inventario', $id])->one();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["regla_distribuidor"])) {
                   $regla->fecha_inicio =  $model->fecha_inicio;
                   $regla->fecha_final = $model->fecha_final;
                   $regla->nuevo_valor = $model->nuevo_valor;
                   $regla->tipo_descuento = $model->tipo_descuento;
                   $regla->estado_regla = $model->estado;
                   $regla->save(false);
                   if($model->estado == 1){
                        $table->aplica_descuento_distribuidor = 0;
                        $table->save();
                    }else{
                        $table->aplica_descuento_distribuidor = 1;
                        $table->save();
                    }     
                   $this->redirect(["inventario-punto-venta/view_descuentos_comerciales",'id' => $id]);
                }
            }else{
                $model->getErrors();
            }    
        }
        if (Yii::$app->request->get()) {
            $model->fecha_inicio = $regla->fecha_inicio;
            $model->fecha_final = $regla->fecha_final;
            $model->nuevo_valor = $regla->nuevo_valor;
            $model->tipo_descuento = $regla->tipo_descuento;
            $model->estado = $regla->estado_regla;
        }
        return $this->renderAjax('_form_editar_descuento_comercial', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //CREAR DESCUENTO PARA PUNTO DE VENTA
    public function actionCrear_descuento_puntoventa($id, $sw = 0) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $inventario = InventarioPuntoVenta::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["descuento_puntoventa"])) {
                    $table = new \app\models\DescuentoPuntoVenta();
                    $table->id_inventario = $id;
                    $table->fecha_inicio =  $model->fecha_inicio;
                    $table->fecha_final = $model->fecha_final;
                    $table->nuevo_valor = $model->nuevo_valor;
                    $table->tipo_descuento = $model->tipo_descuento;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $inventario->aplica_descuento_punto = 1;
                    $inventario->save();
                    $this->redirect(["inventario-punto-venta/view_descuentos_comerciales", 'id' => $id]);
                }
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('_form_descuento_puntoventa', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //EDITAR DESCUENTO COMERCIAL PUNTO DE VENTA
    //EDITAR DESCUENTO COMERCIAL MAYORISTA
    public function actionEditar_descuento_puntoventa($id, $sw = 1) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $table = InventarioPuntoVenta::findOne($id);
        $regla = \app\models\DescuentoPuntoVenta::find()->where(['=','id_inventario', $id])->one();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["descuento_puntoventa"])) {
                   $regla->fecha_inicio =  $model->fecha_inicio;
                   $regla->fecha_final = $model->fecha_final;
                   $regla->nuevo_valor = $model->nuevo_valor;
                   $regla->tipo_descuento = $model->tipo_descuento;
                   $regla->estado_regla = $model->estado;
                   $regla->save(false);
                   if($model->estado == 1){
                        $table->aplica_descuento_punto = 0;
                        $table->save();
                    }else{
                        $table->aplica_descuento_punto = 1;
                        $table->save();
                    }     
                   $this->redirect(["inventario-punto-venta/view_descuentos_comerciales",'id' => $id]);
                }
            }else{
                $model->getErrors();
            }    
        }
        if (Yii::$app->request->get()) {
            $model->fecha_inicio = $regla->fecha_inicio;
            $model->fecha_final = $regla->fecha_final;
            $model->nuevo_valor = $regla->nuevo_valor;
            $model->tipo_descuento = $regla->tipo_descuento;
            $model->estado = $regla->estado_regla;
        }
        return $this->renderAjax('_form_descuento_puntoventa', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //PROCESO QUE VALIDA LA CARGA DE IMAGENES DEL PRODUCTO
     public function actionValidador_imagen($token = 0) {
       if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',88])->all()){
                $form = new \app\models\FormModeloBuscar();
                $q = null;
                $nombre = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $q = Html::encode($form->q);
                        $nombre = Html::encode($form->nombre);
                        if ($q == ''){
                            $table = InventarioPuntoVenta::find()
                                ->Where(['like','nombre_producto', $nombre])
                                ->andwhere(['=','venta_publico', 1])
                                ->andwhere(['>','stock_inventario', 1]);
                        }else{
                            $table = InventarioPuntoVenta::find()
                                ->where(['=','codigo_producto', $q])
                                ->andwhere(['=','venta_publico', 1])
                                ->andwhere(['>','stock_inventario', 1]);
                        } 
                        $table = $table->orderBy('id_inventario DESC');  
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
                    $table = InventarioPuntoVenta::find()->where(['>','stock_inventario', 0])->andWhere(['=','venta_publico', 1]) 
                            ->orderBy('id_inventario DESC');
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
                return $this->render('validador_archivo_imagenes', [
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
    
    //TRASLADAR REFERENCIAS A PUNTOS DE VENTA
    public function actionTrasladar_punto_venta($id) {
        $model = new \app\models\ModeloTrasladoPuntoventa();
        $inventario = InventarioPuntoVenta::findOne($id);
        $confi = \app\models\MatriculaEmpresa::findOne(1);
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["enviar_producto"])) {
                    $table = new InventarioPuntoVenta();
                    $table->codigo_producto = $inventario->codigo_producto;
                    $table->codigo_barra = $inventario->codigo_producto;
                    $table->nombre_producto = $inventario->nombre_producto;
                    $table->costo_unitario = $inventario->costo_unitario;
                    $table->id_proveedor = $inventario->id_proveedor;
                    $table->id_marca = $inventario->id_marca;
                    $table->id_categoria = $inventario->id_categoria;
                    $table->iva_incluido = $inventario->iva_incluido;
                    $table->inventario_inicial = $inventario->inventario_inicial;
                    $table->aplica_inventario = $inventario->aplica_inventario;
                    $table->porcentaje_iva = $inventario->porcentaje_iva;
                    $table->fecha_proceso = date('Y-m-d');
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->venta_publico = $inventario->venta_publico;
                    $table->codigo_enlace_bodega = $inventario->id_inventario;
                    if($confi->aplica_talla_color == 0){
                        if($model->unidades > 0){
                            if($inventario->stock_inventario >= $model->unidades){
                                $table->stock_inventario = $model->unidades; 
                                $table->stock_unidades = $model->unidades;
                                $table->id_punto = $model->punto_venta;
                                $table->save(false);
                                //actualiza
                                $inventario->stock_inventario -= $model->unidades;
                                $inventario->save();
                                
                            }else{
                                return $this->renderAjax('_form_traslado_puntoventa', [
                                'model' => $model,
                                'id' => $id,
                                'confi' => $confi,

                                ]);  
                            }    
                        }else{
                              return $this->renderAjax('_form_traslado_puntoventa', [
                                'model' => $model,
                                'id' => $id,
                                'confi' => $confi,

                            ]);
                        }
                    }else{
                        $table->id_punto = $model->punto_venta;
                        $table->save(false);
                    }
                    $this->redirect(["inventario-punto-venta/index"]);
                }
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('_form_traslado_puntoventa', [
            'model' => $model,
            'id' => $id,
            'confi' => $confi,
            
        ]);
    }
    
    /**
     * Creates a new InventarioPuntoVenta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InventarioPuntoVenta();
        $confi = \app\models\MatriculaEmpresa::findOne(1);
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())){
            $conDato = \app\models\InventarioPuntoVenta::find()->where(['=','codigo_producto', $model->codigo_producto])->one();
            if($conDato){
                 Yii::$app->getSession()->setFlash('info', 'El codigo ('. $model->codigo_producto. ') ya esta codificado en el sistema. Valide la informacion.');
            }else{
                $model->save() ;
                $model->user_name = Yii::$app->user->identity->username;
                $model->codigo_barra = $model->codigo_producto;
                $model->id_punto = 1;
                if($confi->aplica_talla_color == 0){
                    $model->stock_unidades = $model->stock_unidades;
                    $model->stock_inventario = $model->stock_unidades;
                }
                $model->save();
                $id = $model->id_inventario;
                return $this->redirect(['index']);
           }    
        }

        return $this->render('create', [
            'model' => $model,
            'sw' =>0,
            'confi' => $confi,
        ]);
    }
    
     //PROCESO QUE ACTUALIZA LOS PRECIOS DEL PRODUCTO
    protected function ActualizarTotalesProducto($id) {
       $inventario = InventarioPuntoVenta::findOne($id);
       $subtotal =0;
       $impuesto = 0;
       $total = 0;
       $poncentaje = ''.round(($inventario->porcentaje_iva / 100),2);
       $subtotal = $inventario->stock_inventario * $inventario->costo_unitario;
       if($inventario->iva_incluido == 1){
          $impuesto = round($subtotal * $poncentaje);    
       }else{
           $impuesto = 0;
       }
       $inventario->subtotal = $subtotal - $impuesto;
       $inventario->valor_iva = $impuesto;
       $inventario->total_inventario = $subtotal;
       $inventario->save();
    }

    /**
     * Updates an existing InventarioPuntoVenta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $confi = \app\models\MatriculaEmpresa::findOne(1);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($confi->aplica_talla_color == 0){
                    $model->stock_unidades = $model->stock_unidades;
                    $model->stock_inventario = $model->stock_unidades;
                    $model->save();
                }
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'sw' => 1,
            'confi' =>$confi,
        ]);
    }
    
      //ELIMINAR DETALLES  
    public function actionEliminar($id,$id_detalle, $token,$codigo)
    {                                
        $detalles = \app\models\DetalleColorTalla::findOne($id_detalle);
        $detalles->delete();
        $this->ActualizarLineas($id);
        $this->ActualizarTotalesProducto($id);
        $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);        
    } 
    
    //PROCESO QUE GENERA LA COMBINACION DE TALLAS Y COLORES
    public function actionGenerar_combinacion_talla_color($id, $token, $codigo) {
        $form = new \app\models\FiltroBusquedaTallas();
        $codigo_talla = null;
        $conColores = null;
        if ($form->load(Yii::$app->request->get())) {
            $codigo_talla = Html::encode($form->codigo_talla);
            if($codigo_talla > 0){
                $model = \app\models\Tallas::find()->where(['=','id_talla', $codigo_talla])->one();
                if($codigo == 0){
                    $conColores = \app\models\Colores::find()->orderBy('colores ASC')->all();
                }else{
                    $conColores = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $codigo])->andWhere(['=','id_talla', $codigo_talla ])->orderBy('id_color ASC')->all();
                }
                
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar la talla de la lista.');
                return $this->redirect(['generar_combinacion_talla_color','id' =>$id, 'token' =>$token, 'codigo' => $codigo]);
            }
            
        }
        if (isset($_POST["enviarcolores"])) {
            if(isset($_POST["nuevo_color"])){
                foreach ($_POST["nuevo_color"] as $intCodigo) {
                    $table = new \app\models\DetalleColorTalla();
                    $table->id_inventario = $id;
                    $table->id_color = $intCodigo;
                    $table->id_talla = $model->id_talla;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save();
                }
                return $this->redirect(['generar_combinacion_talla_color','id' => $id, 'token' => $token, 'conColores' => $conColores,  'model' =>$model, 'codigo'=> $codigo]);
            }
            
        }
        return $this->render('generar_combinacion', [
            'id' => $id,
            'token' => $token,
            'form' => $form, 
            'conColores' => $conColores,
            'codigo' => $codigo,
        ]);
    }
   
    //CERRAR COMBINACIONES
    public function actionCerrar_combinaciones($id, $token, $codigo){
        $detalle = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->andWhere(['=','cerrado', 0])->all();
        if($detalle){
            foreach ($detalle as $detalles):
                $detalles->cerrado = 1;
                $detalles->save ();
            endforeach;
            $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);
        }else{
            Yii::$app->getSession()->setFlash('warning', 'Este proceso ya esta cerrado para las tallas y colores.');
            $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);
        }    
        
    }
    
     //CREAR PRECIOS DE VENTA
    public function actionCrear_precio_venta() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',87])->all()){
                $form = new \app\models\ModelCrearPrecios();
                $codigo= null;
                $producto = null;
                $marca = null;
                $proveedor = null; $categoria = null; $punto_venta = null;
                $sw  = 0;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $marca = Html::encode($form->marca);
                        $proveedor = Html::encode($form->proveedor);
                        $punto_venta = Html::encode($form->punto_venta);
                        $categoria = Html::encode($form->categoria);
                        $producto = Html::encode($form->producto);
                        $table = InventarioPuntoVenta::find()
                                        ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                        ->andFilterWhere(['=', 'id_marca', $marca])
                                        ->andFilterWhere(['like', 'nombre_producto', $producto])
                                        ->andFilterWhere(['=', 'id_proveedor', $proveedor])
                                        ->andFilterWhere(['=', 'id_categoria', $categoria])
                                        ->andFilterWhere(['=', 'id_punto', $punto_venta])
                                        ->andWhere(['>','stock_inventario', 0]); 
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
                            $sw = 1;
                    } else {
                        $form->getErrors();
                    }
                }else{
                    $table = InventarioPuntoVenta::find()->Where(['>','stock_inventario', 0])->orderBy('id_inventario DESC');
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
                }
                return $this->render('precio_deptal_mayorista', [
                            'model' => $model,
                            'form' => $form,
                            'sw' => $sw,
                            'pagination' => $pages,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //PERMITE CREAR EL PRECIO UNICO PARA VENTA AL DEPTA Y MAYORISTA
    public function actionCrear_precios_deptal_mayorista($id) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = InventarioPuntoVenta::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["crear_precio_venta"])) {
                if($model->tipo_precio == 2){//mayorista
                    $table->precio_mayorista = $model->nuevo_precio;
                }else{
                    $table->precio_deptal = $model->nuevo_precio;
                }    
            $table->save(false);
            $this->redirect(["inventario-punto-venta/crear_precio_venta", 'id' => $id]);
            }
        }
        return $this->renderAjax('_form_precio_punto_distribuidor', [
            'model' => $model,
            'id' => $id,
        ]);
    }
    
    //DESCARGAR INVENTARIO DE BODEGA
    public function actionDescargar_inventario_bodega($token, $id, $codigo){
        $bodega = InventarioPuntoVenta::findOne($codigo);
        $punto = InventarioPuntoVenta::findOne($id);
        $talla_color_bodega = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $codigo])->all();
        $talla_color_punto = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
        foreach ($talla_color_bodega as $talla_bodega):
            foreach ($talla_color_punto as $talla_punto):
                 $saldo = 0;
                 if($talla_bodega->id_color == $talla_punto->id_color && $talla_bodega->id_talla == $talla_punto->id_talla){
                     $saldo = $talla_bodega->stock_punto; 
                     $saldo -= $talla_punto->stock_punto;
                     if($saldo >= 0){
                         $talla_bodega->stock_punto = $saldo;
                         $talla_bodega->save();
                         $punto->inventario_aprobado = 1;
                         $punto->save();
                     }else{
                        Yii::$app->getSession()->setFlash('error', 'La Talla (' . $talla_bodega->talla->nombre_talla . ' ), No cumple con las exitencias actuales en bodega.'); 
                         $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);
                     }
                         
                 }
            endforeach;
        endforeach;
        
       //primera descarga de bodega
        $bodega->stock_inventario -= $punto->stock_inventario;
        $bodega->inventario_aprobado = 1;
        $bodega->save();
        $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);
        
    }
    /**
     * Finds the InventarioPuntoVenta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InventarioPuntoVenta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InventarioPuntoVenta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //EXCELES
    
    public function actionExcelInventarioPuntoVenta($tableexcel) {                
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
        
                                     
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'PRODUCTO')
                    ->setCellValue('D1', 'FECHA PROCESO')
                    ->setCellValue('E1', 'FECHA CREACION')
                    ->setCellValue('F1', 'APLICA INVENTARIO')
                    ->setCellValue('G1', 'INVENTARIO INICIAL')
                    ->setCellValue('H1', 'UNIDADES ENTRADAS')
                    ->setCellValue('I1', 'STOCK')
                    ->setCellValue('J1', 'VALOR UNITARIO')
                    ->setCellValue('K1', 'SUBTOTAL')
                    ->setCellValue('L1', 'IMPUESTO')
                    ->setCellValue('M1', 'VALOR TOTAL')
                    ->setCellValue('N1', 'USER NAME')
                    ->setCellValue('O1', 'CODIGO EAN')
                    ->setCellValue('P1', 'MARCA')
                    ->setCellValue('Q1', 'CATEGORIA')
                    ->setCellValue('R1', 'PRECIO DEPTAL')
                    ->setCellValue('S1', 'PRECIO MAYORISTA')
                    ->setCellValue('T1', 'APLICA DESCTO PUNTO')
                    ->setCellValue('U1', 'APLICA DESCTO MAYORISTA');
            $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_inventario)
                    ->setCellValue('B' . $i, $val->codigo_producto)
                    ->setCellValue('C' . $i, $val->nombre_producto)
                    ->setCellValue('D' . $i, $val->fecha_proceso)
                    ->setCellValue('E' . $i, $val->fecha_creacion)
                    ->setCellValue('F' . $i, $val->aplicaInventario)
                    ->setCellValue('G' . $i, $val->inventarioInicial)
                    ->setCellValue('H' . $i, $val->inventarioInicial)
                    ->setCellValue('I' . $i, $val->stock_unidades)
                    ->setCellValue('J' . $i, $val->costo_unitario)
                    ->setCellValue('K' . $i, $val->subtotal)
                    ->setCellValue('L' . $i, $val->valor_iva)
                    ->setCellValue('M' . $i, $val->total_inventario)
                    ->setCellValue('N' . $i, $val->user_name)
                    ->setCellValue('O' . $i, $val->codigo_barra)
                    ->setCellValue('P' . $i, $val->marca->marca)
                    ->setCellValue('Q' . $i, $val->categoria->categoria)
                    ->setCellValue('R' . $i, $val->precio_deptal)
                    ->setCellValue('S' . $i, $val->precio_mayorista)
                    ->setCellValue('T' . $i, $val->aplicaDescuentoPunto)
                    ->setCellValue('U' . $i, $val->aplicaDescuentoDistribuidor) ;
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
