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
$this->title = 'CONSULTA DE INVENTARIO ('.$local->nombre_punto.')';
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
    "action" => Url::toRoute("inventario-punto-venta/search_inventario"),
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
        <?php if($tokenAcceso == 1){?>
            <div class="row" >
                <?= $formulario->field($form, "codigo")->input("search") ?>
                 <?= $formulario->field($form, "producto")->input("search") ?>
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
                 <?= $formulario->field($form, 'punto_venta')->widget(Select2::classname(), [
                    'data' => $conPunto,
                    'options' => ['prompt' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?> 
                <?= $formulario->field($form, 'inventario_inicial')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
            </div>
        <?php }else{?>
            <div class="row" >
                <?= $formulario->field($form, "codigo")->input("search") ?>
                <?= $formulario->field($form, "producto")->input("search") ?>
            </div>
        <?php }?>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("inventario-punto-venta/search_inventario") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <?php if($tokenAcceso == 1){?>
                    <tr style ='font-size: 90%;'>         
                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Bodega</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Proveedor</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Marca</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Categoria</th>
                        <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Entradas</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                        <th scope="col" style='background-color:#B9D5CE;'>P. mayorista</th>
                        <th scope="col" style='background-color:#B9D5CE;'>P. deptal</th>
                        <th scope="col" style='background-color:#B9D5CE;'></th>
                    </tr>
                <?php }else{?>
                     <tr style ='font-size: 90%;'>         
                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Bodega</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Marca</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Categoria</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Entradas</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Precio venta</th>
                        <th scope="col" style='background-color:#B9D5CE;'></th>
                    </tr>
                <?php }?>    
            </thead>
            <tbody>
            <?php foreach ($model as $val): 
                if($tokenAcceso == 1){
                    ?>
                    <tr style ='font-size: 90%;'>  
                        <td><?= $val->codigo_producto?></td>
                        <td><?= $val->nombre_producto?></td>
                        <td><?= $val->punto->nombre_punto?></td>
                        <td><?= $val->proveedor->nombre_completo?></td>
                        <td><?= $val->marca->marca?></td>
                        <td><?= $val->categoria->categoria?></td>
                        <td><?= $val->fecha_proceso?></td>
                        <td style="text-align: right;"><?= ''.number_format($val->stock_unidades,0)?></td>
                        <td style="text-align: right; background-color:#CBDDE3; color: black"><?= ''.number_format($val->stock_inventario,0)?></td>
                        <td style="text-align: right;"><?= ''.number_format($val->precio_mayorista,0)?></td>
                        <td style="text-align: right;"><?= ''.number_format($val->precio_deptal,0)?></td>
                        <td style= 'width: 25px; height: 10px;'>
                             <a href="<?= Url::toRoute(["inventario-punto-venta/view_search", "id" => $val->id_inventario, 'tokenAcceso' => $tokenAcceso, 'token' =>$token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                        </td> 
                    </tr>   
                <?php  }else{?>
                    <tr style ='font-size: 90%;'>  
                        <td><?= $val->codigo_producto?></td>
                        <td><?= $val->nombre_producto?></td>
                        <td><?= $val->punto->nombre_punto?></td>
                        <td><?= $val->marca->marca?></td>
                        <td><?= $val->categoria->categoria?></td>
                        <td style="text-align: right;"><?= ''.number_format($val->stock_unidades,0)?></td>
                        <td style="text-align: right; background-color:#CBDDE3; color: black"><?= ''.number_format($val->stock_inventario,0)?></td>
                        <td style="text-align: right;"><?= ''.number_format($val->precio_deptal,0)?></td>
                        <td style= 'width: 25px; height: 10px;'>
                             <a href="<?= Url::toRoute(["inventario-punto-venta/view_search", "id" => $val->id_inventario, 'tokenAcceso' => $tokenAcceso, 'token' =>$token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                        </td> 
                    </tr>   
                <?php }    
            endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
        <?php $form->end() ?>
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
