<?
	include ("../../files/conecta.php");
	
	// Insere as mesnsagens de prioridade que estão faltando
	$sql	= "select
			      MSG.IdParametroSistema
			   from
				  (select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=13) MSG
			   where
			      MSG.IdParametroSistema not in (select IdParametroSistema from ParametroSistema where IdGrupoParametroSistema=14)";
	$res	= mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$MsgPrioridade = str_replace('$IdMensagemAviso', $lin[IdParametroSistema], getParametroSistema(22,2));
		$sqlInsertMsgPrioridade = "INSERT INTO ParametroSistema (IdGrupoParametroSistema, IdParametroSistema, DescricaoParametroSistema, LoginCriacao, DataCriacao) VALUES (14, $lin[IdParametroSistema], '$MsgPrioridade', 'root', concat(curdate(),' ',curtime()));";
		mysql_query($sqlInsertMsgPrioridade,$con);
	}

	// Deleta as mesnsagens de prioridade que estão sobrando
	$sql	= "select
			      PRI.IdParametroSistema
			   from
				  (select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=14) PRI
			   where
			      PRI.IdParametroSistema not in (select IdParametroSistema from ParametroSistema where IdGrupoParametroSistema=13)";
	$res	= mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sqlDeletaMsgPrioridade = "DELETE FROM ParametroSistema WHERE IdGrupoParametroSistema=14 AND IdParametroSistema=$lin[IdParametroSistema];";
		mysql_query($sqlDeletaMsgPrioridade,$con);
	}
	
	$nomeXML	= getParametroSistema(6,2);	// Pega o nome do arquivo
		
	$sql	= "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=13";
	$res	= mysql_query($sql,$con);
	
	$sql2	= "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=14";
	$res2	= mysql_query($sql2,$con);
	
	$file = fopen ($nomeXML, "w");
	
	fwrite($file, "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>");	
	fwrite($file, "\n");
	
	fwrite($file, "<reg>");
	fwrite($file, "\n");
	
	while($lin = mysql_fetch_array($res)){

		fwrite($file, "\t<msg$lin[IdParametroSistema]>");
		fwrite($file, "<![CDATA[$lin[ValorParametroSistema]]]>");
		fwrite($file, "</msg$lin[IdParametroSistema]>");
		fwrite($file, "\n");
		
		$lin2 = mysql_fetch_array($res2);
		if($lin2[ValorParametroSistema] != ''){
			fwrite($file, "\t<pri$lin2[IdParametroSistema]>");
			fwrite($file, "$lin2[ValorParametroSistema]");
			fwrite($file, "</pri$lin2[IdParametroSistema]>");
			fwrite($file, "\n");
		}
	}
	fwrite($file, "</reg>");
	
	fclose($file);
?>
