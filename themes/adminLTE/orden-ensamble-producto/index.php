<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdenEnsambleProductoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orden Ensamble Productos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orden-ensamble-producto-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Orden Ensamble Producto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_ensamble',
            'id_orden_produccion',
            'numero_orden_ensamble',
            'id_grupo',
            'numero_lote',
            //'id_etapa',
            //'fecha_proceso',
            //'fecha_hora_registro',
            //'user_name',
            //'peso_neto',
            //'observacion',
            //'responsable',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
