<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use app\models\ComprobanteEgresoTipo;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'ENTRADA DE INVENTARIO (BODEGA)';
$this->params['breadcrumbs'][] = $this->title;
$entrada = \app\models\EntradaProductosInventario::findOne($id);
?>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<p>
    <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
    <?php if ($entrada->autorizado == 0 && $entrada->enviar_materia_prima  == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado_sinoc', 'id' => $id,  'bodega' => $bodega], ['class' => 'btn btn-default btn-sm']);
        } else {
            if ($entrada->autorizado == 1 && $entrada->enviar_materia_prima  == 0) {?> 
                <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado_sinoc', 'id' => $id, 'bodega' => $bodega], ['class' => 'btn btn-default btn-sm'])?>
                <?= Html::a('<span class="glyphicon glyphicon-send"></span> Actualizar inventario', ['enviar_inventario_modulo_sinorden', 'id' => $id, 'bodega' => $bodega],['class' => 'btn btn-info btn-sm',
                           'data' => ['confirm' => 'Esta seguro de actualizar el modulo de  inventarios .', 'method' => 'post']]);?>
            <?php }
    }?>        
</p>  

<!--<h1>Lista Facturas</h1>-->

<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute(["entrada-productos-inventario/codigo_barra_ingreso", 'id' => $id, 'bodega' =>$bodega]),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-3 control-label'],
                    'options' => []
                ],

]);?>
 
<?php if($entrada->autorizado == 0){?>

    <div class="panel panel-success panel-filters">
        <div class="panel-heading">
            Busqueda por codigo de barras
        </div>

        <div class="panel-body" id="entrada_producto">
            <div class="row" >
                <?= $formulario->field($form, 'codigo_producto',['inputOptions' =>['autofocus' => 'autofocus', 'class' => 'form-control']])?>
           </div>
        </div>    
        <div class="panel-footer text-right">
                <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
        </div>

    </div>
    

<?php }
 $formulario->end() ?>
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
                    <th scope="col" align="center" style='background-color:#B9D5CE;'></th>   
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Codigo</th>                        
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Producto</th>                        
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Editar precio Unit.</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>F. vcto</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Cant.</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Iva</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. unitario</th>                        
                     <th scope="col" align="center" style='background-color:#B9D5CE;'>Subtotal</th>
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Impuesto</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Total</th> 
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($model as $val):
                    $inve_entrada = \app\models\InventarioPuntoVenta::findOne($val->id_inventario);
                    ?>
                    <tr style ='font-size:90%;'>
                        <?php if($inve_entrada->aplica_talla_color == 1 && $val->entrada->autorizado == 0){
                            if($entrada_producto->enviar_materia_prima == 0){?>
                                <td style="width: 20px; height: 20px">
                                     <a href="<?= Url::toRoute(["entrada-productos-inventario/crear_talla_color_entrada", 'id' =>$id ,'id_inventario' => $val->id_inventario , 'id_detalle' => $val->id_detalle, 'token' => 1])?>"
                                                    <span class='glyphicon glyphicon-shopping-cart'></span> </a>  
                                </td>
                            <?php }else{?>
                                <td style="width: 20px; height: 20px"></td>
                            <?php }
                        }else{?>
                                <td style="width: 20px; height: 20px"></td>
                        <?php }?>        
                        
                        <td> <?= $val->inventario->codigo_producto?></td>
                        <td> <?= $val->inventario->nombre_producto?></td>
                        <td align="center"><select name="actualizar_precio[]"  style="width: 60px; ">
                                <?php if ($val->actualizar_precio == 0){echo $actualizar = "NO";}else{echo $actualizar ="SI";}?>
                                <option value="<?= $val->actualizar_precio ?>"><?= $actualizar ?></option>
                                <option value="0">NO</option>
                                <option value="1">SI</option>

                        </select> </td> 
                        <td style="padding-right: 1;padding-right: 0; "><input type="date" name="fecha_vcto[]" value="<?= $val->fecha_vencimiento ?>" size="7" required="true"> </td> 
                        <td style="padding-right: 1;padding-right: 0; "><input type="text" name="cantidad[]" value="<?= $val->cantidad ?>" size="7" required="true" style="text-align: right"> </td> 
                        <td> <?= $val->porcentaje_iva ?></td>  
                        <td style="padding-right: 1;padding-right: 0;"><input type="text" name="valor_unitario[]" value="<?= $val->valor_unitario ?>" size="7" style="text-align: right"> </td> 
                        <td style="text-align: right"> <?= '$ '.number_format($val->subtotal,0)?></td>
                        <td style="text-align: right"> <?= '$ '.number_format($val->total_iva, 0)?></td>
                        <td style="text-align: right"> <?= '$ '.number_format($val->total_entrada, 0)?></td>
                        <input type="hidden" name="detalle_entrada[]" value="<?= $val->id_detalle ?>">
                        <?php if($entrada->autorizado == 0){?>
                            <td style= 'width: 1%; height: 1%;'>
                                <a href="<?= Url::toRoute(["entrada-productos-inventario/eliminar_manual", 'id' => $id, 'detalle_manual' => $val->id_detalle, 'bodega' => $bodega])?>"
                                        <span class='glyphicon glyphicon-trash'></span> </a>
                            </td>    
                        <?php }else{?>
                            <td style= 'width: 1%; height: 1%;'></td>
                        <?php }?>    
                    </tr>  
                <?php endforeach;?>
            </tbody> 
        </table> 
        <?php if($entrada->autorizado == 0){?>
            <div class="panel-footer text-right">  
                <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizarlineas']);?>
            </div>    
        <?php }?>   
</div>
</div>    
 <?php $formulario->end() ?>   


