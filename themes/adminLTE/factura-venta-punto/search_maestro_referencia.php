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

$this->title = 'CONSULTA (VENTAS)';
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtropedido");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("factura-venta-punto/search_maestro_referencia"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-3 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-1 control-label'],
                    'options' => []
                ],

]);
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtropedido" style="display:block">
        <div class="radio radio-inline" style="text-align: left" >
            <?= $formulario->field($form, 'busqueda')->label(false)->radioList([1 =>'Clientes con mas ventas',2 =>'Vendedor con menos ventas']);?>
             <?= $formulario->field($form, 'desde')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Fecha de inicio ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
            <?= $formulario->field($form, 'hasta')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Fecha corte ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("factura-venta-punto/search_maestro_referencia") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>
<?php $formulario->end() ?>
<?php
$form = ActiveForm::begin([
                "method" => "post",                            
            ]);
    ?>

<div class="table-responsive">
  <div class="panel panel-success ">
    <div class="panel-heading">
        <?php if($model){?>
           Resultado de la busqueda
        <?php }?>    
    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style="font-size: 90%;">   
                    <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Agente comercial</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Punto de venta</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Desde</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Hasta</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Total factura</th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($model){
                    if($busqueda == 1){
                        foreach ($model as $val):?>
                            <tr style="font-size: 90%;">
                                <td><?=  $val->clienteFactura->nit_cedula?></td>
                                <td><?=  $val->clienteFactura->nombre_completo?></td>
                                <td><?=  $val->agente->nombre_completo?></td>
                                <td><?=  $val->puntoVenta->nombre_punto?></td>
                                <td><?=  $desde?></td>
                                <td><?=  $hasta?></td>
                                <td style="text-align: right"><?=  '$'.number_format($val->subtotal_factura,0)?></td>
                                <td style="text-align: right"><?=  '$'.number_format($val->total_factura,0)?></td>
                                <td style= 'width: 25px; height: 10px; '>
                                    <a href="<?= Url::toRoute(["factura-venta-punto/listado_facturas", "desde" => $desde, 'hasta' => $hasta,'id_cliente' => $val->id_cliente, 'id_agente' => $val->id_agente,'busqueda' => $busqueda]) ?>" ><span class="glyphicon glyphicon-list-alt"></span></a>
                                </td>
                            </tr>
                        <?php endforeach;
                    }else{
                       foreach ($model as $val):?>
                            <tr style="font-size: 90%;">
                                <td style='background-color:#B9D5EE;'><?=  $val->clienteFactura->nit_cedula?></td>
                                <td style='background-color:#B9D5EE;'><?=  $val->clienteFactura->nombre_completo?></td>
                                <td style='background-color:#B9D5EE;'><?=  $val->agente->nombre_completo?></td>
                                <td style='background-color:#B9D5EE;'><?=  $val->puntoVenta->nombre_punto?></td>
                                <td style='background-color:#B9D5EE;'><?=  $desde?></td>
                                <td style='background-color:#B9D5EE;'><?=  $hasta?></td>
                                <td style="text-align: right; background-color:#B9D5EE;"><?=  '$'.number_format($val->subtotal_factura,0)?></td>
                                <td style="text-align: right; background-color:#B9D5EE;"><?=  '$'.number_format($val->total_factura,0)?></td>
                                <td style= 'width: 25px; height: 10px; '>
                                    <a href="<?= Url::toRoute(["factura-venta-punto/listado_facturas", "desde" => $desde, 'hasta' => $hasta,'id_cliente' => $val->id_cliente, 'id_agente' => $val->id_agente, 'busqueda' => $busqueda]) ?>" ><span class="glyphicon glyphicon-list-alt"></span></a>
                                </td>
                            </tr>
                        <?php endforeach; 
                    }    
                }?>        
            </tbody>
        </table>
 
       <?php $form->end() ?>
    </div>
</div>
