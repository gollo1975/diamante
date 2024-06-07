<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$tipodocumento = ArrayHelper::map(\app\models\TipoDocumento::find()->where(['=','proceso_cliente', 1])->all(), 'id_tipo_documento', 'documento');
$departamento = ArrayHelper::map(app\models\Departamentos::find()->orderBy('departamento DESC')->all(), 'codigo_departamento', 'departamento');
$posicion = ArrayHelper::map(app\models\PosicionPrecio::find()->all(), 'id_posicion', 'posicion');
$naturaleza = ArrayHelper::map(app\models\NaturalezaSociedad::find()->all(), 'id_naturaleza', 'naturaleza');
$vendedor = ArrayHelper::map(app\models\AgentesComerciales::find()->where(['=','estado', 0])->orderBy('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
$tipoCliente = ArrayHelper::map(app\models\TipoCliente::find()->all(), 'id_tipo_cliente', 'concepto');
?>

<?php $form = ActiveForm::begin([

    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-2 control-label'],
        'options' => []
    ],
]); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">
        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left">
                   CLIENTES...
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
                        <?= $form->field($model, 'direccion')->input("text", ["maxlength" => 60]) ?>
                        <?= $form->field($model, 'email_cliente')->input("text", ["maxlength" => 60]) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'celular')->input("text") ?>
                        <?= $form->field($model, 'tipo_regimen')->dropdownList(['0' => 'SIMPLIFICADO', '1' => 'COMUN'], ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                            $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
                        <?= $form->field($model, 'codigo_municipio')->dropDownList(['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'tipo_sociedad')->dropdownList(['0' => 'NATURAL', '1' => 'JURIDICA'], ['prompt' => 'Seleccione...']) ?>
                        <?= $form->field($model, 'id_naturaleza')->dropdownList($naturaleza, ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'id_agente')->dropdownList($vendedor, ['prompt' => 'Seleccione...']) ?>
                        <?= $form->field($model, 'id_tipo_cliente')->dropdownList($tipoCliente, ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="panel-footer text-right">			
                         <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'crear_clientes']) ?>                    
                   </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

