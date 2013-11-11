<?
	$local_Login	= $_SESSION["Login"];
	
	$sql	=	"
				UPDATE HelpDeskTipo SET 
					DescricaoTipoHelpDesk	= '$local_DescricaoTipo',  
					IdStatus				=  $local_IdStatus,
					DataAlteracao			=  (concat(curdate(),' ',curtime())),
					LoginAlteracao			= '$local_Login'
				WHERE 
					IdTipoHelpDesk			= $local_IdTipo;
	";
	if(mysql_query($sql,$conCNT) == true){
		$local_Erro = 4;
	}else{
		$local_Erro = 5;
	}
?>
