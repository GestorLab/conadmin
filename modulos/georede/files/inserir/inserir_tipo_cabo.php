<?

	$sql	=	"SELECT (max(IdCaboTipo)+1)IdCaboTipo FROM  CaboTipo  where IdLoja = $local_IdLoja";
	$res	=	mysql_query($sql,$con);
	$lin	=	mysql_fetch_array($res);
	
	if($lin[IdCaboTipo] == ''){
		$lin[IdCaboTipo] = 1;
	}
	
	$sql = "INSERT INTO
					CaboTipo
				SET 
					IdLoja				= $local_IdLoja,
					IdCaboTipo			= $lin[IdCaboTipo],
					DescricaoCaboTipo	= '$local_Descricao',
					LoginCriacao		= '$local_Login',
					DataCriacao			= '".date('Y-m-d H:i:s')."'";
	if(mysql_query($sql,$con) == true){						
			$local_Acao 		= 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro 		= 3;			// Mensagem de Inserчуo Positiva
			$local_IdCaboTipo 	= $lin[IdCaboTipo];
	}else{
		// Muda a aчуo para Inserir
		$local_Acao = 'inserir';
		$local_Erro = 1;			// Mensagem de Inserчуo Negativa
	}
?>