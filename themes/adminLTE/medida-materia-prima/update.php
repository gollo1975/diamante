<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MedidaMateriaPrima */

$this->title = 'Actualizar: ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Medida Materia Primas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_medida, 'url' => ['update', 'id' => $model->id_medida]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="medida-materia-prima-update">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
