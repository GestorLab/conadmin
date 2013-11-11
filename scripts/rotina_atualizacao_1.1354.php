<?
	include ('../files/conecta.php');
	include ('../files/conecta_cntsistemas.php');
	include ('../files/funcoes.php');

	set_time_limit(0);
	ini_set("memory_limit",getParametroSistema(138,1));

	$IdPessoa = getParametroSistema(4,7);
	$Path	  = getParametroSistema(6,1);

	$Error = false;
	$sql = "START TRANSACTION;";
	mysql_query($sql,$conCNT);

	$sql = "select
				HelpDesk.IdTicket,
				HelpDeskAnexo.IdTicketHistorico,
				HelpDeskAnexo.IdAnexo,
				HelpDeskAnexo.NomeOriginal
			from
				HelpDesk,
				HelpDeskAnexo
			where
				HelpDesk.IdPessoa = $IdPessoa and
				HelpDesk.IdTicket = HelpDeskAnexo.IdTicket";
	$res = mysql_query($sql,$conCNT);
	while($lin = mysql_fetch_array($res)){
		$PathTemp = $Path."modulos/helpdesk/anexo/$lin[IdTicket]/$lin[IdTicketHistorico]/$lin[IdAnexo].".end(explode(".",$lin[NomeOriginal]));

		if(file_exists($PathTemp)){
			$content = getContent($PathTemp);

			$sql0 = "
				UPDATE
					HelpDeskAnexo
				SET
					FileAnexo = '$content'
				WHERE
					IdTicket = '$lin[IdTicket]' AND
					IdTicketHistorico = '$lin[IdTicketHistorico]' AND
					IdAnexo = '$lin[IdAnexo]';";
			if(!(mysql_query($sql0,$conCNT))){
				$Error = true;
				break;
			}
		}
	}

	if($Error){
		echo $sql = "ROLLBACK;";
	} else{
		$sql = "COMMIT;";
		system("rm -r ".$Path."modulos/helpdesk/anexo");
	}

	mysql_query($sql,$conCNT);
?>
