<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\AreaEmpresa;
use app\models\TipoSolicitud;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = 'Solicitud de compras';
$this->params['breadcrumbs'][] = ['label' => 'Solicitud de compras', 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = $id;
$tipo = ArrayHelper::map(TipoSolicitud::find()->orderBy ('descripcion ASC')->all(), 'id_solicitud', 'descripcion');
$area = ArrayHelper::map(AreaEmpresa::find()->orderBy ('descripcion ASC')->all(), 'id_area', 'descripcion');
?>
    <div class="modal-body">
        <p>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view', 'id' => $id, 'token' => $token], ['class' => 'btn btn-primary btn-sm']) ?>
        </p>
        
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["orden-compra/importarsolicitud", 'id' => $id, 'token' => $token,'id_solicitud' => $id_solicitud]),
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
                     <?= $formulario->field($form, 'q')->widget(Select2::classname(), [
                'data' => $tipo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
                </div>
                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                    <a align="right" href="<?= Url::toRoute(["orden-compra/importarsolicitud", 'id' => $id, 'token' => $token, 'id_solicitud' => $id_solicitud]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                    Operaciones <span class="badge"><?= $pagination->totalCount ?></span>
                </div>
                <div class="panel-body">
                     <table class="table table-bordered table-hover">
                        <thead>
                            <tr style="font-size: 90%;">
                            <th scope="col" style='background-color:#B9D5CE;'>Id</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Numero</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Tipo solicitud</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Area empresa</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Iva</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Total solicitud</th>
                            <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($operacion as $val): ?>
                        <tr style="font-size: 90%;">
                             <td><?= $val->id_solicitud_compra ?></td>
                            <td><?= $val->numero_solicitud ?></td>
                            <td><?= $val->solicitud->descripcion ?></td>
                            <td><?= $val->area->descripcion ?></td>
                            <td><?= $val->fecha_entrega ?></td>
                            <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                            <td style="text-align: right"><?= ''.number_format($val->total_impuesto,0) ?></td>
                            <td style="text-align: right"><?= ''.number_format($val->total,0) ?></td>
                            <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="solicitud_compras[]" value="<?= $val->id_solicitud_compra ?>"></td> 
                        </tr>
                        </tbody>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm", 'name' => 'enviarsolicitudcompras']) ?>
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
