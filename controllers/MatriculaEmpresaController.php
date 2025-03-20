<?php

namespace app\controllers;

use Yii;
use app\models\MatriculaEmpresa;
use app\models\MatriculaEmpresaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UsuarioDetalle;
use app\models\Municipios;
use app\models\Departamentos;
/**
 * MatriculaEmpresaController implements the CRUD actions for MatriculaEmpresa model.
 */
class MatriculaEmpresaController extends Controller
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
     * Lists all MatriculaEmpresa models.
     * @return mixed
     */

    public function actionMatricula($id)
    {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',3])->all()){
                $model = $this->findModel($id);
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    if($model->razon_social == null){
                       $model->razon_social_completa = strtoupper($model->primer_nombre. ' ' . $model->segundo_nombre .' ' .$model->primer_apellido .' ' .$model->segundo_apellido); 
                    }else{
                        $model->razon_social_completa = strtoupper($model->razon_social); 
                    }
                    $model->save(false);
                    
                }
                return $this->render('matricula', [
                    'model' => $model,
                ]); 

            }else{
                return $this->redirect(['site/sinpermiso']);
            } 
        }else{
            return $this->redirect(['site/login']);
        }
    }

     public function actionParametros($id)
    {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',142])->all()){
                $model = $this->findModel($id);
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                }
                return $this->render('parametros', [
                    'model' => $model,
                ]); 

            }else{
                return $this->redirect(['site/sinpermiso']);
            } 
        }else{
            return $this->redirect(['site/login']);
        }
    }
    
    /**
     * Finds the MatriculaEmpresa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MatriculaEmpresa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MatriculaEmpresa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionMunicipio($id) {
        $rows = Municipios::find()->where(['codigo_departamento' => $id])->all();

        echo "<option required>Seleccione...</option>";
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                echo "<option value='$row->codigo_municipio' required>$row->municipio</option>";
            }
        }
    }
}
