<?php

//modelos
use app\models\Items;
use app\models\SolicitudCompra;
use app\models\SolicitudCompraDetalles;
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

$this->title = 'DETALLE DE AUDITORIA';
$this->params['breadcrumbs'][] = ['label' => 'Auditoria de compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_auditoria;
$view = 'auditoria-compras';
?>

<?php
    //$remision = Remision::find()->where(['=', 'idordenproduccion', $model->idordenproduccion])->one();
?>

<div class="auditoria-compra-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
            <?php if($token == 0){?>
                <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php }else{?>
                <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_orden_compra', 'id' => $model->id_orden_compra], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php }
            if($model->cerrar_auditoria == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar auditoria', ['cerrarauditoria', 'id' => $model->id_auditoria, 'token'=> $token, 'id_orden' =>$model->id_orden_compra],['class' => 'btn btn-warning btn-sm',
                         'data' => ['confirm' => 'Esta seguro que desea cerrar la auditoria a esta orden de compra.', 'method' => 'post']]);
               
                echo Html::a('<span class="glyphicon glyphicon-import"></span> Subir documento',
                    ['/auditoria-compras/subir_documento_factura','id' =>$model->id_auditoria, 'token' => $token],
                    [
                        'title' => 'Permite digitar el documento factura',
                        'data-toggle'=>'modal',
                        'data-target'=>'#modalsubirdocumentofactura',
                        'class' => 'btn btn-info btn-sm'
                    ])
                    ?>
             <div class="modal remote fade" id="modalsubirdocumentofactura">
                     <div class="modal-dialog modal-lg-centered">
                         <div class="modal-content"></div>
                     </div>
             </div>
            <?php }else{
               echo Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' =>17, 'codigo' => $model->id_auditoria,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']); 
               echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_auditoria_compra', 'id' => $model->id_auditoria], ['class' => 'btn btn-default btn-sm']);                
            }?>
            
                   
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            ORDENES DE COMPRAS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_orden_compra") ?></th>
                    <td><?= Html::encode($model->id_orden_compra) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_orden') ?></th>
                    <td><?= Html::encode($model->ordenCompra->tipoOrden->descripcion_orden) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_proveedor') ?></th>
                    <td><?= Html::encode($model->proveedor->nombre_completo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_orden')?></th>
                    <td><?= Html::encode($model->numero_orden) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "fecha_proceso_compra") ?></th>
                    <td><?= Html::encode($model->fecha_proceso_compra) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_auditoria') ?></th>
                    <td><?= Html::encode($model->fecha_auditoria) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name')?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cerrar_auditoria') ?></th>
                     <td><?= Html::encode($model->cerrarAuditoria) ?></td>
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
            <li role="presentation" class="active"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Items <span class="badge"><?= count($detalle_compras) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="items">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Codigo</th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Descripción</th>                        
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. unitario</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><span title="Muetras si la mercancia llego con novedad">E /S</span></th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Estado</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>N. cantidad</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>N. valor</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Estado</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Descripcion del auditor</th>  
                                           
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_compras as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->id_items ?></td>
                                                <td><?= $val->nombre_producto ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                <?php if($val->entrada_salida == null){?>
                                                    <td><?= $val->entrada_salida ?></td>
                                                <?php }else{
                                                    if($val->entrada_salida > 0){?>
                                                        <td style='background-color:#B9D5CE;'><?= $val->entrada_salida ?></td>
                                                    <?php }else{?>    
                                                        <td style='background-color:#F7BEC2;'><?= $val->entrada_salida ?></td>
                                                    <?php }        
                                                }?>  
                                                <td><?= $val->comentario ?></td>
                                                <td style="padding-right: 1;padding-right: 0;"><input type="text" name="nueva_cantidad[]" value="<?= $val->nueva_cantidad ?>" size="9" required="true"> </td> 
                                                <td style="padding-right: 1;padding-right: 0;"><input type="text" name="nuevo_valor[]" value="<?= $val->nuevo_valor ?>" size="9"> </td> 
                                                <td><select name="estado[]" >
                                                    <option value="<?= $val->estado_producto ?>"><?= $val->estadoProducto?></option>
                                                    <option value="0">BUENO</option>
                                                    <option value="1">MALO</option>
                                                </select></td>
                                                <td style="padding-right: 1;padding-right: 0; width: 40%"><input type="text" name="nota[]" value="<?= $val->nota?>" size="78" maxlength="66" required = "true"> </td> 
                                            <input type="hidden" name="detalle_compra[]" value="<?= $val->id ?>">
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <div class="panel-footer text-right">  
                                <?php 
                                if($model->cerrar_auditoria == 0){?>
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizarauditoria']) ?>
                                <?php }?>
                            </div>   
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>

   