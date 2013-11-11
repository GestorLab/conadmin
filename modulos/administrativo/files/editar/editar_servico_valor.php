<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	} else {
		// Sql de Inserção de ServicoValor
		$sql = "START TRANSACTION;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$local_DataInicio		= dataConv($local_DataInicio,'d/m/Y','Y-m-d');
		$local_Valor			= str_replace(".", "", $local_Valor);
		$local_Valor			= str_replace(",", ".", $local_Valor);
		$local_ValorAnterior	= str_replace(".", "", $local_ValorAnterior);
		$local_ValorAnterior	= str_replace(",", ".", $local_ValorAnterior);
		$local_MultaFidelidade	= str_replace(".", "", $local_MultaFidelidade);
		$local_MultaFidelidade	= str_replace(",", ".", $local_MultaFidelidade);
		
		if($local_DataTermino != '') {
			$local_DataTerminoTemp = dataConv($local_DataTermino,'d/m/Y','Y-m-d');
			$local_DataTermino = "'".dataConv($local_DataTermino,'d/m/Y','Y-m-d')."'";
		} else {
			$local_DataTerminoTemp = $local_DataTermino;
			$local_DataTermino = 'NULL';
		}
		
		if($local_IdContratoTipoVigencia == "") {
			$local_IdContratoTipoVigencia = 'NULL';
		}
		
		$sql = "SELECT
					Obs
				FROM
					Servico
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdServico = '$local_IdServico';";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		$TempObsServico = str_replace("'", "\'", $lin[Obs]);
		$ObsServico = '';
		$sql = "SELECT 
					DataTermino,
					Valor,
					MultaFidelidade,
					IdContratoTipoVigencia,
					DescricaoServicoValor,
					ValorRepasseTerceiro
				FROM
					ServicoValor
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdServico = '$local_IdServico' AND
					DataInicio = '$local_DataInicio';";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		$lf = '';
		
		if($lin[DataTermino] != $local_DataTerminoTemp) {
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Valor (".getParametroSistema(5,1).") Serviço ".dataConv($local_DataInicio, "Y-m-d", "d/m/Y")." - Alteração de Data Término [".dataConv($lin[DataTermino],'Y-m-d','d/m/Y')." > ".dataConv($local_DataTerminoTemp,'Y-m-d','d/m/Y')."].";
			$lf = "\n";
		}
		
		if($lin[Valor] != $local_Valor) {
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Valor (".getParametroSistema(5,1).") Serviço ".dataConv($local_DataInicio, "Y-m-d", "d/m/Y")." - Alteração de Valor (".getParametroSistema(5,1).") [".str_replace('.', ',', $lin[Valor])." > ".str_replace('.', ',', $local_Valor)."].";
			$lf = "\n";
		}
		
		if($lin[MultaFidelidade] != $local_MultaFidelidade) {
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Valor (".getParametroSistema(5,1).") Serviço ".dataConv($local_DataInicio, "Y-m-d", "d/m/Y")." - Alteração de Valor Multa Fidelidade (".getParametroSistema(5,1).") [".str_replace('.', ',', $lin[MultaFidelidade])." > ".str_replace('.', ',', $local_MultaFidelidade)."].";
			$lf = "\n";
		}
		
		if($lin[IdContratoTipoVigencia] != $local_IdContratoTipoVigencia) {
			$sql0 = "SELECT
						DescricaoContratoTipoVigencia
					FROM 
						ContratoTipoVigencia
					WHERE 
						IdLoja = '$local_IdLoja' AND
						IdContratoTipoVigencia = '$lin[IdContratoTipoVigencia]';";
			$res0 = mysql_query($sql0, $con);
			$lin0 = @mysql_fetch_array($res0);
			$sql1 = "SELECT
						DescricaoContratoTipoVigencia
					FROM 
						ContratoTipoVigencia
					WHERE 
						IdLoja = '$local_IdLoja' AND
						IdContratoTipoVigencia = '$local_IdContratoTipoVigencia';";
			$res1 = mysql_query($sql1, $con);
			$lin1 = @mysql_fetch_array($res1);
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Valor (".getParametroSistema(5,1).") Serviço ".dataConv($local_DataInicio, "Y-m-d", "d/m/Y")." - Alteração de Tipo Vigência [$lin0[DescricaoContratoTipoVigencia] > $lin1[DescricaoContratoTipoVigencia]].";
			$lf = "\n";
		}
		
		if($lin[DescricaoServicoValor] != $local_DescricaoServicoValor) {
			$ObsServico .= $lf.date("d/m/Y H:i:s")." [".$local_Login."] - Valor (".getParametroSistema(5,1).") Serviço ".dataConv($local_DataInicio, "Y-m-d", "d/m/Y")." - Alteração de Descrição do Valor [".str_replace("'", "\'", $lin[DescricaoServicoValor])." > ".str_replace("'", "\'", $local_DescricaoServicoValor)."].";
			$lf = "\n";
		}
		
		if($TempObsServico != '' && $ObsServico != '') {
			$ObsServico .= "\n".$TempObsServico;
		} else {
			$ObsServico .= $TempObsServico;
		}
		
		$sql = "UPDATE Servico SET
					Obs				= '$ObsServico',
					DataAlteracao	= (concat(curdate(),' ',curtime())),
					LoginAlteracao	= '$local_Login'
				WHERE 
					IdLoja = '$local_IdLoja' AND
					IdServico = '$local_IdServico';";
		$local_transaction[$tr_i] = mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "UPDATE ServicoValor SET 
					DescricaoServicoValor	= '$local_DescricaoServicoValor', 
					DataTermino				= $local_DataTermino,
					Valor					= '$local_Valor',
					MultaFidelidade			= '$local_MultaFidelidade',
					IdContratoTipoVigencia	= $local_IdContratoTipoVigencia,
					DataAlteracao			= (concat(curdate(),' ',curtime())),
					LoginAlteracao			= '$local_Login'
				WHERE 
					IdLoja = $local_IdLoja AND
					IdServico = $local_IdServico AND
					DataInicio = '$local_DataInicio';";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		for($i = 0; $i < $tr_i; $i++) {
			if(!$local_transaction[$i]) {
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction) {
			$sql = "COMMIT;";
			@mysql_query($sql,$con);
			/*Leonardo 12-12-12 Conforme conversado com o Douglas e o Felipe essa ferramenta foi desativada e será usada uma nova ferramenta de alteração de contratos em massa.
			$sql2 = "select
						ServicoValor.DataInicio
					from
						Servico left join (
							select 
								ServicoValor.IdServico, 
								ServicoValor.DataInicio, 
								ServicoValor.Valor 
							from 
								ServicoValor, 
								(
									select 
										ServicoValor.IdServico, 
										max(DataInicio) DataInicio 
									from 
										ServicoValor 
									where 
										ServicoValor.IdLoja = $local_IdLoja and 
										ServicoValor.DataInicio <= curdate() 
									group by 
										ServicoValor.IdServico
								) ServicoValorTemp 
							where 
								ServicoValor.IdLoja = $local_IdLoja and 
								ServicoValor.IdServico = ServicoValorTemp.IdServico and 
								ServicoValor.DataInicio = ServicoValorTemp.DataInicio
						) ServicoValor on (
							Servico.IdServico = ServicoValor.IdServico
						)
					where
						Servico.IdLoja = $local_IdLoja and
						Servico.IdServico = $local_IdServico;";
			$res2 = mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
			
			if($lin2[DataInicio] == $local_DataInicio && $local_Valor != $local_ValorAnterior && ($local_IdTipoServico == 1 || $local_IdTipoServico == 4)) {
				header("Location: alterar_valor_contrato.php?IdServico=$local_IdServico&DataInicio=$local_DataInicio&ValorAntigo=$local_Valor&ValorNovo=$local_Valor");
			} else {*/
				$local_Erro = 4;		// Mensagem de Alteração Positiva
			//}
		} else {
			$sql = "ROLLBACK;";
			@mysql_query($sql,$con);
			$local_Erro = 5;		// Mensagem de Alteração Negativa
		}
	}
?>