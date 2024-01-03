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
use app\models\FacturaVenta;
use app\models\FacturaVentaSearch;
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
 * FacturaVentaController implements the CRUD actions for FacturaVenta model.
 */
class FacturaVentaController extends Controller
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
     * Lists all FacturaVenta models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',52])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; $saldo = null; $numero_factura = null;
                $model = null;
                $pages = null;
               if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $saldo = Html::encode($form->saldo);
                        $numero_factura = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                            ->andFilterWhere(['=', 'nit_cedula', $documento])
                            ->andFilterWhere(['=', 'id_cliente', $cliente])
                            ->andFilterWhere(['between','fecha_inicio', $fecha_inicio, $fecha_corte])
                            ->andFilterWhere(['=','numero_factura', $numero_factura])
                            ->andFilterWhere(['>', 'saldo_factura', $saldo])     
                           ->andFilterWhere(['=','id_agente', $vendedores]);
                        $table = $table->orderBy('id_factura DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                    ->all();
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVenta($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                }else{
                    $table = FacturaVenta::find()->Where(['=','numero_factura', 0])->orderBy('id_factura DESC');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVenta($tableexcel);
                    }
                }
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

    //PROCESO QUE CREA LA FACTURA DE VENTA
    
    public function actionCrear_factura() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',51])->all()){
                $form = new FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; 
               if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $table = Pedidos::find()
                            ->andFilterWhere(['=', 'documento', $documento])
                            ->andFilterWhere(['=', 'id_cliente', $cliente])
                            ->andFilterWhere(['between','fecha_proceso', $fecha_inicio, $fecha_corte])
                            ->andWhere(['=','pedido_validado', 1])
                            ->andWhere(['=','facturado', 0])    
                           ->andFilterWhere(['=','id_agente', $vendedores]);
                        $table = $table->orderBy('id_pedido DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                    ->all();
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaPedidos($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = Pedidos::find()->Where(['=','pedido_validado', 1])
                                            ->andWhere(['=','facturado', 0])->orderBy('id_pedido DESC');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaPedidos($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('crear_factura_venta', [
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
    
    //LISTA TODOS LOS CLIENTES CON CARTERA PARA CADA VENDEDOR
    public function actionSearch_factura_cartera() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',60])->all()){
                $form = new \app\models\FiltroBusquedaPedidos();
                $documento= null;
                $cliente = null;
                $vendedores = null;
                $desde = null;
                $hasta = null;
                $numero = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $desde = Html::encode($form->fecha_inicio);
                        $hasta = Html::encode($form->fecha_corte);
                        $vendedores = Html::encode($form->vendedor);
                        $numero = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'cliente', $cliente])
                                ->andFilterWhere(['=', 'id_agente', $vendedores])
                                ->andFilterWhere(['=', 'numero_factura', $numero])  
                                ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])      
                                ->andWhere(['>', 'saldo_factura', 0])
                                ->andWhere(['>', 'numero_factura', 0]);
                        $table = $table->orderBy('id_factura DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVentaCartera($tableexcel);
                        }
                        
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = FacturaVenta::find()->Where(['>', 'saldo_factura', 0])
                                                 ->andWhere(['>', 'numero_factura', 0])
                                                 ->orderBy('id_factura DESC');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    $this->CargarDiasInteresMora($model);
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVentaCartera($tableexcel);
                    }
                }
                
               return $this->render('search_factura_cartera', [
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
    //SUBPROCESO DE GENERAR INTERESES
    protected function CargarDiasInteresMora($model)
    {
        $tipo_factura = TipoFacturaVenta::findOne(1);
        $fecha_dia = date('Y-m-d');
        $total_dias = 0;
        $intereses = 0;
        $porcentaje_dia = 0;
        $iva_interes = 0;
        foreach ($model as $val):
            if($tipo_factura->aplica_interes_mora == 1){
                if($val->fecha_vencimiento < $fecha_dia){ 
                    $total_dias = strtotime($fecha_dia) - strtotime($val->fecha_vencimiento);
                    $total_dias = round($total_dias / 86400)-1;
                    $porcentaje_dia = $tipo_factura->porcentaje_mora / 30;
                    $intereses = round((($val->saldo_factura * $porcentaje_dia)/100) * $total_dias);
                    $iva_interes = round(($intereses * $val->porcentaje_iva)/100);
                    $val->dias_mora = $total_dias;
                    $val->valor_intereses_mora = $intereses;
                    $val->iva_intereses_mora = $iva_interes;
                    $val->subtotal_interes_masiva = $intereses + $iva_interes;
                    $val->porcentaje_mora = $tipo_factura->porcentaje_mora;
                    $val->save(false);
                }
            }    
                
        endforeach;
    }
   //PROCESO QUE CONSULTA LAS FACTURA
    public function actionSearch_factura_venta() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',66])->all()){
                $form = new \app\models\FiltroBusquedaPedidos();
                $documento= null;
                $cliente = null;
                $vendedores = null;
                $desde = null;
                $hasta = null;
                $numero = null;
                $pages = null;
                $model = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $desde = Html::encode($form->fecha_inicio);
                        $hasta = Html::encode($form->fecha_corte);
                        $vendedores = Html::encode($form->vendedor);
                        $numero = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'cliente', $cliente])
                                ->andFilterWhere(['=', 'id_agente', $vendedores])
                                ->andFilterWhere(['=', 'numero_factura', $numero])  
                                ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])      
                                ->andWhere(['>', 'numero_factura', 0]);
                        $table = $table->orderBy('id_factura DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVenta($tableexcel);
                        }
                        
                    } else {
                        $form->getErrors();
                    }
                }   
                   return $this->render('search_factura_venta', [
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
    
    public function actionSearch_maestro_factura($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',67])->all()){
                $form = new \app\models\FiltroBusquedaPedidos();
                $documento= null;
                $cliente = null;
                $vendedores = null;
                $desde = null;
                $hasta = null;
                $numero = null;
                $pages = null;
                $model = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $desde = Html::encode($form->fecha_inicio);
                        $hasta = Html::encode($form->fecha_corte);
                        $vendedores = Html::encode($form->vendedor);
                        $numero = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'cliente', $cliente])
                                ->andFilterWhere(['=', 'id_agente', $vendedores])
                                ->andFilterWhere(['=', 'numero_factura', $numero])  
                                ->andFilterWhere(['between', 'fecha_inicio', $desde, $hasta])      
                                ->andWhere(['>', 'numero_factura', 0]);
                        $table = $table->orderBy('id_factura DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if(isset($_POST['excel'])){                    
                            $this->actionExcelFacturaVenta($tableexcel);
                        }
                        
                    } else {
                        $form->getErrors();
                    }
                }   
                return $this->render('search_maestro_factura', [
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
     * Displays a single FacturaVenta model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detalle_factura' => $detalle_factura,
            'token' => $token,
        ]);
    }
    //vista de consulta facturas
    public function actionView_consulta($id, $token)
    {
        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
        $nota_credito = NotaCredito::find()->where(['=','id_factura', $id])->orderBy('id_nota DESC')->all();
        $recibo_caja = ReciboCajaDetalles::find()->where(['=','id_factura', $id])->orderBy('id_recibo DESC')->all();
        return $this->render('view_consulta_maestro', [
            'model' => $this->findModel($id),
            'detalle_factura' => $detalle_factura,
            'token' => $token,
            'nota_credito' => $nota_credito,
            'recibo_caja' => $recibo_caja,
        ]);
    }
    // VISTA DE FACTURA DE PUNTO
    public function actionView_factura_venta($id_factura_punto)
    {
        $form = new \app\models\ModeloEntradaProducto();
        $codigo_producto = 0;
        $factura = FacturaVenta::findOne($id_factura_punto);
        $inventario = \app\models\InventarioProductos::find()->where(['>','stock_unidades', 0])
                                                          ->andWhere(['=','venta_publico', 0])
                                                          ->orderBy('nombre_producto ASC')->all();
        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id_factura_punto])->all();
        if ($form->load(Yii::$app->request->get())) {
            $codigo_producto = Html::encode($form->codigo_producto);
            if ($codigo_producto > 0) {
                $conCodigo = \app\models\InventarioProductos::find()->Where(['=','codigo_producto', $codigo_producto])->one();
                if($conCodigo){
                    $conDato = FacturaVentaDetalle::find()->where(['=','id_factura', $id_factura_punto])
                                                          ->andWhere(['=','codigo_producto', $codigo_producto])->one();
                    //declaracion de variables
                    $porcentaje = 0; $subtotal = 0; $total = 0; $iva = 0; $descuento = 0; $cantidad = 0;
                    if(!$conDato){
                        $producto = \app\models\InventarioProductos::find()->where(['=','codigo_producto', $codigo_producto])->one();
                        $table = new FacturaVentaDetalle();
                        $table->id_factura = $id_factura_punto;
                        $table->id_inventario = $producto->id_inventario;
                        $table->codigo_producto = $codigo_producto;
                        $table->producto = $producto->nombre_producto;
                        if($factura->id_tipo_venta == 3){
                            $table->cantidad = 1;
                            $table->valor_unitario = $producto->precio_deptal;    
                        
                            $porcentaje = number_format($conCodigo->porcentaje_iva/100,2);
                            $total = round($table->cantidad * $table->valor_unitario);
                            $iva = round($total * $porcentaje);
                            $subtotal = round($total - $iva);
                            if($producto->aplica_descuento == 1){ //aplicar descuento comercial para punto de venta
                                $fecha_actual = date('Y-m-d');
                                $regla = \app\models\InventarioReglaDescuento::find()->where(['=','id_inventario', $producto->id_inventario])->one();
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
                            }else{
                                $descuento = 0;
                                $table->total_linea = $total;
                                $table->impuesto = $iva;
                                $table->subtotal = $subtotal;
                                $table->porcentaje_descuento = 0;
                                $table->valor_descuento = $descuento;
                                $table->porcentaje_iva = $conCodigo->porcentaje_iva; 
                            }
                        }    
                        $table->save();
                        $id = $id_factura_punto;
                        $this->ActualizarSaldosTotales($id);
                        $this->ActualizarConceptosTributarios($id);
                        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id_factura_punto])->all();
                        $this->redirect(["factura-venta/view_factura_venta",'id_factura_punto' => $id_factura_punto, 'detalle_factura' => $detalle_factura]);
                    }else{
                        if($factura->id_tipo_venta == 2){
                            Yii::$app->getSession()->setFlash('warning', 'Este producto ya se encuentra registrado en esta factura, favor subir las unidades faltantes por  la opcion de MAS');
                            return $this->redirect(['view_factura_venta','id_factura_punto' =>$id_factura_punto]);
                        }else{
                            //si existe el producto
                            $valor_unitario = 0;
                            $detalle = FacturaVentaDetalle::findOne($conDato->id_detalle);
                            $producto = \app\models\InventarioProductos::find()->where(['=','codigo_producto', $codigo_producto])->one();
                            if($factura->id_tipo_venta == 2){
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
                            $detalle->save();
                            $id = $id_factura_punto;
                            $this->ActualizarSaldosTotales($id);
                            $this->ActualizarConceptosTributarios($id);
                           return $this->redirect(['view_factura_venta','id_factura_punto' =>$id_factura_punto]);
                        }   
                    }
                }else{
                    Yii::$app->getSession()->setFlash('info', 'El código del producto NO se encuentra en el sistema.');
                    return $this->redirect(['view_factura_venta','id_factura_punto' =>$id_factura_punto]);
                }
                
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe digitar codigo del producto a buscar.');
                return $this->redirect(['view_factura_venta','id_factura_punto' =>$id_factura_punto]);
            }
        }
        return $this->render('view_factura_venta', [
            'model' => $this->findModel($id_factura_punto),
             'form' => $form,
            'inventario' => ArrayHelper::map($inventario, "id_inventario", "inventario"),
            'detalle_factura' => $detalle_factura,
            
            
        ]);
    }
    //modificar cantidades a vender
    public function actionAdicionar_cantidades($id_factura_punto, $id_detalle) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = FacturaVentaDetalle::findOne($id_detalle);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["adicionar_cantidades"])) {
                $iva = 0; $subtotal = 0; $total = 0; $valor_unitario = 0; $dscto = 0;
                $producto = \app\models\InventarioProductos::findOne($table->id_inventario);
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
               return $this->redirect(['view_factura_venta','id_factura_punto' =>$id_factura_punto]);
            }    
        }
        return $this->renderAjax('_form_adicionar_cantidad', [
            'model' => $model,
        ]);
    }
    
    //CREAR FACTURAS PARA PUNTOS DE VENTA
    public function actionCreate() {
        $model = new FacturaVenta();
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
                return $this->redirect(['view_factura_venta','id_factura_punto' => $table->id_factura]);
        }
        if($matricula->aplica_punto_venta == 1){
            return $this->render('create', [
                'model' => $model,
            ]);
        }else{
             Yii::$app->getSession()->setFlash('info', 'No esta autorizado para utilizar este proceso de facturación. Contacte al administrador.');
             return $this->redirect(['index']);
        }    
    }

    //actualiza la fecha de la factura
    public function actionUpdate($id, $token)
    {
        $model = $this->findModel($id);
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name_editado = Yii::$app->user->identity->username;
            $model->fecha_editada = date('Y-m-d');
            $model->save(false);
            $this->DescuentoFactura($id);
            $this->ActualizarConceptosTributarios($id);
            return $this->redirect(['view', 'id' => $model->id_factura, 'token'=> $token]);
        }

        return $this->render('update', [
            'model' => $model,
            'id' => $id,
            'token' => $token,
        ]);
    }
    ///ACTUALIZAR LA FACTURA
    public function actionUpdate_factura_venta($id_factura_punto){
        
        $model = $this->findModel($id_factura_punto);
        $factura = FacturaVenta::findOne($id_factura_punto);
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
            return $this->redirect(['view_factura_venta','id_factura_punto' => $id_factura_punto]);
         }
        
        return $this->render('update_factura_venta', [
            'model' => $model,
            'id_factura_punto' => $id_factura_punto,

        ]);  
    }
    
    //PROCESO QUE PERMITE PONER EL DECUENTO A LA FACTURA
    
    protected function DescuentoFactura($id) {
        $factura = FacturaVenta::findOne($id);
        $dato = 0;
        if($factura->porcentaje_descuento > 0){
              $dato = round($factura->valor_bruto * $factura->porcentaje_descuento)/100; 
              $factura->subtotal_factura = $factura->valor_bruto - $dato;
              $factura->descuento = $dato;
              $factura->impuesto = round($factura->subtotal_factura * $factura->porcentaje_iva)/100;
              $factura->total_factura = $factura->subtotal_factura +  $factura->impuesto;
              $factura->saldo_factura = $factura->total_factura;
              $factura->save(false);
        }else{
            $dato = 0;
            $factura->subtotal_factura = $factura->valor_bruto;
            $factura->descuento = $dato;
            $factura->impuesto = $factura->impuesto;
            $factura->total_factura = $factura->total_factura ;
            $factura->saldo_factura = $factura->total_factura;
            $factura->save(false);
        }      
    }
   
    //CREAR FACTURA DESDE PEDIDO
    public function actionImportar_pedido_factura($id_pedido, $token = 0) {
        if($factura = FacturaVenta::find()->where(['=','id_pedido', $id_pedido])->one()){
            Yii::$app->getSession()->setFlash('warning', 'Este pedido esta en proceso de facturacion. Consulte con el administrador.'); 
            return $this->redirect(["factura-venta/crear_factura"]);
        }else{
           $pedido = Pedidos::find()->where(['=','id_pedido', $id_pedido])->one();
            $tipo_factura = \app\models\TipoFacturaVenta::findOne(1);
            $resolucion = \app\models\ResolucionDian::find()->where(['=','estado_resolucion', 0])->andWhere(['=','abreviatura', 'F'])->one();
            $iva = \app\models\ConfiguracionIva::findOne(1);
            $empresa = \app\models\MatriculaEmpresa::findOne(1);
            $venta = \app\models\TipoVenta::findOne(1);
            $fecha_actual = date('Y-m-d');
            $table = new FacturaVenta();
            $table->id_pedido = $id_pedido;
            $table->id_cliente = $pedido->id_cliente;
            $table->id_agente = $pedido->id_agente;
            $table->id_tipo_factura = $tipo_factura->id_tipo_factura;
            $table->id_tipo_venta = $venta->id_tipo_venta;
            $table->nit_cedula = $pedido->documento;
            $table->dv = $pedido->dv;
            $table->cliente = $pedido->cliente;
            $table->direccion = $pedido->clientePedido->direccion;
            $table->telefono_cliente = $pedido->clientePedido->celular;
            $table->numero_resolucion = $resolucion->numero_resolucion;
            $table->desde = $resolucion->desde;
            $table->hasta = $resolucion->hasta;
            $table->consecutivo = $resolucion->consecutivo;
            $table->fecha_inicio = $fecha_actual;
            $dias = $pedido->clientePedido->plazo;
            $table->fecha_vencimiento = date("Y-m-d",strtotime($fecha_actual."+".$dias."days")); 
            $table->fecha_generada = $fecha_actual;
            $table->porcentaje_iva = $iva->valor_iva;
            if($pedido->clientePedido->autoretenedor == 1){
                $table->porcentaje_rete_iva = $empresa->porcentaje_reteiva;
            }else{
                $table->porcentaje_rete_iva = 0;
            }
            if($empresa->sugiere_retencion == 0){
               if($pedido->clientePedido->tipo_regimen == 1){
                    $table->porcentaje_rete_fuente = $tipo_factura->porcentaje_retencion; 
                }else{
                    $table->porcentaje_rete_fuente = 0; 
                }
            }else{
                $table->porcentaje_rete_fuente = 0; 
            }
            $table->forma_pago = $pedido->clientePedido->forma_pago;        
            $table->plazo_pago = $pedido->clientePedido->plazo;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save();
            $model = FacturaVenta::find()->orderBy('id_factura DESC')->one();
            $id = $model->id_factura;
            $this->CrearDetalleFactura($id_pedido, $id);
            $this->ActualizarSaldosTotales($id);
            $this->ActualizarConceptosTributarios($id);
            return $this->redirect(["factura-venta/view", 'id' => $id,'token' => $token]);
        }
                
                
    }
  //PROCESO QUE QUE TOTALIZA SALDOS
    protected function CrearDetalleFactura($id_pedido, $id) {
        $detalle_pedido = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id_pedido])->all();
        foreach ($detalle_pedido as $detalle):
            $base = new FacturaVentaDetalle();
            $base->id_factura = $id;
            $base->id_inventario = $detalle->id_inventario;
            $base->codigo_producto = $detalle->inventario->codigo_producto;
            $base->producto = $detalle->inventario->nombre_producto;
            $base->cantidad = $detalle->cantidad;
            $base->valor_unitario = $detalle->valor_unitario;
            $base->subtotal = $detalle->subtotal;
            $base->impuesto = $detalle->impuesto;
            $base->total_linea = $detalle->total_linea;
            $base->save(false);
        endforeach;
    }
    ///PROCESO QUE SUMA LOS TOTALES
    protected function ActualizarSaldosTotales($id) {
        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
        $factura = FacturaVenta::findOne($id);
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
        $factura = FacturaVenta::findOne($id);
        if($factura->id_tipo_factura == 4){
            $tipo_factura = \app\models\TipoFacturaVenta::findOne(4);
        }else{
            $tipo_factura = \app\models\TipoFacturaVenta::findOne(1);
        }
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
    
    //REGERAR FACTURA
    public function actionRegenerar_factura($id, $token) {
        $this->ActualizarSaldosTotales($id);
        $this->ActualizarConceptosTributarios($id);
        $this->redirect(["view", 'id' => $id,'token' => $token]);
    }
    //PROCESO QUE AUTORIZADO O DESAUTORIZA
    public function actionAutorizado($id, $token) {
        $factura = FacturaVenta::findOne($id);
            if($factura->autorizado == 0){
                $factura->autorizado = 1;
            }else{
                $factura->autorizado = 0;
            }
            $factura->save();
            if($token == 0){
               $this->redirect(["view", 'id' => $id,'token' => $token]);    
            }else{
                $this->redirect(["view_factura_venta", 'id_factura_punto' => $id]);
            }
            
    }
    //CREAR EL CONSECUTIVO DEL FACTURA DE VENTA
     public function actionGenerar_factura($id, $id_pedido, $token) {
        //proceso de generar consecutivo
        $pedido = Pedidos::findOne($id_pedido);
        $consecutivo = \app\models\Consecutivos::findOne(6);
        $factura = FacturaVenta::findOne($id);
        $factura->numero_factura = $consecutivo->numero_inicial + 1;
        $factura->save(false);
        $consecutivo->numero_inicial = $factura->numero_factura;
        $consecutivo->save(false);
        $pedido->facturado = 1;
        $pedido->save(false);
        $this->redirect(["view", 'id' => $id, 'token' => $token]);  
    }
    //CREAR EL CONSECUTIVO DEL FACTURA DE VENTA PUNTO DE VENTA
     public function actionGenerar_factura_punto($id) {
        //proceso de generar consecutivo
        $consecutivo = \app\models\Consecutivos::findOne(6);
        $factura = FacturaVenta::findOne($id);
        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
        foreach ($detalle_factura as $detalle):
            $auxiliar = 0; $codigo = 0;
            $producto = \app\models\InventarioProductos::findOne($detalle->id_inventario);
            if($producto){
                $auxiliar = $producto->stock_unidades;
                $producto->stock_unidades = $auxiliar - $detalle->cantidad;
                $producto->save();
                $codigo = $producto->id_inventario;
                $this->ActualizaCostoInventario($codigo);
            }
        endforeach;
        $factura->numero_factura = $consecutivo->numero_inicial + 1;
        $factura->save(false);
        $consecutivo->numero_inicial = $factura->numero_factura;
        $consecutivo->save(false);
        $this->redirect(["view_factura_venta", 'id_factura_punto' => $id]);  
    }
    
    //PROCESO QUE ACTUALIZA EL INVENTARIO
    protected function ActualizaCostoInventario($codigo) {
         $iva = 0; $subtotal = 0;$total = 0;
         $inventario = \app\models\InventarioProductos::findOne($codigo);
         $subtotal = round($inventario->costo_unitario * $inventario->stock_unidades);
         $iva = round(($subtotal * $inventario->porcentaje_iva)/100);
         $total = $subtotal + $iva;
         $inventario->subtotal = $subtotal;
         $inventario->valor_iva = $iva;
         $inventario->total_inventario = $total;
         $inventario->save();
    }
    
    //ELIMINAR LINEA DE FACTURA DE MAYORISTA
    public function actionEliminar_linea_factura_mayorista($id_factura_punto, $id_detalle)
    {                                
        $detalle = FacturaVentaDetalle::findOne($id_detalle);
        $detalle->delete();
        $id =  $id_factura_punto;
        $this->ActualizarSaldosTotales($id);
        $this->ActualizarConceptosTributarios($id);
        $this->redirect(["view_factura_venta",'id_factura_punto' => $id_factura_punto]);        
    } 
    
    //ELIMINAR LINEA DE FACTURA DE PUNTO DE VENTA
    public function actionEliminar_linea_factura_punto($id_factura_punto, $id_detalle)
    {                                
        $detalle = FacturaVentaDetalle::findOne($id_detalle);
        if($detalle->cantidad == 1){
           $detalle->delete();    
        }else{
            $cantidad = 0; $vrl_unitario = 0; $total = 0; $subtotal = 0; $descuento = 0; $porcentaje_dscto = 0; $porcentaje_iva = 0; $iva = 0;
           $producto = \app\models\InventarioProductos::findOne($detalle->id_inventario);
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
        $this->redirect(["view_factura_venta",'id_factura_punto' => $id_factura_punto]);        
    } 
      
    /**
     * Finds the FacturaVenta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FacturaVenta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FacturaVenta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //IMPRESIONES
    public function actionImprimir_factura_venta($id) {
        $model = FacturaVenta::findOne($id);
        return $this->render('../formatos/reporte_factura_venta', [
            'model' => $model,
        ]);
    }
    //proceso de excel
    //PERMITE EXPORTAR A EXCEL EL PRESUPUESTO DE CADA PEDIDO 
    public function actionExcelconsultaPedidos($tableexcel) {                
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
                        ->setCellValue('B1', 'No PEDIDO')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'FECHA PEDIDO')
                        ->setCellValue('F1', 'CANTIDAD')
                        ->setCellValue('G1', 'SUBTOTAL')
                        ->setCellValue('H1', 'IVA')
                        ->setCellValue('I1', 'TOTAL')
                        ->setCellValue('J1', 'VENDEDOR')    
                        ->setCellValue('K1', 'USER NAME')
                        ->setCellValue('L1', 'AUTORIZADO')
                        ->setCellValue('M1', 'CERRADO')
                        ->setCellValue('N1', 'FACTURADO')
                        ->setCellValue('O1', 'APLICA PRESUPUESTO')
                        ->setCellValue('P1', 'VALOR PRESUPUESTO');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_pedido)
                        ->setCellValue('B' . $i, $val->numero_pedido)
                        ->setCellValue('C' . $i, $val->documento)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->fecha_proceso)
                        ->setCellValue('F' . $i, $val->cantidad)
                        ->setCellValue('G' . $i, $val->subtotal)
                        ->setCellValue('H' . $i, $val->impuesto)
                        ->setCellValue('I' . $i, $val->gran_total)
                        ->setCellValue('J' . $i, $val->agentePedido->nombre_completo)
                        ->setCellValue('K' . $i, $val->usuario)
                        ->setCellValue('L' . $i, $val->autorizadoPedido)
                        ->setCellValue('M' . $i, $val->pedidoAbierto)
                        ->setCellValue('N' . $i, $val->pedidoFacturado)
                        ->setCellValue('O' . $i, $val->presupuestoPedido)
                        ->setCellValue('P' . $i, $val->valor_presupuesto);
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Pedidos.xlsx"');
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
    //PROCESO QUE EXPORTA FACTURAS
    public function actionExcelFacturaVenta($tableexcel) {                
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
                        ->setCellValue('G1', 'No PEDIDO')
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
                        ->setCellValue('E' . $i, $val->agenteFactura->nombre_completo)
                        ->setCellValue('F' . $i, $val->tipoFactura->descripcion)
                        ->setCellValue('G' . $i, $val->pedido->numero_pedido)
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
    //exportar cartera de factura con interes de mora
      public function actionExcelFacturaVentaCartera($tableexcel) {                
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'No FACTURA')
                        ->setCellValue('C1', 'DOCUMENTO')
                        ->setCellValue('D1', 'CLIENTE')
                        ->setCellValue('E1', 'VENDEDOR')
                        ->setCellValue('F1', 'TIPO FACTURA')
                        ->setCellValue('G1', 'No PEDIDO')
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
                        ->setCellValue('Y1', 'DIAS MORA')
                        ->setCellValue('Z1', 'VR. INTERESES')
                        ->setCellValue('AA1', 'IVA MORA')
                        ->setCellValue('AB1', 'TOTAL MORA')
                        ->setCellValue('AC1', '% MORA')
                        ->setCellValue('AD1', 'USER CREADOR')
                        ->setCellValue('AE1', 'USER EDITADO')
                        ->setCellValue('AF1', 'F. EDITADO')
                        ->setCellValue('AG1', 'AUTORIZADO')
                        ->setCellValue('AH1', 'OBSERVACION');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_factura)
                        ->setCellValue('B' . $i, $val->numero_factura)
                        ->setCellValue('C' . $i, $val->nit_cedula)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->agenteFactura->nombre_completo)
                        ->setCellValue('F' . $i, $val->tipoFactura->descripcion)
                        ->setCellValue('G' . $i, $val->pedido->numero_pedido)
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
                        ->setCellValue('Y' . $i, $val->dias_mora)
                        ->setCellValue('Z' . $i, $val->valor_intereses_mora)
                        ->setCellValue('AA' . $i, $val->iva_intereses_mora)
                        ->setCellValue('AB' . $i, $val->subtotal_interes_masiva)
                        ->setCellValue('AC' . $i, $val->porcentaje_mora)
                        ->setCellValue('AD' . $i, $val->user_name)
                        ->setCellValue('AE' . $i, $val->user_name_editado)
                        ->setCellValue('AF' . $i, $val->fecha_editada)
                        ->setCellValue('AG' . $i, $val->autorizadofactura)
                        ->setCellValue('AH' . $i, $val->observacion);
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Listado');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Factura_venta_cartera.xlsx"');
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
