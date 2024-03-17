<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_estudio;
$view = 'proveedor-estudios';
$empresa = app\models\MatriculaEmpresa::findOne(1);
if($model->aprobado == 0  && $model->validado == 0 && $model->total_porcentaje == null){
}else{
    if($model->aprobado == 0  && $model->validado == 1 && $model->total_porcentaje <> null){
        Yii::$app->getSession()->setFlash('warning', 'Este proveedor requiere de una AUTORIZACION PREVIA por los administradores. No cumplio con los estandares de calificacion.');
    }else{
        if($model->aprobado == 0  && $model->validado == 1 && $model->total_porcentaje == 0){
            Yii::$app->getSession()->setFlash('warning', 'Este proveedor requiere de una AUTORIZACION PREVIA por los administradores. No cumplio con los estandares de calificacion.');
        }else{
         Yii::$app->getSession()->setFlash('success', 'El proveedor cumplico con los requisisto de la empresa. Continuar con el proceso.');
        } 
    }
}

?>
<div class="proveedor-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_estudio'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }
        if($model->proceso_cerrado){?>
            <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 16, 'codigo' => $model->id_estudio,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_estudio', 'id' => $model->id_estudio, 'token' => $token], ['class' => 'btn btn-default btn-sm']);?>            
        <?php }?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
           ESTUDIO DE PROVEEDORES
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Id</th>
                    <td><?= $model->id_estudio ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo documento:</th>
                    <td><?= $model->tipoDocumento->tipo_documento ?></td>
                    <th style='background-color:#F0F3EF;'>Nit/Cedula</th>
                    <td><?= $model->nit_cedula ?></td>
                    <th style='background-color:#F0F3EF;' >Dv</th>
                    <td><?= $model->dv ?></td>
                      <th style='background-color:#F0F3EF;' >Proveedor</th>
                    <td><?= $model->nombre_completo ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Aprobado</th>
                    <td><?= $model->aprobadoEstudio ?></td>
                    <th style='background-color:#F0F3EF;'>Validado:</th>
                    <td><?= $model->validadoEstudio ?></td>
                    <th style='background-color:#F0F3EF;'>Total porcentaje</th>
                    <td><?= $model->total_porcentaje ?></td>
                    <th style='background-color:#F0F3EF;' >Fecha registro</th>
                    <td><?= $model->fecha_registro ?></td>
                      <th style='background-color:#F0F3EF;' >Usuario</th>
                    <td><?= $model->user_name ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Observacion</th>
                    <td colspan="10"><?= $model->observacion ?></td>
                </tr>
            </table>
        </div>
    </div>
    
    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
    ],
    ]);?>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listadorequisitos" aria-controls="listadorequisitos" role="tab" data-toggle="tab">Listado requisitos  <span class="badge"><?= count($listado_documento) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="listadorequisitos">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Nombre del requisito  </th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Porcentaje</th> 
                                          <th scope="col" style='background-color:#B9D5CE;'>Validado</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Aplica</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Documento Fisico</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'>Cumple</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Observación</th>
                                        <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($listado_documento as $lista):?>
                                        <tr style='font-size:90%;'>
                                            <td><?= $lista->id_requisito?></td>
                                            <td><?= $lista->requisito?></td>
                                            <td style="text-align: right"><?= $lista->porcentaje?>%</td>
                                            <?php if($lista->validado == 0){?>
                                                <td><?= $lista->validadoEstudio?></td>
                                            <?php }else{?>
                                                <td style="background-color:#9DEDE2;"><?= $lista->validadoEstudio?></td>
                                            <?php }?>    
                                            <td><select name="aplica_requisito[]" >
                                                <option value="<?= $lista->aplica ?>"><?= $lista->aplicaEstudio?></option>
                                                <option value="0">NO</option>
                                                <option value="1">SI</option>
                                            </select></td>
                                            <td><select name="documento_fisico[]" >
                                                <option value="<?= $lista->documento_fisico ?>"><?= $lista->documentoFisico?></option>
                                                <option value="0">NO</option>
                                                <option value="1">SI</option>
                                            </select></td>
                                            <td><select name="cumple[]" >
                                                <option value="<?= $lista->cumplio ?>"><?= $lista->cumpleRequisito?></option>
                                                <option value="0">NO</option>
                                                <option value="1">SI</option>
                                            </select></td>
                                            <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="observacion[]" value="<?= $lista->observacion ?>" size ="45" maxlength="40" > </td> 
                                            <input type="hidden" name="listado[]" value="<?= $lista->id?>">
                                            <td ><input type="checkbox" name="registroeliminar[]" value="<?= $lista->id ?>"></td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>      
                            </table>
                            <?php if($model->aprobado == 0){?>
                                <div class="panel-footer text-right" >
                                   <?php if(count($listado_documento) <= 0){
                                        echo Html::a('<span class="glyphicon glyphicon-import"></span> Cargar requisitos', ['cargar_requisitos', 'id' => $model->id_estudio, 'token' => $token], ['class' => 'btn btn-primary btn-sm']);            
                                    }else{?>
                                        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'requisitos'])?>
                                        <?= Html::submitButton("<span class='glyphicon glyphicon-trash'></span> Eliminar", ["class" => "btn btn-danger btn-sm", 'name' => 'eliminardetalles']) ?>
                                    <?php }?>
                                    
                                </div>   
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            <!--TERMINA TABS-->
        </div>
    </div>  
    <?php ActiveForm::end(); ?>
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