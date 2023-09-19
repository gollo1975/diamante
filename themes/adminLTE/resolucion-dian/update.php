<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ResolucionDian */

$this->title = 'Actualizar: ' . $model->numero_resolucion;
$this->params['breadcrumbs'][] = ['label' => 'Resolucion Dian', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_resolucion, 'url' => ['update', 'id' => $model->id_resolucion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="resolucion-dian-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
