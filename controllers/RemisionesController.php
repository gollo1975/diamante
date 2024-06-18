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

use app\models\Remisiones;
use app\models\UsuarioDetalle;
use app\models\Clientes;
use app\models\InventarioPuntoVenta;
use app\models\RemisionDetalles;
use app\models\ReciboCajaPuntoVenta;



/**
 * RemisionesController implements the CRUD actions for Remisiones model.
 */
class RemisionesController extends Controller
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
     * Lists all Remisiones models.
     * @return mixed
     */
   public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',105])->all()){
                $form = new \app\models\FiltroBusquedaRemision();
                $numero = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $punto_venta = null;
                $cliente = null;
                $conPunto = \app\models\PuntoVenta::find()->orderBy('predeterminado DESC')->all();
                $conCliente = Clientes::find()->where(['=','estado_cliente', 0])->orderBy('nombre_completo ASC')->all();
                $accesoToken = Yii::$app->user->identity->id_punto;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $cliente = Html::encode($form->cliente);
                        $punto_venta = Html::encode($form->punto_venta);
                        if($accesoToken == 1){ 
                            $table = Remisiones::find()
                                        ->andFilterWhere(['=', 'numero_remision', $numero])
                                        ->andFilterWhere(['between', 'fecha_inicio', $fecha_inicio, $fecha_corte])
                                        ->andFilterWhere(['=', 'id_cliente', $cliente])
                                        ->andFilterWhere(['=', 'id_punto', $punto_venta]);
                        }else{
                            $table = Remisiones::find()
                                        ->andFilterWhere(['=', 'numero_remision', $numero])
                                        ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                        ->andFilterWhere(['=', 'id_cliente', $cliente])
                                        ->andWhere(['=', 'id_punto', $accesoToken]);
                        }    
                        $table = $table->orderBy('id_remision DESC');
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
                            $check = isset($_REQUEST['id_remision  DESC']);
                            $this->actionExcelRemisiones($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    if($accesoToken == 1){
                        $table = Remisiones::find()->orderBy('id_remision DESC');
                    }else{
                        $table = Remisiones::find()->where(['=','id_punto', $accesoToken])->orderBy('id_remision DESC');
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
                        $this->actionExcelRemisiones($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombre_punto'),
                            'conCliente' => ArrayHelper::map($conCliente, 'id_cliente', 'clienteCompleto'),
                            'accesoToken' => $accesoToken,
                            'cliente' => $cliente,
                            'punto_venta' => $punto_venta,
                            'fecha_inicio' => $fecha_inicio,
                            'fecha_corte' => $fecha_corte,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
   
   // PERMITE BUSCAR EL PRODUCTO MAS VENDIDO
    public function actionSearch_producto_vendido() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',109])->all()){
                $form = new \app\models\FiltroBusquedaInventarioPunto();
                $fecha_inicio = null;
                $fecha_corte = null;
                $punto_venta = null;
                $producto = null;
                $pages = null;
                $model = null;
                $conPunto = \app\models\PuntoVenta::find()->orderBy('nombre_punto ASC')->all();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $producto = Html::encode($form->producto);
                        $punto_venta = Html::encode($form->punto_venta);
                       
                        if($form->punto_venta <> null){
                            $table = \app\models\RemisionDetalles::find()
                                        ->andFilterWhere(['between', 'fecha_inicio', $fecha_inicio, $fecha_corte])
                                        ->andFilterWhere(['=', 'id_inventario', $producto])
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
                                $this->actionExcelProductoVendido($tableexcel);
                            }
                        }else{
                            Yii::$app->getSession()->setFlash('warning', 'Debe se seleccion un PUNTO DE VENTA para ejecutar la consulta.');
                        }       
                    } else {
                        $form->getErrors();
                    }
                } 
                return $this->render('search_producto_vendido', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombre_punto'),
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    } 

    /**
     * Displays a single Remisiones model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $accesoToken)
    {
        $form = new \app\models\ModeloEntradaProducto();
        $codigo_producto = null;
        $nombre_producto = null;
        $factura = Remisiones::findOne($id);
        $punto_venta = \app\models\PuntoVenta::findOne($accesoToken);
        $inventario = \app\models\InventarioPuntoVenta::find()->where(['>','stock_inventario', 0])
                                                          ->andWhere(['=','venta_publico', 1])->andWhere(['=','id_punto', $accesoToken])
                                                          ->orderBy('nombre_producto ASC')->all();
        $detalle_remision = \app\models\RemisionDetalles::find()->where(['=','id_remision', $id])->all();
         if ($form->load(Yii::$app->request->get())) {
            $codigo_producto = Html::encode($form->codigo_producto);
            $nombre_producto = Html::encode($form->nombre_producto);
            if($nombre_producto <> null){
                $conInve = InventarioPuntoVenta::findOne($nombre_producto);
                $codigo_producto = $conInve->codigo_producto;
            }
            if ($codigo_producto > 0) {
                $conCodigo = \app\models\InventarioPuntoVenta::find()->Where(['=','codigo_producto', $codigo_producto])->andWhere(['=','id_punto', $accesoToken])->one();
                if($conCodigo){
                    $conDato = \app\models\RemisionDetalles::find()->where(['=','id_remision', $id])
                                                          ->andWhere(['=','codigo_producto', $codigo_producto])->one();
                    //declaracion de variables
                         
                    $porcentaje = 0; $subtotal = 0; $total = 0; $iva = 0; $descuento = 0; $cantidad = 0;
                    if(!$conDato){
                    
                        $producto = \app\models\InventarioPuntoVenta::find()->where(['=','codigo_producto', $codigo_producto])->andWhere(['=','id_punto', $accesoToken])->one();
                        $table = new \app\models\RemisionDetalles();
                        $table->id_remision = $id;
                        $table->id_inventario = $producto->id_inventario;
                        $table->codigo_producto = $codigo_producto;
                        $table->fecha_inicio = $factura->fecha_inicio;
                        $table->id_punto = $accesoToken;
                        $table->producto = $producto->nombre_producto;
                        if($factura->id_punto  <> 1 ){ ///PROCESO AL DEPTAL
                            $table->cantidad = 1;
                            $table->valor_unitario = $producto->precio_deptal;    
                            $subtotal = round($table->valor_unitario  * $table->cantidad);
                            if($producto->aplica_descuento_punto == 1){ //aplicar descuento comercial para punto de venta
                                $fecha_actual = date('Y-m-d');
                                $regla = \app\models\DescuentoPuntoVenta::find()->where(['=','id_inventario', $producto->id_inventario])->andWhere(['=','id_punto', $accesoToken])->one();
                                if($regla){
                                    if($regla->tipo_descuento == 1 && $regla->fecha_inicio <= $fecha_actual && $regla->fecha_final >= $fecha_actual){
                                        $descuento = round(($subtotal * $regla->nuevo_valor)/100);
                                        $table->total_linea = round($subtotal - $descuento);
                                        $table->subtotal = round($subtotal);
                                        $table->porcentaje_descuento = $regla->nuevo_valor;
                                        $table->valor_descuento = $descuento;
                                    }
                                }else{    
                                    $descuento = 0;
                                    $table->total_linea = round($subtotal);
                                    $table->subtotal = round($subtotal);
                                    $table->porcentaje_descuento = 0;
                                    $table->valor_descuento = $descuento;
                                }
                            }else{ //SI NO TIENE DESCUENTO COMERCIAL
                                $descuento = 0;
                                $table->total_linea = $subtotal;
                                $table->subtotal = $subtotal;
                                $table->porcentaje_descuento = 0;
                                $table->valor_descuento = $descuento;
                            }
                        }    
                        $table->save(false);
                        $this->ActualizarSaldosTotales($id);
                        $detalle_remision = \app\models\RemisionDetalles::find()->where(['=','id_remision', $id])->all();
                        $this->redirect(["remisiones/view",'id' => $id, 'detalle_remision' => $detalle_remision,'accesoToken' => $accesoToken]);
                    }else{
                        if($factura->id_punto == 1){
                            Yii::$app->getSession()->setFlash('warning', 'Este producto ya se encuentra registrado en esta remision, favor subir las unidades faltantes por  la opcion de MAS');
                            return $this->redirect(['view','id' =>$id, 'accesoToken' => $accesoToken]);
                        }else{
                            //si existe el producto
                            $valor_unitario = 0;
                            $detalle = \app\models\RemisionDetalles::findOne($conDato->id_detalle);
                            $producto = \app\models\InventarioPuntoVenta::find()->where(['=','codigo_producto', $codigo_producto])->andWhere(['=','id_punto', $accesoToken])->one();
                            if($factura->id_punto == 1){
                                $valor_unitario = $producto->precio_mayorista;    
                            }else{
                                $valor_unitario = $producto->precio_deptal;   

                            }
                            $pInicio = 0; $pTotal = 0;  $pSubtotal = 0; $pDescuento = 0;
                            $pDescuento = $detalle->porcentaje_descuento;
                            $pTotal = round($valor_unitario);
                            $pSubtotal = round($pTotal);
                           //proceso de variables
                            $cantidad = $conDato->cantidad + 1;
                            $subtotal = $conDato->subtotal + $pSubtotal;
                            if($pDescuento > 0){
                                $descuento = round(($pSubtotal * $pDescuento)/100);
                            }else{
                               $descuento = 0;  
                            }
                            //asignacion
                            $detalle->cantidad = $cantidad;
                            $detalle->subtotal = $detalle->subtotal + $pSubtotal;
                            $detalle->valor_descuento = $detalle->valor_descuento + $descuento;
                            $detalle->total_linea = $detalle->total_linea + $pTotal - $descuento;
                            $detalle->save();
                            $id = $id;
                            $this->ActualizarSaldosTotales($id);
                           return $this->redirect(['view','id' =>$id, 'accesoToken' => $accesoToken]);
                        }   
                    }
                }else{
                    Yii::$app->getSession()->setFlash('info', 'El código del producto NO se encuentra en el sistema.');
                    return $this->redirect(['view','id' =>$id, 'accesoToken' => $accesoToken]);
                }
                
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe digitar codigo del producto a buscar.');
                return $this->redirect(['view','id' =>$id, 'accesoToken' => $accesoToken]);
            }
         }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'accesoToken' => $accesoToken,
            'detalle_remision'=> $detalle_remision,
            'form' => $form,
            'inventario' => ArrayHelper::map($inventario, "id_inventario", "inventario"),
            'punto_venta' => $punto_venta,
        ]);
    }
    
    //VISTA DE CONSULTA DE REMISIONES
    public function actionView_search_remisiones($id) {
        $model = Remisiones::findOne($id);
        $detalle_remision = \app\models\RemisionDetalles::find()->where(['=','id_remision', $id])->all();
        $talla_color = \app\models\RemisionDetalleColoresTalla::find()->where(['=','id_remision', $model->id_remision])->all();
        return $this->render('view_consulta_remision', [
                            'model' => $model,
                            'detalle_remision' => $detalle_remision,
                            'talla_color' => $talla_color,
                ]);
    }
    ///PROCESO QUE SUMA LOS TOTALES
    protected function ActualizarSaldosTotales($id) {
        $detalle_factura = \app\models\RemisionDetalles::find()->where(['=','id_remision', $id])->all();
        $factura = Remisiones::findOne($id);
        $subtotal = 0; $total = 0; $descuento = 0;
        foreach ($detalle_factura as $detalle):
            $subtotal += $detalle->subtotal;
            $total += $detalle->total_linea;
            $descuento += $detalle->valor_descuento;
        endforeach;
        $factura->valor_bruto = $subtotal;
        $factura->subtotal = $subtotal - $descuento ;
        $factura->total_remision = $factura->subtotal;
        $factura->descuento = $descuento;
        $factura->save(false);
    }
    
     //modificar cantidades a vender
    public function actionAdicionar_cantidades($id, $id_detalle, $accesoToken) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = \app\models\RemisionDetalles::findOne($id_detalle);
        $descuento = \app\models\DescuentoDistribuidor::find()->where(['=','id_inventario', $table->id_inventario])->andWhere(['=','estado_regla', 0])->one();
        $sw = 0;
        if($descuento){
            $fecha_proceso = date('Y-m-d');
            $fecha_inicio = $descuento->fecha_inicio;
            $fecha_final = $descuento->fecha_final;
            if($fecha_proceso >= $fecha_inicio && $fecha_proceso <= $fecha_final){
                $sw = 1;
            } 
        }
        
        
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["adicionar_cantidades"])) {
                $iva = 0; $subtotal = 0; $total = 0; $valor_unitario = 0; $dscto = 0;
                $producto = \app\models\InventarioPuntoVenta::find()->where(['=','id_inventario', $table->id_inventario])->andWhere(['=','id_punto', $accesoToken])->one();
                $valor_unitario = $producto->precio_mayorista;
                $total = ($valor_unitario * $model->cantidades);
                $subtotal = ($total);
                $table->cantidad = $model->cantidades;
                $table->valor_unitario = $valor_unitario;
                $table->subtotal = $subtotal;
                
                if($model->descuento > 0){
                   $dscto = round(($subtotal * $model->descuento)/100);
                   $table->total_linea = $total - $dscto;
                   $table->porcentaje_descuento = $model->descuento;
                   $table->valor_descuento = $dscto;
                }else{
                    $table->total_linea = $total;
                    $table->porcentaje_descuento = 0;
                    $table->valor_descuento = 0;
                }        
                $table->save(false);
                $this->ActualizarSaldosTotales($id);
               return $this->redirect(['view','id' =>$id, 'accesoToken' => $accesoToken]);
            }    
        }
        if($sw == 1){
            if (Yii::$app->request->get()) {
                $model->descuento = $descuento->nuevo_valor; 
            }
             return $this->renderAjax('_form_adicionar_cantidad', [
            'model' => $model,
        ]);
        }else{
             return $this->renderAjax('_form_adicionar_cantidad', [
            'model' => $model,
        ]);
        }
      
    }
    
     //PROCESO QUE AUTORIZADO O DESAUTORIZA
    public function actionAutorizado($id, $accesoToken) {
        $detalle = \app\models\RemisionDetalles::find()->where(['=','id_remision', $id])->all();
        $factura = Remisiones::findOne($id);
        $sw = 0;
        foreach ($detalle as $detalle_factura):
            if($talla_color = \app\models\RemisionDetalleColoresTalla::find()->where(['=','id_detalle', $detalle_factura->id_detalle])->one()){
                $sw = 1;
            }
        endforeach;
        if($sw == 0){
            if(count($detalle) > 0 && $factura->valor_bruto > 0){
                if($factura->autorizado == 0){
                    $factura->autorizado = 1;
                }else{
                    $factura->autorizado = 0;
                }
                $factura->save();
                $this->redirect(["view", 'id' => $id,'accesoToken' => $accesoToken]);  
            }else{
                Yii::$app->getSession()->setFlash('warning', 'No se puede AUTORIZAR la remision porque no tiene productos relacionados para la generar la venta o NO le ha asignado cantidades.'); 
                $this->redirect(["view", 'id' => $id,'accesoToken' => $accesoToken]);  
            }
        }else{
            Yii::$app->getSession()->setFlash('error', 'No se puede AUTORIZAR la remision de salida porque NO ha ingresado las TALLAS Y COLORS de la referencia ('.$detalle_factura->producto.').'); 
            $this->redirect(["view", 'id' => $id,'accesoToken' => $accesoToken]); 
        }    
            
    }
    
     //PERMITE CREAR LA TALLA Y COLOR A LA REFERENCIA
    public function actionCrear_talla_color($id, $accesoToken, $id_detalle) {
       
        $form = new \app\models\ModeloTallasColores();
        $id_talla = null;
        $id_color = null;
        $conColores = null;
        $detalle = \app\models\RemisionDetalles::findOne($id_detalle);
        $detalleTalla = \app\models\RemisionDetalleColoresTalla::find()->where(['=','id_detalle', $id_detalle])->andWhere(['=','id_remision', $id])->all();
        $conTallas = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $detalle->id_inventario])->andWhere(['>','stock_punto', 0])->orderBy('id_talla ASC')->all();
        if ($form->load(Yii::$app->request->get())) {
            $id_talla = Html::encode($form->id_talla);
            $id_color = Html::encode($form->id_color);
            if($id_talla > 0){
                $conColores = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $detalle->id_inventario])->andWhere(['=','id_talla', $id_talla ])
                                                     ->orderBy('id_color ASC')->all();
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar la talla de la lista.');
                return $this->redirect(['crear_talla_color','id' =>$id, 'accesoToken' =>$accesoToken, 'conTallas' => $conTallas, 'id_detalle' => $id_detalle]);
            }
        }
        if (Yii::$app->request->post()) {
            if (isset($_POST["enviarcolores"])) {
                if(isset($_POST["nuevo_color_entrada"])){
                    $indice = 0;
                    foreach ($_POST["nuevo_color_entrada"] as $intCodigo) {
                        $cantidad = 0;
                        $cantidad = $_POST["cantidad_venta"][$indice]; 
                        if($cantidad > 0){
                            $colores = \app\models\DetalleColorTalla::findOne($intCodigo);
                            $table = new \app\models\RemisionDetalleColoresTalla();
                            $table->id_detalle =  $id_detalle;
                            $table->id_remision = $id;
                            $table->id_inventario = $detalle->id_inventario;
                            $table->id_color = $colores->id_color;
                            $table->id_talla = $id_talla;
                            $table->cantidad_venta = $_POST["cantidad_venta"][$indice];
                            $table->save(false);
                        }    
                        $indice++; 
                    }
                     return $this->redirect(['crear_talla_color','id' =>$id, 'accesoToken' =>$accesoToken, 'conTallas' => $conTallas, 'id_detalle' => $id_detalle]);
                }else{
                   Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar un registro para procesar la informacion.'); 
                }
            }
        }    
        return $this->render('remision_detalle_tallas_colores', [
            'id' => $id,
            'accesoToken' => $accesoToken,
            'form' => $form, 
            'conColores' => $conColores,
            'conTallas' => ArrayHelper::map($conTallas, 'id_talla', 'nombreTalla'),
            'id_detalle' => $id_detalle,
            'detalleTalla' => $detalleTalla,
        ]);
    }
    //PERMITE ELIMINAR LA TALLA Y COLOR CREADO AL PRODUCTO
    public function actionEliminar_talla_color($id, $id_detalle, $accesoToken, $id_codigo)
    {                                
        $detalle = \app\models\RemisionDetalleColoresTalla::findOne($id_codigo);
        $detalle->delete();
        return $this->redirect(['crear_talla_color','id' =>$id, 'accesoToken' =>$accesoToken, 'id_detalle' => $id_detalle]);
    } 
    
     //CREAR EL CONSECUTIVO DE LA REMISION EN MAYORISTA
    public function actionGenerar_remision($id, $accesoToken) {
        //proceso de generar consecutivo
        $consecutivo = \app\models\Consecutivos::findOne(17);
        $factura = Remisiones::findOne($id);
        $factura->numero_remision = $consecutivo->numero_inicial + 1;
        $factura->save(false);
        $consecutivo->numero_inicial = $factura->numero_remision;
        $consecutivo->save(false);
        $this->redirect(["view", 'id' => $id, 'accesoToken' => $accesoToken]);  
    }
    
    //EXPORTAR REFERENCIAS AL MODULO DE INVETARIO
    public function actionExportar_inventario_punto($id, $accesoToken) {
        $facturaPunto = Remisiones::findOne($id);
        $talla_color_factura = \app\models\RemisionDetalleColoresTalla::find()->where(['=','id_remision', $id])->all();
        if(count($talla_color_factura)> 0){    
            foreach ($talla_color_factura as $factura):
                $inventario = \app\models\InventarioPuntoVenta::findOne($factura->id_inventario);
                $talla_color_bodega = \app\models\DetalleColorTalla::find()->where (['=','id_inventario', $factura->id_inventario])
                                                                           ->andWhere(['=','id_talla', $factura->id_talla])
                                                                           ->andWhere(['=','id_color', $factura->id_color])->all();
                foreach ($talla_color_bodega as $bodega):
                    $bodega->stock_punto -= $factura->cantidad_venta; 
                    $bodega->save ();
                    $inventario->stock_inventario -= $factura->cantidad_venta;
                    $inventario->save ();
                endforeach;        
            endforeach; 
            $facturaPunto->exportar_inventario = 1;
            $facturaPunto->save ();
            return $this->redirect(['view','id' =>$id, 'accesoToken' =>$accesoToken]);
        }else{
            $detalle_remision = \app\models\RemisionDetalles::find()->where(['=','id_remision', $id])->all();
            foreach ($detalle_remision as $detalle):
                $inventario = \app\models\InventarioPuntoVenta::find()->where(['=','id_inventario', $detalle->id_inventario])->andWhere(['=','id_punto', $accesoToken])->one();
                $inventario->stock_inventario -= $detalle->cantidad;
                $inventario->save();
            endforeach;
            $facturaPunto->exportar_inventario = 1;
            $facturaPunto->save ();
            return $this->redirect(['view','id' =>$id, 'accesoToken' =>$accesoToken]);
        }    
    }
    
    /**
     * Creates a new Remisiones model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($accesoToken)
    {
        $cliente = Clientes::find()->where(['=','predeterminado', 1])->one();
        if($cliente){
            $table = new Remisiones();
            $table->id_cliente = $cliente->id_cliente;
            $table->fecha_inicio = date('Y-m-d');
            $table->user_name = Yii::$app->user->identity->username;
            $table->id_punto = $accesoToken;
            $table->observacion = 'Remision para posterior facturacion.';
            $table->save();
            $remision = Remisiones::find()->orderBy('id_remision DESC')->one();
            return $this->redirect(['view','id' => $remision->id_remision, 'accesoToken' => $accesoToken]);
            
        }else{
          Yii::$app->getSession()->setFlash('warning', 'Debe de crear un cliente predeterminado para la creacion de remisiones.');
          return $this->redirect(['index']);
        }
 
       
    }

    /**
     * Updates an existing Remisiones model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $accesoToken)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->id_cliente = $model->id_cliente;
            $model->observacion = $model->observacion;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id_remision, 'accesoToken' => $accesoToken]);
        }

        return $this->render('update', [
            'model' => $model,
            'accesoToken' => $accesoToken,
        ]);
    }
    
     //ELIMINAR LINEA DEL DETALLE DE LA REMISION PUNTO DE VENTA
    public function actionEliminar_linea_remision_punto($id, $id_detalle,$accesoToken)
    {                                
        $detalle = \app\models\RemisionDetalles::findOne($id_detalle);
        $talla_color = \app\models\RemisionDetalleColoresTalla::find()->where(['=','id_detalle', $id_detalle])->one();
        if($talla_color){
            Yii::$app->getSession()->setFlash('error', 'Debe eliminar las tallas y colores de esta referencia y luego volver a ingresar las nuevas cantidades.');
            $this->redirect(["view",'id' => $id, 'accesoToken' => $accesoToken]);   
        }else{
            if($detalle->cantidad == 1){
                $detalle->delete();     
            }else{
                $cantidad = 0; $vrl_unitario = 0; $total = 0; $subtotal = 0; $descuento = 0; $porcentaje_dscto = 0; $porcentaje_iva = 0; $iva = 0;
               $producto = \app\models\InventarioPuntoVenta::findOne($detalle->id_inventario);
               $cantidad = $detalle->cantidad - 1;
               $vrl_unitario = $producto->precio_deptal;
               $porcentaje_dscto = $detalle->porcentaje_descuento;
               $total = round($cantidad * $vrl_unitario);
               $subtotal = round($total);
               $descuento = round($subtotal * $porcentaje_dscto /100);
               //asignacion
               $detalle->cantidad = $cantidad;
               $detalle->subtotal = $subtotal;
               $detalle->valor_descuento = $descuento;
               $detalle->total_linea = round($total - $descuento);
               $detalle->save();
            }
            $this->ActualizarSaldosTotales($id);
            $this->redirect(["view",'id' => $id, 'accesoToken' => $accesoToken]);   
        }    
    } 
    
      //ELIMINAR LINEA DEL DETALLE DE LA REMISION BODEGA
    public function actionEliminar_linea_remision_bodega($id, $id_detalle,$accesoToken)
    {                                
        $detalle = \app\models\RemisionDetalles::findOne($id_detalle);
        $talla_color = \app\models\RemisionDetalleColoresTalla::find()->where(['=','id_detalle', $id_detalle])->one();
        if($talla_color){
            Yii::$app->getSession()->setFlash('error', 'Debe eliminar las tallas y colores de esta referencia y luego volver a ingresar las nuevas cantidades.');
            $this->redirect(["view",'id' => $id, 'accesoToken' => $accesoToken]);   
        }else{
            var_dump($detalle->cantidad);
            if($detalle->cantidad == 1 || $detalle->cantidad == 0){
                $detalle->delete();     
            }else{
                $cantidad = 0; $vrl_unitario = 0; $total = 0; $subtotal = 0; $descuento = 0; $porcentaje_dscto = 0; $porcentaje_iva = 0; $iva = 0;
               $producto = \app\models\InventarioPuntoVenta::findOne($detalle->id_inventario);
               $cantidad = $detalle->cantidad - 1;
               $vrl_unitario = $producto->precio_deptal;
               $porcentaje_dscto = $detalle->porcentaje_descuento;
               $total = round($cantidad * $vrl_unitario);
               $subtotal = round($total);
               $descuento = round($subtotal * $porcentaje_dscto /100);
               //asignacion
               $detalle->cantidad = $cantidad;
               $detalle->subtotal = $subtotal;
               $detalle->valor_descuento = $descuento;
               $detalle->total_linea = round($total - $descuento);
               $detalle->save();
            }
           $this->ActualizarSaldosTotales($id);
           $this->redirect(["view",'id' => $id, 'accesoToken' => $accesoToken]);   
        }    
    } 

     //IMPRESIONES
    public function actionImprimir_remision_venta($id) {
        $model = Remisiones::findOne($id);
        return $this->render('../formatos/reporte_remision_venta', [
            'model' => $model,
        ]);
    }
    
    //PROCESO QUE IMPRIME EL RECIBO EN FORMARTO TICKET
    public function actionImprimir_remision_ticket($id_recibo) {
        $model = ReciboCajaPuntoVenta::findOne($id_recibo);
        return $this->render('../formatos/recibo_caja_remision_ticket', [
            'model' => $model,
        ]);
    }
    
      
    //PROCESO QUE IMPRIME LA REMISION EN TICKET
    public function actionImprimir_remision_venta_ticket($id) {
        $model = Remisiones::findOne($id);
        return $this->render('../formatos/reporte_remision_ticket', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Remisiones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Remisiones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Remisiones::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //PROCESOS DE EXCEL
    public function actionExcelRemisiones($tableexcel)
    {
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

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'NRO REMISION')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'FECHA REMISION')
                        ->setCellValue('F1', 'FECHA HORA REGISTRO')
                        ->setCellValue('G1', 'VALOR BRUTO')
                        ->setCellValue('H1', 'DSCUENTO')
                        ->setCellValue('I1', 'SUBTOTAL')
                        ->setCellValue('J1', 'TOTAL REMISION')
                        ->setCellValue('K1', 'VENDEDOR')    
                        ->setCellValue('L1', 'USER NAME')
                        ->setCellValue('M1', 'AUTORIZADO')
                        ->setCellValue('N1', 'PUNTO DE VENTA')
                        ->setCellValue('O1', 'EXPORTADO')
                        ->setCellValue('P1', 'FACTURADO');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_remision)
                        ->setCellValue('B' . $i, $val->numero_remision)
                        ->setCellValue('C' . $i, $val->cliente->nit_cedula)
                        ->setCellValue('D' . $i, $val->cliente->nombre_completo)
                        ->setCellValue('E' . $i, $val->fecha_inicio)
                        ->setCellValue('F' . $i, $val->fecha_hora_registro)
                        ->setCellValue('G' . $i, $val->valor_bruto)
                        ->setCellValue('H' . $i, $val->descuento)
                        ->setCellValue('I' . $i, $val->subtotal)
                        ->setCellValue('J' . $i, $val->total_remision)
                        ->setCellValue('K' . $i, $val->cliente->agenteComercial->nombre_completo)
                        ->setCellValue('L' . $i, $val->user_name)
                        ->setCellValue('M' . $i, $val->autorizadoRemision)
                        ->setCellValue('N' . $i, $val->puntoVenta->nombre_punto)
                        ->setCellValue('O' . $i, $val->exportarInventario)
                        ->setCellValue('P' . $i, $val->expedirFactura);
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Remisiones.xlsx"');
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
    
    //DETALLE DE LA REMISION
    public function actionExcel_detalle_remision($fecha_inicio, $fecha_corte, $cliente, $punto_venta)
    {
        if($fecha_inicio <> null && $fecha_corte <> null && $cliente <> null){
              $remision = Remisiones::find()->where(['between','fecha_inicio', $fecha_inicio, $fecha_corte])->andWhere(['=','id_cliente', $cliente])->all();
        }else{
            if($fecha_inicio <> null && $fecha_corte <> null && $punto_venta <> null){
                $remision = Remisiones::find()->where(['between','fecha_inicio', $fecha_inicio, $fecha_corte])->andWhere(['=','id_punto', $punto_venta])->all();
            }else{
               if($fecha_inicio <> null && $fecha_corte <> null){
                   $remision = Remisiones::find()->where(['between','fecha_inicio', $fecha_inicio, $fecha_corte])->all();
               }else{
                   if($cliente <> null){
                      $remision = Remisiones::find()->where(['=','id_cliente', $cliente])->all();
                   }else{
                       $remision = Remisiones::find()->where(['=','id_punto', $punto_venta])->all();
                   }
               }
            }
        }
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
            

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'NRO REMISION')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'FECHA REMISION')
                        ->setCellValue('F1', 'FECHA HORA REGISTRO')
                        ->setCellValue('G1', 'VALOR BRUTO')
                        ->setCellValue('H1', 'DSCUENTO')
                        ->setCellValue('I1', 'SUBTOTAL')
                        ->setCellValue('J1', 'TOTAL REMISION')
                        ->setCellValue('K1', 'VENDEDOR')    
                        ->setCellValue('L1', 'USER NAME')
                        ->setCellValue('M1', 'AUTORIZADO')
                        ->setCellValue('N1', 'PUNTO DE VENTA')
                        ->setCellValue('O1', 'EXPORTADO')
                        ->setCellValue('P1', 'FACTURADO')
                        ->setCellValue('Q1', 'CODIGO')
                        ->setCellValue('R1', 'PRODUCTO')
                        ->setCellValue('S1', 'CANTIDAD')
                        ->setCellValue('T1', 'VALOR UNIT.')
                        ->setCellValue('U1', 'SUBTOTAL')
                        ->setCellValue('V1', '% DESCUENTO')
                        ->setCellValue('W1', 'VR.DESCUENTO')
                        ->setCellValue('X1', 'TOTAL LINEA');
            $i = 2;

            foreach ($remision as $val) {
                $detalles = \app\models\RemisionDetalles::find()->where(['=','id_remision', $val->id_remision])->all();
                foreach ($detalles as $detalle){

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $val->id_remision)
                            ->setCellValue('B' . $i, $val->numero_remision)
                            ->setCellValue('C' . $i, $val->cliente->nit_cedula)
                            ->setCellValue('D' . $i, $val->cliente->nombre_completo)
                            ->setCellValue('E' . $i, $val->fecha_inicio)
                            ->setCellValue('F' . $i, $val->fecha_hora_registro)
                            ->setCellValue('G' . $i, $val->valor_bruto)
                            ->setCellValue('H' . $i, $val->descuento)
                            ->setCellValue('I' . $i, $val->subtotal)
                            ->setCellValue('J' . $i, $val->total_remision)
                            ->setCellValue('K' . $i, $val->cliente->agenteComercial->nombre_completo)
                            ->setCellValue('L' . $i, $val->user_name)
                            ->setCellValue('M' . $i, $val->autorizadoRemision)
                            ->setCellValue('N' . $i, $val->puntoVenta->nombre_punto)
                            ->setCellValue('O' . $i, $val->exportarInventario)
                            ->setCellValue('P' . $i, $val->expedirFactura)
                            ->setCellValue('Q' . $i, $detalle->codigo_producto)
                            ->setCellValue('R' . $i, $detalle->producto)
                            ->setCellValue('S' . $i, $detalle->cantidad)
                            ->setCellValue('T' . $i, $detalle->valor_unitario)
                            ->setCellValue('U' . $i, $detalle->subtotal)
                            ->setCellValue('V' . $i, $detalle->porcentaje_descuento)
                            ->setCellValue('W' . $i, $detalle->valor_descuento)
                            ->setCellValue('X' . $i, $detalle->total_linea);
                    $i++;
                }
                $i = $i;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Detalle_remision.xlsx"');
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
    
    //EXCEL PRODUCTO VENDIDO
 public function actionExcelProductoVendido($tableexcel)
 {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'PRODUCTO')
                    ->setCellValue('C1', 'PUNTO DE VENTA')
                    ->setCellValue('D1', 'PROVEEDOR')
                    ->setCellValue('E1', 'MARCA')
                    ->setCellValue('F1', 'CATEGORIA')
                    ->setCellValue('G1', 'FECHA VENTA')
                    ->setCellValue('H1', 'PRECIO COSTO')
                    ->setCellValue('I1', 'PRECIO VENTA')
                    ->setCellValue('J1', 'U. OPERATIVA')    
                    ->setCellValue('K1', '% UTILIDAD')
                    ->setCellValue('L1', 'U. VENDIDAS')
                    ->setCellValue('M1', 'NRO REMISION')
                    ->setCellValue('N1', 'CLIENTE')
                    ->setCellValue('O1', 'VENDEDOR')
                    ->setCellValue('P1', 'USUARIO');
                   
        $i = 2;
         $utilidad = 0;
         $porcentaje = 0;
        foreach ($tableexcel as $val) {
             $utilidad = $val->total_linea - $val->inventario->costo_unitario;
             $porcentaje = ''.number_format((($val->total_linea - $val->inventario->costo_unitario) / $val->inventario->costo_unitario) * 100);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->codigo_producto)
                    ->setCellValue('B' . $i, $val->producto)
                    ->setCellValue('C' . $i, $val->puntoVenta->nombre_punto)
                    ->setCellValue('D' . $i, $val->inventario->proveedor->nombre_completo)
                    ->setCellValue('E' . $i, $val->inventario->marca->marca)
                    ->setCellValue('F' . $i, $val->inventario->categoria->categoria)
                    ->setCellValue('G' . $i, $val->fecha_inicio)
                    ->setCellValue('H' . $i, $val->inventario->costo_unitario)
                    ->setCellValue('I' . $i, $val->total_linea)
                    ->setCellValue('J' . $i, $utilidad)
                    ->setCellValue('K' . $i, $porcentaje)
                    ->setCellValue('L' . $i, $val->cantidad)
                    ->setCellValue('M' . $i, $val->remision->numero_remision)
                    ->setCellValue('N' . $i, $val->remision->cliente->nombre_completo)
                    ->setCellValue('O' . $i, $val->remision->cliente->agenteComercial->nombre_completo)
                    ->setCellValue('P' . $i, $val->remision->user_name);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Listado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Listado_productos_vendidos.xlsx"');
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
