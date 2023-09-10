<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Departamentos;
use app\models\Municipios;
use app\models\Resoluciones;

/* @var $this yii\web\View */
/* @var $model app\models\Parametros */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php
$form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'options' => []
            ],
        ]);
?>
<?php
$resoluciones = ArrayHelper::map(Resoluciones::find()->all(), 'id_resolucion', 'resolucion');
$departamento = ArrayHelper::map(Departamentos::find()->all(), 'codigo_departamento', 'departamento');
$municipio = ArrayHelper::map(Municipios::find()->all(), 'codigo_municipio', 'municipio');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        DATOS DE LA EMPRESA
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'nit_empresa')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'dv')->textInput(['maxlength' => true]) ?>
        </div>                
        <div class="row">
            <?= $form->field($model, 'razon_social')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'primer_nombre')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'segundo_nombre')->textInput(['maxlength' => true]) ?>
        </div>        
        <div class="row">
            <?= $form->field($model, 'primer_apellido')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'segundo_apellido')->textInput(['maxlength' => true]) ?>
        </div>        
        <div class="row">
            <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
        </div>  
        <div class="row">
            <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>  
        <div class="row">
            <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('matricula-empresa/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
            <?= $form->field($model, 'codigo_municipio')->dropDownList($municipio, ['prompt' => 'Seleccione...']) ?>
        </div>                
        <div class="row">
            <?= $form->field($model, 'documento_representante_legal')->textInput(['maxlength' => true]) ?> 
            <?= $form->field($model, 'representante_legal')->textInput(['maxlength' => true]) ?> 
        </div>
        <div class="row">
            <?= $form->field($model, 'nombre_sistema')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'pagina_web')->textInput(['maxlength' => true]) ?>    
        </div>    
        <div class="row">                        
            <?= $form->field($model, 'id_resolucion')->dropDownList($resoluciones, ['prompt' => 'Seleccione una resolucion...']) ?>
        </div>  
        <div class="panel-footer text-right">			                        
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success",]) ?>
       
    </div>
</div>
<?php ActiveForm::end(); ?>
