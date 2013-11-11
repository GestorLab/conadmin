<?php 
	$Path = "../";
	
	include($Path.'files/funcoes.php');
	include($Path.'files/conecta_cntsistemas.php');
	include($Path.'files/conecta.php');

	# TEMP
	$local_Assunto = "Data/Hora desconfigurada";
	$local_IdTipoTicket = "51";
	$local_IdSubTipoTicket = "3";
	$local_IdStatus = "100";
	$local_IdPessoa = getParametroSistema(4,7);
	$local_Login = "automatico";
	
	$sql = "SELECT 
				NOW() DataHora
			FROM
				(
					SELECT 
						COUNT(IdTicket) Qtd 
					FROM
						HelpDesk 
					WHERE 
						HelpDesk.IdTipoHelpDesk = '$local_IdTipoTicket' AND 
						HelpDesk.IdSubTipoHelpDesk = '$local_IdSubTipoTicket' AND 
						HelpDesk.IdStatus != '400' AND 
						HelpDesk.Assunto = '$local_Assunto' and 
						HelpDesk.IdPessoa = '$local_IdPessoa'
				) HelpDeskTmp 
			WHERE 
				NOW() != '".date("Y-m-d H:i:s")."' AND 
				HelpDeskTmp.Qtd = '0' ";
	$res = @mysql_query($sql,$conCNT);
	$num = @mysql_num_rows($res);
	
	if($num > 0){
		$sql = "START TRANSACTION;";
		@mysql_query($sql,$conCNT);
		
		$tr_i = 0;
		$sql = "SELECT 
					(MAX(IdTicket)+1) IdTicket 
				FROM 
					HelpDesk;";
		$res = @mysql_query($sql,$conCNT);
		$lin = @mysql_fetch_array($res);
		
		if($lin["IdTicket"] != null){ 
			$local_IdTicket	= $lin["IdTicket"];
		} else{
			$local_IdTicket	= 1;
		}
		
		$ListaEmailUsuarioHelpDesk = ListaEmailUsuarioHelpDesk(1);
		
		$sql = "INSERT INTO
					HelpDesk
				SET
					IdLoja				= 1,
					IdTicket			= $local_IdTicket,
					IdLojaAbertura		= 1,
					IdLocalAbertura		= 2,
					IdPrioridade		= 0,
					IdPessoa			= $local_IdPessoa,
					Assunto				= '$local_Assunto',
					EmailsGrupo			= '$ListaEmailUsuarioHelpDesk',
					IdTipoHelpDesk		= $local_IdTipoTicket,
					IdSubTipoHelpDesk	= $local_IdSubTipoTicket,
					MD5					= MD5($local_IdTicket),
					IdStatus			= '$local_IdStatus',
					DataCriacao			= NOW(),
					LoginCriacao		= '$local_Login';";
		$local_transaction[$tr_i] = @mysql_query($sql,$conCNT);
		$tr_i++;
		
		$local_Mensagem = "<b>Escrito por:</b> $local_Login <div style=\'margin-top:6px;\'>Atenção: As datas do servidor não estão sincronizadas.</div>";
		
		$sql1 = "SELECT
					HelpDeskTipo.DescricaoTipoHelpDesk,
					HelpDeskSubTipo.DescricaoSubTipoHelpDesk
				FROM
					HelpDeskTipo,
					HelpDeskSubTipo
				WHERE
					HelpDeskTipo.IdStatus = '1' AND
					HelpDeskTipo.IdTipoHelpDesk = $local_IdTipoTicket AND
					HelpDeskSubTipo.IdSubTipoHelpDesk = $local_IdSubTipoTicket AND
					HelpDeskSubTipo.IdTipoHelpDesk = HelpDeskTipo.IdTipoHelpDesk;";
		$res1 = @mysql_query($sql1,$conCNT);
		
		if(($lin1 = @mysql_fetch_array($res1)) && $local_Mensagem != ''){
			$local_Mensagem = "<div style=\'margin:6px 0px 6px 0px;\' class=\'none\'><b>Assunto:</b> $local_Assunto</div>$local_Mensagem";
			$local_Mensagem = "<div style=\'margin-top:6px;\' class=\'none\'><b>SubTipo:</b> $lin1[DescricaoSubTipoHelpDesk]</div>$local_Mensagem";
			$local_Mensagem = "<div style=\'margin-top:6px;\' class=\'none\'><b>Tipo:</b> $lin1[DescricaoTipoHelpDesk]</div>$local_Mensagem";
		}
		
		$sql = "INSERT INTO
					HelpDeskHistorico
				SET
					IdTicket			= $local_IdTicket,
					IdTicketHistorico	= 1,
					Obs					= '$local_Mensagem',
					IdLocalHistorico	= 2,
					IdStatusTicket		= $local_IdStatus,
					Publica				= 1,
					DataCriacao			= NOW(),
					LoginCriacao		= '$local_Login';";
		$local_transaction[$tr_i] = @mysql_query($sql,$conCNT);
		$tr_i++;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}

		if($local_transaction == true){
			$sql = "COMMIT;";
		} else{
			$sql = "ROLLBACK;";
		}
		
		@mysql_query($sql,$conCNT);
	}
?>