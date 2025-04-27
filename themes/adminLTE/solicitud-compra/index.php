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
use app\models\TipoSolicitud;
use app\models\AreaEmpresa;



$this->title = 'SOLICITUD DE COMPRA';
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
    "action" => Url::toRoute("solicitud-compra/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$tipo = ArrayHelper::map(TipoSolicitud::find()->orderBy ('descripcion ASC')->all(), 'id_solicitud', 'descripcion');
$area = ArrayHelper::map(AreaEmpresa::find()->orderBy ('descripcion ASC')->all(), 'id_area', 'descripcion');

?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
             <?= $formulario->field($form, 'solicitud')->widget(Select2::classname(), [
                'data' => $tipo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
             <?= $formulario->field($form, 'area')->widget(Select2::classname(), [
                'data' => $area,
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
            <?= $formulario->field($form, "codigo")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("solicitud-compra/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <th scope="col" style='background-color:#B9D5CE;'>Tipo solicitud</th>
                <th scope="col" style='background-color:#B9D5CE;'>Area</th>
                <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th>
                <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total </th>
                <th scope="col" style='background-color:#B9D5CE;'>User name </th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Autorizado">Aut.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Importado a ordenes de compra">Imp.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th score="col" style='background-color:#B9D5CE;'></th>                              
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style ='font-size: 90%;'>                
                <td><?= $val->numero_solicitud?></td>
                <td><?= $val->solicitud->descripcion?></td>
                <td><?= $val->area->descripcion?></td>
                <td><?= $val->documento_soporte?></td>
                <td><?= $val->fecha_entrega?></td>
                <td style="text-align: right;"><?= ''.number_format($val->subtotal,0)?></td>
                <td style="text-align: right"><?= ''.number_format($val->total_impuesto,0)?></td>
                <td style="text-align: right"><?= ''.number_format($val->total,0)?></td>
                <td><?= $val->user_name?></td>
                <td><?= $val->autorizadoCompra?></td>
                <?php if($val->importado == 0){?>
                    <td><?= $val->importarSolicitud?></td>
                <?php } else { ?>    
                    <td style='background-color:#E3DDAA;'><?= $val->importarSolicitud?></td>
                <?php } ?>    
                <td style= 'width: 25px; height: 10px;'>
                    <a href="<?= Url::toRoute(["solicitud-compra/view", "id" => $val->id_solicitud_compra, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
                
                <?php if($val->numero_solicitud == 0){?>
                    <td style= 'width: 25px; height: 10px;'>
                       <a href="<?= Url::toRoute(["solicitud-compra/update", "id" => $val->id_solicitud_compra]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                    </td>
                <?php }else{
                    if($val->importado == 0){?>
                        <td style= 'width: 25px; height: 10px;'>
                            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['orden-compra/create','id_solicitud' => $val->id_solicitud_compra], [
                                           'class' => '',
                                           'title' => 'Proceso que permite crear LA ORDEN DE COMPRA AL PROVEEDOR)', 
                                           'data' => [
                                               'confirm' => 'Esta seguro de crearle la ORDEN DE COMPRA para ser enviada al PROVEEDOR.',
                                               'method' => 'post',
                                           ],
                             ])?>
                        </td> 
                    <?php }else{ ?>
                        <td style= 'width: 25px; height: 10px;'></td>
                    <?php }   
                }?>    
            </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
            <a align="right" href="<?= Url::toRoute("solicitud-compra/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
        <?php $form->end() ?>
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
