<?
	set_time_limit(0);
	ini_set("memory_limit","512M");

	# Dados de Conexão
	$con_bd[banco]	=	"conadmin";		
	$con_bd[login]	=	"douglas";
	$con_bd[senha]	=	"siemens";
	$con_bd[server]	=	"www.pulo.com.br";

	$con	=	mysql_connect($con_bd[server],$con_bd[login],$con_bd[senha]);
	mysql_select_db($con_bd[banco],$con);

	$cep = file('Cep.sql');

	$total = count($cep);

	for($i=0; $i<$total; $i++){
		if(trim($cep[$i]) != ''){
			$sql = $cep[$i];
			mysql_query($sql,$con);
		}		
	}
?>