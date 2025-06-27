<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntregaSolicitudKitsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Entrega Solicitud Kits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entrega-solicitud-kits-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Entrega Solicitud Kits', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_entrega_kits',
            'id_solicitud',
            'id_presentacion',
            'id_solicitud_armado',
            'total_unidades_entregadas',
            //'fecha_solicitud',
            //'fecha_hora_proceso',
            //'proceso_cerrado',
            //'autorizado',
            //'numero_entrega',
            //'observacion',
            //'cantidad_despachada',
            //'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
