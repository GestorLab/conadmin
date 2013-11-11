	function janela_busca_conta_receber(IdStatus){
		janelas('../administrativo/busca_conta_receber.php',530,350,250,100,'');
	}
	function busca_cancelar_conta_receber(IdContaReceber,Erro,Local,ListarCampo,IdContaReceberRecebimento){
		if(IdContaReceber == ''){
			IdContaReceber = 0;
		}
		if(IdContaReceberRecebimento == undefined){
			IdContaReceberRecebimento = '';
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
	    
	   	url = "xml/conta_receber.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento;
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
						switch (Local){
							default:	
								document.formulario.IdContaReceber.value 			= '';
								document.formulario.NumeroDocumento.value 			= '';
								document.formulario.DataLancamento.value 			= '';
								document.formulario.DataVencimento.value 			= '';
								document.formulario.DataRecebimento.value 			= '';
								document.formulario.ValorContaReceber.value	 		= '';
								document.formulario.ValorDespesas.value				= '';
								document.formulario.ValorDesconto.value 			= '';
								document.formulario.ValorFinal.value 				= '';
								document.formulario.ValorReceber.value 				= '';
								document.formulario.ValorRecebimento.value 			= '';
								document.formulario.ValorMoraMulta.value 			= '';
								document.formulario.IdStatus.value					= '';
								document.formulario.Status.value					= '';
								document.formulario.IdLocalCobranca.value 			= '';
								document.formulario.IdArquivoRetorno.value			= '';
								document.formulario.IdContaReceberRecebimento.value = '';
								document.formulario.IdLocalRecebimento.value 		= '';
								document.formulario.ValorDescontoRecebimento.value 	= '';
								document.formulario.IdRecibo.value			 		= '';
								document.formulario.NumeroNF.value 					= '';
								document.formulario.ObsCancelamento.value			= '';
								document.formulario.Obs.value						= '';
								document.formulario.DataCriacao.value 				= '';
								document.formulario.LoginCriacao.value 				= '';
								document.formulario.DataAlteracao.value 			= '';
								document.formulario.LoginAlteracao.value			= '';
								document.formulario.Acao.value 						= 'alterar';
								document.formulario.IdPessoa.value 					= '';
								
								verificaAcao();
								statusInicial();
								
								while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
									document.getElementById('tabelaLancFinanceiro').deleteRow(1);
								}
								while(document.getElementById('tabelaRecebimentos').rows.length > 2){
									document.getElementById('tabelaRecebimentos').deleteRow(1);
								}
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								
								document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';	
								document.getElementById('cp_parametros').style.display				=	'none';	
								
								if(Local == 'ContaReceber'){					
									document.getElementById('cpVoltarDataBase').innerHTML	=	'';	
								}
								
								document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
								document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
								
								document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
								document.getElementById('totalValorRecebido').innerHTML				=	"0,00";	
								document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";		
								
								busca_pessoa('','false',document.formulario.Local.value);
								
								addParmUrl("marPessoa","IdContaReceber","");
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marPessoaNovo","IdContaReceber","");
								addParmUrl("marContasReceber","IdPessoa",IdPessoa);
								addParmUrl("marContasReceber","IdContaReceber","");
								addParmUrl("marLancamentoFinanceiro","IdContaReceber","");
								
								document.formulario.IdContaReceber.focus();
								break;	
						}			
						// Fim de Carregando
						carregando(false);
					}else{
						mensagens(0);
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLoja = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroDocumento = nameTextNode.nodeValue;						
				
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataLancamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataVencimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorContaReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDescontoRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Tipo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMoraMulta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorMoraMulta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorOutrasDespesas = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesas")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDespesas = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorDesconto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetorno")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdArquivoRetorno = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdRecibo = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusRecebimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatusRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdContaReceberRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetorno")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdArquivoRetorno = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalRecebimento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdLocalRecebimento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Obs = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroNF = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorFinal = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorReceber")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorReceber = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdProcessoFinanceiro = nameTextNode.nodeValue;
						
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Boleto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Boleto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ObsCancelamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ObsCancelamento = nameTextNode.nodeValue;
						
						switch (Local){
							default:
								while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
									document.getElementById('tabelaLancFinanceiro').deleteRow(1);
								}
								while(document.getElementById('tabelaParametro').rows.length > 0){
									document.getElementById('tabelaParametro').deleteRow(0);
								}
								while(document.getElementById('tabelaRecebimentos').rows.length > 2){
									document.getElementById('tabelaRecebimentos').deleteRow(1);
								}
								
								document.formulario.IdPessoa.value 					= IdPessoa;
								
								document.formulario.IdContaReceber.value 			= IdContaReceber;
								document.formulario.NumeroDocumento.value 			= NumeroDocumento;
								document.formulario.ValorContaReceber.value			= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
								document.formulario.DataLancamento.value 			= dateFormat(DataLancamento);
								document.formulario.DataVencimento.value 			= dateFormat(DataVencimento);
								document.formulario.DataRecebimento.value 			= dateFormat(DataRecebimento);
								
								if((ValorDesconto != '0.00' || ValorDesconto != '0') && (ValorDescontoRecebimento == '0.00' || ValorDescontoRecebimento == '0')){
									ValorDescontoRecebimento	=	ValorDesconto;
								}
								if((ValorDespesas != '0.00' || ValorDespesas != '0') && (ValorOutrasDespesas == '0.00' || ValorOutrasDespesas == '0')){
									ValorOutrasDespesas	=	ValorDespesas;
								}
								
								document.formulario.ValorDespesas.value				= formata_float(Arredonda(ValorDespesas,2),2).replace(".",",");
								document.formulario.ValorDesconto.value 			= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
								document.formulario.ValorMoraMulta.value 			= formata_float(Arredonda(ValorMoraMulta,2),2).replace(".",",");
								document.formulario.ValorDescontoRecebimento.value	= formata_float(Arredonda(ValorDescontoRecebimento,2),2).replace(".",",");
								document.formulario.ValorOutrasDespesas.value		= formata_float(Arredonda(ValorOutrasDespesas,2),2).replace(".",",");
								document.formulario.ValorRecebimento.value			= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
								document.formulario.ValorFinal.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
								document.formulario.ValorReceber.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
								document.formulario.IdStatus.value					= IdStatus;
								document.formulario.Status.value					= IdStatus;
								document.formulario.IdLocalCobranca.value 			= IdLocalCobranca;
								document.formulario.IdArquivoRetorno.value			= IdArquivoRetorno;
								document.formulario.IdContaReceberRecebimento.value = IdContaReceberRecebimento;
								document.formulario.IdLocalRecebimento.value 		= IdLocalRecebimento;
								document.formulario.IdRecibo.value 					= IdRecibo;
								document.formulario.Obs.value 						= Obs;
								document.formulario.NumeroNF.value 					= NumeroNF;
								document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 				= LoginCriacao;
								document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value			= LoginAlteracao;
								document.formulario.ValorMoraMulta.readOnly 			= true;
								document.formulario.ValorDescontoRecebimento.readOnly	= true;
								document.formulario.ValorOutrasDespesas.readOnly		= true;
								document.formulario.ObsCancelamento.value			= "";								
								document.formulario.Acao.value 						= 'alterar';
								
								for(var i=0; i<document.formulario.IdLocalCobranca.length; i++){
									if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
										document.formulario.IdLocalCobranca[i].selected = true;
										i = document.formulario.IdLocalCobranca.length;
									}							
								}
								
								for(var i=0; i<document.formulario.IdLocalRecebimento.length; i++){
									if(document.formulario.IdLocalRecebimento[i].value == IdLocalRecebimento){
										document.formulario.IdLocalRecebimento[i].selected = true;
										i = document.formulario.IdLocalRecebimento.length;
									}							
								}
								
								
								document.getElementById('cp_parametros').style.display				=	'none';	
								
								addParmUrl("marPessoa","IdContaReceber",IdContaReceber);
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marPessoaNovo","IdContaReceber",IdContaReceber);
								addParmUrl("marContasReceber","IdPessoa",IdPessoa);
								//addParmUrl("marContasReceber","IdContaReceber",IdContaReceber);
								addParmUrl("marLancamentoFinanceiro","IdContaReceber",IdContaReceber);
								
																
								//Recebido 
								listarRecebimento(IdContaReceber,false,IdContaReceberRecebimento);
								busca_lancamento_financeiro(IdContaReceber,false);
								listarParametro(IdLocalRecebimento,false);
								busca_pessoa(IdPessoa,false);
								listarContaReceberParametroRecebimento(IdContaReceber,IdLocalRecebimento,false);
								statusInicial();
								
								if(Local == 'ContaReceber'){
									busca_lancamentos_data_base(IdContaReceber);
								}
								
								verificaAcao();
								break;
						}
							
					}
					if(window.janela != undefined){
						window.janela.close();
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}


