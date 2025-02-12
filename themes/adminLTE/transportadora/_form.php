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
use app\models\Departamentos;
use app\models\TipoDocumento;

$this->title = 'Nueva transportadora';
$this->params['breadcrumbs'][] = ['label' => 'Transportadora', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                    'labelOptions' => ['class' => 'col-sm-4 control-label'],
                    'options' => []
                ],
]);
?>

<?php
$departamento = ArrayHelper::map(Departamentos::find()->orderBy('departamento ASC')->all(), 'codigo_departamento', 'departamento');
$tipodocumento = ArrayHelper::map(TipoDocumento::find()->where(['=','proceso_proveedor', 1])->all(), 'id_tipo_documento', 'documento');
?>
    <div class="panel panel-success">
        <div class="panel-heading">
            TRANSPORTADORAS
        </div>
        <div class="panel-body">
            <div class="row">
                <?= $form->field($model, 'tipo_documento')->dropDownList($tipodocumento, ['prompt' => 'Seleccione...', 'onchange' => 'mostrar2()', 'id' => 'id_tipo_documento']) ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'nit_cedula')->input('text', ['id' => 'nit_cedula', 'onchange' => 'calcularDigitoVerificacion()']) ?>
                <?= Html::textInput('dv', $model->dv, ['id' => 'dv', 'aria-required' => true, 'aria-invalid' => 'false', 'maxlength' => 1, 'class' => 'form-control', 'placeholder' => 'dv', 'style' => 'width:50px', 'readonly' => true]) ?>       
            </div>
            <div class="row">
               <?= $form->field($model, 'razon_social')->input("text") ?>
            </div>
             <div class="row">
                <?= $form->field($model, 'direccion')->input("text", ["maxlength" => 50]) ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'email_transportadora')->input("text",['maxlength' => 50]) ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'celular')->input("text") ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'telefono')->input("text") ?>
            </div>
            
            <div class="row">
                <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, ['prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
               $( "#' . Html::getInputId($model, 'codigo_municipio', ['required']) . '" ).html( data ); });']); ?>
            </div>
            <div class="row">
               <?= $form->field($model, 'codigo_municipio')->dropDownList(['prompt' => 'Seleccione...']) ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'contacto')->input("text", ["maxlength" => 40]) ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'celular_contacto')->input("text", ["maxlength" => 15]) ?>
            </div>
        </div>    
        <div class="panel-footer text-right">        
            <a href="<?= Url::toRoute("transportadora/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success",]) ?>
        </div>
    </div>
<?php $form->end() ?>
</body>


