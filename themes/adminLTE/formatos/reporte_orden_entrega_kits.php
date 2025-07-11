<?php

use inquid\pdf\FPDF;
use app\models\OrdenEntregaKits;
use app\models\OrdenEntregaKitsDetalles;

class PDF extends FPDF {

    function Header() {
        $id_orden = $GLOBALS['id_orden'];
        $solicitud = \app\models\OrdenEntregaKits::findOne($id_orden);
        //Logo
       $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 19);
        //Encabezado
        

        //FIN
        $this->SetXY(10, 32);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 32.5);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Prestaciones sociales
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(10, 39);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(162, 7, utf8_decode("ORDEN DE ENSAMBLE KIT"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($solicitud->numero_orden, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
       
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("Codigo:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 5, utf8_decode($solicitud->inventario->codigo_producto), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("Presentacion:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(60, 5, utf8_decode($solicitud->presentacion->descripcion), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("Total kis:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(27, 5, $solicitud->total_kits, 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("F. proceso:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 5, utf8_decode($solicitud->fecha_orden), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("F. hora proceso:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(60, 5, utf8_decode($solicitud->fecha_hora_registro), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("Total productos:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(27, 5, $solicitud->total_productos_procesados, 0, 0, 'R', 1);
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
        $this->Line(10,64,10,190);
        $this->Line(40,64,40,190);
        $this->Line(140,64,140,190);
        $this->Line(171,64,171,190);
        $this->Line(202,64,202,190);
        $this->Line(10,190,202,190);//linea horizontal inferior  
    }
    function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('CODIGO', ('PRESENTACION DEL PRODUCTO'),('No LOTE'),('U. X PRODUCTO'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(30, 100,31,31);
        for ($i = 0; $i < count($header); $i++){
            if ($i == 0 || $i == 1){
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            } else {
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            }
        }    
        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }        
    function Body($pdf,$model) {        
      
        $detalles = OrdenEntregaKitsDetalles::find()->where(['=','id_orden_entrega', $model->id_orden_entrega])->all();		
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $currentProductDescription = null; 
	foreach ($detalles as $detalle) {  
                if ($detalle->detalleEntrega->detalle->inventario->nombre_producto !== $currentProductDescription) {
                    // Añadir un salto de línea extra si no es el primer producto
                    if ($currentProductDescription !== null) {
                        $pdf->Ln(7); // Salto de línea más grande para separar productos
                    }

                    $pdf->SetFont('Arial', 'B', 8); // Opcional: negrita para el nombre del producto
                    // Celda que "abarca" para el nombre del producto
                    // Ajusta el ancho (192) según la suma de tus celdas de detalle si es necesario
                    $pdf->Cell(192, 5, utf8_decode('Producto: ' . $detalle->detalleEntrega->detalle->inventario->nombre_producto), 1, 1, 'C'); 
                    $pdf->SetFont('Arial', '', 7); // Volver al tamaño de fuente normal
                    $currentProductDescription = $detalle->detalleEntrega->detalle->inventario->nombre_producto; // Actualizar el producto actual
                    $pdf->Ln(1); // Pequeño espacio después del encabezado del producto
                }
                $pdf->Cell(30, 4, utf8_decode(substr($detalle->detalleEntrega->detalle->inventario->codigo_producto,0, 45)), 0, 0, 'L');
                $pdf->Cell(100, 4, utf8_decode(substr($detalle->detalleEntrega->detalle->inventario->nombre_producto, 0, 80)), 0, 0, 'L');
                $pdf->Cell(31, 4, utf8_decode(substr($detalle->detalleEntrega->numero_lote, 0, 80)), 0, 0, 'L');
                $pdf->Cell(31, 4, utf8_decode(''.number_format($detalle->cantidad_producto,0)), 0, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 20); 
            
                                        
        }
	$pdf->SetXY(10, 200);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(146, 4, utf8_decode('Observacion: '.$model->observacion),0,'J');
	//firma trabajador
        $pdf->SetXY(10, 220);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, '________________________________', 0, 0, 'L',0);
         $pdf->SetXY(10, 225);
        $pdf->Cell(35, 5, 'AREA LOGISTICA', 0, 0, 'L',0);
        $pdf->SetXY(10, 230);
        $pdf->Cell(35, 5, utf8_decode('Recibe'), 0, 0, 'L',0);
        // SEGUNDA FIRMA
        $pdf->SetXY(120, 220);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(120, 5, '________________________________', 0, 0, 'L',0);
        $pdf->SetXY(120, 225);
        $pdf->Cell(120, 5, 'AREA DE PRODUCCION', 0, 0, 'L',0);
        $pdf->SetXY(120, 230);
        $pdf->Cell(120, 5, utf8_decode('Despacha'), 0, 0, 'L',0);
        $pdf->SetXY(120, 235);
        $pdf->Cell(120, 5, utf8_decode('User name: '.$model->user_name), 0, 0, 'L',0);
        //liena
        //encabezado de linea
        $pdf->SetXY(75, 10);
        $pdf->Cell(20, 5, 'GESTION DE PRODUCCION', 0, 0, 'C',0);
        $this->SetXY(44, 13);
        $pdf->Cell(125, 5, '_________________________________________________________________________________________', 0, 0, 'L',0);
        //segunda linea
        $pdf->SetXY(130, 10);
        $pdf->Cell(120, 5, 'Codigo: ', 0, 0, 'L',0);
        $this->SetXY(129, 20);
        $pdf->Cell(124, 5, '_________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(130, 17);
        $pdf->Cell(120, 5, 'Version: ', 0, 0, 'L',0);
        $pdf->SetXY(130, 24);
        $pdf->Cell(120, 5, 'Fecha: ', 0, 0, 'L',0);
        //SEGUNDO NOMBRE
        $pdf->SetXY(75, 20);
        $pdf->Cell(20, 5, 'ENTREGA DE KITS ENSAMBLADOS', 0, 0, 'C',0);

    }

    function Footer() {

        $this->SetFont('Arial', '', 7);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
global $id_orden;
$id_orden = $model->id_orden_entrega;
$pdf = new PDF();
$pdf->SetFont('helvetica','',10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("OrdenEntregaKits_$model->numero_orden.pdf", 'D');

exit;
