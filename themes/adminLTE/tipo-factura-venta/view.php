<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Tipo factura de venta', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_tipo_factura;
?>
<div class="tipo-factura-venta-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_tipo_factura], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            CARGOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?>:</th>
                    <td><?= Html::encode($model->id_tipo_factura) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion') ?>:</th>
                    <td><?= Html::encode($model->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'base_retencion') ?>:</th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->base_retencion,0)) ?></td>
              </tr>
              <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_registro') ?>:</th>
                    <td><?= Html::encode($model->fecha_registro) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?>:</th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'porcentaje_retencion') ?>:</th>
                    <td style="text-align: right"><?= Html::encode($model->porcentaje_retencion) ?></td>
              </tr>
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'porcentaje_mora') ?>:</th>
                    <td><?= Html::encode($model->porcentaje_mora) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_interes_mora') ?>:</th>
                    <td><?= Html::encode($model->aplicaInteres) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'ver_registro_factura') ?>:</th>
                    <td><?= Html::encode($model->verRegistro) ?></td>
              </tr>
            </table>
        </div>
    </div>

</div>
