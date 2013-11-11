<?
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	
	$file = file(getParametroSistema(6,3).'/modulos/cda/form.php');

	echo "<textarea style='width: 100%;' rows=30>";
	for($i=0; $i<count($file); $i++){
		echo $file[$i]."\n";
	}	
	echo "</textarea>";
?>