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

$this->title = 'RECIBOS DE CAJA';
$this->params['breadcrumbs'][] = $this->title;

$banco = ArrayHelper::map(app\models\EntidadBancarias::find()->orderBy ('entidad_bancaria ASC')->all(), 'codigo_banco', 'entidad_bancaria');
$tipo = ArrayHelper::map(app\models\TipoReciboCaja::find()->all(), 'id_tipo', 'concepto');
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
    "action" => Url::toRoute("recibo-caja/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>
<?php if($tokenAcceso == 3){?>
    <div class="panel panel-success panel-filters">
        <div class="panel-heading" onclick="mostrarfiltro()">
            Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
        </div>

        <div class="panel-body" id="filtrocliente" style="display:none">
            <div class="row" >
                <?= $formulario->field($form, "numero")->input("search") ?>
                <?= $formulario->field($form, "cliente")->input("search") ?>

            </div>
            <div class="panel-footer text-right">
                <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                <a align="right" href="<?= Url::toRoute("recibo-caja/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
            </div>
        </div>
    </div>
<?php }else{?>
<div class="panel panel-success panel-filters">
        <div class="panel-heading" onclick="mostrarfiltro()">
            Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
        </div>

        <div class="panel-body" id="filtrocliente" style="display:none">
            <div class="row" >
                <?= $formulario->field($form, "numero")->input("search") ?>
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
                <?= $formulario->field($form, 'banco')->widget(Select2::classname(), [
                    'data' => $banco,
                    'options' => ['prompt' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                    <?= $formulario->field($form, 'tipo_recibo')->widget(Select2::classname(), [
                    'data' => $tipo,
                    'options' => ['prompt' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
             <div class="row checkbox checkbox-success" align ="center">
                <?= $formulario->field($form, 'recibo_detalle')->checkbox(['label' => 'Detalle recibo de caja por fechas', '1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:10px;', 'id'=>'recibo_detalle']) ?>
            </div>
            <div class="panel-footer text-right">
                <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                <a align="right" href="<?= Url::toRoute("recibo-caja/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
            </div>
        </div>
    </div>
<?php }?>
<?php $formulario->end() ?>

<div class="table-responsive">
<div class="panel panel-success ">
    <div class="panel-heading">
        Registros <span class="badge"><?= $pagination->totalCount ?></span>
    </div>
       <?php if($tokenAcceso == 3){?>
            <table class="table table-responsive">
                <thead>
                    <tr style="font-size: 85%;">    
                         <th scope="col" style='background-color:#B9D5CE;'>No recibo</th>
                         <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                         <th scope="col" style='background-color:#B9D5CE;'>F. pago</th>
                         <th scope="col" style='background-color:#B9D5CE;'>Vr pago</th>
                         <th scope="col" style='background-color:#B9D5CE;'></th>  
                         <th scope="col" style='background-color:#B9D5CE;'></th>  

                     </tr>
                </thead>
                <tbody>
                <?php 
                foreach ($model as $val):
                    $detalle = app\models\ReciboCajaDetalles::find()->where(['=','id_recibo', $val->id_recibo])->all();
                    ?>
                        <tr style="font-size: 85%;">                   
                            <td><?= $val->numero_recibo ?></td>
                            <td><?= $val->cliente ?></td>
                             <td><?= $val->fecha_pago ?></td>
                            <td style="text-align: center"><?= '$'.number_format($val->valor_pago,0)?></td>
                            <?php if($tokenAcceso == 3 && count($detalle)<= 0){?>
                                <td style= 'width: 20px; right: 20px;'>
                                   <a href="<?= Url::toRoute(["recibo-caja/view_cliente", "id" => $val->id_recibo, 'token' => $token, 'tokenAcceso' => $tokenAcceso]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                                </td>
                                <td style= 'width: 20px; right: 20px;'>
                                   <a href="<?= Url::toRoute(["recibo-caja/update_cliente", "id" => $val->id_recibo, 'agente' => $agente]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                                </td>
                            <?php }else{ ?>
                                <td style= 'width: 20px; right: 20px;'>
                                   <a href="<?= Url::toRoute(["recibo-caja/view_cliente", "id" => $val->id_recibo, 'token' => $token, 'tokenAcceso' => $tokenAcceso]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                                </td>
                            <?php }?>    
                        </tr>
                <?php endforeach;?>
               </tbody>        
            </table>
       <?php }else{ ?>
        <?php $form = ActiveForm::begin([
                    "method" => "post",                            
                ]);
        ?>
           <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr style="font-size: 85%;">    
                         <th scope="col" style='background-color:#B9D5CE;'>No recibo</th>
                         <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                         <th scope="col" style='background-color:#B9D5CE;'>Tipo recibo</th>
                         <th scope="col" style='background-color:#B9D5CE;'>Banco</th>    
                         <th scope="col" style='background-color:#B9D5CE;'>F. pago</th>
                         <th scope="col" style='background-color:#B9D5CE;'>Vr pago</th>
                         <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso autorizado">Aut.</span></th>
                         <th scope="col" style='background-color:#B9D5CE;'></th>  
                         <th scope="col" style='background-color:#B9D5CE;'></th>  

                     </tr>
                </thead>
                <tbody>
                <?php 
                foreach ($model as $val):
                    $detalle = app\models\ReciboCajaDetalles::find()->where(['=','id_recibo', $val->id_recibo])->all();
                    ?>
                        <tr style="font-size: 85%;">                   
                            <td><?= $val->numero_recibo ?></td>
                            <td><?= $val->cliente ?></td>
                            <td><?= $val->tipo->concepto ?></td>
                            <td><?= $val->codigoBanco->entidad_bancaria ?></td>
                            <td><?= $val->fecha_pago ?></td>
                            <td style="text-align: right"><?= '$'.number_format($val->valor_pago,0)?></td>
                            <td><?= $val->autorizadoRecibo ?></td>
                            <?php if(count($detalle)<= 0){?>
                                <td style= 'width: 20px; right: 20px;'>
                                   <a href="<?= Url::toRoute(["recibo-caja/view", "id" => $val->id_recibo, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                                </td>
                                <td style= 'width: 20px; right: 20px;'>
                            <a href="<?= Url::toRoute(["recibo-caja/update_cliente", "id" => $val->id_recibo, 'agente' => $agente]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                                </td>
                            <?php }else{ ?>
                                <td style= 'width: 20px; right: 20px;'>
                                   <a href="<?= Url::toRoute(["recibo-caja/view", "id" => $val->id_recibo, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                                </td>
                                <td style= 'width: 20px; right: 20px;'></td>
                            <?php }?>    
                        </tr>
                <?php endforeach;?>
               </tbody>        
            </table>
        <?php } 
        if($tokenAcceso <> 3){?>
            <div class="panel-footer text-right" >  
                <div class="btn-group btn-sm" role="group">    
                       <button type="button" class="btn btn-success  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           Exportar excel
                           <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li> <?= Html::submitButton("<span class='glyphicon glyphicon-book'></span> Recibos", ['name' => 'excel']); ?> </li>
                            <?php if($recibo_detalle == 1 && $desde <> '' && $hasta <> ''){?>
                                <li><?= Html::a('<span class="glyphicon glyphicon-export"></span> Detalle', ['excel_recibo_detalle', 'desde' => $desde, 'hasta' => $hasta]) ?></li>
                            <?php }?>    
                        </ul>
                </div>  
            </div>  
     <?php $form->end() ?>
         <?php }?>  
    
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>


