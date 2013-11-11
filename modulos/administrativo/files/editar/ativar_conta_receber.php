<?
	$localModulo		=	1;
	$localOperacao		=	84;
	$localSuboperacao	=	"U";	
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,$localSuboperacao) == false){
		$local_Erro = 2;
		
		header("Location: ../../cadastro_conta_receber_ativar.php?IdContaReceber=$local_IdContaReceber&Erro=$local_Erro");
	}else{
		$local_IdContaReceber		= $_POST['IdContaReceber'];
		$local_IdPosicaoCobranca	= $_POST['IdPosicaoCobranca'];
		$local_ObsAtivar			= formatText($_POST['ObsAtivar'],NULL);
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i	= 0;		
		
		$sql3	=	"select
					      Demonstrativo.IdLoja,
						  Demonstrativo.IdContaReceber,
					      Demonstrativo.IdLancamentoFinanceiro,
					      Demonstrativo.Tipo,
					      Demonstrativo.Codigo
					from
					      Demonstrativo,
					      Pessoa
					where
					      Demonstrativo.IdLoja = $local_IdLoja and
						  Demonstrativo.IdPessoa = Pessoa.IdPessoa and
						  Demonstrativo.IdContaReceber = $local_IdContaReceber
					order by
					      Tipo,
						  Codigo,
						  IdLancamentoFinanceiro DESC";
		$res3 = @mysql_query($sql3,$con);
		while($lin3	= @mysql_fetch_array($res3)){
			
			switch($lin3[Tipo]){
				case 'CO':
					$Obs	=	"";
					
					$sql2	=	"select 
									DataReferenciaFinal
								 from
									LancamentoFinanceiro
								 where
									IdLoja = $local_IdLoja and
									IdLancamentoFinanceiro = $lin3[IdLancamentoFinanceiro]";
					$res2	=	mysql_query($sql2,$con);
					$lin2	=	mysql_fetch_array($res2);
						
					$sql4	=	"select
									DataBaseCalculo,
									Obs
								from
									Contrato
								where
									IdLoja = $local_IdLoja and
									IdContrato = '$lin3[Codigo]'";
					$res4	=	mysql_query($sql4,$con);
					$lin4	=	mysql_fetch_array($res4);
						
					if($lin4[DataBaseCalculo] < $lin2[DataReferenciaFinal]){	
						$temp2	=	dataConv($lin4[DataBaseCalculo],'Y-m-d','d/m/Y');
						$temp	=	dataConv($lin2[DataReferenciaFinal],'Y-m-d','d/m/Y');
						
						$Obs	 =	date("d/m/Y H:i:s")." [".$local_Login."] - Ativação Contas a Receber nº $local_IdContaReceber";
						$Obs	.=	'\n'.date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Base [$temp2 > $temp]";
						
						if($lin4[Obs]!= ""){
							$Obs	.=	'\n'.$lin4[Obs];
						}
						
						if($lin2[DataReferenciaFinal] == ''){
							$lin2[DataReferenciaFinal]	=	'NULL';
						}else{
							$lin2[DataReferenciaFinal]	=	"'".$lin2[DataReferenciaFinal]."'";
						}
						
						$sql	=	"UPDATE Contrato SET
										Obs					= '$Obs',
										DataBaseCalculo		= $lin2[DataReferenciaFinal], 
										LoginAlteracao		= '$local_Login',
										DataAlteracao		= concat(curdate(),' ',curtime())
									 WHERE 
										IdLoja				= '$local_IdLoja' and
										IdContrato			= '$lin3[Codigo]'";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}
					
					$sql	=	"UPDATE LancamentoFinanceiro SET
									IdStatus				= '1'
								WHERE 
									IdLoja					= '$local_IdLoja' and
									IdLancamentoFinanceiro	= '$lin3[IdLancamentoFinanceiro]'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					break;
				default:
					$sql	=	"UPDATE LancamentoFinanceiro SET
									IdStatus				= '1'
								 WHERE 
									IdLoja					= '$local_IdLoja' and
									IdLancamentoFinanceiro	= '$lin3[IdLancamentoFinanceiro]'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
					$sql2	=	"select
									LancamentoFinanceiro.IdLancamentoFinanceiro,
									LancamentoFinanceiro.IdContaEventual,
									LancamentoFinanceiro.IdOrdemServico,
									LancamentoFinanceiro.TipoLancamentoFinanceiro,
									LancamentoFinanceiro.DataReferenciaInicial,
									LancamentoFinanceiro.DataReferenciaFinal,
									LancamentoFinanceiro.NumParcelaEventual,
									LancamentoFinanceiro.Valor,
									LancamentoFinanceiro.ValorRepasseTerceiro,
									LancamentoFinanceiro.IdProcessoFinanceiro
								from
									LancamentoFinanceiro
								where
									LancamentoFinanceiro.IdLoja = $local_IdLoja and
									LancamentoFinanceiro.IdLancamentoFinanceiro = $lin3[IdLancamentoFinanceiro]";
					$res2	=	@mysql_query($sql2,$con);
					$lin2	=	@mysql_fetch_array($res2);
					
					if($lin2[IdContaEventual]=="") 		 $lin2[IdContaEventual]			='NULL';
					if($lin2[IdOrdemServico]=="") 		 $lin2[IdOrdemServico]			='NULL';
					if($lin2[IdProcessoFinanceiro]=="")  $lin2[IdProcessoFinanceiro]	='NULL';
					
					if($lin2[DataReferenciaInicial]!="")$lin2[DataReferenciaInicial]	="'".$lin2[DataReferenciaInicial]."'";
					else								$lin2[DataReferenciaInicial]	='NULL';
					
					if($lin2[DataReferenciaFinal]!="")	$lin2[DataReferenciaFinal]		="'".$lin2[DataReferenciaFinal]."'";
					else								$lin2[DataReferenciaFinal]		='NULL';
					
					$sql4	=	"select
									LancamentoFinanceiro.IdLancamentoFinanceiro
								from 
									LancamentoFinanceiro
								where
									LancamentoFinanceiro.IdLoja = $local_IdLoja and
									LancamentoFinanceiro.IdContaEventual = $lin2[IdContaEventual] and
									LancamentoFinanceiro.IdOrdemServico = $lin2[IdOrdemServico] and
									LancamentoFinanceiro.TipoLancamentoFinanceiro = $lin2[IdOrdemServico] and
									LancamentoFinanceiro.DataReferenciaInicial = $lin2[DataReferenciaInicial] and
									LancamentoFinanceiro.DataReferenciaFinal = $lin2[DataReferenciaFinal] and
									LancamentoFinanceiro.NumParcelaEventual = $lin2[NumParcelaEventual] and
									LancamentoFinanceiro.Valor = '$lin2[Valor]' and
									LancamentoFinanceiro.ValorRepasseTerceiro = '$lin2[ValorRepasseTerceiro]' and
									LancamentoFinanceiro.IdProcessoFinanceiro = $lin2[IdProcessoFinanceiro] and
									LancamentoFinanceiro.IdStatus = 2";
					$res4	=	@mysql_query($sql4,$con);
					while($lin4	=	@mysql_fetch_array($res4)){
						$sql	=	"UPDATE LancamentoFinanceiro SET
										IdStatus				= '0'
									 WHERE 
										IdLoja					= '$local_IdLoja' and
										IdLancamentoFinanceiro	= '$lin4[IdLancamentoFinanceiro]'";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}
					
					break;
			}
		}
		
		$sql4	=  "select 
						ContaReceber.IdPosicaoCobranca, 
						ContaReceber.Obs,
						LocalCobranca.IdTipoLocalCobranca
					from 
						ContaReceber,
						LocalCobranca
					where 
						ContaReceber.IdLoja = $local_IdLoja and 
						ContaReceber.IdLoja = LocalCobranca.IdLoja and
						ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
						ContaReceber.IdContaReceber = $local_IdContaReceber";
		$res4	=	mysql_query($sql4,$con);
		$lin4	=	mysql_fetch_array($res4);
		
		if($lin4[Obs]!=""){
			$lin4[Obs]	=	trim($lin4[Obs]);
		}
		
		if($local_ObsAtivar != ""){
			$local_Obs		.=	date("d/m/Y H:i:s")." [".$local_Login."] - Observações da Ativação: ".trim($local_ObsAtivar)."\n".$lin4[Obs];
		}else{
			$local_Obs		.=	$lin4[Obs];
		}

		if($lin4[IdTipoLocalCobranca] == 4 || $lin4[IdTipoLocalCobranca] == 3){
			
			switch($local_IdPosicaoCobranca){
				case '0':					
					$sql	=	"DELETE FROM ContaReceberPosicaoCobranca WHERE 
									IdLoja = $local_IdLoja and 
									IdContaReceber = $local_IdContaReceber and
									DataRemessa = '0000-00-00' and
									IdPosicaoCobranca = 5";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);							
					$tr_i++;

					$sql7	=	"select IdPosicaoCobranca from ContaReceberPosicaoCobranca where IdLoja = $local_IdLoja and IdContaReceber = $local_IdContaReceber order by IdMovimentacao desc limit 0,1";
					$res7	=	mysql_query($sql7,$con);
					$lin7	=	mysql_fetch_array($res7);

					if($lin7[IdPosicaoCobranca] == ''){
						$lin7[IdPosicaoCobranca] = 1;
					}
						
					$sql	=	"UPDATE ContaReceber SET
										IdPosicaoCobranca   = $lin7[IdPosicaoCobranca]
								 WHERE 
										IdLoja				= $local_IdLoja and
										IdContaReceber		= $local_IdContaReceber";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);						
					$tr_i++;
					break;
				case '10':
					$sql5 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=81 and IdParametroSistema=$lin4[IdPosicaoCobranca]";
					$res5 = @mysql_query($sql5,$con);
					$lin5 = @mysql_fetch_array($res5);			
					
					$sql6 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=81 and IdParametroSistema=$local_IdPosicaoCobranca";
					$res6 = @mysql_query($sql6,$con);
					$lin6 = @mysql_fetch_array($res6);
					
					if($lin4[IdPosicaoCobranca] != $local_IdPosicaoCobranca &&  $local_IdPosicaoCobranca != 0){
						if($local_Obs != "") $local_Obs .= "\n";
						$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração da Posição de Cobrança [$lin5[ValorParametroSistema] > $lin6[ValorParametroSistema]]";
					}

					$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $local_IdContaReceber, $local_IdPosicaoCobranca, $local_Login);
					$tr_i++;
					break;				
			}
		}
		
		$sql = "select	
					count(*) QtdRecebimento
				from
					ContaReceberRecebimento
				where
					IdLoja = $local_IdLoja and
					IdContaReceber = $local_IdContaReceber and
					IdStatus = 1";
		$res8	=	mysql_query($sql,$con);
		$lin8	=	mysql_fetch_array($res8);

		if($lin8[QtdRecebimento] > 0){
			$IdStatusAtivacao = 2;
		}else{
			$IdStatusAtivacao = 1;
		}
				
		$sql = "UPDATE ContaReceber SET
					IdStatus						= $IdStatusAtivacao, 
					IdStatusConfirmacaoPagamento	= NULL,
					Obs								= \"$local_Obs\",
					LoginAlteracao					= '$local_Login',
					DataAlteracao					= concat(curdate(),' ',curtime())
				WHERE 
					IdLoja							= '$local_IdLoja' and
					IdContaReceber					= '$local_IdContaReceber'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
					
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 126;			// Mensagem de Alteração Positiva
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 127;			// Mensagem de Alteração Negativa
		}	
		mysql_query($sql,$con);
		
		header("Location: ../../cadastro_conta_receber.php?IdContaReceber=$local_IdContaReceber&Erro=$local_Erro");
	}
?>
