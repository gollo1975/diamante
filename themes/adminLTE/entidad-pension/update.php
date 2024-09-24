<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadPension */

$this->title = 'ACTUALIZAR: ' . $model->entidad;
$this->params['breadcrumbs'][] = ['label' => 'Entidades de Pension', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entidad_pension, 'url' => ['update', 'id' => $model->id_entidad_pension]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="entidad-pension-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => 1,
    ]) ?>

</div>
