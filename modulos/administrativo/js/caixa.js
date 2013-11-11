	var ValorTotalAberturaTemp = "", ValorTotalAtualTemp = "";
	var ValoresAbertura = new Array(),ValoresAtual = new Array(),contador = 0,contador2 = 0;
	
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'inserir' || document.formulario.Acao.value == 'reabrir'){
				document.formulario.bt_reabrir.style.display = "none";
				document.formulario.bt_abrir.disabled	= false;
				document.formulario.bt_fechar.disabled	= true;
			}
			
			if(document.formulario.Acao.value == 'alterar'){
				document.formulario.bt_abrir.disabled	= true;
				
				if(document.formulario.Titular.value == 1) {
					document.formulario.bt_fechar.disabled	= (document.formulario.IdStatus.value == 2);
				} else {
					document.formulario.bt_fechar.disabled	= true;
				}
				
				if(document.formulario.IdStatus.value == 2){
					document.formulario.bt_reabrir.style.display = "inline";
				}else{
					document.formulario.bt_reabrir.style.display = "none";
				}
			}
		}	
	}
	function validar() {
		for(var i = 0; i < document.formulario.elements.length; i++){
			if(document.formulario.elements[i].name.search(/[^_]*[\d]+/i) > -1){
				var Valor = parseFloat(document.formulario.elements[i].value.replace(/,/g, ".")); 
				
				if(document.formulario.elements[i].name.substr(0,14) == "ValorAbertura_" && (!isNaN(Valor) && Valor < 0.00 || isNaN(Valor))) {
					mensagens(1);
					document.formulario.elements[i].focus();
					return false;
				}
			}
		}
		
		mensagens(0);
		return true;
	}
	function listar_forma_pagamento(IdFormaPagamento, ValorAbertura, ValorCancelado, ValorAtual){		
		
		if(IdFormaPagamento == undefined){
			IdFormaPagamento = "";
		}
		
		if(ValorAbertura == undefined){
			ValorAbertura = "";
		}
		
		if(ValorCancelado == undefined){
			ValorCancelado = "";
		}
		
		if(ValorAtual == undefined){
			ValorAtual = "";
		}
		
		if(IdFormaPagamento == ""){
			document.getElementById("bl_FormaPagamento").innerHTML = "";
			document.formulario.TabIndex.value = document.formulario.TabIndexFix.value;
			document.formulario.FormaPagamento.value = "0";
		}
		
		var url = "xml/forma_pagamento.php?IdFormaPagamento="+IdFormaPagamento+"&alt="+Math.random();
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdFormaPagamento").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdFormaPagamento")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdFormaPagamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFormaPagamento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoFormaPagamento = nameTextNode.nodeValue;
					
					var IdNome = "cp_FormaPagamento_"+IdFormaPagamento;
					var DivLN = document.createElement("table");
					DivLN.setAttribute("id", IdNome);
					document.getElementById("bl_FormaPagamento").appendChild(DivLN, null);
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
					
					c0.className = "find";
					c0.innerHTML = "&nbsp;";
					c1.className = "campo";
					c1.innerHTML = "<input type='text' name='IdFormaPagamento_"+IdFormaPagamento+"' value='"+IdFormaPagamento+"' style='width:73px' maxlength='11' readonly='readonly' /><input class='agrupador' type='text' name='DescricaoFormaPagamento_"+IdFormaPagamento+"' value='"+DescricaoFormaPagamento+"' style='width:248px' readonly='readonly' />";
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					c3.className = "campo";
					
					if(ValorAbertura != ""){
						c3.innerHTML = "<input style='width:140px' type='text' name='ValorAbertura_"+IdFormaPagamento+"' value='"+formata_float(Arredonda(ValorAbertura, 2), 2).replace(/\./, ',')+"' autocomplete='off' maxlength='11' readonly='readonly' />";
						ValoresAbertura[contador] = ValorAbertura;
						contador++;
					} else{
						c3.innerHTML = "<input style='width:140px' type='text' name='ValorAbertura_"+IdFormaPagamento+"' value='"+formata_float(Arredonda(ValorAbertura, 2), 2).replace(/\./, ',')+"' autocomplete='off' maxlength='11' onkeypress=\"mascara(this,event,'float')\" onkeydown=\"backspace(this,event)\" onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\" onChange=\"somar_valor_abertura();\" tabindex='"+(document.formulario.TabIndex.value++)+"' />";
					}
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					c5.className = "campo";
					c5.innerHTML = "<input style='width:140px' type='text' name='ValorCancelado_"+IdFormaPagamento+"' value='"+formata_float(Arredonda(ValorCancelado, 2), 2).replace(/\./, ',')+"' readOnly='readOnly' />";
				
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					c7.className = "campo";
					c7.innerHTML = "<input style='width:140px' type='text' name='SaldoAtual_"+IdFormaPagamento+"' value='"+formata_float(Arredonda(ValorAtual, 2), 2).replace(/\./, ',')+"' readOnly='readOnly' />";
					c8.className = "find";
					c8.style.verticalAlign = "top";
											
					ValorTotalAberturaTemp += ValorAbertura;					
					document.formulario.ValorAberturaTotal.value = formata_float(Arredonda(Number(document.formulario.ValorAberturaTotal.value.replace(/,/,".")) + Number(ValorAbertura),2),2).replace(/\./, ',');
					
					if(ValorAtual != 0 && ValorAtual != 0.00){
						ValorTotalAtualTemp += ValorAtual;					
						document.formulario.ValorAtualTotal.value = formata_float(Arredonda(Number(document.formulario.ValorAtualTotal.value.replace(/,/,".")) + Number(ValorAtual),2),2).replace(/\./, ',');
					}
					
					if(document.formulario.Acao.value == "inserir"){
						c8.innerHTML = "<img onclick=\"del_forma_pagamento("+IdFormaPagamento+");\" alt='Excluir?' title='Excluir?' style='margin-top:6px;' src='../../img/estrutura_sistema/ico_del.gif'>";
					} else {
						c8.innerHTML = "<img alt='Excluir?' title='Excluir?' style='margin-top:6px;' src='../../img/estrutura_sistema/ico_del_c.gif'>";
					}
					
					ValoresAtual[contador2] = ValorAtual;
					contador2++;
					
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
					c1.innerHTML = "<b style='color:#000; margin-right:12px;'>Forma Pag.</b>Nome Forma de Pagamento";
					c2.className = "separador";
					c2.innerHTML = "&nbsp;";
					c3.className = "descCampo";
					
					if(ValorAbertura != ""){
						c3.innerHTML = "Valor Abertura ("+document.formulario.Moeda.value+")";
					} else{
						c3.innerHTML = "<b>Valor Abertura ("+document.formulario.Moeda.value+")</b>";
					}
					
					c4.className = "separador";
					c4.innerHTML = "&nbsp;";
					c5.className = "descCampo";
					c5.innerHTML = "Valor Cancelado ("+document.formulario.Moeda.value+")";
					c6.className = "separador";
					c6.innerHTML = "&nbsp;";
					c7.className = "descCampo";
					c7.innerHTML = "Saldo Atual ("+document.formulario.Moeda.value+")";
					
					document.formulario.FormaPagamento.value += ","+IdFormaPagamento;
				}
				
				if(document.formulario.ValorAbertura_1 != undefined){
					document.formulario.ValorAbertura_1.focus();
				}
				
				document.formulario.bt_abrir.tabIndex = (document.formulario.TabIndex.value++);
				document.formulario.bt_fechar.tabIndex = (document.formulario.TabIndex.value++);				
			}
		});
		
		//atualizarValores(ValoresAtual,ValoresAbertura);
	}
	/*function somar_valor_abertura(ValorAbertura){
		var ValorTotalAbertura = 0.00;
		
		if(ValorAbertura == undefined){
			for(var i = 0; i < document.formulario.elements.length; i++){
				if(document.formulario.elements[i].name.search(/[^_]*[\d]+/i) > -1){
					var Valor = parseFloat(document.formulario.elements[i].value.replace(/,/g, ".")); 
					
					if(document.formulario.elements[i].name.substr(0,14) == "ValorAbertura_" && !isNaN(Valor)){
						ValorTotalAbertura += Valor;
						
						eval("document.formulario.SaldoAtual_"+document.formulario.elements[i].name.substr(14)+".value = '"+document.formulario.elements[i].value+"'")
					}
				}
			}
			
			//somar_valor_atual();
		} else{
			//ValorTotalAbertura = parseFloat(document.formulario.ValorAberturaTotal.value.replace(/,/g, "."));
			ValorAbertura = parseFloat(ValorAbertura.replace(/,/g, "."));
			
			if(!isNaN(ValorAbertura)){
				ValorTotalAbertura += ValorAbertura;
			}
		}
		
		document.formulario.ValorAberturaTotal.value = formata_float(Arredonda(ValorTotalAbertura, 2), 2).replace(/\./, ',');
	}*/
	function somar_valor_atual(ValorAtual){
		var ValorTotalAtual = 0.00;
		
		if(ValorAtual == undefined){
			for(var i = 0; i < document.formulario.elements.length; i++){
				if(document.formulario.elements[i].name.search(/[^_]*[\d]+/i) > -1){
					var Valor = parseFloat(document.formulario.elements[i].value.replace(/,/g, "."));
					
					if(document.formulario.elements[i].name.substr(0,11) == "SaldoAtual_" && !isNaN(Valor)){
						ValorTotalAtual += Valor;
					}
				}
			}
		} else{
			ValorTotalAtual = parseFloat(document.formulario.ValorAtualTotal.value.replace(/,/g, "."));
			ValorAtual = parseFloat(ValorAtual.replace(/,/g, "."));
			
			if(!isNaN(ValorAtual)){
				ValorTotalAtual += ValorAtual;
			}
		}
		
		document.formulario.ValorAtualTotal.value = formata_float(Arredonda(ValorTotalAtual, 2), 2).replace(/\./, ',');
	}
	
	function cadastrar(acao){
		document.formulario.Acao.value = acao;
		
		switch(acao){
			case "inserir":
				if(confirm("ATENÇÃO!\n\nVocê esta prestes a abrir este caixa.\nDeseja continuar?","SIM","NÃO")){
					if(validar()){ 
						document.formulario.submit();
					}
				}
				break;
			case "alterar":
				if(confirm("ATENÇÃO!\n\nVocê esta prestes a fechar este caixa.\nDeseja continuar?","SIM","NÃO")){
					if(validar()){ 
						document.formulario.submit();
					}
				}
				break;
			case "reabrir":
				if(confirm("ATENÇÃO!\n\nVocê esta prestes a reabrir este caixa.\nDeseja continuar?","SIM","NÃO")){
					if(validar()){ 
						document.formulario.submit();
					}
				}
				break;
			default:
				document.formulario.submit();
		}
	}
	
	function del_forma_pagamento(IdFormaPagamento){
		var cp_FormaPagamento = document.getElementById("cp_FormaPagamento_"+IdFormaPagamento);
		var bl_FormaPagamento = document.getElementById("bl_FormaPagamento");
		
		if(bl_FormaPagamento.getElementsByTagName("table").length > 1 && cp_FormaPagamento != null){
			document.formulario.FormaPagamento.value = ((document.formulario.FormaPagamento.value+",").replace(new RegExp("(,"+IdFormaPagamento+",)", "i"), ",")).replace(/(,$)/i, "");
			bl_FormaPagamento.removeChild(cp_FormaPagamento);
		}
	}
	
	/*function atualizarValores(ValoresAtual,ValoresAbertura){
		var Valor = 0,ValorTotalAbertura=0;
		for(var i=0; i <= ValoresAbertura.length; i++){
			somar_valor_abertura(ValoresAbertura[i]);			
		}
		
		for(var i=0; i <= ValoresAtual.length; i++){
			somar_valor_atual(ValoresAtual[i]);
		}
		
		for(var i = 0; i < document.formulario.length; i++){
			if(document.formulario.elements[i].name.substr(0,11) == "SaldoAtual_"){
				Valor = parseFloat(document.formulario.elements[i].value.replace(/,/g, ".")); 
				if(!isNaN(Valor)){
					ValorTotalAbertura += Valor;
				}
			}
			
			if(ValorTotalAbertura != 0 && ValorTotalAbertura != 0.00 && ValorTotalAbertura != "" && ValorTotalAbertura != undefined){
				document.formulario.ValorAberturaTotal.value = formata_float(Arredonda(ValorTotalAbertura, 2), 2).replace(/\./, ',');
			}
			
		}
	}*/