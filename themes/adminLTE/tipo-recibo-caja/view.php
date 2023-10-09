<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Tipo recibo caja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_tipo;
?>
<div class="tipo-recibo-caja-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_tipo], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_tipo], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            TIPOS RECIBO DE CAJA
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo') ?>:</th>
                    <td><?= Html::encode($model->id_tipo) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'concepto') ?>:</th>
                    <td><?= Html::encode($model->concepto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'resta') ?>:</th>
                    <td><?= Html::encode($model->restarecibo) ?></td>
              </tr>
            </table>
        </div>
    </div>

</div>

