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
$this->title = 'ANULAR (PEDIDOS)';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$form = ActiveForm::begin([
                "method" => "post",                            
            ]);
    ?>

<div class="table-responsive">
<div class="panel panel-success ">
    <div class="panel-heading">
        Registros <span class="badge"><?= $pagination->totalCount ?></span>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style="font-size: 90%;">   
                    <th scope="col" style='background-color:#B9D5CE;'>No pedido</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Departamento</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Municipio</th>
                    <th scope="col" style='background-color:#B9D5CE;'>F. pedido</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Iva</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Vr. Presup.</th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Pedido virtual">P.v.</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style="font-size: 90%;">  
                    <td><?= $val->numero_pedido ?></td>
                    <td><?= $val->cliente ?></td>
                    <td><?= $val->clientePedido->codigoDepartamento->departamento ?></td>
                    <td><?= $val->clientePedido->codigoMunicipio->municipio ?></td>
                    <td><?= $val->fecha_proceso ?></td>
                    <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                    <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                    <td style="text-align: right"><?= ''.number_format($val->gran_total,0) ?></td>
                    <td style="text-align: right"><?= ''.number_format($val->valor_presupuesto,0) ?></td>
                    <?php if($val->pedido_virtual == 0){?>
                        <td style='background-color:#F0F3EF;'><?= $val->pedidoVirtual ?></td>
                    <?php }else{?>
                        <td style='background-color:#E1E9F9; color: black'><?= $val->pedidoVirtual ?></td>
                    <?php }?>  
                    <td style= 'width: 25px; height: 25px;'>
                    <?= Html::a('<span class="glyphicon glyphicon-list-alt"></span> ', ['view_anular', 'id' => $val->id_pedido, 'pedido_virtual' => $val->pedido_virtual], [
                              'class' => '',
                              'data' => [
                                  'confirm' => 'Esta seguro de realizar el proceso de eliminacion de pedidos y presupuesto.?',
                                  'method' => 'post',
                              ],
                          ])?>
                    </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
     <div class="panel-footer text-right" >            
        <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
        <?php $form->end() ?>
    </div>
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>