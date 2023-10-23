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
//MODELS
use app\models\NotaCredito;
use app\models\NotaCreditoSearch;
use app\models\FacturaVenta;
use app\models\UsuarioDetalle;
use app\models\Clientes;
use app\models\NotaCreditoDetalle;
/**


/**
 * NotaCreditoController implements the CRUD actions for NotaCredito model.
 */
class NotaCreditoController extends Controller
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
     * Lists all NotaCredito models.
     * @return mixed
     */
     public function actionListado_factura() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',57])->all()){
                $form = new \app\models\FiltroBusquedaPedidos();
                $documento = null; $fecha_inicio = null;
                $cliente = null; $fecha_corte = null;
                $vendedores = null; $numero_factura = null;
                $model = null;
                $pages = null;
               if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $documento = Html::encode($form->documento);
                        $cliente = Html::encode($form->cliente);
                        $vendedores = Html::encode($form->vendedor);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $numero_factura = Html::encode($form->numero_factura);
                        $table = FacturaVenta::find()
                            ->andFilterWhere(['=', 'nit_cedula', $documento])
                            ->andFilterWhere(['=', 'id_cliente', $cliente])
                            ->andFilterWhere(['between','fecha_inicio', $fecha_inicio, $fecha_corte])
                            ->andFilterWhere(['=','numero_factura', $numero_factura])
                            ->andFilterWhere(['=','id_agente', $vendedores])
                            ->andWhere(['>','saldo_factura', 0])
                            ->andWhere(['<>','estado_factura', 3]);
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
                    } else {
                        $form->getErrors();
                    }
                }
                return $this->render('listado_factura', [
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
     * Displays a single NotaCredito model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $detalle_nota = NotaCreditoDetalle::find()->where(['=','id_nota', $id])->limit (2)->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detalle_nota' => $detalle_nota,
        ]);
    }

    /**
     * Creates a new NotaCredito model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCrear_nota_credito($id_factura) {
        if($nota = NotaCredito::find()->where(['=','id_factura', $id_factura])->orderBy('id_factura DESC')->one()){
            Yii::$app->getSession()->setFlash('warning', 'Esta factura esta en proceso de nota credito. Consulte con el administrador.'); 
            return $this->redirect(["nota-credito/listado_factura"]);
        }else{
           $factura = FacturaVenta::find()->where(['=','id_factura', $id_factura])->one();
            $tipo_factura = \app\models\TipoFacturaVenta::findOne(3);
            $empresa = \app\models\MatriculaEmpresa::findOne(1);
            $fecha_actual = date('Y-m-d');
            $table = new NotaCredito();
            $table->id_cliente = $factura->id_cliente;
            $table->nit_cedula = $factura->nit_cedula;
            $table->cliente = $factura->cliente;
            $table->id_factura = $id_factura;
            $table->id_tipo_factura = $tipo_factura->id_tipo_factura;
            $table->fecha_factura = $factura->fecha_inicio;
            $table->fecha_nota_credito = $fecha_actual;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save();
            $model = NotaCredito::find()->orderBy('id_nota DESC')->one();
            $id = $model->id_nota;
            return $this->redirect(["nota-credito/view", 'id' => $id]);
        }
    }
    
    //proceso que trae los detalle de la factura
    public function actionListar_detalle_factura($id, $id_factura) {
        $detalle_factura = \app\models\FacturaVentaDetalle::find()->where(['=','id_factura', $id_factura])->all();
        $form = new \app\models\FormModeloBuscar();
        $q = null;
        $nombre = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $q = Html::encode($form->q);                                
                $nombre = Html::encode($form->nombre);       
                if($q == ''){
                    $conSql = \app\models\FacturaVentaDetalle::find()
                            ->Where(['like','producto', $nombre])
                            ->andwhere(['=','id_factura', $id_factura]);
                }else{
                    $conSql = \app\models\FacturaVentaDetalle::find()
                        ->where(['=','codigo_producto', $q])
                        ->andwhere(['=','id_factura', $id_factura]);
                }    
                $conSql = $conSql->orderBy('producto ASC');  
                $count = clone $conSql;
                $to = $count->count();
                $pages = new Pagination([
                    'pageSize' => 10,
                    'totalCount' => $count->count()
                ]);
                $variable = $conSql
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();         
            } else {
                $form->getErrors();
            }                    
        }else{
            $detalle_factura = \app\models\FacturaVentaDetalle::find()->where(['=','id_factura', $id_factura]);
            $tableexcel = $detalle_factura->all();
            $count = clone $detalle_factura;
            $pages = new Pagination([
                        'pageSize' => 6,
                        'totalCount' => $count->count(),
            ]);
             $variable = $detalle_factura
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        }
         if (isset($_POST["devolucion_productos"])) {
            if(isset($_POST["devolucion_factura_detalle"])){
                 $intIndice = 0;
                foreach ($_POST["devolucion_factura_detalle"] as $intCodigo):
                    //consulta para no duplicar
                    $factura_detalle = \app\models\FacturaVentaDetalle::findOne($intCodigo);
                    $nota = NotaCreditoDetalle::find()->where(['=','id_nota', $id])
                                                                   ->andWhere(['=','id_inventario', $factura_detalle->id_inventario])->one();
                    if(!$nota){
                        if($_POST["cantidad_devolver"]["$intIndice"] > 0){
                                $valor = 0;
                                $valor = $factura_detalle->cantidad;
                                if($_POST["cantidad_devolver"]["$intIndice"] <= $valor ){
                                    $table = new NotaCreditoDetalle();
                                    $table->id_nota = $id;
                                    $table->id_inventario = $factura_detalle->id_inventario;
                                    $table->codigo_producto = $factura_detalle->codigo_producto;
                                    $table->producto = $factura_detalle->producto;
                                    $table->cantidad = $_POST["cantidad_devolver"]["$intIndice"];
                                    $table->valor_unitario = $factura_detalle->valor_unitario;  
                                    $table->subtotal = $factura_detalle->subtotal;
                                    $table->impuesto = $factura_detalle->impuesto;
                                    $table->total_linea = $factura_detalle->total_linea;
                                    $table->save(false);
                                    $datos = $intCodigo;
                                    $token = 0;
                                //    $this->ActualizarInventarioPrecio($datos, $id, $token);
                                  //  $this->TotalPresupuestoPedido($id, $sw);
                                }else{
                                    Yii::$app->getSession()->setFlash('error', 'Las unidades a devolver son  mayores que las cantidad vendidas. Favor validar las cantidades.');
                                    return $this->redirect(['listar_detalle_factura','id' => $id, 'id_factura' => $id_factura]);
                                }    
                        }
                    }    
                    $intIndice ++;
                endforeach;
                return $this->redirect(['view','id' => $id]);
            }
        }
        return $this->render('listado_detalle_factura', [ 
            'id' => $id,
            'id_factura' => $id_factura,
            'variable' => $variable,
            'form' => $form,
            'pagination' => $pages,
            ]);
    }

    /**
     * Deletes an existing NotaCredito model.
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
     * Finds the NotaCredito model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NotaCredito the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NotaCredito::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
