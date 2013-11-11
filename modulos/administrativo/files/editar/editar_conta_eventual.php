<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	} else{
		if($local_FormaCobranca==2){
			$local_ValorTotal			=	str_replace(".", "", $local_ValorTotalIndividual);	
			$local_ValorTotal			= 	str_replace(",", ".", $local_ValorTotal);
			$local_QtdParcela			=   $local_QtdParcelaIndividual;
			$local_IdContratoAgrupador	=	$local_IdContrato;
		} else{
			$local_ValorTotal			=	str_replace(".", "", $local_ValorTotalContrato);	
			$local_ValorTotal			= 	str_replace(",", ".", $local_ValorTotal);
			$local_QtdParcela			=   $local_QtdParcelaContrato;
		}
		
		$local_ValorDespesaLocalCobranca	=	str_replace(".", "", $local_ValorDespesaLocalCobranca);	
		$local_ValorDespesaLocalCobranca	= 	str_replace(",", ".", $local_ValorDespesaLocalCobranca);
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		if($local_IdLocalCobranca==0 || $local_IdLocalCobranca=='')						$local_IdLocalCobranca	 		= 'NULL';
		if($local_IdContratoAgrupador==0 || $local_IdContratoAgrupador=='')				$local_IdContratoAgrupador		= 'NULL';
		if($local_OcultarReferencia=='' )												$local_OcultarReferencia		= 'NULL';
		if($local_IdContrato==0 || $local_IdContrato=='')								$local_IdContrato				= 'NULL';
		if($local_IdFormatoCarne==0 || $local_IdFormatoCarne=='')						$local_IdFormatoCarne			= 'NULL';
		if($local_IdPessoaEnderecoCobranca==0 || $local_IdPessoaEnderecoCobranca=='')	$local_IdPessoaEnderecoCobranca	= 'NULL';
		
		$tr_i = 0;
		
		$select = "";
		
		if($local_SeletorContaCartao != ""){
			$sql = "SELECT
						IdContaDebito,
						IdCartao
					FROM
						ContaEventual
					WHERE
						IdContaEventual = $local_IdContaEventual AND
						IdLoja = $local_IdLoja";
			$res = mysql_query($sql,$con);
			$dados = mysql_fetch_array($res);
			if($local_SeletorContaCartao == "IdContaDebito"){
				if($dados[IdCartao] != ""){
					$sqlDelete = "UPDATE ContaEventual SET 
										IdCartao = NULL
								  WHERE
										IdContaEventual = $local_IdContaEventual AND
										IdLoja = $local_IdLoja";
										
					$local_transaction[$tr_i]	=	mysql_query($sqlDelete,$con);
					$tr_i++;
					
					$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração a forma de pagamento - Cartão de Crédito > Débito em Conta.";
				}
			}
			if($local_SeletorContaCartao == "IdCartao"){
				if($dados[IdContaDebito] != ""){
					$sqlDelete = "UPDATE ContaEventual SET 
										IdContaDebito = NULL
								  WHERE
										IdContaEventual = $local_IdContaEventual AND
										IdLoja = $local_IdLoja";
										
					$local_transaction[$tr_i]	=	mysql_query($sqlDelete,$con);
					$tr_i++;
					
					$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração a forma de pagamento - Débito em Conta > Cartão de Crédito.";
				}
			}
			
			if($local_SeletorContaCartao != "" && $local_IdContaDebitoCartao != ""){
				$select =  "$local_SeletorContaCartao = '$local_IdContaDebitoCartao',"; 
			}
		}else{
			$sqlDelete = "UPDATE ContaEventual SET 
										IdContaDebito = NULL,
										IdCartao = NULL
								  WHERE
										IdContaEventual = $local_IdContaEventual AND
										IdLoja = $local_IdLoja";
			$local_transaction[$tr_i]	=	mysql_query($sqlDelete,$con);
			$tr_i++;
			
		}
		
		if($local_IdStatus == 2){
			$sql	=	"UPDATE ContaEventual SET 
							DescricaoContaEventual		= '$local_DescricaoContaEventual',
							LoginAlteracao				= '$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					= $local_IdLoja and
							IdContaEventual			= '$local_IdContaEventual'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
		} else{
			$sql	=	"UPDATE ContaEventual SET 
							IdPessoa					= $local_IdPessoa,
							DescricaoContaEventual		= '$local_DescricaoContaEventual',
							QtdParcela					= '$local_QtdParcela',
							ValorTotal					= '$local_ValorTotal',
							ValorDespesaLocalCobranca	= '$local_ValorDespesaLocalCobranca',
							IdLocalCobranca				= $local_IdLocalCobranca,
							IdStatus					= $local_IdStatus,
							Carne						= $local_IdFormatoCarne,
							FormaCobranca				= $local_FormaCobranca,
							IdContrato					= $local_IdContratoAgrupador,
							$select
							IdPessoaEnderecoCobranca	= $local_IdPessoaEnderecoCobranca,
							ObsContaEventual			= '$local_ObsContaEventual',
							OcultarReferencia			= $local_OcultarReferencia,
							LoginAlteracao				='$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					= $local_IdLoja and
							IdContaEventual			= '$local_IdContaEventual'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			$sql	=	"DELETE from ContaEventualParcela where IdLoja = $local_IdLoja and IdContaEventual = $local_IdContaEventual";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			if($local_Valor[1] != ''){
				for($i=1;$i<=$local_QtdParcela;$i++){
					if($local_FormaCobranca == 2){
						$MesReferencia		= 'NULL';
						$local_Data[$i]		= dataConv($local_Data[$i],'d/m/Y','Y-m-d');
						$Vencimento			= "'".$local_Data[$i]."'";
						
						if($local_Desp[$i]	== "") $local_Desp[$i]	= 0;
						
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
					$lin3	=	@mysql_fetch_array($res3);
					
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
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 4;	
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;
		}
		
		mysql_query($sql,$con);
	}
?>