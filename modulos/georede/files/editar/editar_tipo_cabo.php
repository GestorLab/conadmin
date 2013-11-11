<?
	$localModulo	=	1;
	$local_DataInstalacao = dataConv($local_DataInstalacao,'d/m/Y','Y-m-d');
	
	
	$sql = "UPDATE 
				CaboTipo
			SET
				DescricaoCaboTipo 	 = '$local_Descricao',
				SiglaCaboTipo	 	 = '$local_SiglaCaboTipo',
				LoginAlteracao 		 = '$local_Login',
				DataAlteracao	 	 = '".date("Y-m-d h:i:s")."'
			WHERE 
				IdLoja 		 	= $local_IdLoja
				AND IdCaboTipo 	= $local_IdCaboTipo";								
	if(mysql_query($sql,$con) == true){
		$local_Erro = 4;
	}else{
		$local_Erro = 5;
	}
	

?>