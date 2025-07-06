<?php
//modelos

//clases
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
?>
<?php
$conSolicitud = ArrayHelper::map(app\models\DocumentoSolicitudes::find()->where(['=','solicitud_materiales', 1])->orderBy ('concepto ASC')->all(), 'id_solicitud', 'concepto');
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-7 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-4 control-label'],
            'options' => []
        ],
        ]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"></h4>
</div>
<div class="modal-body">        
    <div class="table table-responsive">
        <div class="panel panel-success ">
            <div class="panel-heading" style="text-align: left ">
               Crear solicitud de material de empaque
            </div>
            <div class="panel-body">
                 <div class="row">
                    <?= $form->field($model, 'tipo_solicitud')->dropdownList($conSolicitud, ['prompt' => 'Seleccione...', 'required' => true]) ?>		
                </div>
                <div class="row">
                    <?= $form->field($model, 'tipo_entrega')->dropdownList(['1' => 'PARCIAL', '2' => 'COMPLETA'], ['prompt' => 'Seleccione...','onchange' => 'mostrarcampo()', 'id' => 'tipo_entrega', 'required' => true]) ?>
                </div>
                <div class="row">
                    <div id="cantidad_entregada" style="display:none"> <?= $form->field($model, 'cantidad_entregada')->textInput() ?></div>
                </div>    

            </div>  
                <div class="panel-footer text-right">
                   
               </div>

        </div>
    </div>
 
<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#listadosolicitud" aria-controls="listadosolicitud" role="tab" data-toggle="tab">Listado de solicitud <span class="badge"><?= count($solicitud) ?></span></a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane  active" id="listadosolicitud">
            <div class="table-responsive">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr style="font-size: 85%;">
                                    <th scope="col"  style='background-color:#B9D5CE;'><b>Id</b></th>   
                                    <th scope="col"  style='background-color:#B9D5CE;'><b>Presentacion producto</b></th>  
                                    <th scope="col"  style='background-color:#B9D5CE;'><b>Inventario entregado</b></th>
                                    <th scope="col"  style='background-color:#B9D5CE;'><b>Total kits</b></th>
                                    <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                                </tr>
                            </thead>
                            <body>
                                <?php
                                foreach ($solicitud as $val):?>
                                    <tr style="font-size: 85%;">
                                        <td><?= $val->id_entrega_kits ?></td>
                                        <td style='text-align: left'><?= $val->presentacion->descripcion ?></td>
                                        <td style='text-align: right'><?= ''.number_format($val->total_unidades_entregadas,0) ?></td>
                                        <td style='text-align: right'><?= ''.number_format($val->cantidad_despachada_saldo,0) ?></td>
                                        <td style= 'width: 20px; height: 20px;'><input type="checkbox" name="nueva_entrega_materia[]" value="<?= $val->id_entrega_kits ?>"></td> 
                                    </tr>
                                 <?php endforeach;
                                 ?>          
                            </body>
                        </table>
                    </div>
                    <?php if(count($solicitud)> 0){?>
                        <div class="panel-footer text-right">  
                          <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar documento", ["class" => "btn btn-primary", 'name' => 'enviar_documento']) ?>                    
                       </div>
                    <?php }?>
                </div>
            </div>
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
    function mostrarcampo(){
        let tipo_entrega = document.getElementById('tipo_entrega').value;
        console.log('Valor de tipo_entrega:', tipo_entrega); 
        if(tipo_entrega === '1'){
            cantidad_entregada.style.display = "block";
        } else {
           
           cantidad_entregada.style.display = "none";
        }
    }
</script>    


