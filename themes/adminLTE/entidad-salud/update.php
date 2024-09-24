<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadSalud */

$this->title = 'ACTUALIZAR: ' . $model->entidad_salud;
$this->params['breadcrumbs'][] = ['label' => 'Entidades de Salud', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entidad_salud, 'url' => ['update', 'id' => $model->id_entidad_salud]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="entidad-salud-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
         'sw' => $sw,
    ]) ?>

</div>
