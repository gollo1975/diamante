<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Departamentos;
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
        <h4>TIPO DE DOCUMENTOS</h4>
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'tipo_documento')->textInput(['maxlength' => true]) ?>    
        </div>
        <div class="row">
            <?= $form->field($model, 'documento')->textInput(['maxlength' => true]) ?>  					
        </div>
        <div class="row">
                <?= $form->field($model, 'proceso_nomina')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
                <?= $form->field($model, 'proceso_cliente')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
                <?= $form->field($model, 'proceso_proveedor')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
             <?= $form->field($model, 'codigo_interfaz')->textInput(['maxlength' => true]) ?>  					
        </div>  
        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("tipo-documento/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>


