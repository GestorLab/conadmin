<?
	# Dados de Conexão com o Banco de dados ConAdmin da CNT Sistemas
	$con_CNT[banco]		= "conadmin_desenvolvimento";
	$con_CNT[login]		= "root";
	$con_CNT[senha]		= "";
	$con_CNT[server]	= "localhost";

	if(@fsockopen($con_CNT[server], 3306, $numeroDoErro, $stringDoErro, 3)){
		$conCNT = @mysql_connect($con_CNT[server],$con_CNT[login],$con_CNT[senha]);
		@mysql_select_db($con_CNT[banco],$conCNT);
	}
?>