<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaDotacion */

$this->title = 'Actualizar: ' . $model->empleado->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Entrega Dotacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entrega, 'url' => ['view', 'id' => $model->id_entrega]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="entrega-dotacion-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
