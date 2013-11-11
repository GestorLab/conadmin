<?php
	function teste_conexao_api($host,$user,$pass,$debug){

		# Fun�o de teste de conex�o da API

		$API = new routeros_api();

		$API->debug = $debug;

		if($API->connect($host, $user, $pass)){
			if($debug){
			   $API->write('/interface/getall');

			   $READ = $API->read(false);
			   $ARRAY = $API->parse_response($READ);

			   print_r($ARRAY);
			}
			$API->disconnect();
			return true;
		}else{
			return false;
		}
	}
?>