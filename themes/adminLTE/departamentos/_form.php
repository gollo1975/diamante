<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Pais;
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
$pais = ArrayHelper::map(Pais::find()->all(), 'codigo_pais', 'pais');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>DEPARTAMENTOS</h4>
    </div>
    <div class="panel-body"> 
        <?php if($sw == 0){?>        
            <div class="row">
                <?= $form->field($model, 'codigo_departamento')->textInput(['maxlength' => true]) ?>    
            </div>
        <?php }else{?>
            <div class="row">
                <?= $form->field($model, 'codigo_departamento')->textInput(['maxlength' => true, 'readonly' => true]) ?>    
            </div>
        <?php }?>  
        <div class="row">
            <?= $form->field($model, 'departamento')->textInput(['maxlength' => true]) ?>  					
        </div>

        <div class="row">
           <?= $form->field($model, 'codigo_pais')->widget(Select2::classname(), [
                'data' => $pais,
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
            <a href="<?= Url::toRoute("departamentos/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
