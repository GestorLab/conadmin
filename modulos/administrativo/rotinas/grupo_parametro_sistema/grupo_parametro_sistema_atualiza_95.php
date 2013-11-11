<?
	if($local_IdParametroSistema == 32){
		$sql = "update ParametroSistema set ValorParametroSistema = 8 where ValorParametroSistema < 8 and IdGrupoParametroSistema = 95 and IdParametroSistema = 32";
		mysql_query($sql,$con);
	}
?>