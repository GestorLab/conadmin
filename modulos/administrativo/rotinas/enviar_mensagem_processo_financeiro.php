<?
	set_time_limit(0);

	$EndFile 	= "modulos/administrativo/rotinas/enviar_mensagem_processo_financeiro.php";
	$Vars 		= $_SERVER['argv'];
	$Path		= substr($Vars[0],0,strlen($Vars[0])-strlen($EndFile));

	include ($Path.'files/conecta.php');
	include ($Path.'files/funcoes.php');
	include ($Path.'classes/envia_mensagem/envia_mensagem.php');
	 
	$Login					= $Vars[1];
	$IdLoja					= $Vars[2];
	$IdProcessoFinanceiro 	= $Vars[3];
	$tempoEntreEnvios		= getCodigoInterno(2,12);
	$moeda					= getParametroSistema(5,1);
	$local_Login			= $Login;
	$cont					= 0;

	// Mensal de Início de Envio
	$sql = "select
				LogProcessamento
			from
				ProcessoFinanceiro
			where
				IdLoja=$IdLoja AND 
				IdProcessoFinanceiro=$IdProcessoFinanceiro";
	$resProcessoFinanceiro = mysql_query($sql,$con);
	$linProcessoFinanceiro = mysql_fetch_array($resProcessoFinanceiro);
	
	if($linProcessoFinanceiro[LogProcessamento] != ''){
		$linProcessoFinanceiro[LogProcessamento] = "\n".$linProcessoFinanceiro[LogProcessamento];
	}
	
	$linProcessoFinanceiro[LogProcessamento] = date("d/m/Y H:i:s")." [$Login] - Iniciando envio de e-mails para clientes.".$linProcessoFinanceiro[LogProcessamento];
	
	$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
								LogProcessamento = '$linProcessoFinanceiro[LogProcessamento]'
							  WHERE 
							  	IdLoja = $IdLoja AND 
								IdProcessoFinanceiro = $IdProcessoFinanceiro";
	mysql_query($sqlProcessoFinanceiro,$con);

	$sqlContasReceber = "select
							distinct 
							Demonstrativo.IdContaReceber
						from
							Demonstrativo,
							ContaReceber,
							Pessoa
						where
							Demonstrativo.IdLoja = $IdLoja and
							Demonstrativo.IdLoja = ContaReceber.IdLoja and
							Demonstrativo.IdContaReceber = ContaReceber.IdContaReceber and
							Demonstrativo.IdProcessoFinanceiro = $IdProcessoFinanceiro and
							Demonstrativo.IdPessoa = Pessoa.IdPessoa and
							Pessoa.Cob_FormaEmail = 'S' and
							ContaReceber.IdStatus = 1";
	$resContasReceber = mysql_query($sqlContasReceber,$con);
	while($linContasReceber = mysql_fetch_array($resContasReceber)){
		mysql_close($con);	
		include ($Path.'files/conecta.php');
		
		$RetornoMensagemContaReceber = enviaGeraBoleto($IdLoja, $linContasReceber[IdContaReceber]);

		if($RetornoMensagemContaReceber == true){
			$cont++;
			$sqlRetorno = "select
								HistoricoMensagem.Email,
								Pessoa.Nome,
								(ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas) ValorTotal,
								HistoricoMensagem.IdStatus 
							from	
								HistoricoMensagem,
								ContaReceberDados,
								Pessoa
							where
								HistoricoMensagem.IdLoja = $IdLoja and
								HistoricoMensagem.IdLoja = ContaReceberDados.IdLoja and
								HistoricoMensagem.IdHistoricoMensagem = $RetornoMensagemContaReceber and
								HistoricoMensagem.IdContaReceber = ContaReceberDados.IdContaReceber and
								HistoricoMensagem.IdPessoa = Pessoa.IdPessoa";
			$resRetorno = mysql_query($sqlRetorno,$con);
			$linRetorno = mysql_fetch_array($resRetorno);
			$linRetorno[Nome] = substr($linRetorno[Nome],0,20);
			
			if(enviaMensagem($IdLoja, $RetornoMensagemContaReceber)){
				$LogProcessamento = date("d/m/Y H:i:s")." [$Login] - $linRetorno[Nome] ($linRetorno[Email]) $moeda$linRetorno[ValorTotal].";
			}
			
			if($LogProcessamento != ''){
				if($linProcessoFinanceiro[LogProcessamento] != ''){
					$linProcessoFinanceiro[LogProcessamento] = "\n".$linProcessoFinanceiro[LogProcessamento];
				}
				
				$linProcessoFinanceiro[LogProcessamento] = $LogProcessamento.$linProcessoFinanceiro[LogProcessamento];
			}

			$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
										LogProcessamento = '$linProcessoFinanceiro[LogProcessamento]' 
									  WHERE 
									  	IdLoja = $IdLoja AND 
										IdProcessoFinanceiro = $IdProcessoFinanceiro";
			mysql_query($sqlProcessoFinanceiro,$con);
			
			sleep($tempoEntreEnvios);
			
			$sql1 = "select
						HistoricoMensagem.IdStatus 
					from	
						HistoricoMensagem,
						ContaReceberDados,
						Pessoa
					where
						HistoricoMensagem.IdLoja = $IdLoja and
						HistoricoMensagem.IdLoja = ContaReceberDados.IdLoja and
						HistoricoMensagem.IdHistoricoMensagem = $RetornoMensagemContaReceber and
						HistoricoMensagem.IdContaReceber = ContaReceberDados.IdContaReceber and
						HistoricoMensagem.IdPessoa = Pessoa.IdPessoa";
			$res1 = mysql_query($sql1,$con);
			$lin1 = mysql_fetch_array($res1);
			
			switch($lin1[IdStatus]){
				case 3:
					if($cont >= 1){
						$cont--;
					}
					break;
				case 6:
					if($cont >= 1){
						$cont--;
					}
					break;
			}
		}
	}
	
	if($linProcessoFinanceiro[LogProcessamento] != ''){
		$linProcessoFinanceiro[LogProcessamento] = "\n".$linProcessoFinanceiro[LogProcessamento];
	}
	
	$linProcessoFinanceiro[LogProcessamento] = date("d/m/Y H:i:s")." [$Login] - Fim de envio de e-mails para clientes. ".$cont." e-mail´s enviados.".$linProcessoFinanceiro[LogProcessamento];

	$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
								LogProcessamento = '$linProcessoFinanceiro[LogProcessamento]'
							  WHERE 
							  	IdLoja = $IdLoja AND 
								IdProcessoFinanceiro = $IdProcessoFinanceiro";
	mysql_query($sqlProcessoFinanceiro,$con);
	$sql = "select
				Titulo,
				Assunto,
				Conteudo,
				IdStatus
			from
				TipoMensagem
			where
				IdLoja = $local_IdLoja and
				IdTipoMensagem = 3";
		$res = mysql_query($sql,$con);
		$linMensagem = mysql_fetch_array($res);

	if($linMensagem[IdStatus] != 1){
		$local_ErroEmail		= getParametroSistema(13,191);
		$local_TipoEmail		= "'".delimitaAteCaracter($linMensagem[Titulo],'$')."'";
	}

	enviaLogProcessoFinanceiro($IdLoja, $IdProcessoFinanceiro);
?>