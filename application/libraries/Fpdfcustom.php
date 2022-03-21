<?php
require APPPATH . 'third_party/fpdf/fpdf.php';

class Fpdfcustom extends FPDF
{

function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'This report is generated on'.time().' from '.$_SERVER['REMOTE_ADDR'].'  Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
