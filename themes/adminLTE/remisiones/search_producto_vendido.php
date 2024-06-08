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
$this->title = 'CONSULTA PRODUCTO VENDIDO (Remision)';
$this->params['breadcrumbs'][] = $this->title;
$conProducto = \app\models\InventarioPuntoVenta::find()->andWhere(['=','id_punto', $form->punto_venta])->orderBy('nombre_producto DESC')->all();
$conProducto = ArrayHelper::map($conProducto, 'id_inventario', 'nombre_producto');
    
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
    "action" => Url::toRoute("remisiones/search_producto_vendido"),
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
            <?php 
            if($form->punto_venta == null) {?>
                <?= $formulario->field($form, 'punto_venta')->widget(Select2::classname(), [
                   'data' => $conPunto,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
            <?php }else{?>
                 <?= $formulario->field($form, 'punto_venta')->widget(Select2::classname(), [
                   'data' => $conPunto,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?> 
               
                <?= $formulario->field($form, 'producto')->widget(Select2::classname(), [
                      'data' => $conProducto,
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

            <?php }?>
            </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("remisiones/search_producto_vendido") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
            Registros <span class="badge"><?= count($model) ?></span>
        <?php }?>    
    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style ='font-size: 90%;'>         
                <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>P. venta</th>
                <th scope="col" style='background-color:#B9D5CE;'>Proveedor</th>
                <th scope="col" style='background-color:#B9D5CE;'>Marca</th>
                <th scope="col" style='background-color:#B9D5CE;'>Categoria</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>P. Costo</th>
                <th scope="col" style='background-color:#B9D5CE;'>P. Venta</th>
                <th scope="col" style='background-color:#B9D5CE;'>U. Operativa</th>
                <th scope="col" style='background-color:#B9D5CE;'>% Utilidad</th>
                <th scope="col" style='background-color:#B9D5CE;'>Ventas</th>
                <th score="col" style='background-color:#B9D5CE;'></th>  
                         
            </tr>
            </thead>
            <tbody>
                <?php
                $utilidad = 0;
                $porcentaje = 0;
                if($model){
                    foreach ($model as $val):
                        $utilidad = $val->total_linea - $val->inventario->costo_unitario;
                        $porcentaje = ''.number_format((($val->total_linea - $val->inventario->costo_unitario) / $val->inventario->costo_unitario) * 100);
                        ?>
                        <tr style ='font-size: 90%;'>  
                            <td><?= $val->codigo_producto?></td>
                            <td><?= $val->producto?></td>
                            <td><?= $val->puntoVenta->nombre_punto?></td>
                            <td><?= $val->inventario->proveedor->nombre_completo?></td>
                            <td><?= $val->inventario->marca->marca?></td>
                            <td><?= $val->inventario->categoria->categoria?></td>
                            <td><?= $val->fecha_inicio?></td>
                            <td style="text-align: right;"><?= ''.number_format($val->inventario->costo_unitario,0)?></td>
                            <td style="text-align: right;"><?= ''.number_format($val->total_linea,0)?></td>
                            <td style="text-align: right;"><?= ''.number_format($utilidad,0)?></td>
                            <td style="text-align: right;"><?= ''.number_format($porcentaje,0)?> %</td>
                             <td style="text-align: right;"><?= ''.number_format($val->cantidad,0)?></td>
                            <td style= 'width: 25px; height: 10px;'>
                                 <a href="<?= Url::toRoute(["remisiones/view_search_remisiones", "id" => $val->id_remision]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                            </td> 
                        </tr>            
                    <?php 
                    endforeach;
                } ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
        <?php $form->end() ?>
        </div>
     </div>
</div>
<?php if($model){?>
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
<?php } ?>
