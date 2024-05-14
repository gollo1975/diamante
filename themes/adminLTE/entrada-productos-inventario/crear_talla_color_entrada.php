<?php

//modelos
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
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Ordenproduccion */

$this->title = 'CREAR TALLAS Y COLORES';
$this->params['breadcrumbs'][] = ['label' => 'Entrada inventario', 'url' => ['index']];
$this->params['breadcrumbs'][] = $id;
?>
<div class="entrada-productos-inventario-color">
    <p>
       <?php
       if($token == 0){ 
             echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view', 'id' => $id, 'token' => $token], ['class' => 'btn btn-primary btn-sm']);
       }else{
          echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['codigo_barra_ingreso', 'id' => $id, 'bodega' => 1], ['class' => 'btn btn-primary btn-sm']); 
       }      
        if(count($item_cerrado) > 0){
            echo Html::a('<span class="glyphicon glyphicon-send"></span> Cerrar entrada', ['cerrar_entrada_referencia', 'id' => $id, 'token'=> $token, 'id_detalle' => $id_detalle, 'id_inventario' => $id_inventario],['class' => 'btn btn-info btn-sm',
                           'data' => ['confirm' => 'Esta seguro CERRAR el proceso de entradas a esta REFERENCIA.?', 'method' => 'post']]);
        }?>    
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            LISTADO DE TALLAS Y COLORES
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "codigo_producto") ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Nombre_producto') ?></th>
                    <td><?= Html::encode($model->inventario->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cantidad') ?></th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->cantidad,0)) ?></td>
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
            <li role="presentation" class="active"><a href="#tallascolores" aria-controls="tallascolores" role="tab" data-toggle="tab">Historial tallas y colores <span class="badge"><?= count($listadoTallaColor) ?></span></a></li>
            <li role="presentation"><a href="#entradatallacolor" aria-controls="entradatallacolor" role="tab" data-toggle="tab">Entrada de tallas y colores <span class="badge"><?= count($entrada_talla) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tallascolores">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 90%;">
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre de la talla</th>                        
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre del color</th>                        
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad inicial</th>  
                                         <th scope="col" align="center" style='background-color:#B9D5CE;'>Existencias actuales</th>  
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 

                                    </tr>
                                </thead>
                                <body>
                                    <?php
                                    foreach ($listadoTallaColor as $val):?>
                                        <tr style="font-size: 90%;">
                                            <td><?= $val->talla->nombre_talla?></td>
                                            <td><?= $val->color->colores?></td>
                                            <td style="text-align: right"><?= $val->cantidad?></td>
                                            <td style="text-align: right"><?= $val->stock_punto?></td>
                                            <td style="width: 25px; height: 25px;">
                                            <!-- este ajas permite crear los precios al deptal y mayorista -->
                                            <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span> ',
                                                ['/entrada-productos-inventario/entrada_nueva_existencia','id' => $id, 'id_inventario' => $id_inventario, 'id_detalle' => $id_detalle, 'token' => $token, 'id_detalle_existencia' => $val->id_detalle],
                                                [
                                                    'title' => 'Permite entrar las nuevas unidades',
                                                    'data-toggle'=>'modal',
                                                    'data-target'=>'#modalentradanuevaexistencia'.$val->id_inventario,
                                                ])    
                                            ?>
                                            <div class="modal remote fade" id="modalentradanuevaexistencia<?= $val->id_inventario ?>">
                                                <div class="modal-dialog modal-lg" style ="width: 500px;">
                                                     <div class="modal-content"></div>
                                                </div>
                                            </div> 
                                        </td>
                                        </tr>
                                    <?php endforeach;?>
                                </body>        
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--TERMINA TABS-->
            <div role="tabpanel" class="tab-pane" id="entradatallacolor">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 90%;">
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre de la talla</th>                        
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre del color</th>                        
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Nueva cantidad</th>  
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cerrado</th>  
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>User name</th>  
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 

                                    </tr>
                                </thead>
                                <body>
                                    <?php
                                    foreach ($entrada_talla as $val):?>
                                        <tr style="font-size: 90%;">
                                            <td><?= $val->talla->nombre_talla?></td>
                                            <td><?= $val->color->colores?></td>
                                            <td style="text-align: right"><?= $val->cantidad?></td>
                                            <td><?= $val->cerradaEntrada?></td>
                                            <td><?= $val->user_name?></td>
                                            <td style= 'width: 25px; height: 25px;'>
                                                <?php if($val->cerrado == 0){?>
                                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_nueva_entrada', 'id' => $id, 'id_detalle' => $id_detalle, 'token' => $token, 'id_inventario' => $id_inventario, 'codigo' => $val->id], [
                                                               'class' => '',
                                                               'data' => [
                                                                   'confirm' => 'Esta seguro de eliminar el registro?',
                                                                   'method' => 'post',
                                                               ],
                                                           ])
                                                    ?>
                                                <?php }?>    
                                            </td>    
                                        </tr>
                                    <?php endforeach;?>
                                </body>        
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--TERMINA TABS-->
        </div>  
    </div>  
     <?php ActiveForm::end(); ?>  
</div>