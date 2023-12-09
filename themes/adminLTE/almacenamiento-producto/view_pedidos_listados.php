<?php

//modelos
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

$this->title = 'Detalle del pedido';
$this->params['breadcrumbs'][] = ['label' => 'Pedido listadis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_pedido;
?>
<div class="almacenamiento-producto-view_pedidos_listados">
    
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_pedidos_listados'], ['class' => 'btn btn-primary btn-sm']) ?>
     <div class="btn-group btn-sm" role="group">    
            <button type="button" class="btn btn-info  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               Imprimir
               <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                    <li><?= Html::a('<span class="glyphicon glyphicon-print"></span> Pedido', ['/pedidos/imprimir_pedido', 'id' => $model->id_pedido]) ?></li>
                    <?php if($model->presupuesto > 0){?>
                        <li><?= Html::a('<span class="glyphicon glyphicon-print"></span> Presupuesto pedido', ['/pedidos/imprimir_presupuesto', 'id' => $model->id_pedido]) ?></li>
                    <?php }?>    
            </ul>
       </div>    
    <div class="panel panel-success">
        <div class="panel-heading">
            DETALLES DEL PEDIDO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_pedido") ?></th>
                    <td><?= Html::encode($model->id_pedido) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_pedido') ?></th>
                    <td><?= Html::encode($model->numero_pedido) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cantidad') ?></th>
                    <td><?= Html::encode($model->cantidad) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'subtotal') ?></th>
                     <td style="text-align: right;"><?= Html::encode(''.number_format($model->subtotal,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'documento') ?></th>
                    <td><?= Html::encode($model->documento) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cliente')?></th>
                    <td><?= Html::encode($model->cliente) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'impuesto') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->impuesto,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoPedido) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Cerrado') ?></th>
                    <td><?= Html::encode($model->pedidoAbierto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'facturado') ?></th>
                    <td><?= Html::encode($model->pedidoFacturado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'gran_total') ?></th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->gran_total,0)) ?></td>                    
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'usuario') ?></th>
                    <td><?= Html::encode($model->usuario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_agente') ?></th>
                    <td><?= Html::encode($model->agentePedido->nombre_completo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Presupuesto_gastado') ?>:</th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->clientePedido->gasto_presupuesto_comercial,0)) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Presupuesto_asignado') ?>:</th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->clientePedido->presupuesto_comercial,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td  colspan="8"><?= Html::encode($model->observacion) ?></td>
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
            <li role="presentation" class="active"><a href="#detallepedido" aria-controls="detallepedido" role="tab" data-toggle="tab">Pedido <span class="badge"><?= count($detalle_pedido) ?></span></a></li>
            <li role="presentation"><a href="#presupuestocomercial" aria-controls="presupuestocomercial" role="tab" data-toggle="tab">Presupuesto comercial <span class="badge"><?= count($detalle_presupuesto) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="detallepedido">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                          <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                   <thead>
                                       <tr style="font-size: 90%;">
                                           <th scope="col" style='background-color:#B9D5CE;'>Código</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>F. validado</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>C. Vendida</th> 
                                           <th scope="col" style='background-color:#B9D5CE;'>C. despachada</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Vr. unit.</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Subtotal.</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Iva</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                        <?php
                                        foreach ($detalle_pedido as $val):
                                            ?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->inventario->codigo_producto ?></td>
                                                <td><?= $val->inventario->nombre_producto ?></td>
                                                <td><?= $val->fecha_alistamiento ?></td>
                                                <td><?= $val->numero_lote ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->historico_cantidad_vendida,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                     </tbody>
                                   
                               </table>
                           </div>
                           <div class="panel-footer text-right">
                               <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['/pedidos/excel_pedido', 'id' => $model->id_pedido], ['class' => 'btn btn-primary btn-sm']);?>
                           </div>
                       </div>
                </div>
            </div>   
                <!--TERMINA TABS-->
                <div role="tabpanel" class="tab-pane " id="presupuestocomercial">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                           <th scope="col" style='background-color:#B9D5CE;'>Código</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>T. presupuesto</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>F. validado</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>C. Vendida</th> 
                                           <th scope="col" style='background-color:#B9D5CE;'>C. despachada</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Vr. unit.</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Subtotal.</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Iva</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         $subtotal = 0; $impuesto = 0; $total = 0;
                                         foreach ($detalle_presupuesto as $val):
                                              $subtotal += $val->subtotal;
                                            $impuesto += $val->impuesto;
                                            $total += $val->total_linea;
                                             ?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->inventario->codigo_producto ?></td>
                                                <td><?= $val->inventario->nombre_producto ?></td>
                                                <td><?= $val->presupuesto->descripcion ?></td>
                                                <td><?= $val->fecha_alistamiento ?></td>
                                                <td><?= $val->numero_lote ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->historico_cantidad_vendida,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                    <tr>
                                        <td colspan="9"></td>
                                        <td style="text-align: right;"><b>Subtotal:</b></td>
                                        <td align="right"><b><?= '$ '.number_format($subtotal,0); ?></b></td>
                                      
                                    </tr>
                                    <tr>
                                        <td colspan="9"></td>
                                        <td style="text-align: right;"><b>Impuesto:</b></td>
                                        <td align="right" ><b><?= '$ '.number_format($impuesto,0); ?></b></td>
                                      
                                    </tr>
                                     <tr>
                                        <td colspan="9"></td>
                                        <td style="text-align: right;"><b>Total:</b></td>
                                        <td align="right" ><b><?= '$ '.number_format($total,0); ?></b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="panel-footer text-right">
                                <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['/pedidos/excel_pedido_presupuesto', 'id' => $model->id_pedido], ['class' => 'btn btn-primary btn-sm']);?>
                            </div>                           
                        </div>
                    </div>
                </div>    
                <!--TERMINA TBAS-->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>

   