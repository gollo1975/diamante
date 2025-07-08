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
/* @var $model app\models\Empleado */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Materias prima', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_materia_prima;
$view = 'materia-primas';
?>
<div class="operarios-view">
    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_materias'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 1, 'codigo' => $model->id_materia_prima,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            MATERIAS PRIMAS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_materia_prima) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_materia_prima) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'materia_prima') ?></th>
                    <td><?= Html::encode($model->materia_prima) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_iva') ?></th>
                    <td><?= Html::encode($model->aplicaIva) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Vlr_unidad') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->valor_unidad,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Medida') ?></th>
                    <td><?= Html::encode($model->medida->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_entrada') ?></th>
                    <td><?= Html::encode($model->fecha_entrada) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_inventario') ?></th>
                    <td><?= Html::encode($model->aplicaInventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_registro') ?></th>
                    <td><?= Html::encode($model->fecha_registro) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'valor_iva') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->valor_iva,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'usuario_creador') ?></th>
                    <td><?= Html::encode($model->usuario_creador) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'salida_materia_prima') ?></th>
                    <td><?= Html::encode($model->salida_materia_prima) ?></td>
                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Unidades') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_cantidad,0)) ?></td>
                   <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Stock') ?></th>
                    <td style="text-align: right; background-color:#F5EEF8;"><?= Html::encode(''.number_format($model->stock,0)) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'subtotal') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->subtotal,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'inventario_inicial') ?></th>
                    <td><?= Html::encode($model->inventarioInicial) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'usuario_editado') ?></th>
                    <td><?= Html::encode($model->usuario_editado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'porcentaje_iva') ?></th>
                    <td><?= Html::encode($model->porcentaje_iva) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_solicitud') ?></th>
                    <td><?= Html::encode($model->tipoSolicitud->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_materia_prima') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_materia_prima,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'convertir_gramos') ?></th>
                    <td><?= Html::encode($model->covertirGramos) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion') ?></th>
                    <td colspan="7"><?= Html::encode($model->descripcion)?></td>
                </tr>
                
                
            </table>
        </div>
    </div>
    <!--INICIO LOS TABS-->
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#entradamateria" aria-controls="entradamateria" role="tab" data-toggle="tab">Entradas de materias  <span class="badge"><?= $pagination->totalCount ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="entradamateria">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>No entrada</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha entrada</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha vcto</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Soporte</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>No Lote cliente</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Tipo orden</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'>% Iva</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Vr. unitario</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Vr. Iva</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Total</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detalle_entrada as $val):
                                        $entrada = \app\models\EntradaMateriaPrima::findOne($val->id_entrada); 
                                        ?>
                                    <tr style="font-size: 85%;">
                                        <td><?= $val->id_entrada ?></td>  
                                        <td><?= $val->entrada->fecha_proceso ?></td>
                                        <td><?= $val->fecha_vencimiento ?></td>
                                        <td><?= $val->entrada->numero_soporte ?></td>
                                        <td><?= $val->numero_lote ?></td>
                                        <?php if($entrada->id_orden_compra !== null){?>
                                            <td><?= $val->entrada->ordenCompra->tipoOrden->descripcion_orden ?></td>
                                        <?php }else{?>    
                                            <td><?= 'NOT FOUND' ?></td>
                                         <?php }?>
                                        <td style="text-align: right"><?= $val->porcentaje_iva ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->total_iva,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                        <td style="text-align: right"><?= ''.number_format($val->total_entrada,0) ?></td> 
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            <!--INICIO EL OTRO TABS -->
        </div>
    </div>    
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
