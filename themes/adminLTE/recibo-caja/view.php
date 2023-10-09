<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ReciboCaja */

$this->title = $model->id_recibo;
$this->params['breadcrumbs'][] = ['label' => 'Recibo Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="recibo-caja-view">

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
            'numero_recibo',
            'id_cliente',
            'id_tipo',
            'fecha_pago',
            'fecha_proceso',
            'valor_pago',
            'autorizado',
            'codigo_municipio',
            'codigo_banco',
            'observacion',
            'user_name',
        ],
    ]) ?>

</div>
