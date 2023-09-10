<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto empresarial', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_presupuesto;
?>
<div class="presupuesto-empresarial-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_presupuesto], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_presupuesto], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            PRESUPUESTO POR AREAS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_presupuesto') ?>:</th>
                    <td><?= Html::encode($model->id_presupuesto) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion') ?>:</th>
                    <td><?= Html::encode($model->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_area') ?>:</th>
                    <td><?= Html::encode($model->area->descripcion) ?></td>  
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'valor_presupuesto') ?>:</th>
                     <td style="text-align: right"><?= Html::encode(''.number_format($model->valor_presupuesto, 0)) ?></td>  
              </tr>
                <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'año') ?>:</th>
                    <td><?= Html::encode($model->año) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?>:</th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_corte') ?>:</th>
                    <td><?= Html::encode($model->fecha_corte) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'estado') ?>:</th>
                    <td><?= Html::encode($model->estadoRegistro) ?></td>                    
                </tr>   
                <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?>:</th>
                    <td><?= Html::encode($model->user_name) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_registro') ?>:</th>
                    <td><?= Html::encode($model->fecha_registro) ?></td>
                    <th style='background-color:#F0F3EF;'></th>
                    <td></td>  
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'valor_gastado') ?>:</th>
                   <td style="text-align: right"><?= Html::encode(''.number_format($model->valor_gastado, 0)) ?></td>  
                                      
                </tr>    
            </table>
        </div>
    </div>

</div>