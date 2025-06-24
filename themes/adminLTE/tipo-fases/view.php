<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'FASES DEL PROCESO';
$this->params['breadcrumbs'][] = ['label' => 'Tipo fases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_fase;
?>
<div class="tipo-fases-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_fase], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
           FASE
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_fase') ?>:</th>
                    <td><?= Html::encode($model->id_fase) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_fase') ?>:</th>
                    <td><?= Html::encode($model->nombre_fase) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'color') ?>:</th>
                    <td><?= Html::encode($model->color) ?></td>                    
               </tr>    
            </table>
        </div>
    </div>

</div>