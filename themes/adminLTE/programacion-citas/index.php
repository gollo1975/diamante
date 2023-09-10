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
use app\models\Clientes;
use app\models\AgentesComerciales;
use app\models\User;

$this->title = 'PROGRAMACION DE CITAS';
$this->params['breadcrumbs'][] = $this->title;
?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtropedido");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("programacion-citas/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$vendedor = ArrayHelper::map(AgentesComerciales::find()->where(['=','estado', 0])->orderBy ('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');

?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtropedido" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, 'proceso')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Todos...']) ?>
             <?php if($tokenAcceso == 3){
            }else{?>
                <?= $formulario->field($form, 'vendedor')->widget(Select2::classname(), [
                'data' => $vendedor,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                ]);
            }
            ?> 
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
           
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("programacion-citas/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
    <?php if($tokenAcceso == 3){?>
        <table class="table table-responsive">
            <thead>
                <tr style="font-size: 90%;">   
                    <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Desde</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Hasta</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Nro citas</th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso cerrado">Cerrado</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                </tr>
            </thead>    
            <tbody>
            <?php foreach ($model as $val): 
                 $contador = app\models\ProgramacionCitaDetalles::find()->where(['=','id_programacion', $val->id_programacion])->all();
                ?>
            <tr style="font-size: 90%;">  
                <?php if($val->proceso_cerrado == 0){?>                
                        <td><?= $val->id_programacion ?></td>
                        <td><?= $val->fecha_inicio ?></td>
                        <td><?= $val->fecha_final ?></td>
                        <td><?= $val->total_citas ?></td>
                        <td><?= $val->procesoCerrado ?></td>
                        <td style= 'width: 25px; height: 25px;'>
                         <?php if(count($contador) > 0){?>
                         <?php }else{?>    
                            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                    ['/programacion-citas/editar_cita', 'id' => $val->id_programacion, 'agente' => $agente],
                                    [
                                        'title' => 'Permite editar las fechas de la programacion',
                                        'data-toggle'=>'modal',
                                        'data-target'=>'#modaleditarcitas'.$val->id_programacion,
                                    ])    
                               ?>
                            <div class="modal remote fade" id="modaleditarcitas<?= $val->id_programacion ?>">
                                <div class="modal-dialog modal-lg" style ="width: 400px;">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                        </td>
                         <?php }?>
                        <td style= 'width: 25px; height: 25px;'>
                            <a href="<?= Url::toRoute(["programacion-citas/view", "id" => $val->id_programacion, 'agenteToken' => $agente, 'tokenAcceso' => $tokenAcceso]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                <?php }else{?>
                    <td style='background-color:#F0F3EF;'><?= $val->id_programacion ?></td>
                    <td style='background-color:#F0F3EF;'><?= $val->fecha_inicio?></td>
                    <td style='background-color:#F0F3EF;'><?= $val->fecha_final ?></td>
                    <td style='background-color:#F0F3EF;'><?= $val->total_citas ?></td>
                    <td style='background-color:#F0F3EF;'><?= $val->procesoCerrado ?></td>
                    <td style= 'width: 25px; height: 25px; background-color:#F0F3EF;'>
                    </td>
                    <td style= 'width: 25px; height: 25px; background-color:#F0F3EF;'>
                        <a href="<?= Url::toRoute(["programacion-citas/view", "id" => $val->id_programacion,'agenteToken' => $agente, 'tokenAcceso' => $tokenAcceso]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>
                <?php }?>        
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php }else{?>
         <table class="table table-bordered table-hover">
            <thead>
                <tr style="font-size: 90%;">   
                    <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Documento</th>   
                    <th scope="col" style='background-color:#B9D5CE;'>Agente comercial</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Desde</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Hasta</th>
                    <th scope="col" style='background-color:#B9D5CE;'>V. Programadas</th>
                    <th scope="col" style='background-color:#B9D5CE;'>V. Reales</th>
                    <th scope="col" style='background-color:#B9D5CE;'>V. pendientes</th>
                    <th scope="col" style='background-color:#B9D5CE;'>%</th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso cerrado">Cerrado</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style="font-size: 90%;">  
                <?php if($val->proceso_cerrado == 0){?>                
                    <td><?= $val->id_programacion ?></td>
                    <td><?= $val->agente->nit_cedula ?></td>
                    <td><?= $val->agente->nombre_completo ?></td>
                    <td><?= $val->fecha_inicio ?></td>
                    <td><?= $val->fecha_final ?></td>
                    <td><?= $val->total_citas ?></td>
                    <td><?= $val->visitas_cumplidas ?></td>
                    <td><?= $val->visitas_no_cumplidas ?></td>
                    <td><?= $val->porcentaje_eficiencia ?>%</td>
                    <td><?= $val->procesoCerrado ?></td>
                    <td style= 'width: 25px; height: 25px;'>
                        <a href="<?= Url::toRoute(["programacion-citas/view", "id" => $val->id_programacion, 'agenteToken' => $agente, 'tokenAcceso' => $tokenAcceso]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>
                <?php }else{ ?>
                        <td style='background-color:#F0F3EF;'><?= $val->id_programacion ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->agente->nit_cedula ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->agente->nombre_completo?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->fecha_inicio  ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->fecha_final ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->total_citas  ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->visitas_cumplidas  ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->visitas_no_cumplidas  ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->porcentaje_eficiencia  ?>%</td>
                        <td style='background-color:#F0F3EF;'><?= $val->procesoCerrado ?></td>
                        <td style= 'width: 25px; height: 25px; background-color:#F0F3EF;'>
                            <a href="<?= Url::toRoute(["programacion-citas/view", "id" => $val->id_programacion, 'agenteToken' => $agente, 'tokenAcceso' => $tokenAcceso]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                <?php }?>        
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php }
            if($tokenAcceso == 3){?>
                <div class="panel-footer text-right" >            
                   <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Programar dia',
                       ['/programacion-citas/crearcita','agente' => $agente],
                         ['title' => 'Crear nueva cita para cliente',
                          'data-toggle'=>'modal',
                          'data-target'=>'#modalcrearcita',
                          'class' => 'btn btn-success btn-xs'
                         ])    
                   ?>
                   <div class="modal remote fade" id="modalcrearcita">
                        <div class="modal-dialog modal-lg" style ="width: 450px;">    
                            <div class="modal-content"></div>
                        </div>
                   </div>
               </div>
            <?php }?>    
       <?php $form->end() ?>
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>