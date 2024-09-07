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
$this->params['breadcrumbs'][] = ['label' => 'Mover posiciones', 'url' => ['mover_posiciones']];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="almacenamiento-producto-view_almacenamiento">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['mover_posiciones'], ['class' => 'btn btn-primary btn-sm']);?>
        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
           ALMACENAMIENTO DE PRODUCTO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "codigo_producto") ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Presentacion') ?></th>
                    <td><?= Html::encode($model->producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_piso') ?></th>
                    <td><?= Html::encode($model->piso->descripcion) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_rack') ?></th>
                     <td style="text-align: right"><?= Html::encode($model->rack->descripcion) ?></td>
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
            <li role="presentation" class="active"><a href="#moverposiciones" aria-controls="moverposiciones" role="tab" data-toggle="tab">Nueva posicion <span class="badge"><?= count($posiciones) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="moverposiciones">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 90%;">
                                        <th scope="col"  style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                        <th scope="col"  style='background-color:#B9D5CE;'>Presentacion</th>   
                                        <th scope="col"  style='background-color:#B9D5CE;'>Piso anterior</th>   
                                        <th scope="col"  style='background-color:#B9D5CE;'>Nuevo piso</th>   
                                        <th scope="col"  style='background-color:#B9D5CE;'>Posicion anterior</th> 
                                        <th scope="col"  style='background-color:#B9D5CE;'>Nueva posicion</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'>Rack anterior</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'>Nuevo rack</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'>Cant.</th>  
                                        <th scope="col"  style='background-color:#B9D5CE;'>F. movimiento</th>  
                                        
                                    </tr>
                                </thead>
                                <body>
                                     <?php
                                     if($posiciones){
                                        foreach ($posiciones as $val): ?>
                                           <tr style="font-size: 90%;">
                                               <td><?= $val->codigo ?></td>
                                               <td><?= $val->producto ?></td>
                                               <td><?= $val->piso->descripcion ?></td>
                                                <?php if($val->id_piso_nuevo == null){?>
                                                   <td><?= 'NO FOUND' ?></td>
                                               <?php }else{?>
                                                   <td><?= $val->pisoNuevo->descripcion ?></td>
                                               <?php }?>
                                               <td><?= $val->posicion->posicion ?></td>
                                               <?php if($val->id_posicion_nueva == null){?>
                                                   <td><?= 'NO FOUND' ?></td>
                                               <?php }else{?>
                                                   <td><?= $val->posicionNueva->posicion ?></td>
                                               <?php }?>
                                               <td><?= $val->rack->descripcion?></td>
                                               <?php if($val->id_rack_nuevo == null){?>
                                                   <td><?= 'NO FOUND' ?></td>
                                               <?php }else{?>
                                                   <td><?= $val->rackNuevo->descripcion?></td>
                                               <?php }?>
                                               <td style="text-align: right"><?= ''.number_format($val->cantidad,0)?></td>  
                                               <td><?= $val->fecha_proceso ?></td>
                                          </tr>
                                        <?php endforeach;
                                     }else{
                                         Yii::$app->getSession()->setFlash('warning', 'Esta referencia no tiene movimiento de posiciones o nuevo almacenamiento.');
                                     }?>          
                                </body>
                            </table>    
                        </div>
                    </div>
                </div>    
            </div>    
            <!-- termina tabs-->
        </div>    
    </div> 
     <?php ActiveForm::end(); ?>  
</div>