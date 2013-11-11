<?
	# Dados de Conexão com o Banco de dados ConAdmin
	$con_bdConAdmin[banco]	= "conadmin_desenvolvimento";
	$con_bdConAdmin[login]	= "root";
	$con_bdConAdmin[senha]	= "";
	$con_bdConAdmin[server]	= "localhost";

	if(@fsockopen($con_bdConAdmin[server], 3306, $numeroDoErro, $stringDoErro, 3)){
		$conConAdmin = @mysql_connect($con_bdConAdmin[server],$con_bdConAdmin[login],$con_bdConAdmin[senha]);
		@mysql_select_db($con_bdConAdmin[banco],$conConAdmin);
	}
?>