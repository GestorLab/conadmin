	function janela_busca_conta_receber(IdStatus){
		janelas('../administrativo/busca_conta_receber.php',530,350,250,100,'');
	}
	function busca_cancelar_conta_receber(IdContaReceber,Erro,Local,ListarCampo,IdContaReceberRecebimento){
		if(IdContaReceber == ''){
			IdContaReceber = 0;
		}
		
		if(IdContaReceberRecebimento == '' || IdContaReceberRecebimento == undefined){
			IdContaReceberRecebimento = '';
		}
		
	   	var url = "xml/conta_receber.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento;
		
		call_ajax(url, function (xmlhttp) {
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
						document.formulario.ValorContaReceber.value	 		= '';
						document.formulario.ValorDespesas.value				= '';
						document.formulario.ValorDesconto.value 			= '';
						document.formulario.ValorFinal.value 				= '';
						document.formulario.IdStatus.value					= '';
						document.formulario.IdLocalCobranca.value 			= '';
						document.formulario.NumeroNF.value 					= '';
						document.formulario.ObsCancelamento.value			= '';
						document.formulario.Obs.value						= '';
						document.formulario.DataCriacao.value 				= '';
						document.formulario.LoginCriacao.value 				= '';
						document.formulario.DataAlteracao.value 			= '';
						document.formulario.LoginAlteracao.value			= '';
						document.formulario.Acao.value 						= 'alterar';
						document.formulario.IdPessoa.value 					= '';
						document.formulario.ValorMulta.value 				= '';
						document.formulario.ValorJuros.value 				= '';
						document.formulario.ValorTaxa.value 				= '';
						document.formulario.DataNF.value					= '';
						document.formulario.CancelarNotaFiscal.value		= ''; 
						document.formulario.ValorOutrasDesp.value			= '';
						document.formulario.ValorPercentual.value 			= '';	
						document.formulario.IdCancelarNotaFiscal.value 		= '';	
						
						verificaAcao();
						status_inicial();
						
						while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
							document.getElementById('tabelaLancFinanceiro').deleteRow(1);
						}
						
						document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';	
						
						if(Local == 'ContaReceber'){					
							document.getElementById('cpVoltarDataBase').innerHTML	=	'';	
						}
						
						document.getElementById("cp_titCancelarNotaFiscal").style.display	= "none";
						document.getElementById('cp_Status').style.display					= 'none';	
						document.getElementById('cp_Status').innerHTML						= "";
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
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[0]; 
				var nameTextNode = nameNode.childNodes[0];
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Tipo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesas")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorDespesas = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesasVencimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorOutrasDespesasVencimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorDesconto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatusRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdLocalCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Obs = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataNF")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataNF = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NumeroNF = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorFinal = nameTextNode.nodeValue;
				
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMulta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMulta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorJuros")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorJuros = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Status = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CancelarNotaFiscal")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CancelarNotaFiscal = nameTextNode.nodeValue;
				
				document.getElementById('cp_Status').innerHTML			= Status;
				document.getElementById('cp_Status').style.display		= 'block';
				document.getElementById('cp_Status').style.color		= Cor;
				
				while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
					document.getElementById('tabelaLancFinanceiro').deleteRow(1);
				}
				
				document.formulario.IdPessoa.value 					= IdPessoa;
				
				document.formulario.IdContaReceber.value 			= IdContaReceber;
				document.formulario.NumeroDocumento.value 			= NumeroDocumento;
				document.formulario.ValorContaReceber.value			= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
				document.formulario.DataLancamento.value 			= dateFormat(DataLancamento);
				document.formulario.DataVencimento.value 			= dateFormat(DataVencimento);
				
				document.formulario.ValorDespesas.value				= formata_float(Arredonda(ValorDespesas,2),2).replace(".",",");
				document.formulario.ValorDesconto.value 			= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
				document.formulario.ValorFinal.value				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
				document.formulario.IdStatus.value					= IdStatus;
				document.formulario.IdLocalCobranca.value 			= IdLocalCobranca;
				document.formulario.Obs.value 						= Obs;
				document.formulario.NumeroNF.value 					= NumeroNF;
				document.formulario.ValorMulta.value 				= formata_float(Arredonda(ValorMulta,2),2).replace(".",",");
				document.formulario.ValorJuros.value 				= formata_float(Arredonda(ValorJuros,2),2).replace(".",",");
				document.formulario.ValorTaxa.value					= formata_float(Arredonda(ValorTaxaReImpressaoBoleto,2),2).replace(".",","); 
				document.formulario.ValorOutrasDesp.value			= formata_float(Arredonda(ValorOutrasDespesasVencimento,2),2).replace(".",","); 
				
				document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
				document.formulario.LoginCriacao.value 				= LoginCriacao;
				document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
				document.formulario.LoginAlteracao.value			= LoginAlteracao;
				document.formulario.DataNF.value					= dateFormat(DataNF);
				document.formulario.CancelarNotaFiscal.value		= CancelarNotaFiscal; 
				document.formulario.ObsCancelamento.value			= "";								
				document.formulario.IdCancelarNotaFiscal.value		= "";								
				document.formulario.Acao.value 						= 'alterar';
				
				if(ValorDesconto > 0){
					var PercDesconto	=	(parseFloat(ValorDesconto)*100)/parseFloat(ValorFinal);
				}else{
					var PercDesconto = 0;
				}	
				
				document.formulario.ValorPercentual.value = formata_float(Arredonda(PercDesconto,2),2).replace(".",","); 
				
				for(var i=0; i<document.formulario.IdLocalCobranca.length; i++){
					if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
						document.formulario.IdLocalCobranca[i].selected = true;
						i = document.formulario.IdLocalCobranca.length;
					}							
				}
				
				if(CancelarNotaFiscal == '1'){
					document.getElementById("cp_titCancelarNotaFiscal").style.display = "block";
				} else{
					document.getElementById("cp_titCancelarNotaFiscal").style.display = "none";
				}
				
				addParmUrl("marPessoa","IdContaReceber",IdContaReceber);
				addParmUrl("marPessoa","IdPessoa",IdPessoa);
				addParmUrl("marPessoaNovo","IdContaReceber",IdContaReceber);
				addParmUrl("marContasReceber","IdPessoa",IdPessoa);
				addParmUrl("marReenvioMensagem","IdContaReceber",IdContaReceber);
				addParmUrl("marLancamentoFinanceiro","IdContaReceber",IdContaReceber);
				
				//Recebido 
				listarRecebimento(IdContaReceber,false);
				busca_lancamento_financeiro(IdContaReceber,false);
				//listarParametro(IdLocalRecebimento,false);
				busca_pessoa(IdPessoa,false);
				//listarContaReceberParametroRecebimento(IdContaReceber,IdLocalRecebimento,false);
				status_inicial();
				
				if(Local == 'ContaReceber'){
					busca_lancamentos_data_base(IdContaReceber);
				}
				
				verificaAcao();
			}
		});
	}