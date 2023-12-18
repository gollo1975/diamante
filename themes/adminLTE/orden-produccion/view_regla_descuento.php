<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'REGLAS Y DESCUENTOS';
$this->params['breadcrumbs'][] = ['label' => 'Reglas y descuentos', 'url' => ['crear_precio_venta']];
$this->params['breadcrumbs'][] = $table->codigo_producto;
?>
<div class="btn-group btn-sm" role="group">    
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['crear_precio_venta'], ['class' => 'btn btn-primary btn-sm']) ?>
 </div>  
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
<div class="panel panel-success">
    <div class="panel-heading">
        SELECCIONE EL PROCESO
    </div>
    <div class="panel-body">
       
          <div class="row">
            <?=  $form->field($model, 'fecha_inicio')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>
            <?=  $form->field($model, 'fecha_final')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'tipo_descuento')->dropdownList(['1' => 'PORCENTAJE', '2' => 'VALORES'], ['prompt' => 'Seleccione...']) ?>
            <?= $form->field($model, 'nuevo_valor')->textInput(['maxlength' => true]) ?>
        </div>
    </div> 
    <div class="panel-footer text-right">
                <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Grabar", ["class" => "btn btn-success btn-sm", 'name' => 'cambiar_posicion']) ?>     
    </div> 
</div>
<?php $form->end() ?>  
