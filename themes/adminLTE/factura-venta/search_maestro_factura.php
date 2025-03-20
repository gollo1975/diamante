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
use app\models\AgentesComerciales;

$this->title = 'FACTURA DE VENTA';
$this->params['breadcrumbs'][] = $this->title;
$vendedores = ArrayHelper::map(AgentesComerciales::find()->orderBy('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
$tipoFactura = ArrayHelper::map(\app\models\TipoFacturaVenta::find()->where(['=','ver_registro_factura', 1])->all(), 'id_tipo_factura', 'descripcion');
?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtrocliente");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute(["factura-venta/search_maestro_factura",'token' => $token]),
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
	
    <div class="panel-body" id="filtrocliente" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "numero_factura")->input("search") ?>
            <?= $formulario->field($form, 'vendedor')->widget(Select2::classname(), [
                'data' => $vendedores,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
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
             <?= $formulario->field($form, 'tipo_factura')->widget(Select2::classname(), [
                'data' => $tipoFactura,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
             <?= $formulario->field($form, "documento")->input("search") ?>
            <?= $formulario->field($form, "cliente")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-xs",]) ?>
            <a align="right" href="<?= Url::toRoute(["factura-venta/search_maestro_factura",'token' => $token]) ?>" class="btn btn-primary btn-xs"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
            Registros <span class="badge"><?= $pagination->totalCount ?></span>
        <?php }?>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
           <tr style="font-size: 90%;">    
                <th scope="col" style='background-color:#B9D5CE;'>No factura</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                <th scope="col" style='background-color:#B9D5CE;'>Municipio</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. inicio</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. vcto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total pagar</th>
                <th scope="col" style='background-color:#B9D5CE;'>Saldo</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Estado</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Dias de mora factura">D.M.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th> 
            </tr>
            </thead>
            <tbody>
                <?php 
                if($model){
                    $fecha_dia = date('Y-m-d');
                    foreach ($model as $val): ?>
                        <tr style="font-size: 90%;">
                            <td><?= $val->numero_factura ?></td>
                            <td><?= $val->cliente ?></td>
                            <td><?= $val->clienteFactura->codigoMunicipio->municipio?> - <?= $val->clienteFactura->codigoMunicipio->codigoDepartamento->departamento?></td>
                            <td><?= $val->fecha_inicio ?></td>
                            <td><?= $val->fecha_vencimiento ?></td>
                            <td style="text-align: right"><?= '$'.number_format($val->total_factura,0)?></td>
                            <?php if($val->dias_mora > 0){ ?>  
                                <td style="text-align: right; background-color:#FAE4D7;"><?= '$'.number_format($val->saldo_factura,0)?></td>
                                 <td><?= $val->estadoFactura ?></td>
                                <td style="text-align: right"><?= $val->dias_mora ?></td>
                               
                            <?php }else{ ?>
                                <td style="text-align: right;"><?= '$'.number_format($val->saldo_factura,0)?></td>
                                 <td><?= $val->estadoFactura ?></td>
                                 <td style="text-align: right"><?= $val->dias_mora ?></td>
                            <?php }?>    
                            <td style= 'width: 20px; height: 20px;'>
                                <a href="<?= Url::toRoute(["factura-venta/view_consulta", "id" => $val->id_factura, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver del detalle factura de venta"></span></a>
                            </td>
                            <td style= 'width: 20px; height: 20px;'>
                                <a href="<?= Url::toRoute(["factura-venta/imprimir_factura_venta", "id" => $val->id_factura]) ?>" ><span class="glyphicon glyphicon-print" title="Permite imprimir la factura de venta"></span></a>
                            </td>
                        </tr>
                    <?php endforeach;
                } ?>
            </tbody>        
        </table>
     <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-success btn-xs']); ?>                
                   <?php $form->end() ?>
        </div>
    </div>

</div>
<?php if($model){ ?>
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
<?php }?>


