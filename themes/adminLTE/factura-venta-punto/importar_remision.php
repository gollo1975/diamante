<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$tipodocumento = ArrayHelper::map(\app\models\TipoDocumento::find()->where(['=','proceso_cliente', 1])->all(), 'id_tipo_documento', 'documento');
$departamento = ArrayHelper::map(app\models\Departamentos::find()->orderBy('departamento DESC')->all(), 'codigo_departamento', 'departamento');
$posicion = ArrayHelper::map(app\models\PosicionPrecio::find()->all(), 'id_posicion', 'posicion');
$naturaleza = ArrayHelper::map(app\models\NaturalezaSociedad::find()->all(), 'id_naturaleza', 'naturaleza');
$vendedor = ArrayHelper::map(app\models\AgentesComerciales::find()->where(['=','estado', 0])->orderBy('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
$tipoCliente = ArrayHelper::map(app\models\TipoCliente::find()->all(), 'id_tipo_cliente', 'concepto');
?>

<?php $form = ActiveForm::begin([

    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-2 control-label'],
        'options' => []
    ],
]); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">
        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left">
                   LISTADO DE REMISIONES
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr style='font-size:100%;'>
                                <td scope="col" style='background-color:#B9D5CE; text-align: left'><b>Nro remisión</td>
                                <td scope="col" style='background-color:#B9D5CE; text-align: left'><b>Cliente</td>
                                <td scope="col" style='background-color:#B9D5CE; text-align: left'><b>Fecha generada</td>
                                 <td scope="col" style='background-color:#B9D5CE; text-align: left'><b>Punto de venta</td>
                                <td scope="col" style='background-color:#B9D5CE; text-align: left'><b>Total remisión</td>
                                <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($model as $val): ?>
                                <tr style="font-size: 100%;">
                                    <td style="text-align: left"><?= $val->numero_remision ?></td>  
                                    <td style="text-align: left"><?= $val->cliente->nombre_completo ?></td>
                                    <td style="text-align: left"><?= $val->fecha_inicio ?></td>
                                     <td style="text-align: left"><?= $val->puntoVenta->nombre_punto ?></td>
                                    <td style="text-align: right"><?= '$'. number_format($val->total_remision,0) ?></td>
                                    <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="listado_remision[]" value="<?= $val->id_remision ?>"></td> 
                                </tr>
                            <?php
                            endforeach; ?>    
                        </tbody>
                    </table>
                    <div class="panel-footer text-right">			
                    <?= Html::submitButton("<span class='glyphicon glyphicon-import'></span> Importar", ["class" => "btn btn-success", 'name' => 'importar_registros']) ?>                    
                   </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
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

