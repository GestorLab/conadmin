	function janela_busca_servico(IdServico,Local){	
		if(document.formulario.Local.value == 'Contrato'){	
			if(document.formulario.IdContrato.value != ''){
				return false;
			}else{
				if(document.formulario.IdPessoa.value == '' && document.formulario.IdPessoaF.value == ''){
					document.formulario.IdPessoa.focus();
					mensagens(104);	
					return false;
				}
				janelas('busca_servico.php?Local='+document.formulario.Local.value,530,283,250,100,'');
			}
		}else{
			if(IdServico == undefined)	IdServico = '';
			if(Local == undefined)		Local 	  = '';
			if(Local == 'OrdemServico'){
				if(document.formulario.IdPessoa.value == ''){
					return false;
				}else{
					janelas('busca_servico.php?Local='+Local,530,283,250,100,'');
				}
			}else{
				janelas('busca_servico.php?IdServico='+IdServico+'&Local='+Local,530,283,250,100,'');
			}
		}
	}
	function busca_servico(IdServico,Erro,Local,ListarCampo) {
		if(IdServico == '' || IdServico == undefined) {
			IdServico = 0;
		}
		
		if(Local == '' || Local == undefined) {
			Local = document.formulario.Local.value;
		}
		
		if(ListarCampo == undefined) {
			ListarCampo = "";
		}
		
		if(document.formulario.Local.value == 'Contrato' && document.formulario.IdPessoa.value == '' && document.formulario.IdPessoaF.value == '' && document.formulario.IdServico.value != '') {
			document.formulario.IdServico.value = "";
			document.formulario.IdPessoa.focus();
			mensagens(104);
			return false;
		}

		var url = "xml/servico.php?IdServico="+IdServico;
		
		if(document.formulario.IdServicoAnterior != undefined) {
			url += "&IdServicoAlterar="+document.formulario.IdServicoAnterior.value;
		}
		
		if(Local == 'ServicoAgrupador') {
			if(document.formulario.IdServico.value != '') {
				url += "&IdServicoAgrupador="+document.formulario.IdServico.value;
			}
			
			if(document.formulario.IdTipoServico.value == 3 || document.formulario.IdTipoServico.value == 4) {
				url += "&IdTipoServico=1";
			}
		}
		
		if((document.formulario.Local.value == 'Contrato' && document.formulario.Acao.value == 'inserir') || document.formulario.Local.value == 'ContratoServico') {
			var IdPessoa = document.formulario.IdPessoa.value;
			
			if(IdPessoa == "") {
				IdPessoa = document.formulario.IdPessoaF.value;
			}
			
			url += "&IdPessoa="+IdPessoa+"&IdTipoServico=1&IdStatus=1";
		}
		
		if(Local == 'OrdemServico' && document.formulario.Acao.value == 'inserir') {
			if(document.formulario.IdContrato.value != "") {
				url += "&IdTipoServico=3"; /*Agrupado*/
				url += "&IdContrato="+document.formulario.IdContrato.value;
			} else {
				url += "&IdTipoServico=2";	/*Eventual*/
			}
			
			IdPessoa = document.formulario.IdPessoa.value;
			
			if(IdPessoa == "") {
				IdPessoa = document.formulario.IdPessoaF.value;
			}
			
			url += "&IdPessoa="+IdPessoa;
		}	
		
		if(Local == "ServicoCFOP"){
			if(document.formulario.IdTipoServico.value == 1){
				url += "&IdTipoServico=1";
			}
			if(document.formulario.IdTipoServico.value == 4){
				url += "&IdTipoServico=4";
			}
		}
		if(Local == "Contrato"){
			if(document.formulario.Filtro_IdPaisEstadoCidade.value != ""){
				url += "&Filtro_IdPaisEstadoCidade="+document.formulario.Filtro_IdPaisEstadoCidade.value;
			}			
		}
		call_ajax(url, function (xmlhttp) {
			if(Erro != false) {
				document.formulario.Erro.value = 0;
				verificaErro();
			}

			if(xmlhttp.responseText == 'false') {
				switch(Local) {
					case "Contrato": 
						document.formulario.IdServico.value						= "";
						document.formulario.DescricaoServico.value		 		= "";
						document.formulario.DescricaoServicoGrupo.value		 	= "";
						document.formulario.ValorServico.value					= "";
						document.formulario.ValorDesconto.value					= "";
						document.formulario.ValorFinal.value					= "";
						document.formulario.ValorPeriodicidade.value			= "";
						document.formulario.ValorPeriodicidadeTerceiro.value	= "";
						document.formulario.ValorRepasseTerceiro.value			= "";
						document.formulario.MultaFidelidade.value				= "";
						document.formulario.ValorMultaFidelidade.value			= "";
						document.formulario.QtdMesesFidelidade.value	 		= "";
						document.formulario.QtdMesesFidelidadeTemp.value		= "";
						document.formulario.ServicoAutomatico.value				= "";
						document.formulario.Periodicidade.value					= "";
						document.formulario.QuantParcela.value					= "";
						document.formulario.TipoContratoTemp.value				= "";
						document.formulario.IdLocalCobrancaTemp.value			= "";
						document.formulario.MesFechadoTemp.value				= "";
						document.formulario.UrlContratoImpresso.value			= "";
						document.formulario.UrlDistratoImpresso.value			= "";
						document.formulario.IdContratoTipoVigencia.value		= "";
						document.formulario.NotaFiscalCDA.value		 			= "";
						document.formulario.IdAgenteAutorizado.value		 	= "";
						
						while(document.getElementById('tabelaParametro').rows.length > 0) {
							document.getElementById('tabelaParametro').deleteRow(0);
						}
						
						while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0) {
							document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
						}
						
						while(document.formulario.IdPeriodicidade.options.length > 0) {
							document.formulario.IdPeriodicidade.options[0] = null;
						}
						
						while(document.formulario.QtdParcela.options.length > 0) {
							document.formulario.QtdParcela.options[0] = null;
						}
						
						while(document.formulario.TipoContrato.options.length > 0) {
							document.formulario.TipoContrato.options[0] = null;
						}
						
						while(document.formulario.IdLocalCobranca.options.length > 0) {
							document.formulario.IdLocalCobranca.options[0] = null;
						}
						
						while(document.formulario.MesFechado.options.length > 0) {
							document.formulario.MesFechado.options[0] = null;
						}
						
						document.getElementById('cpQtdParcela').style.color					= '#C10000';
						document.getElementById('cpTipoContrato').style.color				= '#C10000';
						document.getElementById('cpLocalCobranca').style.color				= '#C10000';
						document.getElementById('cpMesFechado').style.color					= '#C10000';
						document.getElementById('cp_automatico').style.display				= 'none';
						document.getElementById('cp_parametrosServico').style.display		= 'none';
						document.getElementById('cp_parametrosLocalCobranca').style.display	= 'none';
						document.getElementById('cpNotaFiscalCDA').style.display			= 'none';
						document.getElementById('cp_automatico').innerHTML					= "";
						
						listar_terceiros();
						busca_cfop_servico();						
						listar_carteira();
						
						if(document.formulario.Acao.value == 'inserir' && (document.formulario.IdPessoa.value != "" || document.formulario.IdPessoaF.value != "")) {
							document.formulario.IdServico.focus();
						}
						break;
					case "ContratoServico": 
						document.formulario.IdServico.value					= "";
						document.formulario.DescricaoServico.value			= "";
						document.formulario.IdContratoTipoVigencia.value	= "";
						document.formulario.ValorServico.value				= '';
						document.formulario.ValorPeriodicidade.value		= '';
						document.formulario.ValorRepasseTerceiro.value		= '';
						document.formulario.MultaFidelidade.value			= "";
						document.formulario.ValorMultaFidelidade.value		= "";
						document.formulario.IdCarteira.value				= "";
						document.formulario.IdAgenteAutorizado.value		= "";
						
						while(document.getElementById('tabelaParametro').rows.length > 0) {
							document.getElementById('tabelaParametro').deleteRow(0);
						}
						
						while(document.formulario.IdPeriodicidade.options.length > 0) {
							document.formulario.IdPeriodicidade.options[0] = null;
						}
						
						while(document.formulario.QtdParcela.options.length > 0) {
							document.formulario.QtdParcela.options[0] = null;
						}
						
						while(document.formulario.TipoContrato.options.length > 0) {
							document.formulario.TipoContrato.options[0] = null;
						}
						
						while(document.formulario.IdLocalCobranca.options.length > 0) {
							document.formulario.IdLocalCobranca.options[0] = null;
						}
						
						while(document.formulario.MesFechado.options.length > 0) {
							document.formulario.MesFechado.options[0] = null;
						}
						
						document.getElementById('cpQtdParcela').style.color				= '#C10000';
						document.getElementById('cpTipoContrato').style.color			= '#C10000';
						document.getElementById('cpLocalCobranca').style.color			= '#C10000';
						document.getElementById('cpMesFechado').style.color				= '#C10000';
						document.getElementById('cp_automatico').style.display			= 'none';
						document.getElementById('cp_automatico').innerHTML				= "";
						document.getElementById('cp_parametrosServico').style.display	= 'none';
						document.getElementById('cpNotaFiscalCDA').style.display		= 'none';
						
						document.getElementById("cp_credito").style.display = "none";
						
						listar_terceiros();
						listar_carteira();
						limpaFormCredito();
						busca_cfop_servico();
						
						document.formulario.IdServico.focus();
						verificaAcao();
						break;
					case "Servico":
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						addParmUrl("marServicoCFOP","IdServico","");
						
						document.formulario.IdServico.value							= "";
						document.formulario.DescricaoServico.value					= "";
						document.formulario.IdServicoGrupo.value					= "";
						document.formulario.IdOrdemServicoLayout[0].selected 		= true;
						document.formulario.IdTipoServico[0].selected				= true;
						document.formulario.DescricaoServicoGrupo.value				= "";
						document.formulario.IdPlanoConta.value						= "";
						document.formulario.DescricaoPlanoConta.value				= "";
						document.formulario.IdCentroCusto.value						= "";
						document.formulario.DescricaoCentroCusto.value				= "";
						document.formulario.Unidade.value							= "";
						document.formulario.Cor.value		 						= "";
						document.formulario.ExibirReferencia.value					= "";
						document.formulario.Periodicidade.value						= "";
						document.formulario.IdPeriodicidade.value					= "";
						document.formulario.QtdParcelaMaximo.value					= "";
						document.formulario.AtivacaoAutomatica.value				= "";
						document.formulario.AtivacaoAutomaticaTemp.value			= "";
						document.formulario.DetalheDemonstrativoTerceiro.value		= "";
						document.formulario.IdLocalCobranca[0].selected				= true;
						document.formulario.TipoContrato[0].selected				= true;
						document.formulario.MesFechado[0].selected					= true;
						document.formulario.EmailCobranca[0].selected				= true;
						document.formulario.ExecutarRotinas[0].selected				= true;
						document.formulario.EmailCobrancaTemp.value					= "";
						document.formulario.IdServicoAgrupador.value				= "";
						document.formulario.DescricaoServicoAgrupador.value			= "";
						document.formulario.DiasLimiteBloqueio.value				= "";
						document.formulario.QtdMesesFidelidade.value				= "";
						document.formulario.ValorRepasseTerceiro.value				= "";
						document.formulario.PercentualRepasseTerceiro.value			= '';
						document.formulario.PercentualRepasseTerceiroOutros.value	= '';
						document.formulario.DetalheServico.value					= "";
						document.formulario.DataCriacao.value						= "";
						document.formulario.LoginCriacao.value						= "";
						document.formulario.DataAlteracao.value						= "";
						document.formulario.LoginAlteracao.value					= "";
						document.formulario.QuantPeriodicidade.value				= "";
						document.formulario.ValorInicial.value						= "";
						document.formulario.ServicoAgrupador.value					= "";
						document.formulario.DiasAvisoAposVencimento.value			= "";
						document.formulario.ValorInicial.readOnly					= false;
						document.formulario.Filtro_IdPaisEstadoCidade.value 		= "";
						document.formulario.MsgAuxiliarCobranca.value				= "";
						document.formulario.IdTipoPessoa[0].selected				= true;
						document.formulario.IdNotaFiscalTipo[0].selected			= true;
						document.formulario.IdFaturamentoFracionado.value			= "";
						document.formulario.IdGrupoVelocidade.value					= "";
						document.formulario.FatorMega.value							= "";
						document.formulario.IdTecnologia.value						= "";
						document.formulario.IdDedicado.value						= "";
						document.formulario.HistoricoObs.value						= "";
						document.formulario.DescricaoServicoSMS.value				= "";
						document.formulario.ColetarSICI.value						= "";
						
						document.getElementById("cpHistorico").style.display = "none";
						listar_servico_vinculado('', false);
						status_inicial();
						mudarNotaFicalTipo();
						adicionar_terceiro(true);
						
						while(document.getElementById('tabelaPeriodicidade').rows.length > 2) {
							document.getElementById('tabelaPeriodicidade').deleteRow(1);
						}
						
						while(document.getElementById('tabelaServico').rows.length > 2) {
							document.getElementById('tabelaServico').deleteRow(1);
						}
						
						while(document.getElementById('tabelaCidade').rows.length > 2) {
							document.getElementById('tabelaCidade').deleteRow(1);
						}
						
						while(document.formulario.QtdParcelaMaximo.options.length > 0) {
							document.formulario.QtdParcelaMaximo.options[0] = null;
						}
						
						document.getElementById('cpDadosServicoImportacaoParametros').style.display	= 'block';
						document.getElementById('cpValorInicial').style.color						= '#C10000';
						document.getElementById('cpValorInicialMoeda').style.color					= '#C10000';
						document.getElementById('cpValorRepasseMensal').style.color					= '#000';
						document.getElementById('cpPercRepasseMensal').style.color					= '#000';
						document.getElementById('totaltabelaPeriodicidade').innerHTML				= 'Total: 0';
						document.getElementById('totaltabelaServico').innerHTML						= 'Total: 0';
						document.getElementById('cpPeriodicidade').style.display					= 'none';
						document.getElementById('cpAgrupado').style.display							= 'none';
						document.getElementById('totaltabelaCidade').innerHTML						= "Total: 0";
						document.getElementById('cpValorInicial').innerHTML							= 'Valor Inicial Mensal';
						document.formulario.IdServicoImportar.value									= "";
						document.formulario.DescricaoServicoImportar.value							= "";
						document.formulario.Acao.value 												= 'inserir';
						
						document.formulario.IdServico.focus();
						verificaAcao();
						break;
					case "ServicoValor": 
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoValorNovo","DataInicio","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						addParmUrl("marServicoCFOP","IdServico","");
						
						document.getElementById('tabelaValorValor').innerHTML	= "0,00";
						document.getElementById('tabelaValorRep').innerHTML		= "0,00";
						document.getElementById('tabelaValorTotal').innerHTML	= "Total: 0";
						
						while(document.getElementById('tabelaValor').rows.length > 2) {
							document.getElementById('tabelaValor').deleteRow(1);
						}
						
						document.formulario.DataInicio.value						= "";
						document.formulario.DataTermino.value						= "";
						document.formulario.IdServico.value							= "";
						document.formulario.DescricaoServico.value					= "";
						document.formulario.IdTipoServico.value						= "";
						document.formulario.IdTipoServicoAux.value					= "";
						document.formulario.DataTermino.value						= "";
						document.formulario.DescricaoServicoValor.value				= "";
						document.formulario.Valor.value								= "";
						document.formulario.ValorRepasseTerceiro.value				= "";
						document.formulario.DataCriacao.value						= "";
						document.formulario.LoginCriacao.value						= "";
						document.formulario.DataAlteracao.value						= "";
						document.formulario.LoginAlteracao.value					= "";
						document.formulario.maxQtdMesesFidelidade.value				= "";
						document.formulario.IdPessoa.value							= "";
						document.formulario.Nome.value								= "";
						document.formulario.PercentualRepasseTerceiro.value			= "";
						document.formulario.PercentualRepasseTerceiroOutros.value	= "";
						document.formulario.MultaFidelidade.value					= "0,00";
						document.formulario.IdContratoTipoVigencia[0].selected		= true;
						document.formulario.Acao.value								= "inserir";
						
						status_inicial();
						verificaAcao();
						
						document.getElementById('tabelahelpText2').style.display = 'none';
						
						document.formulario.IdServico.focus();
						break;
					case "ServicoParametro":
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						addParmUrl("marServicoCFOP","IdServico","");
						
						document.getElementById('tabelaParametroTotal').innerHTML = "Total: 0";
						
						while(document.getElementById('tabelaParametro').rows.length > 2) {
							document.getElementById('tabelaParametro').deleteRow(1);
						}
						
						document.formulario.IdServico.value						= "";
						document.formulario.DescricaoServico.value				= "";
						document.formulario.IdTipoServico.value					= "";
						document.formulario.IdParametroServico.value			= "";
						document.formulario.DescricaoParametroServico.value		= "";
						document.formulario.Obs.value							= "";
						//document.formulario.ValorDefaultInput.value				= "";
						document.formulario.IdStatusParametro[0].selected 		= true;
						document.formulario.RotinaCalculo.value					= "";
						document.formulario.IdTipoParametro.value				= "";
						document.formulario.IdMascaraCampo.value				= "";
						document.formulario.OpcaoValor.value					= "";
						document.formulario.DataCriacao.value					= "";
						document.formulario.LoginCriacao.value					= "";
						document.formulario.DataAlteracao.value					= "";
						document.formulario.LoginAlteracao.value				= "";
						document.formulario.IdGrupoUsuarioInput.value			= "";
						document.formulario.IdGrupoUsuarioSelect.value			= "";
						document.formulario.DescricaoGrupoUsuarioInput.value	= "";
						document.formulario.DescricaoGrupoUsuarioSelect.value	= "";
						
						addParmUrl("marServicoParametroNovo","IdParametroServico","");
						
						while(document.formulario.ValorDefaultSelect.options.length > 0) {
							document.formulario.ValorDefaultSelect.options[0] = null;
						}
						
						busca_grupos_usuario();
						verificaTipoParametro();
						status_inicial();
						
						document.getElementById('tabelahelpText2').style.display	= 'none';
						document.getElementById('cpRotinaCalculo').style.display	= 'none';
						document.formulario.Acao.value								= 'inserir';
						
						document.formulario.IdServico.focus();
						mensagens(0);
						break;
					case "ServicoRotina":
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						addParmUrl("marServicoCFOP","IdServico","");
						
						document.formulario.IdServico.value					= "";
						document.formulario.DescricaoServico.value			= "";
						document.formulario.IdTipoServico.value				= "";
						document.formulario.UrlRotinaBloqueio.value			= "";
						document.formulario.UrlContratoImpresso.value		= "";
						document.formulario.UrlRotinaDesbloqueio.value		= "";
						document.formulario.UrlRotinaCriacao.value			= "";
						document.formulario.UrlRotinaCancelamento.value		= "";
						document.formulario.UrlRotinaAlteracao.value		= "";
						document.formulario.UrlDistratoImpresso.value		= "";
						document.formulario.DataCriacao.value				= "";
						document.formulario.LoginCriacao.value				= "";
						document.formulario.DataAlteracao.value				= "";
						document.formulario.LoginAlteracao.value			= "";
						document.formulario.UrlRotinaBloqueio.disabled		= false;
						document.formulario.UrlContratoImpresso.disabled	= false;
						document.formulario.UrlRotinaDesbloqueio.disabled	= false;
						document.formulario.UrlRotinaCriacao.disabled		= false;
						document.formulario.UrlRotinaCancelamento.disabled	= false;
						document.formulario.UrlRotinaAlteracao.disabled		= false;
						document.formulario.UrlDistratoImpresso.disabled	= false;
						
						document.getElementById('LinkUrlContrato').innerHTML = "<img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'>";
						document.getElementById('LinkUrlDistratoImpresso').innerHTML = "<img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'>";
						document.formulario.IdServico.focus();
						
						adicionar_monitor(0);
						mensagens(0);
						verificaAcao();
						break;
					case "OrdemServico": 
						while(document.getElementById('tabelaParametro').rows.length > 1) {
							document.getElementById('tabelaParametro').deleteRow(0);
						}
						
//						if(document.getElementById('tabelaParametroContrato').style.display == 'none' || document.getElementById('tabelaParametroContrato').style.display == '') {
							document.getElementById('cp_parametrosServico').style.display = 'none';
//						}
						
						document.formulario.IdServico.value				= "";
						document.formulario.DescricaoServico.value		= "";
						document.formulario.DescricaoServicoGrupo.value	= "";
						document.formulario.IdTipoServico.value			= "";
						document.formulario.DetalheServico.value		= "";
						document.formulario.IdOrdemServicoLayout.value	= "";
						document.formulario.ValorOutros.value			= "0,00";
						document.formulario.Justificativa.value			= "";
						document.formulario.DescricaoOS.value			= "";
						document.formulario.Valor.value					= "0,00";
						
						calcula_total();
						
						if(document.formulario.IdTipoOrdemServico.value != 2 && document.formulario.Acao.value == 'inserir') {
							document.formulario.IdServico.focus();
						}
						break;
					case "OrdemServicoFatura": 
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						document.formulario.DetalheServico.value	= "";
						
						if(document.formulario.IdTipoOrdemServico.value != 2 && document.formulario.Acao.value == 'inserir') {
							document.formulario.IdServico.focus();
						}
						break;
					case "ServicoImportar":
						document.formulario.IdServicoImportar.value			= "";
						document.formulario.DescricaoServicoImportar.value	= "";
						
						listar_servico_vinculado('', false);
						document.formulario.IdServicoImportar.focus();
						break;
					case "ServicoAgrupador":
						document.formulario.IdServicoAgrupador.value		= "";
						document.formulario.DescricaoServicoAgrupador.value	= "";
						
						document.formulario.IdServicoAgrupador.focus();
						break;
					case "Aviso":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						
						document.formulario.IdServico.focus();
						break;
					case "MascaraVigencia":
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						addParmUrl("marServicoCFOP","IdServico","");
						
						document.formulario.IdServico.value					= "";
						document.formulario.DescricaoServico.value			= "";
						document.formulario.ValorServico.value				= "";
						document.formulario.IdTipoServico.value				= "";
						document.formulario.CountMes.value					= 0;
						document.formulario.Mes.value						= "";
						document.formulario.IdTipoDesconto.value			= 3;
						document.formulario.ValorDesconto.value				= "";
						document.formulario.ValorFinal.value				= "";
						document.formulario.Fator.value						= "";
						document.formulario.IdContratoTipoVigencia.value	= "";
						document.formulario.VigenciaDefinitiva.value		= "";
						document.formulario.IdRepasse.value					= 0;
						document.formulario.LimiteDesconto.value			= "";
						document.formulario.Acao.value						= 'inserir';
						
						verificarRepasse();
						verifica_limite_desconto(3);
						limparDesconto();
						
						while(document.getElementById('tabelaMascaraVigencia').rows.length > 2) {
							document.getElementById('tabelaMascaraVigencia').deleteRow(1);
						}
						
						document.getElementById("tabelaVigenciaTotal").innerHTML = "Total: 0";
						document.getElementById("tabelaVigenciaValor").innerHTML = "0,00";
						document.getElementById("tabelaVigenciaValorDesconto").innerHTML = "0,00";
						document.getElementById("tabelaVigenciaValorFinal").innerHTML = "0,00";
						document.getElementById("tabelaValorRepasse").innerHTML = "0,00";
						document.getElementById("tabelaPercentualRepasse").innerHTML = "0,00";
						
						document.formulario.IdServico.focus();
						verificaAcao();
						break;
					case "MascaraStatus":
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						addParmUrl("marServicoCFOP","IdServico","");
						
						document.formulario.IdServico.value				= "";
						document.formulario.DescricaoServico.value		= "";
						document.formulario.QtdMinimaDia.value			= "";
						document.formulario.IdTipoServico.value			= "";
						document.formulario.IdStatus.value				= "";
						document.formulario.PercentualDesconto.value	= "";
						document.formulario.TaxaMudancaStatus.value		= "";
						document.formulario.LoginCriacao.value			= "";
						document.formulario.DataCriacao.value			= "";
						document.formulario.LoginAlteracao.value		= "";
						document.formulario.DataAlteracao.value			= "";
						document.formulario.Acao.value					= 'inserir';
						
						document.formulario.IdServico.focus();
						listar_mascara_status();
						verificaAcao();
						break;
					case "AdicionarServico":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						document.formulario.IdServico.focus();
						break;
					case "AdicionarMalaDireta":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						document.formulario.IdServico.focus();
						break;
					case "ProcessoFinanceiro":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						document.formulario.IdServico.focus();
						break;
					case "MalaDireta":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						document.formulario.IdServico.focus();
						break;
					case "AdicionarProcessoFinanceiro":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						document.formulario.IdServico.focus();
						break;
					case "LoteRepasse":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						//document.formulario.IdServico.focus();
						break;
					case "AdicionarLoteRepasse":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						document.formulario.IdServico.focus();
						break;
					case "AdicionarServicoNaEtiqueta":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						document.formulario.IdServico.focus();
						break;
					case "Etiqueta":
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						break;
					case "Aliquota":
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						addParmUrl("marServicoCFOP","IdServico","");
						
						document.formulario.IdCategoriaTributaria.value	= "";
						document.formulario.IdServico.value				= "";
						document.formulario.DescricaoServico.value		= "";
						document.formulario.IdTipoServico.value			= "";
						document.formulario.DataCriacao.value			= "";
						document.formulario.LoginCriacao.value			= "";
						document.formulario.DataAlteracao.value			= "";
						document.formulario.LoginAlteracao.value		= "";
						busca_aliquota("",true,Local);
						break;
					case "ServicoParametroNotaFiscal": 
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						document.formulario.IdTipoServico.value		= "";
						
						for(var i = (document.getElementById('tableParametro').rows.length - 1); i > 0; i--) {
							document.getElementById('tableParametro').deleteRow(i);
						}
						
						document.getElementById('cpDadosParametros').style.display = 'none';
						document.formulario.bt_alterar.disabled = true;
						document.formulario.IdServico.focus();
						break;
					case "ServicoAgendamento": 
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						addParmUrl("marServicoCFOP","IdServico","");
						
						document.formulario.QtdDias.value					= '';
						document.getElementById("cpQtdDias").style.display	= "none";
						document.formulario.IdServico.value					= '';
						document.formulario.DescricaoServico.value			= '';
						document.formulario.IdTipoServico.value				= '';
						document.formulario.IdStatus.value					= '';
						document.formulario.IdQtdMeses.value				= '';
						document.formulario.LoginCriacao.value				= '';
						document.formulario.DataCriacao.value				= '';
						document.formulario.LoginAlteracao.value			= '';
						document.formulario.DataAlteracao.value				= '';
						document.formulario.Acao.value						= "inserir";
						
						busca_novo_status(0);
						listar_servico_agendamento(0);
						
						if(document.getElementById('cpAgendamentosCadastrados') != undefined){
							document.getElementById('cpAgendamentosCadastrados').style.display = 'none';
						}
						document.formulario.bt_alterar.disabled = true;
						document.formulario.IdServico.focus();
						break;
					case "ServicoCFOP": 
						addParmUrl("marServico","IdServico","");
						addParmUrl("marServicoValor","IdServico","");
						addParmUrl("marServicoValorNovo","IdServico","");
						addParmUrl("marServicoParametro","IdServico","");
						addParmUrl("marServicoParametroNovo","IdServico","");
						addParmUrl("marServicoRotina","IdServico","");
						addParmUrl("marMascaraVigencia","IdServico","");
						addParmUrl("marMascaraVigenciaNovo","IdServico","");
						addParmUrl("marMascaraStatus","IdServico","");
						addParmUrl("marMascaraStatusNovo","IdServico","");
						addParmUrl("marAliquota","IdServico","");
						addParmUrl("marAliquotaNovo","IdServico","");
						addParmUrl("marServicoParametroNotaFiscal","IdServico","");
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
						addParmUrl("marAgendamento","IdServico","");
						addParmUrl("marServicoCFOP","IdServico","");
						
						document.formulario.IdServico.value			= '';
						document.formulario.DescricaoServico.value	= '';
						document.formulario.IdTipoServico.value		= '';
						document.formulario.Acao.value				= "inserir";
						
						busca_cfop_opcoes();
						listar_cfop_servico();
						if(document.getElementById('cpAgendamentosCadastrados') != undefined){
							document.getElementById('cpAgendamentosCadastrados').style.display = 'none';
						}
						document.formulario.IdServico.focus();
						break;
					default:
						document.formulario.IdServico.value			= "";
						document.formulario.DescricaoServico.value	= "";
						
						document.formulario.IdServico.focus();
						break;
				}
			} else {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoServico = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServicoLayout")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdOrdemServicoLayout = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Valor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DetalheServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DetalheServico = nameTextNode.nodeValue;
				var IdTerceiro = new Array();
				var NomeTerceiro = new Array();
				var ValorRepasseTerceiro = new Array();
				var PercentualRepasseTerceiro = new Array();
				var PercentualRepasseTerceiroOutros = new Array();
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdTerceiro").length; i++) {
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdTerceiro[i] = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeTerceiro[i] = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorRepasseTerceiro[i] = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					PercentualRepasseTerceiro[i] = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiroOutros")[i]; 
					nameTextNode = nameNode.childNodes[0];
					PercentualRepasseTerceiroOutros[i] = nameTextNode.nodeValue;
				}
				
				switch(Local){
					case "Contrato":
						document.getElementById('cp_parametrosServico').style.display = 'none';
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var MultaFidelidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ServicoAutomatico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomaticoAtivo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ServicoAutomaticoAtivo = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoGrupo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServicoGrupo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlContratoImpresso")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlContratoImpresso = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlDistratoImpresso")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlDistratoImpresso = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoTipoVigencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContratoTipoVigencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdNotaFiscalTipo = nameTextNode.nodeValue;
						
						if(document.formulario.Acao.value == "inserir"){
							ServicoAutomatico = ServicoAutomaticoAtivo;
						}
						
						if(Valor == ''){
							Valor = '0';
						}
						
						document.formulario.ValorServico.value					= formata_float(Arredonda(Valor,2),2).replace(/\./i,',');
						document.formulario.ValorDesconto.value					= '0,00';
						document.formulario.ValorFinal.value					= formata_float(Arredonda(Valor,2),2).replace(/\./i,',');
						document.formulario.ValorRepasseTerceiro.value			= '0,00';
						document.formulario.ValorMultaFidelidade.value			= formata_float(Arredonda(MultaFidelidade,2),2).replace(/\./i,',');
						document.formulario.MultaFidelidade.value				= MultaFidelidade;
						document.formulario.IdContratoTipoVigencia.value		= IdContratoTipoVigencia;
						
						while(document.formulario.IdPeriodicidade.options.length > 0) {
							document.formulario.IdPeriodicidade.options[0] = null;
						}
						
						while(document.formulario.QtdParcela.options.length > 0) {
							document.formulario.QtdParcela.options[0] = null;
						}
						
						while(document.formulario.TipoContrato.options.length > 0) {
							document.formulario.TipoContrato.options[0] = null;
						}
						
						while(document.formulario.IdLocalCobranca.options.length > 0) {
							document.formulario.IdLocalCobranca.options[0] = null;
						}
						
						while(document.formulario.MesFechado.options.length > 0) {
							document.formulario.MesFechado.options[0] = null;
						}
						
						document.formulario.IdServico.value				= IdServico;
						document.formulario.DescricaoServico.value		= DescricaoServico;
						document.formulario.ServicoAutomatico.value		= ServicoAutomatico;
						document.formulario.DescricaoServicoGrupo.value	= DescricaoServicoGrupo;
						document.formulario.UrlContratoImpresso.value	= UrlContratoImpresso;
						document.formulario.UrlDistratoImpresso.value	= UrlDistratoImpresso;
						
						listar_terceiros(IdServico);
						busca_cfop_servico(IdServico);
						periodicidade(document.formulario.IdServico.value,ListarCampo);
						listarServicoParametro(document.formulario.IdServico.value,true);
						selecionaCampos();
						
						document.getElementById('cp_automatico').innerHTML		= "";
						document.getElementById('cp_automatico').style.display	= 'none';
						
						if(IdNotaFiscalTipo != "") {
							document.getElementById('cpNotaFiscalCDA').style.display	= 'block';
							document.formulario.NotaFiscalCDA.value 					= document.formulario.NotaFiscalCDADefault.value;
						} else {
							document.getElementById('cpNotaFiscalCDA').style.display	= 'none';
							document.formulario.NotaFiscalCDA.value		 				= "";
						}
						
						if(ServicoAutomatico != '') {
							busca_automatico(ServicoAutomatico);
						}
						
						if(document.formulario.Acao.value == 'inserir' && document.formulario.AvisoContrato.value != 1 && document.formulario.AvisoServico.value == 1 && IdContrato != "") {
							if(!confirm("ATENCAO!\n\nEste cliente já possui um contrato para o serviço selecionado.\nDeseja continuar?","SIM","NAO")) {
								busca_servico(0);
								document.formulario.IdServico.focus();
							}
						} 
						
						busca_opcoes_pessoa_endereco_instalacao(document.formulario.IdPessoa.value, document.formulario.IdEnderecoDefault.value);
						break;
					case "ServicoAutomatico":
						if(Valor == ''){				Valor = '0';	}
						
						var posIni = 0, posFim = 0;
						for(ii=0;ii<document.formulario.length;ii++){
							if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
								if(posIni == 0){
									posIni	=	ii;
								}
								posFim	=	ii;
							}
						}
						
						for(ii=posIni;ii<=posFim;i++){
							if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
								temp	=	document.formulario[ii].name.split("_");
								if(temp[1] == IdServico){
									document.formulario[ii+1].value	=	DescricaoServico;
									document.formulario[ii+2].value	=	formata_float(Arredonda(Valor,2),2).replace('.',',');
									break;
								}
							}
						}
						
						break;
					case "ContratoServico":
						document.getElementById('cp_parametrosServico').style.display		=	'none';
						document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var MultaFidelidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ServicoAutomatico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomaticoAtivo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ServicoAutomaticoAtivo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoTipoVigencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContratoTipoVigencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdNotaFiscalTipo = nameTextNode.nodeValue;
						
						if(document.formulario.Acao.value == "inserir"){
							ServicoAutomatico = ServicoAutomaticoAtivo;
						}
						
						//document.formulario.ParametrosAnterior.value	=	"";
						//listarParametroAnterior(document.formulario.IdServicoAnterior.value,document.formulario.IdContrato.value);
						listarParametroAnteriorContrato(IdServico,false,document.formulario.IdContrato.value);
						//listar_conta_receber_aberto(document.formulario.IdContrato.value);
						listar_lancamento_financeiro_aguardando_cobranca(document.formulario.IdContrato.value);
						
						if(Valor == ''){
							Valor = '0';
						}
						
						document.formulario.IdServico.value					=	IdServico;
						document.formulario.DescricaoServico.value			=	DescricaoServico;
						document.formulario.ServicoAutomatico.value			=	ServicoAutomatico;
						document.formulario.IdContratoTipoVigencia.value	=	IdContratoTipoVigencia;
						document.formulario.ValorServico.value				=	formata_float(Arredonda(Valor,2),2).replace('.',',');
						document.formulario.ValorRepasseTerceiro.value		=	"0,00";
						document.formulario.ValorMultaFidelidade.value		=	formata_float(Arredonda(MultaFidelidade,2),2).replace('.',',');
						document.formulario.MultaFidelidade.value			=	MultaFidelidade;
						
						while(document.formulario.IdPeriodicidade.options.length > 0){
							document.formulario.IdPeriodicidade.options[0] = null;
						}
						while(document.formulario.QtdParcela.options.length > 0){
							document.formulario.QtdParcela.options[0] = null;
						}
						while(document.formulario.TipoContrato.options.length > 0){
							document.formulario.TipoContrato.options[0] = null;
						}
						while(document.formulario.IdLocalCobranca.options.length > 0){
							document.formulario.IdLocalCobranca.options[0] = null;
						}
						while(document.formulario.MesFechado.options.length > 0){
							document.formulario.MesFechado.options[0] = null;
						}
						
						busca_cfop_servico(IdServico);
						periodicidade(document.formulario.IdServico.value,ListarCampo);
						
						document.getElementById('cp_automatico').innerHTML		=	"";
						document.getElementById('cp_automatico').style.display	=	'none';
						
						if(ServicoAutomatico != ''){
							busca_automatico(ServicoAutomatico);
						}
						
						if(IdNotaFiscalTipo != ""){
							document.getElementById('cpNotaFiscalCDA').style.display = 'block';
							document.formulario.NotaFiscalCDA.value = document.formulario.NotaFiscalCDADefault.value;
						}else{
							document.getElementById('cpNotaFiscalCDA').style.display = 'none';
							document.formulario.NotaFiscalCDA.value = "";
						}
						
						selecionaCampos();
						listar_terceiros(IdServico);
						verificaCredito(IdServico,document.formulario.DataBaseCalculo.value,document.formulario.DataCancelamento.value);
						break;	
					case "Servico":
						addParmUrl("marServico","IdServico",IdServico);
						addParmUrl("marServicoValor","IdServico",IdServico);
						addParmUrl("marServicoValorNovo","IdServico",IdServico);
						addParmUrl("marServicoParametro","IdServico",IdServico);
						addParmUrl("marServicoParametroNovo","IdServico",IdServico);
						addParmUrl("marServicoRotina","IdServico",IdServico);
						addParmUrl("marMascaraVigencia","IdServico",IdServico);
						addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
						addParmUrl("marMascaraStatus","IdServico",IdServico);
						addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
						addParmUrl("marMascaraStatus","IdServico",IdServico);
						addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
						addParmUrl("marAliquota","IdServico",IdServico);
						addParmUrl("marAliquotaNovo","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
						addParmUrl("marAgendamento","IdServico",IdServico);
						addParmUrl("marServicoCFOP","IdServico",IdServico);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoGrupo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServicoGrupo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoGrupo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServicoGrupo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCentroCusto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCentroCusto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCentroCusto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoCentroCusto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPlanoConta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPlanoConta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPlanoConta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoPlanoConta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ContratoViaCDA")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ContratoViaCDA = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DetalheDemonstrativoTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DetalheDemonstrativoTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("AtivacaoAutomatica")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var AtivacaoAutomatica = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ExibirReferencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ExibirReferencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Unidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Unidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DiasAvisoAposVencimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DiasAvisoAposVencimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DiasLimiteBloqueio")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DiasLimiteBloqueio = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Filtro_IdPaisEstadoCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Filtro_IdPaisEstadoCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MsgAuxiliarCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var MsgAuxiliarCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("EmailCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var EmailCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ExecutarRotinas")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ExecutarRotinas = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCriacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataAlteracao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginAlteracao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdNotaFiscalTipo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCategoriaTributaria")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCategoriaTributaria = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdFaturamentoFracionado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdFaturamentoFracionado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTecnologia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTecnologia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdDedicado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdDedicado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("FatorMega")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var FatorMega = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoVelocidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoVelocidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Obs = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoSMS")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServicoSMS = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ColetarSICI")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ColetarSICI = nameTextNode.nodeValue;
						
						adicionar_terceiro(true, IdServico);
						
						if(IdNotaFiscalTipo == ''){
							IdNotaFiscalTipo = 0;
						}
						
						document.formulario.IdServico.value						=	IdServico;
						document.formulario.IdOrdemServicoLayout.value			=	IdOrdemServicoLayout;
						document.formulario.DescricaoServico.value				=	DescricaoServico;
						document.formulario.IdTipoServico.value					=	IdTipoServico;
						document.formulario.IdServicoGrupo.value				=	IdServicoGrupo;
						document.formulario.IdPlanoConta.value					=	IdPlanoConta;
						document.formulario.DescricaoPlanoConta.value			=	DescricaoPlanoConta;
						document.formulario.IdCentroCusto.value					=	IdCentroCusto;
						document.formulario.DescricaoCentroCusto.value			=	DescricaoCentroCusto;
						document.formulario.DescricaoServicoGrupo.value			=	DescricaoServicoGrupo;
						document.formulario.ContratoViaCDA.value				=	ContratoViaCDA;
						document.formulario.ExibirReferencia.value				=	ExibirReferencia;
						document.formulario.ValorInicial.readOnly				=	true;
						document.formulario.ValorInicial.value					=	formata_float(Arredonda(Valor,2),2).replace('.',',');
						document.formulario.AtivacaoAutomatica.value			=	AtivacaoAutomatica;
						document.formulario.AtivacaoAutomaticaTemp.value		=	AtivacaoAutomatica;
						document.formulario.DetalheServico.value				=	DetalheServico;
						document.formulario.DiasAvisoAposVencimento.value		=	DiasAvisoAposVencimento;
						document.formulario.Filtro_IdPaisEstadoCidade.value 	= 	Filtro_IdPaisEstadoCidade;
						document.formulario.MsgAuxiliarCobranca.value			=	MsgAuxiliarCobranca;
						document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value					=	LoginCriacao;
						document.formulario.DataAlteracao.value					=	dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value				=	LoginAlteracao;
						document.formulario.EmailCobranca.value					=	EmailCobranca;
						document.formulario.EmailCobrancaTemp.value				=	EmailCobranca;
						document.formulario.ExecutarRotinas.value				=	ExecutarRotinas;
						document.formulario.DiasLimiteBloqueio.value			=	DiasLimiteBloqueio;
						document.formulario.DetalheDemonstrativoTerceiro.value	=	DetalheDemonstrativoTerceiro;
						document.formulario.Periodicidade.value					=	"";
						document.formulario.IdPeriodicidade.value				=	"";
						document.formulario.QtdParcelaMaximo.value				=	"";
						document.formulario.QuantPeriodicidade.value			=	"";
						document.formulario.IdLocalCobranca.value				=	"";
						document.formulario.TipoContrato.value					=	"";
						document.formulario.IdServicoAgrupador.value			=	"";
						document.formulario.DescricaoServicoAgrupador.value		=	"";
						document.formulario.ServicoAgrupador.value				=	"";
						document.formulario.IdTipoPessoa.value					=	IdTipoPessoa;
						document.formulario.IdNotaFiscalTipo.value				=	IdNotaFiscalTipo;
						document.formulario.IdCategoriaTributaria.value			=	IdCategoriaTributaria;
						document.formulario.Unidade.value						=	Unidade;
						document.formulario.Cor.value		 					= 	Cor;
						document.formulario.IdGrupoVelocidade.value				=	IdGrupoVelocidade;
						document.formulario.FatorMega.value						=	formata_float(Arredonda(FatorMega,2),2).replace('.',',');
						document.formulario.ColetarSICI.value					= 	ColetarSICI;
						document.formulario.IdTecnologia.value					=	IdTecnologia;
						document.formulario.IdDedicado.value					=	IdDedicado;
						document.formulario.HistoricoObs.value					=	Obs;
						document.formulario.DescricaoServicoSMS.value			=   DescricaoServicoSMS;
						
						if(Obs != ''){
							document.getElementById("cpHistorico").style.display = "block";
						} else{
							document.getElementById("cpHistorico").style.display = "none";
						}
						
						listar_servico_vinculado(IdServico, false);
						mudarNotaFicalTipo(IdNotaFiscalTipo);
						
						if(IdTipoServico > 1 || IdTipoServico > 3){
							document.formulario.PercentualRepasseTerceiroOutros.value	=	formata_float(Arredonda(PercentualRepasseTerceiroOutros,2),2).replace(/\./i,',');
						}
						
						document.getElementById('cpDadosServicoImportacaoParametros').style.display	= 'none';
						document.getElementById('cpValorInicial').style.color						= '#000';
						document.getElementById('cpValorInicialMoeda').style.color					= '#000';
						document.getElementById('cpValorRepasseMensal').style.color					= '#000';
						document.getElementById('totaltabelaPeriodicidade').innerHTML				= 'Total: 0';
						document.getElementById('totaltabelaServico').innerHTML						= 'Total: 0';
						document.getElementById('cpValorInicial').innerHTML							= 'Valor Mensal Atual';
						
						for(var i=0; i<document.formulario.IdOrdemServicoLayout.length; i++){
							if(document.formulario.IdOrdemServicoLayout[i].value == IdOrdemServicoLayout){
								document.formulario.IdOrdemServicoLayout[i].selected = true;
								i = document.formulario.IdOrdemServicoLayout.length;
							}							
						}
						
						for(i=0; i<document.formulario.IdTipoPessoa.length; i++){
							if(document.formulario.IdTipoPessoa[i].value == IdTipoPessoa){
								document.formulario.IdTipoPessoa[i].selected = true;
								i = document.formulario.IdTipoPessoa.length;
							}							
						}							
						
						for(i=0; i<document.formulario.AtivacaoAutomatica.length; i++){
							if(document.formulario.AtivacaoAutomatica[i].value == AtivacaoAutomatica){
								document.formulario.AtivacaoAutomatica[i].selected = true;
								i = document.formulario.AtivacaoAutomatica.length;
							}							
						}
						
						for(i=0; i<document.formulario.IdStatus.length; i++){
							if(document.formulario.IdStatus[i].value == IdStatus){
								document.formulario.IdStatus[i].selected = true;
								i = document.formulario.IdStatus.length;
							}							
						}
						
						while(document.getElementById('tabelaServico').rows.length > 2){
							document.getElementById('tabelaServico').deleteRow(1);
						}
						
						while(document.getElementById('tabelaCidade').rows.length > 2){
							document.getElementById('tabelaCidade').deleteRow(1);
						}
						
						while(document.getElementById('tabelaPeriodicidade').rows.length > 2){
							document.getElementById('tabelaPeriodicidade').deleteRow(1);
						}
						
						if(IdOrdemServicoLayout != 1 || IdOrdemServicoLayout != 4){
							document.formulario.IdOrdemServicoLayout.style.display 			= 'none';
							document.getElementById('setOrdemServicoLayout').style.display	= 'none';
						}
						
						Valor = Valor.replace(/\./i,',');
						verificaTipoServico(IdTipoServico);
						document.formulario.IdFaturamentoFracionado.value = IdFaturamentoFracionado;
						
						switch(IdTipoServico){
							case '1':
								busca_periodicidade(IdServico,Valor);
								break;
							case '3':
								busca_servico_agrupado(IdServico);
								break;
							case '4':
								busca_servico_agrupado(IdServico);
								break;
						}
						
						//############# Filtro_IdPaisEstadoCidade ###########################
						if(Filtro_IdPaisEstadoCidade != ""){
							var temp3 = Filtro_IdPaisEstadoCidade;
							var tempFiltro3	= temp3.split('^');
							var ii3=0;
							
							if(Filtro_IdPaisEstadoCidade != ''){
								while(tempFiltro3[ii3] != undefined){
									tempFiltro3[ii3]	=	tempFiltro3[ii3].split(',')
									adicionar_cidade(tempFiltro3[ii3][0],tempFiltro3[ii3][1],tempFiltro3[ii3][2],listar);
									ii3++;
								}
							}
							
							document.getElementById('totaltabelaCidade').innerHTML = "Total: "+ii3;
						}
						
						document.formulario.Acao.value = 'alterar';
						
						verificarColetaSICI();
						verificaAcao();
						break;
					case "ServicoValor": 	
						nameNode = xmlhttp.responseXML.getElementsByTagName("maxQtdMesesFidelidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var maxQtdMesesFidelidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DetalheDemonstrativoTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DetalheDemonstrativoTerceiro = nameTextNode.nodeValue;
					
						addParmUrl("marServico","IdServico",IdServico);
						addParmUrl("marServicoValor","IdServico",IdServico);
						addParmUrl("marServicoValorNovo","IdServico",IdServico);
						addParmUrl("marServicoParametro","IdServico",IdServico);
						addParmUrl("marServicoParametroNovo","IdServico",IdServico);
						addParmUrl("marServicoRotina","IdServico",IdServico);
						addParmUrl("marMascaraVigencia","IdServico",IdServico);
						addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
						addParmUrl("marMascaraStatus","IdServico",IdServico);
						addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
						addParmUrl("marAliquota","IdServico",IdServico);
						addParmUrl("marAliquotaNovo","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
						addParmUrl("marAgendamento","IdServico",IdServico);
						addParmUrl("marServicoCFOP","IdServico",IdServico);
						
						listarServicoValor(IdServico,false);
						
						document.formulario.IdServico.value								=	IdServico;
						document.formulario.IdTipoServico.value							=	IdTipoServico;
						document.formulario.IdTipoServicoAux.value						=	IdTipoServico;
						document.formulario.DescricaoServico.value						=	DescricaoServico;
						document.formulario.maxQtdMesesFidelidade.value					=	maxQtdMesesFidelidade;
						document.formulario.DataInicio.value							=	"";
						document.formulario.DataTermino.value							=	"";
						document.formulario.DescricaoServicoValor.value					=	"";
						document.formulario.Valor.value									=	"";
						document.formulario.IdContratoTipoVigencia.value				=	"";
						document.formulario.DataCriacao.value							= 	"";
						document.formulario.LoginCriacao.value							= 	"";
						document.formulario.DataAlteracao.value							= 	"";
						document.formulario.LoginAlteracao.value						= 	"";
						document.formulario.MultaFidelidade.value						=	"0,00";
						document.formulario.IdContratoTipoVigencia[0].selected			=	true;
						
						if(document.formulario.maxQtdMesesFidelidade.value <= 0){
							document.getElementById("cpValorMulta").style.color	=	"#000";
						}else{
							document.getElementById("cpValorMulta").style.color	=	"#C10000";
						}
						
						document.formulario.Acao.value = 'inserir';
						
						addParmUrl("marServicoValorNovo","DataInicio","");
						status_inicial();
						lista_terceiro(IdServico);
						verificaAcao();
						busca_servico_valor(IdServico,false,document.formulario.Local.value,document.formulario.DataInicioTemp.value);
						break;	
					case "ServicoParametro": 
						addParmUrl("marServico","IdServico",IdServico);
						addParmUrl("marServicoValor","IdServico",IdServico);
						addParmUrl("marServicoValorNovo","IdServico",IdServico);
						addParmUrl("marServicoParametro","IdServico",IdServico);
						addParmUrl("marServicoParametroNovo","IdServico",IdServico);
						addParmUrl("marServicoRotina","IdServico",IdServico);
						addParmUrl("marMascaraVigencia","IdServico",IdServico);
						addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
						addParmUrl("marMascaraStatus","IdServico",IdServico);
						addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
						addParmUrl("marAliquota","IdServico",IdServico);
						addParmUrl("marAliquotaNovo","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
						addParmUrl("marAgendamento","IdServico",IdServico);
						addParmUrl("marServicoCFOP","IdServico",IdServico);
						
						mensagens(0);
						listarParametro(IdServico,false);
						
						document.formulario.IdServico.value						=	IdServico;
						document.formulario.DescricaoServico.value				=	DescricaoServico;
						document.formulario.IdTipoServico.value					=	IdTipoServico;
						document.formulario.IdParametroServico.value			=	"";
						document.formulario.DescricaoParametroServico.value		=	"";
						document.formulario.Obrigatorio_Texto[0].selected 		= 	true;
						document.formulario.ObrigatorioStatus_Texto.value 		= 	"";
						document.formulario.Obrigatorio_Selecao[0].selected 	= 	true;
						document.formulario.ObrigatorioStatus_Selecao.value 	= 	"";
						document.formulario.Calculavel_Texto[0].selected 		= 	true;
						document.formulario.Calculavel_Selecao[0].selected 		= 	true;
						document.formulario.Obs.value							=	"";
						//document.formulario.ValorDefaultInput.value				=	"";
						document.formulario.IdStatusParametro[0].selected 		= 	true;
						document.formulario.RotinaCalculo.value					=	"";		
						document.formulario.IdTipoParametro.value				=	"";		
						document.formulario.IdMascaraCampo.value				=	"";		
						document.formulario.OpcaoValor.value					=	"";				
						document.formulario.DataCriacao.value					=	"";
						document.formulario.LoginCriacao.value					=	"";
						document.formulario.DataAlteracao.value					=	"";
						document.formulario.LoginAlteracao.value				=	"";
						document.formulario.Obrigatorio_Texto.disabled			=	false;
						document.formulario.Obrigatorio_Selecao.disabled		=	false;
						
						addParmUrl("marServicoParametroNovo","IdParametroServico","");
						
						while(document.formulario.ValorDefaultSelect.options.length > 0){
							document.formulario.ValorDefaultSelect.options[0] = null;
						}
				
						busca_grupos_usuario();
						verificaTipoParametro();
						status_inicial();
						document.getElementById('tabelahelpText2').style.display	=	'none';
						document.getElementById('cpRotinaCalculo').style.display	=	'none';
						
						document.formulario.Acao.value						= 	'inserir';
						busca_servico_parametro(IdServico,false,document.formulario.Local.value,document.formulario.IdParametroServicoTemp.value);
						document.formulario.IdParametroServicoTemp.value = '';
						
						verificaAcao();
						break;
					case "ServicoRotina":
						addParmUrl("marServico","IdServico",IdServico);
						addParmUrl("marServicoValor","IdServico",IdServico);
						addParmUrl("marServicoValorNovo","IdServico",IdServico);
						addParmUrl("marServicoParametro","IdServico",IdServico);
						addParmUrl("marServicoParametroNovo","IdServico",IdServico);
						addParmUrl("marServicoRotina","IdServico",IdServico);
						addParmUrl("marMascaraVigencia","IdServico",IdServico);
						addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
						addParmUrl("marMascaraStatus","IdServico",IdServico);
						addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
						addParmUrl("marAliquota","IdServico",IdServico);
						addParmUrl("marAliquotaNovo","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
						addParmUrl("marAgendamento","IdServico",IdServico);
						addParmUrl("marServicoCFOP","IdServico",IdServico);
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlContratoImpresso")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlContratoImpresso = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaBloqueio")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlRotinaBloqueio = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaDesbloqueio")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlRotinaDesbloqueio = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlRotinaCriacao = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaAlteracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlRotinaAlteracao = nameTextNode.nodeValue;	
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlRotinaCancelamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlRotinaCancelamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("UrlDistratoImpresso")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var UrlDistratoImpresso = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoMonitor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServicoMonitor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMonitor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTipoMonitor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdSNMP")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdSNMP = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CodigoSNMP")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CodigoSNMP = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ComandoSSH")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ComandoSSH = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Historico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Historico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("TermoCienciaCDA")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var TermoCienciaCDA = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCriacao = nameTextNode.nodeValue;					
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataAlteracao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginAlteracao = nameTextNode.nodeValue;			
						
						document.formulario.IdServico.value						=	IdServico;								
						document.formulario.DescricaoServico.value				=	DescricaoServico;
						document.formulario.IdTipoServico.value					=	IdTipoServico;
						document.formulario.UrlRotinaBloqueio.value				=	UrlRotinaBloqueio;
						document.formulario.UrlContratoImpresso.value			=	UrlContratoImpresso;
						document.formulario.UrlRotinaDesbloqueio.value			=	UrlRotinaDesbloqueio;
						document.formulario.UrlRotinaCriacao.value				=	UrlRotinaCriacao;
						document.formulario.UrlRotinaAlteracao.value			=	UrlRotinaAlteracao;
						document.formulario.UrlRotinaCancelamento.value			=	UrlRotinaCancelamento;
						document.formulario.UrlDistratoImpresso.value			=	UrlDistratoImpresso;
						document.formulario.TermoCienciaCDA.value				=	TermoCienciaCDA;
						document.formulario.IdTipoMonitor.value					=	IdTipoMonitor;
						document.formulario.IdSNMP.value						=	IdSNMP;
						document.formulario.CodigoSNMP.value					=	CodigoSNMP;
						document.formulario.ComandoSSH.value					=	ComandoSSH;
						document.formulario.Historico.value						=	Historico;
						
						
						
						if(UrlContratoImpresso != ''){
							document.getElementById('LinkUrlContrato').innerHTML 	   = "<a href='contrato/"+document.formulario.UrlContratoImpresso.value+"' target='_blank'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'></a>";
							document.getElementById('LinkUrlDistratoImpresso').innerHTML = "<a href='contrato/"+document.formulario.UrlDistratoImpresso.value+"' target='_blank'><img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'></a>";
						}else{
							document.getElementById('LinkUrlContrato').innerHTML 	   = "<img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'>";
							document.getElementById('LinkUrlDistratoImpresso').innerHTML = "<img src='../../img/estrutura_sistema/ico_ie.gif' alt='Visualizar'>";
						}
						
						adicionar_monitor(IdServico);
						
						document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value					=	LoginCriacao;
						document.formulario.DataAlteracao.value					=	dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value				=	LoginAlteracao;										
						
						if(IdTipoServico == 2 || IdTipoServico == 3){
							document.formulario.UrlRotinaBloqueio.disabled		= true;
							document.formulario.UrlContratoImpresso.disabled	= true;
							document.formulario.UrlRotinaDesbloqueio.disabled	= true;
							document.formulario.UrlRotinaCriacao.disabled		= true;
							document.formulario.UrlRotinaCancelamento.disabled	= true;	
							document.formulario.UrlRotinaAlteracao.disabled		= true;
							document.formulario.UrlDistratoImpresso.disabled	= true;
						}else{
							document.formulario.UrlRotinaBloqueio.disabled		= false;
							document.formulario.UrlContratoImpresso.disabled	= false;
							document.formulario.UrlRotinaDesbloqueio.disabled	= false;
							document.formulario.UrlRotinaCriacao.disabled		= false;
							document.formulario.UrlRotinaCancelamento.disabled	= false;	
							document.formulario.UrlRotinaAlteracao.disabled		= false;
							document.formulario.UrlDistratoImpresso.disabled	= false;
						}
						
						document.formulario.Acao.value 		= 'alterar';
						verificaAcao();
						break;
					case "OrdemServico": 
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServicoLayout")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdOrdemServicoLayout = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoGrupo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServicoGrupo = nameTextNode.nodeValue;
						
						document.formulario.IdServico.value					=	IdServico;
						document.formulario.DescricaoServico.value			=	DescricaoServico;
						document.formulario.DetalheServico.value			=	DetalheServico;
						document.formulario.DescricaoServicoGrupo.value		=	DescricaoServicoGrupo;
						document.formulario.IdTipoServico.value				=	IdTipoServico;
						document.formulario.IdOrdemServicoLayout.value		=	IdOrdemServicoLayout;
						
						if(document.formulario.Acao.value == 'inserir'){
							listarServicoParametro(document.formulario.IdServico.value,true);
						}
						
						if(document.formulario.IdOrdemServico.value == '' && document.formulario.Acao.value == 'inserir'){
							document.formulario.Valor.value	= formata_float(Arredonda(Valor,2),2).replace(/\./i,',');
						}
						
						if(document.formulario.Acao.value == 'inserir' && IdOrdemServico!=""){
							if(confirm("ATENCAO!\n\nEste cliente já possui uma ordem de serviço para o serviço selecionado.\nDeseja continuar?","SIM","NAO") == false){
								busca_servico(0);
								document.formulario.IdServico.focus();
							}
						}

						calcula_total();
						break;
					case "OrdemServicoFatura": 
						document.formulario.IdServico.value					=	IdServico;
						document.formulario.DescricaoServico.value			=	DescricaoServico;
						document.formulario.DetalheServico.value			=	DetalheServico;
						document.formulario.IdTipoServico.value				=	IdTipoServico;
						break;	
					case "ProcessoFinanceiro": 
						document.formulario.IdServico.value					=	IdServico;
						document.formulario.DescricaoServico.value			=	DescricaoServico;
						document.formulario.IdTipoServico.value				=	IdTipoServico;
						break;
					case "MalaDireta": 
						document.formulario.IdServico.value					=	IdServico;
						document.formulario.DescricaoServico.value			=	DescricaoServico;
						document.formulario.IdTipoServico.value				=	IdTipoServico;
						break;	
					case "LoteRepasse": 
						document.formulario.IdServico.value					=	IdServico;
						document.formulario.DescricaoServico.value			=	DescricaoServico;
						document.formulario.IdTipoServico.value				=	IdTipoServico;
						break;	
					case "ServicoImportar":
						document.formulario.IdServicoImportar.value			=	IdServico;
						document.formulario.DescricaoServicoImportar.value	=	DescricaoServico;
						
						listar_servico_vinculado(IdServico, true);
						break;
					case 'AdicionarLoteRepasse':
						var cont = 0; ii='';
						if(ListarCampo == '' || ListarCampo == undefined || ListarCampo == 'busca'){
							if(document.formulario.Filtro_IdServico.value == ''){
								document.formulario.Filtro_IdServico.value = IdServico;
								ii = 0;
							}else{
								var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
									
								ii=0; 
								while(tempFiltro[ii] != undefined){
									if(tempFiltro[ii] != IdServico){
										cont++;		
									}
									ii++;
								}
								if(ii == cont){
									document.formulario.Filtro_IdServico.value = document.formulario.Filtro_IdServico.value + "," + IdServico;
								}
							}
						}else{
							ii=0;
						}
						if(ii == cont){
							var tam, linha, c0, c1, c2, c3, c4;
							
							tam 	= document.getElementById('tabelaServico').rows.length;
							linha	= document.getElementById('tabelaServico').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							for(var i = 0; i < IdTerceiro.length; i++){
								if(document.formulario.IdPessoa.value == IdTerceiro[i]) {
									ValorRepasseTerceiro = ValorRepasseTerceiro[i];
									break;
								}
							}
							
							if(ValorRepasseTerceiro == ''){
								ValorRepasseTerceiro = 0;
							}
							
							if(Valor == ''){
								Valor = 0;
							}
							
							linha.accessKey 			= IdServico; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							
							var linkIni = "<a href='cadastro_servico.php?IdServico="+IdServico+"'>";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdServico + linkFim;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + DescricaoServico + linkFim;
							
							c2.innerHTML =  formata_float(Arredonda(Valor,2),2).replace('.',',');
							c2.style.padding =	"0 8px 0 0";
							c2.style.textAlign = "right";
							
							c3.innerHTML =  formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
							c3.style.textAlign = "right";
							c3.style.padding =	"0 8px 0 0";
							
							if(document.formulario.IdStatus.value == 1 || document.formulario.IdStatus.value == ''){
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_servico("+IdServico+")\"></tr>";
							}else{
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
							}
							c4.style.textAlign = "center";
							c4.style.cursor = "pointer";
							
							//if(document.formulario.IdStatus.value == '' || document.formulario.IdStatus.value == 1){
								if(document.formulario.TotalValor.value == ''){
									document.formulario.TotalValor.value = 0;
								}
								
								v1	=	document.formulario.TotalValor.value;
								v1	=	new String(v1);;
								v1	=	v1.replace('.','');
								v1	=	v1.replace('.','');
								v1	=	v1.replace(',','.');
								
								totalv1	=	parseFloat(v1)	+	parseFloat(Valor);
								totalv1	=	formata_float(Arredonda(totalv1,2),2).replace('.',',');
								
								document.formulario.TotalValor.value	=	totalv1;
								
								if(document.formulario.TotalRepasse.value == ''){
									document.formulario.TotalRepasse.value = 0;
								}
								
								v2	=	document.formulario.TotalRepasse.value;
								v2	=	new String(v2);;
								v2	=	v2.replace('.','');
								v2	=	v2.replace('.','');
								v2	=	v2.replace(',','.');
								
								totalv2	=	parseFloat(v2)	+	parseFloat(ValorRepasseTerceiro);
								totalv2	=	formata_float(Arredonda(totalv2,2),2).replace('.',',');
								
								document.formulario.TotalRepasse.value	=	totalv2;
								
								document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii+1);
								document.getElementById('cptotalValor').innerHTML		=	totalv1;
								document.getElementById('cptotalRepasse').innerHTML		=	totalv2;
							/*}else{
								if(document.formulario.Erro.value != ''){
									scrollWindow('bottom');
								}
							}*/
						}
						document.formulario.IdServico.value					=	"";
						document.formulario.DescricaoServico.value			=	"";
						document.formulario.IdTipoServico.value				=	"";
						break;
					case "ServicoAgrupador":
						document.formulario.IdServicoAgrupador.value			=	IdServico;
						document.formulario.DescricaoServicoAgrupador.value		=	DescricaoServico;
						break;
					case 'AdicionarServico':
						var cont = 0; ii='';								
						if(ListarCampo == 'busca'){
							if(document.formulario.Filtro_IdServico.value == ''){
								document.formulario.Filtro_IdServico.value = IdServico;
								ii = 0;
							}else{
								var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
									
								ii=0; 
								while(tempFiltro[ii] != undefined){
									if(tempFiltro[ii] != IdServico){
										cont++;		
									}
									ii++;
								}
								if(ii == cont){
									document.formulario.Filtro_IdServico.value = document.formulario.Filtro_IdServico.value + "," + IdServico;
								}
							}
						}else{
							ii=0;
						}
						
						if(ii == cont){
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoServico = nameTextNode.nodeValue;
							
							var tam, linha, c0, c1, c2, c3, c4;
							
							tam 	= document.getElementById('tabelaServico').rows.length;
							linha	= document.getElementById('tabelaServico').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey 			= IdServico; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							
							var linkIni = "<a href='cadastro_servico.php?IdServico="+IdServico+"'>";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdServico + linkFim;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + DescricaoServico.substr(0,30) + linkFim;
							
							c2.innerHTML = linkIni + DescTipoServico.substr(0,30) + linkFim;
							
							c3.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim ;
							c3.style.textAlign = "right";
							c3.style.padding =	"0 8px 0 0";
							
							if(document.formulario.IdStatus.value == 1){
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_servico("+IdServico+")\"></tr>";
							}else{
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
							}
							c4.style.textAlign = "center";
							c4.style.cursor = "pointer";
							
							if(document.formulario.IdProcessoFinanceiro != undefined){
								if(document.formulario.IdProcessoFinanceiro.value == ''){
									document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
										scrollWindow('bottom');
									}
								}
							} else {
								document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii+1);
							}
						}
						document.formulario.IdServico.value				=	"";
						document.formulario.DescricaoServico.value		=	"";
						document.formulario.IdTipoServico.value			=	"";
						break;
					case 'AdicionarMalaDireta':
						var cont = 0; ii='';
						
						if(ListarCampo == 'busca'){
							if(document.formulario.Filtro_IdServico.value == ''){
								document.formulario.Filtro_IdServico.value = IdServico;
								ii = 0;
							}else{
								var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
									
								ii=0; 
								while(tempFiltro[ii] != undefined){
									if(tempFiltro[ii] != IdServico){
										cont++;		
									}
									ii++;
								}
								if(ii == cont){
									document.formulario.Filtro_IdServico.value = document.formulario.Filtro_IdServico.value + "," + IdServico;
								}
							}
						}else{
							ii=0;
						}
						
						if(ii == cont){
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoServico = nameTextNode.nodeValue;
							
							var tam, linha, c0, c1, c2, c3, c4;
							
							tam 	= document.getElementById('tabelaServico').rows.length;
							linha	= document.getElementById('tabelaServico').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey 			= IdServico; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							
							var linkIni = "<a href='cadastro_servico.php?IdServico="+IdServico+"'>";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdServico + linkFim;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + DescricaoServico.substr(0,30) + linkFim;
							
							c2.innerHTML = linkIni + DescTipoServico.substr(0,30) + linkFim;
							
							c3.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim ;
							c3.style.textAlign = "right";
							c3.style.padding =	"0 8px 0 0";
							
							if(document.formulario.IdStatus.value == 1){
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_servico("+IdServico+")\"></tr>";
							}else{
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
							}
							c4.style.textAlign = "center";
							c4.style.cursor = "pointer";
							
							document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii+1);
						}
						
						document.formulario.IdServico.value				=	"";
						document.formulario.DescricaoServico.value		=	"";
						document.formulario.IdTipoServico.value			=	"";
						break;
					case "MascaraVigencia":
						nameNode = xmlhttp.responseXML.getElementsByTagName("Mes")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Mes = nameTextNode.nodeValue;
						
						addParmUrl("marServico","IdServico",IdServico);
						addParmUrl("marServicoValor","IdServico",IdServico);
						addParmUrl("marServicoValorNovo","IdServico",IdServico);
						addParmUrl("marServicoParametro","IdServico",IdServico);
						addParmUrl("marServicoParametroNovo","IdServico",IdServico);
						addParmUrl("marServicoRotina","IdServico",IdServico);
						addParmUrl("marMascaraVigencia","IdServico",IdServico);
						addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
						addParmUrl("marMascaraStatus","IdServico",IdServico);
						addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
						addParmUrl("marAliquota","IdServico",IdServico);
						addParmUrl("marAliquotaNovo","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
						addParmUrl("marAgendamento","IdServico",IdServico);
						addParmUrl("marServicoCFOP","IdServico",IdServico);
						
						document.formulario.IdServico.value					= IdServico;
						document.formulario.DescricaoServico.value			= DescricaoServico;
						document.formulario.ValorServico.value				= formata_float(Arredonda(Valor,2),2).replace(/\./i,',');
						document.formulario.IdTipoServico.value				= IdTipoServico;
						document.formulario.CountMes.value					= Mes;
						document.formulario.IdTipoDesconto.value			= "3";
						document.formulario.ValorDesconto.value				= "";
						document.formulario.ValorFinal.value				= "";
						document.formulario.Fator.value						= "";
						document.formulario.IdContratoTipoVigencia.value	= "";
						document.formulario.LimiteDesconto.value			= "";
						document.formulario.IdRepasse.value					= 0;
						document.formulario.Acao.value						= 'inserir';
						
						while(document.getElementById('tabelaMascaraVigencia').rows.length > 2){
							document.getElementById('tabelaMascaraVigencia').deleteRow(1);
						}
						
						document.formulario.Mes.value = (parseFloat(Mes)+1)+'º';
						
						verificarRepasse();
						limparDesconto();
						verifica_limite_desconto();
						
						if(IdTipoServico != 2 && IdTipoServico != 3){
							listar_mascara_vigencia(IdServico,false,Local);
						}
						
						verificaAcao();
						break;
					case "MascaraStatus":
						addParmUrl("marServico","IdServico",IdServico);
						addParmUrl("marServicoValor","IdServico",IdServico);
						addParmUrl("marServicoValorNovo","IdServico",IdServico);
						addParmUrl("marServicoParametro","IdServico",IdServico);
						addParmUrl("marServicoParametroNovo","IdServico",IdServico);
						addParmUrl("marServicoRotina","IdServico",IdServico);
						addParmUrl("marMascaraVigencia","IdServico",IdServico);
						addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
						addParmUrl("marMascaraStatus","IdServico",IdServico);
						addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
						addParmUrl("marAliquota","IdServico",IdServico);
						addParmUrl("marAliquotaNovo","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
						addParmUrl("marAgendamento","IdServico",IdServico);
						addParmUrl("marServicoCFOP","IdServico",IdServico);
						
						document.formulario.IdServico.value				=	IdServico;
						document.formulario.DescricaoServico.value		=	DescricaoServico;
						document.formulario.IdTipoServico.value			=	IdTipoServico;
						document.formulario.IdStatus.value				=	"";
						document.formulario.PercentualDesconto.value	=	"";
						document.formulario.TaxaMudancaStatus.value		=	"";
						document.formulario.QtdMinimaDia.value			= 	"";
						document.formulario.LoginCriacao.value			=	"";
						document.formulario.DataCriacao.value			=	"";
						document.formulario.LoginAlteracao.value		=	"";
						document.formulario.DataAlteracao.value			=	"";
						document.formulario.Acao.value					=	"inserir";
						
						listar_mascara_status(IdServico);
						verificaAcao();
						break;
					case "Aviso":
						document.formulario.IdServico.value						=	IdServico;
						document.formulario.DescricaoServico.value				=	DescricaoServico;
						document.formulario.IdTipoServico.value					=	IdTipoServico;
						break;									
					case 'AdicionarServicoNaEtiqueta':
						var cont = 0; ii='';								
						if(ListarCampo == 'busca'){
							if(document.formulario.Filtro_IdServico.value == ''){
								document.formulario.Filtro_IdServico.value = IdServico;
								ii = 0;
							}else{
								var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
									
								ii=0; 
								while(tempFiltro[ii] != undefined){
									if(tempFiltro[ii] != IdServico){
										cont++;		
									}
									ii++;
								}
								if(ii == cont){
									document.formulario.Filtro_IdServico.value = document.formulario.Filtro_IdServico.value + "," + IdServico;
								}
							}
						}else{
							ii=0;
						}
						
						if(ii == cont){
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoServico = nameTextNode.nodeValue;
							
							var tam, linha, c0, c1, c2, c3, c4;
							
							tam 	= document.getElementById('tabelaServico').rows.length;
							linha	= document.getElementById('tabelaServico').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey 			= IdServico; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							
							var linkIni = "<a href='cadastro_servico.php?IdServico="+IdServico+"'>";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdServico + linkFim;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + DescricaoServico.substr(0,30) + linkFim;
							
							c2.innerHTML = linkIni + DescTipoServico.substr(0,30) + linkFim;
							
							c3.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim ;
							c3.style.textAlign = "right";
							c3.style.padding =	"0 8px 0 0";
							
							//if(document.formulario.IdStatus.value == 1){
								c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_servico("+IdServico+")\"></tr>";
						//	}else{
						//		c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
						//	}
							c4.style.textAlign = "center";
							c4.style.cursor = "pointer";
																
							document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii+1);
							
						}
						document.formulario.IdServico.value				=	"";
						document.formulario.DescricaoServico.value		=	"";
						document.formulario.IdTipoServico.value			=	"";
						break;
					case 'Etiqueta':
						document.formulario.IdServico.value				=	IdServico;
						document.formulario.DescricaoServico.value		=	DescricaoServico;
						document.formulario.IdTipoServico.value			=	IdTipoServico;
						break;	
					case "Aliquota":
						
						addParmUrl("marServico","IdServico",IdServico);
						addParmUrl("marServicoValor","IdServico",IdServico);
						addParmUrl("marServicoValorNovo","IdServico",IdServico);
						addParmUrl("marServicoParametro","IdServico",IdServico);
						addParmUrl("marServicoParametroNovo","IdServico",IdServico);
						addParmUrl("marServicoRotina","IdServico",IdServico);
						addParmUrl("marMascaraVigencia","IdServico",IdServico);
						addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
						addParmUrl("marMascaraStatus","IdServico",IdServico);
						addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
						addParmUrl("marAliquota","IdServico",IdServico);
						addParmUrl("marAliquotaNovo","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
						addParmUrl("marAgendamento","IdServico",IdServico);
						addParmUrl("marServicoCFOP","IdServico",IdServico);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCategoriaTributaria")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCategoriaTributaria = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCriacao = nameTextNode.nodeValue;					
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataAlteracao = nameTextNode.nodeValue;
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginAlteracao = nameTextNode.nodeValue;

						document.formulario.IdCategoriaTributaria.value = 	IdCategoriaTributaria;
						document.formulario.IdServico.value				=	IdServico;
						document.formulario.DescricaoServico.value		=	DescricaoServico;
						document.formulario.IdTipoServico.value			=	IdTipoServico;
						document.formulario.DataCriacao.value			=	dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value			=	LoginCriacao;
						document.formulario.DataAlteracao.value			=	dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value		=	LoginAlteracao;
						
						busca_aliquota(IdServico,false,Local);
						break;
					case "ServicoParametroNotaFiscal": 								
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdNotaFiscalTipo = nameTextNode.nodeValue;
						
						addParmUrl("marServico","IdServico",IdServico);
						addParmUrl("marServicoValor","IdServico",IdServico);
						addParmUrl("marServicoValorNovo","IdServico",IdServico);
						addParmUrl("marServicoParametro","IdServico",IdServico);
						addParmUrl("marServicoParametroNovo","IdServico",IdServico);
						addParmUrl("marServicoRotina","IdServico",IdServico);
						addParmUrl("marMascaraVigencia","IdServico",IdServico);
						addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
						addParmUrl("marMascaraStatus","IdServico",IdServico);
						addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
						addParmUrl("marAliquota","IdServico",IdServico);
						addParmUrl("marAliquotaNovo","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
						addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
						addParmUrl("marAgendamento","IdServico",IdServico);
						addParmUrl("marServicoCFOP","IdServico",IdServico);
						
						
						document.formulario.IdServico.value					=	IdServico;
						document.formulario.IdTipoServico.value				=	IdTipoServico;							
						document.formulario.DescricaoServico.value			=	DescricaoServico;
						document.formulario.IdNotaFiscalTipo.value 			= 	IdNotaFiscalTipo;
				
						busca_servico_parametro_nota_fiscal_layout(IdNotaFiscalTipo,false);							
						document.formulario.IdServico.focus();
						break;	
					case "ServicoAgendamento":
						document.formulario.IdTipoServicoTemp.value = IdTipoServico;
						
						if(IdTipoServico == 1 || IdTipoServico == 3){ // Periódico || Agrupado //
							addParmUrl("marServico","IdServico",IdServico);
							addParmUrl("marServicoValor","IdServico",IdServico);
							addParmUrl("marServicoValorNovo","IdServico",IdServico);
							addParmUrl("marServicoParametro","IdServico",IdServico);
							addParmUrl("marServicoParametroNovo","IdServico",IdServico);
							addParmUrl("marServicoRotina","IdServico",IdServico);
							addParmUrl("marMascaraVigencia","IdServico",IdServico);
							addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
							addParmUrl("marMascaraStatus","IdServico",IdServico);
							addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
							addParmUrl("marAliquota","IdServico",IdServico);
							addParmUrl("marAliquotaNovo","IdServico",IdServico);
							addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
							addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
							addParmUrl("marAgendamento","IdServico",IdServico);
							addParmUrl("marServicoCFOP","IdServico",IdServico);
							
							document.formulario.IdServico.value			= IdServico;
							document.formulario.IdTipoServico.value		= IdTipoServico;							
							document.formulario.DescricaoServico.value	= DescricaoServico;
							
							if(IdTipoServico == 1){
								document.formulario.MudarStatusContratoConcluirOS.value = '';
								document.getElementById("cp_opPeriodico").style.display = "block";
								document.getElementById("cp_opAgrupado").style.display = "none";
								
								if(document.formulario.Acao.value != 'alterar' || document.formulario.IdServicoTemp.value != IdServico){
									document.formulario.IdStatus.value						= '';	
									document.formulario.IdQtdMeses.value					= '';
									document.formulario.LoginCriacao.value					= '';
									document.formulario.DataCriacao.value					= '';
									document.formulario.LoginAlteracao.value				= '';
									document.formulario.DataAlteracao.value					= '';
									document.formulario.QtdDias.value						= '';
									document.getElementById("cpQtdDias").style.display		= "block";
									document.getElementById("cpQtdDias").style.display		= "none";
									document.formulario.Acao.value							= "inserir";
								}
								
								document.formulario.IdServicoTemp.value = IdServico;
							} else{
								nameNode = xmlhttp.responseXML.getElementsByTagName("MudarStatusContratoConcluirOS")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var MudarStatusContratoConcluirOS = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("BaseDataStatusContratoOS")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var BaseDataStatusContratoOS = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0];
								nameTextNode = nameNode.childNodes[0];
								var DataCriacao = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0];
								nameTextNode = nameNode.childNodes[0];
								var LoginCriacao = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0];
								nameTextNode = nameNode.childNodes[0];
								var DataAlteracao = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0];
								nameTextNode = nameNode.childNodes[0];
								var LoginAlteracao = nameTextNode.nodeValue;
								
								if(MudarStatusContratoConcluirOS == 201 || MudarStatusContratoConcluirOS == 306){
									document.formulario.QtdDias.value = BaseDataStatusContratoOS;
									document.getElementById("cpQtdDias").style.display = "block";
								} else{
									document.formulario.QtdDias.value = '';
									document.getElementById("cpQtdDias").style.display = "none";
								}
								
								document.getElementById("cp_opPeriodico").style.display	= "none";
								document.getElementById("cp_opAgrupado").style.display	= "block";
								document.formulario.IdServicoTemp.value					= '';
								document.formulario.MudarStatusContratoConcluirOS.value	= MudarStatusContratoConcluirOS;
								document.formulario.DataCriacao.value					= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value					= LoginCriacao;
								document.formulario.DataAlteracao.value					= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value				= LoginAlteracao;
								document.formulario.Acao.value							= "alterar";
							}
							
							busca_novo_status(0);
							listar_servico_agendamento(IdServico);	
						} else{
							addParmUrl("marServico","IdServico","");
							addParmUrl("marServicoValor","IdServico","");
							addParmUrl("marServicoValorNovo","IdServico","");
							addParmUrl("marServicoParametro","IdServico","");
							addParmUrl("marServicoParametroNovo","IdServico","");
							addParmUrl("marServicoRotina","IdServico","");
							addParmUrl("marMascaraVigencia","IdServico","");
							addParmUrl("marMascaraVigenciaNovo","IdServico","");
							addParmUrl("marMascaraStatus","IdServico","");
							addParmUrl("marMascaraStatusNovo","IdServico","");
							addParmUrl("marAliquota","IdServico","");
							addParmUrl("marAliquotaNovo","IdServico","");
							addParmUrl("marServicoParametroNotaFiscal","IdServico","");
							addParmUrl("marServicoParametroNotaFiscalNovo","IdServico","");
							addParmUrl("marAgendamento","IdServico","");
							addParmUrl("marServicoCFOP","IdServico","");
							
							document.formulario.IdServico.value			= '';
							document.formulario.DescricaoServico.value	= '';
							document.formulario.IdTipoServico.value		= '';
							document.formulario.IdStatus.value			= '';	
							document.formulario.IdQtdMeses.value		= '';
							document.formulario.LoginCriacao.value		= '';
							document.formulario.DataCriacao.value		= '';
							document.formulario.LoginAlteracao.value	= '';
							document.formulario.DataAlteracao.value		= '';
							document.formulario.bt_inserir.disabled		= false;
							
							busca_novo_status(0);
							listar_servico_agendamento(0);
							
							document.getElementById("cp_opPeriodico").style.display = "block";
							document.getElementById("cp_opAgrupado").style.display = "none";
							document.getElementById('cpAgendamentosCadastrados').style.display = 'none';
							document.formulario.bt_alterar.disabled = true;
							document.formulario.Acao.value = "inserir";
						}
						
						document.formulario.IdServico.focus();
						break;	
					case "ServicoCFOP":
						if(IdTipoServico == 1 || IdTipoServico == 4){
							addParmUrl("marServico","IdServico",IdServico);
							addParmUrl("marServicoValor","IdServico",IdServico);
							addParmUrl("marServicoValorNovo","IdServico",IdServico);
							addParmUrl("marServicoParametro","IdServico",IdServico);
							addParmUrl("marServicoParametroNovo","IdServico",IdServico);
							addParmUrl("marServicoRotina","IdServico",IdServico);
							addParmUrl("marMascaraVigencia","IdServico",IdServico);
							addParmUrl("marMascaraVigenciaNovo","IdServico",IdServico);
							addParmUrl("marMascaraStatus","IdServico",IdServico);
							addParmUrl("marMascaraStatusNovo","IdServico",IdServico);
							addParmUrl("marAliquota","IdServico",IdServico);
							addParmUrl("marAliquotaNovo","IdServico",IdServico);
							addParmUrl("marServicoParametroNotaFiscal","IdServico",IdServico);
							addParmUrl("marServicoParametroNotaFiscalNovo","IdServico",IdServico);
							addParmUrl("marAgendamento","IdServico",IdServico);
							addParmUrl("marServicoCFOP","IdServico",IdServico);
						
							document.formulario.IdServico.value			= IdServico;
							document.formulario.IdTipoServico.value		= IdTipoServico;							
							document.formulario.DescricaoServico.value	= DescricaoServico;
							
							busca_cfop_opcoes(IdServico);
							listar_cfop_servico(IdServico);
							
							document.formulario.Acao.value = "inserir";
							document.formulario.IdServico.focus();
						}else{
							document.formulario.IdServico.value = "";
						}
						break;	
					default:
						document.formulario.IdServico.value					=	IdServico;
						document.formulario.DescricaoServico.value			=	DescricaoServico;
						break;
				}
			}
			
			if(document.getElementById("quadroBuscaServico") != null) {
				if(document.getElementById("quadroBuscaServico").style.display == "block") {
					document.getElementById("quadroBuscaServico").style.display = "none";
				}
			}
			
			if(document.getElementById("quadroBuscaServicoAgrupador") != null) {
				if(document.getElementById("quadroBuscaServicoAgrupador").style.display == "block") {
					document.getElementById("quadroBuscaServicoAgrupador").style.display = "none";
				}
			}
			
			verificaAcao();
		})
	}
	function calculaPeriodicidade(IdPeriodicidade,valor,campo){
		if(campo == '' || campo == undefined){
			campo = document.formulario.ValorPeriodicidade;
		}
		
		if(valor != ''){
			if(valor.indexOf(",") != -1){	
				valor = valor.replace(/\./g,'');
				valor = valor.replace(/,/i,'.');
			}
			
			valor = parseFloat(valor);
		    var url = "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){	
					var nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					var Fator = nameTextNode.nodeValue;
					
					campo.value = valor*parseInt(Fator);
					campo.value = formata_float(Arredonda(campo.value,2),2).replace(/\./,",");
				}
			});
		} else{
			if(document.formulario.Local.value == 'Contrato'){//&& campo == ''){
				document.formulario.ValorServico.value 			= '0,00';
				document.formulario.ValorPeriodicidade.value	= '0,00';
				document.formulario.ValorFinal.value			= '0,00';
				campo.value 									= '0,00';
				document.formulario.ValorDesconto.value			= '0,00';
			} else if(document.formulario.Local.value == 'ContratoServico'){
				calculaPeriodicidadeServico(document.formulario.IdPeriodicidade.value,document.formulario.ValorServico.value,document.formulario.ValorPeriodicidade);
			}
		}
	}
	function servico_botao_auxiliar_img_1(){
		// FUNÇÃO UTILIZADA PARA FAZER O CARREGAMENTO DAS INSTRUÇÕES PARA O BOTÃO DO PARAMETRO DO SERVIÇO
	}
	function servico_botao_auxiliar_img_2(){
		// FUNÇÃO UTILIZADA PARA FAZER O CARREGAMENTO DAS INSTRUÇÕES PARA O BOTÃO DO PARAMETRO DO SERVIÇO
	}
	function listarServicoParametro(IdServico,Erro,IdContrato){
		while(document.getElementById('tabelaParametro').rows.length > 0){
			document.getElementById('tabelaParametro').deleteRow(0);
		}
		
		if(IdServico == ''){
			IdServico = 0;
		}
		
		var Local = document.formulario.Local.value;
		var url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdStatus=1";
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				if(document.formulario.Local.value == 'OrdemServico'){
					document.getElementById('cp_parametrosServico').style.display = 'none';	
				}
			} else{
				var tam, linha, c0, padding, invisivel = "", obsTemp = new Array(), cont = 0, tamMax = "";
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obrigatorio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDefault = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdParametroServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Editavel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Editavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obs = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoParametro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdMascaraCampo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var OpcaoValor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PermissaoInserir")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PermissaoInserir = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PermissaoEditar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PermissaoEditar = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PermissaoVisualizar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PermissaoVisualizar = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Visivel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("VisivelOS")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var VisivelOS = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RestringirGrupoUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RestringirGrupoUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("CalculavelOpcoes")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var CalculavelOpcoes = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RotinaOpcoesContrato")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RotinaOpcoesContrato = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoTexto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoTexto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("BotaoAuxiliar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var BotaoAuxiliar = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("BotaoAuxiliarIMG")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var BotaoAuxiliarIMG = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMinimo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMinimo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMaximo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMaximo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ExibirSenha")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ExibirSenha = nameTextNode.nodeValue;
					
					//if(Local == 'OrdemServico'){
						//Visivel = VisivelOS;
					//}
					
					if(!(Visivel == '1' && RestringirGrupoUsuario == '1')) {
						Visivel = '2';
					}
					
					if(PermissaoVisualizar != '1') {
						Visivel = '2';
						Obrigatorio = "2";
					}
					
					if(document.formulario.Acao.value != "inserir"){
						if(PermissaoEditar != '1'){
							Obrigatorio = "2";
							Editavel = "2";
						}
					} else if(PermissaoInserir != '1'){
						Obrigatorio = "2";
						Editavel = "2";
					}
					
					if(Visivel == '1'){
						tam = document.getElementById('tabelaParametro').rows.length;
						obsTemp[cont] = Obs;
						
						if(cont%2 == 0){
							linha	 = document.getElementById('tabelaParametro').insertRow(tam);
							tabindex = 23 + cont + 1;
							padding	 = 22;	
							pos		 = 0;
						} else{
							padding	 = 10;	
							tabindex = 23 + cont;
							pos		 = 1;
							
							if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
								if(Obs	==	'')	Obs	=	'<BR>';
							}
						}
						
						if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length && cont%2 == 0){
							padding = 22;
						}
						
						if(Obrigatorio == 1){
							var color = "#C10000";
						} else{
							var color = "#000000";
						}
						
						if(Editavel == 2){
							disabled = "disabled";
						} else{
							disabled = "";
						}
						
						linha.accessKey = IdParametroServico; 
						
						c0	= linha.insertCell(pos);
						c0.style.verticalAlign = "top";
						
						if(TamMaximo!="" && Editavel==1){
							tamMax = "maxlength='"+TamMaximo+"'";
						} else{
							tamMax = "";
						}
						
						if(IdTipoParametro == 1){
							c0.style.verticalAlign = "top";
							var style = "width:399px;";
							var ComplementoHTMLInicio = "";
							var ComplementoHTMLFim = "";
							
							switch(BotaoAuxiliar){
								case "1":
									if(pos == 1){
										style = "width:374px;";
									} else {
										style = "width:375px;";
									}
									
									servico_botao_auxiliar_img_1 = function (Campo){
										if(Campo.value != ""){
											window.open((/(^[\w]*([:]*\/\/))/i).test(Campo.value) ? Campo.value : "//"+Campo.value);
										}
									};
									
									ComplementoHTMLInicio = "<tbody><table cellpadding='0' cellspacing='0'><tr><td>";
									ComplementoHTMLFim = "</td><td style='vertical-align:top;'><img style='margin:17px 0 0 4px; cursor:pointer;' src=\""+BotaoAuxiliarIMG+"\" onClick=\"servico_botao_auxiliar_img_1(document.formulario.Valor_"+IdParametroServico+");\" /></td></table></tbody>";
									break;
								case "2":
									if(pos == 1){
										style = "width:374px;";
									} else {
										style = "width:375px;";
									}
									
									servico_botao_auxiliar_img_2 = function (Campo, TamMaximo, AlphaNumerico){
										var Caracter = (AlphaNumerico ? new Array("a", "s", "d", "f", "g", "h", "j", "k", "l", "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "z", "x", "c", "v", "b", "n", "m", "A", "S", "D", "F", "G", "H", "J", "K", "L", "Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P", "Z", "X", "C", "V", "B", "N", "M", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9") : new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9"));
										var TamMaximo = parseInt(TamMaximo);
										
										if(!isNaN(TamMaximo)){
											var STR = "";
											
											for(var i = 0; i < TamMaximo; i++){
												STR += Caracter[Math.floor(Math.random()*Caracter.length)];
											}
											
											Campo.value = STR;
										}
									};
									
									ComplementoHTMLInicio = "<tbody><table cellpadding='0' cellspacing='0'><tr><td>";
									ComplementoHTMLFim = "</td><td style='vertical-align:top;'><img style='margin:17px 0 0 5px; cursor:pointer;' src=\""+BotaoAuxiliarIMG+"\" onClick=\"servico_botao_auxiliar_img_2(document.formulario.Valor_"+IdParametroServico+", '"+TamMaximo+"', "+(IdTipoTexto == "1" && IdMascaraCampo != "2")+");\" /></td></table></tbody>";
									break;
								case "3":
									if(pos == 1){
										style = "width:374px;";
									} else {
										style = "width:375px;";
									}
									
									ComplementoHTMLInicio = "<tbody><table cellpadding='0' cellspacing='0'><tr><td>";
									ComplementoHTMLFim = "</td><td style='vertical-align:top;'><img id='cpDataBotaoAuxiliarIco_"+IdParametroServico+"' style='margin:18px 0 0 7px; cursor:pointer;' alt='Buscar data' title='Buscar data' src=\""+BotaoAuxiliarIMG+"\" /></td></table></tbody>";
									
									(function runTimeAUX(IdParametroServico) {
										var Campo = eval("document.formulario.Valor_"+IdParametroServico+";");
										
										if(Campo == undefined || document.getElementById("cpDataBotaoAuxiliar_"+IdParametroServico) == null){
											if(typeof Campo == "object"){
												Campo.setAttribute("id", "cpDataBotaoAuxiliar_"+IdParametroServico);
											}
											
											setTimeout(function (){
												runTimeAUX(IdParametroServico);
											}, 111);
										} else {
											Calendar.setup({
												inputField : "cpDataBotaoAuxiliar_"+IdParametroServico,
												checkReadOnly : false,
												ifFormat : "%d/%m/%Y",
												button : "cpDataBotaoAuxiliarIco_"+IdParametroServico
											});
										}
									})(IdParametroServico);
									break;
							}
							
							switch(IdTipoTexto){
								case '1': // Texto
									switch(IdMascaraCampo){
										case '1': // Data (dd/mm/yyyy)
											c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex='"+tabindex+"' /><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"' /><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"' /><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"' /><BR />"+Obs+"</p>"+ComplementoHTMLFim;
											break;
										case '2': // Inteiro
											c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'int')\" tabindex='"+tabindex+"' /><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"' /><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"' /><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"' /><BR />"+Obs+"</p>"+ComplementoHTMLFim;
											break;
										case '3': // Real (1.111,00)
											if(Editavel == 1){
												c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'float')\" "+tamMax+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>"+ComplementoHTMLFim;
											}else{
												c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>"+ComplementoHTMLFim;
											}
											break;
										case '4': // Usuário
											c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'usuario')\" "+tamMax+" tabindex='"+tabindex+"' /><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"' /><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"' /><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"' /><BR />"+Obs+"</p>"+ComplementoHTMLFim;
											break;
										case '5': // MAC (AA:BB:CC:DD:EE:FF)
											c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>"+ComplementoHTMLFim;
											break;
										default:
											c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>"+ComplementoHTMLFim;
									}
									break;
								case '2': // Senha
									if(ExibirSenha == 2){
										tipo = "password";
									} else{
										tipo = "text";
									}
									
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='"+tipo+"' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
								case '3': // GRUPO RADIUS
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" tabindex='"+tabindex+"' readOnly><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
								case '4': // IPv5
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
								case '5': // IPv6
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
								case '6': // Asterisk
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex='"+tabindex+"'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'><BR>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
							}
						} else{
							campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
							campo += "<B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p>";
							campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
							
							if(Editavel == 2){
								disabled = "disabled";
							} else{
								disabled = "";
							}
							
							if(pos == 1){
								campo += "<select name='Valor_"+IdParametroServico+"'  style='width:405px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex='"+tabindex+"' "+disabled+">";
							} else {
								campo += "<select name='Valor_"+IdParametroServico+"'  style='width:404px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+disabled+" tabindex='"+tabindex+"' "+disabled+">";
							}
							
							campo += "<option value=''></option>";
							
							var valor = OpcaoValor.split("\n");
							
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								
								if(trim(ValorDefault) == trim(valor[ii])){
									selecionado = "selected=true";
								}
								
								campo += "<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							
							campo += "</select>";
							campo += "<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'>";
							campo += "<BR>"+Obs+"</p>";
							
							c0.innerHTML = campo;
						}
						
						cont++;
					}else{
						invisivel += "<div style='display:none'>";
						
						if(IdTipoParametro == 1){
							invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'>";
						} else{
							campo = "";
							campo += "<select name='Valor_"+IdParametroServico+"'  style='width:406px;'>";
							campo += "<option value=''></option>";
							
							var valor = OpcaoValor.split("\n");
							
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								
								if(trim(ValorDefault) == trim(valor[ii])){
									selecionado = "selected=true";
								}
								
								campo += "<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							
							campo += "</select>";
							campo += "<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='Visivel_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimo_"+IdParametroServico+"' value='"+TamMinimo+"'>";
							
							invisivel += campo;
						}
						
						invisivel += "</div>";
					}
				}
				
				if(cont > 0){
					document.getElementById('cp_parametrosServico').style.display	= 'block';
					document.getElementById('tabelaParametro').style.display		= 'block';
				}
				
				if(invisivel !=""){
					tam 	= document.getElementById('tabelaParametro').rows.length;
					linha	= document.getElementById('tabelaParametro').insertRow(tam);
					
					linha.accessKey = IdParametroServico; 
					
					c0 = linha.insertCell(0);
					c0.innerHTML = invisivel;
				}
			}
		});
	}
	function remover_filtro_servico(IdServico){
		for(var i=0; i<document.getElementById('tabelaServico').rows.length; i++){
			if(IdServico == document.getElementById('tabelaServico').rows[i].accessKey){
				document.getElementById('tabelaServico').deleteRow(i);
				tableMultColor('tabelaServico');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdServico){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdServico.value = novoFiltro;
		document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii-1);
	}	
	function calculaPeriodicidadeTerceiro(IdPeriodicidade,Valor,Campo) {
		if(Campo == '' || Campo == undefined) {
			Campo = document.formulario.ValorPeriodicidadeTerceiro;
		}
		
		if(Valor != '') {
			if(Valor.indexOf(",") != -1) {
				Valor = Valor.replace(/\./g,'');
				Valor = Valor.replace(/,/i,'.');
			}
			
			Valor = parseFloat(Valor);
			var url = "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade;
			
			call_ajax(url, function (xmlhttp) {
				if(xmlhttp.responseText != 'false') {
					var nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					var Fator = nameTextNode.nodeValue;
					Valor = Valor * parseInt(Fator);
					
					if(isNaN(Valor)) {
						Valor = 0;
					}
					
					Campo.value = formata_float(Arredonda(Valor,2),2).replace(/\./i,",");
				}
			});
		} else {
			if((document.formulario.Local.value == 'Contrato' || document.formulario.Local.value == 'ContratoServico') && Campo == '') {
				document.formulario.ValorServico.value 		 = '0,00';
				document.formulario.ValorPeriodicidade.value = '0,00';
			}
		}
	}
	function listar_terceiros(IdServico,IdTerceiroTemp,Anterior) {
		if(Anterior == undefined) {
			Anterior = false;
		}
		
		if(IdServico == '' || IdServico == undefined) {
			IdServico = 0;
		}
		
		if(Anterior) {
			while(document.formulario.IdTerceiroAnterior.options.length > 0) {
				document.formulario.IdTerceiroAnterior.options[0] = null;
			}
		} else {
			while(document.formulario.IdTerceiro.options.length > 0) {
				document.formulario.IdTerceiro.options[0] = null;
			}
			
			document.formulario.Terceiros.value = '';
		}
		
		call_ajax("xml/servico_terceiro.php?IdServico="+IdServico, function (xmlhttp) {
			if(xmlhttp.responseText != "false") {
				addOption(document.formulario.IdTerceiro,' ','');
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdTerceiro").length; i++) {
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdTerceiro")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdTerceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorRepasseTerceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PercentualRepasseTerceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiroOutros")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var PercentualRepasseTerceiroOutros = nameTextNode.nodeValue;
					
					if(IdTerceiro != '') {
						if(Anterior) {
							addOption(document.formulario.IdTerceiroAnterior,Nome,IdTerceiro);
						} else {
							addOption(document.formulario.IdTerceiro,Nome,IdTerceiro);
							
							var valor = IdTerceiro+"_"+ValorRepasseTerceiro+"_"+PercentualRepasseTerceiro+"_"+PercentualRepasseTerceiroOutros;
							
							if(document.formulario.Terceiros.value != '') {
								document.formulario.Terceiros.value += ","+valor;
							} else {
								document.formulario.Terceiros.value += valor;
							}
						}
					}
				}
				
				if(Anterior) {
					if(IdTerceiroTemp != '' && IdTerceiroTemp != undefined) {
						document.formulario.IdTerceiroAnterior.value = IdTerceiroTemp;
					} else {
						document.formulario.IdTerceiroAnterior.value = '';
					}
				} else {
					document.formulario.IdTerceiro.disabled = false;
					
					if(document.formulario.Local.value != "OrdemServicoFatura") {
						if(Number(document.formulario.ObrigatorioTerceiro.value) == 1) {
							document.getElementById('cpTerceiro').style.color = "#C10000";
						} else {
							document.getElementById('cpTerceiro').style.color = "#000";	
						}
					}
					
					if(IdTerceiroTemp != '' && IdTerceiroTemp != undefined) {
						document.formulario.IdTerceiro.value = IdTerceiroTemp;
						
						if(document.formulario.Local.value == "OrdemServicoFatura") {
							obter_valor_repasse_terceiro(document.formulario.IdTerceiro.value);
						}
					} else {
						document.formulario.IdTerceiro.value = '';
					}
				}
			} else {
				if(!Anterior) {
					document.formulario.IdTerceiro.disabled = true;
					document.getElementById('cpTerceiro').style.color = "#000";	
				}
			}
		});
	}
	function obter_valor_repasse_terceiro(IdTerceiro) {
		var Local = document.formulario.Local.value;
	
		if((document.formulario.IdContrato.value == "" && Local == "Contrato") || Local != "Contrato") {
			if(document.formulario.Terceiros.value != '' && IdTerceiro != '') {
				var Valor = document.formulario.Terceiros.value.split(',');
				IdTerceiro += "[_0-9\.]*";
				
				for(var i = 0; i < Valor.length; i++) {
					if((new RegExp(IdTerceiro)).test(Valor[i])) {
						Valor = Valor[i].split("_");
						var ValorRepasseTerceiro = parseFloat(Valor[1]);
						var PercentualRepasseTerceiro = parseFloat(Valor[2]);
						var ValorRepasseTerceiroOutros = 0;
						var PercentualRepasseTerceiroOutros = parseFloat(Valor[3]);
						break;
					}
				}
			} else {
				var ValorRepasseTerceiro = 0;
				var PercentualRepasseTerceiro = 0;
				var ValorRepasseTerceiroOutros = 0;
				var PercentualRepasseTerceiroOutros = 0;
			}
			
			if(Local == "OrdemServicoFatura") {
				Valor = document.formulario.Valor.value.replace(/\./g,'').replace(/,/i,'.');
				var ValorOutros = document.formulario.ValorOutros.value.replace(/\./g,'').replace(/,/i,'.');
			} else {
				var ValorOutros = Valor = document.formulario.ValorServico.value.replace(/\./g,'').replace(/,/i,'.');
			}
			
			if(ValorRepasseTerceiro == '' && PercentualRepasseTerceiro != '') {
				ValorRepasseTerceiro = parseFloat(Valor) * parseFloat(PercentualRepasseTerceiro) / 100;
				ValorRepasseTerceiroOutros = parseFloat(ValorOutros) * parseFloat(PercentualRepasseTerceiroOutros) / 100;
			} else if(Local == "OrdemServicoFatura" && ValorRepasseTerceiro != '') {
				PercentualRepasseTerceiro = parseFloat(ValorRepasseTerceiro) * 100 / parseFloat(Valor);
			}
			
			if(ValorRepasseTerceiro == '') {
				ValorRepasseTerceiro = '0';
			}
			
			if(isNaN(ValorRepasseTerceiro)) {
				ValorRepasseTerceiro = 0.00;
			}
			
			
			if(Local == "OrdemServicoFatura") {
				if(document.formulario.AtualizarValorRepasseTerceiro.value == 0) {
					document.formulario.AtualizarValorRepasseTerceiro.value = 1;
					//ValorRepasseTerceiro = Number(document.formulario.ValorRepasseTerceiro.value.replace(/\./g,'').replace(/,/i,'.')) - ValorRepasseTerceiroOutros;
					PercentualRepasseTerceiro = ((ValorRepasseTerceiro * 100) / Valor);
				}
				
				document.formulario.ValorRepasseTerceiroOutros.value = formata_float(Arredonda(ValorRepasseTerceiroOutros,2),2).replace(/\./i,',');
				document.formulario.PercentualRepasseTerceiro.value = formata_float(Arredonda(PercentualRepasseTerceiro,2),2).replace(/\./i,',');
				document.formulario.PercentualRepasseTerceiroOutros.value = formata_float(Arredonda(PercentualRepasseTerceiroOutros,2),2).replace(/\./i,',');
				document.getElementById("obsValorRepasseTerceiro").innerHTML = formata_float(Arredonda(PercentualRepasseTerceiro,2),2).replace(/\./i,',')+" (%)";
				document.getElementById("obsValorRepasseTerceiroOutros").innerHTML = formata_float(Arredonda(PercentualRepasseTerceiroOutros,2),2).replace(/\./i,',')+" (%)";
			}
			
			document.formulario.ValorRepasseTerceiro.value = formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace(/\./i,',');
			
			if(Local == "Contrato") {
				calculaPeriodicidadeTerceiro(document.formulario.IdPeriodicidade.value,document.formulario.ValorRepasseTerceiro.value,document.formulario.ValorPeriodicidadeTerceiro);
			}
		}
	}
