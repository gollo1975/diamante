<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Contratos */

$this->title = $model->id_contrato;
$this->params['breadcrumbs'][] = ['label' => 'Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contratos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_contrato], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_contrato], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_contrato',
            'id_empleado',
            'nit_cedula',
            'id_tiempo',
            'id_tipo_contrato',
            'id_cargo',
            'descripcion',
            'fecha_inicio',
            'fecha_final',
            'id_tipo_salario',
            'salario',
            'aplica_auxilio_transporte',
            'horario_trabajo',
            'funciones',
            'id_tipo_cotizante',
            'id_subtipo_cotizante',
            'id_configuracion_eps',
            'id_entidad_salud',
            'id_configuracion_pension',
            'id_entidad_pension',
            'id_caja_compensacion',
            'id_cesantia',
            'id_arl',
            'ultimo_pago_nomina',
            'ultima_pago_prima',
            'ultima_pago_cesantia',
            'ultima_pago_vacacion',
            'ibp_cesantia_inicial',
            'ibp_prima_inicial',
            'ibp_recargo_nocturno',
            'id_motivo_terminacion',
            'contrato_activo',
            'codigo_municipio_laboral',
            'codigo_municipio_contratado',
            'id_centro_trabajo',
            'id_grupo_pago',
            'fecha_preaviso',
            'dias_contrato',
            'generar_liquidacion',
            'observacion',
            'user_name',
            'fecha_hora_registro',
            'user_name_editado',
            'fecha_hora_editado',
        ],
    ]) ?>

</div>
