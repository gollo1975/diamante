
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use app\models\ComprobanteEgresoTipo;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'BUSQUEDA DE REFERENCIAS';
$this->params['breadcrumbs'][] = $this->title;
?>
    <?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute(["inventario-punto-venta/search_referencias"]),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-3 control-label'],
                    'options' => []
                ],

]);?>
<div class="panel panel-success panel-filters">
   <div class="panel-heading">
       Busqueda general del producto
   </div>

   <div class="panel-body" id="entrada_producto">
       <div class="row" >
           <?= $formulario->field($form, 'codigo_producto',['inputOptions' =>['autofocus' => 'autofocus', 'class' => 'form-control']])?>
      </div>
   </div>    
   <div class="panel-footer text-right">
           <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
   </div>

</div>
<?php 

 $formulario->end() ?>
<?php
    $form = ActiveForm::begin([
                "method" => "post",                            
            ]);
?>
<div class="table-responsive">
    <div class="panel panel-success ">
        <div class="panel-heading">
            <?php if($model){?>
               Registros <span class="badge"> <?= count($model)?></span>
            <?php } ?>   
        </div>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr style ='font-size:90%;'>                
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Codigo</th>                        
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre del producto</th>                        
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Punto de venta</th>  
                         <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre de marca</th>  
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Precio venta</th>  
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Existencias</th>      
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Imagen</th>
                        <th scope="col" style='background-color:#B9D5CE;'></th>

                    </tr>
                </thead>
                <tbody>
                    <?php if($model){
                        $cadena = '';
                        $item = \app\models\Documentodir::findOne(18);
                        foreach ($model as $val):
                            $valor = app\models\DirectorioArchivos::find()->where(['=', 'codigo', $val->id_inventario])->andWhere(['=', 'numero', $item->codigodocumento])->one();
                            ?>
                            <tr style ='font-size: 90%;'>                
                                <td><?= $val->codigo_producto ?></td>
                                <td><?= $val->nombre_producto ?></td>
                                <td><?= $val->punto->nombre_punto ?></td>
                                <td><?= $val->marca->marca ?></td>
                                <td style="text-align: right"><?= '$'.number_format($val->precio_deptal,0) ?></td>
                                <td style="text-align: right"><?= ''.number_format($val->stock_inventario,0) ?></td>
                                <?php
                                if ($valor) {
                                    $cadena = 'Documentos/' . $valor->numero . '/' . $valor->codigo . '/' . $valor->nombre;
                                    if ($valor->extension == 'png' || $valor->extension == 'jpeg' || $valor->extension == 'jpg') {
                                        ?>
                                        <td  style="width: 10%; height: 20%; text-align: center; background-color: white" title="<?php echo $val->nombre_producto ?>"> <?= yii\bootstrap\Html::img($cadena, ['width' => '100%;', 'height' => '60%;']) ?></td>
                                    <?php } else { ?>
                                        <td><?= 'NOT FOUND' ?></td>
                                    <?php }
                                } else {
                                    ?>
                                    <td></td>
                                 <?php } ?>
                                <td style= 'width: 25px; height: 10px;'>
                                     <a href="<?= Url::toRoute(["inventario-punto-venta/view_search", "id" => $val->id_inventario, 'tokenAcceso' => $tokenAcceso, 'token' =>$token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite crear las cantidades del producto, lote y codigos"></span></a>
                                </td>     
                                    
                            </tr>            
                        <?php endforeach; 
                    }?>
                </tbody>
            </table>
    </div>
</div> 
 <?php $formulario->end() ?>       
