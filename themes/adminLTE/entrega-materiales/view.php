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

$this->title = 'ENTREGA DE MATERIALES';
$this->params['breadcrumbs'][] = ['label' => 'Entrega de materiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_entrega;
$view = 'entrega-materiales';
?>
<div class="solicitud-materiales-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_entrega], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_orden', 'id' => $model->id_entrega], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        
        <?php if ($model->autorizado == 0 && $model->cerrar_solicitud == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_entrega, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
        } else {
            if ($model->autorizado == 1 && $model->cerrar_solicitud == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_entrega, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar solicitud', ['cerrar_solicitud', 'id' => $model->id_entrega, 'token'=> $token],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Esta seguro de CERRAR y CREAR el consecutivo a la entrega de materiales.', 'method' => 'post']]);
                echo Html::a('<span class="glyphicon glyphicon-check"></span> Observaciones',
                        ['/entrega-materiales/crear_observacion','id' =>$model->id_entrega, 'token' => $token],
                        [
                            'title' => 'Permite subir las observaciones',
                            'data-toggle'=>'modal',
                            'data-target'=>'#modalcrearobservacion',
                            'class' => 'btn btn-info btn-sm'
                        ])?>
                        
                <div class="modal remote fade" id="modalcrearobservacion">
                         <div class="modal-dialog modal-lg" style ="width: 500px;">
                            <div class="modal-content"></div>
                        </div>
                </div>
            <?php }else{
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_entrega_materiales', 'id' => $model->id_entrega], ['class' => 'btn btn-default btn-sm']);            
                echo Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 20, 'codigo' => $model->id_entrega,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']);
            }
        }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4>Informacion de la entrega</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_entrega") ?></th>
                    <td><?= Html::encode($model->id_entrega) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Producto') ?>:</th>
                    <td><?= Html::encode($model->solicitud->ordenProduccion->producto->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_entrega') ?></th>
                    <td><?= Html::encode($model->numero_entrega) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'unidades_solicitadas') ?></th>
                     <td style="text-align: right;"><?= Html::encode(''.number_format($model->unidades_solicitadas,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_despacho') ?></th>
                    <td><?= Html::encode($model->fecha_despacho) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_hora_registro') ?></th>
                    <td><?= Html::encode($model->fecha_hora_registro) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Numero_lote') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->solicitud->ordenProduccion->numero_lote) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado')?></th>
                    <td><?= Html::encode($model->autorizadoSolicitud) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cerrar_solicitud') ?></th>
                    <td><?= Html::encode($model->cerrarSolicitud) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo') ?></th>
                    <td><?= Html::encode($model->codigo) ?></td>
                    <th style='background-color:#F0F3EF;'></th>
                    <td></td>                    
                </tr>
                <tr style="font-size: 85%;">
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
            <li role="presentation" class="active"><a href="#detallesolicitud" aria-controls="detallesolicitud" role="tab" data-toggle="tab">Listado de materiales <span class="badge"><?= count($detalle_solicitud) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane  active" id="detalleorden">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 85%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Código del material</b></th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre del material</th> 
                                              <th scope="col" align="center" style='background-color:#B9D5CE;'>Presentación</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Unidades solicitadas</th>  
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Unidades despachadas</th>
                                        </tr>
                                    </thead>
                                    <body>
                                        <?php
                                        $previousIdProceso = null; // 
                                        $colSpanCount = 8;
                                         foreach ($detalle_solicitud as $val):
                                            if ($previousIdProceso !== null && $val->id_detalle !== $previousIdProceso){ ?>
                                               <tr style="background-color: #f0f0f0;"> <td colspan="<?= $colSpanCount ?>" style="text-align: center; font-weight: bold; padding: 10px;">
                                                   Presentacion: <?= $val->ordenProductos->descripcion ?> 
                                               </tr>
                                            <?php } ?>
                                                <tr style="font-size: 85%;">
                                                    <td><?= $val->codigo_materia ?></td>
                                                    <td><?= $val->materiales ?></td>
                                                      <td><?= $val->ordenProductos->descripcion ?></td>
                                                    <td style="text-align: right"><?= $val->unidades_solicitadas ?></td>
                                                    <?php if($model->autorizado == 0){?>
                                                        <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="unidades_despachadas[]" style ="text-align: right" value="<?= $val->unidades_despachadas ?>" size ="12" required="true"> </td> 
                                                    <?php }else{?>
                                                        <td style='text-align: right'><?= ''.number_format($val->unidades_despachadas,0) ?></td>
                                                    <?php }?>   
                                                    <input type="hidden" name="listado_materiales[]" value="<?= $val->id?>"> 
                                                </tr>
                                                  <?php  $previousIdProceso = $val->id_detalle; 
                                         endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <div class="panel-footer text-right">  
                                <?php 
                                if($model->autorizado == 0){?>
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_cantidad']) ?>
                                
                                <?php } ?>
                            </div>   
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>


   