<?
	if(getParametroSistema(203,1) == 1){
		$sql = "update ParametroSistema set ValorParametroSistema='' where IdGrupoParametroSistema='203' and IdParametroSistema='1'";
		mysql_query($sql,$con);

		system("/usr/local/etc/rc.d/radiusd stop");
		sleep(2);
		system("/usr/local/etc/rc.d/radiusd start");
	}
?>