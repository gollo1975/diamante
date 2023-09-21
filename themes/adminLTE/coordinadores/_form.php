<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\TipoDocumento;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Municipio */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin([
		'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
	'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],
	]); ?>
<?php
$tipo = ArrayHelper::map(TipoDocumento::find()->where(['=','proceso_nomina', 1])->orderBy('tipo_documento ASC')->all(), 'id_tipo_documento', 'tipo_documento');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>MUNICIPIOS</h4>
    </div>
    <div class="panel-body">        														   		
        <div class="row">
             <?= $form->field($model, 'id_tipo_documento')->widget(Select2::classname(), [
                'data' => $tipo,
                'options' => ['prompt' => 'Seleccione un registro ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $form->field($model, 'documento')->textInput(['maxlength' => true]) ?>    
        </div>
        <div class="row">
             <?= $form->field($model, 'nombres')->textInput(['maxlength' => true]) ?>  
            <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>  					
        </div>
        <div class="row">
             <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>  
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>  					
        </div>

        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("coordinadores/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>


