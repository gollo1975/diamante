<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FormatoContenido */

$this->title = 'Create Formato Contenido';
$this->params['breadcrumbs'][] = ['label' => 'Formato Contenidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="formato-contenido-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
