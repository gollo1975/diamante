<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = 'Parametros de configuracion';
$this->params['breadcrumbs'][] = ['label' => 'Parametros', 'url' => ['parametros']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matricula-empresa-vista">
    <?php
    $form = ActiveForm::begin([
                "method" => "post",
                'id' => 'formulario',
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,
                'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
                'fieldConfig' => [
                'template' => '{label}<div class="col-sm-3 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'options' => []
            ],
            ]);
    ?>
       
    <div class="panel panel-success">
        <div class="panel-heading">
            Configuracion del modulo
        </div>
        <div class="panel-body">
            <div class="checkbox checkbox-success" align ="left">
                    <?= $form->field($model, 'sugiere_retencion')->checkBox(['label' => 'Sugiere retencion','1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'sugiere_retencion']) ?>
                    <?= $form->field($model, 'aplica_punto_venta')->checkBox(['label' => 'Maneja punto de venta',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'aplica_punto_venta']) ?>
                    <?= $form->field($model, 'aplica_factura_produccion')->checkBox(['label' => 'Aplica factura a produccion',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'aplica_factura_produccion']) ?>
                    <?= $form->field($model, 'aplica_talla_color')->checkBox(['label' => 'Aplica talla y color',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'aplica_talla_color']) ?>
            </div>
            <div class="checkbox checkbox-success" align ="left">
                    <?= $form->field($model, 'aplica_fabricante')->checkBox(['label' => 'Aplica a fabricante','1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'aplica_fabricante']) ?>
                    <?= $form->field($model, 'recibo_caja_automatico')->checkBox(['label' => 'Recibo de caja automatico',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'recibo_caja_automatico']) ?>
                    <?= $form->field($model, 'modulo_completo')->checkBox(['label' => 'Aplica modulo completo',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'modulo_completo']) ?>
                    <?= $form->field($model, 'aplica_inventario_incompleto')->checkBox(['label' => 'Aplica inventario completo',''=>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'aplica_inventario_incompleto']) ?>
            </div>
            <div class="checkbox checkbox-success" align ="left">
                    <?= $form->field($model, 'inventario_enlinea')->checkBox(['label' => 'Procesar inventario sin existencia','1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:5px;', 'id'=>'inventario_enlinea']) ?>
                   
            </div>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
           Impuestos tributarios
        </div>
        <div class="panel-body">
            <div class="row">          
                <?= $form->field($model, 'porcentaje_reteiva')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'mensaje_normativo1')->textInput(['maxlength' => true, 'size' => 70]) ?>
                
            </div> 
             <div class="row">          
                <?= $form->field($model, 'mensaje_normativo2')->textInput(['maxlength' => true, 'size' => 70]) ?>
                <?= $form->field($model, 'mensaje_normativo3', ['template' => '{label}<div class="col-sm-3  form-group">{input}{error}</div>'])->textarea(['rows' => 2, 'size'=> 85]) ?>
                
            </div>
            <div class="row">          
              <?= $form->field($model, 'email_respuesta', ['template' => '{label}<div class="col-sm-3  form-group">{input}{error}</div>'])->textarea(['rows' => 2, 'size'=> 80]) ?>
                
            </div>
        </div>
    </div>   
    <div class="panel-footer text-right">			
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
    </div>
    <?php $form->end() ?> 
</div>


