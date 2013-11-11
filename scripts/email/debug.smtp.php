<?php
	require('../../classes/phpmailer/class.phpmailer.php');
	
	/*$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host     = "smtp.gmail.com";  // Endereço do servidor SMTP
	$mail->SMTPAuth = true; // Requer autenticação?
	$mail->Port  = 465; // Porta do SMTP
	$mail->SMTPSecure = "ssl"; // Tipo de comunicação segura
	$mail->Username = "felipe@cntsistemas.com.br"; // Usuário SMTP
	$mail->Password = "suportecnt"; // Senha do usuário SMTP

	$mail->From     = "felipe@cntsistemas.com.br"; // E-mail do remetente
	$mail->FromName = "CNT Sistemas"; // Nome do remetente
	$mail->AddAddress("leonardo@cntsistemas.com.br"); // E-mail do destinatário

	$mail->IsHTML(true);
	$mail->Subject = "Teste Conta SMTP SSL";
	$mail->Body    = "<p>Enviou perfeitamente</p>";
*/
	$mail = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	
	$mail->Priority		= 3;
	$mail->Encoding		= '8bit';
	$mail->CharSet		= 'iso-8859-1';
	$mail->WordWrap		= 0;
	$mail->Helo			= '';
	$mail->PluginDir	= $INCLUDE_DIR;
	$mail->Mailer		= 'smtp';
	$mail->TimeOut		= 10;	
	$mail->From			= "conadmin@akto.com.br";
	$mail->FromName 	= "Net HOME";
	$mail->Subject		= "Teste Conta SMTP SSL(Desenvolvimento)"; 
	$mail->Host			= 'smtp.akto.com.br';
	$mail->Port			= 587;
	$mail->SMTPAuth = true;
	#$mail->SMTPSecure 	= "ssl";
	$mail->SMTPSecure 	= "tls";
	$mail->Username 	= "conadmin@akto.com.br";
	$mail->Password 	= "sistema";
	$mail->Sender		= "conadmin@akto.com.br";
	$mail->Body			= "<p>Enviou perfeitamente</p>";
	$mail->AddAddress("leonardo@cntsistemas.com.br");
	
	$mail->IsHTML(true); 
	if(!$mail->Send()) {
		echo "Erro: " . utf8_decode($mail->ErrorInfo);
	} else {
		echo "Email enviado com sucesso!";
	}
?>