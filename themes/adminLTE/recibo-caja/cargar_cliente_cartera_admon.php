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
use app\models\AgentesComerciales;

$this->title = 'LISTADO DE CARTERA';
$this->params['breadcrumbs'][] = $this->title;

$vendedores = ArrayHelper::map(AgentesComerciales::find()->orderBy('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtrocliente");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("recibo-caja/cargar_cartera_admon"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrocliente" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "documento")->input("search") ?>
            <?= $formulario->field($form, "cliente")->input("search") ?>
            <?= $formulario->field($form, 'desde')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
            <?= $formulario->field($form, 'hasta')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
             <?= $formulario->field($form, 'vendedores')->widget(Select2::classname(), [
                'data' => $vendedores,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("recibo-caja/cargar_cartera_admon") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>

<?php $formulario->end() ?>

<div class="table-responsive">
<div class="panel panel-success ">
    <div class="panel-heading">
        Registros <span class="badge"><?= $pagination->totalCount ?></span>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
           <tr style="font-size: 95%;">    
                <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                <th scope="col" style='background-color:#B9D5CE;'>Saldo</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>  
            </tr>
            </thead>
            <tbody>
                <?php 
                $auxiliar = 0;
                foreach ($model as $val):
                    if($auxiliar <> $val->id_cliente){
                        $suma = 0;
                        $factura = \app\models\FacturaVenta::find()->where(['=','id_cliente', $val->id_cliente])->all();
                        foreach ($factura as $facturas):
                            $suma += $facturas->saldo_factura;
                        endforeach;
                        ?>
                        <tr style="font-size: 95%;">                   
                            <td><?= $val->nit_cedula ?></td>
                            <td><?= $val->cliente ?></td>
                            <td style="text-align: right"><?= '$'.number_format($suma,0)?></td>
                            <td style= 'width: 25px; right: 25px;'>
                               <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                    ['/recibo-caja/crear_nuevo_recibo','id_cliente' =>$val->id_cliente, 'tokenAcceso' => $tokenAcceso],
                                      ['title' => 'Crear recibo de caja al clinte',
                                       'data-toggle'=>'modal',
                                       'data-target'=>'#modalcrearrecibocaja',
                                      ])    
                                    ?>
                                    <div class="modal remote fade" id="modalcrearrecibocaja">
                                        <div class="modal-dialog modal-lg" style ="width: 435px;">    
                                             <div class="modal-content"></div>
                                        </div>
                                    </div>   
                            </td>     
                        </tr>
                        <?php $auxiliar = $val->id_cliente; 
                    }else{
                        $auxiliar = $val->id_cliente;
                    }    
                endforeach;?>
            </tbody>        
        </table>
    </div>

</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>


