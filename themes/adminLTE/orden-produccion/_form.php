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
use app\models\GrupoProducto;
use app\models\Almacen;
use app\models\TipoProcesoProduccion;

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
$grupo = ArrayHelper::map(GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');
$almacen = ArrayHelper::map(Almacen::find()->orderBy ('predeterminado DESC')->all(), 'id_almacen', 'almacen');
$conProcesoProduccion = ArrayHelper::map(TipoProcesoProduccion::find()->orderBy ('nombre_proceso ASC')->all(), 'id_proceso_produccion', 'nombre_proceso');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>ORDENES DE PRODUCCION</h4>
    </div>
    
    <div class="panel-body">
        <?php if($sw == 0){?>
            <div class="row">
                <?= $form->field($model, 'id_grupo')->dropDownList($grupo,['prompt'=>'Seleccione...', 'onchange'=>' $.get( "'.Url::toRoute('orden-produccion/cargarproducto').'", { id: $(this).val() } ) .done(function( data ) {
                $( "#'.Html::getInputId($model, 'id_producto',['required', 'class' => 'select-2']).'" ).html( data ); });']); ?>
                <?= $form->field($model, 'id_producto')->dropDownList(['prompt' => 'Seleccione...']) ?>
            </div> 
        <?php }else{?>
            <div class="row">
                <?= $form->field($model, 'id_grupo')->dropDownList($grupo,['prompt'=>'Seleccione...', 'onchange'=>' $.get( "'.Url::toRoute('orden-produccion/cargarproducto').'", { id: $(this).val() } ) .done(function( data ) {
                       $( "#'.Html::getInputId($model, 'id_producto',['required', 'class' => 'select-2']).'" ).html( data ); });']); ?>
                        <?= $form->field($model, 'id_producto')->dropDownList($ConProducto, ['prompt' => 'Seleccione...']) ?>
            </div>   
        <?php } ?>          

        <div class="row">
            <?= $form->field($model, 'fecha_proceso')->textInput(['maxlength' => true, 'readonly' => true]) ?> 
             <?=  $form->field($model, 'fecha_entrega')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>
        </div>
        
        <div class="row">
            <?= $form->field($model, 'tipo_orden')->dropdownList(['0' => 'PORTAFOLIO ACTUAL', '1' => 'NUEVO PRODUCTO'], ['prompt' => 'Seleccione...']) ?>
             <?= $form->field($model, 'id_proceso_produccion')->widget(Select2::classname(), [
                'data' => $conProcesoProduccion,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>    
         <div class="row">
             <?= $form->field($model, 'tamano_lote')->textInput(['maxlength' => true]) ?> 
               <?= $form->field($model, 'responsable')->input(['text', 'maxlength' => true]) ?>
        </div>
        <div class = "row">
             <?= $form->field($model, 'id_almacen')->widget(Select2::classname(), [
                'data' => $almacen,
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2, 'maxlength' => true, 'size' => '100']) ?>
        </div>
            
        <div class="panel-footer text-right">			
            <a href="<?= Url::toRoute("orden-produccion/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
        </div>
    </div>
</div>
<?php $form->end() ?>     
