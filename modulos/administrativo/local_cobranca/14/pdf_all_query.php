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
			$pdf->Tracejado(0);
			$posY = null;
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			break;
		case 1:
			$pdf->Tracejado(98.8);
			$posY = 98.9;	
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			break;
		case 2:
			$pdf->Tracejado(197.6);
			$posY = 197.7;	
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			$pdf->Tracejado(296.4);
			break;
	}	
	$idCarne++;
	
	if($idCarne>2){
		$idCarne=0;
	} 
?>
