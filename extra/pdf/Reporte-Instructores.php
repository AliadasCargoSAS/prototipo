<?php
require('../pdf/fpdf/fpdf.php');
class PDF extends FPDF
{
public function Header()
{   
    $this->SetFont('Arial','B',11);
    $this->Cell(0,5,utf8_decode("Centro de Diseño y Metrologia"),0,0,'C');
    $this->Image('../../images/logito.png',270, 0, 25, 25, 'png');
    $this->Ln(15);
}


function Footer()
{
    $this->SetY(-15);
    $this->SetFont('Arial','',8);
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
}
}

require '../conexion.php';
$query = "SELECT * FROM instructores WHERE Status = 1";
$resultado = $link->query($query);

$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage('LANDSCAPE', 'A4');
$pdf->SetFont('Arial', 'B', 13);
$pdf->SetTextColor(16,87,97);
$pdf->Cell(0, 5, 'REPORTE DE INSTRUCTORES',0 ,0 , 'C');
$pdf->SetDrawColor(61, 174, 233);
$pdf->SetLineWidth(1);
$pdf->line(111, 32, 185, 32);

$pdf->Ln(30); 
$pdf->SetTextColor(40, 40, 40);
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFontSize(8);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(20,10,'N.I',0,0,'C',1);
$pdf->Cell(60,10,'NOMBRE',0,0,'C',1);
$pdf->Cell(25,10,'F. NACIMIENTO',0,0,'C',1);
$pdf->Cell(20,10,'TELEFONO',0,0,'C',1);
$pdf->Cell(30,10,'ESPECIALIDAD',0,0,'C',1);
$pdf->Cell(50,10,'CORREO',0,0,'C',1);
$pdf->Cell(30,10,'T. CONTRATO',0,0,'C',1);
$pdf->Cell(20,10,'I. CONTRATO',0,0,'C',1);
$pdf->Cell(20,10,'F. CONTRATO',0,0,'C',1);  
$pdf->SetDrawColor(61, 174, 233);
$pdf->SetLineWidth(1);
$pdf->line(10, 65, 287, 65);

$pdf->Ln(11);

 while($row = $resultado->fetch_assoc()){
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetFillColor(240,240,240);
    $pdf->SetFontSize(8);
    $pdf->SetDrawColor(255,255,255);
    $pdf->Cell(20,10, $row['Identificacion'],1 , 0, 'L', 1);    
    $pdf->Cell(60,10, $row['Nombre'],1 , 0, 'L', 1);
    $pdf->Cell(25,10, $row['Nacimiento'],1 , 0, 'C', 1);
    $pdf->Cell(20,10, $row['Telefono'],1 , 0, 'C', 1);
    $pdf->Cell(30,10, $row['Especialidad'],1 , 0, 'L', 1);
    $pdf->Cell(50,10, $row['Correo'],1 , 0, 'L', 1);
    $pdf->Cell(30,10, $row['TipoContrato'],1 , 0, 'C', 1);
    $pdf->Cell(20,10, $row['ContratoInicio'],1 , 0, 'C', 1);
    $pdf->Cell(20,10, $row['ContratoFin'],1 , 1, 'C', 1);
}
 
$pdf->Output('D','Reporte de instructores.pdf');


?>