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
   
        <?php if($model->cerrar_auditoria == 0){
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar auditoria', ['cerrar_auditoria', 'id_auditoria' => $model->id_auditoria,'orden' => $model->id_orden_produccion],['class' => 'btn btn-warning btn-sm',
                               'data' => ['confirm' => 'Esta seguro de CERRAR el proceso de auditoria a la OP No ('.$model->ordenProduccion->numero_orden.').', 'method' => 'post']]);   
        
            echo Html::a('<span class="glyphicon glyphicon-check"></span> Aprobar conceptos',
                        ['/orden-produccion/aprobar_orden_produccion','id_auditoria' =>$model->id_auditoria],
                        [
                            'title' => 'Permite subir los parametros de aprobacion',
                            'data-toggle'=>'modal',
                            'data-target'=>'#modalaprobarordenproduccion',
                            'class' => 'btn btn-info btn-sm'
                        ])?>
                        
            <div class="modal remote fade" id="modalaprobarordenproduccion">
                     <div class="modal-dialog modal-lg" style ="width: 650px;">
                        <div class="modal-content"></div>
                    </div>
            </div>
              
       <?php }else{
             echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_informe_auditoria', 'id_auditoria' => $model->id_auditoria], ['class' => 'btn btn-default btn-sm']);
        } ?>
   </p>    
    
    <div class="panel panel-success">
        <div class="panel-heading">
            PRODUCTO AUDITADO
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
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Producto') ?>:</th>
                    <td><?= Html::encode($model->productos->nombre_producto) ?></td>
              </tr>
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_lote') ?></th>
                    <td><?= Html::encode($model->numero_lote) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_orden_produccion') ?></th>
                    <td><?= Html::encode($model->id_orden_produccion) ?></td>   
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'continua') ?></th>
                    <td><?= Html::encode($model->continuaProceso) ?></td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_auditoria') ?></th>
                    <td><?= Html::encode($model->numero_auditoria) ?></td>  
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'condicion_analisis') ?></th>
                    <td><?= Html::encode($model->condicionAnalisis) ?></td> 
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_cierre') ?></th>
                    <td><?= Html::encode($model->fecha_cierre) ?></td>  
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_autorizada') ?></th>
                    <td style='background-color:#aed9e0'><?= Html::encode($model->fecha_autorizada) ?></td>
              </tr>
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="10"><?= Html::encode($model->observacion) ?></td>
                     
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nota') ?></th>
                    <td colspan="10"><?= Html::encode($model->nota) ?></td>
                     
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
                                        <th scope="col" style='background-color:#B9D5CE; text-align: center;'><input type="checkbox" onclick="marcar(this);"/></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($conConcepto as $val):?>
                                        <tr style='font-size:90%;'>
                                            <td><?= $val->analisis->concepto?></td>
                                            <td><?= $val->especificacion->concepto?></td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: left"> <input type="text"  name="resultado[]" value="<?= $val->resultado ?>"  size="15"> </td>
                                            <input type="hidden" name="listado_analisis[]" value="<?= $val->id_detalle?>"> 
                                            <?php if($model->cerrar_auditoria == 0){?>
                                                <td style= 'width: 25px; height: 25px;'>
                                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_auditoria', 'id_auditoria' => $model->id_auditoria, 'detalle' => $val->id_detalle], [
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
                                            <td style="text-align: center;"><input type="checkbox" name="listado_eliminar[]" value="<?= $val->id_detalle ?>"></td>    
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>  
                        <?php if($model->cerrar_auditoria == 0){?>
                            <div class="panel-footer text-right">  
                                <?php if($conConcepto){?>
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_listado_analisis'])?>
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-trash'></span> Eliminar todo", ["class" => "btn btn-danger btn-sm", 'name' => 'eliminar_todo_auditoria']) ?>
                                <?php }else { ?>
                                    <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Refrescar', ['orden-produccion/cargar_items_auditoria', 'id_grupo' => $model->id_grupo, 'id_etapa' => $model->id_etapa, 'id_auditoria'=> $model->id_auditoria],[ 'class' => 'btn btn-info btn-sm']) ?>
                                <?php }?> 
                            </div> 
                        <?php }?>
                    </div>
                </div>
            </div>
            <!-- TERMINA TABS--->
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