<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\GrupoProducto;
use app\models\Marca;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Municipio */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin([
		'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
	'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-6 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-3 control-label'],
                    'options' => []
                ],
	]); ?>
<?php
$grupo = ArrayHelper::map(GrupoProducto::find()->orderBy('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');
$conMarca = ArrayHelper::map(Marca::find()->orderBy('marca ASC')->where(['=','estado', 0])->all(), 'id_marca', 'marca');

?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>PRODUCTOS</h4>
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'nombre_producto')->textInput(['maxlength' => true]) ?>    
        </div>
        <div class="row">
           <?= $form->field($model, 'id_grupo')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>  
        <div class="row">
           <?= $form->field($model, 'id_marca')->widget(Select2::classname(), [
                'data' => $conMarca,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>  
       
        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("productos/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

