<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MunicipioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'TIPO DE ORDENES';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-orden-compra-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?=  $this->render('_search', ['model' => $searchModel]); ?>

    <?php $newButton = Html::a('Nuevo ' . Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['create'], ['class' => 'btn btn-success btn-sm']);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [                
                'attribute' => 'id_tipo_orden',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [                
                'attribute' => 'descripcion_orden',
                'contentOptions' => ['class' => 'col-lg-6'],
            ],
            [                
                'attribute' => 'abreviatura',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'tipo_modulo',
                'value' => function($model) {
                    $estado = app\models\TipoOrdenCompra::findOne($model->id_tipo_orden);
                    return $estado->tipoModulo;
                },
                'filter' => ArrayHelper::map(app\models\TipoOrdenCompra::find()->all(), 'tipo_modulo', 'tipoModulo'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
             [
                'class' => 'yii\grid\ActionColumn', 
                 'contentOptions' => ['class' => 'col-lg-1'],
            ],            
			
        ],
        'tableOptions' => ['class' => 'table table-bordered table-success'],
        'summary' => '<div class="panel panel-success "><div class="panel-heading">Registros <span class= "badge"> {totalCount}</span></div>',

        'layout' => '{summary}{items}</div><div class="row"><div class="col-sm-8">{pager}</div><div class="col-sm-4 text-right">' . $newButton . '</div></div>',
        'pager' => [
            'nextPageLabel' => '<i class="fa fa-forward"></i>',
            'prevPageLabel'  => '<i class="fa fa-backward"></i>',
            'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
            'firstPageLabel'  => '<i class="fa fa-fast-backward"></i>'
        ],
        
    ]); ?>
</div>



