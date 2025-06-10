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

$this->title = 'Packing pedido';
$this->params['breadcrumbs'][] = ['label' => 'packin pedido', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_packing;
?>
<div class="packing-pedido-view">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-xs']);?>
        <?php if($model->autorizado == 0 && $model->numero_packing == 0){
             echo  Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_packing], ['class' => 'btn btn-default btn-xs']);
        }else{
            if($model->autorizado == 1 && $model->numero_packing == 0){
               echo  Html::a('<span class="glyphicon glyphicon-ok"></span> Desautorizar', ['autorizado', 'id' => $model->id_packing], ['class' => 'btn btn-default btn-xs']);?> 
              <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar packing', ['cerrar_packing_pedido', 'id' => $model->id_packing],['class' => 'btn btn-success btn-xs',
                     'data' => ['confirm' => 'Esta seguro de cerrar el PACKING del cliente  '. $model->cliente.'. Debe de subir la guia del proveedor para el despacho.', 'method' => 'post']]);?>
               <?= Html::a('<span class="glyphicon glyphicon-list"></span> Subir guia masivo',
                                 ['packing-pedido/subir_guia_proveedor', 'id' => $model->id_packing],
                                   ['title' => 'Permite subir la guia del proveedor al PACKING',
                                    'data-toggle'=>'modal',
                                    'data-target'=>'#modalsubirguiaproveedor',
                                    'class' => 'btn btn-info btn-xs'
                                   ])    
                ?> 
                <div class="modal remote fade" id="modalsubirguiaproveedor">
                    <div class="modal-dialog modal-lg" style ="width: 500px;">    
                        <div class="modal-content"></div>
                    </div>
                </div>
            <?php }else{
                 echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_packing', 'id' => $model->id_packing], ['class' => 'btn btn-default btn-xs']);
                if($model->pedido->pedido_validado == 0){?>
                    <?= Html::a('<span class="glyphicon glyphicon-list"></span> Transportadora',
                                 ['packing-pedido/adicionar_transportadora', 'id' => $model->id_packing],
                                   ['title' => 'Permite subir la transportadora al packing',
                                    'data-toggle'=>'modal',
                                    'data-target'=>'#modaladicionartransportadora',
                                    'class' => 'btn btn-info btn-xs',
                                    'data-backdrop' => 'static',
                                    'data-keyboard' => 'false'
                                   ]);?> 
                    <div class="modal remote fade" id="modaladicionartransportadora">
                        <div class="modal-dialog modal-lg" style ="width: 500px;">    
                            <div class="modal-content"></div>
                        </div>
                    </div> 
                <?php } 
            }
        } ?> 
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            INFORMACION DEL PACKING
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_packing') ?></th>
                    <td><?= Html::encode($model->id_packing) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "numero_packing") ?></th>
                    <td><?= Html::encode($model->numero_packing) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Cliente') ?>:</th>
                    <td><?= Html::encode($model->cliente) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "total_cajas") ?></th>
                    <td><?= Html::encode($model->total_cajas) ?></td>
                </tr>
                 <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_packing') ?>:</th>
                    <td><?= Html::encode($model->fecha_packing) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "Numero_pedido") ?>:</th>
                    <td><?= Html::encode($model->pedido->numero_pedido) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_transportadora') ?></th>
                    <?php if($model->id_transportadora <> ''){?>
                        <td><?= Html::encode($model->transportadora->razon_social) ?></td>
                    <?php }else{?>
                        <td><?= Html::encode('NO FOUND') ?></td>
                    <?php }?>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "total_cajas") ?></th>
                    <td><?= Html::encode($model->total_cajas) ?></td>
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
           <li role="presentation" class="active"><a href="#listadopacking" aria-controls="listadopacking" role="tab" data-toggle="tab">Listado de packing <span class="badge"><?= 1 ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="pedidocomercial">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 85%;">
                                        <th scope="col" style='background-color:#B9D5CE;'>Nro de caja</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Referencia</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Presentacion del producto</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha y hora packing</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Numero de guia</th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($detalle as $key => $val) {?>
                                        <tr style="font-size: 85%;">
                                            <td><?= $val->numero_caja?></td>
                                            <td><?= $val->codigo_producto?></td>
                                            <td><?= $val->nombre_producto?></td>
                                            <td><?= $val->fecha_cracion_packing?></td>
                                            <td><?= $val->cantidad_despachada?></td>
                                            <td><?= $val->numero_guia?></td>
                                            <?php if($model->autorizado == 0){?>
                                                <td style="width: 25px; height: 25px;">
                                                    <!-- Inicio Nuevo Detalle proceso -->
                                                      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                                          ['/packing-pedido/almacenar_producto_caja', 'id_caja' => $val->id_detalle, 'id' => $model->id_packing],
                                                          [
                                                              'title' => 'Almacenar unidades en caja',
                                                              'data-toggle'=>'modal',
                                                              'data-target'=>'#modalalmacenarunidades'.$val->id_detalle,
                                                          ])    
                                                     ?>
                                                  <div class="modal remote fade" id="modalalmacenarunidades<?= $val->id_detalle ?>">
                                                      <div class="modal-dialog modal-lg" style ="width: 550px;">
                                                          <div class="modal-content"></div>
                                                      </div>
                                                  </div>
                                                </td>
                                                <?php if($val->cantidad_despachada <= 0){?>
                                                <td style= 'width: 20px; height: 20px;'></td>
                                                    <td style= 'width: 20px; height: 20px;'>
                                                         <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['eliminar_caja', 'id_detalle' => $val->id_detalle,'id' => $model->id_packing], [
                                                                'class' => '',
                                                                'title' => 'Permite eliminar las cajas que no sirven.',
                                                                'data' => [
                                                                    'confirm' => '¿Esta seguro que se desea ELIMINAR  esta caja de empaque ?',
                                                                    'method' => 'post',
                                                                ],
                                                                ])?>
                                                    </td>
                                                <?php }else{?>
                                                    <td style= 'width: 20px; height: 20px;'>
                                                    <td style= 'width: 20px; height: 20px;'></td>
                                                <?php } ?>    
                                            <?php }else{?>
                                                <td style="width: 25px; height: 25px;"></td>
                                                <?php if($model->cerrado_proceso == 0){?>
                                                    <td style= 'width: 20px; height: 20px;'>
                                                            <?= Html::a('<span class="glyphicon glyphicon-list"></span>',
                                                                               ['packing-pedido/subir_guia_proveedor_individual', 'id' => $model->id_packing, 'id_detalle' => $val->id_detalle],
                                                                                 ['title' => 'Permite subir la guia del proveedor al PACKING',
                                                                                  'data-toggle'=>'modal',
                                                                                  'data-target'=>'#modalsubirguiaproveedorindividual',
                                                                                  'class' => ''
                                                                                 ])    
                                                              ?> 
                                                              <div class="modal remote fade" id="modalsubirguiaproveedorindividual">
                                                                  <div class="modal-dialog modal-lg" style ="width: 500px;">    
                                                                      <div class="modal-content"></div>
                                                                  </div>
                                                              </div>
                                                   </td> 
                                                <?php }else{ ?>
                                                   <td style="width: 25px; height: 25px;"></td>
                                                <?php }?>   
                                                <td style="width: 25px; height: 25px;"></td>
                                                
                                            <?php }?>    
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php if ($model->autorizado == 0){
                                if ($detalle){ ?>
                                    <div class="panel-footer text-right">
                                        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear caja', ['crear_caja_packing', 'id' => $model->id_packing],['class' => 'btn btn-success btn-xs',
                                         'data' => ['confirm' => 'Esta seguro de crear una nueva caja para el PACKING del cliente  '. $model->cliente.'.', 'method' => 'post']]);?>
                                    </div>
                            
                            <?php }
                            }?>
                        </div>
                    </div>
                </div>
            </div>
           <!--TERMINA TABS-->
        </div>
    </div>    
     <?php ActiveForm::end(); ?> 
</div>
