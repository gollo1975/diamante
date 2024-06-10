<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CierreCaja */

$this->title = $model->id_cierre;
$this->params['breadcrumbs'][] = ['label' => 'Cierre Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cierre-caja-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_cierre], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_cierre], [
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
            'id_cierre',
            'id_punto',
            'fecha_inicio',
            'fecha_corte',
            'total_remision',
            'total_factura',
            'total_efectivo_factura',
            'total_efectivo_remision',
            'total_transacion_factura',
            'total_transacion_remision',
            'user_name',
            'fecha_hora_registro',
        ],
    ]) ?>

</div>
