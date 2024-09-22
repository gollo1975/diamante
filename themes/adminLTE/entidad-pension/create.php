<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadPension */

$this->title = 'Create Entidad Pension';
$this->params['breadcrumbs'][] = ['label' => 'Entidad Pensions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entidad-pension-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
