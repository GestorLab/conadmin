	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == "inserir"){
				document.formulario.bt_concluir.disabled	= true;
				document.formulario.bt_item.disabled		= false;
			}
			
			if(document.formulario.Acao.value == "alterar"){
				document.formulario.bt_concluir.disabled	= true;
				document.formulario.bt_item.disabled		= false;
			}
						
			if(document.formulario.PermisaoCancelar.value == "0" || document.formulario.IdStatus.value == "0"){
				document.formulario.bt_cancelar.onclick		= function () {};
				document.formulario.bt_cancelar.disabled	= true;
			} else {
				document.formulario.bt_cancelar.onclick		= function () { cadastrar('cancelar'); };
				document.formulario.bt_cancelar.disabled	= false;
			}
		}	
	}
	function inicia(){
		tipo_movimentacao();
	}
	function executando(executando) {
		var QTDExecucao = parseInt(document.formulario.Executando.value);
		
		switch(executando){
			case true:
				document.formulario.Executando.value = (QTDExecucao + 1);
				break;
				
			case false:
				if(document.formulario.Executando.value > 0){
					document.formulario.Executando.value = (QTDExecucao - 1);
				}
				break;
				
			default:
				return QTDExecucao;
		}
	}
	function validar() { /* VALIDAR DADOS ANTES DE SUBMETER O FORMULARIO AO ENVIO */
		for(var i = 1; i <= document.formulario.ItemMax.value; i++){
			eval("var CampoBloco = document.getElementById('bl_Item_"+i+"');");
			
			if(CampoBloco != null){
				eval("var IdContaReceberItem = document.formulario.IdContaReceberItem_"+i+";");
				
				if(IdContaReceberItem.value == ''){
					IdContaReceberItem.focus();
					mensagens(1);
					return false;
				}
				
				eval("var ValorDescontoItem = document.formulario.ValorDescontoItem_"+i+";");
				
				if(ValorDescontoItem.value == ''){
					ValorDescontoItem.focus();
					mensagens(1);
					return false;
				}
			}
		}
		
		var FormaPagamentoValor = 0.00;
		
		for(i = 0; i < document.formulario.elements.length; i++){
			if(document.formulario.elements[i].name.substring(0, 20) == "FormaPagamentoValor_"){
				FormaPagamentoValor = parseFloat(document.formulario.elements[i].value.replace(/,/g, '.'));
				
				if(isNaN(FormaPagamentoValor)){
					FormaPagamentoValor = 0.00;
				}
				
				if(document.formulario.elements[i].value == "" || (FormaPagamentoValor < 0.00)){
					document.formulario.elements[i].focus();
					mensagens(1);
					return false;
				}
			} else {
				if(document.formulario.elements[i].name.substring(0, 25) == "FormaPagamentoQtdParcela_"){
					if(FormaPagamentoValor > 0.00 && document.formulario.elements[i].value == ""){
						document.formulario.elements[i].focus();
						mensagens(1);
						return false;
					}
				}
			}
		}
		
		if(parseFloat(document.formulario.TotalValorFinal.value.replace(/,/g, '.')) != parseFloat(document.formulario.TotalFormaPagamentoValor.value.replace(/,/g, '.'))){
			var Element = document.getElementById("bl_FormaPagamento");
			Element = Element.getElementsByTagName("table");
			Element = Element[Element.length - 2].getElementsByTagName("input");
			Element[1].focus();
			mensagens(184);
			return false;
		}
		
		if(document.formulario.Acao.value == "cancelar"){
			if(document.formulario.Obs.value == ""){
				document.formulario.Obs.focus();
				mensagens(1);
				return false;
			}
		}
		
		mensagens(0);
		return true;
	}
	function soma_total(Item){
		var TotalValor = parseFloat(document.formulario.TotalValor.value.replace(/,/g, ".")); 
		var TotalValorMulta = parseFloat(document.formulario.TotalValorMulta.value.replace(/,/g, ".")); 
		var TotalValorJuros = parseFloat(document.formulario.TotalValorJuros.value.replace(/,/g, ".")); 
		var TotalValorDesconto = parseFloat(document.formulario.TotalValorDesconto.value.replace(/,/g, ".")); 
		
		eval("var CampoValor = document.formulario.ValorItem_"+Item+";");
		eval("var CampoValorMulta = document.formulario.ValorMultaItem_"+Item+";");
		eval("var CampoValorJuros = document.formulario.ValorJurosItem_"+Item+";");
		eval("var CampoValorDesconto = document.formulario.ValorDescontoItem_"+Item+";");
		eval("var CampoValorFinal = document.formulario.ValorFinalItem_"+Item+";");
		
		var Valor = parseFloat(CampoValor.value.replace(/,/g, ".")); 
		var ValorMulta = parseFloat(CampoValorMulta.value.replace(/,/g, ".")); 
		var ValorJuros = parseFloat(CampoValorJuros.value.replace(/,/g, ".")); 
		var ValorDesconto = parseFloat(CampoValorDesconto.value.replace(/,/g, ".")); 
		var ValorFinalTemp = parseFloat(CampoValorFinal.value.replace(/,/g, ".")); 
		
		if(isNaN(Valor)){
			Valor = 0.00;
		}
		
		if(isNaN(ValorMulta)){
			ValorMulta = 0.00;
		}
		
		if(isNaN(ValorJuros)){
			ValorJuros = 0.00;
		}
		
		if(isNaN(ValorDesconto)){
			ValorDesconto = 0.00;
		}
		
		if(isNaN(ValorFinalTemp)){
			ValorFinalTemp = 0.00;
		}
		
		var ValorFinal = (Valor + ValorMulta + ValorJuros) - ValorDesconto;
		TotalValorDesconto += (ValorFinalTemp - ValorFinal);
		CampoValorFinal.value = formata_float(Arredonda(ValorFinal, 2), 2).replace(/\./g, ",");
		document.formulario.TotalValorDesconto.value = formata_float(Arredonda(TotalValorDesconto, 2), 2).replace(/\./g, ",");
		var TotalValorFinal = (TotalValor + TotalValorMulta + TotalValorJuros) - TotalValorDesconto;
		
		concluir(false);
		
		document.formulario.TotalValorFinal.value = formata_float(Arredonda(TotalValorFinal, 2), 2).replace(/\./g, ",");
	}
	function cadastrar(acao){
		document.formulario.Acao.value = acao;
		
		switch(acao){
			case "inserir":
				if(validar()){
					if(document.formulario.TipoMovimentacao.value != 4){
						if(confirm("ATENÇÃO!\n\nVocê está prestes a receber os itens selecionados acima. \r\nDeseja continuar?","SIM","NÃO")){
							document.formulario.submit();
						}
					} 						
					else{
						if(confirm("ATENÇÃO!\n\nVocê está prestes a estornar os itens selecionados acima. \r\nDeseja continuar?","SIM","NÃO")){
							document.formulario.submit();
						}
					}
				}
				break;
			case "cancelar":
				if(validar()){ 
					if(confirm("ATENÇÃO!\n\nVocê está prestes a cancelar esta movimentação. \r\nDeseja continuar?","SIM","NÃO")){
						document.formulario.submit();
					}
				}
				break;
			case "recibo":
				if(document.formulario.TipoMovimentacao.value != 4){
					open("./estorno.php?IdCaixa="+document.formulario.IdCaixa.value+"&IdCaixaMovimentacao="+document.formulario.IdCaixaMovimentacao.value+"&IdTipoMovimentacao="+document.formulario.TipoMovimentacao.value);
				}else{
					open("./estorno.php?IdCaixa="+document.formulario.IdCaixa.value+"&IdCaixaMovimentacao="+document.formulario.IdCaixaMovimentacao.value);					
				}
				break;
			default:
				document.formulario.submit();
		}
	}
	function add_item(Forca){
		if(document.formulario.TipoMovimentacao.value == 4){
			if(document.formulario.QuantidadeTituloEstorno.value != "" && document.formulario.QuantidadeTituloEstorno.value != null)
			{
				if(document.formulario.QTDItems.value > document.formulario.QuantidadeTituloEstorno.value){
					alert("Atenção,\nO Caixa esta configurado para estorna apenar "+document.formulario.QuantidadeTituloEstorno.value+" titulos por movimentação.");
					return false;
				}	
			}
		}
		return tipo_movimentacao(document.formulario.TipoMovimentacaoTemp.value, true, Forca);
	}
	function del_item(Item){
		var Element = document.getElementById("bl_Itens");
		var CampoQTDItems = document.formulario.QTDItems;
		var TotalValor = 0.00;
		var TotalValorMulta = 0.00;
		var TotalValorJuros = 0.00;
		var TotalValorDesconto = 0.00;
		
		eval("var ContasReceber = document.formulario.IdContaReceberItem_"+Item+";");
		
		/*if(ContasReceber.readOnly == true){
			return false;
		}*/
		
		ContasReceber = ContasReceber.value;
		document.formulario.ContasReceber.value = document.formulario.ContasReceber.value.replace(new RegExp(","+ContasReceber+",","i"), ",");
		document.formulario.ContasReceber.value = document.formulario.ContasReceber.value.replace(new RegExp(","+ContasReceber+"$","i"), "");
		
		if(CampoQTDItems.value > 1){
		
			if(document.formulario.TipoMovimentacao.value != 4){
				var ElementCentTMP = Element.getElementsByTagName("div");
				var ElementCent = ElementCentTMP[ElementCentTMP.length - 3];
				var ItemName = ElementCent.getElementsByTagName("input")[0].name.substring(19);
				
				if(ItemName == Item){
					ElementCent = ElementCentTMP[ElementCentTMP.length - 3];
					if(ElementCent != undefined)
						ItemName = ElementCent.getElementsByTagName("input")[0].name.substring(19);
				}
			}else{
				var ElementCentTMP = Element.getElementsByTagName("div");
				var ElementCent = ElementCentTMP[ElementCentTMP.length - 2];
				var ItemName = ElementCent.getElementsByTagName("input")[0].name.substring(19);
				
				if(ItemName == Item){
					ElementCent = ElementCentTMP[ElementCentTMP.length - 2];
					if(ElementCent != undefined)
						ItemName = ElementCent.getElementsByTagName("input")[0].name.substring(19);
				}
			}
			
			document.getElementById("tit_ContaReceber_"+ItemName).style.color = "#c10000";
			ElementCent.getElementsByTagName("input")[0].onfocus = function () {
				Foco(this,'in');
			};
			ElementCent.getElementsByTagName("input")[0].onblur = function () {
				Foco(this,'out'); 
			};
			ElementCent.getElementsByTagName("input")[0].readOnly = false;
			ElementCent.getElementsByTagName("input")[0].focus();
			ElementCent.getElementsByTagName("input")[2].onfocus = function (){
				Foco(this,'in');
			};
			ElementCent.getElementsByTagName("input")[2].onblur = function () {
				Foco(this,'out'); 
			};
			ElementCent.getElementsByTagName("input")[2].readOnly = false;
			
			eval(ElementCent.getElementsByTagName("img")[0].onclick.toString().replace(/(false)/i, "true"));
			
			ElementCent.getElementsByTagName("img")[0].onclick = onclick;
			ElementCent.getElementsByTagName("img")[0].src = "../../img/estrutura_sistema/ico_lupa.gif";
			Element.removeChild(document.getElementById("bl_Item_"+Item));
					
			for(var i = 1, Item = 0; i <= document.formulario.ItemMax.value; i++){
				if(document.getElementById("tit_bl_Item_"+i) != null){
					Item++;
					document.getElementById("tit_bl_Item_"+i).innerHTML = "Item "+Item;
					
					eval("var TempValor = parseFloat(document.formulario.ValorItem_"+i+".value.replace(/,/g, '.'));");
					eval("var TempValorMulta = parseFloat(document.formulario.ValorMultaItem_"+i+".value.replace(/,/g, '.'));");
					eval("var TempValorJuros = parseFloat(document.formulario.ValorJurosItem_"+i+".value.replace(/,/g, '.'));");
					eval("var TempValorDesconto = parseFloat(document.formulario.ValorDescontoItem_"+i+".value.replace(/,/g, '.'));");
					
					if(isNaN(TempValor)){
						TempValor = 0.00;
					}
					
					if(isNaN(TempValorMulta)){
						TempValorMulta = 0.00;
					}
					
					if(isNaN(TempValorJuros)){
						TempValorJuros = 0.00;
					}
					
					if(isNaN(TempValorDesconto)){
						TempValorDesconto = 0.00;
					}
					
					TotalValor += TempValor;
					TotalValorMulta += TempValorMulta;
					TotalValorJuros += TempValorJuros;
					TotalValorDesconto += TempValorDesconto;
				}
			}
			
			
			var Quantidade = parseInt(CampoQTDItems.value) - 1;
			CampoQTDItems.value = Quantidade;
						
			var TotalValorFinal = (TotalValor + TotalValorMulta + TotalValorJuros) - TotalValorDesconto;
			
		} else{
			eval("document.formulario.IdContaReceberItem_"+Item+".value = '';");
			eval("document.formulario.NumeroDocumentoItem_"+Item+".value = '';");
			eval("document.formulario.NomePessoaItem_"+Item+".value = '';");
			eval("document.formulario.DataVencimentoItem_"+Item+".value = '';");
			eval("document.formulario.DiaAtrasoItem_"+Item+".value = '';");
			eval("document.formulario.ValorItem_"+Item+".value = '';");
			eval("document.formulario.ValorMultaItem_"+Item+".value = '';");
			eval("document.formulario.ValorJurosItem_"+Item+".value = '';");
			eval("document.formulario.ValorDescontoItem_"+Item+".value = '';");
			eval("document.formulario.ValorFinalItem_"+Item+".value = '';");
			eval("document.formulario.DescontoConceberItem_"+Item+".value = '';");
			
			eval("if(document.formulario.DescontoConceberItem_"+Item+" != undefined) document.formulario.DescontoConceberItem_"+Item+".value=''; document.getElementById('LimiteItem_"+Item+"').innerHTML = ''");
			
			TotalValor = 0.00;
			TotalValorMulta = 0.00;
			TotalValorJuros = 0.00;
			TotalValorDesconto = 0.00;
			TotalValorFinal = 0.00;
			
			var Quantidade = parseInt(CampoQTDItems.value) - 1;
			CampoQTDItems.value = Quantidade;
			
		}
		
		concluir(false);
		
		var ElementCent = Element.getElementsByTagName("div");
		ElementCent = ElementCent[ElementCent.length - 2].getElementsByTagName("input");
		
		if(TotalValorFinal > 0.00 && ElementCent[0].value != ""){
			document.formulario.bt_concluir.disabled = false;
		} else{
			document.formulario.bt_concluir.disabled = true;
		}
		
		document.formulario.TotalValor.value = formata_float(Arredonda(TotalValor, 2), 2).replace(/\./g, ",");
		document.formulario.TotalValorMulta.value = formata_float(Arredonda(TotalValorMulta, 2), 2).replace(/\./g, ",");
		document.formulario.TotalValorJuros.value = formata_float(Arredonda(TotalValorJuros, 2), 2).replace(/\./g, ",");
		document.formulario.TotalValorDesconto.value = formata_float(Arredonda(TotalValorDesconto, 2), 2).replace(/\./g, ",");
		document.formulario.TotalValorFinal.value = formata_float(Arredonda(TotalValorFinal, 2), 2).replace(/\./g, ",");
		
	}
	function selecionar_item(Item){
		document.formulario.ManipulaItem.value = Item;
	}
	function tipo_movimentacao(IdTipoMovimentacao, ADD, Forca){
		if(IdTipoMovimentacao == undefined){
			IdTipoMovimentacao = document.formulario.IdTipoMovimentacaoDefault.value;
		}
		
		if(ADD == false || ADD == undefined){
			document.getElementById("bl_Itens").innerHTML = "";
		}
		
		var Item = Number(document.formulario.QTDItems.value)+1;
		var ItemMax = Number(document.formulario.ItemMax.value)+1;
		
		switch(Number(IdTipoMovimentacao)){
			case 1:
				var Element = document.getElementById("bl_Itens");
				
				if(ItemMax > 1){
					var ElementCent = Element.getElementsByTagName("div");
					ElementCent = ElementCent[ElementCent.length - 3];
					
					if(!Forca){
						if(ElementCent != undefined){
							if(ElementCent.getElementsByTagName("input")[0] != undefined)
								ADD = (ElementCent.getElementsByTagName("input")[0].value != "");
						}
					}
					
					document.formulario.bt_receber.value = "Receber";
					if(ADD){
						var ItemName = ElementCent.getElementsByTagName("input")[0].name.substring(19);
						document.getElementById("tit_ContaReceber_"+ItemName).style.color = "#000";
						ElementCent.getElementsByTagName("input")[0].onfocus = function () {};
						ElementCent.getElementsByTagName("input")[0].readOnly = true;
						ElementCent.getElementsByTagName("input")[2].onfocus = function () {};
						ElementCent.getElementsByTagName("input")[2].readOnly = true;
						eval(ElementCent.getElementsByTagName("img")[0].onclick.toString().replace(/(true)/i, "false"));
						
						ElementCent.getElementsByTagName("img")[0].onclick = onclick;
						ElementCent.getElementsByTagName("img")[0].src = "../../img/estrutura_sistema/ico_lupa_c.gif";
					} else{
						if(ElementCent != undefined){
							ElementCent.getElementsByTagName("input")[0].focus();
						}
					}
				} else {
					ADD = undefined;
				}
				
				if(ADD || ADD == undefined){
					//document.formulario.bt_concluir.disabled = true;
					document.formulario.QTDItems.value = Item;
					document.formulario.ItemMax.value = ItemMax;
					
					var IdNome = "bl_Item_"+ItemMax;
					var DivLN = document.createElement("div");
					DivLN.setAttribute("id", IdNome);
					Element.appendChild(DivLN);
					Element = document.getElementById(IdNome);
					
					IdNome = "cp_tit";
					DivLN = document.createElement("div");
					DivLN.setAttribute("id", IdNome);
					DivLN.innerHTML = "<table style='margin:0;' cellpadding='0' cellspacing='0'><tr><td style='width:824px;' id='tit_bl_Item_"+ItemMax+"'>Item "+Item+"</td><td><span id='icoDelItem_"+ItemMax+"' onClick=\"del_item("+ItemMax+");\" style='cursor:pointer;'>[x]</span></td></tr></table>";
					Element.appendChild(DivLN);
					
					IdNome = "cp_TipoMovimentacao_0_"+ItemMax;
					DivLN = document.createElement("table");
					DivLN.setAttribute("id", IdNome);
					Element.appendChild(DivLN);
					var table = document.getElementById(IdNome);
					var linha = table.insertRow((table.rows.length)-1);
					var c0 = linha.insertCell(0);
					var c1 = linha.insertCell(1);
					var c2 = linha.insertCell(2);
					var c3 = linha.insertCell(3);
					var c4 = linha.insertCell(4);
					var c5 = linha.insertCell(5);
					var c6 = linha.insertCell(6);
					var c7 = linha.insertCell(7);
					var c8 = linha.insertCell(8);
					var c9 = linha.insertCell(9);
					
					c0.className = "find";
					c0.innerHTML = "<img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' id='icoBuscaContaReceber_"+ItemMax+"' onClick=\"selecionar_item("+ItemMax+"); vi_id('quadroBuscaContaReceber', true, event, null, 165);\" />";
					
					c1.className = "campo";
					c1.innerHTML = "<input type='text' name='IdContaReceberItem_"+ItemMax+"' value='' autocomplete='off' style='width:80px' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" onChange=\"selecionar_item("+ItemMax+"); busca_conta_receber(this.value,false,document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int');\" tabindex='"+(document.formulario.TabIndex.value++)+"' /><input type='hidden' name='IdContaReceberItemTemp_"+ItemMax+"' value='' />";
					
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					
					c3.className = "campo";
					c3.innerHTML = "<input type='text' name='NumeroDocumentoItem_"+ItemMax+"' value='' autocomplete='off' style='width:110px' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" onChange=\"selecionar_item("+ItemMax+"); busca_conta_receber(this.value,false,document.formulario.Local.value,'NumeroDocumento');\" onkeypress=\"mascara(this,event,'int');\" tabindex='"+(document.formulario.TabIndex.value++)+"' />";
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					
					c5.className = "campo";
					c5.innerHTML = "<input type='text' name='NomePessoaItem_"+ItemMax+"' value='' style='width:371px' readonly='readonly' />";
					
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					
					c7.className = "campo";
					c7.innerHTML = "<input type='text' name='DataVencimentoItem_"+ItemMax+"' value='' style='width:107px' readonly='readonly' />";
					
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					
					c9.className = "campo";
					c9.innerHTML = "<input type='text' name='DiaAtrasoItem_"+ItemMax+"' value='' style='width:80px' readonly='readonly' />";
					
					linha = table.insertRow((table.rows.length)-1);
					c0 = linha.insertCell(0);
					c1 = linha.insertCell(1);
					c2 = linha.insertCell(2);
					c3 = linha.insertCell(3);
					c4 = linha.insertCell(4);
					c5 = linha.insertCell(5);
					c6 = linha.insertCell(6);
					c7 = linha.insertCell(7);
					c8 = linha.insertCell(8);
					c9 = linha.insertCell(9);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					
					c1.className = "descCampo";
					c1.innerHTML = "<b id='tit_ContaReceber_"+ItemMax+"'>Conta Receber</b>";
					
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					
					c3.className = "descCampo";
					c3.innerHTML = "Número Documento";
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					
					c5.className = "descCampo";
					c5.setAttribute("id", "tit_Pessoa_"+ItemMax);
					c5.innerHTML = "Nome Pessoa";
					
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					
					c7.className = "descCampo";
					c7.innerHTML = "Data Vencimento";
					
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					
					c9.className = "descCampo";
					c9.innerHTML = "Dias Atraso";
					
					IdNome = "cp_TipoMovimentacao_1_"+ItemMax;
					DivLN = document.createElement("table");
					DivLN.setAttribute("id", IdNome);
					Element.appendChild(DivLN);
					table = document.getElementById(IdNome);
					linha = table.insertRow((table.rows.length)-1);
					c0 = linha.insertCell(0);
					c1 = linha.insertCell(1);
					c2 = linha.insertCell(2);
					c3 = linha.insertCell(3);
					c4 = linha.insertCell(4);
					c5 = linha.insertCell(5);
					c6 = linha.insertCell(6);
					c7 = linha.insertCell(7);
					c8 = linha.insertCell(8);
					c9 = linha.insertCell(9);
					c10 = linha.insertCell(10);
					c11 = linha.insertCell(11);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					
					c1.className = "campo";
					c1.innerHTML = "<input type='text' name='ValorItem_"+ItemMax+"' value='' style='width:149px' readonly='readonly' />";
					
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					
					c3.className = "campo";
					c3.innerHTML = "<input type='text' name='ValorMultaJurosItem_"+ItemMax+"' value='' style='width:150px' readonly='readonly' /> <input type='hidden' name='ValorMultaItem_"+ItemMax+"' value='' style='width:149px' readonly='readonly' /> <input type='hidden' name='ValorJurosItem_"+ItemMax+"' value='' style='width:150px' readonly='readonly' />";
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
															
					c5.className = "campo";
					c5.innerHTML = "<input type='text' name='ValorDescontoItem_"+ItemMax+"' value='' style='width:150px' onkeypress=\"mascara(this,event,'float');\" onkeydown=\"backspace(this,event);\" onChange=\"soma_total("+ItemMax+");\" onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" maxlength='14' tabindex='"+(document.formulario.TabIndex.value++)+"' />";
					
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					
					c7.className = "campo";
					c7.innerHTML = "<input type='text' name='ValorFinalItem_"+ItemMax+"' value='' style='width:150px' readonly='readonly' />";
				
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					
					c9.className = "campo";
					c9.innerHTML = "<select name='CalcularMultaJuros_"+ItemMax+"' style='width:150px' onchange='calcularMultaJuros(this.value,this.name)'><option value='1'>Sim</option><option value='2'>Não</option></select>";
					
					linha = table.insertRow((table.rows.length)-1);
					c0 = linha.insertCell(0);
					c1 = linha.insertCell(1);
					c2 = linha.insertCell(2);
					c3 = linha.insertCell(3);
					c4 = linha.insertCell(4);
					c5 = linha.insertCell(5);
					c6 = linha.insertCell(6);
					c7 = linha.insertCell(7);
					c8 = linha.insertCell(8);
					c9 = linha.insertCell(9);
					c10 = linha.insertCell(10);
					c11 = linha.insertCell(11);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					
					c1.className = "descCampo";
					c1.innerHTML = "Valor ("+document.formulario.Moeda.value+")";
					
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					
					c3.className = "descCampo";
					c3.innerHTML = "(+) Valor Multa/Juros ("+document.formulario.Moeda.value+")";
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					
					c5.className = "descCampo";
					c5.innerHTML = "<b id='tit_ValorDesconto_"+ItemMax+"'>(-) Valor Desconto ("+document.formulario.Moeda.value+")</b>";
					
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					
					c7.className = "descCampo";
					c7.innerHTML = "(=) Valor Final ("+document.formulario.Moeda.value+")";
					
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					
					c9.className = "descCampo";
					c9.innerHTML = "Calc. Multa/Juros";
					
					IdNome = "cp_TipoMovimentacao_2_"+ItemMax;
					DivLN = document.createElement("table");
					DivLN.setAttribute("id", IdNome);
					Element.appendChild(DivLN);
					table = document.getElementById(IdNome);
					linha = table.insertRow((table.rows.length)-1);
					c0 = linha.insertCell(0);
					c1 = linha.insertCell(1);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					
					c1.className = "campo";
					c1.innerHTML = "<input type='text' name='DescontoConceberItem_"+ItemMax+"' value='' style='width:149px' readonly='readonly' /><br/><div id='LimiteItem_"+ItemMax+"'></div>";
					
					linha = table.insertRow((table.rows.length)-1);
					c0 = linha.insertCell(0);
					c1 = linha.insertCell(1);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					
					c1.className = "descCampo";
					c1.innerHTML = "Desconto à Conceber";
										
					eval("document.formulario.IdContaReceberItem_"+ItemMax+".focus();");
				}
				
				if(document.formulario.IdContaReceberItemTemp.value != ""){
					selecionar_item(ItemMax);
					busca_conta_receber(document.formulario.IdContaReceberItemTemp.value,false,document.formulario.Local.value);
					document.formulario.IdContaReceberItemTemp.value = "";
				}
				
				break;
			
			case 4:
				var Element = document.getElementById("bl_Itens");
				
				if(ItemMax > 1){
					var ElementCent = Element.getElementsByTagName("div");
					ElementCent = ElementCent[ElementCent.length - 2];
					
					if(!Forca){
						if(ElementCent != undefined){
							ADD = (ElementCent.getElementsByTagName("input")[0].value != "");
						}
					}
					
					document.formulario.bt_receber.value = "Estornar";
					if(ADD){
						var ItemName = ElementCent.getElementsByTagName("input")[0].name.substring(19);
						document.getElementById("tit_ContaReceber_"+ItemName).style.color = "#000";
						ElementCent.getElementsByTagName("input")[0].onfocus = function () {};
						ElementCent.getElementsByTagName("input")[0].readOnly = true;
						ElementCent.getElementsByTagName("input")[2].onfocus = function () {};
						ElementCent.getElementsByTagName("input")[2].readOnly = true;
						eval(ElementCent.getElementsByTagName("img")[0].onclick.toString().replace(/(true)/i, "false"));
						
						ElementCent.getElementsByTagName("img")[0].onclick = onclick;
						ElementCent.getElementsByTagName("img")[0].src = "../../img/estrutura_sistema/ico_lupa_c.gif";
					} else{
						if(ElementCent != undefined){
							ElementCent.getElementsByTagName("input")[0].focus();
						}
					}
				} else {
					ADD = undefined;
				}
				
				if(ADD || ADD == undefined){
					//document.formulario.bt_concluir.disabled = true;
					document.formulario.QTDItems.value = Item;
					document.formulario.ItemMax.value = ItemMax;
					
					var IdNome = "bl_Item_"+ItemMax;
					var DivLN = document.createElement("div");
					DivLN.setAttribute("id", IdNome);
					Element.appendChild(DivLN);
					Element = document.getElementById(IdNome);
					
					IdNome = "cp_tit";
					DivLN = document.createElement("div");
					DivLN.setAttribute("id", IdNome);
					DivLN.innerHTML = "<table style='margin:0;' cellpadding='0' cellspacing='0'><tr><td style='width:824px;' id='tit_bl_Item_"+ItemMax+"'>Item "+Item+"</td><td><span id='icoDelItem_"+ItemMax+"' onClick=\"del_item("+ItemMax+");\" style='cursor:pointer;'>[x]</span></td></tr></table>";
					Element.appendChild(DivLN);
					
					IdNome = "cp_TipoMovimentacao_0_"+ItemMax;
					DivLN = document.createElement("table");
					DivLN.setAttribute("id", IdNome);
					Element.appendChild(DivLN);
					var table = document.getElementById(IdNome);
					var linha = table.insertRow((table.rows.length)-1);
					var c0 = linha.insertCell(0);
					var c1 = linha.insertCell(1);
					var c2 = linha.insertCell(2);
					var c3 = linha.insertCell(3);
					var c4 = linha.insertCell(4);
					var c5 = linha.insertCell(5);
					var c6 = linha.insertCell(6);
					var c7 = linha.insertCell(7);
					var c8 = linha.insertCell(8);
					var c9 = linha.insertCell(9);
					
					c0.className = "find";
					c0.innerHTML = "<img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' id='icoBuscaContaReceber_"+ItemMax+"' onClick=\"selecionar_item("+ItemMax+"); vi_id('quadroBuscaContaReceber', true, event, null, 165);\" />";
					
					c1.className = "campo";
					c1.innerHTML = "<input type='text' name='IdContaReceberItem_"+ItemMax+"' value='' autocomplete='off' style='width:80px' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" onChange=\"selecionar_item("+ItemMax+"); busca_conta_receber(this.value,false,document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int');\" tabindex='"+(document.formulario.TabIndex.value++)+"' /><input type='hidden' name='IdContaReceberItemTemp_"+ItemMax+"' value='' />";
					
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					
					c3.className = "campo";
					c3.innerHTML = "<input type='text' name='NumeroDocumentoItem_"+ItemMax+"' value='' autocomplete='off' style='width:110px' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" onChange=\"selecionar_item("+ItemMax+"); busca_conta_receber(this.value,false,document.formulario.Local.value,'NumeroDocumento');\" onkeypress=\"mascara(this,event,'int');\" tabindex='"+(document.formulario.TabIndex.value++)+"' />";
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					
					c5.className = "campo";
					c5.innerHTML = "<input type='text' name='NomePessoaItem_"+ItemMax+"' value='' style='width:371px' readonly='readonly' />";
					
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					
					c7.className = "campo";
					c7.innerHTML = "<input type='text' name='DataVencimentoItem_"+ItemMax+"' value='' style='width:107px' readonly='readonly' />";
					
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					
					c9.className = "campo";
					c9.innerHTML = "<input type='text' name='DiaAtrasoItem_"+ItemMax+"' value='' style='width:80px' readonly='readonly' />";
					
					linha = table.insertRow((table.rows.length)-1);
					c0 = linha.insertCell(0);
					c1 = linha.insertCell(1);
					c2 = linha.insertCell(2);
					c3 = linha.insertCell(3);
					c4 = linha.insertCell(4);
					c5 = linha.insertCell(5);
					c6 = linha.insertCell(6);
					c7 = linha.insertCell(7);
					c8 = linha.insertCell(8);
					c9 = linha.insertCell(9);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					
					c1.className = "descCampo";
					c1.innerHTML = "<b id='tit_ContaReceber_"+ItemMax+"'>Conta Receber</b>";
					
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					
					c3.className = "descCampo";
					c3.innerHTML = "Número Documento";
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					
					c5.className = "descCampo";
					c5.setAttribute("id", "tit_Pessoa_"+ItemMax);
					c5.innerHTML = "Nome Pessoa";
					
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					
					c7.className = "descCampo";
					c7.innerHTML = "Data Vencimento";
					
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					
					c9.className = "descCampo";
					c9.innerHTML = "Dias Atraso";
					
					IdNome = "cp_TipoMovimentacao_1_"+ItemMax;
					DivLN = document.createElement("table");
					DivLN.setAttribute("id", IdNome);
					Element.appendChild(DivLN);
					table = document.getElementById(IdNome);
					linha = table.insertRow((table.rows.length)-1);
					c0 = linha.insertCell(0);
					c1 = linha.insertCell(1);
					c2 = linha.insertCell(2);
					c3 = linha.insertCell(3);
					c4 = linha.insertCell(4);
					c5 = linha.insertCell(5);
					c6 = linha.insertCell(6);
					c7 = linha.insertCell(7);
					c8 = linha.insertCell(8);
					c9 = linha.insertCell(9);
					c10 = linha.insertCell(10);
					c11 = linha.insertCell(11);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					
					c1.className = "campo";
					c1.innerHTML = "<input type='text' name='ValorItem_"+ItemMax+"' value='' style='width:149px' readonly='readonly' />";
					
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					
					c3.className = "campo";
					c3.innerHTML = "<input type='text' name='ValorMultaJurosItem_"+ItemMax+"' value='' style='width:150px' readonly='readonly' /> <input type='hidden' name='ValorMultaItem_"+ItemMax+"' value='' style='width:149px' readonly='readonly' /> <input type='hidden' name='ValorJurosItem_"+ItemMax+"' value='' style='width:150px' readonly='readonly' />";
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
															
					c5.className = "campo";
					c5.innerHTML = "<input type='text' name='ValorDescontoItem_"+ItemMax+"' value='' style='width:150px' onkeypress=\"mascara(this,event,'float');\" onkeydown=\"backspace(this,event);\" onChange=\"soma_total("+ItemMax+");\" onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" maxlength='14' tabindex='"+(document.formulario.TabIndex.value++)+"' />";
					
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					
					c7.className = "campo";
					c7.innerHTML = "<input type='text' name='ValorFinalItem_"+ItemMax+"' value='' style='width:150px' readonly='readonly' />";
				
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					
					c9.className = "campo";
					c9.innerHTML = "<select name='CalcularMultaJuros_"+ItemMax+"' style='width:150px' onchange='calcularMultaJuros(this.value,this.name)' disabled='disabled'><option value='1'>Sim</option><option value='2'>Não</option></select>";
					
					linha = table.insertRow((table.rows.length)-1);
					c0 = linha.insertCell(0);
					c1 = linha.insertCell(1);
					c2 = linha.insertCell(2);
					c3 = linha.insertCell(3);
					c4 = linha.insertCell(4);
					c5 = linha.insertCell(5);
					c6 = linha.insertCell(6);
					c7 = linha.insertCell(7);
					c8 = linha.insertCell(8);
					c9 = linha.insertCell(9);
					c10 = linha.insertCell(10);
					c11 = linha.insertCell(11);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					
					c1.className = "descCampo";
					c1.innerHTML = "Valor ("+document.formulario.Moeda.value+")";
					
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					
					c3.className = "descCampo";
					c3.innerHTML = "(+) Valor Multa/Juros ("+document.formulario.Moeda.value+")";
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					
					c5.className = "descCampo";
					c5.innerHTML = "<b style='color: #000' id='tit_ValorDesconto_"+ItemMax+"'>(-) Valor Desconto ("+document.formulario.Moeda.value+")</b>";
					
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					
					c7.className = "descCampo";
					c7.innerHTML = "(=) Valor Final ("+document.formulario.Moeda.value+")";
					
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					
					c9.className = "descCampo";
					c9.innerHTML = "Calc. Multa/Juros";
					
					eval("document.formulario.IdContaReceberItem_"+ItemMax+".focus();");
				}
				
				if(document.formulario.IdContaReceberItemTemp.value != ""){
					selecionar_item(ItemMax);
					busca_conta_receber(document.formulario.IdContaReceberItemTemp.value,false,document.formulario.Local.value);
					document.formulario.IdContaReceberItemTemp.value = "";
				}
				
				break;
		}
		
		document.formulario.TipoMovimentacao.value = IdTipoMovimentacao;
		document.formulario.TipoMovimentacaoTemp.value = IdTipoMovimentacao;
		
		return ItemMax;
	}
	function busca_conta_receber(IdContaReceber, Erro, Local, Coluna,IdStatus){
		
		if(IdContaReceber == ''){
			IdContaReceber = 0;
		}
		
		if(Coluna == undefined) {
			Coluna = "IdContaReceber"
		}
		
		if(IdStatus ==  undefined){
			IdStatus = "";
		}else{
			if(document.formulario.TipoMovimentacao.value == 4){
				IdStatus = 2;
			}else{
				IdStatus = "";
			}
		}
		
		var Item = document.formulario.ManipulaItem.value; url = "xml/conta_receber_caixa_movimentacao.php?"+Coluna+"="+IdContaReceber+"&ContasReceber="+document.formulario.ContasReceber.value+"&IdStatus="+IdStatus;
		
		call_ajax(url,function(xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false' || xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length > 1){
				switch(Local){
					case "Movimentacao":
						eval("var IdContaReceberItemTemp = document.formulario.IdContaReceberItemTemp_"+Item+".value;");
						eval("var ValorItem = parseFloat(document.formulario.ValorItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var ValorMultaItem = parseFloat(document.formulario.ValorMultaItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var ValorJurosItem = parseFloat(document.formulario.ValorJurosItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var ValorDescontoItem = parseFloat(document.formulario.ValorDescontoItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var ValorFinalItem = parseFloat(document.formulario.ValorFinalItem_"+Item+".value.replace(/,/g, '.'));");
						
						document.formulario.ContasReceber.value = document.formulario.ContasReceber.value.replace(new RegExp(","+IdContaReceberItemTemp+",","i"), ",");
						document.formulario.ContasReceber.value = document.formulario.ContasReceber.value.replace(new RegExp(","+IdContaReceberItemTemp+"$","i"), "");
						
						if(isNaN(ValorItem)){
							ValorItem = 0.00;
						}
						
						if(isNaN(ValorMultaItem)){
							ValorMultaItem = 0.00;
						}
						
						if(isNaN(ValorJurosItem)){
							ValorJurosItem = 0.00;
						}
						
						if(isNaN(ValorDescontoItem)){
							ValorDescontoItem = 0.00;
						}
						
						if(isNaN(ValorFinalItem)){
							ValorFinalItem = 0.00;
						}
						
						var TotalValor = parseFloat(document.formulario.TotalValor.value.replace(/,/g, ".")) - ValorItem;
						var TotalValorMulta = parseFloat(document.formulario.TotalValorMulta.value.replace(/,/g, ".")) - ValorMultaItem;
						var TotalValorMultaJuros = parseFloat(document.formulario.TotalValorMultaJuros.value.replace(/,/g, ".")) - (ValorMultaItem+ValorJurosItem);
						var TotalValorJuros = parseFloat(document.formulario.TotalValorJuros.value.replace(/,/g, ".")) - ValorJurosItem;
						var TotalValorDesconto = parseFloat(document.formulario.TotalValorDesconto.value.replace(/,/g, ".")) - ValorDescontoItem;
						var TotalValorFinal = parseFloat(document.formulario.TotalValorFinal.value.replace(/,/g, ".")) - ValorFinalItem;
						
						concluir(false);
						
						document.formulario.bt_concluir.disabled		= true;
						document.formulario.TotalValor.value			= formata_float(Arredonda(TotalValor, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorMulta.value		= formata_float(Arredonda(TotalValorMulta, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorMultaJuros.value	= formata_float(Arredonda(TotalValorMultaJuros, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorJuros.value		= formata_float(Arredonda(TotalValorJuros, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorDesconto.value	= formata_float(Arredonda(TotalValorDesconto, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorFinal.value		= formata_float(Arredonda(TotalValorFinal, 2), 2).replace(/\./g, ",");
						
						eval("document.formulario.IdContaReceberItem_"+Item+".value = '';");
						eval("document.formulario.IdContaReceberItemTemp_"+Item+".value = '';");
						eval("document.formulario.NumeroDocumentoItem_"+Item+".value = '';");
						eval("document.formulario.NomePessoaItem_"+Item+".value = '';");
						eval("document.formulario.DataVencimentoItem_"+Item+".value = '';");
						eval("document.formulario.ValorItem_"+Item+".value = '';");
						eval("document.formulario.ValorMultaItem_"+Item+".value = '';");
						eval("document.formulario.ValorJurosItem_"+Item+".value = '';");
						eval("document.formulario.ValorDescontoItem_"+Item+".value = '';");
						eval("document.formulario.ValorFinalItem_"+Item+".value = '';");
						eval("document.formulario.ValorMultaJurosItem_"+Item+".value = '';");
						
						if(document.formulario.TipoMovimentacao.value != 4){
							buscar_DescontoConceber(0,Item);
							alert("Alerta!\nConta a receber não encontrado ou já se encontra quitado.");
						}
						else
							alert("Alerta!\nConta a receber não encontrado ou não se encontra quitado.");
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdContaReceber = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NumeroDocumento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataVencimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DiaAtraso")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DiaAtraso = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Valor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMulta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMulta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorJuros")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorJuros = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CobrarMultaJurosProximaFatura")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CobrarMultaJurosProximaFatura = nameTextNode.nodeValue;
								
				switch(Local){
					case "Movimentacao":
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0];
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0];
						nameTextNode = nameNode.childNodes[0];
						var RazaoSocial = nameTextNode.nodeValue;
						
						if(Nome != ""){
							var NomePessoa = Nome;
							document.getElementById("tit_Pessoa_"+Item).innerHTML = "Nome Pessoa";
						}
						
						if(RazaoSocial != ""){
							var NomePessoa = RazaoSocial;
							document.getElementById("tit_Pessoa_"+Item).innerHTML = "Razão Social";
						}
						
						if(CobrarMultaJurosProximaFatura == 1 || document.formulario.TipoMovimentacao.value == 4){
							ValorMulta = "0,00";
							ValorJuros = "0,00";
						}
						
						Valor 		= Valor.replace(".","");
						
						Valor = Arredonda(parseFloat(Valor.replace(/,/g, ".")), 2);
						ValorMulta = Arredonda(parseFloat(ValorMulta.replace(/,/g, ".")), 2);
						ValorJuros = Arredonda(parseFloat(ValorJuros.replace(/,/g, ".")), 2);
						
						if(isNaN(Valor)){
							Valor = 0.00;
						}
						
						if(isNaN(ValorMulta)){
							ValorMulta = 0.00;
						}
						
						if(isNaN(ValorJuros)){
							ValorJuros = 0.00;
						}
												
						eval("document.formulario.ValorDescontoItem_"+Item+".value = '0,00';");
						
						var ValorFinal = (Valor + ValorMulta + ValorJuros);
						
						if(document.formulario.TipoMovimentacao.value == 4){
							ValorFinal *= -1;
						}
						
						if(isNaN(ValorFinal)){
							ValorFinal = 0.00;
						}
						
						eval("var ValorTemp = parseFloat(document.formulario.ValorItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var ValorMultaTemp = parseFloat(document.formulario.ValorMultaItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var ValorJurosTemp = parseFloat(document.formulario.ValorJurosItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var ValorMultaJurosTemp = parseFloat(document.formulario.ValorMultaJurosItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var ValorDescontoTemp = parseFloat(document.formulario.ValorDescontoItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var ValorFinalTemp = parseFloat(document.formulario.ValorFinalItem_"+Item+".value.replace(/,/g, '.'));");
						eval("var IdContaReceberItemTemp = document.formulario.IdContaReceberItemTemp_"+Item+".value;");
						
						document.formulario.ContasReceber.value = document.formulario.ContasReceber.value.replace(new RegExp(","+IdContaReceberItemTemp+",","i"), ",");
						document.formulario.ContasReceber.value = document.formulario.ContasReceber.value.replace(new RegExp(","+IdContaReceberItemTemp+"$","i"), "");
						
						if(isNaN(ValorTemp)){
							ValorTemp = 0.00;
						}
						
						if(isNaN(ValorMultaTemp)){
							ValorMultaTemp = 0.00;
						}
						
						if(isNaN(ValorJurosTemp)){
							ValorJurosTemp = 0.00;
						}
						
						if(isNaN(ValorDescontoTemp)){
							ValorDescontoTemp = 0.00;
						}
						
						if(isNaN(ValorFinalTemp)){
							ValorFinalTemp = 0.00;
						}
						
						var TotalValor			 = Arredonda((parseFloat(document.formulario.TotalValor.value.replace(/,/g, ".")) - ValorTemp) + Valor,2);
						var TotalValorMulta		 = Arredonda((parseFloat(document.formulario.TotalValorMulta.value.replace(/,/g, ".")) - ValorMultaTemp) + ValorMulta,2);
						var TotalValorMultaJuros = Arredonda((parseFloat(document.formulario.TotalValorMultaJuros.value.replace(/,/g, ".")) - (ValorMultaTemp+ValorJurosTemp)) + (ValorMulta+ValorJuros),2);
						var TotalValorJuros		 = Arredonda((parseFloat(document.formulario.TotalValorJuros.value.replace(/,/g, ".")) - ValorJurosTemp) + ValorJuros,2);
						var TotalValorDesconto	 = parseFloat(document.formulario.TotalValorDesconto.value.replace(/,/g, ".") - ValorDescontoTemp);
						var TotalValorFinal		 = Arredonda((parseFloat(document.formulario.TotalValorFinal.value.replace(/,/g, ".")) - ValorFinalTemp) + ValorFinal,2);
						
						eval("var ValorDescontoTemp = parseFloat(document.formulario.ValorDescontoItem_"+Item+".value.replace(/,/g, '.'));");
						
						if(isNaN(ValorDescontoTemp)){
							ValorDescontoTemp = 0.00;
						}
						
						concluir(false);
						
						if(TotalValorFinal > 0.00){
							document.formulario.bt_concluir.disabled = false;
						} else{
							if(document.formulario.TipoMovimentacao.value == 4 && TotalValorFinal < 0.00){
								document.formulario.bt_concluir.disabled = false;
								eval("document.formulario.ValorDescontoItem_"+Item+".readOnly = true;");
							}	
							else{
								document.formulario.bt_concluir.disabled = true;
								eval("document.formulario.ValorDescontoItem_"+Item+".readOnly = false;");
							}
						}
						
						document.formulario.TotalValor.value			= formata_float(Arredonda(TotalValor, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorMulta.value		= formata_float(Arredonda(TotalValorMulta, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorJuros.value		= formata_float(Arredonda(TotalValorJuros, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorMultaJuros.value		= formata_float(Arredonda(TotalValorMultaJuros, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorDesconto.value	= formata_float(Arredonda(TotalValorDesconto, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorFinal.value		= formata_float(Arredonda(TotalValorFinal, 2), 2).replace(/\./g, ",");
						document.formulario.ContasReceber.value			+= ","+IdContaReceber;
						
						eval("document.formulario.IdContaReceberItem_"+Item+".value = '"+IdContaReceber+"';");
						eval("document.formulario.IdContaReceberItemTemp_"+Item+".value = '"+IdContaReceber+"';");
						eval("document.formulario.NumeroDocumentoItem_"+Item+".value = '"+NumeroDocumento+"';");
						eval("document.formulario.NomePessoaItem_"+Item+".value = '"+NomePessoa+"';");
						eval("document.formulario.DataVencimentoItem_"+Item+".value = '"+dateFormat(DataVencimento)+"';");
						eval("document.formulario.DiaAtrasoItem_"+Item+".value = '"+DiaAtraso+"';");
						eval("document.formulario.ValorItem_"+Item+".value = '"+formata_float(Arredonda(Valor, 2), 2).replace(/\./g, ",")+"';");
						
						eval("document.formulario.ValorJurosItem_"+Item+".value = '"+formata_float(Arredonda(ValorJuros, 2), 2).replace(/\./g, ",")+"';");
						eval("document.formulario.ValorMultaItem_"+Item+".value = '"+formata_float(Arredonda(ValorMulta, 2), 2).replace(/\./g, ",")+"';");
						eval("document.formulario.ValorFinalItem_"+Item+".value = '"+formata_float(Arredonda(ValorFinal, 2), 2).replace(/\./g, ",")+"';");
						eval("document.formulario.ValorMultaJurosItem_"+Item+".value = '"+formata_float(Arredonda((ValorMulta+ValorJuros), 2), 2).replace(/\./g, ",")+"';");
						
						if(document.formulario.TipoMovimentacao.value != 4){
							buscar_DescontoConceber(IdContaReceber,Item);
						}
						
						break;
				}
			}
			
			if(document.getElementById("quadroBuscaContaReceber") != null){
				if(document.getElementById("quadroBuscaContaReceber").style.display	==	"block"){
					document.getElementById("quadroBuscaContaReceber").style.display = "none";
				}
			}
		});
	}
	function limpa_forma_pagamento(){
		var element = document.getElementById("bl_FormaPagamento");
		element.innerHTML = "";
		
		return element;
	}
	function busca_forma_pagamento(IdCaixa){
		if(IdCaixa == "" || IdCaixa == undefined){
			IdCaixa = document.formulario.IdCaixa.value;
		}
		
		var url = "xml/caixa_forma_pagamento.php?IdCaixa="+IdCaixa;
		
		call_ajax(url,function (xmlhttp){
			var element = limpa_forma_pagamento();
			
			if(xmlhttp.responseText != 'false'){
				var DivLN = document.createElement("div");
				DivLN.setAttribute("id", "cp_tit");
				DivLN.innerHTML = "Forma de Pagamento";
				element.appendChild(DivLN);
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdFormaPagamento").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdFormaPagamento")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdFormaPagamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFormaPagamento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoFormaPagamento = nameTextNode.nodeValue;
					var responseXMLParcela = xmlhttp.responseXML.getElementsByTagName("Parcela")[i];
					var CampoQtdParcela = "<select name='FormaPagamentoQtdParcela_"+IdFormaPagamento+"' style='width:79px' onChange=\"calcular_forma_pagamento('"+IdFormaPagamento+"');\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" tabindex='"+(parseFloat(document.formulario.TabIndex.value)+2)+"'>";
					
					for(var ii = 0; ii < responseXMLParcela.getElementsByTagName("QtdParcela").length; ii++){
						nameNode = responseXMLParcela.getElementsByTagName("QtdParcela")[ii]; 
						nameTextNode = nameNode.childNodes[0];
						var QtdParcela = nameTextNode.nodeValue;
						
						nameNode = responseXMLParcela.getElementsByTagName("PercentualJurosMes")[ii]; 
						nameTextNode = nameNode.childNodes[0];
						var PercentualJurosMes = nameTextNode.nodeValue;
						
						CampoQtdParcela += "<option value='"+PercentualJurosMes+"_"+QtdParcela+"'>"+QtdParcela+"</option>";
					}
					
					CampoQtdParcela += "</select>";
					
					var IdNome = "cp_FormaPagamento_"+IdFormaPagamento;
					DivLN = document.createElement("table");
					DivLN.setAttribute("id", IdNome);
					element.appendChild(DivLN);
					var table = document.getElementById(IdNome);
					var linha = table.insertRow((table.rows.length)-1);
					var c0 = linha.insertCell(0);
					var c1 = linha.insertCell(1);
					var c2 = linha.insertCell(2);
					var c3 = linha.insertCell(3);
					var c4 = linha.insertCell(4);
					var c5 = linha.insertCell(5);
					var c6 = linha.insertCell(6);
					var c7 = linha.insertCell(7);
					var c8 = linha.insertCell(8);
					var c9 = linha.insertCell(9);
					var c10 = linha.insertCell(10);
					var c11 = linha.insertCell(11);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					c1.className = "campo";
					c1.innerHTML = "<input type='text' name='FormaPagamentoDescricaoFormaPagamento_"+IdFormaPagamento+"' value='"+DescricaoFormaPagamento+"' style='width:196px' readonly='readonly' />";
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					c3.className = "campo";
					c3.innerHTML = "<input type='text' name='FormaPagamentoValor_"+IdFormaPagamento+"' value='0,00' style='width:121px' onkeypress=\"mascara(this,event,'float');\" onkeydown=\"backspace(this,event);\"  onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" onchange=\"calcular_forma_pagamento('"+IdFormaPagamento+"');\" maxlength='14' tabindex='"+(document.formulario.TabIndex.value++)+"' />";
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					c5.className = "campo";
					c5.innerHTML = CampoQtdParcela;
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					c7.className = "campo";
					c7.innerHTML = "<input type='text' name='FormaPagamentoValorParcela_"+IdFormaPagamento+"' value='0,00' style='width:121px' readonly='readonly' />";
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					c9.className = "campo";
					c9.innerHTML = "<input type='text' name='FormaPagamentoJurosMes_"+IdFormaPagamento+"' value='0,00' style='width:99px' readonly='readonly' />";
					c10.className = "separador";
					c10.innerHTML = "&nbsp;";
					c11.className = "campo";
					c11.innerHTML = "<input type='text' name='FormaPagamentoValorTotal_"+IdFormaPagamento+"' value='0,00' style='width:121px' readonly='readonly' />";
					
					document.formulario.TabIndex.value++;
					
					linha = table.insertRow((table.rows.length)-1);
					c0 = linha.insertCell(0);
					c1 = linha.insertCell(1);
					c2 = linha.insertCell(2);
					c3 = linha.insertCell(3);
					c4 = linha.insertCell(4);
					c5 = linha.insertCell(5);
					c6 = linha.insertCell(6);
					c7 = linha.insertCell(7);
					c8 = linha.insertCell(8);
					c9 = linha.insertCell(9);
					c10 = linha.insertCell(10);
					c11 = linha.insertCell(11);
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					c1.className = "descCampo";
					c1.innerHTML = "Nome Forma de Pagamento";
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					c3.className = "descCampo";
					c3.innerHTML = "<b id='tit_FormaPagamentoValor_"+IdFormaPagamento+"'>(+) Valor ("+document.formulario.Moeda.value+")</b>";
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					c5.className = "descCampo";
					c5.innerHTML = "<b id='tit_FormaPagamentoQtdParcelas_"+IdFormaPagamento+"' style='color:#000;'>Qtd. Parcelas</b>";
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					c7.className = "descCampo";
					c7.innerHTML = "(+) Valor Parcela ("+document.formulario.Moeda.value+")";
					c8.className = "separador";
					c8.innerHTML = "&nbsp;";
					c9.className = "descCampo";
					c9.innerHTML = "(+) Juros Mês ("+document.formulario.Moeda.value+")";
					c10.className = "separador";
					c10.innerHTML = "&nbsp;";
					c11.className = "descCampo";
					c11.innerHTML = "(=) Valor Total ("+document.formulario.Moeda.value+")";
				}
				
				var FaltaFormaPagamentoValorTemp = parseFloat(document.formulario.TotalValorFinal.value.replace(/,/g, "."));
				
				if(isNaN(FaltaFormaPagamentoValorTemp)){
					FaltaFormaPagamentoValorTemp = 0.00;
				}
				
				FaltaFormaPagamentoValorTemp *= -1;
				var FaltaFormaPagamentoValor = formata_float(Arredonda(FaltaFormaPagamentoValorTemp, 2), 2).replace(/\./g, ",")
				
				if(FaltaFormaPagamentoValorTemp > 0.00){
					FaltaFormaPagamentoValor = "+"+FaltaFormaPagamentoValor;
					FaltaFormaPagamentoValorColor = "#000";
				} else{
					FaltaFormaPagamentoValorColor = "#c10000";
				}
				
				FaltaFormaPagamentoValor = document.formulario.Moeda.value+" "+FaltaFormaPagamentoValor;
				IdNome = "cp_FormaPagamentoTotal";
				DivLN = document.createElement("table");
				DivLN.setAttribute("id", IdNome);
				element.appendChild(DivLN);
				table = document.getElementById(IdNome);
				linha = table.insertRow((table.rows.length)-1);
				c0 = linha.insertCell(0);
				c1 = linha.insertCell(1);
				c2 = linha.insertCell(2);
				c3 = linha.insertCell(3);
				c4 = linha.insertCell(4);
				c5 = linha.insertCell(5);
				c6 = linha.insertCell(6);
				c7 = linha.insertCell(7);
				
				c0.className = "find";
				c0.innerHTML = "&nbsp;";
				c1.className = "campo";
				c1.style.width = "202px";
				c2.className = "separador";
				c2.innerHTML = "&nbsp;";
				c3.className = "campo";
				c3.innerHTML = "<input type='text' name='TotalFormaPagamentoValor' value='0,00' style='width:121px' maxlength='14' readonly='readonly' /><div><span id='FaltaFormaPagamentoValor' style='color:"+FaltaFormaPagamentoValorColor+";'>"+FaltaFormaPagamentoValor+"</span></div>";
				c4.className = "separador";
				c4.innerHTML = "&nbsp;";
				c5.className = "campo";
				c5.style.width = "333px";
				c6.className = "separador";
				c6.innerHTML = "&nbsp;";
				c7.className = "campo";
				c7.style.verticalAlign = "top";
				c7.innerHTML = "<input type='text' name='TotalFormaPagamentoValorTotal' value='0,00' style='width:121px' readonly='readonly' />";
				
				linha = table.insertRow((table.rows.length)-1);
				c0 = linha.insertCell(0);
				c1 = linha.insertCell(1);
				c2 = linha.insertCell(2);
				c3 = linha.insertCell(3);
				c4 = linha.insertCell(4);
				c5 = linha.insertCell(5);
				c6 = linha.insertCell(6);
				c7 = linha.insertCell(7);
				
				c0.className = "find";
				c0.innerHTML = "&nbsp;";
				c1.className = "descCampo";
				c2.className = "separador";
				c2.innerHTML = "&nbsp;";
				c3.className = "descCampo";
				c3.innerHTML = "(=) Valor ("+document.formulario.Moeda.value+")";
				c4.className = "separador";
				c4.innerHTML = "&nbsp;";
				c5.className = "descCampo";
				c6.className = "separador";
				c6.innerHTML = "&nbsp;";
				c7.className = "descCampo";
				c7.innerHTML = "(=) Valor Total ("+document.formulario.Moeda.value+")";
				
				if(document.formulario.FormaPagamentoValor_1 != undefined){
					document.formulario.FormaPagamentoValor_1.focus();
				}
			}
		});
	}
	function calcular_forma_pagamento(IdFormaPagamento){
		eval("var CampoFormaPagamentoValor = document.formulario.FormaPagamentoValor_"+IdFormaPagamento+";");
		eval("var CampoFormaPagamentoQtdParcela = document.formulario.FormaPagamentoQtdParcela_"+IdFormaPagamento+";");
		eval("var CampoFormaPagamentoJurosMes = document.formulario.FormaPagamentoJurosMes_"+IdFormaPagamento+";");
		eval("var CampoFormaPagamentoValorParcela = document.formulario.FormaPagamentoValorParcela_"+IdFormaPagamento+";");
		eval("var CampoFormaPagamentoValorTotal = document.formulario.FormaPagamentoValorTotal_"+IdFormaPagamento+";");
		
		var Temp = CampoFormaPagamentoQtdParcela.value.split("_");
		var FormaPagamentoQtdParcelaJuros = Temp[0];
		var FormaPagamentoQtdParcela = parseFloat(Temp[1]);
		
		if(isNaN(FormaPagamentoQtdParcela)){
			FormaPagamentoQtdParcela = 0;
		}
		
		var FormaPagamentoValor = parseFloat(CampoFormaPagamentoValor.value.replace(/,/g, "."));
						
		if(isNaN(FormaPagamentoValor)){
			FormaPagamentoValor = 0.00;
		}
		
		if(FormaPagamentoValor > 0.00){
			document.getElementById("tit_FormaPagamentoQtdParcelas_"+IdFormaPagamento).style.color = "#c10000";
		} else{
			document.getElementById("tit_FormaPagamentoQtdParcelas_"+IdFormaPagamento).style.color = "#000";
			CampoFormaPagamentoQtdParcela.value = "";
		}
		
		var FormaPagamentoJurosMes = parseFloat(FormaPagamentoQtdParcelaJuros.replace(/,/g, "."));
		
		if(isNaN(FormaPagamentoJurosMes)){
			FormaPagamentoJurosMes = 0.00;
		}
		
		FormaPagamentoJurosMes = ((FormaPagamentoValor * FormaPagamentoJurosMes) / 100);
		
		var FormaPagamentoValorParcela = (FormaPagamentoValor / FormaPagamentoQtdParcela);
		
		if(FormaPagamentoValorParcela === Infinity || isNaN(FormaPagamentoValorParcela)){
			FormaPagamentoValorParcela = 0.00;
		}
		
		var FormaPagamentoValorTotal = ((FormaPagamentoValorParcela + FormaPagamentoJurosMes) * FormaPagamentoQtdParcela);
		
		if(isNaN(FormaPagamentoValorTotal)){
			FormaPagamentoValorTotal = 0.00;
		}
		
		if(document.formulario.TipoMovimentacao.value == 4){
			FormaPagamentoJurosMes *= -1;
			FormaPagamentoValorParcela *= -1;
			FormaPagamentoValorTotal *= -1;
		}
		
		CampoFormaPagamentoJurosMes.value = formata_float(Arredonda(FormaPagamentoJurosMes, 2), 2).replace(/\./g, ",");
		CampoFormaPagamentoValorParcela.value = formata_float(Arredonda(FormaPagamentoValorParcela, 2), 2).replace(/\./g, ",");
		CampoFormaPagamentoValorTotal.value = formata_float(Arredonda(FormaPagamentoValorTotal, 2), 2).replace(/\./g, ",");
		
		total_forma_pagamento();
	}
	function total_forma_pagamento(){
		var TotalFormaPagamentoValor = 0.00;
		var TotalFormaPagamentoValorTotal = 0.00;
		
		for(var i = 0; i < document.formulario.elements.length; i++){
			if(document.formulario.elements[i].name.substring(0,20) == "FormaPagamentoValor_"){
				var FormaPagamentoValor = parseFloat(document.formulario.elements[i].value.replace(/,/g, "."));
				
				if(isNaN(FormaPagamentoValor)){
					FormaPagamentoValor = 0.00;
				}
				
				if(document.formulario.TipoMovimentacao.value != 4){
					TotalFormaPagamentoValor += FormaPagamentoValor;
				}else{
					if(FormaPagamentoValor < 0)
						TotalFormaPagamentoValor += FormaPagamentoValor;
					else
						TotalFormaPagamentoValor -= FormaPagamentoValor;
				}
			}
			
			if(document.formulario.elements[i].name.substring(0,25) == "FormaPagamentoValorTotal_"){
				var FormaPagamentoValorTotal = parseFloat(document.formulario.elements[i].value.replace(/,/g, "."));
				
				if(isNaN(FormaPagamentoValorTotal)){
					FormaPagamentoValorTotal = 0.00;
				}
				
				TotalFormaPagamentoValorTotal += FormaPagamentoValorTotal;
			}
		}
		
		document.formulario.TotalFormaPagamentoValor.value = formata_float(Arredonda(TotalFormaPagamentoValor, 2), 2).replace(/\./g, ",");
		document.formulario.TotalFormaPagamentoValorTotal.value = formata_float(Arredonda(TotalFormaPagamentoValorTotal, 2), 2).replace(/\./g, ",");
		var TotalValorFinal = parseFloat(document.formulario.TotalValorFinal.value.replace(/,/g, "."));
		
		if(isNaN(TotalValorFinal)){
			TotalValorFinal = 0.00;
		}
		
		var FaltaFormaPagamentoValorTemp = TotalFormaPagamentoValor - TotalValorFinal;
		
		if(isNaN(FaltaFormaPagamentoValorTemp)){
			FaltaFormaPagamentoValorTemp = 0.00;
		}
		
		var FaltaFormaPagamentoValor = formata_float(Arredonda(FaltaFormaPagamentoValorTemp, 2), 2).replace(/\./g, ",")
		
		if(FaltaFormaPagamentoValorTemp > 0.00){
			FaltaFormaPagamentoValor = "+"+FaltaFormaPagamentoValor;
			document.getElementById("FaltaFormaPagamentoValor").style.color = "#000";
		} else{
			document.getElementById("FaltaFormaPagamentoValor").style.color = "#c10000";
		}
		
		if(FaltaFormaPagamentoValorTemp == 0.00){
			document.getElementById("FaltaFormaPagamentoValor").style.color = "#000";
			FaltaFormaPagamentoValor = "&nbsp;";
		} else{
			FaltaFormaPagamentoValor = document.formulario.Moeda.value+" "+FaltaFormaPagamentoValor;
		}
		
		document.getElementById("FaltaFormaPagamentoValor").innerHTML = FaltaFormaPagamentoValor;
	}
	function concluir(Concluir){
		document.formulario.ObsHistorico.value = "";
		document.getElementById("tit_Observacao").style.color = "#000";
		var ElementCent = document.getElementById("bl_Itens").getElementsByTagName("div");
		
		if(Concluir){
			document.getElementById("tit_TipoMovimentacao").style.color = "#000";
			document.formulario.TipoMovimentacao.onfocus = function () {};
			document.formulario.TipoMovimentacao.disabled = true;
			
			for(var i = 0; i < ElementCent.length; i++) {
				// NÃO DEIXAR VISUALIZAR O QUADRO DE BUSCA
				if(ElementCent[i].getElementsByTagName("img")[0] != undefined){
					//setTimeout("alert(document.getElementById('bl_Itens').getElementsByTagName('div')["+i+"].getElementsByTagName('img')[0].onclick);", 1000);
					
					if(ElementCent[i].getElementsByTagName("img")[0].onclick != null){
						eval(ElementCent[i].getElementsByTagName("img")[0].onclick.toString().replace(/(true)/i, "false"));
					
						ElementCent[i].getElementsByTagName("img")[0].onclick = onclick;
						ElementCent[i].getElementsByTagName("img")[0].src = "../../img/estrutura_sistema/ico_lupa_c.gif";
					}
				}
				// DESABILITANDO OS CAMPO
				if(ElementCent[i].getElementsByTagName("input")[0] != undefined){
					var IdItem = ElementCent[i].getElementsByTagName("input")[0].name.replace(/(^[^\d]*)/, "");
					document.getElementById("tit_ContaReceber_"+IdItem).style.color = "#000";
					document.getElementById("tit_ValorDesconto_"+IdItem).style.color = "#000";
				}
				
				for(var ii = 0; ii < ElementCent[i].getElementsByTagName("input").length; ii++){
					if((/^(IdContaReceberItem_|NumeroDocumentoItem_|ValorDescontoItem_)([\d]+)$/).test(ElementCent[i].getElementsByTagName("input")[ii].name)){
						ElementCent[i].getElementsByTagName("input")[ii].readOnly = true;
						ElementCent[i].getElementsByTagName("input")[ii].onfocus = function () {};
					}
				}
			}
			
			document.formulario.bt_concluir.value = "Voltar";
			document.formulario.bt_item.style.display = "none";
			document.formulario.bt_receber.style.display = "inline";
			document.getElementById("bl_FormaPagamento").style.display = "block";
			document.getElementById("bl_Observacao").style.display = "block";
			
			busca_forma_pagamento();
			
			document.formulario.bt_concluir.onclick = function (){
				concluir(false);
			};
		} else{
			document.getElementById("tit_TipoMovimentacao").style.color = "#c10000";
			document.formulario.TipoMovimentacao.onfocus = function () {};
			document.formulario.TipoMovimentacao.disabled = false;
			
			var i = (ElementCent.length-2);
			// DEIXAR VISUALIZAR O QUADRO DE BUSCA
			if(ElementCent[i].getElementsByTagName("img")[0] != undefined){
				if(ElementCent[i].getElementsByTagName("img")[0].onclick != null){
					eval(ElementCent[i].getElementsByTagName("img")[0].onclick.toString().replace(/(false)/i, "true"));
					
					ElementCent[i].getElementsByTagName("img")[0].onclick = onclick;
					ElementCent[i].getElementsByTagName("img")[0].src = "../../img/estrutura_sistema/ico_lupa.gif";
				}
			}
			// HABILITANDO OS CAMPO
			if(ElementCent[i].getElementsByTagName("input")[0] != undefined){
				var IdItem = ElementCent[i].getElementsByTagName("input")[0].name.replace(/(^[^\d]*)/, "");
				document.getElementById("tit_ContaReceber_"+IdItem).style.color = "#c10000";
				if(document.formulario.TipoMovimentacao.value != 4)
					document.getElementById("tit_ValorDesconto_"+IdItem).style.color = "#c10000";
				else
					document.getElementById("tit_ValorDesconto_"+IdItem).style.color = "#000";
			}
			
			for(var ii = 0; ii < ElementCent[i].getElementsByTagName("input").length; ii++){
				if((/^(IdContaReceberItem_|NumeroDocumentoItem_|ValorDescontoItem_)([\d]+)$/).test(ElementCent[i].getElementsByTagName("input")[ii].name)){
					ElementCent[i].getElementsByTagName("input")[ii].readOnly = false;
					ElementCent[i].getElementsByTagName("input")[ii].onfocus = function () { Foco(this, "in"); };
				}
			}
			
			for(i = 0; i < (ElementCent.length-2); i++) {
				// HABILITANDO OS CAMPO
				if(ElementCent[i].getElementsByTagName("input")[0] != undefined){
					var IdItem = ElementCent[i].getElementsByTagName("input")[0].name.replace(/(^[^\d]*)/, "");
					document.getElementById("tit_ValorDesconto_"+IdItem).style.color = "#c10000";
				}
				
				for(var ii = 0; ii < ElementCent[i].getElementsByTagName("input").length; ii++){
					if((/^(ValorDescontoItem_)([\d]+)$/).test(ElementCent[i].getElementsByTagName("input")[ii].name)){
						ElementCent[i].getElementsByTagName("input")[ii].readOnly = false;
						ElementCent[i].getElementsByTagName("input")[ii].onfocus = function () { Foco(this, "in"); };
					}
				}
			}
			
			document.formulario.bt_concluir.value = "Concluir";
			document.formulario.bt_item.style.display = "inline";
			document.formulario.bt_receber.style.display = "none";
			document.getElementById("bl_FormaPagamento").style.display = "none";
			document.getElementById("bl_Observacao").style.display = "none";
			
			limpa_forma_pagamento();
			
			document.formulario.bt_concluir.onclick = function (){
				concluir(true);
			};
		}
	}
	function buscar_status(IdStatus){
		if(IdStatus == undefined){
			IdStatus = 100;
		}
		
		var url = "xml/caixa_movimentacao_status.php?IdStatus="+IdStatus;
		
		call_ajax(url,function (xmlhttp) {
			if(xmlhttp.responseText != "false"){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Status = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("CorStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var CorStatus = nameTextNode.nodeValue;
				
				document.getElementById("tit_Status").innerHTML = "<b style='color:"+CorStatus+";'>"+Status+"</b>";
			} else {
				document.getElementById("tit_Status").innerHTML = "";
			}
		});
	}
	function remove_fechar(IdStatus,ItemMax){
		for(var i=1; i<=ItemMax; i++){
			if(document.getElementById("icoDelItem_"+i) != undefined){
				if(IdStatus != 200 && IdStatus != 0){
					document.getElementById("icoDelItem_"+i).innerHTML = '[x]';
				}else{
					document.getElementById("icoDelItem_"+i).innerHTML = '';
				}
			}
		}
	}
	
	function zera_ResultadoTotal(){
		TotalValor = 0.00;
		TotalValorMulta = 0.00;
		TotalValorJuros = 0.00;
		TotalValorDesconto = 0.00;
		TotalValorFinal = 0.00;
		
		document.formulario.TotalValor.value = formata_float(Arredonda(TotalValor, 2), 2).replace(/\./g, ",");
		document.formulario.TotalValorMulta.value = formata_float(Arredonda(TotalValorMulta, 2), 2).replace(/\./g, ",");
		document.formulario.TotalValorJuros.value = formata_float(Arredonda(TotalValorJuros, 2), 2).replace(/\./g, ",");
		document.formulario.TotalValorDesconto.value = formata_float(Arredonda(TotalValorDesconto, 2), 2).replace(/\./g, ",");
		document.formulario.TotalValorFinal.value = formata_float(Arredonda(TotalValorFinal, 2), 2).replace(/\./g, ",");
	}
	
	function calcularMultaJuros(Valor,Item){
		var IndexItem = Item.replace("CalcularMultaJuros_","");
		switch(Valor){
			case '1':
				var Coluna = "IdContaReceber";
				eval("var IdContaReceber = document.formulario.IdContaReceberItem_"+IndexItem+".value");
				if(Coluna == undefined){
					Coluna = 'IdContaReceber';
				}
				url = "xml/conta_receber_caixa_movimentacao.php?"+Coluna+"="+IdContaReceber+"&IdStatus=";
		
				call_ajax(url,function(xmlhttp){
					if(xmlhttp.responseText == 'false' || xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length > 1){
						
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMulta")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorMulta = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorJuros")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorJuros = nameTextNode.nodeValue;
						
						ValorMulta = Arredonda(parseFloat(ValorMulta.replace(/,/g, ".")), 2);
						ValorJuros = Arredonda(parseFloat(ValorJuros.replace(/,/g, ".")), 2);
						eval("var ValorFinalItem = parseFloat(document.formulario.ValorFinalItem_"+IndexItem+".value.replace(/,/g, '.'));");
						var MultaJuros = Number(ValorJuros)+Number(ValorMulta);
						
						eval("document.formulario.ValorJurosItem_"+IndexItem+".value = '"+formata_float(Arredonda(ValorJuros, 2), 2).replace(/\./g, ",")+"';");
						eval("document.formulario.ValorMultaItem_"+IndexItem+".value = '"+formata_float(Arredonda(ValorMulta, 2), 2).replace(/\./g, ",")+"';");
						eval("document.formulario.ValorMultaJurosItem_"+IndexItem+".value='"+formata_float(Arredonda((ValorMulta+ValorJuros), 2), 2).replace(/\./g, ",")+"';");
						eval("document.formulario.ValorFinalItem_"+IndexItem+".value = '"+formata_float(Arredonda(MultaJuros+ValorFinalItem, 2), 2).replace(/\./g, ",")+"';");
						
						var TotalValorMulta = Number(document.formulario.TotalValorMulta.value.replace(/,/g,"."));
						var TotalValorJuros = Number(document.formulario.TotalValorJuros.value.replace(/,/g,"."));
						var TotalValorFinal = Number(document.formulario.TotalValorFinal.value.replace(/,/g,"."));
						
						document.formulario.TotalValorMulta.value = formata_float(Arredonda(TotalValorMulta+ValorMulta, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorJuros.value = formata_float(Arredonda(TotalValorJuros+ValorJuros, 2), 2).replace(/\./g, ",");
						document.formulario.TotalValorFinal.value = formata_float(Arredonda(TotalValorFinal+MultaJuros, 2), 2).replace(/\./g, ",");
						
					}
				});		
				break;
			case '2':
				eval("var Multa = document.formulario.ValorMultaItem_"+IndexItem+".value;");
				eval("var Juros = document.formulario.ValorJurosItem_"+IndexItem+".value;");
				
				var TotalValorMulta = Number(document.formulario.TotalValorMulta.value.replace(/,/g,"."));
				var TotalValorJuros = Number(document.formulario.TotalValorJuros.value.replace(/,/g,"."));
				var TotalValorFinal = Number(document.formulario.TotalValorFinal.value.replace(/,/g,"."));
				
				Multa = Multa.replace(",",".");
				Juros = Juros.replace(",",".");
				var MultaJuros = Number(Juros)+Number(Multa);
				document.formulario.TotalValorMulta.value = formata_float(Arredonda(TotalValorMulta-Multa, 2), 2).replace(/\./g, ",");
				document.formulario.TotalValorJuros.value = formata_float(Arredonda(TotalValorJuros-Juros, 2), 2).replace(/\./g, ",");
				document.formulario.TotalValorFinal.value = formata_float(Arredonda(TotalValorFinal-MultaJuros, 2), 2).replace(/\./g, ",");
				
				
				eval("document.formulario.ValorMultaItem_"+IndexItem+".value='0,00';");
				eval("document.formulario.ValorMultaJurosItem_"+IndexItem+".value='0,00';");
				eval("document.formulario.ValorJurosItem_"+IndexItem+".value='0,00';");
				eval("document.formulario.ValorFinalItem_"+IndexItem+".value= document.formulario.ValorItem_"+IndexItem+".value;");
				
				break;
		}
	}
	
	function buscar_DescontoConceber(IdContaReceber,Item){
		var Coluna = 'IdContaReceber';
		if(IdContaReceber == ""){
			IdContaReceber = 0;
		}
		if(Coluna == undefined){
			Coluna = 'IdContaReceber';
		}
		url = "xml/conta_receber_caixa_movimentacao.php?"+Coluna+"="+IdContaReceber+"&IdStatus=";
		call_ajax(url,function(xmlhttp){
			if(xmlhttp.responseText == 'false' || xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length > 1){
				eval("document.formulario.DescontoConceberItem_"+Item+".value = ''");
				eval("document.getElementById('LimiteItem_"+Item+"').innerHTML = ''");
			}else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataLimiteDesconto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataLimiteDesconto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoAConceber")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorDescontoAConceber = nameTextNode.nodeValue;
				
				eval("document.formulario.DescontoConceberItem_"+Item+".value = '"+ValorDescontoAConceber.replace(/\./g, ",")+"';");
		
				if(DataLimiteDesconto != ""){
					eval("document.getElementById('LimiteItem_"+Item+"').innerHTML = 'Desconto até: "+DataLimiteDesconto+".';");
				}
			}
		});
	}