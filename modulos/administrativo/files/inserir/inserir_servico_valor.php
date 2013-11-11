<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false) {
		$local_Erro = 2;
	} else {
		// Sql de Inserчуo de ServicoValor
		$local_DataInicio		= dataConv($local_DataInicio,'d/m/Y','Y-m-d');
		$local_Valor			= str_replace(".", "", $local_Valor);
		$local_Valor			= str_replace(",", ".", $local_Valor);
		$local_MultaFidelidade	= str_replace(".", "", $local_MultaFidelidade);
		$local_MultaFidelidade	= str_replace(",", ".", $local_MultaFidelidade);
		
		if($local_DataTermino != '') {
			$local_DataTermino = "'".dataConv($local_DataTermino,'d/m/Y','Y-m-d')."'";
		} else {
			$local_DataTermino = 'NULL';
		}
		
		$sql = "select DataInicio from ServicoValor where IdLoja = $local_IdLoja and IdServico = $local_IdServico";
		$res = mysql_query($sql,$con);
		
		while($lin = @mysql_fetch_array($res)) {
			if($lin[DataInicio] == $local_DataInicio) {
				$local_Acao = 'inserir';
				$local_Erro = 30;
				break;
			}
		} 
		
		if($local_Erro != 30) {
			if($local_IdContratoTipoVigencia == "") {
				$local_IdContratoTipoVigencia = 'NULL';
			}
			$sql1 ="select
						ServicoValor.DataInicio,
						ServicoValor.DataTermino
					from 
						Loja,
						Servico,
						ServicoValor
					where
						Servico.IdLoja = $local_IdLoja and
						Servico.IdLoja = Loja.IdLoja and
						ServicoValor.IdLoja = Servico.IdLoja and
						Servico.IdServico = ServicoValor.IdServico and
						ServicoValor.IdServico = $local_IdServico
					order by 
						DataInicio DESC 
					Limit 1";
			$res1 = mysql_query($sql1,$con);
			$lin1 = mysql_fetch_array($res1);
			if($lin1[DataTermino] == ""){
				$DataTerminoAnterior =  incrementaData($local_DataInicio,-1);

				$sqlUpdate="update ServicoValor set
								DataTermino = '$DataTerminoAnterior'
							where
								IdLoja = $local_IdLoja and
								IdServico = $local_IdServico and
								DataInicio = '$lin1[DataInicio]'";
				if(mysql_query($sqlUpdate,$con)) {
					$sql = "INSERT INTO ServicoValor SET 
								IdLoja							= $local_IdLoja,
								IdServico						= $local_IdServico, 
								DescricaoServicoValor			= '$local_DescricaoServicoValor', 
								DataInicio						= '$local_DataInicio',
								DataTermino						= $local_DataTermino,
								Valor							= '$local_Valor',
								IdContratoTipoVigencia			= $local_IdContratoTipoVigencia,
								MultaFidelidade					= '$local_MultaFidelidade',
								DataCriacao						= (concat(curdate(),' ',curtime())),
								LoginCriacao					= '$local_Login';";
					
					if(mysql_query($sql,$con)) {
						$local_Acao = 'alterar';
						$local_Erro = 3;		// Mensagem de Alteraчуo Positiva
					} else {
						$local_Acao = 'inserir';
						$local_Erro = 8;		// Mensagem de Alteraчуo Negativa
					}
				}else{
					$local_Acao = 'inserir';
					$local_Erro = 8;		// Mensagem de Alteraчуo Negativa
				}
			}else{
				$sql = "INSERT INTO ServicoValor SET 
							IdLoja							= $local_IdLoja,
							IdServico						= $local_IdServico, 
							DescricaoServicoValor			= '$local_DescricaoServicoValor', 
							DataInicio						= '$local_DataInicio',
							DataTermino						= $local_DataTermino,
							Valor							= '$local_Valor',
							IdContratoTipoVigencia			= $local_IdContratoTipoVigencia,
							MultaFidelidade					= '$local_MultaFidelidade',
							DataCriacao						= (concat(curdate(),' ',curtime())),
							LoginCriacao					= '$local_Login';";
				
				if(mysql_query($sql,$con)) {
					$local_Acao = 'alterar';
					$local_Erro = 3;		// Mensagem de Alteraчуo Positiva
				} else {
					$local_Acao = 'inserir';
					$local_Erro = 8;		// Mensagem de Alteraчуo Negativa
				}
			}
		}
	}
?>