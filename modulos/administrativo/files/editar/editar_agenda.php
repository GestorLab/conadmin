<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		if($local_IdPessoa == '')	$local_IdPessoa  = 'NULL';
			
		if($local_Hora	== '')	$local_Hora = 'NULL'; else $local_Hora = "'".$local_Hora."'";
		
		$sql	=	"UPDATE Agenda SET
							Data		= '".dataConv($local_Data,'d/m/Y','Y-m-d')."',
							Hora		= $local_Hora,
							Descricao	= '$local_Descricao',
							IdPessoa	= $local_IdPessoa,
							Status		= '$local_IdStatus'
					 WHERE 
							IdAgenda	= $local_IdAgenda
							and Login   = '$local_Login'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
