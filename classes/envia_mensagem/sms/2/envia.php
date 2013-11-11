<?
////////////////////////////////////////////////////////////////////////////////
//// Este exemplo foi testado com sucesso em ambientes:
////  - Windows, IIS 5.1, PHP 4.4.2
////  - Linux Fedora Core 1, Apache 2.0.47-10, PHP 4.3.3
//// 
//// Em ambiente Linux, provavelmente não será necessário instalar nada adicio-
//// nalmente ao pacote do PHP. Em Windows, porém, deve-se certificar que a DLL
//// php_curl.dll esteja instalada na pasta de extensions do PHP e que a linha
//// extension=php_curl.dll esteja devidamente descomentada no arquivo php.ini,
//// localizado, normalmente, na pasta C:\Windows.
//// 
//// Além disso, em ambiente Windows, é necessário instalar a OpenSSL, que pode
//// ser baixada do endereço abaixo:
////
//// http://www.openssl.org/related/binaries.html
////
//// OBS: A EXTENSÃO DESTA PÁGINA DEVE SER TROCADA PARA .php
////
//// A chamada para envio de mensagens foi implementada com base na especifica-
//// ção CGI2SMS de acesso ao CGI Comunika disponível para download no painel de
//// controle do nosso site http://www.comunika.com.br
////////////////////////////////////////////////////////////////////////////////


///// inicializa biblioteca CURL
$ch = curl_init();

///// inicializa parâmetros da mensagem
$usuario        = $varsSMS[1];               // Usuário Comunika
$senha          = $varsSMS[2];               // Senha Comunika

#$remetente      = "remetente";              // Remetente com até 10 dígitos (será concatenado no ínicio da mensagem)
$destinatario   = $linSMS[Celular];          // número do destinatário (ex SP: 5511XXXXXXX, onde 55 é o código do Brasil)
#$agendamento    = "AAAA-MM-DD hh:mm:ss";    // Vazio para envio imediato ou uma data no formato AAAA-MM-DD hh:mm:ss
$mensagem       = $linSMS[Conteudo];         // Mensagem com até 150 caracteres (139 caso o remetente for preenchido)
$identificador  = $IdHistoricoMensagem;     // Código que identifique a mensagem no sistema do usuário (ex: chave primária do banco de dados)
$modoTeste      = 0;                         // 0 = modo normal: envia a mensagem, 1 = modo teste: não envia e a mensagem não aparece no painel de controle

///// monta o conteúdo do parâmetro "messages" (não alterar)
$codedMsg       = $remetente."\t".$destinatario."\t".$agendamento."\t".$mensagem."\t".$identificador;


///// configura parâmetros de conexão (não alterar)
$path           = "/3.0/user_message_send.php";
$parametros     = $path."?testmode=".$modoTeste."&linesep=0&user=".urlencode($usuario)."&pass=".urlencode($senha)."&messages=".urlencode($codedMsg);
$url            = "https://cgi2sms.com.br".$parametros;


///// realiza a conexão
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
    ///// para o caso de um erro genérico
    $errorCode  = $error[0];
} else {
    ///// para o caso de erro específico
    $errorPhone	= explode(" ",urldecode($error[1]));
    $errorCode  = $errorPhone[0];
}


///// este código é apenas informativo, devendo ser trocado pelo tratamento desejado à resposta
///// os códigos de erro, exceto os códigos "1" e "404" podem ser encontrados no manual do CGI2SMS
///// para download no painel de controle Comunika
switch($errorCode) {
    case 0   : $msg = "Mensagem enviada com sucesso"; break;
    case 1   : $msg = "Problemas de conexão"; break;
    case 10  : $msg = "Username e/ou Senha inválido(s)"; break;
    case 11  : $msg = "Parâmetro(s) inválido(s) ou faltando"; break;
    case 12  : $msg = "Número de telefone inválido ou não coberto pelo Comunika"; break;
    case 13  : $msg = "Operadora desativada para envio de mensagens"; break;
    case 14  : $msg = "Usuário não pode enviar mensagens para esta operadora"; break;
    case 15  : $msg = "Créditos insuficientes";	break;
    case 16  : $msg = "Tempo mínimo entre duas requisições em andamento"; break;
    case 17  : $msg = "Permissão negada para a utilização do CGI/Produtos Comunika"; break;
    case 18  : $msg = "Operadora Offline"; break;
    case 19  : $msg = "IP de origem negado"; break;
    case 404 : $msg = "Página não encontrada"; break;
}

#echo($errorCode." : ".$msg);
?>