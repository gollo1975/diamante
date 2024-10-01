<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\EntidadPension;

$this->title = 'CAMBIO DE PENSION';
$this->params['breadcrumbs'][] = ['label' => 'Cambio de pension', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
        'fieldConfig' => [
            'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-4 control-label'],
            'options' => []
        ],
    ]);
$pension_anterior = EntidadPension::find()->where(['=','id_entidad_pension', $contrato->id_entidad_pension])->one();
$pension_nueva = ArrayHelper::map(EntidadPension::find()->orderBy('entidad ASC')->all(), 'id_entidad_pension', 'entidad');
?>
        <div class="panel panel-success">
            <div class="panel-heading">
                Cambio pensión
            </div>
            <div class="panel-body">
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-hover">
                        <tr style ='font-size:85%;'>
                            <th><?= Html::activeLabel($model, 'Nro_contrato') ?> :</th>
                            <td><?= Html::encode($model->id_contrato) ?></td>
                            <th><?= Html::activeLabel($model, 'Entidad_Pension_anterior') ?> :</th>
                            <td><?= $pension_anterior->entidad ?></td>
                        <tr> 
                    </table>
                </div>     
                <div class="row">
                    <?= $form->field($model, 'id_entidad_pension_nueva')->widget(Select2::classname(), [
                     'data' => $pension_nueva,
                     'options' => ['placeholder' => 'Seleccione.... '],
                     'pluginOptions' => [
                     'allowClear' => true ]]);
                    ?>
                </div>
                <div class="row">
                     <?= $form->field($model, 'observacion')->textarea(['maxlength' => true]) ?>
                </div>
                <div class="panel-footer text-right">			
                    <a href="<?= Url::toRoute(['contratos/viewparameters', 'id' => $id]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
                </div>
            </div>
        </div>
<?php $form->end() ?>     
