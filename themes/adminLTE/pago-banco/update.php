<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PagoBanco */

$this->title = 'Actualizar: ' . $model->codigoBanco->entidad_bancaria;
$this->params['breadcrumbs'][] = ['label' => 'Pago Bancos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pago_banco, 'url' => ['view', 'id' => $model->id_pago_banco]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="pago-banco-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
