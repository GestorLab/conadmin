<?
	$file = @file("http://www.cntsistemas.com.br/aplicacoes/conadmin/news/news_conadmin.php");

	for($i=0; $i<=count($file); $i++){
		echo $file[$i];
	}
?>