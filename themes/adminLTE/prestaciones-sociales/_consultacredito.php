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

$this->title = 'Creditos';
$this->params['breadcrumbs'][] = $this->title;
?>
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
$valor = count($credito);
?>
<div class="panel panel-success">
    <div class="panel-heading">
        Listado de creditos <span class="badge"><?= $valor?></span>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr style='font-size:85%;'>
                <th scope="col">Nro crédito</th>
                <th scope="col">Tipo credito</th>
                <th scope="col">Valor credito</th>
                <th scope="col">Valor cuota</th>
                <th scope="col">Saldo credito</th>
                <th scope="col">Fecha inicio</th>
                <th scope="col">Usuario</th>
                  
                <th scope="col"><input type="checkbox" onclick="marcar(this);"/></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($credito as $val): ?>
            <tr style='font-size:85%;'>
                <td><?= $val->id_credito ?></td>
                <td><?= $val->codigoCredito->nombre_credito ?></td>
                <td><?= '$'.number_format($val->valor_credito,0) ?></td> 
                <td><?= '$'.number_format($val->valor_cuota,0) ?></td>
                <td><?= '$'.number_format($val->saldo_credito,0) ?></td> 
                <td><?= $val->fecha_inicio?></td>
                <td><?= $val->user_name?></td>
                <td><input type="checkbox" name="idcredito[]" value="<?= $val->id_credito ?>"></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="panel-footer text-right">
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['prestaciones-sociales/view', 'id' => $id, 'pagina' => $pagina], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-success btn-sm",]) ?>
    </div>
</div>
</table>

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