<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		if($local_IdGrupoPessoa == '')	$local_IdGrupoPessoa = 'NULL';		
				
		$local_DataExpiracao	=	dataConv($local_Data,'d/m/Y','Y-m-d')." ".$local_Hora;
		
		$sql	=	"UPDATE FormaAvisoCobranca SET
							IdLoja							= $local_IdLoja,
							DescricaoFormaAvisoCobranca 	= '$local_DescricaoFormaAvisoCobranca',	
							ViaEmail						= '$local_ViaEmail',
							ViaImpressa						= '$local_ViaImpressa',
							MarcadorEstrela					= '$local_MarcadorEstrela',
							MarcadorCirculo					= '$local_MarcadorCirculo',
							MarcadorQuadrado				= '$local_MarcadorQuadrado',
							MarcadorPositivo				= '$local_MarcadorPositivo',
							IdGrupoUsuarioMonitor			= $local_IdGrupoUsuarioMonitor,		
							LoginAlteracao					= '$local_Login',
							DataAlteracao					= concat(curdate(),' ',curtime())
					 WHERE 
							IdFormaAvisoCobranca			= '$local_IdFormaAvisoCobranca'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
