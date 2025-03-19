<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use kartik\select2\Select2;
use kartik\date\DatePicker;

//models
use app\models\Municipios;
use app\models\Departamentos;
use app\models\TipoDocumento;
use app\models\Transaciones;
use app\models\PosicionPrecio;
use app\models\AgentesComerciales;
use app\models\TipoCliente;

//asdshdghasdhas;
$conDepartamento = ArrayHelper::map(Departamentos::find()->orderBy('departamento ASC')->all(), 'codigo_departamento', 'departamento');
$conMunicipio = Municipios::find()->where(['codigo_departamento' => $model->codigo_departamento_residencia])->orderBy('municipio ASC')->all();
$conMunicipio = ArrayHelper::map($conMunicipio, 'codigo_municipio', 'municipio');
$listadoMunicipio = ArrayHelper::map(Municipios::find()->orderBy('municipio ASC')->all(), 'codigo_municipio', 'municipio');
$tipodocumento = ArrayHelper::map(TipoDocumento::find()->where(['=','proceso_nomina', 1])->all(), 'id_tipo_documento', 'documento');
$tipoEmpleado = ArrayHelper::map(\app\models\TipoEmpleado::find()->orderBy('descripcion ASC')->all(), 'tipo_empleado', 'descripcion');
$transacion = ArrayHelper::map(Transaciones::find()->all(), 'tipo_transacion', 'descripcion');
$grupoSanguineo = ArrayHelper::map(app\models\GrupoSanguineo::find()->all(), 'id_grupo', 'clasificacion');
$bancoEmpleado = ArrayHelper::map(app\models\BancoEmpleado::find()->orderBy('entidad ASC')->all(), 'id_banco', 'entidad');
$conProfesion = ArrayHelper::map(\app\models\Profesiones::find()->all(), 'id_profesion', 'profesion');
$conFormaPago = ArrayHelper::map(\app\models\FormaPago::find()->where(['=','servicio_nomina', 1])->all(), 'id_forma_pago', 'concepto');
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
<div class="panel panel-success">
    <div class="panel-heading">
        REGISTRO DEL EMPLEADO
    </div>
    <div class="panel-body">
         <div class="row">
          <?= $form->field($model, 'id_tipo_documento')->dropDownList($tipodocumento, ['prompt' => 'Seleccione...']) ?>
            <?= $form->field($model, 'nit_cedula')->input('text', ['id' => 'nit_cedula', 'onchange' => 'calcularDigitoVerificacion()']) ?>
            <?= Html::textInput('dv', $model->dv, ['id' => 'dv', 'aria-required' => true, 'aria-invalid' => 'false', 'maxlength' => 1, 'class' => 'form-control', 'placeholder' => 'dv', 'style' => 'width:50px', 'readonly' => true]) ?>       
        </div>	
         <div class="row">
            <div id="nombre1" style="display:block"><?= $form->field($model, 'nombre1')->input("text") ?></div>
            <div id="nombre2" style="display:block"><?= $form->field($model, 'nombre2')->input("text") ?></div>    
        </div>													   
       <div class="row">
            <div id="apellido1" style="display:block"><?= $form->field($model, 'apellido1')->input("text") ?></div>
            <div id="apellido2" style="display:block"><?= $form->field($model, 'apellido2')->input("text") ?></div>    
        </div>
        <div class="row">
            <?= $form->field($model, 'direccion')->input("text", ["maxlength" => 50]) ?>
            <?= $form->field($model, 'telefono')->input("text", ["maxlength" => 15]) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'celular')->input("text") ?>
            <?= $form->field($model, 'email_empleado')->input("text", ["maxlength" => 50]) ?>
        </div>
        <?php if($sw == 0){?>
            <div class="row">
                <?= $form->field($model, 'codigo_departamento_residencia')->dropDownList($conDepartamento, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                    $( "#' . Html::getInputId($model, 'codigo_municipio_residencia', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
                <?= $form->field($model, 'codigo_municipio_residencia')->dropDownList(['prompt' => 'Seleccione...']) ?>
            </div>
        <?php }else{?>
            <div class="row">
                <?= $form->field($model, 'codigo_departamento_residencia')->dropDownList($conDepartamento, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                    $( "#' . Html::getInputId($model, 'codigo_municipio_residencia', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
                     <?= $form->field($model, 'codigo_municipio_residencia')->dropDownList($conMunicipio, ['prompt' => 'Seleccione...']) ?>
            </div>    
        <?php }?>
        
        <div class="row">
            <?= $form->field($model, 'barrio')->input("text") ?>   
            <?= $form->field($model, 'estado_civil')->dropdownList(['1' => 'SOLTERO', '2' => 'UNION LIBRE','3' => 'CASADO', '4' => 'VIUDO(A)','5' => 'DIVORCIADO (A)'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'fecha_expedicion_documento')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
             <?= $form->field($model, 'codigo_municipio_expedicion')->widget(Select2::classname(), [
               'data' => $listadoMunicipio,
               'options' => ['prompt' => 'Seleccione...'],
               'pluginOptions' => [
                   'allowClear' => true
               ],
           ]); ?> 
        </div> 
        <div class="row">
               <?= $form->field($model, 'id_grupo')->widget(Select2::classname(), [
                   'data' => $grupoSanguineo,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
                ]); ?>
                <?= $form->field($model, 'genero')->dropdownList(['1' => 'MASCULINO', '2' => 'FEMENINO'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'fecha_nacimiento')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
             <?= $form->field($model, 'codigo_municipio_nacimiento')->widget(Select2::classname(), [
               'data' => $listadoMunicipio,
               'options' => ['prompt' => 'Seleccione...'],
               'pluginOptions' => [
                   'allowClear' => true
               ],
           ]); ?> 
        </div> 
        <div class="row">
            <?= $form->field($model, 'padre_familia')->dropdownList(['1' => 'SI', '2' => 'NO'], ['prompt' => 'Seleccione...']) ?>
            <?= $form->field($model, 'cabeza_hogar')->dropdownList(['1' => 'SI', '2' => 'NO'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'discapacitado')->dropdownList(['1' => 'SI', '2' => 'NO'], ['prompt' => 'Seleccione...']) ?>
             <?= $form->field($model, 'tipo_empleado')->widget(Select2::classname(), [
                   'data' => $tipoEmpleado,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
                ]); ?>
        </div>
        <div class="row">
             <?= $form->field($model, 'id_banco')->widget(Select2::classname(), [
                   'data' => $bancoEmpleado,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
                ]); ?>
            <?= $form->field($model, 'tipo_cuenta')->dropdownList(['S' => 'AHORRO', 'D' => 'CORRIENTE'], ['prompt' => 'Seleccione...']) ?>
        </div>
         <div class="row">
            <?= $form->field($model, 'numero_cuenta')->input("text") ?>
            <?= $form->field($model, 'tipo_transacion')->widget(Select2::classname(), [
                   'data' => $transacion,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
                ]); ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'id_forma_pago')->dropdownList($conFormaPago, ['prompt' => 'Seleccione...']) ?>
            <?= $form->field($model, 'id_profesion')->widget(Select2::classname(), [
                   'data' => $conProfesion,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
                ]); ?>
            
        </div>
         <div class="row">
            <?= $form->field($model, 'talla_zapato')->input("text", ["maxlength" => 10]) ?>
            <?= $form->field($model, 'talla_pantalon')->input("text", ["maxlength" => 10]) ?>
            
        </div>
        <div class="row">
            <div class="field-tblproveedor-observaciones_proveedor has-success">
                <?= $form->field($model, 'talla_camisa')->input("text", ["maxlength" => 10]) ?>
                <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
            </div>    
        </div>
    </div>    
    <div class="panel-footer text-right">
        <a href="<?= Url::toRoute("empleados/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>        
    </div>
</div>
<?php $form->end() ?>
