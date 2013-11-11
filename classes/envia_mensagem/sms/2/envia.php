<?
////////////////////////////////////////////////////////////////////////////////
//// Este exemplo foi testado com sucesso em ambientes:
////  - Windows, IIS 5.1, PHP 4.4.2
////  - Linux Fedora Core 1, Apache 2.0.47-10, PHP 4.3.3
//// 
//// Em ambiente Linux, provavelmente n�o ser� necess�rio instalar nada adicio-
//// nalmente ao pacote do PHP. Em Windows, por�m, deve-se certificar que a DLL
//// php_curl.dll esteja instalada na pasta de extensions do PHP e que a linha
//// extension=php_curl.dll esteja devidamente descomentada no arquivo php.ini,
//// localizado, normalmente, na pasta C:\Windows.
//// 
//// Al�m disso, em ambiente Windows, � necess�rio instalar a OpenSSL, que pode
//// ser baixada do endere�o abaixo:
////
//// http://www.openssl.org/related/binaries.html
////
//// OBS: A EXTENS�O DESTA P�GINA DEVE SER TROCADA PARA .php
////
//// A chamada para envio de mensagens foi implementada com base na especifica-
//// ��o CGI2SMS de acesso ao CGI Comunika dispon�vel para download no painel de
//// controle do nosso site http://www.comunika.com.br
////////////////////////////////////////////////////////////////////////////////


///// inicializa biblioteca CURL
$ch = curl_init();

///// inicializa par�metros da mensagem
$usuario        = $varsSMS[1];               // Usu�rio Comunika
$senha          = $varsSMS[2];               // Senha Comunika

#$remetente      = "remetente";              // Remetente com at� 10 d�gitos (ser� concatenado no �nicio da mensagem)
$destinatario   = $linSMS[Celular];          // n�mero do destinat�rio (ex SP: 5511XXXXXXX, onde 55 � o c�digo do Brasil)
#$agendamento    = "AAAA-MM-DD hh:mm:ss";    // Vazio para envio imediato ou uma data no formato AAAA-MM-DD hh:mm:ss
$mensagem       = $linSMS[Conteudo];         // Mensagem com at� 150 caracteres (139 caso o remetente for preenchido)
$identificador  = $IdHistoricoMensagem;     // C�digo que identifique a mensagem no sistema do usu�rio (ex: chave prim�ria do banco de dados)
$modoTeste      = 0;                         // 0 = modo normal: envia a mensagem, 1 = modo teste: n�o envia e a mensagem n�o aparece no painel de controle

///// monta o conte�do do par�metro "messages" (n�o alterar)
$codedMsg       = $remetente."\t".$destinatario."\t".$agendamento."\t".$mensagem."\t".$identificador;


///// configura par�metros de conex�o (n�o alterar)
$path           = "/3.0/user_message_send.php";
$parametros     = $path."?testmode=".$modoTeste."&linesep=0&user=".urlencode($usuario)."&pass=".urlencode($senha)."&messages=".urlencode($codedMsg);
$url            = "https://cgi2sms.com.br".$parametros;


///// realiza a conex�o
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec ($ch);
$result = ($result == ""?$result="1":$result);

curl_close ($ch); 


///// verifica o resultado
$error      = explode("\n",urldecode($result));
$error[0]   = (int)trim($error[0]);

if($error[0] != 0){
    ///// para o caso de um erro gen�rico
    $errorCode  = $error[0];
} else {
    ///// para o caso de erro espec�fico
    $errorPhone	= explode(" ",urldecode($error[1]));
    $errorCode  = $errorPhone[0];
}


///// este c�digo � apenas informativo, devendo ser trocado pelo tratamento desejado � resposta
///// os c�digos de erro, exceto os c�digos "1" e "404" podem ser encontrados no manual do CGI2SMS
///// para download no painel de controle Comunika
switch($errorCode) {
    case 0   : $msg = "Mensagem enviada com sucesso"; break;
    case 1   : $msg = "Problemas de conex�o"; break;
    case 10  : $msg = "Username e/ou Senha inv�lido(s)"; break;
    case 11  : $msg = "Par�metro(s) inv�lido(s) ou faltando"; break;
    case 12  : $msg = "N�mero de telefone inv�lido ou n�o coberto pelo Comunika"; break;
    case 13  : $msg = "Operadora desativada para envio de mensagens"; break;
    case 14  : $msg = "Usu�rio n�o pode enviar mensagens para esta operadora"; break;
    case 15  : $msg = "Cr�ditos insuficientes";	break;
    case 16  : $msg = "Tempo m�nimo entre duas requisi��es em andamento"; break;
    case 17  : $msg = "Permiss�o negada para a utiliza��o do CGI/Produtos Comunika"; break;
    case 18  : $msg = "Operadora Offline"; break;
    case 19  : $msg = "IP de origem negado"; break;
    case 404 : $msg = "P�gina n�o encontrada"; break;
}

#echo($errorCode." : ".$msg);
?>