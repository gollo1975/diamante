<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Marca;
use app\models\GrupoProducto;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MunicipioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PRODUCTOS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="productos-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?=  $this->render('_search', ['model' => $searchModel]); ?>

    <?php $newButton = Html::a('Nuevo ' . Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['create'], ['class' => 'btn btn-success btn-sm']);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            
            [                
                'attribute' => 'id_producto',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [                
                'attribute' => 'nombre_producto',
                'contentOptions' => ['class' => 'col-lg-3'],
            ],
            [
                'attribute' => 'id_grupo',
                'value' => function($model){
                    $grupo = GrupoProducto::findOne($model->id_grupo);
                    return $grupo->nombre_grupo;
                },
                'filter' => ArrayHelper::map(GrupoProducto::find()->orderBy('nombre_grupo ASC')->all(),'id_grupo','nombre_grupo'),
                'contentOptions' => ['class' => 'col-lg-2'],
            ],
            [
                'attribute' => 'id_marca',
                'value' => function($model){
                    $nombre_marca = app\models\Marca::findOne($model->id_marca);
                    return $nombre_marca->marca;
                },
                'filter' => ArrayHelper::map(app\models\Marca::find()->orderBy('marca ASC')->all(),'id_marca','marca'),
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


