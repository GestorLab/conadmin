<?
	set_time_limit(0);

	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"E";
		
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$Path = "../../../";
	
	include ('../../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdContaReceber	= $_POST['IdContaReceber'];
	$local_IdContaEventual	= $_POST['IdContaEventual'];
	$local_IdOrdemServico	= $_POST['IdOrdemServico'];
	$local_IdContaEmail		= $_POST['IdContaEmail'];
	$local_Email			= $_POST['Email'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"E") == true){
		if($local_IdContaEmail != ''){
			$Erro = enviaTesteContaEmail($local_IdLoja, $local_IdContaEmail, $local_Email);
			
			echo "cadastro_conta_email.php?IdContaEmail=".$local_IdContaEmail."&Erro=".$Erro;
		} else{
			if($local_IdContaReceber != ''){
				$RetornoMensagemContaReceber =  enviaContaReceber($local_IdLoja, $local_IdContaReceber);

				if($RetornoMensagemContaReceber != false){					
					enviaMensagem($local_IdLoja, $RetornoMensagemContaReceber);

					header("Location: ../listar_reenvio_mensagem.php?IdHistoricoMensagem=".$RetornoMensagemContaReceber."&Erro=64");
				}
			}else{
				if($local_IdContaEventual == '' && $local_IdOrdemServico != ''){
					header("Location: ../cadastro_conta_receber.php?IdContaReceber=$local_IdContaReceber");
				}
			}
			
			if($local_IdContaEventual != ''){
				$sql = "select
							LancamentoFinanceiroContaReceber.IdContaReceber
						from
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber
						where
							LancamentoFinanceiro.IdLoja = $local_IdLoja and
							LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
							LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
							LancamentoFinanceiro.IdContaEventual= $local_IdContaEventual
						order by
							LancamentoFinanceiroContaReceber.IdContaReceber";
				$resLancamentoFinanceiro = mysql_query($sql,$con);	
				while($linLancamentoFinanceiro = mysql_fetch_array($resLancamentoFinanceiro)){
					$RetornoMensagemContaEventual =  enviaGeraBoleto($local_IdLoja, $linLancamentoFinanceiro[IdContaReceber]);
					
					if($RetornoMensagemContaEventual != false){	
						enviaMensagem($local_IdLoja, $RetornoMensagemContaEventual);
						header("Location: ../listar_reenvio_mensagem.php?IdContaEventual=".$local_IdContaEventual."&Erro=64");
					}
				}
			}else{
				if($local_IdContaReceber == '' && $local_IdOrdemServico == ''){
					header("Location: ../cadastro_conta_eventual.php?IdContaEnventual=$local_IdContaEventual");			
				}
			}		

			if($local_IdOrdemServico != ''){
				$sql = "select
							LancamentoFinanceiroContaReceber.IdContaReceber
						from
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber
						where
							LancamentoFinanceiro.IdLoja = $local_IdLoja and
							LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
							LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
							LancamentoFinanceiro.IdOrdemServico= $local_IdOrdemServico
						order by
							LancamentoFinanceiroContaReceber.IdContaReceber";
				$resLancamentoFinanceiro = mysql_query($sql,$con);	
				while($linLancamentoFinanceiro = mysql_fetch_array($resLancamentoFinanceiro)){
					$RetornoMensagemOrdemServico =  enviaGeraBoleto($local_IdLoja, $linLancamentoFinanceiro[IdContaReceber]);
					
					if($RetornoMensagemOrdemServico != false){		
						enviaMensagem($local_IdLoja, $RetornoMensagemOrdemServico);
						header("Location: ../listar_reenvio_mensagem.php?IdOrdemServico=".$local_IdOrdemServico."&Erro=64");
					}
				}
			}else{
				if($local_IdContaReceber == '' && $local_IdContaEventual == ''){
					header("Location: ../cadastro_ordem_servico_fatura.php?IdOrdemServico=$local_IdOrdemServico");			
				}
			}
		}
	}	
?>