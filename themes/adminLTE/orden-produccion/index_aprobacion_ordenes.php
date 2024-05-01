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
use app\models\Almacen;
use app\models\TipoProcesoProduccion;



$this->title = 'LISTADO DE ORDENES DE PRODUCCION (Auditoria / Orden de ensamble.)';
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
    "action" => Url::toRoute("orden-produccion/index_ordenes_produccion"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$grupo = ArrayHelper::map(GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');
$almacen = ArrayHelper::map(Almacen::find()->orderBy ('almacen ASC')->all(), 'id_almacen', 'almacen');
$conProcesoProduccion = ArrayHelper::map(TipoProcesoProduccion::find()->orderBy ('nombre_proceso ASC')->all(), 'id_proceso_produccion', 'nombre_proceso');

?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "numero")->input("search") ?>
             <?= $formulario->field($form, 'grupo')->widget(Select2::classname(), [
                'data' => $grupo,
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
             <?= $formulario->field($form, 'almacen')->widget(Select2::classname(), [
                'data' => $almacen,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'tipo_proceso')->widget(Select2::classname(), [
                'data' => $conProcesoProduccion,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, "lote")->input("search") ?>
            <?= $formulario->field($form, 'autorizado')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("orden-produccion/index_ordenes_produccion") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                
                <th scope="col" style='background-color:#B9D5CE;'>Número</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo/Producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Almacen</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. entrega</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo orden</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Paso el proceso de auditoria primera etapa">A. etapa 1</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Paso el proceso de auditoria segunda etapa">A. etapa 2</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style ='font-size: 90%;'>                
                <td><?= $val->numero_orden?></td>
                <td><?= $val->grupo->nombre_grupo?></td>
                <td><?= $val->almacen->almacen?></td>
                <td><?= $val->tipoProceso->nombre_proceso?></td>
                <td><?= $val->numero_lote?></td>
                <td><?= $val->fecha_proceso?></td>
                <td><?= $val->fecha_entrega?></td>
                <td><?= $val->tipoOrden?></td>
                <td><?= $val->seguirProcesoEnsamble?></td>
                <td><?= $val->productoAprobado?></td>
                    <?php if($val->seguir_proceso_ensamble == 0){?>
                        <td style= 'width: 25px; height: 10px;'>
                            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['cargar_concepto_auditoria', 'id' => $val->id_orden_produccion, 'id_grupo' => $val->id_grupo], [
                                           'class' => '',
                                           'title' => 'Proceso que permite cargar los conceptos de auditoria. (Auditoria OP)', 
                                           'data' => [
                                               'confirm' => 'Esta seguro de crear la auditoria a la orden de produccion Nro:  ('.$val->numero_orden.').',
                                               'method' => 'post',
                                           ],
                             ])?>
                        </td> 
                        <th scope="col" style='background-color:#B9D5CE;'></th>
                    <?php }else{ ?>
                         <td style= 'width: 25px; height: 10px;'>
                            <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span> ', ['generar_orden_ensamble', 'id' => $val->id_orden_produccion, 'id_grupo' => $val->id_grupo], [
                                           'class' => '',
                                           'title' => 'Proceso que permite crear la orden de ensamble a la orden de produccion.', 
                                           'data' => [
                                               'confirm' => 'Esta seguro de crear la ORDEN DE ENSAMBLE a la orden de produccion  Nro:  ('.$val->numero_orden.').',
                                               'method' => 'post',
                                           ],
                             ])?>
                         </td>
                         <td style= 'width: 25px; height: 10px;'>
                            <?= Html::a('<span class="glyphicon glyphicon-eye-close"></span> ', ['cerrar_orden_produccion', 'id' => $val->id_orden_produccion], [
                                               'class' => '',
                                               'title' => 'Proceso que permite CERRAR la orden de produccion.', 
                                               'data' => [
                                                   'confirm' => 'Esta seguro de CERRAR la ORDEN DE PRODUCCION Nro:  ('.$val->numero_orden.'). Despues de cerrada NO se puede generar Ordenes de ensamble.',
                                                   'method' => 'post',
                                               ],
                             ])?>
                         </td>     
                        <?php }?>
                </td>
               
            </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
       
     </div>
    <?php $form->end() ?>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
