<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConceptoSalarios */

$this->title = $model->codigo_salario;
$this->params['breadcrumbs'][] = ['label' => 'Concepto Salarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="concepto-salarios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->codigo_salario], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->codigo_salario], [
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
            'codigo_salario',
            'nombre_concepto',
            'compone_salario',
            'inicio_nomina',
            'aplica_porcentaje',
            'porcentaje',
            'porcentaje_tiempo_extra',
            'prestacional',
            'ingreso_base_prestacional',
            'ingreso_base_cotizacion',
            'debito_credito',
            'adicion',
            'auxilio_transporte',
            'concepto_incapacidad',
            'concepto_pension',
            'concepto_salud',
            'concepto_vacacion',
            'provisiona_vacacion',
            'provisiona_indemnizacion',
            'tipo_adicion',
            'recargo_nocturno',
            'hora_extra',
            'concepto_comision',
            'concepto_licencia',
            'fsp',
            'concepto_prima',
            'concepto_cesantias',
            'intereses',
            'fecha_creacion',
        ],
    ]) ?>

</div>
