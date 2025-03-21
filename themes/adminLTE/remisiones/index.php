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
//Modelos...
$Punto = \app\models\PuntoVenta::findOne($accesoToken);
$this->title = 'REMISIONES ('.$Punto->nombre_punto.')';
$this->params['breadcrumbs'][] = $this->title;

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtro");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista Facturas</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("remisiones/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);


?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <?php if($accesoToken == 1){?>
            <div class="row" >
                <?= $formulario->field($form, "numero")->input("search") ?>
                <?= $formulario->field($form, 'punto_venta')->widget(Select2::classname(), [
                    'data' => $conPunto,
                    'options' => ['prompt' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?> 
                <?= $formulario->field($form, 'fecha_inicio')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                    'value' => date('d-M-Y', strtotime('+2 days')),
                    'options' => ['placeholder' => 'Seleccione una fecha ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true]])
                ?>
                <?= $formulario->field($form, 'fecha_corte')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                    'value' => date('d-M-Y', strtotime('+2 days')),
                    'options' => ['placeholder' => 'Seleccione una fecha ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true]])
                ?>
                 <?= $formulario->field($form, 'cliente')->widget(Select2::classname(), [
                    'data' => $conCliente,
                    'options' => ['prompt' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?> 

            </div>
        <?php }else{?>
        <div class="row" >
                <?= $formulario->field($form, "numero")->input("search") ?>
            <?= $formulario->field($form, 'cliente')->widget(Select2::classname(), [
                    'data' => $conCliente,
                    'options' => ['prompt' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?> 
                <?= $formulario->field($form, 'fecha_inicio')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                    'value' => date('d-M-Y', strtotime('+2 days')),
                    'options' => ['placeholder' => 'Seleccione una fecha ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true]])
                ?>
                <?= $formulario->field($form, 'fecha_corte')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                    'value' => date('d-M-Y', strtotime('+2 days')),
                    'options' => ['placeholder' => 'Seleccione una fecha ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true]])
                ?>
            </div>
        <?php }?>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("remisiones/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
          Registros <span class="badge"><?= $pagination->totalCount ?></span>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style ='font-size: 90%;'>         
                <th scope="col" style='background-color:#B9D5CE;'>Numero remision</th>
                <th scope="col" style='background-color:#B9D5CE;'>Punto de venta</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nit/Cedula</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total remision</th>
                <th scope="col" style='background-color:#B9D5CE;'>Estado</th>
                <th scope="col" style='background-color:#B9D5CE;'>A. factura</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th score="col" style='background-color:#B9D5CE;'></th>  
                         
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): 
                $detalle = app\models\RemisionDetalles::find()->where(['=','id_remision', $val->id_remision])->one();
                ?>
                <tr style ='font-size: 90%;'>  
                    <td><?= $val->numero_remision?></td>
                    <td><?= $val->puntoVenta->nombre_punto?></td>
                    <td><?= $val->cliente->nit_cedula?></td>
                    <td><?= $val->cliente->nombre_completo?></td>
                    <td><?= $val->fecha_inicio?></td>
                    <td style="text-align: right"><?= ''. number_format($val->total_remision,0)?></td>
                    <td><?= $val->estadoRemision?></td>
                     <td><?= $val->expedirFactura?></td>
                    <td style= 'width: 25px; height: 10px;'>
                         <a href="<?= Url::toRoute(["remisiones/view", "id" => $val->id_remision, 'accesoToken' => $accesoToken]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                    </td> 
                    <?php if(!$detalle){?>
                        <td style= 'width: 25px; height: 10px;'>
                               <a href="<?= Url::toRoute(["remisiones/update", "id" => $val->id_remision, 'accesoToken' => $accesoToken]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                        </td>
                    <?php }else{?>
                        <td style= 'width: 25px; height: 10px;'></td>
                    <?php }?>    
                </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        
     </div>
    <div class="panel-footer text-right" >            
           
            <div class="btn-group btn-sm" role="group">    
                   <button type="button" class="btn btn-success  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Exportar a excel
                       <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li> <?= Html::submitButton("<span class='glyphicon glyphicon-check'></span> Excel", ['name' => 'excel']); ?> </li>
                        <?php 
                        if($fecha_inicio <> '' && $fecha_corte <> '' || $cliente <> null || $punto_venta <> null  ){?>
                            <li><?= Html::a('<span class="glyphicon glyphicon-export"></span> Detalle', ['excel_detalle_remision', 'fecha_inicio' => $fecha_inicio, 'fecha_corte' => $fecha_corte, 'cliente' =>$cliente, 'punto_venta' => $punto_venta]) ?></li>
                        <?php }?> 
                    </ul>
            </div> 
           
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Nueva remision', ['create','accesoToken' => $accesoToken], [
                                           'class' => '',
                                           'title' => 'Proceso que permite crear una nueva remision de venta.', 
                                           'class' => 'btn btn-default btn-sm',
                                           'data' => [
                                               'confirm' => 'Esta seguro de crear la nueva remision de venta al cliente.',
                                               'method' => 'post',
                                            ''   
                                           ], 
                                           
                             ])?>
        </div>
      <?php $form->end() ?>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
