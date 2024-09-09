<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\ConfiguracionIva;
use app\models\TipoSolicitud;
/* @var $this yii\web\View */
/* @var $model app\models\Municipio */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin([
		'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
	'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-3 control-label'],
                    'options' => []
                ],
	]); ?>
<?php
$iva = ArrayHelper::map(ConfiguracionIva::find()->orderBy ('valor_iva DESC')->all(), 'id_iva', 'valor_iva');
$conSolicitud = ArrayHelper::map(TipoSolicitud::find()->orderBy ('descripcion ASC')->all(), 'id_solicitud', 'descripcion');
$conMedida = ArrayHelper::map(app\models\MedidaMateriaPrima::find()->orderBy ('descripcion ASC')->all(), 'id_medida', 'descripcion');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>INSUMO</h4>
    </div>
    <div class="panel-body">  
        <?php if($sw == 0){ ?>     
            <div class="row">
                <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>  					
            </div>
        <?php }else{?>
            <div class="row">
                <?= $form->field($model, 'codigo')->textInput(['maxlength' => true, 'readonly' => true]) ?>  					
            </div>
        <?php }?>
        <div class="row">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>  					
        </div>
        <div class="row">
            <?= $form->field($model, 'id_solicitud')->widget(Select2::classname(), [
                'data' => $conSolicitud,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>    
         <div class="row">
            <?= $form->field($model, 'id_medida')->widget(Select2::classname(), [
                'data' => $conMedida,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>        
        <div class="row">
            <?= $form->field($model, 'id_iva')->widget(Select2::classname(), [
                'data' => $iva,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>     
        <div class="row">
            <?= $form->field($model, 'convertir_gramo')->dropDownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione una opcion...']) ?>
        </div>
         <div class="row">
            <?= $form->field($model, 'aplica_inventario')->dropDownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione una opcion...']) ?>
        </div>
         <div class="row">
            <?= $form->field($model, 'inventario_inicial')->dropDownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione una opcion...']) ?>
        </div>
         <div class="row checkbox checkbox-success" align ="center">
                <?= $form->field($model, 'codificar')->checkbox(['label' => 'Codificar este insumos en materia prima', '1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:10px;' , 'id'=>'codificar']) ?>
        </div>
        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("items/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>