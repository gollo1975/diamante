<?php

use inquid\pdf\FPDF;
use app\models\OrdenProduccion;
use app\models\OrdenProduccionProductos;
use app\models\OrdenProduccionMateriaPrima;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_orden_produccion = $GLOBALS['id_orden_produccion'];
        $orden = OrdenProduccion::findOne($id_orden_produccion);
        $config = MatriculaEmpresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);
        //Logo
       $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 19);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(53, 9);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("EMPRESA:"), 0, 0, 'l', 0);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 0);
        $this->SetXY(30, 5);
        //FIN
        $this->SetXY(53, 13);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("NIT:"), 0, 0, '0', 0);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 0);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 17);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("DIRECCION:"), 0, 0, '0', 0);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->direccion), 0, 0, 'L', 0);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 21);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("TELEFONO:"), 0, 0, '0', 0);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->telefono), 0, 0, 'L', 0);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 25);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("MUNICIPIO:"), 0, 0, '0', 0);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->codigoMunicipio->municipio." - ".$config->codigoDepartamento->departamento), 0, 0, 'L', 0);
        $this->SetXY(40, 5);

        //FIN
        $this->SetXY(10, 32);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 32.5);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Prestaciones sociales
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(10, 39);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(162, 7, utf8_decode("ORDEN DE PRODUCCION"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($orden->numero_orden, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("TIPO ORDEN:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($orden->tipoOrden), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("GRUPO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($orden->grupo->nombre_grupo), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("T. LOTE:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(10, 5, ''. number_format($orden->tamano_lote,0), 0, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(22, 5, utf8_decode("UNIDADES:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, ' '. number_format($orden->unidades, 0), 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("AUTORIZADO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($orden->autorizadoOrden), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("ALMACEN:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($orden->almacen->almacen), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("CERRADO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, utf8_decode($orden->cerrarOrden), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(17, 5, utf8_decode("C. UNITARIO:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, ''. number_format($orden->costo_unitario,0), 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("USER:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($orden->user_name), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("F. REGISTRO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($orden->fecha_registro), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("F. ENTREGA:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, utf8_decode($orden->fecha_entrega), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(17, 5, utf8_decode("F. PROCESO:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, utf8_decode($orden->fecha_proceso), 0, 0, 'L', 1);
       
        //FIN
         $this->EncabezadoDetalles();
                 
         
        //linea del llogo
        $this->Line(10,8,202,8);//linea superior horizontal
        $this->Line(10,30,10,8);//primera linea en y
        $this->Line(45,30,45,8);//segunda linea en y
        $this->Line(130,30,130,8);//tercera linea en y
        $this->Line(202,30,202,8);//cuarta linea en y
        $this->Line(10,30,202,30);//linea inferior horizontal
       
        //Lineas del encabezado
        $this->Line(10,70,10,102);
        $this->Line(33,70,33,102);
        $this->Line(10,77,202,77);//fila entre registro
        $this->Line(96,70,96,102);
        $this->Line(10,81,202,81);//fila entre registro
        $this->Line(119,70,119,102);
        $this->Line(10,86,202,86);//fila entre registro
        $this->Line(147,70,147,102);
        $this->Line(10,91,202,91);//fila entre registro
        $this->Line(177,70,177,102);
        $this->Line(10,96,202,96);//fila entre registro
        $this->Line(202,70,202,102);
        $this->Line(10,102,202,102);//linea horizontal inferior  
        //Líneas MATERIA PRIMAS FACE 1
        $this->Line(10,110,10,170);
        $this->Line(74,120,74,170);
        $this->Line(10,128,202,128);//fila entre registro
        $this->Line(138,120,138,170);
        $this->Line(10,132,202,132);//fila entre registro
        $this->Line(202,110,202,170);
        $this->Line(10,136,202,136);//fila entre registro
        $this->Line(10,140,202,140);//fila entre registro
        $this->Line(10,144,202,144);//fila entre registro
        $this->Line(10,148,202,148);//fila entre registro
        $this->Line(10,152,202,152);//fila entre registro
        $this->Line(10,156,202,156);//fila entre registro
        $this->Line(10,160,202,160);//fila entre registro
        $this->Line(10,164,202,164);//fila entre registro
        $this->Line(10,170,202,170);//linea horizontal inferior
        //Líneas MATERIA PRIMA FACE 2
        $this->Line(10,110,10,230);
        $this->Line(10,191,202,191);//fila entre registro
        $this->Line(74,110,74,230);
        $this->Line(10,195,202,195);//fila entre registro
        $this->Line(138,110,138,230);
        $this->Line(10,199,202,199);//fila entre registro
        $this->Line(202,110,202,230);
        $this->Line(10,203,202,203);//fila entre registro
        $this->Line(10,207,202,207);//fila entre registro
        $this->Line(10,211,202,211);//fila entre registro
        $this->Line(10,215,202,215);//fila entre registro
        $this->Line(10,219,202,219);//fila entre registro
        $this->Line(10,223,202,223);//fila entre registro
        $this->Line(10,230,202,230);//linea horizontal inferior
        
      
    }
    function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('CODIGO', ('PRODUCTO'), ('CANTIDAD'), ('No LOTE'), ('MEDIDA'),('FECHA VCTO'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(23, 63, 23, 28, 30, 25);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(5);
    }        
                      

    function Body($pdf,$model) {        
        $materiales = app\models\OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion',$model->id_orden_produccion])
                                                                   ->andWhere(['=','id_fase', 1])->orderBy('id_detalle asc')->all();
        $fase2 = app\models\OrdenProduccionFaseInicial::find()->where(['=','id_orden_produccion',$model->id_orden_produccion])
                                                                   ->andWhere(['=','id_fase', 2])->orderBy('id_detalle asc')->all();
        $producto = OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $model->id_orden_produccion])->orderBy('descripcion asc')->all();		
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
		
        foreach ($producto as $detalle) {                                                           
            $pdf->Cell(23, 4, $detalle->codigo_producto, 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(63, 4, utf8_decode($detalle->descripcion), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(23, 4, ''.number_format($detalle->cantidad,0), 0, 0, 'R');
            $pdf->Cell(28, 4, $detalle->numero_lote, 0, 0, 'C');
            $pdf->Cell(30, 4, $detalle->medidaProducto->descripcion, 0, 0, 'C');
            $pdf->Cell(25, 4, $detalle->fecha_vencimiento, 0, 0, 'C');	
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
	$this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //productos
        $pdf->SetXY(10, 63);
        $pdf->Cell(192, 5, 'PRESENTACION DEL PRODUCTO', 1, 0, 'C',1);
        //materiales
        $fase = app\models\TipoFases::findOne(1);
        $pdf->SetXY(10, 109);
        $this->SetFont('', 'B', 7);
        $pdf->Cell(192, 5, utf8_decode('MATERIAS PRIMAS (M.P.)'), 1, 0, 'C',1);
        $pdf->SetXY(10, 114);
        $this->Cell(64, 4, utf8_decode($fase->nombre_fase), 0, 0, 'C', 0);
        $this->Cell(64, 4, utf8_decode($fase->nombre_fase), 0, 0, 'C', 0);
        $this->Cell(64, 4, utf8_decode($fase->nombre_fase), 0, 0, 'C', 0);
        $pdf->SetXY(10, 119);
       //
        $pdf->Cell(64, 4, 'INSUMOS / MP', 1, 0, 'C',1);
        $pdf->Cell(64, 4, '% DE APLICACION', 1, 0, 'C',1);
        $pdf->Cell(64, 4, utf8_decode('CANTIDAD A PESAR EN GRAMOS'), 1, 0, 'C',1);
        $pdf->SetXY(10, 124);
        $pdf->SetFont('Arial', '', 8);
        foreach ($materiales as $detalle) {                                    
            $pdf->Cell(64, 4, $detalle->codigo_homologacion, 0, 0, 'C');            
            $pdf->Cell(64, 4, utf8_decode($detalle->porcentaje_aplicacion), 0, 0, 'R');
            $pdf->Cell(64, 4, ' '.number_format($detalle->cantidad_gramos, 0), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
        // SEGUNDO PROCESO
        $fase = app\models\TipoFases::findOne(2);
        $pdf->SetXY(10, 172);
        $this->SetFont('', 'B', 7);
        $pdf->Cell(192, 5, utf8_decode('MATERIAS PRIMAS (M.P.)'), 1, 0, 'C',1);
        $pdf->SetXY(10, 177);
        $this->Cell(64, 4, utf8_decode($fase->nombre_fase), 0, 0, 'C', 0);
        $this->Cell(64, 4, utf8_decode($fase->nombre_fase), 0, 0, 'C', 0);
        $this->Cell(64, 4, utf8_decode($fase->nombre_fase), 0, 0, 'C', 0);
        $pdf->SetXY(10, 182);
       //
        $pdf->Cell(64, 4, 'INSUMOS / MP', 1, 0, 'C',1);
        $pdf->Cell(64, 4, '% DE APLICACION', 1, 0, 'C',1);
        $pdf->Cell(64, 4, utf8_decode('CANTIDAD A PESAR EN GRAMOS'), 1, 0, 'C',1);
        $pdf->SetXY(10, 187);
        $pdf->SetFont('Arial', '', 8);
        foreach ($fase2 as $detalle) {                                    
            $pdf->Cell(64, 4, $detalle->codigo_homologacion, 0, 0, 'C');            
            $pdf->Cell(64, 4, utf8_decode($detalle->porcentaje_aplicacion), 0, 0, 'R');
            $pdf->Cell(64, 4, ' '.number_format($detalle->cantidad_gramos, 0), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
        
        $pdf->SetXY(10, 237);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(146, 4, utf8_decode('OBSERVACION: '.$model->observacion),0,'J');
	//firma trabajador
        $pdf->SetXY(10, 252);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, '________________________________', 0, 0, 'L',0);
         $pdf->SetXY(10, 257);
        $pdf->Cell(35, 5, 'LIDER DE PRODUCCION', 0, 0, 'L',0);
        $pdf->SetXY(10, 262);
        $pdf->Cell(35, 5, utf8_decode('Emitió y Revisó'), 0, 0, 'L',0);
        // SEGUNDA FIRMA
        $pdf->SetXY(120, 252);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(120, 5, '________________________________', 0, 0, 'L',0);
        $pdf->SetXY(120, 257);
        $pdf->Cell(120, 5, 'DIRECCION TECNICA', 0, 0, 'L',0);
        $pdf->SetXY(120, 262);
        $pdf->Cell(120, 5, utf8_decode('Aprobado por'), 0, 0, 'L',0);
        //liena
        //linea
        $pdf->SetXY(106, 12);
        $pdf->Cell(120, 5, 'GESTION DE PRODUCCION', 0, 0, 'C',0);
        $this->SetXY(129, 16);
        $pdf->Cell(125, 5, '_________________________________________', 0, 0, 'L',0);
        //SEGUNDO NOMBRE
        $pdf->SetXY(106, 22);
        $pdf->Cell(120, 5, 'ORDEN DE PRODUCCION', 0, 0, 'C',0);

    }

    function Footer() {

        $this->SetFont('Arial', '', 7);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
global $id_orden_produccion;
$id_orden_produccion = $model->id_orden_produccion;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Orden_produccion$model->numero_orden.pdf", 'D');

exit;
