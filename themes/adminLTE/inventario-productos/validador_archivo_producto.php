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
use kartik\depdrop\DepDrop;
//Modelos...
$this->title = 'CARGAR IMAGEN';
$this->params['breadcrumbs'][] = $this->title;
$view_archivo = 'view_archivo';

?>
<div class="panel panel-success">
    <div class="panel-body">
        <script language="JavaScript">
            function mostrarfiltro() {
                divC = document.getElementById("filtro");
                if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
            }
        </script>
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["inventario-productos/view_archivo"]),
            "enableClientValidation" => true,
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-3 form-group">{input}{error}</div>',
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            'options' => []
                        ],
        ]);
        ?>
        <div class="panel panel-success panel-filters">
            <div class="panel-heading" onclick="mostrarfiltro()">
                Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
            </div>
            <div class="panel-body" id="filtro" style="display:none">
                <div class="row" >
                    <?= $formulario->field($form, "q")->input("search") ?>
                    <?= $formulario->field($form, "nombre")->input("search") ?>
                </div>

                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                    <a align="right" href="<?= Url::toRoute(["inventario-productos/view_archivo"]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
                </div>
            </div>
        </div>
        <?php $formulario->end() ?>
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]); ?>
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#inventario" aria-controls="inventario" role="tab" data-toggle="tab">Productos <span class="badge"><?= $pagination->totalCount ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="inventario">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                           <div class="panel-body">
                                 <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Nombre_producto</th>
                                             <th scope="col" style='background-color:#B9D5CE;'>Grupo</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Imagen</th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $variable = '';
                                        $item = \app\models\Documentodir::findOne(21);
                                        foreach ($model as $val): 
                                            $valor = app\models\DirectorioArchivos::find()->where(['=','codigo', $val->id_inventario])->andWhere(['=','numero', $item->codigodocumento])
                                                                                          ->andWhere(['=','predeterminado', 1])->one();
                                            ?>
                                            <tr style ='font-size: 90%;'>                
                                                <td><?= $val->codigo_producto?></td>
                                                <td><?= $val->nombre_producto?></td>
                                                <td><?= $val->grupo->nombre_grupo?></td>
                                                <?php 
                                                if($valor){
                                                  $variable = 'Documentos/'.$valor->numero.'/'.$valor->codigo.'/'. $valor->nombre;
                                                  if($valor->extension == 'png' || $valor->extension == 'jpeg' || $valor->extension == 'jpg'){?>
                                                    <td style="width: 100px; background-color: white" title="<?php echo $val->nombre_producto?>"> <?= yii\bootstrap\Html::img($variable, ['width' => '70px;', 'height' => '75px;'])?></td>
                                                  <?php }else {?>
                                                      <td><?= 'NOT FOUND'?></td>
                                                  <?php } 
                                                }else{?>
                                                      <td><?= 'NOT FOUND'?></td>
                                                <?php }?>  
                                                <td style="width: 5%; height: 10%">
                                                    <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index_archivo','numero' => 21, 'codigo' => $val->id_inventario, 'view_archivo' => $view_archivo, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
                                                </td>     
                                                <td style= 'width: 25px; height: 10px;'>
                                                    <a href="<?= Url::toRoute(["inventario-productos/view_imagen", "id" => $val->id_inventario, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Este proceso permite cargar la imagen del producto"></span></a>
                                                </td>  
                                            </tr>            
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?= LinkPager::widget(['pagination' => $pagination]) ?>
                    </div>
                </div>    
                <!-- TERMINA TABS-->  
        </div>     
    </div>        
    <?php ActiveForm::end(); ?>    
</div> 

