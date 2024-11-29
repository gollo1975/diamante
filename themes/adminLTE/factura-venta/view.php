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
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-xs']) ?>
        <?php if ($model->autorizado == 0 && $model->numero_factura == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Modificar factura', ['update', 'id' => $model->id_factura, 'token' =>$token,'id_pedido' => $model->id_pedido], ['class' => 'btn btn-success btn-xs']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Regenerar factura', ['regenerar_factura', 'id' => $model->id_factura, 'token' =>$token,'id_pedido' => $model->id_pedido], ['class' => 'btn btn-info btn-xs']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['factura-venta/autorizado', 'id' => $model->id_factura, 'token' =>$token], ['class' => 'btn btn-default btn-xs']);
        } else {
            if ($model->autorizado == 1 && $model->numero_factura == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_factura, 'token' =>$token], ['class' => 'btn btn-default btn-xs']);
                echo Html::a('<span class="glyphicon glyphicon-book"></span> Generar factura', ['generar_factura', 'id' => $model->id_factura, 'token' =>$token, 'id_pedido' => $model->id_pedido],['class' => 'btn btn-default btn-xs',
                           'data' => ['confirm' => 'Esta seguro de generar la factura de venta al cliente '.$model->cliente.' para ser enviada a la Dian.', 'method' => 'post']]);
                if($model->id_medio_pago == ''){?>
                     <?= Html::a('<span class="glyphicon glyphicon-usd"></span> Medio de pago',
                                     ['factura-venta/subir_medio_pago', 'id' => $model->id_factura, 'token' => $token],
                                       ['title' => 'Permite subir el medio de pago',
                                        'data-toggle'=>'modal',
                                        'data-target'=>'#modalsubirmediopago',
                                        'class' => 'btn btn-default btn-xs',
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
                                        'class' => 'btn btn-default btn-xs',
                                        'data-backdrop' => 'static',
                                        'data-keyboard' => 'false'
                                       ]);?> 
                    <div class="modal remote fade" id="modalsubirmediopago">
                                 <div class="modal-dialog modal-lg" style ="width: 500px;">    
                                     <div class="modal-content"></div>
                                 </div>
                    </div>
                    <?php if($model->id_tipo_factura == 5){?>
                    
                            <?= Html::a('<span class="glyphicon glyphicon-list"></span> Termimos facturacion',
                              ['factura-venta/terminos_factura_exportacion', 'id' => $model->id_factura, 'token' => $token],
                                ['title' => 'Permite subir el medio de pago',
                                 'data-toggle'=>'modal',
                                 'data-target'=>'#modalterminosfacturaexportacion',
                                 'class' => 'btn btn-info btn-xs',
                                 'data-backdrop' => 'static',
                                 'data-keyboard' => 'false'
                                ]);?> 
                            <div class="modal remote fade" id="modalterminosfacturaexportacion">
                                <div class="modal-dialog modal-lg" style ="width: 1000px;">    
                                    <div class="modal-content"></div>
                                </div>
                            </div> 
                    <?php } 
                }    
            }else{
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_factura_venta', 'id' => $model->id_factura,'token' => $token], ['class' => 'btn btn-default btn-xs']);            
                echo Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 12, 'codigo' => $model->id_factura,'view' => $view, 'token' =>$token], ['class' => 'btn btn-default btn-xs']);
                echo Html::a('<span class="glyphicon glyphicon-list"></span> Enviar a la Dian', ['enviar_factura_dian', 'id' => $model->id_factura, 'token' =>$token],['class' => 'btn btn-success btn-xs',
                           'data' => ['confirm' => 'Esta seguro de enviar la factura de venta a la Dian.', 'method' => 'post']]);
            }
        }?>        
    </div>  
    <br>
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
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_factura') ?></th>
                    <td><?= Html::encode($model->numero_factura) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_pedido')?></th>
                    <td><?= Html::encode($model->pedido->numero_pedido) ?></td>
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
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_forma_pago') ?></th>
                    <td><?= Html::encode($model->formaPago->concepto) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'plazo_pago')?></th>
                    <td><?= Html::encode($model->plazo_pago) ?> Dias</td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_factura')?></th>
                    <td><?= Html::encode($model->tipoFactura->descripcion) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoFactura) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="8"><?= Html::encode($model->observacion) ?></td>
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
                                                <th scope="col"  style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                                <th scope="col"  style='background-color:#B9D5CE;'>Descripcion producto</th>     
                                                <th scope="col"  style='background-color:#B9D5CE;'>TV</th>
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: left'>Cantidad</th>       
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: left'>Vr. unitario</th> 
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: left'>% Iva</th>  
                                                <th scope="col"  style='background-color:#B9D5CE; text-align: left'>Subtotal</th>                        
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
                                             if($model->id_tipo_factura <> 5){ ?>
                                           
                                                <tr style="font-size: 85%;">
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
                                        <td colspan="6"></td>
                                        <td style="text-align: right;"><b><?= $moneda->sigla?></b></td>
                                        <td style="text-align: right;"><b><?= 'COP'?></b></td>
                                      
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right;  background-color:#F0F3EF;"><b>Valor Bruto:</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= $model->valor_bruto_internacional; ?></b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_bruto,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Dscto Comercial:</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b> <?= $model->descuento_comercial_internacional?></b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento_comercial,0)?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Dscto Efectivo (<?= $model->porcentaje_descuento?> %) :</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b> <?= $model->descuento_internacional?></b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento,0)?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Subtotal:</b></td>
                                        <td align="right" style=" background-color:#F0F3EF" ><b><?= $model->subtotal_factura_internacional?></b></td>
                                        <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->subtotal_factura,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Impuesto:</b></td>
                                         <td align="right" style=" background-color:#F0F3EF" ><b><?= $model->impuesto_internacional; ?></b></td>
                                        <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->impuesto,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Retencion (<?= $model->porcentaje_rete_fuente?> %) :</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= $model->valor_retencion_internacional; ?></b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_retencion,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Rete Iva (<?= $model->porcentaje_rete_iva?> %) :</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= $model->valor_reteiva_internacional ?></b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_reteiva,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Total Pagar:</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= $model->total_factura_internacional ?></b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->total_factura,0); ?></b></td>
                                    </tr>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>
