<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Tipo racks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_rack;
?>
<div class="cargos-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_rack], ['class' => 'btn btn-success btn-sm']) ?>
        <?php if($model->numero_rack == 0){
            echo Html::a('<span class="glyphicon glyphicon-book"></span> Generar numero', ['generar_numero', 'id' => $model->id_rack],['class' => 'btn btn-default btn-sm',
                           'data' => ['confirm' => 'Esta seguro de generar el NUMERO del rack para el almacenamiento.', 'method' => 'post']]);
        }?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            RACKS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_rack') ?></th>
                    <td><?= Html::encode($model->id_rack) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_rack') ?></th>
                    <td><?= Html::encode($model->numero_rack) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'medidas') ?></th>
                    <td><?= Html::encode($model->medidas) ?></td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion') ?></th>
                    <td><?= Html::encode($model->descripcion) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'capacidad_instalada') ?></th>
                    <td><?= Html::encode($model->capacidad_instalada) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'capacidad_actual') ?></th>
                    <td><?= Html::encode($model->capacidad_actual) ?></td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'estado') ?></th>
                    <td><?= Html::encode($model->estadoActivo) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td> 
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'controlar_capacidad') ?></th>
                    <td><?= Html::encode($model->controlarCapacidad) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_piso') ?></th>
                    <td><?= Html::encode($model->pisos->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'></th>
                    <td></td> 
              </tr>
            </table>
        </div>
    </div>

</div>
