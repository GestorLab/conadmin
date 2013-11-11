<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$local_Erro = 0;
		// Sql de Inserção de Contrato Vigencia
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$local_DataInicioVigencia		= 	dataConv($local_DataInicioVigencia,'d/m/Y','Y-m-d');
		
		if($local_ValorDesconto != ''){ 	
			$local_ValorDesconto		=	str_replace(".", "", $local_ValorDesconto);	
			$local_ValorDesconto		= 	str_replace(",", ".", $local_ValorDesconto);
		}else{
			$local_ValorDesconto		=  	'NULL';
		}
		
		$local_Valor					=	str_replace(".", "", $local_Valor);	
		$local_Valor					= 	str_replace(",", ".", $local_Valor);
		
		$local_ValorRepasseTerceiro		=	str_replace(".", "", $local_ValorRepasseTerceiro);	
		$local_ValorRepasseTerceiro		= 	str_replace(",", ".", $local_ValorRepasseTerceiro);
		
		if($local_Valor < $local_ValorRepasseTerceiro){
			$local_Erro = 144;
		}
		
		$sql 	=   "select DataInicio,DataTermino from ContratoVigencia where IdLoja = $local_IdLoja and  IdContrato = $local_IdContratoFilho";
		$res	= 	mysql_query($sql,$con);
		while( $lin = mysql_fetch_array($res)){
			if($lin[DataTermino] == '' && $local_DataTerminoVigencia == ''){
				$local_Erro = 44;
				break;
			}else{
				if($local_DataInicioVigencia <=  $lin[DataTermino]){
					$local_Erro = 43;
					break;
				}
			}
		} 
		
		if($local_DataTerminoVigencia != ''){ 	
			$local_DataTerminoVigencia			= 	"'".dataConv($local_DataTerminoVigencia ,'d/m/Y','Y-m-d')."'";
		}else{
			$local_DataTerminoVigencia			=  	'NULL';
		}
		
		switch($local_IdTipoDesconto){
			case '2':
				$local_LimiteDesconto	=	$local_DiaLimiteDesconto;
				break;
		}
		
		if($local_Erro == 0){
			if($local_IdContrato != $local_IdContratoFilho){
				$sql2	=	"select ContratoVigencia.LimiteDesconto from Contrato,ContratoVigencia where ContratoVigencia.IdLoja = $local_IdLoja and ContratoVigencia.IdLoja = Contrato.IdLoja and ContratoVigencia.IdContrato = Contrato.IdContrato and ContratoVigencia.IdTipoDesconto=$local_IdTipoDesconto and ContratoVigencia.IdContrato = $local_IdContrato order by ContratoVigencia.DataInicio DESC limit 0,1";
				$res2	=	mysql_query($sql2,$con);
				$lin2	=	mysql_fetch_array($res2);
				
				$local_LimiteDesconto	=	$lin2[LimiteDesconto];
			}
		
		
			$sql	=	"
					INSERT INTO ContratoVigencia SET 
						IdLoja						= $local_IdLoja,
						IdContrato					= $local_IdContratoFilho, 
						IdTipoDesconto				= $local_IdTipoDesconto, 
						IdContratoTipoVigencia		= $local_IdContratoTipoVigencia,
						ValorDesconto				= '$local_ValorDesconto', 
						DataInicio					= '$local_DataInicioVigencia',
						DataTermino					= $local_DataTerminoVigencia,
						Valor						= '$local_Valor',
						ValorRepasseTerceiro		= '$local_ValorRepasseTerceiro',
						LimiteDesconto				= '$local_LimiteDesconto',
						DataCriacao					= (concat(curdate(),' ',curtime())),
						LoginCriacao				= '$local_Login';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			if($local_IdContrato == $local_IdContratoFilho){
				$sql2	=	"select ContratoAutomatico.IdContratoAutomatico,ContratoVigencia.DataInicio from ContratoAutomatico,ContratoVigencia where ContratoAutomatico.IdLoja = $local_IdLoja and ContratoAutomatico.IdLoja = ContratoVigencia.IdLoja and ContratoAutomatico.IdContratoAutomatico = ContratoVigencia.IdContrato and ContratoAutomatico.IdContrato = $local_IdContrato and ContratoVigencia.IdTipoDesconto = $local_IdTipoDesconto";
				$res2	=	mysql_query($sql2,$con);
				while($lin2	=	mysql_fetch_array($res2)){
					$sql	=	"
							UPDATE ContratoVigencia SET 
								LimiteDesconto				= '$local_LimiteDesconto',
								DataAlteracao				= (concat(curdate(),' ',curtime())),
								LoginAlteracao				= '$local_Login'
							WHERE 
								IdLoja						= $local_IdLoja and
								IdContrato					= $lin2[IdContratoAutomatico] and
								DataInicio 					= '$lin2[DataInicio]';";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
			}
			
			// Executa a Sql de Inserção de Codigo Interno
			for($i=0; $i<$tr_i; $i++){
				if($local_transaction[$i] == false){
					$local_transaction = false;				
				}
			}
			
			if($local_transaction == true){
				$local_Erro = 3;
				$local_Acao = 'alterar';		
				$sql = "COMMIT;";
			}else{
				$local_Erro = 8;
				$local_Acao = 'inserir';
				$sql = "ROLLBACK;";
			}
			mysql_query($sql,$con);
		}
	}
?>
