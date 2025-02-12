<?php
$empresa = \app\models\MatriculaEmpresa::findOne(1); 
 $tokenModulo =  Yii::$app->user->identity->modulo;  
?>
    
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/avatar5.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->nombrecompleto ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <h4><?= $tokenModulo;?></h4>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        
        <?php if ($empresa->modulo_completo == 0){
            if($tokenModulo == 1){?> <!--APLICA PARA EL SOFTWARE COMPLETO CON TODOS LOS MODULOS, ESTOS ES FABRICACION Y DISTRIBUCION-->
                <?=
                dmstr\widgets\Menu::widget(
                        [
                            'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                            'items' => [
                                ['label' => 'MENÚ PRINCIPAL', 'options' => ['class' => 'header']],
                                //['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                                //['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                                ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                                [
                                    'label' => 'CONTINUAR',
                                    'icon' => 'share',
                                    'url' => '#',
                                    'items' => [
                                        //['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                                        //['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                                        [
                                            'label' => 'CONFIGURACION',
                                            'icon' => 'database',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'Administración',
                                                    'icon' => 'database',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Departamento', 'icon' => 'plus-square-o', 'url' => ['/departamentos/index']],  
                                                        ['label' => 'Municipio', 'icon' => 'plus-square-o', 'url' => ['/municipios/index']],
                                                        ['label' => 'Entidad bancaria', 'icon' => 'plus-square-o', 'url' => ['/entidad-bancarias/index']],
                                                        ['label' => 'Tipo Documento', 'icon' => 'plus-square-o', 'url' => ['/tipo-documento/index']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Movimiento',
                                                    'icon' => 'book',
                                                    'url' => '#',
                                                    'items' => [
                                                  //  ['label' => 'Cliente', 'icon' => 'plus-square-o', 'url' => ['/clientes/index']],


                                                ]        
                                                ],
                                                [
                                                    'label' => 'Consultas',
                                                    'icon' => 'question',
                                                    'url' => '#',
                                                    'items' => [
                                                      ['label' => 'Departamentos', 'icon' => 'plus-square-o', 'url' => ['/departamentos/indexdepartamento']],
                                                      ['label' => 'Municipios', 'icon' => 'plus-square-o', 'url' => ['/municipios/indexmunicipio']],
                                                      ['label' => 'Presupuesto x Area', 'icon' => 'plus-square-o', 'url' => ['/presupuesto-empresarial/search_presupuesto_area']],  
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Procesos',
                                                    'icon' => 'exchange',
                                                    'url' => '#',
                                                    'items' => [
                                                    ['label' => 'Presupuesto de areas', 'icon' => 'plus-square-o', 'url' => ['/presupuesto-empresarial/index']],                                      
                                                    ['label' => 'Gastos presupuesto', 'icon' => 'plus-square-o', 'url' => ['/presupuesto-empresarial/presupuesto_mensual']],                                          
                                                    ],
                                                ],
                                            ],
                                        ],


                                        //INICIO DEL MENU COMPRAS
                                         [
                                            'label' => 'COMPRAS',
                                            'icon' => 'dollar',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'Administración',
                                                    'icon' => 'database',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Requisitos', 'icon' => 'plus-square-o', 'url' => ['/listado-requisitos/index']],
                                                        ['label' => 'Tipo ordenes', 'icon' => 'plus-square-o', 'url' => ['/tipo-orden-compra/index']],
                                                        ['label' => 'Tipos de solicitud', 'icon' => 'plus-square-o', 'url' => ['/tipo-solicitud/index']],
                                                        ['label' => 'Proveedor', 'icon' => 'plus-square-o', 'url' => ['/proveedor/index']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Utilidades',
                                                    'icon' => 'cube',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Estudios proveedor', 'icon' => 'plus-square-o', 'url' => ['/proveedor-estudios/index']],
                                                        ['label' => 'Aprobar proveedor ', 'icon' => 'plus-square-o', 'url' => ['/proveedor-estudios/aprobar_estudios']],
                                                        ['label' => 'Auditar compras ', 'icon' => 'plus-square-o', 'url' => ['/orden-compra/index_auditar_compras']],
                                                        ['label' => 'Compras auditadas ', 'icon' => 'plus-square-o', 'url' => ['/auditoria-compras/index']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Consultas',
                                                    'icon' => 'question',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Proveedores', 'icon' => 'plus-square-o', 'url' => ['/proveedor/search_consulta_proveedor']],
                                                        ['label' => 'Solicitud de compra', 'icon' => 'plus-square-o', 'url' => ['/solicitud-compra/search_consulta_solicitud_compra']],
                                                        ['label' => 'Ordenes de compra', 'icon' => 'plus-square-o', 'url' => ['/orden-compra/search_consulta_orden_compra']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Movimientos',
                                                    'icon' => 'book',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Items', 'icon' => 'plus-square-o', 'url' => ['/items/index']],
                                                        ['label' => 'Solicitud compras', 'icon' => 'plus-square-o', 'url' => ['/solicitud-compra/index']],
                                                        ['label' => 'Orden compra', 'icon' => 'plus-square-o', 'url' => ['/orden-compra/index']],
                                                    ],
                                                ]
                                            ],
                                        ],
                                        //TERMINA 
                                      //  INICIO MODULO DE PRODUCCION
                                        [
                                            'label' => 'PRODUCCION',
                                            'icon' => 'flask',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'Administración',
                                                    'icon' => 'database',
                                                    'url' => '#',
                                                    'items' => [
                                                       ['label' => 'Medida materia prima', 'icon' => 'plus-square-o', 'url' => ['medida-materia-prima/index']],   
                                                       ['label' => 'Medida producto', 'icon' => 'plus-square-o', 'url' => ['medida-producto-terminado/index']],   
                                                       ['label' => 'Almacen', 'icon' => 'plus-square-o', 'url' => ['almacen/index']],  
                                                       ['label' => 'Grupo', 'icon' => 'plus-square-o', 'url' => ['grupo-producto/index']],
                                                        ['label' => 'Productos', 'icon' => 'plus-square-o', 'url' => ['productos/index']],
                                                       ['label' => 'Presentacion', 'icon' => 'plus-square-o', 'url' => ['presentacion-producto/index']], 
                                                       ['label' => 'Tipo devolucion', 'icon' => 'plus-square-o', 'url' => ['tipo-devolucion-productos/index']], 
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Utilidades',
                                                    'icon' => 'cube',
                                                    'url' => '#',
                                                    'items' => [
                                                        //['label' => 'Parametro presupuesto', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/asignar_producto_presupuesto']],
                                                        [
                                                        'label' => 'Parametros del producto',
                                                        'icon' => 'cart-plus',
                                                        'url' => '#',
                                                        'items' => [
                                                            ['label' => 'Precios y descuentos', 'icon' => 'plus-square-o', 'url' => ['/orden-produccion/crear_precio_venta']],
                                                            ['label' => 'Presupuesto', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/asignar_producto_presupuesto']],
                                                            ['label' => 'Cargar imagenes', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/view_archivo']],
                                                        ]],
                                                        ['label' => 'Cargar devolucion', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/cargar_nota_credito']], 
                                                        ['label' => 'Devolucion productos', 'icon' => 'plus-square-o', 'url' => ['/devolucion-productos/index']], 
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Consultas',
                                                    'icon' => 'question',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Desabastecimiento', 'icon' => 'plus-square-o', 'url' => ['/pedidos/search_desabastecimiento']],
                                                        [
                                                        'label' => 'Materias prima',
                                                        'icon' => 'cart-plus',
                                                        'url' => '#',
                                                        'items' => [
                                                            ['label' => 'Materias primas', 'icon' => 'plus-square-o', 'url' => ['/materia-primas/search_consulta_materias']],
                                                            ['label' => 'Entrada de materias    ', 'icon' => 'plus-square-o', 'url' => ['/entrada-materia-prima/search_consulta_entradas']],
                                                        ]],
                                                        [
                                                        'label' => 'Inventario productos',
                                                        'icon' => 'cart-plus',
                                                        'url' => '#',
                                                        'items' => [
                                                             ['label' => 'Inventario de productos', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/search_consulta_inventario']],
                                                            ['label' => 'Orden producción    ', 'icon' => 'plus-square-o', 'url' => ['/orden-produccion/search_consulta_orden']],
                                                        ]],
                                                        ['label' => 'Devolucion productos    ', 'icon' => 'plus-square-o', 'url' => ['/devolucion-productos/search_consulta_devolucion']],
                                                    ],
                                                ],

                                                [
                                                    'label' => 'Movimientos',
                                                    'icon' => 'book',
                                                    'url' => '#',
                                                    'items' => [
                                                        [
                                                        'label' => 'Materias prima',
                                                        'icon' => 'cart-plus',
                                                        'url' => '#',
                                                        'items' => [
                                                            ['label' => 'Inventario', 'icon' => 'plus-square-o', 'url' => ['/materia-primas/index']],
                                                            ['label' => 'Entradas', 'icon' => 'plus-square-o', 'url' => ['/entrada-materia-prima/index']],
                                                        ]],
                                                        [
                                                        'label' => 'Producto terminado',
                                                        'icon' => 'cart-plus',
                                                        'url' => '#',
                                                        'items' => [
                                                            ['label' => 'Inventario', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/index']],
                                                            ['label' => 'Entradas', 'icon' => 'plus-square-o', 'url' => ['/entrada-producto-terminado/index']],
                                                        ]],
                                                        ['label' => 'Orden produccion', 'icon' => 'plus-square-o', 'url' => ['/orden-produccion/index']],
                                                        ['label' => 'Descargar ME-IP', 'icon' => 'plus-square-o', 'url' => ['/orden-ensamble-producto/index_descargar_inventario']],
                                                        ['label' => 'Solicitud materiales', 'icon' => 'plus-square-o', 'url' => ['/solicitud-materiales/index']],
                                                        ['label' => 'Entrega materiales', 'icon' => 'plus-square-o', 'url' => ['/entrega-materiales/index']],
                                                    ],
                                                ],
                                            ],
                                        ],
                                        //TERMINA 
                                        //  INICIO MODULO DE CALIDAD
                                        [
                                            'label' => 'CALIDAD',
                                            'icon' => 'user',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'Administración',
                                                    'icon' => 'database',
                                                    'url' => '#',
                                                    'items' => [
                                                            ['label' => 'Especificaciones', 'icon' => 'plus-square-o', 'url' => ['especificacion-producto/index']],
                                                            ['label' => 'Concepto analisis', 'icon' => 'plus-square-o', 'url' => ['concepto-analisis/index']],
                                                            ['label' => 'Formula producto', 'icon' => 'plus-square-o', 'url' => ['grupo-producto/index_producto_configuracion','sw' =>0]], 
                                                            ['label' => 'Formula auditoria', 'icon' => 'plus-square-o', 'url' => ['grupo-producto/index_producto_configuracion', 'sw' => 1]], 
                                                        ], 

                                                    ],
                                                [
                                                    'label' => 'Consultas',
                                                    'icon' => 'question',
                                                    'url' => '#',
                                                    'items' => [
                                                     //     ['label' => 'Devolucion productos    ', 'icon' => 'plus-square-o', 'url' => ['/devolucion-productos/search_consulta_devolucion']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Movimiento',
                                                    'icon' => 'cube',
                                                    'url' => '#',
                                                    'items' => [
                                                            ['label' => 'Cargar OP', 'icon' => 'plus-square-o', 'url' => ['/orden-produccion/index_ordenes_produccion']],
                                                            ['label' => 'Auditoria granel', 'icon' => 'plus-square-o', 'url' => ['/orden-produccion/index_resultado_auditoria']],
                                                            ['label' => 'Cargar OE', 'icon' => 'plus-square-o', 'url' => ['/orden-ensamble-producto/index']],
                                                            ['label' => 'Auditoria OE', 'icon' => 'plus-square-o', 'url' => ['/orden-ensamble-producto/index_auditoria_ensamble']],

                                                           ],
                                                ],
                                            ],
                                        ],
                                        //TERMINA 
                                        //INICIO MODULO DE LOGISTICA
                                        [
                                            'label' => 'LOGISTICA',
                                            'icon' => 'automobile',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'Administración',
                                                    'icon' => 'database',
                                                    'url' => '#',
                                                    'items' => [
                                                       ['label' => 'Posiciones', 'icon' => 'plus-square-o', 'url' => ['/posiciones/index']],  
                                                       ['label' => 'Pisos', 'icon' => 'plus-square-o', 'url' => ['/pisos/index']],
                                                       ['label' => 'Tipo de racks ', 'icon' => 'plus-square-o', 'url' => ['/tipo-rack/index']], 
                                                       ['label' => 'Transportadora', 'icon' => 'plus-square-o', 'url' => ['/transportadora/index']], 

                                                    ],
                                                ],
                                                [
                                                    'label' => 'Utilidades',
                                                    'icon' => 'cube',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Listar pedidos', 'icon' => 'plus-square-o', 'url' => ['almacenamiento-producto/listar_pedidos']],
                                                        ['label' => 'Packing', 'icon' => 'plus-square-o', 'url' => ['packing-pedido/index']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Consultas',
                                                    'icon' => 'question',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Almacenamiento', 'icon' => 'plus-square-o', 'url' => ['/almacenamiento-producto/index']],
                                                        ['label' => 'Almacenamiento entradas', 'icon' => 'plus-square-o', 'url' => ['/almacenamiento-producto/search_almacenamiento_entrada']],
                                                        ['label' => 'Pedidos listados', 'icon' => 'plus-square-o', 'url' => ['/almacenamiento-producto/search_pedidos_listados']],
                                                    ],  
                                                ],
                                                [
                                                    'label' => 'Movimientos',
                                                    'icon' => 'book',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Cargar orden produccion', 'icon' => 'plus-square-o', 'url' => ['/almacenamiento-producto/cargar_orden_produccion']],
                                                        ['label' => 'Mover posicion', 'icon' => 'plus-square-o', 'url' => ['/almacenamiento-producto/mover_posiciones']],
                                                        ['label' => 'Cargar entrada producto', 'icon' => 'plus-square-o', 'url' => ['/almacenamiento-producto/cargar_entrada_producto']],
                                                       // ['label' => 'Factura de venta', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/index']],

                                                     ],
                                                ]
                                            ],
                                        ],
                                        //TERMINA LOGISTICA
                                        //INICIO MODULO CRM COMERCIAL
                                        [
                                            'label' => 'CRM COMERCIAL',
                                            'icon' => 'user',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'Administración',
                                                    'icon' => 'database',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Cargos', 'icon' => 'plus-square-o', 'url' => ['/cargos/index']],
                                                        ['label' => 'Coordinadores', 'icon' => 'plus-square-o', 'url' => ['/coordinadores/index']],
                                                        ['label' => 'Agentes comerciales', 'icon' => 'plus-square-o', 'url' => ['/agentes-comerciales/index']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Utilidades',
                                                    'icon' => 'cube',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Pre-pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/index']],
                                                        ['label' => 'Pedidos listos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/pedidoslistos']],
                                                        ['label' => 'Pedido virtual', 'icon' => 'plus-square-o', 'url' => ['/pedidos/pedido_virtual']],
                                                        ['label' => 'Anular pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/anular_pedidos']],
                                                        ['label' => 'Indicador comercial', 'icon' => 'plus-square-o', 'url' => ['/indicador-comercial/index']],
                                                        ['label' => 'Regla comercial', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/regla_comercial']],
                                                        ['label' => 'Citas prospectos', 'icon' => 'plus-square-o', 'url' => ['/cliente-prospecto/listado_cita_prospecto']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Consultas',
                                                    'icon' => 'question',
                                                    'url' => '#',
                                                    'items' => [
                                                       ['label' => 'Agentes comerciales', 'icon' => 'plus-square-o', 'url' => ['/agentes-comerciales/search_consulta_agentes']],
                                                       ['label' => 'Programacion de citas', 'icon' => 'plus-square-o', 'url' => ['/programacion-citas/search_programacion_citas']],
                                                       [
                                                        'label' => 'Indicadores',
                                                        'icon' => 'connectdevelop',
                                                        'url' => '#',
                                                        'items' => [
                                                            ['label' => 'General', 'icon' => 'plus-square-o', 'url' => ['/indicador-comercial/search_indicador_comercial']],
                                                            ['label' => 'Graficas', 'icon' => 'plus-square-o', 'url' => ['/indicador-comercial/search_indicador_vendedor']],

                                                        ]],
                                                        //pedidos comerciales
                                                        [
                                                        'label' => 'Pedidos',
                                                        'icon' => 'list',
                                                        'url' => '#',
                                                        'items' => [
                                                            ['label' => 'General', 'icon' => 'plus-square-o', 'url' => ['/pedidos/search_pedidos']], 
                                                            ['label' => 'Vendedor', 'icon' => 'plus-square-o', 'url' => ['/pedidos/search_pedido_vendedor']],
                                                        ]],
                                                        ['label' => 'Citas prospecto', 'icon' => 'plus-square-o', 'url' => ['/cliente-prospecto/search_cita_prospecto']], 
                                                        [
                                                        'label' => 'Maestros IA',
                                                        'icon' => 'connectdevelop',
                                                        'url' => '#',
                                                        'items' => [
                                                            ['label' => 'Maestro pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/search_maestro_pedidos']],
                                                            //['label' => 'Graficas', 'icon' => 'plus-square-o', 'url' => ['/indicador-comercial/search_indicador_vendedor']],

                                                        ]],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Movimientos',
                                                    'icon' => 'book',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Prospectos', 'icon' => 'plus-square-o', 'url' => ['/cliente-prospecto/index']],
                                                        ['label' => 'Programacion de citas', 'icon' => 'plus-square-o', 'url' => ['/programacion-citas/index']],
                                                        ['label' => 'Gestion comercial', 'icon' => 'plus-square-o', 'url' => ['/programacion-citas/gestion_comercial']],
                                                        ['label' => 'Crear pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/listado_clientes']],


                                                    ],
                                                ]
                                            ],
                                        ],
                                        //TERMINA 

                                        //INICIO MODULO DE FACTURACION
                                        [
                                            'label' => 'FACTURACION',
                                            'icon' => 'money',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'Administración',
                                                    'icon' => 'database',
                                                    'url' => '#',
                                                    'items' => [
                                                       ['label' => 'Resolucion fiscal', 'icon' => 'plus-square-o', 'url' => ['/resolucion-dian/index']],  
                                                       ['label' => 'Tipo de factura', 'icon' => 'plus-square-o', 'url' => ['/tipo-factura-venta/index']], 
                                                       ['label' => 'Clientes', 'icon' => 'plus-square-o', 'url' => ['/clientes/index']],
                                                       ['label' => 'Motivo nota credito', 'icon' => 'plus-square-o', 'url' => ['/motivo-nota-credito/index']], 
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Utilidades',
                                                    'icon' => 'cube',
                                                    'url' => '#',
                                                    'items' => [
                                                        //['label' => 'Clientes', 'icon' => 'plus-square-o', 'url' => ['/clientes/index']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Consultas',
                                                    'icon' => 'question',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Clientes', 'icon' => 'plus-square-o', 'url' => ['/clientes/search_consulta_clientes']],

                                                    [                                            
                                                        'label' => 'Factura produccion',
                                                        'icon' => 'cart-plus',
                                                        'url' => '#',
                                                        'items' => [
                                                            ['label' => 'Maestro factura', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/search_maestro_factura']],
                                                    ]],
                                                ]],
                                                [
                                                    'label' => 'Movimientos',
                                                    'icon' => 'book',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Cargar pedidos', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/crear_factura']],
                                                        ['label' => 'Factura de venta', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/index']],
                                                        ['label' => 'Cargar facturas', 'icon' => 'plus-square-o', 'url' => ['/nota-credito/listado_factura']],
                                                        ['label' => 'Nota crédito', 'icon' => 'plus-square-o', 'url' => ['/nota-credito/index']],

                                                     ],
                                                ],
                                            ],
                                        ],
                                        //TERMINA FACTURACION
                                        //INICIO MODULO DE CARTERA
                                        [
                                            'label' => 'CARTERA',
                                            'icon' => 'list',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'Administración',
                                                    'icon' => 'database',
                                                    'url' => '#',
                                                    'items' => [
                                                       ['label' => 'Tipo recibos', 'icon' => 'plus-square-o', 'url' => ['/tipo-recibo-caja/index']],  

                                                    ],
                                                ],
                                                [
                                                    'label' => 'Utilidades',
                                                    'icon' => 'cube',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Generar cartera', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/search_factura_cartera']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Consultas',
                                                    'icon' => 'question',
                                                    'url' => '#',
                                                    'items' => [
                                                       ['label' => 'Facturas de venta', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/search_factura_venta']],
                                                    ],
                                                ],
                                                [
                                                    'label' => 'Movimientos',
                                                    'icon' => 'book',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Cargar cartera', 'icon' => 'plus-square-o', 'url' => ['/recibo-caja/cargar_cartera']],
                                                        ['label' => 'Recibo de caja', 'icon' => 'plus-square-o', 'url' => ['/recibo-caja/index']],

                                                     ],
                                                ]
                                            ],
                                        ],
                                        //TERMINA CARTERA
                                        //MODULO GENERAL
                                        [
                                            'label' => 'GENERAL',
                                            'icon' => 'wrench',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Configuración', 'icon' => 'cog', 'url' => ['matricula-empresa/parametros', 'id' => 1]],
                                                ['label' => 'Empresa', 'icon' => 'nav-icon fas fa-file', 'url' => ['matricula-empresa/matricula', 'id' => 1]],
                                                [
                                                'label' => 'Contenido',
                                                'icon' => 'comment',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Formato principal', 'icon' => 'tumblr-square', 'url' => ['formato-contenido/index']],
                                                ]],
                                            ],
                                        ],

                                    ],
                                ],
                            ],
                        ]
                )?> 
                <?php } else {?>
                    <?php if($tokenModulo == 2){?> <!--COMIENZA EL MODULO PARA NOMINA DEL SOFTWARE COMPLETO-->
                           <?=  dmstr\widgets\Menu::widget(
                            [
                                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                                    'items' => [
                                        ['label' => 'MENÚ PRINCIPAL', 'options' => ['class' => 'header']],
                                        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                                        [
                                            'label' => 'TALENTO HUMANO ',
                                            'icon' => 'share',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'CONTRATACION',
                                                    'icon' => 'dashboard',
                                                    'url' => '#',
                                                    'items' => [
                                                        [
                                                            'label' => 'Administración',
                                                            'icon' => 'database',
                                                            'url' => '#',
                                                            'items' => [
                                                                ['label' => 'Departamento', 'icon' => 'plus-square-o', 'url' => ['/departamentos/index']],  
                                                                ['label' => 'Municipio', 'icon' => 'plus-square-o', 'url' => ['/municipios/index']],
                                                                ['label' => 'Tipo Documento', 'icon' => 'plus-square-o', 'url' => ['/tipo-documento/index']],
                                                                ['label' => 'Cargos', 'icon' => 'plus-square-o', 'url' => ['/cargos/index']],
                                                                [
                                                                'label' => 'Bancos',
                                                                'icon' => 'connectdevelop',
                                                                'url' => '#',
                                                                'items' => [
                                                                    ['label' => 'Empresariales', 'icon' => 'plus-square-o', 'url' => ['/entidad-bancarias/index']],
                                                                    ['label' => 'Empleados', 'icon' => 'plus-square-o', 'url' => ['/entidad-bancarias/index_banco_empleado']],
                                                                    ]
                                                                ],
                                                            ],
                                                        ],
                                                        //todo lo referente a empleados
                                                        [
                                                            'label' => 'Empleados',
                                                            'icon' => 'user',
                                                            'url' => '#',
                                                            'items' => [
                                                                [
                                                                'label' => 'Configuración',
                                                                'icon' => 'connectdevelop',
                                                                'url' => '#',
                                                                'items' => [
                                                                    ['label' => 'Tipo de estudio', 'icon' => 'plus-square-o', 'url' => ['/departamentos/indexdepartamento']],
                                                                    ]
                                                                ],
                                                                ['label' => 'Empleado', 'icon' => 'plus-square-o', 'url' => ['/empleados/index']],
                                                                
                                                                ],
                                                        ],
                                                        //todo la referente a contratos
                                                        [
                                                            'label' => 'Contratos',
                                                            'icon' => 'list',
                                                            'url' => '#',
                                                            'items' => [
                                                                [
                                                                'label' => 'Configuración',
                                                                'icon' => 'connectdevelop',
                                                                'url' => '#',
                                                                'items' => [
                                                                    ['label' => 'Caja de compensación', 'icon' => 'plus-square-o', 'url' => ['/caja-compensacion/index']],
                                                                    ['label' => 'Centro trabajo', 'icon' => 'plus-square-o', 'url' => ['/centro-trabajo/index']],
                                                                    ['label' => 'Entidad pension', 'icon' => 'plus-square-o', 'url' => ['/entidad-pension/index']],
                                                                    ['label' => 'Entidad salud', 'icon' => 'plus-square-o', 'url' => ['/entidad-salud/index']],
                                                                    ['label' => 'Tipo contrato', 'icon' => 'plus-square-o', 'url' => ['/tipo-contrato/index']],
                                                                    ['label' => 'Tiempo servicio', 'icon' => 'plus-square-o', 'url' => ['/tiempo-servicio/index']],
                                                                    ['label' => 'Tipo cotizante', 'icon' => 'plus-square-o', 'url' => ['/tipo-cotizante/index']],
                                                                    ['label' => 'Subtipo cotizante', 'icon' => 'plus-square-o', 'url' => ['/subtipo-cotizante/index']],
                                                                    ['label' => 'Motivo terminación', 'icon' => 'plus-square-o', 'url' => ['/motivo-terminacion/index']],
                                                                    ['label' => 'Grupo de pago', 'icon' => 'plus-square-o', 'url' => ['/grupo-pago/index']],
                                                                    ['label' => 'Periodo de pago', 'icon' => 'plus-square-o', 'url' => ['/periodo-pago/index']],
                                                                    ['label' => 'Parametros', 'icon' => 'plus-square-o', 'url' => ['/contratos/parametro_contrato']],
                                                                    ]
                                                                ],
                                                                ['label' => 'Contrato', 'icon' => 'plus-square-o', 'url' => ['/contratos/index']],
                                                                
                                                                ],
                                                        ],
                                                    ],
                                                ],
                                                //INICIA MODULO DE GESTION HUMANA
                                                [
                                                    'label' => 'GESTION HUMANA',
                                                    'icon' => 'user',
                                                    'url' => '#',
                                                    'items' => [
                                                        [
                                                            'label' => 'Administracion',
                                                            'icon' => 'database',
                                                            'url' => '#',
                                                            'items' => [
                                                                ['label' => 'Incapacidades', 'icon' => 'plus-square-o', 'url' => ['/incapacidades/index']],  
                                                                ['label' => 'Licencias', 'icon' => 'plus-square-o', 'url' => ['/licencias/index']],
                                                                [
                                                                'label' => 'Bancos',
                                                                'icon' => 'connectdevelop',
                                                                'url' => '#',
                                                                'items' => [
                                                                    ['label' => 'Empresariales', 'icon' => 'plus-square-o', 'url' => ['/entidad-bancarias/index']],
                                                                    ['label' => 'Empleados', 'icon' => 'plus-square-o', 'url' => ['/entidad-bancarias/index_banco_empleado']],
                                                                    ]
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],//TERMINA /MODULO
                                                //INICIA MODULO DE SEGURIDAD EN EL TRABAJO
                                                [
                                                    'label' => 'SG - SST',
                                                    'icon' => 'medkit',
                                                    'url' => '#',
                                                    'items' => [
                                                        [
                                                            'label' => 'Administracion',
                                                            'icon' => 'database',
                                                            'url' => '#',
                                                            'items' => [
                                                                ['label' => 'Diagnostico medico', 'icon' => 'plus-square-o', 'url' => ['diagnostico-incapacidad/index']],
                                                                [
                                                                'label' => 'Configuracion',
                                                                'icon' => 'connectdevelop',
                                                                'url' => '#',
                                                                'items' => [
                                                                    ['label' => 'Incapacidades', 'icon' => 'plus-square-o', 'url' => ['configuracion-incapacidad/index']],  
                                                                    ['label' => 'Licencias', 'icon' => 'plus-square-o', 'url' => ['configuracion-licencia/index']],
                                                                    ['label' => 'Licencias', 'icon' => 'plus-square-o', 'url' => ['configuracion-licencia/index']],
                                                                    ]
                                                                ],
                                                            ],
                                                        ],
                                                        //COMIENZA MEDU DE MOVIMIENTO
                                                        [
                                                            'label' => 'Movimiento',
                                                            'icon' => 'book',
                                                            'url' => '#',
                                                            'items' => [
                                                                ['label' => 'Incapacidades', 'icon' => 'plus-square-o', 'url' => ['incapacidad/index']],
                                                                ['label' => 'Licencias', 'icon' => 'plus-square-o', 'url' => ['licencia/index']],
                                                            ],
                                                        ],
                                                    ],
                                                ],//TERMINA /MODULO
                                                //INICIA NOMINA
                                                [
                                                    'label' => 'NOMINA',
                                                    'icon' => 'money',
                                                    'url' => '#',
                                                    'items' => [
                                                        [
                                                            'label' => 'Administracion',
                                                            'icon' => 'database',
                                                            'url' => '#',
                                                            'items' => [
                                                               // ['label' => 'Diagnostico medico', 'icon' => 'plus-square-o', 'url' => ['diagnostico-incapacidad/index']],
                                                                [
                                                                'label' => 'Configuracion',
                                                                'icon' => 'connectdevelop',
                                                                'url' => '#',
                                                                'items' => [
                                                                    ['label' => 'Concepto de nomina', 'icon' => 'plus-square-o', 'url' => ['concepto-salarios/index']],  
                                                                    ['label' => 'Salarios', 'icon' => 'plus-square-o', 'url' => ['configuracion-salario/index']],  
                                                                    ['label' => 'Tipos de credito', 'icon' => 'plus-square-o', 'url' => ['configuracion-credito/index']],
                                                                    ]
                                                                ],
                                                            ],
                                                        ],
                                                        //COMIENZA MEDU DE MOVIMIENTO
                                                        [
                                                            'label' => 'Movimiento',
                                                            'icon' => 'book',
                                                            'url' => '#',
                                                            'items' => [
                                                                ['label' => 'Generar nomina', 'icon' => 'plus-square-o', 'url' => ['programacion-nomina/index']],
                                                               // ['label' => 'Licencias', 'icon' => 'plus-square-o', 'url' => ['licencia/index']],
                                                            ],
                                                        ],
                                                        //COMIENZA MEDU DE UTILIDADES
                                                        [
                                                            'label' => 'Utilidades',
                                                            'icon' => 'cube',
                                                            'url' => '#',
                                                            'items' => [
                                                                 ['label' => 'Periodos de pago', 'icon' => 'plus-square-o', 'url' => ['periodo-pago-nomina/index_search']],
                                                               // ['label' => 'Licencias', 'icon' => 'plus-square-o', 'url' => ['licencia/index']],
                                                            ],
                                                        ],
                                                        //COMIENZA MEDU DE PROCESOS
                                                        [
                                                            'label' => 'Procesos',
                                                            'icon' => 'exchange',
                                                            'url' => '#',
                                                            'items' => [
                                                                 ['label' => 'Creditos', 'icon' => 'plus-square-o', 'url' => ['credito/index']],
                                                                 ['label' => 'Pago adicional x fecha', 'icon' => 'plus-square-o', 'url' => ['pago-adicional-fecha/index']],
                                                            ],
                                                        ],
                                                    ],
                                                ],//TERMINA /MODULO
                                            ],
                                        ],
                                    ],    
                                ] )?><!--TERMINA EL WITGET-->>
                    <?php }else{?>
                    <?php }?>
                <?php }?>          
            
        <?php }else {?><!--COMIENZA EL MENU PARA PUNTOS DE VENTAS--> 
            <?=
            dmstr\widgets\Menu::widget(
                    [
                        'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                        'items' => [
                            ['label' => 'MENÚ PRINCIPAL', 'options' => ['class' => 'header']],
                            //['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                            //['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                            ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                            [
                                'label' => 'DIAMANTE ERP ',
                                'icon' => 'share',
                                'url' => '#',
                                'items' => [
                                    //['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                                    //['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                                    [
                                        'label' => 'CONFIGURACION',
                                        'icon' => 'database',
                                        'url' => '#',
                                        'items' => [
                                            [
                                                'label' => 'Administración',
                                                'icon' => 'database',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Departamento', 'icon' => 'plus-square-o', 'url' => ['/departamentos/index']],  
                                                    ['label' => 'Municipio', 'icon' => 'plus-square-o', 'url' => ['/municipios/index']],
                                                    ['label' => 'Entidad bancaria', 'icon' => 'plus-square-o', 'url' => ['/entidad-bancarias/index']],
                                                    ['label' => 'Tipo Documento', 'icon' => 'plus-square-o', 'url' => ['/tipo-documento/index']],
                                                ],
                                            ],
                                            
                                            [
                                                'label' => 'Consultas',
                                                'icon' => 'question',
                                                'url' => '#',
                                                'items' => [
                                                  ['label' => 'Departamentos', 'icon' => 'plus-square-o', 'url' => ['/departamentos/indexdepartamento']],
                                                  ['label' => 'Municipios', 'icon' => 'plus-square-o', 'url' => ['/municipios/indexmunicipio']],
                                                ],
                                            ],
                                        ],
                                    ],


                                    //INICIO DEL MENU COMPRAS
                                     [
                                        'label' => 'COMPRAS',
                                        'icon' => 'dollar',
                                        'url' => '#',
                                        'items' => [
                                            [
                                                'label' => 'Administración',
                                                'icon' => 'database',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Requisitos', 'icon' => 'plus-square-o', 'url' => ['/listado-requisitos/index']],
                                                    ['label' => 'Tipo ordenes', 'icon' => 'plus-square-o', 'url' => ['/tipo-orden-compra/index']],
                                                    ['label' => 'Tipos de solicitud', 'icon' => 'plus-square-o', 'url' => ['/tipo-solicitud/index']],
                                                    ['label' => 'Proveedor', 'icon' => 'plus-square-o', 'url' => ['/proveedor/index']],
                                                ],
                                            ],
                                            [
                                                'label' => 'Utilidades',
                                                'icon' => 'cube',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Estudios proveedor', 'icon' => 'plus-square-o', 'url' => ['/proveedor-estudios/index']],
                                                    ['label' => 'Aprobar proveedor ', 'icon' => 'plus-square-o', 'url' => ['/proveedor-estudios/aprobar_estudios']],
                                                    ['label' => 'Auditar compras ', 'icon' => 'plus-square-o', 'url' => ['/orden-compra/index_auditar_compras']],
                                                    ['label' => 'Compras auditadas ', 'icon' => 'plus-square-o', 'url' => ['/auditoria-compras/index']],
                                                ],
                                            ],
                                            [
                                                'label' => 'Consultas',
                                                'icon' => 'question',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Proveedores', 'icon' => 'plus-square-o', 'url' => ['/proveedor/search_consulta_proveedor']],
                                                    ['label' => 'Solicitud de compra', 'icon' => 'plus-square-o', 'url' => ['/solicitud-compra/search_consulta_solicitud_compra']],
                                                    ['label' => 'Ordenes de compra', 'icon' => 'plus-square-o', 'url' => ['/orden-compra/search_consulta_orden_compra']],
                                                ],
                                            ],
                                            [
                                                'label' => 'Movimientos',
                                                'icon' => 'book',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Items', 'icon' => 'plus-square-o', 'url' => ['/items/index']],
                                                    ['label' => 'Solicitud compras', 'icon' => 'plus-square-o', 'url' => ['/solicitud-compra/index']],
                                                    ['label' => 'Orden compra', 'icon' => 'plus-square-o', 'url' => ['/orden-compra/index']],
                                                ],
                                            ]
                                        ],
                                    ],
                                    //TERMINA 
                                   
                                    //INICIO MODULO CRM COMERCIAL
                                    [
                                        'label' => 'CRM COMERCIAL',
                                        'icon' => 'user',
                                        'url' => '#',
                                        'items' => [
                                            [
                                                'label' => 'Administración',
                                                'icon' => 'database',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Cargos', 'icon' => 'plus-square-o', 'url' => ['/cargos/index']],
                                                    ['label' => 'Coordinadores', 'icon' => 'plus-square-o', 'url' => ['/coordinadores/index']],
                                                    ['label' => 'Agentes comerciales', 'icon' => 'plus-square-o', 'url' => ['/agentes-comerciales/index']],
                                                ],
                                            ],
                                        ],
                                    ],
                                    //TERMINA 
                                       //INICIO MODULO INVENTARIO
                                    [
                                        'label' => 'INVENTARIO',
                                        'icon' => 'shopping-cart',
                                        'url' => '#',
                                        'items' => [
                                            [
                                                'label' => 'Administración',
                                                'icon' => 'database',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Punto de venta', 'icon' => 'plus-square-o', 'url' => ['/punto-venta/index']],
                                                ],
                                            ],
                                            [
                                                'label' => 'Utilidades',
                                                'icon' => 'cube',
                                                'url' => '#',
                                                'items' => [
                                                   // ['label' => 'Pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/index']],
                                                   [
                                                    'label' => 'Parametros',
                                                    'icon' => 'connectdevelop',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Precio de venta', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/crear_precio_venta']],
                                                        ['label' => 'Cargar imagenes', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/validador_imagen']],
                                                        ['label' => 'Enviar masivo', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/send_masivo_producto']],

                                                    ]],
                                                    ['label' => 'Traslados pto venta', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/traslado_producto']],
                                                   // ['label' => 'Indicador comercial', 'icon' => 'plus-square-o', 'url' => ['/indicador-comercial/index']],
                                                   // ['label' => 'Regla comercial', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/regla_comercial']],
                                                    //['label' => 'Citas prospectos', 'icon' => 'plus-square-o', 'url' => ['/cliente-prospecto/listado_cita_prospecto']],
                                                ],
                                            ],
                                            [
                                                'label' => 'Consultas',
                                                'icon' => 'question',
                                                'url' => '#',
                                                'items' => [
                                                   ['label' => 'Inventario', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/search_inventario']],
                                                   ['label' => 'Rerefencias', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/search_referencias']],
                                                  [
                                                    'label' => 'Costo y rentabilidad',
                                                    'icon' => 'connectdevelop',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Factura de venta', 'icon' => 'plus-square-o', 'url' => ['/factura-venta-punto/search_producto_vendido']],
                                                        ['label' => 'Remision', 'icon' => 'plus-square-o', 'url' => ['/remisiones/search_producto_vendido']],
                                                        ['label' => 'Producto + vendido', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/producto_masvendido']],
                                                         // ['label' => 'Cargar imagenes', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/validador_imagen']],

                                                    ]],
                                                   [
                                                    'label' => 'Indicadores',
                                                    'icon' => 'connectdevelop',
                                                    'url' => '#',
                                                    'items' => [
                                                    //    ['label' => 'General', 'icon' => 'plus-square-o', 'url' => ['/indicador-comercial/search_indicador_comercial']],
                                                     //   ['label' => 'Graficas', 'icon' => 'plus-square-o', 'url' => ['/indicador-comercial/search_indicador_vendedor']],

                                                    ]],
                                                    //['label' => 'Pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/search_pedidos']], 
                                                    //['label' => 'Citas prospecto', 'icon' => 'plus-square-o', 'url' => ['/cliente-prospecto/search_cita_prospecto']], 
                                                    [
                                                    'label' => 'Maestros IA',
                                                    'icon' => 'connectdevelop',
                                                    'url' => '#',
                                                    'items' => [
                                                       // ['label' => 'Ventas clientes', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/search_maestro_referencia']],
                                                        //['label' => 'Graficas', 'icon' => 'plus-square-o', 'url' => ['/indicador-comercial/search_indicador_vendedor']],

                                                    ]],
                                                ],
                                            ],
                                            [
                                                'label' => 'Movimientos',
                                                'icon' => 'book',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Inventarios', 'icon' => 'plus-square-o', 'url' => ['/inventario-punto-venta/index']],
                                                    ['label' => 'Entradas', 'icon' => 'plus-square-o', 'url' => ['/entrada-productos-inventario/index']],
                                                   // ['label' => 'Gestion comercial', 'icon' => 'plus-square-o', 'url' => ['/programacion-citas/gestion_comercial']],
                                                   // ['label' => 'Crear pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/listado_clientes']],


                                                ],
                                            ],

                                        ],
                                    ],
                                    //TERMINA INVENTARIO
                                    //INICIO MODULO DE FACTURACION
                                    [
                                        'label' => 'FACTURACION',
                                        'icon' => 'money',
                                        'url' => '#',
                                        'items' => [
                                            [
                                                'label' => 'Administración',
                                                'icon' => 'database',
                                                'url' => '#',
                                                'items' => [
                                                   ['label' => 'Resolucion fiscal', 'icon' => 'plus-square-o', 'url' => ['/resolucion-dian/index']],  
                                                   ['label' => 'Tipo de factura', 'icon' => 'plus-square-o', 'url' => ['/tipo-factura-venta/index']], 
                                                   ['label' => 'Clientes', 'icon' => 'plus-square-o', 'url' => ['/clientes/index']],
                                                   ['label' => 'Motivo nota credito', 'icon' => 'plus-square-o', 'url' => ['/motivo-nota-credito/index']], 
                                                ],
                                            ],
                                            [
                                                'label' => 'Utilidades',
                                                'icon' => 'cube',
                                                'url' => '#',
                                                'items' => [
                                                    //['label' => 'Clientes', 'icon' => 'plus-square-o', 'url' => ['/clientes/index']],
                                                ],
                                            ],
                                            [
                                                'label' => 'Consultas',
                                                'icon' => 'question',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Clientes', 'icon' => 'plus-square-o', 'url' => ['/clientes/search_consulta_clientes']],

                                                
                                                [
                                                    'label' => 'Facturas pto venta',
                                                    'icon' => 'cart-plus',
                                                    'url' => '#',
                                                    'items' => [
                                                        ['label' => 'Maestro factura', 'icon' => 'plus-square-o', 'url' => ['/factura-venta-punto/search_maestro_factura']],
                                                        ['label' => 'Ventas clientes', 'icon' => 'plus-square-o', 'url' => ['/factura-venta-punto/search_maestro_referencia']],
                                                        ['label' => 'Cierre caja', 'icon' => 'plus-square-o', 'url' => ['/cierre-caja/search_cierre_caja']],
                                                ]],
                                            ]],
                                            [
                                                'label' => 'Movimientos',
                                                'icon' => 'book',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Factura punto', 'icon' => 'plus-square-o', 'url' => ['/factura-venta-punto/index']],
                                                    ['label' => 'Remision', 'icon' => 'plus-square-o', 'url' => ['/remisiones/index']],
                                                    ['label' => 'Cierre de caja', 'icon' => 'plus-square-o', 'url' => ['/cierre-caja/index']],
                                                    ['label' => 'Cargar facturas', 'icon' => 'plus-square-o', 'url' => ['/nota-credito/listado_factura']],
                                                    ['label' => 'Nota crédito', 'icon' => 'plus-square-o', 'url' => ['/nota-credito/index']],

                                                 ],
                                            ],
                                            
                                        ],
                                    ],
                                    //TERMINA FACTURACION
                                    //INICIO MODULO DE CARTERA
                                    [
                                        'label' => 'CARTERA',
                                        'icon' => 'list',
                                        'url' => '#',
                                        'items' => [
                                            [
                                                'label' => 'Administración',
                                                'icon' => 'database',
                                                'url' => '#',
                                                'items' => [
                                                   ['label' => 'Tipo recibos', 'icon' => 'plus-square-o', 'url' => ['/tipo-recibo-caja/index']],  

                                                ],
                                            ],
                                            [
                                                'label' => 'Utilidades',
                                                'icon' => 'cube',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Generar cartera', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/search_factura_cartera']],
                                                ],
                                            ],
                                            [
                                                'label' => 'Consultas',
                                                'icon' => 'question',
                                                'url' => '#',
                                                'items' => [
                                                   ['label' => 'Facturas de venta', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/search_factura_venta']],
                                                ],
                                            ],
                                            [
                                                'label' => 'Movimientos',
                                                'icon' => 'book',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Cargar cartera', 'icon' => 'plus-square-o', 'url' => ['/recibo-caja/cargar_cartera']],
                                                    ['label' => 'Recibo de caja', 'icon' => 'plus-square-o', 'url' => ['/recibo-caja/index']],

                                                 ],
                                            ]
                                        ],
                                    ],
                                    //TERMINA CARTERA
                                    //MODULO GENERAL
                                    [
                                        'label' => 'GENERAL',
                                        'icon' => 'wrench',
                                        'url' => '#',
                                        'items' => [
                                         //   ['label' => 'Configuración', 'icon' => 'cog', 'url' => ['parametros/parametros', 'id' => 1]],
                                            ['label' => 'Empresa', 'icon' => 'nav-icon fas fa-file', 'url' => ['matricula-empresa/matricula', 'id' => 1]],
                                            [
                                            'label' => 'Contenido',
                                            'icon' => 'comment',
                                            'url' => '#',
                                            'items' => [
                                               // ['label' => 'Formato principal', 'icon' => 'tumblr-square', 'url' => ['formato-contenido/index']],
                                            ]],
                                        ],
                                    ],

                                ],
                            ],
                        ],
                    ]
            )
        
        ?>
        <?php }?>
        

    </section>

</aside>
