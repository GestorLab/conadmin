<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		if($local_ValorDespesaLocalCobranca != ""){
			$local_ValorDespesaLocalCobranca	=	str_replace(".", "", $local_ValorDespesaLocalCobranca);	
			$local_ValorDespesaLocalCobranca	= 	"'".str_replace(",", ".", $local_ValorDespesaLocalCobranca)."'";
		}else{
			$local_ValorDespesaLocalCobranca	=	'NULL';	
		}
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		if($local_FormaCobrancaTemp == 2){
			$local_IdContratoAgrupador = $local_IdContratoIndividual;
		}
		
		if($local_IdLocalCobrancaTemp==0)												$local_IdLocalCobrancaTemp	 	= 'NULL';
		if($local_FormaCobrancaTemp==0 || $local_FormaCobrancaTemp=='')					$local_FormaCobrancaTemp	 	= 'NULL';
		if($local_IdContratoAgrupador==0 || $local_IdContratoAgrupador=='')				$local_IdContratoAgrupador	 	= 'NULL';
		if($local_QtdParcela==0 || $local_QtdParcela=='')								$local_QtdParcela	 			= 'NULL';
		if($local_IdFormatoCarne==0 || $local_IdFormatoCarne=='')						$local_IdFormatoCarne			= 'NULL';
		if($local_IdPessoaEnderecoCobranca==0 || $local_IdPessoaEnderecoCobranca=='')	$local_IdPessoaEnderecoCobranca	= 'NULL';
		
		$local_ValorTotal	=	str_replace(".", "", $local_ValorTotal);	
		$local_ValorTotal	= 	str_replace(",", ".", $local_ValorTotal);
		
		if($local_IdTerceiro == ''){
			$local_IdTerceiro = 'NULL';
		}
		
		$select = "";
		if($local_SeletorContaCartao != ""){
			$sql = "SELECT
						IdContaDebito,
						IdCartao
					FROM
						OrdemServico
					WHERE
						IdOrdemServico = $local_IdOrdemServico AND
						IdLoja = $local_IdLoja";
			$res = mysql_query($sql,$con);
			$dados = mysql_fetch_array($res);
			if($local_SeletorContaCartao == "IdContaDebito"){
				if($dados[IdCartao] != ""){
					$sqlDelete = "UPDATE OrdemServico SET 
										IdCartao = NULL
								  WHERE
										IdOrdemServico = $local_IdOrdemServico AND
										IdLoja = $local_IdLoja";
										
					$local_transaction[$tr_i]	=	mysql_query($sqlDelete,$con);
					$tr_i++;
					
					$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração a forma de pagamento - Cartão de Crédito > Débito em Conta.";
				}
			}
			if($local_SeletorContaCartao == "IdCartao"){
				if($dados[IdContaDebito] != ""){
					$sqlDelete = "UPDATE OrdemServico SET 
										IdContaDebito = NULL
								  WHERE
										IdOrdemServico = $local_IdOrdemServico AND
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
			$sqlDelete = "UPDATE OrdemServico SET 
										IdContaDebito = NULL,
										IdCartao	  = NULL
								  WHERE
										IdOrdemServico = $local_IdOrdemServico AND
										IdLoja = $local_IdLoja";
										
			$local_transaction[$tr_i]	=	mysql_query($sqlDelete,$con);
			$tr_i++;
		}
		
		$tr_i = 0;		
		
		if($local_ValorTotal > 0){
			$sql	=	"UPDATE OrdemServico SET 
							QtdParcela					= $local_QtdParcela,
							ValorDespesaLocalCobranca	= $local_ValorDespesaLocalCobranca,
							IdLocalCobranca				= $local_IdLocalCobrancaTemp,
							IdContratoFaturamento		= $local_IdContratoAgrupador,
							FormaCobranca				= $local_FormaCobrancaTemp,
							IdTerceiro					= $local_IdTerceiro,
							$select
							Carne						= $local_IdFormatoCarne,
							ValorTotal					='$local_ValorTotal',
							IdPessoaEnderecoCobranca	= $local_IdPessoaEnderecoCobranca,
							LoginAlteracao				='$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					= $local_IdLoja and
							IdOrdemServico			= '$local_IdOrdemServico'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}else{
			$sql	=	"UPDATE OrdemServico SET 
							IdStatus					= 200,
							QtdParcela					= $local_QtdParcela,
							ValorDespesaLocalCobranca	= $local_ValorDespesaLocalCobranca,
							IdLocalCobranca				= $local_IdLocalCobrancaTemp,
							IdContratoFaturamento		= $local_IdContratoAgrupador,
							FormaCobranca				= $local_FormaCobrancaTemp,
							IdTerceiro					= $local_IdTerceiro,
							$select
							Carne						= $local_IdFormatoCarne,
							ValorTotal					='$local_ValorTotal',
							IdPessoaEnderecoCobranca	= $local_IdPessoaEnderecoCobranca,
							LoginAlteracao				='$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					= $local_IdLoja and
							IdOrdemServico			= '$local_IdOrdemServico'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		$sql	=	"DELETE from OrdemServicoParcela where IdLoja = $local_IdLoja and IdOrdemServico = $local_IdOrdemServico";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		if($_POST['parcValor_1'] != ''){
			for($i=1;$i<=$local_QtdParcela;$i++){
				$local_Valor[$i][0] = $_POST['parcValor_'.$i]; 
				
				if($local_FormaCobrancaTemp == 2){
					$local_Valor[$i][1] = $_POST['parcDesp_'.$i]; 
					$local_Valor[$i][2] = $_POST['parcData_'.$i]; 
					$local_Valor[$i][2] = dataConv($local_Valor[$i][2],'d/m/Y','Y-m-d');
					$MesReferencia		= 'NULL';
					$Vencimento			= "'".$local_Valor[$i][2]."'";
					
					$local_Valor[$i][1]	=	str_replace(".", "", $local_Valor[$i][1]);	
					$local_Valor[$i][1]	= 	str_replace(",", ".", $local_Valor[$i][1]);
					
					$local_ValorDespesa = "'".$local_Valor[$i][1]."'";
				}else{
					$local_Valor[$i][1] = $_POST['parcData_'.$i]; 
					$MesReferencia		= "'".$local_Valor[$i][1]."'";
					$Vencimento			= 'NULL';
					$local_ValorDespesa = '0.00';
				}
			
				$sql3	=	"select (max(IdOrdemServicoParcela)+1) IdOrdemServicoParcela from OrdemServicoParcela where IdLoja = $local_IdLoja and IdOrdemServico = $local_IdOrdemServico";
				$res3	=	mysql_query($sql3,$con);
				$lin3	=	@mysql_fetch_array($res3);
				
				if($lin3[IdOrdemServicoParcela]!=NULL){ 
					$local_IdOrdemServicoParcela	=	$lin3[IdOrdemServicoParcela];
				}else{
					$local_IdOrdemServicoParcela	=	1;
				}
				
				$local_Valor[$i][0]	=	str_replace(".", "", $local_Valor[$i][0]);	
				$local_Valor[$i][0]	= 	str_replace(",", ".", $local_Valor[$i][0]);
				
				$sql	=	"
					INSERT INTO OrdemServicoParcela SET 
						IdLoja 						= $local_IdLoja,
						IdOrdemServico				= $local_IdOrdemServico,
						IdOrdemServicoParcela		= $local_IdOrdemServicoParcela,
						Valor						= '".$local_Valor[$i][0]."',
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
			$local_Erro = 4;	
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;
		}
		
		mysql_query($sql,$con);
	}
?>
