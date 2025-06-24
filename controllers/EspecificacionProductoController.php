<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UsuarioDetalle;
use yii\data\Pagination;
use yii\helpers\Html;
//models
use app\models\EspecificacionProducto;
use app\models\EspecificacionProductoSearch;

/**
 * EspecificacionProductoController implements the CRUD actions for EspecificacionProducto model.
 */
class EspecificacionProductoController extends Controller
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
     * Lists all EspecificacionProducto models.
     * @return mixed
     */
   public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso', 97])->all()){
                $form = new \app\models\FiltroBusquedaAnalisis();
                $codigo = null;
                $concepto = null; $etapa = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $concepto = Html::encode($form->concepto);
                        $codigo = Html::encode($form->codigo);
                        $table = EspecificacionProducto::find()
                                ->andFilterWhere(['=', 'id_especificacion', $codigo])
                                ->andFilterWhere(['like', 'concepto', $concepto]);
                        $table = $table->orderBy('id_especificacion DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 10,
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
                    $table = EspecificacionProducto::find()
                            ->orderBy('id_especificacion DESC');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 10,
                        'totalCount' => $count->count(),
                    ]);
                    $tableexcel = $table->all();
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
                           
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }
    /**
     * Displays a single EspecificacionProducto model.
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
     * Creates a new EspecificacionProducto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EspecificacionProducto();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->user_name = Yii::$app->user->identity->username;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EspecificacionProducto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    
     public function actionEliminar_linea($id) {
        
        if (Yii::$app->request->post()) {
            $registro = EspecificacionProducto::findOne($id);
            if ((int) $id) {
                try {
                    EspecificacionProducto::deleteAll("id_especificacion=:id_especificacion", [":id_especificacion" => $id]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado con exito.');
                    $this->redirect(["especificacion-producto/index"]);
                } catch (IntegrityException $e) {
                   
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro Nro: ' .$registro->id_especificacion .', tiene registros asociados en otros procesos');
                    return $this->redirect(["especificacion-producto/index"]);
                } catch (\Exception $e) {
                    
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el registro Nro: ' .$registro->id_especificacion .', tiene registros asociados en otros procesos');
                    return $this->redirect(["especificacion-producto/index"]);
                }
            } else {
                // echo "Ha ocurrido un error al eliminar el registros, redireccionando ...";
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("especificacion-producto/index") . "'>";
            }
        } else {
            return $this->redirect(["especificacion-producto/index"]);
        }
    }

        /**
     * Finds the EspecificacionProducto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EspecificacionProducto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EspecificacionProducto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
