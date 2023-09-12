<?php

use inquid\pdf\FPDF;
use app\models\PresupuestoMensual;
use app\models\PresupuestoEmpresarial;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_mensual = $GLOBALS['id_mensual'];
        $mensual = PresupuestoMensual::findOne($id_mensual);
        $config = MatriculaEmpresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);
        //Logo
       $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 19);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(53, 9);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("EMPRESA:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 1);
        $this->SetXY(30, 5);
        //FIN
        $this->SetXY(53, 13);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("NIT:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 17);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("DIRECCION:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->direccion), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 21);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("TELEFONO:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->telefono), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 25);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("MUNICIPIO:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->codigoMunicipio->municipio." - ".$config->codigoDepartamento->departamento), 0, 0, 'L', 1);
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
        $this->Cell(162, 7, utf8_decode("PRESUPUESTO GASTADO MENSUAL"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($mensual->id_mensual, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("ID:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($mensual->id_mensual), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("PRESUPUESTO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($mensual->presupuesto->descripcion), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("F. INICIO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(16, 5, utf8_decode($mensual->fecha_inicio), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(22, 5, utf8_decode("F. CORTE:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, ($mensual->fecha_corte), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("AUTORIZADO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($mensual->autorizadoMes), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("CERRADO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($mensual->cerradoMes), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("No REGISTRO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(16, 5, utf8_decode($mensual->total_registro), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(22, 5, utf8_decode("VL. GASTADO:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, ''. number_format($mensual->valor_gastado,0), 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("USUARIO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($mensual->user_name), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("F. PROCESO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($mensual->fecha_creacion), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("AÑO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(16, 5, utf8_decode($mensual->presupuesto->año), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(22, 5, utf8_decode("T.PRESUPUESTO:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, utf8_decode($mensual->presupuesto->valor_presupuesto), 0, 0, 'R', 1);
        //FIN
         $this->EncabezadoDetalles();
     
        //Lineas del encabezado
        $this->Line(10,70,10,250);
        $this->Line(80,70,80,250);
        $this->Line(120,70,120,250);
        $this->Line(160,70,160,250);
        $this->Line(202,70,202,250);
        $this->Line(10,250,202,250);//linea horizontal inferior  
       
        
      
    }
    function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('CLIENTE', ('DESDE'), ('HASTA'), ('VL. GASTADO'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(70, 40, 40, 42);
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
        $detalles = app\models\PresupuestoMensualDetalle::find()->where(['=','id_mensual', $model->id_mensual])->all();		
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
		
        foreach ($detalles as $detalle) {                                                           
            $pdf->Cell(70, 4, $detalle->cliente->nombre_completo, 0, 0, 'L');
            $pdf->Cell(40, 4, $detalle->mensual->fecha_inicio, 0, 0, 'C');
            $pdf->Cell(40, 4, $detalle->mensual->fecha_corte, 0, 0, 'C');
            $pdf->Cell(42, 4, ''.number_format($detalle->gasto_mensual,0), 0, 0, 'R');
            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
	$this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        
	
        //firma empresa
        $pdf->SetXY(10, 265);//firma trabajador
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA AUTORIZADO: ____________________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 270);
        $pdf->Cell(35, 5, 'NIT/CC.:', 0, 0, 'L',0);
    }

    function Footer() {

        $this->SetFont('Arial', '', 7);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
global $id_mensual;
$id_mensual = $model->id_mensual;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Presupues_mensual_$model->id_mensual.pdf", 'D');

exit;
