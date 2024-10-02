<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\ConfiguracionLicencia;
use app\models\ConceptoSalarios;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionLicencia */
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
        Información: Configuración de licencia
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'concepto')->textInput(['maxlength' => true]) ?>    
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
               <?= $form->field($model, 'codigo')->dropdownList(['1' => 'LM', '0' => 'OTRAS'],['prompt' => 'Seleccione']) ?>
         </div>    
            
       <div class="panel panel-success">
                    <div class="panel-heading">
                        Seleccione la opción..
                    </div>
                    <div class="panel-body">
                        <div class="checkbox checkbox-success" align ="left">
                                <?= $form->field($model, 'afecta_salud')->checkBox(['label' => 'Afecta salud','' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'Afecta salud']) ?>
                                <?= $form->field($model, 'ausentismo')->checkBox(['label' => 'Ausentismo',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'ausentismo']) ?>
                                <?= $form->field($model, 'maternidad')->checkBox(['label' => 'Maternidad',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'maternidad']) ?>
                        </div>
                        <div class="checkbox checkbox-success" align ="left">
                                <?= $form->field($model, 'paternidad')->checkBox(['label' => 'Paternidad','' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'paternidad']) ?>
                                <?= $form->field($model, 'suspension_contrato')->checkBox(['label' => 'Suspension contrato',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'suspension_contrato']) ?>
                                <?= $form->field($model, 'remunerada')->checkBox(['label' => 'Remunerada',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'remunerada']) ?>
                        </div>
                     </div>
                </div>   
                <div class="panel-footer text-right">			
                    <a href="<?= Url::toRoute("configuracion-licencia/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success",]) ?>
                </div>
            </div>
        </div>
<?php $form->end() ?>     