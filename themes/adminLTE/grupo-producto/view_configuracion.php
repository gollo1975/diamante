<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;
/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'FORMULA DEL PRODUCTO ';
$this->params['breadcrumbs'][] = ['label' => 'Grupo de producto', 'url' => ['index_producto_configuracion', 'sw' => $sw]];
$this->params['breadcrumbs'][] = $model->id_grupo;
$conFases = ArrayHelper::map(app\models\TipoFases::find()->all(), 'id_fase', 'nombre_fase');
?>
<div class="grupo-producto-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index_producto_configuracion', 'sw' => $sw], ['class' => 'btn btn-primary btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Detalles del producto
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?>:</th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_grupo') ?>:</th>
                    <td><?= Html::encode($model->grupo->nombre_grupo) ?></td> 
               </tr>           
            </table>
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
        'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
        ],
    ]);?>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#cargar_configuracion" aria-controls="cargar_configuracion" role="tab" data-toggle="tab">Configuracion producto  <span class="badge"><?= count($configuracion) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="asignacioncupo">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE; '>Codigo</th>                        
                                        <th scope="col" style='background-color:#B9D5CE; '>Materia prima</th> 
                                        <th scope="col" style='background-color:#B9D5CE; '>Fase</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Porcentaje_aplicacion</th>    
                                        <th scope="col" style='background-color:#B9D5CE;'>codigo homologacion</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>User_name</th>
                                         <th scope="col" style='background-color:#B9D5CE;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($configuracion as $val):?>
                                        <tr style='font-size:85%;'>
                                            <td><?= $val->codigo_materia?></td>
                                            <td><?= $val->nombre_materia_prima?></td>
                                            <?php if($val->id_fase == 1){?>
                                                <td style="padding-left: 1;padding-right: 0; background-color:<?= $val->fase->color?>"><?= Html::dropDownList('fase[]', $val->id_fase, $conFases, ['class' => 'col-sm-12', 'prompt' => 'Seleccione', 'required' => true]) ?></td>
                                            <?php }else{?>
                                                <td style="padding-left: 1;padding-right: 0; background-color:<?= $val->fase->color?>"><?= Html::dropDownList('fase[]', $val->id_fase, $conFases, ['class' => 'col-sm-12', 'prompt' => 'Seleccione', 'required' => true]) ?></td>
                                            <?php }?>    
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="porcentaje_aplicacion[]" value="<?= ''. number_format($val->porcentaje_aplicacion,4) ?>" style="text-align: right" size="9" required="true"> </td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text"  name="codigo_homologacion[]" value="<?= $val->codigo_homologacion ?>" style="text-align: right" size="9"> </td>
                                            <td><?= $val->user_name?></td>
                                            <input type="hidden" name="listado_materia[]" value="<?= $val->id?>"> 
                                            <td style= 'width: 25px; height: 25px;'>
                                                <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminarmateria', 'detalle' => $val->id, 'sw' => $sw ,'id_producto' =>$model->id_producto], [
                                                              'class' => '',
                                                              'data' => [
                                                                  'confirm' => 'Esta seguro de eliminar el registro?',
                                                                  'method' => 'post',

                                                              ],
                                                ])?>
                                            </td>     
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>  
                        <div class="panel-footer text-right">  
                            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizamateriaprima'])?>
                        </div>   
                    </div>
                </div>
            </div>
            <!-- TERMINA TABS--->
        </div> 
    </div>    
<?php ActiveForm::end(); ?>  
</div>