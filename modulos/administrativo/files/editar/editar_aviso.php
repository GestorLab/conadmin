<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		if($local_IdGrupoPessoa == '')	$local_IdGrupoPessoa = 'NULL';
		if($local_IdPessoa == '')		$local_IdPessoa 	 = 'NULL';		
		if($local_IdServico == '')		$local_IdServico 	 = 'NULL';
		if($local_IdAvisoForma == '') $local_IdAvisoForma 	 = 'NULL';
		if($local_IdGrupoUsuario == '')	$local_IdGrupoUsuario= 'NULL';
		if($local_Data == ''){
			$local_DataExpiracao		 = 'NULL';
		}else{			
			$local_DataExpiracao	=	dataConv($local_Data,'d/m/Y','Y-m-d')." ".$local_Hora;
			$local_DataExpiracao	= "'".$local_DataExpiracao."'";
		}		
		
		$sql	=	"UPDATE Aviso SET							
							DataExpiracao		= $local_DataExpiracao,
							TituloAviso			= '$local_TituloAviso',
							ResumoAviso			= '$local_ResumoAviso',
							Aviso				= '$local_Aviso',
							IdAvisoForma		=  $local_IdAvisoForma,
							IdGrupoPessoa		=  $local_IdGrupoPessoa,
							IdPessoa			=  $local_IdPessoa,						
							IdServico			=  $local_IdServico,
							ParametroContrato	= '$local_ParametroContrato',
							IdGrupoUsuario		= $local_IdGrupoUsuario,
							Usuario				= '$local_Usuario',
							LoginAlteracao		= '$local_Login',
							DataAlteracao		= concat(curdate(),' ',curtime())
					 WHERE 
							IdAviso				= $local_IdAviso and
							IdLoja				= $local_IdLoja";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>