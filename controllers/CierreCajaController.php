<?php

namespace app\controllers;
       
//clases        
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
use app\models\CierreCaja;
use app\models\CierreCajaSearch;
use app\models\UsuarioDetalle;
use app\models\FacturaVentaPunto;
use app\models\Remisiones;

/**
 * CierreCajaController implements the CRUD actions for CierreCaja model.
 */
class CierreCajaController extends Controller
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
     * Lists all CierreCaja models.
     * @return mixed
     */
    public function actionIndex() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',110])->all()){
                $form = new \app\models\FiltroBusquedaCierreCaja();
                $numero_cierre = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $conPunto = \app\models\PuntoVenta::find()->where(['=','id_punto', Yii::$app->user->identity->id_punto])->one();
                $accesoToken = $conPunto->id_punto;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $numero_cierre = Html::encode($form->numero_cierre);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $table = CierreCaja::find()
                                ->andFilterWhere(['between', 'fecha_inicio', $fecha_inicio , $fecha_corte])
                                ->andFilterWhere(['=', 'numero_cierre', $numero_cierre])
                                 ->andWhere(['=', 'id_punto', $accesoToken])
                                ->andWhere(['=','proceso_cerrado', 1]);
                        $table = $table->orderBy('id_cierre DESC');
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
                            $this->actionExcelconsultaCierreCaja($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = CierreCaja::find()->Where(['=', 'id_punto', $conPunto->id_punto])->andWhere(['=','proceso_cerrado', 1])
                            ->orderBy('id_cierre DESC');
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
                    if(isset($_POST['excel'])){                    
                            $this->actionExcelconsultaCierreCaja($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'conPunto' => $conPunto,
                            'accesoToken' => $accesoToken,
                    
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }
    }

    /**
     * Displays a single CierreCaja model.
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
     * Creates a new CierreCaja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($accesoToken)
    {
        $fecha_dia_actual = date('Y-m-d');   
        $facturas = FacturaVentaPunto::find()->where(['=','fecha_inicio', $fecha_dia_actual])->andWhere(['=','id_punto', $accesoToken])->all(); // cargo las facturas
        $remisiones = Remisiones::find()->where(['=','fecha_inicio', $fecha_dia_actual])->andWhere(['=','id_punto', $accesoToken])->all(); // cargo las remisiones  
        $total_remision = 0;
        $total_factura = 0;
        foreach ($facturas as $factura){
            $total_factura += $factura->total_factura;
        }
        foreach ($remisiones as $remision):
            $total_remision += $remision->total_remision; 
        endforeach;
        if(count($facturas)> 0 || count($remisiones)> 0){
            $table = new CierreCaja();
            $table->id_punto = $accesoToken;
            $table->fecha_inicio = $fecha_dia_actual;
            $table->fecha_corte = $fecha_dia_actual;
            $table->total_remision = $total_remision;      
            $table->total_factura = $total_factura;
            $table->user_name = Yii::$app->user->identity->username;
            $table->save();
            $model = CierreCaja::find()->orderBy('id_cierre DESC')->limit(1)->one();
            return $this->render('view', [
                'model' => $model,
            ]);
        }else{
            Yii::$app->getSession()->setFlash('warning', 'No hay ventas realizadas el dia de hoy.');
            $this->redirect(["cierre-caja/index"]);
        }    
    }

    /**
     * Updates an existing CierreCaja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_cierre]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CierreCaja model.
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
     * Finds the CierreCaja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CierreCaja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CierreCaja::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
