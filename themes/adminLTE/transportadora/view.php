<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Transportadora */

$this->title = $model->id_transportadora;
$this->params['breadcrumbs'][] = ['label' => 'Transportadoras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transportadora-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_transportadora], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_transportadora], [
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
            'id_transportadora',
            'tipo_documento',
            'nit_cedula',
            'dv',
            'razon_social',
            'direccion',
            'email_transportadora:email',
            'telefono',
            'celular',
            'codigo_departamento',
            'codigo_municipio',
            'contacto',
            'celular_contacto',
            'user_name',
            'fecha_registro',
        ],
    ]) ?>

</div>
