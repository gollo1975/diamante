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

use app\models\InventarioProductos;
/* @var $this yii\web\View */
/* @var $model app\models\Ordenproduccion */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Entrada prodeucto terminado', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_entrada;
$view = 'entrada-producto-terminado';
$configuracionIva = ArrayHelper::map(app\models\ConfiguracionIva::find()->orderBy ('valor_iva ASC')->all(), 'valor_iva', 'valor_iva');
?>

<div class="entrada-materia-prima-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_entrada], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_entradas', 'id' => $model->id_entrada], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        <?php if ($model->autorizado == 0 && $model->enviar_materia_prima  == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_entrada, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
        } else {
            if ($model->autorizado == 1 && $model->enviar_materia_prima  == 0) {?> 
                <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_entrada, 'token' => $token], ['class' => 'btn btn-default btn-sm'])?>
                <?= Html::a('<span class="glyphicon glyphicon-send"></span> Actualizar producto', ['enviarinventario', 'id' => $model->id_entrada, 'token'=> $token, 'id_compra' => $model->id_orden_compra],['class' => 'btn btn-info btn-sm',
                           'data' => ['confirm' => 'Esta seguro de actualizar el inventario de producto terminado.', 'method' => 'post']]);?>
            <?php }else{ ?>
               <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 6, 'codigo' => $model->id_entrada,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm'])?>  
            <?php }    
        }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            ENTRADA PRODUCTO TERMINADO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_entrada") ?></th>
                    <td><?= Html::encode($model->id_entrada) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_proveedor') ?></th>
                    <td><?= Html::encode($model->proveedor->nombre_completo) ?></td>
                    <?php if($model->id_orden_compra == null){?>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Orden') ?></th>
                        <td><?= Html::encode('NON FOUND') ?></td>
                    <?php }else{?>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_orden_compra') ?></th>
                        <td><?= Html::encode($model->ordenCompra->descripcion) ?></td>
                    <?php }?>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'subtotal') ?></th>
                     <td style="text-align: right;"><?= Html::encode(''.number_format($model->subtotal,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_soporte') ?></th>
                    <td><?= Html::encode($model->numero_soporte) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name_crear')?></th>
                    <td><?= Html::encode($model->user_name_crear) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name_edit') ?></th>
                    <td><?= Html::encode($model->user_name_edit) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'impuesto') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->impuesto,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                   
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_registro') ?></th>
                    <td><?= Html::encode($model->fecha_registro) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoCompra) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_salida') ?></th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->total_salida,0)) ?></td>                    
                </tr>
                <tr style="font-size: 90%;">
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'enviar_materia_prima') ?></th>
                    <td><?= Html::encode($model->enviarMateria) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td  colspan="6"><?= Html::encode($model->observacion) ?></td>
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
            <li role="presentation" class="active"><a href="#entrada" aria-controls="entrada" role="tab" data-toggle="tab">Entrada productos <span class="badge"><?= count($detalle_entrada) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="entrada">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Codigo</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Descripción</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Editar precio</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Lote</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>F. vcto</th>  
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Cant.</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. unitario</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Impuesto</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Subtotal</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Total</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                            
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_entrada as $val):?>
                                            <tr style="font-size: 90%;">
                                                
                                                <?php  if($model->autorizado == 0){
                                                    if($val->id_inventario === (NULL)){?>
                                                         <td style="padding-left: 1;padding-right: 0;"><?= Html::dropDownList('id_inventario[]', $val->id_inventario, $inventario, ['class' => 'col-sm-13', 'prompt' => 'Seleccion...', 'required' => true]) ?></td>
                                                         <td><?= 'No Found'?></td>
                                                    <?php }else{?>    
                                                        <td style="padding-left: 1;padding-right: 0;"><?= Html::dropDownList('id_inventario[]', $val->id_inventario, $inventario, ['class' => 'col-sm-13', 'prompt' => 'Seleccion...', 'required' => true]) ?></td>
                                                        <td><?= $val->inventario->nombre_producto ?></td>
                                                    <?php }?>    
                                                    <td align="center"><select name="actualizar_precio[]" style="width: 60px;">
                                                            <?php if ($val->actualizar_precio == 0){echo $actualizar = "NO";}else{echo $actualizar ="SI";}?>
                                                            <option value="<?= $val->actualizar_precio ?>"><?= $actualizar ?></option>
                                                            <option value="0">NO</option>
                                                            <option value="1">SI</option>

                                                    </select> </td>  
                                                     <td style="padding-right: 1;padding-right: 0; "><input type="text" name="numero_lote[]" value="<?= $val->numero_lote ?>" size="10" required="true" style="text-align: right"> </td> 
                                                    <td style="padding-right: 1;padding-right: 0; "><input type="date" name="fecha_vcto[]" value="<?= $val->fecha_vencimiento ?>" size="7" required="true"> </td> 
                                                    <td style="padding-right: 1;padding-right: 0; "><input type="text" name="cantidad[]" value="<?= $val->cantidad ?>" size="7" required="true" style="text-align: right"> </td> 
                                                    <td style="padding-right: 1;padding-right: 0;"><input type="text" name="valor_unitario[]" value="<?= $val->valor_unitario ?>" size="7" style="text-align: right"> </td> 
                                                    <td style="text-align: right"><?= ''.number_format($val->total_iva,0) ?></td>
                                                    <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                    <td style="text-align: right"><?= ''.number_format($val->total_entrada,0) ?></td>
                                                <?php }else{?>
                                                  <td><?= $val->inventario->codigo_producto ?></td>    
                                                  <td><?= $val->inventario->nombre_producto ?></td>  
                                                  <td style="text-align: left"><?= $val->actualizarPrecio ?></td> 
                                                  <td style="text-align: right"><?= $val->numero_lote ?></td> 
                                                  <td style="text-align: right"><?= $val->fecha_vencimiento ?></td>  
                                                  <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                  <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                  <td style="text-align: right"><?= ''.number_format($val->total_iva,0) ?></td>
                                                  <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                  <td style="text-align: right"><?= ''.number_format($val->total_entrada,0) ?></td>
                                                <?php }?>   
                                                
                                            <input type="hidden" name="detalle_entrada[]" value="<?= $val->id_detalle ?>">
                                                <?php if($model->autorizado == 0){?>
                                                    <td style= 'width: 25px; height: 25px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar', 'id' => $model->id_entrada, 'detalle' => $val->id_detalle, 'token' => $token], [
                                                                   'class' => '',
                                                                   'data' => [
                                                                       'confirm' => 'Esta seguro de eliminar el registro?',
                                                                       'method' => 'post',
                                                                   ],
                                                               ])
                                                        ?>
                                                    </td>    
                                                <?php }else{ ?>
                                                    <td style= 'width: 25px; height: 25px;'></td>
                                                <?php }?>    
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                                <div class="panel-footer text-right">  
                                    <?php 
                                    if($model->autorizado == 0 && count($detalle_entrada) == 0){?>
                                            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Linea', ['entrada-producto-terminado/nuevalinea', 'id' => $model->id_entrada, 'token' => $token], ['class' => 'btn btn-primary btn-sm']); ?>        
                                            <?= Html::a('<span class="glyphicon glyphicon-export"></span> Cargar orden', ['entrada-producto-terminado/importardetallecompra','id' => $model->id_entrada, 'id_orden' => $model->id_orden_compra, 'token' => $token, 'proveedor' => $model->id_proveedor],[ 'class' => 'btn btn-success btn-sm']) ?>                                            
                                            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizarlineas']);?>
                                   <?php }else{
                                       if($model->autorizado == 0 && count($detalle_entrada) > 0){?>
                                            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Linea', ['entrada-producto-terminado/nuevalinea', 'id' => $model->id_entrada, 'token' => $token], ['class' => 'btn btn-primary btn-sm']); ?>        
                                            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizarlineas']);?>
                                       <?php }?>                                      
                                    
                                   <?php }?> 
                                </div>   
                                 <?php ActiveForm::end(); ?>  
                            </div>
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
            </div>  
    
    </div>
</div>

   