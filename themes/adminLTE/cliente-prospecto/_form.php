<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use kartik\select2\Select2;
//models
use app\models\Municipios;
use app\models\Departamentos;
use app\models\TipoDocumento;


$departamento = ArrayHelper::map(Departamentos::find()->orderBy('departamento DESC')->all(), 'codigo_departamento', 'departamento');
$municipio = ArrayHelper::map(Municipios::find()->orderBy('municipio DESC')->all(), 'codigo_municipio', 'municipio');
$tipodocumento = ArrayHelper::map(TipoDocumento::find()->where(['=','proceso_cliente', 1])->all(), 'id_tipo_documento', 'documento');
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
                'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-4 control-label'],
                'options' => []
            ],
        ]);
?>
<div class="panel panel-success">
    <div class="panel-heading">
        PROSPECTO - CLIENTES
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'id_tipo_documento')->dropDownList($tipodocumento, ['prompt' => 'Seleccione...', 'onchange' => 'mostrar2()', 'id' => 'id_tipo_documento']) ?>
        </div>														   
        <div class="row">
            <?= $form->field($model, 'nit_cedula')->input('text', ['id' => 'nit_cedula', 'onchange' => 'calcularDigitoVerificacion()']) ?>
            <?= Html::textInput('dv', $model->dv, ['id' => 'dv', 'aria-required' => true, 'aria-invalid' => 'false', 'maxlength' => 1, 'class' => 'form-control', 'placeholder' => 'dv', 'style' => 'width:35px', 'readonly' => true]) ?>       
        </div>
        <div class="row">
            <div id="primer_nombre" style="display:block"><?= $form->field($model, 'primer_nombre')->input("text") ?></div>
        </div>
        <div class="row">
            <div id="segundo_nombre" style="display:block"><?= $form->field($model, 'segundo_nombre')->input("text") ?></div>    
        </div>
        <div class="row">
            <div id="primer_apellido" style="display:block"><?= $form->field($model, 'primer_apellido')->input("text") ?></div>
        </div>
        <div class="row">
           <div id="segundo_apellido" style="display:block"><?= $form->field($model, 'segundo_apellido')->input("text") ?></div>    
        </div>
        <div class="row">
            <div id="razon_social" style="display:none"><?= $form->field($model, 'razon_social')->input("text") ?></div>
        </div>
         <div class="row">
           <?= $form->field($model, 'celular')->input("text") ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'direccion_prospecto')->input("text", ["maxlength" => 50]) ?>
        </div>
         <div class="row">
            <?= $form->field($model, 'email_prospecto')->input("text", ["maxlength" => 50]) ?>
        </div>
       
        <div class="row">
            <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'codigo_municipio')->dropDownList(['prompt' => 'Seleccione...']) ?>
        </div>  
    </div>    
    <div class="panel-footer text-right">
        <a href="<?= Url::toRoute("cliente-prospecto/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>        
    </div>
</div>
<?php $form->end() ?>
