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
use app\models\NaturalezaSociedad;
use app\models\PosicionPrecio;
use app\models\AgentesComerciales;
use app\models\TipoCliente;
?>

<!--<h1>Nuevo proveedor</h1>-->
<body onload= "mostrar2()">
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
$departamento = ArrayHelper::map(Departamentos::find()->orderBy('departamento ASC')->all(), 'codigo_departamento', 'departamento');
$vendedor = ArrayHelper::map(AgentesComerciales::find()->where(['=','estado', 0])->orderBy('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
$tipodocumento = ArrayHelper::map(TipoDocumento::find()->where(['=','proceso_cliente', 1])->all(), 'id_tipo_documento', 'documento');
$posicion = ArrayHelper::map(PosicionPrecio::find()->all(), 'id_posicion', 'posicion');
$naturaleza = ArrayHelper::map(NaturalezaSociedad::find()->all(), 'id_naturaleza', 'naturaleza');
$tipoCliente = ArrayHelper::map(TipoCliente::find()->all(), 'id_tipo_cliente', 'concepto');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        CLIENTES
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'id_tipo_documento')->dropDownList($tipodocumento, ['prompt' => 'Seleccione...', 'onchange' => 'mostrar2()', 'id' => 'id_tipo_documento']) ?>
            <?= $form->field($model, 'nit_cedula')->input('text', ['id' => 'nit_cedula', 'onchange' => 'calcularDigitoVerificacion()']) ?>
            <?= Html::textInput('dv', $model->dv, ['id' => 'dv', 'aria-required' => true, 'aria-invalid' => 'false', 'maxlength' => 1, 'class' => 'form-control', 'placeholder' => 'dv', 'style' => 'width:50px', 'readonly' => true]) ?>        
        </div>
        <div class="row">
            <div id="primer_nombre" style="display:block"><?= $form->field($model, 'primer_nombre')->input("text") ?></div>
            <div id="segundo_nombre" style="display:block"><?= $form->field($model, 'segundo_nombre')->input("text") ?></div>
        </div>
        <div class="row">
            <div id="primer_apellido" style="display:block"><?= $form->field($model, 'primer_apellido')->input("text") ?></div>
            <div id="segundo_apellido" style="display:block"><?= $form->field($model, 'segundo_apellido')->input("text") ?></div> 
   
        </div>
        <div class="row">
            <div id="razon_social" style="display:none"><?= $form->field($model, 'razon_social')->input("text") ?></div>
        </div>

        <div class="row">
            <?= $form->field($model, 'direccion')->input("text", ["maxlength" => 50]) ?>
            <?= $form->field($model, 'email_cliente')->input("text", ["maxlength" => 50]) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'telefono')->input("text") ?>
            <?= $form->field($model, 'celular')->input("text") ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
           <?= $form->field($model, 'codigo_municipio')->dropDownList($municipio, ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'forma_pago')->dropdownList(['1' => 'CONTADO', '2' => 'CRÉDITO'], ['prompt' => 'Seleccione...', 'onchange' => 'fpago()', 'id' => 'forma_pago']) ?>
            <?= $form->field($model, 'plazo')->input("text",['id' => 'plazo']) ?>
        </div>    
        <div class="row">
            <?= $form->field($model, 'tipo_regimen')->dropdownList(['0' => 'SIMPLIFICADO', '1' => 'COMUN'], ['prompt' => 'Seleccione...']) ?>
            <?= $form->field($model, 'id_naturaleza')->widget(Select2::classname(), [
                   'data' => $naturaleza,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
        </div>
        <div class="row">
               <?= $form->field($model, 'tipo_sociedad')->dropdownList(['0' => 'NATURAL', '1' => 'JURIDICA'], ['prompt' => 'Seleccione...']) ?>
                 <?= $form->field($model, 'id_posicion')->widget(Select2::classname(), [
                   'data' => $posicion,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
               
        </div> 
        <div class="row" >
                <?= $form->field($model, 'id_agente')->widget(Select2::classname(), [
                   'data' => $vendedor,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
                 <?= $form->field($model, 'id_tipo_cliente')->widget(Select2::classname(), [
                   'data' => $tipoCliente,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
        </div>
        <div class="row">
            <div class="field-tblproveedor-observaciones_proveedor has-success">
                    <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
            </div>
        </div> 	
        <div class="panel panel-success">
                    <div class="panel-heading">
                        Parametros
                    </div>
                    <div class="panel-body">
                        <div class="checkbox checkbox-success" align ="left">
                                <?= $form->field($model, 'autoretenedor')->checkBox(['label' => 'Autorretenedor','1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'autoretenedor']) ?>
                                <?= $form->field($model, 'estado_cliente')->checkBox(['label' => 'Inactivo',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'estado_cliente']) ?>
                                <?= $form->field($model, 'aplicar_venta_mora')->checkBox(['label' => 'Vender en mora',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'aplicar_venta_mora']) ?>
                        </div>
                        
                        </div>
                     </div>
        </div>  
    </div>    
    <div class="panel-footer text-right">
        <a href="<?= Url::toRoute("clientes/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>        
    </div>
</div>
<?php $form->end() ?>
</body>
   
