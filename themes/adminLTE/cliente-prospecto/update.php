<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClienteProspecto */

$this->title = 'Actualizar: ' . $model->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Prospectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_prospecto, 'url' => ['update', 'id' => $model->id_prospecto]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="cliente-prospecto-update">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form_editado', [
        'model' => $model,
        'municipios' => $municipios,
    ]) ?>

</div>
