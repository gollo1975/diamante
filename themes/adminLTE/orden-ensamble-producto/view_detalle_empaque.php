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

$this->title = 'ORDEN DE ENSAMBLE ';
$this->params['breadcrumbs'][] = ['label' => 'Orden de ensamble', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_ensamble;


?>
<div class="orden-ensamble-producto-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index_descargar_inventario'], ['class' => 'btn btn-primary btn-sm']); ?>
        <?= Html::a('<span class="glyphicon glyphicon-eye-close"></span> Cerrar  Orden', ['cerrar_orden_ensamlbe', 'id' => $model->id_ensamble], ['class' => 'btn btn-success btn-sm']);?>
       
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
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Producto') ?>:</th>
                    <td><?= Html::encode($model->productos->nombre_producto) ?></td>
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
             <li role="presentation" class="active" ><a href="#material-empaque" aria-controls="material-empaque" role="tab" data-toggle="tab">Material de empaque <span class="badge"><?= count($conMateriales)?></span></a></li>
       </ul>
        <div class="tab-content">
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
                                       <th scope="col" style='background-color:#B9D5CE; '>U. Solicitadas</th> 
                                       <th scope="col" style='background-color:#B9D5CE; '>U. Devolucion</th> 
                                       <th scope="col" style='background-color:#B9D5CE; '>U. Averias</th> 
                                       <th scope="col" style='background-color:#B9D5CE; '>U. Envasadas</th>
                                      <th scope="col" style='background-color:#B9D5CE; text-align: center;'><input type="checkbox" onclick="marcar(this);"/></th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php 
                                   foreach ($conMateriales as $val):
                                       ?>
                                       <tr style='font-size:85%;'>
                                            <td><?= $val->presentacion->descripcion?></td>
                                            <?php if($val->alerta == 'FALTA'){?>
                                               <td style='background-color:#F1F3E3'><?= $val->materiaPrima->materia_prima?></td>
                                            <?php } else { ?>
                                               <td><?= $val->materiaPrima->materia_prima?></td>
                                            <?php }?>
                                            <td style="text-align: right;"><?= ''.number_format($val->unidades_solicitadas,0)?></td>
                                            <td><?= $val->unidades_devolucion ?></td>
                                            <td><?= $val->unidades_averias ?></td>
                                            <td><?= $val->unidades_utilizadas?></td>
                                            <?php if($val->linea_exportada_inventario == 0){?>
                                                <td style="text-align: center; height: 20px; height: 20px">
                                                    <input type="checkbox" name="listado_unidades[]" value="<?= $val->id ?>">
                                                </td>    
                                            <?php }else{?>
                                                <td style="text-align: center; height: 20px; height: 20px"></td>                                             
  
                                            <?php }?>    
                                       </tr>
                                   <?php endforeach;?>
                               </tbody>
                           </table>
                       </div>   
                       <div class="panel-footer text-right">  
                      
                                   <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Descargar Materia prima", ["class" => "btn btn-warning btn-sm", 'name' => 'descargar_material_empaque']);?>
                        
                       </div>     
                   </div>
               </div>
           </div>
           <!-- TERMINA TABS-->
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

