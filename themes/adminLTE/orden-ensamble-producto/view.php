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
/* @var $model app\models\Municipio */

$this->title = 'DETALLE ORDEN DE ENSAMBLE ';
$this->params['breadcrumbs'][] = ['label' => 'Orden de ensamble', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_ensamble;
?>
<div class="orden-ensamble-producto-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index_resultado_auditoria'], ['class' => 'btn btn-primary btn-sm']); ?>
        <?php if($model->autorizado == 0){
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_ensamble, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
        }else{
           echo Html::a('<span class="glyphicon glyphicon-ok"></span> Desautorizar', ['autorizado', 'id' => $model->id_ensamble, 'token' => $token], ['class' => 'btn btn-default btn-sm']); 
        }?>    
    </p>
     <div class="panel panel-success">
        <div class="panel-heading">
            DETALLE DE LA ORDEN DE ENSAMBLE
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_ensamble') ?></th>
                    <td><?= Html::encode($model->id_ensamble) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_orden_ensamble') ?></th>
                    <td><?= Html::encode($model->numero_orden_ensamble) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_etapa') ?></th>
                    <td><?= Html::encode($model->etapa->concepto) ?></td>   
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo') ?></th>
                    <td><?= Html::encode($model->grupo->nombre_grupo) ?></td>
              </tr>
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_lote') ?></th>
                    <td><?= Html::encode($model->numero_lote) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_orden_produccion') ?></th>
                    <td><?= Html::encode($model->id_orden_produccion) ?></td>   
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_unidades') ?></th>
                      <td style="text-align: right"><?= Html::encode(''.number_format($model->total_unidades,0)) ?></td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="8"><?= Html::encode($model->observacion) ?></td>
                     
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
            <li role="presentation" class="active"><a href="#presentacion_producto" aria-controls="presentacion_producto" role="tab" data-toggle="tab">Presentacion del producto  <span class="badge"><?= count($conPresentacion) ?></span></a></li>
            <?php if($model->autorizado <> 0){?>
            <li role="presentation" ><a href="#material-empaque" aria-controls="material-empaque" role="tab" data-toggle="tab">Material de empaque <span class="badge"><?= count($conMateriales)?></span></a></li>
            <?php }?>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="presentacion_producto">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE; '>Codigo</th>                        
                                        <th scope="col" style='background-color:#B9D5CE; '>Presentacion del producto</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>Cantidad proyectada</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>Cantidad real</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>Porcentaje</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($conPresentacion as $val):?>
                                        <tr style='font-size:90%;'>
                                            <td><?= $val->codigo_producto?></td>
                                            <td><?= $val->nombre_producto?></td>
                                            <td style="text-align: right;"><?= ''. number_format($val->cantidad_proyectada,0)?></td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="cantidad_real[]" style = "text-align: right;" value="<?= $val->cantidad_real ?>"  size="15"> </td>
                                            <td><?= $val->porcentaje_rendimiento?></td>
                                            <?php if($model->autorizado == 0){?>
                                                <td style= 'width: 25px; height: 25px;'>
                                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_ensamble', 'id' => $model->id_ensamble, 'id_detalle' => $val->id, 'token' =>$token], [
                                                                  'class' => '',
                                                                  'data' => [
                                                                      'confirm' => 'Esta seguro de eliminar el registro?',
                                                                      'method' => 'post',

                                                                  ],
                                                    ])?>
                                                </td> 
                                            <?php  }else{?>
                                                <td style= 'width: 25px; height: 25px;'></td>
                                            <?php }?>    
                                                <input type="hidden" name="listado_presentacion[]" value="<?= $val->id?>"> 
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>   
                          <?php if($model->autorizado == 0){?>
                            <div class="panel-footer text-right">  
                                 <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Refrescar', ['orden-ensamble-producto/cargar_nuevamente_items', 'id' => $model->id_ensamble, 'id_orden_produccion' => $model->id_orden_produccion, 'token' => $token],[ 'class' => 'btn btn-info btn-sm']) ?>
                                 <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_listado_presentacion'])?>
                            </div> 
                    <?php }?>
                    </div>
                </div>
            </div>
            <!-- TERMINA TABS-->
             <div role="tabpanel" class="tab-pane" id="material-empaque">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE; '>Material de empaque</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>U. Solicitadas</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>U. Devolucion</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>U. Averias</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>U. Utilizadas</th>
                                        <th scope="col" style='background-color:#B9D5CE; '>U. Sala tecnica</th>
                                        <th scope="col" style='background-color:#B9D5CE; '>U. Retencion</th>
                                        <th scope="col" style='background-color:#B9D5CE; '>U. Reales</th>
                                        <th scope="col" style='background-color:#B9D5CE; '></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($conMateriales as $val):?>
                                        <tr style='font-size:90%;'>
                                            <td><?= $val->materiaPrima->materia_prima?></td>
                                            <td style="text-align: right;"><?= ''.number_format($val->unidades_solicitadas,0)?></td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="unidades_devolucion[]" style = "text-align: right;" value="<?= $val->unidades_devolucion ?>"  size="5"> </td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="unidades_averias[]" style = "text-align: right;" value="<?= $val->unidades_averias ?>"  size="5"> </td>
                                            <td style="text-align: right;"><?= ''.number_format($val->unidades_utilizadas,0)?></td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="unidades_sala_tecnica[]" style = "text-align: right;" value="<?= $val->unidades_sala_tecnica ?>"  size="5"> </td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="unidades_muestra_retencion[]" style = "text-align: right;" value="<?= $val->unidades_muestra_retencion ?>"  size="5"> </td> 
                                            <td style="text-align: right;"><?= ''.number_format($val->unidades_reales,0)?></td>
                                            <?php if($model->autorizado == 0){?>
                                                <td style= 'width: 25px; height: 25px;'>
                                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_empaque', 'id' => $model->id_ensamble, 'id_detalle' => $val->id, 'token' =>$token], [
                                                                  'class' => '',
                                                                  'data' => [
                                                                      'confirm' => 'Esta seguro de eliminar el registro?',
                                                                      'method' => 'post',

                                                                  ],
                                                    ])?>
                                                </td> 
                                            <?php  }else{?>
                                                <td style= 'width: 25px; height: 25px;'></td>
                                            <?php }?>    
                                                <input type="hidden" name="listado_empaque[]" value="<?= $val->id?>"> 
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>   
                        <div class="panel-footer text-right">  
                            <?php if($model->autorizado == 1){
                                if(count($conMateriales) > 0){?>
                                    <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Refrescar', ['orden-ensamble-producto/buscar_material_empaque', 'id' => $model->id_ensamble, 'token' => $token, 'id_solicitud' => 2],[ 'class' => 'btn btn-info btn-sm']) ?>
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_material_empaque'])?>
                                <?php }else{ ?>
                                    <?= Html::a('<span class="glyphicon glyphicon-search"></span> Material de empaque', ['orden-ensamble-producto/buscar_material_empaque', 'id' => $model->id_ensamble, 'token' => $token, 'id_solicitud' => 2],[ 'class' => 'btn btn-info btn-sm']) ?>
                                <?php }
                            } ?>
                        </div>     
                    </div>
                </div>
            </div>
            <!-- TERMINA TABS-->
        </div>
    </div>   
    <?php ActiveForm::end(); ?> 
</div> 

