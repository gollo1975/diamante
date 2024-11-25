<?php
//clase
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

$this->title = 'Regla comercial(Productos)';
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto producto', 'url' => ['adicionar_presupuesto', 'id'=> $id, 'sw' => $sw, 'token' => $token, 'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $pedido_virtual ,'tipo_pedido' => $tipo_pedido]];
$this->params['breadcrumbs'][] = $model->id_pedido;
?>
<p>
    <?php if($tipo_pedido == 0){
      echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['adicionar_productos','id' =>$id, 'token' => $token, 'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $model->pedido_virtual, 'tipo_pedido' => $tipo_pedido], ['class' => 'btn btn-primary btn-sm']);
    }else{
        echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['adicionar_producto_pedido','id' =>$id, 'token' => $token, 'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $model->pedido_virtual, 'tipo_pedido' => $tipo_pedido], ['class' => 'btn btn-primary btn-sm']);
    }?>
</p>
    <div class="panel-body">
        <script language="JavaScript">
            function mostrarfiltro() {
                divC = document.getElementById("filtro");
                if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
            }
        </script>
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["pedidos/adicionar_presupuesto", 'id' => $id, 'token' => $token, 'tokenAcceso' => $tokenAcceso, 'sw' => $sw, 'pedido_virtual' => $model->pedido_virtual, 'tipo_pedido' => $tipo_pedido]),
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
            <div class="panel-body" id="filtro" style="display:block">
                <div class="row" >
                    <?= $formulario->field($form, "q")->input("search") ?>
                    <?= $formulario->field($form, "nombre")->input("search") ?>
                </div>

                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                    <a align="right" href="<?= Url::toRoute(["pedidos/adicionar_presupuesto", 'id' => $id, 'token' => $token,'tokenAcceso' => $tokenAcceso, 'sw' => $sw, 'pedido_virtual' => $model->pedido_virtual, 'tipo_pedido' => $tipo_pedido]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <li role="presentation" class="active"><a href="#presupuesto" aria-controls="presupuesto" role="tab" data-toggle="tab">Inventarios presupuesto<span class="badge"><?= $pagination->totalCount ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="presupuesto">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                           <div class="panel-body">
                                 <table class="table table-responsive">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                             <th scope="col" style='background-color:#B9D5CE;'>Código</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Imagen</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cantidad enviada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                         $cadena = '';
                                        $item = \app\models\Documentodir::findOne(8);
                                        foreach ($variable as $val):
                                            $valor = app\models\DirectorioArchivos::find()->where(['=','codigo', $val->id_inventario])->andWhere(['=','numero', $item->codigodocumento])->one();
                                            ?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->codigo_producto ?></td>
                                                <td><?= $val->nombre_producto ?></td>
                                                <td style="background-color:#CBAAE3; color: black"><?= $val->stock_unidades ?></td>
                                                <?php if($valor){
                                                  $cadena = 'Documentos/'.$valor->numero.'/'.$valor->codigo.'/'. $valor->nombre;
                                                  if($valor->extension == 'png' || $valor->extension == 'jpeg' || $valor->extension == 'jpg'){?>
                                                     <td style="width: 100px; border: 0px solid grey;" title="<?php echo $val->nombre_producto?>"> <?= yii\bootstrap\Html::img($cadena, ['width' => '65px;', 'height' => '70px;'])?></td>
                                                  <?php }else {?>
                                                      <td><?= 'NOT FOUND'?></td>
                                                  <?php } 
                                                }else{?>
                                                      <td></td>
                                                <?php }?>     
                                                <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidades[]" style="text-align: right" size="7" maxlength="true"> </td> 
                                                <input type="hidden" name="nuevo_producto_presupueso[]" value="<?= $val->id_inventario?>"> 
                                            </tr>     
                                        <?php endforeach; ?>
                                    </tbody>     
                                </table>
                            </div>
                            <div class="panel-footer text-right">
                               <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar productos", ["class" => "btn btn-success btn-sm", 'name' => 'importar_producto_presupuesto']) ?>
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
