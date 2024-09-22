<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoContratoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipo Contratos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-contrato-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tipo Contrato', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_tipo_contrato',
            'contrato',
            'prorroga',
            'numero_prorrogas',
            'prefijo',
            //'id_configuracion_prefijo',
            //'abreviatura',
            //'estado',
            //'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
