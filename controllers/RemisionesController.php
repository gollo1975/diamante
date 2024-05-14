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
                        $table = Remisiones::find()
                                    ->andFilterWhere(['=', 'numero_remision', $numero])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_cliente', $cliente])
                                    ->andFilterWhere(['=', 'id_punto', $punto_venta]);
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
                    $table = Remisiones::find() ->orderBy('id_remision DESC');
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
        $producto = null;
        $factura = Remisiones::findOne($id);
        $punto_venta = \app\models\PuntoVenta::findOne($accesoToken);
        $inventario = \app\models\InventarioPuntoVenta::find()->where(['>','stock_inventario', 0])
                                                          ->andWhere(['=','venta_publico', 1])->andWhere(['=','id_punto', $accesoToken])
                                                          ->orderBy('nombre_producto ASC')->all();
        $detalle_remision = \app\models\RemisionDetalles::find()->where(['=','id_remision', $id])->all();
         if ($form->load(Yii::$app->request->get())) {
              $codigo_producto = Html::encode($form->codigo_producto);
             $producto = Html::encode($form->producto);
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
                        $table->producto = $producto->nombre_producto;
                        if($factura->id_punto  <> 1 ){ ///PROCESO AL DEPTAL
                            $table->cantidad = 1;
                            $table->valor_unitario = $producto->precio_deptal;    
                            $subtotal = round($table->valor_unitario  * $table->cantidad);
                            if($producto->aplica_descuento_punto == 1){ //aplicar descuento comercial para punto de venta
                                $fecha_actual = date('Y-m-d');
                                $regla = \app\models\DescuentoPuntoVenta::find()->where(['=','id_inventario', $producto->id_inventario])->one();
                                if($regla->tipo_descuento == 1 && $regla->fecha_inicio <= $fecha_actual && $regla->fecha_final >= $fecha_actual){
                                    $descuento = round(($subtotal * $regla->nuevo_valor)/100);
                                    $table->total_linea = round($subtotal - $descuento);
                                    $table->subtotal = round($subtotal);
                                    $table->porcentaje_descuento = $regla->nuevo_valor;
                                    $table->valor_descuento = $descuento;
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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_remision]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Remisiones model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
}
