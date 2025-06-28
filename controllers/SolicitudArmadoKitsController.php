<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\Html;
//models
use app\models\SolicitudArmadoKits;
use app\models\UsuarioDetalle;
/**
 * SolicitudArmadoKitsController implements the CRUD actions for SolicitudArmadoKits model.
 */
class SolicitudArmadoKitsController extends Controller
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
     * Lists all SolicitudArmadoKits models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',167])->all()){
                $form = new \app\models\FiltroBusquedaKits();
                $presentacion = null;
                $solicitud = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $presentacion = Html::encode($form->presentacion);
                        $solicitud = Html::encode($form->solicitud);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = SolicitudArmadoKits::find()
                                    ->andFilterWhere(['=', 'id_solicitud', $solicitud])
                                    ->andFilterWhere(['between', 'fecha_solicitud', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_presentacion', $presentacion]);
                        $table = $table->orderBy('id_solicitud_armado DESC');
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
                    $table = SolicitudArmadoKits::find()->orderBy('id_solicitud_armado DESC');
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
     * Displays a single SolicitudArmadoKits model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle = \app\models\SolicitudArmadoKitsDetalle::find()->where(['=','id_solicitud_armado', $id])->all();
       
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle' => $detalle,
        ]);
    }
    
    //PROCESO QUE SUMA LAS UNIDADES
    protected function SumarCantidades($id) {
        $model = SolicitudArmadoKits::findOne($id);
        $table = \app\models\SolicitudArmadoKitsDetalle::find()->where(['=','id_solicitud_armado', $id])->all();
        $total = 0;
        foreach ($table as $val) {
            $total += $val->cantidad_solicitada;
        }
        $model->total_unidades = $total;
        $model->save();
    }
    /**
     * Creates a new SolicitudArmadoKits model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SolicitudArmadoKits();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name = Yii::$app->user->identity->username;
            $model->save();
            $concepto = \app\models\PresentacionKitsDetalle::find()->where(['=','id_presentacion', $model->id_presentacion])->all();
            if($concepto){
                foreach ($concepto as $val) {
                    $table = new \app\models\SolicitudArmadoKitsDetalle();
                    $table->id_solicitud_armado = $model->id_solicitud_armado;
                    $table->id_inventario = $val->id_inventario;
                    $table->cantidad_solicitada = $model->cantidad_solicitada;
                    $table->saldo_cantidad_solicitada = $model->cantidad_solicitada;
                    $table->save(false);
                    $id = $model->id_solicitud_armado;
                }
                $this->SumarCantidades($id);
               return $this->redirect(['view', 'id' => $model->id_solicitud_armado,'token' => 0]); 
            }else{
                Yii::$app->getSession()->setFlash('error', 'La presentacion ' .$model->presentacion->descripcion . ', NO tiene productos relaccionados. Valide la informacion.');
                return $this->redirect(['index']);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SolicitudArmadoKits model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $concepto = \app\models\PresentacionKitsDetalle::find()->where(['=','id_presentacion', $model->id_presentacion])->all();
            if($concepto){
                foreach ($concepto as $val) {
                    $table = new \app\models\SolicitudArmadoKitsDetalle();
                    $table->id_solicitud_armado = $model->id_solicitud_armado;
                    $table->id_inventario = $val->id_inventario;
                    $table->cantidad_solicitada = $model->cantidad_solicitada;
                    $table->saldo_cantidad_solicitada = $model->cantidad_solicitada;
                    $table->save(false);
                }
                $this->SumarCantidades($id);
                return $this->redirect(['view', 'id' => $id, 'token' => 0]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SolicitudArmadoKits model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    //ELIMINAR DETALLE DEL PRECIO DE VENTA
     public function actionEliminar_detalle($id,$id_detalle, $token)
    {                                
        $dato = \app\models\SolicitudArmadoKitsDetalle::findOne($id_detalle);
        $dato->delete();
        $this->SumarCantidades($id);
        return $this->redirect(["view",'id' => $id, 'token' => $token]);        
    }
    
    //REGENEAR ARCHIVO
    public function actionRegenerar_formula($id, $token, $id_presentacion) {
        $model = SolicitudArmadoKits::findOne($id);
        $presentacion = \app\models\PresentacionKitsDetalle::find()->where(['=','id_presentacion', $id_presentacion])->all();
        if (count($presentacion) > 0){
            foreach ($presentacion as $val) {
                $buscar = \app\models\SolicitudArmadoKitsDetalle::find()->where(['=','id_inventario', $val->id_inventario])->andWhere(['=','id_solicitud_armado', $id])->one();
                if(!$buscar){
                    $table = new \app\models\SolicitudArmadoKitsDetalle();
                    $table->cantidad_solicitada = $model->cantidad_solicitada;
                    $table->id_solicitud_armado = $id;
                    $table->id_inventario = $val->id_inventario;
                    $table->save(false);
                }
            }
            $this->SumarCantidades($id);
            return $this->redirect(['view','id' => $id, 'token' => $token]);
        }else{
            Yii::$app->getSession()->setFlash('error', 'La presentacion ' .$model->presentacion->descripcion . ', NO tiene productos relaccionados. Valide la informacion.');
            return $this->redirect(['view','id' => $id, 'token' => $token]);
        }
            
    }
    
    //AUTORIZAR EL PROCESO
    public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        if(\app\models\SolicitudArmadoKitsDetalle::find()->where(['=','id_solicitud_armado', $id])->one()){
            if ($model->autorizado == 0){  
                $model->autorizado = 1;
                $model->save();
                return $this->redirect(["solicitud-armado-kits/view", 'id' => $id, 'token' =>$token]); 
            } else{
                $model->autorizado = 0;
                $model->save();
                return $this->redirect(["solicitud-armado-kits/view", 'id' => $id, 'token' =>$token]); 
            }                  
            
        }else{
            Yii::$app->getSession()->setFlash('error', 'Debe descargar la presentacion del producto para formar el KITS.'); 
            return $this->redirect(["solicitud-armado-kits/view", 'id' => $id, 'token' => $token]); 
        }    
    }
    
    //
     //CIERRA EL PROCESO DE SOLICTUD
    public function actionCerrar_solicitud($id, $token) {
        //proceso de generar consecutivo
        $lista = \app\models\Consecutivos::findOne(32);
        $model = SolicitudArmadoKits::findOne($id); 
        //genera consecutivo
        $model->numero_solicitud = $lista->numero_inicial + 1;
        $model->proceso_cerrado = 1;
        $model->save();
        //actualiza consecutivo
        $lista->numero_inicial = $model->numero_solicitud;
        $lista->save();
        return  $this->redirect(["solicitud-armado-kits/view", 'id' => $id, 'token' =>$token]);  
    }

    /**
     * Finds the SolicitudArmadoKits model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SolicitudArmadoKits the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SolicitudArmadoKits::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
