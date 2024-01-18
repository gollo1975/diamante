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


$this->title = 'REGLAS Y PRECIOS';
$this->params['breadcrumbs'][] = $this->title;

?>


<!--<h1>Lista Facturas</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("orden-produccion/crear_precio_venta"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$grupo = ArrayHelper::map(app\models\GrupoProducto::find()->where(['=','ver_registro', 1])->orderBy('nombre_grupo DESC')->all(), 'id_grupo', 'nombre_grupo');?>
<div class="panel panel-success panel-filters">
    <div class="panel-heading">
        Parametros de entrada
    </div>
	
    <div class="panel-body" id="importardocumentocontable">
        <div class="row" >
            <?= $formulario->field($form, "codigo")->input("search") ?>
            <?= $formulario->field($form, "producto")->input("search") ?>
            <?= $formulario->field($form, 'grupo')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
       </div>
    </div>    
    <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar registros", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("orden-produccion/crear_precio_venta") ?>" class="btn btn-default btn-sm"><span class='glyphicon glyphicon-refresh'></span> Limpiar</a>
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
        Registros <span class="badge"> <?= 1?></span>
      
    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style ='font-size:90%;'>                
                    <th scope="col" style='background-color:#B9D5CE;'>Codigo producto</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Nombre del Grupo</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Entradas</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Pv. Deptal</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Pv. Mayorista</th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                if($sw <> 0){
                    foreach ($model as $val):
                        $concodigo = \app\models\InventarioReglaDescuento::find()->where(['=','id_inventario', $val->id_inventario])->one();
                        ?>
                        <tr style='font-size:90%;'>             
                            <td><?= $val->codigo_producto ?></td>    
                            <td><?= $val->nombre_producto ?></td>
                            <td><?= $val->grupo->nombre_grupo ?></td>
                            <td style="text-align: right"><?= ''.number_format($val->unidades_entradas,0)?></td>
                            <td style="text-align: right"><?= ''.number_format($val->stock_unidades,0)?></td>
                            <td style="text-align: right"><?= ''.number_format($val->precio_deptal,0)?></td>
                            <td style="text-align: right"><?= ''.number_format($val->precio_mayorista,0)?></td>
                            <td style="width: 25px; height: 25px;">
                                <a href="<?= Url::toRoute(["orden-produccion/crearprecioventaproducto", "id" => $val->id_inventario]) ?>" ><span class="glyphicon glyphicon-share-alt" title="Permite crear varios precios de venta para publico"></span></a>
                            </td>
                            <td style="width: 25px; height: 25px;">
                                <!-- Inicio Nuevo Detalle proceso -->
                                <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span> ',
                                    ['/orden-produccion/crear_precio_unico','id' => $val->id_inventario],
                                    [
                                        'title' => 'Crear precio para punto de venta y venta al por mayor',
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
                               <a href="<?= Url::toRoute(["orden-produccion/view_regla_descuento", "id" => $val->id_inventario]) ?>" ><span class="glyphicon glyphicon-minus-sign" title="Permite crear las reglas de decuentos"></span></a>
                            </td>
                        </tr>  
                    <?php endforeach;
                }?>
            </tbody> 
        </table>   
    </div>
</div>
<?php $formulario->end() ?>

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