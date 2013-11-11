<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de Servico
		$sql	=	"select (max(IdServico)+1) IdServico from Servico where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
			
		if($lin[IdServico]!=NULL){
			$local_IdServico	=	$lin[IdServico];
		}else{
			$local_IdServico	=	1;
		}
		
		if($local_IdServicoGrupo == ""){
			$local_IdServicoGrupo = 'NULL';
		}		
		
		if($local_AtivacaoAutomaticaTemp == ""){
			$local_AtivacaoAutomaticaTemp = '1';
		}
		
		if($local_IdPessoa == ""){
			$local_IdPessoa= 'NULL';
		}else{
			$local_IdPessoa	=	"$local_IdPessoa";
		}		
		
		if($local_IdOrdemServicoLayout == ''){
			$local_IdOrdemServicoLayout = 'NULL';
		}
		
		if($local_IdCategoriaTributaria == ''){
			$local_IdCategoriaTributaria = 'NULL';
		}
		
		if($local_Cor == '' || ($local_IdTipoServico != 2 && $local_IdTipoServico != 3)){
			$local_Cor = 'NULL';
		} else{
			$local_Cor = "'$local_Cor'";
		}
		
		if($local_SICIAtivoDefault == 1 && $local_IdTipoServico == 1){
			if($local_IdTecnologia == ''){
				$local_IdTecnologia = "NULL";
			}
			
			if($local_IdDedicado != ''){
				$local_IdDedicado = "'".$local_IdDedicado."'";
			} else{
				$local_IdDedicado = "NULL";
			}
			
			if($local_FatorMega != ''){
				$local_FatorMega = str_replace ('.' , '' , $local_FatorMega);
				$local_FatorMega = "'".str_replace (',' , '.' , $local_FatorMega)."'";
			} else{
				$local_FatorMega = "NULL";
			}
			
			if($local_IdGrupoVelocidade != ''){
				$local_IdGrupoVelocidade = "'".$local_IdGrupoVelocidade."'";
			} else{
				$local_IdGrupoVelocidade = "NULL";
			}
		} else{
			$local_IdTecnologia			= "NULL";
			$local_IdDedicado			= "NULL";
			$local_FatorMega			= "NULL";
			$local_IdGrupoVelocidade	= "NULL";
		}
		
		if($local_DiasAvisoAposVencimento == ""){
			$local_DiasAvisoAposVencimento= 'NULL';
		}else{
			$local_DiasAvisoAposVencimento	=	"'$local_DiasAvisoAposVencimento'";
		}
		
		if($local_DiasLimiteBloqueio == ""){	$local_DiasLimiteBloqueio= 'NULL';		}
		
		if($local_ValorInicial != ''){ 	
			$local_ValorInicial		=	str_replace(".", "", $local_ValorInicial);	
			$local_ValorInicial		= 	str_replace(",", ".", $local_ValorInicial);
		}else{
			$local_ValorInicial		=  	'NULL';
		}
		
		if($local_IdTipoPessoa == ""){
			$local_IdTipoPessoa = 'NULL';
		}
		
		if($local_IdFaturamentoFracionado == ""){
			$local_IdFaturamentoFracionado = 'NULL';
		}	
		
		if($local_IdNotaFiscalTipo == 0){	$local_IdNotaFiscalTipo= 'NULL';		}
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		if($local_IdServicoImportar != ''){
		//	$ObsServico = date("d/m/Y H:i:s")." [".$local_Login."] - Importado parâmetros e rotinas do Serviço nº \'".$local_IdServicoImportar."\'\n";
			
			// Importação de Rotinas
			if($local_ImportarRotina == 1){
				$sql3	=	"
						select
							Servico.UrlRotinaBloqueio,
							Servico.UrlRotinaDesbloqueio,
							Servico.UrlRotinaCriacao,
							Servico.UrlRotinaCancelamento,
							Servico.UrlRotinaAlteracao,
							Servico.UrlContratoImpresso,
							Servico.UrlDistratoImpresso,
							Servico.IdOrdemServicoLayout,
							Servico.EmailListaBloqueados
						from
						    Loja,
							Servico
						where
							Servico.IdLoja = $local_IdLoja and
							Servico.IdLoja = Loja.IdLoja and
							Servico.IdServico = $local_IdServicoImportar";
				$res3	=	@mysql_query($sql3,$con);
				$lin3	=	@mysql_fetch_array($res3);
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado rotinas do serviço nº \'".$local_IdServicoImportar."\' - Sim.\n";				
			} else{
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado rotinas do serviço nº \'".$local_IdServicoImportar."\' - Não.\n";
			}
			
			$sql	=	"
					INSERT INTO Servico SET 
						IdLoja							= $local_IdLoja,
						IdServico						= $local_IdServico,
						IdTipoServico					= '$local_IdTipoServico', 
						IdOrdemServicoLayout			= $local_IdOrdemServicoLayout,
						DescricaoServico				= \"$local_DescricaoServico\",
						DescricaoServicoSMS				= \"$local_DescricaoServicoSMS\",
						IdServicoGrupo					= $local_IdServicoGrupo,
						IdCentroCusto					= $local_IdCentroCusto,
						IdPlanoConta					= '$local_IdPlanoConta',
						ContratoViaCDA					= $local_ContratoViaCDA,
						AtivacaoAutomatica				= $local_AtivacaoAutomaticaTemp,
						Unidade							= '$local_Unidade',
						Cor								= $local_Cor,
						IdStatus						= $local_IdStatus,
						DetalheDemonstrativoTerceiro	= \"$local_DetalheDemonstrativoTerceiro\",
						ExibirReferencia				= $local_ExibirReferencia,
						DetalheServico					= \"$local_DetalheServico\",
						EmailCobranca					= '$local_EmailCobrancaTemp',
						ExecutarRotinas					= '$local_ExecutarRotinas',
						UrlRotinaBloqueio				= '$lin3[UrlRotinaBloqueio]',
						UrlRotinaDesbloqueio			= '$lin3[UrlRotinaDesbloqueio]',
						UrlRotinaCriacao				= '$lin3[UrlRotinaCriacao]',
						UrlRotinaCancelamento			= '$lin3[UrlRotinaCancelamento]',
						UrlRotinaAlteracao				= '$lin3[UrlRotinaAlteracao]',
						UrlContratoImpresso				= '$lin3[UrlContratoImpresso]',
						UrlDistratoImpresso				= '$lin3[UrlDistratoImpresso]',
						EmailListaBloqueados			= '$lin3[EmailListaBloqueados]',
						DiasAvisoAposVencimento			= $local_DiasAvisoAposVencimento,
						DiasLimiteBloqueio				= $local_DiasLimiteBloqueio,
						Filtro_IdPaisEstadoCidade		= '$local_Filtro_IdPaisEstadoCidade',
						MsgAuxiliarCobranca				= \"$local_MsgAuxiliarCobranca\",
						Filtro_IdTipoPessoa				= $local_IdTipoPessoa,
						IdNotaFiscalTipo				= $local_IdNotaFiscalTipo,
						IdCategoriaTributaria			= $local_IdCategoriaTributaria,
						FaturamentoFracionado			= $local_IdFaturamentoFracionado,
						Tecnologia						= $local_IdTecnologia,
						Dedicado						= $local_IdDedicado,
						FatorMega						= $local_FatorMega,
						GrupoVelocidade					= $local_IdGrupoVelocidade,
						ColetarSICI						= $local_ColetarSICI,
						Obs								= \"$ObsServico\",
						DataCriacao						= (concat(curdate(),' ',curtime())),
						LoginCriacao					= '$local_Login',
						DataAlteracao					= NULL;";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			$sql3 = "
				SELECT  
					IdServico,	
					IdServicoAgrupador
				FROM
					ServicoAgrupado
				WHERE
					ServicoAgrupado.IdLoja = '$local_IdLoja' AND
					ServicoAgrupado.IdServicoAgrupador = '$local_IdServicoImportar' AND
					ServicoAgrupado.IdServico IN ($local_ServicoVinculado);";
			$res3 = @mysql_query($sql3,$con);
			while($lin3 = @mysql_fetch_array($res3)){
				$sql = "
					INSERT INTO ServicoAgrupado SET 
						IdLoja				= '$local_IdLoja',
						IdServico			= '$lin3[IdServico]',
						IdServicoAgrupador	= '$local_IdServico';";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
			if($local_ImportarAgendamento == 1){
				$sql3 = "
					SELECT
						IdLoja,
						IdServico,
						QtdMes,
						IdStatus,
						IdNovoStatus
					FROM
						ServicoAgendamento
					WHERE
						IdLoja = '$local_IdLoja' AND
						IdServico = '$local_IdServicoImportar';";
				$res3 = @mysql_query($sql3,$con);
				while($lin3 = @mysql_fetch_array($res3)){
					$sql = "
						INSERT INTO ServicoAgendamento SET
							IdLoja			= '$lin3[IdLoja]',
							IdServico		= '$local_IdServico',
							QtdMes			= '$lin3[QtdMes]',
							IdStatus		= '$lin3[IdStatus]',
							IdNovoStatus	= '$lin3[IdNovoStatus]',
							LoginCriacao	= '$local_Login',
							DataCriacao		= (concat(curdate(),' ',curtime())),
							DataAlteracao	=  NULL;";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado agendamento do serviço nº \'".$local_IdServicoImportar."\' - Sim.\n";
			}else{
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado agendamento do serviço nº \'".$local_IdServicoImportar."\' - Não.\n";
			}
							
			if($local_ImportarCFOP == 1){
				$sql3 = "
					SELECT
						CFOP
					FROM
						ServicoCFOP
					WHERE
						IdLoja = '$local_IdLoja' AND
						IdServico = '$local_IdServicoImportar';";
				$res3 = @mysql_query($sql3,$con);
				while($lin3 = @mysql_fetch_array($res3)){
					$sql = "
						INSERT INTO ServicoCFOP SET
							IdLoja			= '$local_IdLoja',
							IdServico		= '$local_IdServico',
							CFOP			= '$lin3[CFOP]';";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado CFOP do serviço nº \'".$local_IdServicoImportar."\' - Sim.\n";
			}else{
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado CFOP do serviço nº \'".$local_IdServicoImportar."\' - Não.\n";
			}
		}else{
			$sql	=	"
				INSERT INTO Servico SET 
					IdLoja							= $local_IdLoja,
					IdServico						= $local_IdServico, 
					IdTipoServico					= $local_IdTipoServico,
					IdOrdemServicoLayout			= $local_IdOrdemServicoLayout, 
					DescricaoServico				= \"$local_DescricaoServico\",
					DescricaoServicoSMS				= \"$local_DescricaoServicoSMS\",
					IdServicoGrupo					= $local_IdServicoGrupo,
					IdCentroCusto					= $local_IdCentroCusto,
					IdPlanoConta					= '$local_IdPlanoConta',
					ContratoViaCDA					= $local_ContratoViaCDA,
					AtivacaoAutomatica				= $local_AtivacaoAutomaticaTemp,
					Unidade							= '$local_Unidade',
					Cor								= $local_Cor,
					IdStatus						= $local_IdStatus,
					ExibirReferencia				= $local_ExibirReferencia,
					DetalheDemonstrativoTerceiro	= \"$local_DetalheDemonstrativoTerceiro\",
					EmailCobranca					= '$local_EmailCobrancaTemp',
					ExecutarRotinas					= '$local_ExecutarRotinas',
					DetalheServico					= \"$local_DetalheServico\",
					DiasAvisoAposVencimento			= $local_DiasAvisoAposVencimento,
					MsgAuxiliarCobranca				= \"$local_MsgAuxiliarCobranca\",
					DiasLimiteBloqueio				= $local_DiasLimiteBloqueio,
					Filtro_IdTipoPessoa				= $local_IdTipoPessoa,
					IdNotaFiscalTipo				= $local_IdNotaFiscalTipo,
					IdCategoriaTributaria			= $local_IdCategoriaTributaria,
					FaturamentoFracionado			= $local_IdFaturamentoFracionado,
					Tecnologia						= $local_IdTecnologia,
					Dedicado						= $local_IdDedicado,
					FatorMega						= $local_FatorMega,
					GrupoVelocidade					= $local_IdGrupoVelocidade,
					DataCriacao						= (concat(curdate(),' ',curtime())),
					LoginCriacao					= '$local_Login',
					DataAlteracao					= NULL;";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		$local_IdContratoTipoVigencia	=	getCodigoInterno(3,46);
		
		if($local_IdContratoTipoVigencia == "") {
			$local_IdContratoTipoVigencia = 'NULL';
		}
		
		if($local_Terceiros != '') {
			$local_Terceiros = explode(',',$local_Terceiros);
			
			for($i = 0; $i < count($local_Terceiros); $i++) {
				list($local_IdTerceiro , $local_ValorRepasseTerceiro, $local_PercentualRepasseTerceiro, $local_PercentualRepasseTerceiroOutros) = explode('_',$local_Terceiros[$i]);
				
				$sql = "INSERT INTO ServicoTerceiro SET 
							IdLoja							= $local_IdLoja,
							IdServico						= $local_IdServico, 
							IdPessoa						= $local_IdTerceiro,
							ValorRepasseTerceiro			= $local_ValorRepasseTerceiro, 
							PercentualRepasseTerceiro		= $local_PercentualRepasseTerceiro, 
							PercentualRepasseTerceiroOutros	= $local_PercentualRepasseTerceiroOutros;";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}
		}
		
		/*
		 * Vincula um grupo device a um servico na tabela ServicoGrupoDevice
		 */
		if($local_GrupoDevice){
			foreach($local_GrupoDevice as $value){
				$sql = "INSERT INTO ServicoGrupoDevice SET
				IdLoja = $local_IdLoja,
				IdServico = $local_IdServico,
				IdGrupoDevice = $value";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}
		}
		
		$sql	=	"
				INSERT INTO ServicoValor SET 
					IdLoja							= $local_IdLoja,
					IdServico						= $local_IdServico,
					Valor							= $local_ValorInicial,
					IdContratoTipoVigencia			= $local_IdContratoTipoVigencia,
					MultaFidelidade					= '0.00',
					DataInicio						= curdate(),
					DescricaoServicoValor			= '".getCodigoInterno(3,17)."',
					DataCriacao						= (concat(curdate(),' ',curtime())),
					LoginCriacao					= '$local_Login';";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		switch($local_IdTipoServico){
			case '1':
				$ii		=	0;
				$temp	=	explode('#',$local_Periodicidade);
				
				while($temp[$ii] != ''){
					$temp2	=	explode('_',$temp[$ii]);
				
					$local_IdPeriodicidade		=	$temp2[0];
					$local_QtdParcela			=	$temp2[1];
					$local_TipoContrato			=	$temp2[2];
					$local_IdLocalCobranca		=	$temp2[3];
					$local_MesFechado			=	$temp2[4];
					$local_QtdMesesFidelidade	=	$temp2[5];
					
					$sql	=	"
						INSERT INTO ServicoPeriodicidade SET 
							IdLoja						= $local_IdLoja,
							IdServico					= $local_IdServico,
							IdPeriodicidade				= $local_IdPeriodicidade,
							QtdParcela					= '$local_QtdParcela',
							TipoContrato				= '$local_TipoContrato',
							IdLocalCobranca				= '$local_IdLocalCobranca',
							MesFechado					= '$local_MesFechado',
							QtdMesesFidelidade			= '$local_QtdMesesFidelidade';";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
					$ii++;
				}
				break;
			case '3':
				$ii		=	0;
				$temp	=	explode('#',$local_ServicoAgrupador);
				
				while($temp[$ii] != ''){
					$local_IdServicoAgrupador	=	$temp[$ii];
				
					$sql	=	"
						INSERT INTO ServicoAgrupado SET 
							IdLoja						= $local_IdLoja,
							IdServico					= $local_IdServico,
							IdServicoAgrupador			= $local_IdServicoAgrupador;";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
					$ii++;
				}
				break;
			case '4':
				$ii		=	0;
				$temp	=	explode('#',$local_ServicoAgrupador);
				
				while($temp[$ii] != ''){
					$local_IdServicoAgrupador	=	$temp[$ii];
				
					$sql	=	"
						INSERT INTO ServicoAgrupado SET 
							IdLoja						= $local_IdLoja,
							IdServico					= $local_IdServico,
							IdServicoAgrupador			= $local_IdServicoAgrupador;";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
					$ii++;
				}
				break;
		}
		
		if($local_IdServicoImportar != ''){
			//Importar Paramtros do Serviço
			if($local_ImportarParametro == 1){
				$sql2	=	"select
								ServicoParametro.DescricaoParametroServico,
								ServicoParametro.ValorDefault,
								ServicoParametro.IdParametroServico,
								ServicoParametro.Obrigatorio,
								ServicoParametro.Editavel,
								ServicoParametro.Obs,
								ServicoParametro.ParametroDemonstrativo,
								ServicoParametro.IdStatus,
								ServicoParametro.Calculavel,
								ServicoParametro.CalculavelOpcoes,
								ServicoParametro.Visivel,
								ServicoParametro.VisivelOS,
								ServicoParametro.VisivelCDA,
								ServicoParametro.Unico,
								ServicoParametro.RotinaCalculo,
								ServicoParametro.RotinaOpcoes,
								ServicoParametro.RotinaOpcoesContrato,
								ServicoParametro.IdTipoParametro,
								ServicoParametro.IdMascaraCampo,
								ServicoParametro.OpcaoValor,
								ServicoParametro.IdTipoTexto,
								ServicoParametro.TamMinimo,
								ServicoParametro.TamMaximo,
								ServicoParametro.DescricaoParametroServicoCDA,
								ServicoParametro.ExibirSenha
							from 
								Servico,
								ServicoParametro
							where
								Servico.IdLoja = $local_IdLoja and
								Servico.IdLoja = ServicoParametro.IdLoja and
								Servico.IdServico = ServicoParametro.IdServico and							
								Servico.IdServico = $local_IdServicoImportar order by IdParametroServico";
				$res2	=	@mysql_query($sql2,$con);
				while($lin2	=	@mysql_fetch_array($res2)){
					
					if($lin2[TamMinimo]=="")		$lin2[TamMinimo] 		= 'NULL';
					if($lin2[TamMaximo]=="")		$lin2[TamMaximo] 		= 'NULL';
					if($lin2[IdMascaraCampo]=="")	$lin2[IdMascaraCampo]	= 'NULL';
					if($lin2[IdTipoTexto]=="")		$lin2[IdTipoTexto]		= 'NULL';
					if($lin2[ExibirSenha]=="")		$lin2[ExibirSenha]		= 'NULL';	
						
					$sql	=	"
						INSERT INTO ServicoParametro SET 
							IdLoja						= $local_IdLoja,
							IdServico					= $local_IdServico,
							IdParametroServico			= '$lin2[IdParametroServico]',
							Editavel					= '$lin2[Editavel]',
							DescricaoParametroServico	= '$lin2[DescricaoParametroServico]', 
							Obrigatorio					= '$lin2[Obrigatorio]',
							ValorDefault				= '$lin2[ValorDefault]',
							Calculavel					= '$lin2[Calculavel]',
							CalculavelOpcoes			= $lin2[CalculavelOpcoes],
							RotinaCalculo				= '$lin2[RotinaCalculo]',
							RotinaOpcoes				= '$lin2[RotinaOpcoes]',
							RotinaOpcoesContrato		= '$lin2[RotinaOpcoesContrato]',
							ParametroDemonstrativo		= '$lin2[ParametroDemonstrativo]',
							VisivelOS					= '$lin2[VisivelOS]',
							Visivel						= '$lin2[Visivel]',
							VisivelCDA					= '$lin2[VisivelCDA]',
							Unico						= '$lin2[Unico]',
							Obs							= '$lin2[Obs]',
							IdStatus					= '$lin2[IdStatus]',
							IdTipoParametro				= '$lin2[IdTipoParametro]',
							IdMascaraCampo				= $lin2[IdMascaraCampo],
							OpcaoValor					= '$lin2[OpcaoValor]',
							IdTipoTexto					= $lin2[IdTipoTexto],
							TamMinimo					= $lin2[TamMinimo],
							TamMaximo					= $lin2[TamMaximo],
							DescricaoParametroServicoCDA= '$lin2[DescricaoParametroServicoCDA]',
							ExibirSenha					= $lin2[ExibirSenha],
							DataCriacao					= (concat(curdate(),' ',curtime())),
							LoginCriacao				= '$local_Login'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado parametros do serviço nº \'".$local_IdServicoImportar."\' - Sim.\n";
			}else{
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado parametros do serviço nº \'".$local_IdServicoImportar."\' - Não.\n";
			}

			if($local_ImportarRotina == 1){
				$sql4	=  "select
								IdLoja,
								IdServico,
								IdServicoMonitor,
								TipoMonitor,
								IdConsulta,
								IdParametroServico,
								FiltroContratoParametro,
								IdSNMP,
								ComandoSSH,
								CodigoSNMP,
								Historico,
								IdFormatoResultado
							from
								ServicoMonitor
							where
								IdLoja = $local_IdLoja and							
								IdServico = $local_IdServicoImportar";
				$res4	=	@mysql_query($sql4,$con);
				while($lin4	=	@mysql_fetch_array($res4)){
					
					if(($local_ImportarParametro != 1 && $lin4[IdParametroServico] == '') || ($local_ImportarParametro == 1 && ($lin4[IdParametroServico] != '' || $lin4[IdParametroServico] == ''))){
						if($lin4[IdParametroServico] == ''){
							$lin4[IdParametroServico] = 'NULL';
						}

						if($lin4[FiltroContratoParametro] == ''){
							$lin4[FiltroContratoParametro] = 'NULL';
						}else{
							$lin4[FiltroContratoParametro] = "'".$lin4[FiltroContratoParametro]."'";
						}

						if($lin4[IdFormatoResultado] == ''){
							$lin4[IdFormatoResultado] = 'NULL';
						}

						if($lin4[IdConsulta] == ''){
							$lin4[IdConsulta] = 'NULL';
						}

						$sql = "INSERT INTO ServicoMonitor SET 
									IdLoja						= $local_IdLoja,
									IdServico					= $local_IdServico,
									IdServicoMonitor			= $lin4[IdServicoMonitor],
									TipoMonitor					= '$lin4[TipoMonitor]',
									IdConsulta					= $lin4[IdConsulta],
									IdParametroServico			= $lin4[IdParametroServico],
									FiltroContratoParametro		= $lin4[FiltroContratoParametro],
									IdSNMP						= $lin4[IdSNMP],
									ComandoSSH					= '$lin4[ComandoSSH]',
									CodigoSNMP					= '$lin4[CodigoSNMP]',
									Historico					= '$lin4[Historico]',
									IdFormatoResultado			= $lin4[IdFormatoResultado]";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);					
						$tr_i++;
					}
				}
			}
			
			//Importar Serviço Mascara de Vigência
			if($local_ImportarMascaraVigencia == 1){
				$sql3	=	"SELECT
								Mes,
								Fator,
								IdTipoDesconto,
								IdContratoTipoVigencia,
								LimiteDesconto,
								FatorRepasseTerceiro,
								VigenciaDefinitiva
							 FROM
							 	ServicoMascaraVigencia
							 WHERE
							 	IdLoja = $local_IdLoja and
							 	IdServico = $local_IdServicoImportar 
							 ORDER BY Mes ASC";
				$res3	=	@mysql_query($sql3,$con);
				while($lin3	=	@mysql_fetch_array($res3)){
					$sql	=	"
						INSERT INTO ServicoMascaraVigencia SET 
							IdLoja						= $local_IdLoja,
							IdServico					= $local_IdServico,
							Mes							= '$lin3[Mes]',
							Fator						= '$lin3[Fator]',
							IdTipoDesconto				= '$lin3[IdTipoDesconto]',
							IdContratoTipoVigencia		= '$lin3[IdContratoTipoVigencia]',
							LimiteDesconto				= '$lin3[LimiteDesconto]',
							FatorRepasseTerceiro		= '$lin3[FatorRepasseTerceiro]',
							VigenciaDefinitiva			= '$lin3[VigenciaDefinitiva]',
							DataCriacao					= (concat(curdate(),' ',curtime())),
							LoginCriacao				= '$local_Login'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado máscara de vigência do serviço nº \'".$local_IdServicoImportar."\' - Sim.\n";
			}else{
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado máscara de vigência do serviço nº \'".$local_IdServicoImportar."\' - Não.\n";
			}
			// Importar Serviço Aliquota
			if($local_ImportarAliquota == 1){
				$sql4	=	"
						SELECT
							ServicoAliquota.IdPais,
							ServicoAliquota.IdEstado,
							ServicoAliquota.IdAliquotaTipo,
							ServicoAliquota.Aliquota,
							ServicoAliquota.FatorBaseCalculoAliquota
						FROM
							ServicoAliquota
						WHERE
							ServicoAliquota.IdLoja = $local_IdLoja and
							ServicoAliquota.IdServico = $local_IdServicoImportar";
				$res4	=	@mysql_query($sql4,$con);
				while($lin4	= @mysql_fetch_array($res4)){
					if($lin4[Aliquota] == "" || $lin4[Aliquota] == NULL || $lin4[Aliquota] == "NULL"){						
						$lin4[Aliquota] = "NULL";
					}else{
						$lin4[Aliquota] = "'".$lin4[Aliquota]."'";
					}
					$sql	=	"
						INSERT INTO ServicoAliquota SET
							IdLoja 							= '$local_IdLoja',
							IdServico						= '$local_IdServico',
							IdPais							= '$lin4[IdPais]',
							IdEstado						= '$lin4[IdEstado]',
							IdAliquotaTipo					= '$lin4[IdAliquotaTipo]',
							Aliquota						= $lin4[Aliquota],
							FatorBaseCalculoAliquota	 	= '$lin4[FatorBaseCalculoAliquota]'
						";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado aliquota do serviço nº \'".$local_IdServicoImportar."\' - Sim.\n";
			}else{
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado aliquota do serviço nº \'".$local_IdServicoImportar."\' - Não.\n";
			}
			
			//Importar Serviço Nota Fiscal Layout Parametro
			if($local_ImportarParametroNF == 1){
				$sql	=	"
						SELECT
							ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayout,
							ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro,
							ServicoNotaFiscalLayoutParametro.Valor					
						FROM					    
							ServicoNotaFiscalLayoutParametro
						WHERE
							ServicoNotaFiscalLayoutParametro.IdLoja = $local_IdLoja and
							ServicoNotaFiscalLayoutParametro.IdServico = $local_IdServicoImportar";
				$res5	=	@mysql_query($sql,$con);
				while($lin5	= @mysql_fetch_array($res5)){
					$sql	=	"
						INSERT INTO ServicoNotaFiscalLayoutParametro SET
							IdLoja 							= $local_IdLoja,
							IdServico						= $local_IdServico,
							IdNotaFiscalLayout 				= $lin5[IdNotaFiscalLayout],
							IdNotaFiscalLayoutParametro		= $lin5[IdNotaFiscalLayoutParametro],
							Valor							= '$lin5[Valor]'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;			
				}	
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado parametro da nota fiscal do serviço nº \'".$local_IdServicoImportar."\' - Sim.\n";
			}else{
				$ObsServico .= date("d/m/Y H:i:s")." [".$local_Login."] - Importado parametro da nota fiscal do serviço nº \'".$local_IdServicoImportar."\' - Não.\n";
			}
			
			$sql	=	"UPDATE Servico SET							
							Obs						= \"$ObsServico\"
						WHERE 
							IdLoja					= $local_IdLoja and
							IdServico				= $local_IdServico";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;				
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			// Muda a ação para Inserir
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$sql = "ROLLBACK;";
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
		
		@mysql_query($sql,$con);
	}
?>
