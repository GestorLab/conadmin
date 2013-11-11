<?
	session_start("ConAdmin_session");

	$localRetornoPrincipal	=	"index.php";
	$localRetornoIndex		=	"../../rotinas/sair.php";
	$bloqueio 				=	true;
	$permissao				=	0;
	
	if($localModulo !=''){
	
		$local_login 	= $_SESSION["Login"];
		$local_IdLoja	= $_SESSION["IdLoja"];
		
		for($i=0;$i<count($array_operacao);$i++){
			$sql	=	"select IdSubOperacao from SubOperacao where IdOperacao = ".$array_operacao[$i];
			$res	=	mysql_query($sql,$con);
			while($lin	=	mysql_fetch_array($res)){
				if(permissaoSubOperacao($localModulo, $array_operacao[$i], $lin[IdSubOperacao]) == true){
					$permissao++;	
				}
			}
		}
		if($permissao == 0){
			header("Location: sem_permissao.php");
		}
	}else{
		if(!isset($_SESSION["Login"]) || !isset($_SESSION["IdLoja"])){
			session_destroy();
			header("Location: $localRetornoIndex");
		}
	}
?>
