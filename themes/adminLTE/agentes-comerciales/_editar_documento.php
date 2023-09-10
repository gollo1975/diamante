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
use app\models\Cargos;
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
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'options' => []
            ],
        ]);
?>

<?php
$departamento = ArrayHelper::map(Departamentos::find()->orderBy('departamento DESC')->all(), 'codigo_departamento', 'departamento');
$municipio = ArrayHelper::map(Municipios::find()->orderBy('municipio DESC')->all(), 'codigo_municipio', 'municipio');
$tipodocumento = ArrayHelper::map(TipoDocumento::find()->where(['=','proceso_proveedor', 1])->all(), 'id_tipo_documento', 'documento');
$cargo = ArrayHelper::map(Cargos::find()->orderBy('nombre_cargo DESC')->all(), 'id_cargo', 'nombre_cargo');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        AGENTES COMERCIALES
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'id_tipo_documento')->dropDownList($tipodocumento, ['prompt' => 'Seleccione...', 'onchange' => 'mostrar2()', 'id' => 'id_tipo_documento']) ?>
            <?= $form->field($model, 'nit_cedula')->input('text', ['id' => 'nit_cedula', 'onchange' => 'calcularDigitoVerificacion()']) ?>
            <?= Html::textInput('dv', $model->dv, ['id' => 'dv', 'aria-required' => true, 'aria-invalid' => 'false', 'maxlength' => 1, 'class' => 'form-control', 'placeholder' => 'dv', 'style' => 'width:50px', 'readonly' => true]) ?>       
        </div>														   
        <div class="row">
            <?= $form->field($model, 'primer_nombre')->input("text", ["maxlength" => 15]) ?>
            <?= $form->field($model, 'segundo_nombre')->input("text", ["maxlength" => 15]) ?>    
        </div>
        <div class="row">
            <?= $form->field($model, 'primer_apellido')->input("text", ["maxlength" => 15]) ?>
            <?= $form->field($model, 'segundo_apellido')->input("text", ["maxlength" => 15]) ?>    
        </div>
        <div class="row">
            <?= $form->field($model, 'direccion')->input("text", ["maxlength" => 40]) ?>
            <?= $form->field($model, 'email_agente')->input("text", ["maxlength" => 50]) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'celular_agente')->input("text") ?>
            <?= $form->field($model, 'estado')->dropdownList(['0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione...']) ?>			
        </div>
        <div class="row">
            <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
            <?= $form->field($model, 'codigo_municipio')->dropDownList($municipio, ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">   
            <?= $form->field($model, 'id_cargo')->widget(Select2::classname(), [
                   'data' => $cargo,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
        </div>
    </div>    
    <div class="panel-footer text-right">
        <a href="<?= Url::toRoute("agentes-comerciales/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>        
    </div>
</div>
<?php $form->end() ?>
