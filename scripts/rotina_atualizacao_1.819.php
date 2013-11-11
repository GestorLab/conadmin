<?
	include ("../files/conecta.php");
	include ("../files/funcoes.php");

	$ParametrosBackup = getParametroSistema(83,8);
	if($ParametrosBackup != ''){
		$ParametrosBackup = explode('^',$ParametrosBackup);

		$sql = "update ParametroSistema set ValorParametroSistema='$ParametrosBackup[0]' where IdGrupoParametroSistema='83' and IdParametroSistema='20'";
		mysql_query($sql,$con);

		$sql = "update ParametroSistema set ValorParametroSistema='$ParametrosBackup[1]' where IdGrupoParametroSistema='83' and IdParametroSistema='21'";
		mysql_query($sql,$con);

		$sql = "update ParametroSistema set ValorParametroSistema='$ParametrosBackup[2]' where IdGrupoParametroSistema='83' and IdParametroSistema='22'";
		mysql_query($sql,$con);

		echo "OK";
	}else{
		echo "BACKUP NÃO ESTÁ CONFIGURADO!";
	}
?>