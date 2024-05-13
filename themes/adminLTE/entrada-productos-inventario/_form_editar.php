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
//model
use app\models\Proveedor;
?>

<?php
$form = ActiveForm::begin([
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
$proveedor = ArrayHelper::map(Proveedor::find()->orderBy ('nombre_completo ASC')->all(), 'id_proveedor', 'nombre_completo');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        ENTRADA PRODUCTOS AL INVENTARIO
    </div>
    
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'id_proveedor')->dropDownList($proveedor,['prompt'=>'Seleccione un proveedor...', 'onchange'=>' $.get( "'.Url::toRoute('entrada-producto-terminado/ordencompra').'", { id: $(this).val() } ) .done(function( data ) {
            $( "#'.Html::getInputId($model, 'id_orden_compra',['required', 'class' => 'select-2']).'" ).html( data ); });']); ?>
            <?= $form->field($model, 'id_orden_compra')->dropDownList($ordenes, ['prompt' => 'Seleccione...']) ?>
           
        </div>    
        <div class="row">
            <?=  $form->field($model, 'fecha_proceso')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>
             <?= $form->field($model, 'numero_soporte')->textInput(['maxlength' => true, 'size' => '15']) ?>
             
        </div>
         <div class="row">
           <?= $form->field($model, 'tipo_entrada')->dropdownList(['1' => 'ORDEN DE COMPRA', '2' => 'MANUAL'], ['prompt' => 'Seleccione...', 'onchange' => 'fpago()', 'id' => 'forma_pago']) ?>
            <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
        </div>    
        <div class="panel-footer text-right">			
            <a href="<?= Url::toRoute("entrada-productos-inventario/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
        </div>
    </div>
</div>
<?php $form->end() ?>     
