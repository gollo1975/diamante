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

$this->title = 'Detalle factura de venta';
$this->params['breadcrumbs'][] = ['label' => 'Factura de venta', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_factura;
$view = 'factura-venta';
$moneda = app\models\ClienteMoneda::find()->where(['=','id_cliente', $model->id_cliente])->one(); 
?>

<?php
    //$remision = Remision::find()->where(['=', 'idordenproduccion', $model->idordenproduccion])->one();
?>

<div class="factura-venta-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_maestro_factura'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?=  Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_factura_venta', 'id' => $model->id_factura], ['class' => 'btn btn-default btn-sm']);?>            
       
    </p>  
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
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_pedido')?></th>
                    <td><?= Html::encode($model->id_pedido) ?></td>
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
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'forma_pago') ?></th>
                    <td><?= Html::encode($model->formaPago->concepto) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'plazo_pago')?></th>
                    <td><?= Html::encode($model->plazo_pago) ?> Dias</td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_factura')?></th>
                    <td><?= Html::encode($model->tipoFactura->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Saldo') ?></th>
                    <td style="text-align: right"><?= Html::encode('$ '.number_format($model->saldo_factura,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Estado') ?>:</th>
                    <td><?= Html::encode($model->estadoFactura) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Dias_mora')?>:</th>
                    <td><?= Html::encode($model->dias_mora) ?> dias</td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Intereses')?>:</th>
                    <td style="text-align: right"><?= Html::encode('$ '.number_format($model->valor_intereses_mora,0)) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Total_mora')?>:</th>
                    <td style="text-align: right"><?= Html::encode('$ '.number_format($model->subtotal_interes_masiva,0)) ?></td>
                </tr>
                 <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Porcentaje_mora') ?>:</th>
                    <td><?= Html::encode($model->porcentaje_mora) ?> %</td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_venta') ?></th>
                    <td><?= Html::encode($model->tipoVenta->concepto) ?></td>
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
            <li role="presentation"><a href="#notacredito" aria-controls="notacredito" role="tab" data-toggle="tab">Nota crédito <span class="badge"><?= count($nota_credito) ?></span></a></li>
            <li role="presentation"><a href="#recibocaja" aria-controls="recibocaja" role="tab" data-toggle="tab">Recibo de caja <span class="badge"><?= count($recibo_caja) ?></span></a></li>
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
                                        <td colspan="6"; style="text-align: right"><b>Moneda:</b></td>
                                        <?php if($moneda){?>
                                            <td style="text-align: right;"><b><?= $moneda->sigla?></b></td>
                                            <td style="text-align: right;"><b><?= 'COP'?></b></td>
                                        <?php }else{?>
                                           
                                            <td style="text-align: right;"><b><?= 'COP'?></b></td>
                                        <?php }?>                                         
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right;  background-color:#F0F3EF;"><b>Valor Bruto:</b></td>
                                        <?php if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= $model->valor_bruto_internacional; ?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_bruto,0); ?></b></td>
                                        <?php }else{ ?>    
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_bruto,0); ?></b></td>
                                        <?php }?>    
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Descuento Comercial:</b></td>
                                        <?php if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= $model->descuento_comercial_internacional?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento_comercial,0)?></b></td>
                                        <?php }else{?> 
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento_comercial,0)?></b></td>
                                        <?php }?>    
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Descuento Efectivo (<?= $model->porcentaje_descuento?> %) :</b></td>
                                        <?php if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= $model->descuento_internacional?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento,0)?></b></td>
                                        <?php }else{?>
                                             <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento,0)?></b></td>
                                        <?php }?>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Subtotal:</b></td>
                                        <?php if($moneda){?>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= $model->subtotal_factura_internacional?></b></td>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->subtotal_factura,0); ?></b></td>
                                        <?php }else{?>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->subtotal_factura,0); ?></b></td>
                                        <?php  }?>    
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Impuesto:</b></td>
                                        <?php if($moneda){?>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= $model->impuesto_internacional; ?></b></td>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->impuesto,0); ?></b></td>
                                        <?php }else{?>
                                            <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->impuesto,0); ?></b></td>
                                        <?php }?>     
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Retencion (<?= $model->porcentaje_rete_fuente?> %) :</b></td>
                                        <?php if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= $model->valor_retencion_internacional; ?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_retencion,0); ?></b></td>
                                        <?php }else{?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_retencion,0); ?></b></td>
                                        <?php }?>     
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Rete Iva (<?= $model->porcentaje_rete_iva?> %) :</b></td>
                                        <?php if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= $model->valor_reteiva_internacional ?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_reteiva,0); ?></b></td>
                                        <?php }else{?>    
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_reteiva,0); ?></b></td>
                                        <?php }?>    
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>Total Pagar:</b></td>
                                        <?php if($moneda){?>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= $model->total_factura_internacional ?></b></td>
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->total_factura,0); ?></b></td>
                                        <?php }else{?>    
                                            <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->total_factura,0); ?></b></td>
                                        <?php }?>    
                                    </tr>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
                  <div role="tabpanel" class="tab-pane" id="notacredito">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col"  style='background-color:#B9D5CE;'><b>Numero</b></th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Motivo</th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Fecha proceso</th>       
                                             <th scope="col"  style='background-color:#B9D5CE;'>Usuario</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Total devolución</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Observación</th>  
                                            <th scope="col" style='background-color:#B9D5CE'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($nota_credito as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->numero_nota_credito ?></td>
                                                <td><?= $val->motivo->concepto ?></td>
                                                <td><?= $val->fecha_nota_credito ?></td>
                                                <td><?= $val->user_name ?></td> 
                                                <td style="text-align: right"><?= '$ '.number_format($val->valor_total_devolucion,0) ?></td>
                                                <td><?= $val->observacion ?></td>
                                                <td style= 'width: 20px; height: 20px;'>
                                                    <a href="<?= Url::toRoute(["nota-credito/imprimir_nota_credito", "id" => $val->id_nota]) ?>" ><span class="glyphicon glyphicon-print" title="Permite imprimir la nota credito   "></span></a>
                                                </td>
                                           </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                </div>  
                <!-- TERMINA TABS-->
                <div role="tabpanel" class="tab-pane" id="recibocaja">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col"  style='background-color:#B9D5CE;'><b>Numero</b></th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Tipo recibo</th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Fecha pago</th>       
                                            <th scope="col"  style='background-color:#B9D5CE;'>Fecha proceso</th>       
                                            <th scope="col"  style='background-color:#B9D5CE;'>Banco</th>
                                            <th scope="col"  style='background-color:#B9D5CE;'>Usuario</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Valor pago</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Nuevo saldo</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Observación</th>  
                                            <th scope="col" style='background-color:#B9D5CE'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($recibo_caja as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->recibo->numero_recibo ?></td>
                                                <td><?= $val->recibo->tipo->concepto ?></td>
                                                <td><?= $val->recibo->fecha_pago ?></td>
                                                <td><?= $val->recibo->fecha_proceso ?></td>
                                                <td><?= $val->recibo->codigoBanco->entidad_bancaria ?></td>
                                                <td><?= $val->recibo->user_name ?></td> 
                                                <td style="text-align: right"><?= '$ '.number_format($val->abono_factura,0) ?></td>
                                                <td style="text-align: right"><?= '$ '.number_format($val->saldo_factura,0) ?></td>
                                                <td><?= $val->recibo->observacion ?></td>
                                                <td style= 'width: 20px; height: 20px;'>
                                                    <a href="<?= Url::toRoute(["recibo-caja/imprimir_recibo_caja", "id" => $val->id_recibo]) ?>" ><span class="glyphicon glyphicon-print" title="Permite imprimir el recibo de caja"></span></a>
                                                </td>
                                           </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                </div>  
                <!--TERMINA TABS-->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>
