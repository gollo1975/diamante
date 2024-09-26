<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Departamentos;
use app\models\Municipios;
use app\models\ResolucionDian;
use app\models\NaturalezaSociedad;

/* @var $this yii\web\View */
/* @var $model app\models\Parametros */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Matricula';
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
$resoluciones = ArrayHelper::map(ResolucionDian::find()->where(['=','estado_resolucion', 0])->all(), 'id_resolucion', 'numero_resolucion');
$departamento = ArrayHelper::map(Departamentos::find()->all(), 'codigo_departamento', 'departamento');
$municipio = ArrayHelper::map(Municipios::find()->all(), 'codigo_municipio', 'municipio');
$naturaleza = ArrayHelper::map(NaturalezaSociedad::find()->all(), 'id_naturaleza', 'naturaleza');
$banco = ArrayHelper::map(\app\models\EntidadBancarias::find()->all(), 'codigo_banco', 'entidad_bancaria');
$tipoR = ArrayHelper::map(\app\models\TipoRegimen::find()->all(), 'id_tipo_regimen', 'regimen');
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
            <?= $form->field($model, 'sugiere_retencion')->dropdownList(['0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione...']) ?>
        </div>  
        <div class="row">                        
            <?= $form->field($model, 'id_naturaleza')->dropDownList($naturaleza, ['prompt' => 'Seleccione una resolucion...']) ?>
             <?= $form->field($model, 'id_tipo_regimen')->dropDownList($tipoR, ['prompt' => 'Seleccione...']) ?>
        </div>  
        <div class="row">   
             <?= $form->field($model, 'codigo_banco')->dropDownList($banco, ['prompt' => 'Seleccione una resolucion...']) ?>
            <?= $form->field($model, 'porcentaje_reteiva')->textInput(['maxlength' => true]) ?>
        </div> 
        <div class="row">
            <?= $form->field($model, 'calificacion_proveedor')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'aplica_inventario_incompleto')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">           
            <?= $form->field($model, 'declaracion', ['template' => '{label}<div class="col-sm-10  form-group">{input}{error}</div>'])->textarea(['rows' => 3]) ?>
        </div> 
        <div class="row">           
            <?= $form->field($model, 'presentacion', ['template' => '{label}<div class="col-sm-10  form-group">{input}{error}</div>'])->textarea(['rows' => 3, 'size'=> 100]) ?>
        </div> 
        <div class="panel-footer text-right">			                        
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success",]) ?>
       
    </div>
</div>
<?php ActiveForm::end(); ?>
