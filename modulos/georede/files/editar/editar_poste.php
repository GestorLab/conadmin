<?
	$localModulo	=	1;

	$sql = "UPDATE 
				Poste
			SET
				IdTipoPoste		 = $local_IdTipoPoste,
				NomePoste 		 = '$local_NomePoste',
				DescricaoPoste	 = '$local_DescricaoPoste',
				IdPais 			 = $local_IdPais,
				IdEstado   		 = $local_IdEstado,
				IdCidade 		 = $local_IdCidade,
				Endereco	 	 = '$local_Endereco',
				Numero			 = '$local_Numero',
				Bairro			 = '$local_Bairro',
				Complemento 	 = '$local_Complemento',
				Cep 	 		 = '$local_Cep',
				Latitude 		 = '$local_Latitude',
				Longitude 		 = '$local_Longitude',					
				LoginAlteracao	 = '$local_Login',
				DataAlteracao	 = '".date('Y-m-d h:i:s')."'
			WHERE 
				IdLoja = $local_IdLoja
				AND IdPoste = $local_IdPoste";
	if(mysql_query($sql,$con) == true){
		$local_Erro = 4;
	}else{
		$local_Erro = 5;
	}
	

?>