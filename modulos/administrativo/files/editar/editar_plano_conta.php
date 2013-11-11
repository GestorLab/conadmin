<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{

		if($local_IdAcessoRapido == ""){
			$local_IdAcessoRapido = "NULL";
		}else{
			$local_IdAcessoRapido = "'$local_IdAcessoRapido'";
		}
		
		$sql	=	"UPDATE PlanoConta SET DescricaoPlanoConta	='$local_DescricaoPlanoConta', IdAcessoRapido = $local_IdAcessoRapido, LoginAlteracao =	'$local_Login', DataAlteracao = (concat(curdate(),' ',curtime())) WHERE IdLoja=$local_IdLoja AND IdPlanoConta='$local_IdPlanoConta';";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 57;
		}
	}
?>
