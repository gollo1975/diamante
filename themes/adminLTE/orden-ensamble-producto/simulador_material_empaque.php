<?php

//clase
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;

/* @var $this yii\web\View */
/* @var $model app\models\Ordenproduccion */

$this->title = 'SIMULADOR MATERIAL DE EMPAQUE';
$this->params['breadcrumbs'][] = ['label' => 'Simulador material de empaque', 'url' => ['view','id' =>$id, 'token' =>$token, 'sw' => $sw]];
$this->params['breadcrumbs'][] = $id;
?>
<div class="orden-produccion-simulador">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view', 'id' => $id,'token'=> $token, 'sw' => $sw], ['class' => 'btn btn-primary btn-sm']) ?>  
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4>LISTADO DE MATERIAL DE EMPAQUE</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr style="font-size: 90%;">
                        <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                        <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Materia prima</b></th>
                        <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Stock</b></th>
                        <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Unidades a fabricar</b></th>
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cumple stock</th>
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad faltante</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $cantidad = 0; $auxiliar = ''; $unidades = 0;
                    foreach ($empaque as $materia):  ?>
                        <tr style="font-size: 90%;">
                            <td><?= $materia->codigo_materia_prima?> </td>
                            <td ><?= $materia->materia_prima?> </td>
                            <td style="text-align: right"><?= $materia->stock?> </td>
                            <td style="text-align: right"><?= $orden->total_unidades?> </td>
                            <?php
                            $cantidad = $orden->total_unidades;
                            if($cantidad <= $orden->total_unidades){
                                $auxiliar = 'OK';
                                $unidades = 0;
                            }else{
                                  $auxiliar = 'FALTA';
                                  $unidades = ($cantidad - $orden->total_unidades);
                            }?>
                            <td><?= $auxiliar?> </td>
                            <td style="text-align: right"><?= $unidades?></td>
                              
                        </tr>
                    <?php endforeach;  ?>
                </tbody>
            </table>
        </div>
    </div>    
    
</div>