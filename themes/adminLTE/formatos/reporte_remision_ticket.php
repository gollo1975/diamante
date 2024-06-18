<?php

use inquid\pdf\FPDF;
use app\models\Remisiones;
use app\models\RemisionDetalles;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_remision = $GLOBALS['id_remision'];
        $conRemision = Remisiones::findOne($id_remision);
        $config = MatriculaEmpresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);
        //Logo
        $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 20, 20);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(35, 9);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, utf8_decode("Empresa:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 1);
               //FIN
        $this->SetXY(35, 13);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, utf8_decode("Nit:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(35, 17);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, utf8_decode("Dirección"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->direccion), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(35, 21);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, utf8_decode("Telefono:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->telefono), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(35, 25);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, utf8_decode("Municipio:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->codigoMunicipio->municipio." - ".$config->codigoDepartamento->departamento), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(35, 29);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, utf8_decode("Tipo regimen:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->tipoRegimen), 0, 0, 'L', 1);
       //liena
         $this->SetXY(10, 32);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 32.5);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Recibo caja
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(8, 38);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(63, 7, utf8_decode("REMISION DE SALIDA"), 0, 0, 'l', 0);
        $this->Cell(15, 7, utf8_decode('N°. ' . str_pad($conRemision->numero_remision, 6, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
      //  $this->SetFillColor(200, 200, 200);
        //fin
        $this->SetXY(8, 52); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("Nit/Cedula:"), 0, 0, 'L','0');
        $this->SetFont('Arial', '', 8);        
        $this->Cell(60, 5, utf8_decode($conRemision->cliente->nit_cedula . '-' . $conRemision->cliente->dv), 0, 0, 'L','0');                
        
        $this->SetXY(8, 56); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("Cliente:"), 0, 0, 'L','0');
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 5, utf8_decode($conRemision->cliente->nombre_completo), 0, 0, 'L','0');
        //fin
        $this->SetXY(8, 60); //FILA 3
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("Dirección:"), 0, 0, 'L','0');
        $this->SetFont('Arial', '', 8);        
        $this->Cell(60, 5, utf8_decode($conRemision->cliente->direccion), 0, 0, 'L','0');          
        //fin
        
        $this->SetXY(8, 64); //FILA 3
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("Fecha remision:"), 0, 0, 'L','0');
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 5, utf8_decode($conRemision->fecha_inicio), 0, 0, 'L','0');
        //fin
        $this->SetXY(8, 68); //FILA 2
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("Punto de venta:"), 0, 0, 'L','0');
        $this->SetFont('Arial', '', 8);        
        $this->Cell(60, 5, utf8_decode($conRemision->puntoVenta->nombre_punto), 0, 0, 'L','0'); 
        //fin
        $this->SetXY(8, 72); //FILA 2
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("Total pagar:"), 0, 0, 'L','0');
        $this->SetFont('Arial', '', 8);        
        $this->Cell(60, 5, utf8_decode('$ '.number_format($conRemision->total_remision,0)), 0, 0, 'L','0'); 
       
        //fin
        $this->Line(7,50,95,50);//linea superior horizontal
        $this->Line(7,170,7,50);//primera linea en y
        $this->Line(95,170,95,50);//primera linea en y
        $this->Line(7,170,95,170);//linea inferior horizontal
         //Lineas del encabezado
        $this->Line(10,120,10,83); //la primera con la tercera es para la la raya iguales, la segunda es el lago o corto y la tercera es la tamaño de largo
        $this->Line(42,120,42,83);
        $this->Line(54,120,54,83);
        $this->Line(69,120,69,83);
        $this->Line(93,120,93,83);
        $this->Line(10,120,93,120);//linea inferior horizontal
           $this->EncabezadoDetalles();             
        //Detalle factura
    }   
        function EncabezadoDetalles() {
        $this->Ln(7);
        $header = array('REFERENCIA', 'CANT.','VR. UNIT.','TOTAL');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(32, 12, 15, 24);
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
    function Body($pdf, $model) {
        $detalles = RemisionDetalles::find()->where(['=','id_remision',$model->id_remision])->all();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $cant = 0;
        foreach ($detalles as $detalle) { 
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(32, 4, utf8_decode(mb_substr($detalle->producto, 0,20)) , 0, 0, 'L');
            $pdf->Cell(12, 4, $detalle->cantidad, 0, 0, 'R');
            $pdf->Cell(15, 4, number_format($detalle->valor_unitario, 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(24, 4, number_format($detalle->total_linea, 0, '.', ','), 0, 0, 'R');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);
        }
        $this->SetFont('Arial', '', 12);
        $this->Text(25, 147, utf8_decode('Muchas gracias por su compra!'),0,0, 'C');
        $this->Text(27, 152, utf8_decode('Fue un placer poderle servir!'),0, 0, 'C');
    }
    function Footer() {

        $this->SetFont('Arial', '', 8);
        $this->Text(8, 167, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(250, 205, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

global $id_remision;
$id_remision = $model->id_remision;
$pdf = new PDF('P', 'mm', array(100,185));
//$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf, $model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Remision_No:$model->numero_remision.pdf", 'D');

exit;

function zero_fill($valor, $long = 0) {
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
