<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;                       
use yii\bootstrap\Modal;
use app\models\InventarioProductos;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'Factura de venta ('. $model->tipoVenta->concepto .')';
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<p>
    <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
    <?php if ($model->autorizado == 0 && $model->numero_factura == 0) { 
        echo  Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id_factura_punto' => $model->id_factura, 'accesoToken' => $accesoToken], ['class' => 'btn btn-default btn-sm']); ?>
        <!-- Inicio Nuevo Detalle proceso -->
        <?= Html::a('<span class="glyphicon glyphicon-import"></span> Importar remision',
              ['/factura-venta-punto/importar_remision', 'id_factura_punto' => $model->id_factura, 'accesoToken' => $accesoToken],
                ['title' => 'Importar remisiones generadas',
                 'data-toggle'=>'modal',
                 'data-target'=>'#modalimportarremision',
                 'class' => 'btn btn-info btn-sm'
                ])    
        ?>
        <div class="modal remote fade" id="modalimportarremision">
               <div class="modal-dialog modal-lg" style ="width: 850px;">    
                   <div class="modal-content"></div>
               </div>
        </div>
    <?php }else{
        if ($model->autorizado == 1 && $model->numero_factura == 0){
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id_factura_punto' => $model->id_factura, 'accesoToken' => $accesoToken], ['class' => 'btn btn-default btn-sm']);
            echo Html::a('<span class="glyphicon glyphicon-book"></span> Generar factura', ['generar_factura_punto', 'id_factura_punto' => $model->id_factura, 'accesoToken' => $accesoToken],['class' => 'btn btn-default btn-sm',
                           'data' => ['confirm' => 'Esta seguro de generar la factura de venta al cliente '.$model->cliente.' para ser enviada a la Dian.', 'method' => 'post']]);
        }else{
            if($model->exportar_inventario == 0){
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_factura_venta', 'id_factura_punto' => $model->id_factura], ['class' => 'btn btn-default btn-sm']);                        
                if($model->id_remision == null){
                    echo Html::a('<span class="glyphicon glyphicon-export"></span> Exportar inventario', ['exportar_inventario_punto', 'id_factura_punto' => $model->id_factura, 'accesoToken' => $accesoToken],['class' => 'btn btn-success btn-sm',
                           'data' => ['confirm' => 'Esta seguro de procesar el envio de estas referencias vendidas al modulo de inventario.', 'method' => 'post']]);
                }    
            }else{
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_factura_venta', 'id_factura_punto' => $model->id_factura], ['class' => 'btn btn-default btn-sm']); 
            }    
        }
    }?>
</p>    
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute(["factura-venta-punto/view", 'id_factura_punto' => $model->id_factura, 'accesoToken' => $accesoToken]),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);?>
<div class="panel panel-success">
    <div class="panel-heading">
       FACTURA DE VENTA
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            
            <tr style="font-size: 90%;">
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_factura") ?></th>
                <td><?= Html::encode($model->id_factura) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nit_cedula') ?></th>
                <td><?= Html::encode($model->nit_cedula) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_cliente') ?></th>
                <td><?= Html::encode($model->cliente) ?></td>
                 <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'direccion') ?></th>
                 <td><?= Html::encode($model->direccion) ?></td>
            </tr>
            <tr style="font-size: 90%;">
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "numero_factura") ?></th>
                <td><?= Html::encode($model->numero_factura) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                <td><?= Html::encode($model->fecha_inicio) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_vencimiento') ?></th>
                <td><?= Html::encode($model->fecha_vencimiento) ?></td>
                 <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                 <td><?= Html::encode($model->user_name) ?></td>
            </tr>
        </table>
    </div>
</div>    
<div class="panel panel-success panel-filters">
        <div class="panel-heading">
            Busqueda por codigo de barras
        </div>

        <div class="panel-body" id="entrada_producto">
            <div class="row" >
                <?php if($model->autorizado == 0 && $model->id_remision == null){?>
                    <?= $formulario->field($form, 'codigo_producto',['inputOptions' =>['autofocus' => 'autofocus', 'class' => 'form-control']])?>
                    <?= $formulario->field($form, 'nombre_producto')->widget(Select2::classname(), [
                       'data' => $inventario,
                       'options' => ['prompt' => 'Seleccione...'],
                       'pluginOptions' => [
                           'allowClear' => true
                       ],
                   ]); ?> 
                <?php }else{?>
                        <?= $formulario->field($form, 'codigo_producto',['inputOptions' =>['autofocus' => 'autofocus', 'class' => 'form-control', 'disabled' => 'true']])?>
                <?php }?>
           </div>
        </div>    
       <?php if($model->autorizado == 0){?>
            <div class="panel-footer text-right">
                   <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>               
            </div>
       <?php }?>    

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
            Lineas <span class="badge"> <?= count($detalle_factura)?></span>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style ='font-size:90%;'>  
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Codigo</th>                        
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre del producto</th>   
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Imagen</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Valor unitario</th>                        
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Subtotal</th>
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>% Descto</th>
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. descuento</th>  
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>% Iva</th>
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Valor Iva</th>
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Total linea</th> 
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    
                </tr>
            </thead>
            <tbody>
                <?php     
              
                $cadena = '';
                $item = \app\models\Documentodir::findOne(18);
                foreach ($detalle_factura as $detalle):
                    $inventario = app\models\InventarioPuntoVenta::findOne($detalle->id_inventario);
                    $id_inventario = $inventario->codigo_enlace_bodega; //asigna el codigo de enlace
                    if($id_inventario){
                         $valor = \app\models\DirectorioArchivos::find()->where(['=', 'codigo', $id_inventario])->andWhere(['=', 'numero', $item->codigodocumento])->one();
                    }else{
                       $valor = app\models\DirectorioArchivos::find()->where(['=','codigo', $detalle->id_inventario])
                                                                  ->andWhere(['=','predeterminado', 1])->andWhere(['=','numero', $item->codigodocumento])->one();
                    }    
                    
                    $tallaColor = \app\models\FacturaPuntoDetalleColoresTalla::find()->where(['=','id_detalle', $detalle->id_detalle])->one();
                    $invent = app\models\InventarioPuntoVenta::findOne($detalle->id_inventario);
                    ?>
                    <tr style ='font-size:90%;'>
                        <?php if($model->autorizado == 0 && $model->valor_bruto > 0){
                            if($invent->aplica_talla_color == 1){ ?>
                                <td style="width: 20px; height: 20px">
                                     <a href="<?= Url::toRoute(["factura-venta-punto/crear_talla_color", 'id_factura_punto' => $model->id_factura, 'id_detalle' => $detalle->id_detalle, 'accesoToken'=>$accesoToken])?>"
                                                    <span class='glyphicon glyphicon-shopping-cart'></span> </a>  
                                </td>
                            <?php }else{?>
                                <td style="width: 20px; height: 20px"></td>
                            <?php }
                        } else { ?>
                            <td style="width: 20px; height: 20px"></td>
                        <?php }    
                        if($tallaColor){?>   
                            <td style="background-color: #d8f3dc"><?= $detalle->codigo_producto?></td>
                        <?php }else{?>
                            <td><?= $detalle->codigo_producto?></td>
                        <?php } ?>    
                        <td><?= $detalle->producto?></td>
                        <?php if($valor){
                            $cadena = 'Documentos/'.$valor->numero.'/'.$valor->codigo.'/'. $valor->nombre;
                            if($valor->extension == 'png' || $valor->extension == 'jpeg' || $valor->extension == 'jpg'){?>
                               <td  style=" text-align: center; background-color: white" title="<?php echo $detalle->producto?>"> <?= yii\bootstrap\Html::img($cadena, ['width' => '80;', 'height' => '60;'])?></td>
                            <?php }else {?>
                                <td><?= 'NOT FOUND'?></td>
                            <?php } 
                        }else{?>
                              <td></td>
                        <?php }?>      
                        <td style="text-align: right";><?= ''.number_format($detalle->cantidad,0)?></td>
                        <td style="text-align: right";><?= ''.number_format($detalle->valor_unitario,0)?></td>
                        <td style="text-align: right";><?= ''.number_format($detalle->subtotal,0)?></td>
                        <td style="text-align: right"><?= $detalle->porcentaje_descuento?>%</td>
                        <td style="text-align: right";><?= ''.number_format($detalle->valor_descuento,0)?></td>
                        <td style="text-align: right";><?= ''.number_format($detalle->porcentaje_iva,0)?>%</td>
                        <td style="text-align: right";><?= ''.number_format($detalle->impuesto,0)?></td>
                        <td style="text-align: right";><?= ''.number_format($detalle->total_linea,0)?></td>
                        <?php 
                        if($model->autorizado == 0){
                            if($model->id_tipo_venta == 2 ){
                                if($model->id_remision == null){?>
                                    <td style="width: 25px; height: 25px;">
                                        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ',
                                              ['/factura-venta-punto/adicionar_cantidades', 'id_factura_punto' => $model->id_factura, 'id_detalle' => $detalle->id_detalle,'accesoToken' => $accesoToken],
                                              [
                                                  'title' => 'Adicionar cantidades al codigo del producto',
                                                  'data-toggle'=>'modal',
                                                  'data-target'=>'#modaladicionarcantidades'.$detalle->id_detalle,
                                              ])    
                                        ?>
                                        <div class="modal remote fade" id="modaladicionarcantidades<?= $detalle->id_detalle ?>">
                                          <div class="modal-dialog modal-lg" style ="width: 500px;">
                                              <div class="modal-content"></div>
                                          </div>
                                        </div>
                                    </td>
                                    <td style= 'width: 25px; height: 25px;'>
                                        <a href="<?= Url::toRoute(["factura-venta-punto/eliminar_linea_factura_mayorista", 'id_factura_punto' => $model->id_factura, 'id_detalle' => $detalle->id_detalle, 'accesoToken'=>$accesoToken])?>"
                                                    <span class='glyphicon glyphicon-trash'></span> </a>  
                                    </td>  
                                <?php }else{?>
                                    <td style="width: 25px; height: 25px;"></td>
                                     <td style="width: 25px; height: 25px;"></td>

                                <?php }    
                            }else{         ?>
                                <td style= 'width: 25px; height: 25px;'>
                                    <a href="<?= Url::toRoute(["factura-venta-punto/eliminar_linea_factura_punto", 'id_factura_punto' => $model->id_factura, 'id_detalle' => $detalle->id_detalle, 'accesoToken'=>$accesoToken])?>"
                                    <span class='glyphicon glyphicon-trash'></span> </a>  
                                </td>  
                                <td style= 'width: 25px; height: 25px;'></td>
                            <?php }
                        }else{?>
                               <td style= 'width: 25px; height: 25px;'>
                                <td style= 'width: 25px; height: 25px;'>
                        <?php } ?>   

                    </tr>
                <?php endforeach;?>
            </tbody>
            <tr style="font-size: 90%; background-color:#B9D5CE">
                <td colspan="12"></td>
                <td style="text-align: right;"><b></b></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="10"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>SUBTOTAL:</b></td>
                <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->subtotal_factura,0); ?></b></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="10"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>DSCTO:</b></td>
                <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->descuento,0); ?></b></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="10"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>IMPUESTO :</b></td>
                <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->impuesto,0); ?></b></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="10"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>RETENCION (<?= $model->porcentaje_rete_fuente?> %) :</b></td>
                <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_retencion,0); ?></b></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="10"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>RETE IVA (<?= $model->porcentaje_rete_iva?> %) :</b></td>
                <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_reteiva,0); ?></b></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="font-size: 90%;">
                <td colspan="10"></td>
                <td style="text-align: right; background-color:#F0F3EF"><b>TOTAL PAGAR:</b></td>
                <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->total_factura,0); ?></b></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
<?php $formulario->end() ?>   