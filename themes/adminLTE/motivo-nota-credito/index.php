<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MotivoNotaCreditoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Motivo Nota Creditos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivo-nota-credito-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Motivo Nota Credito', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_motivo',
            'cencepto',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
