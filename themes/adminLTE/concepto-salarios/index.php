<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConceptoSalariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Concepto Salarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="concepto-salarios-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Concepto Salarios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'codigo_salario',
            'nombre_concepto',
            'compone_salario',
            'inicio_nomina',
            'aplica_porcentaje',
            //'porcentaje',
            //'porcentaje_tiempo_extra',
            //'prestacional',
            //'ingreso_base_prestacional',
            //'ingreso_base_cotizacion',
            //'debito_credito',
            //'adicion',
            //'auxilio_transporte',
            //'concepto_incapacidad',
            //'concepto_pension',
            //'concepto_salud',
            //'concepto_vacacion',
            //'provisiona_vacacion',
            //'provisiona_indemnizacion',
            //'tipo_adicion',
            //'recargo_nocturno',
            //'hora_extra',
            //'concepto_comision',
            //'concepto_licencia',
            //'fsp',
            //'concepto_prima',
            //'concepto_cesantias',
            //'intereses',
            //'fecha_creacion',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
