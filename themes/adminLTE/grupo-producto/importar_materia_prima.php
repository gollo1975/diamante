<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = 'Inventario materia prima';
$this->params['breadcrumbs'][] = ['label' => 'Inventario materia prima', 'url' => ['index_producto_configuracion', 'id_grupo' => $id_grupo, 'sw' => $sw ]];
$this->params['breadcrumbs'][] = $id_grupo;
$clasificar = ArrayHelper::map(app\models\TipoSolicitud::find()->orderBy('descripcion ASC')->all(), 'id_solicitud', 'descripcion');
?>
    <div class="modal-body">
        <p>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index_producto_configuracion', 'id_grupo' => $id_grupo, 'sw' => $sw], ['class' => 'btn btn-primary btn-sm']) ?>
        </p>
        
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["grupo-producto/buscarmateriaprima", 'id_grupo' => $id_grupo, 'sw' => $sw]),
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
                     <?= $formulario->field($form, 'clasificacion')->widget(Select2::classname(), [
                     'data' => $clasificar,
                     'options' => ['prompt' => 'Seleccione un registro ...'],
                     'pluginOptions' => [
                         'allowClear' => true
                     ],
                 ]); ?>
             </div>  
                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                    <a align="right" href="<?= Url::toRoute(["grupo-producto/buscarmateriaprima", 'id_grupo' => $id_grupo, 'sw' => $sw]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                    Materiales <span class="badge"><?= count($operacion)?></span>
                </div>
                <div class="panel-body">
                     <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col" style='background-color:#B9D5CE;'>Codigo material</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Materia prima</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Aplica iva</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Porcentaje Iva</th>
                            <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($operacion as $val): ?>
                        <tr style="font-size: 85%;">
                            <td><?= $val->codigo_materia_prima ?></td>
                            <td><?= $val->materia_prima ?></td>
                            <td><?= $val->stock ?></td>
                            <td><?= $val->aplicaIva ?></td>
                            <td><?= $val->porcentaje_iva ?>%</td>
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
