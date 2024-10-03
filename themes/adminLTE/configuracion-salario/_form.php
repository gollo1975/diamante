<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionSalario */
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
<div class="panel panel-success">
    <div class="panel-heading">
        Configuración del nuevo salario
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'salario_minimo_actual')->textInput(['maxlength' => true])?> 
            <?= $form->field($model, 'auxilio_transporte_actual')->textInput(['maxlength' => true]) ?> 
        </div>
       
       <div class="row">      
            <?=  $form->field($model, 'fecha_cierre')->widget(DatePicker::className(), ['name' => 'check_issue_date',
               'value' => date('Y-m-d', strtotime('+2 days')),
               'options' => ['placeholder' => 'Seleccione una fecha ...'],
               'pluginOptions' => [
                   'format' => 'yyyy-m-d',
                   'todayHighlight' => true]])
           ?>
             <?=  $form->field($model, 'fecha_aplicacion')->widget(DatePicker::className(), ['name' => 'check_issue_date',
               'value' => date('Y-m-d', strtotime('+2 days')),
               'options' => ['placeholder' => 'Seleccione una fecha ...'],
               'pluginOptions' => [
                   'format' => 'yyyy-m-d',
                   'todayHighlight' => true]])
           ?>
            
        </div>  
         <div class="row">
           <?= $form->field($model, 'anio')->textInput(['maxlength' => true]) ?> 
             <?= $form->field($model, 'estado')->dropdownList(['1' => 'SI', '0' => 'NO']) ?>
        </div>
        <div class="panel-footer text-right">                
            <a href="<?= Url::toRoute("configuracion-salario/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>