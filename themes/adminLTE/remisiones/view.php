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

$this->title = 'REMISIONES ('.$punto_venta->nombre_punto.')';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
    <?php if ($model->autorizado == 0 && $model->numero_remision == 0) { 
        echo  Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_remision, 'accesoToken' => $accesoToken], ['class' => 'btn btn-default btn-sm']); 
    }else{
        if ($model->autorizado == 1 && $model->numero_remision == 0){
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_remision, 'accesoToken' => $accesoToken], ['class' => 'btn btn-default btn-sm']);
            echo Html::a('<span class="glyphicon glyphicon-book"></span> Generar remision', ['generar_remision', 'id' => $model->id_remision, 'accesoToken' => $accesoToken],['class' => 'btn btn-default btn-sm',
                           'data' => ['confirm' => 'Esta seguro de generar la remision de salida al cliente: ('.$model->cliente->nombre_completo.').', 'method' => 'post']]);
        }else{
            if($model->exportar_inventario == 0){
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_factura_venta', 'id' => $model->id_remision], ['class' => 'btn btn-default btn-sm']);                        
                echo Html::a('<span class="glyphicon glyphicon-export"></span> Exportar inventario', ['exportar_inventario_punto', 'id' => $model->id_remision, 'accesoToken' => $accesoToken],['class' => 'btn btn-success btn-sm',
                           'data' => ['confirm' => 'Esta seguro de procesar la descarga de referencias al modulo de inventario.', 'method' => 'post']]);
            }else{
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_remision_venta', 'id' => $model->id_remision], ['class' => 'btn btn-default btn-sm']); 
            }    
        }
    }?>
</p>    
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute(["remisiones/view", 'id' => $model->id_remision, 'accesoToken' => $accesoToken]),
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
       REMISIONES DE SALIDA
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            
            <tr style="font-size: 90%;">
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_remision") ?></th>
                <td><?= Html::encode($model->id_remision) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Nit_Cedula') ?>:</th>
                <td><?= Html::encode($model->cliente->nit_cedula) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Cliente') ?>:</th>
                <td><?= Html::encode($model->cliente->nombre_completo) ?></td>
                 <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Direccion') ?>:</th>
                 <td><?= Html::encode($model->cliente->direccion) ?></td>
            </tr>
            <tr style="font-size: 90%;">
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "numero_remision") ?></th>
                <td><?= Html::encode($model->numero_remision) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                <td><?= Html::encode($model->fecha_inicio) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_hora_registro') ?></th>
                <td><?= Html::encode($model->fecha_hora_registro) ?></td>
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
                <?php if($model->autorizado == 0){?>
                    <?= $formulario->field($form, 'codigo_producto',['inputOptions' =>['autofocus' => 'autofocus', 'class' => 'form-control']])?>
                    <?= $formulario->field($form, 'producto')->widget(Select2::classname(), [
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
            Lineas <span class="badge"> <?= count($detalle_remision)?></span>
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
                    <th scope="col" align="center" style='background-color:#B9D5CE;'>Total linea</th> 
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    
                </tr>
            </thead>
            <tbody>
                <?php         
                $cadena = '';
                $item = \app\models\Documentodir::findOne(18);
                foreach ($detalle_remision as $detalle):
                    $valor = app\models\DirectorioArchivos::find()->where(['=','codigo', $detalle->id_inventario])->andWhere(['=','numero', $item->codigodocumento])->one();
                    $tallaColor = \app\models\FacturaPuntoDetalleColoresTalla::find()->where(['=','id_detalle', $detalle->id_detalle])->one();
                    ?>
                <tr style ='font-size:90%;'>
                    <?php if($model->autorizado == 0 && $model->valor_bruto > 0){?>
                        <td style="width: 20px; height: 20px">
                             <a href="<?= Url::toRoute(["remisiones/crear_talla_color", 'id' => $model->id_remision, 'id_detalle' => $detalle->id_detalle, 'accesoToken'=>$accesoToken])?>"
                                            <span class='glyphicon glyphicon-shopping-cart'></span> </a>  
                        </td>
                    <?php }else{?>
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
                    <td style="text-align: right";><?= ''.number_format($detalle->total_linea,0)?></td>
                    <?php if($model->autorizado == 0){
                        if($model->id_punto == 1){?>
                            <td style="width: 25px; height: 25px;">
                                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ',
                                      ['/remisiones/adicionar_cantidades', 'id' => $model->id_remision, 'id_detalle' => $detalle->id_detalle,'accesoToken' => $accesoToken],
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
                                <a href="<?= Url::toRoute(["remisiones/eliminar_linea_factura_mayorista", 'id' => $model->id_remision, 'id_detalle' => $detalle->id_detalle, 'accesoToken'=>$accesoToken])?>"
                                            <span class='glyphicon glyphicon-trash'></span> </a>  
                            </td>  
                        <?php }else{?>
                            <td style= 'width: 25px; height: 25px;'>
                                <a href="<?= Url::toRoute(["remisiones/eliminar_linea_remision_punto", 'id' => $model->id_remision, 'id_detalle' => $detalle->id_detalle, 'accesoToken'=>$accesoToken])?>"
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
        </table>
    </div>
</div>
<?php $formulario->end() ?>