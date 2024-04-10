<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */
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
use app\models\EntidadBancarias;
use app\models\NaturalezaSociedad;

$this->title = 'Actualizar';
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$entidad = EntidadBancarias::findOne($model->codigo_banco);
if ($msg == 0){
}else{
    Yii::$app->getSession()->setFlash('warning', ' La entidad ' . $entidad->entidad_bancaria. ' exige (11) digitos en el numero de la cuenta bancaria del proveedor.');
}
?>

<body onload= "mostrar2()">
<!--<h1>Editar proveedor</h1>-->
<?php $form = ActiveForm::begin([
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
//$municipio = ArrayHelper::map(Municipios::find()->orderBy('municipio DESC')->all(), 'codigo_municipio', 'municipio');
$tipodocumento = ArrayHelper::map(TipoDocumento::find()->where(['=','proceso_proveedor', 1])->all(), 'id_tipo_documento', 'documento');
$banco = ArrayHelper::map(EntidadBancarias::find()->where(['=','convenio_proveedor', 1])->all(), 'codigo_banco', 'entidad_bancaria');
$naturaleza = ArrayHelper::map(NaturalezaSociedad::find()->all(), 'id_naturaleza', 'naturaleza');
?>
    <div class="panel panel-success">
        <div class="panel-heading">
            PROVEEDORES
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
                <?= $form->field($model, 'email')->input("text") ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'telefono')->input("text") ?>
                <?= $form->field($model, 'celular')->input("text") ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, ['prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
               $( "#' . Html::getInputId($model, 'codigo_municipio', ['required']) . '" ).html( data ); });']); ?>
                <?= $form->field($model, 'codigo_municipio')->dropDownList($municipio, ['prompt' => 'Seleccione...']) ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'nombre_contacto')->input("text") ?>
                <?= $form->field($model, 'celular_contacto')->input("text") ?>
            </div>	
            <div class="row">
                <?= $form->field($model, 'forma_pago')->dropdownList(['1' => 'CONTADO', '2' => 'CRÉDITO'], ['prompt' => 'Seleccione...', 'onchange' => 'fpago()', 'id' => 'forma_pago']) ?>
                <?= $form->field($model, 'plazo')->input("text",['id' => 'plazo']) ?>
            </div>    
            <div class="row">
                <?= $form->field($model, 'tipo_regimen')->dropdownList(['0' => 'SIMPLIFICADO', '1' => 'COMUN'], ['prompt' => 'Seleccione...']) ?>
                <?= $form->field($model, 'autoretenedor')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>			
            </div>
            <div class="row">   
                <?= $form->field($model, 'id_naturaleza')->widget(Select2::classname(), [
                       'data' => $naturaleza,
                       'options' => ['prompt' => 'Seleccione...'],
                       'pluginOptions' => [
                           'allowClear' => true
                       ],
                   ]); ?> 
                <?= $form->field($model, 'tipo_sociedad')->dropdownList(['0' => 'NATURAL', '1' => 'JURIDICA'], ['prompt' => 'Seleccione...']) ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'codigo_banco')->widget(Select2::classname(), [
                       'data' => $banco,
                       'options' => ['prompt' => 'Seleccione...'],
                       'pluginOptions' => [
                           'allowClear' => true
                       ],
                ]); ?> 
                <?= $form->field($model, 'tipo_cuenta')->dropdownList(['S' => 'CUENTA DE AHORROS', 'D' => 'CUENTA CORRIENTE'], ['prompt' => 'Seleccione...']) ?>
            </div>    
            <div class="row">
                <?= $form->field($model, 'producto')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'tipo_transacion')->dropDownList(['27' => 'ABONO A CTA CORRIENTE', '37' => 'ABONO A CTA AHORRO'], ['prompt' => 'Seleccione una opcion...']) ?> 
            </div>  
            <div class="row">
                   <?= $form->field($model, 'predeterminado')->dropDownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione una opcion...']) ?>
                    <?= $form->field($model, 'requisito_validado')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
            </div> 
             <div class="row">
                <div class="field-tblproveedor-observaciones_proveedor has-success">
                    <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
                </div>
            </div> 	
        </div>
        <div class="panel-footer text-right">        
            <a href="<?= Url::toRoute("proveedor/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success",]) ?>
        </div>
    </div>
<?php $form->end() ?>


