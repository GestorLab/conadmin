<?php
// +----------------------------------------------------------------------+
// | WSBillingStatus v1.0                                                       |
// +----------------------------------------------------------------------+
// | Você precisa da classe phpXML para usar este script. Faça o download |
// | em http://www.phpclasses.org/browse.html/package/180.html.           |
// +----------------------------------------------------------------------+
// | Use este script por sua própria conta e risco!                       |
// +----------------------------------------------------------------------+
// | Autores:                                                             |
// |   Kleber Fernando Vieira <kvieira@f2b.com.br>                   |
// +----------------------------------------------------------------------+

error_reporting (E_ALL ^ E_NOTICE);
include_once ("XML.php");

set_time_limit(0); 

class WSBillingStatus extends XML
{
	var $host = "www.f2b.com.br";
	var $porta = 80;
	var $url = "/WSBillingStatus";
	var $timeout = 30;
	var $encoding = "ISO-8859-1";
	var $version = "1.0";
	var $resposta = "";

    function WSBillingStatus ( $string = "")
    {
		// Verifica se $string não está vazia.
        if (strlen($string) > 0)
        {
			// Cria um parser XML.
            $parser = xml_parser_create();
                
            // "Seta" as opções para o parser XML
            xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
                
            // "Seta" o objeto para o parser
            xml_set_object($parser, $this);
               
            // "Seta" os elementos controladores para o parser
            xml_set_element_handler($parser, "handle_start_element", "handle_end_element");
            xml_set_character_data_handler($parser,"handle_character_data");
                
            // Valida o XML.
            if ( !xml_parse($parser, $string, true) )
            {
				// Exibe uma mensagem de erro, se existir
                $this->display_error("(WSBilling) Erro de XML na linha %d: %s <pre>%s</pre>",
                	xml_get_current_line_number($parser),
                    xml_error_string(xml_get_error_code($parser)),
					htmlspecialchars($string));
			}
			// Libera o parser.
			xml_parser_free($parser);
		}
    }
    function getXML ( $encoding="ISO-8859-1", $version = "1.0", $root = "", $level = 0 )
    {
        // Cria uma string para salvar o XML gerado.
        $xml = "";
        
        // Cria uma string para exibir antes das tags, como tabulação.
        $before = "";
        
        // Calcula a quantidade de espaços em brancos da tabulação.
        for ( $i = 0; $i < ( $level * 2 ); $i++ )
        {
            // Adiciona um espaço em branco.
            $before .= " ";
        }
        
        // Verifica se a raiz do XML foi informado.
        if ( empty($root) )
        {
            // "Seta" a raiz do XML.
            $root = $this->root;
        }
        
        // Agora adiciona os espaços em branco para o XML.
        $xml .= $before;
        
        // Abre a tag.
        $xml .= "<".$this->nodes[$root]["name"];
        
        // Verifica se há atributos para o nó.
        if ( count($this->nodes[$root]["attributes"]) > 0 )
        {
            // Varre todos os atributos.
            foreach ( $this->nodes[$root]["attributes"] as $key => $value )
            {
                
                // Adiciona os atributos para o XML.
                $xml .= " ".$key."=\"".trim(stripslashes($value)). "\"";
                
            }
        }
        
        // Verifica se o nó contém texto ou um nó filho.
        if ( empty($this->nodes[$root]["text"]) &&
            !isset($this->nodes[$root]["children"]) )
        {
            // Se não tem, adiciona o final da tag.
            $xml .= "/";
        }
        
        // Fecha a tag.
        $xml .= ">\n";
        
        // Verifique se o nó contém texto.
        if ( !empty($this->nodes[$root]["text"]) )
        {
            // Adiciona o texto para o XML.
            $xml .= $before."  ".$this->nodes[$root]["text"]."\n";
        }
        
        // Verifica se o nó tem nós filhos.
        if ( isset($this->nodes[$root]["children"]) )
        {
            // Varre todos os nós filhos com nomes diferentes.
            foreach ( $this->nodes[$root]["children"] as $child => $pos )
            {
                // Varre todos os nós filhos com nome igual.
                for ( $i = 1; $i <= $pos; $i++ )
                {
                    // Gera o caminho absoluto para o nó.
                    $fullchild = $root."/".$child."[".$i."]";
                    
                    // Adiciona o nó XML ao documento XML existente.
                    $xml .= $this->getXML($encoding,$version,$fullchild,$level + 1);
                }
            }
        }
        
        // Verifica se há atributos para o nó.
        if ( !empty($this->nodes[$root]["text"]) ||
            isset($this->nodes[$root]["children"]) )
        {
            // Adiciona espaços em branco ao XML, para a tabulação.
            $xml .= $before;
            
            // Adiciona a tag de fechamento.
            $xml .= "</".$this->nodes[$root]["name"].">";
            
            // Adiciona uma linha em branco.
            $xml .= "\n";
        }
		// Se for o elemento raiz, adiciona o cabeçalho XML
        if($level == 0){
			if($version != $this->version) $this->version = $version;
			if($encoding != $this->encoding) $this->encoding = $encoding;
			$xml = "<"."?xml version=\"$version\" encoding=\"$encoding\" ?".">\n".$xml;
		}
        // retorna o conteúdo XML.
        return $xml;
    }

	function connect($host = "", $porta = "", $timeout = "")
	{
		if(!$host) $host = $this->host;
		else $this->host = $host;

		if(!$porta) $porta = $this->porta;
		else $this->porta = $porta;

		if(!$timeout) $timeout = $this->timeout;
		else $this->timeout = $timeout;

		$fp = @fsockopen($host, $porta, $errno, $errstr, $timeout);
		if (!$fp) {
			$this->display_error("(WSBilling) Não foi possível conectar à %s:%d. $errstr ($errno)",$host,$porta);
		} else {
			return $fp;
		}
	}

	function send($data, $host = "", $url = "")
	{
		if(!$host) $host = $this->host;
		if(!$url) $url = $this->url;
		else $this->url = $url;
		
		if(strlen($data) > 0){
			$sendData = "POST $this->url HTTP/1.1\r\n"
			          . "Host: $this->host\r\n"
					  . "User-Agent: PHP_WSBilling\r\n"
					  . "Content-Type: text/xml; charset=\"$this->encoding\"\r\n"
					  . "Content-Length: ".strlen($data)."\r\n\r\n"
					  . $data;		

			if (function_exists('curl_init')) {
				$ch = curl_init('http://'.$host . $url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST , $sendData);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				$resposta = curl_exec($ch);
				curl_close ($ch);

				if ($resposta) {
					$this->resposta = $resposta;
				} else {
					$this->display_error("(WSBilling) Não obteve dados de resposta de %s%s.",$host,$url);
				}					
			} else {
				$fp = $this->connect();
				if(!fputs($fp, $sendData, strlen($sendData))) {
					$this->display_error("(WSBilling) Erro ao enviar dados para %s%s.",$host,$url);
				} else {
					for($resposta = ""; !feof($fp);){
						$linha = fread($fp,1024);
						$resposta .= $linha;
					}
					if($resposta){
						$xmlPos = strpos($resposta,'<?xml');
						if($xmlPos === false){
							$this->display_error("A resposta obtida não é XML.");
						} else {
							$resposta = substr($resposta,$xmlPos,strlen($resposta));
							$resposta = substr($resposta,0,strrpos($resposta,'>')+1);
							$this->resposta = $resposta;
							fclose($fp);
						}
					} else {
						$this->display_error("(WSBilling) Não obteve dados de resposta de %s%s.",$host,$url);
					}
				}
			} 
		} else {
			$this->display_error("(WSBilling) Sem dados para enviar.");
		}
	}

	function pegaTotal()
	{
		$xPathTotal = $this->evaluate("//total");
		$i=0;
		if($xPathTotal){
			foreach($xPathTotal as $pathTotal){
				$total[$i] = $this->get_attributes($pathTotal);
				$i++;
			}
			return $total;
		}
	}

	function pegaCobranca()
	{
		$xPathCobranca = $this->evaluate("//cobranca");
		if($xPathCobranca){
			$i=0;
			foreach($xPathCobranca as $pathCobranca){
				$cobranca[$i] = $this->get_attributes($pathCobranca);
				$cobranca[$i]["nome"] = $this->get_content($pathCobranca . "/nome[1]");
				$cobranca[$i]["email1"] = $this->get_content($pathCobranca . "/email[1]");
				$cobranca[$i]["email2"] = $this->get_content($pathCobranca . "/email[2]");
				$cobranca[$i]["url"] = $this->get_content($pathCobranca . "/url[1]");
				$cobranca[$i]["grupo"] = $this->get_content($pathCobranca . "/grupo[1]");
				$cobranca[$i]["codigo"] = $this->get_content($pathCobranca . "/codigo[1]");
				$i++;
			}
			return $cobranca;
		}
	}
	
	function pegaCliente()
	{
		$xPathCliente = $this->evaluate("//cliente");
		$i=0;
		if($xPathCliente){
			foreach($xPathCliente as $pathCliente){
				$cliente[$i] = $this->get_attributes($pathCliente);
				$i++;
			}
			return $cliente;
		}
	}

	function pegaLog()
	{
		$xPathLog = $this->evaluate("//log");
		if($xPathLog){
			foreach($xPathLog as $pathLog){
				$log["texto"] = $this->get_content($pathLog);
			}
			return $log;
		} else {
			$this->display_error("(WSBilling) Não foi possível encontrar o elemento <b>&lt;log&gt;</b>");
		}
	}
}
?>
