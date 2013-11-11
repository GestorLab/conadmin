<?
	@mkdir(getParametroSistema(6,1)."atualizacao");
	$sql = "update LogAcesso set Fechada = 1 where Fechada = 2;";
	mysql_query($sql,$con);
?>