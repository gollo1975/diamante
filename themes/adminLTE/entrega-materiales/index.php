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

use app\models\SolicitudMateriales;

$this->title = 'ENTREGA DE MATERIALES';
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
    "action" => Url::toRoute("entrega-materiales/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$solicitud = \app\models\EntregaMateriales::find()->all();
$solicitud = ArrayHelper::map($solicitud, 'codigo', 'entregaSolicitud');


?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "numero_entrega")->input("search") ?>
            <?= $formulario->field($form, 'numero_solicitud')->widget(Select2::classname(), [
                'data' => $solicitud,
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
                        
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("entrega-materiales/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                
                <th scope="col" style='background-color:#B9D5CE;'>Id</th>
                <th scope="col" style='background-color:#B9D5CE;'>Numero de entrega</th>
                <th scope="col" style='background-color:#B9D5CE;'>Numero de solicitud</th>
                <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. despacho</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Solicitud cerrada">Cerrado</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Solicitud autorizado">Aut.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Descargar material de empaque">Descargar ME</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
             
                         
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
                <tr style ='font-size: 85%;'>                
                    <td><?= $val->id_entrega?></td>
                    <td><?= $val->numero_entrega?></td>
                    <td><?= $val->solicitud->numero_solicitud?></td>
                    <?php if($val->solicitud->id_orden_produccion !== null){?>
                        <td><?= $val->solicitud->ordenProduccion->producto->nombre_producto?></td>
                         <td><?= $val->solicitud->ordenProduccion->numero_lote?></td>
                    <?php  }else{?>
                        <td><?= $val->solicitud->productos->nombre_producto?></td>
                        <td><?= 'NOT FOUND'?></td>
                    <?php  }?>    
                   
                    <td><?= $val->fecha_despacho?></td>
                    <td><?= $val->fecha_hora_registro?></td>
                    <?php if($val->cerrar_solicitud == 0){?>
                        <td  style="background-color: #BBD3E0"><?= $val->cerrarSolicitud?></td>
                    <?php }else{?>
                        <td style="background-color: #EFFBDC"><?= $val->cerrarSolicitud?></td>
                    <?php }?>    
                    <?php if($val->autorizado == 0){?>
                        <td  style="background-color: #9FE6F3"><?= $val->autorizadosolicitud?></td>
                    <?php }else{?>
                        <td style="background-color: #F9ECCD"><?= $val->autorizadosolicitud?></td>
                    <?php }
                    if($val->descargar_material_empaque == 0){ ?> 
                        <td><?= $val->aplicoMaterialEmpaque?></td>
                    <?php }else{?>
                        <td style="background-color: #FFFFE3"><?= $val->aplicoMaterialEmpaque?></td>
                    <?php }?>    
                    <td style= 'width: 25px; height: 10px;'>
                        <a href="<?= Url::toRoute(["entrega-materiales/view", "id" => $val->id_entrega,'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear la nueva solicitud de material de empaque"></span></a>
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