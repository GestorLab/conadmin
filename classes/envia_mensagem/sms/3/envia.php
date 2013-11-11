<?
	///// inicializa parâmetros da mensagem
	$usuario        = $varsSMS[1];               // Usuário Torpedus
	$senha          = $varsSMS[2];               // Senha Torpedus

	$destinatario   = $linSMS[Celular];          // número do destinatário (ex SP: 5511XXXXXXX, onde 55 é o código do Brasil)
	$mensagem       = urlencode($linSMS[Conteudo]);         // Mensagem com até 150 caracteres (139 caso o remetente for preenchido)
	
	$url = "http://torpedus.com.br/sms/index.php?app=webservices&u=$usuario&p=$senha&ta=pv&to=$destinatario&msg=$mensagem";
	file($url);
?>