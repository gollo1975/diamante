<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PagoAdicionalPermanenteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pago Adicional Permanentes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pago-adicional-permanente-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pago Adicional Permanente', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_pago_permanente',
            'id_empleado',
            'codigo_salario',
            'id_contrato',
            'id_grupo_pago',
            //'id_pago_fecha',
            //'fecha_corte',
            //'tipo_adicion',
            //'valor_adicion',
            //'permanente',
            //'aplicar_dia_laborado',
            //'aplicar_prima',
            //'aplicar_cesantias',
            //'estado_registro',
            //'estado_periodo',
            //'detalle',
            //'fecha_creacion',
            //'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
