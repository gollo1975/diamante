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
        ENTRADA MATERIAS PRIMAS
    </div>
    
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'id_proveedor')->dropDownList($proveedor,['prompt'=>'Seleccione un proveedor...', 'onchange'=>' $.get( "'.Url::toRoute('entrada-materia-prima/ordencompra').'", { id: $(this).val() } ) .done(function( data ) {
            $( "#'.Html::getInputId($model, 'id_orden_compra',['required', 'class' => 'select-2']).'" ).html( data ); });']); ?>
            <?= $form->field($model, 'id_orden_compra')->dropDownList(['prompt' => 'Seleccione...']) ?>
        </div>    
        <div class="row">
             <?= $form->field($model, 'fecha_proceso')->textInput(['maxlength' => true, 'readonly' => 'true']) ?>
             <?= $form->field($model, 'numero_soporte')->textInput(['maxlength' => true, 'size' => '15']) ?>
             
        </div>
         <div class="row">
           
            <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
        </div>    
        <div class="panel-footer text-right">			
            <a href="<?= Url::toRoute("entrada-materia-prima/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
        </div>
    </div>
</div>
<?php $form->end() ?>     
