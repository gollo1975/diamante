<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use kartik\select2\Select2;
use kartik\date\DatePicker;
//models

use app\models\Clientes;

//vectores
$cliente = Clientes::find()->where(['=','id_tipo_cliente', 5])->orderBy('nombre_completo ASC')->all();
$cliente= ArrayHelper::map($cliente, 'id_cliente', 'clienteCompleto');
?>

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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<div class="panel panel-success">
    <div class="panel-heading">
        REMISIONES
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'id_cliente')->widget(Select2::classname(), [
                   'data' => $cliente,
                   'options' => ['prompt' => 'Seleccione...','required' => true],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
            ]);?> 
        </div>
       <div class="row">
                <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
          
        </div>    
       
    </div>    
    <div class="panel-footer text-right">
        <a href="<?= Url::toRoute("remisiones/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
         <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>        
    </div>
</div>
<?php $form->end() ?>
 