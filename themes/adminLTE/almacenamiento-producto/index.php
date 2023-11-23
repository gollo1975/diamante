<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AlmacenamientoProductoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Almacenamiento Productos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="almacenamiento-producto-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Almacenamiento Producto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_almacenamiento',
            'id_orden_produccion',
            'id_documento',
            'id_rack',
            'codigo_producto',
            //'nombre_producto',
            //'unidades_producidas',
            //'unidades_almacenadas',
            //'unidades_faltantes',
            //'fecha_almacenamiento',
            //'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
