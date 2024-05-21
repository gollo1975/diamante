<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Departamentos;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MunicipioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'DEPARTAMENTOS';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="departamentos-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?=  $this->render('_search', ['model' => $searchModel]); ?>

    <?php $newButton = Html::a('Nuevo ' . Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['create'], ['class' => 'btn btn-success btn-sm']);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [                
                'attribute' => 'codigo_departamento',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [                
                'attribute' => 'departamento',
                'contentOptions' => ['class' => 'col-lg-4'],
            ],
            [
                'attribute' => 'codigo_pais',
                'value' => function($model){
                    $pais = \app\models\Pais::findOne($model->codigo_pais);
                    return $pais->pais;
                },
                'filter' => ArrayHelper::map(\app\models\Pais::find()->all(),'codigo_pais','pais'),
                'contentOptions' => ['class' => 'col-lg-4'],
            ],
                        [                
                'attribute' => 'usuario_creador',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
           [
                'attribute' => 'estado_registro',
                'value' => function($model) {
                    $estado = Departamentos::findOne($model->codigo_departamento);
                    return $estado->activo;
                },
                'filter' => ArrayHelper::map(Departamentos::find()->all(), 'estado_registro', 'activo'),
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


