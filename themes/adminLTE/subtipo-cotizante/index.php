<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubtipoCotizanteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subtipo Cotizantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subtipo-cotizante-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Subtipo Cotizante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_subtipo_cotizante',
            'descripcion',
            'codigo_interfaz',
            'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
