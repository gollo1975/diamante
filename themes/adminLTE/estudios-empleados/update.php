<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EstudiosEmpleados */

$this->title = 'Actualizar a : ' . $model->empleado->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Estudios Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="estudios-empleados-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
