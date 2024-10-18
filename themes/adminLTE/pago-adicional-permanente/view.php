<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PagoAdicionalPermanente */

$this->title = $model->id_pago_permanente;
$this->params['breadcrumbs'][] = ['label' => 'Pago Adicional Permanentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pago-adicional-permanente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_pago_permanente], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_pago_permanente], [
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
            'id_pago_permanente',
            'id_empleado',
            'codigo_salario',
            'id_contrato',
            'id_grupo_pago',
            'id_pago_fecha',
            'fecha_corte',
            'tipo_adicion',
            'valor_adicion',
            'permanente',
            'aplicar_dia_laborado',
            'aplicar_prima',
            'aplicar_cesantias',
            'estado_registro',
            'estado_periodo',
            'detalle',
            'fecha_creacion',
            'user_name',
        ],
    ]) ?>

</div>
