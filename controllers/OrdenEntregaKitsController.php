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
//model
use app\models\OrdenEntregaKits;
use app\models\OrdenEntregaKitsSearch;
use app\models\UsuarioDetalle;

/**
 * OrdenEntregaKitsController implements the CRUD actions for OrdenEntregaKits model.
 */
class OrdenEntregaKitsController extends Controller
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
     * Lists all OrdenEntregaKits models.
     * @return mixed
     */
      public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',170])->all()){
                $form = new \app\models\FiltroOrdenEntregaKits();
                $presentacion = null;
                $ordenkits = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $nombre_kits = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $presentacion = Html::encode($form->ordenkits);
                        $ordenkits = Html::encode($form->ordenkits);
                        $nombre_kits = Html::encode($form->nombre_kits);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = OrdenEntregaKits::find()
                                    ->andFilterWhere(['=', 'id_entrega_kits', $ordenkits])
                                    ->andFilterWhere(['between', 'fecha_orden', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_presentacion', $presentacion])
                                   ->andFilterWhere(['=', 'id_inventario', $nombre_kits]);
                        $table = $table->orderBy('id_orden_entrega DESC');
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
                            $this->actionExcelConsultaEntregaKits($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = OrdenEntregaKits::find()->orderBy('id_orden_entrega DESC');
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
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelConsultaEntregaKits($tableexcel);
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
     * Displays a single OrdenEntregaKits model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle = \app\models\OrdenEntregaKitsDetalles::find()->where(['=','id_orden_entrega', $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle' => $detalle,
        ]);
    }
    ///IMPORTAR SOLICITUD DE ENTREGA DE KITS
    public function actionImportar_entrega_kits() {
        $solicitud = \app\models\EntregaSolicitudKits::find()->where(['=','producto_armado', 0])->all();
        $table = new OrdenEntregaKits(); 
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON; 
            if (isset($_POST["nueva_solicitud"])) {
                $selected_id = $_POST['nueva_solicitud'];
                $buscar = \app\models\EntregaSolicitudKits::findOne($selected_id);
                if ($buscar !== null) {
                    $table->id_entrega_kits = $buscar->id_entrega_kits;
                    $table->id_presentacion = $buscar->id_presentacion;
                    $table->total_kits = $buscar->cantidad_despachada;
                    $table->total_productos_procesados = $buscar->total_unidades_entregadas;
                    $table->fecha_orden = date('Y-m-d');
                    $table->fecha_hora_registro = date('Y-m-d H:i:s');
                    $table->user_name = Yii::$app->user->identity->username;
                    if($table->save(false)){
                        $buscarDetalles = \app\models\EntregaSolicitudKitsDetalle::find()->where(['=','id_entrega_kits', $buscar->id_entrega_kits])->all();
                        foreach ($buscarDetalles as $val) {
                            $detalle = new \app\models\OrdenEntregaKitsDetalles();
                            $detalle->id_orden_entrega = $table->id_orden_entrega;
                            $detalle->id_detalle_entrega = $val->id_detalle_entrega;
                            $detalle->cantidad_producto = $val->cantidad_despachada;
                            $detalle->save(false);
                        }
                        Yii::$app->getSession()->setFlash('success', 'La orden de entrega de kit ha sido guardada exitosamente.');
                        return $this->redirect(['view','id' => $table->id_orden_entrega,'token' =>0]);
                    }
                   
                }else{
                    Yii::$app->getSession()->setFlash('error', 'Por favor, selecciona una opción de kit.');
                   return $this->redirect(['index']);
                }
                    
            }
        }
         return $this->renderAjax('_form', [
            'solicitud' => $solicitud,
        ]);  
        
    }   

   //AUTORIZAR EL PROCESO
    public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        if ($model->autorizado == 0){  
            $model->autorizado = 1;
            $model->save();
            return $this->redirect(["orden-entrega-kits/view", 'id' => $id, 'token' =>$token]); 
        } else{
            $model->autorizado = 0;
            $model->save();
            return $this->redirect(["orden-entrega-kits/view", 'id' => $id, 'token' =>$token]); 
        }                  
            
        
    }
    
     //CIERRA EL PROCESO DE SOLICTUD
    public function actionCerrar_solicitud($id, $token) {
        //proceso de generar consecutivo
        $model = OrdenEntregaKits::findOne($id); 
        $lista = \app\models\Consecutivos::findOne(34);
        $entrega = \app\models\EntregaSolicitudKits::findOne($model->id_entrega_kits);//verto que actualiza ls entregs
        //actualiza el esatdo de la entrega de kits
        $entrega->producto_armado  = 1;
        $entrega->save();
        //genera consecutivo
        $model->numero_orden = $lista->numero_inicial + 1;
        $model->proceso_cerrado = 1;
        $model->save();
        //actualiza consecutivo
        $lista->numero_inicial = $model->numero_orden;
        $lista->save();
        Yii::$app->getSession()->setFlash('success', 'La orden de ensamble para la entrega de KITS se cerro exitosamente.');
        return  $this->redirect(["orden-entrega-kits/view", 'id' => $id, 'token' =>$token]);  
    }

    public function actionCrear_producto_kits($id, $token) {
        $model = $this->findModel($id);
        if(\app\models\InventarioProductos::find()->where(['=','id_presentacion', $model->id_presentacion])->one()){//produco existente
            
        }else{
            $proveedor = \app\models\Proveedor::find()->where(['=','predeterminado', 1])->one();
            $porcentaje_iva = \app\models\ConfiguracionIva::find()->where(['=','predeterminado', 1])->one();
            $codigo = $this->CrearConsecutivoPresentacion();
            $table = new \app\models\InventarioProductos();
            $table->codigo_producto = $codigo;
            $table->nombre_producto = $model->presentacion->descripcion;
            $table->descripcion_producto = $model->presentacion->descripcion;
            $table->unidades_entradas = $model->total_kits;
            $table->stock_unidades = $model->total_kits;
            $table->id_grupo = $model->presentacion->id_grupo;
            $table->id_producto = $model->presentacion->id_producto;
            $table->porcentaje_iva = $porcentaje_iva->valor_iva;
            $table->fecha_proceso = $model->fecha_orden;
            $table->user_name = Yii::$app->user->identity->username;
            $table->codigo_ean = $table->codigo_producto;
            $table->inventario_inicial = 0;
            if($proveedor){
               $table->id_proveedor = $proveedor->id_proveedor; 
            }
            $table->id_presentacion = $model->id_presentacion;
            $table->activar_producto_venta = 1;
            $table->save(false);
           // actualiza el estaoo
            $model->inventario_enviado = 1;
            $model->id_inventario = $table->id_inventario;
            $model->save();
            ///guarda la bitagora
            $bitacora = new \app\models\BitacoraInventarioProducto();
            $bitacora->id_inventario = $table->id_inventario;
            $bitacora->cantidad = $model->total_kits;
            $bitacora->fecha_proceso = date('Y-m-d');
            $bitacora->fecha_hora_registro = date('Y-m-d H:i:s');
            $bitacora->user_name = Yii::$app->user->identity->username;;
            $bitacora->id_orden_entrega = $id;
            $bitacora->nota = 'Entrada kits al modulo de inventario';
            $bitacora->save(false);
            return $this->redirect(['view','id' => $id, 'token' => $token]);            
        }
    }
    
    //CREAR CONSECUTIVO
    public function CrearConsecutivoPresentacion() {
        $lista = \app\models\Consecutivos::findOne(4);
        $codigo = $lista->numero_inicial + 1;
        $lista->numero_inicial = $codigo;
        $lista->save();
        return $codigo;
    }
    
    /**
     * Finds the OrdenEntregaKits model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrdenEntregaKits the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrdenEntregaKits::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
