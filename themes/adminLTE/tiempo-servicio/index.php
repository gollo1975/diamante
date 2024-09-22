<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TiempoServicioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tiempo Servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tiempo-servicio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tiempo Servicio', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_tiempo',
            'tiempo_servicio',
            'horas_dia',
            'pago_incapacidad_general',
            'pago_incapacidad_laboral',
            //'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
