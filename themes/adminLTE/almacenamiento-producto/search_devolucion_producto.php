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

$this->title = 'DEVOLUCION PRODUCTOS';
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
    "action" => Url::toRoute("almacenamiento-producto/search_producto_devolucion"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$cliente = ArrayHelper::map(app\models\Clientes::find()->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "numero")->input("search") ?>
             <?= $formulario->field($form, 'cliente')->widget(Select2::classname(), [
                'data' => $cliente,
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
            <a align="right" href="<?= Url::toRoute("almacenamiento-producto/search_producto_devolucion") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <tr style ='font-size: 90%;'>         
                
                <th scope="col" style='background-color:#B9D5CE;'>No devolución</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nota credito</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha devolución</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cant. inventario</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cant. averias</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Producto almacenado">Alm.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                 <th scope="col" style='background-color:#B9D5CE;'></th>
                                          
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($model as $val):
                $almacenar = app\models\AlmacenamientoProducto::find()->where(['=','id_devolucion', $val->id_devolucion])->one();
                ?>
                    <tr style ='font-size: 90%;'>                
                        <td><?= $val->numero_devolucion?></td>
                        <td><?= $val->cliente->nit_cedula?></td>
                        <td><?= $val->cliente->nombre_completo?></td>
                        <td><?= $val->nota->numero_nota_credito?></td>
                        <td><?= $val->fecha_devolucion?></td>
                        <td style="text-align: right"><?= ''.number_format($val->cantidad_inventario,0)?></td>
                        <td style="text-align: right"><?= ''.number_format($val->cantidad_averias,0)?></td>
                        <?php if($val->almacenado == 0){?>
                        <td style="background-color: #aaeeee"><?= $val->productoAlmacenado?></td>
                        <?php }else{?>
                           <td style="background-color:#eae2b7"><?= $val->productoAlmacenado?></td>
                        <?php } 
                        if(!$almacenar){
                            ?>    
                            <td style= 'width: 20px; height: 20px;'>
                                <?= Html::a('<span class="glyphicon glyphicon-list"></span>', ['enviar_lote_almacenar_devolucion', 'id_devolucion' => $val->id_devolucion], [
                                           'class' => '',
                                           'title' => 'Permite almacenar los lotes de la orden de produccion',
                                           'data' => [
                                               'confirm' => '¿Esta seguro que se desea ALMACENAR  las devoluciones de productos contenidas en la Orden No ( '.$val->numero_devolucion.') ?',
                                               'method' => 'post',
                                           ],
                                           ])?>
                                
                            </td> 
                            <td style= 'width: 20px; height: 20px;'></td>
                        <?php }else{?>
                            <td style= 'width: 20px; height: 20px;'></td>
                            <td style= 'width: 20px; height: 20px;'>
                             <a href="<?= Url::toRoute(["almacenamiento-producto/view_almacenamiento_devolucion", "id_devolucion" => $val->id_devolucion, 'token' => 0]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                            </td>  
                        <?php }?>    
                   </tr>            
            <?php endforeach;?>
            </tbody>    
        </table> 
           <?php $form->end() ?>
       
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>

