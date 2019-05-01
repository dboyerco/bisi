<?php

	$parentformfilename = "../ParentForm/".$Account_Code."-ParentformP2.pdf";

	require_once('/usr/share/php/fpdf/fpdf.php');
	require_once('/usr/share/php/fpdf/fpdi.php');

	$pdf = new FPDI();
	$pdf->AddPage();
	$pdf->setSourceFile('../ParentForm/Parents-Release.pdf');
	$tplIdx = $pdf->importPage(2);
	$pdf->useTemplate($tplIdx, 0, 0, 170);
	$pdf->SetFont('arial');
	$pdf->SetFontSize('8');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(30,23,'',0,1);
	$pdf->Cell(73,10,'',0,0);
	$pdf->Cell(58,5,$COName,0,0);

	$pdf->Output($parentformfilename,'F');
	$mergefiles[] = $parentformfilename;
#	$pdf->Output();

?>
