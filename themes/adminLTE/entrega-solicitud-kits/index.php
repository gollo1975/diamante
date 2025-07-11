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

$this->title = 'ENTREGA DE SOLICITUDES';
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
    "action" => Url::toRoute("entrega-solicitud-kits/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$conSolicitud = ArrayHelper::map(app\models\DocumentoSolicitudes::find()->where(['=','logistica', 1])->orderBy ('concepto ASC')->all(), 'id_solicitud', 'concepto');
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
            <a align="right" href="<?= Url::toRoute("entrega-solicitud-kits/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <th scope="col" style='background-color:#B9D5CE;'>No entrega</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo solicitud</th>
                <th scope="col" style='background-color:#B9D5CE;'>No de solicitud</th>
                <th scope="col" style='background-color:#B9D5CE;'>Presentacion</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total kits</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total productos</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total entrega</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Producto ensamblado">P. ensamblado</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso autorizado">Aut.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                                          
            </tr>
            </thead>
            <tbody>
                <?php
                foreach ($model as $val):?>
                        <tr style ='font-size: 85%;'>                
                            <td><?= $val->id_entrega_kits?></td>
                            <td style="text-align: right"><?= $val->numero_entrega?></td>
                            <td><?= $val->solicitud->concepto?></td>
                            <td><?= $val->solicitudArmado->numero_solicitud?></td>
                            <td><?= $val->presentacion->descripcion?></td>
                            <td style="text-align: right"><?= ''. number_format($val->solicitudArmado->cantidad_solicitada,0)?></td>
                            <td style="text-align: right"><?= ''. number_format($val->solicitudArmado->total_unidades,0)?></td>
                            <td style="text-align: right"><?= ''. number_format($val->total_unidades_entregadas,0)?></td>
                            <td><?= $val->fecha_solicitud?></td>
                            <?php if($val->producto_armado == 0){?>
                                <td ><?= $val->productoArmado?></td>
                            <?php }else{?>
                                <td style = "background-color: #ffe8a1" ><?= $val->productoArmado?></td>
                            <?php }?>    
                            <td ><?= $val->autorizadoProceso?></td>
                            <td style= 'width: 20px; height: 20px;'>
                                <a href="<?= Url::toRoute(["entrega-solicitud-kits/view", "id" => $val->id_entrega_kits, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver el detalle de la solicitud"></span></a>
                                 
                            </td> 
                            <?php if(!app\models\EntregaSolicitudKitsDetalle::find()->where(['=','id_entrega_kits', $val->id_entrega_kits])->one()){?>
                                <td style= 'width: 20px; height: 20px;'>
                                    <a href="<?= Url::toRoute(["entrega-solicitud-kits/update", "id" => $val->id_entrega_kits]) ?>" ><span class="glyphicon glyphicon-pencil" title="Permite editar el detalle de la solicitud"></span></a>
                                </td>
                                <td style= 'width: 25px; height: 25px;'>
                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['delete', 'id' => $val->id_entrega_kits], [
                                               'class' => '',
                                               'data' => [
                                                   'confirm' => 'Esta seguro de eliminar el registro?',
                                                   'method' => 'post',
                                               ],
                                           ])
                                    ?>
                                </td>    
                            <?php }else{ ?>
                            <td style= 'width: 20px; height: 20px;'></td>
                            <td style= 'width: 20px; height: 20px;'></td>
                            
                            <?php } ?>
                           
                       </tr>            
                <?php endforeach;?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
            <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar a excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm ']); ?>                
            <td style="width: 25px; height: 25px;">
                <!-- Inicio Nuevo Detalle proceso -->
                <?= Html::a('<span class="glyphicon glyphicon-import"></span> Importar solicitud ',
                      ['/entrega-solicitud-kits/importar_solicitud'],
                      [
                          'title' => 'Importar solicitud de kits',
                          'data-toggle'=>'modal',
                          'data-target'=>'#modalimportarsolicitud',
                          'class' => 'btn btn-success btn-sm',
                          'data-backdrop' => 'static',

                      ]);    
                 ?>
            </td> 
            <div class="modal remote fade" id="modalimportarsolicitud">
                      <div class="modal-dialog modal-lg" style ="width: 700px;">
                          <div class="modal-content"></div>
                      </div>
            </div>
        </div>
    </div>
    <?php $form->end() ?>      
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>

