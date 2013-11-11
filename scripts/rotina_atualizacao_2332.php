<?php
	include("../files/conecta.php");

	$sql = "SELECT 
				IdGrupoPermissao,
				IdLoja,
				IdModulo
			FROM 
				GrupoPermissaoSubOperacao 
			WHERE 
				IdModulo = 1 AND 
				IdOperacao = '26' AND 
				IdSubOperacao = 'V'";
	$res = mysql_query($sql, $con);	
	while($lin = mysql_fetch_array($res)){
		$sql_in = "INSERT INTO 
						GrupoPermissaoSubOperacao
					SET 
						IdGrupoPermissao = '".$lin["IdGrupoPermissao"]."',
						IdLoja = '".$lin["IdLoja"]."', 
						IdModulo = '".$lin["IdModulo"]."', 
						IdOperacao = '201',
						IdSubOperacao = 'V',
						LoginCriacao = 'automatico',
						DataCriacao = NOW()";
		mysql_query($sql_in, $con);

		$sql_in = "INSERT INTO 
						GrupoPermissaoSubOperacao
					SET 
						IdGrupoPermissao = '".$lin["IdGrupoPermissao"]."',
						IdLoja = '".$lin["IdLoja"]."', 
						IdModulo = '".$lin["IdModulo"]."', 
						IdOperacao = '201',
						IdSubOperacao = 'I',
						LoginCriacao = 'automatico',
						DataCriacao = NOW()";
		mysql_query($sql_in, $con);

		$sql_in = "INSERT INTO 
						GrupoPermissaoSubOperacao
					SET 
						IdGrupoPermissao = '".$lin["IdGrupoPermissao"]."',
						IdLoja = '".$lin["IdLoja"]."', 
						IdModulo = '".$lin["IdModulo"]."', 
						IdOperacao = '201',
						IdSubOperacao = 'U',
						LoginCriacao = 'automatico',
						DataCriacao = NOW()";
		mysql_query($sql_in, $con);
	}
	
	$sql = "SELECT 
				Login,
				IdLoja,
				IdModulo
			FROM 
				UsuarioSubOperacao 
			WHERE 
				IdModulo = 1 AND 
				IdOperacao = '26' AND 
				IdSubOperacao = 'V'";
	$res = mysql_query($sql, $con);
	
	while($lin = mysql_fetch_array($res)){
		$sql_in = "INSERT INTO 
						UsuarioSubOperacao
					SET 
						Login = '".$lin["Login"]."',
						IdLoja = '".$lin["IdLoja"]."', 
						IdModulo = '".$lin["IdModulo"]."', 
						IdOperacao = '201',
						IdSubOperacao = 'V',
						LoginCriacao = 'automatico',
						DataCriacao = NOW()";
		mysql_query($sql_in, $con);

		$sql_in = "INSERT INTO 
						UsuarioSubOperacao
					SET 
						Login = '".$lin["Login"]."',
						IdLoja = '".$lin["IdLoja"]."', 
						IdModulo = '".$lin["IdModulo"]."', 
						IdOperacao = '201',
						IdSubOperacao = 'I',
						LoginCriacao = 'automatico',
						DataCriacao = NOW()";
		mysql_query($sql_in, $con);

		$sql_in = "INSERT INTO 
						UsuarioSubOperacao
					SET 
						Login = '".$lin["Login"]."',
						IdLoja = '".$lin["IdLoja"]."', 
						IdModulo = '".$lin["IdModulo"]."', 
						IdOperacao = '201',
						IdSubOperacao = 'U',
						LoginCriacao = 'automatico',
						DataCriacao = NOW()";
		mysql_query($sql_in, $con);
	}
?>