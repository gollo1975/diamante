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

$this->title = 'Listado de pedidos ';
$this->params['breadcrumbs'][] = ['label' => 'Pedido cliente', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_pedido;
$view = 'pedidos';
?>
<div class="orden-compra-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
       <div class="btn-group btn-sm" role="group">    
            <?php if($token == 0){?>
                <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_pedido], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php }else{
                if($token == 1){?>
                    <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_orden_compra', 'id' => $model->id_pedido], ['class' => 'btn btn-primary btn-sm']) ?>
                <?php }else{
                    if($token == 2){
                        echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_pedidos', 'id' => $model->id_pedido], ['class' => 'btn btn-primary btn-sm']);
                    }else{
                        if($token == 3){
                            echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_pedido_vendedor'], ['class' => 'btn btn-primary btn-sm']);
                        }else{
                            echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['pedidoslistos'], ['class' => 'btn btn-primary btn-sm']);
                        }    
                    }    
                }           
            }
            if ($model->autorizado == 1 && $model->cerrar_pedido == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar pedido', ['cerrar_pedido', 'id' => $model->id_pedido, 'token'=> $token],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Esta seguro de cerrar el pedido del cliente  '. $model->cliente.'.', 'method' => 'post']]);

            }else{?>
                <button type="button" class="btn btn-info  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Imprimir
                   <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                        <li><?= Html::a('<span class="glyphicon glyphicon-print"></span> Pedido', ['imprimir_pedido', 'id' => $model->id_pedido]) ?></li>
                        <?php if($model->presupuesto > 0){?>
                            <li><?= Html::a('<span class="glyphicon glyphicon-print"></span> Presupuesto pedido', ['imprimir_presupuesto', 'id' => $model->id_pedido]) ?></li>
                        <?php }?>    
                </ul>
               <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 11, 'codigo' => $model->id_pedido,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']);
            } ?>        
        </div>    
    <div class="panel panel-success">
        <div class="panel-heading">
            DETALLES DEL PEDIDO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Ver nota..
                      </button>
                      <div class="collapse" id="collapseExample">
                          <div class="well" style="font-size: 100%;">
                              <?php echo 'El cliente se ha consumido ('?><?= ''.number_format($model->clientePedido->gasto_presupuesto_comercial,0) ?> <?php echo ') del presupuesto comercial.'?> 
                        </div>
                     </div>
                </tr>
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
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_entrega') ?>:</th>
                    <td ><?= Html::encode($model->fecha_entrega) ?></td>
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
            <li role="presentation" class="active"><a href="#detallepedido" aria-controls="detallepedido" role="tab" data-toggle="tab">Detalle pedido <span class="badge"><?= count($detalle_pedido) ?></span></a></li>
            <li role="presentation"><a href="#presupuestocomercial" aria-controls="presupuestocomercial" role="tab" data-toggle="tab">Presupuesto comercial <span class="badge"><?= count($pedido_presupuesto) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="detallepedido">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 90%;">
                                        <th scope="col" style='background-color:#B9D5CE;'><b>Codigo</b></th>  
                                        <th scope="col" style='background-color:#B9D5CE;'>Presentación</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>C. Faltante</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Vr. unit.</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Subtotal.</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                   </tr>
                               </thead>
                               <tbody>
                               <?php
                               $subtotal = 0; $impuesto = 0; $total = 0;
                               foreach ($detalle_pedido as $val):
                                   $subtotal += $val->subtotal;
                                   $impuesto += $val->impuesto;
                                   $total += $val->total_linea;
                                   ?>
                               <tr style="font-size: 90%;">
                                    <td><?= $val->inventario->codigo_producto ?></td>
                                   <td><?= $val->inventario->nombre_producto ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                   <td style="text-align: right; background-color: #ffcaca"><?= ''.number_format($val->cantidad_faltante,0) ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                   <td style= 'width: 25px; height: 25px;'>
                                        <?php if($model->autorizado == 0){?>
                                            <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle', 'id' => $model->id_pedido, 'detalle' => $val->id_detalle], [
                                                        'class' => '',
                                                        'data' => [
                                                            'confirm' => 'Esta seguro de eliminar este producto del pedido?',
                                                            'method' => 'post',
                                                        ],
                                                    ])
                                            ?>
                                        <?php }?>
                                   </td>
                               </tr>
                               </tbody>
                               <?php endforeach; ?>

                               <tr>
                                    <td colspan="6"></td>
                                    <td style="text-align: right;"><b>Subtotal:</b></td>
                                    <td align="right" ><b><?= '$ '.number_format($subtotal,0); ?></b></td>
                                    <td colspan="1"></td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td style="text-align: right;"><b>Impuesto:</b></td>
                                    <td align="right" ><b><?= '$ '.number_format($impuesto,0); ?></b></td>
                                    <td colspan="1"></td>
                                </tr>
                                 <tr>
                                    <td colspan="6"></td>
                                    <td style="text-align: right;"><b>Total:</b></td>
                                    <td align="right" ><b><?= '$ '.number_format($total,0); ?></b></td>
                                    <td colspan="1"></td>
                                </tr>
                           </table>
                       </div>
                       <div class="panel-footer text-right">
                           <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['excel_pedido', 'id' => $model->id_pedido, 'token' => $token], ['class' => 'btn btn-primary btn-sm']);?>
                       </div>
                   </div>
                </div>
            </div>        
            <!--TERMINA PRIMER TBAS-->
            <div role="tabpanel" class="tab-pane" id="presupuestocomercial">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 90%;">
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Presentacion</th>                        
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Tipo presupuesto</th>
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cant.</th>  
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>C. Faltante</th>  
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. Unitario</th>  
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Subtotal</th>                        
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Impuesto</th>  
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Total</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 
                                    </tr>
                                </thead>
                            <body>
                                 <?php
                                 $subtotal = 0; $impuesto = 0; $total = 0;
                                 foreach ($pedido_presupuesto as $val):
                                      $subtotal += $val->subtotal;
                                    $impuesto += $val->impuesto;
                                    $total += $val->total_linea;
                                     ?>
                                    <tr style="font-size: 90%;">
                                        <td><?= $val->inventario->codigo_producto ?></td>
                                        <td><?= $val->inventario->nombre_producto ?></td>
                                        <td><?= $val->presupuesto->descripcion ?></td>
                                        <?php if($val->cantidad == 0){?>
                                              <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidades[]" value="<?= $val->cantidad?>" style="text-align: right" size="7" maxlength="true"> </td> 
                                        <?php }else{?>
                                              <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                        <?php }?>   
                                        <td style="text-align: right; background-color: #ffcaca"><?= ''.number_format($val->cantidad_faltante,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                        <input type="hidden" name="producto_presupuesto[]" value="<?= $val->id_detalle?>"> 
                                        <td style= 'width: 25px; height: 25px;'>
                                            <?php if($model->cerrar_pedido == 0){?>
                                                <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_presupuesto', 'id' => $model->id_pedido, 'detalle' => $val->id_detalle, 'token' => $token, 'sw' => 1], [
                                                            'class' => '',
                                                            'data' => [
                                                                'confirm' => 'Esta seguro de eliminar este producto del presupuesto comercial?',
                                                                'method' => 'post',
                                                            ],
                                                        ])
                                                ?>
                                            <?php }?>
                                       </td>
                                    </tr>
                                 <?php endforeach;?>          
                            </body>
                            <tr>
                                <td colspan="6"></td>
                                <td style="text-align: right;"><b>Subtotal:</b></td>
                                <td align="right"><b><?= '$ '.number_format($subtotal,0); ?></b></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td style="text-align: right;"><b>Impuesto:</b></td>
                                <td align="right" ><b><?= '$ '.number_format($impuesto,0); ?></b></td>
                                <td colspan="2"></td>
                            </tr>
                             <tr>
                                <td colspan="6"></td>
                                <td style="text-align: right;"><b>Total:</b></td>
                                <td align="right" ><b><?= '$ '.number_format($total,0); ?></b></td>
                                <td colspan="2"></td>
                            </tr>
                        </table>
                        </div>
                            <?php
                            if($cliente->presupuesto_comercial == 0 ){
                                Yii::$app->getSession()->setFlash('info', 'No se le asignado presupuesto a este cliente. Contactar al representante de ventas');     
                            }else{   
                                if($cliente->presupuesto_comercial >= $cliente->gasto_presupuesto_comercial){
                                    if($model->cerrar_pedido == 0){?>
                                        <div class="panel-footer text-right">
                                           <?= Html::a('<span class="glyphicon glyphicon-plus"></span>Adicionar', ['pedidos/adicionar_presupuesto', 'id' => $model->id_pedido, 'token' => $token, 'sw' => 0],[ 'class' => 'btn btn-info btn-sm']) ?>                                            
                                        </div>     
                                    <?php }
                                }else{
                                    Yii::$app->getSession()->setFlash('info', 'Ha superado el presupuesto comercial. Favor eliminar productos o solicitar autorizacion de presupuesto.');     
                                }
                            }    
                            if($model->cerrar_pedido == 1){?>    
                                    <div class="panel-footer text-right">
                                        <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['excel_pedido_presupuesto', 'id' => $model->id_pedido], ['class' => 'btn btn-primary btn-sm']);?>
                                    </div>                           
                            <?php }?>

                    </div>
                </div>
            </div>    
            <!--TERMINA TBAS-->
        </div>   
    </div>
  <?php ActiveForm::end(); ?>  
</div>

   