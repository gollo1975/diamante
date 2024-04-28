<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Municipio */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin([
		'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
	'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-3 control-label'],
                    'options' => []
                ],
	]); ?>
<?php
$clasificar = ArrayHelper::map(app\models\ClasificacionInventario::find()->orderBy('descripcion ASC')->all(), 'id_clasificacion', 'descripcion');

?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>GRUPO DE PRODUCTOS</h4>
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'nombre_grupo')->textInput(['maxlength' => true]) ?>  					
        </div>
        
        <div class="row">
           <?= $form->field($model, 'id_clasificacion')->widget(Select2::classname(), [
                'data' => $clasificar,
                'options' => ['prompt' => 'Seleccione un registro ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>  
         <div class="row">
            <?= $form->field($model, 'ver_registro')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
        </div>   

        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("grupo-producto/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>