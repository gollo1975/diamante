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



$this->title = 'ORDEN DE PRODUCCION';
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
    "action" => Url::toRoute("orden-produccion/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$grupo = ArrayHelper::map(GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');
$conProducto = ArrayHelper::map(app\models\Productos::find()->orderBy ('nombre_producto ASC')->all(), 'id_producto', 'nombre_producto');
$almacen = ArrayHelper::map(Almacen::find()->orderBy ('almacen ASC')->all(), 'id_almacen', 'almacen');
$conProcesoProduccion = ArrayHelper::map(TipoProcesoProduccion::find()->orderBy ('nombre_proceso ASC')->all(), 'id_proceso_produccion', 'nombre_proceso');

?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
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
            <?= $formulario->field($form, 'producto')->widget(Select2::classname(), [
                'data' => $conProducto,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("orden-produccion/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                
                <th scope="col" style='background-color:#B9D5CE;'>Número</th>
                <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. entrega</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo orden</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso aprobado">Pa.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso almacenado">Pa.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso cerrado">Cerrado</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th score="col" style='background-color:#B9D5CE;'></th>  
                 <th score="col" style='background-color:#B9D5CE;'></th>  
                         
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
                <tr style ='font-size: 85%;'>                
                    <td><?= $val->numero_orden?></td>
                    <td><?= $val->producto->nombre_producto?></td>
                     <td><?= $val->tipoProceso->nombre_proceso?></td>
                    <td><?= $val->numero_lote?></td>
                    <td><?= $val->fecha_proceso?></td>
                    <td><?= $val->fecha_entrega?></td>
                    <td><?= $val->tipoOrden?></td>
                    <?php if($val->producto_aprobado == 0){?>
                        <td><?= $val->productoAprobado?></td>
                    <?php }else{?>
                        <td style="background-color: #D3F2FE"><?= $val->productoAprobado?></td>
                    <?php } 
                    if($val->producto_almacenado == 0){?>
                        <td><?= $val->productoAlmacenado?></td>
                    <?php }else{?>
                        <td style="background-color: #F1F3E3"><?= $val->productoAlmacenado?></td>
                    <?php }?>    
                    <td><?= $val->cerrarOrden?></td>
                     <td style= 'width: 25px; height: 10px;'>
                        <a href="<?= Url::toRoute(["orden-produccion/view", "id" => $val->id_orden_produccion, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-list" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                    </td>
                    <td style= 'width: 25px; height: 10px;'>
                        <?php if($val->autorizado == 0){?>
                            <a href="<?= Url::toRoute(["orden-produccion/update", "id" => $val->id_orden_produccion, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                       <?php }else{?>
                                <?php if($val->seguir_proceso_ensamble == 1 ){?>
                                    <td style= 'width: 25px; height: 10px;'></td>
                                <?php }else{?>
                                     <td style= 'width: 25px; height: 10px;'>
                                        <?= Html::a('<span class="glyphicon glyphicon-edit"></span>',
                                           ['/orden-produccion/modificar_estado_orden', 'id' => $val->id_orden_produccion],
                                             ['title' => 'Modifica el estado de seguimiento a la orden de produccion',
                                              'data-toggle'=>'modal',
                                              'data-target'=>'#modalmodificarestadoorden',
                                             ])    
                                        ?>
                                        <div class="modal remote fade" id="modalmodificarestadoorden">
                                            <div class="modal-dialog modal-lg" style ="width: 550px;">    
                                                <div class="modal-content"></div>
                                            </div>
                                       </div>
                                     </td>    
                                <?php } ?>
                       <?php }?>  
                    </td>      
                </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
            <a align="right" href="<?= Url::toRoute("orden-produccion/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
        <?php $form->end() ?>   
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
