<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Especificacion producto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_especificacion;
?>
<div class="concepto-analisis-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_especificacion], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            CARGOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_especificacion') ?>:</th>
                    <td><?= Html::encode($model->id_especificacion) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'concepto') ?>:</th>
                    <td><?= Html::encode($model->concepto) ?></td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_registro') ?>:</th>
                    <td><?= Html::encode($model->fecha_registro) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?>:</th>
                    <td><?= Html::encode($model->user_name) ?></td>
              </tr>
            </table>
        </div>
    </div>

</div>