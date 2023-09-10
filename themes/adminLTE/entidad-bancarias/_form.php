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
$tipo = ArrayHelper::map(TipoDocumento::find()->where(['=','id_tipo_documento', 3])->all(), 'id_tipo_documento', 'documento');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        ENTIDAD FINANCIERA
    </div>
    <div class="panel-body">
         <div class="row">
            <?= $form->field($model, 'id_tipo_documento')->widget(Select2::classname(), [
                   'data' => $tipo,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
            <?= $form->field($model, 'nit_cedula')->input('text', ['id' => 'nit_cedula', 'onchange' => 'calcularDigitoVerificacion()']) ?>
            <?= Html::textInput('dv', $model->dv, ['id' => 'dv', 'aria-required' => true, 'aria-invalid' => 'false', 'maxlength' => 1, 'class' => 'form-control', 'placeholder' => 'dv', 'style' => 'width:50px', 'readonly' => true]) ?>       
            
        </div>	
        <div class="row">
           <?= $form->field($model, 'codigo_banco')->textInput(['maxlength' => true]) ?>
           <?= $form->field($model, 'entidad_bancaria')->input("text", ["maxlength" => 30]) ?> 
        </div>
       
         <div class="row">
            <?= $form->field($model, 'direccion_banco')->input("text", ["maxlength" => 50]) ?>
            <?= $form->field($model, 'telefono_banco')->input("text", ["maxlength" => 15]) ?> 
        </div>
        <div class="row">
            <?= $form->field($model, 'validador_digitos')->input("text", ["maxlength" => 2]) ?>
             <?= $form->field($model, 'codigo_interfaz')->input("text", ["maxlength" => 4]) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
            <?= $form->field($model, 'codigo_municipio')->dropDownList(['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'tipo_producto')->dropdownList(['S' => 'CUENTA DE AHORROS', 'D' => 'CUENTA CORRIENTE'], ['prompt' => 'Seleccione...']) ?>
            <?= $form->field($model, 'producto')->textInput(['maxlength' => true]) ?>
        </div>    
        <div class="panel panel-success">
            <div class="panel-heading">
                Seleccione la opción..
            </div>
            <div class="panel-body">
                <div class="checkbox checkbox-success" align ="left">
                    <?= $form->field($model, 'convenio_nomina')->checkBox(['label' => 'Aplica Nomina','1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:10px;', 'id'=>'convenio_nomina']) ?>
                    <?= $form->field($model, 'convenio_proveedor')->checkBox(['label' => 'Aplica Proveedor','1'=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:10px;', 'id'=>'convenio_proveedor']) ?>
                    <?= $form->field($model, 'convenio_empresa')->checkBox(['label' => 'Aplica empresa','1'=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:10px;', 'id'=>'convenio_empresa']) ?>

                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer text-right">
        <a href="<?= Url::toRoute("entidad-bancarias/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>        
    </div>
</div>
<?php $form->end() ?>
