<?
	///// inicializa par�metros da mensagem
	$usuario        = $varsSMS[1];               // Usu�rio Torpedus
	$senha          = $varsSMS[2];               // Senha Torpedus

	$destinatario   = $linSMS[Celular];          // n�mero do destinat�rio (ex SP: 5511XXXXXXX, onde 55 � o c�digo do Brasil)
	$mensagem       = urlencode($linSMS[Conteudo]);         // Mensagem com at� 150 caracteres (139 caso o remetente for preenchido)
	
	$url = "http://torpedus.com.br/sms/index.php?app=webservices&u=$usuario&p=$senha&ta=pv&to=$destinatario&msg=$mensagem";
	file($url);
?>