<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\PeriodoPago;
use app\models\Departamentos;
use app\models\Municipios;
use app\models\SucursalSeguridadSocial;
use kartik\date\DatePicker;
use kartik\select2\Select2;

?>

<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
            'options' => []
        ],
        ]);
?>
<?php
   $periodo = ArrayHelper::map(PeriodoPago::find()->all(), 'id_periodo_pago', 'nombre_periodo');
   $departamento = ArrayHelper::map(Departamentos::find()->all(), 'codigo_departamento', 'departamento');
   $municipio = ArrayHelper::map(Municipios::find()->all(), 'codigo_municipio', 'municipio');
   $sucursalSS = ArrayHelper::map(SucursalSeguridadSocial::find()->all(), 'id_sucursal', 'sucursal');
?>
<body>
<!--<h1>Editar Cliente</h1>-->

<div class="panel panel-success">
    <div class="panel-heading">
        Información Grupo Pago
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, 'grupo_pago')->textInput(['maxlength' => true]) ?>    
            <?= $form->field($model, 'id_periodo_pago')->dropDownList($periodo, ['prompt' => 'Seleccione...']) ?>
        </div>
        <?php if($sw == 0){?>
            <div class="row">
                <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, [ 'prompt' => 'Seleccione una opcion...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
                <?= $form->field($model, 'codigo_municipio')->dropDownList(['prompt' => 'Seleccione...']) ?>
            </div>
        <?php }else{?>
            <div class="row">
                <?= $form->field($model, 'codigo_departamento')->dropDownList($departamento, [ 'prompt' => 'Seleccione una opcion...', 'onchange' => ' $.get( "' . Url::toRoute('proveedor/municipio') . '", { id: $(this).val() } ) .done(function( data ) {
                $( "#' . Html::getInputId($model, 'codigo_municipio', ['required', 'class' => 'select-2']) . '" ).html( data ); });']); ?>
                <?= $form->field($model, 'codigo_municipio')->dropDownList($municipio, ['prompt' => 'Seleccione...']) ?>
            </div>
        <?php }?>
        <div class="row">
             <?= $form->field($model, 'id_sucursal')->dropDownList($sucursalSS, ['prompt' => 'Seleccione sucursal']) ?>
             <?= $form->field($model, 'limite_devengado')->textInput(['maxlength' => true]) ?> 
        </div>
        <div class="row">
            <?=
            $form->field($model, 'ultimo_pago_nomina')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]])
            ?>       
            
             <?=
            $form->field($model, 'ultimo_pago_prima')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]])
            ?>       
        
        </div>
        
        <div class="row">
              <?=
            $form->field($model, 'ultimo_pago_cesantia')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha '],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]])
           ?>       
            <?= $form->field($model, 'dias_pago')->textInput(['maxlength' => true]) ?>
        </div>	
        <?php if($sw == 1){?>
            <div class="row" col>
                 <?= $form->field($model, 'estado')->dropdownList(['0' => 'SI', '1' => 'NO']) ?>
                <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
            </div>
        <?php }else{ ?>
             <div class="row" col>
                <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
            </div>
        <?php }?>
        <div class="panel-footer text-right">                
            <a href="<?= Url::toRoute("grupo-pago/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>