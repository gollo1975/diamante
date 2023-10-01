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

$this->title = 'REGLA COMERCIAL (Inventario de productos)';
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
    "action" => Url::toRoute("inventario-productos/regla_comercial"),
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
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("inventario-productos/regla_comercial") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                
                <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Unidades</th>
                <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                <th scope="col" style='background-color:#B9D5CE;'>Limite venta</th>
                <th scope="col" style='background-color:#B9D5CE;'>Limite entrega </th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha cierre </th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                         
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): 
                $regla = \app\models\ProductoReglaComercial::find()->where(['=','id_inventario', $val->id_inventario])->andWhere(['=','estado_regla', 0])->one();
                ?>
            <tr style ='font-size: 90%;'>                
                <td><?= $val->codigo_producto?></td>
                <td><?= $val->nombre_producto?></td>
                <td><?= $val->grupo->nombre_grupo?></td>
                <td style="text-align: right;"><?= ''.number_format($val->unidades_entradas,0)?></td>
                <?php if($val->stock_unidades > 0){?>
                    <td style="text-align: right; background-color:#F5EEF8; color: black"><?= ''.number_format($val->stock_unidades,0)?></td>
                <?php }else{?>
                    <td style="text-align: right"><?= ''.number_format($val->stock_unidades,0)?></td>
                <?php }
                if($regla){?>
                    <td style="text-align: right"><?= $regla->limite_venta?></td>
                    <td style="text-align: right"><?= $regla->limite_presupuesto?></td>
                   <td><?= $regla->fecha_cierre ?></td>
                <?php }else{?>
                   <td><?= 'NO FOUND' ?></td>
                   <td><?= 'NO FOUNT' ?></td>
                   <td><?= 'NO FOUNT' ?></td>
                <?php }?> 
                   
                <td style= 'width: 25px; height: 20px;'>
                   <a href="<?= Url::toRoute(["inventario-productos/view_regla", "id" => $val->id_inventario]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                </td>  
            </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
        <?php $form->end() ?>
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
