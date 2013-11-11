<?
	$file = @file("http://www.cntsistemas.com.br/aplicacoes/conadmin/news/news_conadmin_anexo.php");

	for($i=0; $i<=count($file); $i++){
		echo $file[$i];
	}
?>