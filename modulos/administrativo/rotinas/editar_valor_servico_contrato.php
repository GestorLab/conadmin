<?
	$localModulo		= 1;
	$localOperacao		= 25;	

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Login		= $_SESSION["Login"];
	
	$local_IdServico	= $_GET['IdServico'];
	$local_DataInicio	= $_GET['DataInicio'];
	$local_ValorAntigo	= $_GET['ValorAntigo'];
	$local_ValorNovo	= $_GET['ValorNovo'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
		header("Location: ../cadastro_servico_valor.php?IdServico=$local_IdServico&Erro=$local_Erro");
	} else{
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		$cont = 0;
		$valor_temp = 0;
		$valor_desconto_temp = 0;
		$valor_final_temp = 0;
		$where = '';
		
		if($_POST['IdContrato'] != ''){
			$where .= " and Contrato.IdContrato in (" . $_POST['IdContrato'] . ")";
		}
		
		$sql = "select distinct
					Contrato.IdLoja,
					Contrato.IdContrato,
					Contrato.IdTerceiro,
					Contrato.IdPessoa,
					ContratoVigencia.Valor,
					ContratoVigencia.ValorDesconto,
					(ContratoVigencia.Valor - ContratoVigencia.ValorDesconto) ValorFinal,
					ContratoVigencia.IdTipoDesconto,
					ContratoVigencia.ValorRepasseTerceiro,
					ContratoVigencia.IdContratoTipoVigencia,
					ContratoVigencia.Obs,
					ContratoVigencia.DataTermino,
					ContratoVigencia.DataInicio,
				    substring(Pessoa.Nome,1,22) NomeTemp
				from
					Contrato,
					(
						select 
							ContratoVigencia.IdLoja,
							ContratoVigencia.IdContrato,
							MAX(ContratoVigencia.DataInicio) DataInicio 
						from 
							ContratoVigencia 
						group by
							ContratoVigencia.IdContrato
					) ContratoVigenciaTemp,
					ContratoVigencia,
					Pessoa
				where
					Contrato.IdLoja = $local_IdLoja and
					Contrato.IdLoja = ContratoVigencia.IdLoja and
					Contrato.IdServico = $local_IdServico and
					Contrato.IdStatus != 1 and
					Contrato.IdContrato = ContratoVigencia.IdContrato and
					Contrato.IdPessoa = Pessoa.IdPessoa and
					ContratoVigenciaTemp.IdLoja = ContratoVigencia.IdLoja and
					ContratoVigenciaTemp.IdContrato = ContratoVigencia.IdContrato and
					ContratoVigenciaTemp.DataInicio = ContratoVigencia.DataInicio
					$where;";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$ObsVigencia = '';
			// Dados valor Serviço
			$sql1 = "select
						ServicoValor.Valor,
						ServicoValor.IdContratoTipoVigencia,
						ServicoTerceiro.ValorRepasseTerceiro,
						ServicoTerceiro.PercentualRepasseTerceiro
					from
						ServicoValor left join ServicoTerceiro on (
							ServicoValor.IdLoja = ServicoTerceiro.IdLoja and
							ServicoValor.IdServico = ServicoTerceiro.IdServico and
							ServicoTerceiro.IdPessoa = '$lin[IdTerceiro]'
						)
					where
						ServicoValor.IdLoja = $local_IdLoja and
						ServicoValor.IdServico = $local_IdServico and
						ServicoValor.DataInicio = '$local_DataInicio'";
			$res1 = mysql_query($sql1,$con);
			$lin1 = mysql_fetch_array($res1);
			
			if($lin1[ValorRepasseTerceiro] < $lin1[PercentualRepasseTerceiro]){
				$lin1[ValorRepasseTerceiro] = number_format(($lin1[Valor] * $lin1[PercentualRepasseTerceiro] / 100),2,'.','');
			}
			
			if($lin[Valor] == $local_ValorAntigo && $lin[ValorDesconto] == 0){
				if($lin[DataInicio] == date("Y-m-d")){
					// Histórico da vigência
					if($lin[Valor] != $lin1[Valor]){
						$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Valor via SERVIÇO-VALOR de: [".str_replace(".", ",",$lin[Valor])." > ".str_replace(".", ",", $lin1[Valor])."]";
					}
					
					if($lin[ValorRepasseTerceiro] != $lin1[ValorRepasseTerceiro]){
						$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Valor Repasse Terceiro via SERVIÇO-VALOR de: [".str_replace(".", ",",$lin[ValorRepasseTerceiro])." > ".str_replace(".", ",", $lin1[ValorRepasseTerceiro])."]";
					}
					
					if($lin[IdContratoTipoVigencia] != $lin1[IdContratoTipoVigencia]){
						$sql2 = "
							select 
								DescricaoContratoTipoVigencia 
							from 
								ContratoTipoVigencia 
							where 
								IdLoja = $local_IdLoja and
								IdContratoTipoVigencia = $lin[IdContratoTipoVigencia]
							order by 
								DescricaoContratoTipoVigencia ASC";
						$res2 = @mysql_query($sql2,$con);
						$lin2 = @mysql_fetch_array($res2);
						
						$sql3 = "
							select 
								DescricaoContratoTipoVigencia 
							from 
								ContratoTipoVigencia 
							where 
								IdLoja = $local_IdLoja and
								IdContratoTipoVigencia = $lin1[IdContratoTipoVigencia]
							order by 
								DescricaoContratoTipoVigencia ASC";
						$res3 = @mysql_query($sql3,$con);
						$lin3 = @mysql_fetch_array($res3);
						
						$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Tipo Vigência Contrato via SERVIÇO-VALOR de: [$lin2[DescricaoContratoTipoVigencia] > $lin3[DescricaoContratoTipoVigencia]]";
					}
					
					$ObsVigencia = trim($ObsVigencia);
					
					if($lin[Obs] != '' && $ObsVigencia != ''){
						$ObsVigencia .= "\n";
					}
					
					$ObsVigencia .= $lin[Obs];
					
					// Alterando o valor da vigência com a data de hoje
					$sql2 = "update 
								ContratoVigencia 
							set 
								Valor					= '$lin1[Valor]',
								ValorRepasseTerceiro	= '$lin1[ValorRepasseTerceiro]',
								IdContratoTipoVigencia	= '$lin1[IdContratoTipoVigencia]',
								Obs						= '$ObsVigencia'
							where 
								IdLoja = '$lin[IdLoja]' and 
								IdContrato = '$lin[IdContrato]' and 
								DataInicio = curdate();";
					$local_transaction[$tr_i] = mysql_query($sql2,$con);
					$tr_i++;
				} else{
					if(dataConv($lin[DataInicio], 'Y-m-d', 'Ymd') > date('Ymd')){
						$DataTermino = $lin[DataInicio];
					} else{
						$DataTermino = incrementaData(date("Y-m-d"),-1);
					}
					
					// Histórico da vigência
					if($lin[DataTermino] != $DataTermino){
						$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Data Término via SERVIÇO-VALOR de: [".dataConv($lin[DataTermino],'Y-m-d','d/m/Y')." > ".dataConv($DataTermino,'Y-m-d','d/m/Y')."]";
					}
					
					$ObsVigencia = trim($ObsVigencia);
					
					if($lin[Obs] != '' && $ObsVigencia != ''){
						$ObsVigencia .= "\n";
					}
					
					$ObsVigencia .= $lin[Obs];
					
					// Finalizo a ultima vigência
					$sql2 = "update 
								ContratoVigencia 
							set 
								DataTermino	= '$DataTermino',
								Obs			= '$ObsVigencia'
							where 
								IdLoja = '$lin[IdLoja]' and 
								IdContrato = '$lin[IdContrato]' and 
								DataInicio = '$lin[DataInicio]';";
					$local_transaction[$tr_i] = mysql_query($sql2,$con);
					$tr_i++;
					
					// Adiciono uma nova vigência
					$sql3 = "insert into 
								ContratoVigencia 
							set 
								IdLoja					=  $local_IdLoja,
								IdContrato				=  $lin[IdContrato], 
								IdTipoDesconto			=  $lin[IdTipoDesconto], 
								IdContratoTipoVigencia	=  $lin1[IdContratoTipoVigencia],
								ValorDesconto			= '$lin[ValorDesconto]', 
								DataInicio				=  curdate(),
								DataTermino				=  NULL,
								Valor					= '$lin1[Valor]',
								ValorRepasseTerceiro	= '$lin1[ValorRepasseTerceiro]',
								LimiteDesconto			= '$lin[LimiteDesconto]',
								DataCriacao				=  (concat(curdate(),' ',curtime())),
								LoginCriacao			= '$local_Login';";
					$local_transaction[$tr_i] = mysql_query($sql3,$con);
					$tr_i++;
				}
			} elseif($lin[Valor] < $local_ValorNovo){
				if($cont == 0){
					echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
	\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html>
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../../css/impress.css' media='print' />
		<script type = 'text/javascript' src = '../../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../../js/event.js'></script>
		<script type = 'text/javascript' src = '../js/valor_servico_contrato.js'></script>
		<style type='text/css'>
			input[type='radio'], input[type='checkbox'] {
				border: none;
			}
		</style>
	</head>
	<body onLoad=\"ativaNome('Máscara de Vigência',false)\">
		<div id='conteudo'>
			<form name='formulario' method='post' action='editar_valor_servico_contrato.php?IdServico=$local_IdServico&DataInicio=$local_DataInicio'>
				<input type='hidden' name='Acao' value='alterar' />
				<input type='hidden' name='IdContrato' value='' />
				
				<table class='tableListar' id='tableListar' cellspacing='0'>
					<tr class='tableListarTitle'>
						<td style='width:24px;'><input type='checkbox' name='Contrato' value='$lin[IdContrato]' onClick='habilitar_campo(this, true);' /></td>
						<td class='id_listar'>Id</td>
						<td>Nome Pessoa</td>
						<td class='valor'>Valor (" . getParametroSistema(5,1) . ")</td>
						<td class='valor'>Desc. (" . getParametroSistema(5,1) . ")</td>
						<td class='valor'>Valor Final (" . getParametroSistema(5,1) . ")</td>
						<td> Manter Desconto </td>
						<td class='valor'>Novo Desc. (" . getParametroSistema(5,1) . ")</td>
						<td class='valor'>Valor Final (" . getParametroSistema(5,1) . ")</td>
						<td class='bt_lista' />
					</tr>";
				}
				
				echo "
					<tr class='tableListarDados' onmouseover='destacaRegistro(this,true)' onmouseout='destacaRegistro(this,false)' accessKey='$lin[IdContrato]'>
						<td><input type='checkbox' name='Contrato_$lin[IdContrato]' value='$lin[IdContrato]' onClick='habilitar_campo(this);' /></td>
						<td class='sequencial'><a href='../cadastro_vigencia.php?IdContrato=$lin[IdContrato]&IdPessoa=$lin[IdPessoa]' target='_blank'>$lin[IdContrato]</a></td>
						<td><a href='../cadastro_vigencia.php?IdContrato=$lin[IdContrato]&IdPessoa=$lin[IdPessoa]' target='_blank'>$lin[NomeTemp]</a></td>
						<td class='valor'><a href='../cadastro_vigencia.php?IdContrato=$lin[IdContrato]&IdPessoa=$lin[IdPessoa]' target='_blank'>" . str_replace('.', ',', $lin[Valor]) . "</a></td>
						<td class='valor'><a href='../cadastro_vigencia.php?IdContrato=$lin[IdContrato]&IdPessoa=$lin[IdPessoa]' target='_blank'>" . str_replace('.', ',', $lin[ValorDesconto]) . "</a></td>
						<td class='valor'><a href='../cadastro_vigencia.php?IdContrato=$lin[IdContrato]&IdPessoa=$lin[IdPessoa]' target='_blank'>" . str_replace('.', ',', $lin[ValorFinal]) . "</a></td>
						<td>
							<table style='margin:0;' cellpadding='0' cellspacing='0'>
								<tr>
									<td>
										<input type='radio' name='Desconto_$lin[IdContrato]' value='1' onClick=\"calcula(this, $lin[IdContrato], '$lin[Valor]', '$local_ValorNovo', '$lin[ValorDesconto]');\" disabled /> 
									</td>
									<td>
										Atual
									</td>
									<td>&nbsp;</td>
									<td>
										<input type='radio' name='Desconto_$lin[IdContrato]' value='2' onClick=\"calcula(this, $lin[IdContrato], '$lin[Valor]', '$local_ValorNovo', '$lin[ValorDesconto]');\" disabled /> 
									</td>
									<td>
										Proporcional
									</td>
								</tr>
							</table>
						</td>
						<input type='hidden' name='ValorDesconto_$lin[IdContrato]' value='' />
						<td id='valor-desconto_$lin[IdContrato]' class='valor'>
							0,00
						</td>
						<td id='valor-final_$lin[IdContrato]' class='valor'>0,00</td>
						<td class='bt_lista'>&nbsp;</td>
					</tr>";
				$valor_temp += $lin[Valor];
				$valor_desconto_temp += $lin[ValorDesconto];
				$valor_final_temp += $lin[ValorFinal];
				
				$cont++;
			} elseif($_POST['Acao'] == "alterar") {
				$local_ValorDesconto = @number_format($_POST["ValorDesconto_$lin[IdContrato]"], 2, '.', '');
				
				// Histórico da vigência
				if($lin[Valor] != $lin1[Valor]){
					$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Valor via SERVIÇO-VALOR de: [".str_replace(".", ",",$lin[Valor])." > ".str_replace(".", ",", $lin1[Valor])."]";
				}
				
				if($lin[ValorDesconto] != $local_ValorDesconto){
					$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Desconto via SERVIÇO-VALOR de: [".str_replace(".", ",",$lin[ValorDesconto])." > ".str_replace(".", ",", $local_ValorDesconto)."]";
				}
				
				if($lin[ValorRepasseTerceiro] != $lin1[ValorRepasseTerceiro]){
					$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Valor Repasse Terceiro via SERVIÇO-VALOR de: [".str_replace(".", ",",$lin[ValorRepasseTerceiro])." > ".str_replace(".", ",", $lin1[ValorRepasseTerceiro])."]";
				}
				
				if($lin[IdContratoTipoVigencia] != $lin1[IdContratoTipoVigencia]){
					$sql2 = "
						select 
							DescricaoContratoTipoVigencia 
						from 
							ContratoTipoVigencia 
						where 
							IdLoja = $local_IdLoja and
							IdContratoTipoVigencia = $lin[IdContratoTipoVigencia]
						order by 
							DescricaoContratoTipoVigencia ASC";
					$res2 = @mysql_query($sql2,$con);
					$lin2 = @mysql_fetch_array($res2);
					
					$sql3 = "
						select 
							DescricaoContratoTipoVigencia 
						from 
							ContratoTipoVigencia 
						where 
							IdLoja = $local_IdLoja and
							IdContratoTipoVigencia = $lin1[IdContratoTipoVigencia]
						order by 
							DescricaoContratoTipoVigencia ASC";
					$res3 = @mysql_query($sql3,$con);
					$lin3 = @mysql_fetch_array($res3);
					
					$ObsVigencia .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Mudou Tipo Vigência Contrato via SERVIÇO-VALOR de: [$lin2[DescricaoContratoTipoVigencia] > $lin3[DescricaoContratoTipoVigencia]]";
				}
				
				$ObsVigencia = trim($ObsVigencia);
				
				if($lin[Obs] != '' && $ObsVigencia != ''){
					$ObsVigencia .= "\n";
				}
				
				$ObsVigencia .= $lin[Obs];
				
				// Alterando o valor da vigência com a data de hoje
				$sql2 = "update 
							ContratoVigencia 
						set 
							Valor					= '$lin1[Valor]',
							ValorRepasseTerceiro	= '$lin1[ValorRepasseTerceiro]',
							IdContratoTipoVigencia	= '$lin1[IdContratoTipoVigencia]',
							ValorDesconto			= '$local_ValorDesconto',
							Obs						= '$ObsVigencia'
						where 
							IdLoja = '$lin[IdLoja]' and 
							IdContrato = '$lin[IdContrato]' and 
							DataInicio = '$lin[DataInicio]';";
				$local_transaction[$tr_i] = mysql_query($sql2,$con);
				$tr_i++;
			}
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true || $tr_i == 0){
			$sql = "COMMIT;";
			$local_Erro = 106;
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 107;
		}
		
		mysql_query($sql,$con);
		
		if($cont > 0){
			echo "
					<tr class='tableListarTitle'>
						<td />
						<td id='tableListarTotal' colspan='2'>Total:&nbsp;$cont</td>
						<td class='valor'>" . @number_format($valor_temp, 2, ',', '') . "</td>
						<td class='valor'>" . @number_format($valor_desconto_temp, 2, ',', '') . "</td>
						<td class='valor'>" . @number_format($valor_final_temp, 2, ',', '') . "</td>
						<td />
						<td id='valor-desc' class='valor'>0,00</td>
						<td id='valor-final' class='valor'>0,00</td>
						<td />
					</tr>
				</table>
			</form>
			<table style='width:100%;'>
				<tr>
					<td class='find' />
					<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
					<td style='text-align:right; padding:8px 8px;'><input type='button' onClick='submit_formulario();' value='Concluir' /></td>
				</tr>
			</table>
		</div>
	</body>
</html>
<script type='text/javascript'>
<!--
	tableMultColor('tableListar','" . getParametroSistema(15, 1) . "');
	-->
</script>";
		} else {
			header("Location: ../cadastro_servico_valor.php?IdServico=$local_IdServico&Erro=$local_Erro");
		}
	}
?>