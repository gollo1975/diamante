<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\AreaEmpresa;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MunicipioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PRESUPUESTO DE AREAS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presupuesto-empresarial-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?=  $this->render('_search', ['model' => $searchModel]); ?>

    <?php $newButton = Html::a('Nuevo ' . Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['create'], ['class' => 'btn btn-success btn-sm']);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [                
                'attribute' => 'id_presupuesto',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [                
                'attribute' => 'descripcion',
                'contentOptions' => ['class' => 'col-lg-2'],
            ],
            [
                'attribute' => 'id_area',
                'value' => function($model){
                    $area = AreaEmpresa::findOne($model->id_area);
                    return $area->descripcion;
                },
                'filter' => ArrayHelper::map(AreaEmpresa::find()->orderBy('descripcion ASC')->all(),'id_area','descripcion'),
                'contentOptions' => ['class' => 'col-lg-2'],
            ],
            [                
                'attribute' => 'año',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [            
                          'attribute' => 'fecha_inicio',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],    
           [
              'attribute' => 'fecha_corte',
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


