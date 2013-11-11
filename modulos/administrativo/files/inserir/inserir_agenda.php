<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"select (max(IdAgenda)+1) IdAgenda from Agenda";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdAgenda]!=NULL){
			$local_IdAgenda	=	$lin[IdAgenda];
		}else{
			$local_IdAgenda	=	1;
		}
		
		if($local_IdPessoa == '')	$local_IdPessoa = 'NULL';
		
		if($local_Hora	== '')	$local_Hora = 'NULL'; else $local_Hora = "'".$local_Hora."'";
		
		$sql	=	"
				INSERT INTO Agenda SET 
					Login					= '$local_Login',
					IdAgenda				= $local_IdAgenda,
					Data					= '".dataConv($local_Data,'d/m/Y','Y-m-d')."',
					Hora					= $local_Hora,
					Descricao				= '$local_Descricao',
					Status					= '".getCodigoInterno(3,36)."',
					IdPessoa				= $local_IdPessoa;";
					
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>
