<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GrupoPagoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grupo Pagos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupo-pago-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Grupo Pago', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_grupo_pago',
            'grupo_pago',
            'codigo_departamento',
            'codigo_municipio',
            'id_sucursal',
            //'ultimo_pago_nomina',
            //'ultimo_pago_prima',
            //'ultimo_pago_cesantia',
            //'limite_devengado',
            //'dias_pago',
            //'estado',
            //'observacion',
            //'user_name',
            //'fecha_hora_registro',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
