<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use kartik\select2\Select2;
?>
<!--<h1>Nuevo proveedor</h1>-->
<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]);
$pisos = ArrayHelper::map(\app\models\Pisos::find()->all(), 'id_piso', 'descripcion');
?>

<div class="panel panel-success">
    <div class="panel-heading">
        LISTADO DE REQUISITOS
    </div>
    <div class="panel-body">
         
        <div class="row">
            <?= $form->field($model, 'concepto')->textInput(['maxlength' => true]) ?>
        </div>   
        <div class="row">
             <?= $form->field($model, 'porcentaje')->textInput(['maxlength' => true]) ?>
        </div>
       
         <div class="row">
            <?= $form->field($model, 'aplica_proveedor')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
           <?= $form->field($model, 'aplica_requisito')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
        </div>    
        
    </div>
    <div class="panel-footer text-right">
        <a href="<?= Url::toRoute("listado-requisitos/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>        
    </div>
</div>
<?php $form->end() ?>
