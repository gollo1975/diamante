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

//model
$this->title = 'Nueva';
$this->params['breadcrumbs'][] = ['label' => 'Factura de venta', 'url' => ['index']];
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
            'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label'],
            'options' => []
        ],
        ]);
$cliente = ArrayHelper::map(app\models\Clientes::find()->where(['=','estado_cliente', 0])
                                                 ->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
?>
<div class="factura-venta-factura_libre">
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4>FACTURA DE VENTA</h4>

        </div>

        <div class="panel-body">
            <div class="row">

                <div class="row">
                <?= $form->field($model, 'id_cliente')->widget(Select2::classname(), [
                        'data' => $cliente,
                        'options' => ['prompt' => 'Seleccione...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>

                </div>
                <div class="row">
                    <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
                </div>    
            <div class="panel-footer text-right">			
                <a href="<?= Url::toRoute(["factura-venta/index", 'id' => $model->id_factura]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
            </div>
        </div>
    </div>
    </div>
</div>    
<?php $form->end() ?>    