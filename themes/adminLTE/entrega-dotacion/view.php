<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\EntregaDotacion */

$this->title = $model->empleado->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Entrega Dotacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$view = 'entrega-dotacion';
\yii\web\YiiAsset::register($this);
?>
<div class="entrega-dotacion-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_dotaciones'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        <?php if ($model->autorizado == 0 && $model->numero_entrega == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_entrega, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
        } else {
            if ($model->autorizado == 1 && $model->numero_entrega == 0){
                echo Html::a('<span class="glyphicon glyphicon-refresh"></span> Desautorizar', ['autorizado', 'id' => $model->id_entrega, 'token' => $token], ['class' => 'btn btn-default btn-sm']);?>
               <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Generar entrega', ['generar_entrega', 'id' => $model->id_entrega, 'token'=> $token],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Esta seguro de generar el numero de entrega de la dotacion al empleado ' . $model->empleado->nombre_completo.'.', 'method' => 'post']]);
            }else{
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Visualizar PDF', ['imprimir_formato', 'id' => $model->id_entrega], ['class' => 'btn btn-default btn-sm']);?>
                <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 24, 'codigo' => $model->id_entrega,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']);
                if($model->tipo_proceso == 0 && $model->descargar_inventario == 0){?>
                    <?= Html::a('<span class="glyphicon glyphicon-export"></span> Descargar inventarios', ['descargar_inventarios', 'id' => $model->id_entrega, 'token'=> $token],['class' => 'btn btn-success btn-sm',
                           'data' => ['confirm' => 'Desea descargar los productos relacionados en esta Orden de entrega al modulo de inventario.', 'method' => 'post']]);
                }
            }  
        }?>
   </p>  
   <div class="panel panel-success">
        <div class="panel-heading">
           ENTREGA DE DOTACION
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'>Id:</th>
                    <td><?= $model->id_entrega ?></td>
                    <th style='background-color:#F0F3EF;'>Documento:</th>
                    <td><?= $model->empleado->nit_cedula ?></td>
                    <th style='background-color:#F0F3EF;'>Empleado:</th>
                    <td><?= $model->empleado->nombre_completo ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo dotacion:</th>
                    <td><?= $model->tipoDotacion->descripcion ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'>Numero:</th>
                    <td><?= $model->numero_entrega ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo proceso:</th>
                    <td><?= $model->tipoProceso ?></td>
                    <th style='background-color:#F0F3EF;'>Fecha / hora:</th>
                    <td><?= $model->fecha_hora_registro ?></td>
                    <th style='background-color:#F0F3EF;'>Fecha entrega:</th>
                    <td><?= $model->fecha_entrega ?></td>
                </tr>
                 <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'>Autorizado:</th>
                    <td><?= $model->autorizadaEntrega ?></td>
                    <th style='background-color:#F0F3EF;'>Cerrado:</th>
                    <td><?= $model->cerradaEntrega ?></td>
                    <th style='background-color:#F0F3EF;'>User name:</th>
                    <td><?= $model->user_name ?></td>
                    <th style='background-color:#F0F3EF;'>Cantidad:</th>
                    <td style="text-align: right"><?= $model->cantidad ?></td>
                </tr>
                 <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'>Observacion:</th>
                    <td colspan="8"><?= $model->observacion?></td>
                   
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
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#salidas_entradas" aria-controls="salidas_entradas" role="tab" data-toggle="tab">Listado de proceso  <span class="badge"><?= count($totalSalidas) ?></span></a></li>

        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="salidas_entradas">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 85%;">
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Código producto</b></th>
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Descripcion</th> 
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad</th>  
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Talla</th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 
                                    </tr>
                                </thead>
                                <body>
                                    <?php
                                    
                                    foreach ($totalSalidas as $val):
                                        if($model->tipo_proceso == 0){?>
                                            <tr style="font-size: 85%;">
                                                <td><?= $val->inventario->codigo_producto?></td>
                                                <td><?= $val->inventario->nombre_producto?></td>
                                                <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="cantidad[]" value="<?= $val->cantidad ?>" style="text-align: right" size ="10" required = "true"> </td> 
                                                <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="talla[]" value="<?= $val->talla ?>" style="text-align: right" size="10"> </td> 
                                                <?php
                                                if($model->autorizado == 0){?>
                                                    <td style= 'width: 25px; height: 25px;'>                                       
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_linea_entrega', 'id' => $model->id_entrega, 'detalle' => $val->id, 'token' => $token], [
                                                                   'class' => '',
                                                                   'data' => [
                                                                       'confirm' => 'Esta seguro de eliminar el registro?',
                                                                       'method' => 'post',
                                                                   ],
                                                               ])
                                                        ?>
                                                    </td>    
                                                <?php }else{?>
                                                    <td style= 'width: 25px; height: 25px;'></td>
                                                <?php }?>   

                                                 <input type="hidden" name="detalle_dotacion[]" value="<?= $val->id ?>">
                                            </tr>
                                        <?php }else{
                                              
                                            ?>
                                            <tr style="font-size: 85%;">
                                                <td><?= $val->inventario->codigo_producto?></td>
                                                <td><?= $val->inventario->nombre_producto?></td>
                                                <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="cantidad[]" value="<?= $val->cantidad ?>" style="text-align: right" size ="10" required = "true"> </td> 
                                                <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="talla[]" value="<?= $val->talla ?>" style="text-align: right" size="10"> </td> 
                                                <?php
                                                if($model->autorizado == 0){?>
                                                    <td style= 'width: 25px; height: 25px;'>                                       
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_linea_devolucion', 'id' => $model->id_entrega, 'detalle' => $val->id, 'token' => $token], [
                                                                   'class' => '',
                                                                   'data' => [
                                                                       'confirm' => 'Esta seguro de eliminar el registro?',
                                                                       'method' => 'post',
                                                                   ],
                                                               ])
                                                        ?>
                                                    </td>    
                                                <?php }else{?>
                                                    <td style= 'width: 25px; height: 25px;'></td>
                                                <?php }?>   

                                                 <input type="hidden" name="detalle_devolucion[]" value="<?= $val->id ?>">
                                            </tr>
                                        <?php }?>    
                                    <?php
                                    endforeach;?>
                                </body>
                            </table> 
                        </div>
                        <div class="panel-footer text-right">
                            <?php if($model->autorizado == 0){
                                if($model->tipo_proceso == 0){
                                    echo Html::a('<span class="glyphicon glyphicon-search"></span> Buscar producto', ['entrega-dotacion/buscar_producto_inventario', 'id' => $model->id_entrega, 'token' => $token],[ 'class' => 'btn btn-primary btn-sm']);?>                                            
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizar_detalle_producto']);
                                }else{
                                   echo Html::a('<span class="glyphicon glyphicon-send"></span> Descargar salida', ['entrega-dotacion/descargar', 'id' => $model->id_entrega, 'token' => $token],[ 'class' => 'btn btn-primary btn-sm']);?>                                            
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'devolucion_detalle_producto']); 
                                }    
                                
                            }?>
                        </div>  
                            
                    </div>
                </div>    
            </div>
        </div>
    </div>
   <?php $form->end()?>; 
   <!--TERMINA LOS TABS-->
</div>
