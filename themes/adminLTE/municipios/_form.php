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
$depto = ArrayHelper::map(Departamentos::find()->orderBy('departamento ASC')->all(), 'codigo_departamento', 'departamento');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>MUNICIPIOS</h4>
    </div>
    <div class="panel-body">  
        <?php if($sw == 0){?>        
            <div class="row">
                <?= $form->field($model, 'codigo_municipio')->textInput(['maxlength' => true]) ?>    
            </div>
        <?php }else{?>
            <div class="row">
                <?= $form->field($model, 'codigo_municipio')->textInput(['maxlength' => true, 'readonly' => true]) ?>    
            </div>
        <?php }?>
        <div class="row">
            <?= $form->field($model, 'municipio')->textInput(['maxlength' => true]) ?>  					
        </div>

        <div class="row">
           <?= $form->field($model, 'codigo_departamento')->widget(Select2::classname(), [
                'data' => $depto,
                'options' => ['prompt' => 'Seleccione un registro ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>  
        <div class="row">
             <?= $form->field($model, 'codigo_interfaz')->textInput(['maxlength' => true]) ?>  					
        </div>  
        <?php if($sw == 1 ){?>
            <div class="row">
                <?= $form->field($model, 'estado_registro')->dropdownList(['0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione...']) ?>
            </div>
        <?php } ?>
        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("municipios/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

