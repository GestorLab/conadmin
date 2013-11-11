<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$local_DataInicioVigencia		= 	dataConv($local_DataInicioVigencia,'d/m/Y','Y-m-d');
		$local_Valor					=	str_replace(".", "", $local_Valor);	
		$local_Valor					= 	str_replace(",", ".", $local_Valor);
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		if($local_DataTerminoVigencia != ''){ 	
			$local_DataTerminoVigencia			= 	"'".dataConv($local_DataTerminoVigencia ,'d/m/Y','Y-m-d')."'";
		}else{
			$local_DataTerminoVigencia			=  	'';
		}
		
		if($local_ValorDesconto != ''){ 	
			$local_ValorDesconto		=	str_replace(".", "", $local_ValorDesconto);	
			$local_ValorDesconto		= 	str_replace(",", ".", $local_ValorDesconto);
		}else{
			$local_ValorDesconto		=  	'';
		}
		
		
		$local_ValorRepasseTerceiro		=	str_replace(".", "", $local_ValorRepasseTerceiro);	
		$local_ValorRepasseTerceiro		= 	str_replace(",", ".", $local_ValorRepasseTerceiro);
		$local_Erro = 0;
		
		if($local_Valor < $local_ValorRepasseTerceiro){
			$local_Erro = 144;
		}
		
		if($local_Erro == 0){
			switch($local_IdTipoDesconto){
				case '2':
					$local_LimiteDesconto	=	$local_DiaLimiteDesconto;
					break;
			}
			
			$sqlObsVigencia = "
				select 
					IdTipoDesconto,
					IdContratoTipoVigencia,
					ValorDesconto,
					DataTermino,
					Valor,
					ValorRepasseTerceiro,
					LimiteDesconto,
					Obs
				from 
					ContratoVigencia
				where 
					IdLoja = $local_IdLoja and 
					IdContrato = $local_IdContratoFilho and
					DataInicio = '$local_DataInicioVigencia';";
			$resObsVigencia = mysql_query($sqlObsVigencia,$con);
			$linObsVigencia = mysql_fetch_array($resObsVigencia);
			
			if($linObsVigencia[DataTermino] != str_replace("'", '', $local_DataTerminoVigencia)){
				$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Data Término de: [".dataConv($linObsVigencia[DataTermino],'Y-m-d','d/m/Y')." > ".dataConv(str_replace("'", '', $local_DataTerminoVigencia),'Y-m-d','d/m/Y')."]";
			}
			
			if($linObsVigencia[IdContratoTipoVigencia] != $local_IdContratoTipoVigencia){
				$sql = "
					select 
						DescricaoContratoTipoVigencia 
					from 
						ContratoTipoVigencia 
					where 
						IdLoja = $local_IdLoja and
						IdContratoTipoVigencia = $linObsVigencia[IdContratoTipoVigencia]
					order by 
						DescricaoContratoTipoVigencia ASC";
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				$linObsVigencia[ContratoTipoVigencia] = $lin[DescricaoContratoTipoVigencia];
				
				$sql = "
					select 
						DescricaoContratoTipoVigencia 
					from 
						ContratoTipoVigencia 
					where 
						IdLoja = $local_IdLoja and
						IdContratoTipoVigencia = $local_IdContratoTipoVigencia
					order by 
						DescricaoContratoTipoVigencia ASC";
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				$local_ContratoTipoVigencia = $lin[DescricaoContratoTipoVigencia];
				
				$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Tipo Vigência Contrato de: [$linObsVigencia[ContratoTipoVigencia] > $local_ContratoTipoVigencia]";
			}
			
			if($linObsVigencia[Valor] != $local_Valor){
				$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Valor de: [".str_replace(".", ",",$linObsVigencia[Valor])." > ".str_replace(".", ",",$local_Valor)."]";
			}
			
			if($linObsVigencia[IdTipoDesconto] != $local_IdTipoDesconto){
				$linObsVigencia[LimiteDesconto] = '';
				$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Tipo Desconto de: [".getParametroSistema(73, $linObsVigencia[IdTipoDesconto])." > ".getParametroSistema(73, $local_IdTipoDesconto)."]";
			}
			
			if($linObsVigencia[ValorDesconto] != $local_ValorDesconto){
				$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Desconto de: [".str_replace(".", ",",$linObsVigencia[ValorDesconto])." > ".str_replace(".", ",",$local_ValorDesconto)."]";
			}
			
			if($linObsVigencia[ValorRepasseTerceiro] != $local_ValorRepasseTerceiro){
				$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Valor Repasse Terceiro de: [".str_replace(".", ",",$linObsVigencia[ValorRepasseTerceiro])." > ".str_replace(".", ",",$local_ValorRepasseTerceiro)."]";
			}
			
			if($linObsVigencia[LimiteDesconto] != $local_LimiteDesconto && $local_IdTipoDesconto == 2){
				$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Dia Limite Desconto de: [$linObsVigencia[LimiteDesconto] > $local_LimiteDesconto]";
			}
			
			if($linObsVigencia[LimiteDesconto] != $local_LimiteDesconto && $local_IdTipoDesconto == 1){
				$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Data Limite Desc. de: [".dataConv($linObsVigencia[LimiteDesconto],'Y-m-d','d/m/Y')." > ".dataConv($local_LimiteDesconto,'Y-m-d','d/m/Y')."]";
			}
			
			if($local_ObsVigencia != ''){
				$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Obs: $local_ObsVigencia";
			}
			
			$ObsVigencia = trim($ObsVigencia);
			
			if($linObsVigencia[Obs] != '' && $ObsVigencia != ''){
				$ObsVigencia .= "\n";
			}
			
			$ObsVigencia .= $linObsVigencia[Obs];
			
			if($local_DataTerminoVigencia == ''){
				$local_DataTerminoVigencia = 'NULL';
			}
			
			if($local_ValorDesconto == ''){
				$local_ValorDesconto = 'NULL';
			}
			
			$sql	=	"
					UPDATE ContratoVigencia SET 
						IdTipoDesconto				= $local_IdTipoDesconto, 
						IdContratoTipoVigencia		= $local_IdContratoTipoVigencia,
						ValorDesconto				= '$local_ValorDesconto', 
						DataTermino					=  $local_DataTerminoVigencia,
						Valor						= '$local_Valor',
						ValorRepasseTerceiro		= '$local_ValorRepasseTerceiro',
						LimiteDesconto				= '$local_LimiteDesconto',
						Obs							= '$ObsVigencia',
						DataAlteracao				= (concat(curdate(),' ',curtime())),
						LoginAlteracao				= '$local_Login'
					WHERE 
						IdLoja						= $local_IdLoja and
						IdContrato					= $local_IdContratoFilho and
						DataInicio 					= '$local_DataInicioVigencia';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			if($local_IdContrato == $local_IdContratoFilho){
				$sql2	=	"
						select 
							ContratoAutomatico.IdContratoAutomatico,
							ContratoVigencia.DataInicio 
						from 
							ContratoAutomatico,
							ContratoVigencia 
						where 
							ContratoAutomatico.IdLoja = $local_IdLoja and 
							ContratoAutomatico.IdLoja = ContratoVigencia.IdLoja and 
							ContratoAutomatico.IdContratoAutomatico = ContratoVigencia.IdContrato and 
							ContratoAutomatico.IdContrato = $local_IdContrato and 
							ContratoVigencia.IdTipoDesconto = $local_IdTipoDesconto";
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
				$local_Erro = 4;		// Mensagem de Alteração Positiva
				$sql = "COMMIT;";
			}else{
				$local_Erro = 5;		// Mensagem de Alteração Negativa
				$sql = "ROLLBACK;";
			}
			
			mysql_query($sql,$con);
		}
	}
?>
