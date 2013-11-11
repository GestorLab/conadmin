<?
	$EndFile 	= "rotinas/count.php";
	$Vars 		= $_SERVER['argv'];
	$Path		=  substr($Vars[0],0,strlen($Vars[0])-strlen($EndFile));
	
	include($Path.'/files/conecta.php');
	$Qtd = 0;
	$res = mysql_list_tables($con_bd[banco]);
	while ($row = mysql_fetch_row($res)){
		$table = $row[0]; // cada uma das tabelas 
		
		$sql2 = "select count(*) Qtd from $table";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		
		$Qtd += $lin2[Qtd];
	}
	$sql = "update ParametroSistema set ValorParametroSistema='$Qtd' where IdGrupoParametroSistema='115' and IdParametroSistema='1'";
	mysql_query($sql,$con);

	$sql = "update ParametroSistema set ValorParametroSistema=concat(curdate(),' ',curtime()) where IdGrupoParametroSistema='115' and IdParametroSistema='2'";
	mysql_query($sql,$con);

	mysql_close($con);
?>
