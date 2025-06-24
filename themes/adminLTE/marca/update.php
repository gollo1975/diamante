<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Marca */

$this->title = 'Actualizar: ' . $model->marca;
$this->params['breadcrumbs'][] = ['label' => 'Marcas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_marca, 'url' => ['udpdate', 'id' => $model->id_marca]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="marca-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => 1,
    ]) ?>

</div>
