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

$this->title = 'DETALLE AUDITORIA ';
$this->params['breadcrumbs'][] = ['label' => 'Auditoria orden produccion', 'url' => ['index_resultado_auditoria']];
$this->params['breadcrumbs'][] = $model->id_auditoria;
//$conFases = ArrayHelper::map(app\models\TipoFases::find()->all(), 'id_fase', 'nombre_fase');
?>
<div class="grupo-producto-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index_resultado_auditoria'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-check"></span> Aprobar conceptos',
                    ['/orden-produccion/aprobar_orden_produccion','id_auditoria' =>$model->id_auditoria],
                    [
                        'title' => 'Permite subir los parametros de aprobacion',
                        'data-toggle'=>'modal',
                        'data-target'=>'#modalaprobarordenproduccion',
                        'class' => 'btn btn-info btn-sm'
                    ])
                    ?>
             <div class="modal remote fade" id="modalaprobarordenproduccion">
                      <div class="modal-dialog modal-lg" style ="width: 650px;">
                         <div class="modal-content"></div>
                     </div>
             </div>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            GRUPO DE PRODUCTOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_auditoria') ?></th>
                    <td><?= Html::encode($model->id_auditoria) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_orden') ?></th>
                    <td><?= Html::encode($model->ordenProduccion->numero_orden) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'etapa') ?></th>
                    <td><?= Html::encode($model->etapa) ?></td>   
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Grupo') ?>:</th>
                    <td><?= Html::encode($model->ordenProduccion->grupo->nombre_grupo) ?></td>
              </tr>
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_lote') ?></th>
                    <td><?= Html::encode($model->numero_lote) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_orden_produccion') ?></th>
                    <td><?= Html::encode($model->id_orden_produccion) ?></td>   
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'continua') ?></th>
                    <td><?= Html::encode($model->continuaProceso) ?></td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'condicion_analisis') ?></th>
                    <td><?= Html::encode($model->condicionAnalisis) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="6"><?= Html::encode($model->observacion) ?></td>
                     
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
            <li role="presentation" class="active"><a href="#item_auditoria" aria-controls="item_auditoria" role="tab" data-toggle="tab">Concepto de auditoria  <span class="badge"><?= count($conConcepto) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="asignacioncupo">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE; '>Nombre de analisis</th>                        
                                        <th scope="col" style='background-color:#B9D5CE; '>Espeficicaciones</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>Resultado</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($conConcepto as $val):?>
                                        <tr style='font-size:90%;'>
                                            <td><?= $val->analisis->concepto?></td>
                                            <td><?= $val->especificacion->concepto?></td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: left"> <input type="text"  name="resultado[]" value="<?= $val->resultado ?>"  size="15" required="true"> </td>
                                            <input type="hidden" name="listado_analisis[]" value="<?= $val->id_detalle?>"> 
                                            <td style= 'width: 25px; height: 25px;'>
                                                <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_auditoria', 'id_auditoria' => $model->id_auditoria, 'detalle' => $val->id_detalle], [
                                                              'class' => '',
                                                              'data' => [
                                                                  'confirm' => 'Esta seguro de eliminar el registro?',
                                                                  'method' => 'post',

                                                              ],
                                                ])?>
                                            </td>     
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>  
                        <div class="panel-footer text-right">  
                            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_listado_analisis'])?>
                        </div>   
                    </div>
                </div>
            </div>
            <!-- TERMINA TABS--->
        </div> 
    </div>    
<?php ActiveForm::end(); ?>  
</div>