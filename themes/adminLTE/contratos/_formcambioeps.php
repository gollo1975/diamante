<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\EntidadSalud;

$this->title = 'CAMBIO DE EPS';
$this->params['breadcrumbs'][] = ['label' => 'Cambio de eps', 'url' => ['index']];
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
$eps_anterior = EntidadSalud::find()->where(['=','id_entidad_salud', $contrato->id_entidad_salud])->one();
$eps_nueva = ArrayHelper::map(EntidadSalud::find()->orderBy('entidad_salud ASC')->all(), 'id_entidad_salud', 'entidad_salud');
?>
        <div class="panel panel-success">
            <div class="panel-heading">
                Cambio Eps
            </div>
            <div class="panel-body">
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-hover">
                        <tr style ='font-size:85%;'>
                            <th><?= Html::activeLabel($model, 'Nro_contrato') ?> :</th>
                            <td><?= Html::encode($model->id_contrato) ?></td>
                            <th><?= Html::activeLabel($model, 'Entidad_Salud_anterior') ?> :</th>
                            <td><?= $eps_anterior->entidad_salud ?></td>
                        <tr> 
                    </table>
                </div>     
                <div class="row">
                    <?= $form->field($model, 'id_entidad_salud_nueva')->widget(Select2::classname(), [
                     'data' => $eps_nueva,
                     'options' => ['placeholder' => 'Seleccione la eps '],
                     'pluginOptions' => [
                     'allowClear' => true ]]);
                    ?>
                </div>
                <div class="row">
                     <?= $form->field($model, 'observacion')->textarea(['maxlength' => true]) ?>
                </div>
                <div class="panel-footer text-right">			
                    <a href="<?= Url::toRoute(['contratos/viewparameters', 'id' => $id]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm", 'crear_cambios']) ?>
                </div>
            </div>
        </div>
<?php $form->end() ?>     
