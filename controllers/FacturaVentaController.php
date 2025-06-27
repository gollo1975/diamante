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
use DateTime;
use DateInterval;
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
use app\models\ResolucionDian;
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
                $tipo_factura = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; $saldo = null; $numero_factura = null;
                $model = null;
                $pages = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $tipo_factura = Html::encode($form->tipo_factura);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $saldo = Html::encode($form->saldo);
                        $numero_factura = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                            ->andFilterWhere(['=', 'id_tipo_factura', $tipo_factura])
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
                    $table = FacturaVenta::find()->orderBy('id_factura DESC');
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
                $vendedores = null; $tipo_factura = null;
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
                        $tipo_factura = Html::encode($form->tipo_factura);
                        $numero = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'cliente', $cliente])
                                ->andFilterWhere(['=', 'id_agente', $vendedores])
                                ->andFilterWhere(['=', 'numero_factura', $numero])
                                ->andFilterWhere(['=', 'id_tipo_factura', $tipo_factura])
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
                $cliente = null; $saldo = null;
                $vendedores = null; $tipo_factura = null;
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
                        $tipo_factura = Html::encode($form->tipo_factura);
                        $numero = Html::encode($form->numero_factura);
                        $saldo = Html::encode($form->saldo);
                        $table = FacturaVenta::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'cliente', $cliente])
                                ->andFilterWhere(['=', 'id_agente', $vendedores])
                                ->andFilterWhere(['=', 'id_tipo_factura', $tipo_factura])
                                ->andFilterWhere(['=', 'numero_factura', $numero])  
                                ->andFilterWhere(['>', 'saldo_factura', 0])
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
                } else{
                     $table = FacturaVenta::find()->Where(['>', 'numero_factura', 0])
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
                    if(isset($_POST['excel'])){                    
                           $this->actionExcelFacturaVenta($tableexcel);
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
                $model = null; $tipo_factura = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $desde = Html::encode($form->fecha_inicio);
                        $hasta = Html::encode($form->fecha_corte);
                        $vendedores = Html::encode($form->vendedor);
                        $tipo_factura = Html::encode($form->tipo_factura);
                        $numero = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                                ->andFilterWhere(['=', 'nit_cedula', $documento])
                                ->andFilterWhere(['like', 'cliente', $cliente])
                                ->andFilterWhere(['=', 'id_agente', $vendedores])
                                ->andFilterWhere(['=', 'numero_factura', $numero])  
                                 ->andFilterWhere(['=', 'id_tipo_factura', $tipo_factura])  
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
                } else{
                     $table = FacturaVenta::find()->Where(['>', 'numero_factura', 0])
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
                             $this->actionExcelFacturaVenta($tableexcel);
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
    
    // VISTA DE FACTURA DE PUNTO PRODUCTO TERMINADO
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
    public function actionCreate($sw, $accesoToken) {
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
                $model->id_forma_pago = $cliente->id_forma_pago;
                $model->plazo_pago = $cliente->plazo;
                $model->user_name = Yii::$app->user->identity->username;
                if($sw == 1){
                   $model->id_punto = $accesoToken;
                }
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
                if($sw == 0){
                     return $this->redirect(['view_factura_venta','id_factura_punto' => $table->id_factura]);
                }else{
                    return $this->redirect(['view_factura_venta_punto','id_factura_punto' => $table->id_factura, 'accesoToken' => $accesoToken]);
                }
               
        }
            if($sw == 0){
                if($matricula->aplica_factura_produccion == 1){
                    return $this->render('create', [
                        'model' => $model,
                        'sw' => $sw,
                        'accesoToken' => $accesoToken,
                    ]);
                }else{
                     Yii::$app->getSession()->setFlash('info', 'No esta autorizado para utilizar este tipo de factura. Contacte al administrador.');
                     return $this->redirect(['index']);
                }  
            }else{
                if($matricula->aplica_punto_venta == 1){
                    return $this->render('create', [
                        'model' => $model,
                        'sw' => $sw,
                        'accesoToken' => $accesoToken,
                    ]);
                }else{
                     Yii::$app->getSession()->setFlash('info', 'No esta autorizado para utilizar este tipo de factura. Contacte al administrador.');
                     return $this->redirect(['index_factura_punto']);
                }     
            }    
               
    }
    
    //FACTURA LIBRE
    public function actionNueva_factura_libre() {
        $model = new FacturaVenta();
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())){
            if($model->id_cliente <> null){
                $cliente = Clientes::findOne($model->id_cliente);
                $iva = \app\models\ConfiguracionIva::findOne(1);
                $empresa = \app\models\MatriculaEmpresa::findOne(1);
                $tipo_factura = \app\models\TipoFacturaVenta::findOne(1);
                $resolucion = \app\models\ResolucionDian::find()->where(['=','estado_resolucion', 0])
                                                               ->andWhere(['=','id_documento', 1])->one();
                
                $model->id_tipo_factura = 1;
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
                $fecha_actual = new DateTime();
                $model->fecha_inicio = $fecha_actual->format('Y-m-d');
                $dias = $cliente->plazo;
                if (is_numeric($dias)) {
                    $fecha_vencimiento = clone $fecha_actual;
                    $fecha_vencimiento->add(new DateInterval('P' . $dias . 'D'));
                    $model->fecha_vencimiento = $fecha_vencimiento->format('Y-m-d');
                } else {
                    Yii::$app->getSession()->setFlash('error', 'El plazo del cliente No es valido el formato.');
                }
                $model->fecha_generada = date('Y-m-d');
                $model->id_tipo_venta = 1;
                $model->id_resolucion = $resolucion->id_resolucion;
                $model->porcentaje_iva = $iva->valor_iva;
                $model->id_forma_pago = $cliente->id_forma_pago;
                $model->plazo_pago = $cliente->plazo;
                $model->user_name = Yii::$app->user->identity->username;
                $model->factura_libre = 1;
                $model->save();
                $codigo = FacturaVenta::find()->orderBy('id_factura DESC')->one();
                return $this->redirect(['view','id' => $codigo->id_factura, 'token' => 0]);
            }else{
                Yii::$app->getSession()->setFlash('error', 'Debe de seleccionar un cliente de la lista.');
            }    
        }
         return $this->render('/factura-venta/_form_crear_factura_libre', ['model' => $model]);
       
    }
    
    public function actionCargar_documento_libre ($id, $token) {
        $model = new FiltroBusquedaPedidos();
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["enviar_documento"])) {
                if($model->tipo_documento <> null){
                    $Validar = FacturaVentaDetalle::find()->where(['=','codigo_producto', $model->tipo_documento])
                                                          ->andWhere(['=','id_factura', $id])->one();
                    if(!$Validar){
                        $documento = TipoFacturaVenta::findOne($model->tipo_documento);
                        $table = new FacturaVentaDetalle();
                        $table->id_factura = $id;
                        $table->codigo_producto = $model->tipo_documento;
                        $table->producto = $documento->descripcion;
                        $table->tipo_venta = $documento->abreviatura;
                        $table->cantidad = 1;
                        $table->fecha_venta = date('Y-m-d');
                        $table->save();
                        return $this->redirect(["factura-venta/view",'id' => $id, 'token' => $token]);
                    }
                }else{
                    Yii::$app->getSession()->setFlash('error', 'Debe de seleccionar el domento para facturar. Valide la informacion'); 
                    return $this->redirect(["factura-venta/view",'id' => $id, 'token' => $token]);
                }
            }
        }
        return $this->renderAjax('cargar_documento_libre', [
            'model' => $model,
            
        ]);   
    }  
    
    //EDITAR DOCUMENTO FACTURA
    public function actionEditar_concepto($id, $token, $id_detalle) {
        $model = new \app\models\FiltroModeloDocumento();
        if ($model->load(Yii::$app->request->post())) {
              if (isset($_POST["editar_documento"])) {
                  if($model->cantidad <> null && $model->valor <> null){
                       $table = FacturaVentaDetalle::findOne($id_detalle);
                       $table->cantidad = $model->cantidad;
                       $table->valor_unitario = $model->valor;
                       $table->subtotal = $model->cantidad * $model->valor;
                       $table->porcentaje_retencion = $model->porcentaje;
                       $table->save();
                       $this->ActualizaSaldoFactura($id);
                       return $this->redirect(["factura-venta/view",'id' => $id, 'token' => $token]);
                  }else{
                       Yii::$app->getSession()->setFlash('error', 'Campos vacios, debe de llenar los campos.'); 
                       return $this->redirect(["factura-venta/view",'id' => $id, 'token' => $token]);
                  }
                  
              }
        }
         return $this->renderAjax('editar_concepto_factura', [
            'model' => $model,
            
        ]);   
    }

    //ACTUALIZA SALDO DE FACTURA LIBRE
    protected function ActualizaSaldoFactura($id) {
        $model = FacturaVenta::findOne($id);
        $empresa = \app\models\MatriculaEmpresa::findOne(1);
        if($empresa->sugiere_retencion == 1){
            $cliente = Clientes::findOne($model->id_cliente);
            if($cliente->aplica_retencion_fuente == 1){
                $detalle = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
                $total = 0; 
                foreach ($detalle as $detalles) {
                    $total += $detalles->valor_unitario;
                    $porcentaje = round($total * $detalles->porcentaje_retencion)/100;
                    $valor_porcentaje = $detalles->porcentaje_retencion;
                }
                $model->valor_bruto = $total;
                $model->subtotal_factura = $total;
                $model->porcentaje_rete_fuente = $valor_porcentaje;
                $model->valor_retencion = $porcentaje;
                $model->total_factura = $total - $porcentaje;
                $model->saldo_factura = $model->total_factura;
                $model->save(false);
                
            }
        }
    }
    
    
    //actualiza la fecha de la factura
    public function actionUpdate($id, $token, $id_pedido)
    {
        $model = $this->findModel($id);
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $pedido = Pedidos::findOne($id_pedido);
            $model->user_name_editado = Yii::$app->user->identity->username;
            $model->fecha_editada = date('Y-m-d');
            $model->save(false);
            if($pedido->tipo_pedido == 2){
                $this->DescuentoFacturaNacional($id);
                $this->DescuentoFacturaInternacional($id);
                $this->ActualizarConceptosTributariosInternacional($id);
            }else{
                $this->DescuentoFacturaNacional($id);
                $this->ActualizarConceptosTributariosNacional($id);
            }
            
          
           return $this->redirect(['view', 'id' => $model->id_factura, 'token'=> $token]);
        }

        return $this->render('update', [
            'model' => $model,
            'id' => $id,
            'token' => $token,
        ]);
    }
    
    
    //EDITAR FACTURA
    public function actionEditar_factura($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }   
        if ($model->load(Yii::$app->request->post())) {
            $cliente = Clientes::findOne($model->id_cliente);
            $table = FacturaVenta::findOne($id);
            $table->id_cliente = $model->id_cliente;
            $table->cliente = $cliente->nombre_completo;
            $table->nit_cedula = $cliente->nit_cedula;
            $table->direccion =  $cliente->direccion;
            $table->telefono_cliente =  $cliente->telefono;
            $table->id_forma_pago = $cliente->id_forma_pago;
            $table->plazo_pago =  $cliente->plazo;
            $dias = $cliente->plazo;
            $table->fecha_vencimiento = date("Y-m-d",strtotime($table->fecha_inicio."+".$dias."days")); 
            $table->user_name = Yii::$app->user->identity->username;
            $table->save(false);
            return $this->redirect(['view', 'id' => $id,'token' => 0]);
        }
        return $this->render('_form_crear_factura_libre', [
            'model' => $model,
            
        ]);  
    }
    //PROCESO QUE PERMITE PONER EL DECUENTO A LA FACTURA  INTERNACIONAL
    protected function DescuentoFacturaInternacional($id) {
        $factura = FacturaVenta::findOne($id);
        $dato = 0;
        if($factura->porcentaje_descuento > 0){
              $dato = ($factura->valor_bruto_internacional * $factura->porcentaje_descuento)/100; 
              $factura->subtotal_factura_internacional = $factura->valor_bruto_internacional - $dato - $factura->descuento_comercial_internacional;
              $factura->descuento_internacional = $dato;
              $factura->total_factura_internacional = $factura->subtotal_factura_internacional +  $factura->impuesto_internacional;
              $factura->saldo_factura_internacional = $factura->total_factura_internacional;
              $factura->save(false);
        }else{
            $factura->subtotal_factura_internacional = $factura->valor_bruto_internacional - $factura->descuento_comercial_internacional;
            $factura->descuento_internacional = 0;
            $factura->total_factura_internacional = $factura->subtotal_factura_internacional + $factura->impuesto_internacional;
            $factura->saldo_factura_internacional  = $factura->total_factura_internacional;
            $factura->save(false);
        }      
    }
    
    //PROCESO QUE PERMITE PONER EL DECUENTO A LA FACTURA NACIONAL
    protected function DescuentoFacturaNacional($id) {
        $factura = FacturaVenta::findOne($id);
        $dato = 0;
        if($factura->porcentaje_descuento > 0){
              $dato = ($factura->valor_bruto * $factura->porcentaje_descuento)/100; 
              $factura->subtotal_factura = $factura->valor_bruto - $dato - $factura->descuento_comercial;
              $factura->descuento = $dato;
              $factura->total_factura = $factura->subtotal_factura +  $factura->impuesto;
              $factura->saldo_factura = $factura->total_factura;
              $factura->save(false);
        }else{
            $factura->subtotal_factura = $factura->valor_bruto - $factura->descuento_comercial;
            $factura->descuento = 0;
            $factura->total_factura = $factura->subtotal_factura + $factura->impuesto;
            $factura->saldo_factura  = $factura->total_factura;
            $factura->save(false);
        }      
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
            $model->id_forma_pago = $cliente->id_forma_pago;
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
    
    
   
    //CREAR FACTURA DESDE PEDIDO
    public function actionImportar_pedido_factura($id_pedido, $token = 0) {
        if($factura = FacturaVenta::find()->where(['=','id_pedido', $id_pedido])->one()){
            Yii::$app->getSession()->setFlash('warning', 'Este pedido esta en proceso de facturacion. Consulte con el administrador.'); 
            return $this->redirect(["factura-venta/crear_factura"]);
        }else{
            $pedido = Pedidos::find()->where(['=','id_pedido', $id_pedido])->one();
            if($pedido->tipo_pedido == 2){ //internacion
                 $tipo_factura = \app\models\TipoFacturaVenta::findOne(5);
                 $documento = \app\models\DocumentoElectronico::find()->where(['=','id_documento', 5])->one();
            }else{
                 $tipo_factura = \app\models\TipoFacturaVenta::findOne(1);
                 $documento = \app\models\DocumentoElectronico::find()->where(['=','id_documento', 1])->one();
            }
            $resolucion = \app\models\ResolucionDian::find()->where(['=','estado_resolucion', 0])->andWhere(['=','id_documento', $documento->id_documento])->one();
            $tasa = \app\models\ClienteMoneda::find()->where(['=','id_cliente', $pedido->id_cliente])->one();
            if($tasa){
                if($resolucion){
                    $iva = \app\models\ConfiguracionIva::findOne(1);
                    $empresa = \app\models\MatriculaEmpresa::findOne(1);
                    $venta = \app\models\TipoVenta::findOne(1);
                    $fecha_actual = date('Y-m-d');
                    if($fecha_actual <= $resolucion->fecha_vence){
                        if($resolucion->fecha_aviso_vencimiento < $fecha_actual){
                            Yii::$app->getSession()->setFlash('info', 'La resolucion de facturacion electronica No ('. $resolucion->numero_resolucion.') emitida por la DIAN se vence el ('.$resolucion->fecha_vence.')');
                        }
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
                        $table->descuento_comercial = $pedido->descuento_comercial;
                        $table->id_resolucion = $resolucion->id_resolucion;
                        $table->numero_resolucion = $resolucion->numero_resolucion;
                        $table->consecutivo = $resolucion->consecutivo;
                        $table->desde = $resolucion->desde;
                        $table->hasta = $resolucion->hasta;
                        $table->fecha_inicio = $fecha_actual;
                        $dias = $pedido->clientePedido->plazo -1;
                        $table->fecha_vencimiento = date("Y-m-d",strtotime($fecha_actual."+".$dias."days")); 
                        $table->fecha_generada = $fecha_actual;
                        $table->porcentaje_iva = $iva->valor_iva;
                        $table->descuento_comercial = $pedido->descuento_comercial;
                        if($pedido->clientePedido->autoretenedor == 1){
                            $table->porcentaje_rete_iva = $empresa->porcentaje_reteiva;
                        }else{
                            $table->porcentaje_rete_iva = 0;
                        }
                        if($empresa->sugiere_retencion == 1){
                            if($pedido->clientePedido->aplica_retencion_fuente == 1){
                                $table->porcentaje_rete_fuente = $tipo_factura->porcentaje_retencion; 
                            }else{
                                $table->porcentaje_rete_fuente = 0; 
                            }
                        }else{
                            $table->porcentaje_rete_fuente = 0; 
                        }

                        $table->id_forma_pago = $pedido->clientePedido->id_forma_pago;        
                        $table->plazo_pago = $pedido->clientePedido->plazo;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->save();
                        $model = FacturaVenta::find()->orderBy('id_factura DESC')->one();
                        $id = $model->id_factura;
                        if($pedido->tipo_pedido == 2){ //internacion
                            $this->CrearDetalleFactura($id_pedido, $id);
                            $this->ConvertirMonedaExtrajera($id_pedido, $id);
                            $this->ActualizarSaldosTotalesNacional($id);
                            $this->ActualizarSaldosTotalesInternacional($id, $id_pedido);
                            $this->ActualizarConceptosTributariosNacional($id);
                            $this->ActualizarConceptosTributariosInternacional($id);
                        }else{
                            $this->CrearDetalleFactura($id_pedido, $id);
                            $this->ActualizarSaldosTotalesNacional($id);
                            $this->ActualizarConceptosTributariosNacional($id);
                        }

                        return $this->redirect(["factura-venta/view", 'id' => $id,'token' => $token]);
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'La resolucion de facturacion electronica No ('. $resolucion->numero_resolucion.') emitida por la DIAN se vencio el dia ('.$resolucion->fecha_vence.'). Favor solicitar nueva resolucion.');
                        return $this->redirect(["factura-venta/crear_factura"]);
                    }  
                }else{
                    Yii::$app->getSession()->setFlash('error', 'No existe la resolucion para dicho documento o se debe de crear en ADMINISTRACION->RESOLUCIONES.');
                    return $this->redirect(["factura-venta/crear_factura"]); 
                }
            }else{
                Yii::$app->getSession()->setFlash('error', 'No existe tasa de negociacion para este cliente. Valide la informacion del cliente.');
                return $this->redirect(["factura-venta/crear_factura"]); 
                
            }    
        }
                
                
    }
    //PROCESO QUE CARGA EL DETALLE DEL PEDIDO 
    protected function CrearDetalleFactura($id_pedido, $id)
    {
        $detalle_pedido = \app\models\PedidoDetalles::find()->where(['=','id_pedido', $id_pedido])->all();
        $pedido = Pedidos::findOne($id_pedido);
        foreach ($detalle_pedido as $detalle):
            $base = new FacturaVentaDetalle();
            $base->id_factura = $id;
            $base->id_inventario = $detalle->id_inventario;
            $base->codigo_producto = $detalle->inventario->codigo_producto;
            $base->producto = $detalle->inventario->nombre_producto;
            $base->cantidad = $detalle->cantidad;
            $base->valor_unitario = $detalle->valor_unitario;
            if($pedido->tipoPedido->codigo_interface == 'PI'){
                $base->porcentaje_iva = 0;
                $base->impuesto = 0;
            }else{
                $base->porcentaje_iva = $detalle->inventario->porcentaje_iva;
                $base->impuesto = $detalle->impuesto;
            }
            $base->subtotal = $detalle->subtotal;
            $base->total_linea = $detalle->total_linea;
            $base->tipo_venta = $detalle->venta_condicionado;
            $base->fecha_venta = date('Y-m-d');
            $base->numero_lote = $detalle->numero_lote;
            $base->save(false);
        endforeach;
    }
    
    //CONVIERTE VALORES A MONEDA INTERNACION
    protected function ConvertirMonedaExtrajera($id_pedido, $id)
    {
        $pedido = Pedidos::findOne($id_pedido);
        $buscarMoneda = \app\models\ClienteMoneda::find()->where(['=','id_cliente', $pedido->id_cliente])->one();
        $valor_moneda = $buscarMoneda->tasa_negociacion;
        $detalle_pedido = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
        //variables
        $valor_unitario = 0;$subtotal = 0; $descuento = 0; $total = 0;
        foreach ($detalle_pedido as $key => $detalle):
            $valor_unitario = $detalle->valor_unitario;
            $subtotal = $detalle->subtotal;
            $descuento = $detalle->valor_descuento;
            $total = $detalle->total_linea;
            $detalle->valor_unitario_internacional = $valor_unitario / $valor_moneda;
            $detalle->subtotal_internacional = $subtotal / $valor_moneda;
            $detalle->valor_descuento_internacional / $valor_moneda;
            $detalle->total_linea_internacional = $total / $valor_moneda;
            $detalle->save();
        endforeach;
    }
    
    ///PROCESO QUE SUMA LOS TOTALES DE NACIONALES
    protected function ActualizarSaldosTotalesNacional($id)
    {
        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
        $factura = FacturaVenta::findOne($id);
        $subtotal = 0; $impuesto = 0; $total = 0; $descuento = 0; $otro_iva = 0;
        foreach ($detalle_factura as $detalle):
            $subtotal += $detalle->subtotal;
            $impuesto += $detalle->impuesto;
            $total += $detalle->total_linea;
            $descuento += $detalle->valor_descuento;
            if($detalle->tipo_venta == 'B'){
                $otro_iva += $detalle->impuesto;
            }
        endforeach;
        $factura->valor_bruto = $subtotal;
        $factura->subtotal_factura = $factura->valor_bruto - $descuento - $factura->descuento_comercial;
        if($factura->subtotal_factura <= 0){
            $factura-> impuesto= $impuesto - $otro_iva;
            $factura->total_factura = 0;
            $factura->saldo_factura = 0;
            $factura->valor_retencion = 0;
            $factura->valor_reteiva = 0;
            $factura->descuento = $descuento;
        }else{
            $factura->impuesto= $impuesto - $otro_iva;
            $factura->total_factura = 0;
            $factura->saldo_factura = 0;
            $factura->valor_retencion = 0;
            $factura->valor_reteiva = 0;
            $factura->descuento = $descuento;
        }
        $factura->save(false);
    }
    
    //PROCESO QUE SUMA TOTALES DE INTERNACIONALES
     protected function ActualizarSaldosTotalesInternacional($id, $id_pedido)
     {
        $pedido = Pedidos::findOne($id_pedido);
        $detalle_factura = FacturaVentaDetalle::find()->where(['=','id_factura', $id])->all();
        $factura = FacturaVenta::findOne($id);
        $buscarMoneda = \app\models\ClienteMoneda::find()->where(['=','id_cliente', $pedido->id_cliente])->one();
        $valor_moneda = $buscarMoneda->tasa_negociacion;
        //proceso de ingreso
        $descuento_comercial = 0; $subtotal_internacional = 0; $valor_bruto =0; 
        //asigacion variables;
        $valor_bruto = $factura->valor_bruto / $valor_moneda;
        $descuento_comercial = $factura->descuento_comercial / $valor_moneda;
        $subtotal_internacional = $factura->subtotal_factura / $valor_moneda;
       
        //declaracion
        $factura->valor_bruto_internacional = $valor_bruto;
        $factura->descuento_comercial_internacional = $descuento_comercial;
        $factura->subtotal_factura_internacional = $subtotal_internacional;
        $factura->save(false);
    }
    
    //PROCESO QUE TOTALIZA LOS CONCEPTOS TRIBUTARIOS NACIONALES
     protected function ActualizarConceptosTributariosNacional($id)
     {
        $factura = FacturaVenta::findOne($id);
        $cliente = Clientes::findOne($factura->id_cliente);
        $empresa = \app\models\MatriculaEmpresa::findOne(1);
        if($factura->id_tipo_factura == 4){
            $tipo_factura = \app\models\TipoFacturaVenta::find(4);
        }else{
            $tipo_factura = \app\models\TipoFacturaVenta::findOne(1);
        }
        $reteiva = 0; $retecion = 0;
        if($factura->id_tipo_factura == 5){
            $reteiva = 0;
        }else{
            if($cliente->autoretenedor == 1){
                $reteiva = round(($factura->impuesto * $factura->porcentaje_rete_iva)/100); 
            }else{
                $reteiva = 0;
            } 
        }    
        if($factura->id_tipo_factura == 5){
             $retecion = 0; 
        }else{
            
            if($empresa->sugiere_retencion == 1){
                if($cliente->aplica_retencion_fuente == 1) {
                    if($factura->subtotal_factura > $tipo_factura->base_retencion){
                       $retecion = round(($factura->subtotal_factura * $factura->porcentaje_rete_fuente)/100);     
                    }else{
                       $retecion = 0; 
                    }
                }else{
                    $retecion = 0; 
                }    
            }else{
                 $retecion = 0; 
            }  
        }    
        $factura->valor_retencion = $retecion;
        $factura->valor_reteiva = $reteiva;
        $factura->total_factura = round(($factura->subtotal_factura + $factura->impuesto) - ($factura->valor_retencion + $factura->valor_reteiva + $factura->descuento));
        $factura->saldo_factura = $factura->total_factura;
        $factura->save(false);
    }
    
     //PROCESO QUE TOTALIZA LOS CONCEPTOS TRIBUTARIOS NACIONALES
     protected function ActualizarConceptosTributariosInternacional($id) {
        $factura = FacturaVenta::findOne($id);
        $cliente = Clientes::findOne($factura->id_cliente);
        $moneda = \app\models\ClienteMoneda::find()->where(['=','id_cliente', $cliente->id_cliente])->one();
        $empresa = \app\models\MatriculaEmpresa::findOne(1);
        $tipo_factura = \app\models\TipoFacturaVenta::findOne(5);
        $reteiva = 0; $retecion = 0;
        if($cliente->autoretenedor == 1){
            $reteiva = $factura->valor_reteiva / $moneda->tasa_negociada; 
        }else{
            $reteiva = 0;
        }    
        if($empresa->sugiere_retencion == 1){
            if($cliente->aplica_retencion_fuente == 1){
                if($factura->subtotal_factura > $tipo_factura->base_retencion){
                   $retecion = 0;     
                }else{
                   $retecion = 0; 
                }
            }else{
                $retecion = 0; 
            }    
        }else{
             $retecion = 0; 
        }    
        $factura->valor_retencion_internacional = $retecion;
        $factura->valor_reteiva_internacional = $reteiva;
        $factura->total_factura_internacional = (($factura->subtotal_factura_internacional + $factura->impuesto_internacional) - ($factura->valor_retencion_internacional + $factura->valor_reteiva_internacional + $factura->descuento_internacional));
        $factura->saldo_factura_internacional = $factura->total_factura_internacional;
        $factura->save(false);
    }
    
    //REGENERA LA FACTURA
    public function actionRegenerar_factura($id, $token, $id_pedido) {
        $pedido = Pedidos::findOne($id_pedido);
        if($pedido->tipo_pedido == 2){
            $this->ActualizarSaldosTotalesInternacional($id, $id_pedido);
            $this->ActualizarConceptosTributariosInternacional($id);
        }else{
            $this->ActualizarSaldosTotalesNacional($id);
            $this->ActualizarConceptosTributariosNacional($id);
        }
        $this->redirect(["view", 'id' => $id,'token' => $token]);
    }
    
    
    //PROCESO QUE AUTORIZADO O DESAUTORIZA
    public function actionAutorizado($id, $token) {
       
        $factura = FacturaVenta::findOne($id);
        $Cliente = Clientes::findOne($factura->id_cliente);
        $tipoCliente = \app\models\TipoCliente::findOne($Cliente->id_tipo_cliente);
        if($tipoCliente->aplica_descuento_comercial == 0){
            if($factura->autorizado == 0){
                $factura->autorizado = 1;
            }else{
                $factura->autorizado = 0;
            }
            $factura->save(false);
            if($token == 0){
               $this->redirect(["view", 'id' => $id,'token' => $token]);    
            }else{
                 $this->redirect(["view_factura_venta", 'id_factura_punto' => $id]);
            }
        }else{
            if($tipoCliente->abreviatura == 'D' || $tipoCliente->abreviatura == 'M' ){ ///para clientes que son distribuidores
                //primer descuento
                $dias_primer_descuento = $tipoCliente->dias_descuento_uno - 1;
                $nueva_fecha_uno =  date("Y-m-d",strtotime($factura->fecha_inicio."+".$dias_primer_descuento."days")); 
                $valor_descuento_uno = round(($factura->subtotal_factura * $tipoCliente->porcentaje_descuento_uno)/100);
                $valor_pagar_uno = $factura->total_factura - $valor_descuento_uno;
                $factura->valor_pago_descuento_uno = $valor_pagar_uno;
                $factura->nota1 = 'Si paga antes del '.$nueva_fecha_uno.' obtendrá un descuento pronto pago del '.$tipoCliente->porcentaje_descuento_uno.'%. Nuevo valor a cancelar: $'.number_format($valor_pagar_uno,0).'. ';
                 //segundo descueto
                $dias_segundo_descuento =  $tipoCliente->dias_descuento_dos - 1;
                $nueva_fecha_dos =  date("Y-m-d",strtotime($factura->fecha_inicio."+".$dias_segundo_descuento."days")); 
                $valor_descuento_dos = round(($factura->subtotal_factura * $tipoCliente->porcentaje_descuento_dos)/100);
                $valor_pagar_dos = $factura->total_factura - $valor_descuento_dos;
                $factura->valor_pago_descuento_dos = $valor_pagar_dos;
                $factura->nota2 = 'Si paga antes del '.$nueva_fecha_dos.' obtendrá un descuento pronto pago del '.$tipoCliente->porcentaje_descuento_dos.'%. Nuevo valor a cancelar: $'.number_format($valor_pagar_dos,0).'. ';
                $factura->fecha_primer_descuento = $nueva_fecha_uno;
                $factura->fecha_segundo_descuento = $nueva_fecha_dos;
                if($factura->autorizado == 0){
                     $factura->autorizado = 1;
                }else{
                     $factura->autorizado = 0;
                }
                $factura->save(false);
                if($token == 0){
                    return $this->redirect(["view", 'id' => $id,'token' => $token]);    
                }else{
                    return $this->redirect(["view_factura_venta", 'id_factura_punto' => $id]);
                }
            }    
        }    
        
            
    }
   
    
    //CREAR EL CONSECUTIVO DEL FACTURA DE VENTA
     public function actionGenerar_factura($id, $id_pedido, $token) {
        //proceso de generar consecutivo
        $pedido = Pedidos::findOne($id_pedido);
        $factura = FacturaVenta::findOne($id);
        $resolucion = ResolucionDian::findOne($factura->id_resolucion);
        $terminos = \app\models\TerminosFacturaExportacion::find()->where(['=','id_factura', $id])->one();
        if($factura->id_tipo_factura == 5){
            $consecutivo = \app\models\Consecutivos::findOne(25); 
        }else{
           $consecutivo = \app\models\Consecutivos::findOne(6);  
        }
        if($factura->id_medio_pago <> ''){
            if($factura->id_tipo_factura <> 5){
                $factura->numero_factura = $consecutivo->numero_inicial + 1;
                if($factura->numero_factura <= $resolucion->rango_final){
                    $factura->save(false);
                    $consecutivo->numero_inicial = $factura->numero_factura;
                    $consecutivo->save(false);
                    $pedido->facturado = 1;
                    $pedido->save(false);
                    $this->redirect(["view", 'id' => $id, 'token' => $token]);  
                }else {
                    Yii::$app->getSession()->setFlash('error', 'Los consecutivos para esta resolucion se agotaron. Favor solictar otra resolucion en la DIAN.'); 
                    $this->redirect(["view", 'id' => $id, 'token' => $token]);
                }
               
            }else{
                if($terminos){
                    $factura->numero_factura = $consecutivo->numero_inicial + 1;
                    if($factura->numero_factura <= $resolucion->rango_final){
                        $factura->save(false);
                        $consecutivo->numero_inicial = $factura->numero_factura;
                        $consecutivo->save(false);
                        $pedido->facturado = 1;
                        $pedido->save(false);
                        $this->redirect(["view", 'id' => $id, 'token' => $token]);
                    }else{
                       Yii::$app->getSession()->setFlash('error', 'Los consecutivos para esta resolucion se agotaron. Favor solictar otra resolucion en la DIAN.'); 
                       $this->redirect(["view", 'id' => $id, 'token' => $token]); 
                    }    
                }else{
                    Yii::$app->getSession()->setFlash('error', 'Esta factura es INTERNACIONAL debe de llenar los terminos de exportación..');
                    $this->redirect(["view", 'id' => $id, 'token' => $token]); 
                }
            }    
        }else{
             Yii::$app->getSession()->setFlash('error', 'Debe de seleccionar el medio de pago para la factura.');
             $this->redirect(["view", 'id' => $id, 'token' => $token]); 
        }    
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
        return $this->redirect(["view_factura_venta",'id_factura_punto' => $id_factura_punto]);        
    } 
    
    //MEDIO DE PAGO
    public function actionSubir_medio_pago($id, $token)
    {
        $model = new \app\models\ModeloEntradaProducto();
        if ($model->load(Yii::$app->request->post())){
            if (isset($_POST["medio_pago_factura"])){
                if($model->medio_pago !== ''){
                    $factura = FacturaVenta::findOne($id);
                    $factura->id_medio_pago = $model->medio_pago;
                    $factura->observacion = $model->observacion;
                    $factura->save(false);
                    return $this->redirect(["view",'id' => $id,'token' => $token]); 
                }else{
                    Yii::$app->getSession()->setFlash('warning', 'Debe de seleccionar el medio de pago para la factura.');
                   return $this->redirect(["view",'id' => $id,'token' => $token]);  
                }    
            }  
        }
        return $this->renderAjax('form_subir_medio_pago', [
            'model' => $model,
        ]);
    }   
    
    //CONDICIONES Y TERMINOS DE LA FACTURA DE EXPORTACION
    public function actionTerminos_factura_exportacion($id, $token)
    {
        $model = new \app\models\ModeloTerminosFactura();
        if ($model->load(Yii::$app->request->post())){
            if (isset($_POST["crear_terminos_factura"])){
                if($model->validate()){
                    $consulta = \app\models\TerminosFacturaExportacion::find()->where(['=','id_factura', $id])->one();
                    if($consulta){
                        $consulta->id_inconterm = $model->id_inconterm;
                        $consulta->medio_transporte = $model->medio_transporte;
                        $consulta->ciudad_origen = $model->ciudad_origen;
                        $consulta->ciudad_destino = strtoupper($model->ciudad_destino);
                        $consulta->peso_bruto = $model->peso_bruto;
                        $consulta->peso_neto = $model->peso_neto;
                        $consulta->id_medida_producto = $model->id_medida_producto;
                        $consulta->user_name = Yii::$app->user->identity->username;
                        $consulta->id_factura = $id;
                        $consulta->codigo_pais = $model->id_pais;
                        $consulta->save(false);
                        return $this->redirect(["view",'id' => $id,'token' => $token]); 
                    }else{
                        $table = new \app\models\TerminosFacturaExportacion();
                        $table->id_inconterm = $model->id_inconterm;
                        $table->medio_transporte = $model->medio_transporte;
                        $table->ciudad_origen = $model->ciudad_origen;
                        $table->ciudad_destino = strtoupper($model->ciudad_destino);
                        $table->peso_bruto = $model->peso_bruto;
                        $table->peso_neto = $model->peso_neto;
                        $table->id_medida_producto = $model->id_medida_producto;
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->id_factura = $id;
                        $table->codigo_pais = $model->id_pais;
                        $table->save(false);
                        return $this->redirect(["view",'id' => $id,'token' => $token]); 
                    }
                    
                }else{
                   $model->getErrors();
                }    
            }  
        }
        $table = \app\models\TerminosFacturaExportacion::find()->where(['=','id_factura', $id])->one();
        if($table){
            if (Yii::$app->request->get()) {
                $model->id_inconterm = $table->id_inconterm;
                $model->medio_transporte =$table->medio_transporte;
                $model->ciudad_origen = $table->ciudad_origen;
                $model->ciudad_destino = $table->ciudad_destino;
                $model->peso_bruto = $table->peso_bruto;
                $model->peso_neto = $table->peso_neto;
                $model->id_medida_producto = $table->id_medida_producto;
                $model->id_pais =  $table->codigo_pais;
            }
        }    
        return $this->renderAjax('terminos_factura_exportacion', [
            'model' => $model,
        ]);
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
        if($model->id_tipo_factura == 1){
            return $this->render('../formatos/reporte_factura_venta', [
                'model' => $model,
            ]);
        }else{
            return $this->render('../formatos/reporte_factura_venta_exportacion', [
                'model' => $model,
            ]);
        }    
    }
    
    
    //PROCESO PARA EMITIR FACTURA ELECTRONICA A LA DIAN
    public function actionEnviar_factura_dian($id_factura, $token) {
        //models
        $factura = FacturaVenta::findOne($id_factura);
        $clientes = Clientes::findOne($factura->id_cliente);
        $detalle = FacturaVentaDetalle::find()->where(['=','id_factura', $id_factura])->all();
        //asignar variables
        $documentocliente = $factura->nit_cedula;
        $tipodocumento = $clientes->tipoDocumento->codigo_api;
        $nombre_completo = $factura->cliente; 
        $nombre_cliente = '.'; 
        $apellido_cliente = '.';
        $direccioncliente = $clientes->direccion;
        if(!$direccioncliente){
            Yii::$app->getSession()->setFlash('error', 'El campo DIRECCION del cliente  no puede estar vacío. Validar la informacion del cliente');
            return $this->redirect(["view",'id' => $id_factura,'token' => $token]); 
        }
        $telefono = $factura->telefono_cliente;
        if($clientes->email_envio_factura){
            $emailcliente = $clientes->email_envio_factura;
        }else{
            Yii::$app->getSession()->setFlash('error', 'El campo EMAIL del cliente  no puede estar vacío. Validar la informacion del cliente');
            return $this->redirect(["view",'id' => $id_factura,'token' => $token]); 
        }    
        $ciudad = $clientes->codigoMunicipio->municipio;
        if($factura->resolucionDian->codigo_interface){
            $resolucion = $factura->resolucionDian->codigo_interface;
        }else{
            Yii::$app->getSession()->setFlash('error', 'El codigo de la resolucion no esta habilitado.');
            return $this->redirect(["view",'id' => $id_factura,'token' => $token]);  
        }
        
        $consecutivo = $factura->numero_factura;
        $formapago = $factura->formaPago->codigo_api; 
        $fechainicio = $factura->fecha_inicio;
        $observacion = $factura->observacion;
        if($clientes->autoretenedor == 1){
            $rete_iva = true;
        }else{
            $rete_iva = false; 
        }
        if($clientes->aplica_retencion_fuente == 1){
            $rete_fuente = true;
        }else{
            $rete_fuente = false;
        }
        
        $curl = curl_init();
        //$API_KEY = Yii::$app->params['API_KEY_PRODUCCION']; //api_key de produccion
        $API_KEY = Yii::$app->params['API_KEY_DESARROLLO']; //api_key de desarrollo
        $dataHead = json_encode([
                "client" => [
                "document" => "$documentocliente",
                "document_type" => "$tipodocumento",
                "first_name" => "$nombre_completo",
                "last_name_one" => "$nombre_cliente",
                "last_name_two" => "$apellido_cliente",
                "address" => "$direccioncliente",
                "phone" => "$telefono",
                "email" => "$emailcliente",
                "city" => "$ciudad"
            ],
            "observacion" => "$observacion",
            "rete_iva" => "$rete_iva",
            "rete_fuente" => "$rete_fuente",
            "resolucion" => "$resolucion",
            "consecutivo" => "$consecutivo",
            "forma_pago" => "$formapago",
            "date" => "$fechainicio"
           // "items" => [] // Inicializamos el array de items
        ]); 
        $detalleFactura = \app\models\FacturaVentaDetalle::find()->where(['=','id_factura', $id_factura])->all();
        $items = []; // Inicializamos el array de items
        foreach ($detalleFactura as $detalle) {
                 $items[] = [
                    "cod_product" => "$detalle->codigo_producto",
                    "warehouse" => 1,
                    "qty" => $detalle->cantidad,
                    "concept" => "$detalle->producto",
                    "average" => $detalle->valor_unitario,
                    "total" => "$detalle->subtotal"
                ];

        }
       
        //   //ASIGNA A DATABOY EL VECTOR DE DETALLE DE FACTURAS 
        $dataBody = json_encode(["items" => $items]); // Codificamos el array de items a JSON
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://begranda.com/equilibrium2/public/api/bill?key=$API_KEY",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                "head" => $dataHead,
                "body" => $dataBody,
            ],
        ]);
        try {
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                throw new Exception(curl_error($curl));
            }
            curl_close($curl);

            $data = json_decode($response, true);
            
            if ($data === null) {
                throw new Exception('Error al decodificar la respuesta JSON');
            }
            
             // Validar y extraer el CUFE
            if (isset($data['add']['fe']['cufe'])) {
                $cufe = $data['add']['fe']['cufe'];
                $fechaRecepcion = isset($data["data"]["sentDetail"]["response"]["send_email_date_time"]) && !empty($data["data"]["sentDetail"]["response"]["send_email_date_time"]) ? $data["data"]["sentDetail"]["response"]["send_email_date_time"] : date("Y-m-d H:i:s");
                $factura->fecha_recepcion_dian = $fechaRecepcion;
                $factura->fecha_enviada_api = date("Y-m-d H:i:s");
                $factura->save(false);
                if($cufe){
                    $factura->cufe = $cufe;
                    $qrstr = $data['add']['fe']['sentDetail']['response']['QRStr'];
                    $factura->qrstr = $qrstr;
                    $factura->save(false);
                    Yii::$app->getSession()->setFlash('success', "La factura de venta electrónica No ($consecutivo) se envió con éxito a la DIAN.");
                }else{
                   $factura->fecha_enviada_api = date("Y-m-d H:i:s");
                   $factura->save(false);
                   Yii::$app->getSession()->setFlash('warning', "La factura de venta electrónica No ($consecutivo) NO se envió a la DIAN. Favor reenviar el documento nuevamente.");
                   return $this->redirect(['factura-venta/view', 'id' => $id_factura, 'token' => $token]);
                } 
                return $this->redirect(['factura-venta/view', 'id' => $id_factura, 'token' => $token]);
            } else {
                $factura->fecha_enviada_api = date("Y-m-d H:i:s");
                $factura->save(false);
                Yii::$app->getSession()->setFlash('error', "La factura no se envio a la Dian y se encuentra en la API de comunicacion."); 
              //  return $this->redirect(['factura-venta/view', 'id' => $id_factura, 'token' => $token]);
            }
            
        } catch (Exception $e) {
            Yii::$app->getSession()->setFlash('error', 'Error al enviar la factura: ' . $e->getMessage());
        }    
       
        // return $this->redirect(['view','id' => $id_factura,'token' => $token]);   
    }
    
    //PERMITE REENVIAR LA FACTURA SI NO SE CONECTA A LA DIAN
    public function actionReenviar_documento_dian($id_factura, $token)  {
        // Instanciar la factura desde la base de datos
        $factura = FacturaVenta::findOne($id_factura);
        if (!$factura) {
            Yii::$app->getSession()->setFlash('error', 'Factura no encontrada.');
            return $this->redirect(['factura-venta/view', 'id' => $id_factura, 'token' => $token]);
        }
        //ASIGNACION DE VARIABLES
        $resolucion = $factura->resolucionDian->codigo_interface;
        $consecutivo = $factura->numero_factura;
        // URL y clave API
        $API_URL = "http://begranda.com/equilibrium2/public/api/send-electronic-invoice";
        $API_KEY = Yii::$app->params['API_KEY_DESARROLLO']; //api_key de desarrollo
       // $API_KEY = Yii::$app->params['API_KEY_PRODUCCION']; //api_key de produccion

        // Inicializar CURL
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$API_URL?key=$API_KEY&consecutivo=$consecutivo&id_resolucion=$resolucion",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,  // Timeout extendido
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [],
        ]);

        // Ejecutar la solicitud CURL y verificar la respuesta
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Registrar la respuesta completa de la API para depuración
        Yii::info("Respuesta completa de la API desde Begranda: $response", __METHOD__);

        // Verificar errores de conexión o códigos HTTP inesperados
        if ($response === false || $httpCode !== 200) {
            $error = $response === false ? curl_error($curl) : "HTTP $httpCode";
            Yii::$app->getSession()->setFlash('error', 'Hubo un problema al comunicarse con la DIAN. Intenta reenviar más tarde.');
            Yii::error("Error en la solicitud CURL: $error", __METHOD__);
            return $this->redirect(['factura-venta/view', 'id' => $id_factura, 'token' => $token]);
        }

        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Yii::$app->getSession()->setFlash('error', 'Error al procesar la respuesta de la DIAN. Intenta reenviar más tarde.');
            Yii::error("Error al decodificar JSON: " . json_last_error_msg(), __METHOD__);
            return $this->redirect(['factura-venta/view', 'id' => $id_factura, 'token' => $token]);
        }

        // Comprobamos el 'status' de la respuesta para determinar éxito o error
        if (isset($data['status']) && $data['status'] == 'success') {
            // Si la respuesta es exitosa
            Yii::$app->getSession()->setFlash('success', "La factura de venta electrónica No ($consecutivo) se reenvió con éxito a la DIAN.");
            
            // Asignar CUFE y fecha de recepción solo si están disponibles en la respuesta
            $cufe = isset($data["data"]["cufe"]) ? $data["data"]["cufe"] : "";
            $qrstr = isset($data['data']['sentDetail']['response']['QRStr']) ? $data["data"]['sentDetail']['response']['QRStr'] : "";
            $factura->cufe = $cufe;
            $fechaRecepcion = isset($data["data"]["sentDetail"]["response"]["send_email_date_time"]) && !empty($data["data"]["sentDetail"]["response"]["send_email_date_time"]) ? $data["data"]["sentDetail"]["response"]["send_email_date_time"] : date("Y-m-d H:i:s");
            $factura->fecha_recepcion_dian = $fechaRecepcion;
            $factura->reenviar_factura = 0; // Marcar como no pendiente de reenvío
            $factura->qrstr = $qrstr;
            $factura->save(false);
            Yii::info("Respuesta exitosa de la API: " . print_r($data, true), __METHOD__);
        } else {
            // Si el 'status' no es success o hay un mensaje de error
            $errorMessage = isset($data['message']) ? $data['message'] : 'Error desconocido';
            // Mostrar el mensaje específico de la API
            Yii::$app->getSession()->setFlash('error', "No se pudo reenviar la factura. Error: $errorMessage.");
            Yii::error("Error al reenviar factura No ($consecutivo): " . print_r($data, true), __METHOD__);
            $factura->reenviar_factura = 1; // Mantener la factura pendiente de reenvío
            $factura->save(false);
        }

        // Intentar guardar la factura en la base de datos
        if (!$factura->save(false)) {
            Yii::error("Error al guardar la factura No ($consecutivo): " . print_r($factura->errors, true), __METHOD__);
            Yii::$app->getSession()->setFlash('error', 'Hubo un error al guardar la factura.');
            return $this->redirect(['factura-venta/view', 'id' => $id_factura, 'token' => $token]);
        }

        // Redirigir a la vista de la factura
        return $this->redirect(['factura-venta/view', 'id' => $id_factura, 'token' => $token]);
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
            

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
                        ->setCellValue('J1', 'F. ENVIADA API')  
                        ->setCellValue('K1', 'F. ENVIADA DIAN')  
                        ->setCellValue('L1', 'FORMA PAGO')
                        ->setCellValue('M1', 'PLAZO')
                        ->setCellValue('N1', 'VR. BRUTO')
                        ->setCellValue('O1', 'DESCUENTO')
                        ->setCellValue('P1', 'SUBTOTAL')
                        ->setCellValue('Q1', 'IVA')
                        ->setCellValue('R1', 'RETENCION')
                        ->setCellValue('S1', 'RETE IVA')
                        ->setCellValue('T1', 'TOTAL PAGAR')
                        ->setCellValue('U1', 'SALDO FACTURA')
                        ->setCellValue('V1', '% IVA')
                        ->setCellValue('W1', '% RETENCION')
                        ->setCellValue('X1', '% RETE IVA')
                        ->setCellValue('Y1', '% DESCUENTO')
                        ->setCellValue('Z1', 'USER CREADOR')
                        ->setCellValue('AA1', 'USER EDITADO')
                        ->setCellValue('AB1', 'F. EDITADO')
                        ->setCellValue('AC1', 'AUTORIZADO')
                        ->setCellValue('AD1', 'OBSERVACION')
                        ->setCellValue('AE1', 'DESCTO PAGO UNO')
                        ->setCellValue('AF1', 'DESCTO PAGO DOS')
                        ->setCellValue('AG1', 'CUFE')
                        ->setCellValue('AH1', 'NOTA 1')
                        ->setCellValue('AI1', 'NOTA 2');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_factura)
                        ->setCellValue('B' . $i, $val->numero_factura)
                        ->setCellValue('C' . $i, $val->nit_cedula)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->agenteFactura->nombre_completo)
                        ->setCellValue('F' . $i, $val->tipoFactura->descripcion);
                       if($val->id_pedido == null){
                             $objPHPExcel->setActiveSheetIndex(0)
                             ->setCellValue('G' . $i, 'NO FOUND');
                        }else{
                            $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('G' . $i, $val->pedido->numero_pedido);
                        }   
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('H' . $i, $val->fecha_inicio)
                        ->setCellValue('I' . $i, $val->fecha_vencimiento)
                        ->setCellValue('J' . $i, $val->fecha_enviada_api)
                        ->setCellValue('K' . $i, $val->fecha_recepcion_dian)        
                        ->setCellValue('L' . $i, $val->formaPago->concepto)
                        ->setCellValue('M' . $i, $val->plazo_pago)
                        ->setCellValue('N' . $i, $val->valor_bruto)
                        ->setCellValue('O' . $i, $val->descuento)
                        ->setCellValue('P' . $i, $val->subtotal_factura)
                        ->setCellValue('Q' . $i, $val->impuesto)
                        ->setCellValue('R' . $i, $val->valor_retencion)
                        ->setCellValue('S' . $i, $val->valor_reteiva)
                        ->setCellValue('T' . $i, $val->total_factura)
                        ->setCellValue('U' . $i, $val->saldo_factura)
                        ->setCellValue('V' . $i, $val->porcentaje_iva)
                        ->setCellValue('W' . $i, $val->porcentaje_rete_fuente)
                        ->setCellValue('X' . $i, $val->porcentaje_rete_iva)
                        ->setCellValue('Y' . $i, $val->porcentaje_descuento)
                        ->setCellValue('Z' . $i, $val->user_name)
                        ->setCellValue('AA' . $i, $val->user_name_editado)
                        ->setCellValue('AB' . $i, $val->fecha_editada)
                        ->setCellValue('AC' . $i, $val->autorizadofactura)
                        ->setCellValue('AD' . $i, $val->observacion)
                        ->setCellValue('AE' . $i, $val->valor_pago_descuento_uno)
                        ->setCellValue('AF' . $i, $val->valor_pago_descuento_dos)
                        ->setCellValue('AG' . $i, $val->cufe)
                        ->setCellValue('AH' . $i, $val->nota1) 
                        ->setCellValue('AI' . $i, $val->nota2);
                            
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);

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
                        ->setCellValue('J1', 'F. ENVIADA API')
                        ->setCellValue('K1', 'F. RECEPCION DIAN')
                        ->setCellValue('L1', 'FORMA PAGO')
                        ->setCellValue('M1', 'PLAZO')
                        ->setCellValue('N1', 'VR. BRUTO')
                        ->setCellValue('O1', 'DESCUENTO')
                        ->setCellValue('P1', 'SUBTOTAL')
                        ->setCellValue('Q1', 'IVA')
                        ->setCellValue('R1', 'RETENCION')
                        ->setCellValue('S1', 'RETE IVA')
                        ->setCellValue('T1', 'TOTAL PAGAR')
                        ->setCellValue('U1', 'SALDO FACTURA')
                        ->setCellValue('V1', '% IVA')
                        ->setCellValue('W1', '% RETENCION')
                        ->setCellValue('X1', '% RETE IVA')
                        ->setCellValue('Y1', '% DESCUENTO')
                        ->setCellValue('Z1', 'DIAS MORA')
                        ->setCellValue('AA1', 'VR. INTERESES')
                        ->setCellValue('AB1', 'IVA MORA')
                        ->setCellValue('AC1', 'TOTAL MORA')
                        ->setCellValue('AD1', '% MORA')
                        ->setCellValue('AE1', 'USER CREADOR')
                        ->setCellValue('AF1', 'USER EDITADO')
                        ->setCellValue('AG1', 'F. EDITADO')
                        ->setCellValue('AH1', 'AUTORIZADO')
                        ->setCellValue('AI1', 'OBSERVACION');
            $i = 2;

            foreach ($tableexcel as $val) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $val->id_factura)
                        ->setCellValue('B' . $i, $val->numero_factura)
                        ->setCellValue('C' . $i, $val->nit_cedula)
                        ->setCellValue('D' . $i, $val->cliente)
                        ->setCellValue('E' . $i, $val->agenteFactura->nombre_completo)
                        ->setCellValue('F' . $i, $val->tipoFactura->descripcion);
                        if($val->id_pedido == null){
                             $objPHPExcel->setActiveSheetIndex(0)
                             ->setCellValue('G' . $i, 'NO FOUND');
                        }else{
                            $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('G' . $i, $val->pedido->numero_pedido);
                        }   
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('H' . $i, $val->fecha_inicio)
                        ->setCellValue('I' . $i, $val->fecha_vencimiento)
                        ->setCellValue('J' . $i, $val->fecha_enviada_api)
                        ->setCellValue('K' . $i, $val->fecha_recepcion_dian)
                        ->setCellValue('L' . $i, $val->formaPago->concepto)
                        ->setCellValue('M' . $i, $val->plazo_pago)
                        ->setCellValue('N' . $i, $val->valor_bruto)
                        ->setCellValue('O' . $i, $val->descuento)
                        ->setCellValue('P' . $i, $val->subtotal_factura)
                        ->setCellValue('Q' . $i, $val->impuesto)
                        ->setCellValue('R' . $i, $val->valor_retencion)
                        ->setCellValue('S' . $i, $val->valor_reteiva)
                        ->setCellValue('T' . $i, $val->total_factura)
                        ->setCellValue('U' . $i, $val->saldo_factura)
                        ->setCellValue('V' . $i, $val->porcentaje_iva)
                        ->setCellValue('W' . $i, $val->porcentaje_rete_fuente)
                        ->setCellValue('X' . $i, $val->porcentaje_rete_iva)
                        ->setCellValue('Y' . $i, $val->porcentaje_descuento)
                        ->setCellValue('Z' . $i, $val->dias_mora)
                        ->setCellValue('AA' . $i, $val->valor_intereses_mora)
                        ->setCellValue('AB' . $i, $val->iva_intereses_mora)
                        ->setCellValue('AC' . $i, $val->subtotal_interes_masiva)
                        ->setCellValue('AD' . $i, $val->porcentaje_mora)
                        ->setCellValue('AE' . $i, $val->user_name)
                        ->setCellValue('AF' . $i, $val->user_name_editado)
                        ->setCellValue('AG' . $i, $val->fecha_editada)
                        ->setCellValue('AH' . $i, $val->autorizadofactura)
                        ->setCellValue('AI' . $i, $val->observacion);
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
