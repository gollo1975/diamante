<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle del piso';
$this->params['breadcrumbs'][] = ['label' => 'Pisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_piso;
?>
<div class="pisos-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_piso], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_piso], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            PISOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_piso') ?>:</th>
                    <td><?= Html::encode($model->id_piso) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion') ?>:</th>
                    <td><?= Html::encode($model->descripcion) ?></td>
              </tr>
            </table>
        </div>
    </div>
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listadorack" aria-controls="listadorack" role="tab" data-toggle="tab">Listado de racks <span class="badge"><?= count($conRacks) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="listadorack">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 90%;">
                                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Numero rack</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Nombre</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Capacidad</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>User name</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Estado</th>
                                        <th scope="col" style='background-color:#B9D5CE;'><span title="Control de invetario">C. Inventario</span></th>
                                    </tr>
                                </thead>
                                <?php
                                foreach ($conRacks as $val):?>
                                <tr style="font-size: 90%;">
                                    <td><?= $val->id_rack?></td>
                                    <td><?= $val->numero_rack?></td>
                                    <td><?= $val->descripcion?></td>
                                    <td style="text-align: right"><?= ''.number_format($val->capacidad_instalada,0)?></td>
                                    <td style="text-align: right"><?= ''.number_format($val->capacidad_actual,0)?></td>
                                    <td><?= $val->estadoActivo?></td>
                                    <td><?= $val->user_name?></td>
                                    <td><?= $val->controlarCapacidad?></td>
                                </tr>
                                <?php endforeach;?>
                            </table>
                        </div>
                    </div>
                </div>    
            </div>
            <!--TERMINA TABS-->
        </div>
    </div>    
                
</div>

