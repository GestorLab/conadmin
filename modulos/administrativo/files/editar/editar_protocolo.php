<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"U")){
		$local_Erro = 2;
	} else{
		$sql = "start transaction;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		if((int)$local_IdTipoPessoa == 1){
			$local_CPF_CNPJ = $local_CNPJ;
			$local_Email = $local_EmailJuridica;
		} else{
			$local_IdPessoa = $local_IdPessoaF;
			$local_Nome = $local_NomeF;
			$local_CPF_CNPJ = $local_CPF;
		}
		# MONTAGEM DO HISTÓRICO #
		$sql_hist = "select 
					Protocolo.IdProtocoloTipo,
					Protocolo.IdPessoa,
					Protocolo.IdContrato,
					Protocolo.IdContaEventual,
					Protocolo.IdOrdemServico,
					Protocolo.IdContaReceber,
					Protocolo.Assunto,
					Protocolo.PrevisaoEtapa,
					Protocolo.IdStatus,
					Protocolo.IdGrupoUsuario,
					Protocolo.LoginResponsavel
				from 
					Protocolo
				where 
					Protocolo.IdLoja = '$local_IdLoja' and
					Protocolo.IdProtocolo = '$local_IdProtocolo';";
		$res_hist = @mysql_query($sql_hist,$con);
		$lin_hist = @mysql_fetch_array($res_hist);
		$local_HistoricoAlteracao = '';
		$lin_hist[PrevisaoEtapa] = dataConv($lin_hist[PrevisaoEtapa],'Y-m-d H:i:s','d/m/Y H:i');
		
		if($lin_hist[IdProtocoloTipo] != $local_IdProtocoloTipo){
			$sql_temp = "select 
						ProtocoloTipo.DescricaoProtocoloTipo
					from 
						ProtocoloTipo
					where
						ProtocoloTipo.IdLoja = '$local_IdLoja' and
						ProtocoloTipo.IdProtocoloTipo = '".$lin_hist[IdProtocoloTipo]."';";
			$res_temp = @mysql_query($sql_temp,$con);
			$lin_temp = @mysql_fetch_array($res_temp);
			$lin_hist[IdProtocoloTipo] = $lin_temp[DescricaoProtocoloTipo];
			
			$sql_temp = "select 
						ProtocoloTipo.DescricaoProtocoloTipo
					from 
						ProtocoloTipo
					where
						ProtocoloTipo.IdLoja = '$local_IdLoja' and
						ProtocoloTipo.IdProtocoloTipo = '".$local_IdProtocoloTipo."';";
			$res_temp = @mysql_query($sql_temp,$con);
			$lin_temp = @mysql_fetch_array($res_temp);
			$local_IdProtocoloTipoTemp = $lin_temp[DescricaoProtocoloTipo];
			
			$local_HistoricoAlteracao .= "<div>Tipo Protocolo. [".$lin_hist[IdProtocoloTipo]." > ".$local_IdProtocoloTipoTemp."]</div>";
		}
		
		if(trim($lin_hist[IdPessoa]) != trim($local_IdPessoa)){
			$local_HistoricoAlteracao .= "<div>Pessoa. [".trim($lin_hist[IdPessoa])." > ".trim($local_IdPessoa)."]</div>";
		}
		
		if(trim($lin_hist[IdContrato]) != trim($local_IdContrato)){
			$local_HistoricoAlteracao .= "<div>Contrato. [".trim($lin_hist[IdContrato])." > ".trim($local_IdContrato)."]</div>";
		}
		
		if(trim($lin_hist[IdContaEventual]) != trim($local_IdContaEventual)){
			$local_HistoricoAlteracao .= "<div>Conta Eventual. [".trim($lin_hist[IdContaEventual])." > ".trim($local_IdContaEventual)."]</div>";
		}
		
		if(trim($lin_hist[IdOrdemServico]) != trim($local_IdOrdemServico)){
			$local_HistoricoAlteracao .= "<div>OS. [".trim($lin_hist[IdOrdemServico])." > ".trim($local_IdOrdemServico)."]</div>";
		}
		
		if(trim($lin_hist[IdContaReceber]) != trim($local_IdContaReceber)){
			$local_HistoricoAlteracao .= "<div>Conta Receber. [".trim($lin_hist[IdContaReceber])." > ".trim($local_IdContaReceber)."]</div>";
		}
		
		if(trim($lin_hist[Assunto]) != trim($local_Assunto)){
			$local_HistoricoAlteracao .= "<div>Assunto. [".trim($lin_hist[Assunto])." > ".trim($local_Assunto)."]</div>";
		}
		
		if($lin_hist[IdGrupoUsuario] != $local_IdGrupoUsuarioAtendimento){
			$sql_temp = "select 
						GrupoUsuario.DescricaoGrupoUsuario
					from 
						GrupoUsuario
					where
						GrupoUsuario.IdLoja = '$local_IdLoja' and
						GrupoUsuario.IdGrupoUsuario = '".$lin_hist[IdGrupoUsuario]."';";
			$res_temp = @mysql_query($sql_temp,$con);
			$lin_temp = @mysql_fetch_array($res_temp);
			$lin_hist[IdGrupoUsuario] = $lin_temp[DescricaoGrupoUsuario];
			
			$sql_temp = "select 
						GrupoUsuario.DescricaoGrupoUsuario
					from 
						GrupoUsuario
					where
						GrupoUsuario.IdLoja = '$local_IdLoja' and
						GrupoUsuario.IdGrupoUsuario = ".$local_IdGrupoUsuarioAtendimento.";";
			$res_temp = @mysql_query($sql_temp,$con);
			$lin_temp = @mysql_fetch_array($res_temp);
			$local_IdGrupoUsuarioAtendimentoTemp = $lin_temp[DescricaoGrupoUsuario];
			
			$local_HistoricoAlteracao .= "<div>Grupo Atendimento. [".$lin_hist[IdGrupoUsuario]." > ".$local_IdGrupoUsuarioAtendimentoTemp."]</div>";
		}
		
		if($lin_hist[LoginResponsavel] != $local_LoginAtendimento){
			$sql_temp = "select 
						Pessoa.Nome
					from 
						Usuario,
						Pessoa
					where
						Usuario.IdPessoa = Pessoa.IdPessoa and
						Usuario.Login = '".$lin_hist[LoginResponsavel]."';";
			$res_temp = @mysql_query($sql_temp,$con);
			$lin_temp = @mysql_fetch_array($res_temp);
			$lin_hist[LoginResponsavel] = $lin_temp[Nome];
			
			$sql_temp = "select 
						Pessoa.Nome
					from 
						Usuario,
						Pessoa
					where
						Usuario.IdPessoa = Pessoa.IdPessoa and
						Usuario.Login = '".$local_LoginAtendimento."';";
			$res_temp = @mysql_query($sql_temp,$con);
			$lin_temp = @mysql_fetch_array($res_temp);
			$local_LoginAtendimentoTemp = $lin_temp[Nome];
			
			$local_HistoricoAlteracao .= "<div>Usuário Atendimento. [".$lin_hist[LoginResponsavel]." > ".$local_LoginAtendimentoTemp."]</div>";
		}
		
		if($local_Data != "" && $local_Hora != ""){
			$local_PrevisaoEtapa = "'".dataConv($local_Data,'d/m/Y','Y-m-d')." ".$local_Hora.":00'";
			$local_PrevisaoEtapaTemp = "$local_Data $local_Hora";
		} else{
			$local_Data = '';
			$local_Hora = '';
			$local_PrevisaoEtapa = 'NULL';
			$local_PrevisaoEtapaTemp = '';
		}
		
		if($lin_hist[PrevisaoEtapa] != $local_PrevisaoEtapaTemp){
			$local_HistoricoAlteracao .= "<div>Previsão de Conclusão da Etapa. [$lin_hist[PrevisaoEtapa] > $local_PrevisaoEtapaTemp]</div>";
		}
		
		if($local_Mensagem != ''){
			$local_Mensagem = "<b>Escrito por:</b> $local_Login <div style=\'margin-top:6px;\'>".str_replace('"',"'",$local_Mensagem)."</div>";
		}
		
		if($local_HistoricoAlteracao != ''){
			$local_Mensagem .= "<div><br /><div style=\'padding-bottom:10px;\'><b>Alterado por:</b> ".$local_Login."</div><div>".$local_HistoricoAlteracao."</div></div>";
		}
		
		$local_Mensagem = str_replace('"',"'",$local_Mensagem);
		# GRAVAR HISTÓRICO #
		$sql = "select 
					(max(IdProtocoloHistorico) + 1) IdProtocoloHistorico 
				from 
					ProtocoloHistorico
				where
					IdLoja = '$local_IdLoja' and
					IdProtocolo = '$local_IdProtocolo';";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdProtocoloHistorico] != NULL){ 
			$local_IdProtocoloHistorico = $lin[IdProtocoloHistorico];
		} else{
			$local_IdProtocoloHistorico = 1;
		}
		
		if($local_Concluir == 1){
			$lin_hist[IdStatus] = 200;
		} else{
			$lin_hist[IdStatus] = 100;
		}
		
		$sql = "insert into
					ProtocoloHistorico
				set
					IdLoja					= '$local_IdLoja',
					IdProtocolo				= '$local_IdProtocolo',
					IdProtocoloHistorico	= '$local_IdProtocoloHistorico',
					Mensagem				= \"$local_Mensagem\",
					PrevisaoEtapa			= $local_PrevisaoEtapa,
					IdStatus				= '$lin_hist[IdStatus]',
					LoginCriacao			= '$local_Login', 
					DataCriacao				= (concat(curdate(),' ',curtime()));";
		
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		# MODIFICAÇÃO DE VARIÁVEIS PARA A INSERÇÃO NO DATABASE #
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
		
		if($local_IdPessoa == ''){
			$local_IdPessoa = "NULL";
		} else{
			$local_IdPessoa = "'$local_IdPessoa'";
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
		
		if($local_Concluir == 1){
			$set .= ", IdStatus = '200', LoginConclusao = '$local_Login', DataConclusao = (concat(curdate(),' ',curtime()))";
		} else{
			$set .= ", IdStatus = '100'";
		}
		
		$sql = "update Protocolo set
					LocalAbertura		= '$local_IdLocalAbertura',
					IdProtocoloTipo		= '$local_IdProtocoloTipo',
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
					IdGrupoUsuario		= $local_IdGrupoUsuarioAtendimento,
					LoginResponsavel	= $local_LoginAtendimento,
					PrevisaoEtapa		= $local_PrevisaoEtapa,
					LoginAlteracao		= '$local_Login', 
					DataAlteracao		= (concat(curdate(),' ',curtime()))
					$set
				where
					IdLoja = '$local_IdLoja' and
					IdProtocolo = '$local_IdProtocolo';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		for($i = 0; $i < $tr_i; $i++){
			if(!$local_transaction[$i]){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction == true){
			$sql = "commit;";
			@mysql_query($sql,$con);
			
			if($local_Concluir == 1){
				$sqlMensagem = "select
									Titulo,
									Assunto,
									Conteudo,
									IdStatus
								from
									TipoMensagem
								where
									IdLoja = $local_IdLoja and
									IdTipoMensagem = 37";
				$resMensagem = mysql_query($sqlMensagem,$con);
				$linMensagem = mysql_fetch_array($resMensagem);
				
				if($linMensagem[IdStatus] != 1){
					$local_ErroEmail		= getParametroSistema(13,191);
					$local_TipoEmail		= "'".delimitaAteCaracter($linMensagem[Titulo],'$')."'";
				}
				enviarEmailConclusaoProtocolo($local_IdLoja, $local_IdProtocolo);
			}
			
			$local_Erro = 4;			// Mensagem de Inserção Positiva
		} else{
			$sql = "rollback;";
			@mysql_query($sql,$con);
			
			$local_Erro = 5;			// Mensagem de Inserção Negativa
		}
	}
?>
