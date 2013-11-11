<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{	
		$sql	=	"UPDATE ParametroSistema SET
							DescricaoParametroSistema	= '$local_DescricaoParametroSistema', 
							ValorParametroSistema		='$local_ValorParametroSistema',
							LoginAlteracao				='$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdGrupoParametroSistema		= '$local_IdGrupoParametroSistema' and
							IdParametroSistema			= '$local_IdParametroSistema'";
		if(mysql_query($sql,$con) == true){

			$sql	=	"select UrlRotinaAlteracao from GrupoParametroSistema where IdGrupoParametroSistema=$local_IdGrupoParametroSistema";
			$res	=	mysql_query($sql,$con);
			$lin	=	mysql_fetch_array($res);
		
			if($lin[UrlRotinaAlteracao]!=''){
				include($lin[UrlRotinaAlteracao]);
			}

			$local_Erro = 4;

		}else{
			$local_Erro = 5;
		}
	}
?>