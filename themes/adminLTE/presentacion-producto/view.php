<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Presentacion del producto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_presentacion;
?>
<div class="presentacion-producto-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_presentacion], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_presentacion], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            PRESENTACION DEL PRODUCTO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_presentacion') ?>:</th>
                    <td><?= Html::encode($model->id_presentacion) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion') ?>:</th>
                    <td><?= Html::encode($model->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo') ?>:</th>
                    <td><?= Html::encode($model->grupo->nombre_grupo) ?></td>                    
              </tr>
                <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?>:</th>
                    <td><?= Html::encode($model->user_name) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_registro') ?>:</th>
                    <td><?= Html::encode($model->fecha_registro) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_producto') ?></th>
                    <td><?= Html::encode($model->producto->nombre_producto) ?></td>
          
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
            <li role="presentation" class="active"><a href="#materialempaque" aria-controls="materialempaque" role="tab" data-toggle="tab">Material de empaque  <span class="badge"><?= 1 ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="materialempaque">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE; '>Codigo</th>                        
                                        <th scope="col" style='background-color:#B9D5CE; '>Descripcion</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>Medida</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($listadoEmpaque as $val) { ?>
                                        <tr style ='font-size:85%;'>
                                            <td><?= $val->codigo_material?></td>
                                            <td><?= $val->materiaPrima->materia_prima?></td>
                                            <td><?= $val->presentacion->medidaProducto->descripcion?></td>
                                            <td style="width: 25px; height: 20px">
                                                <?= Html::a('', ['eliminar_detalles', 'id' => $val->id_presentacion, 'id_detalle' => $val->id_configuracion], [
                                                    'class' => 'glyphicon glyphicon-trash',
                                                    'data' => [
                                                        'confirm' => 'Esta seguro de eliminar el registro?',
                                                        'method' => 'post',
                                                     
                                                    ],
                                                ]) ?>
                                            </td>
                                        </tr>

                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer text-right"> 
                            <?= Html::a('<span class="glyphicon glyphicon-search"></span> Material de empaque', ['presentacion-producto/buscar_material_empaque', 'id' => $model->id_presentacion],[ 'class' => 'btn btn-warning btn-sm']) ?>   
                        </div>    
                    </div>
                </div>
            </div>
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