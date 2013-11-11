<?
	if($Aviso3 == 1){

		$nAviso		= 0;
		$AvisoCod	= '0';

		$Avisos = avisos(2,$IdPessoa);
		if($Avisos){
			include("avisos_conteudo.php");
		}
	}
?>