<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PagoBanco */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Pago Bancos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pago-banco-create">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
