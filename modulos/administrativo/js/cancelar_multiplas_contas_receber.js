	var ContExecucao = 0;
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_cancelar.disabled = true;
			}
			if(document.formulario.Acao.value=='cancelar'){			
				document.formulario.bt_cancelar.disabled = false;
			}
		}	
	}
	
	function validar(){
		var posInicial = 0, posFinal = 0, campo = "";
		
		for(i = 0; i < document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,16) == 'ValorLancamento_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal = i;
				}
			}
		}
		
		for(i = posInicial; i <= posFinal; i += 8){
			if((document.formulario[i+1].value == "" || document.formulario[i+1].value == "0") && document.formulario[i+1].disabled == false){
				mensagens(1);
				document.formulario[i+1].focus();
				return false;
			}
		}
		
		if(document.formulario.ObsCancelamento.value == ''){
			mensagens(1);
			document.formulario.ObsCancelamento.focus();
			return false;
		}
		
		return true;
	}
	
	function listar_conta_receber(IdOrdemServico){
	   	var url = "xml/conta_receber.php?IdOrdemServico="+IdOrdemServico;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				while(document.getElementById('tabelaContaReceber').rows.length > 2){
					document.getElementById('tabelaContaReceber').deleteRow(1);
				}
				
				document.getElementById('tabelaTotalValor').innerHTML	= "0,00";	
				document.getElementById('tabelaTotalReceb').innerHTML	= "0,00";	
				document.getElementById('tabelaTotal').innerHTML		= "Total: 0";	
				
			}else{
				while(document.getElementById('tabelaContaReceber').rows.length > 2){
					document.getElementById('tabelaContaReceber').deleteRow(1);
				}
				var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, cont, tabindex = Number(document.formulario.TabIndex.value);
				var nameNode,nameTextNode,IdContaReceber,NumeroDocumento,NumeroNF,AbreviacaoNomeLocalCobranca,DataLancamento,Valor,DataVencimento,ValorRecebido,DataRecebimento,DescricaoLocalRecebimento,TotalValor=0,TotalReceb=0;cont=0;
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NumeroDocumento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NumeroNF = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataLancamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Valor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataVencimento = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorRecebido = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoLocalRecebimento = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Tipo = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("Voltar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Voltar = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatusRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatusRecebimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					document.formulario.bt_cancelar.disabled=true;
					document.formulario.todos_cr.disabled = true;
					document.formulario.ObsCancelamento.disabled = true;
					
					if(IdStatus != 0 && IdStatus != "" && IdStatus != 2){
						
						document.formulario.bt_cancelar.disabled= false;
						document.formulario.todos_cr.disabled = false;
						document.formulario.ObsCancelamento.disabled = false;
						
						if(IdStatusRecebimento != 1){
						//	ValorRecebido = DataRecebimento = DescricaoLocalRecebimento = '';
						}
						
						tam 	= document.getElementById('tabelaContaReceber').rows.length;
						linha	= document.getElementById('tabelaContaReceber').insertRow(tam-1);
						
						if(tam%2 != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = IdContaReceber; 
						
						if(ValorRecebido==''){
							ValorRecebido = 0;
						}

						TotalValor	= parseFloat(TotalValor) + parseFloat(Valor);
						TotalReceb	= parseFloat(TotalReceb) + parseFloat(ValorRecebido);
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);	
						c2	= linha.insertCell(2);	
						c3	= linha.insertCell(3);
						c4	= linha.insertCell(4);
						c5	= linha.insertCell(5);
						c6	= linha.insertCell(6);
						c7	= linha.insertCell(7);
						c8	= linha.insertCell(8);
						c9	= linha.insertCell(9);
						c10	= linha.insertCell(10);

						linkIni = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>"
						linkFim	=	"</a>";
						
						c0.innerHTML = "<input style='border:0' type='checkbox' name='cr_"+IdContaReceber+"' onClick='selecionar(this)' tabindex='"+(tabindex+i)+"'>";
						c0.className = "tableListarEspaco";
						
						c1.innerHTML = linkIni + IdContaReceber + linkFim;
						c1.style.padding  =	"0 0 0 5px";
						c1.style.cursor = "pointer";
						
						c2.innerHTML = linkIni + NumeroDocumento + linkFim;
						c2.style.cursor = "pointer";

						c3.innerHTML = linkIni + NumeroNF + linkFim;
						c3.style.cursor = "pointer";

						c4.innerHTML = linkIni + AbreviacaoNomeLocalCobranca + linkFim;
						c4.style.cursor = "pointer";

						c5.innerHTML = linkIni + dateFormat(DataLancamento) + linkFim;
						c5.style.cursor = "pointer";
						
						c6.innerHTML =  linkIni + formata_float(Arredonda(Valor,2),2).replace('.',',') + linkFim;
						c6.style.textAlign = "right";
						c6.style.cursor = "pointer";
						c6.style.padding  =	"0 8px 0 0";

						c7.innerHTML = linkIni + dateFormat(DataVencimento) + linkFim;
						c7.style.cursor = "pointer";

						c8.innerHTML =  linkIni + formata_float(Arredonda(ValorRecebido,2),2).replace('.',',') + linkFim;
						c8.style.textAlign = "right";
						c8.style.cursor = "pointer";
						c8.style.padding  =	"0 8px 0 0";

						c9.innerHTML = linkIni + dateFormat(DataRecebimento) + linkFim;
						c9.style.cursor = "pointer";

						c10.innerHTML = linkIni + DescricaoLocalRecebimento + linkFim;
						c10.style.cursor = "pointer";
						cont++;
					}
				}
				document.formulario.TabIndex.value						= (tabindex+i);
				document.getElementById('tabelaTotalValor').innerHTML	= formata_float(Arredonda(TotalValor,2),2).replace('.',',');	
				document.getElementById('tabelaTotalReceb').innerHTML	= formata_float(Arredonda(TotalReceb,2),2).replace('.',',');	
				document.getElementById('tabelaTotal').innerHTML		= "Total: "+cont;	
			}
			if(cont == 0){
				window.location = "./cadastro_cancelar_lancamento_financeiro.php?IdOrdemServico="+IdOrdemServico;
			}
			if(window.janela != undefined){
				window.janela.close();
			}
		});
		document.formulario.bt_cancelar.disable = true;
	}
	function busca_lancamentos_data_base(IdContaReceber){
	   	var url = "xml/demonstrativo.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cpVoltarDataBase').innerHTML = "";	
			} else{
				document.getElementById('cpVoltarDataBase').innerHTML = "";	
				
				var dados = "", dados_neg = "", tabindex = Number(document.formulario.TabIndex.value);
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdLancamentoFinanceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Tipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Codigo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Codigo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Descricao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Referencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Moeda")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Moeda = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Valor = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Voltar")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Voltar = nameTextNode.nodeValue;
					
					if(Voltar == "true" && !(new RegExp(","+IdLancamentoFinanceiro+",$")).test(","+document.formulario.LancamentoFinanceiroTipoContrato.value+",")){
						Voltar = "false";
					}
					
					if(Valor == ''){
						Valor = 0;
					}
					if(Valor < 0){
						Valor = formata_float(Arredonda(Valor,2),2).replace('.',',');
						
						dados_neg	+=	"<table>";
						dados_neg	+=	"	<tr>";
						dados_neg	+=	"		<td class='find'>&nbsp;</td>";
						dados_neg	+=	"		<td class='descCampo'>Contas R.</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
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
						dados_neg	+=	"			<input type='text' name='ContaReceber_"+IdLancamentoFinanceiro+"' value='"+IdContaReceber+"' style='width:60px' readOnly>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<select name='Tipo_"+IdLancamentoFinanceiro+"' style='width:50px'  disabled>";
						dados_neg	+=	"				<option value='1'>"+Tipo+"</option>";
						dados_neg	+=	"			</select>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Codigo_"+IdLancamentoFinanceiro+"' value='"+Codigo+"' style='width:60px' readOnly>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Descricao_"+IdLancamentoFinanceiro+"' value='"+Descricao+"' style='width:156px' readOnly>";
						dados_neg	+=	"		</td>";
						dados_neg	+=	"		<td class='separador'>&nbsp;</td>";
						dados_neg	+=	"		<td class='campo'>";
						dados_neg	+=	"			<input type='text' name='Referencia_"+IdLancamentoFinanceiro+"' value='"+Referencia+"' style='width:146px' readOnly>";
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
					} else{
					
						Valor	=	formata_float(Arredonda(Valor,2),2).replace('.',',');
						
						dados	+=	"<table>";
						dados	+=	"	<tr>";
						dados	+=	"		<td class='find'>&nbsp;</td>";
						dados	+=	"		<td class='descCampo'>Contas R.</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
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
						
						switch(Tipo){
							case 'CO':
								dados	+=	"	<td class='descCampo'><B>Voltar data base de cálculo?</B></td>";	
								break
							case 'EV':
								dados	+=	"	<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
							case 'OS':
								dados	+=	"	<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
							case 'EF':
								dados	+=	"	<td class='descCampo'><B>Cancelar Lanç. Financeiro?</B></td>";
								break;
						}								
						
						dados	+=	"	</tr>";
						dados	+=	"	<tr>";
						dados	+=	"		<td class='find'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='ContaReceber_"+IdLancamentoFinanceiro+"' value='"+IdContaReceber+"' style='width:60px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<select name='Tipo_"+IdLancamentoFinanceiro+"' style='width:50px'  disabled>";
						dados	+=	"				<option value='1'>"+Tipo+"</option>";
						dados	+=	"			</select>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Codigo_"+IdLancamentoFinanceiro+"' value='"+Codigo+"' style='width:60px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Descricao_"+IdLancamentoFinanceiro+"' value='"+Descricao+"' style='width:156px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='Referencia_"+IdLancamentoFinanceiro+"' value='"+Referencia+"' style='width:146px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						dados	+=	"			<input type='text' name='ValorLancamento_"+IdLancamentoFinanceiro+"' value='"+Valor+"' style='width:84px' readOnly>";
						dados	+=	"		</td>";
						dados	+=	"		<td class='separador'>&nbsp;</td>";
						dados	+=	"		<td class='campo'>";
						
						switch(Tipo){
							case 'CO':
								dados	+=	"		<select name='VoltarDataBase_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"' onChange=\"verificaMudarDataBase("+Codigo+","+IdLancamentoFinanceiro+",this.value);\">";
								dados	+=	"			<option value='0' selected></option>";
								break;
							case 'EV':		
								dados	+=	"		<select name='CancelarContaEventual_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
								dados	+=	"			<option value='0' selected></option>";
								break;
							case 'OS':		
								dados	+=	"		<select name='CancelarOrdemServico_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
								dados	+=	"			<option value='0' selected></option>";
								break;
							case 'EF':
								dados	+=	"		<select name='CancelarEncargoFinanceiro_"+IdLancamentoFinanceiro+"' style='width:170px'  onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='"+(tabindex+i)+"'>";
								break;
						}							
						
						dados	+=	"			</select>";
						dados	+=	"			<input type='hidden' name='VoltarDataBaseDefault_"+IdLancamentoFinanceiro+"' value='"+Voltar+"'>";
						dados	+=	"		</td>";	
						dados	+=	"	</tr>";
						dados	+=	"</table>";
					}
				}
				
				document.getElementById('cpVoltarDataBase').innerHTML = dados_neg+dados;
			}
			
			var posInicial = 0, posFinal = 0, campo = "";
			
			for(i = 0; i < document.formulario.length; i++){
				if(document.formulario[i].name != undefined){
					if(document.formulario[i].name.substring(0,16) == 'ValorLancamento_'){
						if(posInicial == 0){
							posInicial = i;
						}
						
						posFinal = i;
					}
				}
			}
			
			var IdCampo	= 0, aux = 0;
			
			if(posFinal > 0){
				var posFinalTemp = 0;
				
				for(i = posInicial; i <= posFinal; i += 8){
					var temp = document.formulario[i+1].name.split('_');
					IdCampo	= document.formulario[i-3].value;
					switch(temp[0]){
						case 'CancelarContaEventual':
							IdGrupoParametroSistema = 67;
							break;
						case 'CancelarEncargoFinanceiro':
							IdGrupoParametroSistema = 67;
							break;
						case 'VoltarDataBase':
							IdGrupoParametroSistema = 39;
							
							if(aux != trim(IdCampo)){
								document.formulario[i+1].disabled = false;
								aux	=	IdCampo;
							} else{
								document.formulario[i-6].disabled = true;
								document.formulario[i+1].disabled = false;
							}
							
							if(document.formulario[i+2].value == 'false'){
								document.formulario[i+1].disabled = true;
							}
							break;
						case 'ReaproveitarCredito':
							IdGrupoParametroSistema = 110;
							break;
						case 'CancelarOrdemServico':
							IdGrupoParametroSistema = 67;
							break;
					}
					
					addSelect(document.formulario[i+1],IdGrupoParametroSistema,'',true);
					
					if(document.formulario[i-4].options[document.formulario[i-4].selectedIndex].text == "CO"){
						posFinalTemp = i;
					}
				}
				
				verificar_select_lancamentos_data_base(posInicial,posFinalTemp);
			}
		});
	} 
	function verificar_select_lancamentos_data_base(posInicial,posFinal){
		if(ContExecucao > 0){
			setTimeout(function () { verificar_select_lancamentos_data_base(posInicial,posFinal); },100);
		} else{
			var selecionar = 2;
			
			if(document.formulario[posFinal+1].disabled){
				selecionar = 1;
			}
			
			for(var i = posFinal; i >= posInicial; i -= 8){
				if(document.formulario[i-4].options[document.formulario[i-4].selectedIndex].text == "CO"){
					if(selecionar == 1){
						document.formulario[i+1][1].selected = selecionar;
					} else{
						var temp = document.formulario[i+1].name.split('_');
						var LancamentoFinanceiroTipoContrato = ","+temp[1]+",";
						temp = document.formulario[i-7].name.split('_');
						
						if(temp[0] == "VoltarDataBase"){
							LancamentoFinanceiroTipoContrato = ","+temp[1]+LancamentoFinanceiroTipoContrato;
						}
						
						if(!(new RegExp(LancamentoFinanceiroTipoContrato)).test(","+document.formulario.LancamentoFinanceiroTipoContrato.value+",")){
							selecionar = 1;
						}
					}
				}
			}
		}
	}
	function addSelect(campo,IdGrupoParametroSistema,IdParametroSistemaTemp,selecionar){
		if(IdParametroSistemaTemp == undefined){
			IdParametroSistemaTemp = "";
		}
		
		if(selecionar == undefined){
			selecionar = false;
		}
	    
		var url = "xml/parametro_sistema.php?IdGrupoParametroSistema="+IdGrupoParametroSistema;
		
		if(!selecionar){
			url += "&IdParametroSistema="+IdParametroSistemaTemp;
		}
		
		ContExecucao++;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				var nameNode,nameTextNode,IdParametroSistema,ValorParametroSistema;
				
				for(var ii = 0; ii < xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; ii++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[ii]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[ii]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametroSistema = nameTextNode.nodeValue;
					
					addOption(campo,ValorParametroSistema,IdParametroSistema);
				}
				if(IdParametroSistemaTemp == '' || selecionar){
					campo.options[Number(IdParametroSistemaTemp)].selected = true;
				} else{
					campo.options[1].selected = true;
				}
			}
			
			ContExecucao--;
		});
	}
	function verificaMudarDataBase(Codigo,IdLancamentoFinanceiro,valor){
		var posInicial = 0, posFinal = 0, campo = "";
		
		for(i = 0; i < document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,7) == 'Codigo_'){
					if(posInicial == 0){
						posInicial = i;
					}
					
					posFinal = i;
				}
			}
		}
		
		var cont = 0, aux = 0;
		
		for(i = posInicial; i <= posFinal; i += 8){
			if(document.formulario[i].value == Codigo){
				cont++;
			}
		}
		
		var posTemp	= 0;
		
		if(cont > 1){
			for(i = posInicial; i <= posFinal; i += 8){
				if(document.formulario[i].value == Codigo){
					var temp = document.formulario[i].name.split('_');
					
					if(temp[1] == IdLancamentoFinanceiro){
						posTemp = i;
						aux		= 1;
						break;
					}
				}
			}
			
			if(aux == 1 && posTemp >= posInicial){
				var verificador = true;
				
				for(i = posTemp; i >= posInicial; i -= 8){
					if(document.formulario[i].value == Codigo){
						var temp2 = document.formulario[i+4].name.split('_');
						
						if(temp2[0] != 'ReaproveitarCredito'){
							var CampoFocus = '';
							
							if(valor == 2){	//nao
								if(aux == 1){
									document.formulario[i+4].disabled = false;
									aux = 0;
								} else{
									document.formulario[i+4].disabled = true;
									document.formulario[i+4][1].selected = true;
								}
							} else if(valor == 1){ //sim
								if(aux == 1){
									if(document.formulario[i-4].name.substring(0,15) == 'VoltarDataBase_'){
										document.formulario[i-4].disabled		= false;
										document.formulario[i-4][0].selected	= true;
									}
									
									aux = 0;
									CampoFocus = document.formulario[i-4];
								} else{
									if(document.formulario[i-4] != undefined){
										if(document.formulario[i-8].value == Codigo){
											document.formulario[i-4].disabled		= true;
											document.formulario[i-4][0].selected	= true;
										}
									}
								}
							} else{
								if(aux == 1){
									document.formulario[i+4].disabled		= false;
									document.formulario[i+4][0].selected	= true;
									aux = 0;
								} else{
									document.formulario[i+4].disabled		= true;
									document.formulario[i+4][0].selected	= true;
								}
							}
							
							if(document.formulario[i-1].options[document.formulario[i-1].selectedIndex].text == "CO"){
								if(verificador){
									var LancamentoFinanceiroTipoContrato = ","+temp2[1]+",";
									temp2[0] = document.formulario[i-4].name.split('_');
									
									if(temp2[0][0] == "VoltarDataBase"){
										LancamentoFinanceiroTipoContrato = ","+temp2[0][1]+LancamentoFinanceiroTipoContrato;
									}
									
									verificador = (new RegExp(LancamentoFinanceiroTipoContrato)).test(","+document.formulario.LancamentoFinanceiroTipoContrato.value+",");
									
									if(!verificador){
										for(var ii = i-8; ii >= posInicial; ii -= 8){
											document.formulario[ii+4].disabled = true;
											
											if(document.formulario[ii+4][1] != null){
												document.formulario[ii+4][1].selected = true;
												CampoFocus = document.formulario[ii+12];
											}
										}
									}
								} else{
									document.formulario[i+4].disabled = !verificador;
									
									if(document.formulario[i+4][1]){
										document.formulario[i+4][1].selected = !verificador;
									}
								}
							}
							
							if(CampoFocus != ''){
								CampoFocus.focus();
							}
						}
					}
				}
			}
		}
	}
	function selecionar(campo,buscar){
		var table = document.getElementById('tabelaContaReceber');
		
		if(buscar == undefined){
			buscar = true;
		}
		
		if(campo.name == "todos_cr"){
			var Checked = campo.checked;
			
			for(var i = 0; i < table.rows.length; i++){
				var AccessKey = table.rows[i].accessKey;
				if(AccessKey != '' && AccessKey != undefined){
					eval("var campo = document.formulario.cr_"+AccessKey+", valor_checked = "+Checked+"; if(campo.checked != valor_checked) { campo.checked = valor_checked; selecionar(campo,false); }");
				}
			}
		} else{
			if(campo.checked){
				document.formulario.CancelarContaReceber.value += campo.name.replace(/^cr_/i,',');
			} else{
				var ContaReceber = campo.name.replace(/^cr_/i,'');
				
				Exp = new RegExp("^"+ContaReceber+",|,"+ContaReceber+",|,"+ContaReceber+"$","i");
				document.formulario.CancelarContaReceber.value = (document.formulario.CancelarContaReceber.value+",").replace(Exp,',');
			}
			document.formulario.CancelarContaReceber.value = document.formulario.CancelarContaReceber.value.replace(/^,|,,|,$/g,'');
			var tratamento = "document.formulario.todos_cr.checked = (";
			
			for(var i = 0; i < table.rows.length; i++){
				var AccessKey = table.rows[i].accessKey;
				if(AccessKey != '' && AccessKey != undefined){
					tratamento += "document.formulario.cr_"+AccessKey+".checked && ";
				}
			}
			
			tratamento = tratamento.replace(/ && $/i, '')+");";
			
			eval(tratamento);
		}
		if(buscar){
			var CancelarContaReceber = document.formulario.CancelarContaReceber.value;
			
			if(CancelarContaReceber == ''){
				CancelarContaReceber = 0;
			}
			
			busca_lancamentos_data_base(CancelarContaReceber);
		}
	}