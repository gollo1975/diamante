<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DepartamentosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="presupuesto-empresarial-search">

   
    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => [ 'tag' => false,]
                ],
    ]);
    ?>
    <div class="panel panel-success panel-filters" style="display:none">
        <div class="panel-heading">
            Filtros <i class="glyphicon glyphicon-filter"></i>
        </div>
       

<?php ActiveForm::end(); ?>

    </div>
</div>
