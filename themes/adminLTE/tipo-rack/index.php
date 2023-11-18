<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoRackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipo Racks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-rack-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tipo Rack', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_rack',
            'numero_rack',
            'descripcion',
            'medida_ancho',
            'media_alto',
            //'total_peso',
            //'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
