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
use app\models\MedidaMateriaPrima;
use app\models\ConfiguracionIva;

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
$medida = ArrayHelper::map(MedidaMateriaPrima::find()->orderBy ('descripcion ASC')->all(), 'id_medida', 'descripcion');
$porcentaje = ArrayHelper::map(ConfiguracionIva::find()->orderBy ('valor_iva DESC')->all(), 'valor_iva', 'valor_iva');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        MATERIAS PRIMAS
    </div>
    
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'codigo_materia_prima')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'materia_prima')->textInput(['maxlength' => true, 'size' => '30']) ?>
        </div>
        <div class="row">
            <?=  $form->field($model, 'fecha_entrada')->widget(DatePicker::className(), ['name' => 'check_issue_date',
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
            <?= $form->field($model, 'id_medida')->widget(Select2::classname(), [
                'data' => $medida,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $form->field($model, 'valor_unidad')->textInput(['maxlength' => true]) ?>
        </div>        
        <div class="row">
            <?= $form->field($model, 'aplica_iva')->dropDownList(['1' => 'SI', '0' => 'NO'],['onchange' => 'mostrarcampo()', 'id' => 'aplica_iva'])?>
            <div id="porcentaje_iva" style="display:block"> <?= $form->field($model, 'porcentaje_iva')->dropDownList($porcentaje, ['prompt' => 'Seleccione una opcion...']) ?></div>
        </div>                
        <div class="row">
            <?= $form->field($model, 'total_cantidad')->textInput(['maxlength' => true]) ?>
             <?= $form->field($model, 'aplica_inventario')->dropDownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione una opcion...']) ?>
        </div>
        <div class="row">
             <?= $form->field($model, 'inventario_inicial')->dropDownList(['0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione una opcion...']) ?>
            <?= $form->field($model, 'descripcion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
        </div>    
        <div class="panel-footer text-right">			
            <a href="<?= Url::toRoute("materia-primas/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
        </div>
    </div>
</div>
<?php $form->end() ?>     

<script type="text/javascript">
    function mostrarcampo(){
        let aplica_iva = document.getElementById('aplica_iva').value;
        if(aplica_iva === '1'){
          porcentaje_iva.style.display = "block";
        } else {
             porcentaje_iva.style.display = "none";
            
           
        }
    }
</script>    
