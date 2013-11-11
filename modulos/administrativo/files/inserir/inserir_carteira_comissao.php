<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		
		$local_Percentual		=	str_replace(".", "", $local_Percentual);	
		$local_Percentual		= 	str_replace(",", ".", $local_Percentual);
		
		$sql	=	"
				INSERT INTO ComissionamentoCarteira SET 
					IdLoja					= $local_IdLoja,
					IdAgenteAutorizado		= $local_IdAgenteAutorizado,
					IdCarteira				= $local_IdCarteira,
					IdServico				= '$local_IdServico',
					Parcela					= '$local_Parcela',
					Percentual				= '$local_Percentual',
					DataCriacao				= (concat(curdate(),' ',curtime())),
					LoginCriacao			= '$local_Login';";
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>
