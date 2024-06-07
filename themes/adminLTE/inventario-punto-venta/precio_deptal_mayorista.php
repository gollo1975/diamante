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


$this->title = 'REGLA DE PRECIOS Y DESCUENTOS';
$this->params['breadcrumbs'][] = $this->title;

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!--<h1>Lista Facturas</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("inventario-punto-venta/crear_precio_venta"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$conProveedor = ArrayHelper::map(app\models\Proveedor::find()->orderBy('nombre_completo ASC')->all(), 'id_proveedor', 'nombre_completo');
$conMarca = ArrayHelper::map(app\models\Marca::find()->orderBy('marca ASC')->all(), 'id_marca', 'marca');
$conPunto = ArrayHelper::map(app\models\PuntoVenta::find()->orderBy('nombre_punto ASC')->all(), 'id_punto', 'nombre_punto');
$conCatergoria = ArrayHelper::map(app\models\Categoria::find()->orderBy('categoria ASC')->all(), 'id_categoria', 'categoria');
?>
<div class="panel panel-success panel-filters">
    <div class="panel-heading">
        Parametros de entrada
    </div>
	
    <div class="panel-body" id="importardocumentocontable">
        <div class="row" >
            <?= $formulario->field($form, "codigo")->input("search") ?>
            <?= $formulario->field($form, "producto")->input("search") ?>
            <?= $formulario->field($form, 'proveedor')->widget(Select2::classname(), [
                'data' => $conProveedor,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'punto_venta')->widget(Select2::classname(), [
                'data' => $conPunto,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'categoria')->widget(Select2::classname(), [
                'data' => $conCatergoria,
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
    </div>    
    <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar registros", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("inventario-punto-venta/crear_precio_venta") ?>" class="btn btn-default btn-sm"><span class='glyphicon glyphicon-refresh'></span> Limpiar</a>
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
        Registros <span class="badge"> <?= count($model)?></span>
      
    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style ='font-size:90%;'>                
                    <th scope="col" style='background-color:#B9D5CE;'>Codigo producto</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Proveedor</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Marca</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Categoria</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Punto de venta</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Vr. costo</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Pv. Deptal</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Pv. Mayorista</th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                                      
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($model as $val):
                        ?>
                        <tr style='font-size:90%;'>             
                            <td><?= $val->codigo_producto ?></td>    
                            <td><?= $val->nombre_producto ?></td>
                            <td><?= $val->proveedor->nombre_completo ?></td>
                            <td><?= $val->marca->marca ?></td>
                            <td><?= $val->categoria->categoria?></td>
                             <td><?= $val->punto->nombre_punto ?></td>
                             <td style="text-align: right"><?= ''.number_format($val->costo_unitario,0)?></td>
                            <td style="text-align: right"><?= ''.number_format($val->precio_deptal,0)?></td>
                            <td style="text-align: right"><?= ''.number_format($val->precio_mayorista,0)?></td>
                            <td style="width: 25px; height: 25px;">
                                <!-- este ajas permite crear los precios al deptal y mayorista -->
                                <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span> ',
                                    ['/inventario-punto-venta/crear_precios_deptal_mayorista','id' => $val->id_inventario],
                                    [
                                        'title' => 'Crear precio para punto de venta y mayorista',
                                        'data-toggle'=>'modal',
                                        'data-target'=>'#modalcrearpreciopuntomayorista'.$val->id_inventario,
                                    ])    
                                ?>
                                <div class="modal remote fade" id="modalcrearpreciopuntomayorista<?= $val->id_inventario ?>">
                                    <div class="modal-dialog modal-lg" style ="width: 500px;">
                                         <div class="modal-content"></div>
                                    </div>
                                </div> 
                            </td>
                            <td style="width: 25px; height: 25px;">
                               <a href="<?= Url::toRoute(["inventario-punto-venta/view_descuentos_comerciales", "id" => $val->id_inventario, 'id_punto' => $val->id_punto]) ?>" ><span class="glyphicon glyphicon-minus-sign" title="Permite crear las reglas de decuentos"></span></a>
                            </td>
                        </tr>  
                    <?php endforeach;?>
                </tbody> 
        </table>   
    </div>
</div>
<?php $formulario->end() ?>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>

<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>