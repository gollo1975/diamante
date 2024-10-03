<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionSalario */

$this->title = 'COFIGURACION DE SALARIO';
$this->params['breadcrumbs'][] = ['label' => 'Configuración', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_salario;
?>
<div class="configuracion-salario-view">

    <!--<?= Html::encode($this->title) ?>-->

   <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_salario], ['class' => 'btn btn-primary btn-sm']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_salario], ['class' => 'btn btn-success btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Eliminar', ['delete', 'id' => $model->id_salario], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Esta seguro de eliminar el registro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Configuración salario
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'Id') ?>:</th>
                    <td><?= Html::encode($model->id_salario) ?></td>
                    <th><?= Html::activeLabel($model, 'salario_minimo_actual') ?>:</th>
                    <td align="right"><?= Html::encode('$'.number_format($model->salario_minimo_actual,0)) ?></td>
                    <th><?= Html::activeLabel($model, 'salario_minimo_anterior') ?>:</th>
                    <td align="right"><?= Html::encode('$'.number_format($model->salario_minimo_anterior,0)) ?></td>
                     <th><?= Html::activeLabel($model, 'Auxiilio_transporte_actual') ?>:</th>
                    <td align="right"><?= Html::encode('$'.number_format($model->auxilio_transporte_actual,0)) ?></td>
                    
                </tr>                
                 <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'Auxilio_transporte_anterior') ?>:</th>
                    <td align="right"><?= Html::encode('$'.number_format($model->auxilio_transporte_anterior,0)) ?></td>
                    <th><?= Html::activeLabel($model, 'Salario_incapacidad') ?>:</th>
                    <td align="right"><?= Html::encode('$'.number_format($model->salario_incapacidad,0)) ?></td>
                    <th><?= Html::activeLabel($model, 'porcentaje_incremento') ?>:</th>
                    <td><?= Html::encode($model->porcentaje_incremento) ?>%</td>
                    <th><?= Html::activeLabel($model, 'año') ?>:</th>
                    <td><?= Html::encode($model->anio) ?></td>
                </tr>   
                 <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'estado') ?>:</th>
                    <td><?= Html::encode($model->activo) ?></td>
                    <th><?= Html::activeLabel($model, 'user_name') ?>:</th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th><?= Html::activeLabel($model, 'fecha_creacion') ?>:</th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                     <th><?= Html::activeLabel($model, 'fecha_cierre') ?>:</th>
                    <td><?= Html::encode($model->fecha_cierre) ?></td>

                </tr>   
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'fecha_aplicacion') ?>:</th>
                    <td  colspan="8"><?= Html::encode($model->fecha_aplicacion) ?></td>
                </tr>   
                
            </table>
        </div>
    </div>
        
    
    
</div>
