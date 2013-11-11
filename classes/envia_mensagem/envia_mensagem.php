<?
	require($Path.'classes/phpmailer/class.phpmailer.php');
	@include($Path.'classes/envia_mensagem/envia_mensagem_personalizada.php');
	
	function getCelularPessoa($IdPessoa){
		global $con;

		$sql = "select
					Celular
				from
					Pessoa
				where
					IdPessoa = $IdPessoa";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$lin[Celular] = str_replace("-","",$lin[Celular]);
		$lin[Celular] = str_replace(" ","",$lin[Celular]);
		$lin[Celular] = str_replace("(","",$lin[Celular]);
		$lin[Celular] = str_replace(")","",$lin[Celular]);
		$lin[Celular] = trim($lin[Celular]);

		// Isso vai ser removido futuramente.
		if(strlen($lin[Celular]) == 10 || strlen($lin[Celular]) == 11){
			$lin[Celular] = "55".$lin[Celular];
		}

		return $lin[Celular];
	}
	
	function parametroTipoMensagem($IdLoja, $IdTipoMensagem){
		global $con;

		$sqlParametroMensagem = "select
									IdTipoMensagemParametro,
									ValorTipoMensagemParametro
								from
									TipoMensagemParametro
								where
									IdLoja = $IdLoja and
									IdTipoMensagem = $IdTipoMensagem";
		$resParametroMensagem = mysql_query($sqlParametroMensagem,$con);
		while($linParametroMensagem = mysql_fetch_array($resParametroMensagem)){
			$Var[trim($linParametroMensagem[IdTipoMensagemParametro])] = $linParametroMensagem[ValorTipoMensagemParametro];
		}
		return $Var;
	}

	function enviaMensagem($IdLoja, $IdHistoricoMensagem){

		global $con;
	
		$PatchSistema = getParametroSistema(6,1);
		$PatchPHP	  = getParametroSistema(6,4);

		// Inicia a variável Obs do historico de mensagens
		$sql = "UPDATE HistoricoMensagem SET Obs = '' WHERE IdStatus IN (1,3,5) AND Obs IS NULL";
		mysql_query($sql,$con);

		// Faz o cancelamento caso tenha e-mails irregulares
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem,
					HistoricoMensagem.Email,
					TipoMensagem.IdContaEmail
				FROM
					HistoricoMensagem,
					TipoMensagem
				WHERE
					HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and
					HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem and
					TipoMensagem.IdContaEmail != '' and
					HistoricoMensagem.IdStatus IN (1,3,5) and
					(
						HistoricoMensagem.Email like ';' or
						HistoricoMensagem.Email like '%,%' or
						HistoricoMensagem.Email like '% %' or
						HistoricoMensagem.Email like '%@' or
						HistoricoMensagem.Email like '@%' or 
						HistoricoMensagem.Email like '%:%' or 						
						HistoricoMensagem.Email like '%!%' or 
						HistoricoMensagem.Email like '%?%' or 
						HistoricoMensagem.Email like '% %' or 
						HistoricoMensagem.Email like '%\n%' or 
						HistoricoMensagem.Email like '%£%' or 
						HistoricoMensagem.Email = 'NULL' or 
						HistoricoMensagem.Email = ''
					)";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! E-mail inválido!";

			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}
		
		// Faz o cancelamento das contas dos tipos de mensagens já desativados
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem
				FROM
					TipoMensagem,
					HistoricoMensagem
				WHERE
					TipoMensagem.IdStatus != 1 AND
					TipoMensagem.IdLoja = HistoricoMensagem.IdLoja AND
					TipoMensagem.IdTipoMensagem = HistoricoMensagem.IdTipoMensagem AND
					HistoricoMensagem.IdStatus IN (1,3,5)";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Tipo de mensagem desativada!";

			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}

		// Ele faz uma análise sobre cada tipo de msg e seus respectivos tipos de cancelamento

		#2	Aviso de Geração de Boletos #################################################################################################
		# Regras
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem,
					ContaReceber.IdStatus,
					IF(ContaReceber.DataVencimento < CURDATE(),1, 0) CancelaRegra1,
                    IF(ContaReceber.IdStatus NOT IN (1, 3, 4), 1, 0) CancelaRegra2
				FROM
					HistoricoMensagem,
					ContaReceber
				WHERE
					HistoricoMensagem.IdTipoMensagem = 2 AND
					HistoricoMensagem.IdStatus IN (1,3,5) AND
					HistoricoMensagem.IdLoja = ContaReceber.IdLoja AND
					HistoricoMensagem.IdContaReceber = ContaReceber.IdContaReceber";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
		
			# Regra 1: Se a data do vencimento do contas a receber for menor do que a data de hoje ele cancela.
			if($lin[CancelaRegra1] == 1){
				$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Contas a receber vencido.";
				$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
				mysql_query($sql,$con);
			}

			# Regra 2: Se o contas a receber não for 'Aguardando Pagamento', 'Aguardando Envio', 'Em Arquivo de Remessa' ele cancela o envio
			if($lin[CancelaRegra2] == 1){
				$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Contas: ".getParametroSistema(35,$lin[IdStatus]);
				$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
				mysql_query($sql,$con);
			}
		}

		#1	Boleto Disponível para Impressão ###################################################### MESMA REGRA DO DE BAIXO #####
		#5	Aviso de Vencimento #################################################################################################
		# Regras
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem,
					ContaReceber.IdStatus,
                    IF(ContaReceber.IdStatus NOT IN (1, 3, 4), 1, 0) CancelaRegra1
				FROM
					HistoricoMensagem,
					ContaReceber
				WHERE
					(
						HistoricoMensagem.IdTipoMensagem = 1 OR
						HistoricoMensagem.IdTipoMensagem = 5
					) AND
					HistoricoMensagem.IdStatus IN (1,3,5) AND
					HistoricoMensagem.IdLoja = ContaReceber.IdLoja AND
					HistoricoMensagem.IdContaReceber = ContaReceber.IdContaReceber";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			# Regra 1: Se o contas a receber não for 'Aguardando Pagamento', 'Aguardando Envio', 'Em Arquivo de Remessa' ele cancela
			if($lin[CancelaRegra1] == 1){
				$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Contas a receber: ".getParametroSistema(35,$lin[IdStatus]);
				$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
				mysql_query($sql,$con);
			}
		}

		#9.1	Log Backup $DataBackup #################################################################################################
		# Regras
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem
				FROM
					HistoricoMensagem
				WHERE
					HistoricoMensagem.IdTipoMensagem = 8 OR
					HistoricoMensagem.IdTipoMensagem = 9
				ORDER BY
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem DESC
				LIMIT 0,1";
		$res = mysql_query($sql,$con);
		if($lin = mysql_fetch_array($res)){

			# Regra 1: Deixa apenas a última mensagem referente ao backup, o restante ele cancela o envio
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Data de envio ultrapassada.";

			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							(
								IdTipoMensagem = 8 OR
								IdTipoMensagem = 9
							) AND
							IdStatus IN (1,3,5) AND
							IdHistoricoMensagem != '$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}

		#9.2	Log Backup [ERRO] $DataBackup #################################################################################################
		# Regras
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem,
					HistoricoMensagem.DataEnvio
				FROM
					HistoricoMensagem
				WHERE
					HistoricoMensagem.IdTipoMensagem = 9 AND
					HistoricoMensagem.IdStatus IN (1,3,5)";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			# Regra 1: Se o backup não estiver mais apresentando erro, ele cancela o envio
			$sql2 = "SELECT
						COUNT(*) Qtd
					FROM
						Backup
					WHERE
						DataHoraConclusao >= '$lin[DataEnvio]' AND
						Erro = 0";
			$res2 = mysql_query($sql2,$con);
			$lin2 = mysql_fetch_array($res2);

			if($lin2[Qtd] >= 1){
				$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Backup re-estabilizado automaticamente.";
				$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
				mysql_query($sql,$con);
			}
		}

		#4.1  Log do Processo Financeiro ########################################################################################################
		# Regras
		# Regra 1: Se for maior do que 30 dias ele cancela o envio, pois não tem mais necessidade desta informação.
		$sql = "SELECT
						HistoricoMensagem.IdLoja,
						HistoricoMensagem.IdHistoricoMensagem
				FROM
						HistoricoMensagem
				WHERE
						HistoricoMensagem.IdTipoMensagem = 3 AND
						HistoricoMensagem.IdStatus IN (1,3,5) AND						
						DATEDIFF(CURDATE(), HistoricoMensagem.DataCriacao) > 30";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Ultrapassou a data de envio.";
			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}

		#4	Link para download dos Boletos em PDF #################################################################################################
		# Regras
		# Regra 1 (JUSTIFICATIVA PARA TIPO 4): Se for maior do que 30 dias ele cancela o envio, pois os arquivos não estão mais disponíveis para impressão.
		$sql = "SELECT
						HistoricoMensagem.IdLoja,
						HistoricoMensagem.IdHistoricoMensagem
				FROM
						HistoricoMensagem
				WHERE
						HistoricoMensagem.IdTipoMensagem = 4 AND
						HistoricoMensagem.IdStatus IN (1,3,5) AND						
						DATEDIFF(CURDATE(), HistoricoMensagem.DataCriacao) > 30";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Arquivos não estão mais disponíveis para impressão.";
			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}

		#15 Bem-Vindo! ############################################################################################ A MESMA REGRA DA DE ABAIXO ####
		# Regra 1 (JUSTIFICATIVA PARA TIPO 15): Se a pessoa está aconosco a mais de 7 dias, ela não é mais novidade.
		$sql = "SELECT
						HistoricoMensagem.IdLoja,
						HistoricoMensagem.IdHistoricoMensagem
				FROM
						HistoricoMensagem
				WHERE
						HistoricoMensagem.IdTipoMensagem = 15 AND
						HistoricoMensagem.IdStatus IN (1,3,5) AND						
						DATEDIFF(CURDATE(), HistoricoMensagem.DataCriacao) > 7";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Ultrapassou o prazo de envio (7 dias).";
			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}
	
	
		#16 Novo Contrato! ###################################################### MESMA REGRA DA DE BAIXO ##################################
		#23 Mudança de Status! Plano: $NomePlano - Contrato $IdContrato ####################################################################
		# Regra 1: Se o contrato estiver na faixa dos cancelados, ele cancela o envio
		# Regra 2: Se estiver com mais de 7 dias ele cancela o envio
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem
				FROM
					HistoricoMensagem,
					Contrato
				WHERE
					(
						HistoricoMensagem.IdTipoMensagem = 16 OR
						HistoricoMensagem.IdTipoMensagem = 23
					) AND
					HistoricoMensagem.IdStatus IN (1,3,5) AND						
					(
						DATEDIFF(CURDATE(), HistoricoMensagem.DataCriacao) > 7 OR
						Contrato.IdStatus <= 199
					) AND
					HistoricoMensagem.IdLoja = Contrato.IdLoja AND
					HistoricoMensagem.IdContrato = Contrato.IdContrato";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Contrato já cancelado ou ultrapassou o prazo de envio (7 dias).";
			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}	
	
		#18 Contrato Cancelado #############################################################################################################
		#20 Ordem de Serviço Concluída #####################################################################################################
		#27	Mudança de Plano ###############################################################################################################
		#14	Solicitação de nova senha CDA ##################################################################################################
		# Regra 1: Se estiver com mais de 7 dias ele cancela o envio
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem
				FROM
					HistoricoMensagem
				WHERE
					(
						HistoricoMensagem.IdTipoMensagem = 18 OR
						HistoricoMensagem.IdTipoMensagem = 20 OR
						HistoricoMensagem.IdTipoMensagem = 27 OR
						HistoricoMensagem.IdTipoMensagem = 17 OR
						HistoricoMensagem.IdTipoMensagem = 14
					) AND
					HistoricoMensagem.IdStatus IN (1,3,5) AND						
					DATEDIFF(CURDATE(), HistoricoMensagem.DataCriacao) > 7";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Ultrapassou o prazo de envio (7 dias).";
			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}	
	
		#17 Monitor ########################################################################################################################
		# Regra 1: Se estiver com mais de 2 dias ele cancela o envio
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem
				FROM
					HistoricoMensagem
				WHERE
					HistoricoMensagem.IdTipoMensagem = 17 AND
					HistoricoMensagem.IdStatus IN (1,3,5) AND
					DATEDIFF(CURDATE(), HistoricoMensagem.DataCriacao) > 2";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Ultrapassou o prazo de envio (2 dias).";
			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}
		
		#19 Ordem de Serviço $IdOrdemServico
		# Regra: Somente pode enviar se a ordem de serviço estiver em atendimento ainda
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem
				FROM
					HistoricoMensagem,
					OrdemServico
				WHERE
					HistoricoMensagem.IdTipoMensagem = 19 AND
					HistoricoMensagem.IdStatus IN (1,3,5) AND
					HistoricoMensagem.IdLoja = OrdemServico.IdLoja AND
					HistoricoMensagem.IdOrdemServico = OrdemServico.IdOrdemServico AND
					(
						OrdemServico.IdStatus <= 99 OR
						OrdemServico.IdStatus >= 200
					)";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! O ordem de serviço não está mais em atendimento.";
			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}

		#21 Pagamento efetuado com sucesso
		# Regra: Se cacelar o recebimento ele cancela o envio
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem
				FROM
					HistoricoMensagem,
					ContaReceber
				WHERE
					HistoricoMensagem.IdTipoMensagem = 21 AND
					HistoricoMensagem.IdStatus IN (1,3,5) AND
					ContaReceber.IdLoja = HistoricoMensagem.IdLoja AND
					ContaReceber.IdContaReceber = HistoricoMensagem.IdContaReceber AND
					ContaReceber.IdStatus != 2";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! Conta a Receber não está mais quitado.";
			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}
		
		#22 Feliz Aniversário!
		# Regra: Somente pode enviar até a data do aniversário. Após esta data não envie mais.
		$sql = "SELECT
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdHistoricoMensagem
				FROM
					HistoricoMensagem
				WHERE
					HistoricoMensagem.IdTipoMensagem = 22 AND
					HistoricoMensagem.IdStatus IN (1,3,5) AND						
					HistoricoMensagem.DataCriacao <= CONCAT(CURDATE(),' ','23:59:59')";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! A data do aniversário já passou.";
			$sql = "UPDATE HistoricoMensagem SET
							IdStatus = '6',
							Obs = concat('$Obs','\n',Obs)
						WHERE 
							IdLoja='$lin[IdLoja]' AND 
							IdHistoricoMensagem='$lin[IdHistoricoMensagem]'";
			mysql_query($sql,$con);
		}

		if(checaMensagem($IdLoja, $IdHistoricoMensagem)){
			system($PatchPHP." ".$PatchSistema."rotinas/envia_mensagem.php $IdLoja $IdHistoricoMensagem > ".$PatchSistema."rotinas/envia_mensagem.log &");
		}
	}

	function checaMensagem($IdLoja, $IdHistoricoMensagem){

		global $con;		

		$sql = "select
					HistoricoMensagem.IdLoja,
					HistoricoMensagem.IdContaReceber,
					HistoricoMensagem.IdStatus
				from
					HistoricoMensagem
				where
					HistoricoMensagem.IdLoja = $IdLoja and
					HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem";
		$res = mysql_query($sql,$con);
		$linHistoricoMensagem = mysql_fetch_array($res);

		# Se já foi enviada, cancele o envio
		if($linHistoricoMensagem[IdStatus] == 2){	return false;	}

		return true;
	}

	function enviaEmail($IdLoja, $IdHistoricoMensagem){
		global $con, $Path;
	
		$sql = "update HistoricoMensagem set 
					IdStatus = 5,
					QtdTentativaEnvio = (QtdTentativaEnvio + 1)
				where
					IdLoja = $IdLoja and
					IdHistoricoMensagem = $IdHistoricoMensagem";
		mysql_query($sql,$con);
		
		$UrlSistema = getParametroSistema(6,3);
		$UrlCDA 	= $UrlSistema;			
		$UrlCDA		.= '/central'; 

		$sql = "select
					Pessoa.Nome,
					Pessoa.Site,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa	
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);

		if($linEmpresa[Site] == '' || trim($linEmpresa[Site]) == 'http://'){
			$linEmpresa[Site] = "#";
		}
		
		$sqlEmailCCo = "select 
						  ValorCodigoInterno 
						from
						  CodigoInterno 
						where IdLoja = $IdLoja 
						  and IdGrupoCodigoInterno = 38 
						  and IdCodigoInterno = 5";
		$resEmailCCo = mysql_query($sqlEmailCCo,$con);
		$linEmailCCo = mysql_fetch_array($resEmailCCo);
		
		$sql = "select
					HistoricoMensagem.Conteudo,					
					HistoricoMensagem.Email,
					HistoricoMensagem.MD5,
					HistoricoMensagem.IdPessoa,
					HistoricoMensagem.Assunto,
					HistoricoMensagem.Titulo,
					HistoricoMensagem.IdContaReceber,
					HistoricoMensagem.IdReEnvio,
					TipoMensagem.IdContaEmail,										
					TipoMensagem.IdTipoMensagem,
					TipoMensagem.Assinatura,					
					TemplateMensagem.Estrutura,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Nome				
				from
					HistoricoMensagem left join Pessoa on (HistoricoMensagem.IdPessoa = Pessoa.IdPessoa),
					TipoMensagem,
					TemplateMensagem
				where
					HistoricoMensagem.IdLoja = $IdLoja and
					HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and
					HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem and					
					TipoMensagem.IdLoja = TemplateMensagem.IdLoja and
					TipoMensagem.IdTemplate = TemplateMensagem.IdTemplate and
					HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem and
					HistoricoMensagem.IdStatus = 5";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);	

		$ParametroTipoMensagem = parametroTipoMensagem($IdLoja, $lin[IdTipoMensagem]);
		
		$_UrlLogo = $UrlSistema."/img/personalizacao/logo_cab.gif";

		if($lin[IdContaReceber] != ""){
			$sql = "select
						LocalCobranca.IdLocalCobranca,
						LocalCobranca.ExtLogo
					from
						ContaReceber,
						LocalCobranca
					where
						ContaReceber.IdLoja = $IdLoja and
						ContaReceber.IdLoja = LocalCobranca.IdLoja and
						ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
						ContaReceber.IdContaReceber = $lin[IdContaReceber]";
			$resLocalCobranca = mysql_query($sql,$con);
			$linLocalCobranca = mysql_fetch_array($resLocalCobranca);
			
			if($linLocalCobranca[ExtLogo] != ''){
				$_UrlLogo = $UrlSistema."/modulos/administrativo/local_cobranca/personalizacao/".$IdLoja."/".$linLocalCobranca[IdLocalCobranca].".".$linLocalCobranca[ExtLogo];
			}
		}
				
		$sql = "select
					IdContaEmail,
					DescricaoContaEmail,
					NomeRemetente,
					EmailRemetente,
					ServidorSMTP,
					Porta,
					RequerAutenticacao,
					Usuario,
					Senha,
					NomeResposta,
					EmailResposta,
					IntervaloEnvio,
					QtdTentativaEnvio,
					SMTPSeguro
				from
					ContaEmail
				where
					IdLoja = $IdLoja and
					IdContaEmail = $lin[IdContaEmail]";
		$resContaEmail = mysql_query($sql,$con);
		$linContaEmail = mysql_fetch_array($resContaEmail);
		
		if($linContaEmail[Usuario] == '' || $linContaEmail[Senha] == '' || $linContaEmail[EmailRemetente] == '' || $linContaEmail[ServidorSMTP] == ''){
			return false;
		}
		
		$AltBody = 	"Atenção, ative a visualização de HTML ou clique no link para visualizar o e-mail. Visualizar e-mail: $UrlSistema/visualizar_email.php?mensagem=$lin[MD5]";
		
		$Conteudo = $lin[Estrutura];

		$Conteudo = str_replace('$_Conteudo',$lin[Conteudo],$Conteudo);
		$Conteudo = str_replace('$_TituloMensagem',$lin[Titulo],$Conteudo);
		$Conteudo = str_replace('$_Assinatura',$lin[Assinatura],$Conteudo);

		if($lin[IdTipoMensagem] == 7){
			if($linEmpresa[TipoPessoa] == 1){
				$linEmpresa[NomeCliente] = $linEmpresa[NomeRepresentante]." (".$linEmpresa[Nome].")";
			}else{
				$linEmpresa[NomeCliente] = $linEmpresa[Nome];
			}

			$Conteudo = str_replace('$_NomeCliente',$linEmpresa[NomeCliente],$Conteudo);
			$Conteudo = str_replace('$_TituloPersonalizado',$ParametroTipoMensagem['$_TituloPersonalizado'],$Conteudo);
			$Conteudo = str_replace('$_ClienteNomeEmpresa',$linEmpresa[Nome],$Conteudo);		
		}else{
			if($lin[TipoPessoa] == 1){
				$lin[NomeCliente] = $lin[NomeRepresentante]." (".$lin[Nome].")";
			}else{
				$lin[NomeCliente] = $lin[Nome];
			}

			$Conteudo = str_replace('$_NomeCliente',$lin[NomeCliente],$Conteudo);
			$Conteudo = str_replace('$_TituloPersonalizado',$ParametroTipoMensagem['$_TituloPersonalizado'],$Conteudo);
			$Conteudo = str_replace('$_ClienteNomeEmpresa',$lin[Nome],$Conteudo);		
		}

		$Conteudo = str_replace('$_SiteEmpresa',$linEmpresa[Site],$Conteudo);
		$Conteudo = str_replace('$_UrlCDA',$UrlCDA,$Conteudo);
		$Conteudo = str_replace('$_UrlLogo',$_UrlLogo,$Conteudo);

		$sqlParametroMensagem = "select
									IdTipoMensagemParametro,
									ValorTipoMensagemParametro
								from
									TipoMensagemParametro
								where
									IdLoja = $IdLoja and
									IdTipoMensagem = $lin[IdTipoMensagem]";
		$resParametroMensagem = mysql_query($sqlParametroMensagem,$con);
		while($linParametroMensagem = mysql_fetch_array($resParametroMensagem)){
			$Conteudo = str_replace($linParametroMensagem[IdTipoMensagemParametro],$linParametroMensagem[ValorTipoMensagemParametro],$Conteudo);
		}
		
		$Conteudo = str_replace('$_UrlSistema',$UrlSistema,$Conteudo);

		$Conteudo .= "\n<div style='text-align:center; color: #C0C0C0; font: normal 10px Verdana, Arial, Times; border-top: 1px #C0C0C0 solid; margin-top: 10px; padding-top: 5px; '><a href='http://www.cntsistemas.com.br' style='color: #C0C0C0; text-decoration: none;' target='_blank'>ConAdmin - Sistemas Adminstrativos de Qualidade - CNTSistemas</a></font><img src='$UrlSistema/classes/envia_mensagem/logo_mensagem.php?Mensagem=$lin[MD5]&Imagem=$UrlSistema/img/estrutura_sistema/px_transparente.gif' />";
		
		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP

		$mail->Priority		= 3;
		$mail->Encoding		= '8bit';
		$mail->CharSet		= 'iso-8859-1';
		$mail->AltBody		= $AltBody;
		$mail->WordWrap		= 0;
		$mail->Helo			= $linContaEmail[ServidorSMTP];		
		$mail->PluginDir	= $INCLUDE_DIR;
		$mail->Mailer		= 'smtp';
		$mail->TimeOut		= 10;	
		$mail->From			= $linContaEmail[EmailRemetente];
		$mail->FromName 	= $linContaEmail[NomeRemetente];
		$mail->Subject		= $lin[Assunto]; 
		$mail->Host			= $linContaEmail[ServidorSMTP];
		$mail->Port			= $linContaEmail[Porta];
		$mail->SMTPAuth 	= true;		
		
		switch($linContaEmail[SMTPSeguro]){
			case 1:
				$mail->SMTPSecure 	= "ssl";
				break;

			case 2:
				$mail->SMTPSecure 	= "tls";
				break;
		}		

		$mail->Username 	= $linContaEmail[Usuario];		
		$mail->Password 	= $linContaEmail[Senha];		
		$mail->Sender		= $linContaEmail[EmailRemetente];
		$mail->Body			= $Conteudo;
		
		$mail->IsHTML(true); 
		$mail->AddReplyTo($linContaEmail[EmailResposta], $linContaEmail[NomeRemetente]);

		if($lin[IdReEnvio] == ""){
			$IdHistoricoMensagemAnexo = $IdHistoricoMensagem;			
		}else{
			$IdHistoricoMensagemAnexo = buscaIdHistoricoMensagemAnexo($IdLoja, $lin[IdReEnvio]);
		}		
		
		if($IdHistoricoMensagemAnexo == ""){
			$IdHistoricoMensagemAnexo = $IdHistoricoMensagem;
		}

		$sql = "select					
					IdAnexo,
					NomeOriginal
				from	
					HistoricoMensagemAnexo
				where
					IdLoja = $IdLoja and
					IdHistoricoMensagem = $IdHistoricoMensagemAnexo";
		$resHistoricoMensagemAnexo = mysql_query($sql,$con);
		while($linHistoricoMensagemAnexo = mysql_fetch_array($resHistoricoMensagemAnexo)){			
			$AnexoDirPath = $Path."modulos/administrativo/anexos/mensagem/".$IdLoja."/".$IdHistoricoMensagemAnexo."/".$linHistoricoMensagemAnexo[IdAnexo];			
			$mail->AddAttachment($AnexoDirPath,$linHistoricoMensagemAnexo[NomeOriginal]);			
		}
		if($lin[IdTipoMensagem] == "20"){
			$emailCCo = $linEmailCCo[ValorCodigoInterno];
			$AddBCC = explode(";",$emailCCo);

			for($i=0; $i<count($AddBCC); $i++){
				$AddBCC[$i] = trim($AddBCC[$i]);

				$com = 'ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüç';
				$sem   = 'AAAAEEIOOOUUCaaaaeeiooouuc';
				$AddBCC[$i] = strtr($AddBCC[$i], $com, $sem);

				if($AddBCC[$i] != ''){
					$mail->AddBCC($AddBCC[$i],$AddBCC[$i]);
				}
			}
		}
		
		$AddAddress = explode(";",$lin[Email]);

		for($i=0; $i<count($AddAddress); $i++){
			$AddAddress[$i] = trim($AddAddress[$i]);

			$com 	= 'ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüç';
			$sem   	= 'AAAAEEIOOOUUCaaaaeeiooouuc';
			$AddAddress[$i] = strtr($AddAddress[$i], $com, $sem);

			if($AddAddress[$i] != ''){
				$mail->AddAddress($AddAddress[$i],$AddAddress[$i]);
			}
		}
		
		// Tentativcas de Envio
		for($i=1; $i<=$linContaEmail[QtdTentativaEnvio]; $i++){	
			if(@$mail->Send() == true){
				$IdStatus = 2;
				break;
			}else{
				$EnvioCancelado = false;
				$IdStatus = 3;

				if(stripos($mail->ErrorInfo,"The following recipients failed")){							$EnvioCancelado = true;	}
				if($mail->ErrorInfo == "You must provide at least one recipient email address."){			$EnvioCancelado = true;	}

				if($EnvioCancelado == true){

					$Obs = date("d/m/Y H:i:s")." [automatico] - Envio cancelado! E-mail recusado!";

					$sql = "UPDATE HistoricoMensagem SET
								IdStatus = '6',
								Obs = concat('$Obs','\n',Obs)
							WHERE 
								IdLoja='$IdLoja' AND 
								IdHistoricoMensagem='$IdHistoricoMensagem'";
					mysql_query($sql,$con);
					break;

				}else{
					sleep($linContaEmail[IntervaloEnvio]);
				}
			}
		}
		
		if($lin[IdTipoMensagem] =="20"){
			if($linEmailCCo[ValorCodigoInterno] !=""){
				$email = $lin[Email].";".$linEmailCCo[ValorCodigoInterno];
			}else{
				$email = $lin[Email];
			}
		}else{
			$email = $lin[Email];
		}
		
		if($IdStatus == 2){
			$sql = "update HistoricoMensagem set
						Email 					= '$email',
						IdStatus				= 2,						
						DataEnvio				= (concat(curdate(),' ',curtime()))
					where
						IdLoja = $IdLoja and
						IdHistoricoMensagem = $IdHistoricoMensagem";
			mysql_query($sql,$con);			

			return true;
		}else{
			return false;
		}
	}

	function enviaSMS($IdLoja, $IdHistoricoMensagem){
		
		global $con, $Path;

		$sql = "SELECT
					HistoricoMensagem.Conteudo,
					HistoricoMensagem.Celular,
					ContaSMS.IdContaSMS,
					ContaSMS.IdOperadora
				FROM
					HistoricoMensagem,
					TipoMensagem,
					ContaSMS
				WHERE
					HistoricoMensagem.IdLoja = $IdLoja AND
					HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem AND
					HistoricoMensagem.IdLoja = TipoMensagem.IdLoja AND
					HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem AND
					TipoMensagem.IdLoja = ContaSMS.IdLoja";
		$res = mysql_query($sql,$con);
		$linSMS = mysql_fetch_array($res);

		$sql = "SELECT
					IdParametroOperadoraSMS,
					ValorParametroSMS
				FROM
					ContaSMSParametro
				WHERE
					IdLoja = $IdLoja AND
					IdContaSMS = $linSMS[IdContaSMS]";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$varsSMS[$lin[IdParametroOperadoraSMS]] = $lin[ValorParametroSMS];
		}

		include($Path."classes/envia_mensagem/sms/$linSMS[IdOperadora]/envia.php");

		$sql = "UPDATE HistoricoMensagem SET
					IdStatus = '2',
					DataEnvio = concat(curdate(),' ',curtime())
				WHERE 
					IdLoja='$IdLoja' AND 
					IdHistoricoMensagem='$IdHistoricoMensagem'";
		mysql_query($sql,$con);
	}
	
	function enviaTesteContaSMS($IdLoja, $IdContaSMS, $Celular, $IdOperadora, $Identificador){
		
		global $con, $Path;
		
		$sqlEmpresa = "	select Nome from Pessoa	where IdPessoa = 1";
		$resEmpresa = mysql_query($sqlEmpresa,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);
		
		$sql = "SELECT
					IdParametroOperadoraSMS,
					ValorParametroSMS
				FROM
					ContaSMSParametro
				WHERE
					IdLoja = $IdLoja AND
					IdContaSMS = $IdContaSMS";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$varsSMS[$lin[IdParametroOperadoraSMS]] = $lin[ValorParametroSMS];
		}
		
		if($Celular == ''){
			$Celular = 'NULL'; 
		}else{
			
			// Isso vai ser removido futuramente.
			$Celular = str_replace("-","",$Celular);
			$Celular = str_replace(" ","",$Celular);
			$Celular = str_replace("(","",$Celular);
			$Celular = str_replace(")","",$Celular);
			$Celular = trim($Celular);

			if(strlen($Celular) == 10 || strlen($Celular) == 11){
				$Celular = "55".$Celular;
			}
		}
		
		$linSMS[Celular] 	 = $Celular;//Destinatário
		$linSMS[Conteudo]	 = "Teste de envio de SMS(Conta: $IdContaSMS) - ".$linEmpresa[Nome];//Mensagem a ser enviada...!
		$linSMS[IdOperadora] = $IdOperadora;//Operadora API
		
		if($Identificador !=""){
			$IdHistoricoMensagem  = $Identificador;
		}else{
			$IdHistoricoMensagem = "1";
		}
		
		include($Path."classes/envia_mensagem/sms/$linSMS[IdOperadora]/envia.php");
		
		if($linSMS[IdOperadora] == 2){
			if($errorCode == 00){
				return 186;//enviado com sucesso
			} else{
				return 185;//erro, verifique a conta
			}
		}else{
			if($errorCode == 000){
				return 186;
			} else{
				return 185;
			}
		}
	}
	
	function geraMensagem($Vars){
		
		global $con, $Path;
		global $Login;
			
		if($Vars[IdContaReceber] == ''){		$Vars[IdContaReceber] = 'NULL'; }	
		if($Vars[IdProcessoFinanceiro] == ''){	$Vars[IdProcessoFinanceiro] = 'NULL'; }
		if($Vars[IdOrdemServico] == ''){		$Vars[IdOrdemServico] = 'NULL'; }
		if($Vars[IdMalaDireta] == ''){			$Vars[IdMalaDireta] = 'NULL'; }
		if($Vars[IdReEnvio] == ''){				$Vars[IdReEnvio] = 'NULL'; }	
		if($Vars[IdPessoa] == ''){				$Vars[IdPessoa] = 'NULL'; }
		if($Vars[IdContrato] == ''){			$Vars[IdContrato] = 'NULL'; }
		if($Vars[IdMonitor] == ''){				$Vars[IdMonitor] = 'NULL'; }
		if($Vars[IdTicket] == ''){				$Vars[IdTicket] = 'NULL'; }
		if($Vars[Email] == ''){					$Vars[Email] = 'NULL'; }
		if($Vars[Login] == ''){					$Vars[Login] = $Login; }
		
		
		if($Vars[Celular] == ''){
			$Vars[Celular] = 'NULL'; 
		}else{
			
			// Isso vai ser removido futuramente.
			$Vars[Celular] = str_replace("-","",$Vars[Celular]);
			$Vars[Celular] = str_replace(" ","",$Vars[Celular]);
			$Vars[Celular] = str_replace("(","",$Vars[Celular]);
			$Vars[Celular] = str_replace(")","",$Vars[Celular]);
			$Vars[Celular] = trim($Vars[Celular]);

			if(strlen($Vars[Celular]) == 10 || strlen($Vars[Celular]) == 11){
				$Vars[Celular] = "55".$Vars[Celular];
			}
		}
			
		$sql = "select (max(IdHistoricoMensagem))+1 IdHistoricoMensagem from HistoricoMensagem where IdLoja = $Vars[IdLoja]";		
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdHistoricoMensagem] == NULL){
			$lin[IdHistoricoMensagem] = 1;
		}		

		$sql = "select 
					Titulo,
					Assunto
				from 
					TipoMensagem 
				where 
					IdLoja = $Vars[IdLoja] and
					IdTipoMensagem = $Vars[IdTipoMensagem]";		
		$resTipoMensagem = mysql_query($sql,$con);
		$linTipoMensagem = mysql_fetch_array($resTipoMensagem);

		$Vars[Conteudo] = str_replace('"','\'',$Vars[Conteudo]);

		if($Vars[Assunto] != ""){ $linTipoMensagem[Assunto] = $Vars[Assunto]; }
		if($Vars[Titulo] != ""){ $linTipoMensagem[Titulo] = $Vars[Titulo]; }

		$sql = "insert into HistoricoMensagem set
					IdLoja					= $Vars[IdLoja],
					IdTicket				= $Vars[IdTicket],
					IdHistoricoMensagem		= $lin[IdHistoricoMensagem],
					IdTipoMensagem			= $Vars[IdTipoMensagem],
					Email					= '$Vars[Email]',
					Celular					= '$Vars[Celular]',
					Assunto					= '$linTipoMensagem[Assunto]',
					Titulo					= \"$linTipoMensagem[Titulo]\",
					Conteudo				= \"$Vars[Conteudo]\",
					IdPessoa				= $Vars[IdPessoa],
					IdContrato				= $Vars[IdContrato],
					IdContaReceber			= $Vars[IdContaReceber],
					IdProcessoFinanceiro	= $Vars[IdProcessoFinanceiro],
					IdMalaDireta			= $Vars[IdMalaDireta],
					IdOrdemServico			= $Vars[IdOrdemServico],
					IdMonitor				= $Vars[IdMonitor],
					IdReEnvio				= $Vars[IdReEnvio],
					IdStatus				= 1,
					MD5						= md5(concat($Vars[IdLoja],$lin[IdHistoricoMensagem])),
					LoginCriacao			= '$Vars[Login]',
					DataCriacao				= (concat(curdate(),' ',curtime()))";

		if(mysql_query($sql,$con)){				
			#$IdAnexo = array_keys($Vars[Anexo]);

			for($i=0; $i<count($Vars[Anexo]); $i++){				
				$sql = "select (max(IdAnexo))+1 IdAnexo from HistoricoMensagemAnexo where IdLoja = $Vars[IdLoja] and IdHistoricoMensagem = $lin[IdHistoricoMensagem]";		
				$resAnexo = mysql_query($sql,$con);
				$linAnexo = mysql_fetch_array($resAnexo);
				
				if($linAnexo[IdAnexo] == NULL){
					$linAnexo[IdAnexo] = 1;
				}	
				
				$NomeOriginal = endArray(explode($Vars[LocalAnexo]."/",$Vars[Anexo][$i]));
				$NomeOriginal = $NomeOriginal;

				$sql = "insert into HistoricoMensagemAnexo set
							IdLoja					= $Vars[IdLoja],
							IdAnexo					= $linAnexo[IdAnexo],
							IdHistoricoMensagem		= $lin[IdHistoricoMensagem],
							NomeOriginal			= '$NomeOriginal'";			
				mysql_query($sql,$con);	
				
				$TempPath = "modulos/administrativo/anexos/mensagem/".$Vars[IdLoja];				
				@mkdir($Path.$TempPath, 0770);
			
				$TempPath .= "/".$lin[IdHistoricoMensagem];				
				@mkdir($Path.$TempPath, 0770);

				$FileName = $Path.$TempPath."/".$linAnexo[IdAnexo];				
				$FileTemp = $Vars[Anexo][$i];			

				@copy($FileTemp, $FileName);
			}
			if($Vars[IdTipoMensagem] == '32'){
				return enviaSMS($Vars[IdLoja], $lin[IdHistoricoMensagem]);
			}
			if($Vars[EnviarMensagem]){
				return enviaMensagem($Vars[IdLoja], $lin[IdHistoricoMensagem]);		
			}
			return $lin[IdHistoricoMensagem];
		}else{
			return false;
		}
	}

	function enviaContaReceber($IdLoja, $IdContaReceber){
		global $con;
		global $local_Email;
		global $local_Login;

		$url_sistema	= getParametroSistema(6,3);
		$url_sistema	.= '/central';
		$dataAtual		= date("Ymd");

		$IdTipoMensagem = 1;//Boleto Disponível para Impressão

		$ParametroTipoMensagem = parametroTipoMensagem($IdLoja, $IdTipoMensagem);

		$sql = "select
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);		

		$sql = "select
					TipoMensagem.Assunto,
					TipoMensagem.Conteudo,
					TipoMensagem.IdStatus,
					ContaEmail.DescricaoContaEmail
				from
					TipoMensagem,
					ContaEmail
				where
					TipoMensagem.IdLoja = $IdLoja and
					TipoMensagem.IdLoja = ContaEmail.IdLoja and
					TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
					TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resTipoMensagem = mysql_query($sql,$con);
		$linTipoMensagem = mysql_fetch_array($resTipoMensagem);

		if($linTipoMensagem[IdStatus] != 1){
			return false;
		}

		$Conteudo =  $linTipoMensagem[Conteudo];

		$sql = "select 
					ContaReceberDados.IdContaReceber, 
					ContaReceberDados.DataVencimento, 
					ContaReceberDados.ValorLancamento, 
					ContaReceberDados.ValorDespesas, 
					ContaReceberDados.NumeroDocumento, 
					ContaReceberDados.IdLocalCobranca,
					ContaReceberDados.IdStatus,					
					ContaReceberDados.IdPessoa, 
					ContaReceberDados.MD5,
					Pessoa.TipoPessoa, 
					Pessoa.Nome, 
					Pessoa.NomeRepresentante, 
					PessoaEndereco.NomeResponsavelEndereco NomeResponsavel, 
					PessoaEndereco.Endereco, 
					PessoaEndereco.Numero, 
					PessoaEndereco.CEP, 
					PessoaEndereco.Complemento, 
					Pessoa.Email,
					PessoaEndereco.EmailEndereco, 
					Estado.SiglaEstado, 
					Cidade.NomeCidade, 
					LocalCobranca.DescricaoLocalCobranca, 
					LocalCobranca.IdLocalCobrancaLayout,
					LocalCobranca.DiasCompensacao
				from 
					ContaReceberDados,
					Pessoa,
					PessoaEndereco,
					Estado,
					Cidade,
					LocalCobranca
				where 
					ContaReceberDados.IdLoja = $IdLoja and 
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and 
					ContaReceberDados.IdContaReceber = $IdContaReceber and
					ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
					ContaReceberDados.IdPessoa = PessoaEndereco.IdPessoa and
					ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
					PessoaEndereco.IdPais = Estado.IdPais and
					PessoaEndereco.IdPais = Cidade.IdPais and
					PessoaEndereco.IdEstado = Estado.IdEstado and
					PessoaEndereco.IdEstado = Cidade.IdEstado and
					PessoaEndereco.IdCidade = Cidade.IdCidade and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					LocalCobranca.IdTipoLocalCobranca != 3";//Nao manda para CR com LC do tipo 3(Debito em conta)
		$res  = mysql_query($sql,$con);		
		$nReg = mysql_num_rows($res);
		if($Sacado = mysql_fetch_array($res)){

			if($Sacado[EmailEndereco] != ''){
				if(trim($Sacado[Email]) != ''){
					$Sacado[Email] .= ";".trim($Sacado[EmailEndereco]);
				}else{
					$Sacado[Email] = $Sacado[EmailEndereco];
				}
			}

			$Tipo = strtolower(getCodigoInterno(3,165));
			$aux 		.= '$_UrlSistema/modulos/administrativo/boleto.php';
			$LinkBoleto .= '$_UrlSistema/modulos/cda/aviso_titulo_vencido.php?LinkBoleto='.$aux.'&Tipo='.$Tipo.'&ContaReceber='.$Sacado[MD5]."&IdContaReceber=".$Sacado[IdContaReceber]."&IdLoja=".$IdLoja;
			
			if($IdProcessoFinanceiro != ''){
				$Sacado[IdProcessoFinanceiro] = $IdProcessoFinanceiro;
			}else{
				$Sacado[IdProcessoFinanceiro] = 'NULL';
			}

			// Nome
			if($Sacado[Cob_NomeResponsavel] != ''){
				$Sacado[NomeResponsavel] = $Sacado[Cob_NomeResponsavel];
			}else{
				if($Sacado[TipoPessoa] == 1){
					$Sacado[NomeResponsavel] = $Sacado[NomeRepresentante];
				}else{
					$Sacado[NomeResponsavel] = $Sacado[Nome];
				}
			}
	
			// DataVencimento
			$Sacado[DataVencimento] = dataConv($Sacado[DataVencimento],'Y-m-d','d/m/Y');
	
			// Dados do Sacado
			if($Sacado[Complemento] != ''){
				$Sacado[Complemento] = " - ".$Sacado[Complemento];
			}
			$Sacado[DadosSacado]	= $Sacado[Nome]."<br>".$Sacado[Endereco].", ".$Sacado[Numero].$Sacado[Complemento]."<br>".$Sacado[NomeCidade]."-".$Sacado[SiglaEstado]." - CEP: ".$Sacado[CEP];
						
			if($local_Email != ''){
				$Sacado[Email] = $local_Email;
			}

			// Varíáveis de Substituição Dinamicas
			$ValorFinal			= $Sacado[ValorLancamento]+$Sacado[ValorDespesas];
			
			$valorTotal					= 0;
			$i							= 0;
			$DadosLancamento[$i][Valor]	= 0;
			$sql = "select
						Tipo,
						Codigo,
						Descricao,
						Referencia,
						Valor,
						ValorDespesas
					from
						Demonstrativo
					where
						IdLoja = $IdLoja and
						IdContaReceber = $IdContaReceber
					order by
						Tipo,
						Codigo,
						IdLancamentoFinanceiro";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
			
				$valorTotal += $lin[Valor];

				$DadosLancamento[$i][Tipo]			= $lin[Tipo];
				$DadosLancamento[$i][Cod]			= $lin[Codigo];
				$DadosLancamento[$i][Descricao]		= $lin[Descricao];
				$DadosLancamento[$i][Referencia]	= $lin[Referencia];
				$DadosLancamento[$i][Valor]			= $lin[Valor];
				$i++;
			}
			$Demonstrativo .= 
				"<table style='width: 100%; font-size: 11px'>
    				<tr style='background-color: ".$ParametroTipoMensagem['$_ColorBackgroundTituloMensagem']."; color: ".$ParametroTipoMensagem['$_ColorTituloMensagem']."'>
						<td>Tipo</td>
						<td>Cod.</td>
						<td>Descrição</td>
						<td>Referência</td>
						<td>Valor (R$)</td>
					</tr>";
			for($ii = 0; $ii <=count($DadosLancamento); $ii++){

				if($DadosLancamento[$ii][Tipo] != ''){
					$Tipo		= $DadosLancamento[$ii][Tipo];
					$Cod		= $DadosLancamento[$ii][Cod];
					$Descricao	= $DadosLancamento[$ii][Descricao];
					$Referencia	= $DadosLancamento[$ii][Referencia];
					$Valor		= number_format($DadosLancamento[$ii][Valor],2,',','');

					$Demonstrativo .= "
						<tr>
							<td>$Tipo</td>
							<td>$Cod</td>
							<td>$Descricao</td>
							<td style='text-align:center'>$Referencia</td>
							<td style='text-align:right'>$Valor</td>
						</tr>
					";
					$i++;
				}
			}
	
			if($Sacado[ValorDespesas] > 0){			
				
				$Sacado[ValorDespesas]	= number_format($Sacado[ValorDespesas],2,',','');
	
				$Demonstrativo .= "<tr>			
					<td />
					<td />
					<td>Despesas boleto</td>
					<td />
					<td style='text-align:right;'>$Sacado[ValorDespesas]</td>
				</tr>";
			}			
			$ValorTotal = number_format($ValorFinal,2,',','');
	
			$Demonstrativo .=
				"<tr style='background-color: ".$ParametroTipoMensagem['$_ColorBackgroundTituloMensagem']."; color: ".$ParametroTipoMensagem['$_ColorTituloMensagem']."'>
					<td />
					<td />
					<td />
					<td>Total</td>
					<td style='text-align:right;'>$ValorTotal</td>
				</tr>
			</table>";
			
			$Conteudo = str_replace('$_DataVencimento',$Sacado[DataVencimento],$Conteudo);
			$Conteudo = str_replace('$_DadosSacado',$Sacado[DadosSacado],$Conteudo);
			$Conteudo = str_replace('$_LinkBoleto',$LinkBoleto,$Conteudo);
			$Conteudo = str_replace('$_ValorFinal',$ValorTotal,$Conteudo);
			$Conteudo = str_replace('$_NumeroDocumento',$Sacado[NumeroDocumento],$Conteudo);
			$Conteudo = str_replace('$_TabelaDemonstrativo',$Demonstrativo,$Conteudo);
			$Conteudo = str_replace('$_QtdDiaCompensacao',$Sacado[DiasCompensacao],$Conteudo);	
			$Conteudo = str_replace('$EndCDA',$url_sistema,$Conteudo);
			$Conteudo = str_replace('$DescricaoContaEmail',$linTipoMensagem[DescricaoContaEmail],$Conteudo);			
			
			$linTipoMensagem[Assunto]	= str_replace('$DataVencimento',$Sacado[DataVencimento],$linTipoMensagem[Assunto]);
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$linTipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$linTipoMensagem[Assunto]);
			}else{
				$linTipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$linTipoMensagem[Assunto]);
			}

			$GeraMensagem[IdLoja]			= $IdLoja;
			$GeraMensagem[IdTipoMensagem]	= $IdTipoMensagem;
			$GeraMensagem[Email]			= $Sacado[Email];
			$GeraMensagem[Conteudo]			= $Conteudo;
			$GeraMensagem[Assunto]			= $linTipoMensagem[Assunto];
			$GeraMensagem[IdPessoa]			= $Sacado[IdPessoa];
			$GeraMensagem[IdContaReceber]	= $Sacado[IdContaReceber];
			$GeraMensagem[Login]			= $local_Login;				

			return geraMensagem($GeraMensagem);
		}				
	}
	
	function enviaGeraBoleto($IdLoja, $IdContaReceber){
		global $con;
		global $local_Email;

		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';
		$dataAtual				= date("Ymd");
		$IdTipoMensagem = 2;//Aviso de Geração de Boletos

		$ParametroTipoMensagem = parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		
		$sql = "select
					TipoMensagem.Conteudo,
					TipoMensagem.IdStatus,
					ContaEmail.DescricaoContaEmail
				from
					TipoMensagem,
					ContaEmail
				where
					TipoMensagem.IdLoja = $IdLoja and
					TipoMensagem.IdLoja = ContaEmail.IdLoja and
					TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
					TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resTipoMensagem = mysql_query($sql,$con);
		$linTipoMensagem = mysql_fetch_array($resTipoMensagem);

		if($linTipoMensagem[IdStatus] != 1){
			return false;
		}

		$Conteudo =  $linTipoMensagem[Conteudo];

		$sql = "select 
					ContaReceberDados.IdContaReceber, 
					ContaReceberDados.DataVencimento, 
					ContaReceberDados.ValorLancamento, 
					ContaReceberDados.ValorDespesas, 
					ContaReceberDados.NumeroDocumento, 
					ContaReceberDados.IdLocalCobranca, 
					ContaReceberDados.IdPessoa, 
					ContaReceberDados.MD5,
					Pessoa.TipoPessoa, 
					Pessoa.Nome, 
					Pessoa.NomeRepresentante, 
					PessoaEndereco.NomeResponsavelEndereco NomeResponsavel, 
					PessoaEndereco.Endereco, 
					PessoaEndereco.Numero, 
					PessoaEndereco.CEP, 
					PessoaEndereco.Complemento, 
					Pessoa.Email,
					PessoaEndereco.EmailEndereco, 
					Estado.SiglaEstado, 
					Cidade.NomeCidade, 
					LocalCobranca.DescricaoLocalCobranca, 
					LocalCobranca.IdLocalCobrancaLayout,
					LocalCobranca.DiasCompensacao
				from 
					ContaReceberDados,
					Pessoa,
					PessoaEndereco,
					Estado,
					Cidade,
					LocalCobranca
				where 
					ContaReceberDados.IdLoja = $IdLoja and 
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and 
					ContaReceberDados.IdContaReceber = $IdContaReceber and
					ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
					ContaReceberDados.IdPessoa = PessoaEndereco.IdPessoa and
					ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
					ContaReceberDados.IdStatus = 1 and
					PessoaEndereco.IdPais = Estado.IdPais and
					PessoaEndereco.IdPais = Cidade.IdPais and
					PessoaEndereco.IdEstado = Estado.IdEstado and
					PessoaEndereco.IdEstado = Cidade.IdEstado and
					PessoaEndereco.IdCidade = Cidade.IdCidade and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					LocalCobranca.IdTipoLocalCobranca != 3";
		$res  = mysql_query($sql,$con);				
		if($Sacado = mysql_fetch_array($res)){
			$Demonstrativo = '';
			
			$sql = "select 					
						Demonstrativo.IdProcessoFinanceiro
					from 					
						Demonstrativo
					where 
						Demonstrativo.IdLoja = $IdLoja and 					
						Demonstrativo.IdContaReceber = $Sacado[IdContaReceber]";
			$resDemonstrativo  = mysql_query($sql,$con);				
			$linDemonstrativo  = mysql_fetch_array($resDemonstrativo);

			if($Sacado[EmailEndereco] != ''){
				if(trim($Sacado[Email]) != ''){
					$Sacado[Email] .= ";".trim($Sacado[EmailEndereco]);
				}else{
					$Sacado[Email] = $Sacado[EmailEndereco];
				}
			}
			
			$Tipo = strtolower(getCodigoInterno(3,165));
			$aux 		.= '$_UrlSistema/modulos/administrativo/boleto.php';
			$LinkBoleto .= '$_UrlSistema/modulos/cda/aviso_titulo_vencido.php?LinkBoleto='.$aux.'&Tipo='.$Tipo.'&ContaReceber='.$Sacado[MD5]."&IdContaReceber=".$Sacado[IdContaReceber]."&IdLoja=".$IdLoja;  
			
			// Nome
			if($Sacado[Cob_NomeResponsavel] != ''){
				$Sacado[NomeResponsavel] = $Sacado[Cob_NomeResponsavel];
			}else{
				if($Sacado[TipoPessoa] == 1){
					$Sacado[NomeResponsavel] = $Sacado[NomeRepresentante];
				}else{
					$Sacado[NomeResponsavel] = $Sacado[Nome];
				}
			}
	
			// DataVencimento
			$Sacado[DataVencimento] = dataConv($Sacado[DataVencimento],'Y-m-d','d/m/Y');
	
			// Dados do Sacado
			if($Sacado[Complemento] != ''){
				$Sacado[Complemento] = " - ".$Sacado[Complemento];
			}
			$Sacado[DadosSacado]	= $Sacado[Nome]."<br>".$Sacado[Endereco].", ".$Sacado[Numero].$Sacado[Complemento]."<br>".$Sacado[NomeCidade]."-".$Sacado[SiglaEstado]." - CEP: ".$Sacado[CEP];
						
			if($local_Email != ''){
				$Sacado[Email] = $local_Email;
			}

			// Varíáveis de Substituição Dinamicas
			$ValorFinal			= $Sacado[ValorLancamento]+$Sacado[ValorDespesas];
			
			$valorTotal					= 0;
			$i							= 0;
			$DadosLancamento[$i][Valor]	= 0;
			$sql = "select
						Tipo,
						Codigo,
						Descricao,
						Referencia,
						Valor,
						ValorDespesas
					from
						Demonstrativo
					where
						IdLoja = $IdLoja and
						IdContaReceber = $Sacado[IdContaReceber]
					order by
						Tipo,
						Codigo,
						IdLancamentoFinanceiro";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
			
				$valorTotal += $lin[Valor];

				$DadosLancamento[$i][Tipo]			= $lin[Tipo];
				$DadosLancamento[$i][Cod]			= $lin[Codigo];
				$DadosLancamento[$i][Descricao]		= $lin[Descricao];
				$DadosLancamento[$i][Referencia]	= $lin[Referencia];
				$DadosLancamento[$i][Valor]			= $lin[Valor];
				$i++;
			}
			$Demonstrativo .= 
				"<table style='width: 100%; font-size: 11px'>
					<tr style='background-color: ".$ParametroTipoMensagem['$_ColorBackgroundTituloMensagem']."; color: ".$ParametroTipoMensagem['$_ColorTituloMensagem']."'>
						<td>Tipo</td>
						<td>Cod.</td>
						<td>Descrição</td>
						<td>Referência</td>
						<td>Valor (R$)</td>
					</tr>";
			for($ii = 0; $ii <=count($DadosLancamento); $ii++){

				if($DadosLancamento[$ii][Tipo] != ''){
					$Tipo		= $DadosLancamento[$ii][Tipo];
					$Cod		= $DadosLancamento[$ii][Cod];
					$Descricao	= $DadosLancamento[$ii][Descricao];
					$Referencia	= $DadosLancamento[$ii][Referencia];
					$Valor		= number_format($DadosLancamento[$ii][Valor],2,',','');

					$Demonstrativo .= "
						<tr>
							<td>$Tipo</td>
							<td>$Cod</td>
							<td>$Descricao</td>
							<td style='text-align:center'>$Referencia</td>
							<td style='text-align:right'>$Valor</td>
						</tr>
					";
					$i++;
				}
			}
	
			if($Sacado[ValorDespesas] > 0){			
				
				$Sacado[ValorDespesas]	= number_format($Sacado[ValorDespesas],2,',','');
	
				$Demonstrativo .= "<tr>			
					<td />
					<td />
					<td>Despesas boleto</td>
					<td />
					<td style='text-align:right;'>$Sacado[ValorDespesas]</td>
				</tr>";
			}			
			$ValorTotal = number_format($ValorFinal,2,',','');
	
			$Demonstrativo .=
				"<tr style='background-color: ".$ParametroTipoMensagem['$_ColorBackgroundTituloMensagem']."; color: ".$ParametroTipoMensagem['$_ColorTituloMensagem']."'>
					<td />
					<td />
					<td />
					<td>Total</td>
					<td style='text-align:right;'>$ValorTotal</td>
				</tr>
			</table>";
			
			$Conteudo = str_replace('$_DataVencimento',$Sacado[DataVencimento],$Conteudo);
			$Conteudo = str_replace('$_DadosSacado',$Sacado[DadosSacado],$Conteudo);
			$Conteudo = str_replace('$_LinkBoleto',$LinkBoleto,$Conteudo);
			$Conteudo = str_replace('$_ValorFinal',$ValorTotal,$Conteudo);
			$Conteudo = str_replace('$_NumeroDocumento',$Sacado[NumeroDocumento],$Conteudo);
			$Conteudo = str_replace('$_TabelaDemonstrativo',$Demonstrativo,$Conteudo);
			$Conteudo = str_replace('$_QtdDiaCompensacao',$Sacado[DiasCompensacao],$Conteudo);	
			$Conteudo = str_replace('$EndCDA',$url_sistema,$Conteudo);
			$Conteudo = str_replace('$DescricaoContaEmail',$linTipoMensagem[DescricaoContaEmail],$Conteudo);

			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
			$GeraMensagem[Email]					= $Sacado[Email];			
			$GeraMensagem[Conteudo]					= $Conteudo;
			$GeraMensagem[IdPessoa]					= $Sacado[IdPessoa];
			$GeraMensagem[IdContaReceber]			= $Sacado[IdContaReceber];	
			$GeraMensagem[IdProcessoFinanceiro]		= $linDemonstrativo[IdProcessoFinanceiro];	
			
			return geraMensagem($GeraMensagem);			
		}		
	}

	function enviaLogProcessoFinanceiro($IdLoja, $IdProcessoFinanceiro){

		global $con, $local_Login;

		$url_sistema	= getParametroSistema(6,3);
		$url_sistema	.= '/central';
		
		$IdTipoMensagem				= 3;
		$emailLogProcessoFinanceiro	= getCodigoInterno(38,1);
		$url_sistema				= getParametroSistema(6,3);	
		$ParametroTipoMensagem		= parametroTipoMensagem($IdLoja, $IdTipoMensagem);

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);		

		// Log
		$sql = "select
					LogProcessamento
				from
					ProcessoFinanceiro
				where
					IdLoja = $IdLoja and
					IdProcessoFinanceiro=$IdProcessoFinanceiro";
		$res = mysql_query($sql,$con);
		$ProcessoFinanceiro = mysql_fetch_array($res);

		$sql = "select
					TipoMensagem.Titulo,
					TipoMensagem.Assunto,
					TipoMensagem.Conteudo,
					TipoMensagem.IdStatus,
					ContaEmail.DescricaoContaEmail
				from
					TipoMensagem,
					ContaEmail
				where
					TipoMensagem.IdLoja = $IdLoja and
					TipoMensagem.IdLoja = ContaEmail.IdLoja and
					TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
					TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$res = mysql_query($sql,$con);
		$TipoMensagem = mysql_fetch_array($res);

		if($TipoMensagem[IdStatus] != 1){
			return false;
		}

		$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);
		$ProcessoFinanceiro[LogProcessamento] = str_replace(array("\n", "\r\n"), "<br />", $ProcessoFinanceiro[LogProcessamento]);
		$TipoMensagem[Conteudo]	= str_replace('$log',$ProcessoFinanceiro[LogProcessamento],$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('[erro]','<b>[erro]</b>',$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);		
		
		$TipoMensagem[Assunto]	= str_replace('$IdProcessoFinanceiro',$IdProcessoFinanceiro,$TipoMensagem[Assunto]);

		if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
		}else{
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
		}

		$TipoMensagem[Titulo]	= str_replace('$IdLoja',$IdLoja,$TipoMensagem[Titulo]);
		$TipoMensagem[Titulo]	= str_replace('$IdProcessoFinanceiro',$IdProcessoFinanceiro,$TipoMensagem[Titulo]);
				
		$GeraMensagem[IdLoja]					= $IdLoja;
		$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
		$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
		$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
		$GeraMensagem[Email]					= $emailLogProcessoFinanceiro;			
		$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
		$GeraMensagem[IdPessoa]					= $linEmpresa[IdPessoa];
		$GeraMensagem[IdProcessoFinanceiro]		= $IdProcessoFinanceiro;	
		$GeraMensagem[EnviarMensagem]			= true;		
		
		$IdHistoricoMensagem = geraMensagem($GeraMensagem);	

		if($IdHistoricoMensagem){
			
			$sql = "select
						IdStatus,
						Email
					from
						HistoricoMensagem
					where
						IdLoja = $IdLoja and
						IdHistoricoMensagem = $IdHistoricoMensagem";
			$resHistorico = mysql_query($sql,$con);
			$linHistoricio = mysql_fetch_array($resHistorico);

			switch ($linHistoricio[IdStatus]){
				case 2:
					$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Log enviado para o depto financeiro($emailLogProcessoFinanceiro).";
					break;
				case 3:
					$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - [erro] Log enviado para o depto financeiro($emailLogProcessoFinanceiro)";
					break;
			}
			
			$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
										LogProcessamento=concat('$LogProcessamento','',LogProcessamento)
									  WHERE 
										IdLoja=$IdLoja AND 
										IdProcessoFinanceiro=$IdProcessoFinanceiro";
			mysql_query($sqlProcessoFinanceiro,$con);
		}
	}
	
	function enviaLinkBoleto($IdLoja, $IdProcessoFinanceiro){

		global $con;
		global $TotalPartes;

		$IdTipoMensagem				= 4;
		$emailEnvio					= getCodigoInterno(38,2);
		$url_sistema				= getParametroSistema(6,3);	
		$ParametroTipoMensagem		= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select
					Assunto,
					Conteudo,
					IdStatus
				from
					TipoMensagem
				where
					IdLoja = $IdLoja and
					IdTipoMensagem = 4";
		$res = mysql_query($sql,$con);
		$TipoMensagem = mysql_fetch_array($res);

		if($TipoMensagem[IdStatus] != 1){
			return false;
		}

		$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);
		
		if($TotalPartes > 0){
			for($i=1; $i<=$TotalPartes; $i++){
				$linkTemp = getParametroSistema(6,3)."/temp/Boletos_Loja-".$IdLoja."_ProcessoFinanceiro-".$IdProcessoFinanceiro."_pt$i-$TotalPartes.pdf";
				$linkLocal = "../../../temp/Boletos_Loja-".$IdLoja."_ProcessoFinanceiro-".$IdProcessoFinanceiro."_pt$i-$TotalPartes.pdf";
				$link .= "<a href='$linkTemp'>CLIQUE AQUI PARA FAZER O DOWNLOAD DO ARQUIVO - PARTE $i</a><br>";
			}
		}else{
			$link = getParametroSistema(6,3)."/temp/Boletos_Loja-".$IdLoja."_ProcessoFinanceiro-".$IdProcessoFinanceiro.".pdf";
			$linkLocal = "../../../temp/Boletos_Loja-".$IdLoja."_ProcessoFinanceiro-".$IdProcessoFinanceiro."_pt$i-$TotalPartes.pdf";
			$link = "<a href='$link'>CLIQUE AQUI PARA FAZER O DOWNLOAD DO ARQUIVO</a>";
		}
				
		$TipoMensagem[Conteudo]	= str_replace('$url_sistema',$url_sistema,$TipoMensagem[Conteudo]);		
		$TipoMensagem[Conteudo]	= str_replace('$link',$link,$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('$IdLoja',$IdLoja,$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('$IdProcessoFinanceiro',$IdProcessoFinanceiro,$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);
		
		$TipoMensagem[Assunto]	= str_replace('$IdProcessoFinanceiro',$IdProcessoFinanceiro,$TipoMensagem[Assunto]);	

		if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
		}else{
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
		}

		$sqlLogin = "select LoginProcessamento from ProcessoFinanceiro where IdLoja = $IdLoja and IdProcessoFinanceiro = $IdProcessoFinanceiro";
		$resLogin = mysql_query($sqlLogin,$con);
		$linLogin = mysql_fetch_array($resLogin);

		$GeraMensagem[IdLoja]					= $IdLoja;
		$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
		$GeraMensagem[Email]					= $emailEnvio;			
		$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
		$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
		$GeraMensagem[IdPessoa]					= $linEmpresa[IdPessoa];
		$GeraMensagem[IdProcessoFinanceiro]		= $IdProcessoFinanceiro;	
		$GeraMensagem[Login]					= $linLogin[LoginProcessamento];			
		$GeraMensagem[EnviarMensagem]			= true;		

		$IdHistoricoMensagem = geraMensagem($GeraMensagem);	
		
		$LogProcessamento = date("d/m/Y H:i:s")." [automatico] - Fim de Geração dos boletos para serem enviados ao e-mail: ".getCodigoInterno(38,2);
			
		$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
									LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
								  WHERE 
									IdLoja=$IdLoja AND 
									IdProcessoFinanceiro=$IdProcessoFinanceiro";
		mysql_query($sqlProcessoFinanceiro,$con);
		
		if($IdHistoricoMensagem){
			$LogProcessamento = date("d/m/Y H:i:s")." [automatico] - Boletos enviados para o e-mail: $emailEnvio";
		
			$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
										LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
									  WHERE 
										IdLoja=$IdLoja AND 
										IdProcessoFinanceiro=$IdProcessoFinanceiro";
			mysql_query($sqlProcessoFinanceiro,$con);
		}
		
	}

	function avisoVencimento($IdLoja, $IdContaReceber){
		global $con;
		
		$IdTipoMensagem	= 5;//Aviso de Vencimento
		$local_Login	= 'automatico';
		$dataAtual 		= date("Ymd");
		$url_sistema			= getParametroSistema(6,3);
		$url_cda				.= $url_sistema."/central";

		$ParametroTipoMensagem = parametroTipoMensagem($IdLoja, $IdTipoMensagem);

		$sql = "SELECT
					ValorCodigoInterno Tipo
				FROM
					CodigoInterno
				WHERE
					IdLoja = $IdLoja AND
					IdGrupoCodigoInterno = 3 AND
					IdCodigoInterno = 165";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$Tipo = strtolower($lin[Tipo]);

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);

		$sql = "select  
					min(ContaReceberDados.IdContaReceber) IdContaReceber, 
					ContaReceberDados.DataVencimento, 
					ContaReceberDados.NumeroDocumento, 
					ContaReceberDados.MD5,
					(ContaReceberDados.ValorLancamento - ContaReceberDados.ValorDesconto + ContaReceberDados.ValorDespesas) ValorTotal, 	
					LocalCobranca.IdLocalCobrancaLayout,
					LancamentoFinanceiro.IdProcessoFinanceiro,
					LancamentoFinanceiro.IdContrato
				from 
					Contrato,
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber,
					ContaReceberDados,
					LocalCobranca
				where 
					Contrato.IdLoja = $IdLoja and
					Contrato.IdLoja = LancamentoFinanceiro.IdLoja and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = ContaReceberDados.IdLoja and
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
					Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberDados.IdContaReceber and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberDados.IdStatus = 1 and
					Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
					ContaReceberDados.IdContaReceber = $IdContaReceber and
					LocalCobranca.IdTipoLocalCobranca != 3
				group by
					Contrato.IdContrato";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$sql = "select
					Contrato.IdContrato,
					Servico.DiasLimiteBloqueio,
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,
					Pessoa.CampoExtra1,
					Pessoa.CampoExtra2,
					Pessoa.CampoExtra3,
					Pessoa.CampoExtra4
				from
					Pessoa,
					Servico,
					Contrato
				where
					Contrato.IdLoja = $IdLoja and
					Contrato.IdLoja = Servico.IdLoja and 
					Contrato.IdServico = Servico.IdServico and
					Contrato.IdPessoa = Pessoa.IdPessoa and	
					Contrato.IdContrato = $lin[IdContrato]";
		$res2 = mysql_query($sql,$con);
		$lin2 = mysql_fetch_array($res2);

		if($lin2[Email] == ''){
			return false;
		}

		if($lin2[TipoPessoa] == 1){
			$nome_responsavel = $lin2[NomeRepresentante]." (".$lin2[Nome].")";
		}else{
			$nome_responsavel = $lin2[Nome];
		}
	
		$NumeroDocumento		= $lin[NumeroDocumento];
			
		$CampoExtra1			= $lin2[CampoExtra1];
		$CampoExtra2			= $lin2[CampoExtra2];
		$CampoExtra3			= $lin2[CampoExtra3];
		$CampoExtra4			= $lin2[CampoExtra4];
		$nome					= $lin2[Nome];

		$DataVencimento			= dataConv($lin[DataVencimento],'Y-m-d','d/m/Y');
		$ValorTotal				= getParametroSistema(5,1)." ".number_format($lin[ValorTotal],2,',','');
		
		//Barrado por Gilmaico para fins futuros.
		/*$aux  = getParametroSistema(6,3)."/modulos/administrativo/boleto.php";
		$Link = getParametroSistema(6,3)."/modulos/cda/aviso_titulo_vencido.php?LinkBoleto=$aux&Tipo=$Tipo&ContaReceber=".$lin[MD5]."&IdContaReceber=".$lin[IdContaReceber]."&IdLoja=$IdLoja";*/
		
		//$Tipo = strtolower(getCodigoInterno(3,165));
		$aux 		.= '$url_sistema/modulos/administrativo/boleto.php';
		$LinkBoleto  = '$url_sistema/modulos/cda/aviso_titulo_vencido.php?LinkBoleto='.$aux.'&Tipo='.$Tipo.'&ContaReceber='.$lin[MD5]."&IdContaReceber=".$lin[IdContaReceber]."&IdLoja=".$IdLoja;

		$DataCorte				= incrementaData($lin[DataVencimento],$lin2[DiasLimiteBloqueio]);	
		
		$sqlDiaCorte = "select IdCodigoInterno DiaSemana from CodigoInterno where IdLoja = $IdLoja and IdGrupoCodigoInterno = 18 and ValorCodigoInterno = 1";
		$resDiaCorte = mysql_query($sqlDiaCorte,$con);
		while($linDiaCorte = mysql_fetch_array($resDiaCorte)){
			$Cortar[$linDiaCorte[DiaSemana]] = 'S';
		}

		$Incrementa = '';
		while($Incrementa != 'N'){

			$DataCorteAno	= substr($DataCorte,0,4);
			$DataCorteMes	= substr($DataCorte,5,2);
			$DataCorteDia	= substr($DataCorte,8,2);

			$DataCorteTemp = mktime(0,0,0,$DataCorteMes,$DataCorteDia,$DataCorteAno); 
			$DataCorteWeek = date("w",$DataCorteTemp);

			if($Cortar[$DataCorteWeek] == 'S'){
				$Incrementa = 'N';
			}else{
				$DataCorte = incrementaData($DataCorte,1);
				$Incrementa = 'S';
			}
		}

		$DataCorte = dataConv($DataCorte,'Y-m-d','d/m/Y');

		$FaturaUnica = "<tr>
						<td style='text-align:center; font-size: 11px'>$NumeroDocumento</td>
						<td style='text-align:center; font-size: 11px'>$DataVencimento</td>
						<td style='text-align:right; font-size: 11px'>$ValorTotal</td>
						<td style='text-align:center; font-size: 11px'><a href='$LinkBoleto' target='_blank'>2&ordf; Via</a></td>
					</tr>";

		$Faturas = '';
		
		$sqlFaturas = "select 
							Demonstrativo.IdContaReceber,
							ContaReceberDados.NumeroDocumento,
							ContaReceberDados.DataVencimento,
							(ContaReceberDados.ValorLancamento - ContaReceberDados.ValorDesconto + ContaReceberDados.ValorDespesas) ValorTotal,
							LocalCobranca.IdLocalCobrancaLayout
						from
							Demonstrativo,
							ContaReceberBaseVencimento,
							ContaReceberDados,
							LocalCobranca
						where
							Demonstrativo.IdLoja = $IdLoja and
							Demonstrativo.IdPessoa = $lin2[IdPessoa] and
							Demonstrativo.IdLoja = ContaReceberBaseVencimento.IdLoja and
							Demonstrativo.IdLoja = ContaReceberDados.IdLoja and
							Demonstrativo.IdLoja = LocalCobranca.IdLoja and
							Demonstrativo.IdContaReceber = ContaReceberBaseVencimento.IdContaReceber and
							Demonstrativo.IdContaReceber = ContaReceberDados.IdContaReceber and
							ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
							ContaReceberBaseVencimento.BaseVencimento > 0 and
							ContaReceberBaseVencimento.IdStatus = 1
						order by
							ContaReceberDados.IdContaReceber";
		$resFaturas = mysql_query($sqlFaturas,$con);
		while($linFaturas = mysql_fetch_array($resFaturas)){
			$LinkFaturas	= getParametroSistema(6,3)."/modulos/administrativo/boleto.php?Tipo=$Tipo&ContaReceber=".$lin2[MD5];

			$ValorTotal		= getParametroSistema(5,1)." ".number_format($linFaturas[ValorTotal],2,',','');			
			$DataVencimento	= dataConv($linFaturas[DataVencimento],'Y-m-d','d/m/Y');
			
			$Faturas		.= "<tr>
									<td style='text-align:center; font-size: 11px'>$linFaturas[NumeroDocumento]</td>
									<td style='text-align:center; font-size: 11px'>$DataVencimento</td>
									<td style='text-align:right; font-size: 11px'>$ValorTotal</td>
									<td style='text-align:center; font-size: 11px'><a href='$LinkFaturas' target='_blank'>2&ordf; Via</a></td>
								</tr>";
		}

		$sqlMensagem = "select 				
							TipoMensagem.Conteudo,
							TipoMensagem.Assunto,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}

			$TipoMensagem[Conteudo]	= str_replace('$nome_responsavel',$nome_responsavel,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$nome',$nome,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$CampoExtra1',$CampoExtra1,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$CampoExtra2',$CampoExtra2,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$CampoExtra3',$CampoExtra3,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$CampoExtra4',$CampoExtra4,$TipoMensagem[Conteudo]);

			$TipoMensagem[Conteudo]	= str_replace('$FaturaUnica',$FaturaUnica,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Faturas',$Faturas,$TipoMensagem[Conteudo]);			
			
			$TipoMensagem[Conteudo]	= str_replace('$_ColorBackgroundNomeEmpresa',$ParametroTipoMensagem['$_ColorBackgroundNomeEmpresa'],$TipoMensagem[Conteudo]);

			$TipoMensagem[Conteudo]	= str_replace('$link_boleto',$Link,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataCorte',$DataCorte,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$url_sistema',$url_sistema,$TipoMensagem[Conteudo]);			
			$TipoMensagem[Conteudo]	= str_replace('$url_cda',$url_cda,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_cda,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);

			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			
			$TipoMensagem[Assunto]	= str_replace('$DataVencimento',$DataVencimento,$TipoMensagem[Assunto]);

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$lin2[Email] = trim($lin2[Email]);

			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Email]					= $lin2[Email];			
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $lin2[IdPessoa];
			$GeraMensagem[IdContaReceber]			= $lin[IdContaReceber];
			#$GeraMensagem[IdProcessoFinanceiro]		= $lin[IdProcessoFinanceiro];	
			$GeraMensagem[Login]					= $local_Login;				

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviaClientesBloqueados($IdLoja, $Lista){

		global $con, $Path;

		$IdTipoMensagem = 6;
		$emailEnvio		= getCodigoInterno(38,4);
		$url_sistema	= getParametroSistema(6,3);
		$local_Login	= 'automatico';	
		$ListaContrato	= '';
		$Contrato		= '';
		$Anexo			= 0;

		$IdContrato				= array_keys($Lista);
		$QtdContratoBloqueado	= count($IdContrato);
		
		if(count($IdContrato) < 100){
			$QtdContrato = count($IdContrato);
		}else{
			$QtdContrato = 100;
		}
		
		for($i=0; $i<$QtdContrato; $i++){
			if($Lista[$IdContrato[$i]][Moeda] != ''){
				$ListaContrato .="
				<tr>
					<td style='font-size: 10px;'>(".$Lista[$IdContrato[$i]][IdPessoa].")&nbsp;".substr($Lista[$IdContrato[$i]][Nome],0,35)."</td>
					<td style='font-size: 10px;'><a href='".$Lista[$IdContrato[$i]][UrlContrato].$Lista[$IdContrato[$i]][IdContrato]."' style='color: #000000;'>(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")&nbsp;".substr($Lista[$IdContrato[$i]][DescricaoServico],0,20)."</a></td>
					<td style='font-size: 10px; text-align:center;'>".$Lista[$IdContrato[$i]][DataVencimento]."</td>
					<td style='font-size: 10px; text-align:right;'>".$Lista[$IdContrato[$i]][Moeda].$Lista[$IdContrato[$i]][ValorTotal]."</td>
				</tr> \n";
			}else{
				$ListaContrato .=" 
				<tr>
					<td style='font-size: 10px;'>(".$Lista[$IdContrato[$i]][IdPessoa].")&nbsp;".substr($Lista[$IdContrato[$i]][Nome],0,35)."</td>
					<td style='font-size: 10px;'><a href='".$Lista[$IdContrato[$i]][UrlContrato].$Lista[$IdContrato[$i]][IdContrato]."' style='color: #000000;'>(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")&nbsp;".substr($Lista[$IdContrato[$i]][DescricaoServico],0,20)."</a></td>
					<td style='font-size: 10px; text-align:center;'>Bloqueio Agendado</td>
					<td style='font-size: 10px; text-align:right;'>&nbsp;</td>
				</tr> \n";
			}								
		}				
		
		#gerar arquivo txt
		$FileName = "temp/contratos_bloqueados_".date("Y-m-d").".txt";
		@unlink($Path.$FileName);
		
		$File = fopen($Path.$FileName, "a");
	
		$Linha = "LISTA DE CONTRATOS BLOQUEADOS \r\n\r\n";
		$Linha .= "Segue abaixo a lista de clientes bloqueados no dia ".date('d/m/Y').". Loja ".$IdLoja.". \r\n\r\n";
		$Linha .= preenche_tam("Nome Pessoa", 100, 'X');
		$Linha .= preenche_tam("Descrição Serviço", 110, 'X');
		$Linha .= preenche_tam("Vencimento", 30, 'X');
		$Linha .= preenche_tam("Valor", 25, 'X');
		$Linha .= "\r\n";
		
		for($i=0; $i<count($IdContrato); $i++){
			if($Lista[$IdContrato[$i]][Moeda] != ''){
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdPessoa].") ".$Lista[$IdContrato[$i]][Nome], 100, 'X');
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")", 10, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][DescricaoServico], 100, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][DataVencimento], 30, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][Moeda].$Lista[$IdContrato[$i]][ValorTotal], 25, 'X');
				$Linha .= "\r\n";
			}else{
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdPessoa].") ".$Lista[$IdContrato[$i]][Nome], 100, 'X');
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")", 10, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][DescricaoServico], 100, 'X');
				$Linha .= preenche_tam("Bloqueio Agendado", 30, 'X');
				$Linha .= "\r\n";					
			}			
		}			
		
		$Anexo	= $Path.$FileName;		

		fwrite($File, $Linha);			
		fclose($File);			
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select
					Assunto,
					Conteudo,
					IdStatus
				from
					TipoMensagem
				where
					IdLoja = $IdLoja and
					IdTipoMensagem = $IdTipoMensagem";
		$res = mysql_query($sql,$con);
		$TipoMensagem = mysql_fetch_array($res);

		if($TipoMensagem[IdStatus] != 1){
			return false;
		}

		$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('$url_sistema',$url_sistema,$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('$dia',date("d/m/Y"),$TipoMensagem[Conteudo]);			
		$TipoMensagem[Conteudo]	= str_replace('$IdLoja',$IdLoja,$TipoMensagem[Conteudo]);	
		$TipoMensagem[Conteudo]	= str_replace('$Lista',$ListaContrato,$TipoMensagem[Conteudo]);			
		$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);		
		if($QtdContratoBloqueado > 100){
			$TipoMensagem[Conteudo]	.= "<br><b>Atenção: Existe mais de 100 contratos que foram bloqueados, segue em anexo a lista completa.</b><br>"; 
		}else{
			$TipoMensagem[Conteudo]	.= "<br><b>Atenção: Segue em anexo a lista completa.</b><br>"; 
		}
		
		$TipoMensagem[Assunto]	= str_replace('$DataBloqueio',date("d/m/Y"),$TipoMensagem[Assunto]);
		$TipoMensagem[Assunto]	= str_replace('$IdLoja',$IdLoja,$TipoMensagem[Assunto]);
		
		if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
		}else{
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
		}
		
		$GeraMensagem[IdLoja]					= $IdLoja;
		$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
		$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];	
		$GeraMensagem[Email]					= $emailEnvio;	
		$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
		$GeraMensagem[IdPessoa]					= $linEmpresa[IdPessoa];	
		$GeraMensagem[Login]					= $local_Login;	
		$GeraMensagem[Anexo][0]					= $Anexo;
		$GeraMensagem[LocalAnexo]				= "temp";
		$GeraMensagem[EnviarMensagem]			= false;		
		
		return geraMensagem($GeraMensagem);	
	}	

	function enviarTicket($IdLoja, $IdTicket, $IdLojaAbertura = 1){
		global $con, $conCNT, $local_Login;

		$url_sistema	= getParametroSistema(6,3);
		$url_sistema	.= '/central';
		
		$IdTipoMensagem		   = 7;
		$ParametroTipoMensagem = parametroTipoMensagem($IdLoja, $IdTipoMensagem);

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$conCNT);
		$linEmpresa = mysql_fetch_array($resEmpresa);
		
		$sql = "select  
					HelpDesk.EmailsGrupo Email,
					Pessoa.IdPessoa,
					HelpDesk.Assunto,
					HelpDesk.IdStatus
				from 
					HelpDesk,
					Pessoa
				where 
					HelpDesk.IdLoja = $IdLoja and
					HelpDesk.IdLojaAbertura = $IdLojaAbertura and
					HelpDesk.IdPessoa = Pessoa.IdPessoa and
					HelpDesk.IdTicket = $IdTicket";
		$res = mysql_query($sql,$conCNT);
		$lin = mysql_fetch_array($res);	

		$DescricaoStatus = getParametroSistema(128,$lin[IdStatus]);

		$sql = "select  
					HelpDeskHistorico.Obs,
					HelpDeskHistorico.DataCriacao,
					HelpDeskHistorico.IdStatusTicket
				from 
					HelpDeskHistorico
				where 
					HelpDeskHistorico.IdTicket = $IdTicket and
					HelpDeskHistorico.Publica = 1";
		$res2 = mysql_query($sql,$conCNT);
		while($lin2 = mysql_fetch_array($res2)){
			$lin2[DataCriacao]		= dataConv($lin2[DataCriacao],'Y-m-d H:i:s','d/m/Y H:i:s');
			$lin2[IdStatusTicket]	= getParametroSistema(128,$lin2[IdStatusTicket]);
			
			$lin2[Obs] = str_replace("\n","<br>",$lin2[Obs]);
			$Hitorico .="<div style='border:1px solid #000; padding:5px 13px 5px 13px; margin-top:13px; text-align: justify;'>
							<div style='height: 14px; margin-bottom: 2px;'>
								<div style='float:left;'><b>Data:</b> ".$lin2[DataCriacao]."</div>
								<div style='float:right;'><b>Status:</b> ".$lin2[IdStatusTicket]."</div>
							</div>".$lin2[Obs]."</div>";							
		}		

		if($lin[Email] == ''){
			return false;
		}	

		$sqlMensagem = "select 
							TipoMensagem.Assunto,
							TipoMensagem.Titulo,
							TipoMensagem.Conteudo,
							TipoMensagem.IdContaEmail,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){	
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
		
			$TipoMensagem[Conteudo]	= str_replace('$IdTicket',$IdTicket,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Historico',$Hitorico,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);

			$TipoMensagem[Assunto]	= str_replace('$IdTicket',$IdTicket,$TipoMensagem[Assunto]);
			$TipoMensagem[Assunto]	= str_replace('$DescricaoStatus',$DescricaoStatus,$TipoMensagem[Assunto]);

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}

			$TipoMensagem[Titulo]	= str_replace('$IdTicket',$IdTicket,$TipoMensagem[Titulo]);
			
			$lin[Email] = trim($lin[Email]);
						
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTicket]					= $IdTicket;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
			$GeraMensagem[Email]					= $lin[Email];			
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $lin[IdPessoa];			
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviaLogBackup($DataBackup){

		global $con;

		$IdLoja					= 1;
		$local_Login			= 'automatico';

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);

		$sqlConteudo = "select
							Log,
							Erro
						from
							Backup
						where
							DataHoraInicio = '$DataBackup'";
		$resConteudo = mysql_query($sqlConteudo,$con);
		$linConteudo = mysql_fetch_array($resConteudo);

		if($linConteudo[Erro] == 0){
			// Backup Realizado
			$emailEnvio	= trim(getParametroSistema(83,9));
			$IdTipoMensagem	= 8;
		}else{
			// Backup Não Realizado
			$emailEnvio	= trim(getParametroSistema(83,14));
			$IdTipoMensagem	= 9;
		}

		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);

		$sql = "select
					Conteudo,
					Assunto,
					Titulo,
					IdStatus
				from
					TipoMensagem
				where
					IdLoja = $IdLoja and
					IdTipoMensagem = $IdTipoMensagem";
		$res = mysql_query($sql,$con);
		$TipoMensagem = mysql_fetch_array($res);

		if($TipoMensagem[IdStatus] != 1){
			return false;
		}

		if($emailEnvio != '' && $linConteudo[Log] != ''){

			$LogProcessamento = date("d/m/Y H:i:s")." [automatico] - Log enviado para: $emailEnvio.\n".$linConteudo[Log];

			$sqlLog = "update Backup set Log='$LogProcessamento' where DataHoraInicio='$DataBackup'";
			mysql_query($sqlLog,$con);

			$DataBackup = dataConv($DataBackup,"Y-m-d H:i:s","d/m/Y H:i:s");
			
			$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);				
			$TipoMensagem[Conteudo]	= str_replace('$LogBackup',$linConteudo[Log],$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace(array("\n", "\r\n"), "<br />", $TipoMensagem[Conteudo]);
				
			$TipoMensagem[Assunto]	= str_replace('$DataBackup',$DataBackup,$TipoMensagem[Assunto]);

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}

			$TipoMensagem[Titulo]	= str_replace('$DataBackup',$DataBackup,$TipoMensagem[Titulo]);
		
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdPessoa]					= $linEmpresa[IdPessoa];
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
			$GeraMensagem[Email]					= $emailEnvio;			
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
			$GeraMensagem[EnviarMensagem]			= false;
			$GeraMensagem[Login]					= $local_Login;

			return geraMensagem($GeraMensagem);
		}
	}

	function enviaNF2ViaRemessa($IdLoja, $IdNotaFiscalLayout, $MesReferencia, $IdStatusArquivoMestre, $local_Email){

		global $con, $Path;
		global $local_Login;


		$IdTipoMensagem				= 10;
		$emailEnvio					= $local_Email;
		$url_sistema				= getParametroSistema(6,3);	
		$ParametroTipoMensagem		= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select
					Assunto,
					Conteudo,
					IdStatus
				from
					TipoMensagem
				where
					IdLoja = $IdLoja and
					IdTipoMensagem = 10";
		$res = mysql_query($sql,$con);
		$TipoMensagem = mysql_fetch_array($res);

		if($TipoMensagem[IdStatus] != 1){
			return false;
		}

		$AnexoDirPath = $Path."modulos/administrativo/remessa/nota_fiscal/$IdLoja/".dataConv($MesReferencia,'m/Y','Y-m')."/$IdStatusArquivoMestre/";

		$AnexoDir = scandir($AnexoDirPath);
		$j = 0;
		for($i=2; $i<count($AnexoDir); $i++){		
			$GeraMensagem[Anexo][$j] = $AnexoDirPath.$AnexoDir[$i];			
			$j++;
		}
		
		$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);
			
		$TipoMensagem[Assunto] = str_replace('$MesReferencia',$MesReferencia,$TipoMensagem[Assunto]);

		$TipoMensagem[Assunto]	= str_replace('$IdLoja',$IdLoja,$TipoMensagem[Assunto]);

		if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
		}else{
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
		}

		$GeraMensagem[IdLoja]					= $IdLoja;
		$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
		$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
		$GeraMensagem[Email]					= $emailEnvio;			
		$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
		$GeraMensagem[IdPessoa]					= $linEmpresa[IdPessoa];
		$GeraMensagem[Login]					= $local_Login;
		$GeraMensagem[LocalAnexo]				= $Path."modulos/administrativo/remessa/nota_fiscal/".$IdLoja."/".dataConv($MesReferencia,'m/Y','Y-m')."/".$IdStatusArquivoMestre;	
		

		$IdHistoricoMensagem = geraMensagem($GeraMensagem);	
		
		return $IdHistoricoMensagem;
	}

	function enviarNovaSenhaCDA($CPF_CNPJ){
		global $con;
		
		$IdTipoMensagem			= 26;
		$url_sistema			= getParametroSistema(6,3);
		$url_cda				.= $url_sistema.'/central';
		$IdLoja					= 1;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		
		$sql =  "update Pessoa set SolicitacaoAlteracaoSenhaCDA = md5(concat(IdPessoa,curdate())) where CPF_CNPJ = '$CPF_CNPJ'";
		$res = mysql_query($sql,$con);
		
		$sql = "select 
					IdPessoa, 					
					TipoPessoa,
					Nome,
					Email,
					NomeRepresentante,
					SolicitacaoAlteracaoSenhaCDA					
				from					
					Pessoa
				where 
					CPF_CNPJ = '$CPF_CNPJ'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);	

		if($lin[TipoPessoa] == 1){
			$lin[Nome] = $lin[NomeRepresentante];
		}
				
		$sqlMensagem = "select 				
							TipoMensagem.Conteudo,
							TipoMensagem.Assunto,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){	
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$LinkRedefinicaoSenha = $url_sistema."/modulos/cda/rotinas/redefine_senha.php?MD5=$lin[SolicitacaoAlteracaoSenhaCDA]&Envio=true";
			$LinkCancelamento	  = $url_sistema."/modulos/cda/rotinas/redefine_senha.php?MD5=$lin[SolicitacaoAlteracaoSenhaCDA]&Envio=false";

			$IP = $_SERVER["REMOTE_ADDR"];
				
			$TipoMensagem[Conteudo]	= str_replace('$Email',$lin[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomePessoa',$lin[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$LinkRedefinicaoSenha',$LinkRedefinicaoSenha,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$LinkCancelamento',$LinkCancelamento,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataSolicitacao',date("d/m/Y H:i:s"),$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$IP',$IP,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_cda,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);

			$TipoMensagem[Assunto]	= str_replace('$Nome',$lin[Nome],$TipoMensagem[Assunto]);

			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];			
			$GeraMensagem[Email]					= $lin[Email];			
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $lin[IdPessoa];			
			$GeraMensagem[Login]					= 'CDA';				
			$GeraMensagem[EnviarMensagem]			= true;	

			return geraMensagem($GeraMensagem);		
		}
	}
	
	function enviarRedefinicaoSenhaCDA($local_Email, $CPF_CNPJ){
		global $con;
		
		$IdTipoMensagem			= 11;
		$url_sistema			= getParametroSistema(6,3);
		$url_cda				= $url_sistema.'/central';	
		$IdLoja					= 1;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		
		$sql =  "update Pessoa set SolicitacaoAlteracaoSenhaCDA = md5(concat(IdPessoa,curdate())) where CPF_CNPJ = '$CPF_CNPJ'";
		$res = mysql_query($sql,$con);
		
		$sql = "select 
					IdPessoa, 					
					TipoPessoa,
					Nome,
					NomeRepresentante,
					SolicitacaoAlteracaoSenhaCDA					
				from					
					Pessoa
				where 
					CPF_CNPJ = '$CPF_CNPJ'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);	

		if($lin[TipoPessoa] == 1){
			$lin[Nome] = $lin[NomeRepresentante];
		}
				
		$sqlMensagem = "select 				
							TipoMensagem.Conteudo,
							TipoMensagem.Assunto,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$LinkRedefinicaoSenha = $url_sistema."/modulos/cda/rotinas/redefine_senha.php?MD5=$lin[SolicitacaoAlteracaoSenhaCDA]&Envio=true";
			$LinkCancelamento	  = $url_sistema."/modulos/cda/rotinas/redefine_senha.php?MD5=$lin[SolicitacaoAlteracaoSenhaCDA]&Envio=false";

			$IP = $_SERVER["REMOTE_ADDR"];
				
			$TipoMensagem[Conteudo]	= str_replace('$Email',$local_Email,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomePessoa',$lin[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$LinkRedefinicaoSenha',$LinkRedefinicaoSenha,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$LinkCancelamento',$LinkCancelamento,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataSolicitacao',date("d/m/Y H:i:s"),$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$IP',$IP,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_cda,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);

			$TipoMensagem[Assunto]	= str_replace('$Nome',$lin[Nome],$TipoMensagem[Assunto]);

			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];			
			$GeraMensagem[Email]					= $local_Email;			
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $lin[IdPessoa];			
			$GeraMensagem[Login]					= 'CDA';				
			$GeraMensagem[EnviarMensagem]			= true;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarSenhaCDA($MD5){
		global $con;
		
		$IdTipoMensagem			= 14;
		$IdLoja					= 1;
		$url_sistema			= getParametroSistema(6,3);
		$url_cda				= $url_sistema.'/central';	
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		
		$Senha = rand(10000000,99999999);

		$sql =  "update Pessoa set Senha = $Senha where SolicitacaoAlteracaoSenhaCDA = '$MD5'";
		$res = mysql_query($sql,$con);	

		$sql = "select 
					IdPessoa,
					TipoPessoa,
					Nome,
					NomeRepresentante,
					Email
				from					
					Pessoa
				where 					
					SolicitacaoAlteracaoSenhaCDA = '$MD5'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);	

		if($lin[TipoPessoa] == 1){
			$lin[Nome] = $lin[NomeRepresentante];
		}
				
		$sqlMensagem = "select 				
							TipoMensagem.Conteudo,
							TipoMensagem.Assunto,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$IP = $_SERVER["REMOTE_ADDR"];
							
			$TipoMensagem[Conteudo]	= str_replace('$NomePessoa',$lin[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Senha',$Senha,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataConfirmacao',date("d/m/Y H:i:s"),$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$IP',$IP,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_cda,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);

			$TipoMensagem[Assunto]	= str_replace('$Nome',$lin[Nome],$TipoMensagem[Assunto]);
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];			
			$GeraMensagem[Email]					= $lin[Email];			
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $lin[IdPessoa];			
			$GeraMensagem[Login]					= 'CDA';				
			$GeraMensagem[EnviarMensagem]			= true;	

			return geraMensagem($GeraMensagem);		
		}
	}
	
	function enviarRedefinicaoSenha($Login, $Email){

		global $con;

		if(trim($Email) == ''){	return false; }		
		
		$IdTipoMensagem			= 12;
		$url_sistema			= getParametroSistema(6,3);
		$url_url				.= '/central';
		$IdLoja					= 1;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);
		
		$sql =  "update Usuario set SolicitacaoAlteracaoSenha = md5(concat(Login,curdate())) where Login = '$Login'";
		$res = mysql_query($sql,$con);
		
		$sql = "select 
					IdPessoa, 					
					SolicitacaoAlteracaoSenha,
					Login
				from					
					Usuario
				where 
					Login = '$Login'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);	
				
		$sqlMensagem = "select 				
							TipoMensagem.Conteudo,
							TipoMensagem.Assunto,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$LinkRedefinicaoSenha = $url_sistema."/rotinas/redefine_senha.php?MD5=$lin[SolicitacaoAlteracaoSenha]&Envio=true";
			$LinkCancelamento	  = $url_sistema."/rotinas/redefine_senha.php?MD5=$lin[SolicitacaoAlteracaoSenha]&Envio=false";

			$IP = $_SERVER["REMOTE_ADDR"];
				
			$TipoMensagem[Conteudo]	= str_replace('$Email',$Email,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Usuario',$lin[Login],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$LinkRedefinicaoSenha',$LinkRedefinicaoSenha,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$LinkCancelamento',$LinkCancelamento,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataSolicitacao',date("d/m/Y H:i:s"),$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$IP',$IP,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);

			$TipoMensagem[Assunto]	= str_replace('$Usuario',$lin[Login],$TipoMensagem[Assunto]);

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}

			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];			
			$GeraMensagem[Email]					= $Email;			
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $lin[IdPessoa];			
			$GeraMensagem[Login]					= $Login;				
			$GeraMensagem[EnviarMensagem]			= true;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarSenha($MD5){
		global $con;
		
		$IdTipoMensagem			= 13;
		$IdLoja					= 1;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);

		$Senha = geraSenha(8,true,false);

		$sql =  "update Usuario set Password = md5('$Senha'), ForcarAlteracaoSenha = 1 where SolicitacaoAlteracaoSenha = '$MD5'";
		$res = mysql_query($sql,$con);	

		$sql = "select 
					Usuario.IdPessoa,
					Usuario.Login,
					Pessoa.Email
				from					
					Usuario,
					Pessoa
				where 					
					Usuario.SolicitacaoAlteracaoSenha = '$MD5' and
					Usuario.IdPessoa = Pessoa.IdPessoa";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);	

		$sqlMensagem = "select 				
							TipoMensagem.Conteudo,
							TipoMensagem.Assunto,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$IP = $_SERVER["REMOTE_ADDR"];
							
			$TipoMensagem[Conteudo]	= str_replace('$Usuario',$lin[Login],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Senha',$Senha,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataConfirmacao',date("d/m/Y H:i:s"),$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$IP',$IP,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);

			$TipoMensagem[Assunto]	= str_replace('$Usuario',$lin[Login],$TipoMensagem[Assunto]);

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];			
			$GeraMensagem[Email]					= $lin[Email];			
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $lin[IdPessoa];			
			$GeraMensagem[Login]					= $lin[Login];				
			$GeraMensagem[EnviarMensagem]			= true;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarEmailBoasVindas($IdLoja, $local_Email, $IdPessoa){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 15;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		$sql = "select
					Pessoa.Nome,
					Pessoa.RazaoSocial
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select
					Nome,
					NomeRepresentante,
					TipoPessoa,
					Email,
					Senha
				from
					Pessoa					
				where
					IdPessoa = $IdPessoa";
		$resPessoa = mysql_query($sql,$con);
		$linPessoa = mysql_fetch_array($resPessoa);	

		if($linPessoa[TipoPessoa] == 1){
			$linPessoa[Nome] = $linPessoa[NomeRepresentante];
		}
		
		if($linPessoa[Senha] != ''){
			$linPessoa[Senha] = "Senha: ".$linPessoa[Senha];
		}
				
		$sqlMensagem = "select 				
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$sql	=	"select
							Opcoes.IdParametroSistema,
							Opcoes.DescricaoParametroSistema,
							Opcoes.ValorParametroSistema
						from
							(select IdParametroSistema,DescricaoParametroSistema,ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96) Opcoes,
							(select IdParametroSistema,DescricaoParametroSistema,ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 98) OpcoesAtiva
						where
							Opcoes.IdParametroSistema = OpcoesAtiva.IdParametroSistema and
							OpcoesAtiva.ValorParametroSistema = 1 
						order by
							Opcoes.DescricaoParametroSistema";
			$res	=	@mysql_query($sql,$con);
			$ListaMenuCDA = "<ul>";
			while($lin	=	@mysql_fetch_array($res)){				
				$ListaMenuCDA .= "<li>$lin[DescricaoParametroSistema]</li>";				
			}
			$ListaMenuCDA .= "</ul>";
			
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linPessoa[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linPessoa[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);							
			$TipoMensagem[Conteudo]	= str_replace('$SenhaCDA',$linPessoa[Senha],$TipoMensagem[Conteudo]);		
			$TipoMensagem[Conteudo]	= str_replace('$ListaMenuCDA',$ListaMenuCDA,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
	
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $local_Email;			
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $IdPessoa;			
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarEmailAdesaoServico($IdLoja, $IdContrato){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 16;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		$sql = "select
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,					
					Servico.DescricaoServico
				from
					Servico,
					Contrato,
					Pessoa
				where
					Servico.IdLoja = $IdLoja and
					Servico.IdLoja = Contrato.IdLoja and
					Servico.IdServico = Contrato.IdServico and
					Contrato.IdContrato = $IdContrato and
					Contrato.IdPessoa = Pessoa.IdPessoa";
		$resContrato = mysql_query($sql,$con);
		$linContrato = mysql_fetch_array($resContrato);

		$sql = "select
					ContratoParametro.Valor
				from
					ContratoParametro,
					ServicoParametro
				where
					ContratoParametro.IdLoja = $IdLoja and
					ContratoParametro.IdLoja = ServicoParametro.IdLoja and
					ContratoParametro.IdServico = ServicoParametro.IdServico and
					ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and
					ContratoParametro.IdContrato = $IdContrato and
					ServicoParametro.ParametroDemonstrativo = 1";
		$resContratoParametro = mysql_query($sql,$con);
		$linContratoParametro = mysql_fetch_array($resContratoParametro);	
		if($linContrato[Email] == ''){
			return false;
		}

		if($linContrato[TipoPessoa] == 1){
			$linContrato[Nome] = $linContrato[NomeRepresentante];
		}
		
		$sqlMensagem = "select 		
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and 
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){	
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$TipoMensagem[Conteudo]	= str_replace('$IdContrato',$IdContrato,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linContrato[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoServico',$linContrato[DescricaoServico],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$ParametroDemonstrativo',$linContratoParametro[Valor],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linContrato[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			
			$TipoMensagem[Assunto]	= str_replace('$IdContrato',$IdContrato,$TipoMensagem[Assunto]);

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdContrato]				= $IdContrato;			
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linContrato[Email];					
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linContrato[IdPessoa];			
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	
			
			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarEmailCronMonitor($IdLoja, $IdMonitor, $IdStatus){

		global $con, $local_Login;
		
		$IdTipoMensagem			= 17;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		
		$sql = "select
					IdPessoa
				from
					Loja									
				where
					Loja.IdLoja = $IdLoja";
		$resLoja = mysql_query($sql,$con);
		$linLoja = mysql_fetch_array($resLoja);	
		
		$sql = "select
					MonitorPortaAlarme.Mensagem,
					MonitorPortaAlarme.DestinatarioMensagem,
					MonitorPorta.DescricaoMonitor,
					MonitorPorta.HostAddress,
					MonitorPorta.Porta
				from
					MonitorPortaAlarme,
					MonitorPorta
				where
					MonitorPortaAlarme.IdLoja = $IdLoja and
					MonitorPortaAlarme.IdLoja = MonitorPorta.IdLoja and
					MonitorPortaAlarme.IdMonitor = $IdMonitor and
					MonitorPortaAlarme.IdMonitor = MonitorPorta.IdMonitor and
					MonitorPortaAlarme.IdStatus = $IdStatus and
					MonitorPortaAlarme.IdTipoMensagem = $IdTipoMensagem";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);		

		$sqlMensagem = "select 		
							Titulo,
							Assunto,
							Conteudo,
							IdStatus
						from 
							TipoMensagem
						where 
							IdLoja = $IdLoja and 
							IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){	
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}

			if($IdStatus == 0){
				// OFF
				$lin[DescricaoMonitor] = "[off-line] $lin[DescricaoMonitor]";
			}else{
				// ON
				$lin[DescricaoMonitor] = "[on-line] $lin[DescricaoMonitor]";
			}
								
			$TipoMensagem[Titulo]	= str_replace('$HostAddress',$lin[HostAddress],$TipoMensagem[Titulo]);		
			$TipoMensagem[Titulo]	= str_replace('$Porta',$lin[Porta],$TipoMensagem[Titulo]);		

			$TipoMensagem[Assunto]	= str_replace('$DescricaoMonitor',$lin[DescricaoMonitor],$TipoMensagem[Assunto]);	

			$TipoMensagem[Conteudo]	= str_replace('$Mensagem',$lin[Mensagem],$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('$DataCriacao',date("d/m/Y H:i:s"),$TipoMensagem[Conteudo]);
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];			
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Email]					= $lin[DestinatarioMensagem];			
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linLoja[IdPessoa];			
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[IdMonitor]				= $IdMonitor;
			$GeraMensagem[EnviarMensagem]			= false;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarEmailCancelamentoServico($IdLoja, $IdContrato, $Email, $IdProtocolo = null){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 18;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		if(trim($Email) == ''){	return false; }

		$sql = "select
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,
					Servico.DescricaoServico,
					Contrato.DataTermino
				from
					Servico,					
					Contrato,
					Pessoa
				where
					Servico.IdLoja = $IdLoja and
					Servico.IdLoja = Contrato.IdLoja and
					Servico.IdServico = Contrato.IdServico and
					Contrato.IdPessoa = Pessoa.IdPessoa and
					Contrato.IdContrato = $IdContrato";
		$resContrato = mysql_query($sql,$con);
		$linContrato = mysql_fetch_array($resContrato);	

		$sql = "select
					ContratoParametro.Valor
				from
					ContratoParametro,
					ServicoParametro
				where
					ContratoParametro.IdLoja = $IdLoja and
					ContratoParametro.IdLoja = ServicoParametro.IdLoja and
					ContratoParametro.IdServico = ServicoParametro.IdServico and
					ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and
					ContratoParametro.IdContrato = $IdContrato and
					ServicoParametro.ParametroDemonstrativo = 1";
		$resContratoParametro = mysql_query($sql,$con);
		$linContratoParametro = mysql_fetch_array($resContratoParametro);	

		if(trim($linContrato[Email]) == ''){
			if($Email == ''){
				return false;
			}else{
				$linContrato[Email] = trim($Email);
			}
		}else{
			if(trim($Email) != ''){
				$linContrato[Email] = ";".trim($Email);
			}
		}	

		if($linContrato[TipoPessoa] == 1){
			$linContrato[Nome] = $linContrato[NomeRepresentante];
		}

		$linContrato[DataTermino] = dataConv($linContrato[DataTermino],'Y-m-d','d/m/Y');
				
		$sqlMensagem = "select 							
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){	
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			if($IdProtocolo != null){
				$Protocolo = "<p>Protocolo de atendimento: N° $IdProtocolo.</p>";
			}
			
			$TipoMensagem[Conteudo]	= str_replace('$IdContrato',$IdContrato,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linContrato[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoServico',$linContrato[DescricaoServico],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataCancelamento',$linContrato[DataTermino],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$ParametroDemonstrativo',$linContratoParametro[Valor],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Protocolo',$Protocolo,$TipoMensagem[Conteudo]);

			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linContrato[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			
			$TipoMensagem[Assunto]	= str_replace('$IdContrato',$IdContrato,$TipoMensagem[Assunto]);

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linContrato[Email];					
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linContrato[IdPessoa];			
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;			
		
			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarEmailAberturaOrdemServico($IdLoja, $IdOrdemServico){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 19;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		$sql = "select
					Pessoa.Nome					
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,					
					Servico.DescricaoServico,					
					OrdemServico.DescricaoOS,
					OrdemServico.DataCriacao
				from
					Servico,
					OrdemServico,
					Pessoa
				where
					Servico.IdLoja = $IdLoja and
					Servico.IdLoja = OrdemServico.IdLoja and
					Servico.IdServico = OrdemServico.IdServico and
					OrdemServico.IdOrdemServico = $IdOrdemServico and
					OrdemServico.IdPessoa = Pessoa.IdPessoa";
		$resOrdemServico = mysql_query($sql,$con);
		$linOrdemServico = mysql_fetch_array($resOrdemServico);
		
		if($linOrdemServico[Email] == ''){
			return false;
		}

		if($linOrdemServico[TipoPessoa] == 1){
			$linOrdemServico[Nome] = $linOrdemServico[NomeRepresentante];
		}

		$linOrdemServico[DataCriacao] = dataConv($linOrdemServico[DataCriacao],'Y-m-d','d/m/Y');

		$sqlMensagem = "select 		
							TipoMensagem.Titulo,
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){	
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}

			$linOrdemServico[DescricaoOS] = str_replace("\n","<br>",$linOrdemServico[DescricaoOS]);
			
			$TipoMensagem[Conteudo]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoServico',$linOrdemServico[DescricaoServico],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataCriacao',$linOrdemServico[DataCriacao],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linOrdemServico[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linOrdemServico[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			
			$TipoMensagem[Titulo]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Titulo]);
			
			$TipoMensagem[Assunto]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Assunto]);
			
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linOrdemServico[Email];		
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linOrdemServico[IdPessoa];	
			$GeraMensagem[IdOrdemServico]			= $IdOrdemServico;
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarEmailConclusaoOrdemServico($IdLoja, $IdOrdemServico){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 20;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		$sql = "select
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,					
					Servico.DescricaoServico,
					Temp.EmailAtendimento,
					OrdemServico.DescricaoOS,
					OrdemServico.DataCriacao,
					OrdemServico.DataConclusao
				from
					Servico,
					OrdemServico left join (
						select 
							Pessoa.Email EmailAtendimento,
							Usuario.Login 
						from
							Usuario,
							Pessoa 
						where 
							Usuario.IdPessoa = Pessoa.IdPessoa
					) Temp on (
						OrdemServico.LoginAtendimento = Temp.Login
					),
					Pessoa
				where
					Servico.IdLoja = $IdLoja and
					Servico.IdLoja = OrdemServico.IdLoja and
					Servico.IdServico = OrdemServico.IdServico and
					OrdemServico.IdOrdemServico = $IdOrdemServico and
					OrdemServico.IdPessoa = Pessoa.IdPessoa";
		$resOrdemServico = mysql_query($sql,$con);
		$linOrdemServico = mysql_fetch_array($resOrdemServico);
		
		if($linOrdemServico[Email] == '' && getCodigoInterno(38,5)==''){
			return false;
		}

		if($linOrdemServico[TipoPessoa] == 1){
			$linOrdemServico[Nome] = $linOrdemServico[NomeRepresentante];
		}

		$linOrdemServico[DataCriacao]	= dataConv($linOrdemServico[DataCriacao],'Y-m-d','d/m/Y');
		$linOrdemServico[DataConclusao] = dataConv($linOrdemServico[DataConclusao],'Y-m-d','d/m/Y');
		
		$sqlMensagem = "select 		
							TipoMensagem.Titulo,
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and 
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){			

			if($TipoMensagem[IdStatus] != 1){
				return false;
			}

			$linOrdemServico[DescricaoOS] = str_replace("\n","<br>",$linOrdemServico[DescricaoOS]);								

			$TipoMensagem[Conteudo]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoServico',$linOrdemServico[DescricaoServico],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataCriacao',$linOrdemServico[DataCriacao],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataConclusao',$linOrdemServico[DataConclusao],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linOrdemServico[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linOrdemServico[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			
			$TipoMensagem[Titulo]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Titulo]);
			
			$TipoMensagem[Assunto]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Assunto]);		
					
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			/*$EmailConfigurado = getCodigoInterno(38,5);
			
			if(trim($EmailConfigurado) != '' && $linOrdemServico[Email] !=''){
				$linOrdemServico[Email] .= ";".trim($EmailConfigurado);
			}else{
				$linOrdemServico[Email] .= trim($EmailConfigurado);
			}*/
			if(trim($linOrdemServico[EmailAtendimento]) != '' && $linOrdemServico[Email] !=''){
				$linOrdemServico[Email] .= ";".trim($linOrdemServico[EmailAtendimento]);
			}else{
				$linOrdemServico[Email] .= trim($linOrdemServico[EmailAtendimento]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linOrdemServico[Email];		
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linOrdemServico[IdPessoa];
			$GeraMensagem[IdOrdemServico]			= $IdOrdemServico;
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;//Leonardo -> true: envia de imediato -- false: coloca na fila para enviar quando executar a rotina de envio de mensagem
			
			//echo $GeraMensagem[Email];
			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarEmailContaReceberQuitado($IdLoja, $IdContaReceber, $IdContaReceberRecebimento){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 21;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		$sql = "select
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.ValorFinal,
					ContaReceberDados.NumeroDocumento,
					ContaReceberRecebimento.ValorRecebido,
					ContaReceberRecebimento.ValorDesconto
				from
					Pessoa,
					ContaReceberDados,
					ContaReceberRecebimento
				where
					ContaReceberDados.IdLoja = $IdLoja and		
					ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and		
					Pessoa.IdPessoa = ContaReceberDados.IdPessoa and
					ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
					ContaReceberDados.IdContaReceber = $IdContaReceber and
					ContaReceberRecebimento.IdContaReceberRecebimento = $IdContaReceberRecebimento";
		$resContaReceberRecebimento = mysql_query($sql,$con);
		$linContaReceberRecebimento = mysql_fetch_array($resContaReceberRecebimento);
		
		if($linContaReceberRecebimento[Email] == ''){
			return false;
		}

		if($linContaReceberRecebimento[TipoPessoa] == 1){
			$linContaReceberRecebimento[Nome] = $linContaReceberRecebimento[NomeRepresentante];
		}
		
		$sqlMensagem = "select 				
							TipoMensagem.Conteudo,
							TipoMensagem.Assunto,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){	

			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$linContaReceberRecebimento[DataVencimento] = dataConv($linContaReceberRecebimento[DataVencimento],"Y-m-d","d/m/Y");
			$linContaReceberRecebimento[ValorFinal] = number_format($linContaReceberRecebimento[ValorFinal],2,',','');
			$linContaReceberRecebimento[ValorRecebido] = number_format($linContaReceberRecebimento[ValorRecebido],2,',','');
			$linContaReceberRecebimento[ValorDesconto] = number_format($linContaReceberRecebimento[ValorDesconto],2,',','');

			if($linContaReceberRecebimento[ValorDesconto] > 0){
				$Desconto = "Valor Desconto: ".getParametroSistema(5,1).$linContaReceberRecebimento[ValorDesconto]."<br />";
			}
								
			$TipoMensagem[Conteudo]	= str_replace('$DataVencimento',$linContaReceberRecebimento[DataVencimento],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$ValorTitulo',getParametroSistema(5,1).$linContaReceberRecebimento[ValorFinal],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NumeroDocumento',$linContaReceberRecebimento[NumeroDocumento],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$ValorPago',getParametroSistema(5,1).$linContaReceberRecebimento[ValorRecebido],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Desconto',$Desconto,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linContaReceberRecebimento[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linContaReceberRecebimento[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linContaReceberRecebimento[Email];		
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linContaReceberRecebimento[IdPessoa];	
			$GeraMensagem[IdContaReceber]			= $IdContaReceber;
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarEmailAniversario($IdLoja, $IdPessoa){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 22;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_cda				= $url_sistema."/central";
		$url_sistema			.= '/img/estrutura_sistema/baloes.gif';

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);

		$sql = "select					
					Email					
				from
					Pessoa					
				where
					IdPessoa = $IdPessoa";
		$resPessoa = mysql_query($sql,$con);
		$linPessoa = mysql_fetch_array($resPessoa);	

		if($linPessoa[Email] == ''){
			return false;
		}

		$sqlMensagem = "select 		
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and 
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$ConteudoMsg = str_replace("
	","<br>",getParametroSistema(99,45));

			$TipoMensagem[Conteudo]	= str_replace('$EndImagem',$url_sistema,$TipoMensagem[Conteudo]);							
			$TipoMensagem[Conteudo]	= str_replace('$ConteudoMsg',$ConteudoMsg,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_cda,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linPessoa[Email];			
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $IdPessoa;			
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	

			return geraMensagem($GeraMensagem);		
		}
	}
	
	function enviarEmailAlteracaoStatusContrato($IdLoja, $IdContrato, $Email, $IdStatusNovo, $DataBloqueio, $IdProtocolo = null){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 23;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		$sql = "select
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,
					Servico.DescricaoServico
				from
					Contrato,
					Pessoa,
					Servico
				where
					Contrato.IdLoja = $IdLoja and
					Contrato.IdLoja = Servico.IdLoja and
					Contrato.IdServico = Servico.IdServico and
					Contrato.IdPessoa = Pessoa.IdPessoa and
					Contrato.IdContrato = $IdContrato";
		$resContrato = mysql_query($sql,$con);
		$linContrato = mysql_fetch_array($resContrato);	

		$sql = "select
					IdStatusAntigo
				from
					ContratoStatus
				where
					IdLoja = $IdLoja and					
					IdContrato = $IdContrato
				order by
					IdMudancaStatus desc";
		$resContratoStatus = mysql_query($sql,$con);
		$linContratoStatus = mysql_fetch_array($resContratoStatus);	
		
		if(trim($linContrato[Email]) == ''){
			if($Email == ''){
				return false;
			}else{
				$linContrato[Email] = trim($Email);
			}
		}else{
			if(trim($Email) != ''){
				$linContrato[Email] = ";".trim($Email);
			}
		}

		if($linContrato[TipoPessoa] == 1){
			$linContrato[Nome] = $linContrato[NomeRepresentante];
		}
				
		$sqlMensagem = "select 		
							TipoMensagem.Titulo,
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){

			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$StatusNovo		= getParametroSistema(69,$IdStatusNovo);
			if($linContratoStatus[IdStatusAntigo] != ""){
				$StatusAntigo	= getParametroSistema(69,$linContratoStatus[IdStatusAntigo]);
			}else{
				$StatusAntigo	= getParametroSistema(69,$IdStatusNovo);
			}
			switch($IdStatusNovo){
				case 201:
					$StatusNovo = str_replace('Temporariamente','até '.$DataBloqueio, $StatusNovo);
					break;
				case 306:
					$StatusNovo = str_replace('Agendado','até '.$DataBloqueio, $StatusNovo);
					break;			
			}
			
			if($IdProtocolo != null){
				$Protocolo = "<p>Protocolo de atendimento: N° $IdProtocolo.</p>";
			}
			
			$TipoMensagem[Conteudo]	= str_replace('$IdContrato',$IdContrato,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$StatusNovo',$StatusNovo,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$StatusAnterior',$StatusAntigo,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Protocolo',$Protocolo,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomePlano',$linContrato[DescricaoServico],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linContrato[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linContrato[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			
			$TipoMensagem[Titulo]	= str_replace('$IdContrato',$IdContrato,$TipoMensagem[Titulo]);
			$TipoMensagem[Titulo]	= str_replace('$NomePlano',$linContrato[DescricaoServico],$TipoMensagem[Titulo]);

			$TipoMensagem[Assunto]	= str_replace('$IdContrato',$IdContrato,$TipoMensagem[Assunto]);		

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$Aux = explode("\n",$ParametroTipoMensagem['$_StatusNaoPermitidoEnvio']);
			for($i=0; $i<count($Aux); $i++){
				$Status = explode("-",$Aux[$i]);
				if(($IdStatusNovo >= $Status[0]) && ($IdStatusNovo <= $Status[1])){
					return false;
				}
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdContrato]				= $IdContrato;			
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linContrato[Email];		
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linContrato[IdPessoa];			
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviaListaPrevisaoBloqueio($IdLoja, $Lista){

		global $con, $Path;

		$IdTipoMensagem = 24;
		$emailEnvio		= getCodigoInterno(38,4);
		$url_sistema	= getParametroSistema(6,3);
		$local_Login	= 'automatico';	
		$ListaContrato	= '';
		$Contrato		= '';
		$Anexo			= 0;

		$IdContrato				= array_keys($Lista);
		$QtdContratoBloqueado	= count($IdContrato);
		
		if(count($IdContrato) < 100){
			$QtdContrato = count($IdContrato);
		}else{
			$QtdContrato = 100;
		}
		
		for($i=0; $i<$QtdContrato; $i++){
			if($Lista[$IdContrato[$i]][Moeda] != ''){
				$ListaContrato .="
				<tr>
					<td style='font-size: 10px;'>(".$Lista[$IdContrato[$i]][IdPessoa].")&nbsp;".$Lista[$IdContrato[$i]][Nome]."</td>
					<td style='font-size: 10px;'><a href='".$Lista[$IdContrato[$i]][UrlContrato].$Lista[$IdContrato[$i]][IdContrato]."' style='color: #000000;'>(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")&nbsp;".$Lista[$IdContrato[$i]][DescricaoServico]."</a></td>
					<td style='font-size: 10px; text-align:center;'>".$Lista[$IdContrato[$i]][DataVencimento]."</td>
					<td style='font-size: 10px; text-align:right;'>".$Lista[$IdContrato[$i]][Moeda].number_format($Lista[$IdContrato[$i]][ValorTotal],2,',','')."</td>
				</tr> \n";
			}else{
				$ListaContrato .=" 
				<tr>
					<td style='font-size: 10px;'>(".$Lista[$IdContrato[$i]][IdPessoa].")&nbsp;".$Lista[$IdContrato[$i]][Nome]."</td>
					<td style='font-size: 10px;'><a href='".$Lista[$IdContrato[$i]][UrlContrato].$Lista[$IdContrato[$i]][IdContrato]."' style='color: #000000;'>(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")&nbsp;".$Lista[$IdContrato[$i]][DescricaoServico]."</a></td>
					<td style='font-size: 10px; text-align:center;'>Bloqueio Agendado</td>
					<td style='font-size: 10px; text-align:right;'>&nbsp;</td>
				</tr> \n";
			}								
		}				
		
		#gerar arquivo txt
		$FileName = "temp/contratos_previsao_bloqueio_".date("Y-m-d").".txt";
		@unlink($Path.$FileName);
		
		$File = fopen($Path.$FileName, "a");
	
		$Linha = "LISTA DE CONTRATOS BLOQUEADOS \r\n\r\n";
		$Linha .= "Segue abaixo a lista de contratos a serem bloqueados no dia ".date('d/m/Y').". Loja ".$IdLoja.". \r\n\r\n";
		$Linha .= preenche_tam("Nome Pessoa", 35, 'X');
		$Linha .= preenche_tam("Descrição Serviço", 35, 'X');
		$Linha .= preenche_tam("Vencimento", 15, 'X');
		$Linha .= preenche_tam("Valor", 25, 'X');
		$Linha .= "\r\n";
		
		for($i=0; $i<count($IdContrato); $i++){
			if($Lista[$IdContrato[$i]][Moeda] != ''){
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdPessoa].") ".$Lista[$IdContrato[$i]][Nome], 35, 'X');
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")", 10, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][DescricaoServico], 25, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][DataVencimento], 15, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][Moeda].number_format($Lista[$IdContrato[$i]][ValorTotal],2,',',''), 25, 'X');
				$Linha .= "\r\n";
			}else{
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdPessoa].") ".$Lista[$IdContrato[$i]][Nome], 35, 'X');
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")", 10, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][DescricaoServico], 25, 'X');
				$Linha .= preenche_tam("Bloqueio Agendado", 25, 'X');
				$Linha .= "\r\n";					
			}			
		}			
		
		$Anexo	= $Path.$FileName;		

		fwrite($File, $Linha);			
		fclose($File);			
		
		$sql = "select
					Pessoa.IdPessoa
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select
					Assunto,
					Conteudo,
					IdStatus
				from
					TipoMensagem
				where
					IdLoja = $IdLoja and
					IdTipoMensagem = $IdTipoMensagem";
		$res = mysql_query($sql,$con);
		$TipoMensagem = mysql_fetch_array($res);

		if($TipoMensagem[IdStatus] != 1){
			return false;
		}

		$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('$url_sistema',$url_sistema,$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo]	= str_replace('$dia',date("d/m/Y"),$TipoMensagem[Conteudo]);			
		$TipoMensagem[Conteudo]	= str_replace('$IdLoja',$IdLoja,$TipoMensagem[Conteudo]);	
		$TipoMensagem[Conteudo]	= str_replace('$Lista',$ListaContrato,$TipoMensagem[Conteudo]);			
		$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);		
		if($QtdContratoBloqueado > 100){
			$TipoMensagem[Conteudo]	.= "<br><b>Atenção: Existe mais de 100 contratos que seram bloqueados, segue em anexo a lista completa.</b><br>"; 
		}else{
			$TipoMensagem[Conteudo]	.= "<br><b>Atenção: Segue em anexo a lista completa.</b><br>"; 
		}
		
		$TipoMensagem[Assunto]	= str_replace('$DataBloqueio',date("d/m/Y"),$TipoMensagem[Assunto]);	
		
		$GeraMensagem[IdLoja]					= $IdLoja;
		$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
		$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];	
		$GeraMensagem[Email]					= $emailEnvio;	
		$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
		$GeraMensagem[IdPessoa]					= $linEmpresa[IdPessoa];	
		$GeraMensagem[Login]					= $local_Login;	
		$GeraMensagem[Anexo][0]					= $Anexo;
		$GeraMensagem[LocalAnexo]				= "temp";
		$GeraMensagem[EnviarMensagem]			= false;		
		
		return geraMensagem($GeraMensagem);	
	}	

	function enviaTesteContaEmail($IdLoja, $IdContaEmail){
		global $con, $local_Email;
		
		$sql = "select
					ContaEmail.NomeRemetente,
					ContaEmail.EmailRemetente,
					ContaEmail.NomeResposta,
					ContaEmail.EmailResposta,
					ContaEmail.ServidorSMTP,
					ContaEmail.RequerAutenticacao,
					ContaEmail.SMTPSeguro,
					ContaEmail.Porta,
					ContaEmail.Usuario,
					ContaEmail.Senha,
					ContaEmail.QtdTentativaEnvio,
					ContaEmail.IntervaloEnvio,
					concat('[',ContaEmail.IdContaEmail,'] ',ContaEmail.DescricaoContaEmail) DescricaoContaEmail,
					Loja.DescricaoLoja,
					('Teste de Conta de E-mail') Assunto
				from
					ContaEmail,
					Loja
				where
					ContaEmail.IdLoja = $IdLoja and
					ContaEmail.IdContaEmail = $IdContaEmail and
					ContaEmail.IdLoja = Loja.IdLoja;";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		$url_sistema = getParametroSistema(6,3).'/img/personalizacao/logo_cab.gif';
		$Conteudo = "\n\t<div style='text-align:center; padding-bottom:10px;'><img src='".$url_sistema."' /></div>\n\t<div style='font: normal 11px Verdana, Arial, Times; color: #333;'>\n\t\t<p style='background-color: #004492; color: #fff; font-weight: bold; text-align: center; padding-top: 4px; padding-bottom: 4px;'>".$lin[DescricaoLoja]."</p>\n\t\t<div style='padding-top: 10px;'><b>Teste da Conta de E-mail:</b> ".$lin[DescricaoContaEmail]."<br />Teste realizado com sucesso.</div>\n\t\t<br />\n\t\t<div style='border-top: 1px solid #ccc; text-align: center; font-size: 10px; padding: 2px;'><a style='text-decoration: none; color: #ccc;' href='http://www.cntsistemas.com.br/'>ConAdmin - Sistemas Adminstrativos de Qualidade - CNTSistemas</a></div>\n\t</div>";
		$lin[ServidorSMTP]		= trim($lin[ServidorSMTP]);
		$lin[EmailRemetente]	= trim($lin[EmailRemetente]);
		$lin[NomeRemetente]		= trim($lin[NomeRemetente]);
		$lin[EmailResposta]		= trim($lin[EmailResposta]);
		$lin[NomeResposta]		= trim($lin[NomeResposta]);
		$lin[Usuario]			= trim($lin[Usuario]);
		$lin[Senha]				= trim($lin[Senha]);
		
		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		
		$mail->Priority		= 3;
		$mail->Encoding		= '8bit';
		$mail->CharSet		= 'iso-8859-1';
		$mail->WordWrap		= 0;
		$mail->Helo			= $lin[ServidorSMTP];
		$mail->PluginDir	= $INCLUDE_DIR;
		$mail->Mailer		= 'smtp';
		$mail->TimeOut		= 10;	
		$mail->From			= $lin[EmailRemetente];
		$mail->FromName 	= $lin[NomeRemetente];
		$mail->Subject		= $lin[Assunto]; 
		$mail->Host			= $lin[ServidorSMTP];
		$mail->Port			= $lin[Porta];
		
		if($lin[RequerAutenticacao] == 1){
			$mail->SMTPAuth = true;		
		}
				
		switch($lin[SMTPSeguro]){
			case 1:
				$mail->SMTPSecure 	= "ssl";
				break;

			case 2:
				$mail->SMTPSecure 	= "tls";
				break;
		}	
		
		$mail->Username 	= $lin[Usuario];
		$mail->Password 	= $lin[Senha];
		$mail->Sender		= $lin[EmailRemetente];
		$mail->Body			= $Conteudo;
		
		$mail->IsHTML(true); 
		$mail->AddReplyTo($lin[EmailResposta], $lin[NomeResposta]);

		if($local_Email == ''){
			$local_Email = $lin[EmailRemetente];
		}
		$AddAddress = explode(";",$local_Email);

		for($i = 0; $i < count($AddAddress); $i++){
			$AddAddress[$i] = trim($AddAddress[$i]);
			
			if($AddAddress[$i] != ''){
				$mail->AddAddress($AddAddress[$i],$AddAddress[$i]);
			}
		}
		
		// Tentativas de Envio
		for($i = 1; $i <= $lin[QtdTentativaEnvio]; $i++){	
			if(@$mail->Send() == true){
				$IdStatus = 2;
				break;
			} else{
				$IdStatus = 3;
				sleep($lin[IntervaloEnvio]);
			}
		}
		
		if($IdStatus == 2){
			return 149;
		} else{
			return 148;
		}
	}

	
	function enviarEmailMalaDireta($IdLoja, $IdMalaDireta, $IdTipoMensagem, $Email, $IdPessoa){
		global $con, $local_Login;		
		
		$url_sistema			= getParametroSistema(6,3);

		$sql = "select					
					ExtModelo
				from
					MalaDireta
				where
					IdLoja = $IdLoja and
					IdMalaDireta = $IdMalaDireta";
		$resMalaDireta = mysql_query($sql,$con);
		$linMalaDireta = mysql_fetch_array($resMalaDireta);			
		
		$sqlMensagem = "select 		
							Assunto,
							Titulo,
							Conteudo,
							IdStatus
						from 
							TipoMensagem
						where 
							IdLoja = $IdLoja and 
							IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){

			if($TipoMensagem[IdStatus] != 1){
				return false;
			}

			$EndeArquivo = "anexos/mala_direta/$IdLoja/$IdMalaDireta/";
			$LinkArquivo = $url_sistema."/modulos/administrativo/$EndeArquivo";

			if($linMalaDireta[ExtModelo] != ''){			
				switch(strtolower($linMalaDireta[ExtModelo])){
					case "jpg":
						$CodeHTML = "<table cellspacing='0' cellpadding='0'>";
						
						for($y = 0; ; $y++){
							$CodeHTML .= "<tr>";
							
							for($x = 0; $x < 2; $x++){								
								$name = md5($x."_".$y).".jpg";

								if(!file_exists("../$EndeArquivo$name")){																										
									$CodeHTML .= "</tr>";
									break 2;
								}
								
								$CodeHTML .= "<td><img style='float:left; padding:0; margin:0;' src='$LinkArquivo$name' /></td>";			
							}							
							$CodeHTML .= "</tr>";
						}
						
						$CodeHTML .= "</table>";						
						break;
					default:
						$CodeHTML = @file_get_contents($LinkArquivo.$IdMalaDireta.".".$linMalaDireta[ExtModelo]);
				}
			}else{
				$CodeHTML = $TipoMensagem[Conteudo];
			}		

			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $Email;			
			$GeraMensagem[IdMalaDireta]				= $IdMalaDireta;				
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
			$GeraMensagem[Conteudo]					= $CodeHTML;
			$GeraMensagem[IdPessoa]					= $IdPessoa;			
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	

			return geraMensagem($GeraMensagem);		
		}
	}

	function enviarEmailAlteracaoServico($IdLoja, $IdContratoAntigo, $IdContratoNovo){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 27;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		$sql = "select
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,
					Servico.DescricaoServico
				from
					Contrato,
					Pessoa,
					Servico
				where
					Contrato.IdLoja = $IdLoja and
					Contrato.IdLoja = Servico.IdLoja and
					Contrato.IdServico = Servico.IdServico and
					Contrato.IdPessoa = Pessoa.IdPessoa and
					Contrato.IdContrato = $IdContratoNovo";
		$resContratoNovo = mysql_query($sql,$con);
		$linContratoNovo = mysql_fetch_array($resContratoNovo);	
		
		if(trim($linContratoNovo[Email]) == ''){			
			return false;
		}

		if($linContratoNovo[TipoPessoa] == 1){
			$linContratoNovo[Nome] = $linContratoNovo[NomeRepresentante];
		}

		$sql = "select
					Servico.DescricaoServico
				from
					Contrato,					
					Servico
				where
					Contrato.IdLoja = $IdLoja and
					Contrato.IdLoja = Servico.IdLoja and
					Contrato.IdServico = Servico.IdServico and					
					Contrato.IdContrato = $IdContratoAntigo";
		$resContratoAntigo = mysql_query($sql,$con);
		$linContratoAntigo = mysql_fetch_array($resContratoAntigo);	

		$sqlMensagem = "select 		
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){

			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
									
			$TipoMensagem[Conteudo]	= str_replace('$IdContratoNovo',$IdContratoNovo,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$IdContratoAntigo',$IdContratoAntigo,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoServicoNovo',$linContratoNovo[DescricaoServico],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoServicoAntigo',$linContratoAntigo[DescricaoServico],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
	
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}			
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linContratoNovo[Email];					
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[IdPessoa]					= $linContratoNovo[IdPessoa];			
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	

			return geraMensagem($GeraMensagem);		
		}
	}
	
	function enviarEmailSICIExportacao($IdLoja, $emailEnvio, $PeriodoApuracao, $local_FormatoArquivo, $IdMetodoExportacao){
		global $con, $Path, $local_Login;
		
		$IdTipoMensagem 		= 28;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$Anexo					= 0;
		
		include("rotinas/exportar_sici_".$local_FormatoArquivo.".php");
		
		$sql = "select
					Pessoa.Nome,
					Pessoa.IdPessoa
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);
		
		$sql = "select
					Titulo,
					Assunto,
					Conteudo,
					IdStatus
				from
					TipoMensagem
				where
					IdLoja = $IdLoja and
					IdTipoMensagem = $IdTipoMensagem";
		$res = mysql_query($sql,$con);
		$TipoMensagem = mysql_fetch_array($res);

		if($TipoMensagem[IdStatus] != 1){
			return false;
		}
		
		$TipoMensagem[Conteudo]	= str_replace('$PeriodoApuracao',$PeriodoApuracao,$TipoMensagem[Conteudo]);	
		$TipoMensagem[Titulo]	= str_replace('$PeriodoApuracao',$PeriodoApuracao,$TipoMensagem[Titulo]);	
		$TipoMensagem[Assunto]	= str_replace('$PeriodoApuracao',$PeriodoApuracao,$TipoMensagem[Assunto]);	
		
		if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
			$TipoMensagem[Assunto] = str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
		} else{
			$TipoMensagem[Assunto] = str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
		}
		
		$GeraMensagem[IdLoja]					= $IdLoja;
		$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;
		$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];	
		$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];	
		$GeraMensagem[Email]					= $emailEnvio;	
		$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
		$GeraMensagem[IdPessoa]					= $linEmpresa[IdPessoa];	
		$GeraMensagem[Login]					= $local_Login;	
		$GeraMensagem[Anexo][0]					= $Anexo;
		$GeraMensagem[LocalAnexo]				= "temp";
		$GeraMensagem[EnviarMensagem]			= false;		
		
		$IdHistoricoMensagem = geraMensagem($GeraMensagem);
		
		enviaMensagem($IdLoja, $IdHistoricoMensagem);
		// Faça o redirecionamento no arquivo que chama esta função. Ao ver este texto, apague!
		header("Location: listar_reenvio_mensagem.php?IdHistoricoMensagem=".$IdHistoricoMensagem);
	}
	
	function enviaClientesBloqueadosServico($IdLoja, $IdServico, $Lista){
		global $con, $Path;
		
		$IdTipoMensagem = 30;
		$url_sistema	= getParametroSistema(6,3);
		$local_Login	= 'automatico';	
		$ListaContrato	= '';
		$Contrato		= '';
		$Anexo			= 0;
		
		$IdContrato				= array_keys($Lista);
		$QtdContratoBloqueado	= count($IdContrato);
		
		if(count($IdContrato) < 100){
			$QtdContrato = count($IdContrato);
		} else{
			$QtdContrato = 100;
		}
		
		for($i = 0; $i < $QtdContrato; $i++){
			if($Lista[$IdContrato[$i]][Moeda] != ''){
				$ListaContrato .="
				<tr>
					<td style='font-size: 10px;'>(".$Lista[$IdContrato[$i]][IdPessoa].")&nbsp;".substr($Lista[$IdContrato[$i]][Nome],0,35)."</td>
					<td style='font-size: 10px;'><a href='".$Lista[$IdContrato[$i]][UrlContrato].$Lista[$IdContrato[$i]][IdContrato]."' style='color: #000000;'>(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")&nbsp;".substr($Lista[$IdContrato[$i]][DescricaoServico],0,20)."</a></td>
					<td style='font-size: 10px; text-align:center;'>".$Lista[$IdContrato[$i]][DataVencimento]."</td>
					<td style='font-size: 10px; text-align:right;'>".$Lista[$IdContrato[$i]][Moeda].$Lista[$IdContrato[$i]][ValorTotal]."</td>
				</tr> \n";
			} else{
				$ListaContrato .=" 
				<tr>
					<td style='font-size: 10px;'>(".$Lista[$IdContrato[$i]][IdPessoa].")&nbsp;".substr($Lista[$IdContrato[$i]][Nome],0,35)."</td>
					<td style='font-size: 10px;'><a href='".$Lista[$IdContrato[$i]][UrlContrato].$Lista[$IdContrato[$i]][IdContrato]."' style='color: #000000;'>(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")&nbsp;".substr($Lista[$IdContrato[$i]][DescricaoServico],0,20)."</a></td>
					<td style='font-size: 10px; text-align:center;'>Bloqueio Agendado</td>
					<td style='font-size: 10px; text-align:right;'>&nbsp;</td>
				</tr> \n";
			}
		}
		#gerar arquivo txt
		$FileName = "temp/contratos_bloqueados_servico_".date("Y-m-d").".txt";
		@unlink($Path.$FileName);
		
		$File = fopen($Path.$FileName, "a");
		
		$Linha = "LISTA DE CONTRATOS BLOQUEADOS \r\n\r\n";
		$Linha .= "Segue abaixo a lista de clientes bloqueados no dia ".date('d/m/Y').". Loja ".$IdLoja." (Serviço: ".$IdServico."). \r\n\r\n";
		$Linha .= preenche_tam("Nome Pessoa", 100, 'X');
		$Linha .= preenche_tam("Descrição Serviço", 110, 'X');
		$Linha .= preenche_tam("Vencimento", 30, 'X');
		$Linha .= preenche_tam("Valor", 25, 'X');
		$Linha .= "\r\n";
		
		for($i = 0; $i < count($IdContrato); $i++){
			if($Lista[$IdContrato[$i]][Moeda] != ''){
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdPessoa].") ".$Lista[$IdContrato[$i]][Nome], 100, 'X');
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")", 10, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][DescricaoServico], 100, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][DataVencimento], 30, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][Moeda].$Lista[$IdContrato[$i]][ValorTotal], 25, 'X');
				$Linha .= "\r\n";
			} else{
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdPessoa].") ".$Lista[$IdContrato[$i]][Nome], 100, 'X');
				$Linha .= preenche_tam("(".$Lista[$IdContrato[$i]][IdLoja].")(".$Lista[$IdContrato[$i]][IdContrato].")", 10, 'X');
				$Linha .= preenche_tam($Lista[$IdContrato[$i]][DescricaoServico], 100, 'X');
				$Linha .= preenche_tam("Bloqueio Agendado", 30, 'X');
				$Linha .= "\r\n";
			}
		}
		
		$Anexo = $Path.$FileName;
		
		fwrite($File, $Linha);
		fclose($File);
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);
		
		$sql = "select
					Servico.IdServico,
					Servico.DescricaoServico,
					Servico.EmailListaBloqueados
				from
					Servico
				where
					Servico.IdLoja = $IdLoja and
					Servico.IdServico = $IdServico";
		$resServico = mysql_query($sql,$con);
		$linServico = mysql_fetch_array($resServico);
		
		$sql = "select
					Titulo,
					Assunto,
					Conteudo,
					IdStatus
				from
					TipoMensagem
				where
					IdLoja = $IdLoja and
					IdTipoMensagem = $IdTipoMensagem";
		$res = mysql_query($sql,$con);
		$TipoMensagem = mysql_fetch_array($res);
		
		if($TipoMensagem[IdStatus] != 1){
			return false;
		}
		
		$TipoMensagem[Conteudo] = str_replace('"',"'",$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo] = str_replace('$url_sistema',$url_sistema,$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo] = str_replace('$dia',date("d/m/Y"),$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo] = str_replace('$IdLoja',$IdLoja,$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo] = str_replace('$Lista',$ListaContrato,$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo] = str_replace('$IdServico',$linServico[IdServico],$TipoMensagem[Conteudo]);
		$TipoMensagem[Conteudo] = str_replace('\'','"',$TipoMensagem[Conteudo]);
		
		if($QtdContratoBloqueado > 100){
			$TipoMensagem[Conteudo] .= "<br><b>Atenção: Existe mais de 100 contratos que foram bloqueados, segue em anexo a lista completa.</b><br>"; 
		} else{
			$TipoMensagem[Conteudo] .= "<br><b>Atenção: Segue em anexo a lista completa.</b><br>"; 
		}
		
		$TipoMensagem[Assunto]	= str_replace('$DataBloqueio',date("d/m/Y"),$TipoMensagem[Assunto]);
		$TipoMensagem[Assunto]	= str_replace('$IdLoja',$IdLoja,$TipoMensagem[Assunto]);
		$TipoMensagem[Assunto]	= str_replace('$IdServico',$linServico[IdServico],$TipoMensagem[Assunto]);
		
		if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
		} else{
			$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
		}
		
		$TipoMensagem[Titulo] = str_replace('$IdServico',$linServico[IdServico],$TipoMensagem[Titulo]);
		$TipoMensagem[Titulo] = str_replace('$DescricaoServico',$linServico[DescricaoServico],$TipoMensagem[Titulo]);
		
		$GeraMensagem[IdLoja]			= $IdLoja;
		$GeraMensagem[IdTipoMensagem]	= $IdTipoMensagem;
		$GeraMensagem[Titulo]			= $TipoMensagem[Titulo];
		$GeraMensagem[Assunto]			= $TipoMensagem[Assunto];
		$GeraMensagem[Email]			= $linServico[EmailListaBloqueados];
		$GeraMensagem[Conteudo]			= $TipoMensagem[Conteudo];
		$GeraMensagem[IdPessoa]			= $linEmpresa[IdPessoa];
		$GeraMensagem[Login]			= $local_Login;
		$GeraMensagem[Anexo][0]			= $Anexo;
		$GeraMensagem[LocalAnexo]		= "temp";
		$GeraMensagem[EnviarMensagem]	= false;
		
		return geraMensagem($GeraMensagem);
	}
	
	# EM CONSTRUÇÃO
	function avisoVencimentoIndependente($IdLoja, $IdContaReceber){
		global $con;
		
		$IdTipoMensagem	= 31;//Boleto Disponível
		$local_Login	= 'automatico';
		$url_sistema	= getParametroSistema(6,3);
		$url_cda		= $url_sistema."/central";
		
		$ParametroTipoMensagem = parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		
		$sql = "SELECT
					ValorCodigoInterno Tipo
				FROM
					CodigoInterno
				WHERE
					IdLoja = $IdLoja AND
					IdGrupoCodigoInterno = 3 AND
					IdCodigoInterno = 165";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$Tipo = strtolower($lin[Tipo]);
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);
		
		$sql = "select  
					min(ContaReceberDados.IdContaReceber) IdContaReceber, 
					ContaReceberDados.DataVencimento, 
					ContaReceberDados.NumeroDocumento, 
					ContaReceberDados.MD5,
					(ContaReceberDados.ValorLancamento - ContaReceberDados.ValorDesconto + ContaReceberDados.ValorDespesas) ValorTotal, 
					LocalCobranca.IdLocalCobrancaLayout,
					LancamentoFinanceiro.IdProcessoFinanceiro,
					LancamentoFinanceiro.IdContrato
				from 
					Contrato,
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber,
					ContaReceberDados,
					LocalCobranca
				where 
					Contrato.IdLoja = $IdLoja and
					Contrato.IdLoja = LancamentoFinanceiro.IdLoja and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = ContaReceberDados.IdLoja and
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
					Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberDados.IdContaReceber and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberDados.IdStatus = 1 and
					LocalCobranca.IdTipoLocalCobranca != 3 and
					Contrato.IdContrato = LancamentoFinanceiro.IdContrato and
					ContaReceberDados.IdContaReceber = $IdContaReceber
				group by
					Contrato.IdContrato";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$sql = "select
					Contrato.IdContrato,
					Servico.DiasLimiteBloqueio,
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,
					Pessoa.CampoExtra1,
					Pessoa.CampoExtra2,
					Pessoa.CampoExtra3,
					Pessoa.CampoExtra4
				from
					Pessoa,
					Servico,
					Contrato
				where
					Contrato.IdLoja = $IdLoja and
					Contrato.IdLoja = Servico.IdLoja and 
					Contrato.IdServico = Servico.IdServico and
					Contrato.IdPessoa = Pessoa.IdPessoa and	
					Contrato.IdContrato = $lin[IdContrato]";
		$res2 = mysql_query($sql,$con);
		$lin2 = mysql_fetch_array($res2);
		
		if($lin2[Email] == ''){
			return false;
		}
		
		if($lin2[TipoPessoa] == 1){
			$nome_responsavel = $lin2[NomeRepresentante]." (".$lin2[Nome].")";
		} else{
			$nome_responsavel = $lin2[Nome];
		}
		
		$NumeroDocumento		= $lin[NumeroDocumento];
		$CampoExtra1			= $lin2[CampoExtra1];
		$CampoExtra2			= $lin2[CampoExtra2];
		$CampoExtra3			= $lin2[CampoExtra3];
		$CampoExtra4			= $lin2[CampoExtra4];
		$nome					= $lin2[Nome];
		$DataVencimento			= dataConv($lin[DataVencimento],'Y-m-d','d/m/Y');
		$ValorTotal				= getParametroSistema(5,1)." ".number_format($lin[ValorTotal],2,',','');
		
		//Barrado por Gilmaico para fins futuros.
		/*$aux  = getParametroSistema(6,3)."/modulos/administrativo/boleto.php";
		$Link = getParametroSistema(6,3)."/modulos/cda/aviso_titulo_vencido.php?LinkBoleto=$aux&Tipo=$Tipo&ContaReceber=".$lin[MD5]."&IdContaReceber=".$lin[IdContaReceber]."&IdLoja=$IdLoja";*/
		
		//$Tipo = strtolower(getCodigoInterno(3,165));
		$aux 		.= "$url_sistema/modulos/administrativo/boleto.php";
		$LinkBoleto .= "$url_sistema/modulos/cda/aviso_titulo_vencido.php?LinkBoleto=".$aux."&Tipo=".$Tipo."&ContaReceber=".$lin[MD5]."&IdContaReceber=".$lin[IdContaReceber]."&IdLoja=".$IdLoja; 
				
		$FaturaUnica			= "<tr>
										<td style='text-align:center; font-size: 11px'>$NumeroDocumento</td>
										<td style='text-align:center; font-size: 11px'>$DataVencimento</td>
										<td style='text-align:right; font-size: 11px'>$ValorTotal</td>
										<td style='text-align:center; font-size: 11px'><a href='$LinkBoleto' target='_blank'>2&ordf; Via</a></td>
									</tr>";
		$Faturas				= '';
		
		$sqlFaturas = "select 
							Demonstrativo.IdContaReceber,
							ContaReceberDados.NumeroDocumento,
							ContaReceberDados.DataVencimento,
							(ContaReceberDados.ValorLancamento - ContaReceberDados.ValorDesconto + ContaReceberDados.ValorDespesas) ValorTotal,
							LocalCobranca.IdLocalCobrancaLayout
						from
							Demonstrativo,
							ContaReceberBaseVencimento,
							ContaReceberDados,
							LocalCobranca
						where
							Demonstrativo.IdLoja = $IdLoja and
							Demonstrativo.IdPessoa = $lin2[IdPessoa] and
							Demonstrativo.IdLoja = ContaReceberBaseVencimento.IdLoja and
							Demonstrativo.IdLoja = ContaReceberDados.IdLoja and
							Demonstrativo.IdLoja = LocalCobranca.IdLoja and
							Demonstrativo.IdContaReceber = ContaReceberBaseVencimento.IdContaReceber and
							Demonstrativo.IdContaReceber = ContaReceberDados.IdContaReceber and
							ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
							ContaReceberBaseVencimento.BaseVencimento > 0 and
							ContaReceberBaseVencimento.IdStatus = 1
						order by
							ContaReceberDados.IdContaReceber";
		$resFaturas = mysql_query($sqlFaturas,$con);
		
		while($linFaturas = mysql_fetch_array($resFaturas)){
			$LinkFaturas	= getParametroSistema(6,3)."/modulos/administrativo/boleto.php?Tipo=$Tipo&ContaReceber=".$lin[MD5];
			$ValorTotal		= getParametroSistema(5,1)." ".number_format($linFaturas[ValorTotal],2,',','');
			$DataVencimento	= dataConv($linFaturas[DataVencimento],'Y-m-d','d/m/Y');
			$Faturas		.= "<tr>
									<td style='text-align:center; font-size: 11px'>$linFaturas[NumeroDocumento]</td>
									<td style='text-align:center; font-size: 11px'>$DataVencimento</td>
									<td style='text-align:right; font-size: 11px'>$ValorTotal</td>
									<td style='text-align:center; font-size: 11px'><a href='$LinkFaturas' target='_blank'>2&ordf; Via</a></td>
								</tr>";
		}
		
		$sqlMensagem = "select 
							TipoMensagem.Conteudo,
							TipoMensagem.Assunto,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		
		if($TipoMensagem = mysql_fetch_array($resMensagem)){
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$TipoMensagem[Conteudo]	= str_replace('$nome_responsavel',$nome_responsavel,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$nome',$nome,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$CampoExtra1',$CampoExtra1,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$CampoExtra2',$CampoExtra2,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$CampoExtra3',$CampoExtra3,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$CampoExtra4',$CampoExtra4,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$FaturaUnica',$FaturaUnica,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Faturas',$Faturas,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$_ColorBackgroundNomeEmpresa',$ParametroTipoMensagem['$_ColorBackgroundNomeEmpresa'],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$link_boleto',$Link,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$url_sistema',$url_sistema,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_cda,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Assunto]	= str_replace('$DataVencimento',$DataVencimento,$TipoMensagem[Assunto]);
			
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto] = str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			} else{
				$TipoMensagem[Assunto] = str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$lin2[Email]					= trim($lin2[Email]);
			$GeraMensagem[IdLoja]			= $IdLoja;
			$GeraMensagem[IdTipoMensagem]	= $IdTipoMensagem;
			$GeraMensagem[Assunto]			= $TipoMensagem[Assunto];
			$GeraMensagem[Email]			= $lin2[Email];
			$GeraMensagem[Conteudo]			= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]			= $lin2[IdPessoa];
			$GeraMensagem[IdContaReceber]	= $lin[IdContaReceber];
			$GeraMensagem[Login]			= $local_Login;
			
			return geraMensagem($GeraMensagem);
		}
	}
	
	# Remover o parametro $EmAtendimento pois esta verificação será feita antes da chamada da função.
	function enviarSMSAtendenteMudancaStatusOrdemServico($IdLoja, $IdOrdemServico, $EmAtendimento = 1){
		global $con, $Path;
		
		$IdTipoMensagem 		= 32;		
		$local_Login			= "automatico";	

		$sql = "select
					IdPessoa,
					DataAgendamentoAtendimento,
					LoginAtendimento,
					IdServico,
					IdStatus
				from
					OrdemServico
				where
					IdLoja = $IdLoja and
					IdOrdemServico = $IdOrdemServico";
		$resOS = mysql_query($sql,$con);
		$linOS = mysql_fetch_array($resOS);

		$Status = getParametroSistema(40,$linOS[IdStatus]);

		$sql = "select
					DescricaoServicoSMS
				from
					Servico
				where
					IdLoja = $IdLoja and
					IdServico = $linOS[IdServico]";
		$resServico = mysql_query($sql,$con);
		$linServico = mysql_fetch_array($resServico);

		if(trim($linServico[DescricaoServicoSMS]) == ""){
			return false;
		}

		$sql = "select
					IdPessoa				
				from
					Usuario
				where
					Login = '$linOS[LoginAtendimento]'";
		$resAtendente = mysql_query($sql,$con);
		$linAtendente = mysql_fetch_array($resAtendente);
		
		if(getCelularPessoa($linAtendente[IdPessoa]) != ''){
			$Celular = getCelularPessoa($linAtendente[IdPessoa]);
		}else{
			return false;
		}
			
		$sql = "select
					Titulo,
					Assunto,
					Conteudo,
					IdStatus
				from
					TipoMensagem
				where
					IdLoja = $IdLoja and
					IdTipoMensagem = $IdTipoMensagem";
		$res = mysql_query($sql,$con);
		$TipoMensagem = mysql_fetch_array($res);

		if($TipoMensagem[IdStatus] != 1){
			return false;
		}		
		
		$TipoMensagem[Conteudo]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Conteudo]);	
		$TipoMensagem[Conteudo]	= str_replace('$Status',$Status,$TipoMensagem[Conteudo]);
		if($linOS[DataAgendamentoAtendimento] != ''){
			$linOS[DataAgendamentoAtendimento] = dataConv($linOS[DataAgendamentoAtendimento], 'Y-m-d H:i:s','d/m/Y H:i:s');
			$TipoMensagem[Conteudo]	= str_replace('$DataAgendamento', "Agendado para: ".$linOS[DataAgendamentoAtendimento],$TipoMensagem[Conteudo]);	
		}else{
			$TipoMensagem[Conteudo]	= str_replace('$DataAgendamento','',$TipoMensagem[Conteudo]);	
		}
		$TipoMensagem[Conteudo]	= str_replace('$DescricaoServicoSMS',$linServico[DescricaoServicoSMS],$TipoMensagem[Conteudo]);
		
		$GeraMensagem[IdLoja]					= $IdLoja;
		$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;	
		$GeraMensagem[Celular]					= $Celular;	
		$GeraMensagem[IdOrdemServico]			= $IdOrdemServico;
		$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
		$GeraMensagem[IdPessoa]					= $linOS[IdPessoa];	
		$GeraMensagem[Login]					= $local_Login;	
		$GeraMensagem[EnviarMensagem]			= true;		
		
		return geraMensagem($GeraMensagem);		
	}

	
	function enviarEmailOrdemServicoAtendente($IdLoja, $IdOrdemServico){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 33;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= "/modulos/administrativo/imprimir_ordem_servico.php?IdOrdemServico=".$IdOrdemServico."";

		$sql = "select
					Pessoa.Nome					
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "SELECT
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email,					
					Servico.DescricaoServico,					
					OrdemServico.DescricaoOS,
					OrdemServico.DataCriacao,
					OrdemServico.LoginAtendimento
				FROM
					Servico,
					OrdemServico,
					Pessoa,
					Usuario
				WHERE
					Servico.IdLoja = $IdLoja AND
					Servico.IdLoja = OrdemServico.IdLoja AND
					Servico.IdServico = OrdemServico.IdServico AND
					OrdemServico.IdOrdemServico = $IdOrdemServico AND
					Usuario.Login	= OrdemServico.LoginAtendimento AND
					Pessoa.IdPessoa = Usuario.IdPessoa";
		$resOrdemServico = mysql_query($sql,$con);
		$linOrdemServico = mysql_fetch_array($resOrdemServico);
		
		if($linOrdemServico[Email] == ''){
			return false;
		}

		if($linOrdemServico[TipoPessoa] == 1){
			$linOrdemServico[Nome] = $linOrdemServico[NomeRepresentante];
		}

		$linOrdemServico[DataCriacao] = dataConv($linOrdemServico[DataCriacao],'Y-m-d','d/m/Y');

		$sqlMensagem = "select 		
							TipoMensagem.Titulo,
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if(mysql_num_rows($resMensagem) > 0){	
			
			$TipoMensagem = mysql_fetch_array($resMensagem);
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$linOrdemServico[DescricaoOS] = str_replace("\n","<br>",$linOrdemServico[DescricaoOS]);
			
			$TipoMensagem[Conteudo]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoServico',$linOrdemServico[DescricaoServico],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataCriacao',$linOrdemServico[DataCriacao],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linOrdemServico[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linOrdemServico[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$AcessoOrdemServico',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			
			$TipoMensagem[Titulo]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Titulo]);
			
			$TipoMensagem[Assunto]	= str_replace('$IdOrdemServico',$IdOrdemServico,$TipoMensagem[Assunto]);
			
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linOrdemServico[Email];		
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linOrdemServico[IdPessoa];	
			$GeraMensagem[IdOrdemServico]			= $IdOrdemServico;
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	
			
			return geraMensagem($GeraMensagem);		
		}
	}
	
	function enviarTermoQuitacaoAnual($IdLoja, $IdPessoa,$Exercicio){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 34;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_cda				.= $url_sistema.'/central';

		$sql = "select
					Pessoa.Nome					
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "SELECT
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email
				FROM
					Pessoa
				WHERE
					(
						Pessoa.IdLoja = $IdLoja or
						Pessoa.IdLoja is null
					) and
					Pessoa.IdPessoa = $IdPessoa";
		$resPessoa = mysql_query($sql,$con);
		$linPessoa = mysql_fetch_array($resPessoa);
		
		if($linPessoa[Email] == ''){
			return false;
		}

		if($linPessoa[TipoPessoa] == 1){
			$linPessoa[Nome] = $linPessoa[NomeRepresentante];
		}

		$linPessoa[DataCriacao] = dataConv($linPessoa[DataCriacao],'Y-m-d','d/m/Y');

		$sqlMensagem = "select 		
							TipoMensagem.Titulo,
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){	
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$TipoMensagem[Conteudo]	= str_replace('$Exercicio',$Exercicio,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataCriacao',$linPessoa[DataCriacao],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linPessoa[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linPessoa[Email],$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_cda,$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			
			$TipoMensagem[Titulo]	= str_replace('$Exercicio',$Exercicio,$TipoMensagem[Titulo]);
			
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$TipoMensagem[Assunto]	= str_replace('$Ano',$Exercicio,$TipoMensagem[Assunto]);
			$TipoMensagem[Titulo]	= str_replace('$Ano',$Exercicio,$TipoMensagem[Titulo]);
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $IdTipoMensagem;			
			$GeraMensagem[Email]					= $linPessoa[Email];		
			$GeraMensagem[Titulo]					= $TipoMensagem[Titulo];
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linPessoa[IdPessoa];
			$GeraMensagem[Login]					= $local_Login;				
			$GeraMensagem[EnviarMensagem]			= false;	
			
			return geraMensagem($GeraMensagem);		
		}
	}
	
	function enviarEmailMonitorarFinanceiro($IdLoja, $IdPessoa){
		global $con;
		global $local_Email;
		global $local_Login;
		
		$IdTipoMensagem			= 35;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3)."/central";
		$dataAtual				= date("Ymd");
		$Demonstrativo			= "";
		
		$sqlEmpresa = "select
							Pessoa.IdPessoa,
							Pessoa.Nome
						from
							Loja,
							Pessoa					
						where
							Loja.IdLoja = $IdLoja and
							Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sqlEmpresa,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	
		
		$sqlCliente = "select
							Pessoa.IdPessoa,
							Pessoa.TipoPessoa,
							Pessoa.NomeRepresentante,
							Pessoa.Nome
						from
							Pessoa					
						where
							Pessoa.IdPessoa = '$IdPessoa'";
		$resCliente = mysql_query($sqlCliente,$con);
		$linCliente = mysql_fetch_array($resCliente);
		
		if($linCliente[TipoPessoa] == 1){
			$linCliente[NomeCliente] = $linCliente[NomeRepresentante]." (".$linCliente[Nome].")";
		} else{
			$linCliente[NomeCliente] = $linCliente[Nome];
		}

		$sqlTipoMensagem = "select
								TipoMensagem.Assunto,
								TipoMensagem.Conteudo,
								TipoMensagem.IdStatus,
								ContaEmail.DescricaoContaEmail
							from
								TipoMensagem,
								ContaEmail
							where
								TipoMensagem.IdLoja = $IdLoja and
								TipoMensagem.IdLoja = ContaEmail.IdLoja and
								TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
								TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resTipoMensagem = mysql_query($sqlTipoMensagem,$con);
		$linTipoMensagem = mysql_fetch_array($resTipoMensagem);

		if($linTipoMensagem[IdStatus] != 1){
			return false;
		}
		
		$Conteudo = $linTipoMensagem[Conteudo];

		$sqlEmail = "SELECT
					ValorCodigoInterno Email
				FROM
					CodigoInterno
				WHERE
					IdGrupoCodigoInterno = 38 AND
					IdCodigoInterno = 1 AND
					IdLoja = $IdLoja";
		$resEmail = mysql_query($sqlEmail,$con);
		$linEmail = mysql_fetch_array($resEmail);

		$Email = $linEmail[Email];

		$ValorTotal = 0;
		
		$sqlDiaCompe = "select 
							max(LocalCobranca.DiasCompensacao) DiasCompensacao
						from 
							ContaReceberDados,
							Pessoa,
							PessoaEndereco,
							Estado,
							Cidade,
							LocalCobranca
						where 
							ContaReceberDados.IdLoja = $IdLoja and 
							ContaReceberDados.IdLoja = LocalCobranca.IdLoja and 
							Pessoa.IdPessoa = $IdPessoa and
							Pessoa.MonitorFinanceiro = '1' and
							ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
							PessoaEndereco.IdPessoa = ContaReceberDados.IdPessoa and
							ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
							PessoaEndereco.IdPais = Estado.IdPais and
							PessoaEndereco.IdPais = Cidade.IdPais and
							PessoaEndereco.IdEstado = Estado.IdEstado and
							PessoaEndereco.IdEstado = Cidade.IdEstado and
							PessoaEndereco.IdCidade = Cidade.IdCidade and
							ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
							ContaReceberDados.IdStatus not in (0, 2, 7)";
		$resDiaCompe = mysql_query($sqlDiaCompe, $con);
		$linDiaCompe = mysql_fetch_array($resDiaCompe);
		
		$sql = "select 
					ContaReceberDados.IdContaReceber, 
					ContaReceberDados.DataVencimento, 
					ContaReceberDados.ValorLancamento, 
					ContaReceberDados.ValorDespesas, 
					ContaReceberDados.NumeroDocumento, 
					ContaReceberDados.IdLocalCobranca,
					ContaReceberDados.IdStatus,					
					ContaReceberDados.IdPessoa, 
					ContaReceberDados.MD5,
					Pessoa.TipoPessoa, 
					Pessoa.Nome, 
					Pessoa.NomeRepresentante, 
					PessoaEndereco.NomeResponsavelEndereco NomeResponsavel, 
					PessoaEndereco.Endereco, 
					PessoaEndereco.Numero, 
					PessoaEndereco.CEP, 
					PessoaEndereco.Complemento, 
					Pessoa.Email,
					PessoaEndereco.EmailEndereco, 
					Estado.SiglaEstado, 
					Cidade.NomeCidade, 
					LocalCobranca.DescricaoLocalCobranca, 
					LocalCobranca.IdLocalCobrancaLayout
				from 
					ContaReceberDados,
					Pessoa,
					PessoaEndereco,
					Estado,
					Cidade,
					LocalCobranca
				where 
					ContaReceberDados.IdLoja = $IdLoja and 
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and 
					Pessoa.IdPessoa = $IdPessoa and
					Pessoa.MonitorFinanceiro = '1' and
					ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
					PessoaEndereco.IdPessoa = ContaReceberDados.IdPessoa and
					ContaReceberDados.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco and
					PessoaEndereco.IdPais = Estado.IdPais and
					PessoaEndereco.IdPais = Cidade.IdPais and
					PessoaEndereco.IdEstado = Estado.IdEstado and
					PessoaEndereco.IdEstado = Cidade.IdEstado and
					PessoaEndereco.IdCidade = Cidade.IdCidade and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberDados.IdStatus not in (0, 2, 7)";
		$res = mysql_query($sql, $con);
		
		if(mysql_num_rows($res) > 0){
			$Demonstrativo .= 
					"<table style='width: 100%; font-size: 11px;'>
						<tr style='background-color: ".$ParametroTipoMensagem['$_ColorBackgroundTituloMensagem']."; color: ".$ParametroTipoMensagem['$_ColorTituloMensagem'].";'>
							<td>Cod.</td>
							<td>ND.</td>
							<td style='width: 88px; text-align: center;'>Vencimento</td>
							<td>Valor (R$)</td>
							<td style='width: 99px; text-align: center;'>Link do Boleto</td>
						</tr>";
			
			while($lin = mysql_fetch_array($res)){

				$sqlTipo = "SELECT
								ValorCodigoInterno Tipo
							FROM
								CodigoInterno
							WHERE
								IdGrupoCodigoInterno = 3 AND
								IdCodigoInterno = 165 AND
								IdLoja = $IdLoja";
				$resTipo = mysql_query($sqlTipo,$con);
				$linTipo = mysql_fetch_array($resTipo);
				
				$Tipo = strtolower($linTipo[Tipo]);

				$aux = '$_UrlSistema/modulos/administrativo/boleto.php';
				$LinkBoleto = '$_UrlSistema/modulos/cda/aviso_titulo_vencido.php?LinkBoleto='.$aux.'&Tipo='.$Tipo.'&ContaReceber='.$lin[MD5]."&IdContaReceber=".$lin[IdContaReceber]."&IdLoja=".$IdLoja;
				
				if($IdProcessoFinanceiro != ''){
					$lin[IdProcessoFinanceiro] = $IdProcessoFinanceiro;
				} else{
					$lin[IdProcessoFinanceiro] = 'NULL';
				}
				
				if($lin[TipoPessoa] == 1){
					$lin[NomeResponsavel] = $lin[NomeRepresentante];
				} else{
					$lin[NomeResponsavel] = $lin[Nome];
				}
				// DataVencimento
				$lin[DataVencimento] = dataConv($lin[DataVencimento],'Y-m-d','d/m/Y');
				$ValorFinal = $lin[ValorLancamento]+$lin[ValorDespesas];
				$ValorTotal += $ValorFinal;
				$ValorFinal = number_format($ValorFinal,2,',','');
				$Demonstrativo .= "
					<tr>
						<td>$lin[IdContaReceber]</td>
						<td>$lin[NumeroDocumento]</td>
						<td style='text-align: center;'>$lin[DataVencimento]</td>
						<td style='text-align: right;'>$ValorFinal</td>
						<td style='text-align: center;'><a href='$LinkBoleto'>Clique aqui.</a></td>
					</tr>
				";
			}
		} else{
			return false;
		}
		
		$ValorTotal = number_format($ValorTotal, 2, ',', '');
		$Demonstrativo .=
			"<tr style='background-color: ".$ParametroTipoMensagem['$_ColorBackgroundTituloMensagem']."; color: ".$ParametroTipoMensagem['$_ColorTituloMensagem'].";'>
				<td />
				<td />
				<td />
				<td>Total (R$)</td>
				<td />
			</tr>
			<tr>
				<td />
				<td />
				<td />
				<td style='text-align: right;'>$ValorTotal</td>
				<td />
			</tr>
		</table>";
		$Conteudo = str_replace('$_ColorBackgroundTituloMensagem',$ParametroTipoMensagem['$_ColorBackgroundTituloMensagem'],$Conteudo);
		$Conteudo = str_replace('$_ColorTituloMensagem',$ParametroTipoMensagem['$_ColorTituloMensagem'],$Conteudo);
		$Conteudo = str_replace('$_IdCliente',$linCliente[IdPessoa],$Conteudo);
		$Conteudo = str_replace('$_NomeCliente',$linCliente[Nome],$Conteudo);
		$Conteudo = str_replace('$_NumeroDocumento',$lin[NumeroDocumento],$Conteudo);
		$Conteudo = str_replace('$_ValorFinal',$ValorTotal,$Conteudo);
		$Conteudo = str_replace('$_QtdDiaCompensacao',$linDiaCompe[DiasCompensacao],$Conteudo);
		$Conteudo = str_replace('$DescricaoContaEmail',$linTipoMensagem[DescricaoContaEmail],$Conteudo);
		$Conteudo = str_replace('$_TabelaDemonstrativo',$Demonstrativo,$Conteudo);
		$Conteudo = str_replace('$_DataVencimento',$lin[DataVencimento],$Conteudo);
		$Conteudo = str_replace('$EndCDA',$url_sistema,$Conteudo);
		$linTipoMensagem[Assunto] = str_replace('$_DataVencimento',$lin[DataVencimento],$linTipoMensagem[Assunto]);
		
		if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
			$linTipoMensagem[Assunto]	= str_replace('$_NomeReduzido',$linEmpresa[Nome],$linTipoMensagem[Assunto]);
		} else{
			$linTipoMensagem[Assunto]	= str_replace('$_NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$linTipoMensagem[Assunto]);
		}
		
		$GeraMensagem[IdLoja]			= $IdLoja;
		$GeraMensagem[IdTipoMensagem]	= $IdTipoMensagem;
		$GeraMensagem[Email]			= $Email;
		$GeraMensagem[Conteudo]			= $Conteudo;
		$GeraMensagem[Assunto]			= $linTipoMensagem[Assunto];
		$GeraMensagem[IdPessoa]			= $linEmpresa[IdPessoa];
		$GeraMensagem[Login]			= $local_Login;	
		$GeraMensagem[EnviarMensagem]	= false;
		
		return geraMensagem($GeraMensagem);
	}
	
	function enviarEmailAberturaProtocolo($IdLoja, $IdProtocolo){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 36;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		$sql = "select
					Pessoa.Nome					
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select 
					Protocolo.IdProtocolo,
					Protocolo.Assunto,
					Protocolo.DataCriacao,
					ProtocoloTipo.DescricaoProtocoloTipo,
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email
				from
					Protocolo,
					ProtocoloTipo,
					Pessoa 
				where 
					Protocolo.IdLoja = $IdLoja and 
					Protocolo.IdProtocolo = $IdProtocolo and 
					Protocolo.IdLoja = ProtocoloTipo.IdLoja and 
					Protocolo.IdProtocoloTipo = ProtocoloTipo.IdProtocoloTipo and 
					Protocolo.IdPessoa = Pessoa.IdPessoa ";
		$resProtocolo = mysql_query($sql,$con);
		$linProtocolo = mysql_fetch_array($resProtocolo);
		
		if($linProtocolo[Email] == ''){
			return false;
		}

		if($linProtocolo[TipoPessoa] == 1){
			$linProtocolo[Nome] = $linProtocolo[NomeRepresentante];
		}

		$linProtocolo[DataCriacao] = dataConv($linProtocolo[DataCriacao],'Y-m-d','d/m/Y');

		$sqlMensagem = "select 
							TipoMensagem.Titulo,
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		
		if($TipoMensagem = mysql_fetch_array($resMensagem)){	
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
		
			$TipoMensagem[Conteudo]	= str_replace('$IdProtocolo',$linProtocolo[IdProtocolo],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Assunto',$linProtocolo[Assunto],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataCriacao',$linProtocolo[DataCriacao],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoProtocoloTipo',$linProtocolo[DescricaoProtocoloTipo],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linProtocolo[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linProtocolo[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			$TipoMensagem[Titulo]	= str_replace('$IdProtocolo',$linProtocolo[IdProtocolo],$TipoMensagem[Titulo]);
			$TipoMensagem[Assunto]	= str_replace('$IdProtocolo',$linProtocolo[IdProtocolo],$TipoMensagem[Assunto]);
			
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			} else {
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]			= $IdLoja;
			$GeraMensagem[IdTipoMensagem]	= $IdTipoMensagem;
			$GeraMensagem[Email]			= $linProtocolo[Email];	
			$GeraMensagem[Titulo]			= $TipoMensagem[Titulo];
			$GeraMensagem[Assunto]			= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]			= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]			= $linProtocolo[IdPessoa];	
			$GeraMensagem[IdProtocolo]		= $linProtocolo[IdProtocolo];
			$GeraMensagem[Login]			= $local_Login;	
			$GeraMensagem[EnviarMensagem]	= false;
			
			return geraMensagem($GeraMensagem);	
		}
	}
	
	function enviarEmailConclusaoProtocolo($IdLoja, $IdProtocolo){
		global $con, $local_Login;
		
		$IdTipoMensagem			= 37;
		$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $IdTipoMensagem);
		$url_sistema			= getParametroSistema(6,3);
		$url_sistema			.= '/central';

		$sql = "select
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);	

		$sql = "select 
					Protocolo.IdProtocolo,
					Protocolo.Assunto,
					Protocolo.DataCriacao,
					Protocolo.DataConclusao,
					Temp.EmailAtendimento,
					ProtocoloTipo.DescricaoProtocoloTipo,
					Pessoa.IdPessoa,
					Pessoa.Nome,
					Pessoa.NomeRepresentante,
					Pessoa.TipoPessoa,
					Pessoa.Email
				from
					Protocolo left join (
						select 
							Pessoa.Email EmailAtendimento,
							Usuario.Login 
						from
							Usuario,
							Pessoa 
						where 
							Usuario.IdPessoa = Pessoa.IdPessoa
					) Temp on (
						Protocolo.LoginResponsavel = Temp.Login
					),
					ProtocoloTipo,
					Pessoa 
				where 
					Protocolo.IdLoja = $IdLoja and 
					Protocolo.IdProtocolo = $IdProtocolo and 
					Protocolo.IdLoja = ProtocoloTipo.IdLoja and 
					Protocolo.IdProtocoloTipo = ProtocoloTipo.IdProtocoloTipo and 
					Protocolo.IdPessoa = Pessoa.IdPessoa ";
		$resProtocolo = mysql_query($sql,$con);
		$linProtocolo = mysql_fetch_array($resProtocolo);
		
		if($linProtocolo[Email] == '' && getCodigoInterno(38,5) == ''){
			return false;
		}

		if($linProtocolo[TipoPessoa] == 1){
			$linProtocolo[Nome] = $linProtocolo[NomeRepresentante];
		}

		$linProtocolo[DataCriacao] = dataConv($linProtocolo[DataCriacao],'Y-m-d','d/m/Y');
		$linProtocolo[DataConclusao] = dataConv($linProtocolo[DataConclusao],'Y-m-d','d/m/Y');
		
		$sqlMensagem = "select
							TipoMensagem.Titulo,
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.DescricaoContaEmail
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and 
							TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resMensagem = mysql_query($sqlMensagem,$con);
		
		if($TipoMensagem = mysql_fetch_array($resMensagem)){
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}
			
			$TipoMensagem[Conteudo]	= str_replace('$IdProtocolo',$linProtocolo[IdProtocolo],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$Assunto',$linProtocolo[Assunto],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataCriacao',$linProtocolo[DataCriacao],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DataConclusao',$linProtocolo[DataConclusao],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$DescricaoProtocoloTipo',$linProtocolo[DescricaoProtocoloTipo],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$NomeCliente',$linProtocolo[Nome],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EmailCliente',$linProtocolo[Email],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_sistema,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);
			$TipoMensagem[Titulo]	= str_replace('$IdProtocolo',$linProtocolo[IdProtocolo],$TipoMensagem[Titulo]);
			$TipoMensagem[Assunto]	= str_replace('$IdProtocolo',$linProtocolo[IdProtocolo],$TipoMensagem[Assunto]);
			
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			} else {
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			if(trim($linProtocolo[EmailAtendimento]) != '' && $linProtocolo[Email] != ''){
				$linProtocolo[Email] .= ";".trim($linProtocolo[EmailAtendimento]);
			} else {
				$linProtocolo[Email] .= trim($linProtocolo[EmailAtendimento]);
			}
			
			$GeraMensagem[IdLoja]			= $IdLoja;
			$GeraMensagem[IdTipoMensagem]	= $IdTipoMensagem;
			$GeraMensagem[Email]			= $linProtocolo[Email];
			$GeraMensagem[Titulo]			= $TipoMensagem[Titulo];
			$GeraMensagem[Assunto]			= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]			= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]			= $linProtocolo[IdPessoa];
			$GeraMensagem[IdProtocolo]		= $linProtocolo[IdProtocolo];
			$GeraMensagem[Login]			= $local_Login;
			$GeraMensagem[EnviarMensagem]	= false;
			
			return geraMensagem($GeraMensagem);
		}
	}
		
	function enviarEmailTesteContaEmail($IdLoja, $IdContaEmail){
		global $con;
		
		$url_sistema			= getParametroSistema(6,3);
		$url_cda				= $url_sistema."/central";		

		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.Nome
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);

		 $sqlMensagem = "select 		
							TipoMensagem.IdTipoMensagem,
							TipoMensagem.Assunto,
							TipoMensagem.Conteudo,
							TipoMensagem.IdStatus,
							ContaEmail.IdContaEmail,
							ContaEmail.NomeRemetente,
							ContaEmail.DescricaoContaEmail,
							ContaEmail.EmailRemetente
						from 
							TipoMensagem,
							ContaEmail
						where 
							TipoMensagem.IdLoja = $IdLoja and 
							TipoMensagem.IdLoja = ContaEmail.IdLoja and 
							TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and 
							TipoMensagem.IdContaEmail = $IdContaEmail and
							TipoMensagem.Titulo = 'Teste de Conta de Email' and
							TipoMensagem.IdTipoMensagem != 38";
		$resMensagem = mysql_query($sqlMensagem,$con);
		if($TipoMensagem = mysql_fetch_array($resMensagem)){

			$ParametroTipoMensagem	= parametroTipoMensagem($IdLoja, $TipoMensagem[IdTipoMensagem]);

			if($TipoMensagem[EmailRemetente] == ''){
				return false;
			}
			
			if($TipoMensagem[IdStatus] != 1){
				return false;
			}

			$TipoMensagem[Conteudo]	= str_replace('$IdContaEmail',$TipoMensagem[IdContaEmail],$TipoMensagem[Conteudo]);							
			$TipoMensagem[Conteudo]	= str_replace('$NomeRemetente',$TipoMensagem[NomeRemetente],$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('\'','"',$TipoMensagem[Conteudo]);
			$TipoMensagem[Conteudo]	= str_replace('$EndCDA',$url_cda,$TipoMensagem[Conteudo]);	
			$TipoMensagem[Conteudo] = str_replace('$DescricaoContaEmail',$TipoMensagem[DescricaoContaEmail],$TipoMensagem[Conteudo]);

			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$TipoMensagem[Assunto]);
			}else{
				$TipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$TipoMensagem[Assunto]);
			}
			
			$GeraMensagem[IdLoja]					= $IdLoja;
			$GeraMensagem[IdTipoMensagem]			= $TipoMensagem[IdTipoMensagem];			
			$GeraMensagem[Email]					= $TipoMensagem[EmailRemetente];			
			$GeraMensagem[Assunto]					= $TipoMensagem[Assunto];
			$GeraMensagem[Conteudo]					= $TipoMensagem[Conteudo];
			$GeraMensagem[IdPessoa]					= $linEmpresa[IdPessoa];			
			$GeraMensagem[Login]					= 'automatico';				
			$GeraMensagem[EnviarMensagem]			= true;	

			return geraMensagem($GeraMensagem);		
		}
	}
	
	function enviaNotasFiscais($IdLoja,$IdContaReceber){
		global $con;
		global $local_Email;
		global $local_Login;
	
		$url_sistema	= getParametroSistema(6,3);
		$url_sistema	.= '/central';
		$dataAtual		= date("Ymd");

		$IdTipoMensagem = 40;//Nota Fiscal Emitida

		$ParametroTipoMensagem = parametroTipoMensagem($IdLoja, $IdTipoMensagem);

		$sql = "select
					Pessoa.Nome,
					Pessoa.Email
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);		

	   $sql = "select
					TipoMensagem.Assunto,
					TipoMensagem.Conteudo,
					TipoMensagem.IdStatus,
					ContaEmail.DescricaoContaEmail
				from
					TipoMensagem,
					ContaEmail
				where
					TipoMensagem.IdLoja = $IdLoja and
					TipoMensagem.IdLoja = ContaEmail.IdLoja and
					TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
					TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resTipoMensagem = mysql_query($sql,$con);
		$linTipoMensagem = mysql_fetch_array($resTipoMensagem);

		if($linTipoMensagem[IdStatus] != 1){
			return false;
		}
		
		$Conteudo =  $linTipoMensagem[Conteudo];
	    $sql = "SELECT
				  	Pessoa.Nome,
				  	Pessoa.IdPessoa,
				  	Pessoa.Email,
					ContaReceber.NumeroDocumento,
					ContaReceber.IdContaReceber,
				  	NotaFiscal.IdNotaFiscalLayout,
				  	NotaFiscal.IdLoja,
					NotaFiscal.IdNotaFiscal,
				  	NotaFiscal.PeriodoApuracao,
				  	NotaFiscal.DataEmissao,
				  	NotaFiscal.Modelo,
				  	NotaFiscal.Serie,
				  	NotaFiscal.CodigoAutenticacaoDocumento,
				  	NotaFiscal.ValorTotal,
				  	NotaFiscal.ValorDesconto,
				  	NotaFiscal.ValorBaseCalculoICMS,
				  	NotaFiscal.ValorICMS,
				  	NotaFiscal.ValorNaoTributado,
				  	NotaFiscal.ValorOutros
				FROM 
				  	NotaFiscal,
				  	ContaReceber,
				  	Pessoa
				WHERE
				   	NotaFiscal.IdLoja = '$IdLoja' AND
				   	NotaFiscal.`IdContaReceber` = '$IdContaReceber' AND
				   	ContaReceber.IdLoja = NotaFiscal.IdLoja AND
				   	ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND
				   	Pessoa.IdPessoa = ContaReceber.IdPessoa"; //Nao manda para CR com LC do tipo 3(Debito em conta)
		$res  = mysql_query($sql,$con);		
		$nReg = mysql_num_rows($res);
		$i=0;
		while($Sacado = mysql_fetch_array($res)){ 
			switch(strtolower(getCodigoInterno(3,245))){
				case 'html': 
					$LinkBoleto = getParametroSistema(6,3)."/modulos/administrativo/nota_fiscal/$Sacado[IdNotaFiscalLayout]/nota_fiscal_html.php?IdContaReceber=".$Sacado['IdContaReceber']."&IdLoja=".$Sacado['IdLoja'];
					break;
				case 'pdf': 
					$LinkBoleto = getParametroSistema(6,3)."/modulos/administrativo/nota_fiscal/$Sacado[IdNotaFiscalLayout]/nota_fiscal_pdf.php?IdContaReceber=".$Sacado['IdContaReceber']."&IdLoja=".$Sacado['IdLoja'];
					break;
				default:
					$LinkBoleto = getParametroSistema(6,3)."/modulos/administrativo/nota_fiscal/$Sacado[IdNotaFiscalLayout]/nota_fiscal_html.php?IdContaReceber=".$Sacado['IdContaReceber']."&IdLoja=".$Sacado['IdLoja'];
					break;
			}
			
			$Sacado[IdNotaFiscalTemp]	= str_pad($Sacado[IdNotaFiscal], 9, "0", STR_PAD_LEFT);
			$Sacado[IdNotaFiscalTemp]	= substr($Sacado[IdNotaFiscalTemp],0,3).".".substr($Sacado[IdNotaFiscalTemp],3,3).".".substr($Sacado[IdNotaFiscalTemp],6,3);
			
			$FaturaUnica = "<tr>
								<td style='text-align:center; font-size: 11px'>$Sacado[Nome]</td>
								<td style='text-align:center; font-size: 11px'>$Sacado[IdNotaFiscalTemp]</td>
								<td style='text-align:center; font-size: 11px'>$Sacado[NumeroDocumento]</td>
								<td style='text-align:center; font-size: 11px'>".number_format($Sacado[ValorTotal],2,',','.')."</td>
								<td style='text-align:center; font-size: 11px'>".dataConv($Sacado[DataEmissao],"Y-m-d","d/m/Y")."</td>
								<td style='text-align:center; font-size: 11px'><a href='$LinkBoleto' target='_blank'>2&ordf; Via</a></td>
							</tr>";
							
			$Conteudo = str_replace('$FaturaUnica',$FaturaUnica,$Conteudo);
			$Conteudo = str_replace('$_LinkBoleto',$LinkBoleto,$Conteudo);
			$Conteudo = str_replace('$DescricaoContaEmail',$linTipoMensagem[DescricaoContaEmail],$Conteudo);			
			$Conteudo = str_replace('$DataEmissao',dataConv($DataEmissao,"Y-m-d","d/m/Y"),$Conteudo);			
			$Conteudo = str_replace('$DataAtual',date("d/m/Y"),$Conteudo);			
			$Conteudo = str_replace('$nome_responsavel',$Sacado[Nome],$Conteudo);
			$Conteudo = str_replace('$EndCDA',$url_sistema,$Conteudo);			
						
			$linTipoMensagem[Assunto]	= str_replace('$DataVencimento',$Sacado[DataVencimento],$linTipoMensagem[Assunto]);
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$linTipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$linTipoMensagem[Assunto]);
			}else{
				$linTipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$linTipoMensagem[Assunto]);
			}
			
			
			$linTipoMensagem[Assunto]	= str_replace('$IdNotaFiscal',$Sacado[IdNotaFiscalTemp],$linTipoMensagem[Assunto]);
			$linTipoMensagem[Assunto]	= str_replace('$DataEmissao',dataConv($Sacado['DataEmissao'],"Y-m-d","d/m/Y"),$linTipoMensagem[Assunto]);
			
		
			$GeraMensagem[IdLoja]			= $IdLoja;
			$GeraMensagem[IdTipoMensagem]	= $IdTipoMensagem;
			$GeraMensagem[Email]			= $Sacado[Email];
			$GeraMensagem[Conteudo]			= $Conteudo;
			$GeraMensagem[Assunto]			= $linTipoMensagem[Assunto];
			$GeraMensagem[IdPessoa]			= $Sacado[IdPessoa];
			$GeraMensagem[IdContaReceber]	= $Sacado[IdContaReceber];
			$GeraMensagem[Login]			= $local_Login;				
		
			return geraMensagem($GeraMensagem);
		}		
	}
	
		function enviaNotasFiscaisEmissaoDiaria($IdLoja,$DataEmissao,$IdNotaFiscal){
		global $con;
		global $local_Email;
		global $local_Login;

		$url_sistema	= getParametroSistema(6,3);
		$url_sistema	.= '/central';
		$dataAtual		= date("Ymd");

		$IdTipoMensagem = 40;//Nota Fiscal Emitida

		$ParametroTipoMensagem = parametroTipoMensagem($IdLoja, $IdTipoMensagem);

		$sql = "select
					Pessoa.Nome,
					Pessoa.Email
				from
					Loja,
					Pessoa					
				where
					Loja.IdLoja = $IdLoja and
					Pessoa.IdPessoa = Loja.IdPessoa";
		$resEmpresa = mysql_query($sql,$con);
		$linEmpresa = mysql_fetch_array($resEmpresa);		

		$sql = "select
					TipoMensagem.Assunto,
					TipoMensagem.Conteudo,
					TipoMensagem.IdStatus,
					ContaEmail.DescricaoContaEmail
				from
					TipoMensagem,
					ContaEmail
				where
					TipoMensagem.IdLoja = $IdLoja and
					TipoMensagem.IdLoja = ContaEmail.IdLoja and
					TipoMensagem.IdContaEmail = ContaEmail.IdContaEmail and
					TipoMensagem.IdTipoMensagem = $IdTipoMensagem";
		$resTipoMensagem = mysql_query($sql,$con);
		$linTipoMensagem = mysql_fetch_array($resTipoMensagem);

		if($linTipoMensagem[IdStatus] != 1){
			return false;
		}

		$Conteudo =  $linTipoMensagem[Conteudo];

		$sql = "SELECT
				  	Pessoa.Nome,
				  	Pessoa.IdPessoa,
				  	Pessoa.Email,
					ContaReceber.NumeroDocumento,
					ContaReceber.IdContaReceber,
				  	NotaFiscal.IdNotaFiscalLayout,
				  	NotaFiscal.IdLoja,
					NotaFiscal.IdNotaFiscal,
				  	NotaFiscal.PeriodoApuracao,
				  	NotaFiscal.DataEmissao,
				  	NotaFiscal.Modelo,
				  	NotaFiscal.Serie,
				  	NotaFiscal.CodigoAutenticacaoDocumento,
				  	NotaFiscal.ValorTotal,
				  	NotaFiscal.ValorDesconto,
				  	NotaFiscal.ValorBaseCalculoICMS,
				  	NotaFiscal.ValorICMS,
				  	NotaFiscal.ValorNaoTributado,
				  	NotaFiscal.ValorOutros
				FROM 
				  	NotaFiscal,
				  	ContaReceber,
				  	Pessoa
				WHERE
				   	NotaFiscal.IdLoja = '$IdLoja' AND
				   	NotaFiscal.DataEmissao = '$DataEmissao' AND
				   	NotaFiscal.IdNotaFiscal = '$IdNotaFiscal' AND
				   	ContaReceber.IdLoja = NotaFiscal.IdLoja AND
				   	ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND
				   	Pessoa.IdPessoa = ContaReceber.IdPessoa"; //Nao manda para CR com LC do tipo 3(Debito em conta)
		$res  = mysql_query($sql,$con);		
		$nReg = mysql_num_rows($res);
		while($Sacado = mysql_fetch_array($res)){
			$LinkBoleto = getParametroSistema(6,3)."/modulos/administrativo/nota_fiscal/$Sacado[IdNotaFiscalLayout]/nota_fiscal_html.php?IdContaReceber=".$Sacado['IdContaReceber']."&IdLoja=".$Sacado['IdLoja'];
			
			$Sacado[IdNotaFiscalTemp]	= str_pad($Sacado[IdNotaFiscal], 9, "0", STR_PAD_LEFT);
			$Sacado[IdNotaFiscalTemp]	= substr($Sacado[IdNotaFiscalTemp],0,3).".".substr($Sacado[IdNotaFiscalTemp],3,3).".".substr($Sacado[IdNotaFiscalTemp],6,3);
			
			$FaturaUnica = "<tr>
								<td style='text-align:center; font-size: 11px'>$Sacado[Nome]</td>
								<td style='text-align:center; font-size: 11px'>$Sacado[IdNotaFiscal]</td>
								<td style='text-align:center; font-size: 11px'>$Sacado[NumeroDocumento]</td>
								<td style='text-align:center; font-size: 11px'>".number_format($Sacado[ValorTotal],2,',','.')."</td>
								<td style='text-align:center; font-size: 11px'>".dataConv($Sacado[DataEmissao],"Y-m-d","d/m/Y")."</td>
								<td style='text-align:center; font-size: 11px'><a href='$LinkBoleto' target='_blank'>2&ordf; Via</a></td>
							</tr>";
							
			$Conteudo = str_replace('$FaturaUnica',$FaturaUnica,$Conteudo);
			$Conteudo = str_replace('$_LinkBoleto',$LinkBoleto,$Conteudo);
			$Conteudo = str_replace('$DescricaoContaEmail',$linTipoMensagem[DescricaoContaEmail],$Conteudo);			
			$Conteudo = str_replace('$DataEmissao',dataConv($DataEmissao,"Y-m-d","d/m/Y"),$Conteudo);			
			$Conteudo = str_replace('$DataAtual',date("d/m/Y"),$Conteudo);			
			$Conteudo = str_replace('$nome_responsavel',$Sacado[Nome],$Conteudo);
			$Conteudo = str_replace('$EndCDA',$url_sistema,$Conteudo);			
			
			$linTipoMensagem[Assunto]	= str_replace('$DataVencimento',$Sacado[DataVencimento],$linTipoMensagem[Assunto]);
			if($ParametroTipoMensagem['$_NomeReduzido'] == ''){
				$linTipoMensagem[Assunto]	= str_replace('$NomeReduzido',$linEmpresa[Nome],$linTipoMensagem[Assunto]);
			}else{
				$linTipoMensagem[Assunto]	= str_replace('$NomeReduzido',$ParametroTipoMensagem['$_NomeReduzido'],$linTipoMensagem[Assunto]);
			}
			
			$linTipoMensagem[Assunto]	= str_replace('$IdNotaFiscal',$Sacado[IdNotaFiscalTemp],$linTipoMensagem[Assunto]);
			$linTipoMensagem[Assunto]	= str_replace('$DataEmissao',dataConv($Sacado['DataEmissao'],"Y-m-d","d/m/Y"),$linTipoMensagem[Assunto]);
			
			$GeraMensagem[IdLoja]			= $IdLoja;
			$GeraMensagem[IdTipoMensagem]	= $IdTipoMensagem;
			$GeraMensagem[Email]			= $Sacado[Email];
			$GeraMensagem[Conteudo]			= $Conteudo;
			$GeraMensagem[Assunto]			= $linTipoMensagem[Assunto];
			$GeraMensagem[IdPessoa]			= $Sacado[IdPessoa];
			$GeraMensagem[IdContaReceber]	= $Sacado[IdContaReceber];
			$GeraMensagem[Login]			= $local_Login;				

			return geraMensagem($GeraMensagem);
		}		
	}		
?>