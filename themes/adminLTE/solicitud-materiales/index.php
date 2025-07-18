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
use app\models\TipoSolicitud;



$this->title = 'SOLICITUD MATERIALES';
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
    "action" => Url::toRoute("solicitud-materiales/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$tipoSolicitud = ArrayHelper::map(\app\models\DocumentoSolicitudes::find()->where(['=','todas', 1])->orderBy ('concepto ASC')->all(), 'id_solicitud', 'concepto');
$producto = ArrayHelper::map(app\models\Productos::find()->orderBy ('nombre_producto ASC')->all(), 'id_producto', 'nombre_producto');
$grupo = ArrayHelper::map(app\models\GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');
$ordenProduccion = \app\models\OrdenEnsambleProducto::find()->all();
$ordenProduccion = ArrayHelper::map($ordenProduccion, 'id_orden_produccion', 'ordenEnsambleConsulta');


?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "numero_solicitud")->input("search") ?>
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
            <?= $formulario->field($form, 'tipo')->widget(Select2::classname(), [
                'data' => $tipoSolicitud,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
             <?= $formulario->field($form, 'producto')->widget(Select2::classname(), [
                'data' => $producto,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("solicitud-materiales/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <tr style ='font-size: 85%;'>         
                
                <th scope="col" style='background-color:#B9D5CE;'>No solicitud</th>
                <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Orden produccion</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo solicitud</th>
                <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. cierre</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Solicitud cerrada">Cerr.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Solicitud autorizado">Aut.</span></th>
                 <th scope="col" style='background-color:#B9D5CE;'>Despachado</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
             
                         
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
                <tr style ='font-size: 85%;'>                
                    <td><?= $val->numero_solicitud?></td>
                    <td><?= $val->productos->nombre_producto?></td>
                    <?php if($val->id_orden_produccion == null){?>
                        <td><?= 'NO FOUNT' ?></td>
                        
                    <?php } else { ?>
                        <td><?= $val->ordenProduccion->numero_orden?></td>
                    <?php }     
                    if($val->id_solicitud_documento == null){?>
                         <td><?= 'NO FOUNT' ?></td>
                    <?php } else { ?>     
                          <td><?= $val->solicitudDocumento->concepto?></td>
                    <?php } ?>         
                    <td><?= $val->numero_lote?></td>
                    <td><?= $val->fecha_hora_registro?></td>
                    <td><?= $val->fecha_cierre?></td>
                    <?php if($val->cerrar_solicitud == 0){?>
                        <td  style="background-color: #BBD3E0"><?= $val->cerrarSolicitud?></td>
                    <?php }else{?>
                        <td style="background-color: #EFFBDC"><?= $val->cerrarSolicitud?></td>
                    <?php }?>    
                    <?php if($val->autorizado == 0){?>
                        <td  style="background-color: #9FE6F3"><?= $val->autorizadosolicitud?></td>
                    <?php }else{?>
                        <td style="background-color: #F9ECCD"><?= $val->autorizadosolicitud?></td>
                    <?php }?>  
                     <?php if($val->despachado == 0){?>
                        <td  style="background-color: #F0DBF9"><?= $val->despachadoSolicitud?></td>
                    <?php }else{?>
                        <td style="background-color: #CFF7E9"><?= $val->despachadoSolicitud?></td>
                    <?php }
                    if($val->id_solicitud_documento == 4){?>    
                        <td style= 'width: 25px; height: 10px;'>
                            <a href="<?= Url::toRoute(["solicitud-materiales/view", "id" => $val->codigo,'token' => $token, 'sw' =>0]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear la nueva solicitud de material de empaque"></span></a>
                        </td>
                    <?php }elseif($val->id_solicitud_documento == 3){?>
                        <td style= 'width: 25px; height: 10px;'>
                            <a href="<?= Url::toRoute(["solicitud-materiales/view", "id" => $val->codigo,'token' => $token,'sw' =>1]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear la nueva solicitud de material de empaque"></span></a>
                        </td>
                    <?php }    
                    if($val->autorizado == 0){
                        if($val->id_orden_produccion !== null) { ?>
                            <td style= 'width: 25px; height: 10px;'>
                                <a href="<?= Url::toRoute(["solicitud-materiales/update", "id" => $val->codigo,'token' => $token]) ?>" ><span class="glyphicon glyphicon-pencil" title="Permite modificar la solicitud"></span></a>
                            </td>
                        <?php }else{?>
                            <td style= 'width: 25px; height: 10px;'>
                                
                            </td>
                        <?php }?>    
                    <?php }else {
                        if($val->cerrar_solicitud == 1 && $val->despachado == 0 ){
                            if ($val->id_orden_produccion !== null) { ?>
                                <td style= 'width: 25px; height: 10px;'>
                                    <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span> ', ['generar_despacho_material', 'id' => $val->codigo, 'token' => $token,'sw' => 0], [
                                                   'class' => '',
                                                   'title' => 'Proceso que permite crear el despacho de material de empaque.', 
                                                   'data' => [
                                                       'confirm' => 'Esta seguro de crear el despacho a la solicitud  Nro:  ('.$val->numero_solicitud.').',
                                                       'method' => 'post',
                                                   ],
                                    ])?>
                                </td>  
                            <?php }else{?>
                                <td style= 'width: 25px; height: 10px;'>
                                    <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span> ', ['generar_despacho_material', 'id' => $val->codigo, 'token' => $token,'sw' => 1], [
                                                   'class' => '',
                                                   'title' => 'Proceso que permite crear el despacho de material de empaque.', 
                                                   'data' => [
                                                       'confirm' => 'Esta seguro de crear el despacho a la solicitud  Nro:  ('.$val->numero_solicitud.').',
                                                       'method' => 'post',
                                                   ],
                                    ])?>
                                </td>  
                            <?php }?>
                        <?php }else{?>
                              <td style= 'width: 25px; height: 10px;'></td>
                        <?php }    
                    } ?>
                    
                </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
            <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar a excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?> 
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Nueva solicitud KITS',
                      ['/solicitud-materiales/nueva_solicitud_materiales_kits'],
                      [
                          'title' => 'Permite cargar la solicitud de kits',
                          'data-toggle'=>'modal',
                          'data-target'=>'#modalcargarsolicitudkits',
                          'class' => 'btn btn-info btn-sm',
                          'data-backdrop' => 'static',

                      ]);    
                 ?>
            </td> 
            <div class="modal remote fade" id="modalcargarsolicitudkits">
                      <div class="modal-dialog modal-lg" style ="width: 700px;">
                          <div class="modal-content"></div>
                      </div>
            </div>
            <a align="right" href="<?= Url::toRoute("solicitud-materiales/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nueva Solicitud OP</a>               
            <?php $form->end() ?>   
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
