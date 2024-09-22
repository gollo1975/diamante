<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CajaCompensacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Caja Compensacions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caja-compensacion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Caja Compensacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_caja',
            'caja',
            'telefono',
            'direccion',
            'codigo',
            //'codigo_municipio',
            //'estado',
            //'porcentaje',
            //'user_name',
            //'fecha_hora_registro',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
