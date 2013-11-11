<?
	$local_Login	= $_SESSION["Login"];
	
	$sql	=	"
				UPDATE HelpDeskSubTipo SET 
					DescricaoSubTipoHelpDesk	= '$local_DescricaoSubTipo',  
					IdStatus					=  $local_IdStatusSubTipo,
					DataAlteracao				=  (concat(curdate(),' ',curtime())),
					LoginAlteracao				= '$local_Login'
				WHERE 
					IdSubTipoHelpDesk			= $local_IdSubTipo;
	";
	if(mysql_query($sql,$conCNT) == true){
		$local_Erro = 4;
	}else{
		$local_Erro = 5;
	}
?>
