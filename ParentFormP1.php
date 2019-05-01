<?php
	$parentformfilename = "../ParentForm/".$Account_Code."-ParentformP1.pdf";

	require_once('/usr/share/php/fpdf/fpdf.php');
	require_once('/usr/share/php/fpdf/fpdi.php');

	$pdf = new FPDI();
	$pdf->AddPage();
	$pdf->setSourceFile('../ParentForm/Parents-Release.pdf');
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx, 0, 0, 170);
	$pdf->SetFont('arial');
	$pdf->SetFontSize('8');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(30,9,'',0,1);
	$pdf->Cell(23,5,'',0,0);	
	$pdf->Cell(48,5,$COName,0,0);
	$pdf->Cell(47,5,'',0,0);	
	$pdf->Cell(48,5,$Account_Code,0,0);
	$pdf->Cell(30,11,'',0,1);
	$pdf->Cell(1,2,'',0,0);	
	$pdf->Cell(48,5,$COName,0,0);
	$pdf->Output($parentformfilename,'F');
#	$mergefiles[] = $pdfI9filename;

#	$pdf->Output();

?>
