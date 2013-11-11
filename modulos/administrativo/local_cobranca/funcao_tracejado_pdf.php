<?
	global $Background;
	global $Path;

	if($Background == 's'){
		$PatchImagens = $Path."/modulos/administrativo/local_cobranca/";
	}else{
		$PatchImagens = "../";
	}

	$i=11;
	while($i < 200){
		$this->Image($PatchImagens."img/tracejado.jpg",$i,$Posicao,1,0.1,jpg);
		$i += 3;
	}
?>