<?
	$localModulo	=	1;
	$local_DataInstalacao = dataConv($local_DataInstalacao,'d/m/Y','Y-m-d');
	
	
	$sql = "UPDATE 
				Cabo
			SET
				IdTipoCabo	 	 = $local_TipoCabo,
				NomeCabo	 	 = '$local_NomeCabo',
				Especificacao	 = '$local_Especificacao',
				QtdFibra	 	 = '$local_QtdFibra',
				DataInstalacao	 = '$local_DataInstalacao',
				Cor				 = '$local_Cor',
				EspessuraVisual	 = '$local_EspessuraVisual',
				Opacidade		 = '$local_Opacidade',
				Oculto			 = '$local_Oculto',
				LoginAlteracao 	 = '$local_Login',
				DataAlteracao 	 = '".date("Y/m/d H:i:s")."'
			WHERE
				IdLoja		= $local_IdLoja
				AND IdCabo  = $local_IdCabo";
				
	if(mysql_query($sql,$con) == true){
		$local_Erro = 4;
	}else{
		$local_Erro = 5;
	}
	

?>