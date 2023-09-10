<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MunicipioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'GRUPO DE PRODUCTOS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupo-producto-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?=  $this->render('_search', ['model' => $searchModel]); ?>

    <?php $newButton = Html::a('Nuevo ' . Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['create'], ['class' => 'btn btn-success btn-sm']);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [                
                'attribute' => 'id_grupo',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [                
                'attribute' => 'nombre_grupo',
                'contentOptions' => ['class' => 'col-lg-2'],
            ],
            [
                'attribute' => 'id_medida_producto',
                'value' => function($model){
                    $medida = \app\models\MedidaProductoTerminado::findOne($model->id_medida_producto);
                    return $medida->descripcion;
                },
                'filter' => ArrayHelper::map(\app\models\MedidaProductoTerminado::find()->orderBy('descripcion ASC')->all(),'id_medida_producto','descripcion'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
                        [
                'attribute' => 'id_clasificacion',
                'value' => function($model){
                    $clasificar = \app\models\ClasificacionInventario::findOne($model->id_clasificacion);
                    return $clasificar->descripcion;
                },
                'filter' => ArrayHelper::map(\app\models\ClasificacionInventario::find()->orderBy('descripcion ASC')->all(),'id_clasificacion','descripcion'),
                'contentOptions' => ['class' => 'col-lg-2'],
            ],

            [                
                'attribute' => 'user_name',
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



