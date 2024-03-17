<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Listado requisitos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_requisito;
?>
<div class="listado-requisitos-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_requisito], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            LISTADO DE REQUISITOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_requisito') ?></th>
                    <td><?= Html::encode($model->id_requisito) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'concepto') ?></th>
                    <td><?= Html::encode($model->concepto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'porcentaje') ?></th>
                    <td><?= Html::encode($model->porcentaje) ?>%</td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_proveedor') ?></th>
                    <td><?= Html::encode($model->aplicaProveedor) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_requisito') ?></th>
                    <td><?= Html::encode($model->aplicaRequisito) ?></td>
                    <th style='background-color:#F0F3EF;'></th>
                    <td></td>
              </tr>
            </table>
        </div>
    </div>

</div>

