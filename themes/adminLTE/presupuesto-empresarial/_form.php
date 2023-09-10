<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\AreaEmpresa;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
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
$area = ArrayHelper::map(AreaEmpresa::find()->orderBy('descripcion ASC')->all(), 'id_area', 'descripcion');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>PRESUPUESTO </h4>
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>  					
        </div>
        <div class="row">
            <?= $form->field($model, 'valor_presupuesto')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="row">
           <?= $form->field($model, 'id_area')->widget(Select2::classname(), [
                'data' => $area,
                'options' => ['prompt' => 'Seleccione un registro ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>  
        <div class="row">
             <?= $form->field($model, 'año')->textInput(['maxlength' => true]) ?>  					
        </div>  
        <div class="row">
            <?= $form->field($model, 'fecha_inicio')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                            'value' => date('d-M-Y', strtotime('+2 days')),
                            'options' => ['placeholder' => 'Seleccione una fecha ...'],
                            'pluginOptions' => [
                                'format' => 'yyyy-m-d',
                                'todayHighlight' => true]])
            ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'fecha_corte')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                            'value' => date('d-M-Y', strtotime('+2 days')),
                            'options' => ['placeholder' => 'Seleccione una fecha ...'],
                            'pluginOptions' => [
                                'format' => 'yyyy-m-d',
                                'todayHighlight' => true]])
            ?>
        </div>
        <?php if($sw == 1 ){?>
            <div class="row">
                <?= $form->field($model, 'estado')->dropdownList(['0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione...']) ?>
            </div>
        <?php } ?>
        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("presupuesto-empresarial/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

