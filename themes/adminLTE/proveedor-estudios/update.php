<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProveedorEstudios */

$this->title = 'Actualizar: ' . $model->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Proveedor Estudios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_estudio, 'url' => ['view', 'id' => $model->id_estudio]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="proveedor-estudios-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
