<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{		
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		if($local_IdLocalCobranca == ""){
			$local_IdLocalCobranca = $local_IdLocalCobrancaTemp;
		}
		
		$Obs	=	"";
		
		$sql	=	"select	
						ContaReceber.Obs,
						ContaReceber.DataNF,
						ContaReceber.IdPosicaoCobranca,
						ContaReceber.IdLocalCobranca,
						ContaReceber.IdPessoaEndereco,
						ContaReceber.NumeroNF,
						ContaReceber.ModeloNF,
						NotaFiscal.ObsVisivel,
						LocalCobranca.IdTipoLocalCobranca
					from 
						ContaReceber left join NotaFiscal on (
							ContaReceber.IdLoja = NotaFiscal.IdLoja and 
							ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber
						),
						LocalCobranca
					where
						ContaReceber.IdLoja = $local_IdLoja and
						ContaReceber.IdLoja = LocalCobranca.IdLoja and
						ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
						ContaReceber.IdContaReceber = $local_IdContaReceber";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
		
		if($local_NumeroNF != $lin[NumeroNF]){
			if($Obs != "") $Obs .= "\n";
			$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Número Nota Fiscal [$lin[NumeroNF] > $local_NumeroNF]";
		}
		
		$temp	=	"";
		$temp2	=	dataConv($lin[DataNF],'Y-m-d','d/m/Y');
		if($temp2!=$local_DataNF){
			if($Obs != "") $Obs .= "\n";
			if($local_DataNF != ""){
				$temp	=	$local_DataNF;
			}
			$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração da Data Nota Fiscal [$temp2 > $temp]";
		}
		
		if($local_ModeloNF != $lin[ModeloNF]){
			if($Obs != "") $Obs .= "\n";
			$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Modelo Nota Fiscal [$lin[ModeloNF] > $local_ModeloNF]";
		}
		
		if($local_ObsNotaFiscal != $lin[ObsVisivel]){
			if($Obs != "") $Obs .= "\n";
			$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração da Observação Nota Fiscal [$lin[ObsVisivel] > $local_ObsNotaFiscal]";
		}		
		
		if($lin[IdPessoaEndereco] != $local_IdPessoaEndereco){
			$sql = "SELECT
									ContaReceber.IdContaReceber,
									ContaReceber.IdPessoa,
									ContaReceber.IdPessoaEndereco,
									ContaReceber.IdLocalCobranca
								FROM
									LocalCobranca,
									ContaReceber
								WHERE
									LocalCobranca.IdLoja = $local_IdLoja AND
									LocalCobranca.IdLoja = ContaReceber.IdLoja AND																		
									LocalCobranca.IdLocalCobranca = ContaReceber.IdLocalCobranca AND
									LocalCobranca.IdTipoLocalCobranca IN (3,4,6) AND									
									ContaReceber.IdContaReceber = $local_IdContaReceber AND
									ContaReceber.IdStatus = 6";
			
			$resContaReceber = mysql_query($sql,$con);
			if(mysql_num_rows($resContaReceber) > 0){
				$dadosContaReceber = mysql_fetch_array($resContaReceber);
				$sqlContaReceber2 = "Update ContaReceber set
										IdStatus = 3 
									where
										IdContaReceber =  $dadosContaReceber[IdContaReceber]";
										
				$local_transaction[$tr_i]	=	mysql_query($sqlContaReceber2,$con);
				$tr_i++;
				
				$local_IdPosicaoCobranca =  9;
			}
		}
		
		// Posição da Cobrança para Cobrança Registrada
		if($lin[IdTipoLocalCobranca] == 4 || $lin[IdTipoLocalCobranca] == 3){
				
			$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=81 and IdParametroSistema=$lin[IdPosicaoCobranca]";
			$res4 = @mysql_query($sql4,$con);
			$lin4 = @mysql_fetch_array($res4);			
			
			$sql5 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=81 and IdParametroSistema=$local_IdPosicaoCobranca";
			$res5 = @mysql_query($sql5,$con);
			$lin5 = @mysql_fetch_array($res5);
			
			if($lin[IdPosicaoCobranca] != $local_IdPosicaoCobranca &&  $local_IdPosicaoCobranca != 0){
				if($Obs != "") $Obs .= "\n";
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração da Posição de Cobrança [$lin4[ValorParametroSistema] > $lin5[ValorParametroSistema]]";
			}
			
			if($lin[IdTipoLocalCobranca] == 3){
				$NumeroContaFinal = "";
				
				$select = "SELECT
							IdCartao,
							IdContaDebito,
							IdPessoa
						FROM
							ContaReceber
						WHERE
							IdLoja = $local_IdLoja and
							IdContaReceber = '$local_IdContaReceber'";
				$result = mysql_query($select,$con);
				$linresult = mysql_fetch_array($result);
				
				if($local_IdContaDebito != "" && $linresult[IdContaDebito] != "NULL"){
					if($local_IdContaDebito != $linresult[IdContaDebito]){
						$sql = "select NumeroAgencia,DigitoAgencia,NumeroConta,DigitoConta from PessoaContaDebito where IdLoja = '$local_IdLoja' and IdPessoa = '$linresult[IdPessoa]' and IdContaDebito = '$linresult[IdContaDebito]'";
						
						$resContaDebito = mysql_query($sql,$con);
						$linContaDebito = mysql_fetch_array($resContaDebito);
						
						if($linContaDebito[NumeroAgencia]  != "" && $linContaDebito[DigitoAgencia] != ""){
							$NumeroContaFinal .= $linContaDebito[NumeroAgencia]."-".$linContaDebito[DigitoAgencia]." ";
						}else{
							$NumeroContaFinal .= $linContaDebito[NumeroAgencia]." ";
						}
						
						if($linContaDebito[NumeroConta] != "" && $linContaDebito[DigitoConta] != ""){
							$NumeroContaFinal .= $linContaDebito[NumeroConta]."-"+$linContaDebito[DigitoConta];
						}else{
							$NumeroContaFinal .= $linContaDebito[NumeroConta];
						}
						
						$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alterou a Número da Conta Débito $NumeroContaFinal > $local_NumeroContaDebito";
					}
				}
			}

			$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $local_IdContaReceber, $local_IdPosicaoCobranca, $local_Login, $local_IdContaCartaoAux);
			$tr_i++;
		}
		if($lin[IdTipoLocalCobranca] == 3 || $lin[IdTipoLocalCobranca] == 6){		
			
			if($lin[IdTipoLocalCobranca] == 6){
				$NumeroContaFinal = "";
				
				$select = "SELECT
							IdCartao,
							IdContaDebito,
							IdPessoa
						FROM
							ContaReceber
						WHERE
							IdLoja = $local_IdLoja and
							IdContaReceber = '$local_IdContaReceber'";
				$result = mysql_query($select,$con);
				$linresult = mysql_fetch_array($result);
				
				if($local_NumeroCartaoCreditoAux != "" && $linresult[IdCartao] != "NULL"){
					if($local_NumeroCartaoCredito != $linresult[IdCartao]){
						$sql = "select NumeroCartao from PessoaCartao where IdLoja = '$local_IdLoja' and IdPessoa = '$linresult[IdPessoa]' and IdCartao = '$linresult[IdCartao]'";
						
						$resCartaoCredito = mysql_query($sql,$con);
						$linCartaoCredito = mysql_fetch_array($resCartaoCredito);
						
						$NumeroCartaoFinal = substr($linCartaoCredito[NumeroCartao],0,4)." **** **** ".substr($linCartaoCredito[NumeroCartao],15,18);
						
						$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alterou a Número do Cartão de Crédito $NumeroCartaoFinal > $local_NumeroCartaoCreditoAux";
					}
				}
			}
			
			$sql = "UPDATE ContaReceberPosicaoCobranca SET 
						IdContaDebito = $local_IdContaDebito,
						IdCartao	  = $local_NumeroCartaoCredito
					WHERE
						IdLoja = $local_IdLoja and
						IdMovimentacao = 1 and
						IdContaReceber = '$local_IdContaReceber' and
						IdPosicaoCobranca = 1 and
						DataRemessa = '0000-00-00' ";
			
			$local_transaction[$tr_i]	= mysql_query($sql,$con);
			$tr_i++;
			
			$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $local_IdContaReceber, $local_IdPosicaoCobranca, $local_Login, $local_IdContaCartaoAux);
			$tr_i++;
		}
		
		if($local_Obs!=""){
			if($Obs != "") $Obs .= "\n";
			$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Obs: ".trim($local_Obs);
		}
			
		if($lin[Obs]!=""){
			if($Obs != "") $Obs .= "\n";
			$Obs	.=	trim($lin[Obs]);
		}
		
		if($local_DataNF == ''){				
			$local_DataNF	=	"NULL";			
		}else{
			$local_DataNF	=	"'".dataConv($local_DataNF,'d/m/Y','Y-m-d')."'";
		}
		
		if($local_ModeloNF == ''){
			$local_ModeloNF = "NULL";
		} else{
			$local_ModeloNF = "'".$local_ModeloNF."'";
		}
		
		if($local_ObsNotaFiscal == ''){
			$local_ObsNotaFiscal = "NULL";
		} else{
			$local_ObsNotaFiscal = "'".$local_ObsNotaFiscal."'";
		}
		if($local_IdPessoaEndereco == ""){
			$local_IdPessoaEndereco = $local_IdPessoaEnderecoTemp;
		}
		
		$sql = "UPDATE 
					NotaFiscal 
				SET
					ObsVisivel = $local_ObsNotaFiscal
				WHERE
					NotaFiscal.IdLoja = '$local_IdLoja' AND 
					NotaFiscal.IdContaReceber = '$local_IdContaReceber';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$adicional = "";
		
		if($local_IdContaDebito != ""){
			$adicional = ",IdLocalCobranca = '$local_IdLocalCobranca'";
			
			if($lin[IdLocalCobranca] != $local_IdLocalCobranca){
				if($local_Obs!="")	$local_Obs	.= "\n";
				
				$sql1	=  "select DescricaoLocalCobranca from LocalCobranca where IdLoja = $local_IdLoja and IdLocalCobranca = $local_IdLocalCobranca";
				$res1	=	mysql_query($sql1,$con);
				$lin1	=	mysql_fetch_array($res1);
				
				$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Local de Cobrança. [$linObs[DescricaoLocalCobranca] > $lin1[DescricaoLocalCobranca]]";
			}
		}
		
		$sql	=	"UPDATE ContaReceber SET
						Obs					= '$Obs',
						IdPessoaEndereco	= '$local_IdPessoaEndereco',
						NumeroNF			= '$local_NumeroNF',
						DataNF				= $local_DataNF,
						ModeloNF			= $local_ModeloNF,
						LoginAlteracao		= '$local_Login',
						IdCartao			= $local_NumeroCartaoCredito,
						IdContaDebito		= $local_IdContaDebito,
						DataAlteracao		= concat(curdate(),' ',curtime())
						$adicional			
					 WHERE 
						IdLoja				= '$local_IdLoja' and
						IdContaReceber		= '$local_IdContaReceber'";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
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