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
                                            [
                                                'label' => 'Demandas',
                                                'icon' => 'cube',
                                                'url' => '#',
                                                'items' => [
                                                  //  ['label' => 'Juez', 'icon' => 'plus-square-o', 'url' => ['juez/index']],
                                                  
                                            ]],
                                            
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
                                              //  ['label' => 'Contabiizar', 'icon' => 'plus-square-o', 'url' => ['/contabilizar/contabilizar']],
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
                                               ['label' => 'Presentacion productos', 'icon' => 'plus-square-o', 'url' => ['presentacion-producto/index']], 
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
                                                    ['label' => 'Precios de venta', 'icon' => 'plus-square-o', 'url' => ['/orden-produccion/crear_precio_venta']],
                                                    ['label' => 'Configuración', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/asignar_producto_presupuesto']],
                                                    ['label' => 'Cargar imagenes', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/validador_imagen']],
                                                ]],
                                            ],
                                        ],
                                        [
                                            'label' => 'Consultas',
                                            'icon' => 'question',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Materias primas', 'icon' => 'plus-square-o', 'url' => ['/materia-primas/search_consulta_materias']],
                                                ['label' => 'Inventario de productos', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/search_consulta_inventario']],
                                                ['label' => 'Entrada de materias    ', 'icon' => 'plus-square-o', 'url' => ['/entrada-materia-prima/search_consulta_entradas']],
                                                ['label' => 'Orden producción    ', 'icon' => 'plus-square-o', 'url' => ['/orden-produccion/search_consulta_orden']],
                                            ],
                                        ],
                                      
                                        [
                                            'label' => 'Movimientos',
                                            'icon' => 'book',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Materias prima', 'icon' => 'plus-square-o', 'url' => ['/materia-primas/index']],
                                                 ['label' => 'Inventario de productos', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/index']],
                                                ['label' => 'Entrada materia prima', 'icon' => 'plus-square-o', 'url' => ['/entrada-materia-prima/index']],
                                                ['label' => 'Orden produccion', 'icon' => 'plus-square-o', 'url' => ['/orden-produccion/index']],
                                            ],
                                        ],
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
                                        [
                                            'label' => 'Utilidades',
                                            'icon' => 'cube',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/index']],
                                                  ['label' => 'Anular pedidos', 'icon' => 'plus-square-o', 'url' => ['/pedidos/anular_pedidos']],
                                                ['label' => 'Indicador comercial', 'icon' => 'plus-square-o', 'url' => ['/indicador-comercial/index']],
                                                ['label' => 'Regla comercial', 'icon' => 'plus-square-o', 'url' => ['/inventario-productos/regla_comercial']],
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
                                            ],
                                        ],
                                        [
                                            'label' => 'Movimientos',
                                            'icon' => 'book',
                                            'url' => '#',
                                            'items' => [
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
                                            ],
                                        ],
                                        [
                                            'label' => 'Movimientos',
                                            'icon' => 'book',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Cargar factura', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/crear_factura']],
                                                ['label' => 'Factura de venta', 'icon' => 'plus-square-o', 'url' => ['/factura-venta/index']],
                                               
                                             ],
                                        ]
                                    ],
                                ],
                                //TERMINA FACTURACION
                                
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
