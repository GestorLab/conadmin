<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"U")){
		$local_Erro = 2;
	} else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;

		if($local_SMTPSeguro == ''){
			$local_SMTPSeguro = 'NULL';
		}
		if($local_LimiteDiario == ''){
			$local_LimiteDiario = 'NULL';
		}
	 	$sql = "update ContaEmail set 
					DescricaoContaEmail	= '$local_DescricaoContaEmail',
					NomeRemetente		= '$local_NomeRemetente',
					EmailRemetente		= '$local_EmailRemetente',
					ServidorSMTP		= '$local_ServidorSMTP',
					Porta				= $local_Porta,
					RequerAutenticacao	= $local_RequerAutenticacao,
					Usuario				= '$local_Usuario',
					Senha				= '$local_Senha',
					NomeResposta		= '$local_NomeResposta',
					EmailResposta		= '$local_EmailResposta',
					IntervaloEnvio		= $local_IntervaloEnvio,
					QtdTentativaEnvio	= $local_QtdTentativaEnvio,
					SMTPSeguro			= $local_SMTPSeguro,
					LimiteEnvioDiario	= $local_LimiteDiario,
					LoginAlteracao		= '$local_Login', 
					DataAlteracao		= (concat(curdate(),' ',curtime()))
				where
					IdLoja = '$local_IdLoja' and
					IdContaEmail = '$local_IdContaEmail';";
		$local_transaction[$tr_i] = mysql_query($sql,$con);				
		$tr_i++;
		
		$sql = "select
					count(*) Qtd
				from
					TipoMensagem
				where
					IdLoja = $local_IdLoja and
					IdContaEmail = $local_IdContaEmail and
					Titulo = 'Teste de Conta de Email'";
		$res_ = mysql_query($sql,$con);
		$lin_ = @mysql_fetch_array($res_);
		
		if($lin_[Qtd] == 0){
			$sql = "select
						IdLoja,
						IdTipoMensagem,
						IdTemplate,
						IdContaEmail,
						IdContaSMS,
						Titulo,
						Assunto,
						Conteudo,
						Assinatura,
						DelayDisparo,
						IdStatus,
						PrioridadeEnvio,
						LimiteEnvioDiario,
						DataAlteracao,
						LoginAlteracao
					from
						TipoMensagem
					where
						IdLoja = $local_IdLoja and
						IdTipoMensagem = 38";
			$res = mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);

			$sql = "select (max(IdTipoMensagem) + 1) IdTipoMensagem from TipoMensagem where IdLoja = '$local_IdLoja' and IdTipoMensagem > 999999;";
			$res2 = mysql_query($sql,$con);
			$lin2 = @mysql_fetch_array($res2);
			
			if($lin2[IdTipoMensagem] != NULL){
				$local_IdTipoMensagem = $lin2[IdTipoMensagem];
			} else{
				$local_IdTipoMensagem = 1000000;
			}

			if($lin[LimiteEnvioDiario] == ''){ $lin[LimiteEnvioDiario] = "NULL"; }

			$sql = "insert into TipoMensagem set
						IdLoja				= $lin[IdLoja],
						IdTipoMensagem		= $local_IdTipoMensagem,
						IdTemplate			= $lin[IdTemplate],
						IdContaEmail		= $local_IdContaEmail,
						IdContaSMS			= NULL,
						Titulo				= \"$lin[Titulo]\",
						Assunto				= \"$lin[Assunto]\",
						Conteudo			= \"$lin[Conteudo]\",
						Assinatura			= \"$lin[Assinatura]\",
						DelayDisparo		= '$lin[DelayDisparo]',
						IdStatus			= $lin[IdStatus],
						PrioridadeEnvio		= $lin[PrioridadeEnvio],
						LimiteEnvioDiario	= $lin[LimiteEnvioDiario],
						DataAlteracao		= (concat(curdate(),' ',curtime())),
						LoginAlteracao		= '$local_Login'";
			$local_transaction[$tr_i] = mysql_query($sql,$con);			
			$tr_i++;

			$sql = "select
						IdLoja,
						IdTipoMensagem,
						IdTipoMensagemParametro,
						DescricaoTipoMensagemParametro,
						ValorTipoMensagemParametro
					from
						TipoMensagemParametro
					where
						IdLoja = $local_IdLoja and
						IdTipoMensagem = 38";
			$res = mysql_query($sql,$con);
			while($lin = @mysql_fetch_array($res)){
				
				$sql = "insert into TipoMensagemParametro set
							IdLoja							= $lin[IdLoja],
							IdTipoMensagem					= $local_IdTipoMensagem,
							IdTipoMensagemParametro			= \"$lin[IdTipoMensagemParametro]\",
							DescricaoTipoMensagemParametro	= \"$lin[DescricaoTipoMensagemParametro]\",
							ValorTipoMensagemParametro		= \"$lin[ValorTipoMensagemParametro]\"";
				$local_transaction[$tr_i] = mysql_query($sql,$con);				
				$tr_i++;
			}
		}

		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
				break;
			}
		}
	
		if($local_transaction == true) {		
			$sql = "COMMIT;";
			@mysql_query($sql,$con);

			$local_Erro = 4;			#MENSAGEM DE INSERÇÃO POSITIVA
		} else {
			$sql = "ROLLBACK;";
			@mysql_query($sql,$con);			

			$local_Erro = 5;			# MENSAGEM DE INSERÇÃO NEGATIV
		}
	}
?>