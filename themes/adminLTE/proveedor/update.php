<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Proveedor */

$this->title = 'Actualizar: ' . $model->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Proveedors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_proveedor, 'url' => ['view', 'id' => $model->id_proveedor]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<!--<div class="proveedor-update">-->

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('editardocumento', [
        'model' => $model,
        'msg' => $msg,
        'municipio' => $municipio,
      
    ]) ?>

</div>
