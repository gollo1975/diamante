<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
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
$tipoSolicitud = ArrayHelper::map(app\models\TipoSolicitud::find()->all(), 'id_solicitud', 'descripcion');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>TIPO DE ORDENES</h4>
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'descripcion_orden')->textInput(['maxlength' => true]) ?>  					
        </div>
        <div class="row">
            <?= $form->field($model, 'abreviatura')->textInput(['maxlength' => true]) ?>  					
        </div>
        <div class="row">
             <?= $form->field($model, 'tipo_modulo')->dropdownList(['1' => 'PRODUCCION', '2' => 'INVENTARIOS'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
             <?= $form->field($model, 'id_solicitud')->widget(Select2::classname(), [
                'data' => $tipoSolicitud,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>

        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("tipo-orden-compra/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
