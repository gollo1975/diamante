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
    public function actionIndex()
    {
        $searchModel = new FacturaVentaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
    /**
     * Displays a single FacturaVenta model.
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

    /**
     * Creates a new FacturaVenta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FacturaVenta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_factura]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FacturaVenta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_factura]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FacturaVenta model.
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
    //CREAR FACTURA DESDE PEDIDO
    public function actionImportar_pedido_factura($id_pedido) {
        $pedido = Pedidos::find()->where(['=','id_pedido', $id_pedido])->one();
        $tipo_factura = \app\models\TipoFacturaVenta::findOne(1);
        $resolucion = \app\models\ResolucionDian::find()->where(['=','estado_resolucion', 0])->one();
        $iva = \app\models\ConfiguracionIva::findOne(1);
        $empresa = \app\models\MatriculaEmpresa::findOne(1);
        $fecha_actual = date('Y-m-d');
        $table = new FacturaVenta();
        $table->id_pedido = $id_pedido;
        $table->id_cliente = $pedido->id_cliente;
        $table->id_tipo_factura = $tipo_factura->id_tipo_factura;
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
        $this->CrearDetalleFactura($id_pedido, $model);
        
    }
    protected function CrearDetalleFactura($id_pedido, $model) {
        $detalle_pedido = \app\models\PedidoDetalles::find(['=','id_pedido', $id_pedido])->all();
        foreach ($detalle_pedido as $detalle):
            $base = new FacturaVentaDetalle();
            $base->id_factura = $model->id_factura;
            $base->id_inventario = $detalle->id_inventario;
            $base->codigo_producto = $detalle->inventario->codigo_producto;
            $base->producto = $detalle->inventario->nombre_producto;
            $base->cantidad = $detalle->cantidad;
            $base->valor_unitario = $detalle->valor_unitario;
            $base->subtotal = $detalle->subtotal;
            $base->impuesto = $detalle->impuesto;
            $base->total_linea = $detalle->total_linea;
            $detalle->save();
        endforeach;
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
}
