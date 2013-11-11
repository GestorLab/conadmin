<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE TipoEmail SET 
							DescricaoTipoEmail		='$local_DescricaoTipoEmail',
							AssuntoEmail			='$local_AssuntoEmail',
							DiasParaEnvio			='$local_DiasParaEnvio',
							EstruturaEmail			='$local_EstruturaEmail'
						WHERE 
							IdLoja					= $local_IdLoja and
							IdTipoEmail				= '$local_IdTipoEmail'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
