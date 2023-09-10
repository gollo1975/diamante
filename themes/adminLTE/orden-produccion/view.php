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

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Orden de produccion', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_orden_produccion;
$view = 'orden-produccion';
$medida = ArrayHelper::map(app\models\MedidaProductoTerminado::find()->orderBy ('descripcion ASC')->all(), 'id_medida_producto', 'descripcion');
$iva = ArrayHelper::map(app\models\ConfiguracionIva::find()->orderBy ('valor_iva ASC')->all(), 'valor_iva', 'valor_iva');
?>

<?php
    //$remision = Remision::find()->where(['=', 'idordenproduccion', $model->idordenproduccion])->one();
?>

<div class="orden-produccion-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_orden_produccion], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_orden', 'id' => $model->id_orden_produccion], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        <?php if ($model->autorizado == 0 && $model->numero_orden == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_orden_produccion, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
        } else {
            if ($model->autorizado == 1 && $model->numero_orden == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_orden_produccion, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Generar orden', ['generarorden', 'id' => $model->id_orden_produccion, 'token'=> $token],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Esta seguro de generar el numero de la orden de producción!.', 'method' => 'post']]);
            }else{
                if($model->numero_orden > 0 && $model->numero_lote == 0){
                    echo Html::a('<span class="glyphicon glyphicon-remove"></span> Generar lote', ['generarlote', 'id' => $model->id_orden_produccion, 'token'=> $token, 'fecha_actual' =>$model->fecha_proceso],['class' => 'btn btn-warning btn-sm',
                               'data' => ['confirm' => 'Esta seguro de generar el numero de lote a esta orden de producción!.', 'method' => 'post']]);
                }else{
                    if($token == 0){
                        echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimirordenproduccion', 'id' => $model->id_orden_produccion], ['class' => 'btn btn-default btn-sm']);            
                        echo Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 7, 'codigo' => $model->id_orden_produccion,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']);?>
                        <?= Html::a('<span class="glyphicon glyphicon-export"></span> Exportar a inventarios', ['exportarinventarios', 'id' => $model->id_orden_produccion, 'token'=> $token,'grupo' =>$model->id_grupo],['class' => 'btn btn-info btn-sm',
                                   'data' => ['confirm' => 'Esta seguro de exportar esta orden produccion al modulo de inventarios de productos!.', 'method' => 'post']]);?>
                         <?= Html::a('<span class="glyphicon glyphicon-import"></span> Descargar materia prima', ['descargarmateriaprima', 'id' => $model->id_orden_produccion, 'token'=> $token],['class' => 'btn btn-success btn-sm',
                                   'data' => ['confirm' => 'Esta seguro de enviar el proceso de descargue de materias primas al modulo de inventario de materia prima!.', 'method' => 'post']]); 
                    }else{
                         echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimirordenproduccion', 'id' => $model->id_orden_produccion], ['class' => 'btn btn-default btn-sm']);            
                        echo Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 7, 'codigo' => $model->id_orden_produccion,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']);?>
                   <?php }               
                }    
            }
        }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4>ORDEN DE PRODUCCION</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_orden_produccion") ?></th>
                    <td><?= Html::encode($model->id_orden_produccion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo') ?></th>
                    <td><?= Html::encode($model->grupo->nombre_grupo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_almacen') ?></th>
                    <td><?= Html::encode($model->almacen->almacen) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'subtotal') ?></th>
                     <td style="text-align: right;"><?= Html::encode(''.number_format($model->subtotal,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_orden') ?></th>
                    <td><?= Html::encode($model->numero_orden) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_entrega') ?></th>
                    <td><?= Html::encode($model->fecha_entrega) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'iva') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->iva,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_lote')?></th>
                    <td><?= Html::encode($model->numero_lote) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoOrden) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_registro') ?></th>
                    <td><?= Html::encode($model->fecha_registro) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_orden') ?></th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->total_orden,0)) ?></td>                    
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cerrar_orden') ?></th>
                    <td><?= Html::encode($model->cerrarOrden) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'tipo_orden') ?></th>
                    <td><?= Html::encode($model->tipoOrden) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'unidades') ?></th>
                     <td style="text-align: right"><?= Html::encode(''.number_format($model->unidades,0)) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'costo_unitario') ?></th>
                     <td style="text-align: right"><?= Html::encode(''.number_format($model->costo_unitario,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'responsable') ?></th>
                    <td><?= Html::encode($model->responsable) ?></td>
                     <th style='background-color:#F0F3EF;'></th>
                    <td></td>
                     <th style='background-color:#F0F3EF;'></th>
                    <td></td>
                </tr>
                <tr style="font-size: 90%;">
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
            <li role="presentation" class="active"><a href="#listadomateriales" aria-controls="listadomateriales" role="tab" data-toggle="tab">Listado materiales <span class="badge"><?= count($detalle_materia) ?></span></a></li>
            <li role="presentation"><a href="#detalleorden" aria-controls="detalleorden" role="tab" data-toggle="tab">Listado productos <span class="badge"><?= count($detalle_orden) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="listadomateriales">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Código material</b></th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Materia prima</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Stock</th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Tipo medida</th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. unitario</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Porcentaje Iva</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><span title="Descargar materia prima del inventario">Imp.</span></th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. Iva</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Subtotal</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Total</th>  
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_materia as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->materiaPrima->codigo_materia_prima ?></td>
                                                <td><?= $val->materiaPrima->materia_prima ?></td>
                                                <?php if($model->autorizado == 0){?>
                                                    <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidad_materia[]" value="<?= $val->cantidad ?>" style="text-align: right" size="9" required="true"> </td> 
                                                <?php }else{?>
                                                   <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                <?php }?>    
                                                   <td style="text-align: right; background-color:#CBAAE3; color: black"><?= ''.number_format($val->materiaPrima->stock,0) ?></td>    
                                                <td><?= $val->materiaPrima->medida->descripcion ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                <td  style="text-align: right"><?= $val->porcentaje_iva ?>%</td>
                                                <?php if($val->importado == 0){?>
                                                   <td style='background-color:#B9D5CE;'><?= $val->documentoExportado ?></td>
                                                <?php }else{ ?>
                                                   <td><?= $val->documentoExportado ?></td>
                                                <?php }?>   
                                                <td style="text-align: right"><?= ''.number_format($val->valor_iva,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->total,0) ?></td>
                                                <input type="hidden" name="listado_materia[]" value="<?= $val->id_detalle?>">  
                                                <td style= 'width: 25px; height: 25px;'>
                                                    <?php if($model->autorizado == 0){?>
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminarmateria', 'id' => $model->id_orden_produccion, 'detalle' => $val->id_detalle, 'token' => $token], [
                                                                      'class' => '',
                                                                      'data' => [
                                                                          'confirm' => 'Esta seguro de eliminar el registro?',
                                                                          'method' => 'post',
                                                                         
                                                                      ],
                                                                  ])?>
                                                       
                                                        <?php }?>         
                                                </td>     
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <div class="panel-footer text-right">  
                                <?php 
                                if($model->autorizado == 0){?>
                                    <?= Html::a('<span class="glyphicon glyphicon-search"></span> Materia prima', ['orden-produccion/buscarmateriaprima', 'id' => $model->id_orden_produccion, 'token' => $token],[ 'class' => 'btn btn-primary btn-sm']) ?>                                            
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizamateriaprima'])?>
                                <?php }?>
                            </div>   
                        </div>
                    </div>
                </div>  
                <!--TERMINA TABS MATERIAS-->
                <div role="tabpanel" class="tab-pane" id="detalleorden">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Id</b></th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Código producto</b></th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE; width: 25%'>Descripción producto</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Tipo medida</th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Numero lote</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>F. Vcto</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>User name</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Aplica Iva</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Iva</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cerrado</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><span title="Registro exportado a inventarios">Exp.</span></th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_orden as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->id_detalle ?></td>
                                                <td><?= $val->codigo_producto ?></td>
                                                <?php if($model->autorizado == 0){?>
                                                    <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="descripcion[]" value="<?= $val->descripcion ?>" size ="45" maxlength="40" required="true"> </td> 
                                                    <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidad_producto[]" value="<?= $val->cantidad ?>" style="text-align: right" size="9" required="true"> </td> 
                                                    <td style="padding-left: 1;padding-right: 0;"><?= Html::dropDownList('tipo_medida[]', $val->id_medida_producto, $medida, ['class' => 'col-sm-12', 'prompt' => 'Seleccione', 'required' => true]) ?></td>
                                                    <td style="text-align: right"><?= $val->numero_lote ?></td>  
                                                    <td><?= $val->fecha_vencimiento ?></td>
                                                    <td><?= $val->user_name ?></td>
                                                    <td><?= $val->aplicaIva ?></td>
                                                    <?php if($val->aplica_iva == 0){?>
                                                        <td style="padding-left: 1;padding-right: 0;"><?= Html::dropDownList('porcentaje_iva[]', $val->porcentaje_iva, $iva, ['class' => 'col-sm-11', 'prompt' => 'Iva', 'required' => true]) ?></td>
                                                    <?php }else{?>
                                                        <td><?= '0'?>%</td>    
                                                    <?php }?>    
                                                    <td><?= $val->cerrarLinea ?></td>
                                                <?php }else{?>
                                                   <td><?= $val->descripcion ?></td>
                                                   <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                   <td><?= $val->medidaProducto->descripcion ?></td>
                                                   <td style="text-align: right"><?= $val->numero_lote ?></td>  
                                                   <td><?= $val->fecha_vencimiento ?></td>
                                                   <td><?= $val->user_name ?></td>
                                                   <td><?= $val->aplicaIva ?></td>
                                                   <td><?= $val->porcentaje_iva?>%</td>
                                                   <td><?= $val->cerrarLinea ?></td>
                                                   <td><?= $val->documentoExportado ?></td>
                                                <?php }?>   
                                                <input type="hidden" name="listado_producto[]" value="<?= $val->id_detalle?>">  
                                                <td style= 'width: 25px; height: 25px;'>
                                                <?php 
                                                    if($model->autorizado == 0 && $val->importado == 0){?>
                                                       <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar', 'id' => $model->id_orden_produccion, 'detalle' => $val->id_detalle, 'token' => $token], [
                                                                  'class' => '',
                                                                  'data' => [
                                                                      'confirm' => 'Esta seguro de eliminar el registro?',
                                                                      'method' => 'post',
                                                                  ],
                                                              ])
                                                       ?>
                                                     <td style= 'width: 25px; height: 25px;'>
                                                    <?php } else {
                                                        if($model->autorizado == 1 && $val->importado == 0){?>
                                                            <td style="width: 25px; height: 25px;">
                                                                  <!-- Inicio Nuevo Detalle proceso -->
                                                                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                                                        ['/orden-produccion/modificarcantidades', 'id' => $model->id_orden_produccion, 'detalle' => $val->id_detalle, 'token' => $token],
                                                                        [
                                                                            'title' => 'Modificar cantidades de producción',
                                                                            'data-toggle'=>'modal',
                                                                            'data-target'=>'#modalmodificarcantidades'.$val->id_detalle,
                                                                        ])    
                                                                   ?>
                                                                <div class="modal remote fade" id="modalmodificarcantidades<?= $val->id_detalle ?>">
                                                                    <div class="modal-dialog modal-lg" style ="width: 500px;">
                                                                        <div class="modal-content"></div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        <?php }else{?>
                                                            <td style="width: 25px; height: 25px;">
                                                        <?php }         
                                                    }?>
                                                    </div>    
                                                </td>     
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <div class="panel-footer text-right">  
                                <?php 
                                if($model->autorizado == 0){
                                    if($model->tipo_orden == 0){?>
                                        <?= Html::a('<span class="glyphicon glyphicon-search"></span> Buscar producto', ['orden-produccion/buscarproducto', 'id' => $model->id_orden_produccion, 'token' => $token, 'grupo' => $model->id_grupo],[ 'class' => 'btn btn-primary btn-sm']) ?>                                            
                                        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizaregistro'])?>
                                    <?php }else{ ?>   
                                        <!-- Inicio Nuevo Detalle proceso -->
                                        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear producto',
                                              ['/orden-produccion/crearproducto', 'id' => $model->id_orden_produccion, 'token' => $token, 'grupo' => $model->id_grupo],
                                                ['title' => 'Crear producto nuevos',
                                                 'data-toggle'=>'modal',
                                                 'data-target'=>'#modalcrearproducto',
                                                 'class' => 'btn btn-info btn-xs'
                                                ])    
                                        ?>
                                        <div class="modal remote fade" id="modalcrearproducto">
                                               <div class="modal-dialog modal-lg" style ="width: 500px;">    
                                                   <div class="modal-content"></div>
                                               </div>
                                        </div>
                                        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear codigos', ['crearcodigoproducto', 'id' => $model->id_orden_produccion, 'token' => $token], ['class' => 'btn btn-primary btn-sm'])?>
                                       <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizaregistro']) ?>
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

   