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

$this->title = 'Listado de pre-pedidos';
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
    "action" => Url::toRoute("pedidos/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
if($tokenAcceso == 3){
    $agente = AgentesComerciales::find()->where(['=','nit_cedula', $tokenAgente])->one();
    $cliente = ArrayHelper::map(Clientes::find()->where(['=','estado_cliente', 0])
                                                ->andWhere(['=','id_agente', $agente->id_agente])
                                                ->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
}else{
    if($tokenAcceso == 1){
        $cliente = ArrayHelper::map(Clientes::find()->where(['=','estado_cliente', 0])
                                                  ->andWhere(['=','id_tipo_cliente', 2])
                                                  ->orWhere(['=','id_tipo_cliente', 3])
                                                ->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
    }else{
        $cliente = ArrayHelper::map(Clientes::find()->where(['=','estado_cliente', 0])
                                                    ->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
        $vendedores = ArrayHelper::map(AgentesComerciales::find()->where(['=','estado', 0])->orderBy('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
    }    
}
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtropedido" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "numero_pedido")->input("search") ?>
            <?= $formulario->field($form, "documento")->input("search") ?>
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
            <?= $formulario->field($form, 'cliente')->widget(Select2::classname(), [
                'data' => $cliente,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
             <?php if($tokenAcceso == 2){?>
                <?= $formulario->field($form, 'vendedor')->widget(Select2::classname(), [
                'data' => $vendedores,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                'allowClear' => true
                ],
            ]);?>
            <?php }?>           
           
            <?= $formulario->field($form, 'pedido_cerrado')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
            <?= $formulario->field($form, 'presupuesto')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
                  
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("pedidos/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
        <?php if($model){?>
            Registros <span class="badge"><?= $pagination->totalCount ?></span>
        <?php } ?>    
    </div>
    <?php if($tokenAcceso == 3 || $tokenAcceso == 1){
         ?>
        <table class="table table-responsive">
            <thead>
                <tr style="font-size: 90%;">   
                    <th scope="col" style='background-color:#B9D5CE;'>No pedido</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Pedido cerrado">Cer.</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Pedido virtual">P.v.</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Pedido anulado">P.a.</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                </tr>
            </thead>    
            <tbody>
            <?php
            if($model){
                foreach ($model as $val): ?>
                <tr style = "font-size: 90%;">  
                <?php if($val->cerrar_pedido == 0){?>                
                    <td><?= $val->numero_pedido ?></td>
                    <td><?= $val->cliente ?></td>
                    <td><?= $val->pedidoAbierto ?></td>
                    <td><?= $val->pedidoVirtual ?></td>
                     <td><?= $val->pedidoAnulado ?></td>
                    <td style= 'width: 25px; height: 25px;'>
                        <?php if($val->gran_total == 0 ){?>   
                            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                    ['/pedidos/editarcliente', 'id' => $val->id_pedido, 'tokenAcceso' => $tokenAcceso],
                                    [
                                        'title' => 'Editar el cliente para el pedido',
                                        'data-toggle'=>'modal',
                                        'data-target'=>'#modaleditarcliente'.$val->id_pedido,
                                    ])    
                               ?>
                            <div class="modal remote fade" id="modaleditarcliente<?= $val->id_pedido ?>">
                                <div class="modal-dialog modal-lg" style ="width: 430px;">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                        <?php }?>    
                    </td>
                    <?php
                    if($val->numero_pedido == 0 ){
                        if($empresa->inventario_enlinea == 0){ ?>   
                            <td style= 'width: 25px; height: 25px;'>
                                <a href="<?= Url::toRoute(["pedidos/adicionar_productos", "id" => $val->id_pedido, 'tokenAcceso' => $tokenAcceso, 'token' => 1, 'pedido_virtual' => $val->pedido_virtual, 'tipo_pedido' => $empresa->inventario_enlinea]) ?>" ><span class="glyphicon glyphicon-share-alt"></span></a>
                            </td>
                        <?php }else{ ?>
                            <td style= 'width: 25px; height: 25px;'>
                                <a href="<?= Url::toRoute(["pedidos/adicionar_producto_pedido", "id" => $val->id_pedido, 'tokenAcceso' => $tokenAcceso, 'token' => 1, 'pedido_virtual' => $val->pedido_virtual,'tipo_pedido' => $empresa->inventario_enlinea]) ?>" ><span class="glyphicon glyphicon-share-alt"></span></a>
                            </td>
                        <?php }    
                    }else{  ?>    
                        <td style= 'width: 25px; height: 25px;'>
                            <a href="<?= Url::toRoute(["pedidos/adicionar_productos", "id" => $val->id_pedido, 'tokenAcceso' => $tokenAcceso, 'token' => 1, 'pedido_virtual' => $val->pedido_virtual, 'tipo_pedido' => $empresa->inventario_enlinea]) ?>" ><span class="glyphicon glyphicon-share-alt"></span></a>
                        </td>
                        <td style= 'width: 25px; height: 25px;'>
                            <a href="<?= Url::toRoute(["pedidos/imprimir_pedido", "id" => $val->id_pedido, 'tokenAcceso' => $tokenAcceso]) ?>" ><span class="glyphicon glyphicon-print"></span></a>
                        </td>
                    <?php }?>    
                <?php }else{
                    ?>
                    <td style='background-color:#F0F3EF;'><?= $val->numero_pedido ?></td>
                    <td style='background-color:#F0F3EF;'><?= $val->cliente ?></td>
                    <td style='background-color:#F0F3EF;'><?= $val->pedidoAbierto ?></td>
                    <?php if($val->pedido_virtual == 0){?>
                        <td style='background-color:#F0F3EF;'><?= $val->pedidoVirtual ?></td>
                    <?php }else{?>
                        <td style='background-color:#E1E9F9; color: black'><?= $val->pedidoVirtual ?></td>
                    <?php }  
                    if($val->pedido_anulado == 0){?>
                       <td style='background-color:#F0F3EF;'><?= $val->pedidoAnulado ?></td>
                    <?php }else{?>
                       <td style='background-color:#F7F3E1; color: black'><?= $val->pedidoAnulado ?></td>
                    <?php }?>      
                    <td style= 'width: 25px; height: 25px; background-color:#F0F3EF;'>
                    </td>
                    <td style= 'width: 25px; height: 25px; background-color:#F0F3EF;'>
                        <a href="<?= Url::toRoute(["pedidos/adicionar_productos", "id" => $val->id_pedido,'tokenAcceso' => $tokenAcceso,'token' => 1, 'pedido_virtual' => $val->pedido_virtual, 'tipo_pedido' => $empresa->inventario_enlinea]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>
                <?php }?>        
            </tr>
            <?php endforeach;
            }?>
            </tbody>
        </table>
    <?php }else{?> <!--TERMINA EL CICLO DE LOS COMERCIALES-->
         <table class="table table-bordered table-hover">
            <thead>
                <tr style="font-size: 90%;">   
                    <th scope="col" style='background-color:#B9D5CE;'>No pedido</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Departamento</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Municipio</th>
                    <th scope="col" style='background-color:#B9D5CE;'>F. pedido</th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Aplica presupuesto al cliente">Ap</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Pedido virtual">Pv</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Pedido validado para inventarios">Ok</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th> 
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                    
                </tr>
            </thead>
            <tbody>
            <?php
            if($model){
                foreach ($model as $val): ?>
            <tr style="font-size: 90%;">  
                <?php if($val->cerrar_pedido == 0){?>                
                    <td><?= $val->numero_pedido ?></td>
                    <td><?= $val->cliente ?></td>
                    <td><?= $val->clientePedido->codigoDepartamento->departamento ?></td>
                    <td><?= $val->clientePedido->codigoMunicipio->municipio ?></td>
                    <td><?= $val->fecha_proceso ?></td>
                    <?php if($val->valor_presupuesto == 0){?>
                       <td style='background-color:#F0F3EF;'><?= $val->presupuestoPedido ?></td>
                    <?php }else{?>
                       <td style='background-color:#8FA5D5; color: black'><?= $val->presupuestoPedido ?></td>
                    <?php }
                    if($val->pedido_virtual == 0){?>
                       <td><?= $val->pedidoVirtual ?></td>
                    <?php }else{?>
                       <td style='background-color:#E1E9F9; color: black'><?= $val->pedidoVirtual ?></td>
                    <?php }?>
                    
                    
                    <td style= 'width: 25px; height: 25px;'>
                        <a href="<?= Url::toRoute(["pedidos/adicionar_productos", "id" => $val->id_pedido,'tokenAcceso' => $tokenAcceso,'token' => 1, 'pedido_virtual' => $val->pedido_virtual, 'tipo_pedido' => $empresa->inventario_enlinea]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>
                <?php }else{ ?>
                        <td style='background-color:#F0F3EF;'><?= $val->numero_pedido ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->cliente ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->clientePedido->codigoDepartamento->departamento ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->clientePedido->codigoMunicipio->municipio ?></td>
                        <td style='background-color:#F0F3EF;'><?= $val->fecha_proceso ?></td>
                        <?php if($val->valor_presupuesto == 0){?>
                            <td style='background-color:#F0F3EF;'><?= $val->presupuestoPedido ?></td>
                        <?php }else{?>
                           <td style='background-color:#8FA5D5; color: black'><?= $val->presupuestoPedido ?></td>
                        <?php }
                        if($val->pedido_virtual == 0){?>
                            <td style='background-color:#F0F3EF;'><?= $val->pedidoVirtual ?></td>
                        <?php }else{?>
                               <td style='background-color:#E1E9F9; color: black'><?= $val->pedidoVirtual ?></td>
                        <?php }
                        if($val->liberado_inventario == 0){?>
                            <td><?= $val->pedidoLiberado ?></td>
                         <?php }else{?>
                            <td style='background-color:#f4f0bb; color: black'><?= $val->pedidoLiberado ?></td>
                         <?php }?>
                        <td style= 'width: 25px; height: 25px; background-color:#F0F3EF;'>
                            <a href="<?= Url::toRoute(["pedidos/view", "id" => $val->id_pedido,'tokenAcceso' => $tokenAcceso,'token' => 0]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                         <td style= 'width: 25px; height: 10px;'>
                            <?= Html::a('<span class="glyphicon glyphicon-user"></span> ', ['pedidos/validar_lineas_pedido', 'id' => $val->id_pedido], [
                                           'class' => '',
                                           'title' => 'Proceso que permite validar si hay inventario para este pedido.', 
                                           'data' => [
                                               'confirm' => 'Esta seguro de VALIDAR el Pedido Nro:  ('.$val->numero_pedido.') para verificar si hay existencias en el modulo de inventario.',
                                               'method' => 'post',
                                           ],
                             ])?>
                         </td>
                         <?php if($val->liberado_inventario == 1){
                                if($val->presupuesto == 0){
                                    ?>
                                   <td style= 'width: 25px; height: 10px;'>
                                       <?= Html::a('<span class="glyphicon glyphicon-import"></span> ', ['pedidos/validar_linea_inventario', 'id' => $val->id_pedido], [
                                                      'class' => '',
                                                      'title' => 'Proceso que permite descargar la cantidad vendida del inventario.', 
                                                      'data' => [
                                                          'confirm' => '¿Esta seguro de enviar las unidadades vendidas al modulo de inventario?. Tener presente que debe descargar el presupuesto comercial.',
                                                          'method' => 'post',
                                                      ],
                                        ])?>
                                   </td>
                                <?php }else{?>   
                                    <td style= 'width: 25px; height: 10px;'>
                                        <?= Html::a('<span class="glyphicon glyphicon-ruble"></span> ', ['pedidos/validar_lineas_presupuesto', 'id' => $val->id_pedido], [
                                                       'class' => '',
                                                       'title' => 'Proceso que permite validar el presupuesto comercial.', 
                                                       'data' => [
                                                           'confirm' => 'Esta seguro de VALIDAR el presupuesto comercial Nro:  ('.$val->numero_pedido.') para verificar si hay existencias en el modulo de inventario.',
                                                           'method' => 'post',
                                                       ],
                                         ])?>
                                    </td>
                                    <td style= 'width: 25px; height: 10px;'>
                                       <?= Html::a('<span class="glyphicon glyphicon-import"></span> ', ['pedidos/validar_linea_inventario', 'id' => $val->id_pedido], [
                                                      'class' => '',
                                                      'title' => 'Proceso que permite descargar la cantidad vendida del inventario.', 
                                                      'data' => [
                                                          'confirm' => '¿Esta seguro de enviar las unidadades vendidas al modulo de inventario?. Tener presente que debe descargar el presupuesto comercial.',
                                                          'method' => 'post',
                                                      ],
                                        ])?>
                                   </td>
                                <?php }    
                        }else{?>
                            <td style= 'width: 25px; height: 10px;'></td>
                             <td style= 'width: 25px; height: 10px;'></td>
                         <?php }?>

                <?php }?>        
            </tr>
            <?php endforeach; 
            }?>
            </tbody>
        </table>
        <?php }
        if($model){?>
            <div class="panel-footer text-right" >            
               <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
           </div>
        <?php }?>
    </div>
</div>
<?php if($model){?>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
<?php } ?>
  <?php $form->end() ?>