<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Departamentos;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MunicipioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RESOLUCION DIAN';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resolucion-dian-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?=  $this->render('_search', ['model' => $searchModel]); ?>

    <?php $newButton = Html::a('Nuevo ' . Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['create'], ['class' => 'btn btn-success btn-sm']);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [                
                'attribute' => 'id_resolucion',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [                
                'attribute' => 'numero_resolucion',
                'contentOptions' => ['class' => 'col-lg-2'],
            ],
            [                
                'attribute' => 'desde',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
             [                
                'attribute' => 'hasta',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
             [                
                'attribute' => 'fecha_vence',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
           [
                'attribute' => 'estado_resolucion',
                'value' => function($model) {
                    $estado = app\models\ResolucionDian::findOne($model->id_resolucion);
                    return $estado->activo;
                },
                'filter' => ArrayHelper::map(app\models\ResolucionDian::find()->all(), 'estado_resolucion', 'activo'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
                        [
                'attribute' => 'abreviatura',
                'value' => function($model) {
                    $abre = app\models\ResolucionDian::findOne($model->id_resolucion);
                    return $abre->abreviaturaResolucion;
                },
                'filter' => ArrayHelper::map(app\models\ResolucionDian::find()->all(), 'abreviatura', 'abreviaturaResolucion'),
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


