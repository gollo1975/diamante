<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\ConceptoSalarios;
use app\models\ConfiguracionIncapacidad;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConceptoSalariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuracion incapacidades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracion-incapacidad-index">

   <?= $this->render('_search', ['model' => $searchModel]); ?>
   <?php $newButton = Html::a('Nuevo ' . Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['create'], ['class' => 'btn btn-success']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'codigo_incapacidad',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'nombre',
                'contentOptions' => ['class' => 'col-lg-2.5'],
            ],
            [
                'attribute' => 'genera_pago',
                'value' => function($model) {
                    $generaPago = ConfiguracionIncapacidad::findOne($model->codigo_incapacidad);
                    return $generaPago->generapago;
                },
                'filter' => ArrayHelper::map(ConfiguracionIncapacidad::find()->all(), 'genera_pago', 'generapago'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'genera_ibc',
                'value' => function($model) {
                    $generaIbc = ConfiguracionIncapacidad::findOne($model->codigo_incapacidad);
                    return $generaIbc->generaibc;
                },
                'filter' => ArrayHelper::map(ConfiguracionIncapacidad::find()->all(), 'genera_ibc', 'generaibc'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
           [
                'attribute' => 'codigo_salario',
                'value' => function($model) {
                    $codigoSalario = ConceptoSalarios::findOne($model->codigo_salario);
                    return $codigoSalario->nombre_concepto;
                },
                         'contentOptions' => ['class' => 'col-lg-2.5'],
               
            ],
            
           [
                'class' => 'yii\grid\ActionColumn',
            ],
            
        ],
       'tableOptions' => ['class' => 'table table-bordered table-success'],
        'summary' => '<div class="panel panel-success "><div class="panel-heading">Registros: <spam class="badge"> {totalCount}</spam></div>',
        'layout' => '{summary}{items}</div><div class="row"><div class="col-sm-8">{pager}</div><div class="col-sm-4 text-right">' . $newButton . '</div></div>',
        'pager' => [
            'nextPageLabel' => '<i class="fa fa-forward"></i>',
            'prevPageLabel' => '<i class="fa fa-backward"></i>',
            'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
            'firstPageLabel' => '<i class="fa fa-fast-backward"></i>',
        ],
    ]); ?>
</div>
