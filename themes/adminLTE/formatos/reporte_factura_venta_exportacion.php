<?php
ob_start();
include "../vendor/phpqrcode/qrlib.php";
use inquid\pdf\FPDF;
use app\models\FacturaVenta;
use app\models\FacturaVentaDetalle;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//clase
class PDF extends FPDF {
    function Header() {
        $id_factura = $GLOBALS['id_factura'];
        $factura = FacturaVenta::findOne($id_factura);
        $config = MatriculaEmpresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);
        $resolucion = \app\models\ResolucionDian::findOne($factura->id_resolucion);
        //Logo
        $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 7, 30, 30);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(53, 7);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Empresa:"), 0, 0, 'l', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 0);
        $this->SetXY(30, 5);
        //FIN
        $this->SetXY(53, 10);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("Nit:"), 0, 0, 'l', 0);
         $this->SetFont('Arial', '', 8);
        $this->Cell(50, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 0);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 13);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Dirección:"), 0, 0, 'l', 0);
         $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($config->direccion), 0, 0, 'L', 0);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 16);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Telefono:"), 0, 0, 'l', 0);
         $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($config->telefono), 0, 0, 'L', 0);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 19);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Municipio:"), 0, 0, 'L', 0);
         $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($config->codigoMunicipio->municipio." - ".$config->codigoDepartamento->departamento), 0, 0, 'L', 0);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 22);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Tipo regimen:"), 0, 0, 'L', 0);
         $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($config->tipoRegimen->regimen), 0, 0, 'L', 0);
        $this->SetXY(40, 5);
        //fin
         $this->SetXY(53, 25);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Email:"), 0, 0, 'L', 0);
          $this->SetFont('Arial', '', 8);
        $this->Cell(50, 5, utf8_decode($config->email_factura_exportacion), 0, 0, 'L','0');
        $this->SetXY(40, 5);
        //fin
           
        $this->SetXY(53, 32);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(100, 3, utf8_decode($config->mensaje_normativo1), 0, 0, 'L','0');
        $this->SetXY(40, 3);
        //fin
        $this->SetXY(19, 35);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(100, 3, utf8_decode($config->mensaje_normativo2.' '.$config->mensaje_normativo3), 0, 0, 'L','0');
        $this->SetXY(40, 3);
        //fin
        //DATOS DE LA FACTURA
          $this->SetXY(135, 7);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(162, 7, utf8_decode("FACTURA DE EXPORTACION"), 0, 0, 'l', 0);
        $this->SetXY(155, 12);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 7, utf8_decode('No '.$factura->consecutivo.'    '.($factura->numero_factura)), 0, 0, 'l', 0);
        $this->SetXY(140, 18);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 4, utf8_decode('Resolución Dian No: '.$resolucion->numero_resolucion), 0, 0, 'l', 0);
        //
        $this->SetXY(127, 22);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 3, utf8_decode('Fecha formalización: '.$resolucion->desde. ' hasta el ' .$resolucion->hasta), 0, 0, 'l', 0);
        //
        $this->SetXY(145, 26);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 3, utf8_decode('Habilita rango: '.$resolucion->rango_inicio. ' hasta el ' .$resolucion->rango_final), 0, 0, 'l', 0);
         //
        $this->SetXY(155, 30);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 3, utf8_decode('Vigencia: '.$resolucion->vigencia.' Meses'), 0, 0, 'l', 0);
        
        // datos del cliente
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 40);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Nit:"), 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(48, 5, utf8_decode($factura->nit_cedula.'-'.$factura->dv), 0, 0, 'L',0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Cliente:"), 0, 0, 'c', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 5, utf8_decode($factura->cliente), 0, 0, 'c', 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("F. pago:"), 0, 0, 'c', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 5, utf8_decode($factura->formaPago->concepto.' ('.$factura->plazo_pago.')  Dias'), 0, 0, 'L', 0);
        //FIN
        $this->SetXY(10, 44);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Dirección:"), 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(48, 5, utf8_decode($factura->direccion), 0, 0, 'L',0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Email:"), 0, 0, 'c', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 5, utf8_decode($factura->clienteFactura->email_cliente) , 0, 0, 'c', 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Telefono:"), 0, 0, 'c', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 5, utf8_decode($factura->telefono_cliente) , 0, 0, 'c', 0);
        //FIN
         $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Pais:"), 0, 0, 'l', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(48, 5, utf8_decode($factura->clienteFactura->codigoDepartamento->departamento), 0, 0, 'L',0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Ciudad:"), 0, 0, 'l', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 5, utf8_decode($factura->clienteFactura->codigoMunicipio->municipio), 0, 0, 'L', 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Vendedor:"), 0, 0, 'c', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 5, utf8_decode(substr($factura->agenteFactura->nombre_completo,0, 18)), 0, 0, 'c', 0);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Fecha expedición:"), 0, 0, 'l', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(48, 5, utf8_decode($factura->fecha_inicio), 0, 0, 'L',0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Fecha vcto:"), 0, 0, 'l', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 5, utf8_decode($factura->fecha_vencimiento), 0, 0, 'L', 0);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Medio pago:"), 0, 0, 'c', 0);
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 5, utf8_decode($factura->medioPago->concepto), 0, 0, 'c', 0);
       //FIN
       //LINEAS DEL DETALLE
        $this->Line(10,65,10,260); //la primera con la tercera es para la la raya iguales, la segunda es el lago o corto y la tercera es la tamaño de largo
        $this->Line(15,65,15,204);
        $this->Line(37,65,37,204);
        $this->Line(111,65,111,204);
        $this->Line(124,65,124,204);
        $this->Line(144,65,144,204);
        $this->Line(173,65,173,204);
        $this->Line(202,65,202,260);
        //lineas del marco
        $this->Line(10,40,202,40);//linea superior horizontal
        $this->Line(10,40,10,58);//primera linea en y
        $this->Line(202,40,202,58);//primera linea en y
        $this->Line(10,58,202,58);//linea inferior horizontal
        
        $this->Line(10,204,202,204);//linea horizontal superior
        $this->Line(10,260,202,260);//linea horizontal superior
        
        
         $this->EncabezadoDetalles();
    }
    
    function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('#', 'Codigo / Code', 'Descripcion / Description', 'U.M / Unit' ,'Cant. / Quantity', 'Vr. Unit / Unit Value', 'Vr.Total / Total Value');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(5, 22, 74, 13, 20, 29, 29);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(6);
        
    }
    
    function Body($pdf,$model) {
        $config = MatriculaEmpresa::findOne(1);
        $detalles = FacturaVentaDetalle::find()->where(['=','id_factura',$model->id_factura])->all();
        $moneda = app\models\ClienteMoneda::find()->where(['=','id_cliente', $model->id_cliente])->one(); 
        $terminos = \app\models\TerminosFacturaExportacion::find()->where(['=','id_factura', $model->id_factura])->one();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $cant = 0;
        $lineas = 0;
        $contador = 0;
        foreach ($detalles as $detalle) {
            $contador += 1;
            $lineas += 1;
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(5, 4, $lineas, 0, 0, 'L');
            $pdf->Cell(22, 4, $detalle->inventario->codigo_producto, 0, 0, 'L');
             $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(74, 4, $detalle->inventario->nombre_producto , 0, 0, 'L');
             $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(13,  4, $detalle->inventario->presentacion->medidaProducto->codigo_enlace, 0, 0, 'L');
            $pdf->Cell(20, 4, $detalle->cantidad, 0, 0, 'R');
            $pdf->Cell(29, 4, $detalle->valor_unitario_internacional, 0, 0, 'R');
            $pdf->Cell(29, 4, $detalle->subtotal_internacional, 0, 0, 'R');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 19);
            $cant += $detalle->cantidad;
            if ($contador % 34 == 0) {
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(88, 270);
                $pdf->MultiCell(60, 7, utf8_decode('Continua en la otra página'),0,'L');
                $pdf->AddPage();
                $this->Line(10,260,202,260);
           
            }
            
        }
        if (!$contador % 34 == 0) {
             $this->Line(10,260,202,260);
        }
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 205);
        $this->Cell(20, 4, utf8_decode("Value in letters:"), 0, 0, 'L', 0);
         $this->SetFont('Arial', '', 8);
        $pdf->MultiCell(146, 4, utf8_decode(valorEnLetras($model->total_factura_internacional)),0,'J');
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(144, 204);
        $pdf->MultiCell(29, 7, 'Cant. / Quantity:',1,'L',1);
        $pdf->SetXY(173, 204);
        $pdf->MultiCell(29, 7, ''.number_format($cant, 0, '.', ','),1,'R');
       //FIN
       
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 211);
        $this->Cell(20, 4, utf8_decode("Observacion:"), 0, 0, 'L', 0);
        $this->SetFont('Arial', '', 8);
        $pdf->MultiCell(146, 4, utf8_decode($model->observacion),0,'J');
        $pdf->SetXY(144, 211);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(29, 7, 'Total bruto:',1,'L',1);
        $pdf->SetXY(173, 211);
        $pdf->MultiCell(29, 7, $model->valor_bruto_internacional, 1, 'R');
         //FIN
        $pdf->SetXY(144, 218);
        $pdf->MultiCell(29, 7, 'D. Comercial:',1,'L',1);
        $pdf->SetXY(173, 218);
        $pdf->MultiCell(29, 7, $model->descuento_comercial_internacional,1,'R');
        //FIN
        $pdf->SetXY(144, 225);
        $pdf->MultiCell(29, 7, 'Subtotal:',1,'L', 1);
        $pdf->SetXY(173, 225);
        $pdf->MultiCell(29, 7, $model->subtotal_factura_internacional,1,'R');
        ///fin
        
        $pdf->SetXY(144, 232);           
        $pdf->MultiCell(29, 7, 'Total Pagar en '. $moneda->sigla, 1, 'L', 1);
        $pdf->SetXY(173, 232);
        $pdf->MultiCell(29, 7, $model->total_factura_internacional,1,'R');
        //fin del subtotal
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 218); 
        $pdf->MultiCell(70, 4, utf8_decode('Datos exportacion / Export data'),1,'C');  
        //campos
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 224); 
        $pdf->Cell(40, 4, utf8_decode('Incoterm / Incoterm:'),0,'L');
        //fin
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 228); 
        $pdf->Cell(40, 4, utf8_decode('Descripción exportación / Description:'),0,'L');
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(62, 228);
        if($terminos){
            $pdf->MultiCell(40, 4, $terminos->inconterm->concepto, 0, 'L');
        }    
        //fin
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 232); 
        $pdf->Cell(40, 4, utf8_decode('Medio transporte / Transportmean:'),0,'L');
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(62, 232);
        if($terminos){
            if($terminos->medio_transporte == 0){
                $pdf->MultiCell(40, 4, 'TERRESTRE', 0, 'L');
            }else{
                if($terminos->medio_transporte == 1){
                     $pdf->MultiCell(40, 4, 'MARITIMO', 0, 'L');
                }else{
                     $pdf->MultiCell(40, 4, 'AEREO', 0, 'L');
                }
            }   
        }    
        //fin
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 236); 
        $pdf->Cell(40, 4, utf8_decode('Pais origen / OrigenCountry:'),0,'L');
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(62, 236);
        if($terminos){
            $pdf->MultiCell(40, 4, $terminos->pais->pais, 0, 'L');
        }
        
        //fin
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 240); 
        $pdf->Cell(40, 4, utf8_decode('Ciudad origen / OrigenCity:'),0,'L');
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(62, 240);
        if($terminos){
            $pdf->MultiCell(40, 4, $terminos->ciudadOrigen->municipio, 0, 'L');
        }    
        //FIN
        
         //fin
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 244); 
        $pdf->Cell(40, 4, utf8_decode('Pais Destino / TargetCountry:'),0,'L');
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(62, 244);
        $pdf->MultiCell(40, 4, $model->clienteFactura->codigoDepartamento->codigoPais->pais, 0, 'L');
        
        //moneda negociada
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(144, 243); 
        $pdf->Cell(40, 4, utf8_decode('Moneda / Money:'),0,'L');
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(180, 243);
        $pdf->MultiCell(40, 4, $moneda->sigla, 0, 'L');
        //fin
       
        //fin
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 248); 
        $pdf->Cell(40, 4, utf8_decode('Ciudad destino / TargetCity:'),0,'L');
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(62, 248);
        if($terminos){
            $pdf->MultiCell(40, 4, $terminos->ciudad_destino, 0, 'L');
        }    
        //FIN
         //peso bruto
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(144, 247); 
        $pdf->Cell(40, 4, utf8_decode('Peso bruto / GrossWeight:'),0,'L');
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(180, 247);
        if($terminos){
            $pdf->MultiCell(40, 4, $terminos->peso_bruto.' - '.$terminos->medidaProducto->descripcion, 0, 'L');
        }    
        //fin
          //peso neto
        $this->SetFont('Arial', 'B', 8);
        $pdf->SetXY(144, 251); 
        $pdf->Cell(40, 4, utf8_decode('Peso neto / NetWeight:'),0,'L');
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(180, 251);
        if($terminos){
            $pdf->MultiCell(40, 4, $terminos->peso_neto.' - '.$terminos->medidaProducto->descripcion, 0, 'L');
        }    
        //fin
        ///cufe
        $this->SetFont('Arial', '', 8);
        $pdf->SetXY(10, 255);//tipo cuenta
        $pdf->MultiCell(192, 5, utf8_decode('Cufe: '.$model->cufe),0,'L');
        //declaracion de norma de la factura
        $this->SetFont('Arial', '', 7);
        $pdf->SetXY(10, 263);//tipo cuenta
        $pdf->MultiCell(192, 4, utf8_decode($config->declaracion),1,'J');  
       
        ///representacion grafica
        $pdf->SetXY(138, 218);//recibido,aceptado 
        $this->SetFont('Arial', '', 8);
     /*   $qrstr = utf8_decode($model->qrstr);
        $pdf->SetXY(120, 70); // Establece la posición donde aparecerá el QR
        QRcode::png($qrstr,"test.png");
        $pdf->Image("test.png", 102, 208, 38, 35, "png");
        unlink("test.png");*/
    
    }
    function Footer() {
        $this->SetFont('Arial', '', 7);
        $this->Text(170, 285, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
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
$pdf->Output("Factura_venta_Exportacion$model->numero_factura.pdf", 'D');
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
        $I6 = "Million ";
    } else {
        $I6 = "Milliones ";
    }

    if ($G8 == 0 AND $G7 == 0) {
        $I8 = " ";
    } else {
        $I8 = "thousand ";
    }

    $I10 = "With ";
    $I11 = "  Centavos ";

    $C3 = $signo . $H6 . $I6 . $H7 . $H8 . $I8 . $H9 . $H10 . $I10 . $H11 . $I11;

    return $C3; //Retornar el resultado 
}

function unidades($u) {
    if ($u == 0) {
        $ru = " ";
    } elseif ($u == 1) {
        $ru = "One ";
    } elseif ($u == 2) {
        $ru = "Two ";
    } elseif ($u == 3) {
        $ru = "Three ";
    } elseif ($u == 4) {
        $ru = "Four ";
    } elseif ($u == 5) {
        $ru = "Five ";
    } elseif ($u == 6) {
        $ru = "Six ";
    } elseif ($u == 7) {
        $ru = "Seven ";
    } elseif ($u == 8) {
        $ru = "Eight ";
    } elseif ($u == 9) {
        $ru = "Nine ";
    } elseif ($u == 10) {
        $ru = "Ten ";
    } elseif ($u == 11) {
        $ru = "Eleve ";
    } elseif ($u == 12) {
        $ru = "Twelve ";
    } elseif ($u == 13) {
        $ru = "Thirteen ";
    } elseif ($u == 14) {
        $ru = "Fourteen ";
    } elseif ($u == 15) {
        $ru = "Fifteen ";
    } elseif ($u == 16) {
        $ru = "Sixteen ";
    } elseif ($u == 17) {
        $ru = "Seventeen ";
    } elseif ($u == 18) {
        $ru = "Eighteen ";
    } elseif ($u == 19) {
        $ru = "Nineteen ";
    } elseif ($u == 20) {
        $ru = "Twenty ";
    } elseif ($u == 21) {
        $ru = "Twenty one ";
    } elseif ($u == 22) {
        $ru = "Twenty two ";
    } elseif ($u == 23) {
        $ru = "Twenty three ";
    } elseif ($u == 24) {
        $ru = "Twenty four ";
    } elseif ($u == 25) {
        $ru = "Twenty five ";
    } elseif ($u == 26) {
        $ru = "Twenty six ";
    } elseif ($u == 27) {
        $ru = "Twenty seven ";
    } elseif ($u == 28) {
        $ru = "Twenty eigth ";
    } elseif ($u == 29) {
        $ru = "Twenty nine ";
    } elseif ($u == 30) {
        $ru = "Thirty ";
    } elseif ($u == 31) {
        $ru = "Thirty one ";
    } elseif ($u == 32) {
        $ru = "Thirty two ";
    } elseif ($u == 33) {
        $ru = "Thirty three ";
    } elseif ($u == 34) {
        $ru = "Thirty four ";
    } elseif ($u == 35) {
        $ru = "Thirty five ";
    } elseif ($u == 36) {
        $ru = "Thirty six ";
    } elseif ($u == 37) {
        $ru = "Thirty seven ";
    } elseif ($u == 38) {
        $ru = "Thirty eigth ";
    } elseif ($u == 39) {
        $ru = "Thirty nine ";
    } elseif ($u == 40) {
        $ru = "forty ";
    } elseif ($u == 41) {
        $ru = "forty one ";
    } elseif ($u == 42) {
        $ru = "forty two ";
    } elseif ($u == 43) {
        $ru = "forty three ";
    } elseif ($u == 44) {
        $ru = "forty four ";
    } elseif ($u == 45) {
        $ru = "forty five ";
    } elseif ($u == 46) {
        $ru = "forty six ";
    } elseif ($u == 47) {
        $ru = "forty seven ";
    } elseif ($u == 48) {
        $ru = "forty eight ";
    } elseif ($u == 49) {
        $ru = "forty nine ";
    } elseif ($u == 50) {
        $ru = "fifty ";
    } elseif ($u == 51) {
        $ru = "fifty one ";
    } elseif ($u == 52) {
        $ru = "fifty two ";
    } elseif ($u == 53) {
        $ru = "fifty three ";
    } elseif ($u == 54) {
        $ru = "fifty four ";
    } elseif ($u == 55) {
        $ru = "fifty five ";
    } elseif ($u == 56) {
        $ru = "fifty six ";
    } elseif ($u == 57) {
        $ru = "fifty seven ";
    } elseif ($u == 58) {
        $ru = "fifty eight ";
    } elseif ($u == 59) {
        $ru = "fifty nine ";
    } elseif ($u == 60) {
        $ru = "sixty ";
    } elseif ($u == 61) {
        $ru = "sixty one ";
    } elseif ($u == 62) {
        $ru = "sixty two ";
    } elseif ($u == 63) {
        $ru = "sixty threee ";
    } elseif ($u == 64) {
        $ru = "sixty four ";
    } elseif ($u == 65) {
        $ru = "sixty five ";
    } elseif ($u == 66) {
        $ru = "sixty six ";
    } elseif ($u == 67) {
        $ru = "sixty seven ";
    } elseif ($u == 68) {
        $ru = "sixty eight ";
    } elseif ($u == 69) {
        $ru = "sixty nine ";
    } elseif ($u == 70) {
        $ru = "seventy ";
    } elseif ($u == 71) {
        $ru = "seventy one ";
    } elseif ($u == 72) {
        $ru = "seventy two ";
    } elseif ($u == 73) {
        $ru = "seventy three ";
    } elseif ($u == 74) {
        $ru = "seventy four ";
    } elseif ($u == 75) {
        $ru = "seventy five";
    } elseif ($u == 76) {
        $ru = "seventy six ";
    } elseif ($u == 77) {
        $ru = "seventy seven ";
    } elseif ($u == 78) {
        $ru = "seventy eight ";
    } elseif ($u == 79) {
        $ru = "seventy nine ";
    } elseif ($u == 80) {
        $ru = "eighty ";
    } elseif ($u == 81) {
        $ru = "eighty one ";
    } elseif ($u == 82) {
        $ru = "eighty two ";
    } elseif ($u == 83) {
        $ru = "eighty three ";
    } elseif ($u == 84) {
        $ru = "eighty four ";
    } elseif ($u == 85) {
        $ru = "eighty five ";
    } elseif ($u == 86) {
        $ru = "eighty six ";
    } elseif ($u == 87) {
        $ru = "eighty seven ";
    } elseif ($u == 88) {
        $ru = "eighty eight ";
    } elseif ($u == 89) {
        $ru = "eighty nine ";
    } elseif ($u == 90) {
        $ru = "ninety ";
    } elseif ($u == 91) {
        $ru = "ninety one ";
    } elseif ($u == 92) {
        $ru = "ninety two ";
    } elseif ($u == 93) {
        $ru = "ninety three ";
    } elseif ($u == 94) {
        $ru = "ninety four ";
    } elseif ($u == 95) {
        $ru = "ninety five ";
    } elseif ($u == 96) {
        $ru = "ninety six ";
    } elseif ($u == 97) {
        $ru = "ninety seven ";
    } elseif ($u == 98) {
        $ru = "ninety eight ";
    } else {
        $ru = "ninety nine ";
    }
    return $ru; //Retornar el resultado 
}

function decenas($d) {
    if ($d == 0) {
        $rd = "";
    } elseif ($d == 1) {
        $rd = "Hundred ";
    } elseif ($d == 2) {
        $rd = "Two hundred ";
    } elseif ($d == 3) {
        $rd = "Three hundred ";
    } elseif ($d == 4) {
        $rd = "Four hundred ";
    } elseif ($d == 5) {
        $rd = "Five hundred ";
    } elseif ($d == 6) {
        $rd = "Six hundred ";
    } elseif ($d == 7) {
        $rd = "Seven hundred ";
    } elseif ($d == 8) {
        $rd = "Eight hundred ";
    } else {
        $rd = "Nine hundred ";
    }
    return $rd; //Retornar el resultado 
}