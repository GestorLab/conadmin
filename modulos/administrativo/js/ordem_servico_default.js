	function janela_busca_ordem_servico(){
		janelas('busca_ordem_servico.php',507,283,250,100,'');
	}
	function busca_ordem_servico(IdOrdemServico,Erro,Local,NumParcelaEventual){
		if(IdOrdemServico == '' || IdOrdemServico == undefined){
			IdOrdemServico = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		if(NumParcelaEventual == undefined){
			NumParcelaEventual	=	'';
		}
		
		
		var nameNode, nameTextNode, url;
			    
	   	url = "xml/ordem_servico.php?IdOrdemServico="+IdOrdemServico;
		
		if(Local == 'Protocolo'){
			if(document.formulario.IdPessoa != ''){
				IdPessoa = document.formulario.IdPessoa.value;
			} else{
				IdPessoa = document.formulario.IdPessoaF.value;
			}
			
			//url	+= "&IdPessoa="+IdPessoa+"&IdContrato="+document.formulario.IdContrato.value+"&IdContaReceber="+document.formulario.IdContaReceber.value;
		}
		
		//url += "&"+Math.random();
		
		call_ajax(url, function(xmlhttp){
				
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){	
				switch(Local){
					case 'OrdemServico':								
						document.formulario.IdOrdemServico.value					= "";
						document.formulario.TempoAbertura.value						= "";
//								document.formulario.IdTipoOrdemServico.value				= document.formulario.IdTipoOrdemServicoDefault.value;
						document.formulario.IdTipoOrdemServico.value				= "";
						document.formulario.IdTipoOrdemServicoTemp.value			= "";
						document.formulario.IdPessoa.value 							= '';
						document.formulario.IdPessoaF.value 						= '';
						document.formulario.Nome.value 								= '';
						document.formulario.NomeF.value 							= '';
						document.formulario.RazaoSocial.value 						= '';
						document.formulario.CPF.value 								= '';
						document.formulario.CNPJ.value 								= '';
						document.formulario.IdContrato.value						= "";
						document.formulario.IdServicoContrato.value					= "";
						document.formulario.DescricaoServicoContrato.value			= "";
						document.formulario.DescPeriodicidadeContrato.value			= "";
						document.formulario.QtdParcelaContrato.value				= "";
						document.formulario.DataInicio.value						= "";
						document.formulario.DescricaoServicoGrupo.value				= "";
						document.formulario.DataTermino.value						= "";
						document.formulario.DataBaseCalculo.value					= "";
						document.formulario.DataUltimaCobranca.value				= "";
						document.formulario.AssinaturaContrato.value				= "0";
						document.formulario.TipoContrato.value						= "0";
						document.formulario.IdServico.value							= "";
						document.formulario.DescricaoServico.value					= "";
						document.formulario.IdTipoServico.value						= "";
						document.formulario.Valor.value								= "";
						document.formulario.ValorOutros.value						= "";	
						document.formulario.IdStatus.value							= "";	
						document.formulario.DescricaoOS.value						= "";	
						document.formulario.DescricaoCDA.value						= "";	
						document.formulario.DescricaoOSInterna.value				= "";	
						document.formulario.Obs.value								= "";
						document.formulario.DetalheServico.value					= "";
						document.formulario.Data.value								= "";
						document.formulario.Hora.value								= "";	
						document.formulario.IdStatusNovo.value						= "";
						document.formulario.LoginAtendimentoAtual.value				= "";
						document.formulario.IdGrupoUsuarioAtendimentoAtual.value	= "";
						document.formulario.LoginAtendente.value					= "";
						document.formulario.DataAtual.value							= "";
						document.formulario.HoraAtual.value							= "";
						document.formulario.HistoricoObs.value						= "";
						document.formulario.LoginAtendimento.value					= "";
						document.formulario.IdGrupoUsuarioAtendimento.value			= "";
						document.formulario.DataCriacao.value						= "";
						document.formulario.LoginCriacao.value						= "";
						document.formulario.DataAlteracao.value						= "";
						document.formulario.LoginAlteracao.value					= "";
						document.formulario.LoginSupervisor.value					= "";
						document.formulario.ServicoAutomatico.value					= "";
						document.formulario.IdMarcador.value						= "";
						document.formulario.IdOrdemServicoLayout.value				= "";
						document.formulario.IdOrdemServicoLayoutImprimir.value		= "";
						document.formulario.Justificativa.value						= "";
						document.formulario.PermissaoGeralOsTipoAtendimento.value 	= "";
						document.formulario.PermissaoGeralOsTipoInterno.value 		= "";
						document.formulario.Faturado.value 							= '';
						document.formulario.MD5.value								= '';
						document.formulario.EcluirAnexos.value						= '';
						document.formulario.CountArquivo.value						= '0';
						document.formulario.IdAgenteAutorizado.value				=	"";
						document.formulario.DataConclusao.value						=	"";
						document.formulario.LoginConclusao.value					=	"";
						document.formulario.DataFaturamento.value					=	"";
						document.formulario.LoginFaturamento.value					=	"";
						document.formulario.NomeAgenteAutorizado.value				=	"";
						document.formulario.IdCarteira.value						=	"";
						document.formulario.IdSubTipoOrdemServicoTemp.value			=	'';
						document.formulario.UsuarioAtendimentoAntigo.value			=	'';
						document.formulario.Acao.value								= 'inserir';
						permissaoQuadroDescricaoOs();
						
						addParmUrl("marContrato","IdContrato",'');
						addParmUrl("marContrato","IdContrato",'');
						addParmUrl("marContrato","IdPessoa",'');
						addParmUrl("marContratoNovo","IdPessoa",'');
						addParmUrl("marContasReceber","IdContrato",'');
						addParmUrl("marLancamentoFinanceiro","IdContrato",'');
						addParmUrl("marProcessoFinanceiro","IdContrato",'');
						addParmUrl("marProcessoFinanceiroNovo","IdContrato",'');
						//addParmUrl("marReenvioMensagem","IdPessoa",'');
						//addParmUrl("marReenvioMensagem","IdContrato",'');
						addParmUrl("marContaEventual","IdPessoa",'');
						addParmUrl("marContaEventualNovo","IdPessoa",'');
						addParmUrl("marPessoa","IdPessoa",'');
						addParmUrl("marOrdemServico","IdPessoa",'');
						addParmUrl("marOrdemServicoNovo","IdContrato",'');
						addParmUrl("marOrdemServicoNovo","IdPessoa",'');
						addParmUrl("marOrdemServico","IdContrato",'');
						addParmUrl("marVigencia","IdContrato",'');
						addParmUrl("marVigenciaNovo","IdContrato",'');
						addParmUrl("marVigenciaNovo","IdPessoa",'');

						while(document.getElementById('tabelaParametro').rows.length > 0){
							document.getElementById('tabelaParametro').deleteRow(0);
						}
						while(document.getElementById('tabelaParametroContrato').rows.length > 0){
							document.getElementById('tabelaParametroContrato').deleteRow(0);
						}			
						
						while(document.formulario.LoginAtendimento.options.length > 0){
							document.formulario.LoginAtendimento.options[0] = null;
						}
						busca_status_novo(40,document.formulario.IdStatusNovo,'','');
						
						for(i=0; i<document.formulario.IdStatusNovo.options.length; i++){
							if(document.formulario.IdStatusNovo.options[i].value >= 0 && document.formulario.IdStatusNovo.options[i].value <= 99){
								document.formulario.IdStatusNovo.options[i] = null;
							}
						}
						
						listarOrdemServicoCliente('');
						addOption(document.formulario.IdStatusNovo,"","");
						
						while(document.getElementById('tabelaHistorico').rows.length > 2){
							document.getElementById('tabelaHistorico').deleteRow(1);
						}
						
						while(document.formulario.IdPessoaEndereco.options.length > 0){
							document.formulario.IdPessoaEndereco.options[0] = null;
						}
						
						document.formulario.IdPessoaEndereco.value					=	"";
						document.formulario.IdPessoaEnderecoTemp.value				=	"";
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
						
						document.formulario.NomeRepresentante.value 				=	"";
						document.formulario.InscricaoEstadual.value 				= 	"";
						document.formulario.DataNascimento.value 					=	"";	
						document.formulario.RG.value 								=	"";
						document.formulario.Telefone1.value 						=	"";
						document.formulario.Telefone2.value 						= 	"";
						document.formulario.Telefone3.value 						= 	"";
						document.formulario.Celular.value 							= 	"";	
						document.formulario.Fax.value 								= 	"";
						document.formulario.ComplementoTelefone.value 				= 	"";
						document.formulario.EmailJuridica.value 					= 	"";
	
						document.getElementById('cp_historico_os').style.display	=	'none';	
						document.getElementById('tableMarcador').style.display		=	'none';	
						document.getElementById('titTableMarcador').style.display	=	'none';
						document.getElementById("cpArquivosAnexados").style.display	=	"none";
						document.getElementById("tableEmAndamento").style.display	=	"none";
						
						document.formulario.IdGrupoUsuarioAtendimento.disabled		= 	false;
						document.formulario.LoginAtendimento.disabled				= 	false;
						document.formulario.IdStatus.disabled						= 	true;
						document.formulario.IdStatusNovo.disabled					= 	false;
						document.formulario.DescricaoOS.readOnly					= 	false;
						document.formulario.Valor.readOnly							= 	false;
						document.formulario.Data.readOnly							=	false;
						document.formulario.Hora.readOnly							=	false;
						document.formulario.Obs.readOnly							=	false;
						document.formulario.IdPessoa.readOnly						= 	false;
						document.formulario.IdPessoaF.readOnly 						=	false;
						document.formulario.IdServico.readOnly						= 	false;
						document.formulario.IdContrato.readOnly						= 	false;
						document.formulario.IdTipoOrdemServico.disabled				= 	false;
						document.formulario.ValorOutros.readOnly 					= 	false;
						document.formulario.IdSubTipoOrdemServico.disabled			=	false;
						document.formulario.IdPessoaEndereco.disabled 				=	false;
						
						document.getElementById('cp_juridica').style.display		= 'block';
						document.getElementById('cp_fisica').style.display			= 'none';
						document.getElementById('cp_Status').style.display			= "none";
						document.getElementById('titStatus').style.color			= "#C10000";
						
						document.getElementById('cp_parametrosServico').style.display	= 'none';
						document.getElementById('cp_parametrosContrato').style.display	= 'none';
						document.getElementById("cpHistoricoAtual").style.display		= "none";
						document.getElementById("cpFaturamento").style.display			= "none";								
						document.getElementById("cpDescricaoOSInterna").style.display	= "none";
						document.getElementById("cp_automatico").style.display			= "none";
						document.getElementById('cp_automatico').innerHTML				= "";
																		
						document.getElementById("titTempoAbertura").style.display		= 'none';						
						document.getElementById("cp_TempoAbertura").style.display		= 'none';							
						document.getElementById("cpStatus").style.width					= '260px';
						addParmUrl("marOrdemServico","IdOrdemServico",'');
						
						calcula_total();
						atualiza_tipo_servico(document.formulario.IdTipoOrdemServico.value, document.formulario.IdTipoOrdemServicoDefault.value);
						document.formulario.IdOrdemServico.focus();
						status_inicial();
						permissaoQuadroDescricaoOs();
						break;
					case 'OrdemServicoFatura':
						document.formulario.IdOrdemServico.value						= "";
						document.formulario.IdTipoOrdemServico.value					= "";
						document.formulario.IdSubTipoOrdemServico.value					= "";
						document.formulario.IdPessoa.value 								= '';
						document.formulario.Nome.value 									= '';
						document.formulario.IdPessoaF.value 							= '';
						document.formulario.NomeF.value 								= '';
						document.formulario.RazaoSocial.value 							= '';						
						document.formulario.Email.value 								= '';					
						document.formulario.IdContrato.value							= "";
						document.formulario.IdServicoContrato.value						= "";
						document.formulario.DescricaoServicoContrato.value				= "";
						document.formulario.DescPeriodicidadeContrato.value				= "";
						document.formulario.QtdParcelaContrato.value					= "";
						document.formulario.DataInicio.value							= "";
						document.formulario.DataTermino.value							= "";
						document.formulario.DataBaseCalculo.value						= "";
						document.formulario.DataUltimaCobranca.value					= "";
						document.formulario.AssinaturaContrato.value					= "";
						document.formulario.TipoContrato.value							= "";
						document.formulario.IdServico.value								= "";
						document.formulario.DescricaoServico.value						= "";
						document.formulario.IdTipoServico.value							= "";
						document.formulario.Valor.value									= "";	
						document.formulario.IdStatus.value								= "";	
						document.formulario.DescricaoOS.value							= "";	
						document.formulario.DetalheServico.value						= "";
						document.formulario.PercentualParcela.value						= "";
						document.formulario.ValorRepasseTerceiro.value					= "";
						document.formulario.PercentualRepasseTerceiro.value				= "";
						document.formulario.ValorRepasseTerceiroOutros.value			= "";
						document.formulario.PercentualRepasseTerceiroOutros.value		= "";
						document.getElementById("descricaoNotaFiscal").innerHTML		= "";
						document.getElementById("descricaoNotaFiscal").style.display	= "none";
						
						document.formulario.ValorTotal.value							= "";
						document.formulario.FormaCobranca.value							= "";
						document.formulario.FormaCobrancaTemp.value						= "";	
						document.formulario.QtdParcela.value							= "";
						document.formulario.DataPrimeiroVencimentoContrato.value		= "";
						document.formulario.IdLocalCobranca.value						= "";
						document.formulario.IdLocalCobrancaTemp.value					= "";
						document.formulario.ValorDespesaLocalCobranca.value				= "";
						document.formulario.DataPrimeiroVencimentoIndividual.value		= "";
						document.formulario.IdContratoAgrupador.value					= "";
						document.formulario.IdContratoIndividual.value					= "";
						document.formulario.simulado.value								= "";
						document.formulario.Faturado.value 								= "";
						document.formulario.CNPJ.value									= "";
						document.formulario.DataCriacao.value							= "";
						document.formulario.LoginCriacao.value							= "";
						document.formulario.DataAlteracao.value							= "";
						document.formulario.LoginAlteracao.value						= "";
						document.formulario.DataFaturamento.value						= "";
						document.formulario.LoginFaturamento.value						= "";
						document.formulario.Acao.value									= 'inserir';
						
						addParmUrl("marContrato","IdContrato",'');
						addParmUrl("marContrato","IdContrato",'');
						addParmUrl("marContrato","IdPessoa",'');
						addParmUrl("marContratoNovo","IdPessoa",'');
						addParmUrl("marContasReceber","IdContrato",'');
						addParmUrl("marLancamentoFinanceiro","IdContrato",'');
						addParmUrl("marProcessoFinanceiro","IdContrato",'');
						addParmUrl("marProcessoFinanceiroNovo","IdContrato",'');
						//addParmUrl("marReenvioMensagem","IdPessoa",'');
						//addParmUrl("marReenvioMensagem","IdContrato",'');
						addParmUrl("marContaEventual","IdPessoa",'');
						addParmUrl("marContaEventualNovo","IdPessoa",'');
						addParmUrl("marPessoa","IdPessoa",'');
						addParmUrl("marOrdemServico","IdPessoa",'');
						addParmUrl("marOrdemServicoNovo","IdContrato",'');
						addParmUrl("marOrdemServicoNovo","IdPessoa",'');
						addParmUrl("marOrdemServico","IdContrato",'');
						addParmUrl("marVigencia","IdContrato",'');
						addParmUrl("marVigenciaNovo","IdContrato",'');
						addParmUrl("marVigenciaNovo","IdPessoa",'');
/*								
						while(document.getElementById('tabelaVencimento').rows.length > 0){
							document.getElementById('tabelaVencimento').deleteRow(0);
						}	
*/								while(document.formulario.IdContratoAgrupador.options.length > 0){
							document.formulario.IdContratoAgrupador.options[0] = null;
						}
						while(document.formulario.IdContratoIndividual.options.length > 0){
							document.formulario.IdContratoIndividual.options[0] = null;
						}		
						while(document.formulario.IdPessoaEnderecoCobranca.options.length > 0){
							document.formulario.IdPessoaEnderecoCobranca.options[0] = null;
						}
						
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
						
						document.getElementById('cp_juridica').style.display		= 'block';
						document.getElementById('cp_fisica').style.display			= 'none';							
						document.getElementById('cp_Status').style.display			= "none";
						document.getElementById("cp_Vencimento").style.display		= "none";
						
						document.getElementById('obsDataPrimeiroVencimentoContrato').style.display		= "none";
						document.getElementById('titDataPrimeiroVencimentoContrato').style.display		= "none";
						document.getElementById("titLocalCobranca").style.display						= "none";
						document.getElementById("titContrato").style.display							= "none";
						document.getElementById('titValorDespesas').style.display						= "none";
						document.getElementById('cpDataPrimeiroVencimentoContrato').style.display		= "none";
						document.getElementById("cpValorDespesa").style.display							= "none";
						document.getElementById('cpDataPrimeiroVencimento').style.display				= "none";
						document.getElementById('titQtdParcela').style.display							= "none";
						document.getElementById('cpQtdParcela').style.display							= "none";
						document.getElementById('cpLocalCobranca').style.display						= "none";
						document.getElementById('cpContrato').style.display								= "none";
						document.getElementById('titContratoInd').style.display							= "none";
						document.getElementById('cpContratoInd').style.display							= "none";
						document.getElementById('cpTerceiro').style.display								= "none";
						
						document.getElementById('titDataPrimeiroVencimentoIndividual').style.display	= "none";
						document.getElementById('cpDataPrimeiroVencimentoIndividual').style.display		= "none";
						document.getElementById('cpDataPrimeiroVencimentoIndividualIco').style.display	= "none";
						document.getElementById('titFormaCobranca').style.display						= "none";
						document.getElementById('cpFormaCobranca').style.display						= "none";
						
						document.formulario.QtdParcela.readOnly							=   false;
						document.formulario.IdLocalCobranca.disabled					=   false;
						document.formulario.ValorDespesaLocalCobranca.readOnly			=   false;
						document.formulario.bt_simular.disabled							=   false;
						document.formulario.DataPrimeiroVencimentoIndividual.readOnly	=   false;
						document.formulario.DataPrimeiroVencimentoContrato.readOnly		=   false;
						document.formulario.bt_simular.disabled							=	true;
						document.formulario.bt_enviar.disabled 							= 	true;	
						
						listar_terceiros(0);
						addParmUrl("marOrdemServico","IdOrdemServico",'');
						status_inicial();
						
						verificar_local_cobranca("","","");
						document.formulario.IdOrdemServico.focus();
						break;
					case 'LancamentoFinanceiro':
						document.formulario.IdOrdemServico.value 					= '';
						document.formulario.IdServicoOS.value 						= '';
						document.formulario.DescricaoServicoOS.value 				= '';
						document.formulario.FormaCobrancaOS[0].selected 			= true;
						document.formulario.IdContratoAgrupador[0].selected 		= true;
						document.formulario.ValorTotalContrato.value				= '';
						document.formulario.QtdParcelaContrato.value				= '';
						document.formulario.DataPrimeiroVencimentoContrato.value	= '';
						document.formulario.ValorTotalIndividual.value				= '';
						document.formulario.ValorDespesaLocalCobranca.value			= '';
						document.formulario.QtdParcelaIndividual.value				= '';
						document.formulario.DataPrimeiroVencimentoIndividual.value	= '';
					
						document.getElementById('cpContaEventual').style.display	=	'none';
						break;
					case 'Protocolo':
						document.formulario.IdOrdemServico.value 			= '';
						document.formulario.NomePessoaOrdemServico.value	= '';
						document.formulario.IdTipoOrdemServico.value 		= '';
						document.formulario.IdStatusOrdemServico.value 		= '';
						document.formulario.NomeServicoOrdemServico.value 	= '';
						document.formulario.DescricaoOrdemServico.value 	= '';
						document.formulario.ValorOdemServico.value 			= '';
						
						document.formulario.IdContaEventual.readOnly = false;
						document.formulario.IdContaEventual.onfocus = function (){
							Foco(this,'in');
						};
				}				
			
				// Fim de Carregando
				carregando(false);
			}else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdSubTipoOrdemServico = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdServicoContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoa = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContrato = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAtendimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAtendimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuarioAtendimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdGrupoUsuarioAtendimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdLocalCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutros")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorOutros = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Valor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoOS")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoOS = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCDA")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoCDA = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Obs = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAgendamentoAtendimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAgendamentoAtendimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescTipoOrdemServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServicoLayoutImprimir")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdOrdemServicoLayoutImprimir = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Faturado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Faturado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataConclusao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataConclusao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginConclusao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginConclusao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataFaturamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataFaturamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginFaturamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginFaturamento = nameTextNode.nodeValue;
				
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
				
				switch(Local){
					case 'OrdemServico':
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoaEndereco = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdMarcador")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdMarcador = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("PermissaoFatura")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var PermissaoFatura = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("PermissaoGeralOsTipoAtendimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var PermissaoGeralOsTipoAtendimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("PermissaoGeralOsTipoInterno")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var PermissaoGeralOsTipoInterno = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("TempoAbertura")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var TempoAbertura = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Justificativa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Justificativa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador1")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CorMarcador1 = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador2")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CorMarcador2 = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CorMarcador3")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CorMarcador3 = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoFaturamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContratoFaturamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdAgenteAutorizado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeAgenteAutorizado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeAgenteAutorizado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCarteira = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("MD5")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var MD5 = nameTextNode.nodeValue;
						
						document.formulario.EcluirAnexos.value = '';
						Data = dateFormat(DataAgendamentoAtendimento.substr(0,10));
						Hora = DataAgendamentoAtendimento.substr(11,5);
						
						if(IdTipoOrdemServico == 4){
							addOption(document.formulario.IdTipoOrdemServico,DescTipoOrdemServico,IdTipoOrdemServico);
						}	
						
						if(Hora == '00:00'){
							Hora = "";
						}			
						
						document.formulario.IdGrupoUsuarioAtendimento.value			= 	IdGrupoUsuarioAtendimento;								
						document.formulario.TempoAbertura.value						=	TempoAbertura;
						document.formulario.IdOrdemServico.value					=	IdOrdemServico;
						document.formulario.IdServicoContrato.value					=	IdServicoContrato;
						document.formulario.IdServico.value							=	IdServico;
						document.formulario.IdPessoa.value							=	IdPessoa;
						document.formulario.IdPessoaF.value							=	IdPessoa;
						document.formulario.IdTipoOrdemServico.value				=	IdTipoOrdemServico;
						document.formulario.IdTipoOrdemServicoTemp.value			= 	IdTipoOrdemServico;
						document.formulario.IdSubTipoOrdemServico.value				= 	IdSubTipoOrdemServico;
						document.formulario.IdSubTipoOrdemServicoTemp.value			= 	IdSubTipoOrdemServico;
						document.formulario.ValorOutros.value						=	formata_float(Arredonda(ValorOutros,2),2).replace(".",",");
						document.formulario.Valor.value								=	formata_float(Arredonda(Valor,2),2).replace(".",",");
						document.formulario.IdStatus.value							=	IdStatus;
						document.formulario.IdStatusNovo.value						=	"";
						document.formulario.IdContrato.value						=	IdContrato;
						document.formulario.DataCriacao.value						=	dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value						=	LoginCriacao;
						document.formulario.DataAlteracao.value						=	dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value					=	LoginAlteracao;
						document.formulario.LoginAtendimentoAtual.value				=	LoginAtendimento;
						document.formulario.LoginAtendente.value					=	LoginAtendimento;
						document.formulario.UsuarioAtendimentoAntigo.value			=	LoginAtendimento;
						document.formulario.IdGrupoUsuarioAtendimentoAtual.value	=	IdGrupoUsuarioAtendimento;
						document.formulario.DataAtual.value							=	Data;
						document.formulario.HoraAtual.value							=	Hora;
						document.formulario.IdMarcador.value						=	IdMarcador;
						document.formulario.HistoricoObs.value						=	Obs;
						document.formulario.IdOrdemServicoLayout.value				=	"";
						document.formulario.IdOrdemServicoLayoutImprimir.value		=	IdOrdemServicoLayoutImprimir;
						document.formulario.LoginAtendimento.value					=	"";						
						document.formulario.Obs.value								=	"";
						document.formulario.Data.value								=	"";
						document.formulario.Hora.value								=	"";
						document.formulario.Periodicidade.value						=	"";
						document.formulario.ServicoAutomatico.value					=	"";								
						document.formulario.PermissaoGeralOsTipoAtendimento.value 	= 	PermissaoGeralOsTipoAtendimento;
						document.formulario.PermissaoGeralOsTipoInterno.value 		= 	PermissaoGeralOsTipoInterno;
						document.formulario.PermissaoFatura.value 					= 	PermissaoFatura;
						document.formulario.Justificativa.value 					= 	Justificativa;
						document.formulario.Faturado.value 							= 	Faturado;
						document.formulario.IdAgenteAutorizado.value				=	IdAgenteAutorizado;
						document.formulario.MD5.value								=	MD5;
						document.formulario.LoginConclusao.value					=	LoginConclusao;
						document.formulario.DataConclusao.value						=	dateFormat(DataConclusao);
						document.formulario.LoginFaturamento.value					=	LoginFaturamento;
						document.formulario.DataFaturamento.value					=	dateFormat(DataFaturamento);
						document.formulario.NomeAgenteAutorizado.value				=	NomeAgenteAutorizado;
						document.formulario.Acao.value 								=	'alterar';
						
						
						atualiza_tipo_servico(IdTipoOrdemServico,IdSubTipoOrdemServico);
						busca_subtipo_ordem_servico(IdTipoOrdemServico,IdSubTipoOrdemServico);
						listarOrdemServicoCliente(IdPessoa);
						busca_login_supervisor(IdGrupoUsuarioAtendimento,document.formulario.LoginSupervisorAtual);
						buscaAtendimento(IdOrdemServico);
						
						if((IdStatus >=0 && IdStatus <= 99) || (IdStatus >= 200 && IdStatus <= 299)){		
							document.getElementById("titTempoAbertura").style.display	= 'none';						
							document.getElementById("cp_TempoAbertura").style.display	= 'none';
//									document.getElementById("sep_TempoAbertura").style.display	= 'none';
//									document.getElementById("sepTempoAbertura").style.display	= 'none';
						}else{									
							if(IdStatus >=100 && IdStatus <= 199){
//										document.getElementById("sep_TempoAbertura").style.display	= 'block';
//										document.getElementById("sepTempoAbertura").style.display	= 'block';
								document.getElementById("cpStatus").style.width				= '210px';	
								document.getElementById("cpMarcador").style.paddingRight	= '8px';			
							}else{	
								document.getElementById("cpStatus").style.width				= '312px';
								document.getElementById("cpMarcador").style.paddingRight	= '0';												
//										document.getElementById("sep_TempoAbertura").style.display	= 'none';
//										document.getElementById("sepTempoAbertura").style.display	= 'none';						
							}
							document.getElementById("titTempoAbertura").style.display		= 'block';	
							document.getElementById("cp_TempoAbertura").style.display		= 'block';																								
						}
						
						if(Obs!=""){
							document.getElementById("cpHistoricoAtual").style.display	= "block";
						}else{
							document.getElementById("cpHistoricoAtual").style.display	= "none";
						}
						document.getElementById('cp_parametrosServico').style.display	= 'none';
						document.getElementById('cp_parametrosContrato').style.display	= 'none';
						
						
						document.getElementById("cpFaturamento").style.display			= "none";
						
						document.getElementById('titStatus').style.color				= "#000";								
						document.getElementById('cp_automatico').innerHTML				= "";							
						document.getElementById('cp_automatico').style.display			= "none";
						
						while(document.getElementById('tabelaParametro').rows.length > 0){
							document.getElementById('tabelaParametro').deleteRow(0);
						}
						while(document.getElementById('tabelaParametroContrato').rows.length > 0){
							document.getElementById('tabelaParametroContrato').deleteRow(0);
						}
						
						if(IdTipoOrdemServico == 1)	busca_pessoa(IdPessoa,false,Local);
				
						busca_status(IdStatus);
						
						
						document.formulario.DescricaoCDA.value						=	DescricaoCDA;
						document.formulario.DescricaoOS.value						=	DescricaoOS;
						document.formulario.DescricaoOSInterna.value				=	DescricaoOS;
						document.formulario.Obs.value								= 	'';	
						
						if(IdTipoOrdemServico == 1)	busca_servico(IdServico,false);
						
						busca_login_usuario(IdGrupoUsuarioAtendimento,document.formulario.LoginAtendimentoAtual,LoginAtendimento);
						
						if(IdTipoOrdemServico == 1){
							listarParametro(IdOrdemServico,IdServico,false);						
							busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEndereco);
							busca_pessoa_endereco(IdPessoa,IdPessoaEndereco);
						}
						
						if(IdContrato != ""){
							busca_contrato(IdContrato,false,'OrdemServico');
						}else{
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
							
							for(var i=0; i<document.formulario.TipoContrato.length; i++){
								if(document.formulario.TipoContrato[i].value == ''){
									document.formulario.TipoContrato[i].selected = true;
									i = document.formulario.TipoContrato.length;
								}							
							}
						}

						addParmUrl("marPessoa","IdPessoa",IdPessoa);
						addParmUrl("marContrato","IdPessoa",IdPessoa);
						addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
						addParmUrl("marVigenciaNovo","IdPessoa",IdPessoa);
						addParmUrl("marContasReceber","IdPessoa",IdPessoa);
						addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
						addParmUrl("marLancamentoFinanceiro","IdOrdemServico",IdOrdemServico);
						addParmUrl("marContasReceber","IdOrdemServico",IdOrdemServico);
						addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
						addParmUrl("marContaEventual","IdPessoa",IdPessoa);
						addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
						addParmUrl("marReenvioMensagem","IdOrdemServico",IdOrdemServico);
						/*
						if(IdStatus >= 500 && IdStatus <= 599){
							addParmUrl("marOrdemServico","IdOrdemServico",IdOrdemServico);
						}else{
							addParmUrl("marOrdemServico","IdOrdemServico","");
						}*/						
						
						document.getElementById('titGrupoAtendimento').style.display	= 'none';
						document.getElementById('titUsuarioAtendimento').style.display	= 'none';
						document.getElementById('titDataAgendamento').style.display		= 'none';
						document.getElementById('titHoraAgendamento').style.display		= 'none';
						document.getElementById('cpGrupoAtendimento').style.display		= 'none';
						document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
						document.getElementById('cpData').style.display					= 'none';
						document.getElementById('cpDataIco').style.display				= 'none';
						document.getElementById('cpHora').style.display					= 'none';
				
						document.getElementById('titObs').style.color					= '#000';
						
						document.formulario.IdPessoa.readOnly				= 	true;
						document.formulario.IdServico.readOnly				= 	true;
						document.formulario.IdContrato.readOnly				= 	true;
						document.formulario.IdTipoOrdemServico.disabled		= 	true;
						document.formulario.IdSubTipoOrdemServico.disabled	= 	false;
						document.formulario.IdStatusNovo.disabled			=   false;
							
						if(document.formulario.Login.value == LoginAtendimento || document.formulario.Login.value == document.formulario.LoginCriacao.value){
							document.formulario.DescricaoOS.readOnly					= 	false;
							document.formulario.Valor.readOnly							= 	false;
							document.formulario.IdStatusNovo.disabled					=	false;
							document.formulario.Data.readOnly							=	false;
							document.formulario.Hora.readOnly							=	false;
							document.formulario.IdGrupoUsuarioAtendimento.disabled		=	false;
							document.formulario.LoginAtendimento.disabled				=	false;
							document.formulario.Obs.readOnly							=	false;
							temp = 1;
						}else{
							document.formulario.DescricaoOS.readOnly					= 	true;
							document.formulario.Valor.readOnly							= 	true;

							if(((IdStatus > 199 && IdStatus < 400) && document.formulario.Login.value == LoginCriacao) || (IdStatus > 199 && IdStatus < 300)){
								document.formulario.IdStatusNovo.disabled					=	false;
								document.formulario.Data.readOnly							=	false;
								document.formulario.Hora.readOnly							=	false;
								document.formulario.IdGrupoUsuarioAtendimento.disabled		=	false;
								document.formulario.LoginAtendimento.disabled				=	false;
								document.formulario.Obs.readOnly							=	false;	
							}else{
								document.formulario.IdStatusNovo.disabled					=	true;
								document.formulario.Data.readOnly							=	true;
								document.formulario.Hora.readOnly							=	true;
								document.formulario.IdGrupoUsuarioAtendimento.disabled		=	true;
								document.formulario.LoginAtendimento.disabled				=	true;
								document.formulario.Obs.readOnly							=	false;
							}
						}
						if((IdStatus >= 0 && IdStatus <=99) || (IdStatus >= 400 && IdStatus <= 499)){
							document.formulario.DescricaoOS.readOnly					= 	true;
							document.formulario.Valor.readOnly							=	true;
							document.formulario.Data.readOnly							=	true;
							document.formulario.Hora.readOnly							=	true;
							document.formulario.IdGrupoUsuarioAtendimento.disabled		=	true;
							document.formulario.LoginAtendimento.disabled				=	true;
							document.formulario.Obs.readOnly							=	false;
							
							if(IdStatus >= 400 && IdStatus <= 499){
								document.formulario.IdStatusNovo.disabled				=	false;
								
								addStatus('0,100');	
								
								document.formulario.IdStatusNovo[0].selected	=	true;									
							}else{
								document.formulario.IdStatusNovo.disabled		=	true;
								addStatus('');
							}
						}else{
							addStatus('');
						}
						
						if(IdTipoOrdemServico == 2){
							document.getElementById('cpDescricaoOSInterna').style.display	=	'block';
						}else{
							document.getElementById('cpDescricaoOSInterna').style.display	=	'none';
						}
						if(IdTipoOrdemServico == 1){								
							if(PermissaoGeralOsTipoAtendimento == 1 && ((IdStatus >= 100 && IdStatus <=199) || (IdStatus >= 300 && IdStatus <= 399))){																	
								document.formulario.IdStatusNovo.disabled				=	false;
								document.formulario.Data.readOnly						=	false;
								document.formulario.Hora.readOnly						=	false;
								document.formulario.IdGrupoUsuarioAtendimento.disabled	=	false;
								document.formulario.LoginAtendimento.disabled			=	false;
								document.formulario.Obs.readOnly						=	false;
								document.formulario.DescricaoOS.readOnly				= 	false;		
								document.formulario.Valor.readOnly						=	false;									
							}else{																	
								if((IdStatus >= 100 && IdStatus <= 199) || (IdStatus >= 300 && IdStatus <=399)){										
									verifica_permissao_update(IdGrupoUsuarioAtendimento);
								}
							}
						}else{									
							if(PermissaoGeralOsTipoInterno == 1 && ((IdStatus >= 100 && IdStatus <= 199) || (IdStatus >= 300 && IdStatus <= 399))){																			
								document.formulario.IdStatusNovo.disabled				=	false;
								document.formulario.Data.readOnly						=	false;
								document.formulario.Hora.readOnly						=	false;
								document.formulario.IdGrupoUsuarioAtendimento.disabled	=	false;
								document.formulario.LoginAtendimento.disabled			=	false;
								document.formulario.Obs.readOnly						=	false;		
								document.formulario.DescricaoOS.readOnly				= 	false;		
								document.formulario.Valor.readOnly						=	false;																				
							}else{											
								if((IdStatus >= 100 && IdStatus <= 199) || (IdStatus >= 300 && IdStatus <= 399)){										
									verifica_permissao_update(IdGrupoUsuarioAtendimento);
								}
							}								
						}
						if(PermissaoFatura == true && (IdStatus >= 400 && IdStatus <=499)){
							if(IdStatus <= 499){
								document.formulario.IdStatusNovo.disabled			=	false;
							}else{
								document.formulario.IdStatusNovo.disabled			=	true;
							}									
							document.formulario.Data.readOnly						=	false;
							document.formulario.Hora.readOnly						=	false;
							document.formulario.IdGrupoUsuarioAtendimento.disabled	=	false;
							document.formulario.LoginAtendimento.disabled			=	false;
							document.formulario.Obs.readOnly						=	false;
							document.formulario.DescricaoOS.readOnly				= 	false;
							document.formulario.Valor.readOnly						=	false;
								
						}								
						
						if(IdStatus >= 100 && IdStatus <= 199){
							document.getElementById('titTableMarcador').style.display	=	'block';	
							document.getElementById('tableMarcador').style.display		=	'block';
							
							document.getElementById('mVermelho').style.backgroundColor	=	CorMarcador1;
							document.getElementById('mAmarelo').style.backgroundColor	=	CorMarcador2;
							document.getElementById('mVerde').style.backgroundColor		=	CorMarcador3;
							document.formulario.IdMarcador.value						=	IdMarcador;
							
							if(IdMarcador != '3' && IdMarcador != '2' && IdMarcador != '1'){
								document.formulario.IdMarcador.value	=	'';
							}
						}else{
							document.getElementById('titTableMarcador').style.display	=	'none';
							document.getElementById('tableMarcador').style.display		=	'none';													
						}
						
						if(IdStatus < 100 ) {
							document.formulario.IdAgenteAutorizado.readOnly = true;
							document.formulario.IdCarteira.disabled = true;
						} else {
							document.formulario.IdAgenteAutorizado.readOnly = false;
						}
						
						buscar_arquivo(IdOrdemServico);
						listar_carteira(IdAgenteAutorizado, IdCarteira);
						calcula_total();
						
						document.formulario.bt_inserir.disabled 	= true;
						
						while(document.getElementById('tabelaArquivos').rows.length > 0){
							document.getElementById('tabelaArquivos').deleteRow(0);
						}
						document.formulario.CountArquivo.value = 0;
						
						if(Faturado == 1) {
							document.formulario.Valor.readOnly = true;
							document.formulario.ValorOutros.readOnly = true;
							document.formulario.ValorTotal.readOnly = true;
						} else {
							document.formulario.Valor.readOnly = false;
							document.formulario.ValorOutros.readOnly = false;
							//document.formulario.ValorTotal.readOnly = false;
						}
						if(IdStatus == 200){
							document.formulario.IdSubTipoOrdemServico.disabled = true;
							document.formulario.IdPessoa.readOnly = true;
							document.formulario.IdPessoaF.readOnly = true;
							document.formulario.ValorOutros.readOnly = true;
							document.formulario.IdPessoaEndereco.disabled = true;
							
						}
						
						verificaAcao();
						addArquivo();
						permissaoQuadroDescricaoOs();
						break;
					case 'OrdemServicoFatura':
						nameNode = xmlhttp.responseXML.getElementsByTagName("FormaCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var FormaCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var QtdParcela = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataFaturamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataFaturamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginFaturamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginFaturamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiroVencimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataPrimeiroVencimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescTipoOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoFaturamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContratoFaturamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEnderecoCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoaEnderecoCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("EditarTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var EditarTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLancamentoFinanceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorRepasseTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Carne")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Carne = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdContaReceberAguardandoPagamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var QtdContaReceberAguardandoPagamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCartao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContaDebito = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaF")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalCobrancaF = nameTextNode.nodeValue;
						
						if(IdTipoOrdemServico == 4){
							addOption(document.formulario.IdTipoOrdemServico,DescTipoOrdemServico,IdTipoOrdemServico);
						}
						
						while(document.formulario.IdContratoAgrupador.options.length > 0){
							document.formulario.IdContratoAgrupador.options[0] = null;
						}
						while(document.formulario.IdContratoIndividual.options.length > 0){
							document.formulario.IdContratoIndividual.options[0] = null;
						}
						while(document.formulario.IdPessoaEnderecoCobranca.options.length > 0){
							document.formulario.IdPessoaEnderecoCobranca.options[0] = null;
						}
						
						var ValorTotal = parseFloat(parseFloat(Valor)+parseFloat(ValorOutros));
						
						if(IdLancamentoFinanceiro != ''){
							document.formulario.AtualizarValorRepasseTerceiro.value = 0;
						}
						
						if(ValorRepasseTerceiro != '') {
							ValorRepasseTerceiro = formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace(/\./i,",")
						}
						
						document.formulario.ValorRepasseTerceiro.value				= ValorRepasseTerceiro;
						document.formulario.PercentualRepasseTerceiro.value			= '';
						document.formulario.ValorRepasseTerceiroOutros.value		= '';
						document.formulario.PercentualRepasseTerceiroOutros.value	= '';
						document.formulario.IdPessoa.value							=	IdPessoa;
						document.formulario.IdPessoaF.value							=	IdPessoa;
						document.formulario.IdOrdemServico.value					=	IdOrdemServico;
						document.formulario.IdTipoOrdemServico.value				=	IdTipoOrdemServico;
						document.formulario.IdSubTipoOrdemServico.value				=	IdSubTipoOrdemServico;
						document.formulario.Valor.value								=	formata_float(Arredonda(Valor,2),2).replace(".",",");
						document.formulario.ValorOutros.value						=	formata_float(Arredonda(ValorOutros,2),2).replace(".",",");
						document.formulario.ValorTotal.value						=	formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
						document.formulario.IdStatus.value							=	IdStatus;
						document.formulario.DescricaoOS.value						=	DescricaoOS;
						document.formulario.IdContrato.value						=	IdContrato;	
						document.formulario.DataCriacao.value						=	dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value						=	LoginCriacao;
						document.formulario.DataAlteracao.value						=	dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value					=	LoginAlteracao;
						document.formulario.DataFaturamento.value					=	dateFormat(DataFaturamento);
						document.formulario.LoginFaturamento.value					=	LoginFaturamento;
						document.formulario.FormaCobranca.value						=   FormaCobranca;
						document.formulario.FormaCobrancaTemp.value					=   FormaCobranca;		
						document.formulario.QtdParcela.value						=   QtdParcela;
						document.formulario.IdLocalCobranca.value					=   IdLocalCobrancaF;
						document.formulario.IdLocalCobrancaTemp.value				=   IdLocalCobrancaF;
						document.formulario.EditarTerceiro.value					=   EditarTerceiro;
						document.formulario.ValorDespesaLocalCobranca.value			=   formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
						document.formulario.IdContratoAgrupador.value				=   "";
						document.formulario.IdContratoIndividual.value				=   "";
						document.formulario.simulado.value							=	"";
						document.formulario.Faturado.value 							= 	Faturado;
						document.formulario.Acao.value 								= 	"alterar";
						
						//Controle de visibilidade do botao Cancelar Faturamento
						if(IdStatus == 400 || IdStatus == 100 && QtdParcela > 0){
							document.formulario.bt_cancelar.style.display = "inline";
						}
						else{
							document.formulario.bt_cancelar.style.display = "none";
						}
						
						verificaParcela(QtdParcela);
						busca_status(IdStatus);
						atualiza_tipo_servico(IdTipoOrdemServico,IdSubTipoOrdemServico);		
						busca_servico(IdServico,false);
						listar_terceiros(IdServico,IdTerceiro);
						
						if(IdPessoaEnderecoCobranca!=""){
							busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEnderecoCobranca);
							busca_pessoa_endereco_cobranca(IdPessoa,IdPessoaEnderecoCobranca);
							busca_pessoa(IdPessoa,false,Local);
						}else{
							busca_pessoa(IdPessoa,false,Local);	
						}
						
						if(IdContrato != ""){
							busca_contrato(IdContrato,false);
						}else{
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
							
							if(document.formulario.IdOrdemServico.value == ""){
								document.formulario.IdLocalCobranca.value			=	"";
								document.formulario.IdLocalCobrancaTemp.value		=	"";
							}
							
							for(var i=0; i<document.formulario.TipoContrato.length; i++){
								if(document.formulario.TipoContrato[i].value == ''){
									document.formulario.TipoContrato[i].selected = true;
									i = document.formulario.TipoContrato.length;
								}							
							}
						}
						
						
						addParmUrl("marPessoa","IdPessoa",IdPessoa);
						addParmUrl("marContrato","IdPessoa",IdPessoa);
						addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
						addParmUrl("marContasReceber","IdPessoa",IdPessoa);
						addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
						addParmUrl("marLancamentoFinanceiro","IdOrdemServico",IdOrdemServico);
						addParmUrl("marContasReceber","IdOrdemServico",IdOrdemServico);
						addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
						addParmUrl("marContaEventual","IdPessoa",IdPessoa);
						addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServico","IdOrdemServico","");
						addParmUrl("marReenvioMensagem","IdOrdemServico",IdOrdemServico);
						
						if(DataPrimeiroVencimento == ""){
							document.formulario.DataPrimeiroVencimentoContrato.value = data();	
							document.formulario.DataPrimeiroVencimentoIndividual.value = data();
						}
						
						var qtdMes	= document.formulario.QtdMesesVencimento.value;			
						
						if(qtdMes > 0){
							var dataTemp = addMonth(qtdMes);
						}else{
							var dataTemp = data();
						}
						
						switch(FormaCobranca){
							case '1':
								document.formulario.DataPrimeiroVencimentoContrato.value					= DataPrimeiroVencimento;
								document.formulario.DataPrimeiroVencimentoIndividual.value					= "";
								document.getElementById('obsDataPrimeiroVencimentoContrato').style.display	= "block";
								document.getElementById("descricaoNotaFiscal").innerHTML					= '';
								document.getElementById("descricaoNotaFiscal").style.display				= "block";
								
								buscar_descricao_layout(IdContratoFaturamento);
								break;
							case '2': //Individual	
								document.getElementById('obsDataPrimeiroVencimentoContrato').style.display		= 'none';
								if(DataPrimeiroVencimento == ""){
									DataPrimeiroVencimento	=	dataTemp;
									
									document.formulario.DataPrimeiroVencimentoIndividual.value	= DataPrimeiroVencimento;
								}else{
									document.formulario.DataPrimeiroVencimentoIndividual.value	= dateFormat(DataPrimeiroVencimento);
								}
								
								document.getElementById("descricaoNotaFiscal").innerHTML		= '';
								document.getElementById("descricaoNotaFiscal").style.display	= "none";
								document.formulario.DataPrimeiroVencimentoContrato.value		= "";
								
								if(Carne != ""){
									document.formulario.IdFormatoCarne.value = Carne;
								}
								break;
						}
						
						document.getElementById('obsDataPrimeiroVencimentoContrato').style.display	= "none";
						document.getElementById('titDataPrimeiroVencimentoContrato').style.display	= "none";
						document.getElementById("titLocalCobranca").style.display					= "none";
						document.getElementById("titContrato").style.display						= "none";
						document.getElementById('titValorDespesas').style.display					= "none";
						document.getElementById('titQtdParcela').style.display						= "none";
						document.getElementById('cpQtdParcela').style.display						= "none";
						document.getElementById('cpDataPrimeiroVencimentoContrato').style.display	= "none";
						document.getElementById("cpValorDespesa").style.display						= "none";
						document.getElementById('cpDataPrimeiroVencimento').style.display			= "none";
						document.getElementById('cpLocalCobranca').style.display					= "none";
						document.getElementById('cpContrato').style.display							= "none";
						document.getElementById('cpLocalCobranca').style.display					= "none";
						document.getElementById('cpContrato').style.display							= "none";
						document.getElementById('titContratoInd').style.display						= "none";
						document.getElementById('cpContratoInd').style.display						= "none";
						
						if(ValorTotal > 0){	
							if(IdContratoFaturamento == "" && IdContrato!=""){
								IdContratoFaturamento	=	IdContrato;
							}
						
							busca_forma_cobranca(FormaCobranca,IdContratoFaturamento);
						}else{
							verificaValor(ValorTotal);
						}
						
						if(document.formulario.FormaCobranca.value == 1){
							listar_contrato(IdPessoa,IdContratoFaturamento);
							
							if(document.formulario.QtdParcela.value == ""){
								atualizaPrimeiraReferencia(IdContratoFaturamento, DataPrimeiroVencimento);
							}
						} else {
							listar_contrato_individual(IdPessoa,IdContratoFaturamento);
						}
						
						listar_ordem_servico_parcela(IdOrdemServico);
						busca_local_cobranca_fatura(IdLocalCobrancaF, IdStatus);
						
						if(IdStatus >= 0 && IdStatus <= 99){
							document.formulario.FormaCobranca.disabled						=   true;
							document.formulario.QtdParcela.readOnly							=   true;
							document.formulario.IdLocalCobranca.disabled					=   true;
							document.formulario.ValorDespesaLocalCobranca.readOnly			=   true;
							document.formulario.DataPrimeiroVencimentoIndividual.readOnly	=   true;
							document.formulario.DataPrimeiroVencimentoContrato.readOnly		=   true;
							document.formulario.IdContaDebitoCartao.disabled 				= 	true;
							document.formulario.bt_simular.disabled							=   true;
							
							if(IdTerceiro == ''){
								document.formulario.PercentualRepasseTerceiro.value				=   "0,00";
								document.formulario.PercentualRepasseTerceiroOutros.value		=	"0,00";
							}
						} else{
							document.formulario.FormaCobranca.disabled						=   false;
							document.formulario.QtdParcela.readOnly							=   false;
							document.formulario.IdLocalCobranca.disabled					=   false;
							document.formulario.ValorDespesaLocalCobranca.readOnly			=   false;
							document.formulario.bt_simular.disabled							=   false;
							document.formulario.DataPrimeiroVencimentoIndividual.readOnly	=   false;
							document.formulario.IdContaDebitoCartao.disabled 				= 	false;
							document.formulario.DataPrimeiroVencimentoContrato.readOnly		=   false;
						}
						
						if(ValorTotal <= 0){
							document.formulario.bt_simular.disabled							=   true;
						}
						
						if(IdStatus >= 100 && IdStatus <= 199){
							if(QtdContaReceberAguardandoPagamento >= 1){										
								document.formulario.bt_enviar.disabled = false;
							}else{										
								document.formulario.bt_enviar.disabled = true;								
							}
						}
						
						verificar_local_cobranca(IdLocalCobranca,IdCartao,IdContaDebito);
						break;
					case 'LancamentoFinanceiro':
						nameNode = xmlhttp.responseXML.getElementsByTagName("FormaCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var FormaCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorTotal = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiroVencimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataPrimeiroVencimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var QtdParcela = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalCobranca = nameTextNode.nodeValue;
						
						document.formulario.IdOrdemServico.value 			= IdOrdemServico;
						document.formulario.IdServicoOS.value 				= IdServico;
						document.formulario.DescricaoServicoOS.value 		= DescricaoServico;
						document.formulario.FormaCobrancaOS.value 			= FormaCobranca;
						
						if(NumParcelaEventual != ''){
							QtdParcela = NumParcelaEventual+'/'+QtdParcela;
						}
						
						switch(FormaCobranca){
							case '1': //Contrato
								document.getElementById('cpContaEventualContrato').style.display	=	'block';
								document.getElementById('cpContaEventualIndividual').style.display	=	'none';
								
								document.formulario.ValorTotalContrato.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
								document.formulario.DataPrimeiroVencimentoContrato.value	= DataPrimeiroVencimento;
								document.formulario.IdContratoAgrupador.value 				= IdContrato;
								document.formulario.QtdParcelaContrato.value				= QtdParcela;
								
								listar_contrato_agrupador(IdPessoa,IdContrato);
								break;
							case '2': //Individual
								document.getElementById('cpContaEventualContrato').style.display	=	'none';
								document.getElementById('cpContaEventualIndividual').style.display	=	'block';
								
								document.formulario.DataPrimeiroVencimentoIndividual.value	= dateFormat(DataPrimeiroVencimento);
								document.formulario.ValorTotalIndividual.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
								document.formulario.ValorDespesaLocalCobranca.value			= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
								document.formulario.IdLocalCobranca.value 					= IdLocalCobranca;
								document.formulario.QtdParcelaIndividual.value				= QtdParcela;
								break;
						}
						
						break;
					case 'Protocolo':
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						var ValorTotal = Number(ValorOutros) + Number(Valor);
						
						document.formulario.IdOrdemServico.value 			= IdOrdemServico;
						document.formulario.NomePessoaOrdemServico.value	= Nome;
						document.formulario.IdTipoOrdemServico.value 		= IdTipoOrdemServico;
						document.formulario.IdStatusOrdemServico.value 		= IdStatus;
						document.formulario.NomeServicoOrdemServico.value 	= DescricaoServico;
						document.formulario.DescricaoOrdemServico.value 	= DescricaoOS;
						document.formulario.ValorOdemServico.value 			= formata_float(Arredonda(ValorTotal,2),2).replace(/\./i,",");
						
						document.formulario.IdContaEventual.readOnly = true;
						document.formulario.IdContaEventual.onfocus = undefined;
						
						busca_conta_eventual(0,false,document.formulario.Local.value);
						break;
				}
			}
			if(window.janela != undefined){
				window.janela.close();
			}
			if(document.getElementById("quadroBuscaOrdemServico") != null){
				if(document.getElementById("quadroBuscaOrdemServico").style.display	==	"block"){
					document.getElementById("quadroBuscaOrdemServico").style.display = "none";
				}
			}
			verificaAcao();
		});
	}