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

use app\models\MateriaPrimas;
/* @var $this yii\web\View */
/* @var $model app\models\Ordenproduccion */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Entrada materia prima', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_entrada;
$view = 'entrada-materia-prima';
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
                <?= Html::a('<span class="glyphicon glyphicon-send"></span> Actualizar materia prima', ['enviarmateriales', 'id' => $model->id_entrada, 'token'=> $token, 'id_compra' => $model->id_orden_compra],['class' => 'btn btn-info btn-sm',
                           'data' => ['confirm' => 'Esta seguro de actualizar el inventario de materia prima.', 'method' => 'post']]);?>
            <?php }else{ ?>
               <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 15, 'codigo' => $model->id_entrada,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm'])?>  
            <?php }    
        }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            ENTRADA MATERIA PRIMA
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_entrada") ?></th>
                    <td><?= Html::encode($model->id_entrada) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_proveedor') ?></th>
                    <td><?= Html::encode($model->proveedor->nombre_completo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_orden_compra') ?></th>
                    <td><?= Html::encode($model->ordenCompra->descripcion) ?></td>
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
            <li role="presentation" class="active"><a href="#entrada" aria-controls="entrada" role="tab" data-toggle="tab">Detalle entrada <span class="badge"><?= count($detalle_entrada) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="entrada">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 85%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Codigo</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Insumos</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Editar precio</th>  
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>F. vencimiento</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Iva</th>       
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. unitario</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Numero lote</th>  
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                            
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_entrada as $val):?>
                                            <tr style="font-size: 85%;">
                                                
                                                <?php  if($model->autorizado == 0){
                                                    if($val->id_materia_prima === (NULL)){?>
                                                         <td style="padding-left: 1;padding-right: 0;"><?= Html::dropDownList('id_materia_prima[]', $val->id_materia_prima, $materiaprima, ['class' => 'col-sm-13', 'prompt' => 'Seleccion...', 'required' => true]) ?></td>
                                                         <td><?= 'No Found'?></td>
                                                    <?php }else{?>    
                                                        <td style="padding-left: 1;padding-right: 0;"><?= Html::dropDownList('id_materia_prima[]', $val->id_materia_prima, $materiaprima, ['class' => 'col-sm-13', 'prompt' => 'Seleccion...', 'required' => true]) ?></td>
                                                        <td><?= $val->materiaPrima->materia_prima ?></td>
                                                    <?php }?>    
                                                    <td align="center"><select name="actualizar_precio[]" style="width: 60px;">
                                                            <?php if ($val->actualizar_precio == 0){echo $actualizar = "NO";}else{echo $actualizar ="SI";}?>
                                                            <option value="<?= $val->actualizar_precio ?>"><?= $actualizar ?></option>
                                                            <option value="0">NO</option>
                                                            <option value="1">SI</option>

                                                    </select> </td>       
                                                    <td style="padding-right: 1;padding-right: 0; "><input type="date" name="fecha_vcto[]" value="<?= $val->fecha_vencimiento ?>" size="7" required="true"> </td> 
                                                    <td style="padding-left: 1;padding-right: 0;"><?= Html::dropDownList('porcentaje_iva[]', $val->porcentaje_iva, $configuracionIva, ['class' => 'col-sm-10', 'prompt' => 'Seleccione', 'required' => true]) ?></td>
                                                    <td style="padding-right: 1;padding-right: 0; "><input type="text" name="cantidad[]" value="<?= $val->cantidad ?>" size="7" required="true" style="text-align: right"> </td> 
                                                    <td style="padding-right: 1;padding-right: 0;"><input type="text" name="valor_unitario[]" value="<?= $val->valor_unitario ?>" size="7" style="text-align: right"> </td> 
                                                    <td style="padding-right: 1;padding-right: 0;"><input type="text" name="numero_lote[]" value="<?= $val->numero_lote ?>" size="20" style="text-align: right"> </td> 
                                                    
                                                <?php }else{?>
                                                  <td><?= $val->materiaPrima->codigo_materia_prima ?></td>    
                                                  <td><?= $val->materiaPrima->materia_prima ?></td>  
                                                  <td style="text-align: left"><?= $val->actualizarPrecio ?></td> 
                                                   <td style="text-align: right"><?= $val->fecha_vencimiento ?></td>  
                                                  <td style="text-align: right"><?= $val->porcentaje_iva ?>%</td>  
                                                  <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                  <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                  <td style="text-align: right"><?= $val->numero_lote ?></td>
                                                
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

                                                        </div>    
                                                    </td>    
                                                <?php }else{ ?>
                                                    <td style= 'width: 25px; height: 25px;'></td>
                                                <?php }?>    
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <div class="panel-footer text-right">  
                                <?php 
                                if($model->autorizado == 0){
                                   if ($sw <> 0){?>
                                        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Linea', ['entrada-materia-prima/nuevalinea', 'id' => $model->id_entrada, 'token' => $token], ['class' => 'btn btn-primary btn-sm']); ?>        
                                        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizarlineas']);?>    
                                    <?php }else{?>
                                        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Linea', ['entrada-materia-prima/nuevalinea', 'id' => $model->id_entrada, 'token' => $token], ['class' => 'btn btn-primary btn-sm']); ?>        
                                        <?= Html::a('<span class="glyphicon glyphicon-export"></span> Cargar items', ['entrada-materia-prima/importardetallecompra','id' => $model->id_entrada, 'id_orden' => $model->id_orden_compra, 'token' => $token, 'proveedor' => $model->id_proveedor],[ 'class' => 'btn btn-success btn-sm']) ?>                                            
                                        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizarlineas']);?>
                                    <?php }
                                                                          
                                }?>
                            </div>   
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>

   