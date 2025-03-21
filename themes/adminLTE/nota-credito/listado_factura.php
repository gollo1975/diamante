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

$this->title = 'FACTURA ELECTRONICA';
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
    "action" => Url::toRoute("nota-credito/listado_factura"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$tipoFactura = ArrayHelper::map(app\models\TipoFacturaVenta::find()->where(['=','ver_registro_factura', 1])->all(), 'id_tipo_factura', 'descripcion');
$vendedor = ArrayHelper::map(app\models\AgentesComerciales::find()->where(['=','estado', 0])->orderBy ('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
$cliente = ArrayHelper::map(app\models\Clientes::find()->where(['=','estado_cliente', 0])
                                                 ->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, 'tipo_factura')->widget(Select2::classname(), [
                'data' => $tipoFactura,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, "numero_factura")->input("search") ?>
            <?= $formulario->field($form, 'fecha_inicio')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todaHighlight' => true]])
            ?>
            <?= $formulario->field($form, 'fecha_corte')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
             <?= $formulario->field($form, 'cliente')->widget(Select2::classname(), [
                'data' => $cliente,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
             <?= $formulario->field($form, 'vendedor')->widget(Select2::classname(), [
                'data' => $vendedor,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
     
        </div>
        
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("nota-credito/listado_factura") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <tr style ='font-size: 85%;'>         
                
                <th scope="col" style='background-color:#B9D5CE;'>No factura</th>
                <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                <th scope="col" style='background-color:#B9D5CE;'>Vendedor</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. factura</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. vencimiento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th>
                <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total pagar</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Estado factura">Est.</span></th>
                 <th scope="col" style='background-color:#B9D5CE;'><span title="Tipo de factura">TF.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                                          
            </tr>
            </thead>
            <tbody>
            <?php
            if($model){
                foreach ($model as $val):?>
                    <tr style ='font-size: 85%;'>                
                        <td><?= $val->numero_factura?></td>
                        <td><?= $val->nit_cedula?></td>
                        <td><?= $val->clienteFactura->nombre_completo?></td>
                        <td><?= $val->agenteFactura->nombre_completo?></td>
                        <td><?= $val->fecha_inicio?></td>
                        <td><?= $val->fecha_vencimiento?></td>
                        <td style="text-align: right"><?= ''.number_format($val->subtotal_factura,0)?></td>
                        <td style="text-align: right"><?= ''.number_format($val->impuesto,0)?></td>
                        <td style="text-align: right"><?= ''.number_format($val->total_factura,0)?></td>
                        <td><?= $val->estadoFactura?></td>
                        <td><?= $val->tipoFactura->abreviatura?></td>
                        <td style= 'width: 25px; height: 25px;'>
                             <?= Html::a('<span class="glyphicon glyphicon-book"></span>', ['crear_nota_credito', 'id_factura' => $val->id_factura], [
                            'class' => '',
                            'title' => 'Permite crear la NOTA CREDITO a la factura de venta.',
                            'data' => [
                                'confirm' => '¿Esta seguro que se desea crear la NOTA CREDITO  a la factura de venta No '.$val->numero_factura.' ?',
                                'method' => 'post',
                            ],
                            ])?>
                        </td>
                   </tr>            
                <?php endforeach;
            }?>
            </tbody>    
        </table> 
        <?php $form->end() ?>
    </div>
</div>
 <?php if($model){?>
     <?= LinkPager::widget(['pagination' => $pagination]) ?>
 <?php }?>
