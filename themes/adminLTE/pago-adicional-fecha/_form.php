<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TipoRecibo */
/* @var $form yii\widgets\ActiveForm */
?>



<?php
$form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]);
?>


<div class="panel panel-success">
    <div class="panel-heading">
        Pago adicional por fecha
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?=
            $form->field($model, 'fecha_corte')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]])
            ?>         
        </div>
        <div class="row">
            <?= $form->field($model, 'detalle')->textInput(['maxlength' => true]) ?>
        </div>
        <?php if($sw == 1){?>
            <div class="row">
               <?= $form->field($model, 'estado_registro')->dropdownList(['0' => 'ABIERTO', '1' => 'CERRADO']) ?>
            </div>
        <?php }?>
        <div class="panel-footer text-right">                
            <a href="<?= Url::toRoute("pago-adicional-fecha/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
