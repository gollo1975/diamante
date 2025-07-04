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
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>TIPO DE FACTURA</h4>
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>  					
        </div>
        <div class="row">
               <?= $form->field($model, 'porcentaje_retencion')->textInput(['maxlength' => true]) ?>  
        </div>
        <div class="row">
                <?= $form->field($model, 'aplica_interes_mora')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
            </div>
        <div class="row">
               <?= $form->field($model, 'porcentaje_mora')->textInput(['maxlength' => true]) ?>  
        </div>
        <div class="row">
               <?= $form->field($model, 'base_retencion')->textInput(['maxlength' => true]) ?>  
        </div>
         <div class="row">
                <?= $form->field($model, 'ver_registro_factura')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
            </div>
         <div class="row">
                <?= $form->field($model, 'documento_libre')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
            </div>
        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("tipo-factura-venta/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>