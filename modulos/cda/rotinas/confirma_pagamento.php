<?
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');	
	include('../files/funcoes.php');	
	include('verifica.php');

	$local_IdLoja			= $_SESSION["IdLojaCDA"];
	$local_IdPessoa			= $_SESSION["IdPessoaCDA"];
	$local_ContaReceber		= $_GET["ContaReceber"];

	$sql = "select
				IdStatusConfirmacaoPagamento
			from
				ContaReceber
			where
				IdLoja = '$local_IdLoja' and
				IdStatusConfirmacaoPagamento = '2' and
				IdStatus = 1 and 
				IdPessoa = $local_IdPessoa";
	$res = @mysql_query($sql,$con);
	if(@mysql_num_rows($res) > 0){
		header("Location: ../menu.php?ctt=confirma_pagamento.php&Erro=46");
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "select
					Obs
				from
					ContaReceber
				where
					MD5 = '$local_ContaReceber'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$sql = "
			UPDATE
				ContaReceber 
			SET
				Obs = '".date("d/m/Y H:i:s")." [cda] - Liberação via Pré-Confirmação de Pagamento.\n$lin[Obs]',
				IdStatusConfirmacaoPagamento = '1',
				DataSolicitacaoConfirmacaoPagamento	= concat(curdate(),' ',curtime())
			WHERE
				MD5 = '$local_ContaReceber';";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sqlContaReceber = "select
								ContaReceber.IdContaReceber,
								LocalCobranca.DiasCompensacao
							from
								ContaReceber,
								LocalCobranca
							where
								ContaReceber.MD5 = '$local_ContaReceber' and
								ContaReceber.IdLoja = LocalCobranca.IdLoja and
								ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
		$resContaReceber = mysql_query($sqlContaReceber,$con);
		$linContaReceber = mysql_fetch_array($resContaReceber);
		
		$AtivoAte = dataConv(incrementaData(date("Y-m-d"),$linContaReceber[DiasCompensacao]),"Y-m-d","d/m/Y");
 
		$sqlContrato = "select
							distinct
							Contrato.IdContrato
						from
							Contrato left join (select 
										LancamentoFinanceiro.IdLoja,
										LancamentoFinanceiro.IdContrato,
										Contrato.IdContratoAgrupador
									from
										ContaReceber,
										LancamentoFinanceiroContaReceber,
										LancamentoFinanceiro,
										Contrato,
										Servico,
										Pessoa
									where
										ContaReceber.IdLoja = $local_IdLoja and
										ContaReceber.IdContaReceber = $linContaReceber[IdContaReceber] and
										ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
										LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
										ContaReceber.IdLoja = Contrato.IdLoja and
										Contrato.IdLoja = Servico.IdLoja and
										ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
										LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
										LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
										Contrato.IdServico = Servico.IdServico and
										Contrato.IdPessoa = Pessoa.IdPessoa) ContratoBloquear on 
											(Contrato.IdLoja = ContratoBloquear.IdLoja and (Contrato.IdContrato = ContratoBloquear.IdContrato or Contrato.IdContrato = ContratoBloquear.IdContratoAgrupador))
							left join (select
											LancamentoFinanceiro.IdLoja,
											ContaEventual.IdContrato
										from
											ContaReceber,
											LancamentoFinanceiroContaReceber,
											LancamentoFinanceiro,
											ContaEventual,
											Contrato,
											Servico,
											Pessoa
										where
											ContaReceber.IdLoja = $local_IdLoja and
											ContaReceber.IdContaReceber = $linContaReceber[IdContaReceber] and
											ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
											LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
											ContaReceber.IdLoja = Contrato.IdLoja and
											ContaEventual.IdLoja = Servico.IdLoja and
											Contrato.IdLoja = Servico.IdLoja and
											ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
											LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
											LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual and
											ContaEventual.IdContrato = Contrato.IdContrato and
											Contrato.IdServico = Servico.IdServico and
											Contrato.IdPessoa = Pessoa.IdPessoa) ContaEventualBloquear on (Contrato.IdLoja = ContaEventualBloquear.IdLoja and Contrato.IdContrato = ContaEventualBloquear.IdContrato)
							left join (select
											LancamentoFinanceiro.IdLoja,
											OrdemServico.IdContratoFaturamento
										from
											ContaReceber,
											LancamentoFinanceiroContaReceber,
											LancamentoFinanceiro,
											OrdemServico,
											Contrato,
											Servico,
											Pessoa
										where
											ContaReceber.IdLoja = $local_IdLoja and
											ContaReceber.IdContaReceber = $linContaReceber[IdContaReceber] and
											ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
											LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
											ContaReceber.IdLoja = Contrato.IdLoja and
											OrdemServico.IdLoja = Servico.IdLoja and
											Contrato.IdLoja = Servico.IdLoja and
											ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
											LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
											LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico and
											OrdemServico.IdContratoFaturamento = Contrato.IdContrato and
											Contrato.IdServico = Servico.IdServico and
											Contrato.IdPessoa = Pessoa.IdPessoa) OrdemServicoBloquear on (Contrato.IdLoja = OrdemServicoBloquear.IdLoja and Contrato.IdContrato = OrdemServicoBloquear.IdContratoFaturamento)
						where
							(ContratoBloquear.IdLoja != '' and ContratoBloquear.IdContrato != '') or
							(ContratoBloquear.IdLoja != '' and ContratoBloquear.IdContratoAgrupador != '') or
							(ContaEventualBloquear.IdLoja != '' and ContaEventualBloquear.IdContrato != '') or
							(OrdemServicoBloquear.IdLoja != '' and OrdemServicoBloquear.IdContratoFaturamento != '')";
		$resContrato = @mysql_query($sqlContrato,$con);
		while($linContrato = @mysql_fetch_array($resContrato)){
		
			// Rotina de Ativação Temporária
			$sql = "select
						Servico.UrlRotinaDesbloqueio
					from
						Contrato,
						Servico
					where
						Contrato.IdLoja = $local_IdLoja and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdContrato = $linContrato[IdContrato] and
						Contrato.IdServico = Servico.IdServico and
						Contrato.IdStatus = 303 and
						Servico.UrlRotinaDesbloqueio != ''";
			$res = @mysql_query($sql,$con);
			if($lin = @mysql_fetch_array($res)){

				$lin[UrlRotinaDesbloqueio] = "../../administrativo/".$lin[UrlRotinaDesbloqueio];

				if(file_exists($lin[UrlRotinaDesbloqueio])){
					$local_IdContrato = $linContrato[IdContrato];
					include($lin[UrlRotinaDesbloqueio]);
				}		
			}

			// Salva como Ativo Temporariamente para Hoje + LocalCobranca.DiasCompensacao (Quando Bloqueado Financeiro).
			$sql = "Update Contrato set 
							IdStatus = 201, 
							VarStatus = '$AtivoAte',
							Obs = concat('".date("d/m/Y H:i:s")." [cda] - Mudou status para Ativo (até $AtivoAte)\n',Obs)
						where
							IdLoja = $local_IdLoja and
							IdContrato = $linContrato[IdContrato] and
							IdStatus > 199";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;			
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			mysql_query($sql,$con);
			header("Location: ../menu.php?ctt=tela_aviso.php&Erro=47&IdParametroSistema=9");
		}else{
			$sql = "ROLLBACK;";
			mysql_query($sql,$con);
			header("Location: ../menu.php?ctt=tela_aviso.php&Erro=46&IdParametroSistema=9");
		}
	}
?>
