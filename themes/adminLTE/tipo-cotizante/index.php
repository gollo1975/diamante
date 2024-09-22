<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoCotizanteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipo Cotizantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-cotizante-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tipo Cotizante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_tipo_cotizante',
            'tipo',
            'codigo_intefaz',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
