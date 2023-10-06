<?php

use inquid\pdf\FPDF;
use app\models\FacturaVenta;
use app\models\FacturaVentaDetalle;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;
class PDF extends FPDF {
     function Header() {
        $id_factura = $GLOBALS['id_factura'];
        $factura = FacturaVenta::findOne($id_factura);
        $config = MatriculaEmpresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);
        //Logo
        $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 30);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(53, 9);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Empresa:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 1);
        $this->SetXY(30, 5);
        //FIN
        $this->SetXY(53, 13);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Nit:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 17);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Dirección"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->direccion), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 21);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Telefono:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->telefono), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 25);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Municipio:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->codigoMunicipio->municipio." - ".$config->codigoDepartamento->departamento), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 29);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Tipo regimen:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->tipoRegimen), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //DATOS DE LA FACTURA
          $this->SetXY(135, 7);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(162, 7, utf8_decode("FACTURA ELECTRONICA DE VENTA"), 0, 0, 'l', 0);
        $this->SetXY(155, 12);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 7, utf8_decode('No '.$factura->consecutivo.' '.str_pad($factura->numero_factura, 6, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
        $this->SetXY(140, 18);
        $this->SetFont('Arial', '', 9);
        $this->Cell(20, 7, utf8_decode('Resolución Dian No: '.$config->resolucion->numero_resolucion), 0, 0, 'l', 0);
        //
        $this->SetXY(127, 22);
        $this->SetFont('Arial', '', 9);
        $this->Cell(20, 7, utf8_decode('Fecha formalización: '.$config->resolucion->desde. ' hasta el ' .$config->resolucion->hasta), 0, 0, 'l', 0);
        //
        $this->SetXY(145, 26);
        $this->SetFont('Arial', '', 9);
        $this->Cell(20, 7, utf8_decode('Habilita rango: '.$config->resolucion->rango_inicio. ' hasta el ' .$config->resolucion->rango_final), 0, 0, 'l', 0);
         //
        $this->SetXY(155, 30);
        $this->SetFont('Arial', '', 9);
        $this->Cell(20, 7, utf8_decode('Vigencia: '.$config->resolucion->vigencia.' Meses'), 0, 0, 'l', 0);
        //linea
        $this->SetXY(10, 32);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 32.5);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //comienza datos del cliente
         //FIN
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 40);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Nit:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(82, 5, utf8_decode($factura->nit_cedula.'-'.$factura->dv), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Cliente:"), 0, 0, 'c', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(67, 5, utf8_decode($factura->cliente), 0, 0, 'c', 1);
        //FIN
        $this->SetXY(10, 44);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Dirección:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(82, 5, utf8_decode($factura->direccion), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Telefono:"), 0, 0, 'c', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(67, 5, utf8_decode($factura->telefono_cliente), 0, 0, 'c', 1);
        //FIN
         $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Departamento:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(82, 5, utf8_decode($factura->clienteFactura->codigoDepartamento->departamento), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Municipio:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(67, 5, utf8_decode($factura->clienteFactura->codigoMunicipio->municipio), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Fecha expedición:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(82, 5, utf8_decode($factura->fecha_inicio), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Fecha vcto:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(67, 5, utf8_decode($factura->fecha_vencimiento), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Email.:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(82, 5, utf8_decode($factura->clienteFactura->email_cliente), 0, 0, 'l', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Forma pago:"), 0, 0, 'J', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(67, 5, utf8_decode($factura->formaPago.' ('.$factura->plazo_pago.')  Dias'), 0, 0, 'L', 1);
        //FIN
         //Lineas del encabezado
        $this->Line(10,67,10,188); //la primera con la tercera es para la la raya iguales, la segunda es el lago o corto y la tercera es la tamaño de largo
        $this->Line(23,67,23,188);
        $this->Line(88,67,88,188);
        $this->Line(100,67,100,188);
        $this->Line(115,67,115,188);
        $this->Line(132,67,132,188);
        $this->Line(146,67,146,188);
        $this->Line(164,67,164,188);
        $this->Line(180,67,180,188);
        $this->Line(202,67,202,188);
        //Cuadro de la nota
        $this->Line(10,188,157,188);//linea horizontal superior
        $this->Line(10,170,10,178);//linea vertical
        $this->Line(10,196,157,196);//linea horizontal inferior
        //Linea de las observacines
        $this->Line(10,178,10,228);//linea vertical
        //lineas para los cuadros de nit/cc,fecha,firma   
        $this->Line(10,218,10,245);//linea vertical x1,y1,x2,y2   
        $this->Line(0,218,0,245);//linea vertical x1,y1,x2,y2
        $this->Line(0,218,0,245);//linea vertical x1,y1,x2,y2
        $this->Line(0,218,0,245);//linea vertical x1,y1,x2,y2
        $this->Line(202,218,202,245);//linea vertical x1,y1,x2,y2       
        
        $this->EncabezadoDetalles();
     }
     function EncabezadoDetalles() {
        $this->Ln(7);
        $header = array('CODIGO', 'NOMBRE PRODUCTO', 'CANT.', 'VR UNIT.','SUBTOTAL', '% DCTO','VR. DESCTO', 'IVA', 'TOTAL');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(13, 65, 12,  15, 17, 14, 18, 16, 22);
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
        $config = MatriculaEmpresa::findOne(1);
        $detalles = FacturaVentaDetalle::find()->where(['=','id_factura',$model->id_factura])->all();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $cant = 0;
        foreach ($detalles as $detalle) { 
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(13, 4, $detalle->inventario->codigo_producto, 0, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(65, 4, $detalle->inventario->nombre_producto , 0, 0, 'L');
            $pdf->Cell(12, 4, $detalle->cantidad, 0, 0, 'R');
            $pdf->Cell(15, 4, number_format($detalle->valor_unitario, 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(17, 4, number_format($detalle->subtotal, 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(14, 4, $detalle->porcentaje_descuento, 0, 0, 'R');
            $pdf->Cell(18, 4, number_format($detalle->valor_descuento, 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(16, 4, number_format($detalle->impuesto, 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(22, 4, number_format($detalle->total_linea, 0, '.', ','), 0, 0, 'R');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);
            $cant += $detalle->cantidad;
        }
        $this->SetFillColor(200, 200, 200);
        $pdf->SetXY(10, 188);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(146, 4, utf8_decode(valorEnLetras($model->total_factura)),0,'J');
        $pdf->SetXY(157, 188);
        $pdf->MultiCell(20, 8, 'VR BRUTO:',1,'C');
        $pdf->SetXY(177, 188);
        $pdf->MultiCell(25, 8, number_format($model->valor_bruto, 0, '.', ','),1,'R');
       //FIN
        $pdf->SetXY(10, 196);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(146, 4, utf8_decode('Observacion: '.$model->observacion),0,'J');
        $pdf->SetXY(157, 196);
        $pdf->MultiCell(20, 8, 'DESCUENTO:',1,'C');
        $pdf->SetXY(177, 196);
        $pdf->MultiCell(25, 8, number_format($model->descuento, 0, '.', ','),1,'R');
        //FIN
        $pdf->SetXY(157, 204);
        $pdf->MultiCell(20, 8, 'SUBTOTAL:',1,'C');
        $pdf->SetXY(177, 204);
        $pdf->MultiCell(25, 8, number_format($model->subtotal_factura, 0, '.', ','),1,'R');
        ///fin
        $pdf->SetXY(157, 212);
        $pdf->MultiCell(20, 8, 'IVA:',1,'C');
        $pdf->SetXY(177, 212);
        $pdf->MultiCell(25, 8, number_format($model->impuesto, 0, '.', ','),1,'R');
        //fin
        $pdf->SetXY(157, 220);
        $pdf->MultiCell(20, 8, 'RETENCION:',1,'C');
        $pdf->SetXY(177, 220);
        $pdf->MultiCell(25, 8, number_format($model->valor_retencion, 0, '.', ','),1,'R');
        //fin
        $pdf->SetXY(157, 228);
        $pdf->MultiCell(20, 8, 'RETE IVA:',1,'C');
        $pdf->SetXY(177, 228);
        $pdf->MultiCell(25, 8, number_format($model->valor_reteiva, 0, '.', ','),1,'R');
        //fin
        $pdf->SetXY(10, 236);
        $pdf->MultiCell(109, 8, '',1,'R',1);
        $pdf->SetXY(119, 236);
        $pdf->MultiCell(38, 8, 'UNIDADES: '.$cant,1,'C',1);
        $pdf->SetXY(157, 236);           
        $pdf->MultiCell(20, 8, 'TOTAL:',1,'C',1);
        $pdf->SetXY(177, 236);
        $pdf->MultiCell(25, 8, number_format($model->total_factura, 0, '.', ','),1,'R',1);
        
       
        $pdf->SetXY(10, 245);//nit,fecha,fecha,firma  
        $pdf->MultiCell(192, 4, utf8_decode($config->declaracion),1,'J');
        $pdf->SetXY(10, 266);//tipo cuenta
        $pdf->Cell(191, 5, '           Tipo de cuenta: '.$config->entidadBancaria->tipoCuenta.'     Numero de cuenta:   '.$config->entidadBancaria->producto.'    Entidad bancaria:   '.$config->entidadBancaria->entidad_bancaria,1,'C');
       /* $pdf->SetXY(10, 255);//firma trabajador
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA CLIENTE: ____________________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 260);
        $pdf->Cell(35, 5, 'NIT/CC.:', 0, 0, 'L',0);*/
    }
}
global $id_factura;
$id_factura = $model->id_factura;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Factura_venta_$model->numero_factura.pdf", 'D');
exit;
function zero_fill ($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}
function valorEnLetras($x) {
    if ($x < 0) {
        $signo = "menos ";
    } else {
        $signo = "";
    }
    $x = abs($x);
    $C1 = $x;

    $G6 = floor($x / (1000000));  // 7 y mas 

    $E7 = floor($x / (100000));
    $G7 = $E7 - $G6 * 10;   // 6 

    $E8 = floor($x / 1000);
    $G8 = $E8 - $E7 * 100;   // 5 y 4 

    $E9 = floor($x / 100);
    $G9 = $E9 - $E8 * 10;  //  3 

    $E10 = floor($x);
    $G10 = $E10 - $E9 * 100;  // 2 y 1 


    $G11 = round(($x - $E10) * 100);  // Decimales 
////////////////////// 

    $H6 = unidades($G6);

    if ($G7 == 1 AND $G8 == 0) {
        $H7 = "Cien ";
    } else {
        $H7 = decenas($G7);
    }

    $H8 = unidades($G8);

    if ($G9 == 1 AND $G10 == 0) {
        $H9 = "Cien ";
    } else {
        $H9 = decenas($G9);
    }

    $H10 = unidades($G10);

    if ($G11 < 10) {
        $H11 = "" . $G11;
    } else {
        $H11 = $G11;
    }

///////////////////////////// 
    if ($G6 == 0) {
        $I6 = " ";
    } elseif ($G6 == 1) {
        $I6 = "Millón ";
    } else {
        $I6 = "Millones ";
    }

    if ($G8 == 0 AND $G7 == 0) {
        $I8 = " ";
    } else {
        $I8 = "Mil ";
    }

    $I10 = "Pesos ";
    $I11 = "M.L ";

    $C3 = $signo . $H6 . $I6 . $H7 . $H8 . $I8 . $H9 . $H10 . $I10 . $H11 . $I11;

    return $C3; //Retornar el resultado 
}

function unidades($u) {
    if ($u == 0) {
        $ru = " ";
    } elseif ($u == 1) {
        $ru = "Un ";
    } elseif ($u == 2) {
        $ru = "Dos ";
    } elseif ($u == 3) {
        $ru = "Tres ";
    } elseif ($u == 4) {
        $ru = "Cuatro ";
    } elseif ($u == 5) {
        $ru = "Cinco ";
    } elseif ($u == 6) {
        $ru = "Seis ";
    } elseif ($u == 7) {
        $ru = "Siete ";
    } elseif ($u == 8) {
        $ru = "Ocho ";
    } elseif ($u == 9) {
        $ru = "Nueve ";
    } elseif ($u == 10) {
        $ru = "Diez ";
    } elseif ($u == 11) {
        $ru = "Once ";
    } elseif ($u == 12) {
        $ru = "Doce ";
    } elseif ($u == 13) {
        $ru = "Trece ";
    } elseif ($u == 14) {
        $ru = "Catorce ";
    } elseif ($u == 15) {
        $ru = "Quince ";
    } elseif ($u == 16) {
        $ru = "Dieciseis ";
    } elseif ($u == 17) {
        $ru = "Decisiete ";
    } elseif ($u == 18) {
        $ru = "Dieciocho ";
    } elseif ($u == 19) {
        $ru = "Diecinueve ";
    } elseif ($u == 20) {
        $ru = "Veinte ";
    } elseif ($u == 21) {
        $ru = "Veinti un ";
    } elseif ($u == 22) {
        $ru = "Veinti dos ";
    } elseif ($u == 23) {
        $ru = "Veinti tres ";
    } elseif ($u == 24) {
        $ru = "Veinti cuatro ";
    } elseif ($u == 25) {
        $ru = "Veinti cinco ";
    } elseif ($u == 26) {
        $ru = "Veinti seis ";
    } elseif ($u == 27) {
        $ru = "Veinti siente ";
    } elseif ($u == 28) {
        $ru = "Veintio cho ";
    } elseif ($u == 29) {
        $ru = "Veinti nueve ";
    } elseif ($u == 30) {
        $ru = "Treinta ";
    } elseif ($u == 31) {
        $ru = "Treinta y un ";
    } elseif ($u == 32) {
        $ru = "Treinta y dos ";
    } elseif ($u == 33) {
        $ru = "Treinta y tres ";
    } elseif ($u == 34) {
        $ru = "Treinta y cuatro ";
    } elseif ($u == 35) {
        $ru = "Treinta y cinco ";
    } elseif ($u == 36) {
        $ru = "Treinta y seis ";
    } elseif ($u == 37) {
        $ru = "Treinta y siete ";
    } elseif ($u == 38) {
        $ru = "Treinta y ocho ";
    } elseif ($u == 39) {
        $ru = "Treinta y nueve ";
    } elseif ($u == 40) {
        $ru = "Cuarenta ";
    } elseif ($u == 41) {
        $ru = "Cuarenta y un ";
    } elseif ($u == 42) {
        $ru = "Cuarenta y dos ";
    } elseif ($u == 43) {
        $ru = "Cuarenta y tres ";
    } elseif ($u == 44) {
        $ru = "Cuarenta y cuatro ";
    } elseif ($u == 45) {
        $ru = "Cuarenta y cinco ";
    } elseif ($u == 46) {
        $ru = "Cuarenta y seis ";
    } elseif ($u == 47) {
        $ru = "Cuarenta y siete ";
    } elseif ($u == 48) {
        $ru = "Cuarenta y ocho ";
    } elseif ($u == 49) {
        $ru = "Cuarenta y nueve ";
    } elseif ($u == 50) {
        $ru = "Cincuenta ";
    } elseif ($u == 51) {
        $ru = "Cincuenta y un ";
    } elseif ($u == 52) {
        $ru = "Cincuenta y dos ";
    } elseif ($u == 53) {
        $ru = "Cincuenta y tres ";
    } elseif ($u == 54) {
        $ru = "Cincuenta y cuatro ";
    } elseif ($u == 55) {
        $ru = "Cincuenta y cinco ";
    } elseif ($u == 56) {
        $ru = "Cincuenta y seis ";
    } elseif ($u == 57) {
        $ru = "Cincuenta y siete ";
    } elseif ($u == 58) {
        $ru = "Cincuenta y ocho ";
    } elseif ($u == 59) {
        $ru = "Cincuenta y nueve ";
    } elseif ($u == 60) {
        $ru = "Sesenta ";
    } elseif ($u == 61) {
        $ru = "Sesenta y un ";
    } elseif ($u == 62) {
        $ru = "Sesenta y dos ";
    } elseif ($u == 63) {
        $ru = "Sesenta y tres ";
    } elseif ($u == 64) {
        $ru = "Sesenta y cuatro ";
    } elseif ($u == 65) {
        $ru = "Sesenta y cinco ";
    } elseif ($u == 66) {
        $ru = "Sesenta y seis ";
    } elseif ($u == 67) {
        $ru = "Sesenta y siete ";
    } elseif ($u == 68) {
        $ru = "Sesenta y ocho ";
    } elseif ($u == 69) {
        $ru = "Sesenta y nueve ";
    } elseif ($u == 70) {
        $ru = "Setenta ";
    } elseif ($u == 71) {
        $ru = "Setenta y un ";
    } elseif ($u == 72) {
        $ru = "Setenta y dos ";
    } elseif ($u == 73) {
        $ru = "Setenta y tres ";
    } elseif ($u == 74) {
        $ru = "Setenta y cuatro ";
    } elseif ($u == 75) {
        $ru = "Setentaycinco ";
    } elseif ($u == 76) {
        $ru = "Setenta y seis ";
    } elseif ($u == 77) {
        $ru = "Setenta y siete ";
    } elseif ($u == 78) {
        $ru = "Setenta y ocho ";
    } elseif ($u == 79) {
        $ru = "Setenta y nueve ";
    } elseif ($u == 80) {
        $ru = "Ochenta ";
    } elseif ($u == 81) {
        $ru = "Ochenta y un ";
    } elseif ($u == 82) {
        $ru = "Ochenta y dos ";
    } elseif ($u == 83) {
        $ru = "Ochenta y tres ";
    } elseif ($u == 84) {
        $ru = "Ochenta y cuatro ";
    } elseif ($u == 85) {
        $ru = "Ochenta y cinco ";
    } elseif ($u == 86) {
        $ru = "Ochenta y seis ";
    } elseif ($u == 87) {
        $ru = "Ochenta y siete ";
    } elseif ($u == 88) {
        $ru = "Ochenta y ocho ";
    } elseif ($u == 89) {
        $ru = "Ochenta y nueve ";
    } elseif ($u == 90) {
        $ru = "Noventa ";
    } elseif ($u == 91) {
        $ru = "Noventa y un ";
    } elseif ($u == 92) {
        $ru = "Noventa y dos ";
    } elseif ($u == 93) {
        $ru = "Noventa y tres ";
    } elseif ($u == 94) {
        $ru = "Noventa y cuatro ";
    } elseif ($u == 95) {
        $ru = "Noventa y cinco ";
    } elseif ($u == 96) {
        $ru = "Noventa y seis ";
    } elseif ($u == 97) {
        $ru = "Noventaysiete ";
    } elseif ($u == 98) {
        $ru = "Noventa y ocho ";
    } else {
        $ru = "Noventa y nueve ";
    }
    return $ru; //Retornar el resultado 
}

function decenas($d) {
    if ($d == 0) {
        $rd = "";
    } elseif ($d == 1) {
        $rd = "Ciento ";
    } elseif ($d == 2) {
        $rd = "Doscientos ";
    } elseif ($d == 3) {
        $rd = "Trescientos ";
    } elseif ($d == 4) {
        $rd = "Cuatrocientos ";
    } elseif ($d == 5) {
        $rd = "Quinientos ";
    } elseif ($d == 6) {
        $rd = "Seiscientos ";
    } elseif ($d == 7) {
        $rd = "Setecientos ";
    } elseif ($d == 8) {
        $rd = "Ochocientos ";
    } else {
        $rd = "Novecientos ";
    }
    return $rd; //Retornar el resultado 
}


