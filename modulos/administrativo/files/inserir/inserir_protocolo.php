<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I") && $local_Login != "cda"){
		$local_Erro = 2;
	} else{
	
		if ($local_Login == "cda") {
			$Path = "../../";
			
			include($Path.'classes/envia_mensagem/envia_mensagem.php');
		}
		
		$sql = "start transaction;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "select (max(IdProtocolo) + 1) IdProtocolo from Protocolo;";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdProtocolo] != NULL){ 
			$local_IdProtocolo = $lin[IdProtocolo];
		} else{
			$local_IdProtocolo = 1;
		}
		
		if((int)$local_IdTipoPessoa == 1){
			$local_CPF_CNPJ = $local_CNPJ;
			$local_Email = $local_EmailJuridica;
		} else{
			$local_IdPessoa = $local_IdPessoaF;
			$local_Nome = $local_NomeF;
			$local_CPF_CNPJ = $local_CPF;
		}
		
		if($local_CPF_CNPJ == ''){
			$local_CPF_CNPJ = "NULL";
		} else{
			$local_CPF_CNPJ = "'$local_CPF_CNPJ'";
		}
		
		if($local_Telefone1 == ''){
			$local_Telefone1 = "NULL";
		} else{
			$local_Telefone1 = "'$local_Telefone1'";
		}
		
		if($local_Telefone2 == ''){
			$local_Telefone2 = "NULL";
		} else{
			$local_Telefone2 = "'$local_Telefone2'";
		}
		
		if($local_Telefone3 == ''){
			$local_Telefone3 = "NULL";
		} else{
			$local_Telefone3 = "'$local_Telefone3'";
		}
		
		if($local_Celular == ''){
			$local_Celular = "NULL";
		} else{
			$local_Celular = "'$local_Celular'";
		}
		
		if($local_Email == ''){
			$local_Email = "NULL";
		} else{
			$local_Email = "'$local_Email'";
		}
		
		if($local_IdStatus == ''){
			$local_IdStatus = "100";
		}
		
		if($local_IdContrato == ''){
			$local_IdContrato = "NULL";
		} else{
			$local_IdContrato = "'$local_IdContrato'";
		}
		
		if($local_IdContaEventual == ''){
			$local_IdContaEventual = "NULL";
		} else{
			$local_IdContaEventual = "'$local_IdContaEventual'";
		}
		
		if($local_IdContaReceber == ''){
			$local_IdContaReceber = "NULL";
		} else{
			$local_IdContaReceber = "'$local_IdContaReceber'";
		}
		
		if($local_IdOrdemServico == ''){
			$local_IdOrdemServico = "NULL";
		} else{
			$local_IdOrdemServico = "'$local_IdOrdemServico'";
		}
		
		if($local_IdGrupoUsuarioAtendimento == ''){
			$local_IdGrupoUsuarioAtendimento = "NULL";
		} else{
			$local_IdGrupoUsuarioAtendimento = "'$local_IdGrupoUsuarioAtendimento'";
		}
		
		if($local_LoginAtendimento == ''){
			$local_LoginAtendimento = "NULL";
		} else{
			$local_LoginAtendimento = "'$local_LoginAtendimento'";
		}
		
		if($local_IdProtocoloTipo == ''){
			$local_IdProtocoloTipo = "NULL";
		} else{
			$local_IdProtocoloTipo = "'$local_IdProtocoloTipo'";
		}
		
		if($local_IdPessoa == ''){
			$local_IdPessoa = "NULL";
		} else{
			$local_IdPessoa = "'$local_IdPessoa'";
		}
		
		if($local_Data != "" && $local_Hora != ""){
			$local_PrevisaoEtapa = $local_Data." ".$local_Hora.":00";
			$local_PrevisaoEtapa = "'".dataConv($local_PrevisaoEtapa, "d/m/Y H:i:s", "Y-m-d H:i:s")."'";
		} else {
			$local_PrevisaoEtapa = "NULL";
		}
		
		if($local_IdPessoa == ""){
			$sql = "insert into Protocolo set
						IdLoja				= '$local_IdLoja',
						IdProtocolo			= '$local_IdProtocolo',
						LocalAbertura		= '$local_IdLocalAbertura',
						IdProtocoloTipo		= $local_IdProtocoloTipo,
						Assunto				= '$local_Assunto',
						IdPessoa			= $local_IdPessoa,
						IdContrato			= $local_IdContrato,
						IdContaEventual		= $local_IdContaEventual,
						IdContaReceber		= $local_IdContaReceber,
						IdOrdemServico		= $local_IdOrdemServico,
						CPF_CNPJ			= $local_CPF_CNPJ,
						Nome				= '$local_Nome',
						Telefone1			= $local_Telefone1,
						Telefone2			= $local_Telefone2,
						Telefone3			= $local_Telefone3,
						Celular				= $local_Celular,
						Email				= $local_Email,
						IdStatus			= $local_IdStatus,
						IdGrupoUsuario		= $local_IdGrupoUsuarioAtendimento,
						LoginResponsavel	= $local_LoginAtendimento,
						PrevisaoEtapa		= $local_PrevisaoEtapa,
						LoginCriacao		= '$local_Login',
						DataCriacao			= (concat(curdate(),' ',curtime()));";
		}else{
			$sql = "insert into Protocolo set
						IdLoja				= '$local_IdLoja',
						IdProtocolo			= '$local_IdProtocolo',
						LocalAbertura		= '$local_IdLocalAbertura',
						IdProtocoloTipo		= $local_IdProtocoloTipo,
						Nome				= '',
						Assunto				= '$local_Assunto',
						IdPessoa			= $local_IdPessoa,
						IdContrato			= $local_IdContrato,
						IdContaEventual		= $local_IdContaEventual,
						IdContaReceber		= $local_IdContaReceber,
						IdOrdemServico		= $local_IdOrdemServico,
						IdStatus			= $local_IdStatus,
						IdGrupoUsuario		= $local_IdGrupoUsuarioAtendimento,
						LoginResponsavel	= $local_LoginAtendimento,
						PrevisaoEtapa		= $local_PrevisaoEtapa,
						LoginCriacao		= '$local_Login',
						DataCriacao			= (concat(curdate(),' ',curtime()));";
		}
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		if($local_FormaGeracaoCodigo != 2){
			$local_Mensagem = "<b>Escrito por:</b> $local_Login <div style=\'margin-top:6px;\'>".str_replace('"',"'",$local_Mensagem)."</div>";
			$local_Mensagem = "<div style=\'margin:6px 0px 6px 0px;\' class=\'none\'><b>Assunto:</b> $local_Assunto</div>$local_Mensagem";
			
			$sql1 = "select DescricaoProtocoloTipo from ProtocoloTipo where IdLoja = '$local_IdLoja' and IdProtocoloTipo = $local_IdProtocoloTipo;";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			$local_Mensagem = "<div style=\'margin:6px 0px 6px 0px;\' class=\'none\'><b>Tipo Protocolo:</b> ".$lin1[DescricaoProtocoloTipo]."</div>$local_Mensagem";
			
			$sql = "insert into
						ProtocoloHistorico
					set
						IdLoja					= '$local_IdLoja',
						IdProtocolo				= '$local_IdProtocolo',
						IdProtocoloHistorico	= '1',
						Mensagem				= \"$local_Mensagem\",
						PrevisaoEtapa			= $local_PrevisaoEtapa,
						IdStatus				= '$local_IdStatus',
						LoginCriacao			= '$local_Login', 
						DataCriacao				= (concat(curdate(),' ',curtime()));";
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
		}
		
		for($i = 0; $i < $tr_i; $i++){
			if(!$local_transaction[$i]){
				$local_transaction = false;
			}
		}
		
		if($local_transaction == true){
			$sql = "commit;";
			@mysql_query($sql,$con);
			$sql = "select
						Titulo,
						Assunto,
						Conteudo,
						IdStatus
					from
						TipoMensagem
					where
						IdLoja = $local_IdLoja and
						IdTipoMensagem = 36";
			$res = mysql_query($sql,$con);
			$linMensagem = mysql_fetch_array($res);

			if($linMensagem[IdStatus] != 1){
				$local_ErroEmail		= getParametroSistema(13,191);
				$local_TipoEmail		= "'".delimitaAteCaracter($linMensagem[Titulo],'$')."'";
			}
			enviarEmailAberturaProtocolo($local_IdLoja,$local_IdProtocolo);
			
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		} else{
			$sql = "rollback;";
			@mysql_query($sql,$con);
			
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>