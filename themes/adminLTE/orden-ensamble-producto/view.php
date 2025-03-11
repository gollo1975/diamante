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
if($sw == 0){
    
}else{
   // Yii::$app->getSession()->setFlash('info', 'Esta orden de produccion ya tiene una ORDENES DE ENSAMBLE creadas. Validar la informacion.'); 
}
?>
<div class="orden-ensamble-producto-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']); ?>
        <?php if($model->autorizado == 0){
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_ensamble, 'token' => $token, 'sw' => $sw], ['class' => 'btn btn-default btn-sm']);
        }else{
            if($model->autorizado == 1 && $model->cerrar_orden_ensamble == 0){
                echo Html::a('<span class="glyphicon glyphicon-ok"></span> Desautorizar', ['autorizado', 'id' => $model->id_ensamble, 'token' => $token, 'sw' => $sw], ['class' => 'btn btn-default btn-sm']); 
                
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Generar orden', ['generar_orden_ensamble', 'id' => $model->id_ensamble,'token' => $token, 'sw' => $sw],['class' => 'btn btn-warning btn-sm',
                                    'data' => ['confirm' => 'Esta seguro de GENERAR la orden de ensamble para la OP No ('.$model->ordenProduccion->numero_orden.').', 'method' => 'post']]);   
                 
                 echo Html::a('<span class="glyphicon glyphicon-check"></span> Aprobar conceptos',
                        ['/orden-ensamble-producto/subir_responsable','id' =>$model->id_ensamble, 'token' => $token, 'sw' => $sw],
                        [
                            'title' => 'Permite subir informacion de los responsables',
                            'data-toggle'=>'modal',
                            'data-target'=>'#modalsubirresponsable',
                            'class' => 'btn btn-info btn-sm'
                        ])?>
                        
                <div class="modal remote fade" id="modalsubirresponsable">
                         <div class="modal-dialog modal-lg" style ="width: 650px;">
                            <div class="modal-content"></div>
                        </div>
                </div>
            <?php }else{
                if($model->cerrar_orden_ensamble == 1 && $model->cerrar_proceso == 0){
                    echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar orden ensamble', ['cerrar_orden_ensamble', 'id' => $model->id_ensamble,'token' => $token ,'sw' => $sw],['class' => 'btn btn-default btn-sm',
                                        'data' => ['confirm' => 'Esta seguro de CERRAR la orden de ensamble No ('.$model->numero_orden_ensamble.'). Favor validar las cantidades reales en el sistema y modificar las unidades reales.', 'method' => 'post']]);   
                    echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_orden_ensamble', 'id' => $model->id_ensamble], ['class' => 'btn btn-default btn-sm']);
                }else {
                    if ($model->proceso_auditado == 0){
                        echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_orden_ensamble', 'id' => $model->id_ensamble], ['class' => 'btn btn-default btn-sm']);
                    }else{
                         echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_orden_ensamble', 'id' => $model->id_ensamble], ['class' => 'btn btn-default btn-sm']);
                    }    
                }    
            }  
        }?>    
    </p>
     <div class="panel panel-success">
        <div class="panel-heading">
            DETALLE DE LA ORDEN DE ENSAMBLE
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_ensamble') ?></th>
                    <td><?= Html::encode($model->id_ensamble) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_orden_ensamble') ?></th>
                    <td><?= Html::encode($model->numero_orden_ensamble) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_etapa') ?></th>
                    <td><?= Html::encode($model->etapa->concepto) ?></td>   
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo') ?></th>
                    <td><?= Html::encode($model->grupo->nombre_grupo) ?></td>
              </tr>
               <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_lote') ?></th>
                    <td><?= Html::encode($model->numero_lote) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_orden_produccion') ?></th>
                    <td><?= Html::encode($model->id_orden_produccion) ?></td>   
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Unidad_lote') ?></th>
                      <td style="text-align: right"><?= Html::encode(''.number_format($model->total_unidades,0)) ?></td>
              </tr>
              <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td> 
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'peso_neto') ?></th>
                     <td><?= Html::encode($model->peso_neto) ?></td> 
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_hora_cierre') ?></th>
                    <td><?= Html::encode($model->fecha_hora_cierre) ?></td> 
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'responsable') ?></th>
                    <td><?= Html::encode($model->responsable) ?></td>
              </tr>
              <tr style ='font-size:85%;'>
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
    ]);
    ?>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <?php if(count($conMateriales) == 0){?>
                <li role="presentation" class="active"><a href="#presentacion_producto" aria-controls="presentacion_producto" role="tab" data-toggle="tab">Presentacion del producto  <span class="badge"><?= count($conPresentacion) ?></span></a></li>
            <?php }else{?>
                <li role="presentation"><a href="#presentacion_producto" aria-controls="presentacion_producto" role="tab" data-toggle="tab">Presentacion del producto  <span class="badge"><?= count($conPresentacion) ?></span></a></li>
                <li role="presentation" class="active" ><a href="#material-empaque" aria-controls="material-empaque" role="tab" data-toggle="tab">Material de empaque <span class="badge"><?= count($conMateriales)?></span></a></li>
            <?php }?>
        </ul>
        <div class="tab-content">
            <?php if(count($conMateriales) == 0){
                ?> <!--INIICO EL TABS-->
                <div role="tabpanel" class="tab-pane active" id="presentacion_producto">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style='font-size:85%;'>
                                            <th scope="col" style='background-color:#B9D5CE; '>Codigo</th>                        
                                            <th scope="col" style='background-color:#B9D5CE; '>Presentacion del producto</th> 
                                            <th scope="col" style='background-color:#B9D5CE; '>Cantidad proyectada</th> 
                                            <th scope="col" style='background-color:#B9D5CE; '>Cantidad real</th> 
                                            <th scope="col" style='background-color:#B9D5CE; '>Importado</th> 
                                            <th scope="col" style='background-color:#B9D5CE; '>Porcentaje rendimiento</th> 
                                            <th scope="col" style='background-color:#B9D5CE; '></th> 
                                            <th scope="col" style='background-color:#B9D5CE; '></th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($conPresentacion as $val):
                                            $empacada = app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_presentacion', $val->id])->one();
                                            ?>
                                            <tr style='font-size:85%;'>
                                                <td><?= $val->codigo_producto?></td>
                                                <td><?= $val->nombre_producto?></td>
                                                <td style="text-align: right;"><?= ''. number_format($val->cantidad_proyectada,0)?></td>
                                                <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="cantidad_real[]" style = "text-align: right;" value="<?= $val->cantidad_real ?>"  size="15"> </td>
                                                  <td><?= $val->importadoRegistro?></td>
                                                <td style="text-align: right;"><?= $val->porcentaje_rendimiento?>%</td>
                                                <?php if($model->autorizado == 0 ){?>
                                                    <td style= 'width: 25px; height: 25px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_ensamble', 'id' => $model->id_ensamble, 'id_detalle' => $val->id, 'token' =>$token, 'sw' => $sw], [
                                                                      'class' => '',
                                                                      'data' => [
                                                                          'confirm' => 'Esta seguro de eliminar el registro?',
                                                                          'method' => 'post',

                                                                      ],
                                                        ])?>
                                                    </td> 
                                                    <?php if(!$empacada){?>
                                                        <td style= 'width: 25px; height: 20px;'>
                                                           <a href="<?= Url::toRoute(["orden-ensamble-producto/buscar_material_empaque", 'id' => $model->id_ensamble, 'token' => $token, 'id_solicitud' => 2, 'sw' => $sw, 'id_producto' => $val->id])?>"><span class="glyphicon glyphicon-search" title ="Permite descargar el materia de empaque."></span></a>
                                                       </td>
                                                    <?php }else{
                                                        ?>
                                                       <td style= 'width: 25px; height: 25px;'></td>
                                                    <?php }   
                                                   }else{
                                                    if ($model->cerrar_orden_ensamble == 1 && $model->cerrar_proceso == 0){ 
                                                        ?>
                                                        <td style= 'width: 25px; height: 25px;'>
                                                            <!-- Inicio Nuevo Detalle proceso -->
                                                                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                                                    ['/orden-ensamble-producto/modificar_cantidades', 'id' => $model->id_ensamble, 'detalle' => $val->id, 'token' => $token,  'codigo' => $val->id_detalle, 'sw' => $sw],
                                                                    [
                                                                        'title' => 'Modificar cantidades de producción',
                                                                        'data-toggle'=>'modal',
                                                                        'data-target'=>'#modalsubirnuevasunidades'.$val->id,
                                                                    ])    
                                                               ?>
                                                            <div class="modal remote fade" id="modalsubirnuevasunidades<?= $val->id ?>">
                                                                <div class="modal-dialog modal-lg" style ="width: 550px;">
                                                                    <div class="modal-content"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    <?php }else{?>
                                                        <td style= 'width: 25px; height: 25px;'></td>
                                                        <td style= 'width: 25px; height: 25px;'></td>
                                                    <?php }  
                                                }?>    
                                                    <input type="hidden" name="listado_presentacion[]" value="<?= $val->id?>"> 
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>   
                            <?php 
                            if($model->autorizado == 0){?>
                                <div class="panel-footer text-right">  
                                     <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Refrescar', ['orden-ensamble-producto/cargar_nuevamente_items', 'id' => $model->id_ensamble, 'id_orden_produccion' => $model->id_orden_produccion, 'token' => $token, 'sw' => $sw],[ 'class' => 'btn btn-info btn-sm']) ?>
                                     <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_listado_presentacion'])?>
                                </div> 
                            <?php }else{
                                if((count($conMateriales) == 0)){?>
                                   <div class="panel-footer text-right"> 
                                        <?= Html::a('<span class="glyphicon glyphicon-search"></span> Simular material empaque', ['orden-ensamble-producto/simulador_material_empaque', 'id' => $model->id_ensamble, 'token' => $token, 'grupo' => $model->id_grupo, 'sw' => $sw],[ 'class' => 'btn btn-info btn-sm']) ?>                                            
                                   </div>
                                <?php }
                            }?>                       
                        </div>
                    </div>
                </div>
                <!-- TERMINA TABS-->
            <?php }else{
                ?> 
                 <div role="tabpanel" class="tab-pane" id="presentacion_producto">
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
                                            <th scope="col" style='background-color:#B9D5CE; '>Importado</th> 
                                            <th scope="col" style='background-color:#B9D5CE; '>Porcentaje rendimiento</th> 
                                            <th scope="col" style='background-color:#B9D5CE; '></th> 
                                            <th scope="col" style='background-color:#B9D5CE; '></th> 
                                            <th scope="col" style='background-color:#B9D5CE; '></th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($conPresentacion as $val):
                                            $empacada = app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_presentacion', $val->id])->one();
                                            ?>
                                            <tr style='font-size:90%;'>
                                                <td><?= $val->codigo_producto?></td>
                                                <td><?= $val->nombre_producto?></td>
                                                <td style="text-align: right;"><?= ''. number_format($val->cantidad_proyectada,0)?></td>
                                                <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="cantidad_real[]" style = "text-align: right;" value="<?= $val->cantidad_real ?>"  size="15"> </td>
                                                  <td><?= $val->importadoRegistro?></td>
                                                <td style="text-align: right;"><?= $val->porcentaje_rendimiento?>%</td>
                                                <?php if($model->autorizado == 0 ){?>
                                                    <td style= 'width: 25px; height: 25px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_ensamble', 'id' => $model->id_ensamble, 'id_detalle' => $val->id, 'token' =>$token, 'sw' => $sw], [
                                                                      'class' => '',
                                                                      'data' => [
                                                                          'confirm' => 'Esta seguro de eliminar el registro?',
                                                                          'method' => 'post',

                                                                      ],
                                                        ])?>
                                                    </td> 
                                                    <td style= 'width: 25px; height: 25px;'>
                                                            <!-- Inicio Nuevo Detalle proceso -->
                                                                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                                                    ['/orden-ensamble-producto/modificar_cantidades', 'id' => $model->id_ensamble, 'detalle' => $val->id, 'token' => $token,  'codigo' => $val->id_detalle, 'sw' => $sw],
                                                                    [
                                                                        'title' => 'Modificar cantidades de producción',
                                                                        'data-toggle'=>'modal',
                                                                        'data-target'=>'#modalsubirnuevasunidades'.$val->id,
                                                                    ])    
                                                               ?>
                                                            <div class="modal remote fade" id="modalsubirnuevasunidades<?= $val->id ?>">
                                                                <div class="modal-dialog modal-lg" style ="width: 550px;">
                                                                    <div class="modal-content"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    <?php if(!$empacada){?>
                                                        <td style= 'width: 25px; height: 20px;'>
                                                           <a href="<?= Url::toRoute(["orden-ensamble-producto/buscar_material_empaque", 'id' => $model->id_ensamble, 'token' => $token, 'id_solicitud' => 2, 'sw' => $sw, 'id_producto' => $val->id])?>"><span class="glyphicon glyphicon-search" title ="Permite descargar el materia de empaque."></span></a>
                                                       </td>
                                                    <?php }else{
                                                        ?>
                                                       <td style= 'width: 25px; height: 25px;'></td>
                                                       
                                                    <?php }   
                                                }else{
                                                    if ($model->cerrar_orden_ensamble == 1 && $model->cerrar_proceso == 0){ 
                                                        ?>
                                                        <td style= 'width: 25px; height: 25px;'>
                                                            <!-- Inicio Nuevo Detalle proceso -->
                                                                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                                                    ['/orden-ensamble-producto/modificar_cantidades', 'id' => $model->id_ensamble, 'detalle' => $val->id, 'token' => $token,  'codigo' => $val->id_detalle, 'sw' => $sw],
                                                                    [
                                                                        'title' => 'Modificar cantidades de producción',
                                                                        'data-toggle'=>'modal',
                                                                        'data-target'=>'#modalsubirnuevasunidades'.$val->id,
                                                                    ])    
                                                               ?>
                                                            <div class="modal remote fade" id="modalsubirnuevasunidades<?= $val->id ?>">
                                                                <div class="modal-dialog modal-lg" style ="width: 550px;">
                                                                    <div class="modal-content"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                         <td style= 'width: 25px; height: 25px;'></td>
                                                    <?php }else{?>
                                                        <td style= 'width: 25px; height: 25px;'></td>
                                                        <td style= 'width: 25px; height: 25px;'></td>
                                                    <?php }?> 
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
                                     <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Refrescar', ['orden-ensamble-producto/cargar_nuevamente_items', 'id' => $model->id_ensamble, 'id_orden_produccion' => $model->id_orden_produccion, 'token' => $token, 'sw' => $sw],[ 'class' => 'btn btn-info btn-sm']) ?>
                                     <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_listado_presentacion'])?>
                                </div> 
                            <?php }else{
                                if((count($conMateriales) == 0)){?>
                                   <div class="panel-footer text-right"> 
                                        <?= Html::a('<span class="glyphicon glyphicon-search"></span> Simular material empaque', ['orden-ensamble-producto/simulador_material_empaque', 'id' => $model->id_ensamble, 'token' => $token, 'grupo' => $model->id_grupo, 'sw' => $sw],[ 'class' => 'btn btn-info btn-sm']) ?>                                            
                                   </div>
                                <?php }
                            }?>                       
                        </div>
                    </div>
                </div>
                <!-- TERMINA TABS-->
                <div role="tabpanel" class="tab-pane active" id="material-empaque">
                   <div class="table-responsive">
                       <div class="panel panel-success">
                           <div class="panel-body">
                               <table class="table table-bordered table-hover">
                                   <thead>
                                       <tr style='font-size:85%;'>
                                           <th scope="col" style='background-color:#B9D5CE; '>Presentacion</th> 
                                           <th scope="col" style='background-color:#B9D5CE; '>Material de empaque</th> 
                                           <th scope="col" style='background-color:#B9D5CE; '>Estado</th>
                                           <th scope="col" style='background-color:#B9D5CE; '>Stock</th>
                                           <th scope="col" style='background-color:#B9D5CE; '>U. Solicitadas</th> 
                                           <th scope="col" style='background-color:#B9D5CE; '>U. Devolucion</th> 
                                           <th scope="col" style='background-color:#B9D5CE; '>U. Averias</th> 
                                           <th scope="col" style='background-color:#B9D5CE; '>U. Envasadas</th>
                                           <th scope="col" style='background-color:#B9D5CE; '>U. Sala tecnica</th>
                                           <th scope="col" style='background-color:#B9D5CE; '>U. Retencion</th>
                                           <th scope="col" style='background-color:#B9D5CE; '>U. Reales</th>
                                           <th scope="col" style='background-color:#B9D5CE; '><span title="Importar registro">Imp.</span></th>
                                           <th scope="col" style='background-color:#B9D5CE; '></th>
                                          <th scope="col" style='background-color:#B9D5CE; text-align: center;'><input type="checkbox" onclick="marcar(this);"/></th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       <?php 
                                       foreach ($conMateriales as $val):
                                           ?>
                                           <tr style='font-size:85%;'>
                                                <td><?= $val->productoEmpaque->nombre_producto?></td>
                                                <?php if($val->alerta == 'FALTA'){?>
                                                   <td style='background-color:#F1F3E3'><?= $val->materiaPrima->materia_prima?></td>
                                                <?php } else { ?>
                                                   <td><?= $val->materiaPrima->materia_prima?></td>
                                                <?php }?>
                                                <td><?= $val->alerta?></td>
                                                <?php if($val->importado == 0){?>
                                                    <td style="text-align: right"><?= ''. number_format($val->stock,0) ?></td>
                                                    <td style="text-align: right;"><?= ''.number_format($val->unidades_solicitadas,0)?></td>
                                                    <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="unidades_devolucion[]" style = "text-align: right;" value="<?= $val->unidades_devolucion ?>"  size="5"> </td>
                                                    <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="unidades_averias[]" style = "text-align: right;" value="<?= $val->unidades_averias ?>"  size="5"> </td>
                                                    <td style="text-align: right;"><?= ''.number_format($val->unidades_utilizadas,0)?></td>
                                                    <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="unidades_sala_tecnica[]" style = "text-align: right;" value="<?= $val->unidades_sala_tecnica ?>"  size="5"> </td>
                                                    <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="unidades_muestra_retencion[]" style = "text-align: right;" value="<?= $val->unidades_muestra_retencion ?>"  size="5"> </td> 
                                                    <td style="text-align: right;"><?= ''.number_format($val->unidades_reales,0)?></td>
                                                <?php }else{?>
                                                    <td style="text-align: right"><?= ''. number_format($val->stock,0) ?></td>
                                                    <td style="text-align: right;"><?= ''.number_format($val->unidades_solicitadas,0)?></td>
                                                    <td><?= $val->unidades_devolucion ?></td>
                                                    <td><?= $val->unidades_averias ?></td>
                                                    <td><?= $val->unidades_utilizadas?></td>
                                                    <td><?= $val->unidades_sala_tecnica ?></td>
                                                    <td><?= $val->unidades_muestra_retencion ?></td>
                                                    <td style="text-align: right;"><?= ''.number_format($val->unidades_reales,0)?></td>
                                                <?php } ?>    
                                                <td><?= $val->importadoRegistro?></td>
                                                <input type="hidden" name="listado_empaque[]" value="<?= $val->id?>"> 
                                                <?php if($val->importado == 0){?>
                                                    <td style= 'width: 25px; height: 20px;'>
                                                                <?= Html::a('<span class="glyphicon glyphicon-eye-close"></span> ', ['orden-ensamble-producto/cerrar_linea_empacada', 'id' => $model->id_ensamble, 'detalle' => $val->id, 'token' => $token,  'id_producto' => $val->id, 'sw' => $sw], [
                                                        'class' => '',
                                                        'data' => [
                                                            'confirm' => 'Esta seguro de CERRAR esta Linea de empaque?. Despues de cerrada no se puede modificar el proceso.',
                                                            'method' => 'post',
                                                        ],
                                                    ]) ?>
                                                    </td>
                                                   <td style="text-align: center;"><input type="checkbox" name="listado_unidades[]" value="<?= $val->id ?>"></td>
                                               <?php }else{ ?>
                                                   <td style= 'width: 25px; height: 20px;'></td>
                                                   <td style= 'width: 25px; height: 20px;'></td>
                                               <?php }?>    
                                               
                                           </tr>
                                       <?php endforeach;?>
                                   </tbody>
                               </table>
                           </div>   
                           <div class="panel-footer text-right">  
                               <?php if(count($conTerminadas) > 0){
                                       echo Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_material_empaque']);
                                       echo Html::submitButton("<span class='glyphicon glyphicon-trash'></span> Eliminar todo", ["class" => "btn btn-danger btn-sm", 'name' => 'eliminar_todo_empaque']);
                              } ?>
                           </div>     
                       </div>
                   </div>
               </div>
               <!-- TERMINA TABS-->
            <?php }?>   
        </div>
    </div>   
    <?php ActiveForm::end(); ?> 
</div> 
<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>

