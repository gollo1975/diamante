<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MotivoTerminacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Motivo Terminacions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivo-terminacion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Motivo Terminacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_motivo_terminacion',
            'motivo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
