<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoRack */

$this->title = 'Actualizar: ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Racks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_rack, 'url' => ['update', 'id' => $model->id_rack]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="tipo-rack-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
