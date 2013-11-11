<?
	$local_Login	= $_SESSION["Login"];
	
	// Sql de Alteração de Help Desk //
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$conCNT);
	$tr_i = 0;
	
	if(($local_IdStatus != 400 || $local_IdStatus != 600) && $local_Mensagem != ''){
		if($local_Acao=='finalizar'){
			$local_IdStatus = 600;
		}
		
		if($local_Acao=='pendente'){
			$local_IdStatus = 300;
		}
		
		
		$sql	=	"select 
						HelpDesk.IdGrupoUsuario,
						HelpDesk.IdStatus,
						HelpDesk.LoginResponsavel,
						HelpDesk.PrevisaoEtapa,
						HelpDesk.IdTipoHelpDesk,
						HelpDesk.IdSubTipoHelpDesk,
						HelpDesk.IdPessoa,
						HelpDeskTipo.DescricaoTipoHelpDesk,
						HelpDeskSubTipo.DescricaoSubTipoHelpDesk
					from 
						HelpDesk,
						HelpDeskTipo,
						HelpDeskSubTipo
					where 
						HelpDesk.IdLoja = $local_IdLoja and
						HelpDesk.IdTicket = $local_IdTicket and
						HelpDesk.IdTipoHelpDesk = HelpDeskTipo.IdTipoHelpDesk and
						HelpDesk.IdTipoHelpDesk = HelpDeskSubTipo.IdTipoHelpDesk and
						HelpDesk.IdSubTipoHelpDesk = HelpDeskSubTipo.IdSubTipoHelpDesk;";
		$res	=	@mysql_query($sql,$conCNT);
		$lin	=	@mysql_fetch_array($res);
		
		$lin[PrevisaoEtapa] = dataConv($lin[PrevisaoEtapa],'Y-m-d H:i:s','d/m/Y H:i');
		
		if($local_Login == "root"){
			$local_Mensagem = "<b>Escrito por:</b> $local_Login <div style=\'margin-top:6px;\'>$local_Mensagem</div>";
		} else {
			$sql_sc = "SELECT Pessoa.TipoPessoa, Pessoa.Nome, Pessoa.NomeRepresentante FROM Usuario, Pessoa WHERE Login = '$local_Login' AND Usuario.IdPessoa = Pessoa.IdPessoa";
			$res_sc = @mysql_query($sql_sc,$conCNT);
			$lin_sc = @mysql_fetch_array($res_sc);
			
			if($lin_sc[TipoPessoa] == 2){
				$local_Mensagem = "<b>Escrito por:</b> $local_Login ($lin_sc[Nome]) <div style=\'margin-top:6px;\'>$local_Mensagem</div>";
			} else {
				$local_Mensagem = "<b>Escrito por:</b> $local_Login ($lin_sc[Nome]/$lin_sc[NomeRepresentante]) <div style=\'margin-top:6px;\'>$local_Mensagem</div>";
			}
		}
		
		$local_Alteracao = '';
		
		if($lin[IdGrupoUsuario] != $local_IdGrupoUsuarioAtendimento){
			$set = ", IdMarcador = (NULL)";
		}
		
		if($lin[IdTipoHelpDesk] != $local_IdTipoTicket){
			$sql1	=	"select 
							HelpDeskTipo.DescricaoTipoHelpDesk,
							HelpDeskSubTipo.DescricaoSubTipoHelpDesk
						from 
							HelpDeskTipo,
							HelpDeskSubTipo
						where 
							HelpDeskTipo.IdTipoHelpDesk = HelpDeskSubTipo.IdTipoHelpDesk and
							HelpDeskSubTipo.IdTipoHelpDesk = $local_IdTipoTicket and
							HelpDeskSubTipo.IdSubTipoHelpDesk = $local_IdSubTipoTicket;";
			$res1	=	@mysql_query($sql1, $conCNT);
			$lin1	=	@mysql_fetch_array($res1);
			
			$local_Alteracao .= "<br />Tipo. [$lin[DescricaoTipoHelpDesk] > $lin1[DescricaoTipoHelpDesk]]";
			$local_Alteracao .= "<br />SubTipo. [$lin[DescricaoSubTipoHelpDesk] > $lin1[DescricaoSubTipoHelpDesk]]";
		} else{
			if($lin[IdSubTipoHelpDesk] != $local_IdSubTipoTicket){
				$sql1	=	"select 
								HelpDeskSubTipo.DescricaoSubTipoHelpDesk
							from 
								HelpDeskSubTipo
							where 
								HelpDeskSubTipo.IdTipoHelpDesk = $local_IdTipoTicket and
								HelpDeskSubTipo.IdSubTipoHelpDesk = $local_IdSubTipoTicket;";
				$res1	=	@mysql_query($sql1, $conCNT);
				$lin1	=	@mysql_fetch_array($res1);
				
				$local_Alteracao .= "<br />SubTipo. [$lin[DescricaoSubTipoHelpDesk] > $lin1[DescricaoSubTipoHelpDesk]]";
			}
		}
		
		if($local_IdGrupoUsuarioAtendimento != $lin[IdGrupoUsuario]){
			if($lin[IdGrupoUsuario] != ''){
				$sql1	=	"select 
								UsuarioGrupoUsuario.IdGrupoUsuario, 
								GrupoUsuario.DescricaoGrupoUsuario 
							from 
								UsuarioGrupoUsuario, 
								GrupoUsuario, 
								Usuario, 
								Pessoa 
							where 
								UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
								UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
								UsuarioGrupoUsuario.Login = Usuario.Login and 
								UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
								UsuarioGrupoUsuario.IdGrupoUsuario = $lin[IdGrupoUsuario] and 
								Usuario.IdPessoa = Pessoa.IdPessoa and 
								Pessoa.TipoUsuario = 1 and 
								Usuario.IdStatus = 1 
							group by 
								UsuarioGrupoUsuario.IdGrupoUsuario;";
				$res1	=	@mysql_query($sql1, $conCNT);
				$lin1	=	@mysql_fetch_array($res1);
			}
			
			$sql2	=	"select 
							UsuarioGrupoUsuario.IdGrupoUsuario, 
							GrupoUsuario.DescricaoGrupoUsuario 
						from 
							UsuarioGrupoUsuario, 
							GrupoUsuario, 
							Usuario, 
							Pessoa 
						where 
							UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
							UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
							UsuarioGrupoUsuario.Login = Usuario.Login and 
							UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
							UsuarioGrupoUsuario.IdGrupoUsuario = $local_IdGrupoUsuarioAtendimento and 
							Usuario.IdPessoa = Pessoa.IdPessoa and 
							Pessoa.TipoUsuario = 1 and 
							Usuario.IdStatus = 1 
						group by 
							UsuarioGrupoUsuario.IdGrupoUsuario;";
			$res2	=	@mysql_query($sql2, $conCNT);
			$lin2	=	@mysql_fetch_array($res2);
			
			$local_Alteracao .= "<br />Grupo Atendimento. [$lin1[DescricaoGrupoUsuario] > $lin2[DescricaoGrupoUsuario]]";
		}
		
		if($local_LoginAtendimento != $lin[LoginResponsavel]){
			if($lin[LoginResponsavel] != ''){
				$sql1	=	"select 
								UsuarioGrupoUsuario.IdGrupoUsuario, 
								Pessoa.Nome
							from 
								UsuarioGrupoUsuario, 
								GrupoUsuario, 
								Usuario, 
								Pessoa 
							where 
								UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
								UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
								UsuarioGrupoUsuario.Login = Usuario.Login and 
								UsuarioGrupoUsuario.Login = '$lin[LoginResponsavel]' and 
								UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
								UsuarioGrupoUsuario.IdGrupoUsuario = $lin[IdGrupoUsuario] and 
								Usuario.IdPessoa = Pessoa.IdPessoa and 
								Pessoa.TipoUsuario = 1 and 
								Usuario.IdStatus = 1 
							group by 
								UsuarioGrupoUsuario.IdGrupoUsuario;";
				$res1	=	@mysql_query($sql1, $conCNT);
				$lin1	=	@mysql_fetch_array($res1);
			}
			
			$sql2	=	"select 
							UsuarioGrupoUsuario.IdGrupoUsuario, 
							Pessoa.Nome
						from 
							UsuarioGrupoUsuario, 
							GrupoUsuario, 
							Usuario, 
							Pessoa 
						where 
							UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
							UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
							UsuarioGrupoUsuario.Login = Usuario.Login and 
							UsuarioGrupoUsuario.Login = '$local_LoginAtendimento' and 
							UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
							UsuarioGrupoUsuario.IdGrupoUsuario = $local_IdGrupoUsuarioAtendimento and 
							Usuario.IdPessoa = Pessoa.IdPessoa and 
							Pessoa.TipoUsuario = 1 and 
							Usuario.IdStatus = 1 
						group by 
							UsuarioGrupoUsuario.IdGrupoUsuario;";
			$res2	=	@mysql_query($sql2, $conCNT);
			$lin2	=	@mysql_fetch_array($res2);
			
			$local_Alteracao .= "<br />Usuário Atendimento. [$lin1[Nome] > $lin2[Nome]]";
		}
		
		if(($lin[IdStatus] == $local_IdStatus || $local_IdStatus == 500) && $local_Data != ''){
			$local_PrevisaoEtapa = "'".dataConv($local_Data,'d/m/Y','Y-m-d')." ".$local_Hora.":00'";
			$local_PrevisaoEtapaTemp = "$local_Data $local_Hora";
		} else{
			$local_Data = '';
			$local_Hora = '';
			$local_PrevisaoEtapa = '';
			$local_PrevisaoEtapaTemp = '';
		}
		
		if($local_PrevisaoEtapaTemp != $lin[PrevisaoEtapa]){
			$local_Alteracao .= "<br />Previsão de Conclusão da Etapa. [$lin[PrevisaoEtapa] > $local_Data $local_Hora]";
		}
		
		if($local_PrevisaoEtapa == ''){
			$local_PrevisaoEtapa = "NULL";
		}
		
		if($local_Alteracao != ''){
			if($local_Mensagem != ''){
				$local_Mensagem .= "<br />";
			}
			
			$local_Mensagem = "$local_Mensagem<div style=\'margin:2px 0 -5px 0;\'><b>Alterado por:</b> $local_Login</div> $local_Alteracao";
		}
		
		if($local_IdGrupoUsuarioAtendimento == ""){
			$local_IdGrupoUsuarioAtendimento	= '(NULL)';
		}

		$sqlEmails = "select 
					EmailsGrupo,
					if(DataAlteracao is null, concat(curdate(), ' ',curtime()),DataAlteracao) DataAlteracao
				from 
					HelpDesk 
				where 
					EmailsGrupo != '' and 
					IdPessoa = $lin[IdPessoa] and
					IdLoja = $local_IdLoja
				order by 
					DataAlteracao DESC,
					DataCriacao DESC
				limit 0,1";
		$resEmails = mysql_query($sqlEmails,$con);
		$linEmails = mysql_fetch_array($resEmails);
		
		$sql	=	"
					UPDATE HelpDesk SET
						IdLoja				=  $local_IdLoja,
						IdLojaAbertura		=  $local_IdLojaHelpDesk,
						LoginAlteracao		= '$local_Login',
						IdTipoHelpDesk		=  $local_IdTipoTicket,
						Assunto				= '$local_Assunto',
						EmailsGrupo			= '$linEmails[EmailsGrupo]',
						IdSubTipoHelpDesk	=  $local_IdSubTipoTicket,
						IdGrupoUsuario		=  $local_IdGrupoUsuarioAtendimento,
						LoginResponsavel	= '$local_LoginAtendimento',				
						PrevisaoEtapa		=  $local_PrevisaoEtapa,
						DataAlteracao		=  concat(curdate(),' ',curtime()),
						IdStatus			=  $local_IdStatus
						$set
					WHERE 
						IdLoja				=  $local_IdLoja and
						IdTicket			=  $local_IdTicket
		";
		$local_transaction[$tr_i] = @mysql_query($sql,$conCNT);
		$tr_i++;
		
		$sql	=	"select (max(IdTicketHistorico)+1) IdTicketHistorico from HelpDeskHistorico where IdTicket = $local_IdTicket;";
		$res	=	@mysql_query($sql,$conCNT);
		$lin	=	@mysql_fetch_array($res);
		
		if($lin[IdTicketHistorico]!=NULL){ 
			$local_IdTicketHistorico	= $lin[IdTicketHistorico];
		}else{
			$local_IdTicketHistorico	= 1;
		}
		
		$sql	=	"
					INSERT INTO
						HelpDeskHistorico
					SET
						IdTicket			= $local_IdTicket,
						IdTicketHistorico	= $local_IdTicketHistorico,
						Obs					= '$local_Mensagem',
						Publica				= $local_Publica,
						IdLocalHistorico	= 2,
						IdStatusTicket		= $local_IdStatus,
						DataCriacao			= (concat(curdate(),' ',curtime())),
						LoginCriacao		= '$local_Login';
		";
		$local_transaction[$tr_i] = @mysql_query($sql,$conCNT);
		$tr_i++;
		
		for($i = 1; $i <= $_POST["MaxUploads"]; $i++){
			if($_POST['fakeupload_'.$i] != '' && $_POST['DescricaoArquivo_'.$i] != ''){
				$sql	=	"select 
								(max(IdAnexo)+1) IdAnexo
							from 
								HelpDeskAnexo
							where 
								IdTicket = $local_IdTicket and
								IdTicketHistorico = $local_IdTicketHistorico;
				";
				$res	=	@mysql_query($sql,$conCNT);
				$lin	=	@mysql_fetch_array($res);
				
				if($lin['IdAnexo'] == ''){
					$lin['IdAnexo'] = 1;
				}
				
				$local_NomeOriginal	= $_FILES['EndArquivo_'.$i]['name'];
				$local_ExtArquivo	= endArray(explode(".", $local_NomeOriginal));
				$local_MD5			= md5($local_IdTicket.$local_IdTicketHistorico.$lin[IdAnexo]);

				$local_NomeOriginal = str_replace(",",".",$local_NomeOriginal);
				
				if(in_array(strtolower($local_ExtArquivo), $extensao_anexo)){
					$content_arq = getContent($_FILES['EndArquivo_'.$i]['tmp_name']);
					
					if($content_arq){
						$sql	=	"
									INSERT INTO
										HelpDeskAnexo
									SET
										IdTicket			=  $local_IdTicket,
										IdTicketHistorico	=  $local_IdTicketHistorico,
										IdAnexo				=  $lin[IdAnexo],
										DescricaoAnexo		= '".$_POST['DescricaoArquivo_'.$i]."',
										NomeOriginal		= '".$local_NomeOriginal."',
										FileAnexo			= '$content_arq',
										MD5					= '$local_MD5';
						";
						$local_transaction[$tr_i] = @mysql_query($sql,$conCNT);
					} else{
						$local_transaction[$tr_i] = false;
					}
				} else{
					$local_transaction[$tr_i] = false;
				}
				
				$tr_i++;
			}
		}
	} 
	if($local_ChangeLog != ''){
		$sql	=	"
					UPDATE HelpDesk SET 
						ChangeLog			= '$local_ChangeLog',
						LoginAlteracao		= '$local_Login',
						DataAlteracao		=  concat(curdate(),' ',curtime())
					WHERE 
						IdLoja				=  $local_IdLoja and
						IdTicket			=  $local_IdTicket
		";
		$local_transaction[$tr_i] = @mysql_query($sql,$conCNT);	
		$tr_i++;
	}
	
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
		}
	}
	
	if($local_transaction == true){
		$sql = "COMMIT;";
		mysql_query($sql,$conCNT);

		if($local_Publica == 1){
			$sqlMensagem = "select
								Titulo,
								Assunto,
								Conteudo,
								IdStatus
							from
								TipoMensagem
							where
								IdLoja = $local_IdLoja and
								IdTipoMensagem = 7";
			$resMensagem = mysql_query($sqlMensagem,$con);
			$linMensagem = mysql_fetch_array($resMensagem);
			
			if($linMensagem[IdStatus] != 1){
				$local_ErroEmail		= getParametroSistema(13,191);
				$local_TipoEmail		= "'".delimitaAteCaracter($linMensagem[Titulo],'$')."'";
			}
			enviarTicket($local_IdLoja, $local_IdTicket, $local_IdLojaHelpDesk);
		}

		$local_Erro = 4;
	}else{
		$sql = "ROLLBACK;";
		mysql_query($sql,$conCNT);

		$local_Erro = 5;
	}
	
?>
