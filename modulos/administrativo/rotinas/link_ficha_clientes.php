<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');	
	
	$local_IdLoja			= $_SESSION["IdLoja"];
	$PeriodoInicial			= $_GET['PeriodoInicial'];
	$PeriodoFinal			= $_GET['PeriodoFinal'];
	$TorrePainel			= $_GET['TorrePainel'];
	$Quantidade				= $_GET['Quantidade'];
	$IdStatusContrato		= $_GET['IdStatusContrato'];
	
	$i = 0;
	$where = "";
	$SqlAux = "";

	if($PeriodoInicial!=""){
		$PeriodoInicial = dataConv($PeriodoInicial,'d/m/Y','Y-m-d');
		$where .= " and substring(Contrato.DataInicio, 1, 10) >= '$PeriodoInicial'";
	}
	
	if($PeriodoFinal!=""){
		$PeriodoFinal = dataConv($PeriodoFinal,'d/m/Y','Y-m-d');
		$where .= " and substring(Contrato.DataInicio, 1, 10) <= '$PeriodoFinal'";
	}

	if($TorrePainel != ''){	
		$SqlAux = ", ContratoParametro";
 		$where .= " and ContratoParametro.IdLoja = $local_IdLoja 
					and Contrato.IdContrato = ContratoParametro.IdContrato 
					and ContratoParametro.IdParametroServico = 6 
		 			and ContratoParametro.Valor like '%$TorrePainel%'";
 	}
	
	if($IdStatusContrato!=''){		
		
		$aux	=	explode("G_",$IdStatusContrato);
		
		if($aux[1]!=""){
			switch($aux[1]){
				case '1':
					$where .= " and (Contrato.IdStatus >= 1 and Contrato.IdStatus < 100)";
					break;
				case '2':
					$where .= " and (Contrato.IdStatus >= 200 and Contrato.IdStatus < 300)";
					break;
				case '3':
					$where .= " and (Contrato.IdStatus >= 300 and Contrato.IdStatus < 400)";
					break;
			}
		}else{
			$where .= " and Contrato.IdStatus = '$IdStatusContrato'";
		}
	}	

	$sql =" select
				Contrato.IdContrato
			from				
				Contrato
				$SqlAux
			where
				Contrato.IdLoja = $local_IdLoja 
				$where";
	$res	=	@mysql_query($sql,$con);
 	$Qtd 	= 	@mysql_num_rows($res);	
 		 
 	if($Quantidade != '' && $Quantidade < $Qtd){ 			
		$Qtd = $Quantidade;
	}
	$QtdRegistros = 1000; // Define a quantidade de Registros por arquivo
	
	if($Qtd < $QtdRegistros){
		$QtdRegistros = $Qtd;
	}
	
	if($Qtd > 0){
		$QtdArquivos = (int)($Qtd/$QtdRegistros);
		if($Qtd%$QtdRegistros > 0){
			$QtdArquivos++;
		}	
	}else{
		echo "Não foi encontrado registros.";
	}
	
	$LimiteIni 	= 0;
	$LimiteFin 	= $QtdRegistros;
	$QtdAux 	= $Qtd;
	
	for($i=1; $i<=$QtdArquivos; $i++){
		if($i > 1){
			$LimiteIni = $LimiteFin;
			$AuxLimiteFin = $LimiteFin;
			$LimiteFin += $QtdRegistros;
			if($LimiteFin > $Qtd){
				$LimiteFin = $Qtd;
			} 		
		}	
		echo "<br><a href='exportar_ficha_clientes.php?PeriodoInicial=$PeriodoInicial&PeriodoFinal=$PeriodoFinal&LimiteIni=$LimiteIni&LimiteFin=$LimiteFin&TorrePainel=$TorrePainel&IdStatusContrato=$IdStatusContrato&Quantidade=$Quantidade' target='_blank'>ficha_clientes_$LimiteIni-$LimiteFin.xsl</a><br />";	
	}
?>
