<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"
				INSERT INTO DatasEspeciais SET 
					IdLoja						= $local_IdLoja,
					Data						= '".dataConv($local_Data,'d/m/Y','Y-m-d')."',
					TipoData					= '$local_TipoData',
					DescricaoData				= '$local_DescricaoData',
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
