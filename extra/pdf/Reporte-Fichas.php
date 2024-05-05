<?php
require('fpdf/fpdf.php');
class PDF extends FPDF
{
function Header()
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
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
}
}


require '../conexion.php';
$query = "SELECT * FROM fichas WHERE Status = 1";
$resultado = $link->query($query);

$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage('LANDSCAPE', 'A4');
$pdf->SetFont('Arial', 'B', 13);
$pdf->SetTextColor(16,87,97);
$pdf->Cell(0, 5, 'REPORTE DE FICHAS',0 ,0 , 'C');
$pdf->SetDrawColor(61, 174, 233);
$pdf->SetLineWidth(1);
$pdf->line(111, 32, 185, 32);


$pdf->Ln(30); 
$pdf->SetTextColor(40, 40, 40);
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFontSize(10);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(20,10,'NUMERO', 0, 0,'C',1);
$pdf->Cell(120,10,'PROGRAMA', 0, 0,'C',1);
$pdf->Cell(45,10,'APRENDICES', 0, 0,'C',1);
$pdf->Cell(30,10,'JORNADA', 0, 0,'C',1);
$pdf->Cell(30,10,'FECHA INICIO', 0, 0,'C',1);
$pdf->Cell(30,10,'FECHA FIN', 0, 0,'C',1);
$pdf->SetDrawColor(61, 174, 233);
$pdf->SetLineWidth(1);
$pdf->line(10, 65, 287, 65);

$pdf->Ln(11);

while($row = $resultado->fetch_assoc()){
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetFillColor(240,240,240);
    $pdf->SetFontSize(8);
    $pdf->SetDrawColor(255,255,255);   
    $pdf->Cell(20,10, $row['Numero'],1 , 0, 'C', 1);
    $pdf->Cell(120,10, utf8_decode($row['Programa']),1 , 0, 'L', 1);
    $pdf->Cell(45,10, $row['Aprendices'],1 , 0, 'C', 1);
    $pdf->Cell(30,10, utf8_decode($row['Jornada']),1 , 0, 'C', 1);
    $pdf->Cell(30,10, $row['Inicio'],1 , 0, 'C', 1);
    $pdf->Cell(30,10, $row['Fin'],1 , 1, 'C', 1);
}

$pdf->Output('D','Reporte de fichas.pdf');
?>