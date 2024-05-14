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
use app\models\ConfiguracionIva;
use app\models\Proveedor;
use app\models\PuntoVenta;
use app\models\Marca;
use app\models\Categoria;
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
$conPunto = ArrayHelper::map(PuntoVenta::find()->where(['=','predeterminado', 1])->all(), 'id_punto', 'nombre_punto');
$porcentaje = ArrayHelper::map(ConfiguracionIva::find()->orderBy ('valor_iva DESC')->all(), 'valor_iva', 'valor_iva');
$provedor = ArrayHelper::map(Proveedor::find()->orderBy('nombre_completo ASC')->all(), 'id_proveedor', 'nombre_completo');
$marca = ArrayHelper::map(Marca::find()->orderBy('marca ASC')->all(), 'id_marca', 'marca');
$categoria = ArrayHelper::map(Categoria::find()->orderBy('categoria ASC')->all(), 'id_categoria', 'categoria');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        INVENTARIO PUNTO DE VENTA
    </div>
    
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'codigo_producto')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'nombre_producto')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="row">
            <?=  $form->field($model, 'fecha_proceso')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>
            <?= $form->field($model, 'id_proveedor')->widget(Select2::classname(), [
                   'data' => $provedor,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
            
        </div>
        
        <div class="row">
            
        </div>    
        <div class="row">
            <?= $form->field($model, 'costo_unitario')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'venta_publico')->dropDownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione una opcion...']) ?>
        </div>  
        <div class="row">
            <?php if($sw == 1){?>
                 <?= $form->field($model, 'iva_incluido')->dropDownList(['0' => 'NO', '1' => 'SI'],['onchange' => 'validar()', 'id' => 'iva_incluido'])?>
                 <div id="porcentaje_iva" style="display:block"> <?= $form->field($model, 'porcentaje_iva')->dropDownList($porcentaje, ['prompt' => 'Seleccione...']) ?></div>
            <?php }else{?>
                <?= $form->field($model, 'iva_incluido')->dropDownList(['0' => 'NO', '1' => 'SI'],['onchange' => 'mostrarcampo()', 'id' => 'iva_incluido'])?>
                <div id="porcentaje_iva" style="display:none"> <?= $form->field($model, 'porcentaje_iva')->dropDownList($porcentaje, ['prompt' => 'Seleccione...']) ?></div>
             <?php }?>
        </div>        
        <div class="row">

            <?= $form->field($model, 'inventario_inicial')->dropDownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione una opcion...']) ?>
            <?= $form->field($model, 'aplica_inventario')->dropDownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione una opcion...']) ?>
               
        </div>
         <div class="row">
            <?= $form->field($model, 'id_marca')->widget(Select2::classname(), [
                   'data' => $marca,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
             <?= $form->field($model, 'id_categoria')->widget(Select2::classname(), [
                   'data' => $categoria,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
            
        </div>
        <?php if($confi->aplica_talla_color == 0){?>
            <div class="row">
                <?= $form->field($model, 'stock_unidades')->textInput(['maxlength' => true, 'required' => true]) ?>
                <?= $form->field($model, 'descripcion_producto', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
            </div> 
        <?php }else{?>
            <div class="row">
                <?= $form->field($model, 'descripcion_producto', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
            </div> 
        <?php }?>
        <div class="panel-footer text-right">			
            <a href="<?= Url::toRoute("inventario-punto-venta/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
        </div>
    </div>
</div>
<?php $form->end() ?>     

<script type="text/javascript">
    function mostrarcampo(){
        let aplica_iva = document.getElementById('iva_incluido').value;
        if(aplica_iva === '0'){
          porcentaje_iva.style.display = "none";
        } else {
             porcentaje_iva.style.display = "block";
            
           
        }
    }
    
</script>    
<script type="text/javascript">
       function validar(){
        let aplica = document.getElementById('iva_incluido').value;
        if(aplica === '1'){
          porcentaje_iva.style.display = "block";
        } else {
             porcentaje_iva.style.display = "none";
            
           
        }
    }
</script>    

