	function busca_lancamento_financeiro(IdLancamentoFinanceiro, Erro, Local){
		if(IdLancamentoFinanceiro == ''){
			IdLancamentoFinanceiro = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		
		var nameNode, nameTextNode, url;
		
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
			}
		}else if (window.ActiveXObject){ // IE
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            } catch (e) {}
	        }
	    }
		url = "xml/lancamento_financeiro.php?IdLancamentoFinanceiro="+IdLancamentoFinanceiro;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){

					if(Erro != false){
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						document.formulario.IdLancamentoFinanceiro.value 			= '';
																	
						// Pessoa //
						document.formulario.IdPessoa.value 							= '';
						document.formulario.Nome.value 								= '';
						document.formulario.RazaoSocial.value 						= '';
						document.formulario.Cidade.value 							= '';
						document.formulario.CPF_CNPJ.value 							= '';
						document.formulario.Email.value 							= '';
						document.formulario.Telefone1.value							= '';
						document.formulario.SiglaEstado.value						= '';
						
						// Contrato //
						document.formulario.IdContrato.value						=	"";
						document.formulario.IdServico.value							=	"";
						document.formulario.DescricaoServico.value					=	"";
						document.formulario.DescPeriodicidade.value					=	"";
						document.formulario.QtdParcela.value						=	"";
						document.formulario.AssinaturaContrato.value				=	"";
						document.formulario.DataInicio.value						=	"";
						document.formulario.DataTermino.value						=	"";
						document.formulario.DataBaseCalculo.value					=	"";
						document.formulario.TipoContrato.value						=	"";
						document.formulario.DataUltimaCobranca.value				=	"";
						document.formulario.DataReferenciaInicial.value				=	"";
						document.formulario.DataReferenciaFinal.value				=	"";
						
						// ContratoEventual //
						document.formulario.IdContaEventual.value 					= '';
						document.formulario.DescricaoContaEventual.value 			= '';
						document.formulario.FormaCobranca[0].selected 				= true;
						document.formulario.IdContratoAgrupador[0].selected 		= true;
						document.formulario.ValorTotalContrato.value				= '';
						document.formulario.QtdParcelaContrato.value				= '';
						document.formulario.DataPrimeiroVencimentoContrato.value	= '';
						document.formulario.ValorTotalIndividual.value				= '';
						document.formulario.ValorDespesaLocalCobranca.value			= '';
						document.formulario.QtdParcelaIndividual.value				= '';
						document.formulario.DataPrimeiroVencimentoIndividual.value	= '';
						
						// Ordem de Serviço //
						
						document.formulario.IdOrdemServico.value 					= '';
						document.formulario.IdServicoOS.value 						= '';
						document.formulario.DescricaoServicoOS.value 				= '';
						document.formulario.FormaCobrancaOS[0].selected 			= true;
						
						// Processo Financeiro //
						
						document.formulario.Valor.value 							= '';
						document.formulario.ValorRepasseTerceiro.value 				= '';
						document.formulario.IdStatus[0].selected 					= true;
						document.formulario.DataCancelamento.value 					= '';
						document.formulario.LoginCancelamento.value					= '';
						document.formulario.ObsLancamentoFinanceiro.value 			= '';
						document.formulario.Acao.value 								= 'cancelar';
						
						document.getElementById('cpObservacoesLog').style.display		=	'none';
						document.getElementById('cpContrato').style.display				=	'none';
						document.getElementById('cpContaEventual').style.display		=	'none';
						document.getElementById('cpProcessoFinaceiro').style.display	=	'none';
						document.getElementById('cp_juridica').style.display			= 	'block';
						document.getElementById('cp_fisica').style.display				= 	'none';								
						document.getElementById('cp_CPF_CNPJ_Titulo').innerHTML 		= 	"CNPJ";
						
						addParmUrl("marLancamentoFinanceiro","IdLancamentoFinanceiro","");
						addParmUrl("marContasReceber","IdContaReceber","");
						addParmUrl("marContaEventual","IdContaEventual","");
						addParmUrl("marContrato","IdContrato","");
						addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro","");
						addParmUrl("marPessoa","IdPessoa","");
						
						document.formulario.IdLancamentoFinanceiro.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdLancamentoFinanceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Tipo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaEventual")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContaEventual = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdOrdemServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataReferenciaInicial")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataReferenciaInicial = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataReferenciaFinal")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataReferenciaFinal = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdProcessoFinanceiro = nameTextNode.nodeValue;
												
						nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Valor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorRepasseTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("ObsLancamentoFinanceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ObsLancamentoFinanceiro = nameTextNode.nodeValue;					
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCancelamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCancelamento = nameTextNode.nodeValue;
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCancelamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCancelamento = nameTextNode.nodeValue;
						
						addParmUrl("marLancamentoFinanceiro","IdLancamentoFinanceiro",IdLancamentoFinanceiro);
						addParmUrl("marContasReceber","IdContaReceber",IdContaReceber);
						addParmUrl("marContaEventual","IdContaEventual",IdContaEventual);
						addParmUrl("marContrato","IdContrato",IdContrato);
						addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",IdProcessoFinanceiro);
						addParmUrl("marPessoa","IdPessoa",IdPessoa);
						
						document.formulario.IdLancamentoFinanceiro.value		= IdLancamentoFinanceiro;
						document.formulario.Valor.value 						= formata_float(Arredonda(Valor,2),2).replace('.',',');
						document.formulario.ValorRepasseTerceiro.value 			= formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');
						document.formulario.IdStatus.value 						= IdStatus;
						document.formulario.ObsLancamentoFinanceiro.value 		= ObsLancamentoFinanceiro;
						document.formulario.DataCancelamento.value 				= dateFormat(DataCancelamento);
						document.formulario.LoginCancelamento.value				= LoginCancelamento;
						document.formulario.DataReferenciaInicial.value 		= dateFormat(DataReferenciaInicial);
						document.formulario.DataReferenciaFinal.value 			= dateFormat(DataReferenciaFinal);
						
						switch(IdStatus){ 
							case '0': //Cancelado//
								document.getElementById('cpObservacoesLog').style.display	=	'block';
								document.getElementById('cpObs').style.color				= 	'#000';
								break;
								break
							default:
								document.getElementById('cpObservacoesLog').style.display	=	'none';
								document.getElementById('cpObs').style.color				= 	'#000';
						}
						switch(Tipo){
							case 'CO':
								document.getElementById('cpContrato').style.display			=	'block';
								document.getElementById('cpContaEventual').style.display	=	'none';
								
								busca_contrato(IdContrato,false,document.formulario.Local.value);
								break;
							case 'EV':
								document.getElementById('cpContrato').style.display					=	'none';
								document.getElementById('cpContaEventual').style.display			=	'block';
								document.getElementById('titContaEventual').innerHTML				=	'Dados do Conta Eventual';
								document.getElementById('ContaEventualIndividual').style.display	=	'block';
								document.getElementById('tableOrdemServico').style.display			=	'none';
								
								busca_conta_eventual(IdContaEventual,false,document.formulario.Local.value);
								break;
							case 'OS':
								document.getElementById('cpContrato').style.display					=	'none';
								document.getElementById('cpContaEventual').style.display			=	'block';
								document.getElementById('titContaEventual').innerHTML				=	'Dados da Ordem de Serviço';
								document.getElementById('ContaEventualIndividual').style.display	=	'none';
								document.getElementById('tableOrdemServico').style.display			=	'block';
								
								busca_ordem_servico(IdOrdemServico,false,document.formulario.Local.value);
								break;
						}
						
						if(IdProcessoFinanceiro !=''){
							document.getElementById('cpProcessoFinaceiro').style.display	=	'block';
							
							busca_processo_financeiro(IdProcessoFinanceiro,false,document.formulario.Local.value);
						}else{
							document.getElementById('cpProcessoFinaceiro').style.display	=	'none';
						}
						
						busca_pessoa(IdPessoa,false,document.formulario.Local.value);
						
						document.formulario.Acao.value 							= 'cancelar';
					}
					if(window.janela != undefined){
						window.janela.close();
					}
					verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}	
	
