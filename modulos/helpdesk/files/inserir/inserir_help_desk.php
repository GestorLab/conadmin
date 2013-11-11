<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de Help Desk //
		$sql	=	"START TRANSACTION;";
		@mysql_query($sql,$conCNT);
		$tr_i = 0;
				
		$sql	=	"select (max(IdTicket)+1) IdTicket from HelpDesk;";
		$res	=	@mysql_query($sql,$conCNT);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdTicket]!=NULL){ 
			$local_IdTicket	= $lin[IdTicket];
		}else{
			$local_IdTicket	= 1;
		}

		//$local_Mensagem = str_replace("'","\'",$local_Mensagem);//Leonardo -> A variável ja é formatada no cadastro ao ser passada por POST
				
				
		if($local_Mensagem != ''){
			if($local_Login == "root"){
				$local_Mensagem = "<b>Escrito por:</b> $local_Login <div style=\'margin-top:6px;\'>$local_Mensagem</div>$local_MensagemTemp";
			} else {
				$sql = "SELECT Pessoa.TipoPessoa, Pessoa.Nome, Pessoa.NomeRepresentante FROM Usuario, Pessoa WHERE Login = '$local_Login' AND Usuario.IdPessoa = Pessoa.IdPessoa";
				$res = @mysql_query($sql,$con);
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

		$ListaEmailUsuarioHelpDesk = ListaEmailUsuarioHelpDesk($local_IdLoja);
		
		$sql	=	"
					INSERT INTO
						HelpDesk
					SET
						IdLoja					= 1,
						IdLojaAbertura			= $local_IdLoja,
						IdTicket				= $local_IdTicket,
						IdLocalAbertura			= 1,
						IdPrioridade			= 0,
						IdPessoa				= $local_IdPessoa,
						Assunto					= '$local_Assunto',
						IdTipoHelpDesk			= $local_IdTipoTicket,
						IdSubTipoHelpDesk		= $local_IdSubTipoTicket,
						EmailsGrupo				= '$ListaEmailUsuarioHelpDesk',
						MD5						= MD5($local_IdTicket),
						IdStatus				= 100,
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
		$sql	=	"
					INSERT INTO
						HelpDeskHistorico
					SET
						IdTicket			= $local_IdTicket,
						IdTicketHistorico	= $local_IdTicketHistorico,
						Obs					= '$local_Mensagem',
						IdLocalHistorico	= 1,
						IdStatusTicket		= 100,
						DataCriacao			= (concat(curdate(),' ',curtime())),
						LoginCriacao		= '$local_Login';
		";
		$local_transaction[$tr_i] = @mysql_query($sql,$conCNT);
		$tr_i++;
		
		for($i = 1; $i <= $_POST["MaxUploads"]; $i++){
			if($_POST['fakeupload_'.$i] != ''){
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
				$local_ExtArquivo	= end(explode(".",$local_NomeOriginal));
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
										MD5					= '$local_MD5';";
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
			@mysql_query($sql,$conCNT);

			// Corrigir IdLoja
			enviarTicket(1, $local_IdTicket, $local_IdLoja);

			// Muda a ação para Inserir
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$sql = "ROLLBACK;";
			@mysql_query($sql,$conCNT);

			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>