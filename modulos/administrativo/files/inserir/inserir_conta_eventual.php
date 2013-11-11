<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		
		// Sql de Inserção de ContaEventual
		$sql3	=	"select (max(IdContaEventual)+1) IdContaEventual from ContaEventual where IdLoja = $local_IdLoja";
		$res3	=	mysql_query($sql3,$con);
		$lin3	=	@mysql_fetch_array($res3);
		$select	= 	"";	
		
		if($lin3[IdContaEventual]!=NULL){ 
			$local_IdContaEventual	=	$lin3[IdContaEventual];
		}else{
			$local_IdContaEventual	=	1;
		}
		
		if($local_FormaCobranca==2){
			$local_ValorTotal			=	str_replace(".", "", $local_ValorTotalIndividual);	
			$local_ValorTotal			= 	str_replace(",", ".", $local_ValorTotal);
			$local_QtdParcela			=   $local_QtdParcelaIndividual;
			$local_IdContratoAgrupador	=	$local_IdContrato;
		}else{
			$local_ValorTotal			=	str_replace(".", "", $local_ValorTotalContrato);	
			$local_ValorTotal			= 	str_replace(",", ".", $local_ValorTotal);
			$local_QtdParcela			=   $local_QtdParcelaContrato;
		}
		
		$local_ValorDespesaLocalCobranca	=	str_replace(".", "", $local_ValorDespesaLocalCobranca);	
		$local_ValorDespesaLocalCobranca	= 	str_replace(",", ".", $local_ValorDespesaLocalCobranca);
				
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;	
		
		if($local_SeletorContaCartao != ""){
			$select =  "$local_SeletorContaCartao = '$local_IdContaDebitoCartao',"; 
		}
		
		if($local_IdLocalCobranca==0 || $local_IdLocalCobranca=='')						$local_IdLocalCobranca	 		= 'NULL';	
		if($local_IdContratoAgrupador==0 || $local_IdContratoAgrupador=='')				$local_IdContratoAgrupador		= 'NULL';	
		if($local_IdContrato==0 || $local_IdContrato=='')								$local_IdContrato				= 'NULL';
		if($local_IdFormatoCarne==0 || $local_IdFormatoCarne=='')						$local_IdFormatoCarne			= 'NULL';	
		if($local_IdPessoaEnderecoCobranca==0 || $local_IdPessoaEnderecoCobranca=='')	$local_IdPessoaEnderecoCobranca	= 'NULL';
		if($local_ValorDespesaLocalCobranca == '')										$local_ValorDespesaLocalCobranca = '0.00';		
		
		$sql	=	"
				INSERT INTO ContaEventual SET 
					IdLoja						= $local_IdLoja,
					IdContaEventual				= $local_IdContaEventual,
					IdPessoa					= $local_IdPessoa,
					DescricaoContaEventual		= '$local_DescricaoContaEventual',
					ValorTotal					= '$local_ValorTotal',
					ValorDespesaLocalCobranca	= '$local_ValorDespesaLocalCobranca',
					IdLocalCobranca				= $local_IdLocalCobranca,
					IdPessoaEnderecoCobranca	= $local_IdPessoaEnderecoCobranca,
					IdStatus					= '1',
					Carne						= $local_IdFormatoCarne,
					IdContrato					= $local_IdContratoAgrupador,
					ObsContaEventual			= '$local_ObsContaEventual',
					QtdParcela					= '$local_QtdParcela',
					FormaCobranca				= $local_FormaCobranca,
					OcultarReferencia			= $local_OcultarReferencia,
					$select
					DataCriacao					= (concat(curdate(),' ',curtime())),
					LoginCriacao				= '$local_Login';";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		if($local_Valor[1] != ''){
			for($i=1;$i<=$local_QtdParcela;$i++){
				if($local_FormaCobranca == 2){
					$MesReferencia		= 'NULL';
					$local_Data[$i]		= dataConv($local_Data[$i],'d/m/Y','Y-m-d');
					$Vencimento			= "'".$local_Data[$i]."'";
					
					$local_Desp[$i]		=	str_replace(".", "", $local_Desp[$i]);	
					$local_Desp[$i]		= 	str_replace(",", ".", $local_Desp[$i]);
					
					$local_ValorDespesa = "'".$local_Desp[$i]."'";
				}else{
					$MesReferencia		= "'".$local_Data[$i]."'";
					$Vencimento			= 'NULL';
					$local_ValorDespesa = '0.00';
				}
			
				$sql3	=	"select (max(IdContaEventualParcela)+1) IdContaEventualParcela from ContaEventualParcela where IdLoja = $local_IdLoja and IdContaEventual = $local_IdContaEventual";
				$res3	=	mysql_query($sql3,$con);
				$lin3	=	mysql_fetch_array($res3);
				
				if($lin3[IdContaEventualParcela]!=NULL){ 
					$local_IdContaEventualParcela	=	$lin3[IdContaEventualParcela];
				}else{
					$local_IdContaEventualParcela	=	1;
				}
				
				$local_Valor[$i]	=	str_replace(".", "", $local_Valor[$i]);	
				$local_Valor[$i]	= 	str_replace(",", ".", $local_Valor[$i]);
				
				$sql	=	"
					INSERT INTO ContaEventualParcela SET 
						IdLoja 						= $local_IdLoja,
						IdContaEventual				= $local_IdContaEventual,
						IdContaEventualParcela		= $local_IdContaEventualParcela,
						Valor						= '".$local_Valor[$i]."',
						ValorDespesaLocalCobranca	= $local_ValorDespesa,
						Vencimento					= $Vencimento,
						MesReferencia				= $MesReferencia;";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
		}

		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			// Muda a ação para Inserir
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
			mysql_query($sql,$con);
		}else{
			$sql = "ROLLBACK;";
			
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
			mysql_query($sql,$con);
		}
	}
?>
