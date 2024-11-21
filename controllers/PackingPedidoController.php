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
use app\models\PackingPedido;
use app\models\PackingPedidoSearch;
use app\models\UsuarioDetalle;


/**
 * PackingPedidoController implements the CRUD actions for PackingPedido model.
 */
class PackingPedidoController extends Controller
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
     * Lists all PackingPedido models.
     * @return mixed
     */
     public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',146])->all()){
                $form = new \app\models\FiltroBusquedaPacking();
                $numero_pedido = null;
                $numero_packing = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $cliente =null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $cliente = Html::encode($form->cliente);
                        $numero_pedido = Html::encode($form->numero_pedido);
                        $numero_packing = Html::encode($form->numero_packing);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = PackingPedido::find()
                                    ->andFilterWhere(['between', 'fecha_packing', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'numero_pedido', $numero_pedido])
                                    ->andFilterWhere(['=', 'numero_packing', $numero_packing])
                                    ->andFilterWhere(['like', 'cliente', $cliente]);
                        $table = $table->orderBy('id_packing DESC');
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
                            $check = isset($_REQUEST['id_packing  DESC']);
                            $this->actionExcelPacking($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                }else{
                    
                    $table = PackingPedido::find()->orderBy('id_packing DESC');  
                                         
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
                    if (isset($_POST['excel'])) {
                        $check = isset($_REQUEST['id_packing  DESC']);
                        $this->actionExcelPacking($tableexcel);
                    }  
                }   
                return $this->render('index', [
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
     * Displays a single PackingPedido model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $detalle = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $id])->orderBy('numero_caja ASC')->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detalle' => $detalle,
        ]);
    }

    /**
     * Creates a new PackingPedido model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAutorizado($id)
    {
         $model = $this->findModel($id);
        if($model->autorizado == 0){
            $this->TotalizarUnidades($id);
            $model->autorizado = 1;
            $model->save();
        }else{
            $model->autorizado = 0;
            $model->save();
        }
        return $this->redirect(['packing-pedido/view','id' => $id]);
    }
    
    //proceso que totaliza
    protected function TotalizarUnidades($id) {
        $detalle = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $id])->orderBy('numero_caja ASC')->all();
        $model = $this->findModel($id);
        $contar_unidades = 0; $contar_caja = 0; $auxiliar = 0;
        foreach ($detalle as $key => $detalles) {
            $contar_unidades += $detalles->cantidad_despachada;
            if($auxiliar <> $detalles->numero_caja){
                $contar_caja += 1;
                $auxiliar = $detalles->numero_caja;
            }else{
                $auxiliar = $detalles->numero_caja;
            }    
        }
        $model->total_cajas = $contar_caja;
        $model->total_unidades_packing = $contar_unidades;
        $model->save();
    }
    
    
    //CERRAR EL EL PACKING
    public function actionCerrar_packing_pedido($id) {
         $model = $this->findModel($id);
        $detalle = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $id])->orderBy('numero_caja DESC')->all();
        $sw = 0;
        foreach ($detalle as $key => $detalles) {
            if($detalles->cantidad_despachada <= 0){
                $sw = 1;
            }
        }
        if($sw == 0){    
            //generar consecutivo
             $dato = \app\models\Consecutivos::findOne(24);
             $codigo = $dato->numero_inicial + 1;
             $model->numero_packing = $codigo;
             $model->save();
             $model->cerrar_proceso = 1;
             $model->estado_packing = 1;
             $dato->numero_inicial = $codigo;
             $dato->save();
             return $this->redirect(['packing-pedido/view','id' => $id]);
        }else{
            Yii::$app->getSession()->setFlash('error', 'Hay cajas vacias en el PACKING, favor eliminarlas o llenarlas.');
            return $this->redirect(['packing-pedido/view','id' => $id]);
        }     
         
    }
    
    //CREAR CAJA PARA EL PAKING
    public function actionCrear_caja_packing($id) {
        $model = $this->findModel($id);
        $detalle = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $id])->orderBy('numero_caja DESC')->one();
        $table = new \app\models\PackingPedidoDetalle();
        $table->id_packing = $id;
        if($detalle){
            $table->numero_caja = $detalle->numero_caja + 1;
        }else{
            $table->numero_caja = 1;
        }
        $table->save();
        return $this->redirect(['packing-pedido/view','id' => $id]);
    }

       
     //ALMACENAR PRODUCTOS EN CAJA
    public function actionAlmacenar_producto_caja($id,  $id_caja) {
        $model = new \app\models\ModeloDocumento(); 
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["empacar_producto"])) {
                    if($model->cantidad_despachada > 0){
                        $table = \app\models\PackingPedidoDetalle::findOne($id_caja) ;
                        
                        $table->cantidad_despachada = $model->cantidad_despachada;
                        $table->save(false);
                        return $this->redirect(['packing-pedido/view', 'id' => $id]);
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'Este campo no puede ser vacion, debe de ingreso al menos 1 unidad.');
                        return $this->redirect(['packing-pedido/view', 'id' => $id]);
                    }    
                }
            }else{
              $model->getErrors();  
            }
         }
         $table = \app\models\PackingPedidoDetalle::findOne($id_caja) ;
         if (Yii::$app->request->get()) {
            $model->cantidad_despachada = $table->cantidad_despachada;
         }    
         return $this->renderAjax('/almacenamiento-producto/form_almacenar_caja', [
                    'model' => $model,
                    
                ]);
    }
    
    /**
     * Deletes an existing PackingPedido model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionEliminar_caja($id_detalle, $id)
    {
        try {
            $dato = \app\models\PackingPedidoDetalle::findOne($id_detalle);
            $dato->delete();
            $this->TotalizarUnidades($id);
            Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
            $this->redirect(["packing-pedido/view",'id' => $id]);
        } catch (IntegrityException $e) {
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
            $this->redirect(["packing-pedido/view",'id' => $id]);
        } catch (\Exception $e) {            
            Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro, tiene registros asociados en otros procesos');
            $this->redirect(["packing-pedido/view",'id' => $id]);
        }
    }

    /**
     * Finds the PackingPedido model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PackingPedido the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PackingPedido::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
