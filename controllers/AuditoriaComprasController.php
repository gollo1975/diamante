<?php

namespace app\controllers;

use Yii;
use app\models\AuditoriaCompras;
use app\models\AuditoriaComprasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuditoriaComprasController implements the CRUD actions for AuditoriaCompras model.
 */
class AuditoriaComprasController extends Controller
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
     * Lists all AuditoriaCompras models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditoriaComprasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuditoriaCompras model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token)
    {
        $detalle_compras = \app\models\AuditoriaCompraDetalles::find()->where(['=','id_auditoria', $id])->all();
        if(isset($_POST["actualizarauditoria"])){
            if(isset($_POST["detalle_compra"])){
                $intIndice = 0;
                foreach ($_POST["detalle_compra"] as $intCodigo):
                    $table = \app\models\AuditoriaCompraDetalles::findOne($intCodigo);
                    $table->nueva_cantidad = $_POST["nueva_cantidad"]["$intIndice"];
                    $table->nuevo_valor = $_POST["nuevo_valor"]["$intIndice"];
                    $table->nota = $_POST["nota"]["$intIndice"];
                    $table->save(false);
                    $intIndice++;
                endforeach;
                $detalle_compras = \app\models\AuditoriaCompraDetalles::find()->where(['=','id_auditoria', $id])->all();
               return $this->redirect(['view','id' =>$id, 'token' => $token, 'detalle_compras' => $detalle_compras]);
            }
            
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'detalle_compras' => $detalle_compras,
        ]);
    }

    /**
     * Creates a new AuditoriaCompras model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuditoriaCompras();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_auditoria]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuditoriaCompras model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_auditoria]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AuditoriaCompras model.
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
    
    //CERRAR AUDITORIA
    
    public function actionCerrarauditoria($id, $token, $id_orden) {
        $model = $this->findModel($id);
        $orden = \app\models\OrdenCompra::findOne($id_orden);
        $model->cerrar_auditoria = 1;
        $model->save();
        $orden->auditada = 1;
        $orden->save();
      return $this->redirect(['view','id' =>$id, 'token' => $token]);
    }
    
    /**
     * Finds the AuditoriaCompras model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuditoriaCompras the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuditoriaCompras::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
