<?php

use inquid\pdf\FPDF;

use app\models\ProgramacionNomina;
use app\models\ProgramacionNominaDetalle;
use app\models\ConceptoSalarios;
use app\models\PeriodoPagoNomina;
use app\models\Banco;
use app\models\PeriodoPago;

class PDF extends FPDF {

    function Header() {
        $id_programacion = $GLOBALS['id_programacion'];
        $programacionNomina = ProgramacionNomina::findOne($id_programacion);
        $empleado = \app\models\Empleados::findOne($programacionNomina->id_empleado);
        $banco_empleado = \app\models\BancoEmpleado::findOne($empleado->id_banco);
        $config = \app\models\MatriculaEmpresa::findOne(1);
        $municipio = \app\models\Municipios::findOne($config->codigo_municipio);
        $departamento = app\models\Departamentos::findOne($config->codigo_departamento);
        $periodo_pago = PeriodoPagoNomina::findOne($programacionNomina->id_periodo_pago_nomina);
        $periodo_pago_nomina = PeriodoPago::findOne($periodo_pago->id_periodo_pago);
        $tipo_pago = app\models\TipoNomina::findOne($periodo_pago->id_tipo_nomina);
        //Logo
        $this->SetXY(43, 10);
         $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 30);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(70, 9);
        $this->SetFont('Arial', '', 10);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Empresa:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 1);
        $this->SetXY(30, 5);
        //FIN
        $this->SetXY(70, 13);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Nit:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(70, 17);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Dirección:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->direccion), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(70, 21);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Telefono:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->telefono), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(70, 25);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Municipio:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->codigoMunicipio->municipio." - ".$config->codigoDepartamento->departamento), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN

        $this->SetXY(10, 29);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 30);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Programación Nomina
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(10, 36);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(162, 7, utf8_decode("COMPROBANTE DE PAGO"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($programacionNomina->nro_pago, 4, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(300, 300, 300);
        $this->SetXY(10, 44);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Documento:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($programacionNomina->cedula_empleado), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Fecha desde:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, utf8_decode($programacionNomina->fecha_desde), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(40, 5, utf8_decode("Fecha hasta:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, utf8_decode($programacionNomina->fecha_hasta), 0, 0, 'R', 1);
      //BLOQUE
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Empleado:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($programacionNomina->empleado->nombre_completo), 0, 0, 'L',1);
         $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Tipo de Pago:"), 0, 0, 'L', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, utf8_decode($tipo_pago->tipo_pago), 0, 0, 'L', 1);
         $this->SetFont('Arial', 'B', 7);
         $this->Cell(40, 5, utf8_decode("Tipo cuenta:"), 0, 0, 'R', 1);
          $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, utf8_decode($empleado->tipoCuenta), 0, 0, 'R', 1);  
        
       //FIN BLOQUE
        //INICIO
         $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("No cuenta:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($empleado->numero_cuenta), 0, 0, 'l', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Banco:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, utf8_decode($empleado->banco->entidad), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(40, 5, utf8_decode("Salario promedio:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, '$'. number_format($programacionNomina->salario_promedio, 0), 0, 0, 'R', 1);
        //FIN  
        $this->SetFont('Arial', '', 7);
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Grupo pago:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($programacionNomina->grupoPago->grupo_pago), 0, 0, 'L', 1);
         $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("No contrato:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, utf8_decode($programacionNomina->id_contrato), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(40, 5, utf8_decode("Salario:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);        
        $this->Cell(30, 5, '$ '. number_format($programacionNomina->salario_contrato, 0), 0, 0, 'R', 1);        
        
        $this->SetXY(10, 60);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("No periodo:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($programacionNomina->id_periodo_pago_nomina), 0, 0, 'l', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Dias periodo:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, utf8_decode($programacionNomina->dias_pago), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7); 
        $this->Cell(40, 5, utf8_decode("Total devengado:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, '$ '. number_format($programacionNomina->total_devengado, 0), 0, 0, 'R', 1);                        
       
        $this->SetXY(10, 64);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Dias reales:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($programacionNomina->dia_real_pagado), 0, 0, 'l', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Tipo pago:"), 0, 0, 'L', 1); ///OJOOOOOOOOOOOO
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, utf8_decode($periodo_pago->periodoPago->nombre_periodo), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(40, 5, utf8_decode("Total deducción:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, '$ '. number_format($programacionNomina->total_deduccion, 0), 0, 0, 'R', 1);
        
        $this->SetXY(10, 68);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("Inicio contrato:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($programacionNomina->fecha_inicio_contrato), 0, 0, 'l', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("User_name:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(23, 5, utf8_decode($programacionNomina->user_name), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(37, 5, utf8_decode("Neto a pagar:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, '$ '. number_format($programacionNomina->total_pagar, 0), 0, 0, 'R', 1);
        
        //Lineas del encabezado
        $this->Line(10,78,10,140);
        $this->Line(23,78,23,140);
        $this->Line(91,78,91,140);
        $this->Line(100,78,100,140);
        $this->Line(116,78,116,140);
        $this->Line(132,78,132,140);
        $this->Line(145,78,145,140);
        $this->Line(160,78,160,140);
        $this->Line(180,78,180,140);
        $this->Line(200,78,200,140);        
        $this->Line(10,140,200,140);//linea horizontal inferior        
        $this->EncabezadoDetalles();                
    }

    function EncabezadoDetalles() {
        $this->Ln(7);
        $header = array(utf8_decode('CÓDIGO'), 'CONCEPTO', '%', utf8_decode('N° HORAS'), 'VLR HORA', utf8_decode('N° DIAS'), 'VLR DIA', utf8_decode('DEDUCCIÓN'), 'DEVENGADO');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(13, 68, 9, 16, 16, 13, 15, 20, 20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    function Body($pdf,$model) {        
        $detalles = ProgramacionNominaDetalle::find()->where(['=','id_programacion',$model->id_programacion])->orderBy('vlr_deduccion ASC')->all();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        
        foreach ($detalles as $detalle) {
            $codigo_salario = $detalle->codigo_salario;
            $concepto = ConceptoSalarios::find()->where(['=','codigo_salario', $codigo_salario])->one();
            if($concepto->auxilio_transporte == 1){
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(13, 4, $codigo_salario, 0, 0, 'L');
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(68, 4, $concepto->nombre_concepto, 0, 0, 'L');
                $pdf->Cell(9, 4, $detalle->porcentaje, 0, 0, 'R');
                $pdf->Cell(16, 4, $detalle->horas_periodo_reales, 0, 0, 'R');
                $pdf->Cell(16, 4, number_format($detalle->vlr_hora, 2), 0, 0, 'R');
                $pdf->Cell(13, 4, $detalle->dias_transporte, 0, 0, 'R');
                $pdf->Cell(15, 4, number_format($detalle->vlr_dia, 2), 0, 0, 'R');
                $pdf->Cell(20, 4, number_format($detalle->vlr_deduccion, 2), 0, 0, 'R');
                $pdf->Cell(20, 4, number_format($detalle->auxilio_transporte, 2), 0, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 20);                
            }else{
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(13, 4, $codigo_salario, 0, 0, 'L');
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(68, 4, $concepto->nombre_concepto, 0, 0, 'L');
                $pdf->Cell(9, 4, $detalle->porcentaje, 0, 0, 'R');
                $pdf->Cell(16, 4, $detalle->horas_periodo_reales, 0, 0, 'R');
                $pdf->Cell(16, 4, number_format($detalle->vlr_hora, 2), 0, 0, 'R');
                $pdf->Cell(13, 4, $detalle->dias_reales, 0, 0, 'R');
                $pdf->Cell(15, 4, number_format($detalle->vlr_dia, 2), 0, 0, 'R');
                $pdf->Cell(20, 4, number_format($detalle->vlr_deduccion, 2), 0, 0, 'R');
                $pdf->Cell(20, 4, number_format($detalle->vlr_devengado, 2), 0, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 20);                               
            }    
        }        
    }

    function Footer() {

        $this->SetFont('Arial', '', 8);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
global $id_programacion;
$id_programacion = $model->id_programacion;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Colilla$model->id_programacion.pdf", 'D');

exit;