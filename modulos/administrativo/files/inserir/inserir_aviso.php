<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"select (max(IdAviso)+1) IdAviso from Aviso";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdAviso]!=NULL){
			$local_IdAviso	=	$lin[IdAviso];
		}else{
			$local_IdAviso	=	1;
		}
		
		if($local_IdGrupoPessoa == '')	$local_IdGrupoPessoa = 'NULL';
		if($local_IdPessoa == '')		$local_IdPessoa 	 = 'NULL';		
		if($local_IdServico == '')		$local_IdServico 	 = 'NULL';
		if($local_IdAvisoForma == '')	$local_IdAvisoForma 	 = 'NULL';
		if($local_IdGrupoUsuario == '')	$local_IdGrupoUsuario 	 = 'NULL';
		if($local_Data == ''){
			$local_DataExpiracao		 = 'NULL';
		}else{			
			$local_DataExpiracao	=	dataConv($local_Data,'d/m/Y','Y-m-d')." ".$local_Hora;
			$local_DataExpiracao = "'".$local_DataExpiracao."'";
		}		
		
		$sql	=	"
					INSERT INTO Aviso SET 
						IdLoja					= '$local_IdLoja',
						IdAviso					= '$local_IdAviso',
						DataExpiracao			= $local_DataExpiracao,
						TituloAviso				= '$local_TituloAviso',
						ResumoAviso				= '$local_ResumoAviso',
						Aviso					= '$local_Aviso',
						IdAvisoForma			=  $local_IdAvisoForma,
						IdGrupoPessoa			=  $local_IdGrupoPessoa,
						IdPessoa				=  $local_IdPessoa,					
						IdServico				=  $local_IdServico,
						ParametroContrato		= '$local_ParametroContrato',
						IdGrupoUsuario			= $local_IdGrupoUsuario,
						Usuario					= '$local_Usuario',
						LoginCriacao			= '$local_Login',
						DataCriacao				= concat(curdate(),' ',curtime());";
					
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserчуo Negativa
		}
	}
?>