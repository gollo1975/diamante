<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$terminos = ArrayHelper::map(\app\models\IncotermFactura::find()->all(), 'id_inconterm', 'concepto');
$paises = ArrayHelper::map(\app\models\Pais::find()->all(), 'codigo_pais', 'pais');
$municipio = ArrayHelper::map(app\models\Municipios::find()->orderBy('municipio ASC')->all(), 'codigo_municipio', 'municipio');
$medida = ArrayHelper::map(app\models\MedidaProductoTerminado::find()->orderBy('descripcion ASC')->all(), 'id_medida_producto', 'descripcion');
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
        ]);?>
   <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left ">
                  Terminos factura de exportacion.  
                </div>
                <div class="panel-body">
                    
                    
                    <div class="row">
                        <?= $form->field($model, 'id_inconterm')->dropdownList($terminos, ['prompt' => 'Seleccione...', 'required' => true]) ?>
                        <?= $form->field($model, 'medio_transporte')->dropdownList(['0' => 'TERRESTE', '1' => 'MARITIMO','2' => 'AEREO'], ['prompt' => 'Seleccione...']) ?>
                    </div>
                    
                    <div class="row">
                        <?= $form->field($model, 'ciudad_origen')->dropDownList($municipio, ['prompt' => 'Seleccione...']) ?>
                         <?= $form->field($model, 'ciudad_destino')->input("text", ["maxlength" => 30]) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'peso_bruto')->input("text", ["maxlength" => 11]) ?>
                        <?= $form->field($model, 'peso_neto')->input("text", ["maxlength" => 11]) ?>
                      
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'id_medida_producto')->dropdownList($medida, ['prompt' => 'Seleccione...']) ?>
                         <?= $form->field($model, 'id_pais')->dropdownList($paises, ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="panel-footer text-right">			
                         <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'crear_terminos_factura']) ?>                    
                   </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

