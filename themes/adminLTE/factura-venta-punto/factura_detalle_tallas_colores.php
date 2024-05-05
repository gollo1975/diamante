<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use kartik\select2\Select2;

use app\models\Tallas;
use app\models\DetalleColorTalla;

$this->title = 'TALLAS Y COLORES';
$this->params['breadcrumbs'][] = ['label' => 'Factura de venta', 'url' => ['view','id_factura_punto'=> $id_factura_punto, 'accesoToken' => $accesoToken]];
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view','id_factura_punto' => $id_factura_punto, 'accesoToken' =>$accesoToken], ['class' => 'btn btn-primary btn-sm']) ?>
</p>   
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute(["factura-venta-punto/crear_talla_color", 'id_factura_punto' => $id_factura_punto,'accesoToken' => $accesoToken,'id_detalle' => $id_detalle]),
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
        Busqueda por tallas
    </div>

    <div class="panel-body" id="entrada_producto">
        <div class="row" >
            <?= $formulario->field($form, 'id_talla')->widget(Select2::classname(),[
                'data' => $conTallas,
                'options' => ['prompt' => 'Seleccione la talla...'],
                'pluginOptions' => [
                    'allowClear' => true,
                ]

                ])?>
       </div>
    </div>    
    <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Cargar colores", ["class" => "btn btn-primary btn-sm",]) ?>
    </div>

</div>
<?php $formulario->end() ?>
<?php $form = ActiveForm::begin([
                "method" => "post",                            
            ]);
    ?>

    <div class="panel panel-success">
        <div class="panel-heading">
            Listado de colores
        </div>
        <div class="panel-body">
             <table class="table table-bordered table-hover">
                <thead>
                    <tr style='font-size:90%;'>
                        <th scope="col" style='background-color:#B9D5CE;'>Codigo del color</th>                      
                        <th scope="col" style='background-color:#B9D5CE;'>Nombre del color</th> 
                        <th scope="col" style='background-color:#B9D5CE;'>Stock</th> 
                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad a vender</th> 
                        <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($conColores){
                        $auxiliar = 0;
                        foreach ($conColores  as $val):
                            if($auxiliar <> $val->id_color){
                                $auxiliar = $val->id_color;  ?>
                                <tr>
                                    <td><?= $val->id_color?></td>
                                    <td><?= $val->color->colores?></td> 
                                    <td><?= $val->stock_punto?></td>
                                    <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="cantidad_venta[]" style="text-align: right" size="9" > </td> 
                                    <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="nuevo_color[]" value="<?= $val->id_color ?>"></td> 
                                </tr>
                             
                            <?php }   

                        endforeach;
                    }    ?>
                </tbody>
            </table>    
        </div>
        <div class="panel-footer text-right">
       <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-success btn-sm", 'name' => 'enviarcolores']) ?>        
    </div>
    <?php $form->end() ?>
    </div>
    
       
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

         