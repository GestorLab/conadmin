<?
	# Dados de Conexão com o Banco de dados Principal
	$con_bd['banco']	=	"conadmin_desenvolvimento";
	$con_bd['login']	=	"root";
	$con_bd[senha]	=	"";
	$con_bd[server]	=	"localhost";

	$con	=	mysql_connect($con_bd[server],$con_bd[login],$con_bd[senha]);
	mysql_select_db($con_bd[banco],$con);
?>