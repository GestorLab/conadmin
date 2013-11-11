<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de Help Desk //
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$conCNT);
		$tr_i = 0;
		
		$sql	=	"select (max(IdTicket)+1) IdTicket from HelpDesk;";
		$res	=	@mysql_query($sql,$conCNT);
		$lin	=	@mysql_fetch_array($res);
		
		if($lin[IdTicket]!=NULL){ 
			$local_IdTicket	= $lin[IdTicket];
		}else{
			$local_IdTicket	= 1;
		}
		
		if($local_Data!=''){
			$local_MensagemTemp		= "<div style=\'margin-top:6px;\'>Previsão de Conclusão da Etapa. [$local_Data $local_Hora]</div>";
			$local_PrevisaoEtapa	= "'".dataConv($local_Data,'d/m/Y','Y-m-d')." ".$local_Hora.":00'";
		}else{
			$local_MensagemTemp		= '';
			$local_PrevisaoEtapa	= "NULL";
		}
		
		if($local_ChangeLog == ""){
			$local_ChangeLog = '(NULL)';
		} else{
			$local_ChangeLog = "'$local_ChangeLog'";
		}
		
		if($local_IdGrupoUsuarioAtendimento == "")	$local_IdGrupoUsuarioAtendimento	= '(NULL)';
		if($local_IdPessoa == "")					$local_IdPessoa						= '(NULL)';
		if($local_IdPrioridade == "")				$local_IdPrioridade					= 0;

		$sql = "select 
					EmailsGrupo,
					if(DataAlteracao is null, concat(curdate(), ' ',curtime()),DataAlteracao) DataAlteracao
				from 
					HelpDesk 
				where 
					IdLoja = $local_IdLoja and
					EmailsGrupo != '' and 
					IdPessoa = $local_IdPessoa
				order by 
					DataAlteracao DESC,
					DataCriacao DESC
				limit 0,1";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$sql	= "
					INSERT INTO
						HelpDesk
					SET
						IdTicket				= $local_IdTicket,
						IdLoja					= $local_IdLoja,
						IdLojaAbertura			= $local_IdLojaHelpDesk,
						IdLocalAbertura			= 2,
						IdPrioridade			= $local_IdPrioridade,
						IdPessoa				= $local_IdPessoa,
						Assunto					= '$local_Assunto',
						EmailsGrupo				= '$lin[EmailsGrupo]',
						IdTipoHelpDesk			= $local_IdTipoTicket,
						IdSubTipoHelpDesk		= $local_IdSubTipoTicket,
						IdGrupoUsuario			= $local_IdGrupoUsuarioAtendimento,
						LoginResponsavel		= '$local_LoginAtendimento',
						PrevisaoEtapa			= $local_PrevisaoEtapa,
						ChangeLog				= $local_ChangeLog,
						MD5						= MD5($local_IdTicket),
						IdStatus				= $local_IdStatus,
						DataCriacao				= (concat(curdate(),' ',curtime())),
						LoginCriacao			= '$local_Login';
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
		
		if($local_Mensagem != ''){
			if($local_Login == "root"){
				$local_Mensagem = "<b>Escrito por:</b> $local_Login <div style=\'margin-top:6px;\'>$local_Mensagem</div>$local_MensagemTemp";
			} else {
				$sql = "SELECT Pessoa.TipoPessoa, Pessoa.Nome, Pessoa.NomeRepresentante FROM Usuario, Pessoa WHERE Login = '$local_Login' AND Usuario.IdPessoa = Pessoa.IdPessoa";
				$res = @mysql_query($sql,$conCNT);
				$lin = @mysql_fetch_array($res);
				
				if($lin[TipoPessoa] == 2){
					$local_Mensagem = "<b>Escrito por:</b> $local_Login ($lin[Nome]) <div style=\'margin-top:6px;\'>$local_Mensagem</div>$local_MensagemTemp";
				} else {
					$local_Mensagem = "<b>Escrito por:</b> $local_Login ($lin[Nome]/$lin[NomeRepresentante]) <div style=\'margin-top:6px;\'>$local_Mensagem</div>$local_MensagemTemp";
				}
			}
		}
		
		$sql1	=	"select
						HelpDeskTipo.DescricaoTipoHelpDesk,
						HelpDeskSubTipo.DescricaoSubTipoHelpDesk
					from
						HelpDeskTipo,
						HelpDeskSubTipo
					where
						HelpDeskTipo.IdStatus = 1 and
						HelpDeskTipo.IdTipoHelpDesk = $local_IdTipoTicket and
						HelpDeskSubTipo.IdSubTipoHelpDesk = $local_IdSubTipoTicket and
						HelpDeskSubTipo.IdTipoHelpDesk = HelpDeskTipo.IdTipoHelpDesk;";
		$res1	=	@mysql_query($sql1,$conCNT);
		if(($lin1 = @mysql_fetch_array($res1)) && $local_Mensagem != ''){
			$local_Mensagem = "<div style=\'margin:6px 0px 6px 0px;\' class=\'none\'><b>Assunto:</b> $local_Assunto</div>$local_Mensagem";
			$local_Mensagem = "<div style=\'margin-top:6px;\' class=\'none\'><b>SubTipo:</b> $lin1[DescricaoSubTipoHelpDesk]</div>$local_Mensagem";
			$local_Mensagem = "<div style=\'margin-top:6px;\' class=\'none\'><b>Tipo:</b> $lin1[DescricaoTipoHelpDesk]</div>$local_Mensagem";
		}
		
		$sql	=	"
					INSERT INTO
						HelpDeskHistorico
					SET
						IdTicket			= $local_IdTicket,
						IdTicketHistorico	= $local_IdTicketHistorico,
						Obs					= '$local_Mensagem',
						IdLocalHistorico	= 2,
						IdStatusTicket		= $local_IdStatus,
						Publica				= 1,
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
				$local_ExtArquivo	= endArray(explode(".",$local_NomeOriginal));
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
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}

		if($local_transaction == true){
			$sql = "COMMIT;";
			mysql_query($sql,$conCNT);
			// Muda a ação para Inserir

			// Corrigir IdLoja
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

			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$sql = "ROLLBACK;";
			mysql_query($sql,$conCNT);

			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>
