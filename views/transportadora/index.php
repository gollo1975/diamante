<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransportadoraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transportadoras';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transportadora-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Transportadora', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_transportadora',
            'tipo_documento',
            'nit_cedula',
            'dv',
            'razon_social',
            //'direccion',
            //'email_transportadora:email',
            //'telefono',
            //'celular',
            //'codigo_departamento',
            //'codigo_municipio',
            //'contacto',
            //'celular_contacto',
            //'user_name',
            //'fecha_registro',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
