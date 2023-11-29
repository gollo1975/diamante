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
use app\models\ConfiguracionIva;
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
$presentacion = ArrayHelper::map(app\models\PresentacionProducto::find()->where(['=','id_grupo', $model->id_grupo])->orderBy ('descripcion ASC')->all(), 'id_presentacion', 'descripcion');
$grupo = ArrayHelper::map(GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');
$porcentaje = ArrayHelper::map(ConfiguracionIva::find()->orderBy ('valor_iva DESC')->all(), 'valor_iva', 'valor_iva');
$provedor = ArrayHelper::map(Proveedor::find()->orderBy('nombre_completo ASC')->all(), 'id_proveedor', 'nombre_completo');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        INVENTARIO PRODUCTO TERMINADO
    </div>
    
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'codigo_producto')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'nombre_producto')->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="row">
            <?=  $form->field($model, 'fecha_proceso')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>
            <?=  $form->field($model, 'fecha_vencimiento')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>
        </div>
        
        <div class="row">
            <?php if ($IdToken == 0){?>
                <?= $form->field($model, 'id_grupo')->dropDownList($grupo,['prompt'=>'Seleccione el grupo...', 'onchange'=>' $.get( "'.Url::toRoute('inventario-productos/presentacion').'", { id: $(this).val() } ) .done(function( data ) {
                $( "#'.Html::getInputId($model, 'id_presentacion',['required', 'class' => 'select-2']).'" ).html( data ); });']); ?>
                 <?= $form->field($model, 'id_presentacion')->dropDownList(['prompt' => 'Seleccione...']) ?>
            <?php }else{ ?>
                   <?= $form->field($model, 'id_grupo')->dropDownList($grupo,['prompt'=>'Seleccione el grupo...', 'onchange'=>' $.get( "'.Url::toRoute('inventario-productos/presentacion').'", { id: $(this).val() } ) .done(function( data ) {
                 $( "#'.Html::getInputId($model, 'id_presentacion',['required', 'class' => 'select-2']).'" ).html( data ); });']); ?>
                  <?= $form->field($model, 'id_presentacion')->dropDownList($presentacion, ['prompt' => 'Seleccione...']) ?>
            <?php }?>
        </div>    
        <div class="row">
            <?= $form->field($model, 'unidades_entradas')->textInput(['maxlength' => true]) ?>
             <?= $form->field($model, 'costo_unitario')->textInput(['maxlength' => true]) ?>
        </div>        
        <div class="row">
            <?= $form->field($model, 'aplica_iva')->dropDownList(['0' => 'SI', '1' => 'NO'],['onchange' => 'mostrarcampo()', 'id' => 'aplica_iva'])?>
            <div id="porcentaje_iva" style="display:block"> <?= $form->field($model, 'porcentaje_iva')->dropDownList($porcentaje, ['prompt' => 'Seleccione...']) ?></div>
        </div>                
        <div class="row">
              <?= $form->field($model, 'inventario_inicial')->dropDownList(['0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione una opcion...']) ?>
             <?= $form->field($model, 'aplica_inventario')->dropDownList(['0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione una opcion...']) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'venta_publico')->dropDownList(['0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione una opcion...']) ?>
            <?= $form->field($model, 'aplica_regla_comercial')->dropDownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione una opcion...']) ?>
        </div> 
        <div class="row">
            <?= $form->field($model, 'id_proveedor')->widget(Select2::classname(), [
                   'data' => $provedor,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
             <?= $form->field($model, 'descripcion_producto', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
        </div> 
        <div class="panel-footer text-right">			
            <a href="<?= Url::toRoute("inventario-productos/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
        </div>
    </div>
</div>
<?php $form->end() ?>     

<script type="text/javascript">
    function mostrarcampo(){
        let aplica_iva = document.getElementById('aplica_iva').value;
        if(aplica_iva === '0'){
          porcentaje_iva.style.display = "block";
        } else {
             porcentaje_iva.style.display = "none";
            
           
        }
    }
</script>    
