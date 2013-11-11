<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"F")){
		$sql_cx = "SELECT 
						Caixa.LoginAbertura
					FROM 
						Caixa
					WHERE 
						Caixa.IdLoja = '$local_IdLoja' AND 
						Caixa.IdCaixa = '$local_IdCaixa'";
						
		$res_UsuarioAbertura  = mysql_query($sql_cx,$con);
		$dado_UsuarioAbertura = mysql_fetch_array($res_UsuarioAbertura);
		
		$sql2 	= "SELECT
						*
					FROM
						Caixa
					WHERE
						IdLoja = '$local_IdLoja' AND 
						LoginAbertura = '$dado_UsuarioAbertura[LoginAbertura]' AND
						IdStatus = 1";
		$res_cx = @mysql_query($sql2, $con);
		if(mysql_num_rows($res_cx) > 0)
			$local_Erro = 197;
		else{
			$sql = "UPDATE 
					Caixa 
				SET
					IdStatus	= '1'
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
	} else {
		$sql_cx = "SELECT 
						Caixa.LoginAbertura
					FROM 
						Caixa
					WHERE 
						Caixa.IdLoja = '$local_IdLoja' AND 
						Caixa.IdCaixa = '$local_IdCaixa' AND 
						Caixa.LoginAbertura = '$local_Login'";
		$res_cx = @mysql_query($sql_cx, $con);
		$dado_cx = mysql_fetch_array($res_cx);
		if(mysql_num_rows($res_cx) <= 0)
			$local_Erro = 2;
		else{
			$sql2 	= "SELECT
						*
					FROM
						Caixa
					WHERE
						IdLoja = '$local_IdLoja' AND 
						LoginAbertura = '$dado_cx[LoginAbertura]' AND
						IdStatus = 1";
			$res_cx2 = @mysql_query($sql2, $con);
			if(mysql_num_rows($res_cx2) > 0)
				$local_Erro = 197;
			else{
				$sql = "UPDATE 
						Caixa 
					SET
						IdStatus	= '1'
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
	}
?>