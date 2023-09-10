<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Departamentos;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MunicipioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'TIPO DOCUMENTOS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-documento-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?=  $this->render('_search', ['model' => $searchModel]); ?>

    <?php $newButton = Html::a('Nuevo ' . Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['create'], ['class' => 'btn btn-success btn-sm']);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [                
                'attribute' => 'tipo_documento',
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'documento',
                'contentOptions' => ['class' => 'col-lg-2'],
            ],
            [    
                'attribute' => 'proceso_nomina',
                'value' => function($model) {
                    $nomina = app\models\TipoDocumento::findOne($model->id_tipo_documento);
                    return $nomina->procesonomina;
                },
                'filter' => ArrayHelper::map(app\models\TipoDocumento::find()->all(), 'proceso_nomina', 'procesonomina'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [    
                'attribute' => 'proceso_cliente',
                'value' => function($model) {
                    $nomina = app\models\TipoDocumento::findOne($model->id_tipo_documento);
                    return $nomina->procesocliente;
                },
                'filter' => ArrayHelper::map(app\models\TipoDocumento::find()->all(), 'proceso_cliente', 'procesocliente'),
                'contentOptions' => ['class' => 'col-lg-1'],
            ],
            [    
                'attribute' => 'proceso_proveedor',
                'value' => function($model) {
                    $nomina = app\models\TipoDocumento::findOne($model->id_tipo_documento);
                    return $nomina->procesoproveedor;
                },
                'filter' => ArrayHelper::map(app\models\TipoDocumento::find()->all(), 'proceso_proveedor', 'procesoproveedor'),
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


