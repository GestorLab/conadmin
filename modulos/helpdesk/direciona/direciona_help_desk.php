<?
	include ('../../../files/conecta.php');
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');

	$Ticket = $_GET['Ticket'];
	$local_Url = "?url=";
	
	if(array_key_exists('Direciona', $_GET)){
		$local_Url .= $_GET['Direciona'];
	} else{
		$local_Url .= "cadastro_help_desk.php?Ticket=$Ticket";
	}
	
	include ('../rotinas/autentica.php');
?>
