<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\TipoPagoCredito;
use kartik\select2\Select2;
use kartik\date\DatePicker;

$this->title = 'Refinanciar credito';
$this->params['breadcrumbs'][] = ['label' => 'Refinanciar creditos', 'url' => ['view','id' => $credito->id_credito, 'token' =>$token]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
        'fieldConfig' => [
            'template' => '{label}<div class="col-sm-6 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label'],
            'options' => []
        ],
    ]); ?>

<?php
$tipopagocredito = ArrayHelper::map(TipoPagoCredito::find()->where(['=','estado', 1])->orderBy('descripcion ASC')->all(), 'id_tipo_pago', 'descripcion');
?>
        <div class="panel panel-success">
            <div class="panel-heading">
                Información del credito...
            </div>
            <div class="panel-body">
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <th><?= Html::activeLabel($credito, 'Numero_Credito') ?>:</th>
                            <td><?= Html::encode($credito->id_credito) ?></td>
                             <th><?= Html::activeLabel($credito, 'Valor_Cuota') ?>:</th>
                            <td><?= Html::encode('$'.number_format($credito->valor_cuota,2)) ?></td>
                             <th><?= Html::activeLabel($credito, 'Saldo_Actual') ?>:</th>
                            <td><?= Html::encode('$'.number_format($credito->saldo_credito,2)) ?></td>
                        </tr>   
                    </table>
                </div>
                <div class="row">
                     <?= $form->field($model,'adicionar_valor')->textInput(['maxlength' => true]) ?>
                </div>
                  <div class="row">
                     <?= $form->field($model,'numero_cuotas')->textInput(['maxlength' => true]) ?>
                </div>
                  <div class="row">
                     <?= $form->field($model,'numero_cuota_actual')->textInput(['maxlength' => true, 'value' => 0]) ?>
                </div>
                <div class="row">
                     <?= $form->field($model, 'nota')->textarea(['maxlength' => true]) ?>
                </div>
               
                <div class="panel-footer text-right">			
                    <a href="<?= Url::toRoute(['credito/view' , 'id' => $credito->id_credito, 'token' =>$token]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
                </div>
            </div>
        </div>
<?php $form->end() ?>     
