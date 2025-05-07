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
use app\models\GrupoProducto;

$this->title = 'INVENTARIO PRODUCTO TERMINADO';
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
    "action" => Url::toRoute("inventario-productos/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$grupo = ArrayHelper::map(GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "codigo")->input("search") ?>
             <?= $formulario->field($form, "producto")->input("search") ?>
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
             <?= $formulario->field($form, 'grupo')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'inventario_inicial')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row checkbox checkbox-success" align ="center">
                <?= $formulario->field($form, 'busqueda_vcto')->checkbox(['label' => 'Aplica fecha vencimiento', '1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:10px;', 'id'=>'busqueda_vcto']) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("inventario-productos/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <tr style ='font-size: 85%;'>         
                
                <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Presentacion producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. Vcto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Entradas</th>
                <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Aplica para el presupuesto comercial">A. presupuesto</span></th>
                  <th scope="col" style='background-color:#B9D5CE;'><span title="Venta a publico">V. publico</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th score="col" style='background-color:#B9D5CE;'></th>  
                         
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style ='font-size: 85%;'>           
                <td><?= $val->codigo_producto?></td>
                <td><?= $val->nombre_producto?></td>
                <?php if($val->id_grupo == NULL){?>
                    <td><?= 'NO FOUND'?></td>
                <?php }else{?> 
                    <td><?= $val->grupo->nombre_grupo?></td>
                <?php }?>   
                <td><?= $val->fecha_proceso?></td>
                <td><?= $val->fecha_vencimiento?></td>
                <td style="text-align: right;"><?= ''.number_format($val->unidades_entradas,0)?></td>
                <td style="text-align: right; background-color:#CBDDE3; color: black"><?= ''.number_format($val->stock_unidades,0)?></td>
               
                <?php if($val->aplica_presupuesto == 0){?>
                     <td><?= $val->aplicaPresupuesto?></td>
                <?php }else{?>
                     <td style='background-color:#F0F3EF;'><?= $val->aplicaPresupuesto?></td>
                <?php }?>     
                <td><?= $val->venta_Publico?></td>
                <td style= 'width: 25px; height: 10px;'>
                   <a href="<?= Url::toRoute(["inventario-productos/view", "id" => $val->id_inventario, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                </td>  
                <?php if($val->unidades_entradas == $val->stock_unidades){?>
                    <td style= 'width: 25px; height: 10px;'>
                           <a href="<?= Url::toRoute(["inventario-productos/update", "id" => $val->id_inventario]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                    </td>
                <?php }else{?>
                    <td style= 'width: 25px; height: 10px;'></td>
                <?php }?>    
            </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        
        <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
            <a align="right" href="<?= Url::toRoute("inventario-productos/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
        <?php $form->end() ?>
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
