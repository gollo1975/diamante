<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AlmacenamientoProducto */

$this->title = $model->id_almacenamiento;
$this->params['breadcrumbs'][] = ['label' => 'Almacenamiento Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="almacenamiento-producto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_almacenamiento], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_almacenamiento], [
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
            'id_almacenamiento',
            'id_orden_produccion',
            'id_documento',
            'id_rack',
            'codigo_producto',
            'nombre_producto',
            'unidades_producidas',
            'unidades_almacenadas',
            'unidades_faltantes',
            'fecha_almacenamiento',
            'user_name',
        ],
    ]) ?>

</div>
