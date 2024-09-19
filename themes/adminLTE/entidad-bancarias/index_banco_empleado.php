<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;
//Modelos...
use app\models\Departamentos;
use app\models\Municipios;

$this->title = 'BANCOS DE EMPLEADOS';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php
$form = ActiveForm::begin([
                "method" => "post",                            
            ]);
    ?>
<div class="table-responsive">
<div class="panel panel-success ">
    <div class="panel-heading">
        Registros <span class="badge"><?= count($model) ?></span>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style ='font-size: 90%;'>         
                
                <th scope="col" style='background-color:#B9D5CE;'>Código</th>
                <th scope="col" style='background-color:#B9D5CE;'>Entidad financiera</th>
                <th scope="col" style='background-color:#B9D5CE;'>Codigo interfaz</th>
                <th score="col" style='background-color:#B9D5CE;'></th>                              
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
                <tr style ='font-size: 90%;'>                
                    <td><?= $val->id_banco?></td>
                    <td><?= $val->entidad?></td>
                    <td><?= $val->codigo_interfaz ?></td>
                    <td style= 'width: 25px; height: 10px;'>
                           <a href="<?= Url::toRoute(["entidad-bancarias/update_banco", "id_banco" => $val->id_banco]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                    </td>
                </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
            <a align="right" href="<?= Url::toRoute("entidad-bancarias/crear_bancos") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
        <?php $form->end() ?>
        </div>
     </div>
</div>

