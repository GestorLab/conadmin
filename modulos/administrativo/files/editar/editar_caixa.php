<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"U")){
		$local_Erro = 2;
	} else{
		$sql_cx = "SELECT 
						Caixa.LoginAbertura
					FROM 
						Caixa
					WHERE 
						Caixa.IdLoja = '$local_IdLoja' AND 
						Caixa.IdCaixa = '$local_IdCaixa' AND 
						Caixa.LoginAbertura = '$local_Login'";
		$res_cx = @mysql_query($sql_cx, $con);
		
		if(@mysql_num_rows($res_cx) == 0){
			$local_Erro = 2;
		} else {
			$sql = "UPDATE 
						Caixa 
					SET
						IdStatus		= '2',
						LoginFechamento	= '$local_Login',
						DataFechamento	= (concat(curdate(),' ',curtime()))
					WHERE
						IdLoja = '$local_IdLoja' AND
						IdCaixa = '$local_IdCaixa';";
			
			if(@mysql_query($sql, $con)){
				$sql = "COMMIT;";
				$local_Erro = 4;
			} else{
				$sql = "ROLLBACK;";
				$local_Erro = 5;
			}
			
			@mysql_query($sql,$con);
		}
	}
?>