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
use app\models\TipoOrdenCompra;
use app\models\Proveedor;



$this->title = 'COMPRAS AUDITADAS';
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
    "action" => Url::toRoute("auditoria-compras/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$tipo = ArrayHelper::map(TipoOrdenCompra::find()->orderBy ('descripcion_orden ASC')->all(), 'id_tipo_orden', 'descripcion_orden');
$proveedor = ArrayHelper::map(Proveedor::find()->orderBy ('nombre_completo ASC')->all(), 'id_proveedor', 'nombre_completo');

?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
             <?= $formulario->field($form, 'tipo')->widget(Select2::classname(), [
                'data' => $tipo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
             <?= $formulario->field($form, 'proveedor')->widget(Select2::classname(), [
                'data' => $proveedor,
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
            <?= $formulario->field($form, "numero")->input("search") ?>
        </div>
         <div class="row checkbox checkbox-success" align ="center">
                <?= $formulario->field($form, 'tipo_busqueda')->checkbox(['label' => 'Detalle solo por fechas', '1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:10px;', 'id'=>'tipo_busqueda']) ?>
            </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("auditoria-compras/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                
                <th scope="col" style='background-color:#B9D5CE;'>Número compra</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo compra</th>
                <th scope="col" style='background-color:#B9D5CE;'>Proveedor</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nro factura</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. solicitud compra</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. auditoria</th>
                <th scope="col" style='background-color:#B9D5CE;'>User name </th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Autorizado">Cerrado.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
            </tr>
            </thead>
            <tbody>
            <?php 
            
            foreach ($model as $val): ?>
            <tr style ='font-size: 90%;'>                
                <td><?= $val->numero_orden?></td>
                <td><?= $val->tipoOrden->descripcion_orden?></td>
                <td><?= $val->proveedor->nombre_completo?></td>
                <td><?= $val->numero_factura?></td>
                <td><?= $val->fecha_proceso_compra?></td>
                <td><?= $val->fecha_auditoria?></td>
                <td><?= $val->user_name?></td>
                <?php if($val->cerrar_auditoria == 0){?>
                    <td><?= $val->cerrarAuditoria?></td>
                <?php }else{?>    
                    <td style='background-color:#D8E1C2;'><?= $val->cerrarAuditoria?></td>
                <?php }?>    
                <td style= 'width: 25px; height: 10px;'>
                    <a href="<?= Url::toRoute(["auditoria-compras/view", "id" => $val->id_auditoria, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
            </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >  
            <div class="btn-group btn-sm" role="group">    
                   <button type="button" class="btn btn-success  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Exportar excel
                       <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li> <?= Html::submitButton("<span class='glyphicon glyphicon-check'></span> Excel", ['name' => 'excel']); ?> </li>
                        <?php 
                        if($tipo_busqueda == 1 && $fecha_inicio <> '' && $fecha_corte <> ''){?>
                            <li><?= Html::a('<span class="glyphicon glyphicon-export"></span> Detalle', ['exceldetalleauditoria', 'fecha_inicio' => $fecha_inicio, 'fecha_corte' => $fecha_corte]) ?></li>
                        <?php }?> 
                    </ul>
            </div> 
             <?php $form->end() ?>
        </div>  
    
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
