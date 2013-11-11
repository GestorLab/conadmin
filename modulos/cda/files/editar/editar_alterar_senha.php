<?
	
	$sql =	"select Senha from Pessoa where IdPessoa = '$local_IdPessoa'and Senha='$local_SenhaAntiga';";
	$res	=	mysql_query($sql,$con);
    if($lin	=	mysql_fetch_array($res)){

		$sql	=	"UPDATE Pessoa SET 				
						Senha	    		= '$local_NovaSenha',
						LoginAlteracao		= '$local_Login',
						DataAlteracao		= concat(curdate(),' ',curtime())
					WHERE
						IdPessoa			='$local_IdPessoa';";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 114;		
		}else{
			$local_Erro = 115;
		}
	}else{
		$local_Erro = 13;
	}

?>
