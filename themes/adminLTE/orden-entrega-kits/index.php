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

$this->title = 'ORDEN DE ENSAMBLE DE KITS';
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
    "action" => Url::toRoute("orden-entrega-kits/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$conSolicitud = ArrayHelper::map(app\models\EntregaSolicitudKits::find()->orderBy ('id_entrega_kits DESC')->all(), 'id_entrega_kits', 'entregaKits');
$conPresentacion = ArrayHelper::map(app\models\PresentacionProducto::find()->where(['=','tipo_venta', 1])->orderBy ('descripcion ASC')->all(), 'id_presentacion', 'descripcion');
$producto = ArrayHelper::map(app\models\InventarioProductos::find()->where(['=','tipo_producto', 1])->orderBy ('nombre_producto ASC')->all(), 'id_inventario', 'nombre_producto');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
           
             <?= $formulario->field($form, 'nombre_kits')->widget(Select2::classname(), [
                'data' => $conPresentacion,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'presentacion')->widget(Select2::classname(), [
                'data' => $conSolicitud,
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
              <?= $formulario->field($form, 'ordenkits')->widget(Select2::classname(), [
                'data' => $producto,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
     
        </div>
        
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("orden-entrega-kits/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                 <th scope="col" style='background-color:#B9D5CE;'>Numero orden</th>
                <th scope="col" style='background-color:#B9D5CE;'>Numero de solicitud</th>
                <th scope="col" style='background-color:#B9D5CE;'>Presentacion</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total kits</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total productos</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. hora registro</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso autorizado">Autorizado</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso autorizado">Cerrado</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                
                                          
            </tr>
            </thead>
            <tbody>
                <?php
                foreach ($model as $val):?>
                    <tr style ='font-size: 85%;'>                
                        <td><?= $val->id_orden_entrega?></td>
                         <td><?= $val->numero_orden?></td>
                        <td><?= $val->entregaKits->numero_entrega?></td>
                        <td><?= $val->presentacion->descripcion?></td>
                        <td style="text-align: right"><?= ''. number_format($val->total_kits,0)?></td>
                        <td style="text-align: right"><?= ''. number_format($val->total_productos_procesados,0)?></td>
                        <td><?= $val->fecha_orden?></td>
                        <td><?= $val->fecha_hora_registro?></td>
                        <td ><?= $val->autorizadoProceso?></td>
                         <td ><?= $val->procesoCerrado?></td>
                        <td style= 'width: 20px; height: 20px;'>
                            <a href="<?= Url::toRoute(["orden-entrega-kits/view", "id" => $val->id_orden_entrega, 'token' =>$token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver el detalle de la solicitud"></span></a>
                        </td> 
                    </tr>            
                <?php endforeach;?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
            <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar a excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm ']); ?>                
            <td style="width: 25px; height: 25px;">
                <!-- Inicio Nuevo Detalle proceso -->
                <?= Html::a('<span class="glyphicon glyphicon-import"></span> Importar entrega kits ',
                      ['/orden-entrega-kits/importar_entrega_kits'],
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

