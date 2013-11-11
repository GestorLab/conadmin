<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
	
		$local_Valor		=	str_replace(".", "", $local_Valor);	
		$local_Valor		= 	str_replace(",", ".", $local_Valor);
		
		if($local_DataTermino != ''){ 	
			$local_DataTermino			= 	"'".dataConv($local_DataTermino,'d/m/Y','Y-m-d')."'";
		}else{
			$local_DataTermino			=  	'NULL';
		}
		
		if($local_ValorDesconto != ''){ 	
			$local_ValorDesconto		=	str_replace(".", "", $local_ValorDesconto);	
			$local_ValorDesconto		= 	str_replace(",", ".", $local_ValorDesconto);
			$local_ValorDesconto		= 	"'".$local_ValorDesconto."'";
		}else{
			$local_ValorDesconto		=  	'NULL';
		}	
		
		$sql	=	"
				INSERT INTO ProdutoVigencia SET 
					IdLoja					= $local_IdLoja,
					IdProduto				= $local_IdProduto, 
					IdProdutoTipoVigencia	= $local_IdProdutoTipoVigencia, 
					DataInicio				= '".dataConv($local_DataInicio,'d/m/Y','Y-m-d')."',
					DataTermino				= $local_DataTermino,
					Valor					= '$local_Valor',
					ValorDesconto			= $local_ValorDesconto,
					DataLimiteDesconto		= '$local_DataLimiteDesconto',
					DataCriacao				= (concat(curdate(),' ',curtime())),
					LoginCriacao			= '$local_Login';";
					
		// Executa a Sql de Inserção de Pais
		if(mysql_query($sql,$con) == true){						
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 1;			// Mensagem de Inserção Negativa
		}
	}
?>
