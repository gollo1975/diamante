<?php

//modelos
use app\models\GrupoProducto;

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

$this->title = 'ORDEN DE ENSAMBLE DE KITS / DETALLE';
$this->params['breadcrumbs'][] = ['label' => 'Entrega orden de kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_orden_entrega;

?>

<div class="orden-entrega-kits-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_orden'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        
        <?php if ($model->autorizado == 0 && $model->proceso_cerrado == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_orden_entrega, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
        } else {
            if ($model->autorizado == 1 && $model->proceso_cerrado == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_orden_entrega, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar solicitud', ['cerrar_solicitud', 'id' => $model->id_orden_entrega, 'token'=> $token],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Esta seguro de CERRAR y CREAR el consecutivo a la orden de entrega de los KITS.', 'method' => 'post']]);
            }else{
                if($model->inventario_enviado == 0){?>
                    <?= Html::a('<span class="glyphicon glyphicon-print"></span> Visualizar PDF', ['imprimir_solicitud_kits', 'id' => $model->id_orden_entrega], ['class' => 'btn btn-default btn-sm']);  ?>          
                    <?= Html::a('<span class="glyphicon glyphicon-send"></span> Enviar kits a inventarios', ['crear_producto_kits', 'id' => $model->id_orden_entrega, 'token'=> $token],['class' => 'btn btn-info btn-sm',
                           'data' => ['confirm' => 'Esta seguro de CREAR la presentacion del prdoucto '.$model->presentacion->descripcion.' en el modulo de inventarios.', 'method' => 'post']]);
                }else{?>
                    <?= Html::a('<span class="glyphicon glyphicon-print"></span> Visualizar PDF', ['imprimir_solicitud_kits', 'id' => $model->id_orden_entrega], ['class' => 'btn btn-default btn-sm']);  
                } 
            }
        }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4>ORDENES DE ENTREGA</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "Id") ?></th>
                    <td><?= Html::encode($model->id_orden_entrega) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_entrega_kits') ?></th>
                    <td><?= Html::encode($model->entregaKits->numero_entrega) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_kits') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_kits,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <?php if($model->id_inventario !== null){?>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo_presentacion') ?></th>
                        <td><?= Html::encode($model->inventario->codigo_producto) ?></td>
                    <?php }else{?>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo_presentacion') ?></th>
                        <td><?= Html::encode('NO FOUND') ?></td>
                    <?php }?>    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_presentacion') ?></th>
                    <td><?= Html::encode($model->presentacion->descripcion) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_productos_procesados') ?></th>
                      <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_productos_procesados,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoProceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'proceso_cerrado') ?></th>
                    <td><?= Html::encode($model->procesoCerrado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>                    
                </tr>
                <tr style="font-size: 85%;">
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_orden') ?></th>
                    <td><?= Html::encode($model->fecha_orden) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_hora_registro') ?></th>
                    <td><?= Html::encode($model->fecha_hora_registro) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_orden')?>:</th>
                    <td style="text-align: right"><?= Html::encode($model->numero_orden) ?></td>
                    
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
    ]);
     ?>
    <!-- comienza los tabs -->
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listadopresentacion" aria-controls="listadopresentacion" role="tab" data-toggle="tab">Listado de presentacion <span class="badge"><?= count($detalle) ?></span></a></li>
        </ul>
            <div class="tab-content">
                 <div role="tabpanel" class="tab-pane  active" id="listadopresentacion">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 85%;">
                                            <th scope="col"  style='background-color:#B9D5CE;'><b>Id</b></th>   
                                            <th scope="col"  style='background-color:#B9D5CE;'><b>Codigo del producto</b></th>                        
                                            <th scope="col" style='background-color:#B9D5CE;'><b>Presentacion del producto</b></th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Numero de lote</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cantidad de kits</th>
                           
                                        </tr>
                                    </thead>
                                    <body>
                                        <?php
                                        foreach ($detalle as $val):?>
                                            <tr style="font-size: 85%;">
                                                <td><?= $val->id_detalle ?></td>
                                                <td style='text-align: right'><?= $val->detalleEntrega->detalle->inventario->codigo_producto?></td>
                                                <td ><?= $val->detalleEntrega->detalle->inventario->nombre_producto?></td>
                                                <td ><?= $val->detalleEntrega->numero_lote?></td>
                                                <td style='text-align: right'><?= ''.number_format($val->cantidad_producto,0) ?></td>
                                                
                                            </tr>
                                         <?php endforeach;
                                         ?>          
                                    </body>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>


   