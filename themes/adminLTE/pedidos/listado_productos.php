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

$this->title = 'Nuevo pedido';
$this->params['breadcrumbs'][] = ['label' => 'Pedidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_pedido;
?>
 
        <p>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php if($model->autorizado == 0 && $model->numero_pedido == 0){?>
                <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_pedido, 'tokenAcceso' => $tokenAcceso, 'token' => $token], ['class' => 'btn btn-default btn-sm']);?>
            <?php }else{
                if($model->autorizado == 1  && $model->numero_pedido == 0){?>
                    <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_pedido, 'tokenAcceso' => $tokenAcceso, 'token' => $token], ['class' => 'btn btn-default btn-sm']);?>
                    <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Crear pedido', ['crear_pedido_cliente', 'id' => $model->id_pedido, 'tokenAcceso'=> $tokenAcceso, 'token' => $token],['class' => 'btn btn-warning btn-sm',
                               'data' => ['confirm' => 'Esta seguro de CREAR el pedido al cliente ' .$model->cliente. '.', 'method' => 'post']]);?>
                     <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Observaciones',
                                              ['/pedidos/crear_observacion', 'id' => $model->id_pedido, 'tokenAcceso' => $tokenAcceso, 'token' => $token],
                                                ['title' => 'Crear observaciones al pedido',
                                                 'data-toggle'=>'modal',
                                                 'data-target'=>'#modalcrearobservacion',
                                                 'class' => 'btn btn-info btn-xs'
                                                ])    
                    ?>
                    <div class="modal remote fade" id="modalcrearobservacion">
                           <div class="modal-dialog modal-lg" style ="width: 430px;">    
                               <div class="modal-content"></div>
                           </div>
                    </div>
                <?php }
            
            }?>
        </p>
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
            "action" => Url::toRoute(["pedidos/adicionar_productos", 'id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' => $token]),
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
                    <a align="right" href="<?= Url::toRoute(["pedidos/adicionar_productos", 'id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' => $token]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <li role="presentation" class="active"><a href="#listadoproductos" aria-controls="listadoproductos" role="tab" data-toggle="tab">Inventarios <span class="badge"><?= $pagination->totalCount ?></span></a></li>
                <li role="presentation"><a href="#detallepedido" aria-controls="detallepedido" role="tab" data-toggle="tab">Detalle del pedido <span class="badge"><?= count($detalle_pedido) ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="listadoproductos">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                           <div class="panel-body">
                                 <table class="table table-responsive">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Código</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Imagen</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cant.</th>
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
                                            <td style="background-color:#EDF5F3; color: black"><?= $val->stock_unidades ?></td>
                                            <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidad_productos[]"  style="text-align: right" size="7" maxlength="true"> </td> 
                                            <input type="hidden" name="nuevo_producto[]" value="<?= $val->id_inventario?>"> 
                                        </tr>
                                    </tbody>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <?php if($model->autorizado == 0){?>
                                <div class="panel-footer text-right">
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-plus'></span> Adicionar", ["class" => "btn btn-info btn-sm", 'name' => 'cargar_producto']) ?>
                                </div>
                            <?php }?>
                        </div>
                        <?= LinkPager::widget(['pagination' => $pagination]) ?>
                    </div>
                </div>    
                <!-- TERMINA TABS-->  
                <div role="tabpanel" class="tab-pane" id="detallepedido">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                          <div class="panel-body">
                                <table class="table table-responsive">
                                   <thead>
                                       <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Vr. unit.</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Subtotal.</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                   <?php
                                   $subtotal = 0; $impuesto = 0; $total = 0;
                                   foreach ($detalle_pedido as $val):
                                       $subtotal += $val->subtotal;
                                       $impuesto += $val->impuesto;
                                       $total += $val->total_linea;
                                       ?>
                                   <tr style="font-size: 90%;">
                                       <td><?= $val->inventario->nombre_producto ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                       <td style= 'width: 25px; height: 25px;'>
                                            <?php if($model->autorizado == 0){?>
                                                <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle', 'id' => $model->id_pedido, 'detalle' => $val->id_detalle, 'tokenAcceso' => $tokenAcceso, 'token' => 1], [
                                                            'class' => '',
                                                            'data' => [
                                                                'confirm' => 'Esta seguro de eliminar este producto del pedido?',
                                                                'method' => 'post',
                                                            ],
                                                        ])
                                                ?>
                                            <?php }?>
                                       </td>
                                   </tr>
                                   </tbody>
                                   <?php endforeach; ?>
                                   <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;"><b>Subtotal:</b></td>
                                        <td align="right" style="width: 15%" ><b><?= '$ '.number_format($subtotal,0); ?></b></td>
                                        <td colspan="1"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;"><b>Impuesto:</b></td>
                                        <td align="right" ><b><?= '$ '.number_format($impuesto,0); ?></b></td>
                                        <td colspan="1"></td>
                                    </tr>
                                     <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;"><b>Total:</b></td>
                                        <td align="right" ><b><?= '$ '.number_format($total,0); ?></b></td>
                                        <td colspan="1"></td>
                                    </tr>
                               </table>
                           </div>
                           <div class="panel-footer text-right">
                               <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['excel_pedido', 'id' => $id, 'tokenAcceso' => $tokenAcceso], ['class' => 'btn btn-primary btn-sm']);?>
                           </div>
                       </div>
                </div>
                    <?php ActiveForm::end(); ?>
            </div>        
             <!-- TERMINA TABS-->
        </div>     
    </div>        
</div> 
<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>
