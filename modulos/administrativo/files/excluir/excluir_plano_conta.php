<?
	$localModulo		=	1;
	$localOperacao		=	21;
	
	$local_IdPlanoConta		= 	$_GET['IdPlanoConta'];
	$local_IdLoja			=	$_SESSION["IdLoja"];
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
		
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{	
		$sql	=	"select count(*) Qtd from PlanoConta where IdLoja = $local_IdLoja and IdPlanoConta like '$local_IdPlanoConta.%'";
		$res	=	mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		if($lin[Qtd] == 0){
		
			$sql	=	"DELETE FROM PlanoConta WHERE IdLoja = $local_IdLoja and IdPlanoConta = '$local_IdPlanoConta'";
			if(mysql_query($sql,$con) == true){
			
				$local_IdPlanoContaArray	= explode(".",$local_IdPlanoConta);
				$QtdNiveisPlano				= count($local_IdPlanoContaArray);
				$QtdNiveisPlanoConta 		= getParametroSistema(5,2);
			
				$local_IdPlanoContaPai = "";
				
				for($i=0;$i<$QtdNiveisPlano-1;$i++){
					$local_IdPlanoContaPai .= $local_IdPlanoContaArray[$i];	
					if($i+1<$QtdNiveisPlano-1){
						$local_IdPlanoContaPai .= ".";
					}
				}
			
				$sql	=	"select count(*) Qtd from PlanoConta where IdLoja = $local_IdLoja and IdPlanoConta like '$local_IdPlanoContaPai.%'";
				$res	=	mysql_query($sql,$con);
				$lin 	= 	mysql_fetch_array($res);
				
				if($lin[Qtd] == 0){
					$sql = "UPDATE PlanoConta SET TipoPlanoConta=1 WHERE IdLoja=$local_IdLoja AND IdPlanoConta='$local_IdPlanoContaPai';";
					mysql_query($sql,$con);
				}
	
				echo $local_Erro = 7;
			}else{
				echo $local_Erro = 33;
			}
		}else{
			echo $local_Erro = 33;
		}
	}
?>
