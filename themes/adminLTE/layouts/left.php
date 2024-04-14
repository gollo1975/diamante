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
                              // MODULO DE PRODUCCION
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
                                               ['label' => 'Grupo productos', 'icon' => 'plus-square-o', 'url' => ['grupo-producto/index']],
                                               ['label' => 'Presentacion producto', 'icon' => 'plus-square-o', 'url' => ['presentacion-producto/index']], 
                                               ['label' => 'Formula producto', 'icon' => 'plus-square-o', 'url' => ['grupo-producto/index_producto_configuracion','sw' =>0]], 
                                               ['label' => 'Formula analisis', 'icon' => 'plus-square-o', 'url' => ['grupo-producto/index_producto_configuracion', 'sw' => 1]], 
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
                                                    ['label' => 'Configuración', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/asignar_producto_presupuesto']],
                                                    ['label' => 'Cargar imagenes', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/validador_imagen']],
                                                ]],
                                                [
                                                'label' => 'Control calidad',
                                                'icon' => 'check',
                                                'url' => '#',
                                                'items' => [
                                                    ['label' => 'Orden de produccion', 'icon' => 'plus-square-o', 'url' => ['/orden-produccion/index_ordenes_produccion']],
                                                 //   ['label' => 'Configuración', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/asignar_producto_presupuesto']],
                                                    
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
                                                
                                            ],
                                        ],
                                        [
                                            'label' => 'Utilidades',
                                            'icon' => 'cube',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Listar pedidos', 'icon' => 'plus-square-o', 'url' => ['almacenamiento-producto/listar_pedidos']],
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
                                                ['label' => 'Pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/index']],
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
                                                ['label' => 'Pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/search_pedidos']], 
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
                                              //  ['label' => 'Coordinadores', 'icon' => 'plus-square-o', 'url' => ['/coordinadores/index']],
                                               // ['label' => 'Agentes comerciales', 'icon' => 'plus-square-o', 'url' => ['/agentes-comerciales/index']],
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
                                                    
                                                ]],
                                              //    ['label' => 'Anular pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/anular_pedidos']],
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
                                            //   ['label' => 'Agentes comerciales', 'icon' => 'plus-square-o', 'url' => ['/agentes-comerciales/search_consulta_agentes']],
                                              // ['label' => 'Programacion de citas', 'icon' => 'plus-square-o', 'url' => ['/programacion-citas/search_programacion_citas']],
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
                                                  //  ['label' => 'Maestro pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/search_maestro_pedidos']],
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
                                              //  ['label' => 'Programacion de citas', 'icon' => 'plus-square-o', 'url' => ['/programacion-citas/index']],
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
                                                ['label' => 'Maestro factura', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/search_maestro_factura']],
                                            ],  
                                        ],
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
                                        [
                                            'label' => 'Punto de venta',
                                            'icon' => 'folder-open',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Factura punto', 'icon' => 'plus-square-o', 'url' => ['/factura-venta-punto/index']],
                                           //     ['label' => 'Cargar facturas', 'icon' => 'plus-square-o', 'url' => ['/nota-credito/listado_factura']],
                                             //   ['label' => 'Nota crédito', 'icon' => 'plus-square-o', 'url' => ['/nota-credito/index']],
                                               
                                             ],
                                        ]
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
                                        ['label' => 'Configuración', 'icon' => 'cog', 'url' => ['parametros/parametros', 'id' => 1]],
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
        )
        ?>

    </section>

</aside>
