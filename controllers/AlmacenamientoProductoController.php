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
use app\models\AlmacenamientoProducto;
use app\models\AlmacenamientoProductoSearch;
use app\models\UsuarioDetalle;
use app\models\OrdenProduccion;
use app\models\AlmacenamientoProductoDetalles;


/**
 * AlmacenamientoProductoController implements the CRUD actions for AlmacenamientoProducto model.
 */
class AlmacenamientoProductoController extends Controller
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
     * Lists all AlmacenamientoProducto models.
     * @return mixed
     */
     public function actionCargar_orden_produccion() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',72])->all()){
                $form = new \app\models\FiltroBusquedaAlmacenamiento();
                $orden = null;
                $lote = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $orden = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $orden = Html::encode($form->orden);
                        $lote = Html::encode($form->lote);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = OrdenProduccion::find()
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'numero_lote', $lote])
                                    ->andFilterWhere(['=', 'numero_orden', $orden])
                                    ->andWhere(['=', 'producto_almacenado', 0])
                                    ->andWhere(['=', 'producto_aprobado', 1]);
                        $table = $table->orderBy('id_orden_produccion DESC');
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
                } else {
                    $table = OrdenProduccion::find()->Where(['=', 'producto_almacenado', 0])
                                                    ->andWhere(['=', 'producto_aprobado', 1])
                                                    ->orderBy('id_orden_produccion DESC');
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
                }
                return $this->render('cargar_ordenes', [
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
     * Displays a single AlmacenamientoProducto model.
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
    //VISTA DE ALMACENAMIENTO
    public function actionView_almacenamiento($id_orden)
    {
        $detalle = AlmacenamientoProducto::find()->where(['=','id_orden_produccion', $id_orden])->all();
        $conAlmacenado = AlmacenamientoProductoDetalles::find()->where(['=','id_orden_produccion', $id_orden])->all();
        $model = OrdenProduccion::findOne($id_orden);
        return $this->render('view_almacenamiento', [
            'detalle' => $detalle,
            'id_orden' => $id_orden,
            'model' => $model,
            'conAlmacenado' => $conAlmacenado,
        ]);
    }
    //CREAR DOCUMENTO
    public function actionSubir_documento($id, $id_orden) {
        $model = new \app\models\ModeloDocumento(); 
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (isset($_POST["nuevo_documento"])) {
                   $table = AlmacenamientoProducto::findOne($id) ;
                   $table->id_documento = $model->documento;
                   $table->save();
                   return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden]);
                }
            }else{
              $model->getErrors();  
            }
         }
          return $this->renderAjax('form_subir_almacenamiento', [
                    'model' => $model,
                    'id' => $id,
                    'id_orden' => $id_orden, 
        ]);
    }
    //ENVIAR UNIDADES AL RACK
    public function actionCrear_almacenamiento($id_orden, $id) {
        $model = new \app\models\ModeloEnviarUnidadesRack();
        $racks = \app\models\TipoRack::find()->where(['=','estado', 0])->all();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if(isset($_POST["crear_almacenamiento"])){
                    $total = 0; $cant = 0; $id_rack = 0;
                    $conProducto = AlmacenamientoProducto::findOne($id);
                    if($model->cantidad <= $conProducto->unidades_producidas){
                        if($conProducto->unidades_almacenadas == 0){
                             $table = new AlmacenamientoProductoDetalles();
                            $table->id_almacenamiento = $id;
                            $table->id_orden_produccion = $id_orden;
                            $table->id_rack = $model->rack;
                            $table->id_piso = $model->piso;
                            $table->id_posicion = $model->posicion; 
                            $table->cantidad = $model->cantidad;
                            $table->codigo_producto = $conProducto->codigo_producto;
                            $table->producto = $conProducto->nombre_producto;
                            $table->numero_lote = $conProducto->numero_lote;
                            $table->save(false);
                            $cant = $model->cantidad;
                            $id_rack = $model->rack;
                            $this->ActualizarUnidadesAlmacenadas($id, $id_orden);
                            $this->SumarUnidadesRack($id_rack, $cant);
                            return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden]); 
                        }else{
                            $total = $conProducto->unidades_faltantes;
                            if($model->cantidad <= $total){
                                $table = new AlmacenamientoProductoDetalles();
                                $table->id_almacenamiento = $id;
                                $table->id_orden_produccion = $id_orden;
                                $table->id_rack = $model->rack;
                                $table->id_piso = $model->piso;
                                $table->id_posicion = $model->posicion; 
                                $table->cantidad = $model->cantidad;
                                $table->codigo_producto = $conProducto->codigo_producto;
                                $table->producto = $conProducto->nombre_producto;
                                $table->numero_lote = $conProducto->numero_lote;
                                $table->save(false);
                                $cant = $model->cantidad;
                                $id_rack = $model->rack;
                                $this->ActualizarUnidadesAlmacenadas($id, $id_orden);
                                 $this->SumarUnidadesRack($id_rack, $cant);
                                return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden]); 
                            }else{
                                Yii::$app->getSession()->setFlash('info', 'las unidades que se van a ALMACENAR son mayores con las unidades PRODUCIDAS.!');
                                return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden]); 
                            }
                        }    
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'Las unidades ENVIADAS son mayores que las unidades RESTANTES.!');
                        return $this->redirect(['view_almacenamiento', 'id_orden' => $id_orden]); 
                    }    
                }
            }else{
                $model->getErrors();
            }
        }
        return $this->renderAjax('_enviar_unidades_almacenamiento', [
                    'model' => $model,
                    'id' => $id,
                    'id_orden' => $id_orden, 
                    'tipo_rack' => ArrayHelper::map($racks, "id_rack", "tiporack"),
        ]);
    }
    
    //PROCESO QUE ACUTLIZA UNIDADES
    protected function ActualizarUnidadesAlmacenadas($id, $id_orden) {
        $almacenamiento = AlmacenamientoProducto::findOne($id);
        $detalle = AlmacenamientoProductoDetalles::find()->where(['=','id_almacenamiento', $id])->all();
        $suma = 0;
        foreach ($detalle as $detalles):
            $suma += $detalles->cantidad;    
        endforeach;
        $almacenamiento->unidades_almacenadas = $suma;
        $almacenamiento->unidades_faltantes = $almacenamiento->unidades_producidas - $suma;
        $almacenamiento->save();
    }
    //RELACION DE PISOS CON RACKS
    
     public function actionPiso_rack($id){
        $rows = \app\models\TipoRack::find()->where(['=','id_piso', $id])
                                               ->andWhere(['=','estado', 0])->all();

        echo "<option value='' required>Seleccione el rack...</option>";
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id_rack' required>$row->numero_rack - $row->descripcion</option>";
            }
        }
    }
    ///PROCESO QUE SUMA  LAS UNIDADES EN CADA RACK
    protected function SumarUnidadesRack($id_rack, $cant) {
        $rack = \app\models\TipoRack::findOne($id_rack);
        $rack->capacidad_actual =  $rack->capacidad_actual +  $cant;
        $rack->save(false);
    }
    
    /**
     * Creates a new AlmacenamientoProducto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AlmacenamientoProducto();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_almacenamiento]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    ///PROCESO QUE CARGA EL PROCESO PARA ALMACENAR.
    public function actionEnviar_lote_almacenar($id_orden) {
        $lotes = \app\models\OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $id_orden])->all();
        $con = 0;
        foreach ($lotes as $detalle):
            $table = new AlmacenamientoProducto();
            $table->id_orden_produccion = $id_orden;
            $table->codigo_producto = $detalle->codigo_producto;
            $table->nombre_producto = $detalle->descripcion;
            $table->unidades_producidas = $detalle->cantidad;
            $table->fecha_almacenamiento = date('Y-m-d');
            $table->numero_lote = $detalle->numero_lote;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save(false);
            $con += 1;
        endforeach;
        Yii::$app->getSession()->setFlash('success', 'Se exporto (' . $con.') lotes de la orden de produccion No ('. $id_orden.') al modulo de almacenamiento con éxito.');
        return $this->redirect(['cargar_orden_produccion']);
    }
    
    /**
     * Updates an existing AlmacenamientoProducto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_almacenamiento]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AlmacenamientoProducto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    //ELIMINAR ALMACENAMIENTO
      public function actionEliminar_detalle_almacenamiento($id_orden, $detalle)
    {                                
        $conBuscar = AlmacenamientoProducto::findOne($detalle);
        $conBuscar->delete();
        return $this->redirect(["view_almacenamiento", 'id_orden' => $id_orden]);        
    }
    
    //ELIMINAR ALMACENAMIENTO RACKS
      public function actionEliminar_items_rack($id_orden, $id_detalle)
    {                                
        $conBuscar = AlmacenamientoProductoDetalles::findOne($id_detalle);
        $conBuscar->delete();
        $codigo = $conBuscar->id_almacenamiento;
        $this->ActualizarUnidadesEliminadas($id_detalle, $id_orden, $codigo);
        return $this->redirect(["view_almacenamiento", 'id_orden' => $id_orden]);        
    }
    
    //PROCESO QUE ACTUALIZA UNIDADES ALMACENADAS CUANDO SE ELIMINA
    protected function ActualizarUnidadesEliminadas($id_detalle, $id_orden, $codigo) {
        $almacenamiento = AlmacenamientoProducto::findOne($codigo);
        $detalle = AlmacenamientoProductoDetalles::find()->where(['=','id_almacenamiento', $codigo])->all();
        $suma = 0; $total = 0;
        foreach ($detalle as $detalles):
            $suma += $detalles->cantidad;    
        endforeach;
        $total = $almacenamiento->unidades_producidas;
        $almacenamiento->unidades_faltantes = $total - $suma;
        $almacenamiento->unidades_almacenadas = $suma;
        $almacenamiento->save();
    }

    /**
     * Finds the AlmacenamientoProducto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AlmacenamientoProducto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AlmacenamientoProducto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
