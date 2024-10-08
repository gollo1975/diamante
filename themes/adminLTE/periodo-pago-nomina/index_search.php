<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use app\models\PeriodoPago;
use app\models\GrupoPago;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'Periodos de pago';
$this->params['breadcrumbs'][] = $this->title;


?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtro");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista Facturas</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("periodo-pago-nomina/index_search"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$grupo = ArrayHelper::map(GrupoPago::find()->all(), 'id_grupo_pago', 'grupo_pago');
$periodo = ArrayHelper::map(PeriodoPago::find()->all(), 'id_periodo_pago', 'nombre_periodo');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, 'id_grupo_pago')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione el grupo pago ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
             <?= $formulario->field($form, 'id_periodo_pago')->widget(Select2::classname(), [
                'data' => $periodo,
                'options' => ['prompt' => 'Seleccione el periodo ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("periodo-pago-nomina/index_search") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>

<?php $formulario->end() ?>
<?php
    $form = ActiveForm::begin([
                "method" => "post",                            
            ]);
    ?>
<div class="table-responsive">
<div class="panel panel-success ">
    <div class="panel-heading">
        Registros: <?= $pagination->totalCount ?>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>                
                <th scope="col">Codigo</th>
                <th scope="col">Grupo de pago</th>
                <th scope="col">Periodo de pago</th>
                <th scope="col">Ultima pago de nomina</th>                
                <th scope="col">Ultima pago de prima</th>
                <th scope="col">Ultima pago de cesantia</th>
                <td align="center"><input type="checkbox" onclick="marcar(this);"/></td>                              
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>                
                <td><?= $val->id_grupo_pago ?></td>
                <td><?= $val->grupo_pago ?></td>
                <td><?= $val->periodoPago->nombre_periodo ?></td>
                <td><?= $val->ultimo_pago_nomina?></td>
                <td><?= $val->ultimo_pago_prima ?></td>
                <td><?= $val->ultimo_pago_cesantia ?></td>
                <td align="center"><input type="checkbox" name="id_grupo_pago[]" value="<?= $val->id_grupo_pago ?>"></td>
            </tr>            
            </tbody>            
            <?php endforeach; ?>
        </table>    
        <div class="panel-footer text-right" >            
                
                <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Excel", ['name' => 'excel','class' => 'btn btn-primary ']); ?>                
                <?= Html::submitButton("<span class='glyphicon glyphicon-share'></span> Cesantia", ['name' => 'crear_periodo_cesantia','class' => 'btn btn-primary ']); ?>
                <?= Html::submitButton("<span class='glyphicon glyphicon-share'></span> Prima", ['name' => 'crear_periodo_prima','class' => 'btn btn-primary ']); ?>
                <?= Html::submitButton("<span class='glyphicon glyphicon-share'></span> Nomina", ['name' => 'crear_periodo_nomina','class' => 'btn btn-primary ']); ?>
           
        </div>
    </div>
     <?php $form->end() ?>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>

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