<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\ConceptoSalarios;
use app\models\ConfiguracionLicencia;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConceptoSalariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuracion licencia';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracion-licencia-index">

   <?= $this->render('_search', ['model' => $searchModel]); ?>
   <?php $newButton = Html::a('Nuevo ' . Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['create'], ['class' => 'btn btn-success']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'codigo_licencia',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'concepto',
                'contentOptions' => ['class' => 'col-lg-2.5'],
            ],
            [
                'attribute' => 'afecta_salud',
                'value' => function($model) {
                    $afectaSalud = ConfiguracionLicencia::findOne($model->codigo_licencia);
                    return $afectaSalud->afectasalud;
                },
                'filter' => ArrayHelper::map(ConfiguracionLicencia::find()->all(), 'afecta_salud', 'afectasalud'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'ausentismo',
                'value' => function($model) {
                    $ausentismoL = ConfiguracionLicencia::findOne($model->codigo_licencia);
                    return $ausentismoL->lausentismo;
                },
                'filter' => ArrayHelper::map(ConfiguracionLicencia::find()->all(), 'ausentismo', 'Lausentismo'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'maternidad',
                'value' => function($model) {
                    $maternidad = ConfiguracionLicencia::findOne($model->codigo_licencia);
                    return $maternidad->lmaternidad;
                },
                'filter' => ArrayHelper::map(ConfiguracionLicencia::find()->all(), 'maternidad', 'lmaternidad'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'paternidad',
                'value' => function($model) {
                    $paternidad = ConfiguracionLicencia::findOne($model->codigo_licencia);
                    return $paternidad->licenciapaternidad;
                },
                'filter' => ArrayHelper::map(ConfiguracionLicencia::find()->all(), 'paternidad', 'licenciapaternidad'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'remunerada',
                'value' => function($model) {
                    $remunerada = ConfiguracionLicencia::findOne($model->codigo_licencia);
                    return $remunerada->lremunerada;
                },
                'filter' => ArrayHelper::map(ConfiguracionLicencia::find()->all(), 'remunerada', 'lremunerada'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],    
                        [
                'attribute' => 'suspension_contrato',
                'value' => function($model) {
                    $suspension = ConfiguracionLicencia::findOne($model->codigo_licencia);
                    return $suspension->suspensioncontrato;
                },
                'filter' => ArrayHelper::map(ConfiguracionLicencia::find()->all(), 'suspension_contrato', 'suspensioncontrato'),
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
        'summary' => '<div class="panel panel-success "><div class="panel-heading">Registros:<span class="badge">{totalCount}</spam></div>',
        'layout' => '{summary}{items}</div><div class="row"><div class="col-sm-8">{pager}</div><div class="col-sm-4 text-right">' . $newButton . '</div></div>',
        'pager' => [
            'nextPageLabel' => '<i class="fa fa-forward"></i>',
            'prevPageLabel' => '<i class="fa fa-backward"></i>',
            'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
            'firstPageLabel' => '<i class="fa fa-fast-backward"></i>',
        ],
    ]); ?>
</div>
