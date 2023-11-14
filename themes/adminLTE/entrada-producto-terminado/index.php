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
use app\models\Proveedor;
use app\models\OrdenCompra;



$this->title = 'ENTRADA PRODUCTO TERMINADO';
$this->params['breadcrumbs'][] = $this->title;

?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtro");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista Facturas</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("entrada-producto-terminado/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$proveedor = ArrayHelper::map(Proveedor::find()->orderBy ('nombre_completo ASC')->all(), 'id_proveedor', 'nombre_completo');
$orden_compra = ArrayHelper::map(OrdenCompra::find()->orderBy ('descripcion ASC')->all(), 'id_orden_compra', 'descripcion');

?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            
             <?= $formulario->field($form, 'proveedor')->widget(Select2::classname(), [
                'data' => $proveedor,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'orden')->widget(Select2::classname(), [
                'data' => $orden_compra,
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
            <?= $formulario->field($form, 'tipo_entrada')->dropdownList(['1' => 'ORDEN DE COMPRA', '2' => 'MANUAL'], ['prompt' => 'Seleccione...']) ?>
            
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("entrada-producto-terminado/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                
                <th scope="col" style='background-color:#B9D5CE;'>No Orden</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo orden</th>
                <th scope="col" style='background-color:#B9D5CE;'>Entrada</th>
                <th scope="col" style='background-color:#B9D5CE;'>Proveedor</th>
                <th scope="col" style='background-color:#B9D5CE;'>Soporte</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. entrada</th>
                <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th>
                <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total </th>
                <th scope="col" style='background-color:#B9D5CE;'>User name </th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Autorizado">Aut.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th score="col" style='background-color:#B9D5CE;'></th>                              
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): 
                $detalle = app\models\EntradaProductoTerminadoDetalle::find()->where(['=','id_entrada', $val->id_entrada])->one();
                ?>
            <tr style ='font-size: 90%;'>                
                <td><?= $val->id_entrada?></td>
                <?php if($val->id_orden_compra == NULL){?>
                    <td><?= 'NO FOUNT'?></td>
                <?php }else{?>    
                    <td><?= $val->ordenCompra->tipoOrden->descripcion_orden?></td>
                <?php } ?>    
                   <td><?= $val->tipoEntrada?></td>    
                <td><?= $val->proveedor->nombre_completo?></td>
                <td><?= $val->numero_soporte?></td>
                <td><?= $val->fecha_proceso?></td>
                <td style="text-align: right;"><?= ''.number_format($val->subtotal,0)?></td>
                <td style="text-align: right"><?= ''.number_format($val->impuesto,0)?></td>
                <td style="text-align: right"><?= ''.number_format($val->total_salida,0)?></td>
                <td><?= $val->user_name_crear?></td>
                <td><?= $val->autorizadoCompra?></td>
                <?php if($val->tipo_entrada == 1){?>
                    <td style= 'width: 20px; height: 20px;'>
                        <a href="<?= Url::toRoute(["entrada-producto-terminado/view", "id" => $val->id_entrada, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>
                <?php }else{?>    
                    <td style= 'width: 20px; height: 20px;'>
                        <a href="<?= Url::toRoute(["entrada-producto-terminado/codigo_barra_ingreso", "id" => $val->id_entrada]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>
                <?php }    
                if(!$detalle && $val->tipo_entrada == 1){?>
                    <td style= 'width: 20px; height: 20px;'>
                       <a href="<?= Url::toRoute(["entrada-producto-terminado/update", "id" => $val->id_entrada, 'sw' => 0]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                    </td>
                <?php }else{
                        if(!$detalle && $val->tipo_entrada == 2){?>
                            <td style= 'width: 20px; height: 20px;'>
                                <a href="<?= Url::toRoute(["entrada-producto-terminado/update", "id" => $val->id_entrada, 'sw' => 1]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                            </td>
                        <?php }else{?>    
                            <td style= 'width: 20px; height: 20px;'>
                                <a href="<?= Url::toRoute(["entrada-producto-terminado/imprimir_entrada_producto", "id" => $val->id_entrada, 'sw' => 1]) ?>" ><span class="glyphicon glyphicon-print"></span></a>                   
                            </td>
                        <?php }    
                }?>  
            </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
            <a align="right" href="<?= Url::toRoute(["entrada-producto-terminado/create", 'sw' => 1]) ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo sin OC</a>
            <a align="right" href="<?= Url::toRoute(["entrada-producto-terminado/create", 'sw' => 0]) ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo con OC</a>
        <?php $form->end() ?>
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
