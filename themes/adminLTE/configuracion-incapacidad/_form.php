<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\ConfiguracionIncapacidad;
use app\models\ConceptoSalarios;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionIncapacidad */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
            'options' => []
        ],
        ]);
?>
<?php
   $salarios = ArrayHelper::map(ConceptoSalarios::find()->all(), 'codigo_salario', 'nombre_concepto');
?>

<div class="panel panel-success">
    <div class="panel-heading">
        Información: Configuración de incapacidad
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>    
            <?= $form->field($model, 'codigo_salario')->widget(Select2::classname(), [
            'data' => $salarios,
            'options' => ['placeholder' => 'Seleccione el concepto'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
        </div>  
        <div class="row">
              <?= $form->field($model, 'porcentaje')->textInput(['maxlength' => true]) ?>  
               <?= $form->field($model, 'codigo')->dropdownList(['1' => 'IG', '2' => 'IL'],['prompt' => 'Seleccione']) ?>
         </div>    
       <div class="panel panel-success">
                    <div class="panel-heading">
                        Seleccione la opción..
                    </div>
                    <div class="panel-body">
                        <div class="checkbox checkbox-success" align ="left">
                                <?= $form->field($model, 'genera_pago')->checkBox(['label' => 'Genera_pago','' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'genera_pago']) ?>
                                <?= $form->field($model, 'genera_ibc')->checkBox(['label' => 'Genera_ibc',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'genera_ibc']) ?>
                        </div>
                    </div>
        </div>   
            <div class="panel-footer text-right">			
                <a href="<?= Url::toRoute("configuracion-incapacidad/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success",]) ?>
            </div>
    </div>
        </div>
<?php $form->end() ?>     