<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FormatoContenidoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Formato Contenidos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="formato-contenido-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Formato Contenido', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_formato_contenido',
            'nombre_formato',
            'contenido:ntext',
            'id_configuracion_prefijo',
            'fecha_creacion',
            //'user_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
