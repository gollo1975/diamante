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
$this->title = 'LISTAR PEDIDOS';
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
    "action" => Url::toRoute("almacenamiento-producto/listar_pedidos"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$cliente = ArrayHelper::map(\app\models\Clientes::find()->where(['=','estado_cliente', 0])
                                                ->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
$vendedor = ArrayHelper::map(\app\models\AgentesComerciales::find()->where(['=','estado', 0])->orderBy ('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "numero_pedido")->input("search") ?>
            <?= $formulario->field($form, "documento")->input("search") ?>
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
                ]);
            ?> 
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("almacenamiento-producto/listar_pedidos") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                    <th scope="col" style='background-color:#B9D5CE;'>No pedido</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Departamento</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Municipio</th>
                    <th scope="col" style='background-color:#B9D5CE;'>F. pedido</th>
                    <th scope="col" style='background-color:#B9D5CE;'>F. entrega</th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Pedido virtual">P.V.</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Vr. Presup.</th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Pedido validado">P. val.</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Aplica presupuesto al cliente">A.p.</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                    <th scope="col" style='background-color:#B9D5CE;'></th> 
                    <th scope="col" style='background-color:#B9D5CE;'></th> 
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($model as $val):?>
                    <tr style ='font-size: 90%;'>                
                        <td><?= $val->numero_pedido ?></td>
                        <td><?= $val->cliente ?></td>
                        <td><?= $val->clientePedido->codigoDepartamento->departamento ?></td>
                        <td><?= $val->clientePedido->codigoMunicipio->municipio ?></td>
                        <td><?= $val->fecha_proceso ?></td>
                        <td><?= $val->fecha_entrega ?></td>
                        <?php if($val->pedido_virtual == 0){?>
                            <td><?= $val->pedidoVirtual ?></td>
                        <?php }else{?>
                            <td style="background-color: #e7c6ff"><?= $val->pedidoVirtual ?></td>
                        <?php }?>    
                        <td style="text-align: right"><?= ''.number_format($val->gran_total,0) ?></td>
                        <td style="text-align: right"><?= ''.number_format($val->valor_presupuesto,0) ?></td>
                        <?php if($val->pedido_validado == 0){ ?>
                        <td style='background-color:#B9D5CE;'><?= $val->pedidoValidado ?></td>
                        <?php }else{?>
                            <td><?= $val->pedidoValidado ?></td>
                        <?php }
                        if($val->valor_presupuesto == 0){?>
                           <td><?= $val->presupuestoPedido ?></td>
                           <td style= 'width: 25px; height: 25px;'>
                                <a href="<?= Url::toRoute(["pedidos/imprimir_pedido", "id" => $val->id_pedido]) ?>" ><span class="glyphicon glyphicon-print"></span></a>
                           </td>
                           <td style= 'width: 25px; height: 25px;'></td>
                        <?php }else{?>
                            <td style='background-color:#8FA5D5; color: black'><?= $val->presupuestoPedido ?></td>
                            <td style= 'width: 25px; height: 25px;'>
                                <a href="<?= Url::toRoute(["pedidos/imprimir_pedido", "id" => $val->id_pedido]) ?>" ><span class="glyphicon glyphicon-print"></span></a>
                            </td>
                            <td style= 'width: 25px; height: 25px;'>
                                <a href="<?= Url::toRoute(["pedidos/imprimir_presupuesto", "id" => $val->id_pedido]) ?>" ><span class="glyphicon glyphicon-print"></span></a>
                            </td>
                        <?php }?>   
                       
                        <td style= 'width: 25px; height: 25px;'>
                            <a href="<?= Url::toRoute(["almacenamiento-producto/view_listar", "id_pedido" => $val->id_pedido]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                    </tr>            
                <?php endforeach; ?>
            </tbody>    
    </table> 
        <div class="panel-footer text-right" >
           <?php if($model){?>
               <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
           <?php }
           $form->end() ?>
        </div>
    </div>    
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
