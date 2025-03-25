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

$this->title = 'NOTA CREDITO - DEVOLUCION';
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
    "action" => Url::toRoute("inventario-productos/cargar_nota_credito"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$motivo = ArrayHelper::map(app\models\MotivoNotaCredito::find()->orderBy ('id_motivo ASC')->all(), 'id_motivo', 'concepto');
$cliente = ArrayHelper::map(app\models\Clientes::find()->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "numero")->input("search") ?>
            <?= $formulario->field($form, "factura")->input("search") ?>
            <?= $formulario->field($form, 'desde')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todaHighlight' => true]])
            ?>
            <?= $formulario->field($form, 'hasta')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
             <?= $formulario->field($form, "documento")->input("search") ?>
             <?= $formulario->field($form, 'cliente')->widget(Select2::classname(), [
                'data' => $cliente,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
     
        </div>
        
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("inventario-productos/cargar_nota_credito") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                
                <th scope="col" style='background-color:#B9D5CE;'>No nota</th>
                 <th scope="col" style='background-color:#B9D5CE;'>No factura</th>
                <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                <th scope="col" style='background-color:#B9D5CE;'>Motivo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha creación</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso cerrado">Cerrado</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                                          
            </tr>
            </thead>
            <tbody>
                <?php
                foreach ($model as $val):?>
                    <tr style ='font-size: 90%;'>                
                        <td><?= $val->numero_nota_credito?></td>
                        <td><?= $val->numero_factura?></td>
                        <td><?= $val->nit_cedula?></td>
                        <td><?= $val->cliente?></td>
                        <?php if($val->id_motivo == ''){?>
                           <td style="background-color: moccasin"><?= 'NO FOUND'?></td>
                        <?php }else{?>
                           <td><?= $val->motivo->concepto?></td>
                        <?php }?>   
                        <td><?= $val->fecha_nota_credito?></td>
                        <td><?= $val->cerrarNota?></td>
                        <td style= 'width: 20px; height: 20px;'>
                            <?= Html::a('<span class="glyphicon glyphicon-list"></span> ', ['crear_devolucion_producto', 'id_nota' => $val->id_nota], [
                                          'class' => '',
                                          'title' => 'Proceso que permite crear devolucion desde nota credito.', 
                                          'data' => [
                                              'confirm' => '¿Esta seguro de crear la devolucion de productos y subirlo al inventario.?',
                                              'method' => 'post',
                                          ],
                            ])?>
                       </td>
                   </tr>            
                <?php endforeach;?>
            </tbody>    
        </table> 
         <?php $form->end() ?>
       
     </div>
</div>

   <?= LinkPager::widget(['pagination' => $pagination]) ?>

