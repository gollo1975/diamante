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

$this->title = 'SOLICITUD DE KITS';
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
    "action" => Url::toRoute("solicitud-armado-kits/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$conSolicitud = ArrayHelper::map(app\models\DocumentoSolicitudes::find()->where(['=','produccion', 1])->orderBy ('concepto ASC')->all(), 'id_solicitud', 'concepto');
$conPresentacion = ArrayHelper::map(app\models\PresentacionProducto::find()->where(['=','tipo_venta', 1])->orderBy ('descripcion ASC')->all(), 'id_presentacion', 'descripcion');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
           
             <?= $formulario->field($form, 'solicitud')->widget(Select2::classname(), [
                'data' => $conSolicitud,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'presentacion')->widget(Select2::classname(), [
                'data' => $conPresentacion,
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
                    'todaHighlight' => true]])
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
            <a align="right" href="<?= Url::toRoute("solicitud-armado-kits/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
        Registros <span class="badge"><?= count($model) ?></span>

    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style ='font-size: 85%;'>         
                
                <th scope="col" style='background-color:#B9D5CE;'>Id</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Tipo solicitud</th>
                <th scope="col" style='background-color:#B9D5CE;'>Presentacion</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total unidades</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>No solicitud</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso autorizado">Aut.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                                          
            </tr>
            </thead>
            <tbody>
                <?php
                foreach ($model as $val):?>
                        <tr style ='font-size: 85%;'>                
                            <td><?= $val->id_solicitud_armado?></td>
                            <td><?= $val->solicitud->concepto?></td>
                            <td><?= $val->presentacion->descripcion?></td>
                            <td style="text-align: right"><?= ''. number_format($val->total_unidades,0)?></td>
                            <td><?= $val->fecha_solicitud?></td>
                            <td style="text-align: right"><?= $val->numero_solicitud?></td>
                            <td ><?= $val->autorizadoProceso?></td>
                            <td style= 'width: 20px; height: 20px;'>
                                <a href="<?= Url::toRoute(["solicitud-armado-kits/view", "id" => $val->id_solicitud_armado, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver el detalle de la solicitud"></span></a>
                            </td> 
                            <?php if(!\app\models\SolicitudArmadoKitsDetalle::find()->where(['=','id_solicitud_armado', $val->id_solicitud_armado])->one()){?>
                                <td style= 'width: 20px; height: 20px;'>
                                    <a href="<?= Url::toRoute(["solicitud-armado-kits/update", "id" => $val->id_solicitud_armado]) ?>" ><span class="glyphicon glyphicon-pencil" title="Permite editar el detalle de la solicitud"></span></a>
                                </td>
                            <?php }else{?><td style= 'width: 20px; height: 20px;'></td>
                                
                            <?php }?>    
                       </tr>            
                <?php endforeach;?>
            </tbody>    
        </table> 
    </div>
    <div class="panel-footer text-right" >            
            <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar a excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm ']); ?>                
            <a align="right" href="<?= Url::toRoute("solicitud-armado-kits/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
    </div>
    <?php $form->end() ?>      
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>

