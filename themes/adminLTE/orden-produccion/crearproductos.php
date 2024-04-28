<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>


<?php $form = ActiveForm::begin([

    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
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
                   LISTADO DE PRESENTACIONES
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr style='font-size:100%;'>
                                <td scope="col" style='background-color:#B9D5CE; text-align: left'><b>Codigo</td>
                                <td scope="col" style='background-color:#B9D5CE; text-align: left'><b>Descripcion</td>
                                <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($presentacion as $val):
                                $inventario = \app\models\InventarioProductos::find()->where(['=','id_presentacion', $val->id_presentacion])->one();
                                if(!$inventario){
                                    ?>
                                    <tr style="font-size: 100%;">
                                        <td style="text-align: left"><?= $val->id_presentacion ?></td>  
                                        <td style="text-align: left"><?= $val->descripcion ?></td>
                                        <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="listado[]" value="<?= $val->id_presentacion ?>"></td> 
                                    </tr>
                                <?php }    
                            endforeach; ?>    
                        </tbody>
                    </table>
                    <div class="panel-footer text-right">			
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'listadopresentacion']) ?>                    
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

