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

use app\models\DevolucionProductos;
use app\models\DevolucionProductosSearch;
use app\models\UsuarioDetalle;
use app\models\InventarioProductos;
use app\models\TipoDevolucionProductos;
use app\models\Clientes;

/**
 * DevolucionProductosController implements the CRUD actions for DevolucionProductos model.
 */
class DevolucionProductosController extends Controller
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
     * Lists all DevolucionProductos models.
     * @return mixed
     */
  public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',64])->all()){
                $form = new \app\models\FiltroBusquedaDevolucion();
                $numero = null;
                $cliente = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero = Html::encode($form->numero);
                        $cliente = Html::encode($form->cliente);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = DevolucionProductos::find()
                                    ->andFilterWhere(['=', 'numero_devolucion', $numero])
                                    ->andFilterWhere(['between', 'fecha_devolucion', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['=', 'id_cliente', $cliente]);
                        $table = $table->orderBy('id_devolucion DESC');
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
                            $check = isset($_REQUEST['id_inventario  DESC']);
                            $this->actionExcelConsultaDevolucion($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = DevolucionProductos::find()->orderBy('id_devolucion DESC');
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
                        $this->actionExcelConsultaInventario($tableexcel);
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
     * Displays a single DevolucionProductos model.
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
     * Creates a new DevolucionProductos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DevolucionProductos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_devolucion]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DevolucionProductos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_devolucion]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DevolucionProductos model.
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
     * Finds the DevolucionProductos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DevolucionProductos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DevolucionProductos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
