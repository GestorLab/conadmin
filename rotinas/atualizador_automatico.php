<?
	$PathAtualizacao = "rotinas/rotina_atualizacao.php";
	$PathAtualizacaoLog = $Path."rotinas/rotina_atualizacao.log";

	$ExecAtualizador = true;

	system("rm ".$Path."/rotinas/rotina_atualizacao_execucao.log");
	system("ps -ax > ".$Path."/rotinas/rotina_atualizacao_execucao.log");

	$lin = file($Path."/rotinas/rotina_atualizacao_execucao.log");

	for($i=0; $i<count($lin); $i++){
		$lin2 = explode(" ",$lin[$i]);
		for($ii=0; $ii<count($lin2); $ii++){
			if(trim($lin2[$ii]) == $PathAtualizacao){
				$ExecAtualizador = false;
				break;
				break;
			}
		}
	}
	if($ExecAtualizador == true){
		system("cd $Path && $PathPHP $PathAtualizacao > $PathAtualizacaoLog &");
	}
?>