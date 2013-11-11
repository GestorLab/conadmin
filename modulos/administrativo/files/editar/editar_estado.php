<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE Estado SET
							NomeEstado		= '$local_Estado',
							SiglaEstado		= '$local_SiglaEstado'
						WHERE 
							IdPais			= $local_IdPais and
							IdEstado		= $local_IdEstado";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
