<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Tipo de ordenes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_tipo_orden;
?>
<div class="tipo-documento-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_tipo_orden], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_tipo_orden], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            TIPOS DE ORDENES
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?>:</th>
                    <td><?= Html::encode($model->id_tipo_orden) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Descripción') ?>:</th>
                    <td><?= Html::encode($model->descripcion_orden) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'abreviatura') ?>:</th>
                    <td><?= Html::encode($model->abreviatura) ?></td>
              </tr>
            </table>
        </div>
    </div>

</div>
