<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\Session;
use yii\db\ActiveQuery;


/* @var $this yii\web\View */
/* @var $model app\models\Licencia */

$this->title = 'Adicion por fecha';
$this->params['breadcrumbs'][] = ['label' => 'Adicional x fecha', 'url' => ['view','id' =>$id, 'fecha_corte' => $fecha_corte]];
$this->params['breadcrumbs'][] = $model->id_pago_permanente;
?>

 <div class="adicional-pago-fecha-vista">
              <!--<h1><?= Html::encode($this->title) ?></h1>-->
            <p>
                 <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view','id'=>$id, 'fecha_corte' => $fecha_corte], ['class' => 'btn btn-primary btn-sm']) ?>
                
            </p>    
    
   
    <div class="panel panel-success">
        <div class="panel-heading">
            Detalle del registro
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                  
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_salario') ?>:</th>
                        <td><?= Html::encode($model->codigoSalario->nombre_concepto) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'valor_adicion') ?>:</th>
                        <td><?= Html::encode('$'. number_format($model->valor_adicion,0)) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_dia_laborado') ?>:</th>
                        <td ><?= Html::encode($model->aplicardialaborado) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplicar_prima') ?>:</th>
                        <td><?= Html::encode($model->aplicarPrima) ?></td>
                   </tr> 
                   <tr style="font-size: 85%;">
                        
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplicar_cesantias') ?>:</th>
                        <td><?= Html::encode($model->aplicarCesantia) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?>:</th>
                        <td><?= Html::encode($model->fecha_creacion) ?></td>
                         <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?>:</th>
                        <td><?= Html::encode($model->user_name) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_contrato') ?>:</th>
                        <td><?= Html::encode($model->contrato->id_contrato) ?></td>
                      
                   </tr>
                  <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_empleado') ?>:</th>
                    <td><?= Html::encode($model->empleado->nit_cedula .'--'. $model->empleado->nombre_completo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo_pago') ?>:</th>
                    <td><?= Html::encode($model->grupoPago->grupo_pago) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'detalle') ?>:</th>
                    <td colspan="4"><?= Html::encode($model->detalle) ?></td>
                   </tr>   
                  

            </table>
        </div>
    </div>
</div>    
   
