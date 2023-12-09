<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'MOVER POSICIONES';
$this->params['breadcrumbs'][] = ['label' => 'Mover posiciones', 'url' => ['cambiar_almacenamiento_rack', 'id_rack' => $id_rack]];
$this->params['breadcrumbs'][] = $id_rack;
?>
<div class="btn-group btn-sm" role="group">    
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['mover_posiciones'], ['class' => 'btn btn-primary btn-sm']) ?>
 </div>  
<?php $form = ActiveForm::begin([
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
$conPosicion = ArrayHelper::map(app\models\Posiciones::find()->all(), 'id_posicion', 'posicion');
$tipo_rack = ArrayHelper::map(app\models\TipoRack::find()->all(), 'id_rack', 'descripcion');
$conPiso = ArrayHelper::map(app\models\Pisos::find()->all(), 'id_piso', 'descripcion');
?>

<div class="panel panel-success">
    <div class="panel-heading">
        SELECCIONE EL PROCESO
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'nuevo_piso')->dropDownList($conPiso,['prompt'=>'Seleccione el piso...', 'onchange'=>' $.get( "'.Url::toRoute('almacenamiento-producto/llenaracks').'", { id: $(this).val() } ) .done(function( data ) {
            $( "#'.Html::getInputId($model, 'nuevo_rack',['required', 'class' => 'select-2']).'" ).html( data ); });']); ?>
            <?= $form->field($model, 'nuevo_rack')->dropDownList(['prompt' => 'Seleccione...']) ?>
        </div>
        
        <div class="row">
            <?= $form->field($model, 'nueva_posicion')->widget(Select2::classname(), [
                'data' => $conPosicion,
                'options' => ['prompt' => 'Seleccione un registro ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>   
        </div>
    </div> 
    <div class="panel panel-success">
        <div class="panel-heading">
            LISTADO DE PRODUCTOS POR RACKS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="font-size: 90%;">
                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Piso</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Rack</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Posicion</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Capacidad</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                         <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                        <th scope="col" style='background-color:#B9D5CE;'></th>
                    </tr>
                </thead>
                <?php
                    foreach ($conRacks as $val): ?>
                        <tr style="font-size: 90%;">
                            <td> <?= $val->codigo_producto ?></td>
                            <td> <?= $val->producto ?></td>
                            <td> <?= $val->piso->descripcion ?></td>
                            <td> <?= $val->rack->descripcion ?></td>
                            <td> <?= $val->posicion->posicion ?></td>
                            <td style="text-align: right"> <?= ''.number_format($val->rack->capacidad_instalada,0) ?></td>
                            <td style="text-align: right"> <?= ''.number_format($val->rack->capacidad_actual,0) ?></td>
                            <td> <?= $val->fecha_almacenamiento ?></td>
                            <td style="text-align: right"> <?= ''.number_format($val->cantidad,0) ?></td>
                            <td style= 'width: 20px;'><input type="checkbox" name="seleccione_item[]" value="<?= $val->id ?>"></td> 
                        </tr>
                    <?php endforeach; ?>
  
            </table> 
            <div class="panel-footer text-right">
                <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar", ["class" => "btn btn-success btn-sm", 'name' => 'cambiar_posicion']) ?>     
            </div>    
        </div>
    </div>    
</div>    
<?php $form->end() ?>  