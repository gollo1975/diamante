<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProgramacionNomina */

$this->title = 'Update Programacion Nomina: ' . $model->id_programacion;
$this->params['breadcrumbs'][] = ['label' => 'Programacion Nominas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_programacion, 'url' => ['view', 'id' => $model->id_programacion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="programacion-nomina-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
