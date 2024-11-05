<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;
use app\models\Clientes;
use app\models\AgentesComerciales;
use app\models\User;

$this->title = 'Consulta (DESABASTECIMIENTO)';
$this->params['breadcrumbs'][] = $this->title;
$form = ActiveForm::begin(["method" => "post"]);?>

<div class="table-responsive">
    <div class="panel panel-success ">
        <div class="panel-heading">
            <?php if($model){?>
            Listado de referencias
            <?php }?>
        </div>
        <table class="table table-bordered table-hover">
        <thead>
            <tr style="font-size: 90%;">   
                <th scope="col" style='background-color:#B9D5CE;'>Referencia</th>
                <th scope="col" style='background-color:#B9D5CE;'>Presentacion producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nombre del producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Unidades vendidas</th>
                <th scope="col" style='background-color:#B9D5CE;'>Stock (Unidades)</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>  
            </tr>
        </thead>
        <tbody>
            <?php
            $auxiliar = 0; $contar = 0; $sumaVentas = 0;
            foreach ($model as $val):
                if($auxiliar <> $val->id_inventario){
                    $datos = app\models\PedidoDetalles::find()->where(['=','id_inventario', $val->id_inventario])->andWhere(['<','cantidad_faltante', 0])->all(); 
                       if(count($datos) > 0){
                            $contar = 0;  
                            $sumaVentas = 0;
                            foreach ($datos as $key => $dato) {
                               $contar += $dato->cantidad_faltante;
                               $sumaVentas += $dato->cantidad;
                            } ?> 
                            <tr style="font-size: 90%;">  
                                <td><?= $val->inventario->codigo_producto ?></td>
                                <td><?= $val->inventario->nombre_producto ?></td>
                                <td><?= $val->inventario->producto->nombre_producto ?></td>
                                <td><?= $val->inventario->producto->grupo->nombre_grupo?> </td>
                                <td style="text-align: right"><?= $sumaVentas?></td>
                                <td style="text-align: right"><?= $contar ?></td>
                                <td style= 'width: 25px; height: 25px;'>
                                    <a href="<?= Url::toRoute(["pedidos/view_desabastecimiento", "id_inventario" => $val->id_inventario]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                                </td>
                            </tr>
                       <?php }
                       $auxiliar = $val->id_inventario;
                }else{
                    $auxiliar = $val->id_inventario;
                }
                ?>
            <?php endforeach;?>
        </tbody>
    </table>
    </div>    
</div>
<div class="panel-footer text-right" >   
     <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Excel", ['name' => 'excel', 'class' => 'btn btn-success btn-sm']); ?>
</div>
 <?= LinkPager::widget(['pagination' => $pagination]) ?>
<?php $form->end() ?>