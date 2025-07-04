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

$this->title = 'Factura de venta';
$this->params['breadcrumbs'][] = ['label' => 'Factura de venta', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_factura;
$view = 'factura-venta';
?>

<?php
$moneda = app\models\ClienteMoneda::find()->where(['=','id_cliente', $model->id_cliente])->one(); 
?>

<div class="factura-venta-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <div class="btn-group" role="group" aria-label="...">
        <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-xs']) ?>
        <?php if ($model->autorizado == 0 && $model->numero_factura == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['factura-venta/autorizado', 'id' => $model->id_factura, 'token' =>$token], ['class' => 'btn btn-default btn-xs']);
        } else {
            if ($model->autorizado == 1 && $model->numero_factura == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_factura, 'token' =>$token], ['class' => 'btn btn-default btn-xs']);
                echo Html::a('<span class="glyphicon glyphicon-book"></span> Generar consecutivo', ['generar_factura', 'id' => $model->id_factura, 'token' =>$token, 'id_pedido' => $model->id_pedido],['class' => 'btn btn-info btn-xs',
                           'data' => ['confirm' => 'Esta seguro de generar el consecutivo a la factura de venta del cliente '.$model->cliente.' para ser enviada a la Dian.', 'method' => 'post']]);
                if($model->id_medio_pago == ''){?>
                     <?= Html::a('<span class="glyphicon glyphicon-usd"></span> Medio de pago',
                                     ['factura-venta/subir_medio_pago', 'id' => $model->id_factura, 'token' => $token],
                                       ['title' => 'Permite subir el medio de pago',
                                        'data-toggle'=>'modal',
                                        'data-target'=>'#modalsubirmediopago',
                                        'class' => 'btn btn-success btn-xs',
                                        'data-backdrop' => 'static',
                                        'data-keyboard' => 'false'
                                       ]);?> 
                    <div class="modal remote fade" id="modalsubirmediopago">
                                 <div class="modal-dialog modal-lg" style ="width: 500px;">    
                                     <div class="modal-content"></div>
                                 </div>
                    </div>
                <?php }else{    
                     echo Html::a('<span class="glyphicon glyphicon-print"></span> Visualidar PDF', ['imprimir_factura_venta', 'id' => $model->id_factura, 'token' => $token], ['class' => 'btn btn-default btn-xs']);?>
                     <?= Html::a('<span class="glyphicon glyphicon-usd"></span> Medio de pago',
                                     ['factura-venta/subir_medio_pago', 'id' => $model->id_factura, 'token' => $token],
                                       ['title' => 'Permite subir el medio de pago',
                                        'data-toggle'=>'modal',
                                        'data-target'=>'#modalsubirmediopago',
                                        'class' => 'btn btn-success btn-xs',
                                        'data-backdrop' => 'static',
                                        'data-keyboard' => 'false'
                                       ]);?> 
                   
                    <?php if($model->id_tipo_factura == 5){?>
                    
                            <?= Html::a('<span class="glyphicon glyphicon-list"></span> Termimos de facturacion',
                              ['factura-venta/terminos_factura_exportacion', 'id' => $model->id_factura, 'token' => $token],
                                ['title' => 'Permite subir el medio de pago',
                                 'data-toggle'=>'modal',
                                 'data-target'=>'#modalterminosfacturaexportacion',
                                 'class' => 'btn btn-warning btn-xs',
                                 'data-backdrop' => 'static',
                                 'data-keyboard' => 'false'
                                ]);?> 
                            <div class="modal remote fade" id="modalterminosfacturaexportacion">
                                <div class="modal-dialog modal-lg" style ="width: 1000px;">    
                                    <div class="modal-content"></div>
                                </div>
                            </div> 
                    <?php }?>
                    <div class="modal remote fade" id="modalsubirmediopago">
                                 <div class="modal-dialog modal-lg" style ="width: 500px;">    
                                     <div class="modal-content"></div>
                                 </div>
                    </div>
                    
                <?php }    
            }else{
                if($model->fecha_enviada_api == ''){?>
                    <?= Html::a('<span class="glyphicon glyphicon-send"></span> Enviar documento a la Dian', ['enviar_factura_dian', 'id_factura' => $model->id_factura, 'token' =>$token],['class' => 'btn btn-success btn-xs','id' => 'my_button', 'onclick' => '$("#my_button").attr("disabled", "disabled")',
                               'data' => ['confirm' => 'Esta seguro de enviar la factura de venta electronica No ('.$model->numero_factura.' ) a la Dian. Tener presente que este proceso es definitivo.', 'method' => 'post']]);?>
                    <?= Html::a('<span class="glyphicon glyphicon-print"></span> Visualizar PDF', ['imprimir_factura_venta', 'id' => $model->id_factura,'token' => $token], ['class' => 'btn btn-default btn-xs']);?>            
                    <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 12, 'codigo' => $model->id_factura,'view' => $view, 'token' =>$token], ['class' => 'btn btn-default btn-xs']);
                }else{
                    $disabled = $model->fecha_enviada_api !== '' ? true : false;?>
                    <?= Html::a('<span class="glyphicon glyphicon-send"></span> Enviar factura a la Dian', ['desactivado','id_factura' => $model->id_factura, 'token' => $token],['class' => 'btn btn-success btn-xs disabled', 'id' => 'my_button']);?>
                    <?= Html::a('<span class="glyphicon glyphicon-send"></span>  Reenviar factura a la dian', ['reenviar_documento_dian', 'id_factura' => $model->id_factura, 'token' => $token],['class' => 'btn btn-warning btn-xs', 'id' => 'my_button', 'onclick' => '$("#my_button").attr("disabled", "disabled")',
                        'data' => ['confirm' => 'Esta seguro de REENVIAR la Factura de venta No  '. $model->numero_factura. ' a la DIAN y al CLIENTE.', 'method' => 'post']]);?>
                    <?= Html::a('<span class="glyphicon glyphicon-print"></span> Visualizar PDF', ['imprimir_factura_venta', 'id' => $model->id_factura,'token' => $token], ['class' => 'btn btn-default btn-xs']);?>            
                    <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 12, 'codigo' => $model->id_factura,'view' => $view, 'token' =>$token], ['class' => 'btn btn-default btn-xs']);
                }
            }
        }?>   
    </p>
    </div>  
  
    <br>
    <div class="panel panel-success">
        <div class="panel-heading">
           FACTURA DE VENTA
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_factura") ?></th>
                    <td><?= Html::encode($model->id_factura) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nit_cedula') ?></th>
                    <td><?= Html::encode($model->nit_cedula) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_cliente') ?></th>
                    <td><?= Html::encode($model->cliente) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'direccion') ?></th>
                     <td><?= Html::encode($model->direccion) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_factura') ?></th>
                    <td><?= Html::encode($model->numero_factura) ?></td>
                    <?php if($model->id_pedido <> null ){?>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_pedido')?></th>
                        <td><?= Html::encode($model->pedido->numero_pedido) ?></td>
                    <?php }else{ ?>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_pedido')?></th>
                        <td><?= Html::encode('No found') ?></td>
                    <?php } ?>    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'telefono_cliente') ?></th>
                    <td><?= Html::encode($model->telefono_cliente) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Municipio') ?></th>
                    <td><?= Html::encode($model->clienteFactura->codigoDepartamento->departamento)?> - <?= Html::encode($model->clienteFactura->codigoMunicipio->municipio)?></td>
                </tr>
                <tr style="font-size: 85%;">
                   
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_vencimiento') ?></th>
                    <td><?= Html::encode($model->fecha_vencimiento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_generada') ?></th>
                    <td><?= Html::encode($model->fecha_generada) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>                    
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_forma_pago') ?></th>
                    <td><?= Html::encode($model->formaPago->concepto) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'plazo_pago')?></th>
                    <td><?= Html::encode($model->plazo_pago) ?> Dias</td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_factura')?></th>
                    <td style='background-color:#cbf3f0;'><?= Html::encode($model->tipoFactura->descripcion) ?></td>
                    <?php if($model->id_medio_pago <> ''){?>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_medio_pago') ?></th>
                        <td><?= Html::encode($model->medioPago->concepto) ?></td>
                    <?php }else{?>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_medio_pago') ?></th>
                        <td style='background-color:#fae1dd;'><?= Html::encode('No found') ?></td>
                    <?php }?>    
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="8"><?= Html::encode($model->observacion) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Cufe') ?></th>
                    <td colspan="8"><?= Html::encode($model->cufe) ?></td>
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
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="detallefactura">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <?php if($model->id_tipo_factura <> 5){?>
                                            <tr style="font-size: 85%; text-align: center">
                                                <?php if($model->factura_libre == 1){?>
                                                    <th scope="col"  style='background-color:#B9D5CE;text-align: center'></th> 
                                                <?php }else{?>
                                                <?php }?>
                                                <th scope="col"  style='background-color:#B9D5CE;text-align: center'><b>Codigo</b></th>                        
                                                <th scope="col"  style='background-color:#B9D5CE;text-align: center'>Descripcion producto</th>     
                                                <th scope="col"  style='background-color:#B9D5CE;text-align: center'>TV</th>
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: center'>Cantidad</th>       
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: center'>Vr. unitario</th> 
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: center'>% Iva</th>  
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: center'>Subtotal</th>                        
                                            </tr>
                                        <?php }else{ ?>
                                            <tr style="font-size: 85%;">
                                                <th scope="col"  style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                                <th scope="col"  style='background-color:#B9D5CE;'>Descripcion producto</th>     
                                                <th scope="col"  style='background-color:#B9D5CE;'>TV</th>
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: left'>Cant.</th>       
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: left'>Vr. unitario (<?= 'COL'?>) </th> 
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: left'>Vr. unitario (<?= $moneda->sigla?>) </th> 
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: left'>Subtotal (<?= $moneda->sigla?>)</th>
                                                 <th scope="col"  style='background-color:#B9D5CE; text-align: left'>Subtotal (<?= 'COP'?>)</th> 
                                            </tr>
                                        <?php } ?>    
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_factura as $val):
                                            if($model->id_tipo_factura <> 5){ 
                                                   if($model->factura_libre == 1){
                                                       if($model->autorizado == 0){?>
                                                            <tr style="font-size: 85%;">
                                                                <td style= 'width: 20px; height: 20px;'>
                                                                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                                                           ['/factura-venta/editar_concepto','id' => $model->id_factura, 'token' =>$token, 'id_detalle' => $val->id_detalle],
                                                                           [
                                                                               'title' => 'Editar concepto de factura',
                                                                               'data-toggle'=>'modal',
                                                                               'data-target'=>'#modaleditarconceptofactura'.$model->id_factura,
                                                                               'class' => '',
                                                                               'data-backdrop' => 'static',
                                                                               'data-keyboard' => 'false'
                                                                           ])    
                                                                      ?>
                                                                    <div class="modal remote fade" id="modaleditarconceptofactura<?= $model->id_factura ?>">
                                                                         <div class="modal-dialog modal-lg" style ="width: 500px;">
                                                                              <div class="modal-content"></div>
                                                                         </div>
                                                                    </div> 
                                                               </td>


                                                                <td><?= $val->codigo_producto ?></td>
                                                                <td><?= $val->producto ?></td>
                                                                <td><?= $val->tipo_venta ?></td>
                                                                <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                                <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                                <td style="text-align: right"><?= $val->porcentaje_iva ?>%</td>
                                                                <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                            </tr>
                                                        <?php }else{?>
                                                            <tr style="font-size: 85%;">
                                                                <td style= 'width: 20px; height: 20px;'>
                                                                   
                                                               </td>


                                                                <td><?= $val->codigo_producto ?></td>
                                                                <td><?= $val->producto ?></td>
                                                                <td><?= $val->tipo_venta ?></td>
                                                                <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                                <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                                <td style="text-align: right"><?= $val->porcentaje_iva ?>%</td>
                                                                <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                            </tr>
                                                        <?php }    
                                                    }else{?>
                                                        <tr style="font-size: 85%;">
                                                            <td><?= $val->codigo_producto ?></td>
                                                            <td><?= $val->producto ?></td>
                                                            <td><?= $val->tipo_venta ?></td>
                                                            <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                            <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                            <td style="text-align: right"><?= $val->porcentaje_iva ?>%</td>
                                                            <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                        </tr>
                                                   <?php }?>     
                                                   
                                             <?php }else{?>
                                               <tr style="font-size: 85%;">
                                                  
                                                    <td><?= $val->codigo_producto ?></td>
                                                    <td><?= $val->producto ?></td>
                                                    <td><?= $val->tipo_venta ?></td>
                                                    <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                    <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                    <td style="text-align: right; background-color: #c4ebf3"><?= $val->valor_unitario_internacional ?></td>
                                                    <td style="text-align: right; background-color: #c4ebf3"><?= $val->subtotal_internacional ?></td>
                                                    <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                               </tr>
                                             <?php }
                                         endforeach;?>          
                                    </body>
                                    <tr style="font-size: 90%; background-color:#B9D5CE">
                                        <?php if($model->factura_libre == 1){?>
                                        <td colspan="7"; style="text-align: right"><b>Moneda:</b></td>
                                        <?php }else{?>
                                        <td colspan="6"; style="text-align: right"><b>Moneda:</b></td>
                                        <?php }?>
                                        
                                        <?php if($moneda){?>
                                            <td style="text-align: right;"><b><?= $moneda->sigla?></b></td>
                                            <td style="text-align: right;"><b><?= 'COP'?></b></td>
                                        <?php }else{?>
                                           
                                            <td style="text-align: right;"><b><?= 'COP'?></b></td>
                                        <?php }?>                                         
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <?php if($model->factura_libre == 1){?>
                                            <td colspan="6"></td>
                                            <td style="text-align: right;  background-color:#F0F3EF;"><b>Valor Bruto:</b></td>
                                        <?php }else{?>
                                            <td colspan="5"></td>
                                            <td style="text-align: right;  background-color:#F0F3EF;"><b>Valor Bruto:</b></td> 
                                        <?php }    
                                        if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= $model->valor_bruto_internacional; ?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_bruto,0); ?></b></td>
                                        <?php }else{ ?>    
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_bruto,0); ?></b></td>
                                        <?php }?>    
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <?php if($model->factura_libre == 1){?>
                                            <td colspan="6"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Descuento Comercial:</b></td>
                                        <?php }else{?>
                                            <td colspan="5"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Descuento Comercial:</b></td>
                                        <?php }    
                                        if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= $model->descuento_comercial_internacional?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento_comercial,0)?></b></td>
                                        <?php }else{?> 
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento_comercial,0)?></b></td>
                                        <?php }?>    
                                    </tr>
                                    <tr style="font-size: 90%;">
                                       <?php if($model->factura_libre == 1){?>
                                            <td colspan="6"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Descuento Efectivo (<?= $model->porcentaje_descuento?> %) :</b></td>
                                        <?php }else{?>
                                            <td colspan="5"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Descuento Efectivo (<?= $model->porcentaje_descuento?> %) :</b></td>
                                        <?php }    
                                        if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= $model->descuento_internacional?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento,0)?></b></td>
                                        <?php }else{?>
                                             <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento,0)?></b></td>
                                        <?php }?>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <?php if($model->factura_libre == 1){?>
                                            <td colspan="6"></td>
                                             <td style="text-align: right; background-color:#F0F3EF"><b>Subtotal:</b></td>
                                        <?php }else{?>
                                              <td colspan="5"></td>
                                             <td style="text-align: right; background-color:#F0F3EF"><b>Subtotal:</b></td>
                                        <?php }    
                                       
                                        if($moneda){?>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= $model->subtotal_factura_internacional?></b></td>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->subtotal_factura,0); ?></b></td>
                                        <?php }else{?>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->subtotal_factura,0); ?></b></td>
                                        <?php  }?>    
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <?php if($model->factura_libre == 1){?>
                                            <td colspan="6"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Impuesto:</b></td>
                                        <?php }else{?> 
                                            <td colspan="5"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Impuesto:</b></td>
                                        <?php }    
                                        if($moneda){?>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= $model->impuesto_internacional; ?></b></td>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->impuesto,0); ?></b></td>
                                        <?php }else{?>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->impuesto,0); ?></b></td>
                                        <?php }?>     
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <?php if($model->factura_libre == 1){?>
                                            <td colspan="6"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Retencion (<?= $model->porcentaje_rete_fuente?> %) :</b></td>
                                         <?php }else{?> 
                                            <td colspan="5"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Retencion (<?= $model->porcentaje_rete_fuente?> %) :</b></td>
                                        <?php }    
                                        if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= $model->valor_retencion_internacional; ?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_retencion,0); ?></b></td>
                                        <?php }else{?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_retencion,0); ?></b></td>
                                        <?php }?>     
                                    </tr>
                                    <tr style="font-size: 90%;">
                                       <?php if($model->factura_libre == 1){?>
                                            <td colspan="6"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Rete Iva (<?= $model->porcentaje_rete_iva?> %) :</b></td>
                                        <?php }else{?> 
                                            <td colspan="5"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Rete Iva (<?= $model->porcentaje_rete_iva?> %) :</b></td>
                                        <?php }    
                                        if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= $model->valor_reteiva_internacional ?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_reteiva,0); ?></b></td>
                                        <?php }else{?>    
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_reteiva,0); ?></b></td>
                                        <?php }?>    
                                    </tr>
                                    <tr style="font-size: 90%;">
                                       <?php if($model->factura_libre == 1){?>
                                            <td colspan="6"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Total Pagar:</b></td>
                                        <?php }else{?> 
                                            <td colspan="5"></td>
                                            <td style="text-align: right; background-color:#F0F3EF"><b>Total Pagar:</b></td>
                                        <?php }    
                                        if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= $model->total_factura_internacional ?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->total_factura,0); ?></b></td>
                                        <?php }else{?>    
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->total_factura,0); ?></b></td>
                                        <?php }?>    
                                    </tr>
                                </table>
                               
                            </div>
                            <?php if($model->factura_libre == 1 && $model->autorizado == 0){?>
                                <div class="panel-footer text-right" >
                                    <td style="width: 25px; height: 25px;">
                                        <!-- Inicio Nuevo Detalle proceso -->
                                          <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Cargar documento ',
                                              ['/factura-venta/cargar_documento_libre', 'id' => $model->id_factura,'token' => $token],
                                              [
                                                  'title' => 'Cargar documento contable',
                                                  'data-toggle'=>'modal',
                                                  'data-target'=>'#modalcargardocumentolibre'.$model->id_factura,
                                                  'class' => 'btn btn-success btn-sm',
                                                  'data-backdrop' => 'static',

                                              ])    
                                         ?>
                                    </td> 
                                    <div class="modal remote fade" id="modalcargardocumentolibre<?= $model->id_factura ?>">
                                              <div class="modal-dialog modal-lg" style ="width: 650px;">
                                                  <div class="modal-content"></div>
                                              </div>
                                          </div>
                                    </td>
                                </div> 
                            <?php }?>
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>
