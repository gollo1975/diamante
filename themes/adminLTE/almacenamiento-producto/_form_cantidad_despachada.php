<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use kartik\select2\Select2;
//models
$this->title = 'Listar despacho (Referencia: '.$detalle->inventario->codigo_producto.')';
$this->params['breadcrumbs'][] = ['label' => 'Listar despacho', 'url' => ['view_listar', 'id_pedido' => $id_pedido]];
$this->params['breadcrumbs'][] = $id_pedido;
?>
<div class="btn-group btn-sm" role="group">    
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view_listar', 'id_pedido' => $id_pedido], ['class' => 'btn btn-primary btn-sm']) ?>
 </div>  
<?php $form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'options' => []
            ],
        ]);
?>
<div class="panel panel-success">
    <div class="panel-heading">
        CANTIDADES A DESPACHAR
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'cantidad_vendida')->textInput(['disabled' => true]) ?>
       
            <?= $form->field($model, 'cantidad_despachada')->textInput(['required' => 'true']) ?>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
            MEDIOS DE ALMACENAMIENTO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="font-size: 90%;">
                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Piso</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Rack</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Posicion</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Lote</th>
                         <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                        <th scope="col" style='background-color:#B9D5CE;'></th>
                    </tr>
                </thead>
                <?php
                
                if(count($almacenamiento) > 0){
                    foreach ($almacenamiento as $val): ?>
                        <tr style="font-size: 90%;">
                            <td> <?= $val->codigo_producto ?></td>
                            <td> <?= $val->producto ?></td>
                            <td> <?= $val->piso->descripcion ?></td>
                            <td> <?= $val->rack->descripcion ?></td>
                            <td> <?= $val->posicion->posicion ?></td>
                            <td> <?= $val->numero_lote ?></td>
                            <td> <?= $val->fecha_almacenamiento ?></td>
                            <td style="text-align: right"> <?= ''.number_format($val->cantidad,0) ?></td>
                            <td style= 'width: 20px;'><input type="checkbox" name="seleccione_item[]" value="<?= $val->id ?>"></td> 
                        </tr>
                    <?php endforeach; 
                }else{
                        Yii::$app->getSession()->setFlash('info', 'Este producto NO presenta almacenamiento en los diferentes RACKS de la empresa. Contactar al administrador.');
                }?>    
            </table> 
            <div class="panel-footer text-right">
                <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar", ["class" => "btn btn-success btn-sm", 'name' => 'cantidaddespachada']) ?>     
            </div>    
        </div>
    </div>
</div>
<?php $form->end() ?>

