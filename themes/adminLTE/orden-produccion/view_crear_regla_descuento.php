<?php
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
/* @var $model app\models\Empleado */

$this->title = 'DESCUENTOS COMERCIALES ';
$this->params['breadcrumbs'][] = ['label' => 'Inventario producto', 'url' => ['crear_precio_venta']];
$this->params['breadcrumbs'][] = $id;
?>
<div class="orden-produccion-view_precio_venta">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
           <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['crear_precio_venta'], ['class' => 'btn btn-primary btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            REGLA Y DESCUENTOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_inventario') ?></th>
                    <td><?= Html::encode($model->id_inventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_iva') ?></th>
                    <td><?= Html::encode($model->aplicaIva) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'costo_unitario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->costo_unitario,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo') ?></th>
                    <td><?= Html::encode($model->grupo->nombre_grupo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_vencimiento') ?></th>
                    <td><?= Html::encode($model->fecha_vencimiento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                   <td><?= Html::encode($model->user_name) ?></td>
                </tr>
                                
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion_producto') ?></th>
                    <td colspan="9"><?= Html::encode($model->descripcion_producto)?></td>
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
    <!--INICIO LOS TABS-->
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#regladistribuidor" aria-controls="regladistribuidor" role="tab" data-toggle="tab">Regla distribuidor <span class="badge"><?= count($regla_distribuidor)?></span></a></li>
            <li role="presentation" ><a href="#reglapunto" aria-controls="reglapunto" role="tab" data-toggle="tab">Regla punto venta <span class="badge"><?= count($regla_punto)?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="regladistribuidor">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha vencimiento</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Tipo descuento</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Nuevo valor</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Activo</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>User name</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha registro</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($regla_distribuidor as $val):?>
                                        <tr style="font-size: 90%;">
                                            <td><?= $val->id_regla?></td>
                                            <td><?= $val->fecha_inicio?></td>
                                            <td><?= $val->fecha_final?></td>
                                            <td><?= $val->tipoDescuento?></td>
                                            <td style="text-align: right"><?= $val->nuevo_valor?></td>
                                            <td><?= $val->estadoRegla?></td>
                                            <td><?= $val->user_name?></td>
                                            <td><?= $val->fecha_registro?></td>
                                            <td style= 'width: 25px; height: 25px;'>
                                                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                                    ['/orden-produccion/editar_regla_distribuidor','id' => $model->id_inventario],
                                                    [
                                                        'title' => 'Editar regla comercial de distribuidor',
                                                        'data-toggle'=>'modal',
                                                        'data-target'=>'#modaleditarregladistribuidor'.$model->id_inventario,
                                                    ])    
                                                ?>
                                                <div class="modal remote fade" id="modaleditarregladistribuidor<?= $model->id_inventario ?>">
                                                    <div class="modal-dialog modal-lg" style ="width: 500px;">
                                                         <div class="modal-content"></div>
                                                    </div>
                                                </div> 
                                            </td>     
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>      
                            </table>
                        </div>
                        <?php if(!$regla_distribuidor){?> 
                            <div class="panel-footer text-right">
                                <!-- Inicio Nuevo Detalle proceso -->
                                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear regla distribuidor',
                                        ['/orden-produccion/crear_regla_distribuidor','id' => $model->id_inventario],
                                        [
                                            'title' => 'Crear la nueva regla comercial de descuento',
                                            'data-toggle'=>'modal',
                                            'data-target'=>'#modalnuevoregladistribuidor'.$model->id_inventario,
                                            'class' => 'btn btn-success btn-sm'
                                        ])    
                                   ?>
                                <div class="modal remote fade" id="modalnuevoregladistribuidor<?= $model->id_inventario ?>">
                                    <div class="modal-dialog modal-lg" style ="width: 500px;">
                                         <div class="modal-content"></div>
                                    </div>
                                </div> 
                            </div>
                        <?php }?>
                        </div>   
                </div>
            </div>
            <!--TERMINA TABS-->
             <div role="tabpanel" class="tab-pane" id="reglapunto">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha vencimiento</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Tipo descuento</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Nuevo valor</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Activo</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>User name</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha registro</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($regla_punto as $val):?>
                                        <tr style="font-size: 90%;">
                                            <td><?= $val->id_regla?></td>
                                            <td><?= $val->fecha_inicio?></td>
                                            <td><?= $val->fecha_final?></td>
                                            <td><?= $val->tipoDescuento?></td>
                                            <td style="text-align: right"><?= $val->nuevo_valor?></td>
                                            <td><?= $val->estadoRegla?></td>
                                            <td><?= $val->user_name?></td>
                                            <td><?= $val->fecha_registro?></td>
                                            <td style= 'width: 25px; height: 25px;'>
                                                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                                    ['/orden-produccion/editar_regla_punto','id' => $model->id_inventario],
                                                    [
                                                        'title' => 'Editar regla comercial de punto de venta',
                                                        'data-toggle'=>'modal',
                                                        'data-target'=>'#modaleditarreglapunto'.$model->id_inventario,
                                                    ])    
                                                ?>
                                                <div class="modal remote fade" id="modaleditarreglapunto<?= $model->id_inventario ?>">
                                                    <div class="modal-dialog modal-lg" style ="width: 500px;">
                                                         <div class="modal-content"></div>
                                                    </div>
                                                </div> 
                                            </td>     
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>      
                            </table>
                        </div>
                        <?php if(!$regla_punto){?> 
                            <div class="panel-footer text-right">
                                <!-- Inicio Nuevo Detalle proceso -->
                                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear regla punto',
                                        ['/orden-produccion/crear_regla_punto','id' => $model->id_inventario],
                                        [
                                            'title' => 'Crear la nueva regla comercial de descuento para punto de venta',
                                            'data-toggle'=>'modal',
                                            'data-target'=>'#modalnuevoreglapunto'.$model->id_inventario,
                                            'class' => 'btn btn-info btn-sm'
                                        ])    
                                   ?>
                                <div class="modal remote fade" id="modalnuevoreglapunto<?= $model->id_inventario ?>">
                                    <div class="modal-dialog modal-lg" style ="width: 500px;">
                                         <div class="modal-content"></div>
                                    </div>
                                </div> 
                            </div>
                        <?php }?>
                        </div>   
                </div>
            </div>
            <!--TERMINA TABS -->
        </div>
    </div>    
          <?php ActiveForm::end(); ?>  
</div>

