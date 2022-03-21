<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 if ( ! function_exists('downloadPDFDocument'))
 {
	function downloadPdfdocument(){
		include APPPATH . 'third_party/fpdf/fpdf.php';
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(40,10,'Hello World!');
		$pdf->Output("D","abc.pdf");
	}
 }




 
?>
