<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_items;
?>
<div class="items-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_items], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_items], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            ITEMS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?>:</th>
                    <td><?= Html::encode($model->id_items) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Materia_prima') ?>:</th>
                    <td><?= Html::encode($model->descripcion) ?></td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_iva') ?>:</th>
                    <td><?= Html::encode($model->iva->valor_iva) ?>%</td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_solicitud') ?>:</th>
                    <td><?= Html::encode($model->tipoSolicitud->descripcion) ?></td>
              </tr>
            </table>
        </div>
    </div>

</div>
