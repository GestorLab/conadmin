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
			var nameNode, nameTextNode;
			
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){		
				switch (Local){
					default:
						document.formulario.IdContaReceber.value 				= '';
						document.formulario.NumeroDocumento.value 				= '';
						document.formulario.DataLancamento.value 				= '';
						document.formulario.DataVencimento.value 				= '';
						document.formulario.ValorContaReceber.value	 			= '';
						document.formulario.ValorDespesas.value					= '';
						document.formulario.ValorDesconto.value 				= '';
						document.formulario.ValorFinal.value 					= '';
						document.formulario.IdStatus.value						= '';
						document.formulario.IdLocalCobranca.value 				= '';
						document.formulario.NumeroNF.value 						= '';
						document.formulario.ObsCancelamento.value				= '';
						document.formulario.Obs.value							= '';
						document.formulario.DataCriacao.value 					= '';
						document.formulario.LoginCriacao.value 					= '';
						document.formulario.DataAlteracao.value 				= '';
						document.formulario.LoginAlteracao.value				= '';
						document.formulario.Acao.value 							= 'alterar';
						document.formulario.IdPessoa.value 						= '';
						
						document.formulario.ValorMulta.value					= '';
						document.formulario.ValorTaxa.value						= '';
						document.formulario.ValorJuros.value					= '';
						document.formulario.ValorOutrasDesp.value				= '';
						document.formulario.ValorPercentual.value				= '';
						
						document.formulario.IdContaReceberRecebimento.value 	= '';
						document.formulario.IdLocalRecebimento.value 			= '';
						document.formulario.IdCaixa.value 						= '';
						document.formulario.IdCaixaMovimentacao.value 			= '';
						document.formulario.IdCaixaItem.value 					= '';
						document.formulario.DataRecebimento.value 				= '';
						document.formulario.IdArquivoRetorno.value 				= '';
						document.formulario.IdRecibo.value 						= '';
						document.formulario.ValorRecebimento.value 				= '';
						document.formulario.ValorDescontoRecebimento.value 		= '';
						document.formulario.ValorMoraMulta.value 				= '';
						document.formulario.ValorOutrasDespesas.value 			= '';
						document.formulario.ValorReceber.value 					= '';
						document.formulario.IdCancelarNotaFiscal.value 			= '';
						document.formulario.DataNF.value 						= '';
						document.formulario.CancelarNotaFiscalRecebimento.value = '';
						
						document.getElementById("sp_titLocalRecebimento").style.display = "table-cell";
						document.getElementById("titLocalRecebimento").style.display = "table-cell";
						document.getElementById("sp_cpIdLocalRecebimento").style.display = "table-cell";
						document.getElementById("cpIdLocalRecebimento").style.display = "table-cell";
						
						document.getElementById("sp_titCaixa").style.display = "none";
						document.getElementById("titCaixa").style.display = "none";
						document.getElementById("sp_titMovimentacao").style.display = "none";
						document.getElementById("titMovimentacao").style.display = "none";
						document.getElementById("sp_titItem").style.display = "none";
						document.getElementById("titItem").style.display = "none";
						document.getElementById("sp_cpIdCaixa").style.display = "none";
						document.getElementById("cpIdCaixa").style.display = "none";
						document.getElementById("sp_cpIdCaixaMovimentacao").style.display = "none";
						document.getElementById("cpIdCaixaMovimentacao").style.display = "none";
						document.getElementById("sp_cpIdCaixaItem").style.display = "none";
						document.getElementById("cpIdCaixaItem").style.display = "none";
						
						verificaAcao();
						status_inicial();
						
						while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
							document.getElementById('tabelaLancFinanceiro').deleteRow(1);
						}
						while(document.getElementById('tabelaParametro').rows.length > 2){
							document.getElementById('tabelaParametro').deleteRow(1);
						}
						
						document.getElementById('cp_Status').style.display					= 'none';	
						document.getElementById('cp_Status').innerHTML						= "";
						document.getElementById('cp_lancamentos_financeiros').style.display	= 'none';	
						document.getElementById('cp_parametros').style.display				= 'none';	
						document.getElementById("cp_titCancelarNotaFiscal").style.display	= "none";
						
						if(Local == 'ContaReceber'){					
							document.getElementById('cpVoltarDataBase').innerHTML	=	'';	
						}
						
						document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
						document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
						
						document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
						document.getElementById('totalValorRecebido').innerHTML				=	"0,00";	
						document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";		
						
						busca_pessoa('','false','ContaReceber');
						
						addParmUrl("marPessoa","IdContaReceber","");
						addParmUrl("marPessoa","IdPessoa",IdPessoa);
						addParmUrl("marPessoaNovo","IdContaReceber","");
						addParmUrl("marContasReceber","IdPessoa",IdPessoa);
						addParmUrl("marContasReceber","IdContaReceber","");
						addParmUrl("marLancamentoFinanceiro","IdContaReceber","");
						addParmUrl("marReenvioMensagem","IdContaReceber","");
						
						document.formulario.IdContaReceber.focus();
						break;	
				}
			}else{
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Tipo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesas")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorDespesas = nameTextNode.nodeValue;
				
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NumeroNF = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataNF")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataNF = nameTextNode.nodeValue;
				
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaReceberRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdLocalRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCaixa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixaMovimentacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCaixaMovimentacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixaItem")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCaixaItem = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetorno")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdArquivoRetorno = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdRecibo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorDescontoRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMoraMulta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMoraMulta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorOutrasDespesas = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorReceber = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorRecebido = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMulta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMulta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorJuros")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorJuros = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesasVencimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorOutrasDespesasVencimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Status = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("StatusPagamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var StatusPagamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CancelarNotaFiscalRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CancelarNotaFiscalRecebimento = nameTextNode.nodeValue;
				
				var valor_cp_Status = "<div style='line-height:11px; font-size:15px;'><b style='color:" + Cor + ";'>" + Status + "<br><span style='font-size:9px;'>" + StatusPagamento + "</span><b></div>";
				
				document.getElementById('cp_Status').innerHTML			= valor_cp_Status;
				document.getElementById('cp_Status').style.display		= 'block';
				document.getElementById('cp_Status').style.color		= Cor;
					
				while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
					document.getElementById('tabelaLancFinanceiro').deleteRow(1);
				}
				while(document.getElementById('tabelaParametro').rows.length > 2){
					document.getElementById('tabelaParametro').deleteRow(1);
				}
				
				document.formulario.IdPessoa.value 						= IdPessoa;
				
				document.formulario.IdContaReceber.value 				= IdContaReceber;
				document.formulario.NumeroDocumento.value 				= NumeroDocumento;
				document.formulario.ValorContaReceber.value				= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
				document.formulario.DataLancamento.value 				= dateFormat(DataLancamento);
				document.formulario.DataVencimento.value 				= dateFormat(DataVencimento);
				
				document.formulario.ValorDespesas.value					= formata_float(Arredonda(ValorDespesas,2),2).replace(".",",");
				document.formulario.ValorDesconto.value 				= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
				document.formulario.ValorFinal.value					= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
				document.formulario.IdStatus.value						= IdStatus;
				document.formulario.IdLocalCobranca.value 				= IdLocalCobranca;
				document.formulario.Obs.value 							= Obs;
				document.formulario.NumeroNF.value 						= NumeroNF;
				document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
				document.formulario.LoginCriacao.value 					= LoginCriacao;
				document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
				document.formulario.LoginAlteracao.value				= LoginAlteracao;
				document.formulario.ObsCancelamento.value				= "";								
				
				document.formulario.IdContaReceberRecebimento.value 	= IdContaReceberRecebimento;
				document.formulario.IdLocalRecebimento.value 			= IdLocalRecebimento;
				document.formulario.IdCaixa.value 						= IdCaixa;
				document.formulario.IdCaixaMovimentacao.value 			= IdCaixaMovimentacao;
				document.formulario.IdCaixaItem.value 					= IdCaixaItem;
				document.formulario.DataRecebimento.value 				= dateFormat(DataRecebimento);
				document.formulario.DataNF.value 						= dateFormat(DataNF);
				document.formulario.CancelarNotaFiscalRecebimento.value = CancelarNotaFiscalRecebimento;
				document.formulario.IdArquivoRetorno.value 				= IdArquivoRetorno;
				document.formulario.IdRecibo.value 						= IdRecibo;
				document.formulario.ValorRecebimento.value 				= formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
				document.formulario.ValorDescontoRecebimento.value 		= formata_float(Arredonda(ValorDescontoRecebimento,2),2).replace(".",",");
				document.formulario.ValorMoraMulta.value 				= formata_float(Arredonda(ValorMoraMulta,2),2).replace(".",",");
				document.formulario.ValorOutrasDespesas.value 			= formata_float(Arredonda(ValorOutrasDespesas,2),2).replace(".",",");
				document.formulario.ValorReceber.value 					= formata_float(Arredonda(ValorRecebido,2),2).replace(".",",");
				
				document.formulario.ValorTaxa.value 					= formata_float(Arredonda(ValorTaxaReImpressaoBoleto,2),2).replace(".",",");
				document.formulario.ValorMulta.value 					= formata_float(Arredonda(ValorMulta,2),2).replace(".",",");
				document.formulario.ValorJuros.value 					= formata_float(Arredonda(ValorJuros,2),2).replace(".",",");
				document.formulario.ValorOutrasDesp.value 				= formata_float(Arredonda(ValorOutrasDespesasVencimento,2),2).replace(".",",");
				document.formulario.IdCancelarNotaFiscal.value 			= '';
				
				document.formulario.Acao.value 							= 'alterar';
				
				if(IdLocalRecebimento != ''){
					document.getElementById("sp_titLocalRecebimento").style.display = "table-cell";
					document.getElementById("titLocalRecebimento").style.display = "table-cell";
					document.getElementById("sp_cpIdLocalRecebimento").style.display = "table-cell";
					document.getElementById("cpIdLocalRecebimento").style.display = "table-cell";
					
					document.getElementById("sp_titCaixa").style.display = "none";
					document.getElementById("titCaixa").style.display = "none";
					document.getElementById("sp_titMovimentacao").style.display = "none";
					document.getElementById("titMovimentacao").style.display = "none";
					document.getElementById("sp_titItem").style.display = "none";
					document.getElementById("titItem").style.display = "none";
					document.getElementById("sp_cpIdCaixa").style.display = "none";
					document.getElementById("cpIdCaixa").style.display = "none";
					document.getElementById("sp_cpIdCaixaMovimentacao").style.display = "none";
					document.getElementById("cpIdCaixaMovimentacao").style.display = "none";
					document.getElementById("sp_cpIdCaixaItem").style.display = "none";
					document.getElementById("cpIdCaixaItem").style.display = "none";
				} else {
					document.getElementById("sp_titLocalRecebimento").style.display = "none";
					document.getElementById("titLocalRecebimento").style.display = "none";
					document.getElementById("sp_cpIdLocalRecebimento").style.display = "none";
					document.getElementById("cpIdLocalRecebimento").style.display = "none";
					
					document.getElementById("sp_titCaixa").style.display = "table-cell";
					document.getElementById("titCaixa").style.display = "table-cell";
					document.getElementById("sp_titMovimentacao").style.display = "table-cell";
					document.getElementById("titMovimentacao").style.display = "table-cell";
					document.getElementById("sp_titItem").style.display = "table-cell";
					document.getElementById("titItem").style.display = "table-cell";
					document.getElementById("sp_cpIdCaixa").style.display = "table-cell";
					document.getElementById("cpIdCaixa").style.display = "table-cell";
					document.getElementById("sp_cpIdCaixaMovimentacao").style.display = "table-cell";
					document.getElementById("cpIdCaixaMovimentacao").style.display = "table-cell";
					document.getElementById("sp_cpIdCaixaItem").style.display = "table-cell";
					document.getElementById("cpIdCaixaItem").style.display = "table-cell";
				}
				
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
				
				if(CancelarNotaFiscalRecebimento == '1'){
					document.getElementById("cp_titCancelarNotaFiscal").style.display = "block";
				} else{
					document.getElementById("cp_titCancelarNotaFiscal").style.display = "none";
				}
				
				addParmUrl("marPessoa","IdContaReceber",IdContaReceber);
				addParmUrl("marPessoa","IdPessoa",IdPessoa);
				addParmUrl("marPessoaNovo","IdContaReceber",IdContaReceber);
				addParmUrl("marContasReceber","IdPessoa",IdPessoa);
				addParmUrl("marLancamentoFinanceiro","IdContaReceber",IdContaReceber);
				addParmUrl("marReenvioMensagem","IdContaReceber",IdContaReceber);
				
				//Recebido 
				listarRecebimento(IdContaReceber,false);
				busca_lancamento_financeiro(IdContaReceber,false);
				listarParametro(IdLocalRecebimento,false);
				busca_pessoa(IdPessoa,false);
				listarContaReceberParametroRecebimento(IdContaReceber,IdLocalRecebimento,false);
				status_inicial();
				
				if(Local == 'ContaReceber'){
					busca_lancamentos_data_base(IdContaReceber);
				}
				
				verificaAcao();
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}