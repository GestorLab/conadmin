<?
	include("../../files/funcoes.php");

	if(file_exists($_GET[Url])){
		switch(endArray(explode(".",$_GET[Url]))){
			case "css":
				break;
			default:
				header('Content-Type: image/jpeg');
				break;
		}
		include($_GET[Url]);
	}else{
		echo "ARQUIVO NÃO ENCONTRADO";
	}
?>