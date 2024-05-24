    <?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

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
//Modelos...
$this->title = 'CARGAR IMAGEN';
$this->params['breadcrumbs'][] = $this->title;
$validador_imagen = 'inventario-punto-venta';

?>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
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
            "action" => Url::toRoute(["inventario-punto-venta/validador_imagen"]),
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
                    <a align="right" href="<?= Url::toRoute(["inventario-punto-venta/validador_imagen"]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                                     <link rel="stylesheet" href="dist/css/site.css">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Nombre_producto</th>
                                             <th scope="col" style='background-color:#B9D5CE;'>Punto de venta</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Marca</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Categoria</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Imagen</th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $cadena = '';
                                        $item = \app\models\Documentodir::findOne(18);
                                        foreach ($model as $val): 
                                            $valor = app\models\DirectorioArchivos::find()->where(['=','codigo', $val->id_inventario])
                                                                                          ->andWhere(['=','predeterminado', 1])->andWhere(['=','numero', $item->codigodocumento])->one();
                                            ?>
                                            <tr style ='font-size: 90%;'>                
                                                <td><?= $val->codigo_producto?></td>
                                                <td><?= $val->nombre_producto?></td>
                                                 <td><?= $val->punto->nombre_punto?></td>
                                                <td><?= $val->marca->marca?></td>
                                                <td><?= $val->categoria->categoria?></td>
                                                <?php if($valor){
                                                    $cadena = 'Documentos/'.$valor->numero.'/'.$valor->codigo.'/'. $valor->nombre;
                                                    if($valor->extension == 'png' || $valor->extension == 'jpeg' || $valor->extension == 'jpg'){?>
                                                       <td  style="width: 10%; height: 20%; text-align: center; background-color: white" title="<?php echo $val->nombre_producto?>"> <?= yii\bootstrap\Html::img($cadena, ['width' => '100%;', 'height' => '60%;'])?></td>
                                                    <?php }else {?>
                                                        <td><?= 'NOT FOUND'?></td>
                                                    <?php } 
                                                }else{?>
                                                      <td><?= 'No found'?></td>
                                                <?php }?>      
                                                      <td style="width: 5%; height: 10%">
                                                    <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index_imagen_punto','numero' => 18, 'codigo' => $val->id_inventario, 'validador_imagen' => $validador_imagen, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
                                                    
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

