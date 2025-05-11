<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\Session;
use yii\data\Pagination;
use yii\db\ActiveQuery;

/* @var $this yii\web\View */
/* @var $model app\models\Facturaventadetalle */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Nómina x pagar';
$this->params['breadcrumbs'][] = $this->title;
$empleado = ArrayHelper::map(\app\models\Empleados::find()->where(['=','estado', 0])->orderBy('nombre_completo ASC')->all(), 'id_empleado', 'nombre_completo');
$grupo = ArrayHelper::map(\app\models\GrupoPago::find()->where(['=','estado', 1])->orderBy('grupo_pago ASC')->all(), 'id_grupo_pago', 'grupo_pago');
?>

<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute(["pago-banco/nuevopagoperario", 'id' => $id, 'tipo_proceso' => $tipo_proceso, 'token' => $token]),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    
	'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],
    

]);
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading">
        Parametros de entrada
    </div>
	
    <div class="panel-body" id="buscarmaquina">
        <div class="row" >
            <?= $formulario->field($form, 'nombres')->widget(Select2::classname(), [
                'data' => $empleado,
                'options' => ['prompt' => 'Seleccione el empleado...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>        
            <?= $formulario->field($form, 'grupo')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione el grupo..'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute(["pago-banco/nuevopagoperario", 'id' => $id, 'tipo_proceso' => $tipo_proceso, 'token' => $token]) ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>

<?php $formulario->end() ?>

<?php
$form = ActiveForm::begin([
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

<?php
if ($mensaje != ""){
    ?> <div class="alert alert-danger"><?= $mensaje ?></div> <?php
}
?>

<div class="table table-responsive">
    <div class="panel panel-success ">
        <div class="panel-heading">
            Registros  <span class="badge"><?= count($listadoPago) ?> </span>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th scope="col" style='background-color:#B9D5CE;'>Id</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Nombres</th>
                     <th scope="col" style='background-color:#B9D5CE;'>No cuenta</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Fecha corte</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Grupo de pago</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Valor pagar</th>                    
                    <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($listadoPago as $val):
                        if($tipo_proceso == 1 || $tipo_proceso == 2){?>
                            <tr style="font-size: 85%;">
                                <td><?= $val->id_programacion?></td>
                                <td><?= $val->cedula_empleado?></td>
                                <td><?= $val->empleado->nombre_completo ?></td>
                                 <?php if ($val->empleado->numero_cuenta == 0){?> 
                                     <td style='background-color:#AAE3C6;'><?= $val->empleado->numero_cuenta ?></td> 
                                 <?php }else{?>
                                     <td><?= $val->empleado->numero_cuenta ?></td> 
                                 <?php }?>
                                <td><?= $val->fecha_desde ?></td>
                                <td><?= $val->fecha_hasta ?></td>
                                <td><?= $val->contrato->grupoPago->grupo_pago ?></td>
                                <td style="text-align: right"><?= '$'.number_format($val->total_pagar,0) ?></td>
                                <td style="width: 30px;"><input type="checkbox" name="aplicar_pago[]" value="<?= $val->id_programacion ?>"></td>
                                <input type="hidden" name="tipo_proceso" value="<?= $tipo_proceso ?>">
                            </tr>   
                        <?php }   
                    endforeach; ?>
               </tbody>         
            </table>
        </div>
        <div class="panel-footer text-right">
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['pago-banco/view', 'id' => $id, 'token' => $token], ['class' => 'btn btn-primary btn-sm']) ?>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-success btn-sm",]) ?>
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