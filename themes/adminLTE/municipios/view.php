<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Municipios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->codigo_municipio;
?>
<div class="municipios-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->codigo_municipio], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->codigo_municipio], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            MUNICIPIOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_municipio') ?>:</th>
                    <td><?= Html::encode($model->codigo_municipio) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'municipio') ?>:</th>
                    <td><?= Html::encode($model->municipio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'departamento') ?>:</th>
                    <td><?= Html::encode($model->codigoDepartamento->departamento) ?></td>                    
              </tr>
                <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_interfaz') ?>:</th>
                    <td><?= Html::encode($model->codigo_interfaz) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?>:</th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'activo') ?>:</th>
                    <td><?= Html::encode($model->activo) ?></td>                    
                </tr>                
            </table>
        </div>
    </div>

</div>