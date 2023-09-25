<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FacturaVenta */

$this->title = $model->id_factura;
$this->params['breadcrumbs'][] = ['label' => 'Factura Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="factura-venta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_factura], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_factura], [
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
            'id_factura',
            'id_pedido',
            'id_cliente',
            'id_tipo_factura',
            'numero_factura',
            'nit_cedula',
            'dv',
            'cliente',
            'numero_resolucion',
            'desde',
            'hasta',
            'consecutivo',
            'fecha_inicio',
            'fecha_vencimiento',
            'fecha_generada',
            'fecha_enviada',
            'subtotal_factura',
            'descuento',
            'impuesto',
            'total_factura',
            'porcentaje_iva',
            'porcentaje_rete_iva',
            'porcentaje_rete_fuente',
            'valor_retencion',
            'valor_reteiva',
            'porcentaje_descuento',
            'saldo_factura',
            'forma_pago',
            'plazo_pago',
            'autorizado',
            'user_name',
        ],
    ]) ?>

</div>
