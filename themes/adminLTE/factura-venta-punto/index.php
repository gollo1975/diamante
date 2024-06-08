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
$punto = app\models\PuntoVenta::findOne($accesoToken);

$this->title = 'FACTURA DE VENTA ('.$punto->nombre_punto.')';
$this->params['breadcrumbs'][] = $this->title;

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
    "action" => Url::toRoute("factura-venta-punto/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$convendedor = ArrayHelper::map(app\models\AgentesComerciales::find()->where(['=','estado', 0])->orderBy ('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
$Concliente = ArrayHelper::map(app\models\Clientes::find()->where(['=','estado_cliente', 0])->andWhere(['=','id_tipo_cliente', 5])
                                                ->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
$conPunto = ArrayHelper::map(app\models\PuntoVenta::find()->all(), 'id_punto', 'nombre_punto');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "documento")->input("search") ?>
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
                'data' => $Concliente,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
             <?= $formulario->field($form, 'vendedor')->widget(Select2::classname(), [
                'data' => $convendedor,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?php if($accesoToken == 1){?>
                <?= $formulario->field($form, 'punto_venta')->widget(Select2::classname(), [
                   'data' => $conPunto,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]); ?>
            <?php }?>
            <?= $formulario->field($form, 'saldo')->dropDownList(['0' => 'SI'],['prompt' => 'Seleccione una opcion ...']) ?>         
        </div>
        
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("factura-venta-punto/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                    <th scope="col" style='background-color:#B9D5CE;'>No factura</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Punto de venta</th>
                    <th scope="col" style='background-color:#B9D5CE;'>F. factura</th>
                    <th scope="col" style='background-color:#B9D5CE;'>F. vencimiento</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Total pagar</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Saldo</th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                </tr>
            </thead>
            <tbody>
            <?php
            $fecha_dia = date('Y-m-d');
                foreach ($model as $val):
                    $dato = \app\models\FacturaVentaPuntoDetalle::find()->where(['=','id_factura', $val->id_factura])->all();
                    ?>
                    <tr style ='font-size: 90%;'>                
                        <td><?= $val->numero_factura?></td>
                        <td><?= $val->nit_cedula?></td>
                        <td><?= $val->clienteFactura->nombre_completo?></td>
                        <td><?= $val->puntoVenta->nombre_punto?></td>
                        <td><?= $val->fecha_inicio?></td>
                        <td><?= $val->fecha_vencimiento?></td>
                        <td style="text-align: right"><?= ''.number_format($val->subtotal_factura,0)?></td>
                        <td style="text-align: right"><?= ''.number_format($val->impuesto,0)?></td>
                        <td style="text-align: right"><?= ''.number_format($val->total_factura,0)?></td>
                        <td style="text-align: right;"><?= ''.number_format($val->saldo_factura,0)?></td>
                        <?php if($val->id_tipo_factura == 4){
                            if(!$dato){?>    
                                <td style= 'width: 20px; height: 20px;'>    
                                    <a href="<?= Url::toRoute(["factura-venta-punto/view", "id_factura_punto" => $val->id_factura, 'accesoToken' => $accesoToken]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver la vista de la factura y el detalle"></span></a>
                                </td>
                                <td style= 'width: 20px; height: 20px;'>
                                    <a href="<?= Url::toRoute(["factura-venta-punto/update", "id_factura_punto" => $val->id_factura,'accesoToken' => $accesoToken]) ?>" ><span class="glyphicon glyphicon-pencil" title="Permite editar la factura de venta"></span></a>
                                </td>    
                            <?php }else{?>
                                 <td style= 'width: 20px; height: 20px;'>    
                                    <a href="<?= Url::toRoute(["factura-venta-punto/view", "id_factura_punto" => $val->id_factura, 'accesoToken' => $accesoToken]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver la vista de la factura y el detalle"></span></a>
                                </td>
                                <td style= 'width: 20px; height: 20px;'></td>
                            <?php }
                        }?>
                   </tr>            
                <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
           <div class="btn-group btn-sm" role="group">    
                   <button type="button" class="btn btn-success  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Exportar a excel
                       <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li> <?= Html::submitButton("<span class='glyphicon glyphicon-check'></span> Excel", ['name' => 'excel']); ?> </li>
                        <?php 
                        if($fecha_inicio <> null && $fecha_corte <> null ||  $punto_venta <> null || $documento <> null || $cliente <> null || $vendedor <> null || $documento <> null || $numero_factura <> null){?>
                            <li><?= Html::a('<span class="glyphicon glyphicon-export"></span> Detalle', ['excel_detalle_factura', 'fecha_inicio' => $fecha_inicio, 'fecha_corte' => $fecha_corte, 'cliente' =>$cliente, 'punto_venta' => $punto_venta,'numero_factura' => $numero_factura,'documento' => $documento, 'vendedor'=> $vendedor]) ?></li>
                        <?php }?> 
                    </ul>
            </div> 
           <a href="<?= Url::toRoute(["factura-venta-punto/create" , 'accesoToken' => $accesoToken]) ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span>Nueva factura</a>  
          
        </div>
     </div>
</div>
<?php $form->end() ?>
 <?php if($model){?>
     <?= LinkPager::widget(['pagination' => $pagination]) ?>
 <?php }?>
