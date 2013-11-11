<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		// Sql de Alteração de Help Desk //
		$sql	=	"START TRANSACTION;";
		@mysql_query($sql,$conCNT);
		$tr_i = 0;
		
		if($local_Acao=='aceitar' || $local_Acao=='reabrir' || $local_Acao=='encaminhar' || $local_IdStatus=='200'){
			if($local_Acao=='aceitar'){
				$local_IdStatus = 400;
			} else{
				if($local_Acao=='reabrir' || $local_Acao=='encaminhar' || $local_IdStatus=='200'){
					$local_IdStatus = 100;
				}
			}

			$ListaEmailUsuarioHelpDesk = ListaEmailUsuarioHelpDesk($local_IdLoja);
		
			$sql = "update
						HelpDesk
					set
						EmailsGrupo = '$ListaEmailUsuarioHelpDesk',
						IdStatus = $local_IdStatus,
						DataAlteracao = NOW()
					where
						IdLoja = 1 and
						IdLojaAbertura = $local_IdLoja and
						IdTicket = $local_IdTicket;";
			$local_transaction[$tr_i] = @mysql_query($sql,$conCNT);			
			$tr_i++;
		}
		
		$sql	=	"select 
						(max(HelpDeskHistorico.IdTicketHistorico)+1) IdTicketHistorico,
						HelpDesk.IdStatus
					from 
						HelpDesk,
						HelpDeskHistorico
					where 
						HelpDesk.IdLoja = 1 and
						HelpDesk.IdLojaAbertura = $local_IdLoja and
						HelpDesk.IdTicket = $local_IdTicket and
						HelpDesk.IdTicket = HelpDeskHistorico.IdTicket;
		";
		$res	=	@mysql_query($sql,$conCNT);
		$lin	=	@mysql_fetch_array($res);
		
		if($local_IdStatus!=""){
			$lin[IdStatus] = $local_IdStatus;
		}
		
		if($lin[IdTicketHistorico]!=NULL){ 
			$local_IdTicketHistorico	= $lin[IdTicketHistorico];
		}else{
			$local_IdTicketHistorico	= 1;
		}

		//$local_Mensagem = str_replace("'","\'",$local_Mensagem);//Leonardo -> A variável ja é formatada no cadastro ao ser passada por POST
		
		if($local_Mensagem != ''){
			if($local_Login == "root"){
				$local_Mensagem = "<b>Escrito por:</b> $local_Login <div style=\'margin-top:6px;\'>$local_Mensagem</div>";
			} else {
				$sql_sc = "SELECT Pessoa.TipoPessoa, Pessoa.Nome, Pessoa.NomeRepresentante FROM Usuario, Pessoa WHERE Login = '$local_Login' AND Usuario.IdPessoa = Pessoa.IdPessoa";
				$res_sc = @mysql_query($sql_sc,$con);
				$lin_sc = @mysql_fetch_array($res_sc);
				
				if($lin_sc[TipoPessoa] == 2){
					$local_Mensagem = "<b>Escrito por:</b> $local_Login ($lin_sc[Nome]) <div style=\'margin-top:6px;\'>$local_Mensagem</div>";
				} else {
					$local_Mensagem = "<b>Escrito por:</b> $local_Login ($lin_sc[Nome]/$lin_sc[NomeRepresentante]) <div style=\'margin-top:6px;\'>$local_Mensagem</div>";
				}
			}
		}
		
		$sql	=	"
					INSERT INTO
						HelpDeskHistorico
					SET
						IdTicket			= $local_IdTicket,
						IdTicketHistorico	= $local_IdTicketHistorico,
						Obs					= '$local_Mensagem',
						IdLocalHistorico	= 1,
						IdStatusTicket		= $lin[IdStatus],
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
				$local_ExtArquivo	= end(explode(".", $local_NomeOriginal));
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
			@mysql_query($sql,$conCNT);

			// Corrigir IdLoja
			enviarTicket(1, $local_IdTicket, $local_IdLoja);

			$local_Erro = 4;
		}else{
			$sql = "ROLLBACK;";
			@mysql_query($sql,$conCNT);
			$local_Erro = 5;
		}
	}
?>