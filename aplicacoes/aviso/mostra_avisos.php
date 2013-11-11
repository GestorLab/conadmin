<?
	if($Aviso[geral] == false){
		header("Location: $UrlRedirecionamento");
	}else{
		$AvisoPos = array_keys($Aviso);

		for($i=0; $i<count($AvisoPos); $i++){

			$Mensagem = "";

			if($Aviso[$AvisoPos[$i]] == true){

				$Mensagem = $Msg[$AvisoPos[$i]];
				
				include("layout/$LayoutAvisos/modelo.php");
		
				$Conteudo .= "\n".$Modelo;
			}
		}

		include("layout/$LayoutAvisos/layout.php");
	}
?>