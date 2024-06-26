<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = 'TRASLADO DE REFERENCIAS (Punto de venta saliente)';
$this->params['breadcrumbs'][] = ['label' => 'Traslasdo de referencias', 'url' => ['view_traslado', 'id' => $id, 'id_punto' => $id_punto, 'sw' => $sw]];
$this->params['breadcrumbs'][] = $id_punto;
$conPunto = ArrayHelper::map(\app\models\PuntoVenta::find()->andWhere(['<>','id_punto', 1])->orderBy ('nombre_punto ASC')->all(), 'id_punto', 'nombre_punto');
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <div class="modal-body">
        <p>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view_traslado', 'id' => $id, 'id_punto' => $id_punto, 'sw' => $sw], ['class' => 'btn btn-primary btn-sm']) ?>
        </p>
        
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["inventario-punto-venta/buscar_punto_venta", 'id' => $id, 'id_punto' => $id_punto, 'sw' => $sw]),
            "enableClientValidation" => true,
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                            'labelOptions' => ['class' => 'col-sm-4 control-label'],
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
                    <?= $formulario->field($form, 'punto')->widget(Select2::classname(), [
                        'data' => $conPunto,
                        'options' => ['prompt' => 'Seleccione...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                     ]); ?> 
                </div>   
                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                     <a align="right" href="<?= Url::toRoute(["inventario-punto-venta/buscar_punto_venta", 'id' => $id, 'id_punto' => $id_punto, 'sw' => $sw]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                    <?php 
                    if($operacion){?>
                        Productos <span class="badge"><?= $pagination->totalCount ?></span>
                    <?php }?>
                </div>
                <div class="panel-body">
                     <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Referencia</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Talla</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Color</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Punto de venta</th>
                            <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($operacion){
                                foreach ($operacion as $val): ?>
                                    <tr style="font-size: 85%;">
                                        <td><?= $val->codigo_producto ?></td>
                                        <td><?= $val->inventario->nombre_producto ?></td>
                                        <td><?= $val->stock_punto ?></td>
                                        <td><?= $val->talla->nombre_talla ?></td>
                                        <td><?= $val->color->colores ?></td>
                                        <td><?= $val->punto->nombre_punto ?></td>
                                        <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="nuevo_traslado[]" value="<?= $val->id_detalle ?>"></td> 
                                    </tr>
                                <?php endforeach;
                            }   ?>
                        <tbody>
                    </table>
                </div>
                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar datos", ["class" => "btn btn-success btn-sm", 'name' => 'traslado_punto_venta']) ?>
                </div>

            </div>
            <?php
            if($operacion){?>
                <?= LinkPager::widget(['pagination' => $pagination]) ?>
            <?php }?>
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
