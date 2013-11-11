	function busca_caixa_movimentacao(IdCaixa, IdCaixaMovimentacao, Erro, Local){
		if(IdCaixa == "" || IdCaixa == undefined){
			IdCaixa = 0;
		}
		
		if(IdCaixaMovimentacao == "" || IdCaixaMovimentacao == undefined){
			IdCaixaMovimentacao = 0;
		}
		
		var url = "xml/caixa_movimentacao.php?IdCaixa="+IdCaixa+"&IdCaixaMovimentacao="+IdCaixaMovimentacao;
		
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case "Movimentacao": // VOLTAR O FORMULARIO PARA O ESTADO INICIAL
						add_item();
						concluir(false);
						buscar_status();
						// LIMPAR TODOS OS ITENS DO FORMULARIO 
						var Items = document.getElementById("bl_Itens").getElementsByTagName("div");
						
						while(document.formulario.QTDItems.value > 1){
							if("bl_Item_" == Items[0].id.replace(/[\d]*$/, "")){
								var res = del_item(parseInt(Items[0].id.replace(/([^_]*_)+/, "")));
								if(res == false){
									break;
								};
							}
						}
						
						document.formulario.IdCaixaMovimentacao.value = "";
						document.formulario.DataHoraCriacao.value = "";
						document.formulario.TotalValor.value = "0,00";
						document.formulario.TotalValorMulta.value = "0,00";
						document.formulario.TotalValorJuros.value = "0,00";
						document.formulario.TotalValorDesconto.value = "0,00";
						document.formulario.TotalValorFinal.value = "0,00";
						document.formulario.Obs.readOnly = false;
						document.formulario.Obs.onfocus = function () { Foco(this,'in'); };
						document.formulario.bt_concluir.style.display = "inline";
						document.formulario.bt_recibo.style.display = "none";
						document.formulario.bt_cancelar.style.display = "none";
						document.getElementById("bl_Historico").style.display = "none";
						document.getElementById("tit_Observacao").style.color = "#000";
						document.getElementById("bl_Observacao").style.display = "none";
						document.formulario.ObsHistorico.value = "";
						document.formulario.Acao.value = "inserir";
						
						addParmUrl("marCaixaMovimentacao","IdCaixa","");
						document.formulario.IdCaixaMovimentacao.focus();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixa")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdCaixa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixaMovimentacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCaixaMovimentacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMovimentacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoMovimentacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("TipoMovimentacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var TipoMovimentacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Obs = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataHoraCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataHoraCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				switch(Local) {
					case "Movimentacao": // MONTAR O FORMULARIO APÓS A CONCLUSÃO DA CONSULTA 
						nameNode = xmlhttp.responseXML.getElementsByTagName("PermisaoCancelar")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var PermisaoCancelar = nameTextNode.nodeValue;
						
						if(PermisaoCancelar == 0){
							document.formulario.PermisaoCancelar.value = PermisaoCancelar;
						}
						
						document.formulario.DataHoraCriacao.value = DataHoraCriacao;
						
						var Item = xmlhttp.responseXML.getElementsByTagName("Item")[0];
						// LIMPAR TODOS OS ITENS DO FORMULARIO 
						add_item();
						
						var Items = document.getElementById("bl_Itens").getElementsByTagName("div");
						
						while(document.formulario.QTDItems.value > 1){
							if("bl_Item_" == Items[0].id.replace(/[\d]*$/, "")){
								var res = del_item(parseInt(Items[0].id.replace(/([^_]*_)+/, "")));
								if(res == false){
									break;
								}
							}
						}
						
						addParmUrl("marCaixaMovimentacao","IdCaixa",IdCaixa);
						concluir(false);
						buscar_status(IdStatus);
						
						
						document.formulario.IdCaixaMovimentacao.value	= IdCaixaMovimentacao;
						document.formulario.IdStatus.value				= IdStatus;
					
						// TRATAR OS ITENS 
						for(var i = 0; i < Item.getElementsByTagName("IdCaixaItem").length; i++){
							nameNode = Item.getElementsByTagName("IdCaixaItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdCaixaItem = nameTextNode.nodeValue;
							
							nameNode = Item.getElementsByTagName("IdContaReceberItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContaReceberItem = nameTextNode.nodeValue;	
							
							nameNode = Item.getElementsByTagName("ValorItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorItem = nameTextNode.nodeValue;
							
							nameNode = Item.getElementsByTagName("ValorMultaItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorMultaItem = nameTextNode.nodeValue;
							
							nameNode = Item.getElementsByTagName("ValorJurosItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorJurosItem = nameTextNode.nodeValue;
							
							nameNode = Item.getElementsByTagName("ValorDescontoItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDescontoItem = nameTextNode.nodeValue;
							
							nameNode = Item.getElementsByTagName("ValorFinalItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorFinalItem = nameTextNode.nodeValue;
							
							nameNode = Item.getElementsByTagName("NumeroDocumentoItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var NumeroDocumentoItem = nameTextNode.nodeValue;
							
							nameNode = Item.getElementsByTagName("DataVencimentoItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataVencimentoItem = nameTextNode.nodeValue;
							
							nameNode = Item.getElementsByTagName("DiaAtrasoItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DiaAtrasoItem = nameTextNode.nodeValue;
							
							nameNode = Item.getElementsByTagName("NomeItem")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeItem = nameTextNode.nodeValue;
							
							if(i > 0) {
								add_item(true);
							}
							
							var ItemMax = document.formulario.ItemMax.value;
							var CampoTotalValor = document.formulario.TotalValor;
							var CampoTotalValorMulta = document.formulario.TotalValorMulta;
							var CampoTotalValorMultaJuros = document.formulario.TotalValorMultaJuros;
							var CampoTotalValorJuros = document.formulario.TotalValorJuros;
							var CampoTotalValorDesconto = document.formulario.TotalValorDesconto;
							var CampoTotalValorFinal = document.formulario.TotalValorFinal;
							var ValorTmp = parseFloat(CampoTotalValor.value.replace(/,/g, ".")) + parseFloat(ValorItem);
							var ValorMultaTmp = parseFloat(CampoTotalValorMulta.value.replace(/,/g, ".")) + parseFloat(ValorMultaItem);
							var ValorJurosTmp = parseFloat(CampoTotalValorJuros.value.replace(/,/g, ".")) + parseFloat(ValorJurosItem);
							var ValorDescontoTmp = parseFloat(CampoTotalValorDesconto.value.replace(/,/g, ".")) + parseFloat(ValorDescontoItem);
							var ValorFinalTmp = parseFloat(CampoTotalValorFinal.value.replace(/,/g, ".")) + parseFloat(ValorFinalItem);
								
							remove_fechar(IdStatus, ItemMax);
							CampoTotalValor.value = formata_float(Arredonda(ValorTmp, 2), 2).replace(/\./g, ",");
							CampoTotalValorMulta.value = formata_float(Arredonda(ValorMultaTmp, 2), 2).replace(/\./g, ",");
							CampoTotalValorJuros.value = formata_float(Arredonda(ValorJurosTmp, 2), 2).replace(/\./g, ",");
							CampoTotalValorMultaJuros.value = formata_float(Arredonda((ValorJurosTmp+ValorMultaTmp), 2), 2).replace(/\./g, ",");
							CampoTotalValorDesconto.value = formata_float(Arredonda(ValorDescontoTmp, 2), 2).replace(/\./g, ",");
							CampoTotalValorFinal.value = formata_float(Arredonda(ValorFinalTmp, 2), 2).replace(/\./g, ",");
							document.formulario.TipoMovimentacao.value = IdTipoMovimentacao;
							if(document.formulario.TipoMovimentacao.value == 4){
								document.formulario.bt_cancelar.style.display="none";
								DiaAtraso = 0;
							}else{
								document.formulario.bt_cancelar.style.display="block";
							}
							// MODULO DE EXECUÇÃO EM ESPERA (MONTAGEM E PREENCHIMENTO DOS ITENS)
							(function runTimeIT(Inicia, Item) {
								if(Inicia){
									if(document.getElementById("icoDelItem_"+Item.ItemMax) != null) {
										document.getElementById("icoDelItem_"+Item.ItemMax).onclick = function () {};
										
										var ValorTotalMultaJuros = Number(Item.ValorMulta)+Number(Item.ValorJuros);
										
										if(ValorTotalMultaJuros == '0'){
											eval("document.formulario.CalcularMultaJuros_"+Item.ItemMax+".options[1].selected = 'true'");
										}else{
											eval("document.formulario.CalcularMultaJuros_"+Item.ItemMax+".options[0].selected = 'true'");
										}
										
										eval("document.formulario.IdContaReceberItem_"+Item.ItemMax+".value = '"+Item.IdContaReceber+"';");
										eval("document.formulario.IdContaReceberItemTemp_"+Item.ItemMax+".value = '"+Item.IdContaReceber+"';");
										eval("document.formulario.NumeroDocumentoItem_"+Item.ItemMax+".value = '"+Item.NumeroDocumento+"';");
										eval("document.formulario.NomePessoaItem_"+Item.ItemMax+".value = '"+Item.Nome+"';");
										eval("document.formulario.DataVencimentoItem_"+Item.ItemMax+".value = '"+dateFormat(Item.DataVencimento)+"';");
										eval("document.formulario.DiaAtrasoItem_"+Item.ItemMax+".value = '"+Item.DiaAtraso+"';");
										eval("document.formulario.ValorItem_"+Item.ItemMax+".value = '"+formata_float(Arredonda(Item.Valor, 2), 2).replace(/\./g, ",")+"';");
										eval("document.formulario.ValorMultaJurosItem_"+Item.ItemMax+".value = '"+formata_float(Arredonda((Number(Item.ValorMulta)+Number(Item.ValorJuros)), 2), 2).replace(/\./g, ",")+"';");
										eval("document.formulario.CalcularMultaJuros_"+Item.ItemMax+".disabled = 'true'");
										eval("document.formulario.ValorMultaItem_"+Item.ItemMax+".value = '"+formata_float(Arredonda(Item.ValorMulta, 2), 2).replace(/\./g, ",")+"';");
										eval("document.formulario.ValorJurosItem_"+Item.ItemMax+".value = '"+formata_float(Arredonda(Item.ValorJuros, 2), 2).replace(/\./g, ",")+"';");
										eval("document.formulario.ValorDescontoItem_"+Item.ItemMax+".value = '"+formata_float(Arredonda(Item.ValorDesconto, 2), 2).replace(/\./g, ",")+"';");
										eval("document.formulario.ValorFinalItem_"+Item.ItemMax+".value = '"+formata_float(Arredonda(Item.ValorFinal, 2), 2).replace(/\./g, ",")+"';");
									
										buscar_DescontoConceber(Item.IdContaReceber,Item.ItemMax);
									}
									
									executando(false);
								} else {
									executando(true);
									setTimeout(function () { 
										runTimeIT(true, Item); 
									}, 111);
								}
							})(false, {ItemMax : ItemMax, IdContaReceber : IdContaReceberItem, NumeroDocumento : NumeroDocumentoItem, Nome : NomeItem, DataVencimento : DataVencimentoItem, DiaAtraso : DiaAtrasoItem, Valor : ValorItem, ValorMulta : ValorMultaItem, ValorJuros : ValorJurosItem, ValorDesconto : ValorDescontoItem, ValorFinal : ValorFinalItem});
						}
						// MODULO DE EXECUÇÃO EM ESPERA (VERIFICAÇÃO DE CANCELAMENTO E ATRIBUIÇÃO DE VALOR PARA O HÍSTORICO)
						(function runTimeCC(Obs){
							if(executando() == 0){
								concluir(true);
								
								if(Obs != ""){
									document.getElementById("bl_Historico").style.display = "block";
								} else {
									document.getElementById("bl_Historico").style.display = "none";
								}
								
								document.formulario.ObsHistorico.value = Obs;
								document.formulario.Obs.readOnly = false;
								document.formulario.Obs.onfocus = function () { Foco(this,'in'); };
								
								if(IdStatus != '0'){
									document.getElementById("bl_Observacao").style.display = "block";
									
									if(document.formulario.PermisaoCancelar.value == "0"){
										document.getElementById("tit_Observacao").style.color = "#000";
										document.formulario.Obs.readOnly = true;
										document.formulario.Obs.onfocus = function () {};
									} else {
										document.getElementById("tit_Observacao").style.color = "#c10000";
									}
								} else {
									document.getElementById("tit_Observacao").style.color = "#000";
									document.getElementById("bl_Observacao").style.display = "none";
								}
							} else {
								setTimeout(function () {
									runTimeCC(Obs);
								}, 111);
							}
						})(Obs);
						
						var FormaPagamento = xmlhttp.responseXML.getElementsByTagName("FormaPagamento")[0];
						// TRATAR AS FORMAS DE PAGAMENTO 
						for(i = 0; i < FormaPagamento.getElementsByTagName("IdFormaPagamento").length; i++){
							nameNode = FormaPagamento.getElementsByTagName("IdFormaPagamento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdFormaPagamento = nameTextNode.nodeValue;
							
							nameNode = FormaPagamento.getElementsByTagName("ValorFormaPagamento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorFormaPagamento = nameTextNode.nodeValue;
							
							nameNode = FormaPagamento.getElementsByTagName("QtdParcelas")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdParcelas = nameTextNode.nodeValue;
							
							nameNode = FormaPagamento.getElementsByTagName("ValorParcela")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorParcela = nameTextNode.nodeValue;
							
							nameNode = FormaPagamento.getElementsByTagName("ValorJuros")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorJuros = nameTextNode.nodeValue;
							
							nameNode = FormaPagamento.getElementsByTagName("ValorTotal")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorTotal = nameTextNode.nodeValue;
							// MODULO DE EXECUÇÃO EM ESPERA (FORMA DE PAGAMENTO) 
							(function runTimeFP(FormaPagamento) {
								var titValor = document.getElementById("tit_FormaPagamentoValor_"+FormaPagamento.IdFormaPagamento);
								var titQtdParcelas = document.getElementById("tit_FormaPagamentoQtdParcelas_"+FormaPagamento.IdFormaPagamento);
								
								eval("var campValor = document.formulario.FormaPagamentoValor_"+FormaPagamento.IdFormaPagamento+";");
								eval("var campQtdParcelas = document.formulario.FormaPagamentoQtdParcela_"+FormaPagamento.IdFormaPagamento+";");
								eval("var campValorParcela = document.formulario.FormaPagamentoValorParcela_"+FormaPagamento.IdFormaPagamento+";");
								eval("var campJurosMes = document.formulario.FormaPagamentoJurosMes_"+FormaPagamento.IdFormaPagamento+";");
								eval("var campValorTotal = document.formulario.FormaPagamentoValorTotal_"+FormaPagamento.IdFormaPagamento+";");
								
								// VERIFICA SE OS ELEMENTOS FORAM CRIADOS 
								if(executando() == 0 && titValor != null && titQtdParcelas != null && campValor != undefined && campQtdParcelas != undefined && campValorParcela != undefined && campJurosMes != undefined && campValorTotal != undefined) {
									campValor.value = formata_float(Arredonda(FormaPagamento.ValorFormaPagamento, 2), 2).replace(/\./g, ",");
									campValorParcela.value = formata_float(Arredonda(FormaPagamento.ValorParcela, 2), 2).replace(/\./g, ",");
									campJurosMes.value = formata_float(Arredonda(FormaPagamento.ValorJuros, 2), 2).replace(/\./g, ",");
									campValorTotal.value = formata_float(Arredonda(FormaPagamento.ValorTotal, 2), 2).replace(/\./g, ",");
									
									for(var i = 0; i <campQtdParcelas.options.length; i++){
										if(campQtdParcelas.options[i].text == FormaPagamento.QtdParcelas){
											campQtdParcelas.options[i].selected = true;
											
											total_forma_pagamento();
											break;
										}
									}
									
									Foco(campValor, "out");
									
									titValor.style.color = "#000";
									titQtdParcelas.style.color = "#000";
									campValor.onfocus = function () {};
									campQtdParcelas.onfocus = function () {};
									campValor.readOnly = true;
									campQtdParcelas.disabled = true;
									
									if(document.formulario.IdStatus.value == '200'){
										document.formulario.bt_recibo.style.display = "inline";
									}
									
									document.formulario.bt_concluir.style.display = "none";
									document.formulario.bt_receber.style.display = "none";
									if(document.formulario.TipoMovimentacao.value == 4)
										document.formulario.bt_cancelar.style.display = "none";
									else
										document.formulario.bt_cancelar.style.display = "inline";
									
									
									document.formulario.IdCaixaMovimentacao.focus();
								} else {
									setTimeout(function () { 
										runTimeFP(FormaPagamento); 
									}, 111);
								}
							})({IdFormaPagamento : IdFormaPagamento, ValorFormaPagamento : ValorFormaPagamento, QtdParcelas : QtdParcelas, ValorParcela : ValorParcela, ValorJuros : ValorJuros, ValorTotal : ValorTotal});
						}
						
						document.formulario.Acao.value = "alterar";
						
						//pagamentoParcial(eval("document.formulario.RecebimentoParcialItem_"+Item+".value"),Item);
						verificaAcao();
						break;
				}
			}
		});
	}