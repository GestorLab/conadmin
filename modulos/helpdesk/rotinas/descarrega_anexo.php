<?
	$sql =  "SELECT
				HelpDeskAnexo.IdTicket,
				HelpDeskAnexo.IdTicketHistorico,
				HelpDeskAnexo.IdAnexo,
				HelpDeskAnexo.NomeOriginal,
				HelpDeskAnexo.MD5,
				HelpDeskAnexo.FileAnexo
			FROM
				HelpDesk,
				HelpDeskAnexo,
				HelpDeskHistorico
			WHERE
				HelpDesk.IdTicket = HelpDeskHistorico.IdTicket AND
				HelpDeskAnexo.IdTicket = HelpDeskHistorico.IdTicket AND
				HelpDeskAnexo.IdTicketHistorico = HelpDeskHistorico.IdTicketHistorico AND
				HelpDesk.IdStatus = 400 AND
				HelpDesk.DataAlteracao <= DATE_ADD(CURDATE(),INTERVAL -1 MONTH) AND
				HelpDeskAnexo.FileAnexo IS NOT NULL
			limit 0,1";
	$res = mysql_query($sql,$conCNT);
	if($lin = mysql_fetch_array($res)){
		
		$PatchAnexo = $Path."modulos/helpdesk/anexo/".$lin[IdTicket];

		@mkdir($PatchAnexo);

		$PatchAnexo = $PatchAnexo."/".$lin[MD5];

		@unlink($PatchAnexo);

		$file = fopen($PatchAnexo, "x");
		if(is_writable($PatchAnexo)){
			fwrite($file, $lin[FileAnexo]);
		}

		fclose($file);

		if(file_exists($PatchAnexo)){
			$sqlDescarrega = "UPDATE HelpDeskAnexo SET FileAnexo = NULL WHERE 
								IdTicket = $lin[IdTicket] AND 
								IdTicketHistorico = $lin[IdTicketHistorico] AND 
								IdAnexo = $lin[IdAnexo]";
			mysql_query($sqlDescarrega,$conCNT);
		}
	}
?>