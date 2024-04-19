<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Pais;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

?>

    <?php $form = ActiveForm::begin([
		'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
	'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-4 control-label'],
                    'options' => []
                ],
	]); ?>
<?php
$conEtapa = ArrayHelper::map(app\models\EtapasAuditoria::find()->all(), 'id_etapa', 'concepto');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>CONCEPTO DE ANALISIS</h4>
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'concepto')->textInput(['maxlength' => true]) ?>  					
        </div>

        <div class="row">
           <?= $form->field($model, 'id_etapa')->widget(Select2::classname(), [
                'data' => $conEtapa,
                'options' => ['prompt' => 'Seleccione un registro ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>  
        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("concepto-anaisis/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>