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

$this->title = 'PEDIDO VIRTUAL';
$this->params['breadcrumbs'][] = ['label' => 'Pedido virtual', 'url' => ['pedido_virtual']];
$this->params['breadcrumbs'][] = $model->id_pedido;
?>
<div class="orden-compra-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <div class="btn-group btn-sm" role="group">    
         <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['pedido_virtual'], ['class' => 'btn btn-primary btn-sm']) ?>
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
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_entrega') ?>:</th>
                    <td ><?= Html::encode($model->fecha_entrega) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'documento') ?></th>
                    <td><?= Html::encode($model->documento) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cliente')?></th>
                    <td><?= Html::encode($model->cliente) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'usuario') ?></th>
                    <td><?= Html::encode($model->usuario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_agente') ?></th>
                    <td><?= Html::encode($model->agentePedido->nombre_completo) ?></td>
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
            <li role="presentation" class="active"><a href="#detallepedido" aria-controls="detallepedido" role="tab" data-toggle="tab">Pedido virtual <span class="badge"><?= count($detalle_pedido) ?></span></a></li>
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
                                        <th scope="col" style='background-color:#B9D5CE;'><b>Producto</b></th>  
                                        <th scope="col" style='background-color:#B9D5CE;'>Presentación</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad vendida</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Valor unitario</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Subtotal.</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                   </tr>
                               </thead>
                               <tbody>
                               <?php
                               foreach ($detalle_pedido as $val): ?>
                               <tr style="font-size: 90%;">
                                   <td><?= $val->inventario->codigo_producto ?></td>
                                    <td><?= $val->inventario->producto->nombre_producto ?></td>
                                   <td><?= $val->inventario->nombre_producto ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                   <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                   <?php if($val->cargar_existencias == 0){?>
                                        <td style= 'width: 25px; height: 10px;'>
                                             <?= Html::a('<span class="glyphicon glyphicon-search"></span> ', ['search_inventario_pedido', 'id_inventario' => $val->id_inventario, 'id' => $model->id_pedido, 'idToken' => $idToken], [
                                                            'class' => '',
                                                            'title' => 'Proceso que permite buscar existencias de inventarios)', 
                                                            'data' => [
                                                                'confirm' => 'Desea buscar existencias en el módulo de inventarios de la presentacion del producto ('.$val->inventario->nombre_producto.').',
                                                                'method' => 'post',
                                                            ],
                                              ])?>
                                         </td>
                                         <?php if($idToken == 1){?>
                                             <td style= 'width: 25px; height: 10px;'>
                                                 <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> ', ['cargar_inventario_pedido', 'id_inventario' => $val->id_inventario, 'id' => $model->id_pedido, 'idToken' => $idToken, 'pedido' => 0], [
                                                            'class' => '',
                                                            'title' => 'Proceso que permite cargar las  existencias de inventarios al produto)', 
                                                            'data' => [
                                                                'confirm' => 'Desea cargar desde el modulo de inventario las '.$val->cantidad.' unidades vendidas al producto ('.$val->inventario->nombre_producto.').',
                                                                'method' => 'post',
                                                            ],
                                              ])?>
                                             </td>
                                         <?php }else{?>
                                             <td style= 'width: 25px; height: 10px;'></td>
                                         <?php }
                                   }else{?>
                                             <td style= 'width: 25px; height: 10px;'>
                                                 <span class="glyphicon glyphicon-thumbs-up"></span>
                                             </td>
                                      <td style= 'width: 25px; height: 10px;'></td>
                                   <?php }?>          
                               </tr>
                               </tbody>
                               <?php endforeach; ?>

                           </table>
                       </div>
                       <div class="panel-footer text-right">
                           <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['excel_pedido', 'id' => $model->id_pedido], ['class' => 'btn btn-primary btn-sm']);?>
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
                                         <th scope="col" align="center" style='background-color:#B9D5CE;'>Stock</th>    
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cant.</th>       
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
                                        <td style="background-color:#CBAAE3; color: black"><?= $val->inventario->stock_unidades ?></td>
                                        <?php if($val->cantidad == 0){?>
                                              <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidades[]" value="<?= $val->cantidad?>" style="text-align: right" size="7" maxlength="true"> </td> 
                                        <?php }else{?>
                                              <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                        <?php }?>      
                                        <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                        <input type="hidden" name="producto_presupuesto[]" value="<?= $val->id_detalle?>"> 
                                        
                                    </tr>
                                 <?php endforeach;?>          
                            </body>
                        </table>
                        </div>
                        <div class="panel-footer text-right">
                            <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['excel_pedido_presupuesto', 'id' => $model->id_pedido], ['class' => 'btn btn-primary btn-sm']);?>
                        </div>                           
                            
                    </div>
                </div>
            </div>    
            <!--TERMINA TBAS-->
        </div>   
    </div>
  <?php ActiveForm::end(); ?>  
</div>

   