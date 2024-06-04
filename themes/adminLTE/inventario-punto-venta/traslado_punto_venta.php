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
$this->title = 'TRASLADO (Punto de venta)';
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
    "action" => Url::toRoute("inventario-punto-venta/traslado_producto"),
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
	
    <div class="panel-body" id="filtro" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "codigo")->input("search") ?>
            <?= $formulario->field($form, "producto")->input("search") ?>
            <?= $formulario->field($form, 'punto_venta')->widget(Select2::classname(), [
                'data' => $conPunto,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'marca')->widget(Select2::classname(), [
                'data' => $conMarca,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("inventario-punto-venta/traslado_producto") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Punto de venta</th>
                <th scope="col" style='background-color:#B9D5CE;'>Marca</th>
                <th scope="col" style='background-color:#B9D5CE;'>Categoria</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>Existencias</th>
                <th score="col" style='background-color:#B9D5CE;'></th>  
                         
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
                <tr style ='font-size: 90%;'>  
                    <td><?= $val->codigo_producto?></td>
                    <td><?= $val->nombre_producto?></td>
                    <td><?= $val->punto->nombre_punto?></td>
                    <td><?= $val->marca->marca?></td>
                    <td><?= $val->categoria->categoria?></td>
                    <td><?= $val->fecha_proceso?></td>
                    <td style="text-align:right"><?= ''.number_format($val->stock_inventario,0)?></td>
                    <?php 
                    if($val->aplica_talla_color == 1){?>
                        <td style= 'width: 25px; height: 10px;'>
                             <a href="<?= Url::toRoute(["inventario-punto-venta/view_traslado", "id" => $val->id_inventario,'id_punto' => $val->id_punto,'sw' => 0]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite trasladar productos entre puntos de ventas."></span></a>
                        </td> 
                    <?php }else{?>
                        <td style= 'width: 25px; height: 10px;'>
                          <a href="<?= Url::toRoute(["inventario-punto-venta/view_traslado", "id" => $val->id_inventario,'id_punto' => $val->id_punto, 'sw' => 1]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite trasladar productos entre puntos de ventas."></span></a>
                        </td>    
                    <?php }?>    
                </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
     </div>
</div>
<?php $formulario->end() ?>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
