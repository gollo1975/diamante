<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use kartik\select2\Select2;
use kartik\date\DatePicker;
//models
use app\models\Municipios;
use app\models\Departamentos;
use app\models\Clientes;
use app\models\TipoVenta;
//vectores
$departamento = ArrayHelper::map(Departamentos::find()->orderBy('departamento DESC')->all(), 'codigo_departamento', 'departamento');
$municipio = ArrayHelper::map(Municipios::find()->orderBy('municipio DESC')->all(), 'codigo_municipio', 'municipio');
$cliente = Clientes::find()->where(['=','id_tipo_cliente', 5])->orderBy('nombre_completo ASC')->all();
$cliente= ArrayHelper::map($cliente, 'id_cliente', 'clienteCompleto');
if($accesoToken == 1){
        $tipo_venta= ArrayHelper::map(TipoVenta::find()->where(['=','id_tipo_venta', 2])->orderBy('concepto ASC')->all(), 'id_tipo_venta', 'concepto');
}else{
    $tipo_venta= ArrayHelper::map(TipoVenta::find()->where(['=','id_tipo_venta', 3])->orderBy('concepto ASC')->all(), 'id_tipo_venta', 'concepto');  
}    
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!--<h1>Nuevo proveedor</h1>-->
<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
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
        FACTURA DE VENTA
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'id_cliente')->widget(Select2::classname(), [
                   'data' => $cliente,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
            ]);?> 
        </div>
        
       
    </div>    
    <div class="panel-footer text-right">
        <a href="<?= Url::toRoute("factura-venta-punto/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
         <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>        
    </div>
</div>
<?php $form->end() ?>
 