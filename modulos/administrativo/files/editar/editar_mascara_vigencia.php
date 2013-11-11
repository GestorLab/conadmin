<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$local_Mes = explode('º',$local_Mes);
		$local_Mes = $local_Mes[0];	
		
		if($local_IdTipoDesconto == 1){
			$local_LimiteDesconto	=	dataConv($local_LimiteDesconto,'d/m/Y','Y-m-d');
		}
		
		if($local_IdTipoDesconto == 3){
			$local_Fator	=	1;
		}
		
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		$sql = "
			SELECT
				Obs
			FROM
				Servico
			WHERE
				IdLoja = '$local_IdLoja' AND
				IdServico = '$local_IdServico';";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		$TempObsServico = str_replace("'", "\'", $lin[Obs]);
		$ObsServico = '';
		$sql = "
			SELECT
				IdTipoDesconto,
				Fator,
				ValorRepasseTerceiro,
				PercentualRepasseTerceiro,
				IdContratoTipoVigencia,
				VigenciaDefinitiva,
				LimiteDesconto
			FROM
				ServicoMascaraVigencia
			WHERE
				IdLoja = '$local_IdLoja' AND
				IdServico = '$local_IdServico' AND
				Mes = '$local_Mes';";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		$lf = '';
		
		if($lin[IdTipoDesconto] != $local_IdTipoDesconto){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '73' AND
					IdParametroSistema = '$lin[IdTipoDesconto]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '73' AND
					IdParametroSistema = '$local_IdTipoDesconto';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Mascará Vigência ".$local_Mes."º Mês - Alteração de Tipo Desconto [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		$sql0 = "
			SELECT
				Valor
			FROM 
				ServicoValor
			WHERE 
				DataInicio <= CURDATE() AND 
				(DataTermino IS NULL OR 
						DataTermino >= CURDATE()) AND 
				IdLoja = '$local_IdLoja' AND
				IdServico = '$local_IdServico'
			ORDER BY 
				DataInicio DESC;"; 
		$res0 = @mysql_query($sql0, $con);
		$lin0 = @mysql_fetch_array($res0);
		$lin0[ValorDesconto] = $lin0[Valor] - ($lin[Fator] * $lin0[Valor]);
		$lin0[ValorDesconto] = round($lin0[ValorDesconto] * pow(10, 2))/ pow(10, 2);
		$temp = explode('.', $lin0[ValorDesconto]);
		$lin0[ValorDesconto] = $temp[0] . ',' . substr($temp[1] . '00', 0, 2);
//		$lin0[ValorDesconto] = str_replace('.', ',', ($lin0[Valor] - ($lin[Fator] * $lin0[Valor])));
//		$f = 10.00 - (0.9999999999999999 * 10.00);
//		$f = 0.9999999999999999;
//		echo round( $f * pow( 10 , 2 ) ) / pow( 10 , 2 );
		
		if($lin0[ValorDesconto] != $local_ValorDesconto){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Mascará Vigência ".$local_Mes."º Mês - Alteração de Desconto (".getParametroSistema(5,1).") [$lin0[ValorDesconto] > $local_ValorDesconto].";
			$lf = "\n";
		}
		
		if($lin[IdContratoTipoVigencia] != $local_IdContratoTipoVigencia){
			$sql0 = "
				SELECT
					DescricaoContratoTipoVigencia
				FROM 
					ContratoTipoVigencia
				WHERE 
					IdLoja = '$local_IdLoja' AND
					IdContratoTipoVigencia = '$lin[IdContratoTipoVigencia]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					DescricaoContratoTipoVigencia
				FROM 
					ContratoTipoVigencia
				WHERE 
					IdLoja = '$local_IdLoja' AND
					IdContratoTipoVigencia = '$local_IdContratoTipoVigencia';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Mascará Vigência ".$local_Mes."º Mês - Alteração de Tipo Vigência Contrato [".$lin0[DescricaoContratoTipoVigencia]." > ".$lin1[DescricaoContratoTipoVigencia]."].";
			$lf = "\n";
		}
		
		if($lin[VigenciaDefinitiva] != $local_VigenciaDefinitiva){
			$sql0 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '144' AND
					IdParametroSistema = '$lin[VigenciaDefinitiva]';";
			$res0 = @mysql_query($sql0,$con);
			$lin0 = @mysql_fetch_array($res0);
			
			$sql1 = "
				SELECT
					ValorParametroSistema
				FROM
					ParametroSistema
				WHERE
					IdGrupoParametroSistema = '144' AND
					IdParametroSistema = '$local_VigenciaDefinitiva';";
			$res1 = @mysql_query($sql1,$con);
			$lin1 = @mysql_fetch_array($res1);
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Mascará Vigência ".$local_Mes."º Mês - Alteração de Vigência Definitiva [".$lin0[ValorParametroSistema]." > ".$lin1[ValorParametroSistema]."].";
			$lf = "\n";
		}
		
		if($lin[LimiteDesconto] != $local_LimiteDesconto && $local_IdTipoDesconto == 2){
			if($lin[IdTipoDesconto] == 1){
				$lin[LimiteDesconto] = '';
			}
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Mascará Vigência ".$local_Mes."º Mês - Alteração de Dia Limite Desconto [$lin[LimiteDesconto] > $local_LimiteDesconto].";
			$lf = "\n";
		}
		
		if($lin[LimiteDesconto] != $local_LimiteDesconto && $local_IdTipoDesconto == 1){
			if($lin[IdTipoDesconto] == 2){
				$lin[LimiteDesconto] = '';
			}
			
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Mascará Vigência ".$local_Mes."º Mês - Alteração de Data Limite Desc [".dataConv($lin[LimiteDesconto],'Y-m-d','d/m/Y')." > ".dataConv($local_LimiteDesconto,'Y-m-d','d/m/Y')."].";
			$lf = "\n";
		}
		
		if($lin[ValorRepasseTerceiro] != '') {
			$lin[ValorRepasseTerceiro] = @number_format($lin[ValorRepasseTerceiro], 2, ',', '');
		}
		
		if($lin[ValorRepasseTerceiro] != $local_ValorRepasseTerceiro){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Mascará Vigência Terceiro ".$local_Mes."º Mês - Alteração de Valor Repasse Mensal (".getParametroSistema(5,1).") [$lin[ValorRepasseTerceiro] > $local_ValorRepasseTerceiro].";
			$lf = "\n";
		}
		
		if($lin[PercentualRepasseTerceiro] != '') {
			$lin[PercentualRepasseTerceiro] = @number_format($lin[PercentualRepasseTerceiro], 2, ',', '');
		}
		
		if($lin[PercentualRepasseTerceiro] != $local_PercentualRepasseTerceiro){
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Mascará Vigência Terceiro ".$local_Mes."º Mês - Alteração de Percentual (%) [$lin[PercentualRepasseTerceiro] > $local_PercentualRepasseTerceiro].";
			$lf = "\n";
		}
		
		if($TempObsServico != '' && $ObsServico != ''){
			$ObsServico .= "\n".$TempObsServico;
		} else{
			$ObsServico .= $TempObsServico;
		}
		
		$sql = "
			UPDATE Servico SET
				Obs				= '$ObsServico',
				LoginAlteracao	= '$local_Login',
				DataAlteracao	= concat(curdate(),' ',curtime())
			WHERE 
				IdLoja = '$local_IdLoja' AND
				IdServico = '$local_IdServico';";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		if($local_IdTipoDesconto != 2){
			$local_LimiteDesconto = "NULL";
		}
		
		if($local_IdRepasse == 1) {
			$local_ValorRepasseTerceiro = str_replace(array('.',','), array('','.'), $local_ValorRepasseTerceiro);
			$local_PercentualRepasseTerceiro = "NULL";
		} elseif($local_IdRepasse == 2) { 
			$local_ValorRepasseTerceiro = "NULL";
			$local_PercentualRepasseTerceiro = str_replace(array('.',','), array('','.'), $local_PercentualRepasseTerceiro);
		} else {
			$local_ValorRepasseTerceiro = "NULL";
			$local_PercentualRepasseTerceiro = "NULL";
		}
		
		$sql	=	"UPDATE ServicoMascaraVigencia SET
							IdTipoDesconto				= '$local_IdTipoDesconto',
							LimiteDesconto				=  $local_LimiteDesconto,
							IdContratoTipoVigencia		= '$local_IdContratoTipoVigencia',
							Fator						= '$local_Fator',
							ValorRepasseTerceiro		= $local_ValorRepasseTerceiro,
							PercentualRepasseTerceiro	= $local_PercentualRepasseTerceiro,
							VigenciaDefinitiva			= '$local_VigenciaDefinitiva',
							LoginAlteracao				= '$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
					WHERE 
							IdLoja					= '$local_IdLoja' and
							IdServico				= '$local_IdServico' and
							Mes						= '$local_Mes'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		if($local_VigenciaDefinitiva == 1){
			$sql	=	"UPDATE ServicoMascaraVigencia SET						
								VigenciaDefinitiva		= '2'						
						WHERE 
								IdLoja					= '$local_IdLoja' and
								IdServico				= '$local_IdServico' and
								Mes						!= '$local_Mes'"; 
						
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;		
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			// Muda a ação para Inserir
			$local_Acao = 'alterar';
			$local_Erro = 4;			// Mensagem de Inserção Positiva
		}else{
			$sql = "ROLLBACK;";
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 5;			// Mensagem de Inserção Negativa
		}
		
		mysql_query($sql,$con);						
	}
?>