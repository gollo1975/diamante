<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Periodopago */

$this->title = 'PERIODO DE PAGO';
$this->params['breadcrumbs'][] = ['label' => 'Periodo de pago', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_periodo_pago;

?>
<div class="periodo_pago-view">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_periodo_pago], ['class' => 'btn btn-primary btn-sm']) ?>
		
    </p>

    <div class="panel panel-success">
        <div class="panel-heading">
            Periodo de Pago
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'id_periodo_pago') ?>:</th>
                    <td><?= Html::encode($model->id_periodo_pago) ?></td>
                    <th><?= Html::activeLabel($model, 'Nombre_periodo') ?>:</th>
                    <td><?= Html::encode($model->nombre_periodo) ?></td>
                    <th><?= Html::activeLabel($model, 'Dias') ?>:</th>
                    <td><?= Html::encode($model->dias) ?></td> 
                    <th><?= Html::activeLabel($model, 'continua') ?>:</th>
                    <td><?= Html::encode($model->continuaP) ?></td>
                </tr>                
            </table>
        </div>
    </div>

</div>
