<?
	$localModulo		=	1;
	$localOperacao		=	137;
	$localSuboperacao	=	"U";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_IdContaReceber	= $_POST['IdContaReceber'];
	$local_IdPessoa			= $_POST['IdPessoa'];
	
	if($local_IdContaReceber == '') {
		$local_IdContaReceber = $_GET['IdContaReceber'];
	}
	
	if($local_IdPessoa == '') {
		$local_IdPessoa = $_GET['IdPessoa'];
	}
	
	if($local_Acao == '') {
		$local_Acao = $_GET['Acao'];
	}
	
	switch($local_Acao){
		case "voltar":
			header("Location: cadastro_conta_receber.php?IdContaReceber=$local_IdContaReceber&Erro=$local_Erro");
			break;
		case "confirmar":
			$sql = "START TRANSACTION;";
			@mysql_query($sql,$con);
			
			$tr_i = 0;
			$sql = "SELECT
						MD5,
						Obs
					FROM
						ContaReceber
					WHERE
						IdLoja = '$local_IdLoja' AND
						IdContaReceber = '$local_IdContaReceber';";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);

			$sql = "UPDATE
						ContaReceber 
					SET
						Obs = '".date("d/m/Y H:i:s")." [$local_Login] - Liberação via Pré-Confirmação de Pagamento.\n$lin[Obs]',
						IdStatusConfirmacaoPagamento = '1',
						DataSolicitacaoConfirmacaoPagamento	= concat(curdate(),' ',curtime())
					WHERE
						MD5 = '$lin[MD5]';";
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
			
			$sqlContaReceber = "SELECT
									ContaReceber.IdContaReceber,
									LocalCobranca.DiasCompensacao
								FROM
									ContaReceber,
									LocalCobranca
								WHERE
									ContaReceber.MD5 = '$lin[MD5]' AND
									ContaReceber.IdLoja = LocalCobranca.IdLoja AND
									ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
			$resContaReceber = @mysql_query($sqlContaReceber,$con);
			$linContaReceber = @mysql_fetch_array($resContaReceber);
			
			$AtivoAte = dataConv(incrementaData(date("Y-m-d"),$linContaReceber[DiasCompensacao]),"Y-m-d","d/m/Y");
	 
			$sqlContrato = "SELECT
								distinct
								Contrato.IdContrato
							FROM
								Contrato LEFT JOIN (
									SELECT 
										LancamentoFinanceiro.IdLoja,
										LancamentoFinanceiro.IdContrato,
										Contrato.IdContratoAgrupador
									FROM
										ContaReceber,
										LancamentoFinanceiroContaReceber,
										LancamentoFinanceiro,
										Contrato,
										Servico,
										Pessoa
									WHERE
										ContaReceber.IdLoja = $local_IdLoja AND
										ContaReceber.IdContaReceber = $linContaReceber[IdContaReceber] AND
										ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
										LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND 
										ContaReceber.IdLoja = Contrato.IdLoja AND
										Contrato.IdLoja = Servico.IdLoja AND
										ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber AND
										LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro AND
										LancamentoFinanceiro.IdContrato = Contrato.IdContrato AND
										Contrato.IdServico = Servico.IdServico AND
										Contrato.IdPessoa = Pessoa.IdPessoa
								) ContratoBloquear ON (
									Contrato.IdLoja = ContratoBloquear.IdLoja AND (
										Contrato.IdContrato = ContratoBloquear.IdContrato or Contrato.IdContrato = ContratoBloquear.IdContratoAgrupador
									)
								) LEFT JOIN (
									SELECT
										LancamentoFinanceiro.IdLoja,
										ContaEventual.IdContrato
									FROM
										ContaReceber,
										LancamentoFinanceiroContaReceber,
										LancamentoFinanceiro,
										ContaEventual,
										Contrato,
										Servico,
										Pessoa
									WHERE
										ContaReceber.IdLoja = $local_IdLoja AND
										ContaReceber.IdContaReceber = $linContaReceber[IdContaReceber] AND
										ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
										LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
										ContaReceber.IdLoja = Contrato.IdLoja AND
										ContaEventual.IdLoja = Servico.IdLoja AND
										Contrato.IdLoja = Servico.IdLoja AND
										ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber AND
										LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro AND
										LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual AND
										ContaEventual.IdContrato = Contrato.IdContrato AND
										Contrato.IdServico = Servico.IdServico AND
										Contrato.IdPessoa = Pessoa.IdPessoa
								) ContaEventualBloquear ON (
									Contrato.IdLoja = ContaEventualBloquear.IdLoja AND 
									Contrato.IdContrato = ContaEventualBloquear.IdContrato
								) LEFT JOIN (
									SELECT
										LancamentoFinanceiro.IdLoja,
										OrdemServico.IdContratoFaturamento
									FROM
										ContaReceber,
										LancamentoFinanceiroContaReceber,
										LancamentoFinanceiro,
										OrdemServico,
										Contrato,
										Servico,
										Pessoa
									WHERE
										ContaReceber.IdLoja = $local_IdLoja AND
										ContaReceber.IdContaReceber = $linContaReceber[IdContaReceber] AND
										ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
										LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
										ContaReceber.IdLoja = Contrato.IdLoja AND
										OrdemServico.IdLoja = Servico.IdLoja AND
										Contrato.IdLoja = Servico.IdLoja AND
										ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber AND
										LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro AND
										LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico AND
										OrdemServico.IdContratoFaturamento = Contrato.IdContrato AND
										Contrato.IdServico = Servico.IdServico AND
										Contrato.IdPessoa = Pessoa.IdPessoa
								) OrdemServicoBloquear ON (
									Contrato.IdLoja = OrdemServicoBloquear.IdLoja AND
									Contrato.IdContrato = OrdemServicoBloquear.IdContratoFaturamento
								)
							WHERE
								(
									ContratoBloquear.IdLoja != '' AND 
									ContratoBloquear.IdContrato != ''
								) OR (
									ContratoBloquear.IdLoja != '' AND 
									ContratoBloquear.IdContratoAgrupador != ''
								) OR (
									ContaEventualBloquear.IdLoja != '' AND 
									ContaEventualBloquear.IdContrato != ''
								) OR (
									OrdemServicoBloquear.IdLoja != '' AND
									OrdemServicoBloquear.IdContratoFaturamento != ''
								);";
			$resContrato = @mysql_query($sqlContrato,$con);
			while($linContrato = @mysql_fetch_array($resContrato)) {
				// Salva como Ativo Temporariamente para Hoje + LocalCobranca.DiasCompensacao
				$sql = "UPDATE Contrato set 
							IdStatus = 201, 
							VarStatus = '$AtivoAte',
							Obs = concat('".date("d/m/Y H:i:s")." [$local_Login] - Mudou status para Ativo (até $AtivoAte)\n',Obs)
						WHERE
							IdLoja = $local_IdLoja AND
							IdContrato = $linContrato[IdContrato] AND
							IdStatus >= 200;";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);
				$tr_i++;
				// Rotina de Ativação Temporária
				$sql = "SELECT
							Servico.UrlRotinaDesbloqueio
						FROM
							Contrato,
							Servico
						WHERE
							Contrato.IdLoja = $local_IdLoja AND
							Contrato.IdLoja = Servico.IdLoja AND
							Contrato.IdContrato = $linContrato[IdContrato] AND
							Contrato.IdServico = Servico.IdServico AND
							Contrato.IdStatus >= 200 AND
							Servico.UrlRotinaDesbloqueio != '';";
				$res = @mysql_query($sql,$con);
				if($lin = @mysql_fetch_array($res)) {
					if(file_exists($lin[UrlRotinaDesbloqueio])) {
						$local_IdContrato = $linContrato[IdContrato];
						include($lin[UrlRotinaDesbloqueio]);
					}
				}
			}
			
			for($i = 0; $i < $tr_i; $i++) {
				if(!$local_transaction[$i]) {
					$local_transaction = false;
					break;
				}
			}
			
			if($local_transaction) {
				$sql = "COMMIT;";
				$local_Erro = 47;
			} else {
				$sql = "ROLLBACK;";
				$local_Erro = 46;
			}
			
			@mysql_query($sql,$con);
			header("Location: cadastro_conta_receber.php?IdContaReceber=$local_IdContaReceber&Erro=$local_Erro");
			break;
		case 'desbloquear':
			$sql = "UPDATE ContaReceber SET
						IdStatusConfirmacaoPagamento = (NULL)
					WHERE
						MD5 = '".$_GET['MD5']."';";
			@mysql_query($sql,$con);
			break;
		default:
			$local_Acao = '';
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Contas a Receber (Informar Pagamento)')">
		<div id='filtroBuscar'></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_informar_pagamento.php'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ContaReceber'>
				<input type='hidden' name='IdContaReceber' value='<?=$local_IdContaReceber?>'>
				<input type='hidden' name='IdPessoa' value='<?=$local_IdPessoa?>'>
		<?
			$sql = "SELECT
						IdContaReceber
					FROM
						ContaReceber
					WHERE
						IdLoja = '$local_IdLoja' AND
						IdStatusConfirmacaoPagamento = '2' and
						IdPessoa = $local_IdPessoa;";
			$res = @mysql_query($sql,$con);
			if(@mysql_num_rows($res) > 0) {
				$sql = "SELECT
							count(*) Qtd
						FROM
							ContaReceber
						WHERE
							IdLoja = '$local_IdLoja' AND
							IdStatusConfirmacaoPagamento = '2' and
							IdPessoa = $local_IdPessoa;";
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				echo "
				<div id='cp_lancamentos_financeiros'>
					<div id='cp_tit' style='margin-bottom:0'>Pré-confirmação negada</div>
					<table id='tabelaLancFinanceiro' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 40px'>CR.</td>
							<td style='padding:0pt 0pt 0pt 5px;'>Nome Pessoa</td>
							<td style='padding:0pt 0pt 0pt 5px;'>Nº Doc.</td>
							<td style='padding:0pt 0pt 0pt 5px;'>Nº NF</td>
							<td style='padding:0pt 0pt 0pt 5px;'>Local Cob.</td>
							<td style='padding:0pt 0pt 0pt 5px;'>Data Lanç.</td>
							<td class='valor' style='padding:0pt 0pt 0pt 5px;'>Valor (".getParametroSistema(5,1).")</td>
							<td style='padding:0pt 0pt 0pt 5px;'>Vencimento</td>
							<td style='padding:0pt 0pt 0pt 5px;'>Status</td>
							<td />
						</tr>";
				$sum = 0;
				$sql_cr = "SELECT
								ContaReceber.MD5,
								ContaReceberDados.IdContaReceber,
								(ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas) Valor,
								ContaReceberDados.DataLancamento,
								ContaReceberDados.DataVencimento,
								ContaReceberDados.NumeroDocumento,
								ContaReceberDados.NumeroNF,
								ContaReceberDados.IdStatus,
								Pessoa.Nome,
								LocalCobranca.AbreviacaoNomeLocalCobranca
							FROM
								ContaReceber,
								ContaReceberDados,
								Pessoa,
								LocalCobranca
							WHERE
								ContaReceber.IdLoja = '$local_IdLoja' AND
								ContaReceber.IdStatusConfirmacaoPagamento = '2' AND
								ContaReceber.IdPessoa = $local_IdPessoa AND
								ContaReceberDados.IdLoja = ContaReceber.IdLoja AND
								ContaReceberDados.IdContaReceber = ContaReceber.IdContaReceber AND
								ContaReceberDados.IdPessoa = Pessoa.IdPessoa AND
								ContaReceberDados.IdLoja = LocalCobranca.IdLoja AND
								ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca;";
				$res_cr = @mysql_query($sql_cr,$con);
				while($lin_cr = @mysql_fetch_array($res_cr)) {
					$link_ini = "<a href='cadastro_conta_receber.php?IdContaReceber=$lin_cr[IdContaReceber]' target='_blank'>";
					$link_fin = "</a>";
					$lin_cr[Status] = getParametroSistema(35,$lin_cr[IdStatus]);
					echo "
						<tr>
							<td style='padding:0pt 0pt 0pt 5px;'>$link_ini$lin_cr[IdContaReceber]$link_fin</td>
							<td style='padding:0pt 0pt 0pt 5px;'>$link_ini$lin_cr[Nome]$link_fin</td>
							<td style='padding:0pt 0pt 0pt 5px;'>$link_ini$lin_cr[NumeroDocumento]$link_fin</td>
							<td style='padding:0pt 0pt 0pt 5px;'>$link_ini$lin_cr[NumeroNF]$link_fin</td>
							<td style='padding:0pt 0pt 0pt 5px;'>$link_ini$lin_cr[AbreviacaoNomeLocalCobranca]$link_fin</td>
							<td style='padding:0pt 0pt 0pt 5px;'>$link_ini".dataConv($lin_cr[DataLancamento],"Y-m-d","d/m/Y")."$link_fin</td>
							<td class='valor' style='padding:0pt 0pt 0pt 5px;'>$link_ini".number_format($lin_cr[Valor],2,',','')."$link_fin</td>
							<td style='padding:0pt 0pt 0pt 5px;'>$link_ini".dataConv($lin_cr[DataVencimento],"Y-m-d","d/m/Y")."$link_fin</td>
							<td style='padding:0pt 0pt 0pt 5px;'>$link_ini$lin_cr[Status]$link_fin</td>
							<td style='padding:0pt 0pt 0pt 5px;'><a href='cadastro_informar_pagamento.php?Acao=desbloquear&IdPessoa=$local_IdPessoa&IdContaReceber=$local_IdContaReceber&MD5=$lin_cr[MD5]'>Desbloquear</a></td>
						</tr>";
					$sum += $lin_cr[Valor];
				}
				
				echo "
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='6'></td>
							<td class='valor' style='padding:0pt 0pt 0pt 5px;'>".number_format($sum,2,',','')."</td>
							<td colspan='3' />
						</tr>
					</table>
				</div>";
				echo "
				<div id='sem_permissao'>
					<p id='p1'>Você está prestes a pré-confirmar o pagamento deste registro.</p>
					<p id='p2'><B>Atenção!</B> Este cliente possui $lin[Qtd] pré-confirmação(ões) negadas anteriormente.</p>
					<p id='p2'>Deseja continuar?</p>
				</div>";
			} else {
				echo "
				<div id='sem_permissao'>
					<p id='p1'>Atenção! Você está prestes a pré-confirmar o pagamento deste registro.</p>
					<p id='p2'>Deseja continuar?</p>
				</div>";
			}
		?>
				<table  style='width:100%; margin-top:30px; text-align:center'>
					<tr>
						<td class='campo'>
							<input type='button' name='bt_voltar' value='Voltar' tabindex='5' onClick="cadastrar('voltar')">
							<input type='button' name='bt_confirmar' value='Confirmar' tabindex='5' onClick="cadastrar('confirmar')">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>	
</html>
<script type='text/javascript'>
	function cadastrar(acao) {
		document.formulario.Acao.value = acao;
		document.formulario.submit();
	}
	
	addParmUrl("marContaEventual","IdContaReceber",'<?=$local_IdContaReceber?>');
	addParmUrl("marContaEventual","IdContaReceber",'<?=$local_IdPessoa?>');
</script>