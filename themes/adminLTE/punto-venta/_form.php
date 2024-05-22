<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Departamentos;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Municipio */
/* @var $form yii\widgets\ActiveForm */
?>

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
if($sw == 1){
     $municipio = app\models\Municipios::find()->Where(['=', 'codigo_departamento', $model->codigo_departamento])->all();
     $municipio = ArrayHelper::map($municipio, "codigo_municipio", "municipio");
}            
$depto = ArrayHelper::map(Departamentos::find()->orderBy('departamento ASC')->all(), 'codigo_departamento', 'departamento');
?>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>PUNTOS DE VENTA</h4>
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'nombre_punto')->textInput(['maxlength' => true]) ?>    
            <?= $form->field($model, 'direccion_punto')->textInput(['maxlength' => true]) ?>  					
        </div>
        <div class="row">
             <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>  					
             <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>  					
        </div>  
        <div class="row">
             <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'administrador')->textInput(['maxlength' => true]) ?> 
           
        </div>  
        <?php if($sw == 0){?>
            <div class="row">
                <?= $form->field($model, 'codigo_departamento')->dropDownList($depto, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                    $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
                <?= $form->field($model, 'codigo_municipio')->dropDownList(['prompt' => 'Seleccione...']) ?>
            </div>
        <?php }else{?>
            <div class="row">
            <?= $form->field($model, 'codigo_departamento')->dropDownList($depto, [ 'prompt' => 'Seleccione...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
           <?= $form->field($model, 'codigo_municipio')->dropDownList($municipio, ['prompt' => 'Seleccione...']) ?>
        </div>
        <?php }?>
        <div class="row">
            <?= $form->field($model, 'predeterminado')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
              <?=  $form->field($model, 'fecha_inicio')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                                       'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>            				
        </div>  
    
        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("punto-venta/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
