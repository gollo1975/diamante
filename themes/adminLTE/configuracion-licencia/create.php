<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionLicencia */

$this->title = 'Configuracion Licencia';
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracion-licencia-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
