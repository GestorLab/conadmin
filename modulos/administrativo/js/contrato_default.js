	function janela_busca_contrato(){
		janelas('../administrativo/busca_contrato.php',530,350,250,100,'');
	}
	function janela_busca_contrato2(){
		janelas('../administrativo/busca_contrato2.php',530,350,250,100,'');
	}
	function busca_contrato(IdContrato,Erro,Local,IdPessoa,ListarCampo){
		if(IdContrato == '' || IdContrato == undefined){
			IdContrato = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		if(Local == 'OrdemServico' && document.formulario.IdTipoOrdemServico.value != 2){
			if(document.formulario.IdPessoa != ''){
				IdPessoa = document.formulario.IdPessoa.value;
			} else{
				IdPessoa = document.formulario.IdPessoaF.value;
			}
			
			if(document.formulario.IdPessoa.value == '' && document.formulario.IdPessoaF.value == ''){
				document.formulario.IdPessoa.focus();
				mensagens(104);
				return false;
			}
		}
		
		if(IdPessoa == undefined){
			IdPessoa = '';
		}
		
	   	var url = "xml/contrato.php?IdContrato="+IdContrato;
		if(Local == 'Protocolo'){
			if(document.formulario.IdPessoa != ''){
				IdPessoa = document.formulario.IdPessoa.value;
			} else{
				IdPessoa = document.formulario.IdPessoaF.value;
			}
			
			url	+= "&IdStatusExc=1";
			
		} else{
			if(IdPessoa != '' && IdPessoa != undefined){
				url += "&IdPessoa="+IdPessoa;
			}
		}
		
		url = url + "&" + Math.random();
		
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				if(Local!='Agendamento' && Local!= 'ProcessoFinanceiro' && Local != 'LancamentoFinanceiro' && Local != 'HelpDesk' && Local != 'ForcarExclusaoContratoVinculos' && Local != 'MalaDireta'){
					addParmUrl("marContrato","IdContrato","");
					addParmUrl("marContasReceber","IdContrato","");
					addParmUrl("marLancamentoFinanceiro","IdContrato","");
					addParmUrl("marProcessoFinanceiro","IdContrato","");
					addParmUrl("marProcessoFinanceiroNovo","IdContrato","");
					addParmUrl("marOrdemServicoNovo","IdContrato","");
					addParmUrl("marOrdemServico","IdContrato","");
					addParmUrl("marVigencia","IdContrato","");
					addParmUrl("marVigenciaNovo","IdContrato","");
				}
				
				switch(Local){
					case 'ContratoAtivar':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServico.value					=	"";
						document.formulario.DescricaoServico.value			=	"";
						document.formulario.DescPeriodicidade.value			=	"";
						document.formulario.QtdParcela.value				=	"";
						document.formulario.DataInicio.value				=	"";
						document.formulario.DataTermino.value				=	"";
						document.formulario.DataBaseCalculo.value			=	"";
						document.formulario.DataUltimaCobranca.value		=	"";
						document.formulario.DataPrimeiraCobranca.value		=	"";
						document.formulario.AssinaturaContrato.value		=	"";
						document.formulario.TipoContrato[0].selected		=	true;
						document.formulario.DataAtivacaoInicio.value		=	"";
						document.formulario.DataAtivacaoFim.value			=	"";
						document.formulario.AgruparContrato.value			=	"";
						document.formulario.DataPrimeiroVenc.value			=	"";
						document.formulario.DiaCobranca.value				=	"";
						
						document.getElementById('cp_juridica').style.display			= 'block';
						document.getElementById('cp_fisica').style.display				= 'none';
						
						document.formulario.IdPessoa.value 			= '';
						document.formulario.IdPessoaF.value 		= '';
						document.formulario.Nome.value 				= '';
						document.formulario.RazaoSocial.value 		= '';
						document.formulario.CPF.value 				= '';
						document.formulario.CNPJ.value 				= '';
						document.formulario.Email.value 			= '';
						document.formulario.Acao.value 				= 'inserir';
						
						verificaAcao();
						break;
					case 'ContratoDataBase':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdContrato.value 				= 	'';
						document.formulario.IdServico.value					=	"";
						document.formulario.DescricaoServico.value			=	"";
						document.formulario.DescPeriodicidade.value			=	"";
						document.formulario.QtdParcela.value				=	"";
						document.formulario.DataInicio.value				=	"";
						document.formulario.DataTermino.value				=	"";
						document.formulario.DataBaseCalculo.value			=	"";
						document.formulario.DataUltimaCobranca.value		=	"";
						document.formulario.DataPrimeiraCobranca.value		=	"";
						document.formulario.AssinaturaContrato.value		=	"";
						document.formulario.TipoContrato[0].selected		=	true;
						
						document.getElementById('cp_juridica').style.display			= 'block';
						document.getElementById('cp_fisica').style.display				= 'none';
						
						document.formulario.IdPessoa.value 			= '';
						document.formulario.IdPessoaF.value 		= '';
						document.formulario.Nome.value 				= '';
						document.formulario.RazaoSocial.value 		= '';
						document.formulario.CPF.value 				= '';
						document.formulario.CNPJ.value 				= '';
						document.formulario.Email.value 			= '';
						document.formulario.Acao.value 				= 'inserir';
						
						verificaAcao();
						document.formulario.IdContrato.focus();
						break;
					case 'Protocolo':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.QtdParcelaContrato.value		=	"";
						document.formulario.DataInicio.value				=	"";
						document.formulario.DataTermino.value				=	"";
						document.formulario.DataBaseCalculo.value			=	"";
						document.formulario.DataUltimaCobranca.value		=	"";
						document.formulario.AssinaturaContrato.value		=	"";
						document.formulario.TipoContrato.value				=	"";
						break;
					case 'OrdemServico':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.QtdParcelaContrato.value		=	"";
						document.formulario.DataInicio.value				=	"";
						document.formulario.DataTermino.value				=	"";
						document.formulario.DataBaseCalculo.value			=	"";
						document.formulario.DataUltimaCobranca.value		=	"";
						document.formulario.AssinaturaContrato.value		=	"";
						document.formulario.TipoContrato.value				=	"";
						document.formulario.Periodicidade.value				=	"";
						
						for(var i=0; i<document.formulario.TipoContrato.length; i++){
							if(document.formulario.TipoContrato[i].value == ''){
								document.formulario.TipoContrato[i].selected = true;
								i = document.formulario.TipoContrato.length;
							}
						}

						while(document.getElementById('tabelaParametro').rows.length > 1){
							document.getElementById('tabelaParametro').deleteRow(0);
						}
						
						if(document.getElementById('tabelaParametroContrato').style.display == 'none' || document.getElementById('tabelaParametroContrato').style.display == ''){
							document.getElementById('cp_parametrosServico').style.display	= 'none';
						}

						document.formulario.IdServico.value					=	"";
						document.formulario.DescricaoServico.value			=	"";
						document.formulario.IdTipoServico.value				= 	"";
						document.formulario.DetalheServico.value			=	"";
						document.formulario.IdOrdemServicoLayout.value		=	"";
						document.formulario.ValorOutros.value				=	"0,00";
						document.formulario.Justificativa.value				=	"";
						document.formulario.DescricaoOS.value				=	"";
						document.formulario.Valor.value						=	"0,00";
						
						calcula_total();
						
						if(document.formulario.IdTipoOrdemServico.value != 2 && document.formulario.Acao.value == 'inserir'){
							document.formulario.IdServico.focus();
						}
						
						if(document.formulario.Local.value == 'OrdemServico'){
							document.formulario.ServicoAutomatico.value			=	"";
							
							document.getElementById('cp_parametrosContrato').style.display	=	'none';
							document.getElementById("cp_automatico").style.display			= 	"none";
							document.getElementById('cp_automatico').innerHTML				= 	"";	
							
							document.formulario.IdContrato.focus();	
						}
						
						break;
					case 'Agendamento':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.DataInicio.value				=	"";
						document.formulario.DataTermino.value				=	"";
						document.formulario.DataBaseCalculo.value			=	"";
						document.formulario.DataUltimaCobranca.value		=	"";
						document.formulario.AssinaturaContrato.value		=	"";
						document.formulario.TipoContrato.value				=	"";
						break;
					case 'Contrato':
						document.formulario.IdContrato.value				=	"";	
						document.formulario.IdPessoa.value					=	"";
						document.formulario.IdPessoaF.value 				= 	'';
						document.formulario.Nome.value						=	"";
						document.formulario.IdServico.readOnly				=	false;
						document.formulario.IdPessoa.readOnly				=	false;
						document.formulario.IdPessoaF.readOnly				=	false;
						document.formulario.IdServico.value					=	"";
						document.formulario.DescricaoServico.value			=	"";
						document.formulario.DescricaoServicoGrupo.value		=	"";
						document.formulario.IdPeriodicidade.value			=	"";
						document.formulario.Periodicidade.value				=	"";
						document.formulario.QtdParcela.value				=	"";
						document.formulario.QuantParcela.value				=	"";
						document.formulario.DataInicio.value				=	"";
						document.formulario.DataTermino.value				=	"";
						document.formulario.DataBaseCalculo.value			=	"";
						document.formulario.DataPrimeiraCobranca.value		=	"";
						document.formulario.DataUltimaCobranca.value		=	"";
						document.formulario.AssinaturaContrato.value		=	"";
						document.formulario.IdLocalCobranca.value			=	"";
						document.formulario.IdLocalCobrancaTemp.value	 	= 	"";
						document.formulario.IdStatus.value 					= 	"";
						document.formulario.IdCarteira.disabled				=	true;
						document.formulario.DataTermino.readOnly			=	false;
						document.formulario.DataUltimaCobranca.readOnly		=	false;
						document.formulario.IdLocalCobranca.value			=	"";
						document.formulario.IdCarteira.value				=	"";
						document.formulario.IdCarteiraTemp.value			=	"";
						document.formulario.IdAgenteAutorizado.value		=	"";
						document.formulario.IdContratoAgrupador.value		=	"";
						document.formulario.IdContratoAgrupador.disabled	=	false;
						document.formulario.AdequarLeisOrgaoPublico.value	=	"";
						document.formulario.NotaFiscalCDA.value				=	"";
						document.formulario.ValorServico.value				=	"";
						document.formulario.ValorServico.tabIndex			=	"18";
						document.formulario.ValorDesconto.value				=	"";
						document.formulario.ValorFinal.value				=	"";
						document.formulario.ValorRepasseTerceiro.value		=	"";
						document.formulario.ValorRepasseTerceiro.tabIndex	=	"19";
						document.formulario.ValorPeriodicidade.value		=	"";
						document.formulario.ValorPeriodicidadeTerceiro.value=	"";
						document.formulario.DataCriacao.value				=	"";
						document.formulario.LoginCriacao.value				=	"";
						document.formulario.DataAlteracao.value				=	"";
						document.formulario.LoginAlteracao.value			=	"";
						document.formulario.TipoContrato.value				=	"";
						document.formulario.TipoContratoTemp.value			=	"";
						document.formulario.Obs.value						=	"";
						document.formulario.UrlContratoImpresso.value		=	"";
						document.formulario.UrlDistratoImpresso.value		=	"";
						document.formulario.Redirecionar.value				=	"";
						document.formulario.MesFechado.value 				= 	"";
						document.formulario.MesFechadoTemp.value			=	"";
						document.formulario.ValorServico.readOnly			= 	false;
						document.formulario.QtdMesesFidelidade.value		=	"";
						document.formulario.QtdMesesFidelidadeTemp.value	=	"";
						document.formulario.ServicoAutomatico.value			=	"";
						document.formulario.HistoricoObs.value				=	"";
						document.formulario.MultaFidelidade.value			=	"";
						document.formulario.ValorMultaFidelidade.value		=	"";
						document.formulario.IdTipoLocalCobranca.value		=	"";
						document.formulario.DiaCobranca.value				=	0;
						document.formulario.DiaCobrancaTemp.value			=	"";
						document.formulario.DiaCobranca.disabled			=	false;
						document.formulario.IdCarteira.disabled 			= 	false;
						document.formulario.StatusTempoAlteracao.value 		= 	"";
						document.formulario.Filtro_IdPaisEstadoCidade.value =   "";
						
						document.formulario.NomeRepresentante.value 		=	"";
						document.formulario.InscricaoEstadual.value 		= 	"";
						document.formulario.DataNascimento.value 			=	"";	
						document.formulario.RG.value 						=	"";
						document.formulario.Telefone1.value 				=	"";
						document.formulario.Telefone2.value 				= 	"";
						document.formulario.Telefone3.value 				= 	"";
						document.formulario.Celular.value 					= 	"";	
						document.formulario.Fax.value 						= 	"";
						document.formulario.ComplementoTelefone.value 		= 	"";
						document.formulario.EmailJuridica.value 			= 	"";
						
						document.getElementById('cp_automatico').innerHTML			=	"";
						document.getElementById('cp_automatico').style.display		=	'none';
						document.getElementById('cpStatusContrato').style.display	=	'none';
						document.getElementById('cpHistorico').style.display		=	'none';
						document.getElementById('cp_parametrosServico').style.display		=	'none';
						document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
						document.getElementById('titDesconto').innerHTML			=	'Valor Desconto Mensal';
						
						ocultar_resumo_conexao(true);
						status_inicial();
						
						document.getElementById('cpNotaFiscalCDA').style.display		= 'block';
						document.getElementById('cp_juridica').style.display			= 'block';
						document.getElementById('cp_fisica').style.display				= 'none';
						document.getElementById('helpText2').innerHTML 					= '';
						document.getElementById('helpText2').style.display 				= "none";
						document.getElementById('titMudarServico').style.display 		= "none";
						document.getElementById('titValorServico').style.color	 		= "#C10000";
						document.formulario.bt_carta.style.display						= 'none';
//						document.getElementById('cp_MonitorSinal').style.display		= 'none';
						//document.formulario.AtualizacaoAutomatica.checked				= false;
						
						document.formulario.Nome.value 				= '';
						document.formulario.NomeF.value				= '';
						document.formulario.RazaoSocial.value 		= '';
						document.formulario.CPF.value 				= '';
						document.formulario.CNPJ.value 				= '';
						document.formulario.Email.value 			= '';
						
						while(document.formulario.IdPessoaEndereco.options.length > 0){
							document.formulario.IdPessoaEndereco.options[0] = null;
						}
						while(document.formulario.IdPessoaEnderecoCobranca.options.length > 0){
							document.formulario.IdPessoaEnderecoCobranca.options[0] = null;
						}
						
						document.formulario.IdPessoaEndereco.value					=	"";
						document.formulario.NomeResponsavelEndereco.value			=	"";
						document.formulario.CEP.value								=	"";
						document.formulario.Endereco.value							=	"";
						document.formulario.Numero.value							=	"";
						document.formulario.Complemento.value						=	"";
						document.formulario.Bairro.value							=	"";
						document.formulario.IdPais.value							=	"";
						document.formulario.Pais.value								=	"";
						document.formulario.IdEstado.value							=	"";
						document.formulario.Estado.value							=	"";
						document.formulario.IdCidade.value							=	"";
						document.formulario.Cidade.value							=	"";
						
						document.formulario.IdPessoaEnderecoCobranca.value			=	"";
						document.formulario.NomeResponsavelEnderecoCobranca.value	=	"";
						document.formulario.CEPCobranca.value						=	"";
						document.formulario.EnderecoCobranca.value					=	"";
						document.formulario.NumeroCobranca.value					=	"";
						document.formulario.ComplementoCobranca.value				=	"";
						document.formulario.BairroCobranca.value					=	"";
						document.formulario.IdPaisCobranca.value					=	"";
						document.formulario.PaisCobranca.value						=	"";
						document.formulario.IdEstadoCobranca.value					=	"";
						document.formulario.EstadoCobranca.value					=	"";
						document.formulario.IdCidadeCobranca.value					=	"";
						document.formulario.CidadeCobranca.value					=	"";
						
						while(document.getElementById('tabelaParametro').rows.length > 0){
							document.getElementById('tabelaParametro').deleteRow(0);
						}
						while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0){
							document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
						}
						
						document.getElementById('cp_parametrosServico').style.display	= 'none';
						
						document.getElementById('DataInicio').style.backgroundColor = '#FFFFFF';
						document.getElementById('DataInicio').style.color = '#C10000';
						
						document.getElementById('DataUltimaCobranca').style.backgroundColor = '#FFFFFF';
						document.getElementById('DataUltimaCobranca').style.color = '#000000';
						
						document.formulario.bt_imprimir_distrato.disabled					=	true;
						
						document.getElementById('imgDataTermino').style.display				=	'block';
						document.getElementById('imgDataUltimaCobranca').style.display		=	'block';
						
						document.getElementById('imgDataTerminoDisab').style.display			=	'none';
						document.getElementById('imgDataUltimaCobrancaDisab').style.display		=	'none';
						
						document.formulario.IdContrato.focus();
						document.formulario.Acao.value 				= 'inserir';
						
						listar_carteira();
						listar_terceiros();
						busca_dia_cobranca('',document.formulario.DiaCobrancaDefault.value);
						busca_cfop_servico();
						verificaAcao();
						verificar_local_cobranca("","","");
						busca_monitor();
//						atualizar_grafico();
						verificarPermissaoEdicao("","");
						break;
					case 'ContratoServico':
						document.formulario.IdContrato.value							=	"";
						document.formulario.IdContratoAnterior.value					=	'';
						document.formulario.IdPessoa.value								=	'';
						document.formulario.IdPessoaF.value 							=	'';
						document.formulario.IdServicoAnterior.value						=	'';
						document.formulario.DescricaoServicoAnterior.value				=	'';
						document.formulario.DescPeriodicidadeAnterior.value				=	'';
						document.formulario.QtdParcelaAnterior.value					=	'';
						document.formulario.ValorServicoAnterior.value					=	'';
						document.formulario.TipoContratoAnterior.value					=	'';
						document.formulario.LocalCobrancaAnterior.value					=	'';
						document.formulario.MesFechadoAnterior.value					=	'';
						document.formulario.ValorPeriodicidadeAnterior.value			=	'';
						document.formulario.ValorRepasseTerceiroAnterior.value			=	'';
						document.formulario.ValorPeriodicidadeTerceiroAnterior.value	=	'';
						document.formulario.QtdMesesFidelidadeAnterior.value			=	'';
						document.formulario.ValorMultaFidelidadeAnterior.value			=	'';
						document.formulario.DataInicio.value							=	"";
						document.formulario.DataPrimeiraCobranca.value					=	"";
						document.formulario.DataBaseCalculo.value						=	"";
						document.formulario.DataTermino.value							=	"";
						document.formulario.DataUltimaCobranca.value					=	"";
						document.formulario.AssinaturaContrato.value					=	"";
						document.formulario.QuantParametros.value						=	"";
						document.formulario.QuantParametrosLocalCobranca.value			=	"";
						document.formulario.Periodicidade.value							=	"";
						document.formulario.ParametrosAnterior.value					=	"";
						document.formulario.QuantParcela.value							=	"";
						document.formulario.ServicoAutomatico.value						=	"";
						document.formulario.MultaFidelidade.value						=	"";
						document.formulario.IdStatus.value								=	"";
						document.formulario.TipoContratoTemp.value						=	"";
						document.formulario.MesFechadoTemp.value						=	"";
						document.formulario.IdLocalCobrancaTemp.value					=	"";
						document.formulario.IdTipoServico.value							=	"";
						document.formulario.IdAgenteAutorizadoAnterior.value			=	"";
						document.formulario.IdCarteiraAnterior.value					=	"";
						document.formulario.IdCarteiraTemp.value						=	"";
						document.formulario.ServicoAutomaticoAnterior.value				=	"";
						document.formulario.LancamentoFinanceiroTipoContrato.value		=	'';
						document.formulario.IdCarteira.disabled					=	false;
						
						document.getElementById('cp_juridica').style.display			= 'block';
						document.getElementById('cp_fisica').style.display				= 'none';
						document.getElementById('helpText2').innerHTML 					=  '';
						document.getElementById('helpText2').style.display 				= "none";
						document.getElementById('titMudarServico').style.display 		= "none";
						document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= "CNPJ";
						document.getElementById('cp_parametrosServico').style.display	= 'none';
						
						document.formulario.Nome.value 				= '';
						document.formulario.RazaoSocial.value 		= '';
						document.formulario.Cidade.value 			= '';
						document.formulario.CPF_CNPJ.value 			= '';
						document.formulario.Email.value 			= '';
						document.formulario.Telefone1.value			= '';
						document.formulario.SiglaEstado.value		= '';
						document.formulario.Acao.value 				= 'inserir';
						
						if(document.formulario.PermissaoCancelarContaReceber.value == 1){
							listar_conta_receber_aberto();
						}
			
						limpaFormCredito();
						
						document.formulario.IdContrato.focus();
						document.formulario.Acao.value 				= 'alterar';
						
						scrollWindow('top');
						break;
					case 'ContratoStatus':
						document.formulario.IdContrato.value						=	'';
						document.formulario.IdPessoa.value							=	'';
						document.formulario.IdServico.value							=	'';
						document.formulario.DescricaoServico.value					=	'';
						document.formulario.DescPeriodicidade.value					=	'';
						document.formulario.QtdParcela.value						=	'';
						document.formulario.ValorServico.value						=	'';
						document.formulario.IdStatusAnterior.value					=	'';
						document.formulario.IdStatus.value							=	'';
						document.formulario.VarStatus.value							=	'';
						document.formulario.VarStatusAnterior.value					=	'';
						document.formulario.DataInicio.value						=	'';
						document.formulario.DataPrimeiraCobranca.value				=	'';
						document.formulario.DataBaseCalculo.value					=	'';
						document.formulario.DataTermino.value						=	'';
						document.formulario.DataUltimaCobranca.value				=	'';
						document.formulario.IdStatusAnteriorTemp.value				=	'';
						document.formulario.LancamentoFinanceiroTipoContrato.value	=	'';
						
						document.getElementById('cp_juridica').style.display			= 'block';
						document.getElementById('cp_fisica').style.display				= 'none';
						document.getElementById('helpText2').innerHTML 					=  '';
						document.getElementById('helpText2').style.display 				= "none";
						
						document.formulario.IdPessoa.value 			= '';
						document.formulario.IdPessoaF.value 		= '';
						document.formulario.Nome.value 				= '';
						document.formulario.RazaoSocial.value 		= '';
						document.formulario.CPF.value 				= '';
						document.formulario.CNPJ.value 				= '';
						document.formulario.Email.value 			= '';
						document.formulario.EmailNotificacao.value 	= '';
						
						document.getElementById('cpContaReceberAberto').style.display	=	'none';
						
						while(document.getElementById('tabelaContaReceber').rows.length > 2){
							document.getElementById('tabelaContaReceber').deleteRow(1);
						}
						
						//document.getElementById('cpValorTotal').innerHTML		=	"0,00";
						//document.getElementById('tabelaTotal').innerHTML		=	"Total: 0";
						
						document.formulario.IdContrato.focus();
						document.formulario.Acao.value 				= 'alterar';
						
						verifica_status('');
						scrollWindow('top');
						verificaAcao();
						break;
					case "Vigencia":
						document.formulario.IdContrato.value			=	"";
						document.formulario.IdServico.value				=	"";
						document.formulario.DescricaoServico.value		=	"";
						document.formulario.DescPeriodicidade.value		=	"";
						document.formulario.DataPrimeiraCobranca.value	=	"";
						document.formulario.TipoContrato.value			=	"0";
						document.formulario.AssinaturaContrato.value	=	"0";
						document.formulario.QtdParcela.value			=	"";
						document.formulario.DataInicio.value			=	"";
						document.formulario.DataTermino.value			=	"";
						document.formulario.DataBaseCalculo.value		=	"";
						document.formulario.DataUltimaCobranca.value	=	"";
						document.formulario.AssinaturaContrato.value	=	"";
						document.formulario.TipoContrato.value			=	"";
						document.formulario.Valor.value					=	"";
						document.formulario.ServicoAutomatico.value		=	"";
						document.formulario.Periodicidade.value			=	"";
						document.formulario.IdContratoFilho.value		=	"";
						
						document.getElementById('cp_automatico').innerHTML			=	"";
						document.getElementById('cp_automatico').style.display		=	'none';
						document.getElementById('cp_juridica').style.display		= 	'block';
						document.getElementById('cp_fisica').style.display			= 	'none';
						
						document.formulario.IdPessoa.value 			= '';
						document.formulario.IdPessoaF.value 		= '';
						document.formulario.Nome.value 				= '';
						document.formulario.RazaoSocial.value 		= '';
						document.formulario.CPF.value 				= '';
						document.formulario.CNPJ.value 				= '';
						document.formulario.Email.value 			= '';
						
						listarVigencia(document.formulario.IdContrato.value,false);
						break;
					case "LancamentoFinanceiro":
						document.formulario.IdContrato.value			=	"";
						document.formulario.IdServico.value				=	"";
						document.formulario.DescricaoServico.value		=	"";
						document.formulario.DescPeriodicidade.value		=	"";
						document.formulario.QtdParcela.value			=	"";
						document.formulario.DataInicio.value			=	"";
						document.formulario.DataTermino.value			=	"";
						document.formulario.DataBaseCalculo.value		=	"";
						document.formulario.DataUltimaCobranca.value	=	"";
						document.formulario.AssinaturaContrato.value	=	"";
						document.formulario.TipoContrato.value			=	"";
//								document.getElementById('cpLancamentoFinanceiro').style.display				= 'none';
						break;
					case "LancamentoFinanceiroReferencia":
						document.formulario.IdContrato.value			=	"";
						document.formulario.IdServico.value				=	"";
						document.formulario.DescricaoServico.value		=	"";
						document.formulario.DescPeriodicidade.value		=	"";
						document.formulario.QtdParcela.value			=	"";
						document.formulario.DataInicio.value			=	"";
						document.formulario.DataTermino.value			=	"";
						document.formulario.DataBaseCalculo.value		=	"";
						document.formulario.DataUltimaCobranca.value	=	"";
						document.formulario.AssinaturaContrato.value	=	"";
						document.formulario.TipoContrato.value			=	"";
//								document.getElementById('cpLancamentoFinanceiro').style.display				= 'none';
						break;
					case 'CancelarContrato':	
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdPessoa.value 					= '';
						document.formulario.IdPessoaF.value 				= '';
						document.formulario.Nome.value 						= '';
						document.formulario.RazaoSocial.value 				= '';
						document.formulario.CPF.value 						= '';
						document.formulario.CNPJ.value 						= '';
						document.formulario.Email.value 					= '';
						document.formulario.IdServico.value					=	"";
						document.formulario.DescricaoServico.value			=	"";
						document.formulario.DescPeriodicidade.value			=	"";
						document.formulario.QtdParcela.value				=	"";
						document.formulario.DataInicio.value				=	"";
						document.formulario.DataTermino.value				=	"";
						document.formulario.DataBaseCalculo.value			=	"";
						document.formulario.DataPrimeiraCobranca.value		=	"";
						document.formulario.DataUltimaCobranca.value		=	"";
						document.formulario.AssinaturaContrato.value		=	"";
						document.formulario.IdLocalCobranca[0].selected 	= 	true;
						document.formulario.IdLocalCobranca.value			=	"";
						document.formulario.IdCarteira.value				=	"";
						document.formulario.IdAgenteAutorizado.value		=	"";
						document.formulario.NomeAgenteAutorizado.value		=	"";
						document.formulario.IdContratoAgrupador.value		=	"";
						document.formulario.IdContratoAgrupador.disabled	=	false;
						document.formulario.AdequarLeisOrgaoPublico.value	=	"";
						document.formulario.ValorServico.value				=	"";
						document.formulario.ValorPeriodicidade.value		=	"";
						document.formulario.DataCriacao.value				=	"";
						document.formulario.LoginCriacao.value				=	"";
						document.formulario.DataAlteracao.value				=	"";
						document.formulario.LoginAlteracao.value			=	"";
						document.formulario.TipoContrato.value				=	"";
						document.formulario.Obs.value						=	"";
						
						document.getElementById('cp_juridica').style.display			= 'block';
						document.getElementById('cp_fisica').style.display				= 'none';
						
						document.formulario.IdContrato.focus();
						document.formulario.bt_confirmar.disabled 	= true;
						
						break;
					case 'ProcessoFinanceiro':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.DescTipoContrato.value			=	"";
						
						document.formulario.IdContrato.focus();
						break;
					case 'MalaDireta':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.DescTipoContrato.value			=	"";
						
						document.formulario.IdContrato.focus();
						break;
					case 'AdicionarProcessoFinanceiro':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.DescTipoContrato.value			=	"";
						
						
						document.formulario.IdContrato.focus();
						break;
					case 'AdicionarContrato':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.DescTipoContrato.value			=	"";
						
						document.formulario.IdContrato.focus();
						break;
					case 'AdicionarMalaDireta':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.DescTipoContrato.value			=	"";
						
						document.formulario.IdContrato.focus();
						break;
					case 'HelpDesk':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.DescTipoContrato.value			=	"";
						document.formulario.Status.value					=	"";
						break;
					case 'ForcarExclusaoContratoVinculos':
						document.formulario.IdContrato.value				=	"";
						document.formulario.IdServicoContrato.value			=	"";
						document.formulario.DescricaoServicoContrato.value	=	"";
						document.formulario.DescPeriodicidadeContrato.value	=	"";
						document.formulario.DescTipoContrato.value			=	"";
						
						document.formulario.IdContrato.focus();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoa = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPeriodicidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescPeriodicidade = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QtdParcela = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataInicio = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataTermino")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataTermino = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataBaseCalculo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataBaseCalculo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataUltimaCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataUltimaCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiraCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataPrimeiraCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("AssinaturaContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var AssinaturaContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("TipoContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var TipoContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MesFechado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MesFechado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdAgenteAutorizado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeAgenteAutorizado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeAgenteAutorizado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCarteira = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoAgrupador")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContratoAgrupador = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdLocalCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoLocalCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("AdequarLeisOrgaoPublico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var AdequarLeisOrgaoPublico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NotaFiscalCDA")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NotaFiscalCDA = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Obs = nameTextNode.nodeValue;
				
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("UrlContratoImpresso")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var UrlContratoImpresso = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("UrlDistratoImpresso")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var UrlDistratoImpresso = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Valor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("VarStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var VarStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorRepasseTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("AtivacaoAutomatica")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var AtivacaoAutomatica = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataReferenciaFinal")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataReferenciaFinal = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMesesFidelidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QtdMesesFidelidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("MultaFidelidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var MultaFidelidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DiaCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DiaCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoPai")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContratoPai = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("TipoPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var TipoPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTerceiro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTerceiro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("TipoMonitor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var TipoMonitor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdSNMP")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdSNMP = nameTextNode.nodeValue;
				
				if(IdContratoPai != ""){
					busca_contrato(IdContratoPai,false,Local);
				} else{
					if(Local == 'OrdemServico'){
						addParmUrl("marContrato","IdContrato",IdContrato);
					}
					if(Local!='Agendamento' && Local!= 'ProcessoFinanceiro' && Local!= 'AdicionarContrato' && Local != 'LancamentoFinanceiro' && Local != 'HelpDesk' && Local != 'ForcarExclusaoContratoVinculos' && Local != 'MalaDireta'  && Local != 'AdicionarMalaDireta' && Local != 'OrdemServico'){
						
						//addParmUrl("marContrato","IdContrato",IdContrato); //Se for desbarrado vai trazer apenas um registro!
						addParmUrl("marContrato","IdPessoa",IdPessoa);
						addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
						addParmUrl("marContasReceber","IdContrato",IdContrato);
						addParmUrl("marLancamentoFinanceiro","IdContrato",IdContrato);
						addParmUrl("marProcessoFinanceiro","IdContrato",IdContrato);
						addParmUrl("marProcessoFinanceiroNovo","IdContrato",IdContrato);
						//addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);// Leonardo - Se estou em contrato nao necessito desta linha
						addParmUrl("marReenvioMensagem","IdContrato",IdContrato);
						addParmUrl("marContaEventual","IdPessoa",IdPessoa);
						addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
						addParmUrl("marPessoa","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServicoNovo","IdContrato",IdContrato);
						addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServico","IdContrato",IdContrato);
						addParmUrl("marVigencia","IdContrato",IdContrato);
						addParmUrl("marVigenciaNovo","IdContrato",IdContrato);
						addParmUrl("marVigenciaNovo","IdPessoa",IdPessoa);
					}
					switch(Local){
						case 'ContratoAtivar':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CPF_CNPJ = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.CPF.value 				= CPF_CNPJ;
							document.formulario.CNPJ.value 				= CPF_CNPJ;
							document.formulario.Email.value 			= Email;
							
							if(TipoPessoa == 2){
								document.formulario.IdPessoaF.value 	= IdPessoa;
								document.formulario.NomeF.value 		= Nome;
								
								document.getElementById('cp_fisica').style.display		= 'block';
								document.getElementById('cp_juridica').style.display	= 'none';
							} else{
								document.formulario.RazaoSocial.value 	= RazaoSocial;
								document.formulario.Nome.value 			= Nome;
								document.formulario.IdPessoa.value 		= IdPessoa;
								
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
							}
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServico.value					=	IdServico;
							document.formulario.DescricaoServico.value			=	DescricaoServico;
							document.formulario.DescPeriodicidade.value			=	DescPeriodicidade;
							document.formulario.QtdParcela.value				=	QtdParcela;
							document.formulario.DataInicio.value				=	dateFormat(DataInicio);
							document.formulario.DataTermino.value				=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
							document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
							document.formulario.TipoContrato.value				=	TipoContrato;
							document.formulario.DataPrimeiraCobranca.value		=	dateFormat(DataPrimeiraCobranca);
							document.formulario.DataAtivacaoInicio.value		=	dateFormat(DataPrimeiraCobranca);
							document.formulario.DataAtivacaoFim.value			=	dateFormat(DataReferenciaFinal);
							document.formulario.DataPrimeiroVenc.value			=	"";
							document.formulario.DiaCobranca.value				=	DiaCobranca;
							document.formulario.Acao.value						=	'confirmar';
							
							status_inicial();
							verificaAcao();
							break;
						case 'ContratoDataBase':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CPF_CNPJ = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServico.value					=	IdServico;
							document.formulario.DescricaoServico.value			=	DescricaoServico;
							document.formulario.DescPeriodicidade.value			=	DescPeriodicidade;
							document.formulario.QtdParcela.value				=	QtdParcela;
							document.formulario.DataInicio.value				=	dateFormat(DataInicio);
							document.formulario.DataTermino.value				=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
							document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
							document.formulario.TipoContrato.value				=	TipoContrato;
							document.formulario.DataPrimeiraCobranca.value		=	dateFormat(DataPrimeiraCobranca);
							document.formulario.Acao.value						=	'alterar';
							
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.CPF.value 				= CPF_CNPJ;
							document.formulario.CNPJ.value 				= CPF_CNPJ;
							document.formulario.Email.value 			= Email;
							
							if(TipoPessoa == 2){
								document.formulario.IdPessoaF.value 	= IdPessoa;
								document.formulario.NomeF.value 		= Nome;
								
								document.getElementById('cp_fisica').style.display		= 'block';
								document.getElementById('cp_juridica').style.display	= 'none';
							} else{
								document.formulario.RazaoSocial.value 	= RazaoSocial;
								document.formulario.Nome.value 			= Nome;
								document.formulario.IdPessoa.value 		= IdPessoa;
								
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
							}
							
							verificaAcao();
							break;
							
						case 'Protocolo':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ServicoAutomatico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServicoContrato.value			=	IdServico;
							document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
							document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
							document.formulario.QtdParcelaContrato.value		=	QtdParcela;
							document.formulario.DataInicio.value				=	dateFormat(DataInicio);
							document.formulario.DataTermino.value				=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
							document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
							document.formulario.TipoContrato.value				=	TipoContrato;
							
							if(IdStatus == 303 && document.formulario.Acao.value == "inserir"){
								alert("ATENÇÃO!\n\nEste contrato esta com status "+Status+'.');
							}
							break;
						case 'OrdemServico':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ServicoAutomatico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdParcela = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServicoContrato.value			=	IdServico;
							document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
							document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
							document.formulario.QtdParcelaContrato.value		=	QtdParcela;
							document.formulario.DataInicio.value				=	dateFormat(DataInicio);
							document.formulario.DataTermino.value				=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
							document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
							document.formulario.TipoContrato.value				=	TipoContrato;
							document.formulario.ServicoAutomatico.value			=	ServicoAutomatico;
							document.formulario.Periodicidade.value				=	IdPeriodicidade;
							
							if(IdStatus == 303 && document.formulario.Acao.value == "inserir"){
								alert("ATENÇÃO!\n\nEste contrato esta com status "+Status+'.');
							}
							
							if(document.formulario.Local.value == 'OrdemServico'){
								listarParametroContrato(IdServico,false,IdContrato);
								
								if(document.formulario.Acao.value == 'inserir'){
									nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var IdPessoaEndereco = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var Nome = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var RazaoSocial = nameTextNode.nodeValue;
									
									nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
									nameTextNode = nameNode.childNodes[0];
									var CPF_CNPJ = nameTextNode.nodeValue;
									
									document.formulario.IdPessoa.value 			= IdPessoa;
									document.formulario.CPF.value 				= CPF_CNPJ;
									document.formulario.CNPJ.value 				= CPF_CNPJ;
									
									if(TipoPessoa == 2){
										document.formulario.IdPessoaF.value 	= IdPessoa;
										document.formulario.NomeF.value 		= Nome;
										
										document.getElementById('cp_fisica').style.display		= 'block';
										document.getElementById('cp_juridica').style.display	= 'none';
									} else{
										document.formulario.RazaoSocial.value 	= RazaoSocial;
										document.formulario.Nome.value 			= Nome;
										document.formulario.IdPessoa.value 		= IdPessoa;
										
										document.getElementById('cp_juridica').style.display	= 'block';
										document.getElementById('cp_fisica').style.display		= 'none';
									}
									
									if(document.formulario.IdServico.value != ""){
										busca_servico('');
									}
									busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEndereco);
									busca_pessoa_endereco(IdPessoa,IdPessoaEndereco);
								}
							}
							
							if(ServicoAutomatico != ''){
								busca_automatico(document.formulario.ServicoAutomatico.value);
							}
							/*
							if(document.formulario.Acao.value == "inserir"){
								document.formulario.IdOrdemServico.focus();
							}*/
							break;
						case 'OrdemServicoFatura':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ServicoAutomatico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoContrato = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServicoContrato.value			=	IdServico;
							document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
							document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
							document.formulario.QtdParcelaContrato.value		=	QtdParcela;
							document.formulario.DataInicio.value				=	dateFormat(DataInicio);
							document.formulario.DataTermino.value				=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
							document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
							document.formulario.TipoContrato.value				=	TipoContrato;
							break;
						case 'Agendamento':
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServicoContrato.value			=	IdServico;
							document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
							document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
							document.formulario.DataInicio.value				=	dateFormat(DataInicio);
							document.formulario.DataTermino.value				=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
							document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
							document.formulario.TipoContrato.value				=	TipoContrato;
							break;
						case 'Contrato':
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdLancamentos")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdLancamentos = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdLancamentosCancelado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdLancamentosCancelado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ServicoAutomatico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoPai")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContratoPai = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CPF_CNPJ = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPessoaEndereco = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEnderecoCobranca")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPessoaEnderecoCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDesconto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEnderecoCobranca")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPessoaEnderecoCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorFinal = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("TipoDesconto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var TipoDesconto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalTipo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdNotaFiscalTipo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("StatusTempoAlteracao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var StatusTempoAlteracao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeRepresentante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeRepresentante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RG_IE")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RG_IE = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone1")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone1 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone2")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone2 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Telefone3")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Telefone3 = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Celular = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Fax")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Fax = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ComplementoTelefone")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ComplementoTelefone = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataNascimento")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataNascimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("OrdemServico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var OrdemServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoDesconto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoDesconto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CFOP")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CFOP = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("MonitorSignalStrength")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var MonitorSignalStrength = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoGrupo")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoServicoGrupo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContaDebito = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdCartao = nameTextNode.nodeValue;
							
							if(AtivacaoAutomatica == 2 && QtdLancamentosCancelado == 0 && document.formulario.Redirecionar.value !='N'){
								window.location.replace('cadastro_contrato_ativar.php?IdContrato='+IdContrato);
							}
							
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
							
							document.formulario.QtdMesesFidelidade.value		=	"";
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdPessoa.value					=	IdPessoa;
							document.formulario.IdServico.value					=	IdServico;
							document.formulario.DescricaoServico.value			=	DescricaoServico;
							document.formulario.IdPeriodicidade.value			=	IdPeriodicidade;
							document.formulario.IdServico.readOnly				=	true;
							document.formulario.IdPessoa.readOnly				=	true;
							document.formulario.IdPessoaF.readOnly				=	true;
							document.formulario.DataInicio.value				=	dateFormat(DataInicio);
							document.formulario.DataTermino.value				=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
							document.formulario.DataPrimeiraCobranca.value		=	dateFormat(DataPrimeiraCobranca);
							document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
							document.formulario.IdAgenteAutorizado.value		=	IdAgenteAutorizado;
							document.formulario.IdContratoAgrupador.value		=	IdContratoAgrupador;
							document.formulario.IdContratoAgrupador.disabled	=	false;
							document.formulario.AdequarLeisOrgaoPublico.value	=	AdequarLeisOrgaoPublico;
							document.formulario.NotaFiscalCDA.value				=	NotaFiscalCDA;
							document.formulario.IdLocalCobrancaTemp.value		=	IdLocalCobranca;
							document.formulario.IdTipoLocalCobranca.value		=	IdTipoLocalCobranca;
							document.formulario.TipoContratoTemp.value			=	TipoContrato;
							document.formulario.MesFechadoTemp.value			=	MesFechado;
							document.formulario.QtdMesesFidelidadeTemp.value	=	QtdMesesFidelidade;
							document.formulario.DiaCobranca.value				=	DiaCobranca;
							document.formulario.DiaCobrancaTemp.value			=	DiaCobranca;
							document.formulario.DiaCobranca.disabled			=	false;
							document.formulario.Obs.value						=	"";
							document.formulario.HistoricoObs.value				=	Obs;
							document.formulario.ValorServico.value				=	formata_float(Arredonda(Valor,2),2).replace('.',',');
							document.formulario.ValorDesconto.value				=	formata_float(Arredonda(ValorDesconto,2),2).replace('.',',');
							document.formulario.ValorFinal.value				=	formata_float(Arredonda(ValorFinal,2),2).replace('.',',');
							document.formulario.ValorRepasseTerceiro.value		=	formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
							document.formulario.ValorServico.readOnly			= 	true;
							document.formulario.ValorServico.tabIndex			= 	"0";
							document.formulario.ValorRepasseTerceiro.tabIndex	= 	"0";
							document.formulario.DataCriacao.value				=	dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value				=	LoginCriacao;
							document.formulario.DataAlteracao.value				=	dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value			=	LoginAlteracao;
							document.formulario.UrlContratoImpresso.value		=	UrlContratoImpresso;
							document.formulario.UrlDistratoImpresso.value		=	UrlDistratoImpresso;
							document.formulario.IdStatus.value					=	IdStatus;
							document.formulario.QuantParcela.value				=	QtdParcela;
							document.formulario.Periodicidade.value				=	IdPeriodicidade;
							document.formulario.ServicoAutomatico.value			=	ServicoAutomatico;
							document.formulario.Acao.value 						= 	'alterar';
							document.formulario.IdCarteiraTemp.value			=	IdCarteira;
							document.formulario.StatusTempoAlteracao.value		=	StatusTempoAlteracao;
							document.formulario.NomeRepresentante.value 		= 	NomeRepresentante;
							document.formulario.DataNascimento.value 			= 	dateFormat(DataNascimento);
							document.formulario.InscricaoEstadual.value			=	RG_IE;
							document.formulario.RG.value						=	RG_IE;
							document.formulario.Telefone1.value					=   Telefone1;
							document.formulario.Telefone2.value					=   Telefone2;
							document.formulario.Telefone3.value					=   Telefone3;
							document.formulario.Celular.value					=   Celular;
							document.formulario.Fax.value						=   Fax;
							document.formulario.DescricaoServicoGrupo.value		=   DescricaoServicoGrupo;
							document.formulario.ComplementoTelefone.value		=   ComplementoTelefone;
							document.formulario.EmailJuridica.value				=   Email;
							verificar_local_cobranca(IdLocalCobranca,IdCartao,IdContaDebito);
							
							if(IdNotaFiscalTipo != ""){
								document.getElementById('cpNotaFiscalCDA').style.display 	= 'block';
								document.formulario.NotaFiscalCDA.value 					= NotaFiscalCDA;
							} else{
								document.getElementById('cpNotaFiscalCDA').style.display 	= 'none';
								document.formulario.NotaFiscalCDA.value		 				= "";
							}
							
							if(ServicoAutomatico != ''){
								document.getElementById('cp_automatico').innerHTML			=	"";
								busca_automatico(document.formulario.ServicoAutomatico.value);
							} else{
								document.getElementById('cp_automatico').innerHTML			=	"";
								document.getElementById('cp_automatico').style.display		=	'none';
							}
							
							while(document.formulario.IdPeriodicidade.options.length > 1){
								document.formulario.IdPeriodicidade.options[1] = null;
							}
							
							while(document.formulario.QtdParcela.options.length > 1){
								document.formulario.QtdParcela.options[1] = null;
							}
							
							while(document.formulario.TipoContrato.options.length > 1){
								document.formulario.TipoContrato.options[1] = null;
							}
							
							while(document.formulario.IdLocalCobranca.options.length > 1){
								document.formulario.IdLocalCobranca.options[1] = null;
							}
							
							while(document.formulario.MesFechado.options.length > 1){
								document.formulario.MesFechado.options[1] = null;
							}
							
							if(Obs != ""){
								document.getElementById('cpHistorico').style.display		=	'block';
							} else{
								document.getElementById('cpHistorico').style.display		=	'none';
							}
							
							document.getElementById('cpStatusContrato').style.display			=	'none';
							document.getElementById('cp_parametrosServico').style.display		=	'none';
							document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
							
							switch(parseInt(IdTipoDesconto)){
								case 1:
									document.getElementById('titDesconto').innerHTML	=	'Valor Desc. Conc. Mensal';
									break;
								case 2:
									document.getElementById('titDesconto').innerHTML	=	'Valor Desc. A Conc. Mensal';
									break;
								case 3:
									document.getElementById('titDesconto').innerHTML	=	'Valor Desconto Mensal';
									break;
							}
							
							document.getElementById('titValorServico').style.color	 			= "#000000";
							
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.CPF.value 				= CPF_CNPJ;
							document.formulario.CNPJ.value 				= CPF_CNPJ;
							document.formulario.Email.value 			= Email;
							
							if(TipoPessoa == 2){
								document.formulario.IdPessoaF.value 	= IdPessoa;
								document.formulario.NomeF.value 		= Nome;
								
								document.getElementById('cp_fisica').style.display		= 'block';
								document.getElementById('cp_juridica').style.display	= 'none';
							} else{
								document.formulario.RazaoSocial.value 	= RazaoSocial;
								document.formulario.Nome.value 			= Nome;
								document.formulario.IdPessoa.value 		= IdPessoa;
								
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
							}
							
							if(CFOP == '0.00000'){
								CFOP = 0;
							}
							
							listar_terceiros(IdServico,IdTerceiro);
							busca_cfop_servico(IdServico, CFOP);
							busca_dia_cobranca(IdPessoa,DiaCobranca);
							listarParametro(document.formulario.IdServico.value,false,document.formulario.IdContrato.value);
							listarParametroLocalCobranca(IdLocalCobranca,false,document.formulario.IdContrato.value);
							periodicidade(IdServico);
							listar_carteira(IdAgenteAutorizado,IdCarteira);
							listar_contrato_agrupador(IdPessoa,IdContratoAgrupador,IdContrato);
							busca_status(IdStatus,VarStatus);
							calculaPeriodicidade(IdPeriodicidade,document.formulario.ValorFinal.value);
							calculaPeriodicidadeTerceiro(IdPeriodicidade,document.formulario.ValorRepasseTerceiro.value,document.formulario.ValorPeriodicidadeTerceiro);
							busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEndereco,IdPessoaEnderecoCobranca);
							listarOrdemServicoCliente(IdPessoa,IdContrato);
							listarProtocoloCliente(IdPessoa,IdContrato);
							
							if(TipoMonitor != ''&& IdSNMP != ''){
								document.getElementById("cp_monitor").style.display = "block";
							} else{
								document.getElementById("cp_monitor").style.display = "none";
							}
							
							if(document.formulario.ResumoConexaoAbertoDefault.value != 1){
								function espera_execucao(){
									if(document.getElementById("carregando-ajax").accessKey == 0){
										ocultarQuadro(document.getElementById('botaoQuadroConexao'), 'tableQuadroConexao', true); 
										busca_resumo_conexao(IdContrato,1);
										
/*										if(document.formulario.MonitorAbertoDefault.value != '' && TipoMonitor == '' && IdSNMP == ''){
											ocultarQuadroMonitor(document.getElementById('botaoQuadroMonitor'), 'tableQuadroMonitor');
										}*/
									} else{
										setTimeout(function (){espera_execucao();}, 1000);
									}
								}
								
								espera_execucao();
							} else{
								ocultarQuadroConexao(document.getElementById('botaoQuadroConexao'), 'tableQuadroConexao');
							}
							
							document.formulario.IdCarteira.value				=	IdCarteira;
							document.formulario.QtdMesesFidelidade.value		=	QtdMesesFidelidade;
							document.formulario.MultaFidelidade.value			=	MultaFidelidade;
							document.formulario.ValorMultaFidelidade.value		=	formata_float(Arredonda(MultaFidelidade,2),2).replace('.',',');
							
							document.getElementById('DataInicio').style.backgroundColor = '#FFFFFF';
							document.getElementById('DataInicio').style.color = '#C10000';
							
							document.getElementById('DataPrimeiraCobranca').style.backgroundColor = '#FFFFFF';
							document.getElementById('DataPrimeiraCobranca').style.color = '#C10000';
							
							document.getElementById('DataTermino').style.backgroundColor = '#FFFFFF';
							document.getElementById('DataTermino').style.color = '#000000';
							
							document.getElementById('titMudarServico').style.display 	= "block";
							
							document.getElementById('DataUltimaCobranca').style.backgroundColor = '#FFFFFF';
							document.getElementById('DataUltimaCobranca').style.color = '#000000';
							
							if(UrlContratoImpresso !=''){
								document.formulario.bt_imprimir_contrato.disabled	=	false;
							} else{
								document.formulario.bt_imprimir_contrato.disabled	=	true;
							}
							
							if(DataTermino != "" && UrlDistratoImpresso != ""){
								document.formulario.bt_imprimir_distrato.disabled	=	false;
							} else{
								document.formulario.bt_imprimir_distrato.disabled	=	true;
							}
							
							if(OrdemServico != ""){
								document.formulario.bt_relatorio.disabled	=	false;
							} else{
								document.formulario.bt_relatorio.disabled	=	true;
							}
							
							var hoje = new Date();
							var dia = hoje.getDate();
							var mes = (hoje.getMonth())+1; 
							var ano = hoje.getFullYear();
							if(dia < 10) dia = "0" + dia;
							if(mes < 10) mes = "0" + mes;
							hoje = ano+"-"+mes+"-"+dia;
							
							if(QtdLancamentos > 0){
								document.formulario.bt_fatura.disabled		=	true;
							} else{
								document.formulario.bt_fatura.disabled		=	false;
							}
							
							if(document.formulario.IdTipoLocalCobranca.value == 3){
							//	document.formulario.bt_carta.style.display		=	'block';
							} else{
							//	document.formulario.bt_carta.style.display		=	'none';
							}
							
							// CHAMADA DO GRÁFICO DE MONITORAMENTO
							/*if(MonitorSignalStrength > 0){
								document.getElementById("cp_MonitorSinal").style.display = 'block';
								document.formulario.IdTipoGrafico.value = document.formulario.MonitorSinalIdGrafico.value;
								
								if(document.formulario.MonitorSinalAtualizacaoAutomatica.value == 1){
									document.formulario.AtualizacaoAutomatica.checked = true;
								} else{
									document.formulario.AtualizacaoAutomatica.checked = false;
								}
								
								atualizar_grafico();
							} else{
								document.getElementById("cp_MonitorSinal").style.display = 'none';
							}
							*/
							document.formulario.IdContrato.focus();
							verificaAcao();
							scrollWindow('top');
							if(IdCartao != ""){
								document.formulario.IdContaDebitoCartao.value 		= IdCartao;
							}
							
							if(IdContaDebito != ""){
								document.formulario.IdContaDebitoCartao.value 		= IdContaDebito;
							}
							verificarPermissaoEdicao(document.formulario.PermissaoEditarContrato.value,document.formulario.PermissaoEditarParametroContrato.value);
							break;
						case 'ContratoServico':
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescMesFechado")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescMesFechado = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ServicoAutomatico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorFinal = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CPF_CNPJ = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LancamentoFinanceiroTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var LancamentoFinanceiroTipoContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContaDebito = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdCartao = nameTextNode.nodeValue;
							
							verificar_local_cobranca(IdLocalCobranca,IdCartao,IdContaDebito,'Anterior');
							
							if(ValorFinal == '')	ValorFinal = 0;
							
							while(document.getElementById('tabelaContasReceber').rows.length > 2){
								document.getElementById('tabelaContasReceber').deleteRow(1);
							}
							
							document.formulario.ParametrosAnterior.value				=	"";
							document.formulario.IdContrato.value						=	IdContrato;
							document.formulario.IdContratoAnterior.value				=	IdContrato;
							document.formulario.IdPessoa.value							=	IdPessoa;
							document.formulario.IdServicoAnterior.value					=	IdServico;
							document.formulario.DescricaoServicoAnterior.value			=	DescricaoServico;
							document.formulario.DescPeriodicidadeAnterior.value			=	DescPeriodicidade;
							document.formulario.QtdParcelaAnterior.value				=	QtdParcela;
							document.formulario.ValorServicoAnterior.value				=	formata_float(Arredonda(ValorFinal,2),2).replace('.',',');
							document.formulario.TipoContratoAnterior.value				=	DescTipoContrato;
							document.formulario.MesFechadoAnterior.value				=	DescMesFechado;
							document.formulario.LocalCobrancaAnterior.value				=	DescricaoLocalCobranca;
							document.formulario.ValorRepasseTerceiroAnterior.value		=	formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
							document.formulario.QtdMesesFidelidadeAnterior.value		=	QtdMesesFidelidade;
							document.formulario.ValorMultaFidelidadeAnterior.value		=	formata_float(Arredonda(MultaFidelidade,2),2).replace('.',',');
							document.formulario.DataInicio.value						=	dateFormat(DataInicio);
							document.formulario.DataPrimeiraCobranca.value				=	dateFormat(DataPrimeiraCobranca);
							document.formulario.DataBaseCalculo.value					=	dateFormat(DataBaseCalculo);
							document.formulario.DataTermino.value						=	dateFormat(DataTermino);
							document.formulario.DataUltimaCobranca.value				=	dateFormat(DataUltimaCobranca);
							document.formulario.DiaCobrancaAnterior.value				=	DiaCobranca;
							document.formulario.AssinaturaContrato.value				=	AssinaturaContrato;
							document.formulario.IdAgenteAutorizadoAnterior.value		=	IdAgenteAutorizado;
							document.formulario.LancamentoFinanceiroTipoContrato.value	=	LancamentoFinanceiroTipoContrato;
							document.formulario.Acao.value								=	'inserir';
							document.formulario.IdCarteira.disabled						=	false;
							document.formulario.IdCarteiraTemp.value					=	IdCarteira;
							document.formulario.ServicoAutomaticoAnterior.value			=	ServicoAutomatico;
							
							if(document.formulario.IdAgenteAutorizado.value != ''){
								document.formulario.IdCarteira.disabled				=	false;
							}else{
								document.formulario.IdCarteira.disabled				=	true;
							}
							
							listar_terceiros(IdServico,IdTerceiro,true);
							busca_dia_cobranca(IdPessoa, document.formulario.DiaCobrancaDefault.value);
							listar_carteira_anterior(IdAgenteAutorizado,IdCarteira);
							listarParametroAnterior(IdServico,IdContrato);
							calculaPeriodicidadeServico(IdPeriodicidade,document.formulario.ValorServicoAnterior.value,document.formulario.ValorPeriodicidadeAnterior);
							calculaPeriodicidadeTerceiroServico(IdPeriodicidade,document.formulario.ValorRepasseTerceiroAnterior.value,document.formulario.ValorPeriodicidadeTerceiroAnterior);
							
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.CPF.value 				= CPF_CNPJ;
							document.formulario.CNPJ.value 				= CPF_CNPJ;
							document.formulario.Email.value 			= Email;
							
							if(TipoPessoa == 2){
								document.formulario.IdPessoaF.value 	= IdPessoa;
								document.formulario.NomeF.value 		= Nome;
							
								document.getElementById('cp_fisica').style.display		= 'block';
								document.getElementById('cp_juridica').style.display	= 'none';
							}else{
								document.formulario.RazaoSocial.value 	= RazaoSocial;
								document.formulario.Nome.value 			= Nome;
								document.formulario.IdPessoa.value 		= IdPessoa;
							
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
							}
							
							limpaFormCredito();
							if(document.formulario.PermissaoCancelarContaReceber.value == 1){
								listar_conta_receber_aberto(IdContrato);
							}
							document.getElementById('cp_parametrosServico').style.display	=	'none';
							
							verificaAcao();
							scrollWindow('top');
							break;
						case 'ContratoStatus':
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ServicoAutomatico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorFinal = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CPF_CNPJ = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("StatusTempoAlteracao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var StatusTempoAlteracao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LancamentoFinanceiroTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var LancamentoFinanceiroTipoContrato = nameTextNode.nodeValue;
							
							if(ValorFinal == '')	ValorFinal = 0;
							
							document.formulario.IdContrato.value						=	IdContrato;
							document.formulario.IdPessoa.value							=	IdPessoa;
							document.formulario.IdServico.value							=	IdServico;
							document.formulario.DescricaoServico.value					=	DescricaoServico;
							document.formulario.DescPeriodicidade.value					=	DescPeriodicidade;
							document.formulario.QtdParcela.value						=	QtdParcela;
							document.formulario.VarStatusAnterior.value					=	VarStatus;
							document.formulario.ServicoAutomaticoAnterior.value			=	ServicoAutomatico;
							document.formulario.IdStatusAnteriorTemp.value				=	IdStatus;
							document.formulario.StatusTempoAlteracao.value				=	StatusTempoAlteracao;
							document.formulario.ValorServico.value						=	formata_float(Arredonda(ValorFinal,2),2).replace('.',',');
							document.formulario.DataInicio.value						=	dateFormat(DataInicio);
							document.formulario.DataTermino.value						=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value					=	dateFormat(DataBaseCalculo);
							document.formulario.DataPrimeiraCobranca.value				=	dateFormat(DataPrimeiraCobranca);
							document.formulario.DataUltimaCobranca.value				=	dateFormat(DataUltimaCobranca);
							document.formulario.LancamentoFinanceiroTipoContrato.value	=	LancamentoFinanceiroTipoContrato;
							
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.CPF.value 				= CPF_CNPJ;
							document.formulario.CNPJ.value 				= CPF_CNPJ;
							document.formulario.Email.value 			= Email;
							document.formulario.EmailNotificacao.value 	= Email;
							
							if(TipoPessoa == 2){
								document.formulario.IdPessoaF.value 	= IdPessoa;
								document.formulario.NomeF.value 		= Nome;
							
								document.getElementById('cp_fisica').style.display		= 'block';
								document.getElementById('cp_juridica').style.display	= 'none';
							}else{
								document.formulario.RazaoSocial.value 	= RazaoSocial;
								document.formulario.Nome.value 			= Nome;
								document.formulario.IdPessoa.value 		= IdPessoa;
							
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
							}
							
							document.getElementById('cp_conta_receber').style.display	=	'none';
							
							while(document.getElementById('tabelaContaReceber').rows.length > 2){
								document.getElementById('tabelaContaReceber').deleteRow(1);
							}
							
							//document.getElementById('cpValorTotal').innerHTML		=	"0,00";		
							//document.getElementById('tabelaTotal').innerHTML		=	"Total: 0";	
							
							verifica_status('');
							busca_status(document.formulario.IdStatusAnterior,IdStatus,VarStatus);
							busca_status_contrato(IdStatus,VarStatus);
							calculaPeriodicidadeServico(IdPeriodicidade,document.formulario.ValorServico.value,document.formulario.ValorPeriodicidade);
							busca_vigencia(IdContrato,false);
							
							scrollWindow('top');
							verificaAcao();
							break;
						case "Vigencia":
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ServicoAutomatico")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ServicoAutomatico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CPF_CNPJ = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value			=	IdContrato;
							
							if(ServicoAutomatico == ""){
								document.formulario.IdContratoFilho.value		=	IdContrato;
							}else{
								document.formulario.IdContratoFilho.value		=	"";
							}
							document.formulario.IdServico.value				=	IdServico;
							document.formulario.DescricaoServico.value		=	DescricaoServico;
							document.formulario.DescPeriodicidade.value		=	DescPeriodicidade;
							document.formulario.QtdParcela.value			=	QtdParcela;
							document.formulario.DataInicio.value			=	dateFormat(DataInicio);
							document.formulario.DataPrimeiraCobranca.value	=	dateFormat(DataPrimeiraCobranca);
							document.formulario.DataTermino.value			=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value		=	dateFormat(DataBaseCalculo);
							document.formulario.DataUltimaCobranca.value	=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value	=	AssinaturaContrato;
							document.formulario.TipoContrato.value			=	TipoContrato;
							document.formulario.Valor.value					=	formata_float(Arredonda(Valor,2),2).replace('.',',');
							document.formulario.ServicoAutomatico.value		=	ServicoAutomatico;
							document.formulario.Periodicidade.value			=	IdPeriodicidade;
							
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.CPF.value 				= CPF_CNPJ;
							document.formulario.CNPJ.value 				= CPF_CNPJ;
							document.formulario.Email.value 			= Email;
							
							if(TipoPessoa == 2){
								document.formulario.IdPessoaF.value 	= IdPessoa;
								document.formulario.NomeF.value 		= Nome;
							
								document.getElementById('cp_fisica').style.display		= 'block';
								document.getElementById('cp_juridica').style.display	= 'none';
							}else{
								document.formulario.RazaoSocial.value 	= RazaoSocial;
								document.formulario.Nome.value 			= Nome;
								document.formulario.IdPessoa.value 		= IdPessoa;
							
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
							}
							
							if(ServicoAutomatico != ''){
								document.getElementById('cp_automatico').innerHTML			=	"";
								busca_automatico(document.formulario.ServicoAutomatico.value);
							}else{
								document.getElementById('cp_automatico').innerHTML			=	"";
								document.getElementById('cp_automatico').style.display		=	'none';
							}
							
							for(var i=0; i<document.formulario.AssinaturaContrato.length; i++){
								if(document.formulario.AssinaturaContrato[i].value == AssinaturaContrato){
									document.formulario.AssinaturaContrato[i].selected = true;
									i = document.formulario.AssinaturaContrato.length;
								}							
							}
							
							for(var i=0; i<document.formulario.TipoContrato.length; i++){
								if(document.formulario.TipoContrato[i].value == TipoContrato){
									document.formulario.TipoContrato[i].selected = true;
									i = document.formulario.TipoContrato.length;
								}							
							}
															
							calculaValorFinal(document.formulario.Valor.value,document.formulario.ValorDesconto.value,'',document.formulario.ValorDesconto);
							
							listarVigencia(IdContrato,false);
							break;
						case "LancamentoFinanceiro":
							document.formulario.IdContrato.value			=	IdContrato;
							document.formulario.IdServico.value				=	IdServico;
							document.formulario.DescricaoServico.value		=	DescricaoServico;
							document.formulario.DescPeriodicidade.value		=	DescPeriodicidade;
							document.formulario.QtdParcela.value			=	QtdParcela;
							document.formulario.DataInicio.value			=	dateFormat(DataInicio);
							document.formulario.DataTermino.value			=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value		=	dateFormat(DataBaseCalculo);
							document.formulario.DataUltimaCobranca.value	=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value	=	AssinaturaContrato;
							document.formulario.TipoContrato.value			=	TipoContrato;
							break;
						case "LancamentoFinanceiroReferencia":
							document.formulario.IdContrato.value			=	IdContrato;
							document.formulario.IdServico.value				=	IdServico;
							document.formulario.DescricaoServico.value		=	DescricaoServico;
							document.formulario.DescPeriodicidade.value		=	DescPeriodicidade;
							document.formulario.QtdParcela.value			=	QtdParcela;
							document.formulario.DataInicio.value			=	dateFormat(DataInicio);
							document.formulario.DataTermino.value			=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value		=	dateFormat(DataBaseCalculo);
							document.formulario.DataUltimaCobranca.value	=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value	=	AssinaturaContrato;
							document.formulario.TipoContrato.value			=	TipoContrato;
							break;
						case 'CancelarContrato':
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorFinal = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Nome = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var RazaoSocial = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CPF_CNPJ = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Email = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdPessoa.value					=	IdPessoa;
							document.formulario.IdServico.value					=	IdServico;
							document.formulario.DescricaoServico.value			=	DescricaoServico;
							document.formulario.DescPeriodicidade.value			=	DescPeriodicidade;
							document.formulario.QtdParcela.value				=	QtdParcela;
							document.formulario.DataInicio.value				=	dateFormat(DataInicio);
							document.formulario.DataTermino.value				=	dateFormat(DataTermino);
							document.formulario.DataBaseCalculo.value			=	dateFormat(DataBaseCalculo);
							document.formulario.DataPrimeiraCobranca.value		=	dateFormat(DataPrimeiraCobranca);
							document.formulario.DataUltimaCobranca.value		=	dateFormat(DataUltimaCobranca);
							document.formulario.AssinaturaContrato.value		=	AssinaturaContrato;
							document.formulario.IdAgenteAutorizado.value		=	IdAgenteAutorizado;
							document.formulario.NomeAgenteAutorizado.value		=	NomeAgenteAutorizado;
							document.formulario.IdContratoAgrupador.value		=	IdContratoAgrupador;
							document.formulario.IdContratoAgrupador.disabled	=	false;
							document.formulario.AdequarLeisOrgaoPublico.value	=	AdequarLeisOrgaoPublico;
							document.formulario.IdLocalCobranca.value			=	IdLocalCobranca;
							document.formulario.TipoContrato.value				=	TipoContrato;
							document.formulario.HistoricoObs.value				=	Obs;
							document.formulario.Obs.value						=	"";
							document.formulario.ValorServico.value				=	ValorFinal;
							document.formulario.DataCriacao.value				=	dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value				=	LoginCriacao;
							document.formulario.DataAlteracao.value				=	dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value			=	LoginAlteracao;
							document.formulario.bt_confirmar.disabled			= 	false;
							
							document.formulario.IdPessoa.value 			= IdPessoa;
							document.formulario.CPF.value 				= CPF_CNPJ;
							document.formulario.CNPJ.value 				= CPF_CNPJ;
							document.formulario.Email.value 			= Email;
							
							if(TipoPessoa == 2){
								document.formulario.IdPessoaF.value 	= IdPessoa;
								document.formulario.NomeF.value 		= Nome;
							
								document.getElementById('cp_fisica').style.display		= 'block';
								document.getElementById('cp_juridica').style.display	= 'none';
							}else{
								document.formulario.RazaoSocial.value 	= RazaoSocial;
								document.formulario.Nome.value 			= Nome;
								document.formulario.IdPessoa.value 		= IdPessoa;
							
								document.getElementById('cp_juridica').style.display	= 'block';
								document.getElementById('cp_fisica').style.display		= 'none';
							}
							
							for(var i=0; i<document.formulario.AssinaturaContrato.length; i++){
								if(document.formulario.AssinaturaContrato[i].value == AssinaturaContrato){
									document.formulario.AssinaturaContrato[i].selected = true;
									i = document.formulario.AssinaturaContrato.length;
								}							
							}
							
							for(var i=0; i<document.formulario.TipoContrato.length; i++){
								if(document.formulario.TipoContrato[i].value == TipoContrato){
									document.formulario.TipoContrato[i].selected = true;
									i = document.formulario.TipoContrato.length;
								}							
							}
							
							calculaPeriodicidade(IdPeriodicidade,Valor);
							listar_carteira(IdAgenteAutorizado,IdCarteira);
							listar_contrato_agrupador(IdPessoa,IdContratoAgrupador,IdContrato);
							
							document.formulario.IdCarteira.value	=	IdCarteira;
							document.formulario.IdCarteira.disabled	=	true;
							
							scrollWindow('top');							
							break;
						case 'AdicionarContrato':
							var cont = 0; ii='';									
							if(ListarCampo == '' || ListarCampo == undefined){										
								if(document.formulario.Filtro_IdContrato.value == ''){
									document.formulario.Filtro_IdContrato.value = IdContrato;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdContrato.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdContrato){
											cont++;		
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdContrato.value = document.formulario.Filtro_IdContrato.value + "," + IdContrato;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){										
								nameNode = xmlhttp.responseXML.getElementsByTagName("NomePessoa")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var NomePessoa = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescTipoContrato = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorDesconto = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Status = nameTextNode.nodeValue;
								
								var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11;
								
								tam 	= document.getElementById('tabelaContrato').rows.length;
								linha	= document.getElementById('tabelaContrato').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdContrato; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								c8	= linha.insertCell(8);
								c9	= linha.insertCell(9);
								c10	= linha.insertCell(10);
								c11	= linha.insertCell(11);
								
								var linkIni = "<a href='cadastro_contrato.php?IdContrato="+IdContrato+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdContrato + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + NomePessoa.substr(0,20) + linkFim;
								
								c2.innerHTML = linkIni + DescricaoServico.substr(0,20) + linkFim;
								
								c3.innerHTML = linkIni + DescTipoContrato.substr(0,3) + linkFim;
								
								c4.innerHTML = linkIni + dateFormat(DataInicio) + linkFim;
								
								c5.innerHTML = linkIni + dateFormat(DataTermino) + linkFim;
								
								c6.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
								
								c7.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim ;
								c7.style.textAlign = "right";
								c7.style.padding =	"0 8px 0 0";
								
								c8.innerHTML = linkIni + formata_float(Arredonda(ValorDesconto,2),2).replace('.',',') + linkFim ;
								c8.style.textAlign = "right";
								c8.style.padding =	"0 8px 0 0";
								
								c9.innerHTML = linkIni + formata_float(Arredonda((Valor-ValorDesconto),2),2).replace('.',',') + linkFim ;
								c9.style.textAlign = "right";
								c9.style.padding =	"0 8px 0 0";
								
								c10.innerHTML = linkIni + Status + linkFim;
								
								if(document.formulario.IdStatus.value == 1){
									c11.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_contrato("+IdContrato+")\"></tr>";
								}else{
									c11.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c11.style.textAlign = "center";
								c11.style.cursor = "pointer";
								
								switch(document.formulario.Local.value){
									case "ProcessoFinanceiro":
										if(document.formulario.IdProcessoFinanceiro.value == ''){
											document.getElementById('totaltabelaContrato').innerHTML	=	'Total: '+(ii+1);
										}else{
											if(document.formulario.Erro.value != ''){
												scrollWindow('bottom');
											}
										}
										break;
									default:
										document.getElementById('totaltabelaContrato').innerHTML	=	'Total: '+(ii+1);
								}
							}
							
							document.formulario.IdContrato.value				=	"";
							document.formulario.IdServicoContrato.value			=	"";
							document.formulario.DescricaoServicoContrato.value	=	"";
							document.formulario.DescPeriodicidadeContrato.value	=	"";
							document.formulario.DescTipoContrato.value			=	"";
							break;
						case 'AdicionarMalaDireta':
							var cont = 0; ii='';									
							if(ListarCampo == '' || ListarCampo == undefined){										
								if(document.formulario.Filtro_IdContrato.value == ''){
									document.formulario.Filtro_IdContrato.value = IdContrato;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdContrato.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdContrato){
											cont++;		
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdContrato.value = document.formulario.Filtro_IdContrato.value + "," + IdContrato;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){										
								nameNode = xmlhttp.responseXML.getElementsByTagName("NomePessoa")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var NomePessoa = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DescTipoContrato = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorDesconto = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var Status = nameTextNode.nodeValue;
								
								var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11;
								
								tam 	= document.getElementById('tabelaContrato').rows.length;
								linha	= document.getElementById('tabelaContrato').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdContrato; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								c5	= linha.insertCell(5);
								c6	= linha.insertCell(6);
								c7	= linha.insertCell(7);
								c8	= linha.insertCell(8);
								c9	= linha.insertCell(9);
								c10	= linha.insertCell(10);
								c11	= linha.insertCell(11);
								
								var linkIni = "<a href='cadastro_contrato.php?IdContrato="+IdContrato+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + IdContrato + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + NomePessoa.substr(0,20) + linkFim;
								
								c2.innerHTML = linkIni + DescricaoServico.substr(0,20) + linkFim;
								
								c3.innerHTML = linkIni + DescTipoContrato.substr(0,3) + linkFim;
								
								c4.innerHTML = linkIni + dateFormat(DataInicio) + linkFim;
								
								c5.innerHTML = linkIni + dateFormat(DataTermino) + linkFim;
								
								c6.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
								
								c7.innerHTML = linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim ;
								c7.style.textAlign = "right";
								c7.style.padding =	"0 8px 0 0";
								
								c8.innerHTML = linkIni + formata_float(Arredonda(ValorDesconto,2),2).replace('.',',') + linkFim ;
								c8.style.textAlign = "right";
								c8.style.padding =	"0 8px 0 0";
								
								c9.innerHTML = linkIni + formata_float(Arredonda((Valor-ValorDesconto),2),2).replace('.',',') + linkFim ;
								c9.style.textAlign = "right";
								c9.style.padding =	"0 8px 0 0";
								
								c10.innerHTML = linkIni + Status + linkFim;
								
								if(document.formulario.IdStatus.value == 1){
									c11.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_contrato("+IdContrato+")\"></tr>";
								}else{
									c11.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c11.style.textAlign = "center";
								c11.style.cursor = "pointer";
								
								document.getElementById('totaltabelaContrato').innerHTML	=	'Total: '+(ii+1);
							}
							
							document.formulario.IdContrato.value				=	"";
							document.formulario.IdServicoContrato.value			=	"";
							document.formulario.DescricaoServicoContrato.value	=	"";
							document.formulario.DescPeriodicidadeContrato.value	=	"";
							document.formulario.DescTipoContrato.value			=	"";
							break;
						case 'ProcessoFinanceiro':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoContrato = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServicoContrato.value			=	IdServico;
							document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
							document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
							document.formulario.DescTipoContrato.value			=	DescTipoContrato;
							break;
						case 'MalaDireta':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoContrato = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServicoContrato.value			=	IdServico;
							document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
							document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
							document.formulario.DescTipoContrato.value			=	DescTipoContrato;
							break;
						case 'HelpDesk':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServicoContrato.value			=	IdServico;
							document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
							document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
							document.formulario.DescTipoContrato.value			=	DescTipoContrato;
							document.formulario.Status.value					=	Status;
							break;
						case 'ForcarExclusaoContratoVinculos':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescPeriodicidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescPeriodicidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescTipoContrato = nameTextNode.nodeValue;
							
							document.formulario.IdContrato.value				=	IdContrato;
							document.formulario.IdServicoContrato.value			=	IdServico;
							document.formulario.DescricaoServicoContrato.value	=	DescricaoServico;
							document.formulario.DescPeriodicidadeContrato.value	=	DescPeriodicidade;
							document.formulario.DescTipoContrato.value			=	DescTipoContrato;
							break;
					}
				}
			}
			
			if(document.getElementById("quadroBuscaContrato") != null){
				if(document.getElementById("quadroBuscaContrato").style.display == "block"){
					document.getElementById("quadroBuscaContrato").style.display =	"none";
				}
			}
		});
	}
	function listar_carteira(IdAgenteAutorizado,IdCarteiraTemp){
		if(document.formulario.Local.value == "Contrato" || document.formulario.Local.value == "ContratoServico") {
			if(IdAgenteAutorizado != '' && IdAgenteAutorizado != undefined) {
				document.formulario.IdCarteira.disabled = false;
			} else {
				document.formulario.IdCarteira.disabled = true;
			}
		}
		
		if(IdAgenteAutorizado == ''){
			while(document.formulario.IdCarteira.options.length > 0){
				document.formulario.IdCarteira.options[0] = null;
			}
			
			document.formulario.IdCarteiraTemp.value = "";
			return false;
		}
		
		if(IdCarteiraTemp == undefined){
			IdCarteiraTemp = '';
		}
		
		if(IdCarteiraTemp == "" && document.formulario.IdAgenteAutorizadoLogin.value != ""){
			IdCarteiraTemp	=	document.formulario.IdPessoaLogin.value;
		}
	
		var url = "xml/carteira.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdStatus=1";
		
		if(document.formulario.IdAgenteAutorizadoLogin.value != ""){
			url	+=	"&IdCarteira="+IdCarteiraTemp;	
		}
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(document.formulario.IdCarteira.options.length > 0){
					document.formulario.IdCarteira.options[0] = null;
				}
				
				document.formulario.IdCarteiraTemp.value  =	"";
				document.formulario.IdCarteira.disabled	  =	true;
			} else{
				while(document.formulario.IdCarteira.options.length > 0){
					document.formulario.IdCarteira.options[0] = null;
				}
				
				addOption(document.formulario.IdCarteira,"","0");
				
				if(document.formulario.Local.value == 'Contrato' || document.formulario.Local.value == 'ContratoServico' || document.formulario.Local.value == 'OrdemServico'){
					document.formulario.IdCarteira.disabled	=	false;
					if(document.formulario.Acao.value == 'inserir'){
						document.formulario.IdCarteira.focus();	
					}
				}
					
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCarteira").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdCarteira = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Nome = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdCarteira,Nome,IdCarteira);
				}
				
				if(IdCarteiraTemp!=''){
					var aux	=	0;
					for(ii=0;ii<document.formulario.IdCarteira.length;ii++){
						if(document.formulario.IdCarteira[ii].value == IdCarteiraTemp){
							document.formulario.IdCarteira[ii].selected = true;
							document.formulario.IdCarteiraTemp.value    = document.formulario.IdCarteira[ii].value;
							aux	=	1;
							break;
						}
					}
					
					if(aux == 0){
						addCarteira(IdAgenteAutorizado,IdCarteiraTemp);
					} else{
						if(document.formulario.IdAgenteAutorizadoLogin.value != ""){
							document.formulario.IdCarteira.disabled    = true;
						}
					}					
				} else{
					if(document.formulario.SelecionarCamposUmaOpcao != undefined){
						if(document.formulario.Acao.value == 'inserir' && document.formulario.SelecionarCamposUmaOpcao.value == 1 && document.formulario.IdCarteira[2] == undefined){
							document.formulario.IdCarteira[1].selected	= true;
						}else{
							document.formulario.IdCarteira[0].selected = true;
						}
						document.formulario.IdCarteiraTemp.value   = document.formulario.IdCarteira[0].value;	
					}
				}
			}
		});
		
		if(document.formulario.Local.value == "Contrato")
			verificarPermissaoEdicao(document.formulario.PermissaoEditarContrato.value,document.formulario.PermissaoEditarParametroContrato.value);
	} 
	function addCarteira(IdAgenteAutorizado,IdCarteiraSelected){
		var url = "xml/carteira.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdCarteira="+IdCarteiraSelected;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCarteira").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdCarteira = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdCarteira,Nome,IdCarteira);
				}
				
				if(IdCarteiraSelected!=''){
					for(ii=0;ii<document.formulario.IdCarteira.length;ii++){
						if(document.formulario.IdCarteira[ii].value == IdCarteiraSelected){
							document.formulario.IdCarteira[ii].selected = true;
							document.formulario.IdCarteiraTemp.value    = document.formulario.IdCarteira[ii].value;
							break;
						}
					}
				}
			} else{
				document.formulario.IdCarteira[0].selected	=	true;
			}
		});
		
		verificarPermissaoEdicao(document.formulario.PermissaoEditarContrato.value,document.formulario.PermissaoEditarParametroContrato.value);
	} 
	function listar_contrato_agrupador(IdPessoa,IdContratoAgrupadorTemp,IdContrato){
		if(IdPessoa == '' && document.formulario.Local.value != 'OrdemServicoFatura'){
			while(document.formulario.IdContratoAgrupador.options.length > 0){
				document.formulario.IdContratoAgrupador.options[0] = null;
			}
			return false;
		}
		
		if(IdContratoAgrupadorTemp == undefined){
			IdContratoAgrupadorTemp = '';
		}
		
		if(IdContrato == undefined){
			if(document.formulario.Local.value == 'ContaEventual'){
				IdContrato = '';
			}else{
				IdContrato = 0;
			}
		}
		
		if((document.formulario.Local.value == 'OrdemServicoFatura' || document.formulario.Local.value == 'LancamentoFinanceiro') && IdContratoAgrupadorTemp != ""){
			var url = "xml/contrato.php?IdContrato="+IdContratoAgrupadorTemp;
		}else{
			var url = "xml/contrato_agrupador.php?IdPessoa="+IdPessoa+"&IdContrato="+IdContrato;
		}
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(document.formulario.IdContratoAgrupador.options.length > 0){
					document.formulario.IdContratoAgrupador.options[0] = null;
				}
				
				document.formulario.IdContratoAgrupador.disabled	=	true;
				
				if(document.formulario.Local.value == 'Contrato'){
					document.formulario.DiaCobranca.disabled = false;
				}
			} else{
				while(document.formulario.IdContratoAgrupador.options.length > 0){
					document.formulario.IdContratoAgrupador.options[0] = null;
				}
				
				if(document.formulario.Local.value != "LancamentoFinanceiro"){
					document.formulario.IdContratoAgrupador.disabled	=	false;
				}
				
				addOption(document.formulario.IdContratoAgrupador,"","0");
				
				if(document.formulario.Local.value == 'Contrato'){
					document.formulario.IdCarteira.disabled		=	false;
				}
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdContrato = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoAgrupador")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoContratoAgrupador = nameTextNode.nodeValue;
					
					var Descricao	=	"("+IdContrato+") "+DescricaoContratoAgrupador;
					
					addOption(document.formulario.IdContratoAgrupador,Descricao,IdContrato);
				}
				
				if(IdContratoAgrupadorTemp!=''){
					for(ii=0;ii<document.formulario.IdContratoAgrupador.length;ii++){
						if(document.formulario.IdContratoAgrupador[ii].value == IdContratoAgrupadorTemp){
							document.formulario.IdContratoAgrupador[ii].selected = true;
							
							if(document.formulario.Local.value == 'Contrato'){
								document.formulario.DiaCobranca.disabled	=	true;
							}
							break;
						}
					}
				} else{
					document.formulario.IdContratoAgrupador[0].selected = true;
				}
			}
			verificarPermissaoEdicao(document.formulario.PermissaoEditarContrato.value,document.formulario.PermissaoEditarParametroContrato.value);
		});
		
	} 
	function busca_automatico(ServicoAutomatico, Idloja){
		document.getElementById('cp_automatico').style.display = 'block';
		var aux, IdContrato, IdContratoTemp = "", IdServico, table = "", script_temp = "", carteira_temp = '', temp = ServicoAutomatico.split("#"), tabindexAutomatico = 199;
		for(var i = 0; i < temp.length; i++){
			if(temp[i].indexOf("¬") != "-1"){
				aux			= temp[i].split("¬");
				IdServico	= aux[0]; 
				IdContrato 	= aux[1];
				
				if(IdContratoTemp != ""){
					IdContratoTemp += ",";
				}
				
				tabindexAutomatico +=1;
				IdContratoTemp += IdContrato;
				table		+=	"<div id='cp_tit'>Serviço Automático ("+(i+1)+")</div>";
				table		+=		"<table>";
				table		+=				"<tr>";
				table		+=					"<td class='find'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Contrato </td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'><B  style='margin-right:35px; color:#000' id='IdServico'>Serviço</B>Nome Serviço</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Valor Mensal do Serviço ("+document.formulario.MoedaAtual.value+")</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Valor Periodicidade ("+document.formulario.MoedaAtual.value+")</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Nota Fiscal CDA</td>";
				table		+=				"</tr>";
				table		+=				"<tr>";
				table		+=					"<td class='find'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				
				if(document.formulario.Local.value != 'OrdemServico' && document.formulario.Local.value != 'Vigencia'){
					table		+=						"<input type='text' name='IdContratoAutomatico_"+IdServico+"' value='"+IdContrato+"' style='width:70px'>";
				}else{
					table		+=						"<input type='text' name='IdContratoAutomatico_"+IdServico+"' value='"+IdContrato+"' style='width:70px' readOnly>";
				}
				
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='IdServico_"+IdServico+"' value='"+IdServico+"'  style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico_"+IdServico+"' style='width:189px' maxlength='100' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='ValorInicial_"+IdServico+"' value='' style='width:160px' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='ValorPeriodicidade_"+IdServico+"' value='' style='width:160px' maxlength='12' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				
				if(document.formulario.Local.value != 'OrdemServico' && document.formulario.Local.value != 'Vigencia'){
					table		+=						"<select name='NotaFiscalCDAAutomatico_"+IdServico+"' style='width:100px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+tabindexAutomatico+"'>";				
					table		+=						"</select>";
				}else {
					table		+=						"<select name='NotaFiscalCDAAutomatico_"+IdServico+"' style='width:100px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+tabindexAutomatico+"' disabled>";				
					table		+=						"</select>";
				}
				tabindexAutomatico +=1;
				table		+=					"</td>";
				table		+=				"</tr>";
				table		+=			"</table>";
				table		+=			"<table id='tabelaParametro_"+IdServico+"' style='display:none'>";
				table		+=			"</table>";
				
				table		+=		"<table id='cpAgenteCarteira_"+IdServico+"'>";
				table		+=		"	<tr>";
				table		+=		"		<td class='find'>&nbsp;</td>";
				table		+=		"		<td class='descCampo'><B style='color:#000' id='cpAgenteAutorizado_"+IdServico+"'>Agente Autorizado</B></td>";
				table		+=		"		<td class='separador'>&nbsp;</td>";
				table		+=		"		<td class='descCampo'><B style='color:#000' id='cpCarteira_"+IdServico+"'>Vendedor</B></td>";
				table		+=		"		<td class='separador'>&nbsp;</td>";
				if(document.formulario.Local.value != 'OrdemServico' && document.formulario.Local.value != 'Vigencia'){
					table		+=		"		<td class='descCampo'><B style='color:#000' id='cpTerceiro_"+IdServico+"'>Terceiro</B></td>";
				}else{
					table		+=		"		<td class='descCampo' id='cpTerceiro_"+IdServico+"'>Terceiro</td>";
				}
				table		+=		"	</tr>";
				table		+=		"	<tr>";
				table		+=		"		<td class='find'>&nbsp;</td>";
				table		+=		"		<td class='campo'>";
				if(document.formulario.Local.value != 'OrdemServico' && document.formulario.Local.value != 'Vigencia'){//Agente Autorizado
					table		+=		"			<select id='IdAgenteAutorizado_"+IdServico+"' name='IdAgenteAutorizado_"+IdServico+"' style='width:280px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange=\"listar_carteira_automatico(document.formulario.IdCarteira_"+IdServico+",this.value);\" tabindex='"+tabindexAutomatico+"'>";
					table		+=		"			</select>";
				} else{	
					table		+=		"			<select id='IdAgenteAutorizado_"+IdServico+"' name='IdAgenteAutorizado_"+IdServico+"' style='width:280px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange=\"listar_carteira_automatico(document.formulario.IdCarteira_"+IdServico+",this.value);\" tabindex='"+tabindexAutomatico+"' disabled>";
					table		+=		"			</select>";
				}
				table		+=		"		</td>";
				tabindexAutomatico +=1;
				table		+=		"		<td class='separador'>&nbsp;</td>";
				table		+=		"		<td class='campo'>";
				table		+=		"			<select type='text' name='IdCarteira_"+IdServico+"' value='' style='width:260px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='"+tabindexAutomatico+"' disabled>";//501
				table		+=		"				<option value=''>&nbsp;</option>";
				table		+=		"			</select>";
				table		+=		"		</td>";
				tabindexAutomatico +=1;
				table		+=		"		<td class='separador'>&nbsp;</td>";
				table		+=		"		<td class='campo'>";
				if(document.formulario.Local.value != 'OrdemServico' && document.formulario.Local.value != 'Vigencia'){//Terceiro
					table		+=		"			<select type='text' name='IdTerceiro_"+IdServico+"' value='' style='width:260px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\"  onchange=\"listar_terceiros_automatico(document.formulario.IdTerceiro_"+IdServico+","+IdServico+", this.value);\" tabindex='"+tabindexAutomatico+"' disabled>";
					table		+=		"				<option value=''>&nbsp;</option>";
					table		+=		"			</select>";
				} else{
					table		+=		"			<select type='text' name='IdTerceiro_"+IdServico+"' value='' style='width:260px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\"  onchange='' tabindex='"+tabindexAutomatico+"' disabled>";
					table		+=		"			</select>";
				}
				table		+=		"		</td>";
				table		+=		"	</tr>";
				tabindexAutomatico +=1;
				table		+=		"</table>";
			}else{
				
				IdServico	=	temp[i];
				IdContrato	=	"";
				table		+=	"<div id='cp_tit'>Serviço Automático ("+(i+1)+")</div>";
				table		+=		"<table>";
				table		+=				"<tr>";
				table		+=					"<td class='find'>&nbsp;</td>";
				table		+=					"<td class='descCampo'><B  style='margin-right:35px; color:#000' id='IdServico'>Serviço</B>Nome Serviço</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Valor Mensal do Serviço("+document.formulario.MoedaAtual.value+")</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Valor Periodicidade("+document.formulario.MoedaAtual.value+")</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='descCampo'>Nota Fiscal CDA</td>";
				table		+=				"</tr>";
				table		+=				"<tr>";
				table		+=					"<td class='find'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='hidden' name='IdContratoAutomatico_"+IdServico+"' value='"+IdContrato+"'><input type='text' name='IdServico_"+IdServico+"' value='"+IdServico+"'  style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico_"+IdServico+"' style='width:276px' maxlength='100' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='ValorInicial_"+IdServico+"' value='' style='width:160px' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";
				table		+=						"<input type='text' name='ValorPeriodicidade_"+IdServico+"' value='' style='width:160px' maxlength='12' readOnly>";
				table		+=					"</td>";
				table		+=					"<td class='separador'>&nbsp;</td>";
				table		+=					"<td class='campo'>";	
				table		+=						"<select name='NotaFiscalCDAAutomatico_"+IdServico+"' style='width:100px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+tabindexAutomatico+"' >";				
				table		+=						"</select>";
				table		+=					"</td>";
				table		+=				"</tr>";
				table		+=			"</table>";	
				table		+=			"<table id='tabelaParametro_"+IdServico+"' style='display:none'>";
				table		+=			"</table>";
				
				tabindexAutomatico +=1;
				table		+=		"<table id='cpAgenteCarteira_"+IdServico+"'>";
				table		+=		"	<tr>";
				table		+=		"		<td class='find'>&nbsp;</td>";
				table		+=		"		<td class='descCampo'><B style='color:#000' id='cpAgenteAutorizado_"+IdServico+"'>Agente Autorizado</B></td>";
				table		+=		"		<td class='separador'>&nbsp;</td>";
				table		+=		"		<td class='descCampo'><B style='color:#000' id='cpCarteira_"+IdServico+"'>Vendedor</B></td>";
				table		+=		"		<td class='separador'>&nbsp;</td>";
				table		+=		"		<td class='descCampo'><B style='color:#000' id='cpTerceiro_"+IdServico+"'>Terceiro</B></td>";
				table		+=		"	</tr>";
				table		+=		"	<tr>";
				table		+=		"		<td class='find'>&nbsp;</td>";
				table		+=		"		<td class='campo'>";
				table		+=		"			<select id='IdAgenteAutorizado_"+IdServico+"' name='IdAgenteAutorizado_"+IdServico+"' style='width:280px' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onChange=\"listar_carteira_automatico(document.formulario.IdCarteira_"+IdServico+",this.value);\" tabindex='"+tabindexAutomatico+"'>";
				table		+=		"			</select>";
				table		+=		"		</td>";
				tabindexAutomatico +=1;
				table		+=		"		<td class='separador'>&nbsp;</td>";
				table		+=		"		<td class='campo'>";
				table		+=		"			<select type='text' name='IdCarteira_"+IdServico+"' value='' style='width:260px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='"+tabindexAutomatico+"' disabled>";//504
				table		+=		"				<option value=''>&nbsp;</option>";
				table		+=		"			</select>";
				table		+=		"		</td>";
				tabindexAutomatico +=1;
				table		+=		"		<td class='separador'>&nbsp;</td>";
				table		+=		"		<td class='campo'>";
				table		+=		"			<select type='text' name='IdTerceiro_"+IdServico+"' value='' style='width:260px' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\"  onchange=\"listar_terceiros_automatico(document.formulario.IdTerceiro_"+IdServico+","+IdServico+", this.value);\" tabindex='"+tabindexAutomatico+"' disabled>";//505
				table		+=		"				<option value=''>&nbsp;</option>";
				table		+=		"			</select>";
				table		+=		"		</td>";
				tabindexAutomatico +=1;
				table		+=		"	</tr>";
				table		+=		"</table>";
				script_temp	+=		"preenche_agente_autorizado(document.formulario.IdAgenteAutorizado_"+IdServico+");";
				script_temp	+=		"listar_carteira_automatico(document.formulario.IdCarteira_"+IdServico+");";
				script_temp	+=		"listar_terceiros_automatico(document.formulario.IdTerceiro_"+IdServico+","+IdServico+");";
			}
			
			busca_servico_automatico(IdServico,IdContrato);
			if(document.formulario.Local.value == "Contrato"){
				verificarPermissaoEdicao(document.formulario.PermissaoEditarContrato.value,document.formulario.PermissaoEditarParametroContrato.value);
			}
		}
		
		document.getElementById("cp_automatico").innerHTML = table;	
		tabindex = 0;
		
		eval(script_temp);
		
		if(IdContratoTemp != ""){
			var url = "xml/contrato.php?IdContrato="+IdContratoTemp;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != "false"){
					var script_temp = "";
					
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdServico").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdAgenteAutorizado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCarteira = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTerceiro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTerceiro = nameTextNode.nodeValue;
						
						script_temp += "preenche_agente_autorizado(document.formulario.IdAgenteAutorizado_"+IdServico+",'"+IdAgenteAutorizado+"');";
						script_temp += "listar_carteira_automatico(document.formulario.IdCarteira_"+IdServico+",'"+IdAgenteAutorizado+"','"+IdCarteira+"');";
						script_temp += "listar_terceiros_automatico(document.formulario.IdTerceiro_"+IdServico+","+IdServico+",'"+IdTerceiro+"');";
					}
					
					eval(script_temp);
				}
			});
		}
		
		for(i = 0; i < temp.length; i++){
			if(temp[i].indexOf("¬") != "-1"){
				aux = temp[i].split("¬");
				IdServico = aux[0];
				IdContratoAutomatico = aux[1];
			}else{
				IdServico = temp[i];
				IdContratoAutomatico = "";
			}
			
			if(tabindex == 0){
				tabindex = 194;
			}
			tabindex = tabindex + 5;
			if(document.formulario.Local.value == 'ContratoServico'){
				listarServicoParametroAnteriorAutomatico(IdServico,tabindex,IdContratoAutomatico);	
			}else{
				if(document.formulario.Local.value != 'Vigencia'){
					listarServicoParametroAutomatico(IdServico,tabindex,IdContratoAutomatico);	
				}
			}
		}
	}
	function busca_servico_automatico(IdServico,IdContrato){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		
		if(IdContrato == undefined){
			IdContrato = '';
		}
		
	    var url = "xml";
		
	    if(IdContrato != ""){
	   		url += "/contrato.php?IdContrato="+IdContrato;
	   	} else{
	   		url += "/servico.php?IdServico="+IdServico+"&Local=Servico";
		}
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Valor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				if(IdStatus == 2 && document.formulario.Acao.value == "inserir"){
					document.formulario.ServicoAutomatico.value = "";
					document.getElementById('cp_automatico').style.display = "none";
					return false;
				}
				
				var campo = "NotaFiscalCDAAutomatico_"+IdServico;
				var campo_valor = "ValorInicial_"+IdServico;
				
				eval("var campo_periodicidade = document.formulario.ValorPeriodicidade_"+IdServico);
				
				for(var i = 0; i < document.formulario.length; i++){
					if(document.formulario[i].name == campo){
						addOption(document.formulario[i],"","");
					}
				}
				
				if(IdContrato == ""){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("NotaFiscalCDADefault")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NotaFiscalCDADefault = nameTextNode.nodeValue;					
				} else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("NotaFiscalCDA")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NotaFiscalCDA = nameTextNode.nodeValue;
				}
				
				for(i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalCDAOpc").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalCDAOpc")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdNotaFiscalCDAOpc = nameTextNode.nodeValue;
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoNotaFiscalCDAOpc")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoNotaFiscalCDAOpc = nameTextNode.nodeValue;

					for(var ii = 0; ii < document.formulario.length; ii++){
						if(document.formulario[ii].name == campo){								
							addOption(document.formulario[ii],DescricaoNotaFiscalCDAOpc,IdNotaFiscalCDAOpc);									
						}
					}													
				}
				
				if(IdContrato == ""){
					for(i = 0; i < document.formulario.length; i++){
						if(document.formulario[i].name == campo){
							document.formulario[i].value = NotaFiscalCDADefault;
						}
					}
				} else{
					for(i = 0; i < document.formulario.length; i++){
						if(document.formulario[i].name == campo){
							document.formulario[i].value  = NotaFiscalCDA;
						}
					}
				}
				
				for(i = 0; i < document.formulario.length; i++){
					if(document.formulario[i].name == campo_valor){
						document.formulario[i].value  = Valor;
					}
				}
				
				var posIni = 0, posFim = 0;
				
				for(ii = 0; ii < document.formulario.length; ii++){
					if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
						if(posIni == 0){
							posIni = ii;
						}
						
						posFim = ii;
					}
				}
				
				var Periodicidade;
				
				for(ii = posIni; ii <= posFim; ii++){
					if(document.formulario[ii].name.substring(0,10) == "IdServico_"){
						temp = document.formulario[ii].name.split("_");
						
						if(temp[1] == IdServico){
							document.formulario[ii+1].value = DescricaoServico;
							document.formulario[ii+2].value = formata_float(Arredonda(Valor,2),2).replace('.',',');
							
							if(IdContrato != ""){
								Periodicidade = document.formulario.Periodicidade.value;
							} else{
								Periodicidade = document.formulario.IdPeriodicidade.value;
							}
							
							if(Periodicidade != ''){
								calculaPeriodicidade(Periodicidade,Valor,campo_periodicidade);
							}
							
							break;
						}
					}
				}
			}
		});
	}
	function contrato_botao_auxiliar_automatico_img_1(){
		// FUNÇÃO UTILIZADA PARA FAZER O CARREGAMENTO DAS INSTRUÇÕES PARA O BOTÃO DO PARAMETRO DO SERVIÇO
	}
	function contrato_botao_auxiliar_automatico_img_2(){
		// FUNÇÃO UTILIZADA PARA FAZER O CARREGAMENTO DAS INSTRUÇÕES PARA O BOTÃO DO PARAMETRO DO SERVIÇO
	}
	function calculaServicoAutomatico(IdPeriodicidade){	
		if(document.formulario.Local.value == 'Contrato' && document.formulario.ServicoAutomatico.value != ""){
			var posIni = 0, posFim = 0;
			
			for(ii=0;ii<document.formulario.length;ii++){
				if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
					if(posIni == 0){
						posIni	=	ii;
					}
					posFim	=	ii;
				}
			}
			
			for(ii=posIni;ii<=posFim;ii++){
				if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
					calculaPeriodicidade(document.formulario.IdPeriodicidade.value,document.formulario[ii+2].value,document.formulario[ii+3]);
				}
			}
		}
	}	
	function listarServicoParametroAutomatico(IdServico,tabindex,IdContrato){
		while(document.getElementById("tabelaParametro_"+IdServico+"").rows.length > 0){
			document.getElementById("tabelaParametro_"+IdServico+"").deleteRow(0);
		}
		
		if(IdServico == ''){
			IdServico = 0;
		}
		
		if(IdContrato == undefined){
			IdContrato = '';
		}
		
		var url = "xml/servico_parametro.php?IdServico="+IdServico+"&IdStatus=1";
		
	    if(IdContrato!=""){
	    	url = "xml/contrato_parametro.php?IdServico="+IdServico+"&IdStatus=1&IdContrato="+IdContrato;
			
			if(document.formulario.Local.value == 'OrdemServico'){
				url += '&VisivelOS=1';
			}
			
			if(document.formulario.Local.value == 'Contrato' || document.formulario.Local.value == 'ContratoServico'){
				url += '&Visivel=1';
			}
		}
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.getElementById("tabelaParametro_"+IdServico+"").style.display = 'none';
			} else{
				var tam, linha, c0, padding, visivel, color, obsTemp = new Array(), invisivel = "", cont = 0;
				
				document.getElementById("tabelaParametro_"+IdServico+"").style.display = 'block';
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obrigatorio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;	
					
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("BotaoAuxiliar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var BotaoAuxiliar = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("BotaoAuxiliarIMG")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var BotaoAuxiliarIMG = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RestringirGrupoUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RestringirGrupoUsuario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoTexto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTipoTexto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMinimo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMinimo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TamMaximo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var TamMaximo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ExibirSenha")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ExibirSenha = nameTextNode.nodeValue;
					
					if(Valor == "" && document.formulario.Acao.value == 'inserir'){
						Valor = ValorDefault;
					}
					
					if(Obs != "") 
						Obs = trim(Obs);
					
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
						tam = document.getElementById("tabelaParametro_"+IdServico+"").rows.length;
						obsTemp[cont] = Obs;
						
						if(cont%2 == 0){
							linha		= document.getElementById("tabelaParametro_"+IdServico+"").insertRow(tam);
							tabindex	= tabindex + cont + 1;
							padding		= 22;
							pos			= 0;
						} else{
							padding		= 10;
							tabindex	= tabindex + cont;
							pos			= 1;
						}
						
						if(Obs != ""){
							Obs = "<br />"+Obs;
						}
						
						if((cont + 1) == xmlhttp.responseXML.getElementsByTagName("tabelaParametro_"+IdServico+"").length && (cont % 2) == 0){
							padding = 22;
						}
						
						if(document.formulario.Local.value == 'OrdemServico'){
							color = "#000000";
							visivel = 'readOnly';
						} else{
							if(Obrigatorio == 1){
								color = "#C10000";
							} else{
								color = "#000000";
							}
							
							if(Editavel == 1){
								visivel = '';
							} else{
								visivel = 'readOnly';
							}
						}
						
						linha.accessKey = IdParametroServico; 
						
						c0 = linha.insertCell(pos);
						c0.style.verticalAlign = "top";
						
						if(TamMaximo != "" && Editavel == 1){
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
									
									contrato_botao_auxiliar_automatico_img_1 = function (Campo){
										if(Campo.value != ""){
											window.open((/(^[\w]*([:]*\/\/))/i).test(Campo.value) ? Campo.value : "//"+Campo.value);
										}
									};
									
									ComplementoHTMLInicio = "<tbody><table cellpadding='0' cellspacing='0'><tr><td>";
									ComplementoHTMLFim = "</td><td style='vertical-align:top;'><img style='margin:17px 0 0 4px; cursor:pointer;' src=\""+BotaoAuxiliarIMG+"\" onClick=\"contrato_botao_auxiliar_automatico_img_1(document.formulario.ValorAutomatico_"+IdServico+"_"+IdParametroServico+");\" /></td></table></tbody>";
									break;
								case "2":
									if(pos == 1){
										style = "width:374px;";
									} else {
										style = "width:375px;";
									}
									
									contrato_botao_auxiliar_automatico_img_2 = function (Campo, TamMaximo, AlphaNumerico){
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
									ComplementoHTMLFim = "</td><td style='vertical-align:top;'><img style='margin:17px 0 0 5px; cursor:pointer;' src=\""+BotaoAuxiliarIMG+"\" onClick=\"contrato_botao_auxiliar_automatico_img_2(document.formulario.ValorAutomatico_"+IdServico+"_"+IdParametroServico+", '"+TamMaximo+"', "+(IdTipoTexto == "1" && IdMascaraCampo != "2")+");\" /></td></table></tbody>";
									break;
								case "3":
									if(pos == 1){
										style = "width:374px;";
									} else {
										style = "width:375px;";
									}
									
									ComplementoHTMLInicio = "<tbody><table cellpadding='0' cellspacing='0'><tr><td>";
									ComplementoHTMLFim = "</td><td style='vertical-align:top;'><img id='cpDataBotaoAuxiliarAutomaticoIco_"+IdServico+"_"+IdParametroServico+"' style='margin:18px 0 0 7px; cursor:pointer;' alt='Buscar data' title='Buscar data' src=\""+BotaoAuxiliarIMG+"\" /></td></table></tbody>";
									
									(function runTimeAUX(IdServico, IdParametroServico) {
										var Campo = eval("document.formulario.ValorAutomatico_"+IdServico+"_"+IdParametroServico+";");
										
										if(Campo == undefined || document.getElementById("cpDataBotaoAuxiliarAutomatico_"+IdServico+"_"+IdParametroServico) == null){
											if(typeof Campo == "object"){
												Campo.setAttribute("id", "cpDataBotaoAuxiliarAutomatico_"+IdServico+"_"+IdParametroServico);
											}
											
											setTimeout(function (){
												runTimeAUX(IdServico, IdParametroServico);
											}, 111);
										} else {
											Calendar.setup({
												inputField : "cpDataBotaoAuxiliarAutomatico_"+IdServico+"_"+IdParametroServico,
												checkReadOnly : false,
												ifFormat : "%d/%m/%Y",
												button : "cpDataBotaoAuxiliarAutomaticoIco_"+IdServico+"_"+IdParametroServico
											});
										}
									})(IdServico, IdParametroServico);
									break;
							}
							
							switch(IdTipoTexto){
								case '1': // Texto
									switch(IdMascaraCampo){
										case '1': // Data (dd/mm/yyyy)
											c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
											break;
										case '2': // Inteiro
											c0.innerHTML=  ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'int')\" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
											break;
										case '3': // Real
											if(Editavel == 1){
												c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'float')\" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
											}else{
												c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
											}
											break;
										case '4': // Usuário
											c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+"  onkeypress=\"mascara(this,event,'usuario')\" "+tamMax+" tabindex='"+tabindex+"' /><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"' /><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"' /><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"' />"+Obs+"</p>"+ComplementoHTMLFim;
											break;
										case '5': // MAC (AA:BB:CC:DD:EE:FF)
											c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+"  onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
											break;
										default:
											c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
									}
									break;
								case '2': // Senha
									if(ExibirSenha == 2){
										tipo = "password";
									} else{
										tipo = "text";
									}
									
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='"+tipo+"' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
								case '3': // GRUPO RADIUS
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+ValorDefault+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"' readOnly><input type='hidden' name='ObrigatorioAutomatico_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
								case '4': // IPv4
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
								case '5': // IPv6
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
								case '6': // Asterisk
									c0.innerHTML = ComplementoHTMLInicio+"<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='"+style+"' autocomplete='off' "+tamMax+" onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'><input type='hidden' name='ObrigatorioAutomatico_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>"+Obs+"</p>"+ComplementoHTMLFim;
									break;
							}
						} else{
							if(visivel == 'readOnly'){
								visivel = 'disabled';
							}
							
							campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
							campo += "<B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p>";
							campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
							
							
							if(pos == 1){
								campo += "<select name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"'  style='width:405px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'>";
							} else {
								campo += "<select name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"'  style='width:404px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'>";
							}
							
							campo += "<option value='"+Valor+"'></option>";
							
							valor = OpcaoValor.split("\n");
							
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								
								if(trim(Valor) == trim(valor[ii])){
									selecionado = "selected=true";
								}
								
								campo += "<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							
							campo += "</select>";
							campo += "<input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>";
							campo += Obs+"</p>";
							
							c0.innerHTML = campo;
						}
						
						cont++;
					} else{
						invisivel += "<div style='display:none'>";
						
						if(IdTipoParametro == 1){
							invisivel += "<input type='text' name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off'><input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>";
						} else{
							campo = "";
							campo += "<select name='ValorAutomatico_"+IdServico+"_"+IdParametroServico+"'  style='width:406px;'>";
							campo += "<option value=''></option>";
							
							valor = OpcaoValor.split("\n");
							
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								
								if(trim(Valor) == trim(valor[ii])){
									selecionado = "selected=true";
								}
								
								campo += "<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							
							campo += "</select>";
							campo += "<input type='hidden' name='ObrigatorioAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Obrigatorio+"'><input type='hidden' name='VisivelAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+Visivel+"'><input type='hidden' name='TamMinimoAutomatico_"+IdServico+"_"+IdParametroServico+"' value='"+TamMinimo+"'>";
							
							invisivel += campo;
						}
						
						invisivel += "</div>";
					}
				}
				
				if(invisivel != ""){
					tam 	 = document.getElementById("tabelaParametro_"+IdServico+"").rows.length;
					linha	 = document.getElementById("tabelaParametro_"+IdServico+"").insertRow(tam);
					
					linha.accessKey = IdParametroServico; 
					
					c0 = linha.insertCell(0);
					c0.innerHTML = invisivel;
				}
				
				if(document.formulario.Erro.value != '0' && document.formulario.Erro.value!=''){
					scrollWindow('bottom');
				} else{
					scrollWindow('top');
				}
			}
		});
	}
	function periodicidade(IdServico,Temp){
		if(Temp == undefined){
			Temp = "";
		}
		
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
		
//		document.formulario.QtdMesesFidelidade.value = "";	
		
		if(IdServico != ""){
			var url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&Local="+document.formulario.Local.value;
			
			if(document.formulario.IdPessoa != undefined){
				url += "&IdPessoa="+document.formulario.IdPessoa.value;
			}
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){
					addOption(document.formulario.IdPeriodicidade,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade").length; i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdPeriodicidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPeriodicidade")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoPeriodicidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdContratoPeriodicidade")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var QtdContratoPeriodicidade = nameTextNode.nodeValue;
						
						if(document.formulario.Local.value == "Contrato" && QtdContratoPeriodicidade > 0){
							DescricaoPeriodicidade += " ***";
						}
						
						addOption(document.formulario.IdPeriodicidade,DescricaoPeriodicidade,IdPeriodicidade);
					}
					
					if(i == 1){
						document.formulario.IdPeriodicidade[1].selected = true;
						IdPeriodicidade = document.formulario.IdPeriodicidade[1].value;
						
						servico_periodicidade_parcelas(IdServico,IdPeriodicidade,Temp);
						calculaPeriodicidade(IdPeriodicidade,document.formulario.ValorFinal.value);
						calculaPeriodicidadeTerceiro(IdPeriodicidade,document.formulario.ValorRepasseTerceiro.value);
						
						if(Temp == "" && document.formulario.Acao.value == 'inserir'){
							contrato_periodicidade(document.formulario.Periodicidade.value);
						}
					} else{
						if(document.formulario.Acao.value == 'inserir'){
							document.formulario.IdPeriodicidade[0].selected = true;
						} else{
							if(Temp == ""){
								contrato_periodicidade(document.formulario.Periodicidade.value);
								servico_periodicidade_parcelas(IdServico,document.formulario.Periodicidade.value)
							} else{
								document.formulario.IdPeriodicidade[0].selected = true;
							}
						}
					}
				} else{
					contrato_periodicidade(document.formulario.Periodicidade.value);
				}
			});
		}
	}
	function servico_periodicidade_parcelas(IdServico,IdPeriodicidade,Temp){
		if(Temp == undefined){
			Temp = "";
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
//		document.formulario.QtdMesesFidelidade.value		=	"";	
		
		if(IdPeriodicidade!=""){
			var url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&Local="+document.formulario.Local.value;
			
			if(document.formulario.IdPessoa != undefined){
				url += "&IdPessoa="+document.formulario.IdPessoa.value;
			}
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode;					
					addOption(document.formulario.QtdParcela,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdParcela = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdContratoQtdParcela")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdContratoQtdParcela = nameTextNode.nodeValue;
						
						QtdParcelaDescricao = QtdParcela;
						
						if(document.formulario.Local.value == "Contrato" && QtdContratoQtdParcela > 0){
							QtdParcelaDescricao += " ***";
						}
						
						addOption(document.formulario.QtdParcela,QtdParcelaDescricao,QtdParcela);
					}
					
					if(i==1){
						document.formulario.QtdParcela[1].selected = true;
						QtdParcela	=	document.formulario.QtdParcela[1].value;
						
						busca_servico_tipo_contrato(IdServico,IdPeriodicidade,QtdParcela,Temp);
						
						if(Temp == "" && document.formulario.Acao.value == 'alterar'){
							contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
						}
					} else{
						if(document.formulario.Acao.value == 'inserir'){
							document.formulario.QtdParcela[0].selected = true;
						} else{
							if(Temp == ""){
								contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
								busca_servico_tipo_contrato(IdServico,document.formulario.Periodicidade.value,document.formulario.QuantParcela.value)
							} else{
								document.formulario.QtdParcela[0].selected = true;
							}
						}
					}
				} else{
					contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
				}
			});
		} else{
			while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0){
				document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
			}
			
			document.getElementById('cp_parametrosLocalCobranca').style.display	=	'none';
		}
	}
	function busca_servico_tipo_contrato(IdServico,IdPeriodicidade,QtdParcela,Temp){
		if(Temp == undefined){
			Temp = "";
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
//		document.formulario.QtdMesesFidelidade.value		=	"";	
		
		if(QtdParcela != ''){
		    var url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&Local=Contrato";
			
			if(document.formulario.IdPessoa != undefined){
				url += "&IdPessoa="+document.formulario.IdPessoa.value;
			}
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){
					var nameNode, nameTextNode, DescTipoContrato, TipoContrato;					
					addOption(document.formulario.TipoContrato,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("TipoContrato").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("TipoContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						TipoContrato = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescTipoContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdContratoTipoContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdContratoTipoContrato = nameTextNode.nodeValue;
						
						if(document.formulario.Local.value == "Contrato" && QtdContratoTipoContrato > 0){
							DescTipoContrato += " ***";
						}
						
						addOption(document.formulario.TipoContrato,DescTipoContrato,TipoContrato);
					}
					
					if(i == 1){
						document.formulario.TipoContrato[1].selected		=	true;
						TipoContrato	=	document.formulario.TipoContrato[1].value;
						
						busca_servico_local_cobranca(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,Temp);
						
						if(Temp == "" && document.formulario.Acao.value == 'alterar'){
							contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
						}
					} else{
						if(document.formulario.Acao.value == 'inserir'){
							document.formulario.TipoContrato[0].selected = true;
						} else{
							if(Temp == ""){
								contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
								busca_servico_local_cobranca(IdServico,document.formulario.Periodicidade.value,document.formulario.QuantParcela.value,document.formulario.TipoContratoTemp.value)
							} else{
								document.formulario.TipoContrato[0].selected = true;
							}
						}
					}					
				} else{
					contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
				}
			});
		}
	}
	function busca_servico_local_cobranca(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,Temp){
		if(Temp == undefined){
			Temp = "";
		}
		
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
//		document.formulario.QtdMesesFidelidade.value		=	"";	
		
		if(TipoContrato!=""){
			var url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&TipoContrato="+TipoContrato+"&Local=Contrato";
			
			if(document.formulario.IdPessoa != undefined){
				url += "&IdPessoa="+document.formulario.IdPessoa.value;
			}
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdLocalCobranca, DescricaoLocalCobranca, IdTipoLocalCobranca;
					
					addOption(document.formulario.IdLocalCobranca,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdTipoLocalCobranca = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdContratoLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdContratoLocalCobranca = nameTextNode.nodeValue;
						
						if(document.formulario.Local.value == "Contrato" && QtdContratoLocalCobranca > 0){
							DescricaoLocalCobranca += " ***";
						}
						
						addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca,Temp);
					}
					
					if(i==1){
						document.formulario.IdLocalCobranca[1].selected		=	true;	
						IdLocalCobranca	=	document.formulario.IdLocalCobranca[1].value;

						if(document.formulario.Acao.value == 'inserir' || Temp != ""){
							listarLocalCobrancaParametro(IdLocalCobranca,true);
						}
						
						busca_servico_mes_fechado(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca);
						
						if(Temp == "" && document.formulario.Acao.value == 'alterar'){
							contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
						}
					} else{
						if(document.formulario.Acao.value == 'inserir'){
							document.formulario.IdLocalCobranca[0].selected = true;
						} else{
							if(Temp == ""){
								contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
								busca_servico_mes_fechado(IdServico,document.formulario.Periodicidade.value,document.formulario.QuantParcela.value,document.formulario.TipoContratoTemp.value,document.formulario.IdLocalCobrancaTemp.value);
							} else{
								document.formulario.IdLocalCobranca[0].selected = true;
							}
						}
					}
				} else{
					contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
				}
			});
		}
		
		if(document.formulario.IdLocalCobranca.value != ""){
			verificar_local_cobranca(document.formulario.IdLocalCobranca.value);
		}
	}
	function busca_servico_mes_fechado(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,Temp){
		if(Temp == undefined){
			Temp = "";
		}
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
//		document.formulario.QtdMesesFidelidade.value		=	"";	
		
		if(IdLocalCobranca != ''){
			var url = "xml/servico_periodicidade.php?IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&TipoContrato="+TipoContrato+"&IdLocalCobranca="+IdLocalCobranca+"&Local=Contrato";
			
			if(document.formulario.IdPessoa != undefined){
				url += "&IdPessoa="+document.formulario.IdPessoa.value;
			}
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, MesFechado, DescricaoLocalCobranca, QtdMesesFidelidade;
					addOption(document.formulario.MesFechado,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("MesFechado").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("MesFechado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						MesFechado = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoMesFechado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoMesFechado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMesesFidelidade")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdMesesFidelidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdContratoMesFechado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdContratoMesFechado = nameTextNode.nodeValue;
						
						if(document.formulario.Local.value == "Contrato" && QtdContratoMesFechado > 0){
							DescricaoMesFechado += " ***";
						}
						
						addOption(document.formulario.MesFechado,DescricaoMesFechado,MesFechado);
					}
					
					if(i==1){
						document.formulario.MesFechado[1].selected  	= true;							
						
						if(document.formulario.QtdMesesFidelidadeTemp != undefined){
							if(document.formulario.QtdMesesFidelidadeTemp.value == ''){
								document.formulario.QtdMesesFidelidade.value	=	QtdMesesFidelidade;
							}else{
								document.formulario.QtdMesesFidelidadeTemp.value = '';
							}
						}
						
						if(Temp == "" && document.formulario.Acao.value == 'alterar'){
							contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidade.value);
						}
					} else{
						if(document.formulario.Acao.value == 'inserir'){
							document.formulario.MesFechado[0].selected = true;
						} else{
							if(Temp == ""){
								contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidade.value);
							}else{
								document.formulario.MesFechado[0].selected = true;	
							}
						}
					}
				} else{
					contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidade.value);
				}
			});
		}
	}
	function contrato_periodicidade(IdPeriodicidade){
		var temp	=	0;
		
		for(i=0;i<document.formulario.IdPeriodicidade.options.length;i++){
			if(document.formulario.IdPeriodicidade[i].value == IdPeriodicidade){
				document.formulario.IdPeriodicidade[i].selected	=	true;
				temp++;
				break;
			}
		}
		
		if(temp == 0){
			var url = "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdPeriodicidade;
					
					if(document.formulario.IdPeriodicidade.options.length == 0){
						addOption(document.formulario.IdPeriodicidade,"","");
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdPeriodicidade = nameTextNode.nodeValue;
						
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPeriodicidade")[0]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoPeriodicidade = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdPeriodicidade,DescricaoPeriodicidade,IdPeriodicidade);
					contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
				}
			});
		}	
	}
	function contrato_periodicidade_parcela(QtdParcela){
		var temp	=	0;
		for(i=0;i<document.formulario.QtdParcela.options.length;i++){
			if(document.formulario.QtdParcela[i].value == QtdParcela){
				document.formulario.QtdParcela[i].selected	=	true;
				temp++;
				break;
			}
		}
		if(temp == 0){
			if(document.formulario.QtdParcela.options.length == 0){
				addOption(document.formulario.QtdParcela,"","");
			}
			
			addOption(document.formulario.QtdParcela,QtdParcela,QtdParcela);
			
			contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
		}
	}
	function contrato_tipo_contrato(TipoContrato){
		var temp	=	0;
		for(i=0;i<document.formulario.TipoContrato.options.length;i++){
			if(document.formulario.TipoContrato[i].value == TipoContrato){
				document.formulario.TipoContrato[i].selected	=	true;
				temp++;
				break;
			}
		}
		
		if(temp == 0){
			var url = "xml/parametro_sistema.php?IdGrupoParametroSistema=28&IdParametroSistema="+TipoContrato;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdParametroSistema;
					
					if(document.formulario.TipoContrato.options.length == 0){
						addOption(document.formulario.TipoContrato,"","");
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametroSistema = nameTextNode.nodeValue;

					addOption(document.formulario.TipoContrato,ValorParametroSistema,IdParametroSistema);
					
					contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
				}
			});
		}	
	}
	function contrato_local_cobranca(IdLocalCobranca){
		var temp	=	0;
		
		for(i=0;i<document.formulario.IdLocalCobranca.options.length;i++){
			if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
				document.formulario.IdLocalCobranca[i].selected	=	true;
				temp++;
				break;
			}
		}
		
		if(temp == 0){
			var  url = "xml/local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdLocalCobranca;
					
					if(document.formulario.IdLocalCobranca.options.length == 0){
						addOption(document.formulario.IdLocalCobranca,"","");
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdLocalCobranca = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoLocalCobranca = nameTextNode.nodeValue;

					addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca);
					
					contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidadeTemp.value);
				}
			});
		}	
	}
	function contrato_mes_fechado(MesFechado,QtdMesesFidelidade){
		var temp	=	0;
		for(i=0;i<document.formulario.MesFechado.options.length;i++){
			if(document.formulario.MesFechado[i].value == MesFechado){
				document.formulario.MesFechado[i].selected	=	true;
				temp++;
				break;
			}
		}
		if(temp == 0){
			var url = "xml/parametro_sistema.php?IdGrupoParametroSistema=70&IdParametroSistema="+MesFechado;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdPeriodicidade;
					
					if(document.formulario.MesFechado.options.length == 0){
						addOption(document.formulario.MesFechado,"","");
					}
						
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametroSistema = nameTextNode.nodeValue;

					addOption(document.formulario.MesFechado,ValorParametroSistema,IdParametroSistema);
					
					document.formulario.QtdMesesFidelidade.value	=	QtdMesesFidelidade;
				}
			});
		}	
	}
	function listarLocalCobrancaParametro(IdLocalCobranca,Erro,IdContrato){
		while(document.getElementById('tabelaParametroLocalCobranca').rows.length > 0){
			document.getElementById('tabelaParametroLocalCobranca').deleteRow(0);
		}
		
		if(IdLocalCobranca == ''){
			IdLocalCobranca = 0;
		}
		
		if(IdContrato == undefined){
			IdContrato = '';
		}
		
		var url = "xml/local_cobranca_parametro_contrato.php?IdLocalCobranca="+IdLocalCobranca+"&IdStatus=1";
		
	    if(IdContrato != ""){
    		url = "xml/contrato_local_cobranca_parametro_contrato.php?IdLocalCobranca="+IdLocalCobranca+"&IdStatus=1&IdContrato="+IdContrato;
	    }
		
		var Local = document.formulario.Local.value;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_parametrosLocalCobranca').style.display = 'none';	
			} else{
				var tam, linha, c0, padding, visivel, color, obsTemp = new Array(), invisivel = "", cont = 0;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdLocalCobrancaParametroContrato = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroContrato")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroContrato = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obrigatorio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorDefault = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;	
					
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
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Visivel")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Visivel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("VisivelOS")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var VisivelOS = nameTextNode.nodeValue;
					
					if(Local == 'OrdemServico'){
						Visivel	=	VisivelOS;	
					}
					
					if(IdContrato!=""){
						ValorDefault = Valor;
					}
					
					if(Visivel == '1'){
						tam = document.getElementById('tabelaParametroLocalCobranca').rows.length;
						
						obsTemp[cont] = Obs;
						
						if(cont%2 == 0){
							linha	 = document.getElementById('tabelaParametroLocalCobranca').insertRow(tam);
							tabindex = 100 + cont + 1;
							padding	 = 22;	
							pos		 = 0;
						}else{
							padding	 = 10;	
							tabindex = 100 + cont;
							pos		 = 1;
							if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
								if(Obs	==	'')	Obs	=	'<BR>';
							}
						}
						
						if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length && cont%2 == 0){
							padding	 = 22;	
						}
							
						if(Obrigatorio == 1){
							color = "#C10000";
						}else{
							color = "#000000";
						}
						
						if(Editavel == 1){
							visivel	=	'';
						}else{
							visivel	=	'readOnly';
						}
						
						linha.accessKey = IdLocalCobrancaParametroContrato; 
						
						c0	= linha.insertCell(pos);
						c0.style.verticalAlign = "top";
						
						if(IdTipoParametro == 1){
							switch(IdMascaraCampo){
								case '1':
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex='"+tabindex+"'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									break;
								case '2':
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'int')\" tabindex='"+tabindex+"'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									break;
								case '3':
									if(Editavel == '1'){
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex='"+tabindex+"'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									}else{
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" maxlength='12' tabindex='"+tabindex+"'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									}
									break;
								case '4':
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'usuario')\" maxlength='255' tabindex='"+tabindex+"'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									break;
								case '5':
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex='"+tabindex+"'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
									break;
								default:
									c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'><BR>"+Obs+"</p>";
							}
						}else{
							campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
							campo +=	"<B style='color:"+color+";'>"+DescricaoParametroContrato+"</B></p>";
							campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
							
							if(Editavel == 2){
								disabled	=	"disabled";
							}else{
								disabled	=	"";
							}
							
							campo +=	"<select name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+visivel+" tabindex='"+tabindex+"' "+disabled+">";
							campo += "<option value=''></option>";
										
							valor	=	OpcaoValor.split("\n");
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								if(trim(ValorDefault) == trim(valor[ii])){
									selecionado	=	"selected=true";
								}
								campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							campo += "</select>";
							campo +=	"<input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
							campo +=	"<BR>"+Obs+"</p>";
							
							c0.innerHTML	=	campo;
						}
						
						cont++;
					}else{
						invisivel	+=	"<div style='display:none'>";
						
						if(IdTipoParametro == 1){
							invisivel += "<input type='text' name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
						}else{
							campo  = "";
							campo +=	"<select name='LocalCobranca_Valor_"+IdLocalCobrancaParametroContrato+"'  style='width:406px;'>";
							campo += "<option value=''></option>";
										
							valor	=	OpcaoValor.split("\n");
							for(var ii=0; ii<valor.length; ii++){
								selecionado = "";
								if(trim(ValorDefault) == trim(valor[ii])){
									selecionado	=	"selected=true";
								}
								campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
							}
							campo += "</select>";
							campo +=	"<input type='hidden' name='LocalCobranca_Obrigatorio_"+IdLocalCobrancaParametroContrato+"' value='"+Obrigatorio+"'><input type='hidden' name='LocalCobranca_Visivel_"+IdLocalCobrancaParametroContrato+"' value='"+Visivel+"'>";
							
							invisivel	+=	campo;
						}
					
					
						invisivel	+=	"</div>";
					}
				}
				
				if(cont > 0){
					document.getElementById('cp_parametrosLocalCobranca').style.display			=	'block';	
					document.getElementById('tabelaParametroLocalCobranca').style.display		=	'block';		
				}
				
				if(invisivel !=""){
					tam 	 = document.getElementById('tabelaParametroLocalCobranca').rows.length;
					linha	 = document.getElementById('tabelaParametroLocalCobranca').insertRow(tam);
						
					linha.accessKey = IdLocalCobrancaParametroContrato; 
						
					c0	= linha.insertCell(0);
					c0.innerHTML	=	invisivel;
				}
			}
			
			if(document.formulario.Local.value == "ContratoServico" || document.formulario.Local.value == "ContratoStatus"){
				scrollWindow('bottom');
			}
		});
	}
	function preenche_agente_autorizado(Campo,IdAgenteAutorizadoDefault){
		if(IdAgenteAutorizadoDefault == undefined){
			IdAgenteAutorizadoDefault = '';
		}
		
		var url = "xml/agente.php?IdStatus=1&GroupBy=IdAgenteAutorizado";
		
		call_ajax(url,function (xmlhttp){
			while(Campo.options.length > 0){
				Campo.options[0] = null;
			}
			
			addOption(Campo,"","");
			
			if(xmlhttp.responseText != 'false'){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdAgenteAutorizado = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var RazaoSocial = nameTextNode.nodeValue;
					
					Nome 		= Nome.substring(0,30);
					RazaoSocial = RazaoSocial.substring(0,30);
					
					if(RazaoSocial == ''){
						RazaoSocial	= Nome;
					}
					
					addOption(Campo,RazaoSocial,IdAgenteAutorizado);
				}
				
				for(i = 0; i < Campo.options.length; i++){
					if(Campo.options[i].value == IdAgenteAutorizadoDefault){
						Campo.options[i].selected = true;
						break;
					}							
				}	
			}
		});
		if(document.formulario.Local.value == "Contrato"){
			verificarPermissaoEdicao("","","");
		}
	}
	function listar_carteira_automatico(Campo, IdAgenteAutorizado,IdCarteiraDefault){
		if(IdCarteiraDefault == undefined){
			IdCarteiraDefault = '';
		}
		
		while(Campo.options.length > 0){
			Campo.options[0] = null;
		}
		
		addOption(Campo," ","");
		
		if(IdAgenteAutorizado == ''){
			Campo.disabled = true;
			return;
		}
		
		var url = "xml/carteira.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdStatus=1";
		
		call_ajax(url,function(xmlhttp){
		
			if(xmlhttp.responseText != 'false'){
				Campo.disabled = false;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCarteira").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdCarteira = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Nome = nameTextNode.nodeValue;
					
					addOption(Campo,Nome,IdCarteira);
				}
				
				for(i = 0; i < Campo.options.length; i++){
					if(Campo.options[i].value == IdCarteiraDefault){
						Campo.options[i].selected = true;
						
						break;
					}							
				}
			}else{
				Campo.disabled = true;
			}
			
		});
	} 
	function listar_terceiros_automatico(Campo, IdServico, IdTerceiroDefault){
		if(IdServico == '' || IdServico == undefined) {
			IdServico = 0;
		}
		
		if(IdTerceiroDefault == undefined){
			IdTerceiroDefault = '';
		}
	
		while(Campo.options.length > 0){
			Campo.options[0] = null;
		}
		
		addOption(Campo," ","");
		Campo.disabled = true;
		document.getElementById("cpTerceiro_"+IdServico).style.color = "#000";
		
		var url = "xml/servico_terceiro.php?IdServico="+IdServico;
		
		call_ajax(url,function (xmlhttp) {
			if(xmlhttp.responseText != "false") {
				if(document.formulario.Local.value != 'OrdemServico'){
					Campo.disabled = false;
					document.getElementById("cpTerceiro_"+IdServico).style.color = "#C10000";
				} else{
					Campo.disabled = true;
				}
				if(document.formulario.Local.value == 'Vigencia'){
					Campo.disabled = true;
					document.getElementById("cpTerceiro_"+IdServico).style.color = "#000";
				} else{
					Campo.disabled = false;
					document.getElementById("cpTerceiro_"+IdServico).style.color = "#C10000";
				}
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdTerceiro").length; i++) {
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdTerceiro")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdTerceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					addOption(Campo,Nome,IdTerceiro);
				}
				
				if(IdTerceiroDefault != ''){
					for(i = 0; i < Campo.options.length; i++){
						if(Campo.options[i].value == IdTerceiroDefault){
							Campo.options[i].selected = true;
							break;
						}
					}
				} else{
					Campo.options[0].selected = true;
				}
			}
		});
		if(document.formulario.Local.value == "Contrato"){
			verificarPermissaoEdicao(document.formulario.PermissaoEditarContrato.value,document.formulario.PermissaoEditarParametroContrato.value);
		}
	}
