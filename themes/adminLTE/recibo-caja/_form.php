<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
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
                    'template' => '{label}<div class="col-sm-6 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-3 control-label'],
                    'options' => []
                ],
	]); ?>
<?php
$tipo = ArrayHelper::map(\app\models\TipoReciboCaja::find()->all(), 'id_tipo', 'concepto');
$banco = ArrayHelper::map(\app\models\EntidadBancarias::find()->where(['=','convenio_empresa', 1])->all(), 'codigo_banco', 'entidad_bancaria');
$cliente = ArrayHelper::map(\app\models\Clientes::find()->where(['=','id_agente', $agente])->orderBy('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
$cliente_admon = ArrayHelper::map(\app\models\Clientes::find()->where(['=','estado_cliente', 0])->orderBy('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>RECIBO DE CAJA</h4>
    </div>
    <div class="panel-body">  
       <div class="row">
           <?php if($agente > 0){?>
           
                <?= $form->field($model, 'id_cliente')->widget(Select2::classname(), [
                   'data' => $cliente,
                   'options' => ['prompt' => 'Seleccione un registro ...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?>
           <?php }else{?>
               <?= $form->field($model, 'id_cliente')->widget(Select2::classname(), [
                   'data' => $cliente_admon,
                   'options' => ['prompt' => 'Seleccione un registro ...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?>
           <?php }?>
        </div>  
        <div class="row">
             <?= $form->field($model, 'codigo_banco')->widget(Select2::classname(), [
                'data' => $banco,
                'options' => ['prompt' => 'Seleccione un registro ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="row">
             <?= $form->field($model, 'id_tipo')->widget(Select2::classname(), [
                'data' => $tipo,
                'options' => ['prompt' => 'Seleccione un registro ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="row">
             <?= $form->field($model, 'fecha_pago')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
        </div>

        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("recibo-caja/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>