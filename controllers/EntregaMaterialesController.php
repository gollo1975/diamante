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
use app\models\EntregaMateriales;
use app\models\UsuarioDetalle;
use app\models\EntregaMaterialesDetalle;
use app\models\SolicitudMateriales;



/**
 * EntregaMaterialesController implements the CRUD actions for EntregaMateriales model.
 */
class EntregaMaterialesController extends Controller
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
     * Lists all EntregaMateriales models.
     * @return mixed
     */
     public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',99])->all()){
                $form = new \app\models\FiltroBusquedaSolicitudMateriales();
                $numero_solicitud = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $numero_entrega = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_solicitud = Html::encode($form->numero_solicitud);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $numero_entrega = Html::encode($form->numero_entrega);
                        $table = EntregaMateriales::find()
                                    ->andFilterWhere(['=', 'numero_entrega', $numero_entrega])
                                    ->andFilterWhere(['between', 'fecha_despacho', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'codigo', $numero_solicitud]);
                        $table = $table->orderBy('id_entrega DESC');
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
                            $check = isset($_REQUEST['id_entrega  DESC']);
                            $this->actionExcelConsultaEntrega($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = EntregaMateriales::find()
                            ->orderBy('id_entrega DESC');
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
                        $this->actionExcelConsultaEntrega($tableexcel);
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
     * Displays a single EntregaMateriales model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id,$token)
    {
        $detalle_solicitud = EntregaMaterialesDetalle::find()->where(['=','id_entrega', $id])->all();
        $validar_inventario = EntregaMaterialesDetalle::find()->where(['=','id_entrega', $id])->andWhere(['=','validar_linea_materia_prima', 0])->all();
        if (Yii::$app->request->post()) {
            if(isset($_POST["actualizar_cantidad"])){
                if(isset($_POST["listado_materiales"])){
                    $intIndice = 0; $cantidad = 0;
                    foreach ($_POST["listado_materiales"] as $intCodigo):
                        $table = \app\models\EntregaMaterialesDetalle::findOne($intCodigo);
                        $cantidad = $_POST["unidades_despachadas"][$intIndice];
                        if($cantidad <= $table->unidades_solicitadas){
                            $table->unidades_despachadas = $_POST["unidades_despachadas"][$intIndice];
                            $materia = \app\models\MateriaPrimas::findOne($table->id_materia_prima);
                            $table->save(false);
                            $intIndice++;
                        }else{
                            Yii::$app->getSession()->setFlash('warning', 'La cantidad a entregar NO puede ser mayor que la cantidad solicitada. Valide la informacion!');
                        }    
                    endforeach;
                   return $this->redirect(['view','id' =>$id, 'token' => $token]);
                }
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_solicitud' => $detalle_solicitud,
            'validar_inventario' => $validar_inventario,
        ]);
    }
    
  

      //SE AUTORIZA O DESAUTORIZA EL PRODUCTO
    public function actionAutorizado($id, $token) {
        $model = $this->findModel($id);
        $entrega = \app\models\EntregaMaterialesDetalle::find()->where(['=','id_entrega', $model->id_entrega])->all();
        foreach ($entrega as $valor) {
            if ($valor->unidades_despachadas <= 0) {
               Yii::$app->getSession()->setFlash('error', 'El campo de UNIDADES DESPACHADAS no puede ser vacio o igual a 0');
               return $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' => $token]);
            }
        }
        if ($model->autorizado == 0){  
            $model->autorizado = 1;
            $model->update();
            $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' =>$token]); 
        } else{
                $model->autorizado = 0;
                $model->update();
                $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' =>$token]);  
        }    
    }
    
    //PERMITE SUBIR LAS OBSERVACIONES DE LA ENTREGA
    
    public function actionCrear_observacion($id, $token) {
        $model = new \app\models\FormModeloSubirAuditoria();
        $ensamble = \app\models\EntregaMateriales::findOne($id);
       
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["subir_observacion"])) { 
                $ensamble->observacion = $model->observacion;
                $ensamble->save(false);
                $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' => $token]);
            }    
        }
         if (Yii::$app->request->get()) {
            $model->observacion = $ensamble->observacion;
         }
        return $this->renderAjax('subir_observacion', [
            'model' => $model,
            'id' => $id,
        ]);
    }
  
     //CIERRA EL PROCESO DE SOLICTUD
    public function actionCerrar_solicitud($id, $token) {
        //proceso de generar consecutivo
        $lista = \app\models\Consecutivos::findOne(15);
        $solicitud = EntregaMateriales::findOne($id); 
        $orden = SolicitudMateriales::findOne($solicitud->codigo);
        $solicitud->numero_entrega = $lista->numero_inicial + 1;
        $solicitud->cerrar_solicitud = 1;
        $solicitud->fecha_despacho = date('Y-m-d');
        $solicitud->save(false);
        $orden->despachado = 1;
        $orden->save(false);
        $lista->numero_inicial = $solicitud->numero_entrega;
        $lista->save(false);
        $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' =>$token]);  
    }
    
     //DESCARGAR MATERIAL DE EMPAQUE
    public function actionDescargar_material_empaque($id, $token)
    {
        $model = \app\models\EntregaMateriales::findOne($id);
        $detalle = \app\models\EntregaMaterialesDetalle::find()->where(['=','id_entrega', $id])->andWhere(['=','validar_linea_materia_prima', 0])->all();
        $con = 0; $stock = 0;
        foreach ($detalle as $val) {
            $materia = \app\models\MateriaPrimas::findOne($val->id_materia_prima);
            if($materia){
                $stock = $materia->stock - $val->unidades_despachadas;
                $id_materia = $val->id_materia_prima;
                if($stock >= 0){
                    $con++;
                    $materia->stock = $stock;
                    $materia->salida_materia_prima += $val->unidades_despachadas;
                    $materia->save(false);
                    //actualiza la linea de inventario
                    $val->validar_linea_materia_prima = 1;
                    $val->save(false);
                    //guarda la bitagora
                    $table = new \app\models\BitacoraMateriasPrimas();
                    $table->id_materia_prima = $id_materia;
                    $table->cantidad = $val->unidades_despachadas;
                    $table->fecha_salida = date('Y-m-d');
                    $table->fecha_hora_salida = date('Y-m-d H:i:s');
                    $table->user_name = Yii::$app->user->identity->username;
                    if($model->solicitud->id_orden_produccion !== null){
                       $table->descripcion_salida = 'Salida de material de empaque para ordenes de produccion';
                       $table->id_orden_produccion = $model->solicitud->id_orden_produccion;

                    }else{
                        $table->descripcion_salida = 'Salida de material de empaque para entrega de kits';
                        $table->id_entrega_kits = $model->solicitud->id_entrega_kits;
                    }
                    $table->save(false);
                    $this->SumarTotalesMateria($id_materia);
                }    
            }
        }
        if(count($detalle)> 0){
            $model->descargar_material_empaque = 1;
            $model->save();
        }    
        Yii::$app->getSession()->setFlash('success', 'Se enviaron al modulo de inventario de materias primas ('.$con.') registros para ser descargados. Se validaron exitosamente.');
        return $this->redirect(["entrega-materiales/view", 'id' => $id, 'token' =>$token]);  
    }
    
    //PROCESO QUE ACTUALIZA SALDOS
    protected function SumarTotalesMateria($id_materia) {
        $materia = \app\models\MateriaPrimas::findOne($id_materia);
        $total = 0; $iva = 0;
         if ($materia->valor_unidad !== 0){
             $total = $materia->valor_unidad * $materia->stock;     
             $iva = ($total * $materia->porcentaje_iva)/100;
             $materia->valor_iva = round($iva);
             $materia->subtotal = $total;
             $materia->total_materia_prima = $total + $iva;
             $materia->save(false);
         }
         
        
    }
    
       //REPORTES
    public function actionImprimir_entrega_materiales($id) {
        $model = EntregaMateriales::findOne($id);
        return $this->render('../formatos/reporte_entrega_materiales', [
            'model' => $model,
        ]);
    }
    

    /**
     * Finds the EntregaMateriales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EntregaMateriales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntregaMateriales::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
     //exceles
    public function actionExcelConsultaEntrega($tableexcel) {
          Yii::$app->getSession()->setFlash('info', 'Este proceso esta en desarrollo.'); 
    }
}
