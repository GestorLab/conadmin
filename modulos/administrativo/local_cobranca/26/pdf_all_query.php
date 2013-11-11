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
			$pdf->Tracejado(18.2);
			$posY = 21;
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			break;
		case 1:
			$pdf->Tracejado(98.4);
			$posY = 101;	
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			break;
		case 2:
			$pdf->Tracejado(178.4);
			$posY = 181.2;	
			$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
			$pdf->Tracejado(258.9);
			break;
	}	
	$idCarne++;
	
	if($idCarne>2){
		$idCarne=0;
	} 
?>
