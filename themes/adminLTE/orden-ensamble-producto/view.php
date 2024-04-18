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
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index_resultado_auditoria'], ['class' => 'btn btn-primary btn-sm']) ?>
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
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="10"><?= Html::encode($model->observacion) ?></td>
                     
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
            <li role="presentation" class="active"><a href="#presentacion_producto" aria-controls="presentacion_producto" role="tab" data-toggle="tab">Presentacion producto  <span class="badge"><?= count($conPresentacion) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="asignacioncupo">
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
                                            <input type="hidden" name="listado_presentacion[]" value="<?= $val->id_detalle?>"> 
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>    
                    </div>
                    <?php if($model->autorizado == 0){?>
                            <div class="panel-footer text-right">  
                                <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_listado_presentacion'])?>
                            </div> 
                    <?php }?>
                </div>
            </div>
            <!-- TERMINA TABS-->
        </div>
    </div>   
    <?php ActiveForm::end(); ?> 
</div> 

