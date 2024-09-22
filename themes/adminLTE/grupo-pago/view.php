<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoPago */

$this->title = $model->id_grupo_pago;
$this->params['breadcrumbs'][] = ['label' => 'Grupo Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="grupo-pago-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_grupo_pago], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_grupo_pago], [
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
            'id_grupo_pago',
            'grupo_pago',
            'codigo_departamento',
            'codigo_municipio',
            'id_sucursal',
            'ultimo_pago_nomina',
            'ultimo_pago_prima',
            'ultimo_pago_cesantia',
            'limite_devengado',
            'dias_pago',
            'estado',
            'observacion',
            'user_name',
            'fecha_hora_registro',
        ],
    ]) ?>

</div>
