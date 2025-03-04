<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Ordenproduccion;
use app\models\TiposMaquinas;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

$this->title = 'MATERIA DE EMPAQUE';
$this->params['breadcrumbs'][] = ['label' => 'Materias primas', 'url' => ['view', 'id' => $id, 'token' => $token]];
$this->params['breadcrumbs'][] = $id;
?>
    <div class="modal-body">
        <p>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view', 'id' => $id, 'token' => $token], ['class' => 'btn btn-primary btn-sm']) ?>
        </p>
        
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["solicitud-materiales/buscar_material_empaque", 'id' => $id, 'token' => $token, 'id_solicitud' => $id_solicitud, 'id_detalle' => $id_detalle]),
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
                Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
            </div>

            <div class="panel-body" id="filtrocliente">
                <div class="row" >
                    <?= $formulario->field($form, "q")->input("search") ?>
                </div>
                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                     <a align="right" href="<?= Url::toRoute(["solicitud-materiales/buscar_material_empaque", 'id' => $id, 'token' => $token, 'id_solicitud' => $id_solicitud, 'id_detalle' => $id_detalle]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
                </div>
            </div>
        </div>

        <?php $formulario->end() ?>
        
        
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]); ?>
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading">
                    Productos <span class="badge"><?= $pagination->totalCount ?></span>
                </div>
                <div class="panel-body">
                     <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Materia prima</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                             <th scope="col" style='background-color:#B9D5CE;'>Stock gramos</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Aplica iva</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Porcentaje Iva</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Clasificacion</th>
                            <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($operacion as $val): ?>
                        <tr style="font-size: 85%;">
                            <td><?= $val->codigo_materia_prima ?></td>
                            <td><?= $val->materia_prima ?></td>
                            <td><?= $val->stock ?></td>
                            <td><?= $val->stock_gramos ?></td>
                            <td><?= $val->aplicaIva ?></td>
                            <td><?= $val->porcentaje_iva ?>%</td>
                             <td><?= $val->tipoSolicitud->descripcion?></td>
                            <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="nuevo_materia_prima[]" value="<?= $val->id_materia_prima ?>"></td> 
                        </tr>
                        </tbody>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar datos", ["class" => "btn btn-success btn-sm", 'name' => 'guardarmateriaprima']) ?>
                </div>

            </div>
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
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
