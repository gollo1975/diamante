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
use app\models\TipoCliente;


$this->title = 'CLIENTES';
$this->params['breadcrumbs'][] = $this->title;


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
    "action" => Url::toRoute("clientes/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$vendedor = ArrayHelper::map(AgentesComerciales::find()->where(['=','estado', 0])->orderBy('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
$tipoCliente = ArrayHelper::map(TipoCliente::find()->orderBy('concepto ASC')->all(), 'id_tipo_cliente', 'concepto');
$tipoZona = ArrayHelper::map(\app\models\ZonaClientes::find()->all(), 'id_zona', 'nombre_zona');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrocliente" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "nitcedula")->input("search") ?>
            <?= $formulario->field($form, "nombre_completo")->input("search") ?>
            <?= $formulario->field($form, 'vendedor')->widget(Select2::classname(), [
                'data' => $vendedor,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
             <?= $formulario->field($form, 'tipo_cliente')->widget(Select2::classname(), [
                'data' => $tipoCliente,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
             <?= $formulario->field($form, 'zona')->widget(Select2::classname(), [
                'data' => $tipoZona,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'activo')->dropdownList(['0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("clientes/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>

<?php $formulario->end() ?>

<div class="table-responsive">
<div class="panel panel-success ">
    <div class="panel-heading">
        <?php if($model){?> 
            Registros <span class="badge"><?= $pagination->totalCount ?></span>
        <?php } ?>     
    </div>
        <table class="table table-bordered table-hover">
            <thead>
           <tr style="font-size: 85%;">    
                <th scope="col" style='background-color:#B9D5CE;'>Tipo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cedula/Nit</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                <th scope="col" style='background-color:#B9D5CE;'>Dirección</th>
                <th scope="col" style='background-color:#B9D5CE;'>Teléfono</th>
                <th scope="col" style='background-color:#B9D5CE;'>Celular</th>
                <th scope="col" style='background-color:#B9D5CE;'>Departamento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Municipio</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Tipo de cliente">T.C.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Estado actual del cliente">Act.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>  
                <th scope="col" style='background-color:#B9D5CE;'></th> 
                <th scope="col" style='background-color:#B9D5CE;'></th>  
            </tr>
            </thead>
            <tbody>
            <?php
            if($model){ 
                foreach ($model as $val): ?>
            <tr style="font-size: 85%;">                   
                 <td><?= $val->tipoDocumento->tipo_documento ?></td>
                <td><?= $val->nit_cedula ?></td>
                <td><?= $val->nombre_completo ?></td>
                <td><?= $val->direccion ?></td>
                <td><?= $val->telefono ?></td>
                <td><?= $val->celular ?></td>
                <td><?= $val->codigoDepartamento->departamento ?></td>
                <td><?= $val->codigoMunicipio->municipio ?></td>
                 <td><?= $val->tipoCliente->abreviatura ?></td>
                <?php if($val->estado_cliente == 0){?>
                    <td style='background-color:#F7EDEA;'><?= $val->estadoCliente ?></td>
                <?php }else{?>
                    <td style='background-color:#F6C8BE;'><?= $val->estadoCliente ?></td>
                <?php }?>
                <td style= 'width: 25px; height: 20px;'>
                    <?= Html::a('<span class="glyphicon glyphicon-list-alt"></span>',
                        ['/clientes/parametro_cliente', 'id' => $val->id_cliente, 'token' => $token],
                          ['title' => 'Parametros del cliente',
                           'data-toggle'=>'modal',
                           'data-target'=>'#modalparametrocliente',
                          ])    
                    ?>
                    <div class="modal remote fade" id="modalparametrocliente">
                         <div class="modal-dialog modal-lg" style ="width: 530px;">    
                             <div class="modal-content"></div>
                         </div>
                    </div>
                </td>
                <td style= 'width: 25px; height: 20px;'>
                    <a href="<?= Url::toRoute(["clientes/view", "id" => $val->id_cliente, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
                <td style= 'width: 25px; height: 20px;'>
                    <a href="<?= Url::toRoute(["clientes/update", "id" => $val->id_cliente])?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                </td>
            </tr>
            </tbody>
            <?php endforeach; 
            }?>
        </table>
        <div class="panel-footer text-right" >
             <?php
                $form = ActiveForm::begin([
                            "method" => "post",                            
                        ]);
                ?> 
             <?php if($model){?> 
                <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
                <a align="right" href="<?= Url::toRoute("clientes/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
             <?php }else{ ?>     
                <a align="right" href="<?= Url::toRoute("clientes/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>   
             <?php }?>   
              <?php $form->end() ?>
            
        </div>
    </div>
</div>
 <?php if($model){?> 
   <?= LinkPager::widget(['pagination' => $pagination]) ?>
 <?php } ?> 