<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AuditoriaComprasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auditoria Compras';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auditoria-compras-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Auditoria Compras', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_auditoria',
            'id_orden_compra',
            'id_tipo_orden',
            'id_proveedor',
            'fecha_proceso_compra',
            //'numero_orden',
            //'cerrar_auditoria',
            //'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
