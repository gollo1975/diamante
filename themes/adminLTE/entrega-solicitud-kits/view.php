<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaSolicitudKits */

$this->title = $model->id_entrega_kits;
$this->params['breadcrumbs'][] = ['label' => 'Entrega Solicitud Kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="entrega-solicitud-kits-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_entrega_kits], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_entrega_kits], [
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
            'id_entrega_kits',
            'id_solicitud',
            'id_presentacion',
            'id_solicitud_armado',
            'total_unidades_entregadas',
            'fecha_solicitud',
            'fecha_hora_proceso',
            'proceso_cerrado',
            'autorizado',
            'numero_entrega',
            'observacion',
            'cantidad_despachada',
            'user_name',
        ],
    ]) ?>

</div>
