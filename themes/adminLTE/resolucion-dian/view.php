<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Resolucion dian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_resolucion;
?>
<div class="resolucion-dian-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_resolucion], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_resolucion], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            MUNICIPIOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_resolucion') ?>:</th>
                    <td><?= Html::encode($model->id_resolucion) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_resolucion') ?>:</th>
                    <td><?= Html::encode($model->numero_resolucion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'consecutivo') ?>:</th>
                    <td><?= Html::encode($model->consecutivo) ?></td>                    
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?>:</th>
                    <td><?= Html::encode($model->user_name) ?></td>   
              </tr>
                <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'desde') ?>:</th>
                    <td><?= Html::encode($model->desde) ?></td>    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'hasta') ?>:</th>
                    <td><?= Html::encode($model->hasta) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_vence') ?>:</th>
                    <td><?= Html::encode($model->fecha_vence) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'estado_resolucion') ?>:</th>
                    <td><?= Html::encode($model->activo) ?></td>                    
                </tr>     
                <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'rango_inicio') ?>:</th>
                    <td><?= Html::encode($model->rango_inicio) ?></td>    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'rango_final') ?>:</th>
                    <td><?= Html::encode($model->rango_final) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'vigencia') ?>:</th>
                    <td><?= Html::encode($model->vigencia) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_documento') ?>:</th>
                    <td><?= Html::encode($model->documentosElectronicos->concepto) ?></td>              
                </tr>       
            </table>
        </div>
    </div>

</div>