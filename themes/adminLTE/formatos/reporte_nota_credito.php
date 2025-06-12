<?php
ob_start();
include "../vendor/phpqrcode/qrlib.php";
use inquid\pdf\FPDF;
use app\models\NotaCredito;
use app\models\NotaCreditoDetalle;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;
class PDF extends FPDF {
     function Header() {
        $id_nota = $GLOBALS['id_nota'];
        $nota = NotaCredito::findOne($id_nota);
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
        $this->Cell(40, 5, utf8_decode($config->tipoRegimen->regimen), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //DATOS DE LA FACTURA
          $this->SetXY(151, 7);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(162, 7, utf8_decode("NOTA CREDITO"), 0, 0, 'l', 0);
        $this->SetXY(155, 12);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 7, utf8_decode('No '.$nota->numero_nota_credito), 0, 0, 'l', 0);
        //
        $this->SetXY(140, 18);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 7, utf8_decode('Fecha procesada:        '.$nota->fecha_hora_enviada), 0, 0, 'l', 0);
        //
        $this->SetXY(140, 22);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 7, utf8_decode('Fecha recepción dian: '.$nota->fecha_recepcion_dian), 0, 0, 'l', 0);
        //
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
        $this->Cell(82, 5, utf8_decode($nota->nit_cedula.'-'.$nota->clienteNota->dv), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Cliente:"), 0, 0, 'c', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(63, 5, utf8_decode($nota->cliente), 0, 0, 'c', 1);
        //FIN
      
        $this->SetXY(10, 44);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Departamento:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(82, 5, utf8_decode($nota->clienteNota->codigoDepartamento->departamento), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Municipio:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(63, 5, utf8_decode($nota->clienteNota->codigoMunicipio->municipio), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Numero factura:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(82, 5, utf8_decode($nota->factura->consecutivo.'-'.$nota->numero_factura), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Fecha factura:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(63, 5, utf8_decode($nota->fecha_factura), 0, 0, 'L', 1);
        //FIN
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Motivo:"), 0, 0, 'l', 1);
        if ($nota->id_motivo <> null){
            $this->SetFont('Arial', '', 8);
            $this->Cell(82, 5, utf8_decode($nota->motivo->concepto), 0, 0, 'L',1);
        }else{
            $this->SetFont('Arial', '', 8);
            $this->Cell(82, 5, utf8_decode('NOT FOUND'), 0, 0, 'L',1);
        }    
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Fecha nota:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(63, 5, utf8_decode($nota->fecha_nota_credito), 0, 0, 'L', 1);
         //FIN
        $this->SetXY(10, 57);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Cufe factura:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(166, 5, utf8_decode($nota->cufe_factura), 0, 0, 'L',1);
       
         //Lineas del encabezado

         //Lineas del encabezado
        $this->Line(10,64,10,188); //la primera con la tercera es para la la raya iguales, la segunda es el lago o corto y la tercera es la tamaño de largo
        $this->Line(25,64,25,188);
        $this->Line(86,64,86,188);
        $this->Line(102,64,102,188);
        $this->Line(127,64,127,188);
        $this->Line(157,64,157,188);
        $this->Line(177,64,177,188);
        $this->Line(202,64,202,188);
        //Cuadro de la nota
        $this->Line(10,188,157,188);//linea horizontal superior
        $this->Line(10,170,10,178);//linea vertical
        $this->Line(10,196,157,196);//linea horizontal inferior
        //Linea de las observacines
        $this->Line(10,178,10,240);//linea vertical
       
        
        $this->EncabezadoDetalles();
     }
     function EncabezadoDetalles() {
        $this->Ln(7);
        $header = array('CODIGO', 'NOMBRE PRODUCTO', 'CANTIDAD', 'VR UNITARIO','SUBTOTAL', 'IVA', 'TOTAL');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7); 

        //creamos la cabecera de la tabla.
        $w = array(15, 61, 16,  25 , 30, 20, 25);
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
        $detalles = NotaCreditoDetalle::find()->where(['=','id_nota',$model->id_nota])->all();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $cant = 0;
        foreach ($detalles as $detalle) { 
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(15, 4, $detalle->inventario->codigo_producto, 0, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(61, 4, utf8_decode($detalle->inventario->nombre_producto) , 0, 0, 'L');
            $pdf->Cell(16, 4, $detalle->cantidad, 0, 0, 'R');
            $pdf->Cell(25, 4, number_format($detalle->valor_unitario, 2, '.', ','), 0, 0, 'R');
            $pdf->Cell(30, 4, number_format($detalle->subtotal, 2, '.', ','), 0, 0, 'R');
            $pdf->Cell(20, 4, number_format($detalle->impuesto, 2, '.', ','), 0, 0, 'R');
            $pdf->Cell(25, 4, number_format($detalle->total_linea, 2, '.', ','), 0, 0, 'R');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);
            $cant += $detalle->cantidad;
        }
        $this->SetFillColor(200, 200, 200);
        $pdf->SetXY(10, 188);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(146, 4, utf8_decode(valorEnLetras($model->valor_total_devolucion)),0,'J');
        $pdf->SetXY(157, 188);
        $pdf->MultiCell(20, 8, 'VR BRUTO:',1,'L');
        $pdf->SetXY(177, 188);
        $pdf->MultiCell(25, 8, number_format($model->valor_bruto, 2, '.', ','),1,'R');
       //FIN
        $pdf->SetXY(10, 196);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(146, 4, utf8_decode('Observacion: '.$model->observacion),0,'J');
        $pdf->SetXY(157, 196);
        $pdf->MultiCell(20, 8, 'DESCUENTO:',1,'L');
        $pdf->SetXY(177, 196);
        $pdf->MultiCell(25, 8, '', 1, 'L');
        //FIN
        $pdf->SetXY(157, 204);
        $pdf->MultiCell(20, 8, 'SUBTOTAL:',1,'L');
        $pdf->SetXY(177, 204);
        $pdf->MultiCell(25, 8, number_format($model->valor_bruto, 2, '.', ','),1,'R');
        ///fin
        $pdf->SetXY(157, 212);
        $pdf->MultiCell(20, 8, 'IVA:',1,'L');
        $pdf->SetXY(177, 212);
        $pdf->MultiCell(25, 8, number_format($model->impuesto, 2, '.', ','),1,'R');
        //fin
        $pdf->SetXY(157, 220);
        $pdf->MultiCell(20, 8, 'RETENCION:',1,'C');
        $pdf->SetXY(177, 220);
        $pdf->MultiCell(25, 8, number_format($model->retencion, 2, '.', ','),1,'R');
        //fin
        $pdf->SetXY(157, 228);
        $pdf->MultiCell(20, 8, 'RETE IVA:',1,'L');
        $pdf->SetXY(177, 228);
        $pdf->MultiCell(25, 8, number_format($model->rete_iva, 2, '.', ','),1,'R');
        //fin
        $pdf->SetXY(10, 236);
        $pdf->MultiCell(109, 8, '',1,'R',1);
        $pdf->SetXY(119, 236);
        $pdf->MultiCell(38, 8, 'UNIDADES: '.$cant,1,'C',1);
        $pdf->SetXY(157, 236);           
        $pdf->MultiCell(20, 8, 'TOTAL:',1,'L',1);
        $pdf->SetXY(177, 236);
        $pdf->MultiCell(25, 8, number_format($model->valor_total_devolucion, 2, '.', ','),1,'R',1);
        
        //mostra del cude de la nota credito
        $this->SetFillColor(200, 200, 200);
        $pdf->SetXY(10, 245);
        $this->SetFont('', 'B', 9);
        $pdf->MultiCell(20, 5, 'Cude:',0,'L');
        $pdf->SetXY(20, 245);
        $this->SetFont('', '', 9);
        $this->Cell(166, 4, utf8_decode($model->cude), 0, 0, 'J',0);
       
        //muestra la representacion grafica
        $pdf->SetXY(138, 210);//recibido,aceptado 
        $this->SetFont('Arial', '', 8);
        $qrstr = utf8_decode($model->qrstr);
        $pdf->SetXY(120, 70); // Establece la posición donde aparecerá el QR
        QRcode::png($qrstr,"test.png");
      //  $pdf->Image("test.png", 118.5, 198, 38, 35, "png");
        
        //muestra software propio
        $pdf->SetXY(100, 231);
        $this->SetFont('Arial', 'B', 6);
        $pdf->Cell(64, 5, utf8_decode($config->razon_social_completa.'-'.$config->nit_empresa.'-'.$config->dv. ' Software Propio '),0,'J',1);
        //
        $pdf->SetXY(10, 260);//firma trabajador
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA CLIENTE: ____________________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 265);
        $pdf->Cell(35, 5, 'NIT/CC.:', 0, 0, 'L',0);
    }
}
global $id_nota;
$id_nota = $model->id_nota;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Nota_credito_$model->numero_nota_credito.pdf", 'D');
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


