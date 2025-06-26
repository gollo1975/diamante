<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SolicitudArmadoKits */

$this->title = 'Actualizar: ' . $model->presentacion->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Solicitud Armado Kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_solicitud_armado, 'url' => ['update', 'id' => $model->id_solicitud_armado]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="solicitud-armado-kits-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
