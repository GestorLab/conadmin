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
					//alert(xmlhttp.responseText);
					if(xmlhttp.responseText == 'false'){
						document.formulario.IdLancamentoFinanceiro.value 			= '';
																	
						// Pessoa //
						document.formulario.IdPessoa.value 							= '';
						document.formulario.IdPessoaF.value 						= '';
						document.formulario.Nome.value 								= '';
						document.formulario.NomeF.value 							= '';
						document.formulario.RazaoSocial.value 						= '';
						document.formulario.CPF.value 								= '';
						document.formulario.CNPJ.value 								= '';
						
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
						document.formulario.ValorDescontoAConceber.value 			= '';
						document.formulario.PercentualDesconto.value 				= '';
						document.formulario.ValorFinal.value 						= '';					
						document.formulario.DataCancelamento.value 					= '';
						document.formulario.LoginCancelamento.value					= '';
						document.formulario.ObsLancamentoFinanceiro.value 			= '';
						document.formulario.ValorDescontoAConceber.readOnly			= true;
						document.formulario.PercentualDesconto.readOnly				= true;
						document.formulario.Acao.value 								= 'cancelar';
						
						document.getElementById('cpObservacoesLog').style.display		=	'none';
						document.getElementById('cpContrato').style.display				=	'none';
						document.getElementById('cpContaEventual').style.display		=	'none';
						document.getElementById('cpProcessoFinaceiro').style.display	=	'none';
						document.getElementById('cp_juridica').style.display			= 	'block';
						document.getElementById('cp_fisica').style.display				= 	'none';	
						document.getElementById('cp_Status').style.display				=	'none';							
						
						addParmUrl("marLancamentoFinanceiro","IdLancamentoFinanceiro","");
						addParmUrl("marLancamentoFinanceiro","IdPessoa","");
						addParmUrl("marContasReceber","IdContaReceber","");
						addParmUrl("marContasReceber","IdPessoa","");
						addParmUrl("marContaEventual","IdContaEventual","");
						addParmUrl("marContaEventual","IdPessoa","");
						addParmUrl("marContaEventualNovo","IdPessoa","");
						addParmUrl("marContrato","IdContrato","");
						addParmUrl("marContrato","IdPessoa","");
						addParmUrl("marContratoNovo","IdPessoa","");
						addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro","");
						addParmUrl("marProcessoFinanceiro","IdPessoa","");
						addParmUrl("marPessoa","IdPessoa","");
						addParmUrl("marReenvioMensagem","IdPessoa","");
						addParmUrl("marOrdemServico","IdOrdemServico","");
						addParmUrl("marOrdemServico","IdPessoa","");
						addParmUrl("marOrdemServicoNovo","IdPessoa","");
						
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
						
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("ObsLancamentoFinanceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ObsLancamentoFinanceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusContaReceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatusContaReceber = nameTextNode.nodeValue;					
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCancelamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCancelamento = nameTextNode.nodeValue;
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCancelamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCancelamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoAConceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDescontoAConceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumParcelaEventual")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NumParcelaEventual = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						addParmUrl("marLancamentoFinanceiro","IdLancamentoFinanceiro",IdLancamentoFinanceiro);
						addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
						addParmUrl("marContasReceber","IdContaReceber",IdContaReceber);
						addParmUrl("marContasReceber","IdPessoa",IdPessoa);
						addParmUrl("marContaEventual","IdContaEventual",IdContaEventual);
						addParmUrl("marContaEventual","IdPessoa",IdPessoa);
						addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
						addParmUrl("marContrato","IdContrato",IdContrato);
						addParmUrl("marContrato","IdPessoa",IdPessoa);
						addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
						addParmUrl("marPessoa","IdPessoa",IdPessoa);
						addParmUrl("marReenvioMensagem","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServico","IdOrdemServico",IdOrdemServico);
						addParmUrl("marOrdemServico","IdPessoa",IdPessoa);
						addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
						
						if(IdProcessoFinanceiro != '') {
							addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",IdProcessoFinanceiro);
						} else {
							addParmUrl("marProcessoFinanceiro","IdPessoa",IdPessoa);
						}
						
						if(ValorDescontoAConceber>0){
							var total	=	parseFloat(Valor) - parseFloat(ValorDescontoAConceber);
							var perc	=	(parseFloat(ValorDescontoAConceber)*100)/parseFloat(Valor);
						}else{
							var total	=	parseFloat(Valor);
							var perc	=	0
						}

						document.formulario.IdLancamentoFinanceiro.value		= IdLancamentoFinanceiro;
						document.formulario.Valor.value 						= formata_float(Arredonda(Valor,2),2).replace('.',',');
						document.formulario.ValorRepasseTerceiro.value 			= formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace('.',',');						
						document.formulario.ObsLancamentoFinanceiro.value 		= ObsLancamentoFinanceiro;
						document.formulario.DataCancelamento.value 				= dateFormat(DataCancelamento);
						document.formulario.LoginCancelamento.value				= LoginCancelamento;
						
						document.formulario.DataReferenciaInicial.value 		= dateFormat(DataReferenciaInicial);
						document.formulario.DataReferenciaFinal.value 			= dateFormat(DataReferenciaFinal);
						document.formulario.ValorDescontoAConceber.value 		= formata_float(Arredonda(ValorDescontoAConceber,2),2).replace('.',',');
						document.formulario.PercentualDesconto.value 			= formata_float(Arredonda(perc,2),2).replace('.',',');
						document.formulario.ValorFinal.value 					= formata_float(Arredonda(total,2),2).replace('.',',');
						document.formulario.IdStatusContaReceber.value			= IdStatusContaReceber;
						document.formulario.IdContaReceber.value				= IdContaReceber;
						document.formulario.ContasReceber.value					= IdContaReceber;
						
						document.getElementById('cpContrato').style.display	=	'none';
						
						if(IdContrato != ''){
							document.getElementById('cpContrato').style.display			=	'block';
							document.getElementById('cpContaEventual').style.display	=	'none';
							
							busca_contrato(IdContrato,false,document.formulario.Local.value);
						}
						
						switch(Tipo){
							case 'EV':
								document.getElementById('cpContaEventual').style.display			=	'block';
								document.getElementById('titContaEventual').innerHTML				=	'Dados do Conta Eventual';
								document.getElementById('ContaEventualIndividual').style.display	=	'block';
								document.getElementById('tableOrdemServico').style.display			=	'none';
								
								busca_conta_eventual(IdContaEventual, false, document.formulario.Local.value, NumParcelaEventual);
								break;
							case 'OS':
								document.getElementById('cpContaEventual').style.display			=	'block';
								document.getElementById('titContaEventual').innerHTML				=	'Dados da Ordem de Serviço';
								document.getElementById('ContaEventualIndividual').style.display	=	'none';
								document.getElementById('tableOrdemServico').style.display			=	'block';
								
								busca_ordem_servico(IdOrdemServico, false, document.formulario.Local.value, NumParcelaEventual);
								break;
						}
						
						if(IdProcessoFinanceiro !=''){
							document.getElementById('cpProcessoFinaceiro').style.display	=	'block';
							
							busca_processo_financeiro(IdProcessoFinanceiro,false,document.formulario.Local.value);
						}else{
							document.getElementById('cpProcessoFinaceiro').style.display	=	'none';
						}
						
						busca_status(IdStatus);
						busca_pessoa(IdPessoa,false,document.formulario.Local.value);
						
						document.formulario.Acao.value 							= 'cancelar';
					}
					if(window.janela != undefined){
						window.janela.close();
					}
					verificaAcao();
					//alert("IdConta "+IdContaReceber);
					
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}	
	
	
