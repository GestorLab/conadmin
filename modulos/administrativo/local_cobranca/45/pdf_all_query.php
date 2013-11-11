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
			$pdf->Tracejado(88.8);
			$posY = 88.9;	
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			break;
		case 2:
			$pdf->Tracejado(177.6);
			$posY = 177.7;	
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			$pdf->Tracejado(267.0);
			break;
	}	
	$idCarne++;
	
	if($idCarne>2){
		$idCarne=0;
	} 
?>
