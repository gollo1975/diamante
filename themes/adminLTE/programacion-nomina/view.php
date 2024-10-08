<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProgramacionNomina */

$this->title = $model->id_programacion;
$this->params['breadcrumbs'][] = ['label' => 'Programacion Nominas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="programacion-nomina-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_programacion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_programacion], [
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
            'id_programacion',
            'id_grupo_pago',
            'id_periodo_pago_nomina',
            'id_tipo_nomina',
            'id_contrato',
            'id_empleado',
            'cedula_empleado',
            'salario_contrato',
            'fecha_inicio_contrato',
            'fecha_final_contrato',
            'fecha_ultima_prima',
            'fecha_ultima_cesantia',
            'fecha_ultima_vacacion',
            'fecha_desde',
            'nro_pago',
            'total_devengado',
            'total_pagar',
            'total_deduccion',
            'total_auxilio_transporte',
            'ibc_prestacional',
            'vlr_ibp_medio_tiempo',
            'ibc_no_prestacional',
            'total_licencia',
            'total_incapacidad',
            'ajuste_incapacidad',
            'total_tiempo_extra',
            'total_recargo',
            'fecha_hasta',
            'fecha_real_corte',
            'fecha_creacion',
            'fecha_inicio_vacacion',
            'fecha_final_vacacion',
            'dias_vacacion',
            'horas_vacacion',
            'ibc_vacacion',
            'dias_pago',
            'dia_real_pagado',
            'horas_pago',
            'estado_generado',
            'estado_liquidado',
            'estado_cerrado',
            'factor_dia',
            'salario_medio_tiempo',
            'salario_promedio',
            'dias_ausentes',
            'total_ibc_no_prestacional',
            'user_name',
            'importar_prima',
            'pago_aplicado',
        ],
    ]) ?>

</div>
