<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"select (max(IdLink)+1) IdLink from Link";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdLink]!=NULL){
			$local_IdLink	=	$lin[IdLink];
		}else{
			$local_IdLink	=	1;
		}
		
		$sql	=	"
				INSERT INTO Link SET 
					IdLoja						= $local_IdLoja,
					IdLink						= $local_IdLink,
					DescricaoLink				= '$local_DescricaoLink',
					Link						= '$local_Link',
					DataCriacao					= (concat(curdate(),' ',curtime())),
					LoginCriacao				= '$local_Login';";
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>
