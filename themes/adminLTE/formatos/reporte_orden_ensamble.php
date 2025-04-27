<?php

use inquid\pdf\FPDF;
use app\models\OrdenEnsambleProducto;
use app\models\OrdenEnsambleProductoDetalle;

class PDF extends FPDF {

    function Header() {
        $id_ensamble = $GLOBALS['id_ensamble'];
        $orden = OrdenEnsambleProducto::findOne($id_ensamble);
//Logo
         $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 45, 10, 30, 19);
        //Encabezado
        
        //FIN
        $this->SetXY(10, 29);
        $this->Cell(250, 7, utf8_decode("____________________________________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 30);
        $this->Cell(250, 7, utf8_decode("____________________________________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Programación Nomina
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(10, 36);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(225, 7, utf8_decode("ORDEN DE ENSAMBLE - " .$orden->productos->nombre_producto), 0, 0, 'l', 0);
        $this->Cell(40, 7, utf8_decode('N°. '.str_pad($orden->numero_orden_ensamble, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
        //fin
        $this->SetXY(10, 44); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("Grupo:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($orden->grupo->nombre_grupo), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("Responsable:"), 0, 0, 'L','1');
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 5, utf8_decode($orden->responsable), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 5, utf8_decode("Orden produccion:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(46, 5, utf8_decode($orden->ordenProduccion->numero_orden), 0, 0, 'R', 1);
        //fin
        $this->SetXY(10, 48); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("Etapa:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($orden->etapa->concepto), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("Usuario:"), 0, 0, 'L','1');
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 5, utf8_decode($orden->user_name), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 5, utf8_decode("Peso neto:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(46, 5, utf8_decode($orden->peso_neto), 0, 0, 'L', 1);
        //FIN
         $this->SetXY(10, 52); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("F. proceso:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($orden->fecha_hora_registro), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("Fecha carga:"), 0, 0, 'L','1');
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 5, utf8_decode($orden->fecha_proceso), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 5, utf8_decode("Fecha hora cierre:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(46, 5, utf8_decode($orden->fecha_hora_cierre), 0, 0, 'L', 1);
        //FIN
        //linea del llogo
        $this->Line(45,8,240,8);//linea superior horizontal
        $this->Line(45,30,45,8);//primera linea en y
        $this->Line(80,30,80,8);//segunda linea en y
        $this->Line(165,30,165,8);//tercera linea en y
        $this->Line(240,30,240,8);//cuarta linea en y
        $this->Line(45,30,240,30);//linea inferior horizontal
        
        //Lineas del encabezado
        $this->Line(10, 65, 10, 80); //x1,y1,x2,y2        
        $this->Line(40, 65, 40, 80); //x1,y1,x2,y2
        $this->Line(143, 65, 143, 80); //x1,y1,x2,y2
        $this->Line(184, 65, 184, 80); //x1,y1,x2,y2
        $this->Line(226, 65, 226, 80); //x1,y1,x2,y2
        $this->Line(267, 65, 267, 80); //x1,y1,x2,y2
        $this->Line(10, 80, 267, 80); //linea horizontal inferior x1,y1,x2,y2
         //Líneas MATERIAL DE EMPAQUE
        $this->Line(10,90,10,145);
        $this->Line(70,90,70,145);
        $this->Line(97,90,97,145);
        $this->Line(124,90,124,145);
        $this->Line(149,90,149,145);
        $this->Line(176,90,176,145);
        $this->Line(206,90,206,145);
        $this->Line(246,90,246,145);
        $this->Line(268,90,268,145);
        $this->Line(10,145,268,145);//linea horizontal inferior
        //Detalle factura
        $this->EncabezadoDetalles();
    }

    function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array(utf8_decode('CODIGO'), ('PRESENTACION PRODUCTO'),'CANTIDAD PROYECTADA', 'CANTIDAD REAL', '% RENDIMIENTO');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(30,103,41, 42, 41);
        for ($i = 0; $i < count($header); $i++){
            if ($i == 0 || $i == 1){
                $this->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);
            }else{
                $this->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);
            }
        }
        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(5);
    }

    function Body($pdf, $model) {
        $detalles = OrdenEnsambleProductoDetalle::find()->where(['=', 'id_ensamble', $model->id_ensamble])->andWhere(['<>','porcentaje_rendimiento', ''])->all();
        $materiales = app\models\OrdenEnsambleProductoEmpaque::find()->where(['=','id_ensamble', $model->id_ensamble])->all();
        $hola = (count($materiales));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $i = 0;
        foreach ($detalles as $detalle) {
            $i = $i + 1;
            $pdf->Cell(30, 4, $detalle->codigo_producto, 0, 0, 'L');
            $pdf->Cell(103, 4, $detalle->nombre_producto, 0, 0, 'L');
            $pdf->Cell(41, 4, $detalle->cantidad_proyectada, 0, 0, 'R');
            $pdf->Cell(42, 4, $detalle->cantidad_real, 0, 0, 'R');
            $pdf->Cell(41, 4, $detalle->porcentaje_rendimiento, 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 10);
        }
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        $pdf->SetXY(10, 84);
        $this->SetFont('', 'B', 7);
        $pdf->Cell(258, 5, utf8_decode('MATERIAL DE EMPAQUE (M.E.)'), 1, 0, 'C',1);
        $pdf->SetXY(10, 89);
        $pdf->Cell(60, 4, 'MATERIAL DE EMPAQUE', 1, 0, 'C',1);
        $pdf->Cell(27, 4, 'U. SOLICITADAS', 1, 0, 'C',1);
        $pdf->Cell(27, 4, utf8_decode('U. DEVOLUCION'), 1, 0, 'C',1);
        $pdf->Cell(25, 4, utf8_decode('U. AVERIAS'), 1, 0, 'C',1);
        $pdf->Cell(27, 4, utf8_decode('U. UTILIZADAS'), 1, 0, 'C',1);
        $pdf->Cell(30, 4, utf8_decode('U. SALA TECNICA'), 1, 0, 'C',1);
        $pdf->Cell(40, 4, utf8_decode('U. MUESTRAS RETENCION'), 1, 0, 'C',1);
        $pdf->Cell(22, 4, utf8_decode('U. REALES'), 1, 0, 'C',1);
        $pdf->SetXY(10, 94);
        $pdf->SetFont('Arial', '', 7);
        foreach ($materiales as $empaque) {                                    
            $pdf->Cell(60, 4, utf8_decode(substr($empaque->materiaPrima->materia_prima, 0, 30)), 0, 0, 'L');            
            $pdf->Cell(27, 4, ''. number_format($empaque->unidades_solicitadas,0), 0, 0, 'R');
            $pdf->Cell(27, 4, ''.number_format($empaque->unidades_devolucion, 0), 0, 0, 'R');
            $pdf->Cell(25, 4, ''.number_format($empaque->unidades_averias, 0), 0, 0, 'R');
            $pdf->Cell(27, 4, ''.number_format($empaque->unidades_utilizadas, 0), 0, 0, 'R');
            $pdf->Cell(30, 4, ''.number_format($empaque->unidades_sala_tecnica, 0), 0, 0, 'R');
            $pdf->Cell(40, 4, ''.number_format($empaque->unidades_muestra_retencion, 0), 0, 0, 'R');
            $pdf->Cell(22, 4, ''.number_format($empaque->unidades_reales, 0), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
        $pdf->SetXY(10, 155);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(146, 4, utf8_decode('OBSERVACION: '.$model->observacion),0,'J');  
        //firmas
        $pdf->SetXY(10, 175);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(100, 5, '__________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 180);
        $pdf->Cell(120, 5, 'Lider de produccion', 0, 0, 'L',0);
        //
        $pdf->SetXY(68, 175);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(100, 5, '__________________________', 0, 0, 'L',0);
        $pdf->SetXY(68, 180);
        $pdf->Cell(120, 5, 'Operario de produccion', 0, 0, 'L',0);
         //
        $pdf->SetXY(136, 175);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(100, 5, '__________________________', 0, 0, 'L',0);
        $pdf->SetXY(136, 180);
        $pdf->Cell(120, 5, 'Operario de logistica', 0, 0, 'L',0);
         //
        $pdf->SetXY(203, 175);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(100, 5, '__________________________', 0, 0, 'L',0);
        $pdf->SetXY(203, 180);
        $pdf->Cell(120, 5, 'Calidad', 0, 0, 'L',0);
        //LINEAS QUE LLENA EL FORMATO DE ISO 
        $pdf->SetXY(110, 10);
        $this->SetFont('', 'B', 12);
        $pdf->Cell(20, 5, 'GESTION DE PRODUCCION', 0, 0, 'C',0);
        $this->SetXY(79, 13);
        $this->SetFont('', '', 12);
        $pdf->Cell(120, 5, '____________________________________________________________________', 0, 0, 'L',0);
        //segunda linea
        $pdf->SetXY(165, 10);
        $pdf->Cell(120, 5, 'Codigo: GP-F-012', 0, 0, 'L',0);
        $this->SetXY(164, 20);
        $pdf->Cell(124, 5, '________________________________', 0, 0, 'L',0);
        $pdf->SetXY(165, 18);
        $pdf->Cell(120, 5, 'Version: 05', 0, 0, 'L',0);
        $pdf->SetXY(165, 25);
        $pdf->Cell(120, 5, 'Fecha: 30/01/23', 0, 0, 'L',0);
        //SEGUNDO NOMBRE
        $pdf->SetXY(110, 20);
        $this->SetFont('', 'B', 12);
        $pdf->Cell(20, 5, 'RENDIMIENTO Y CONCILIACION', 0, 0, 'C',0);
       
    }
    
    

    function Footer() {

        $this->SetFont('Arial', '', 8);
        $this->Text(10, 205, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(250, 205, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}

global $id_ensamble;
$id_ensamble = $model->id_ensamble;
$pdf = new PDF('L','mm','letter');
$pdf->SetFont('Arial','',10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf, $model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("OrdenEnsamble$model->numero_orden_ensamble.pdf", 'D');

exit;

function zero_fill($valor, $long = 0) {
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}
