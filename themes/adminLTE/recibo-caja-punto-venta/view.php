<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ReciboCajaPuntoVenta */

$this->title = $model->id_recibo;
$this->params['breadcrumbs'][] = ['label' => 'Recibo Caja Punto Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="recibo-caja-punto-venta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_recibo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_recibo], [
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
            'id_recibo',
            'id_remision',
            'id_factura',
            'id_tipo',
            'id_punto',
            'fecha_recibo',
            'numero_recibo',
            'valor_abono',
            'valor_saldo',
            'user_name',
        ],
    ]) ?>

</div>
