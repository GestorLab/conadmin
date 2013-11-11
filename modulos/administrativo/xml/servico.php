<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Servico(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdServico	 				= $_GET['IdServico'];
		$IdTipoServico	 			= $_GET['IdTipoServico'];
		$IdServicoGrupo	 			= $_GET['IdServicoGrupo'];
		$DescricaoServico	  		= $_GET['DescricaoServico'];
		$Periodicidade		  		= $_GET['Periodicidade'];
		$IdServicoAlterar			= $_GET['IdServicoAlterar'];
		$IdServicoAgrupador			= $_GET['IdServicoAgrupador'];
		$IdPessoa					= $_GET['IdPessoa'];
		$IdPessoaF					= $_GET['IdPessoaF'];
		$IdContrato					= $_GET['IdContrato'];
		$IdStatus					= $_GET['IdStatus'];
	//$Local						= $_GET['FiltroCidade'];
		$IdNotaFiscalTipo			= $_GET['IdNotaFiscalTipo'];
		$ServicoAutomaticoAtivo		= $_GET['ServicoAutomaticoAtivo'];
		$Filtro_IdPaisEstadoCidade 	= $_GET['Filtro_IdPaisEstadoCidade'];
		
		$where					= "";
		
		if($IdPessoa == "" && $IdPessoaF!=""){
			$IdPessoa	=	$IdPessoaF;
		}
		/*
		switch($Local){
			case "Contrato":
				$WhereTemp = "";
				$sql = "select 
							concat(PessoaEndereco.IdPais,',',PessoaEndereco.IdEstado,',',PessoaEndereco.IdCidade) Filtro_IdPaisEstadoCidades
						from 
							PessoaEndereco
						where
							PessoaEndereco.IdPessoa = $IdPessoa";
				$res = mysql_query($sql,$con);
				
				while($lin = mysql_fetch_array($res)){
					if($WhereTemp == ""){
						$WhereTemp = " and (";
					} else {
						$WhereTemp .= " or ";
					}
					
					$WhereTemp .= "(Servico.Filtro_IdPaisEstadoCidade like '%$lin[Filtro_IdPaisEstadoCidades]' or Servico.Filtro_IdPaisEstadoCidade like '%$lin[Filtro_IdPaisEstadoCidades]^%')";
				}
				
				if($WhereTemp != ""){
					$where .= $WhereTemp.")";
				}
				
				break;
		}
		*/
		if($IdServico != ''){			$where .= " and Servico.IdServico=$IdServico";							}
		if($IdServicoAlterar != ''){	$where .= " and Servico.IdServico != $IdServicoAlterar";				}
		if($DescricaoServico !=''){		$where .= " and Servico.DescricaoServico like '$DescricaoServico%'";	}
		if($Periodicidade != ""){		$where .= " and Servico.Periodicidade = $Periodicidade";				}
		if($IdServicoAgrupador != ""){	$where .= " and Servico.IdServico != $IdServicoAgrupador";				}
		if($IdTipoServico != ''){		$where .= " and Servico.IdTipoServico in ($IdTipoServico)";				}
		if($IdServicoGrupo != ''){		$where .= " and Servico.IdServicoGrupo=$IdServicoGrupo";				}
		if($IdStatus != ''){			$where .= " and Servico.IdStatus=$IdStatus";							}
		if($IdNotaFiscalTipo != ''){	$where .= " and Servico.IdNotaFiscalTipo != ''";						}
		
		if($IdContrato != ""){			
			$sql	=	"select IdServico from Contrato where IdLoja = $IdLoja and IdContrato = $IdContrato";
			$res	=	mysql_query($sql,$con);
			$lin	=	mysql_fetch_array($res);
			
			if($lin[IdServico] != ""){
				$sql2	=	"select	
								ServicoAgrupado.IdServico 
							from	
								ServicoAgrupado,
								Servico
							where 
								ServicoAgrupado.IdLoja = $IdLoja and 
								ServicoAgrupado.IdLoja = Servico.IdLoja and 
								ServicoAgrupado.IdServico = Servico.IdServico and 
								ServicoAgrupado.IdServicoAgrupador = $lin[IdServico] and 
								Servico.IdStatus = 1";
				$res2	=	@mysql_query($sql2,$con);
				while($lin2	=	mysql_fetch_array($res2)){
					if($IdServicoAgrupador	!= ""){
						$IdServicoAgrupador	 .= ',';
					}
					$IdServicoAgrupador	.=	$lin2[IdServico];		
				}
				$where .= " and Servico.IdServico in ($IdServicoAgrupador)";	
			}else{
				$where .= " and Servico.IdServico = 0";				
			}
		}
			
		
		$sql	=	"select
						Servico.IdLoja,
						Servico.IdServico,
						Servico.IdTipoServico,
						Servico.IdOrdemServicoLayout,
						Servico.BaseDataStatusContratoOS,
						Servico.DescricaoServico,
						Servico.DescricaoServicoSMS,
						Servico.IdCentroCusto,
						CentroCusto.DescricaoCentroCusto,
						Servico.IdPlanoConta,
						PlanoConta.DescricaoPlanoConta,
						Servico.ContratoViaCDA,
						Servico.IdServicoGrupo,
						ServicoGrupo.DescricaoServicoGrupo,
						Servico.UrlContratoImpresso,
						Servico.UrlRotinaBloqueio,
						Servico.UrlRotinaDesbloqueio,
						Servico.UrlRotinaCriacao,
						Servico.UrlRotinaCancelamento,
						Servico.UrlRotinaAlteracao,
						Servico.UrlDistratoImpresso,
						Servico.IdStatus,
						Servico.AtivacaoAutomatica,
						Servico.Unidade,						
						Servico.Cor,					
						Servico.DetalheServico,
						Servico.ExibirReferencia,
						Servico.DetalheDemonstrativoTerceiro,
						Servico.EmailCobranca,
						Servico.ExecutarRotinas,
						Servico.DiasLimiteBloqueio,
						Servico.DataCriacao,
						Servico.LoginCriacao,
						Servico.DataAlteracao,
						Servico.LoginAlteracao,
						Periodicidade.DescricaoPeriodicidade,
						ServicoPeriodicidade.QtdParcela,
						Servico.DiasAvisoAposVencimento,
						Servico.Filtro_IdPaisEstadoCidade,
						Servico.MsgAuxiliarCobranca,
						Servico.Filtro_IdTipoPessoa,
						Servico.IdNotaFiscalTipo,
						Servico.MudarStatusContratoConcluirOS,
						Servico.FaturamentoFracionado,
						Servico.IdCategoriaTributaria,
						Servico.Tecnologia,
						Servico.Dedicado,
						Servico.FatorMega,
						Servico.ColetarSICI,
						Servico.GrupoVelocidade,
						Servico.Obs,
						Servico.TermoCienciaCDA
					from
					    Loja,
						Servico LEFT JOIN ServicoPeriodicidade ON (
							ServicoPeriodicidade.IdLoja = $IdLoja and 
							Servico.IdServico = ServicoPeriodicidade.IdServico
						) LEFT JOIN Periodicidade ON (
							ServicoPeriodicidade.IdPeriodicidade = Periodicidade.IdPeriodicidade 
						),
						CentroCusto, 
						ServicoGrupo,
						PlanoConta
					where
						Servico.IdLoja = $IdLoja and
						Servico.IdLoja = Loja.IdLoja and
						Servico.IdLoja = ServicoGrupo.IdLoja and
						Servico.IdServicoGrupo = ServicoGrupo.IdServicoGrupo and
						Servico.IdLoja = CentroCusto.IdLoja and
						Servico.IdCentroCusto = CentroCusto.IdCentroCusto and
						Servico.IdLoja = PlanoConta.IdLoja and
						Servico.IdPlanoConta = PlanoConta.IdPlanoConta $where						
					group by
						Servico.IdServico";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		} else{
			return "false";
		}
		
		$query	=	0;
		$j		=	0;
		
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[IdCategoriaTributaria] == ''){
				$lin[IdCategoriaTributaria] = 0;
			}
			
			$sql4	=	"select 
							Valor,
							MultaFidelidade,
							IdContratoTipoVigencia
						from 
							ServicoValor 
						where 
							DataInicio <= curdate() and 
							(DataTermino is Null  or DataTermino >= curdate()) and 
							IdLoja =$lin[IdLoja] and 
							IdServico = $lin[IdServico] 
						order BY 
							DataInicio DESC 
						LIMIT 0,1"; 
			$res4	=	@mysql_query($sql4,$con);
			$lin4	=	@mysql_fetch_array($res4);
			
			$mostra	=	'true';
			
			if($lin[IdTipoServico] == 3 && $IdPessoa!=''){
				$sql3	=	"select 
								count(IdContrato) QTD 
							from 
								Contrato 
							where 
								Contrato.IdLoja = $IdLoja and 
								Contrato.IdPessoa = $IdPessoa and 
								Contrato.IdServico in 
								(select 
									IdServicoAgrupador 
								from 
									ServicoAgrupado 
								where 
									IdLoja = $IdLoja and 
									IdServico = $lin[IdServico]);";
				$res3	=	@mysql_query($sql3,$con);
				$lin3	=	@mysql_fetch_array($res3);
				
				if($lin3[QTD] == 0){
					$mostra	= "false";
				}
			}
			
			if($IdPessoa!=''){
				if($lin[Filtro_IdPaisEstadoCidade]!=""){
					$mostra	= "false";
					$sql8 = "select 
								IdPais, 
								IdEstado, 
								IdCidade 
							from 
								Pessoa, 
								PessoaEndereco 
							where 
								Pessoa.IdPessoa = $IdPessoa and 
								Pessoa.IdPessoa = PessoaEndereco.IdPessoa";
					$res8 = @mysql_query($sql8, $con);
					
					while($lin8 = @mysql_fetch_array($res8)){
						$cidade = explode('^', $lin["Filtro_IdPaisEstadoCidade"]);
						
						for($i = 0; $i < count($cidade); $i++){
							$temp = explode(',', $cidade[$i]);
							$IdPais = $temp[0];
							$IdEstado = $temp[1];
							$IdCidade = $temp[2];
							
							if($lin8["IdPais"] == $IdPais && $lin8["IdEstado"] == $IdEstado && $lin8["IdCidade"] == $IdCidade){
								$mostra = "true";
								break;
							}
						}
					}
					// Filtro por Endereço do Contrato/Instalação do contrato
					if($Filtro_IdPaisEstadoCidade != ""){
						$cidade = explode('^', $lin["Filtro_IdPaisEstadoCidade"]);
						$Filtro_IdPaisEstadoCidadeAux = explode(',', $Filtro_IdPaisEstadoCidade);
						$lin8["IdPais"] = $Filtro_IdPaisEstadoCidadeAux[0];
						$lin8["IdEstado"] = $Filtro_IdPaisEstadoCidadeAux[1];
						$lin8["IdCidade"] = $Filtro_IdPaisEstadoCidadeAux[2];
						
						for($i = 0; $i < count($cidade); $i++){
							$temp = explode(',', $cidade[$i]);
							$IdPais = $temp[0];
							$IdEstado = $temp[1];
							$IdCidade = $temp[2];
							
							if($lin8["IdPais"] == $IdPais && $lin8["IdEstado"] == $IdEstado && $lin8["IdCidade"] == $IdCidade){
								$mostra = "true";
								break;
							}
						}
					}
				}

				if($lin[Filtro_IdTipoPessoa]!="" && $lin[Filtro_IdTipoPessoa] != 0){
					$sql9	=	"select 
									TipoPessoa 
								from	
									Pessoa								
								where 									
									IdPessoa = $IdPessoa";
									
					$res9	=	@mysql_query($sql9,$con);
					$lin9	=	@mysql_fetch_array($res9);										
					
					if($lin9[TipoPessoa] != $lin[Filtro_IdTipoPessoa]){				
						$mostra	=	"false";
					}
				}
				
				$sql9	=	"select 
								IdContrato 
							from 
								Contrato 
							where 
								Contrato.IdLoja = $IdLoja and 
								Contrato.IdPessoa = $IdPessoa and 
								Contrato.IdServico = $lin[IdServico] and 
								DataTermino is NULL limit 0,1";
				$res9	=	@mysql_query($sql9,$con);
				$lin9	=	@mysql_fetch_array($res9);
				
				if($lin[IdTipoServico] == 2 || $lin[IdTipoServico] == 3){
					$sql10	=	"select 
									OrdemServico.IdOrdemServico
								from 
									OrdemServico
								where 
									OrdemServico.IdLoja = $IdLoja and 
									OrdemServico.IdPessoa = $IdPessoa and 
									OrdemServico.IdServico = $lin[IdServico] and
									(
										OrdemServico.IdStatus = 100 or
										OrdemServico.IdStatus = 300 or
										OrdemServico.IdStatus = 400
									)
								limit 0,1";
					$res10	=	@mysql_query($sql10,$con);
					$lin10	=	@mysql_fetch_array($res10);
				}
			}
			
			$sql5	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 71 and IdParametroSistema = $lin[IdTipoServico]"; 
			$res5	=	@mysql_query($sql5,$con);
			$lin5	=	@mysql_fetch_array($res5);
			
			$sql6 = "select	
						ServicoAgrupado.IdServico, 
						Servico.IdStatus
					from
						ServicoAgrupado,
						Servico 
					where 
						ServicoAgrupado.IdLoja = $IdLoja and 
						ServicoAgrupado.IdLoja = Servico.IdLoja and 
						ServicoAgrupado.IdServico = Servico.IdServico and 
						ServicoAgrupado.IdServicoAgrupador = $lin[IdServico] and 
						Servico.IdTipoServico = 4";
			$res6 = @mysql_query($sql6,$con);
			while($lin6 = @mysql_fetch_array($res6)){
				if($lin[ServicoAutomatico] != ""){
					$lin[ServicoAutomatico] .= "#";
				}
				
				$lin[ServicoAutomatico] .= $lin6[IdServico];
				
				if($lin6[IdStatus] == 1){
					if($lin[ServicoAutomaticoAtivo] != ""){
						$lin[ServicoAutomaticoAtivo] .= "#";
					}
					
					$lin[ServicoAutomaticoAtivo] .= $lin6[IdServico];
				}
			}
			
			$sql7	=	"select max(QtdMesesFidelidade) maxQtdMesesFidelidade from ServicoPeriodicidade where IdLoja = $IdLoja and IdServico = $lin[IdServico]";
			$res7	=	@mysql_query($sql7,$con);
			$lin7	=	@mysql_fetch_array($res7);
			
			$sql8	=	"select count(*) Mes from ServicoMascaraVigencia where IdLoja = $IdLoja and IdServico = $lin[IdServico]";
			$res8	=	@mysql_query($sql8,$con);
			$lin8	=	@mysql_fetch_array($res8);
			
			if($mostra	==	"true"){
				$query++;
				$j++;
			
				$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
				$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";
				$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
				$dados	.=	"\n<DescricaoServicoSMS><![CDATA[$lin[DescricaoServicoSMS]]]></DescricaoServicoSMS>";
				$dados	.=	"\n<IdTipoServico><![CDATA[$lin[IdTipoServico]]]></IdTipoServico>";
				$dados	.=	"\n<DescTipoServico><![CDATA[$lin5[ValorParametroSistema]]]></DescTipoServico>";
				$dados	.=	"\n<Filtro_IdPaisEstadoCidade><![CDATA[$lin[Filtro_IdPaisEstadoCidade]]]></Filtro_IdPaisEstadoCidade>";
				$dados	.=	"\n<IdServicoGrupo><![CDATA[$lin[IdServicoGrupo]]]></IdServicoGrupo>";
				$dados	.=	"\n<AtivacaoAutomatica><![CDATA[$lin[AtivacaoAutomatica]]]></AtivacaoAutomatica>";
				$dados	.=	"\n<BaseDataStatusContratoOS><![CDATA[$lin[BaseDataStatusContratoOS]]]></BaseDataStatusContratoOS>";
				$dados	.=	"\n<Unidade><![CDATA[$lin[Unidade]]]></Unidade>";
				$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
				$dados	.=	"\n<DescricaoServicoGrupo><![CDATA[$lin[DescricaoServicoGrupo]]]></DescricaoServicoGrupo>";
				$dados	.=	"\n<IdCentroCusto>$lin[IdCentroCusto]</IdCentroCusto>";
				$dados	.=	"\n<DescricaoCentroCusto><![CDATA[$lin[DescricaoCentroCusto]]]></DescricaoCentroCusto>";
				$dados	.=	"\n<IdPlanoConta>$lin[IdPlanoConta]</IdPlanoConta>";
				$dados	.=	"\n<EmailCobranca><![CDATA[$lin[EmailCobranca]]]></EmailCobranca>";
				$dados	.=	"\n<CFOP><![CDATA[$lin[CFOP]]]></CFOP>";
				$dados	.=	"\n<ExecutarRotinas><![CDATA[$lin[ExecutarRotinas]]]></ExecutarRotinas>";
				$dados	.=	"\n<DescricaoPlanoConta><![CDATA[$lin[DescricaoPlanoConta]]]></DescricaoPlanoConta>";
				$dados	.=	"\n<ContratoViaCDA>$lin[ContratoViaCDA]</ContratoViaCDA>";
				$dados	.=	"\n<UrlContratoImpresso><![CDATA[$lin[UrlContratoImpresso]]]></UrlContratoImpresso>";
				$dados	.=	"\n<UrlRotinaBloqueio><![CDATA[$lin[UrlRotinaBloqueio]]]></UrlRotinaBloqueio>";
				$dados	.=	"\n<UrlRotinaDesbloqueio><![CDATA[$lin[UrlRotinaDesbloqueio]]]></UrlRotinaDesbloqueio>";
				$dados	.=	"\n<UrlRotinaCriacao><![CDATA[$lin[UrlRotinaCriacao]]]></UrlRotinaCriacao>";
				$dados	.=	"\n<UrlRotinaCancelamento><![CDATA[$lin[UrlRotinaCancelamento]]]></UrlRotinaCancelamento>";
				$dados	.=	"\n<UrlRotinaAlteracao><![CDATA[$lin[UrlRotinaAlteracao]]]></UrlRotinaAlteracao>";
				$dados	.=	"\n<UrlDistratoImpresso><![CDATA[$lin[UrlDistratoImpresso]]]></UrlDistratoImpresso>";
				$dados	.=	"\n<IdServicoMonitor><![CDATA[$lin[IdServicoMonitor]]]></IdServicoMonitor>";
				$dados	.=	"\n<IdTipoMonitor><![CDATA[$lin[IdTipoMonitor]]]></IdTipoMonitor>";
				$dados	.=	"\n<TipoMonitor><![CDATA[$lin[TipoMonitor]]]></TipoMonitor>";
				$dados	.=	"\n<IdSNMP><![CDATA[$lin[IdSNMP]]]></IdSNMP>";
				$dados	.=	"\n<CodigoSNMP><![CDATA[$lin[CodigoSNMP]]]></CodigoSNMP>";
				$dados	.=	"\n<ComandoSSH><![CDATA[$lin[ComandoSSH]]]></ComandoSSH>";
				$dados	.=	"\n<Historico><![CDATA[$lin[Historico]]]></Historico>";
				$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados	.=	"\n<DetalheDemonstrativoTerceiro><![CDATA[$lin[DetalheDemonstrativoTerceiro]]]></DetalheDemonstrativoTerceiro>";
				$dados	.=	"\n<DiasLimiteBloqueio><![CDATA[$lin[DiasLimiteBloqueio]]]></DiasLimiteBloqueio>";
				$dados	.=	"\n<DetalheServico><![CDATA[$lin[DetalheServico]]]></DetalheServico>";
				$dados	.=	"\n<ExibirReferencia><![CDATA[$lin[ExibirReferencia]]]></ExibirReferencia>";
				$dados	.=	"\n<Valor><![CDATA[$lin4[Valor]]]></Valor>";
				$dados	.=	"\n<DescPeriodicidade><![CDATA[$lin[DescricaoPeriodicidade]]]></DescPeriodicidade>";
				$dados	.=	"\n<QtdParcela><![CDATA[$lin[QtdParcela]]]></QtdParcela>";
				$dados	.=	"\n<MultaFidelidade><![CDATA[$lin4[MultaFidelidade]]]></MultaFidelidade>";
				$dados	.=	"\n<IdContratoTipoVigencia><![CDATA[$lin4[IdContratoTipoVigencia]]]></IdContratoTipoVigencia>";
				$dados	.=	"\n<ServicoAutomatico><![CDATA[$lin[ServicoAutomatico]]]></ServicoAutomatico>";
				$dados	.=	"\n<ServicoAutomaticoAtivo><![CDATA[$lin[ServicoAutomaticoAtivo]]]></ServicoAutomaticoAtivo>";
				$dados	.=	"\n<maxQtdMesesFidelidade><![CDATA[$lin7[maxQtdMesesFidelidade]]]></maxQtdMesesFidelidade>";
				$dados	.=	"\n<DiasAvisoAposVencimento><![CDATA[$lin[DiasAvisoAposVencimento]]]></DiasAvisoAposVencimento>";
				$dados	.=	"\n<MsgAuxiliarCobranca><![CDATA[$lin[MsgAuxiliarCobranca]]]></MsgAuxiliarCobranca>";
				$dados	.=	"\n<Mes><![CDATA[$lin8[Mes]]]></Mes>";
				$dados	.=	"\n<IdContrato><![CDATA[$lin9[IdContrato]]]></IdContrato>";
				$dados	.=	"\n<IdOrdemServico><![CDATA[$lin10[IdOrdemServico]]]></IdOrdemServico>";
				$dados	.=	"\n<IdOrdemServicoLayout><![CDATA[$lin[IdOrdemServicoLayout]]]></IdOrdemServicoLayout>";
				$dados	.=	"\n<IdTipoPessoa><![CDATA[$lin[Filtro_IdTipoPessoa]]]></IdTipoPessoa>";
				$dados	.=	"\n<IdNotaFiscalTipo><![CDATA[$lin[IdNotaFiscalTipo]]]></IdNotaFiscalTipo>";
				$dados	.=	"\n<IdCategoriaTributaria><![CDATA[$lin[IdCategoriaTributaria]]]></IdCategoriaTributaria>";
				$dados	.=	"\n<IdFaturamentoFracionado><![CDATA[$lin[FaturamentoFracionado]]]></IdFaturamentoFracionado>";
				$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
				$dados	.=	"\n<MudarStatusContratoConcluirOS><![CDATA[$lin[MudarStatusContratoConcluirOS]]]></MudarStatusContratoConcluirOS>";
				$dados	.=	"\n<IdTecnologia><![CDATA[$lin[Tecnologia]]]></IdTecnologia>";
				$dados	.=	"\n<IdDedicado><![CDATA[$lin[Dedicado]]]></IdDedicado>";
				$dados	.=	"\n<FatorMega><![CDATA[$lin[FatorMega]]]></FatorMega>";
				$dados	.=	"\n<IdGrupoVelocidade><![CDATA[$lin[GrupoVelocidade]]]></IdGrupoVelocidade>";
				$dados	.=	"\n<TermoCienciaCDA><![CDATA[$lin[TermoCienciaCDA]]]></TermoCienciaCDA>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<ColetarSICI><![CDATA[$lin[ColetarSICI]]]></ColetarSICI>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$NotaFiscalCDADefault = getCodigoInterno(3,110);				
				$dados	.=	"\n <NotaFiscalCDADefault><![CDATA[$NotaFiscalCDADefault]]></NotaFiscalCDADefault>";
				
				$sql13 = "select
						      IdParametroSistema,					     
						      ValorParametroSistema						
						  from 
						      ParametroSistema					     
						  where
						      IdGrupoParametroSistema = 133
						  order by
						  	  ValorParametroSistema";
				$res13 	= @mysql_query($sql13,$con);
				while($lin13  = 	@mysql_fetch_array($res13)){
					$dados	.=	"\n<NotaFiscalCDAOpc>";	
					$dados	.=	"\n <IdNotaFiscalCDAOpc>$lin13[IdParametroSistema]</IdNotaFiscalCDAOpc>";
					$dados	.=	"\n <DescricaoNotaFiscalCDAOpc><![CDATA[$lin13[ValorParametroSistema]]]></DescricaoNotaFiscalCDAOpc>";				
					$dados	.=	"\n</NotaFiscalCDAOpc>";	
				}
				
				$dados	.=	"\n<Terceiro>";	
				$sql13 = "select
								ServicoTerceiro.ValorRepasseTerceiro,
								ServicoTerceiro.PercentualRepasseTerceiro,
								ServicoTerceiro.PercentualRepasseTerceiroOutros,
								Terceiro.IdPessoa,
								Pessoa.Nome
							from 
								ServicoTerceiro,
								Terceiro,
								Pessoa
							where
								ServicoTerceiro.IdLoja = $lin[IdLoja] and
								ServicoTerceiro.IdServico = $lin[IdServico] and
								ServicoTerceiro.IdLoja = Terceiro.IdLoja and
								ServicoTerceiro.IdPessoa = Terceiro.IdPessoa and
								Terceiro.IdPessoa = Pessoa.IdPessoa;";
				$res13 = @mysql_query($sql13,$con);
				
				while($lin13 = @mysql_fetch_array($res13)){
					$dados	.=	"\n <IdTerceiro>$lin13[IdPessoa]</IdTerceiro>";	
					$dados	.=	"\n <NomeTerceiro>$lin13[Nome]</NomeTerceiro>";	
					$dados	.=	"\n <ValorRepasseTerceiro>$lin13[ValorRepasseTerceiro]</ValorRepasseTerceiro>";	
					$dados	.=	"\n <PercentualRepasseTerceiro>$lin13[PercentualRepasseTerceiro]</PercentualRepasseTerceiro>";	
					$dados	.=	"\n <PercentualRepasseTerceiroOutros>$lin13[PercentualRepasseTerceiroOutros]</PercentualRepasseTerceiroOutros>";	
				}
				$dados	.=	"\n</Terceiro>";
				
				if($Limit != "" && $j > $Limit){
					break;
				}
			}
		}
		if(mysql_num_rows($res) >=1 && $query > 0){
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_Servico();
?>
