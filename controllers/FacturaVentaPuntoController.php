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
use app\models\FacturaVentaPunto;
use app\models\FacturaVentaPuntoDetalle;
use app\models\UsuarioDetalle;
use app\models\Clientes;
use app\models\AgentesComerciales;
use app\models\FiltroBusquedaPedidos;
use app\models\Pedidos; 
use app\models\FacturaVentaDetalle;
use app\models\TipoFacturaVenta;
use app\models\NotaCredito;
use app\models\NotaCreditoDetalle;
use app\models\ReciboCajaDetalles;


/**
 * FacturaVentaPuntoController implements the CRUD actions for FacturaVentaPunto model.
 */
class FacturaVentaPuntoController extends Controller
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
     * Lists all FacturaVentaPunto models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',89])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; $saldo = null; $numero_factura = null;
                $model = null; $punto_venta = null;
                $accesoToken = Yii::$app->user->identity->id_punto;
                $rolUsuario = Yii::$app->user->identity->role;
                $local = Yii::$app->user->identity->id_punto;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $saldo = Html::encode($form->saldo);
                        $punto_venta = Html::encode($form->punto_venta);
                        $numero_factura = Html::encode($form->numero_factura);
                        if($local == 1 ){
                            $table = FacturaVentaPunto::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['=', 'id_cliente', $cliente])
                                ->andFilterWhere(['between','fecha_inicio', $fecha_inicio, $fecha_corte])
                                ->andFilterWhere(['=','numero_factura', $numero_factura])
                                ->andFilterWhere(['>', 'saldo_factura', $saldo])     
                                ->andFilterWhere(['=','id_punto', $punto_venta])
                               ->andFilterWhere(['=','id_agente', $vendedores]);
                        }else{
                            $table = FacturaVentaPunto::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['=', 'id_cliente', $cliente])
                                ->andFilterWhere(['between','fecha_inicio', $fecha_inicio, $fecha_corte])
                                ->andFilterWhere(['=','numero_factura', $numero_factura])
                                ->andFilterWhere(['>', 'saldo_factura', $saldo])     
                                ->andFilterWhere(['=','id_agente', $vendedores])
                                ->andWhere(['=','id_punto', $accesoToken]);
                        }
                        
                        $table = $table->orderBy('id_factura DESC');
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
                            $this->actionExcelFacturaVentaPunto($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                }else{
                    if($local === 1){
                        $table = FacturaVentaPunto::find()->orderBy('id_factura DESC');
                    }else{
                        $table = FacturaVentaPunto::find()->Where(['=','id_punto', $local])->orderBy('id_factura DESC');
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
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVentaPunto($tableexcel);
                    }
                }
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'accesoToken' => $accesoToken,
                            'rolUsuario' => $rolUsuario,
                            'token' => $token,
                           
                            
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
     
    //CONSULTA DE TODAS LAS FACTUAS
    public function actionSearch_maestro_factura($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',103])->all()){
                $form = new \app\models\FiltroBusquedaPedidos();
                $documento= null;
                $cliente = null;
                $punto_venta = null;
                $desde = null;
                $hasta = null;
                $numero = null;
                $local = Yii::$app->user->identity->id_punto;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $desde = Html::encode($form->fecha_inicio);
                        $hasta = Html::encode($form->fecha_corte);
                        $punto_venta = Html::encode($form->punto_venta);
                        $numero = Html::encode($form->numero_factura);
                        if($local === 1){
                            $table = FacturaVentaPunto::find()
                                    ->andFilterWhere(['=', 'nit_cedula', $documento])
                                    ->andFilterWhere(['like', 'cliente', $cliente])
                                    ->andFilterWhere(['=', 'id_punto', $punto_venta])
                                    ->andFilterWhere(['=', 'numero_factura', $numero])  
                                    ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])      
                                    ->andWhere(['>', 'numero_factura', 0]);
                        }else{
                            $table = FacturaVentaPunto::find()
                                    ->andFilterWhere(['=', 'nit_cedula', $documento])
                                    ->andFilterWhere(['like', 'cliente', $cliente])
                                    ->andFilterWhere(['=', 'numero_factura', $numero])  
                                    ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])      
                                    ->andWhere(['=', 'id_punto', $local])
                                    ->andWhere(['>', 'numero_factura', 0]); 
                        }    
                        $table = $table->orderBy('id_factura DESC');
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
                            $this->actionExcelFacturaVentaPunto($tableexcel);
                        }
                        
                    } else {
                        $form->getErrors();
                    }
                }else{
                    if($local === 1){
                        $table = FacturaVentaPunto::find()->orderBy('id_factura DESC');
                    }else{
                        $table = FacturaVentaPunto::find()->Where(['=','id_punto', $local])->orderBy('id_factura DESC');
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
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVentaPunto($tableexcel);
                    }
                }   
                return $this->render('search_maestro_factura', [
                                'model' => $model,
                                'form' => $form,
                                'pagination' => $pages,
                                'token' => $token,
                                'local' => $local,
                    ]); 
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
     // MAESTRO CONSULTA DE PRODUCTO, FACTURAS Y CLIENTES IA
    public function actionSearch_maestro_referencia() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',106])->all()){
                $form = new \app\models\ModelBusquedaAvanzada();
                $hasta = null;
                $desde = null;
                $busqueda = null; 
                $model = null;
                $pages = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $busqueda = Html::encode($form->busqueda);
                        $desde = Html::encode($form->desde);
                        $hasta = Html::encode($form->hasta);
                        if($busqueda <> null && $desde <> null && $hasta <> null){
                            if($busqueda == 1){
                                $query =new Query();
                                $table = FacturaVentaPunto::find()->select([new Expression('SUM(subtotal_factura) as subtotal_factura, SUM(total_factura) AS total_factura, numero_factura, id_agente, id_punto'), 'id_cliente'])
                                            ->where(['between','fecha_inicio', $desde, $hasta])
                                            ->groupBy('id_cliente')
                                            ->orderBy('subtotal_factura DESC')
                                            ->limit (1)
                                            ->all();       
                                $model = $table;
                            }else{
                                $query =new Query();
                                $table = FacturaVentaPunto::find()->select([new Expression('SUM(subtotal_factura) AS subtotal_factura,  SUM(total_factura) AS total_factura, id_agente, id_cliente, id_punto'), 'id_agente'])
                                            ->where(['between','fecha_inicio', $desde, $hasta])
                                            ->groupBy('id_agente')
                                            ->orderBy('subtotal_factura ASC')
                                            ->limit (1)
                                            ->all();       
                                $model = $table;
                            }    
                        }else{
                            Yii::$app->getSession()->setFlash('info', 'Debe de seleccionar el tipo de busqueda y las fechas. Favor validar la informacion.'); 
                            return $this->redirect(['search_maestro_referencia']);
                        }
                    } else {
                        $form->getErrors();
                    }
                } 
                return $this->render('search_maestro_referencia', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'desde' => $desde,
                            'hasta' => $hasta,
                            'busqueda' => $busqueda,
                           
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    /**
     * Displays a single FacturaVentaPunto model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     // VISTA FACTURA PARA PUNTO DE VENTA CON EL MODULO DE INVENTARIOS
    public function actionView($id_factura_punto, $accesoToken) {
        
        $form = new \app\models\ModeloEntradaProducto();
        $codigo_producto = null;
        $nombre_producto = null;
        
        $factura = FacturaVentaPunto::findOne($id_factura_punto);
        $inventario = \app\models\InventarioPuntoVenta::find()->where(['>','stock_inventario', 0])
                                                          ->andWhere(['=','venta_publico', 1])->andWhere(['=','id_punto', $accesoToken])
                                                          ->orderBy('nombre_producto ASC')->all();
        $detalle_factura = FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $id_factura_punto])->all();
        if ($form->load(Yii::$app->request->get())) {
             $codigo_producto = Html::encode($form->codigo_producto);
             $nombre_producto = Html::encode($form->nombre_producto);
             if($nombre_producto > 0 ){
                $busquedaCodido = \app\models\InventarioPuntoVenta::findOne($nombre_producto); 
                $codigo_producto = $busquedaCodido->codigo_producto;
             }
             
            if ($codigo_producto > 0) {
                $conCodigo = \app\models\InventarioPuntoVenta::find()->Where(['=','codigo_producto', $codigo_producto])->andWhere(['=','id_punto', $accesoToken])->one();
                if($conCodigo){
                    $conDato = FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $id_factura_punto])
                                                          ->andWhere(['=','codigo_producto', $codigo_producto])->one();
                    //declaracion de variables
                         
                    $porcentaje = 0; $subtotal = 0; $total = 0; $iva = 0; $descuento = 0; $cantidad = 0;
                    if(!$conDato){
                    
                        $producto = \app\models\InventarioPuntoVenta::find()->where(['=','codigo_producto', $codigo_producto])->andWhere(['=','id_punto', $accesoToken])->one();
                        $table = new \app\models\FacturaVentaPuntoDetalle();
                        $table->id_factura = $id_factura_punto;
                        $table->id_inventario = $producto->id_inventario;
                        $table->codigo_producto = $codigo_producto;
                        $table->producto = $producto->nombre_producto;
                        if($producto->aplica_talla_color == 1){
                            $table->genera_talla = 1;
                        }
                        if($factura->id_tipo_venta == 3){ //para puntos de venta al deptal
                            $table->cantidad = 1;
                            $table->valor_unitario = $producto->precio_deptal;    
                            $porcentaje = number_format($conCodigo->porcentaje_iva/100,2);
                            $total = round($table->cantidad * $table->valor_unitario);
                            $iva = round($total * $porcentaje);
                            $subtotal = round($total - $iva);
                            if($producto->aplica_descuento_punto == 1){ //aplicar descuento comercial para punto de venta
                                $fecha_actual = date('Y-m-d');
                                $regla = \app\models\DescuentoPuntoVenta::find()->where(['=','id_inventario', $producto->id_inventario])->one();
                                if($regla->tipo_descuento == 1 && $regla->fecha_inicio <= $fecha_actual && $regla->fecha_final >= $fecha_actual){
                                    $descuento = round(($subtotal * $regla->nuevo_valor)/100);
                                    $table->total_linea = round($total - $descuento);
                                    $table->impuesto = round($iva);
                                    $table->subtotal = round($subtotal);
                                    $table->porcentaje_descuento = $regla->nuevo_valor;
                                    $table->valor_descuento = $descuento;
                                    $table->porcentaje_iva = $conCodigo->porcentaje_iva; 
                                }else{
                                    $descuento = 0;
                                    $table->total_linea = round($total);
                                    $table->impuesto = round($iva);
                                    $table->subtotal = round($subtotal);
                                    $table->porcentaje_descuento = 0;
                                    $table->valor_descuento = $descuento;
                                    $table->porcentaje_iva = $conCodigo->porcentaje_iva; 
                                }
                            }else{ //SI NO TIENE DESCUENTO COMERCIAL
                                $descuento = 0;
                                $table->total_linea = $total;
                                $table->impuesto = $iva;
                                $table->subtotal = $subtotal;
                                $table->porcentaje_descuento = 0;
                                $table->valor_descuento = $descuento;
                                $table->porcentaje_iva = $conCodigo->porcentaje_iva; 
                            }
                        } 
                       
                        $table->save(false);

                        $id = $id_factura_punto;
                        $this->ActualizarSaldosTotales($id);
                        $this->ActualizarConceptosTributarios($id);
                        $detalle_factura = \app\models\FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $id_factura_punto])->all();
                        $this->redirect(["factura-venta-punto/view",'id_factura_punto' => $id_factura_punto, 'detalle_factura' => $detalle_factura,'accesoToken' => $accesoToken]);
                          
                    }else{
                        if($factura->id_tipo_venta == 2){ //PROCESO AL POR MAYOR
                            Yii::$app->getSession()->setFlash('warning', 'Este producto ya se encuentra registrado en esta factura, favor subir las unidades faltantes por  la opcion de MAS');
                            return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' => $accesoToken]);
                        }else{
                             
                            //si existe el producto
                            $valor_unitario = 0;
                            $detalle = \app\models\FacturaVentaPuntoDetalle::findOne($conDato->id_detalle);
                            $producto = \app\models\InventarioPuntoVenta::find()->where(['=','codigo_producto', $codigo_producto])->andWhere(['=','id_punto', $accesoToken])->one();
                            if($factura->id_tipo_venta == 2){ //PRECIO MAYORISTA
                                $valor_unitario = $producto->precio_mayorista;    
                            }else{
                                $valor_unitario = $producto->precio_deptal;   
                            }
                            $pInicio = 0; $pTotal = 0; $pIva = 0; $pSubtotal = 0; $pDescuento = 0;
                            $pInicio = $detalle->porcentaje_iva;
                            $pDescuento = $detalle->porcentaje_descuento;
                            $pTotal = round($valor_unitario);
                            $pIva = round($pTotal * $pInicio)/100;
                            $pSubtotal = round($pTotal - $pIva);
                           //proceso de variables
                            $cantidad = $conDato->cantidad + 1;
                            $subtotal = $conDato->subtotal + $pSubtotal;
                            $iva = $conDato->impuesto + $pIva;
                            if($pDescuento > 0){
                                $descuento = round(($pSubtotal * $pDescuento)/100);
                            }else{
                               $descuento = 0;  
                            }
                            //asignacion
                            $detalle->cantidad = $cantidad;
                            $detalle->subtotal = $detalle->subtotal + $pSubtotal;
                            $detalle->valor_descuento = $detalle->valor_descuento + $descuento;
                            $detalle->impuesto = $detalle->impuesto + $pIva;
                            $detalle->total_linea = $detalle->total_linea + $pTotal - $descuento;
                            if($producto->aplica_talla_color == 1){
                                $detalle->genera_talla = 1;
                            }
                            $sumar = $this->ContarUnidadesInventario($codigo_producto, $accesoToken, $id_factura_punto);
                            if($sumar != 0){
                                $detalle->save();
                                $id = $id_factura_punto;
                                $this->ActualizarSaldosTotales($id);
                                $this->ActualizarConceptosTributarios($id);
                               return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' => $accesoToken]);
                            }else{
                                 Yii::$app->getSession()->setFlash('error', 'No hay mas inventario de esta referencia para vender. Comunicate con el administrador');
                            }   
                        }   
                    }
                }else{
                    Yii::$app->getSession()->setFlash('info', 'El código del producto NO se encuentra en el sistema.');
                    return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' => $accesoToken]);
                }
                
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe digitar codigo del producto a buscar.');
                return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' => $accesoToken]);
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id_factura_punto),
            'form' => $form,
            'inventario' => ArrayHelper::map($inventario, "id_inventario", "inventario"),
            'detalle_factura' => $detalle_factura,
            'accesoToken' => $accesoToken,
            
            
        ]);
    }
    
    //PROCESO QUE CUENTA EL INVENTARIO
    protected function ContarUnidadesInventario($codigo_producto, $accesoToken, $id_factura_punto) {
        $inventario = \app\models\InventarioPuntoVenta::find()->where(['=','codigo_producto', $codigo_producto])->andWhere(['=','id_punto', $accesoToken])->one();
        $detalle_factura = FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $id_factura_punto])->andWhere(['=','id_inventario', $inventario->id_inventario])->one();
        $sumar = $inventario->stock_inventario - $detalle_factura->cantidad;
        return ($sumar);
       
    }
    //vista de consulta facturas
    public function actionView_consulta($id_factura_punto, $token)
    {
        $detalle_factura = FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $id_factura_punto])->all();
        $talla_color = \app\models\FacturaPuntoDetalleColoresTalla::find()->where(['=','id_factura', $id_factura_punto])->all();
       // $recibo_caja = ReciboCajaDetalles::find()->where(['=','id_factura', $id])->orderBy('id_recibo DESC')->all();
        return $this->render('view_consulta_maestro', [
            'model' => $this->findModel($id_factura_punto),
            'detalle_factura' => $detalle_factura,
            'token' => $token,
            'talla_color' => $talla_color,
           // 'recibo_caja' => $recibo_caja,
        ]);
    }
    ///PROCESO QUE SUMA LOS TOTALES
    protected function ActualizarSaldosTotales($id) {
        $detalle_factura = FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $id])->all();
        $factura = FacturaVentaPunto::findOne($id);
        $subtotal = 0; $impuesto = 0; $total = 0; $descuento = 0;
        foreach ($detalle_factura as $detalle):
            $subtotal += $detalle->subtotal;
            $impuesto += $detalle->impuesto;
            $total += $detalle->total_linea;
            $descuento += $detalle->valor_descuento;
        endforeach;
        $factura->valor_bruto = $subtotal;
        $factura->subtotal_factura = $subtotal;
        $factura-> impuesto= $impuesto;
        $factura->total_factura = $total;
        $factura->saldo_factura = $total;
        $factura->valor_retencion = 0;
        $factura->valor_reteiva = 0;
        $factura->descuento = $descuento;
        $factura->save(false);
    }
    
    //PROCESO QUE TOTALIZA LOS CONCEPTOS TRIBUTARIOS
    protected function ActualizarConceptosTributarios($id) {
        $factura = FacturaVentaPunto::findOne($id);
        $tipo_factura = \app\models\TipoFacturaVenta::findOne(4);
        $reteiva = 0; $retecion = 0;
        $reteiva = round($factura->impuesto * $factura->porcentaje_rete_iva)/100; 
        if($factura->subtotal_factura > $tipo_factura->base_retencion){
           $retecion = round($factura->subtotal_factura * $factura->porcentaje_rete_fuente)/100;     
        }else{
           $retecion = 0; 
        }
        $factura->valor_retencion = $retecion;
        $factura->valor_reteiva = $reteiva;
        $factura->total_factura = round(($factura->subtotal_factura + $factura->impuesto) - ($factura->valor_retencion + $factura->valor_reteiva + $factura->descuento));
        $factura->saldo_factura = $factura->total_factura;
        $factura->save(false);
    }
    
    //modificar cantidades a vender
    public function actionAdicionar_cantidades($id_factura_punto, $id_detalle, $accesoToken) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = FacturaVentaPuntoDetalle::findOne($id_detalle);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["adicionar_cantidades"])) {
                $iva = 0; $subtotal = 0; $total = 0; $valor_unitario = 0; $dscto = 0;
                $producto = \app\models\InventarioPuntoVenta::find()->where(['=','id_inventario', $table->id_inventario])->andWhere(['=','id_punto', $accesoToken])->one();
                if($producto->precio_mayorista > 0){
                    if($model->cantidades <= $producto->stock_inventario){
                        $porcentaje = number_format($producto->porcentaje_iva / 100, 2);
                        $valor_unitario = $producto->precio_mayorista; 
                        $total = ($valor_unitario * $model->cantidades);
                        $iva = round($total * $porcentaje);
                        $subtotal = ($total - $iva);
                        $table->cantidad = $model->cantidades;
                        $table->valor_unitario = $valor_unitario;
                        $table->subtotal = $subtotal;
                        $table->impuesto = $iva;
                        if($model->descuento > 0){
                           $dscto = round(($subtotal * $model->descuento)/100);
                           $table->total_linea = $total - $dscto;
                           $table->porcentaje_descuento = $model->descuento;
                           $table->valor_descuento = $dscto;
                        }else{
                            $table->total_linea = $total;
                            $table->porcentaje_descuento = 0;
                            $table->valor_descuento = 0;
                            $table->porcentaje_iva = $producto->porcentaje_iva;
                        }        
                        $table->save();
                        $id = $id_factura_punto;
                        $this->ActualizarSaldosTotales($id);
                        $this->ActualizarConceptosTributarios($id);
                        return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' => $accesoToken]);  
                    }else{
                        Yii::$app->session->setFlash('error', 'No hay existencias suficientes de la referencia ('.$producto->nombre_producto.') para la venta.');  
                        return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' => $accesoToken]);
                    }  
                }else{
                    return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' => $accesoToken]);
                    
                }
            }    
        }
        return $this->renderAjax('_form_adicionar_cantidad', [
            'model' => $model,
           
        ]);
    }
    
      //ELIMINAR LINEA DE FACTURA DE MAYORISTA
    public function actionEliminar_linea_factura_mayorista($id_factura_punto, $id_detalle, $accesoToken)
    {                                
        $detalle = FacturaVentaPuntoDetalle::findOne($id_detalle);
        $talla_color = \app\models\FacturaPuntoDetalleColoresTalla::find()->where(['=','id_detalle', $id_detalle])->one();
        if($talla_color){
            Yii::$app->getSession()->setFlash('error', 'No se puede eliminar esta linea de compra porque debe de ELIMINAR primero las tallas y colores de la referencia.');
            $this->redirect(["view",'id_factura_punto' => $id_factura_punto, 'accesoToken' => $accesoToken]);   
        }else{
            $detalle->delete();
            $id =  $id_factura_punto;
            $this->ActualizarSaldosTotales($id);
            $this->ActualizarConceptosTributarios($id);
            $this->redirect(["view",'id_factura_punto' => $id_factura_punto, 'accesoToken' => $accesoToken]);    
        }    
    } 
    
       
    //ELIMINAR LINEA DE FACTURA DE PUNTO DE VENTA
    public function actionEliminar_linea_factura_punto($id_factura_punto, $id_detalle,$accesoToken)
    {                                
        $detalle = FacturaVentaPuntoDetalle::findOne($id_detalle);
        $talla_color = \app\models\FacturaPuntoDetalleColoresTalla::find()->where(['=','id_detalle', $id_detalle])->one();
        if($talla_color){
            Yii::$app->getSession()->setFlash('error', 'Debe eliminar las tallas y colores de esta referencia y luego volver a ingresar las nuevas cantidades.');
            $this->redirect(["view",'id_factura_punto' => $id_factura_punto, 'accesoToken' => $accesoToken]);   
        }else{
            if($detalle->cantidad == 1){
                $detalle->delete();     
            }else{
                $cantidad = 0; $vrl_unitario = 0; $total = 0; $subtotal = 0; $descuento = 0; $porcentaje_dscto = 0; $porcentaje_iva = 0; $iva = 0;
               $producto = \app\models\InventarioPuntoVenta::findOne($detalle->id_inventario);
               $cantidad = $detalle->cantidad - 1;
               $vrl_unitario = $producto->precio_deptal;
               $porcentaje_dscto = $detalle->porcentaje_descuento;
               $porcentaje_iva = number_format($detalle->porcentaje_iva / 100,2);
               $total = round($cantidad * $vrl_unitario);
               $iva = round($total * $porcentaje_iva);
               $subtotal = round($total - $iva);
               $descuento = round($subtotal * $porcentaje_dscto /100);
               //asignacion
               $detalle->cantidad = $cantidad;
               $detalle->subtotal = $subtotal;
               $detalle->impuesto = $iva;
               $detalle->valor_descuento = $descuento;
               $detalle->total_linea = round($total - $descuento);
               $detalle->save();
            }
            $id =  $id_factura_punto;
            $this->ActualizarSaldosTotales($id);
            $this->ActualizarConceptosTributarios($id);
            $this->redirect(["view",'id_factura_punto' => $id_factura_punto, 'accesoToken' => $accesoToken]);   
        }    
    } 

    /**
     * Creates a new FacturaVentaPunto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    //CREAR FACTURAS PARA PUNTOS DE VENTA
    public function actionCreate($accesoToken) {
        $model = new FacturaVentaPunto();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $matricula = \app\models\MatriculaEmpresa::findOne(1);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $cliente = Clientes::findOne($model->id_cliente);
                $iva = \app\models\ConfiguracionIva::findOne(1);
                $empresa = \app\models\MatriculaEmpresa::findOne(1);
                $tipo_factura = \app\models\TipoFacturaVenta::findOne(4);
                $resolucion = \app\models\ResolucionDian::find()->where(['=','estado_resolucion', 0])
                                                               ->andWhere(['=','abreviatura', 'PV'])->one();
                $model->id_tipo_factura = 4;
                $model->id_agente = $cliente->id_agente;
                $model->nit_cedula = $cliente->nit_cedula;
                $model->dv = $cliente->dv;
                $model->cliente = $cliente->nombre_completo;
                $model->direccion = $cliente->direccion;
                $model->telefono_cliente = $cliente->telefono;
                $model->numero_resolucion = $resolucion->numero_resolucion;
                $model->desde = $resolucion->desde;
                $model->hasta = $resolucion->hasta;
                $model->consecutivo = $resolucion->consecutivo;
                $model->fecha_inicio = $model->fecha_inicio;
                $model->fecha_vencimiento = $model->fecha_inicio;
                $model->fecha_vencimiento = date('Y-m-d');
                $model->fecha_generada = date('Y-m-d');
                $model->porcentaje_iva = $iva->valor_iva;
                $model->forma_pago = $cliente->forma_pago;
                $model->plazo_pago = $cliente->plazo;
                $model->user_name = Yii::$app->user->identity->username;
                $model->id_punto = $accesoToken;
                if($cliente->autoretenedor == 1){
                    $model->porcentaje_rete_iva = $empresa->porcentaje_reteiva;
                }else{
                    $model->porcentaje_rete_iva = 0;
                }
                if($empresa->sugiere_retencion == 0){
                   if($cliente->tipo_regimen == 1){
                        $model->porcentaje_rete_fuente = $tipo_factura->porcentaje_retencion; 
                    }else{
                        $model->porcentaje_rete_fuente = 0; 
                    }
                }else{
                    $model->porcentaje_rete_fuente = 0; 
                }
                $model->save(false);
                $table = $this->findModel($model->id_factura);
                return $this->redirect(['view','id_factura_punto' => $table->id_factura, 'accesoToken' => $accesoToken]);
                
               
        }
        if($matricula->aplica_punto_venta == 1){
           return $this->render('create', [
               'model' => $model,
               'accesoToken' => $accesoToken,
           ]);
        }else{
            Yii::$app->getSession()->setFlash('info', 'No esta autorizado para utilizar este tipo de factura. Contacte al administrador.');
            return $this->redirect(['index_factura_punto']);
        }     
                
               
    }

    /**
     * Updates an existing FacturaVentaPunto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_factura_punto, $accesoToken)
    {
        $model = $this->findModel($id_factura_punto);
        $factura = FacturaVentaPunto::findOne($id_factura_punto);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
         if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $empresa = \app\models\MatriculaEmpresa::findOne(1);
            $tipo_factura = \app\models\TipoFacturaVenta::findOne(4);
            $cliente = Clientes::findOne($model->id_cliente);
            $model->id_agente = $cliente->id_agente;
            $model->nit_cedula = $cliente->nit_cedula;
            $model->dv = $cliente->dv;
            $model->cliente = $cliente->nombre_completo;
            $model->direccion = $cliente->direccion;
            $model->telefono_cliente = $cliente->telefono;
            $model->forma_pago = $cliente->forma_pago;
            $model->plazo_pago = $cliente->plazo;
            $model->fecha_inicio = $model->fecha_inicio;
            $model->fecha_vencimiento = $model->fecha_inicio;
            if($cliente->autoretenedor == 1){
                $model->porcentaje_rete_iva = $empresa->porcentaje_reteiva;
            }else{
                $model->porcentaje_rete_iva = 0;
            }
            if($empresa->sugiere_retencion == 0){
               if($cliente->tipo_regimen == 1){
                    $model->porcentaje_rete_fuente = $tipo_factura->porcentaje_retencion; 
                }else{
                    $model->porcentaje_rete_fuente = 0; 
                }
            }else{
                $model->porcentaje_rete_fuente = 0; 
            }
            $model->save(false);
            return $this->redirect(['index']);
         }
        
        return $this->render('update', [
            'model' => $model,
            'accesoToken' => $accesoToken,

        ]);  
    }

    //PROCESO QUE AUTORIZADO O DESAUTORIZA
    public function actionAutorizado($id_factura_punto, $accesoToken) {
        $detalle = FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $id_factura_punto])->all();
        $factura = FacturaVentaPunto::findOne($id_factura_punto);
        $sw = 0;
        foreach ($detalle as $detalle_factura):
            $item = \app\models\InventarioPuntoVenta::findOne($detalle_factura->id_inventario);
            if($item->aplica_talla_color == 1){
                if(!$talla_color = \app\models\FacturaPuntoDetalleColoresTalla::find()->where(['=','id_detalle', $detalle_factura->id_detalle])->one()){
                    $sw = 1;
                }
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
                $this->redirect(["view", 'id_factura_punto' => $id_factura_punto,'accesoToken' => $accesoToken]);  
            }else{
                Yii::$app->getSession()->setFlash('warning', 'No se puede AUTORIZAR la factura porque no tiene productos relacionados, No hay precio de venta o NO se le ha asignado cantidades.'); 
                $this->redirect(["view", 'id_factura_punto' => $id_factura_punto,'accesoToken' => $accesoToken]);  
            }
        }else{
            Yii::$app->getSession()->setFlash('error', 'No se puede AUTORIZAR la factura porque NO ha ingresado las TALLAS Y COLORS de la referencia ('.$detalle_factura->producto.').'); 
            $this->redirect(["view", 'id_factura_punto' => $id_factura_punto,'accesoToken' => $accesoToken]); 
        }    
            
    }
    
    //CREAR EL CONSECUTIVO DEL FACTURA DE VENTA DE PUNTO DE VENTA
    public function actionGenerar_factura_punto($id_factura_punto, $accesoToken) {
        //proceso de generar consecutivo
        $consecutivo = \app\models\Consecutivos::findOne(16);
        $factura = FacturaVentaPunto::findOne($id_factura_punto);
        $factura->numero_factura = $consecutivo->numero_inicial + 1;
        $factura->save(false);
        $consecutivo->numero_inicial = $factura->numero_factura;
        $consecutivo->save(false);
        $this->redirect(["view", 'id_factura_punto' => $id_factura_punto, 'accesoToken' => $accesoToken]);  
    }
    
    //PERMITE CREAR LA TALLA Y COLOR A LA REFERENCIA
    public function actionCrear_talla_color($id_factura_punto, $accesoToken, $id_detalle) {
       
        $form = new \app\models\ModeloTallasColores();
        $id_talla = null;
        $id_color = null;
        $conColores = null;
        $detalle = FacturaVentaPuntoDetalle::findOne($id_detalle);
        if($detalle->cantidad > 0){
            $detalleTalla = \app\models\FacturaPuntoDetalleColoresTalla::find()->where(['=','id_detalle', $id_detalle])->andWhere(['=','id_factura', $id_factura_punto])->all();
            $conTallas = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $detalle->id_inventario])->andWhere(['>','stock_punto', 0])->orderBy('id_talla ASC')->all();
            if ($form->load(Yii::$app->request->get())) {
                $id_talla = Html::encode($form->id_talla);
                $id_color = Html::encode($form->id_color);
                if($id_talla > 0){
                    $conColores = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $detalle->id_inventario])->andWhere(['=','id_talla', $id_talla ])
                                                         ->orderBy('id_color ASC')->all();
                }else{
                    Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar la talla de la lista.');
                    return $this->redirect(['crear_talla_color','id_factura_punto' =>$id_factura_punto, 'accesoToken' =>$accesoToken, 'conTallas' => $conTallas, 'id_detalle' => $id_detalle]);
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
                                $table = new \app\models\FacturaPuntoDetalleColoresTalla();
                                $table->id_detalle =  $id_detalle;
                                $table->id_factura = $id_factura_punto;
                                $table->id_inventario = $detalle->id_inventario;
                                $table->id_color = $colores->id_color;
                                $table->id_talla = $id_talla;
                                $table->cantidad_venta = $_POST["cantidad_venta"][$indice];
                                $table->save(false);
                            }    
                            $indice++; 
                        }
                         return $this->redirect(['crear_talla_color','id_factura_punto' =>$id_factura_punto, 'accesoToken' =>$accesoToken, 'conTallas' => $conTallas, 'id_detalle' => $id_detalle]);
                    }else{
                       Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar un registro para procesar la informacion.'); 
                    }
                }
            }    
            return $this->render('factura_detalle_tallas_colores', [
                'id_factura_punto' => $id_factura_punto,
                'accesoToken' => $accesoToken,
                'form' => $form, 
                'conColores' => $conColores,
                'conTallas' => ArrayHelper::map($conTallas, 'id_talla', 'nombreTalla'),
                'id_detalle' => $id_detalle,
                'detalleTalla' => $detalleTalla,
            ]);
        }else{
            Yii::$app->getSession()->setFlash('error', 'Debe ingresar primero las unidades que a vender antes de generar las tallas y colores.'); 
            $this->redirect(["view", 'id_factura_punto' => $id_factura_punto,'accesoToken' => $accesoToken]); 
        }    
    }
    
    //PERMITE ELIMINAR LA TALLA Y COLOR CREADO AL PRODUCTO
    public function actionEliminar_talla_color($id_factura_punto, $id_detalle, $accesoToken, $id_codigo)
    {                                
        $detalle = \app\models\FacturaPuntoDetalleColoresTalla::findOne($id_codigo);
        $detalle->delete();
        return $this->redirect(['crear_talla_color','id_factura_punto' =>$id_factura_punto, 'accesoToken' =>$accesoToken, 'id_detalle' => $id_detalle]);
    } 
  
    //EXPORTAR REFERENCIAS AL MODULO DE INVETARIO
    public function actionExportar_inventario_punto($id_factura_punto, $accesoToken) {
        $facturaPunto = FacturaVentaPunto::findOne($id_factura_punto);
        $talla_color_factura = \app\models\FacturaPuntoDetalleColoresTalla::find()->where(['=','id_factura', $id_factura_punto])->all();
        $detalle_factura = FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $id_factura_punto])->andWhere(['=','genera_talla', 0])->all();
        if(count($talla_color_factura) > 0){
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
        }
        if(count($detalle_factura) > 0){
            foreach ($detalle_factura as $detalle):
                $inventario = \app\models\InventarioPuntoVenta::findOne($detalle->id_inventario);
                $inventario->stock_inventario -= $detalle->cantidad;
                $inventario->save (); 
            endforeach;             
        }
        $facturaPunto->exportar_inventario = 1;
        $facturaPunto->save ();
        return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' =>$accesoToken]);
    }
    
     //LISTAR FACTURAS POR CLIENTE
    public function actionListado_facturas($desde, $hasta, $id_cliente, $busqueda, $id_agente) {
        if($busqueda == 1){
            $model = Clientes::findOne($id_cliente);
            $facturas = FacturaVentaPunto::find()->where(['=','id_cliente', $id_cliente])
                                            ->andWhere(['between','fecha_inicio', $desde, $hasta])
                                            ->orderBy('cliente ASC')->all(); 
        }else{
            $model = AgentesComerciales::findOne($id_agente);
            $facturas = FacturaVentaPunto::find()->where(['=','id_agente', $id_agente])
                                            ->andWhere(['between','fecha_inicio', $desde, $hasta])
                                            ->orderBy('cliente ASC')->all(); 
        }    
        return $this->render('view_listado_facturas', [
            'model' =>$model,
            'facturas' => $facturas,
            'desde' => $desde,
            'hasta' =>$hasta,
            'busqueda' => $busqueda,
           
        ]);   
    }
    
     //IMPRESIONES
    public function actionImprimir_factura_venta($id_factura_punto) {
        $model = FacturaVentaPunto::findOne($id_factura_punto);
        return $this->render('../formatos/reporte_factura_venta_punto', [
            'model' => $model,
        ]);
    }
    /**
     * Finds the FacturaVentaPunto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FacturaVentaPunto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FacturaVentaPunto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
      //PROCESO QUE EXPORTA FACTURAS
    public function actionExcelFacturaVentaPunto($tableexcel) {                
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

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'No FACTURA')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'VENDEDOR')
                        ->setCellValue('F1', 'TIPO FACTURA')
                        ->setCellValue('G1', 'PUNTO DE VENTA')
                        ->setCellValue('H1', 'FECHA INICIO')
                        ->setCellValue('I1', 'FECHA VENCIMIENTO')
                        ->setCellValue('J1', 'F. ENVIADA DIAN')    
                        ->setCellValue('K1', 'FORMA PAGO')
                        ->setCellValue('L1', 'PLAZO')
                        ->setCellValue('M1', 'VR. BRUTO')
                        ->setCellValue('N1', 'DESCUENTO')
                        ->setCellValue('O1', 'SUBTOTAL')
                        ->setCellValue('P1', 'IVA')
                        ->setCellValue('Q1', 'RETENCION')
                        ->setCellValue('R1', 'RETE IVA')
                        ->setCellValue('S1', 'TOTAL PAGAR')
                        ->setCellValue('T1', 'SALDO FACTURA')
                        ->setCellValue('U1', '% IVA')
                        ->setCellValue('V1', '% RETENCION')
                        ->setCellValue('W1', '% RETE IVA')
                        ->setCellValue('X1', '% DESCUENTO')
                        ->setCellValue('Y1', 'USER CREADOR')
                        ->setCellValue('Z1', 'USER EDITADO')
                        ->setCellValue('AA1', 'F. EDITADO')
                        ->setCellValue('AB1', 'AUTORIZADO')
                        ->setCellValue('AC1', 'OBSERVACION');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_factura)
                        ->setCellValue('B' . $i, $val->numero_factura)
                        ->setCellValue('C' . $i, $val->nit_cedula)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->agente->nombre_completo)
                        ->setCellValue('F' . $i, $val->tipoFactura->descripcion)
                        ->setCellValue('G' . $i, $val->puntoVenta->nombre_punto)
                        ->setCellValue('H' . $i, $val->fecha_inicio)
                        ->setCellValue('I' . $i, $val->fecha_vencimiento)
                        ->setCellValue('J' . $i, $val->fecha_enviada)
                        ->setCellValue('K' . $i, $val->formaPago)
                        ->setCellValue('L' . $i, $val->plazo_pago)
                        ->setCellValue('M' . $i, $val->valor_bruto)
                        ->setCellValue('N' . $i, $val->descuento)
                        ->setCellValue('O' . $i, $val->subtotal_factura)
                        ->setCellValue('P' . $i, $val->impuesto)
                        ->setCellValue('Q' . $i, $val->valor_retencion)
                        ->setCellValue('R' . $i, $val->valor_reteiva)
                        ->setCellValue('S' . $i, $val->total_factura)
                        ->setCellValue('T' . $i, $val->saldo_factura)
                        ->setCellValue('U' . $i, $val->porcentaje_iva)
                        ->setCellValue('V' . $i, $val->porcentaje_rete_fuente)
                        ->setCellValue('W' . $i, $val->porcentaje_rete_iva)
                        ->setCellValue('X' . $i, $val->porcentaje_descuento)
                        ->setCellValue('Y' . $i, $val->user_name)
                        ->setCellValue('Z' . $i, $val->user_name_editado)
                        ->setCellValue('AA' . $i, $val->fecha_editada)
                        ->setCellValue('AB' . $i, $val->autorizadofactura)
                        ->setCellValue('AC' . $i, $val->observacion);
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Factura_venta.xlsx"');
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
