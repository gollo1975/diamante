<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Posiciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_posicion;
?>
<div class="items-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_posicion], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_posicion], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            POSICIONES
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_posicion') ?>:</th>
                    <td><?= Html::encode($model->id_posicion) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'posicion') ?>:</th>
                    <td><?= Html::encode($model->posicion) ?></td>
              </tr>
            </table>
        </div>
    </div>

</div>
