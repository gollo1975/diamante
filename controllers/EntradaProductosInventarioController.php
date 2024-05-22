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

use app\models\EntradaProductosInventario;
use app\models\UsuarioDetalle;
use app\models\InventarioPuntoVenta;
use app\models\OrdenCompra;


/**
 * EntradaProductosInventarioController implements the CRUD actions for EntradaProductosInventario model.
 */
class EntradaProductosInventarioController extends Controller
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
     * Lists all EntradaProductosInventario models.
     * @return mixed
     */
   public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',104])->all()){
                $form = new \app\models\FiltroBusquedaEntradaMateria();
                $id_entrada= null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $proveedor = null;
                $orden = null;
                $tipo_entrada = null;       
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $id_entrada = Html::encode($form->id_entrada);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $proveedor = Html::encode($form->proveedor);
                        $orden = Html::encode($form->orden);
                        $tipo_entrada = Html::encode($form->tipo_entrada);
                        $table = EntradaProductosInventario::find()
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_orden_compra', $orden])
                                    ->andFilterWhere(['=', 'tipo_entrada', $tipo_entrada])
                                    ->andFilterWhere(['=', 'id_proveedor', $proveedor]);
                        $table = $table->orderBy('id_entrada DESC');
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
                            $check = isset($_REQUEST['id_entrada  DESC']);
                            $this->actionExcelConsultaEntrada($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = EntradaProductosInventario::find()
                            ->orderBy('id_entrada DESC');
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
                        $this->actionExcelConsultaEntrada($tableexcel);
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
     * Displays a single EntradaProductosInventario model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $models = new \app\models\ModeloEntradaProducto();
        $inventario = InventarioPuntoVenta::find()->orderBy('nombre_producto ASC')->where(['=','id_punto', 1])->all();
        $detalle_entrada = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','id_entrada', $id])->all();
        $empresa = \app\models\MatriculaEmpresa::findOne(1);
        if(isset($_POST["actualizarlineas"])){
            if(isset($_POST["detalle_entrada"])){
                $intIndice = 0;
                $auxiliar = 0;
                $iva = 0 ;
                foreach ($_POST["detalle_entrada"] as $intCodigo):
                    $table = \app\models\EntradaProductoInventarioDetalle::findOne($intCodigo);
                    $table->id_inventario= $_POST["id_inventario"]["$intIndice"];
                    $inven = InventarioPuntoVenta::findOne($table->id_inventario);
                    $table->codigo_producto = $inven->codigo_producto;
                    $table->cantidad = $_POST["cantidad"]["$intIndice"];
                    $table->actualizar_precio = $_POST["actualizar_precio"]["$intIndice"];
                    $table->porcentaje_iva = $_POST["porcentaje_iva"]["$intIndice"];
                    $table->fecha_vencimiento = $_POST["fecha_vcto"]["$intIndice"];
                    $table->valor_unitario = $_POST["valor_unitario"]["$intIndice"];
                    $auxiliar =  $table->cantidad * $table->valor_unitario;
                    $iva = round(($auxiliar * $table->porcentaje_iva)/100);
                    $table->total_iva = $iva;
                    $table->subtotal = $auxiliar;
                    $table->total_entrada = $iva + $auxiliar;
                    $table->save(false);
                    $auxiliar = 0;
                    $iva = 0;   
                    $intIndice++;
                endforeach;
                $this->ActualizarLineas($id);
                return $this->redirect(['view','id' =>$id, 'token' => $token]);
            }
            
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_entrada' => $detalle_entrada,
            'inventario' => ArrayHelper::map($inventario, "id_inventario", "inventario"),
            'models' => $models,
            'empresa' => $empresa,
        ]);
    }

     //proceso que suma los totales
    protected function ActualizarLineas($id) {
        $entrada = EntradaProductosInventario::findOne($id);
        $detalle = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','id_entrada', $id])->all();
        $subtotal = 0; $iva = 0; $total = 0;
        foreach ($detalle as $detalles):
            $subtotal += $detalles->subtotal;
            $iva += $detalles->total_iva;
            $total += $detalles->total_entrada;
        endforeach;
        $entrada->subtotal = $subtotal;
        $entrada->impuesto = $iva;
        $entrada->total_salida = $total;
        $entrada->save(false);
    }
    
    /**
     * Creates a new EntradaProductosInventario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate($sw)
    {
        $model = new EntradaProductosInventario();
        $ordenes = \app\models\OrdenCompra::find()->orderBy('id_orden_compra desc')->all(); 
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($sw == 0){
                $ordenCompra = OrdenCompra::findOne($model->id_orden_compra);
                $model->id_tipo_orden = $ordenCompra->id_tipo_orden;
            }else{
                 $model->id_tipo_orden = null;
                 $model->id_orden_compra = null;
            }    
            $model->user_name_crear= Yii::$app->user->identity->username;
            $model->update();
            $token = 0;
            if($sw == 0){
                return $this->redirect(['view', 'id' => $model->id_entrada, 'token'=> $token]);
            }else{
                return $this->redirect(['codigo_barra_ingreso', 'id' => $model->id_entrada, 'bodega' => 1]);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'sw' => $sw,
            'ordenes' => ArrayHelper::map($ordenes, "id_orden_compra", "descripcion"),
        ]);
    }
    
     
    //proceso que lleva el combo con las ordenes de cada proveedor
    public function actionOrdencompra($id){
        $rows = \app\models\OrdenCompra::find()->where(['=','id_proveedor', $id])
                                               ->andWhere(['=','importado', 0])
                                               ->andWhere(['=','abreviatura', 'IP'])->orderBy('descripcion desc')->all();

        echo "<option value='' required>Seleccione una orden...</option>";
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id_orden_compra' required>$row->descripcion</option>";
            }
        }
    }

    /**
     * Updates an existing EntradaProductosInventario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
  public function actionUpdate($id, $sw)
    {
        $model = $this->findModel($id);
        $ordenes = \app\models\OrdenCompra::find()->where(['=','abreviatura', 'IP'])->andWhere(['=','importado', 0])->orderBy('id_orden_compra desc')->all(); 
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($sw == 0){
                $ordenCompra = OrdenCompra::findOne($model->id_orden_compra);
                $model->id_tipo_orden = $ordenCompra->id_tipo_orden;
            }else{
                 $model->id_tipo_orden = null;
                 $model->id_orden_compra = null;
            }    
            
            $model->user_name_edit= Yii::$app->user->identity->username;
            if($sw == 1){
                $model->id_orden_compra = null;
            }
            $model->update(false);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'ordenes' => ArrayHelper::map($ordenes, "id_orden_compra", "descripcion"),
            'sw' => $sw,
        ]);
    }

     //importar lineas de la orden de compra
    public function actionImportardetallecompra($id, $id_orden, $token, $proveedor)
    {                                
        $orden_compra = OrdenCompra::find()->where(['=','id_proveedor' , $proveedor])->andWhere(['=','importado', 0])->andWhere(['=','abreviatura', 'IP'])->one();
        if($orden_compra){
            $detalle_compra = \app\models\OrdenCompraDetalle::find()->where(['=','id_orden_compra', $orden_compra->id_orden_compra])->all();
            foreach ( $detalle_compra as $detalle_compras):
                    $table = new \app\models\EntradaProductoInventarioDetalle();
                    $table->id_entrada = $id;
                    $table->fecha_vencimiento = date('Y-m-d');
                    $table->porcentaje_iva = $detalle_compras->porcentaje;
                    $table->cantidad = $detalle_compras->cantidad;
                    $table->valor_unitario = $detalle_compras->valor;
                    $table->insert();
            endforeach;
            $this->redirect(["view",'id' => $id, 'token' => $token]);  
        }else{
            Yii::$app->getSession()->setFlash('warning', 'El proveedor NO tiene ORDENES DE COMPRA programdas para entregar.');
             $this->redirect(["view",'id' => $id, 'token' => $token]);  
        }
            
        
             
    } 
    
      //ELIMINAR DETALLES  
    public function actionEliminar($id,$detalle, $token)
    {                                
        $detalles = \app\models\EntradaProductoInventarioDetalle::findOne($detalle);
        $detalles->delete();
        $this->ActualizarLineas($id);
        $this->redirect(["view",'id' => $id, 'token' => $token]);        
    } 
    
    //ELIMINAR DETALLES  manuales
    public function actionEliminar_manual($id, $detalle_manual, $bodega)
    {                                
        $detalle = \app\models\EntradaProductoInventarioDetalle::findOne($detalle_manual);
        $detalle->delete();
        $this->ActualizarLineas($id);
        $this->redirect(["codigo_barra_ingreso",'id' => $id, 'bodega' => $bodega]);        
    } 

    //AUTORIZAR ENTRADA
     public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        $detalle = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','id_entrada', $id])->all();
        $sw = 0;
        if(count($detalle) > 0){
            foreach ($detalle as $validar):
                if($validar->id_inventario == null){
                    $sw = 1;
                }
            endforeach;
            if($sw == 0){
                if ($model->autorizado == 0) {                        
                    $model->autorizado = 1;            
                   $model->update();
                   $this->redirect(["entrada-productos-inventario/view", 'id' => $id, 'token' =>$token]);  

                } else{
                        $model->autorizado = 0;
                        $model->update();
                        $this->redirect(["entrada-productos-inventario/view", 'id' => $id, 'token' =>$token]);  
                } 
            }else{
                 Yii::$app->getSession()->setFlash('error', 'No puede AUTORIZAR la entrada porque NO ha seleccionado el productos para descargar la entrada.');
                $this->redirect(["view",'id' => $id, 'token' => $token]);   
            }
            
        }else{
             Yii::$app->getSession()->setFlash('warning', 'Debe de importar primero la orden de compra.');
             $this->redirect(["view",'id' => $id, 'token' => $token]);   
        }    
    }
    
     //AUTORIZAR ENTRADA SIN OC
     public function actionAutorizado_sinoc($id, $bodega) {
        $model = $this->findModel($id);
        if ($model->autorizado == 0) {                        
                $model->autorizado = 1;            
               $model->update();
               $this->redirect(["entrada-productos-inventario/codigo_barra_ingreso", 'id' => $id, 'bodega' => $bodega]);  

        } else{
                $model->autorizado = 0;
                $model->update();
                 $this->redirect(["entrada-productos-inventario/codigo_barra_ingreso", 'id' => $id, 'bodega' => $bodega]);  
        }    
    }
    
    //PROCESO NUEVA ENTRADA DE TALLAS Y COLORES
    public function actionCrear_talla_color_entrada($id, $id_inventario , $id_detalle, $token) {
        $listadoTallaColor = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id_inventario])->all();
        $model = \app\models\EntradaProductoInventarioDetalle::findOne($id_detalle);
        $entrada_talla = \app\models\EntradaTallaColor::find()->where(['=','id_detalle', $id_detalle])->all();
        $item_cerrado = \app\models\EntradaTallaColor::find()->where(['=','id_detalle', $id_detalle])->andWhere(['=','cerrado', 0])->all();
        return $this->render('crear_talla_color_entrada', [
            'token' => $token,
            'id_detalle' => $id_detalle,
            'id_inventario' => $id_inventario,
            'listadoTallaColor' => $listadoTallaColor,
            'token' => $token,
            'id' => $id,
            'model' => $model,
            'entrada_talla' => $entrada_talla,
            'item_cerrado' => $item_cerrado,
        ]);
    }
    
     //PERMITE ENTAR LAS NUEVAS EXISTENCIAS POR TALLA Y COLOR
    public function actionEntrada_nueva_existencia($id, $id_inventario, $id_detalle, $token, $id_detalle_existencia) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = \app\models\DetalleColorTalla::findOne($id_detalle_existencia);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["nueva_entrada"])) {
                $fila = new \app\models\EntradaTallaColor();
                $fila->id_detalle = $id_detalle;
                $fila->id_inventario =  $id_inventario;
                $fila->id_entrada = $id;
                $fila->id_color = $table->id_color;
                $fila->id_talla = $table->id_talla;
                $fila->cantidad = $model->nueva_cantidad;
                $fila->user_name = Yii::$app->user->identity->username;
                $fila->save();
                $this->redirect(["entrada-productos-inventario/crear_talla_color_entrada", 'id' => $id, 'id_inventario' => $id_inventario, 'id_detalle' => $id_detalle, 'token' => $token]);
            }
        }
        return $this->renderAjax('nueva_entrada_inventario', [
            'model' => $model,
            'id' => $id,
            'id_inventario' => $id_inventario,
            'id_detalle' => $id_detalle,
            'token' => $token,
            
        ]);
    }
    
    //ELIMINAR DETALLES  DE NUEVA ENTRADA O EXISTENCIAS
    public function actionEliminar_nueva_entrada($id, $id_inventario, $id_detalle, $token, $codigo)
    {                                
        $entrada = \app\models\EntradaTallaColor::findOne($codigo);
        $entrada->delete();
        $this->redirect(["entrada-productos-inventario/crear_talla_color_entrada", 'id' => $id, 'id_inventario' => $id_inventario, 'id_detalle' => $id_detalle, 'token' => $token]);        
    } 
    
    //PROCESO QUE CIERRA LA ENTRADA DE UNIDADES
    public function actionCerrar_entrada_referencia($id, $token, $id_detalle, $id_inventario) {
        $nueva_entrada = \app\models\EntradaTallaColor::find()->where(['=','id_detalle',$id_detalle])->all();
        $detalle_referencia = \app\models\EntradaProductoInventarioDetalle::findOne($id_detalle);
        $contador = 0;
        foreach ($nueva_entrada as $contar):
            $contador += $contar->cantidad;
        endforeach;
        if($contador == $detalle_referencia->cantidad){
            foreach ($nueva_entrada as $nueva):
                $existencias = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id_inventario])
                                                                    ->andWhere(['=','id_talla', $nueva->id_talla])
                                                                    ->andWhere(['=','id_color', $nueva->id_color])->one();
                if($existencias){
                    $existencias->cantidad += $nueva->cantidad;
                    $existencias->stock_punto += $nueva->cantidad;
                    $existencias->save();
                    $nueva->cerrado = 1;
                    $nueva->save();
                }
            endforeach;
            $this->redirect(["entrada-productos-inventario/crear_talla_color_entrada", 'id' => $id, 'id_inventario' => $id_inventario, 'id_detalle' => $id_detalle, 'token' => $token]);
        }else{
             Yii::$app->getSession()->setFlash('error', 'Las cantidades ingresas en la orden entrada deben de ser igual a las cantidades ingresadas en TALLA Y COLOR. Validar la informacion.');
             $this->redirect(["entrada-productos-inventario/crear_talla_color_entrada", 'id' => $id, 'id_inventario' => $id_inventario, 'id_detalle' => $id_detalle, 'token' => $token]);
        }
        
    }
    
    //ENVIAR INVENTARIO AL MODULO
    public function actionEnviar_inventario_modulo($id, $token, $id_compra, $genera_talla) {
        $orden_compra = OrdenCompra::findOne($id_compra);
        $entrada_inventario = EntradaProductosInventario::findOne($id);
        $detalle_entrada = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','id_entrada', $id])->all();
        $sw = 0;
        if($genera_talla == 0){
            foreach ($detalle_entrada as $detalle):
                $inventario_entrada = InventarioPuntoVenta::findOne($detalle->id_inventario);
                $inventario_entrada->stock_unidades += $detalle->cantidad; 
                $inventario_entrada->stock_inventario += $detalle->cantidad;
                $inventario_entrada->save();
            endforeach;
            $entrada_inventarioº->enviar_materia_prima = 1;
            $entrada_inventario->save();
            $orden_compra->importado = 1;
            $orden_compra->save();
            $this->redirect(["entrada-productos-inventario/view", 'id' => $id, 'token' => $token]);
        }else{
            foreach ($detalle_entrada as $detalle):
                $entrada = \app\models\EntradaTallaColor::find()->where(['=','id_detalle', $detalle->id_detalle])->one(); 
                if($entrada){
                    $inventario_entrada = InventarioPuntoVenta::findOne($detalle->id_inventario);
                    $inventario_entrada->stock_unidades += $detalle->cantidad; 
                    $inventario_entrada->stock_inventario += $detalle->cantidad;
                    $inventario_entrada->save(); 
                }else{
                    $sw = 1;
                }
            endforeach;
        }    
        if($sw == 1){
             Yii::$app->getSession()->setFlash('error', 'Debe de subir las TALLAS Y COLORES a esta referencia.');
             $this->redirect(["entrada-productos-inventario/view", 'id' => $id, 'token' => $token]);
        }else{
            $entrada_inventario->enviar_materia_prima = 1;
            $entrada_inventario->save();
            $orden_compra->importado = 1;
            $orden_compra->save();
            $this->redirect(["entrada-productos-inventario/view", 'id' => $id, 'token' => $token]);
        }
            
    }
    
    //ENVIAR INVENTARIO AL MODULO SIN ORDEN DE COMPRA
   public function actionEnviar_inventario_modulo_sinorden($id, $genera_talla, $bodega) {
        $entrada_inventario = EntradaProductosInventario::findOne($id);
        $detalle_entrada = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','id_entrada', $id])->all();
        if($genera_talla == 0){
            foreach ($detalle_entrada as $detalle):
                $inventario_entrada = InventarioPuntoVenta::findOne($detalle->id_inventario);
                $inventario_entrada->stock_unidades += $detalle->cantidad; 
                $inventario_entrada->stock_inventario += $detalle->cantidad;
                $inventario_entrada->save();
            endforeach;
            $entrada_inventario->enviar_materia_prima = 1;
            $entrada_inventario->save();
            $this->redirect(["entrada-productos-inventario/codigo_barra_ingreso", 'id' => $id, 'bodega' => $bodega]);
        }else{
            foreach ($detalle_entrada as $detalle):
                $entrada = \app\models\EntradaTallaColor::find()->where(['=','id_detalle', $detalle->id_detalle])->one(); 
                if($entrada){
                    $sw = 0;
                    $inventario_entrada = InventarioPuntoVenta::findOne($detalle->id_inventario);
                    $inventario_entrada->stock_unidades += $detalle->cantidad; 
                    $inventario_entrada->stock_inventario += $detalle->cantidad;
                    $inventario_entrada->save(); 
                    
                }else{
                    $sw = 1;
                }
            endforeach;
            if($sw == 1){
                Yii::$app->getSession()->setFlash('error', 'Debe de subir las TALLAS Y COLORES a cada referencia.');
                $this->redirect(["entrada-productos-inventario/codigo_barra_ingreso", 'id' => $id, 'bodega' => $bodega]);
            }else{
                $entrada_inventario->enviar_materia_prima = 1;
                $entrada_inventario->save();
                $this->redirect(["entrada-productos-inventario/codigo_barra_ingreso", 'id' => $id, 'bodega' => $bodega]);
            }
        }    
        
            
    }
    
   //PROCESO QUE INGRESA CON CODIGO DE BARRAS
    public function actionCodigo_barra_ingreso($id, $bodega) {
        $form = new \app\models\ModeloEntradaProducto();
        $model = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','id_entrada', $id])->all();
        $empresa = \app\models\MatriculaEmpresa::findOne(1);
        $entrada_producto = EntradaProductosInventario::findOne($id);
        $codigo_producto = 0;
        if ($form->load(Yii::$app->request->get())) {
            $codigo_producto = Html::encode($form->codigo_producto);
            if ($codigo_producto > 0) {
                $table = InventarioPuntoVenta::find()->Where(['=','codigo_producto', $codigo_producto])->andWhere(['=','id_punto', $bodega])->one();
                if($table){
                    $conDato = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','codigo_producto', $codigo_producto])
                                                                      ->andWhere(['=','id_entrada', $id])->one();
                    if(!$conDato){
                        $entrada = new \app\models\EntradaProductoInventarioDetalle();
                        $entrada->id_entrada = $id;
                        $entrada->id_inventario = $table->id_inventario;
                        $entrada->codigo_producto = $codigo_producto;
                        $entrada->fecha_vencimiento = date('Y-m-d');
                        $entrada->porcentaje_iva = $table->porcentaje_iva;
                        $entrada->valor_unitario = $table->costo_unitario;
                        $entrada->save(false);
                        $model = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','id_entrada', $id])->all(); 
                        $this->redirect(["entrada-productos-inventario/codigo_barra_ingreso",'id' => $id, 'bodega' => $bodega]);
                        if (isset($_POST['excel'])) {
                                $check = isset($_REQUEST['id_entrada  DESC']);
                                $this->actionExcelConsultaEntrada($tableexcel);
                        }
                    }else{
                        Yii::$app->getSession()->setFlash('success', 'El código digitado ya se en cuentra agregado a esta entrada.');
                     return $this->redirect(['codigo_barra_ingreso','id' =>$id, 'bodega' => $bodega]);
                    }    
                }else{
                     Yii::$app->getSession()->setFlash('info', 'El código del producto no se encuentra en el sistema.');
                     return $this->redirect(['codigo_barra_ingreso','id' =>$id, 'bodega' => $bodega]);
                }
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe digitar codigo del producto a buscar.');
                return $this->redirect(['codigo_barra_ingreso','id' =>$id, 'bodega' => $bodega]);
            }    
        }
        if(isset($_POST["actualizarlineas"])){
            if(isset($_POST["detalle_entrada"])){
                $intIndice = 0;
                $auxiliar = 0;
                $iva = 0 ;
                foreach ($_POST["detalle_entrada"] as $intCodigo):
                    $table = \app\models\EntradaProductoInventarioDetalle::findOne($intCodigo);
                    $table->actualizar_precio = $_POST["actualizar_precio"]["$intIndice"];
                    $table->cantidad = $_POST["cantidad"]["$intIndice"];
                    $table->fecha_vencimiento = $_POST["fecha_vcto"]["$intIndice"];
                    $table->valor_unitario = $_POST["valor_unitario"]["$intIndice"];
                    if($_POST["actualizar_precio"]["$intIndice"] == 1){
                       $auxiliar =  $table->cantidad * $table->valor_unitario;
                       $iva = round(($auxiliar * $table->porcentaje_iva)/100);
                    }else{
                       $auxiliar =  $table->cantidad * $table->valor_unitario;
                       $iva = round(($auxiliar * $table->porcentaje_iva)/100);
                    }
                    $table->total_iva = $iva;
                    $table->subtotal = $auxiliar;
                    $table->total_entrada = $iva + $auxiliar;
                    $table->save(false);
                    $auxiliar = 0;
                    $iva = 0;   
                    $intIndice++;
                endforeach;
                $this->ActualizarLineas($id);
                return $this->redirect(['codigo_barra_ingreso','id' =>$id, 'bodega' => $bodega]);
            }
            
        }
        return $this->render('_form_codigo_barras', [
                    'model' => $model,
                    'form' => $form,
                    'id' => $id,
                    'bodega' => $bodega,
                    'empresa' => $empresa,
                    'entrada_producto' => $entrada_producto,
         ]);
        
    }
   
    
    
     //IMPRESIONES
    public function actionImprimir_entrada_producto($id, $token) {
        $model = EntradaProductosInventario::findOne($id);
        return $this->render('../formatos/reporte_entrada_inventario', [
            'model' => $model,
        ]);
    }
    /**
     * Finds the EntradaProductosInventario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EntradaProductosInventario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntradaProductosInventario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
