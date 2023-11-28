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

$this->title = 'Listar pedidos';
$this->params['breadcrumbs'][] = ['label' => 'Listar pedidos', 'url' => ['listar_pedidos']];
$this->params['breadcrumbs'][] = $model->id_pedido;
?>

<div class="almacenamiento-producto-view_lsitar">
    <p>    
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['listar_pedidos'], ['class' => 'btn btn-primary btn-sm']);
        if($model->pedido_validado == 0){
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Pedido validado', ['pedido_validado_facturacion', 'id_pedido' => $model->id_pedido],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Este pedido queda listo para facturacion. Esta seguro de cerrar el pedido del cliente  '. $model->cliente.'.', 'method' => 'post']]);
        }?>    
    </p>    
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
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Cliente') ?></th>
                        <td><?= Html::encode($model->cliente) ?></td>
                         <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Total_pedido') ?></th>
                         <td style="text-align: right;"><?= Html::encode(''.number_format($model->gran_total,0)) ?></td>
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
       <div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#pedidocomercial" aria-controls="pedidocomercial" role="tab" data-toggle="tab">Pedido <span class="badge"><?= count($pedido_detalle) ?></span></a></li>
                <?php if ($pedido_presupuesto){?>
                    <li role="presentation"><a href="#presupuestocomercial" aria-controls="presupuestocomercial" role="tab" data-toggle="tab">Presupuesto comercial <span class="badge"><?= count($pedido_presupuesto) ?></span></a></li>
                <?php } ?>    
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="pedidocomercial">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cant. vendida</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cant. despachada</th>
                                             <th scope="col" style='background-color:#B9D5CE;'>Regenerar</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Vr. unit.</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Subtotal.</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Validado</th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                        </tr>
                                    </thead>
                                    <?php 
                                    $conNumero = 0;
                                    foreach ($pedido_detalle as $pedido):
                                        if($pedido->regenerar_linea == 1){
                                            $conNumero += 1;
                                        }
                                        ?>
                                        <tr style="font-size: 90%;">
                                            <td><?= $pedido->inventario->codigo_producto ?></td>
                                            <td><?= $pedido->inventario->nombre_producto ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->cantidad,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->cantidad_despachada,0) ?></td>
                                            <?php if($pedido->regenerar_linea == 0){?>
                                                <td><?= $pedido->regenerarLinea ?></td>
                                            <?php }else{?>
                                                <td style='background-color:#F0E9D4;'><?= $pedido->regenerarLinea ?></td>
                                            <?php }?>    
                                            <td style="text-align: right"><?= ''.number_format($pedido->valor_unitario,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->subtotal,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->impuesto,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->total_linea,0) ?></td>
                                            <td><?= $pedido->lineaValidada ?></td>
                                            <?php if($pedido->linea_validada == 0){?>
                                                <td style= 'width: 20px; height: 20px;'>
                                                   <a href="<?= Url::toRoute(["almacenamiento-producto/cantidad_despachada", "id_pedido" => $pedido->id_pedido, 'id_detalle' => $pedido->id_detalle, 'sw' => 0]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                                                </td> 
                                            <?php }else {?>    
                                                <td style= 'width: 20px; height: 20px;'></td>                                                
                                            <?php }    
                                            if($pedido->regenerar_linea == 1){?>
                                                <td style= 'width: 20px; height: 20px;'><input type="checkbox" name="numero_linea[]" value="<?= $pedido->id_detalle ?>"></td> 
                                            <?php }else{?>
                                                <td style= 'width: 20px; height: 20px;'></td>
                                            <?php }?>    
                                        </tr>
                                    <?php endforeach;?>
                                </table>
                                <?php if($conNumero <> 0){?>
                                    <div class="panel-footer text-right" >  
                                        <?= Html::submitButton("<span class='glyphicon glyphicon-plus'></span> Regenerar lineas", ["class" => "btn btn-success btn-sm", 'name' => 'regenerar_linea']) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>  
                <!-- TERMINA TABS-->
                  <div role="tabpanel" class="tab-pane" id="presupuestocomercial">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cant. vendida</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cant. despachada</th>
                                             <th scope="col" style='background-color:#B9D5CE;'>Regenerar</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Vr. unit.</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Subtotal.</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Validado</th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                        </tr>
                                    </thead>
                                    <?php 
                                    $conNumero = 0;
                                    foreach ($pedido_presupuesto as $pedido):
                                        if($pedido->regenerar_linea == 1){
                                            $conNumero += 1;
                                        }
                                        ?>
                                        <tr style="font-size: 90%;">
                                            <td><?= $pedido->inventario->codigo_producto ?></td>
                                            <td><?= $pedido->inventario->nombre_producto ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->cantidad,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->cantidad_despachada,0) ?></td>
                                            <?php if($pedido->regenerar_linea == 0){?>
                                                <td><?= $pedido->regenerarLinea ?></td>
                                            <?php }else{?>
                                                <td style='background-color:#F0E9D4;'><?= $pedido->regenerarLinea ?></td>
                                            <?php }?>    
                                            <td style="text-align: right"><?= ''.number_format($pedido->valor_unitario,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->subtotal,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->impuesto,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($pedido->total_linea,0) ?></td>
                                            <td><?= $pedido->lineaValidada ?></td>
                                            <?php if($pedido->linea_validada == 0){?>
                                                <td style= 'width: 20px; height: 20px;'>
                                                   <a href="<?= Url::toRoute(["almacenamiento-producto/cantidad_despachada", "id_pedido" => $pedido->id_pedido, 'id_detalle' => $pedido->id_detalle, 'sw' => 1]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                                                </td> 
                                            <?php }else {?>    
                                                <td style= 'width: 20px; height: 20px;'></td>                                                
                                            <?php }    
                                            if($pedido->regenerar_linea == 1){?>
                                                <td style= 'width: 20px; height: 20px;'><input type="checkbox" name="numero_linea[]" value="<?= $pedido->id_detalle ?>"></td> 
                                            <?php }else{?>
                                                <td style= 'width: 20px; height: 20px;'></td>
                                            <?php }?>    
                                        </tr>
                                    <?php endforeach;?>
                                </table>
                                <?php if($conNumero <> 0){?>
                                    <div class="panel-footer text-right" >  
                                        <?= Html::submitButton("<span class='glyphicon glyphicon-plus'></span> Regenerar lineas", ["class" => "btn btn-success btn-sm", 'name' => 'regenerar_linea']) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>  
                <!--TERMINA TABS-->
            </div>
       </div>    
           <?php ActiveForm::end(); ?> 
</div>    