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
$this->title = 'LISTAR SOLICITUD (Referencia: '.$detalle->detalle->inventario->codigo_producto.')';
$this->params['breadcrumbs'][] = ['label' => 'Listar despacho', 'url' => ['view', 'id' => $id, 'token' => 0]];
$this->params['breadcrumbs'][] = $id;
?>
<div class="btn-group btn-sm" role="group">    
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view', 'id' => $id,'token' => 0], ['class' => 'btn btn-primary btn-sm']) ?>
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
            <?= $form->field($model, 'cantidad_solicitadas')->textInput(['disabled' => true]) ?>
       
            <?= $form->field($model, 'cantidad_despachada')->textInput(['required' => 'true']) ?>
        </div>
       
    </div>
</div>    
        
<div class="panel panel-success">
     <div class="panel-heading">
         LUGARES DE ALMACENAMIENTO
     </div>
     <div class="panel-body">
         <table class="table table-bordered table-hover">
             <thead>
                 <tr style="font-size: 85%;">
                     <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Presentacion</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Piso</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Rack</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Posicion</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Lote</th>
                      <th scope="col" style='background-color:#B9D5CE;'>F. Vcto</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                     <th scope="col" style='background-color:#B9D5CE;'></th>
                 </tr>
             </thead>
             <?php
                $con = 0;   
             if(count($almacenamiento) > 0){
                 foreach ($almacenamiento as $val):
                    $con += 1;?>
                     <tr style="font-size: 85%;">
                         <td> <?= $val->inventarioProducto->codigo_producto ?></td>
                         <td> <?= $val->inventarioProducto->nombre_producto ?></td>
                         <td> <?= $val->piso->descripcion ?></td>
                         <td> <?= $val->rack->descripcion ?></td>
                         <td> <?= $val->posicion->posicion ?></td>
                         <td> <?= $val->numero_lote ?></td>
                         <td style="background-color: #f8efc0"> <?= $val->fecha_vencimiento ?></td>
                         <td style="text-align: right"> <?= ''.number_format($val->cantidad,0) ?></td>
                         <?php if($con == 1){?>
                            <td style= 'width: 20px;'><input type="checkbox" name="seleccione_item[]" value="<?= $val->id?>"></td> 
                         <?php }else{ ?>
                            <td style= 'width: 20px; height: 20px'></td>
                         <?php } ?>   
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

<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>