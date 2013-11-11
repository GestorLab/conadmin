<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE Cidade SET
							NomeCidade		= '$local_Cidade'
						WHERE 
							IdPais			= $local_IdPais and
							IdEstado		= $local_IdEstado and
							IdCidade		= $local_IdCidade";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
