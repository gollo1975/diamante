<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\TipoPagoCredito;
use kartik\select2\Select2;
use kartik\date\DatePicker;

$this->title = 'Abono a creditos';
$this->params['breadcrumbs'][] = ['label' => 'Abonos', 'url' => ['view', 'id' => $id_credito, 'token' =>$token]];
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
$tipopagocredito = ArrayHelper::map(TipoPagoCredito::find()->where(['=','estado',1])->orderBy('descripcion ASC')->all(), 'id_tipo_pago', 'descripcion');
?>
        <div class="panel panel-success">
            <div class="panel-heading">
                Información de abonos
            </div>
            <div class="panel-body">
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                             <th><?= Html::activeLabel($credito, 'id_credito') ?></th>
                            <td><?= Html::encode($credito->id_credito) ?></td>
                            <th><?= Html::activeLabel($credito, 'saldo_credito') ?></th>
                            <td><?= Html::encode('$'.number_format($credito->saldo_credito,0)) ?></td>
                        </tr>   
                    </table>
                </div>
                
                <div class="row">
                    <?= $form->field($model, 'id_tipo_pago')->widget(Select2::classname(), [
                    'data' => $tipopagocredito,
                    'options' => ['placeholder' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true ]]);
                    ?>
                </div>
                
                <div class="row">
                     <?= $form->field($model,'valor_abono')->textInput(['maxlength' => true]) ?>
                </div>
                 <div class="row">      
                        <?=  $form->field($model, 'fecha_abono')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
                       ?>
                 </div>

                <div class="row">
                     <?= $form->field($model, 'observacion')->textarea(['maxlength' => true]) ?>
                </div>
               
                <div class="panel-footer text-right">			
                    <a href="<?= Url::toRoute(['credito/view' , 'id' => $credito->id_credito,'token' =>$token]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
                </div>
            </div>
        </div>
<?php $form->end() ?>     
