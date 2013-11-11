	function inicia(){
		document.formulario.IdContaEventual.focus();
		status_inicial();
	}
	function validar(){
		if(validar_parcial() == true){
			var temp=0,posInicial=0,posFinal=0,valor,desp,total,perc,valorTotal=0,valorDesp=0,valorPerc=0,totalTotal=0,data;
			for(i = 0; i<document.formulario.length; i++){
				if(document.formulario[i].name != undefined){
					if(temp == 0 && document.formulario[i].name.substring(0,4) == 'parc'){
						posInicial = i;
						temp = 1;
					}
					if(document.formulario[i].name.substring(0,3) == 'Obs'){
						posFinal = i;
					}
				}
			}
			if(posInicial != 0){
				if(document.formulario.FormaCobranca.value == 2){
					for(i = posInicial; i<posFinal; i=i+5){
						valor	=	document.formulario[i].value;
						valor	=	new String(valor);
						valor	=	valor.replace('.','');
						valor	=	valor.replace('.','');
						valor	=	valor.replace('.','');
						valor	=	valor.replace(',','.');
						
						data	=	document.formulario[i+4].value
						
						if(isData(data) == false){
							mensagens(27);
							document.formulario[i+4].focus();
							return false;
						}else{
							if(formatDate(data) < formatDate(document.formulario.DataPrimeiroVencimentoIndividual.value)){
								mensagens(70);
								document.formulario[i+4].focus();	
								return false;
							}
						}
						
						valorTotal	+=	parseFloat(valor);
						
						valor	=	Arredonda(valor,2);
						valor	=	formata_float(valor,2);
						valor	=	new String(valor);
						valor	=	valor.replace('.',',');
					}
					
					valorTotal	=	Arredonda(valorTotal,2);
					
					temp	=	document.formulario.ValorTotalIndividual.value;
					
					temp	=	new String(temp);
					temp	=	temp.replace('.','');
					temp	=	temp.replace('.','');
					temp	=	temp.replace('.','');
					temp	=	temp.replace(',','.');
					
					if(valorTotal != temp){
						mensagens(71);
						document.formulario[posInicial].focus();	
						return false;
					}
				}else{
					for(i = posInicial; i<posFinal; i=i+4){
						valor	=	document.formulario[i].value;
						valor	=	new String(valor);
						valor	=	valor.replace('.','');
						valor	=	valor.replace('.','');
						valor	=	valor.replace('.','');
						valor	=	valor.replace(',','.');
						
						data	=	document.formulario[i+3].value
						
						if(isMes(data) == false){
							mensagens(27);
							document.formulario[i+3].focus();
							return false;
						}else{
							dataTemp	=	'01/'+data;
							dataTemp2	=	'01/'+document.formulario.DataPrimeiroVencimentoContrato.value;

							if(formatDate(dataTemp) < formatDate(dataTemp2)){
								mensagens(70);
								document.formulario[i+4].focus();	
								return false;
							}
						}
						
						valorTotal	+=	parseFloat(valor);
						
						valor	=	Arredonda(valor,2);
						valor	=	formata_float(valor,2);
						valor	=	new String(valor);
						valor	=	valor.replace('.',',');
					}
					
					valorTotal	=	Arredonda(valorTotal,2);
					
					temp	=	document.formulario.ValorTotalContrato.value;
					
					temp	=	new String(temp);
					temp	=	temp.replace('.','');
					temp	=	temp.replace('.','');
					temp	=	temp.replace('.','');
					temp	=	temp.replace(',','.');
					
					if(valorTotal != temp){
						mensagens(71);
						document.formulario[posInicial].focus();	
						return false;
					}
				}
			}else{
				mensagens(72);
				document.formulario.bt_simular.focus();	
				return false;
			}
			document.formulario.IdStatus.value	=	document.formulario.Status.value;
			return true;
		}
	}
	function validar_parcial(){
		if(document.formulario.IdPessoa.value==''){
			mensagens(1);
			document.formulario.IdPessoa.focus();
			return false;
		}
		if(document.formulario.DescricaoContaEventual.value==''){
			mensagens(1);
			document.formulario.DescricaoContaEventual.focus();
			return false;
		}
		if(document.formulario.FormaCobranca.value==0){
			mensagens(1);
			document.formulario.FormaCobranca.focus();
			return false;
		}
		if(document.formulario.OcultarReferencia.value==0 || document.formulario.OcultarReferencia.value==''){
			mensagens(1);
			document.formulario.OcultarReferencia.focus();
			return false;
		}
		switch(document.formulario.FormaCobranca.value){
			case '1': //Contrato				
				if(document.formulario.IdContratoAgrupador.value==0){
					mensagens(1);
					document.formulario.IdContratoAgrupador.focus();
					return false;
				}
				if(document.formulario.ValorTotalContrato.value=='' || document.formulario.ValorTotalContrato.value == 0 || document.formulario.ValorTotalContrato.value == '0,00'){
					mensagens(1);
					document.formulario.ValorTotalContrato.focus();
					return false;
				}
				if(document.formulario.QtdParcelaContrato.value==''){
					mensagens(1);
					document.formulario.QtdParcelaContrato.focus();
					return false;
				}
				if(document.formulario.DataPrimeiroVencimentoContrato.value==''){
					mensagens(1);
					document.formulario.DataPrimeiroVencimentoContrato.focus();
					return false;
				}else{
					if(isMes(document.formulario.DataPrimeiroVencimentoContrato.value) == false){		
						document.getElementById('DataPrimeiroVencimentoContrato').style.backgroundColor = '#C10000';
						document.getElementById('DataPrimeiroVencimentoContrato').style.color='#FFFFFF';
						mensagens(27);
						return false;
					}
					else{
						document.getElementById('DataPrimeiroVencimentoContrato').style.backgroundColor='#FFFFFF';
						document.getElementById('DataPrimeiroVencimentoContrato').style.color='#C10000';
						mensagens(0);
					}
				}
				break;
			case '2':	//Individual
				if(document.formulario.IdLocalCobranca.value==0){
					mensagens(1);
					document.formulario.IdLocalCobranca.focus();
					return false;
				}
				if(document.formulario.ValorTotalIndividual.value=='' || document.formulario.ValorTotalIndividual.value==0 || document.formulario.ValorTotalIndividual.value=='0,00'){
					mensagens(1);
					document.formulario.ValorTotalIndividual.focus();
					return false;
				}else{
					Valor	=	document.formulario.ValorTotalIndividual.value;
					Valor	=	new String(Valor);
					Valor	=	Valor.replace('.','');
					Valor	=	Valor.replace('.','');	
					Valor	=	Valor.replace('.','');	
					Valor	=	Valor.replace(',','.');	
					
					Despesa	=	document.formulario.ValorDespesaLocalCobranca.value;
					Despesa	=	new String(Despesa);
					Despesa	=	Despesa.replace('.','');
					Despesa	=	Despesa.replace('.','');	
					Despesa	=	Despesa.replace('.','');	
					Despesa	=	Despesa.replace(',','.');
					
					Total	=	parseFloat(Valor) + parseFloat(Despesa);
					
					if(Total < document.formulario.ValorCobrancaMinima.value){
						mensagens(80);
						document.formulario.ValorTotalIndividual.focus();
						return false;
					}
				}
				if(document.formulario.QtdParcelaIndividual.value==''){
					mensagens(1);
					document.formulario.QtdParcelaIndividual.focus();
					return false;
				}
				if(document.formulario.DataPrimeiroVencimentoIndividual.value==''){
					mensagens(1);
					document.formulario.DataPrimeiroVencimentoIndividual.focus();
					return false;
				}else{
					if(isData(document.formulario.DataPrimeiroVencimentoIndividual.value) == false){		
						document.getElementById('DataPrimeiroVencimentoIndividual').style.backgroundColor = '#C10000';
						document.getElementById('DataPrimeiroVencimentoIndividual').style.color='#FFFFFF';
						mensagens(27);
						return false;
					}
					else{
						document.getElementById('DataPrimeiroVencimentoIndividual').style.backgroundColor='#FFFFFF';
						document.getElementById('DataPrimeiroVencimentoIndividual').style.color='#C10000';
						mensagens(0);
					}
				}
				break;
		}
		return true;
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
				document.formulario.bt_confirmar.disabled 	= true;
				document.formulario.bt_cancelar.disabled 	= true;
				document.formulario.bt_enviar.disabled 		= true;
				document.formulario.bt_imprimir.disabled 	= true;
				document.formulario.bt_simular.disabled 	= false;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_inserir.disabled 	= true;
				
				switch(document.formulario.IdStatus.value){
					case '2': /*Confirmado*/
						document.formulario.bt_confirmar.disabled 	= true;
						document.formulario.bt_cancelar.disabled 	= false;
						document.formulario.bt_alterar.disabled 	= true;
						document.formulario.bt_excluir.disabled 	= true;
						document.formulario.bt_simular.disabled 	= true;
						
						/*Individual*/
						if(document.formulario.FormaCobranca.value == 2){
							document.formulario.bt_enviar.disabled 		= false;
							document.formulario.bt_imprimir.disabled 	= false;
						}else{
						/*Contrato*/	
							document.formulario.bt_enviar.disabled 		= true;
							document.formulario.bt_imprimir.disabled 	= true;
						}
						break;
					case '0': /*Cancelado*/
						document.formulario.bt_confirmar.disabled 	= true;
						document.formulario.bt_cancelar.disabled 	= true;
						document.formulario.bt_alterar.disabled 	= true;
						document.formulario.bt_excluir.disabled 	= false;
						document.formulario.bt_simular.disabled 	= true;
						document.formulario.bt_enviar.disabled 		= true;
						document.formulario.bt_imprimir.disabled 	= true;
						break;
					default:
						document.formulario.bt_confirmar.disabled 	= false;
						document.formulario.bt_cancelar.disabled 	= true;
						document.formulario.bt_alterar.disabled 	= false;
						document.formulario.bt_excluir.disabled 	= false;
						document.formulario.bt_simular.disabled 	= false;
						document.formulario.bt_enviar.disabled 		= true;
						document.formulario.bt_imprimir.disabled 	= true;
						break;
				}
			}
		}	
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFFFFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return true;
		}	
	}
	function simular(){
		if(validar_parcial()== true){
			
			var FormaCobranca	=	document.formulario.FormaCobranca.value;
			
			while(document.getElementById('tabelaVencimento').rows.length > 2){
				document.getElementById('tabelaVencimento').deleteRow(1);
			}
			document.getElementById('cp_Vencimento').style.display	=	'block';
			
			var tam, linha, c0, c1, c2, c3, c4, c5, tabindex, QtdParcela, valor, perc, desp, total, valorTotal=0, percTotal=0;
			var valorT, despTotal=0, totalTotal=0, data, dianovo='', mes, ano, qtdDiasMes, dataI, dia='', i, cont=0, temp, ii=1;
			var ArrayData	= new Array();
			
			switch(FormaCobranca){
				case '1': //Contrato
					QtdParcela	=	parseInt(document.formulario.QtdParcelaContrato.value);
					dataI		=	document.formulario.DataPrimeiroVencimentoContrato.value;
					document.getElementById('tabValorDesp').style.display	=	'none';
					mes		=	dataI.substring(0,2);
					ano		=	dataI.substring(3,7);
					break
				case '2': //Individual
					QtdParcela	=	parseInt(document.formulario.QtdParcelaIndividual.value);
					dataI		=	document.formulario.DataPrimeiroVencimentoIndividual.value;
					dia			=	dataI.substring(0,2);
					mes		=	dataI.substring(3,5);
					ano		=	dataI.substring(6,10);
					document.getElementById('tabValorDesp').style.display	=	'block';
					break;
			}
			
			i			=	parseFloat(mes);
			temp		=	i+QtdParcela;
			while(i<temp){
				if(i < 13){
					mes	=	i;
				}else{
					if(cont == 0){
						mes	 =	1;
						ano++;
						cont = 1;
					}else{
						mes++;
					}
				}
				
				if(mes == 12) cont = 0;
				
				qtdDiasMes	=	numDiasMes(ano, mes);
				
				if (mes < 10)	mes= "0" + mes;
				
				if(FormaCobranca==2){
					if(parseInt(dia) > parseInt(qtdDiasMes))	dianovo = parseInt(qtdDiasMes);
					else										dianovo = dia;
					
					data	=	mostraDataFim(qtdDiasMes,dianovo+"/"+mes+"/"+ano);
				}else{
					data	=	mes+"/"+ano;
				}
				
				ArrayData[ii]	=	data;
				i++;
				ii++
			}
			
			ii=1;
			for(i=1;i<=QtdParcela;i++){
				tam 	= document.getElementById('tabelaVencimento').rows.length;
				linha	= document.getElementById('tabelaVencimento').insertRow(tam-1);
				
				if(tam%2 != 0){
					linha.style.backgroundColor = "#E2E7ED";
				}
				
				tabindex =	10 * i;
				
				linha.accessKey = i; 
				
				c0	= linha.insertCell(0);	
				c1	= linha.insertCell(1);	
				c2	= linha.insertCell(2);	
				c3	= linha.insertCell(3);	
				c4	= linha.insertCell(4);
				
				if(FormaCobranca == 2){
					c5	= linha.insertCell(5);
					valorT	=	document.formulario.ValorTotalIndividual.value;
				}else{
					valorT	=	document.formulario.ValorTotalContrato.value;
				
				}
				
				valorT	=	new String(valorT);
				valorT	=	valorT.replace('.','');
				valorT	=	valorT.replace('.','');
				valorT	=	valorT.replace('.','');
				valorT	=	valorT.replace(',','.');
				
				valor	=	new String(valor);
				valor	=	valor.replace('.','');
				valor	=	valor.replace('.','');
				valor	=	valor.replace('.','');
				valor	=	valor.replace(',','.');
				
				if(FormaCobranca == 2){
					desp	=	document.formulario.ValorDespesaLocalCobranca.value;
					desp	=	new String(desp);
					desp	=	desp.replace('.','');
					desp	=	desp.replace('.','');
					desp	=	desp.replace('.','');
					desp	=	desp.replace(',','.');
					desp	=	parseFloat(desp);
				}
					
				valor	=	parseFloat(valorT) / QtdParcela;
				valor	=	Arredonda(valor,2);
				
				
				perc	=	(100 * parseFloat(valor))/parseFloat(valorT);
				
				if(FormaCobranca == 2){
					total	=	parseFloat(valor) + parseFloat(desp);
				}else{
					total	=	parseFloat(valor);
				}
				
				valorTotal	+=	valor;
				
				if(FormaCobranca == 2){
					despTotal	+=  desp;
					
					desp	=	Arredonda(desp,2);
					desp	=	formata_float(desp,2);
					desp	=	new String(desp);
					desp	=	desp.replace('.',',');
				}
				totalTotal	+= 	total;
				percTotal	+=  perc;
				
				
				valor	=	formata_float(valor,2);
				valor	=	new String(valor);
				valor	=	valor.replace('.',',');
				
				perc	=	Arredonda(perc,2);
				perc	=	formata_float(perc,2);
				perc	=	new String(perc);
				perc	=	perc.replace('.',',');
				
				total	=	Arredonda(total,2);
				total	=	formata_float(total,2);
				total	=	new String(total);
				total	=	total.replace('.',',');
				
				data	=	ArrayData[ii];
				
				c0.innerHTML = i;
				c0.style.textAlign = "center";
				
				c1.innerHTML = "<input class='valor' style='margin:0; width:150px;' name='parcValor_"+i+"' value='"+valor+"' maxlength='16' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out'); calcula_valor("+i+")\" onkeypress='reais(this,event)' onkeydown='backspace(this,event)' tabindex="+(tabindex)+">&nbsp;&nbsp;";
				c1.style.textAlign = "right";
				
				c2.innerHTML = "<input class='valor' style='margin:0; width:60px' name='parcPerc_"+i+"' value='"+perc+"'  maxlength='6' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'float')\" tabindex="+(tabindex+1)+" onChange=\"calcula_valor("+i+",this.value)\">&nbsp;&nbsp;";
				c2.style.textAlign = "right";
				
				if(FormaCobranca==2){
					c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcDesp_"+i+"' value='"+desp+"' maxlength='16' onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out'); calcula_valor("+i+")\" tabindex="+(tabindex+2)+">&nbsp;&nbsp;";
					c3.style.textAlign = "right";
				
					c4.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+i+"' value='"+total+"' readOnly>&nbsp;&nbsp;";
					c4.style.textAlign = "right";
				
					c5.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+i+"' value='"+data+"' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'date')\" tabindex="+(tabindex+3)+">";
				}else{
					c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+i+"' value='"+total+"' readOnly>&nbsp;&nbsp;";
					c3.style.textAlign = "right";
				
					c4.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+i+"' value='"+data+"' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'mes')\" tabindex="+(tabindex+3)+">";
				}
				ii++
				
			}
			if(FormaCobranca == 2){	
				document.getElementById('tableDataVenc').innerHTML			= 	'Data Vencimento';
				document.getElementById('totalValorDespesa').style.display	=	'block';
				document.getElementById('totalValorDespesa').innerHTML		=	formata_float(Arredonda(despTotal,2),2).replace(".",",");
			}else{
				document.getElementById('tableDataVenc').innerHTML			=	'Mês Referência';
				document.getElementById('totalValorDespesa').style.display	=	'none';
			}
			document.getElementById('totalVencimentos').innerHTML		=	"Total: "+(i-1);
			document.getElementById('totalValor').innerHTML				=	formata_float(Arredonda(valorTotal,2),2).replace(".",",");
			document.getElementById('totalPercentual').innerHTML		=	formata_float(Arredonda(percTotal,2),2).replace(".",",");
			document.getElementById('totalValorTotal').innerHTML		=	formata_float(Arredonda(totalTotal,2),2).replace(".",",");
		}
		else{
			return false;
		}
	}
	function excluir(IdContaEventual,IdStatus){
		if(IdContaEventual == ''){
			var IdContaEventual = document.formulario.IdContaEventual.value;
		}
		if(IdStatus == 2){
			return false;
		}
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
			}
			var xmlhttp   = false;
			if (window.XMLHttpRequest) { // Mozilla, Safari,...
				xmlhttp = new XMLHttpRequest();
		        if(xmlhttp.overrideMimeType){
		    //    	xmlhttp.overrideMimeType('text/xml');
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
	
			url = "files/excluir/excluir_conta_eventual.php?IdContaEventual="+IdContaEventual;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 
	
				// Carregando...
				carregando(true);
	
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							document.formulario.Erro.value = xmlhttp.responseText;
							if(parseInt(xmlhttp.responseText) == 7){
								document.formulario.Acao.value 	= 'inserir';
								url = 'cadastro_conta_eventual.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdContaEventual == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[7].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
									}
									document.getElementById('tableListarValor').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
								}									
							}
						}
					}
					// Fim de Carregando
					carregando(false);
				}
				return true;
			}
			xmlhttp.send(null);
		}
	} 
	function calcula_valor(parcela,percentual){
		
		var QtdParcela, valor=0, perc=0, desp=0, total=0, valorTotal=0, percTotal=0;
		var valorT, despTotal=0, totalTotal=0, i, cont=1, pos;
		var posInicial=0,posFinal=0,temp=0,valorTemp=0, despTemp=0, despT;
		
		if(percentual == undefined)	percentual= '';
		
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(temp == 0 && document.formulario[i].name.substring(0,4) == 'parc'){
					posInicial = i;
					temp = 1;
				}
				if(document.formulario[i].name.substring(0,3) == 'Obs'){
					posFinal = i;
				}
			}
		}
		
		var FormaCobranca	=	document.formulario.FormaCobranca.value;
		var tam	=	0;
		
		switch(FormaCobranca){
			case '1': //Contrato
				QtdParcela	=	document.formulario.QtdParcelaContrato.value;
				valorT		=	document.formulario.ValorTotalContrato.value;
				tam			=	4;
				break;
			case '2': //Individual
				QtdParcela	=	document.formulario.QtdParcelaIndividual.value;
				valorT		=	document.formulario.ValorTotalIndividual.value;
				tam			=	5;
				
				desp	=	document.formulario.ValorDespesaLocalCobranca.value;
				desp	=	new String(desp);
				desp	=	desp.replace('.','');
				desp	=	desp.replace('.','');
				desp	=	desp.replace('.','');
				desp	=	desp.replace(',','.');
				break;
		}
		
		valorT	=	new String(valorT);
		valorT	=	valorT.replace('.','');
		valorT	=	valorT.replace('.','');
		valorT	=	valorT.replace('.','');
		valorT	=	valorT.replace(',','.');
		
		temp = 0;
		if(posInicial != 0){
			for(i = posInicial; i<posFinal; i=i+tam){
				pos	= document.formulario[i].name.split('_');
				
				if(pos[1] == parcela || pos[1] < parcela){
					valor	=	document.formulario[i].value;
				}
				
				valor	=	new String(valor);
				valor	=	valor.replace('.','');
				valor	=	valor.replace('.','');
				valor	=	valor.replace('.','');
				valor	=	valor.replace(',','.');
				
				valorTemp	=	valor;
				
				if(FormaCobranca==2){
					desp	=	document.formulario[i+2].value;
					desp	=	new String(desp);
					desp	=	desp.replace('.','');
					desp	=	desp.replace('.','');
					desp	=	desp.replace('.','');
					desp	=	desp.replace(',','.');
				}
				
				if(pos[1] > parcela){
					valor	=	valor.replace(',','.');
					
					if(temp == 0){	
						if(valor != 0){
							valor	=	valorTotal;
						}
						valorTemp	=	valorT	-	valor;
						
						valor	=	parseFloat(valorTemp) / (QtdParcela-parcela);
						temp	=	1;	
					}
				}
				
				if(percentual != '' && pos[1] == parcela){
					perc	=	percentual;
					perc	=	new String(perc);
					perc	=	perc.replace('.','');
					perc	=	perc.replace('.','');
					perc	=	perc.replace('.','');
					perc	=	perc.replace(',','.');
					
					valor	=	(perc*valorT)/100;
				}else{
					perc	=	(100 * parseFloat(valor))/parseFloat(valorT);
				}
				
				if(valor < 0)	valor	=	0;
				if(perc < 0)	perc	=	0;
				
				if(FormaCobranca==2){
					if(desp < 0)	desp	=	0;
					
					desp	=	Arredonda(desp,2);
					desp	=	formata_float(desp,2);
				}
				
				valor	=	Arredonda(valor,2);
				valor	=	formata_float(valor,2);
					
				perc	=	Arredonda(perc,2);
				perc	=	formata_float(perc,2);
				
				if(FormaCobranca==2){	
					total	=	parseFloat(valor) + parseFloat(desp);	
				}
				if(FormaCobranca==1){	
					total	=	parseFloat(valor);	
				}
				
				if(total < 0)	total	=	0;
				
				total	=	Arredonda(total,2);
				total	=	formata_float(total,2);
				
				valorTotal	+=	parseFloat(valor);
				percTotal	+=  parseFloat(perc);
				
				if(FormaCobranca==2){	
					despTotal  +=  parseFloat(desp);
					desp		=	new String(desp);
					desp		=	desp.replace('.',',');
				}
				
				totalTotal	+= 	parseFloat(total);
				
				valor	=	new String(valor);
				valor	=	valor.replace('.',',');
					
				perc	=	new String(perc);
				perc	=	perc.replace('.',',');
					
				total	=	new String(total);
				total	=	total.replace('.',',');
				
				document.formulario[i].value	=	valor;
				document.formulario[i+1].value	=	perc;
				
				if(FormaCobranca==2){	
					document.formulario[i+2].value	=	desp;
					document.formulario[i+3].value	=	total;
				}else{
					document.formulario[i+2].value	=	total;
				}
				
				
				cont++;
			}

			
			temp	=	new String(percTotal);
			temp	=	temp.split('.');
			
			if(temp[1] > 0 && temp[0] == 100){
				percTotal	=	temp[0]+'.00';	
			}
			
			document.getElementById('totalValor').innerHTML				=	formata_float(Arredonda(valorTotal,2),2).replace(".",",");
			document.getElementById('totalPercentual').innerHTML		=	formata_float(Arredonda(percTotal,2),2).replace(".",",");
			
			if(FormaCobranca==2){	
				document.getElementById('totalValorDespesa').display		=	'block';
				document.getElementById('totalValorDespesa').innerHTML		=	formata_float(Arredonda(despTotal,2),2).replace(".",",");
			}else{
				document.getElementById('totalValorDespesa').display		=	'none';
			}
			document.getElementById('totalValorTotal').innerHTML		=	formata_float(Arredonda(totalTotal,2),2).replace(".",",");
		}
	}
	function listar_parcela_conta(IdContaEventual){
		while(document.getElementById('tabelaVencimento').rows.length > 2){
			document.getElementById('tabelaVencimento').deleteRow(1);
		}
		document.getElementById('cp_Vencimento').style.display	=	'block';
			
		if(IdContaEventual == ''){
			IdContaEventual = 0;
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
	    
	   	url = "../administrativo/xml/conta_eventual_parcela.php?IdContaEventual="+IdContaEventual;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('cp_Vencimento').style.display	=	'none';
					}else{
						var valorTotal=0,despTotal=0,percTotal=0,totalTotal=0, valor, perc, desc, total, QtdParcela;
						
						QtdParcela	=	xmlhttp.responseXML.getElementsByTagName("IdContaEventualParcela").length;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaEventualParcela").length; i++){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("FormaCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var FormaCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaEventualParcela")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContaEventualParcela = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("Vencimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Vencimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorTotal = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaVencimento').rows.length;
							linha	= document.getElementById('tabelaVencimento').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							tabindex =	10 * i;
							
							linha.accessKey = i; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);	
							c4	= linha.insertCell(4);
							
							if(FormaCobranca == 2){
								c5	= linha.insertCell(5);
								
								if(i==0){
									document.getElementById('tabValorDesp').style.display		=	'block';
									document.getElementById('totalValorDespesa').style.display	=	'block';
									document.getElementById('tableDataVenc').innerHTML			=	'Data Vencimento';
								}
							}else{
								if(i==0){
									document.getElementById('tabValorDesp').style.display		=	'none';
									document.getElementById('totalValorDespesa').style.display	=	'none';
									document.getElementById('tableDataVenc').innerHTML			=	'Mês Referência';
								}
							}
							
							valorT	=	ValorTotal;
							
							valor	=	parseFloat(Valor) / QtdParcela;
							perc	=	(100 * parseFloat(Valor))/parseFloat(valorT);
							total	=	parseFloat(Valor) + parseFloat(ValorDespesaLocalCobranca);
							
							valorTotal	+=	parseFloat(Valor);
							percTotal	+=  parseFloat(perc);
							
							if(FormaCobranca == 2){
								despTotal	+=  parseFloat(ValorDespesaLocalCobranca);
							}
							
							totalTotal	+= 	parseFloat(total);
							
							c0.innerHTML = IdContaEventualParcela;
							c0.style.textAlign = "center";
							
							c1.innerHTML = "<input class='valor' style='margin:0; width:150px;' name='parcValor_"+IdContaEventualParcela+"' value='"+formata_float(Arredonda(Valor,2),2).replace(".",",")+"' maxlength='16' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out'); calcula_valor("+(i+1)+")\" onkeypress='reais(this,event)' onkeydown='backspace(this,event)' tabindex="+(tabindex)+">&nbsp;&nbsp;";
							c1.style.textAlign = "right";
							
							c2.innerHTML = "<input class='valor' style='margin:0; width:60px' name='parcPerc_"+IdContaEventualParcela+"' value='"+formata_float(Arredonda(perc,2),2).replace(".",",")+"'  maxlength='6' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'float')\" tabindex="+(tabindex+1)+" onChange=\"calcula_valor("+(i+1)+",this.value)\">&nbsp;&nbsp;";
							c2.style.textAlign = "right";
							
							if(FormaCobranca == 2){
								c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcDesp_"+IdContaEventualParcela+"' value='"+formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",")+"' maxlength='12' onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out'); calcula_valor("+(i+1)+")\" tabindex="+(tabindex+2)+">&nbsp;&nbsp;";
								c3.style.textAlign = "right";
								
								c4.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+IdContaEventualParcela+"' value='"+formata_float(Arredonda(total,2),2).replace(".",",")+"' readOnly>&nbsp;&nbsp;";
								c4.style.textAlign = "right";
								
								c5.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+IdContaEventualParcela+"' value='"+dateFormat(Vencimento)+"' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'date')\" tabindex="+(tabindex+3)+">";
							}else{
								c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+IdContaEventualParcela+"' value='"+formata_float(Arredonda(total,2),2).replace(".",",")+"' readOnly>&nbsp;&nbsp;";
								c3.style.textAlign = "right";
								
								c4.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+IdContaEventualParcela+"' value='"+Vencimento+"' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'mes')\" tabindex="+(tabindex+3)+">";
							}
						}
						document.getElementById('totalVencimentos').innerHTML		=	"Total: "+(i);
						document.getElementById('totalValor').innerHTML				=	formata_float(Arredonda(valorTotal,2),2).replace(".",",");
						document.getElementById('totalPercentual').innerHTML		=	formata_float(Arredonda(percTotal,2),2).replace(".",",");
						
						if(FormaCobranca == 2){
							document.getElementById('totalValorDespesa').innerHTML		=	formata_float(Arredonda(despTotal,2),2).replace(".",",");
						}
						
						document.getElementById('totalValorTotal').innerHTML		=	formata_float(Arredonda(totalTotal,2),2).replace(".",",");
					}
				}
			} 
			if(document.formulario.Erro.value != 0){
				scrollWindow('bottom');
			}else{
				scrollWindow('top');
			}
			// Fim de Carregando
			carregando(false);
			return true;
		}
		xmlhttp.send(null);
		
	}
	function cadastrar(acao){
		document.formulario.Acao.value	=	acao;
		if(acao == 'alterar' || acao == 'inserir' || acao == 'confimar'){
			if(validar(acao)==true){
				document.formulario.submit();
			}
		}else{
			document.formulario.submit();
		}
	}
	function busca_valor_minimo(IdLocalCobranca){
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
	    
	   	url = "../administrativo/xml/local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCobrancaMinima")[0]; 
						nameTextNode = nameNode.childNodes[0];
						ValorCobrancaMinima = nameTextNode.nodeValue;
						
						document.formulario.ValorCobrancaMinima.value	=	ValorCobrancaMinima;
					}
				}
			} 
		}
		xmlhttp.send(null);	
	}
	function busca_forma_cobranca(valor){
		while(document.getElementById('tabelaVencimento').rows.length > 2){
			document.getElementById('tabelaVencimento').deleteRow(1);
		}
		
		document.getElementById('cp_Vencimento').style.display		=	'none';
		
		switch(valor){
			case '1': //Em Contrato
				document.getElementById('totalValorDespesa').style.display	=	'none';
				document.getElementById('tableDataVenc').innerHTML			=	'Mês Referência';
				
				
				listar_contrato(document.formulario.IdPessoa.value);
				
				if(document.formulario.ValorTotal.value!='0,00' && document.formulario.ValorTotal.value!=''){
					document.formulario.ValorTotalContrato.value	=	document.formulario.ValorTotal.value;
					document.formulario.ValorTotalIndividual.value	=	'';
				}else{
					document.formulario.ValorTotalContrato.value	=	'0,00';
				}
				if(document.formulario.QtdParcela.value != ''){
					document.formulario.QtdParcelaContrato.value	=	document.formulario.QtdParcela.value;
					document.formulario.QtdParcelaIndividual.value	=	'';
				}
				document.getElementById('titFormaCobranca').style.display	=	'none';
				document.getElementById('titContrato').style.display		=	'block';
				document.getElementById('titIndividual').style.display		=	'none';	
				break;
			case '2': //Individual				
				if(document.formulario.ValorTotal.value!='0,00' && document.formulario.ValorTotal.value!=''){
					document.formulario.ValorTotalContrato.value	=	'';
					document.formulario.ValorTotalIndividual.value	=	document.formulario.ValorTotal.value;
				}else{
					document.formulario.ValorTotalIndividual.value	= '0,00';
				}
				if(document.formulario.ValorDespesaLocalCobranca.value!='0,00' && document.formulario.ValorDespesaLocalCobranca.value!=''){
					document.formulario.ValorDespesaLocalCobranca.value	=	'0,00';
				}
				if(document.formulario.QtdParcela.value != ''){
					document.formulario.QtdParcelaContrato.value	=	'';
					document.formulario.QtdParcelaIndividual.value	=	document.formulario.QtdParcela.value;
				}
				document.getElementById('titFormaCobranca').style.display		=	'none';
				document.getElementById('titContrato').style.display			=	'none';
				document.getElementById('titIndividual').style.display			=	'block';
				
				break;
			default:
				document.getElementById('titFormaCobranca').style.display		=	'block';
				document.getElementById('titContrato').style.display			=	'none';
				document.getElementById('titIndividual').style.display			=	'none';
		}
	}
	
	function listar_contrato(IdPessoa,IdContratoAgrupadorTemp){
		if(IdPessoa == ''){
			while(document.formulario.IdContratoAgrupador.options.length > 0){
				document.formulario.IdContratoAgrupador.options[0] = null;
			}
			return false;
		}
		if(IdContratoAgrupadorTemp == undefined){
			IdContratoAgrupadorTemp = '';
		}
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	    //    	xmlhttp.overrideMimeType('text/xml');
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
		
		url = "xml/contrato.php?IdPessoa="+IdPessoa+"&IdStatusExc=1";
		
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){	
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdContratoAgrupador.options.length > 0){
							document.formulario.IdContratoAgrupador.options[0] = null;
						}
						
						document.formulario.IdContratoAgrupador.disabled	=	true;
					}else{
						while(document.formulario.IdContratoAgrupador.options.length > 0){
							document.formulario.IdContratoAgrupador.options[0] = null;
						}
						
						document.formulario.IdContratoAgrupador.disabled	=	false;
						
						addOption(document.formulario.IdContratoAgrupador,"","0");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoServico = nameTextNode.nodeValue;
							
							var Descricao	=	"("+IdContrato+") "+DescricaoServico;
							
							addOption(document.formulario.IdContratoAgrupador,Descricao,IdContrato);
						}
						if(IdContratoAgrupadorTemp!=''){
							for(ii=0;ii<document.formulario.IdContratoAgrupador.length;ii++){
								if(document.formulario.IdContratoAgrupador[ii].value == IdContratoAgrupadorTemp){
									document.formulario.IdContratoAgrupador[ii].selected = true;
									break;
								}
							}
						}else{
							document.formulario.IdContratoAgrupador[0].selected = true;
						}
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	} 

