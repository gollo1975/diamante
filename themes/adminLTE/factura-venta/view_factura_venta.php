<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use app\models\InventarioProductos;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'Vista (Factura punto de venta)';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
</p>    
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute(["factura-venta/view_factura_venta", 'id_factura_punto' => $model->id_factura]),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);?>
<div class="panel panel-success">
    <div class="panel-heading">
       FACTURA DE VENTA
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            <tr style="font-size: 90%;">
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_factura") ?></th>
                <td><?= Html::encode($model->id_factura) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nit_cedula') ?></th>
                <td><?= Html::encode($model->nit_cedula) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_cliente') ?></th>
                <td><?= Html::encode($model->cliente) ?></td>
                 <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'direccion') ?></th>
                 <td><?= Html::encode($model->direccion) ?></td>
            </tr>
        </table>
    </div>
</div>    
<div class="panel panel-success panel-filters">
        <div class="panel-heading">
            Busqueda por codigo de barras
        </div>

        <div class="panel-body" id="entrada_producto">
            <div class="row" >
                <?= $formulario->field($form, 'codigo_producto',['inputOptions' =>['autofocus' => 'autofocus', 'class' => 'form-control']])?>
                <?= $formulario->field($form, 'producto')->widget(Select2::classname(), [
                   'data' => $inventario,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
           </div>
        </div>    
        <div class="panel-footer text-right">
               <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>               
        </div>

</div>
<?php $formulario->end() ?>
<?php
    $form = ActiveForm::begin([
                "method" => "post",                            
            ]);
    ?>
<div class="table-responsive">
    <div class="panel panel-success ">
        <div class="panel-heading">
            Lineas <span class="badge"> <?= count($detalle_factura)?></span>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style ='font-size:90%;'>                
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Codigo</th>                        
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre del producto</th>                        
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Valor unitario</th>                        
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Subtotal</th>
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>% Descto</th>
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. descuento</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Iva</th>
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Total linea</th> 
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                </tr>
            </thead>
            <tbody>
                <?php                    
                foreach ($detalle_factura as $detalle):?>
                <tr style ='font-size:90%;'>
                    <td><?= $detalle->codigo_producto?></td>
                    <td><?= $detalle->producto?></td>
                    <td style="text-align: right";><?= ''.number_format($detalle->cantidad,0)?></td>
                    <td style="text-align: right";><?= ''.number_format($detalle->valor_unitario,0)?></td>
                    <td style="text-align: right";><?= ''.number_format($detalle->subtotal,0)?></td>
                    <td><?= $detalle->porcentaje_descuento?></td>
                    <td style="text-align: right";><?= ''.number_format($detalle->valor_descuento,0)?></td>
                    <td style="text-align: right";><?= ''.number_format($detalle->impuesto,0)?></td>
                    <td style="text-align: right";><?= ''.number_format($detalle->total_linea,0)?></td>
                    <td style= 'width: 25px; height: 25px;'>
                         <a href="<?= Url::toRoute(["factura-venta/eliminar_linea_factura", 'id_factura_punto' => $model->id_factura, 'id_detalle' => $detalle->id_detalle])?>"
                                        <span class='glyphicon glyphicon-trash'></span> </a>  
                    </td>    
                </tr>
                <?php endforeach;?>
            </tbody>
            <tr style="font-size: 90%; background-color:#B9D5CE">
                <td colspan="8"></td>
                <td style="text-align: right;"><b></b></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="7"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>SUBTOTAL:</b></td>
                <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->subtotal_factura,0); ?></b></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="7"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>DSCTO:</b></td>
                <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->descuento,0); ?></b></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="7"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>IMPUESTO (<?= $model->porcentaje_iva?> %) :</b></td>
                <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->impuesto,0); ?></b></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="7"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>RETENCION (<?= $model->porcentaje_rete_fuente?> %) :</b></td>
                <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_retencion,0); ?></b></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="7"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>RETE IVA (<?= $model->porcentaje_rete_iva?> %) :</b></td>
                <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_reteiva,0); ?></b></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="7"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>TOTAL PAGAR:</b></td>
                <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->total_factura,0); ?></b></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
<?php $formulario->end() ?>   