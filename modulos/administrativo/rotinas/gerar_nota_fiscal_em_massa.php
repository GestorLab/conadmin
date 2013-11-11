<?	
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
		$local_IdContaReceber	= $_POST['IdContaReceber'];	
		$i = 0;
		
		$IdContaReceber = explode(',',$local_IdContaReceber);
		
		while($IdContaReceber[$i] != ''){			
			gera_nf($local_IdLoja, $IdContaReceber[$i]);		
			$i++;
		}		
	}
?>