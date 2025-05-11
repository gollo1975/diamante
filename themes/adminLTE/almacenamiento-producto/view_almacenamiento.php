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

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Orden produccion', 'url' => ['cargar_orden_produccion']];
$this->params['breadcrumbs'][] = $id_orden;
?>
<div class="almacenamiento-producto-view_almacenamiento">
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']);?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['cargar_orden_produccion'], ['class' => 'btn btn-primary btn-sm']);?>
        <?php }
        if($model->producto_almacenado == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Cerrar orden produccion', ['cerrar_orden_produccion', 'id_orden' => $model->id_orden_produccion, 'token' =>$token],['class' => 'btn btn-success btn-sm',
                               'data' => ['confirm' => 'Esta seguro de CERRAR la Orden de produccion No ('.$model->numero_orden.'). Tener presente que todas las unidades deben de estar almacendas.', 'method' => 'post']]);?>
        <?php }?>
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
           ALMACENAMIENTO DE PRODUCTO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_orden_produccion") ?></th>
                    <td><?= Html::encode($model->id_orden_produccion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_orden') ?></th>
                    <td><?= Html::encode($model->numero_orden) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_producto') ?></th>
                    <td><?= Html::encode($model->producto->nombre_producto) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'unidades') ?></th>
                     <td style="text-align: right"><?= Html::encode(''.number_format($model->unidades,0)) ?></td>
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
            <li role="presentation" class="active"><a href="#detalleorden" aria-controls="detalleorden" role="tab" data-toggle="tab">Detalle productos <span class="badge"><?= count($detalle) ?></span></a></li>
            <li role="presentation"><a href="#detallealmacenamiento" aria-controls="detallealmacenamiento" role="tab" data-toggle="tab">Almacenamiento productos <span class="badge"><?= count($conAlmacenado) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="detalleorden">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 85%;">
                                        <th scope="col"  style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                        <th scope="col"  style='background-color:#B9D5CE;'>Presentación</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'>U. Fabricadas</th> 
                                        <th scope="col"  style='background-color:#B9D5CE;'>U. Almacenadas</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'>U. Faltantes</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'>Tipo documento</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'>Numero lote</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'>F. Vencimiento</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'></th> 
                                        <th scope="col"  style='background-color:#B9D5CE;'></th> 
                                        <th scope="col"  style='background-color:#B9D5CE;'></th>
                                    </tr>
                                </thead>
                                <body>
                                     <?php
                                     foreach ($detalle as $val):
                                         $conDato = app\models\AlmacenamientoProductoDetalles::find()->where(['=','id_almacenamiento', $val->id_almacenamiento])->one();
                                         ?>
                                        <tr style="font-size: 85%;">
                                            <td><?= $val->codigo_producto ?></td>
                                            <td><?= $val->nombre_producto ?></td>
                                            <td style="text-align: right"><?= ''.number_format($val->unidades_producidas,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($val->unidades_almacenadas,0) ?></td>
                                            <td style="text-align: right"><?= ''.number_format($val->unidades_faltantes,0) ?></td>
                                            <?php if($val->id_documento == null){?>
                                                <td><?= 'NO FOUND' ?></td>
                                            <?php }else{?>
                                                <td><?= $val->documento->concepto ?></td>
                                            <?php }?>
                                            <td><?= $val->numero_lote ?></td>
                                             <td><?= $val->fecha_vencimiento ?></td>
                                            <?php if($val->unidades_almacenadas <>  $val->unidades_producidas){?>
                                                <td style= 'width: 20px; height: 20px;'>
                                                    <?= Html::a('<span class="glyphicon glyphicon-floppy-disk"></span>',
                                                       ['/almacenamiento-producto/subir_documento', 'id_orden' => $model->id_orden_produccion, 'id' => $val->id_almacenamiento, 'token' =>$token, 'sw' =>0],
                                                         ['title' => 'Subir el documento del almacenamiento',
                                                          'data-toggle'=>'modal',
                                                          'data-target'=>'#modalsubirdocumento',
                                                         ])    
                                                   ?>
                                                   <div class="modal remote fade" id="modalsubirdocumento">
                                                        <div class="modal-dialog modal-lg" style ="width: 560px;">    
                                                            <div class="modal-content"></div>
                                                        </div>
                                                   </div>
                                                </td>   
                                                <td style= 'width: 20px; height: 20px;'>
                                                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                                       ['/almacenamiento-producto/crear_almacenamiento', 'id_orden' => $model->id_orden_produccion, 'id' => $val->id_almacenamiento,'token' =>$token],
                                                         ['title' => 'Crear el almacenamiento de las unidades',
                                                          'data-toggle'=>'modal',
                                                          'data-target'=>'#modalcrearalmacenamiento',
                                                         ])    
                                                   ?>
                                                   <div class="modal remote fade" id="modalcrearalmacenamiento">
                                                        <div class="modal-dialog modal-lg" style ="width: 550px;">    
                                                            <div class="modal-content"></div>
                                                        </div>
                                                   </div>
                                                </td>       
                                            <?php }else{?>
                                                    <td style= 'width: 20px; height: 20px;'></td>
                                                    <td style= 'width: 20px; height: 20px;'></td>
                                            <?php }  
                                            if (!$conDato){?>    
                                                <td style= 'width: 20px; height: 20px;'>
                                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_almacenamiento', 'id_orden' => $model->id_orden_produccion, 'detalle' => $val->id_almacenamiento, 'token' =>$token, 'sw' =>0], [
                                                                 'class' => '',
                                                                 'data' => [
                                                                     'confirm' => 'Esta seguro de eliminar este producto del proceso de almacenamiento?',
                                                                     'method' => 'post',
                                                                 ],
                                                             ])
                                                     ?>
                                                </td>
                                            <?php }else {?>
                                                <td style= 'width: 20px; height: 20px;'></td>
                                            <?php }?>    
                                                    
                                       </tr>
                                     <?php endforeach;?>          
                                </body>
                            </table>    
                        </div>
                    </div>
                </div>    
            </div>    
            <!-- termina tabs-->
            <div role="tabpanel" class="tab-pane" id="detallealmacenamiento">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 85%;">
                                        <th scope="col"  style='background-color:#B9D5CE;'><b>Piso</b></th>                        
                                        <th scope="col"  style='background-color:#B9D5CE;'>Rack</th>                        
                                        <th scope="col"  style='background-color:#B9D5CE;'>Ubicación</th> 
                                        <th scope="col"  style='background-color:#B9D5CE;'>Numero lote</th>
                                        <th scope="col"  style='background-color:#B9D5CE;'>F. Almacenamiento</th>
                                        <th scope="col"  style='background-color:#B9D5CE;'>Codigo</th>
                                        <th scope="col"  style='background-color:#B9D5CE;'>Presentacion</th>
                                        <th scope="col"  style='background-color:#B9D5CE;'>Stock</th>
                                        <th scope="col"  style='background-color:#B9D5CE;'></th> 
                                    </tr>
                                </thead>
                                <body>
                                     <?php
                                     foreach ($conAlmacenado as $dato):?>
                                        <tr style="font-size: 85%;">
                                            <td><?= $dato->piso->descripcion ?></td>
                                            <td><?= $dato->rack->numero_rack ?> - <?= $dato->rack->descripcion ?></td>
                                            <td><?= $dato->posicion->posicion ?></td>
                                            <td><?= $dato->numero_lote ?></td>
                                            <td><?= $dato->fecha_proceso_lote ?></td>
                                            <td><?= $dato->codigo_producto ?></td>
                                            <td><?= $dato->producto ?></td>
                                            <td style="text-align: right"><?= ''.number_format($dato->cantidad,0) ?></td>
                                            <?php if($model->producto_almacenado == 0){?>
                                                <td style= 'width: 20px; height: 20px;'>
                                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_items_rack', 'id_orden' => $model->id_orden_produccion, 'id_detalle' => $dato->id, 'token' =>$token, 'sw' => 0], [
                                                                 'class' => '',
                                                                 'data' => [
                                                                     'confirm' => 'Esta seguro de eliminar este producto del proceso de almacenamiento?',
                                                                     'method' => 'post',
                                                                 ],
                                                             ])
                                                     ?>
                                               </td>
                                            <?php }else{ ?>
                                               <td style= 'width: 20px; height: 20px;'></td>
                                            <?php }?>   
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
</div>