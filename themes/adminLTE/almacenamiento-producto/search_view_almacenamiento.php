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
        <?php }else {
            if($token == 2){
                echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']);
            } else{
                 echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['cargar_orden_produccion'], ['class' => 'btn btn-primary btn-sm']);
            }
        }    ?>
        
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