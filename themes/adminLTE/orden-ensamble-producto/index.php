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
use app\models\OrdenProduccion;



$this->title = 'ORDENES DE ENSAMBLE';
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
    "action" => Url::toRoute("orden-ensamble-producto/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$grupo = ArrayHelper::map(GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');
$ordenProduccion = \app\models\OrdenEnsambleProducto::find()->all();
$ordenProduccion = ArrayHelper::map($ordenProduccion, 'id_orden_produccion', 'OrdenEnsambleConsulta');


?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "numero_ensamble")->input("search") ?>
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
             <?= $formulario->field($form, 'orden')->widget(Select2::classname(), [
                'data' => $ordenProduccion,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
             <?= $formulario->field($form, "numero_lote")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("orden-ensamble-producto/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                
                <th scope="col" style='background-color:#B9D5CE;'>Orden ensamble</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Orden produccion</th>
                <th scope="col" style='background-color:#B9D5CE;'>Etapa</th>
                <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>Responsable</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Orden de ensamble cerrado">Cerrado</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
             
                         
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
                <tr style ='font-size: 90%;'>                
                    <td><?= $val->numero_orden_ensamble?></td>
                    <td><?= $val->grupo->nombre_grupo?></td>
                    <td><?= $val->ordenProduccion->numero_orden?></td>
                     <td><?= $val->etapa->concepto?></td>
                    <td><?= $val->numero_lote?></td>
                    <td><?= $val->fecha_proceso?></td>
                    <td><?= $val->responsable?></td>
                    <td><?= $val->cerrarOrdenEnsamble?></td>
                     <td style= 'width: 25px; height: 10px;'>
                        <a href="<?= Url::toRoute(["orden-ensamble-producto/view", "id" => $val->id_ensamble,'token' => $token,'sw' =>0]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                    </td>
                    <?php if($val->cerrar_orden_ensamble == 1){
                        $auditoria = \app\models\OrdenEnsambleAuditoria::find()->where(['=','id_ensamble', $val->id_ensamble])->one();
                        if($auditoria){?>
                            <td style= 'width: 25px; height: 10px;'></td>
                        <?php }else{?> 
                            <td style= 'width: 25px; height: 10px;'>
                                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['orden-ensamble-producto/segunda_auditoria', 'id' => $val->id_ensamble, 'id_grupo' => $val->id_grupo], [
                                              'class' => '',
                                              'title' => 'Proceso que permite cargar los conceptos de la segunda auditoria.', 
                                              'data' => [
                                                  'confirm' => 'Esta seguro de crear la auditoria a la orden de ensamble Nro:  ('.$val->numero_orden_ensamble.').',
                                                  'method' => 'post',
                                              ],
                                ]);?>
                            </td> 
                        <?php }    
                    }else { ?>
                        <td style= 'width: 25px; height: 10px;'></td>
                    <?php } ?>
                    
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
