<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CajaCompensacion */

$this->title = $model->id_caja;
$this->params['breadcrumbs'][] = ['label' => 'Caja Compensacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="caja-compensacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_caja], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_caja], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_caja',
            'caja',
            'telefono',
            'direccion',
            'codigo',
            'codigo_municipio',
            'estado',
            'porcentaje',
            'user_name',
            'fecha_hora_registro',
        ],
    ]) ?>

</div>
