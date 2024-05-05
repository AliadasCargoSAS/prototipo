<?php

$ficha = trim($_GET["ficha"]);
$trimestre = trim($_GET["trimestre"]);

if (empty($ficha) || empty($trimestre)) {   
    echo "<script>window.location= '/'</script>";    
}

require('fpdf/fpdf.php');
require('fpdf/htmlparser.php');

class PDF_HTML_Table extends FPDF
{
    protected $B;
    protected $I;
    protected $U;
    protected $HREF;

    function __construct($orientation = 'P', $unit = 'mm', $format = 'A4')
    {
        //Call parent constructor
        parent::__construct($orientation, $unit, $format);
        //Initialization
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';
    }

    function WriteHTML2($html)
    {
        //HTML parser
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                //Text
                if ($this->HREF)
                    $this->PutLink($this->HREF, $e);
                else
                    $this->Write(5, $e);
            } else {
                //Tag
                if ($e[0] == '/')
                    $this->CloseTag(strtoupper(substr($e, 1)));
                else {
                    //Extract attributes
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag, $attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        //Opening tag
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, true);
        if ($tag == 'A')
            $this->HREF = $attr['HREF'];
        if ($tag == 'BR')
            $this->Ln(5);
        if ($tag == 'P')
            $this->Ln(10);
    }

    function CloseTag($tag)
    {
        //Closing tag
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, false);
        if ($tag == 'A')
            $this->HREF = '';
        if ($tag == 'P')
            $this->Ln(10);
    }

    function SetStyle($tag, $enable)
    {
        //Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (array('B', 'I', 'U') as $s)
            if ($this->$s > 0)
                $style .= $s;
        $this->SetFont('', $style);
    }

    function PutLink($URL, $txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0, 0, 255);
        $this->SetStyle('U', true);
        $this->Write(5, $txt, $URL);
        $this->SetStyle('U', false);
        $this->SetTextColor(0);
    }

    function WriteTable($data, $w)
    {
        $this->SetLineWidth(.3);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        foreach ($data as $row) {
            $nb = 0;
            for ($i = 0; $i < count($row); $i++)
                $nb = max($nb, $this->NbLines($w[$i], trim($row[$i])));
            $h = 5 * $nb;
            $this->CheckPageBreak($h);
            for ($i = 0; $i < count($row); $i++) {
                $x = $this->GetX();
                $y = $this->GetY();
                $this->Rect($x, $y, $w[$i], $h);
                $this->MultiCell($w[$i], 5, trim($row[$i]), 0, 'C');
                //Put the position to the right of the cell
                $this->SetXY($x + $w[$i], $y);
            }
            $this->Ln($h);
        }
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function ReplaceHTML($html)
    {
        $html = str_replace('<li>', "\n<br> - ", $html);
        $html = str_replace('<LI>', "\n - ", $html);
        $html = str_replace('</ul>', "\n\n", $html);
        $html = str_replace('<strong>', "<b>", $html);
        $html = str_replace('</strong>', "</b>", $html);
        $html = str_replace('&#160;', "\n", $html);
        $html = str_replace('&nbsp;', " ", $html);
        $html = str_replace('&quot;', "\"", $html);
        $html = str_replace('&#39;', "'", $html);
        return $html;
    }

    function ParseTable($Table)
    {
        $_var = '';
        $htmlText = $Table;
        $parser = new HtmlParser($htmlText);
        while ($parser->parse()) {
            if (strtolower($parser->iNodeName) == 'table') {
                if ($parser->iNodeType == NODE_TYPE_ENDELEMENT)
                    $_var .= '/::';
                else
                    $_var .= '::';
            }

            if (strtolower($parser->iNodeName) == 'tr') {
                if ($parser->iNodeType == NODE_TYPE_ENDELEMENT)
                    $_var .= '!-:'; //opening row
                else
                    $_var .= ':-!'; //closing row
            }
            if (strtolower($parser->iNodeName) == 'td' && $parser->iNodeType == NODE_TYPE_ENDELEMENT) {
                $_var .= '#,#';
            }
            if ($parser->iNodeName == 'Text' && isset($parser->iNodeValue)) {
                $_var .= $parser->iNodeValue;
            }
        }
        $elems = explode(':-!', str_replace('/', '', str_replace('::', '', str_replace('!-:', '', $_var)))); //opening row
        foreach ($elems as $key => $value) {
            if (trim($value) != '') {
                $elems2 = explode('#,#', $value);
                array_pop($elems2);
                $data[] = $elems2;
            }
        }
        return $data;
    }

    function WriteHTML($html)
    {
        $html = $this->ReplaceHTML($html);
        //Search for a table
        $start = strpos(strtolower($html), '<table');
        $end = strpos(strtolower($html), '</table');
        if ($start !== false && $end !== false) {
            $this->WriteHTML2(substr($html, 0, $start) . '<BR>');

            $tableVar = substr($html, $start, $end - $start);
            $tableData = $this->ParseTable($tableVar);
            for ($i = 1; $i <= count($tableData[0]); $i++) {
                if ($this->CurOrientation == 'L')
                    $w[] = abs(120 / (count($tableData[0]) - 1)) + 24;
                else
                    $w[] = abs(120 / (count($tableData[0]) - 1)) + 5;
            }
            $this->WriteTable($tableData, $w);

            $this->WriteHTML2(substr($html, $end + 8, strlen($html) - 1) . '<BR>');
        } else {
            $this->WriteHTML2($html);
        }
    }
}


require '../conexion.php';

$query = "SELECT IdPrincipal, Fase, Competencia, Rap, NumeroAmbiente, Dia, substring(HoraInicio, 1, 5), substring(HoraFin, 1, 5), hour(HoraInicio), hour(HoraFin), Instructor FROM eventoficha WHERE CabeceraFicha = '$ficha' and trimestreFicha = '$trimestre'";
$resultado = $link->query($query);

$query2 = "SELECT Fechainicio FROM eventoficha WHERE CabeceraFicha = '$ficha' and trimestreFicha = '$trimestre' limit 1";
$resultado2 = $link->query($query2);

$query3 = "SELECT Fechafin FROM eventoficha WHERE CabeceraFicha = '$ficha' and trimestreFicha = '$trimestre' limit 1";
$resultado3 = $link->query($query3);

$query4 = "SELECT substring(Programa, 1, 30) from fichas where Numero='$ficha'";
$resultado4 = $link->query($query4);

$query5 = "SELECT Jornada from fichas where Numero='$ficha'";
$resultado5 = $link->query($query5);

$query6 = "SELECT substring(Proyecto, 1, 55) from programas where Nombre = (Select Programa from fichas where Numero = '$ficha')";
$resultado6 = $link->query($query6);

$sql2 = "SELECT 
CASE
when month(curdate()) = 1 then 'I'
when month(curdate()) between 11 and 12 then 'I'
when month(curdate()) between 2 and 4 then 'II'
when month(curdate()) between 5 and 7 then 'III'
when month(curdate()) between 8 and 10 then 'IV'
END 'Trimestre año'";



$htmlTablebody = '';

$htmlTablehead = '<table>
<tr>
<td>FASE DEL PROYECTO</td>
<td>COMPETENCIA</td>
<td>RESULTADO DE APRENDIZAJE</td>
<td>AMBIENTE</td>
<td>DIAÑ</td>
<td>HORARIO</td>
<td>INSTRUCTOR</td>
</tr>';


while ($row = mysqli_fetch_array($resultado)) {
   $htmlTablebody .= '<tr>
<td>' . $row['Fase'] . '</td>
<td>' . $row["Competencia"] . '</td>
<td>' . $row["Rap"] . '</td>
<td>' . $row["NumeroAmbiente"] . '</td>
<td>' . $row["Dia"] . '</td>
<td>' . $row['substring(HoraInicio, 1, 5)'] . ' - ' . $row['substring(HoraFin, 1, 5)'] . '</td>
<td>' . $row["Instructor"] . '</td>
</tr>';
}

$htmlTablefooter = '</table>';

$pdf = new PDF_HTML_Table('L', 'mm', array(200, 327));
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);
$pdf->SetFillColor(237, 255, 237);
//$pdf->SetTextColor(116,116,116);
//$pdf->SetFillColor(255, 255, 255);
    $pdf->Ln(5);
    $pdf->Cell(40, 40, '', 1, 0, 'C', 0);
    $pdf->Image('https://imgur.com/6R5EFuH.png',22,21,16);
    $pdf->Cell(268, 7, '   PROGRAMACION EVENTOS FORMATIVOS', 1, 1, 'L', 1);
    $pdf->Cell(40);
    $pdf->Cell(268, 7, '   FOO1 - POO2 - 08-11-9216', 1, 1, 'L', 1);
    $pdf->Cell(40);
    $pdf->Cell(268, 7, '   PROCESO: EJECUCION DE LA FORMACION PROFESIONAL', 1, 1, 'L', 1);
    $pdf->Cell(40);
    $pdf->Cell(268, 7, '   PROCEDIMIENTO: DESARROLLO CURRICULAR', 1, 1, 'L', 1);
// $pdf->Ln(1);
$pdf->Cell(63, 7, "   NUMERO DE LA FICHA:  $ficha", 1, 0, 'L', 1);
if ($result = mysqli_query($link, $sql2)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $tmtreaño = $row['Trimestre año'];
            $pdf->Cell(50, 7, "   TRIMESTRE DEL AÑO:  $tmtreaño", 1, 0, 'L', 1);
        }
    }
}
    $año = date('Y');
    $pdf->Cell(35, 7, "   AÑO:  $año", 1, 0, 'L', 1);
$sqlnumapr = "SELECT Aprendices from fichas where Numero='$ficha' and status = 1";
if ($result = mysqli_query($link, $sqlnumapr)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $ctaprendices = $row['Aprendices'];
            $pdf->Cell(60, 7, "   NUMERO DE APRENDICES:  $ctaprendices", 1, 0, 'L', 1);    
        }
    }
};
while ($row = mysqli_fetch_array($resultado2)) {
    $inicio = $row["Fechainicio"];
    $pdf->Cell(50, 7, "   FECHA INICIO:  $inicio", 1, 0, 'L', 1);
}
while ($row = mysqli_fetch_array($resultado3)) {
    $fin = $row["Fechafin"];
    $pdf->Cell(50, 7, "   FECHA FIN:  $fin", 1, 1, 'L', 1);
}
while ($row = mysqli_fetch_array($resultado4)) {
    if(strlen($row["substring(Programa, 1, 30)"]) >= 30){
        $programa = $row["substring(Programa, 1, 30)"] .'...';
    }else{
        $programa = $row["substring(Programa, 1, 30)"];
    }
    $pdf->Cell(100, 7, "  PROGRAMA DE FORMACION:  $programa", 1, 0, 'L', 1);       
}
$pdf->Cell(52, 7, "  TRIMESTRE DE LA FICHA:  $trimestre", 1, 0, 'L', 1);
while ($row = mysqli_fetch_array($resultado5)) {
    $jornada = $row["Jornada"];
    $pdf->Cell(40, 7, "  JORNADA:  $jornada", 1, 0, 'L', 1);    
}
while ($row = mysqli_fetch_array($resultado6)) {
    if(strlen($row["substring(Proyecto, 1, 55)"]) >= 55){
        $proyecto = $row["substring(Proyecto, 1, 55)"] .'...';
    }else{
        $proyecto = $row["substring(Proyecto, 1, 55)"];
    }
    $pdf->Cell(116, 7, "  PROYECTO:  $proyecto", 1, 0, 'L', 1);
}

$pdf->Ln(2);

$pdf->WriteHTML("$htmlTablehead . $htmlTablebody . $htmlTablefooter");
$pdf->Output('D', 'Evento Ficha '.$ficha.'.pdf');

