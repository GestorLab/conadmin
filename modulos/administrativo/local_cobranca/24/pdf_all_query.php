<?
	$sqlCarne = "select 
					IdContaReceber
				from 
					ContaReceber
				where 
					IdLoja = $IdLoja and 
					IdCarne = $lin[IdCarne]
				order by
					IdContaReceber
				limit 0,1";
	$resCarne = mysql_query($sqlCarne,$con);
	$linCarne = mysql_fetch_array($resCarne);

	switch($idCarne){
		case 0:
			$pdf->AddPage();
			$pdf->Tracejado(12.2);
			$posY = 11;
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			break;
		case 1:
			$pdf->Tracejado(95.0);
			$posY = 93.8;	
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			break;
		case 2:
			$pdf->Tracejado(178.1);
			$posY = 177.0;	
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			$pdf->Tracejado(261.5);
			break;
	}	
	$idCarne++;
	
	if($idCarne>2){
		$idCarne=0;
	} 
?>
