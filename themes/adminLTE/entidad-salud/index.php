<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntidadSaludSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Entidad Saluds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entidad-salud-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Entidad Salud', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_entidad_salud',
            'entidad_salud',
            'estado',
            'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
