<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"select (max(IdFormaAvisoCobranca)+1) IdFormaAvisoCobranca from FormaAvisoCobranca";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdFormaAvisoCobranca]!=NULL){
			$local_IdFormaAvisoCobranca	=	$lin[IdFormaAvisoCobranca];
		}else{
			$local_IdFormaAvisoCobranca	=	1;
		}
		
		if($local_IdGrupoUsuarioMonitor == '')	$local_IdGrupoUsuarioMonitor = 'NULL';
						
		$sql	=	"
				INSERT INTO FormaAvisoCobranca SET 
					IdLoja							= '$local_IdLoja',
					IdFormaAvisoCobranca			= '$local_IdFormaAvisoCobranca',
					DescricaoFormaAvisoCobranca 	= '$local_DescricaoFormaAvisoCobranca',
					ViaEmail						= '$local_ViaEmail',
					ViaImpressa						= '$local_ViaImpressa',
					MarcadorEstrela					= '$local_MarcadorEstrela',
					MarcadorCirculo					= '$local_MarcadorCirculo',
					MarcadorQuadrado				= '$local_MarcadorQuadrado',
					MarcadorPositivo				= '$local_MarcadorPositivo',
					IdGrupoUsuarioMonitor			= $local_IdGrupoUsuarioMonitor,			
					LoginCriacao					= '$local_Login',
					DataCriacao						= concat(curdate(),' ',curtime());";
										
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>
