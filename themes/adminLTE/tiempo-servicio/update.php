   <?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoContrato */

$this->title = 'ACTUALIZAR: ' . $model->tiempo_servicio;
$this->params['breadcrumbs'][] = ['label' => 'Tiempo de servicio', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tiempo, 'url' => ['update', 'id' => $model->id_tiempo]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="tiempo-servicio-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
