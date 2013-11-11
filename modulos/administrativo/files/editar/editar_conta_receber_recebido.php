<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		if($local_DataVencimento != ''){
			$local_DataVencimento	=	"'".dataConv($local_DataVencimento,'d/m/Y','Y-m-d')."'";
		}else{
			$local_DataVencimento	=	"NULL";
		}
		
		if($local_DataNF != ''){
			$local_DataNF	=	"'".dataConv($local_DataNF,'d/m/Y','Y-m-d')."'";
		}else{
			$local_DataNF	=	"NULL";
		}
		
		$sql	=	"select Obs from ContaReceber where IdLoja = $local_IdLoja and IdContaReceber = $local_IdContaReceber";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
		
		if($local_Obs != ""){
			$local_Obs	=	date("d/m/Y H:i:s")." [".$local_Login."] - ".$local_Obs."."."\n".trim($lin[Obs]);
		}else{
			$local_Obs	= trim($lin[Obs]);
		}
		
		$sql	=	"UPDATE ContaReceber SET
							NumeroNF			= '$local_NumeroNF',
							DataNF				= $local_DataNF,
							Obs					= '$local_Obs',
							DataVencimento		= $local_DataVencimento,
							LoginAlteracao		= '$local_Login',
							DataAlteracao		= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja			= '$local_IdLoja' and
							IdContaReceber	= '$local_IdContaReceber'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
