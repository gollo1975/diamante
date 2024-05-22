<?php

//modelos

//clase
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;

/* @var $this yii\web\View */
/* @var $model app\models\Ordenproduccion */

$this->title = 'DETALLE DE LA VENTA';
$this->params['breadcrumbs'][] = ['label' => 'Factura de venta', 'url' => ['search_maestro_factura']];
$this->params['breadcrumbs'][] = $model->id_factura;
$view = 'factura-venta';
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<?php
    //$remision = Remision::find()->where(['=', 'idordenproduccion', $model->idordenproduccion])->one();
?>

<div class="factura-venta-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_maestro_factura'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?=  Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_factura_venta', 'id_factura_punto' => $model->id_factura], ['class' => 'btn btn-default btn-sm']);?>            
       
    </p>  
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
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_factura') ?></th>
                    <td><?= Html::encode($model->numero_factura) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_venta') ?></th>
                    <td><?= Html::encode($model->tipoVenta->concepto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'telefono_cliente') ?></th>
                    <td><?= Html::encode($model->telefono_cliente) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Municipio') ?></th>
                    <td><?= Html::encode($model->clienteFactura->codigoDepartamento->departamento)?> - <?= Html::encode($model->clienteFactura->codigoMunicipio->municipio)?></td>
                </tr>
                <tr style="font-size: 90%;">
                   
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_vencimiento') ?></th>
                    <td><?= Html::encode($model->fecha_vencimiento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_generada') ?></th>
                    <td><?= Html::encode($model->fecha_generada) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>                    
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'forma_pago') ?></th>
                    <td><?= Html::encode($model->formaPago) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'plazo_pago')?></th>
                    <td><?= Html::encode($model->plazo_pago) ?> Dias</td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_factura')?></th>
                    <td><?= Html::encode($model->tipoFactura->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Saldo') ?></th>
                    <td style="text-align: right"><?= Html::encode('$ '.number_format($model->saldo_factura,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Estado') ?>:</th>
                    <td><?= Html::encode($model->estadoFactura) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="6"><?= Html::encode($model->observacion) ?></td>
                </tr>
              
            </table>
        </div>
    </div>
     <?php $form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
    ],
    ]);?>
    <!-- comienza los tabs -->
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#detallefactura" aria-controls="detallefactura" role="tab" data-toggle="tab">Detalle factura <span class="badge"><?= count($detalle_factura) ?></span></a></li>
            <li role="presentation"><a href="#tallascolores" aria-controls="tallascolores" role="tab" data-toggle="tab">Tallas y colores <span class="badge"><?= count($talla_color) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="detallefactura">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col"  style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Descripcion producto</th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Cantidad</th>       
                                             <th scope="col"  style='background-color:#B9D5CE;'>Vr. unitario</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Subtotal</th>                        
                                            <th scope="col"  style='background-color:#B9D5CE; width: 12%'>Impuesto</th>  
                                            <th scope="col" style='background-color:#B9D5CE; width: 12%'>Total linea</th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_factura as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->codigo_producto ?></td>
                                                <td><?= $val->producto ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                                <td style="text-align: right"><?= '$'.number_format($val->total_linea,0) ?></td>
                                           </tr>
                                         <?php endforeach;?>          
                                    </body>
                                    <tr style="font-size: 90%; background-color:#B9D5CE">
                                        <td colspan="5"></td>
                                        <td style="text-align: right;"><b></b></td>
                                        <td></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right;  background-color:#F0F3EF;"><b>VALOR BRUTO:</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_bruto,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>DESCTO (<?= $model->porcentaje_descuento?> %) :</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento,0)?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>SUBTOTAL:</b></td>
                                        <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->subtotal_factura,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>IMPUESTO (<?= $model->porcentaje_iva?> %) :</b></td>
                                        <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->impuesto,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>RETENCION (<?= $model->porcentaje_rete_fuente?> %) :</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_retencion,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>RETE IVA (<?= $model->porcentaje_rete_iva?> %) :</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_reteiva,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>TOTAL PAGAR:</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->total_factura,0); ?></b></td>
                                    </tr>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
                  <div role="tabpanel" class="tab-pane" id="tallascolores">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col"  style='background-color:#B9D5CE;'>Tipo de talla</th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Nombre del color</th>                        
                                             <th scope="col"  style='background-color:#B9D5CE;'>Cantidad vendida</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Fecha hora registro</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Numero factura</th>  
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($talla_color as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->talla->nombre_talla ?></td>
                                                <td><?= $val->color->colores ?></td>
                                                <td style="text-align: right"><?= ''. number_format($val->cantidad_venta,0) ?></td>
                                                <td><?= $val->fecha_registro ?></td> 
                                                <td style="text-align: right"><?= $val->factura->numero_factura ?></td>
                                           </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                </div>  
                <!-- TERMINA TABS-->
                
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>
