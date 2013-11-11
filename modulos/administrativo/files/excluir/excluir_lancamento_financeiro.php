<?
	$localModulo		=	1;
	$localOperacao		=	3;
	
	$local_IdLoja					=	$_SESSION["IdLoja"];
	$local_IdLancamentoFinanceiro	=	$_GET['IdLancamentoFinanceiro'];
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
		
	$local_Login					=	$_SESSION["Login"];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		echo $local_Erro = 2;
	}else{
		$sql = "select
					IdProcessoFinanceiro
				from
					LancamentoFinanceiro
				where
					IdLoja = $local_IdLoja and
					IdLancamentoFinanceiro = $local_IdLancamentoFinanceiro";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$sql2 = "select
					Tipo
				from
					LancamentoFinanceiroDados
				where
					IdLoja = $local_IdLoja and
					IdLancamentoFinanceiro = $local_IdLancamentoFinanceiro";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		
		if($lin2[Tipo] == 'CO' || $lin2[Tipo] == 'EF'){
			$sql	=	"update LancamentoFinanceiro set
							IdStatus = 0
						where
							IdLoja = $local_IdLoja and
							IdLancamentoFinanceiro = $local_IdLancamentoFinanceiro;";
			if(mysql_query($sql,$con)==true){
		
				$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº$local_IdLancamentoFinanceiro foi cancelado.";
				
				$sqlProcessoFinanceiro = "	update ProcessoFinanceiro set
												LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento)
											where
								  				IdLoja = $local_IdLoja and
												IdProcessoFinanceiro = $lin[IdProcessoFinanceiro]";
				mysql_query($sqlProcessoFinanceiro,$con);
			
				echo $local_Erro = 54;
			}else{
				echo $local_Erro = 55;
			}
		}else{
			$sql	=	"update LancamentoFinanceiro set
							IdStatus = 2,
							IdProcessoFinanceiro = null
						where IdLoja = $local_IdLoja and
							IdLancamentoFinanceiro = $local_IdLancamentoFinanceiro;";
			if(mysql_query($sql,$con)==true){
				$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Lancamento financeiro nº$local_IdLancamentoFinanceiro foi removido.";
				
				$sqlProcessoFinanceiro = "update ProcessoFinanceiro set
												LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento)
											where
								  				IdLoja = $local_IdLoja and
												IdProcessoFinanceiro = $lin[IdProcessoFinanceiro]";
				mysql_query($sqlProcessoFinanceiro,$con);
				
				echo $local_Erro = 121;
			}else{
				echo $local_Erro = 122;
			}
		}
	}
?>
