<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoPago */

$this->title = 'ACTUALIZAR: ' . $model->grupo_pago;
$this->params['breadcrumbs'][] = ['label' => 'Grupo Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_grupo_pago, 'url' => ['update', 'id' => $model->id_grupo_pago]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grupo-pago-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
