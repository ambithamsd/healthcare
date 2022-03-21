<?php
require APPPATH . 'third_party/fpdf/fpdf.php';

class Fpdfcustom extends FPDF
{
	public $imagePath;

	function Header()
	{
		// Logo
		$this->Image($this->imagePath,10,6,30);
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(30,10,'Report',1,0,'C');
		// Line break
		$this->Ln(20);
	}


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
