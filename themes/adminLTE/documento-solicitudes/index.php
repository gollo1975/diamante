<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentoSolicitudesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documento Solicitudes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documento-solicitudes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Documento Solicitudes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_solicitud',
            'concepto',
            'produccion',
            'logistica',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
