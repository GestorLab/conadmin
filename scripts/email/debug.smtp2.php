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
	
	$mail->Priority		= 3;//Prioridade
	$mail->Encoding		= '8bit';//Codificação
	$mail->CharSet		= 'iso-8859-1';//Tipo de codificação
	$mail->WordWrap		= 0;
	$mail->Helo			= 'smtp.gmail.com';
	$mail->PluginDir	= $INCLUDE_DIR;
	$mail->Mailer		= 'smtp';
	$mail->TimeOut		= 10;	
	$mail->From			= "automatico@cntsistemas.com.br";
	$mail->FromName 	= "CNTSistemas";
	$mail->Subject		= "Teste Conta SMTP SSL(CNTSistemas)"; 
	$mail->Host			= 'smtp.gmail.com';
	$mail->Port			= 465;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure 	= "ssl";
	#$mail->SMTPSecure 	= "tls";
	$mail->Username 	= "automatico@cntsistemas.com.br";//Usuario
	$mail->Password 	= "35483K77";//Senha
	$mail->Sender		= "automatico@cntsistemas.com.br";
	$mail->Body			= "<p>Enviou perfeitamente</p>";
	$mail->AddAddress("leonardo@cntsistemas.com.br");
	
	$mail->IsHTML(true); 
	if(!$mail->Send()) {
		echo "Erro: " . utf8_decode($mail->ErrorInfo);
	} else {
		echo "Email enviado com sucesso!";
	}
?>