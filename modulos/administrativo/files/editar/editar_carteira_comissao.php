<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$local_Percentual		=	str_replace(".", "", $local_Percentual);	
		$local_Percentual		= 	str_replace(",", ".", $local_Percentual);
		
		$sql	=	"UPDATE ComissionamentoCarteira SET 
							Percentual				='$local_Percentual',
							LoginAlteracao			='$local_Login',
							DataAlteracao			= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					= $local_IdLoja and
							IdAgenteAutorizado		= '$local_IdAgenteAutorizado' and
							IdCarteira				= '$local_IdCarteira' and
							IdServico				= '$local_IdServico' and
							Parcela					= '$local_Parcela'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
