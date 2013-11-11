	function verificaAcao(){
		if(document.formulario.IdStatus.value == 0){
			document.formulario.bt_cancelar.disabled		=	true;
		}else{
			document.formulario.bt_cancelar.disabled		=	false;
		}
	}
	function cadastrar(acao){
		switch(acao){
			case "voltar":
				window.location.href = "cadastro_conta_receber.php?IdContaReceber="+document.formulario.IdContaReceber.value;
				break;
			default:
				if(validar()==true){
					document.formulario.submit();
				}
		}
	}
	function validar(){
		if(document.formulario.CreditoFuturo.value==''){
			mensagens(1);
			document.formulario.CreditoFuturo.focus();
			return false;
		}else{
			if(document.formulario.CreditoFuturo.value=='2' && document.formulario.IdLocalEstorno.value==''){
				mensagens(1);
				document.formulario.IdLocalEstorno.focus();
				return false;
			}	
		}
		if(document.formulario.ObsCancelamento.value==''){
			mensagens(1);
			document.formulario.ObsCancelamento.focus();
			return false;
		}
		if(document.formulario.IdCancelarNotaFiscal.value == '' && document.getElementById("cp_titCancelarNotaFiscal").style.display == "block"){
			mensagens(1);
			document.formulario.IdCancelarNotaFiscal.focus();
			return false;
		}
		
		return true;
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			colorTemp = document.getElementById(campo.name).style.backgroundColor;
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return true;
		}	
	}
	
	function inicia(){
		document.formulario.IdContaReceber.focus();
		status_inicial();
	}
	function busca_lancamento_financeiro(IdContaReceber,Erro,IdStatus){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		
		var url = "xml/demonstrativo.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function (xmlhttp) { 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
				document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
				document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";	
			}else{
				document.getElementById('cp_lancamentos_financeiros').style.display	=	'block';	
				var cont=0,IdLancamentoFinanceiro,Tipo,Valor,IdContrato,IdContaEventual,NumParcelaEventual,QtdParcela,DescricaoContaEventual,DataReferenciaInicial,DataReferenciaFinal,DescricaoServico,TotalValor,IdPessoa,IdContrato,TotalValor=0;
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdLancamentoFinanceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Valor = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Tipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Codigo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Codigo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Descricao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Referencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdStatus = nameTextNode.nodeValue;
					
					tam 	= document.getElementById('tabelaLancFinanceiro').rows.length;
					linha	= document.getElementById('tabelaLancFinanceiro').insertRow(tam-1);
					
					if(tam%2 != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					switch(IdStatus){
						case '0':
							linha.style.backgroundColor = "#FFD2D2";
							break;
						case '2':
							linha.style.backgroundColor = "#FFFFCA";
							break;
					}
					
					if(Valor == '') Valor = 0;
					
					linha.accessKey = IdLancamentoFinanceiro; 
					
					TotalValor	=	parseFloat(TotalValor) + parseFloat(Valor);
					
					c0	= linha.insertCell(0);	
					c1	= linha.insertCell(1);	
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					
					switch(Tipo){
						case 'CO':
							linkIni	= "<a href='cadastro_contrato.php?IdContrato="+Codigo+"'>";	
							break;
						case 'EV':
							linkIni	= "<a href='cadastro_conta_eventual.php?IdContaEventual="+Codigo+"'>";	
							break;
					}
					
					if(Tipo == 'EV'){
						cont++;
					}
					
					linkFim	=	"</a>";
					
					c0.innerHTML = linkIni + Tipo + linkFim;
					c0.style.padding  =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + Codigo + linkFim;
					c1.style.cursor = "pointer";
					
					c2.innerHTML = Descricao;
					
					c3.innerHTML = Referencia;
					
					c4.innerHTML =  formata_float(Arredonda(Valor,2),2).replace('.',',');
					c4.style.textAlign = "right";
					c4.style.padding  =	"0 8px 0 0";
				}
				document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
				document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: "+i;
			}
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	
	function listarParametro(IdLocalRecebimento,Erro){
		if(IdLocalRecebimento == '' || IdLocalRecebimento == undefined){
			IdLocalRecebimento = 0;
		}
		
		while(document.getElementById('tabelaParametro').rows.length > 0){
			document.getElementById('tabelaParametro').deleteRow(0);
		}		
		
		if(IdLocalRecebimento == ''){
			IdLocalRecebimento = 0;
		}
		
		var url = "xml/parametro_recebimento.php?IdLocalRecebimento="+IdLocalRecebimento;
		
		call_ajax(url, function (xmlhttp) { 
			var nameNode, nameTextNode, tam, linha, c0, c1, c2, c3, c4, c5;
			
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_parametros').style.display	=	'none';
			}else{
				document.getElementById('cp_parametros').style.display	=	'block';
				var DescricaoParametroRecebimento, Obrigatorio, IdParametroRecebimento, color,cont=0,tab=10,tabindex=0,temp=4;
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento").length; i++){
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoParametroRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Obrigatorio = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroRecebimento = nameTextNode.nodeValue;
					
					tam 	 = document.getElementById('tabelaParametro').rows.length;
					
					padding  = "";
					
					if(i%4 == 0){
						linha	 = document.getElementById('tabelaParametro').insertRow(tam);
						if(tabindex == 0){
							tabindex = tab + 4;
						}else{
							tabindex = tab + 4 + i;
						}
					}else{
						tabindex = tabindex - 1;
					}
					
					if(cont > 1) cont = 0;
					
					if(calculate(i) == true){
						if(cont == 0){
							padding	=	'padding-left:24px';
						}else{
							padding	=	'padding-left:10px';
						}
						cont++;
					}else{
						padding	=	'padding-left:10px';
					}
					
					if((i+1)==xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento").length && calculate(i) == false) padding	=	'padding-left:24px';
					
					linha.accessKey = IdParametroRecebimento; 

					c0	= linha.insertCell(0);
					c0.innerHTML = "<p style='margin:0; padding-bottom:1px; "+padding+"'><B style='color:#000;'>"+DescricaoParametroRecebimento+"</B></p><p style='padding-bottom:4px; margin:0; "+padding+"'><input type='text' name='Valor^"+IdParametroRecebimento+"' value='' style='width:190px' maxlength='255' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex="+tabindex+" readOnly='true'><input type='hidden' name='Obrigatorio^"+IdParametroRecebimento+"' value='"+Obrigatorio+"'></p>";
				}
				document.formulario.QuantParametros.value = i;
				if(document.formulario.Erro.value != ''){
					scrollWindow('bottom');
				}else{
					scrollWindow('top');
				}
			}	
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	function calculate(number) {
		var num=parseInt(number);
		for (var i=2;i<num;i++) {
			if (num % i == 0) {
				var prime="yes";
				return false;
				break;
			}
			if (num % i != 0) var prime="no";
		}
		if (prime == "no") return true;
	}
	function calculaValor(conta,nome){
		if(conta == 'ContaReceber'){
			var desc, desp, valor;
	
			desc	=	document.formulario.ValorDesconto.value;
			desc	=	new String(desc);
			desc	=	desc.replace('.','');
			desc	=	desc.replace('.','');
			desc	=	desc.replace(',','.');
			
			desp	=	document.formulario.ValorDespesas.value;
			desp	=	new String(desp);;
			desp	=	desp.replace('.','');
			desp	=	desp.replace('.','');
			desp	=	desp.replace(',','.');
			
			valor	=	document.formulario.ValorContaReceber.value;
			valor	=	new String(valor);;
			valor	=	valor.replace('.','');
			valor	=	valor.replace('.','');
			valor	=	valor.replace(',','.');
	
			valor	=	parseFloat(valor) + parseFloat(desp) - parseFloat(desc);
			valor	=	Arredonda(valor,2);
			valor	=	formata_float(valor,2);
			
			document.formulario.ValorFinal.value	=	valor.replace('.',',');
			
			if(document.formulario.ValorDescontoRecebimento.value != '0,00' && nome=='ValorDesconto'){
				descR	=	document.formulario.ValorDescontoRecebimento.value;
				descR	=	new String(descR);
				descR	=	descR.replace('.','');
				descR	=	descR.replace('.','');
				descR	=	descR.replace(',','.');
				
				descT	=	parseFloat(desc) + parseFloat(descR);
				descT	=	Arredonda(descT,2);
				descT	=	formata_float(descT,2);
				document.formulario.ValorDescontoRecebimento.value	=	descT.replace('.',',');
			}else{
				document.formulario.ValorDescontoRecebimento.value	=	document.formulario.ValorDesconto.value;
				calculaValor('ContaRecebimento');
			}
			
			if(document.formulario.ValorOutrasDespesas.value != '0,00' && nome=='ValorDespesas'){
				despO	=	document.formulario.ValorOutrasDespesas.value;
				despO	=	new String(despO);
				despO	=	despO.replace('.','');
				despO	=	despO.replace('.','');
				despO	=	despO.replace(',','.');
				
				despT	=	parseFloat(desp) + parseFloat(descO);
				despT	=	Arredonda(despT,2);
				despT	=	formata_float(despT,2);
				document.formulario.ValorOutrasDespesas.value	=	despT.replace('.',',');
			}else{
				document.formulario.ValorOutrasDespesas.value	=	document.formulario.ValorDespesas.value;
				calculaValor('ContaRecebimento');
			}
		}else{
			var desc, desp, valor, valorFinal;
	
			desc	=	document.formulario.ValorDescontoRecebimento.value;
			desc	=	new String(desc);
			desc	=	desc.replace('.','');
			desc	=	desc.replace('.','');
			desc	=	desc.replace(',','.');
			
			desp	=	document.formulario.ValorOutrasDespesas.value;
			desp	=	new String(desp);;
			desp	=	desp.replace('.','');
			desp	=	desp.replace('.','');
			desp	=	desp.replace(',','.');
			
			mora	=	document.formulario.ValorMoraMulta.value;
			mora	=	new String(mora);;
			mora	=	mora.replace('.','');
			mora	=	mora.replace('.','');
			mora	=	mora.replace(',','.');
			
			valor	=	document.formulario.ValorRecebimento.value;
			valor	=	new String(valor);;
			valor	=	valor.replace('.','');
			valor	=	valor.replace('.','');
			valor	=	valor.replace(',','.');
	
			valor	=	parseFloat(valor) + parseFloat(desp) + parseFloat(mora) - parseFloat(desc);
			valor	=	Arredonda(valor,2);
			valor	=	formata_float(valor,2);
			
			document.formulario.ValorReceber.value	=	valor.replace('.',',');
		}
	}
	function listarContaReceberParametroRecebimento(IdContaReceber,IdLocalRecebimento,Erro){
		if(IdContaReceber == '' || IdContaReceber == undefined){
			IdContaReceber = 0;
		}
		
		if(IdLocalRecebimento == '' || IdLocalRecebimento == undefined){
			IdLocalRecebimento = 0;
		}
	    
		var posInicial=0,posFinal=0;
		
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name == 'ValorReceber'){
					if(posInicial = 0){
						posInicial = i;
					}
					posFinal = 0;
				}
			}
		}
		
		var url = "xml/conta_receber_recebimento_parametro.php?IdContaReceber="+IdContaReceber+"&IdLocalRecebimento="+IdLocalRecebimento;
 		
		call_ajax(url, function (xmlhttp) {
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_parametros').style.display	=	'none';
				
				for(ii=posInicial;ii<=posFinal;ii++){
					if(document.formulario[ii].name != undefined){
						document.formulario[ii].value	=	"";
					}
				}
			}else{
				document.getElementById('cp_parametros').style.display	=	'block';
			
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento").length; i++){
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametro = nameTextNode.nodeValue;
					
					for(ii=posInicial;ii<=posFinal;ii++){
						if(document.formulario[ii].name != undefined){
							if(document.formulario[ii].name == 'Valor^'+IdParametroRecebimento){
								document.formulario[ii].value	=	ValorParametro;
								break;
							}
						}
					}	
				}
			}
		});
	}
	function busca_lancamentos_data_base(IdContaReceber){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		
		var url = "xml/demonstrativo.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function (xmlhttp) { 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cpVoltarDataBase').innerHTML		=	"";	
			}else{
				document.getElementById('cpVoltarDataBase').innerHTML		=	"";	
				
				var dados 	 = "",	dados_neg	=	"";
				var tabindex = 2;
				var aux		 = 0;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdLancamentoFinanceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Tipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Codigo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Codigo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Descricao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Referencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Moeda")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Moeda = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Valor = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Voltar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Voltar = nameTextNode.nodeValue;	
					
					if(Valor == '') Valor = 0;
					
					if(Valor < 0){
						Valor	=	formata_float(Arredonda(Valor,2),2).replace('.',',');
						
						dados_neg	+=	"<table>";
						dados_neg	+=	"	<tr>";
						dados_neg	+=	"		<td class='find'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Tipo</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Código</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Descrição</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Referência</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Valor ("+Moeda+")</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'><B>Reaproveitar Crédito?</B></td>";	
						dados_neg	+=	"	</tr>";
						dados_neg	+=	"	<tr>";
						dados_neg	+=	"		<td class='find'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<select name='Tipo_"+IdLancamentoFinanceiro+"' style='width:60px'  disabled>";
						dados_neg	+=	"				<option value='1'>"+Tipo+"</option>";
						dados_neg	+=	"			</select>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Codigo_"+IdLancamentoFinanceiro+"' value='"+Codigo+"' style='width:60px' readOnly>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Descricao_"+IdLancamentoFinanceiro+"' value='"+Descricao+"' style='width:220px' readOnly>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Referencia_"+IdLancamentoFinanceiro+"' value='"+Referencia+"' style='width:150px' readOnly>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='ValorLancamento_"+IdLancamentoFinanceiro+"' value='"+Valor+"' style='width:84px' readOnly>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<select name='ReaproveitarCredito_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'\">";
						dados_neg	+=	"				<option value='0' selected></option>";
						dados_neg	+=	"			</select>";
						dados_neg	+=	"			<input type='hidden' name='ReaproveitarCreditoDefault_"+IdLancamentoFinanceiro+"' value='"+Voltar+"'>";
						dados_neg	+=	"		</td>";	
						dados_neg	+=	"	</tr>";
						dados_neg	+=	"</table>";
					}else{
					
						Valor	=	formata_float(Arredonda(Valor,2),2).replace('.',',');
						
						dados	+=	"<table>";
						dados	+=	"	<tr>";
						dados	+=	"		<td class='find'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Tipo</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Código</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Descrição</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Referência</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Valor ("+Moeda+")</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						
						if(Tipo == 'CO'){
							dados	+=	"	<td class='descCampo'><B>Voltar data base de cálculo?</B></td>";	
						}else{			
							dados	+=	"	<td class='descCampo'><B>Cancelar conta eventual?</B></td>";
						}							
						
						dados	+=	"	</tr>";
						dados	+=	"	<tr>";
						dados	+=	"		<td class='find'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<select name='Tipo_"+IdLancamentoFinanceiro+"' style='width:60px'  disabled>";
						dados	+=	"				<option value='1'>"+Tipo+"</option>";
						dados	+=	"			</select>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Codigo_"+IdLancamentoFinanceiro+"' value='"+Codigo+"' style='width:60px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Descricao_"+IdLancamentoFinanceiro+"' value='"+Descricao+"' style='width:220px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Referencia_"+IdLancamentoFinanceiro+"' value='"+Referencia+"' style='width:150px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='ValorLancamento_"+IdLancamentoFinanceiro+"' value='"+Valor+"' style='width:84px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						
						if(Tipo == 'CO'){
							dados	+=	"		<select name='VoltarDataBase_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"' onChange=\"verificaMudarDataBase("+Codigo+","+IdLancamentoFinanceiro+",this.value);\">";
							dados	+=	"			<option value='0' selected></option>";
						}else{			
							dados	+=	"		<select name='CancelarContaEventual_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
							dados	+=	"			<option value='0' selected></option>";
						}							
						
						dados	+=	"			</select>";
						dados	+=	"			<input type='hidden' name='VoltarDataBaseDefault_"+IdLancamentoFinanceiro+"' value='"+Voltar+"'>";
						dados	+=	"		</td>";	
						dados	+=	"	</tr>";
						dados	+=	"</table>";
					}
				}
				
				document.getElementById('cpVoltarDataBase').innerHTML		=	dados_neg+dados;
			}
			
			var posInicial = 0, posFinal = 0, campo = "";
			
			for(i = 0; i<document.formulario.length; i++){
				if(document.formulario[i].name != undefined){
					if(document.formulario[i].name.substring(0,16) == 'ValorLancamento_'){
						if(posInicial == 0){
							posInicial = i;
						}
						posFinal = i;
					}
				}
			}
			
			aux	=	0; 
			var IdCampo	=	0;
			
			for(i=posInicial;i<=posFinal;i=i+7){
				var temp	=	document.formulario[i+1].name.split('_');
				
				switch(temp[0]){
					case 'CancelarContaEventual':
						IdGrupoParametroSistema = 67;
						break;
					case 'VoltarDataBase':
						IdGrupoParametroSistema = 39;
						break;
					case 'ReaproveitarCredito':
						IdGrupoParametroSistema = 110;
						break;
				}
				
				IdParametroSistema		= "";
				IdCampo	=	document.formulario[i-3].value;
					
				if(temp[0] == 'VoltarDataBase'){
					if(aux!=IdCampo){
						document.formulario[i+1].disabled	=	false;
						aux	=	IdCampo;
					}else{
						document.formulario[i-6].disabled	=	true;
						document.formulario[i+1].disabled	=	false;
					}	
					
					if(document.formulario[i+2].value == 'false'){
						IdParametroSistema = 2;
						document.formulario[i+1].disabled	=	true;
					}
				}
				
					
				addSelect(document.formulario[i+1],IdGrupoParametroSistema,IdParametroSistema);
			}
		});
	} 
	function addSelect(campo,IdGrupoParametroSistema,IdParametroSistemaTemp){
		var url = "xml/parametro_sistema.php?IdGrupoParametroSistema="+IdGrupoParametroSistema+"&IdParametroSistema="+IdParametroSistemaTemp;
		
		call_ajax(url, function (xmlhttp) { 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				var IdParametroSistema,ValorParametroSistema;
				
				for(var ii=0; ii<xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; ii++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[ii]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[ii]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametroSistema = nameTextNode.nodeValue;
					
					addOption(campo,ValorParametroSistema,IdParametroSistema);
				}
				if(IdParametroSistemaTemp == ""){
					campo.options[0].selected = true;
				}else{
					campo.options[1].selected = true;
				}
			}
		});
	}
	function listarRecebimento(IdContaReceber,Erro,IdContaReceberRecebimentoTemp){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		
		if(IdContaReceberRecebimentoTemp == undefined){
			IdContaReceberRecebimentoTemp = '';
		}
		
		var url = "xml/conta_receber_recebimento.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url, function (xmlhttp) { 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
				document.getElementById('totalValorRecebido').innerHTML				=	"0,00";	
				document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";	
			}else{
				while(document.getElementById('tabelaRecebimentos').rows.length > 2){
					document.getElementById('tabelaRecebimentos').deleteRow(1);
				}
				
				var tam, linha, c0, c1, c2, c3, c4;
				var IdContaReceberRecebimento,DataRecebimento,ValorDesconto,ValorRecebido,Valor,DescricaoLocalRecebimento,TotalDesc=0,IdRecibo,TotalReceb=0;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento").length; i++){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceberRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorDescontoRecebimento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorRecebido = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoLocalRecebimento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdRecibo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdLoja = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Obs = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoEstorno")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContratoEstorno  = nameTextNode.nodeValue;
					
					if(ValorDescontoRecebimento == '')  ValorDescontoRecebimento = '0.00';
					if(ValorRecebido == '')				ValorRecebido = '0.00';
					
					TotalDesc	=	TotalDesc +	parseFloat(ValorDescontoRecebimento);
					TotalReceb	=	TotalReceb + parseFloat(ValorRecebido);
					
					tam 	= document.getElementById('tabelaRecebimentos').rows.length;
					linha	= document.getElementById('tabelaRecebimentos').insertRow(tam-1);
					
					if(tam%2 != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					if(IdContaReceberRecebimentoTemp == IdContaReceberRecebimento){
						linha.style.backgroundColor = "#A0C4EA";
					}
					
					if(IdStatus == 0 || IdStatus == 3){ /*Cancelado || Estorno*/
						linha.style.backgroundColor = "#FFD2D2";
					}
					
					linha.accessKey = IdContaReceberRecebimento; 
					
					c0	= linha.insertCell(0);	
					c1	= linha.insertCell(1);	
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					c6	= linha.insertCell(6);
					
					var linkIni = "<a href='cadastro_conta_receber_recebimento.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento+"'>";
					var linkFim = "</a>";
					
					c0.innerHTML = linkIni + IdContaReceberRecebimento + linkFim;
					c0.style.cursor  = "pointer";
					c0.style.padding =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + dateFormat(DataRecebimento) + linkFim;
					c1.style.cursor = "pointer";
					
					c2.innerHTML = linkIni + ValorDescontoRecebimento.replace('.',',')+ linkFim + "&nbsp;&nbsp;" ;
					c2.style.cursor = "pointer";
					c2.style.textAlign = "right";
					
					c3.innerHTML = linkIni + ValorRecebido.replace('.',',') + linkFim + "&nbsp;&nbsp;";
					c3.style.cursor = "pointer";
					c3.style.textAlign = "right";
					
					c4.innerHTML = linkIni + DescricaoLocalRecebimento + linkFim;
					c4.style.cursor = "pointer";
					
					switch(IdStatus){
						case '0':
							c5.innerHTML = "Canc.";
							break;
						case '3':
							c5.innerHTML = "<a href='estorno.php?IdLoja="+IdLoja+"&IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento+"' target='_blank'>Estorno</a>";
							break;
						default:
							c5.innerHTML = "<a href='recibo.php?IdLoja="+IdLoja+"&IdRecibo="+IdRecibo+"' target='_blank'>"+IdRecibo+"</a>";
					}
					c5.style.cursor = "pointer";
					
					if(IdStatus == 1 && document.formulario.PermissaoCancelarRecebimento.value == 1 ){
						c6.innerHTML    = "<a href='cadastro_cancelar_conta_receber_recebimento.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento+"'><img src='../../img/estrutura_sistema/ico_del.gif' alt='Cancelar?'></a>";
					}else{
						c6.innerHTML    = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Cancelar?'>";
					}
					c6.style.cursor = "pointer";
				}
				document.getElementById('totalValorDesconto').innerHTML		=	formata_float(Arredonda(TotalDesc,2),2).replace('.',',');	
				document.getElementById('totalValorRecebido').innerHTML		=	formata_float(Arredonda(TotalReceb,2),2).replace('.',',');	
				document.getElementById('totalRecebimentos').innerHTML		=	"Total: "+i;	
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	} 
	function verificaMudarDataBase(Codigo,IdLancamentoFinanceiro,valor){
		var posInicial = 0, posFinal = 0, campo = "";
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,7) == 'Codigo_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal = i;
				}
			}
		}
		var cont	=	0, 	aux	=	0;;
		for(i=posInicial;i<=posFinal;i=i+7){
			if(document.formulario[i].value == Codigo){
				cont++;
			}
		}	
		var posTemp	=	0;	
		if(cont>1){
			for(i=posInicial;i<=posFinal;i=i+7){
				if(document.formulario[i].value == Codigo){
					var temp	=	document.formulario[i].name.split('_');
					if(temp[1] == IdLancamentoFinanceiro){
						posTemp 	=	i;
						aux			=	1;
						break;
					}
				}
			}
			if(aux == 1 && posTemp >= posInicial){
				//alert(posTemp+" "+posInicial);
				for(i=posTemp;i>=posInicial;i=i-7){
					if(document.formulario[i].value == Codigo){
						if(valor == 2){	//nao
							var temp2	=	document.formulario[i+4].name.split('_');
							if(temp2[0] != 'ReaproveitarCredito'){
								if(aux == 1){
									document.formulario[i+4].disabled		=	false;
									aux = 0;
								}else{
									document.formulario[i+4].disabled		=	true;
									document.formulario[i+4][2].selected	=	true;
								}
							}
						}else if(valor == 1){ //sim
							var temp2	=	document.formulario[i-3].name.split('_');
							if(temp2[0] != 'ReaproveitarCredito'){
								if(aux == 1){
									if(document.formulario[i-3].name.substring(0,15) == 'VoltarDataBase_'){
										document.formulario[i-3].disabled			=	false;
										document.formulario[i-3][0].selected		=	true;
									}
									aux = 0;
									
									document.formulario[i-3].focus();
								}else{
									if(document.formulario[i-3] != undefined){
										if(document.formulario[i-7].value == Codigo){
											document.formulario[i-3].disabled			=	true;
											document.formulario[i-3][0].selected		=	true;
										}
									}
								}
							}
						}else{
							var temp2	=	document.formulario[i+4].name.split('_');
							if(temp2[0] != 'ReaproveitarCredito'){
								if(aux == 1){
									document.formulario[i+4].disabled		=	false;
									document.formulario[i+4][0].selected	=	true;
									aux = 0;
								}else{
									document.formulario[i+4].disabled		=	true;
									document.formulario[i+4][0].selected	=	true;
								}
							}
						}
					}
				}
			}
		}
	}
	function verifica_estorno(CreditoFuturo){
		switch(CreditoFuturo){
			case '1':
				document.getElementById('titLocalEstorno').style.display	=	'none';
				document.getElementById('cpLocalEstorno').style.display		=	'none';
				document.getElementById('titContratoEstorno').style.display	=	'block';
				document.getElementById('cpContratoEstorno').style.display	=	'block';
				
				var IdPessoa = document.formulario.IdPessoa.value;
				
				if(IdPessoa == ""){
					IdPessoa = document.formulario.IdPessoaF.value;
				}
				
				listar_contrato(IdPessoa);
				break;
			case '2':
				document.getElementById('titLocalEstorno').style.display	=	'block';
				document.getElementById('cpLocalEstorno').style.display		=	'block';
				document.getElementById('titContratoEstorno').style.display	=	'none';
				document.getElementById('cpContratoEstorno').style.display	=	'none';
				break;
			default:
				document.getElementById('titLocalEstorno').style.display	=	'none';
				document.getElementById('cpLocalEstorno').style.display		=	'none';
				document.getElementById('titContratoEstorno').style.display	=	'none';
				document.getElementById('cpContratoEstorno').style.display	=	'none';
		}
	}
	function listar_contrato(IdPessoa,IdContratoTemp){
		if(IdContratoTemp == undefined){
			IdContratoTemp = '';
		}
		
		var url = "xml/contrato.php?IdPessoa="+IdPessoa;
		
		call_ajax(url, function (xmlhttp) { 
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				while(document.formulario.IdContratoEstorno.options.length > 0){
					document.formulario.IdContratoEstorno.options[0] = null;
				}
			}else{
				while(document.formulario.IdContratoEstorno.options.length > 0){
					document.formulario.IdContratoEstorno.options[0] = null;
				}
				
				addOption(document.formulario.IdContratoEstorno,"","0");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdContrato = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoAgrupador")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoContratoAgrupador = nameTextNode.nodeValue;
					
					var Descricao	=	"("+IdContrato+") "+DescricaoContratoAgrupador;
					
					addOption(document.formulario.IdContratoEstorno,Descricao,IdContrato);
				}
				
				if(IdContratoTemp!=''){
					for(ii=0;ii<document.formulario.IdContratoEstorno.length;ii++){
						if(document.formulario.IdContratoEstorno[ii].value == IdContratoTemp){
							document.formulario.IdContratoEstorno[ii].selected = true;
							break;
						}
					}
				}else{
					document.formulario.IdContratoEstorno[0].selected = true;
				}
			}
		});
	} 