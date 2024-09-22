<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionFormatoPrefijo */

$this->title = 'Create Configuracion Formato Prefijo';
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Formato Prefijos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracion-formato-prefijo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
