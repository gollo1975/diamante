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
                $pages = null;
                $accesoToken = Yii::$app->user->identity->id_punto;
               $rolUsuario = Yii::$app->user->identity->role;
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
                        if($rolUsuario ==  2 || $rolUsuario  == 1 ){
                            var_dump($punto_venta);
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
                            $this->actionExcelFacturaVenta($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                }else{
                    if($rolUsuario <> 3){
                        $table = FacturaVentaPunto::find()->orderBy('id_factura DESC');
                    }else{
                        $table = FacturaVentaPunto::find()->Where(['=','id_punto', $punto_venta])->orderBy('id_factura DESC');
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
                            $this->actionExcelFacturaVenta($tableexcel);
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
        $factura = FacturaVentaPunto::findOne($id_factura_punto);
        $inventario = \app\models\InventarioPuntoVenta::find()->where(['>','stock_inventario', 0])
                                                          ->andWhere(['=','venta_publico', 1])->andWhere(['=','id_punto', $accesoToken])
                                                          ->orderBy('nombre_producto ASC')->all();
        $detalle_factura = FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $id_factura_punto])->all();
        if ($form->load(Yii::$app->request->get())) {
            $codigo_producto = Html::encode($form->codigo_producto);
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
                        if($factura->id_tipo_venta == 3){
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
                        if($factura->id_tipo_venta == 2){
                            Yii::$app->getSession()->setFlash('warning', 'Este producto ya se encuentra registrado en esta factura, favor subir las unidades faltantes por  la opcion de MAS');
                            return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' => $accesoToken]);
                        }else{
                             
                            //si existe el producto
                            $valor_unitario = 0;
                            $detalle = \app\models\FacturaVentaPuntoDetalle::findOne($conDato->id_detalle);
                            $producto = \app\models\InventarioPuntoVenta::find()->where(['=','codigo_producto', $codigo_producto])->andWhere(['=','id_punto', $accesoToken])->one();
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
                           return $this->redirect(['view','id_factura_punto' =>$id_factura_punto, 'accesoToken' => $accesoToken]);
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
                $producto = \app\models\InventarioPuntoVenta::findOne($table->id_inventario);
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
        $detalle->delete();
        $id =  $id_factura_punto;
        $this->ActualizarSaldosTotales($id);
        $this->ActualizarConceptosTributarios($id);
        $this->redirect(["view",'id_factura_punto' => $id_factura_punto, 'accesoToken' => $accesoToken]);        
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
        if(count($detalle) > 0 && $factura->valor_bruto > 0){
            if($factura->autorizado == 0){
                $factura->autorizado = 1;
            }else{
                $factura->autorizado = 0;
            }
            $factura->save();
            $this->redirect(["view", 'id_factura_punto' => $id_factura_punto,'accesoToken' => $accesoToken]);  
        }else{
            Yii::$app->getSession()->setFlash('warning', 'No se puede AUTORIZAR la factura porque no tiene productos relacionados para la generar la venta o NO le ha asignado cantidades.'); 
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
    
    public function actionCrear_talla_color($id_factura_punto, $accesoToken, $id_detalle) {
       
        $form = new \app\models\ModeloTallasColores();
        $id_talla = null;
        $id_color = null;
        $conColores = null;
        $detalle = FacturaVentaPuntoDetalle::findOne($id_detalle);
        $conTallas = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $detalle->id_inventario])->andWhere(['>','stock_punto', 0])->orderBy('id_talla ASC')->all();
        if ($form->load(Yii::$app->request->get())) {
            $id_talla = Html::encode($form->id_talla);
            $id_color = Html::encode($form->id_color);
            if($id_talla > 0){
                if($id_color <> 0){
                    $conColores = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $detalle->id_inventario])->andWhere(['=','id_talla', $id_talla ])
                                                                       ->orderBy('id_color ASC')->all();
                }
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar la talla de la lista.');
                return $this->redirect(['crear_talla_color','id_factura_punto' =>$id_factura_punto, 'accesoToken' =>$accesoToken, 'conTallas' => $conTallas, 'id_detalle' => $id_detalle]);
            }
        }
        if (isset($_POST["enviarcolores"])) {
            if(isset($_POST["nuevo_color"])){
                $intIndice = 0;
                foreach ($_POST["nuevo_color"] as $intCodigo) {
                    if($_POST["cantidad_venta"][$intIndice] > 0){
                        $table = new \app\models\FacturaPuntoDetalleColoresTalla();
                        $table->id_detalle =  $id_detalle;
                        $table->id_factura = $id_factura_punto;
                        $table->id_inventario = $detalle->id_inventario;
                        $table->id_color = $intCodigo;
                        $table->id_talla = $id_talla;
                        $table->cantidad_venta = $_POST["cantidad_venta"][$intIndice];
                        $table->save(false);
                        $intIndice++; 
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'Debe digitar la cantidad de unidades a vender.'); 
                    }
                }
                 return $this->redirect(['crear_talla_color','id_factura_punto' =>$id_factura_punto, 'accesoToken' =>$accesoToken, 'conTallas' => $conTallas, 'id_detalle' => $id_detalle]);
            }else{
               Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar un registro para procesar la informacion.'); 
            }
        }
        return $this->render('factura_detalle_tallas_colores', [
            'id_factura_punto' => $id_factura_punto,
            'accesoToken' => $accesoToken,
            'form' => $form, 
            'conColores' => $conColores,
            'conTallas' => ArrayHelper::map($conTallas, 'id_talla', 'nombreTalla'),
            'id_detalle' => $id_detalle,
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
}
