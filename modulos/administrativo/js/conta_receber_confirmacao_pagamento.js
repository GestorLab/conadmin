	function excluir(IdContaReceber){
		if(confirm("ATENCAO!\n\nVoce esta prestes a autorizar este registro.\nDeseja continuar?","SIM","NAO") == true){
			var xmlhttp = false;
			
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
    		
   			url = "./files/editar/cancelar_conta_receber_confirmacao_pagamento.php?IdContaReceber="+IdContaReceber;
			xmlhttp.open("GET", url,true);
			xmlhttp.onreadystatechange = function(){ 
				// Carregando...
				carregando(true);
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						var numMsg = parseInt(xmlhttp.responseText);
						if(numMsg == 67){ // registro cancelado com sucesso	
							for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
								var temp = document.getElementById('tableListar').rows[i].cells[0].innerHTML.split(">");
								temp = temp[1].split("<");
								
								var aux = 0;
								if(document.getElementById('tableListar').rows[i].accessKey == IdContaReceber){
									var vsoma = document.getElementById("vsoma_"+IdContaReceber).innerHTML;
									var totalsoma = document.getElementById("valorsoma").innerHTML;
									var vrecebido = document.getElementById("vrecebido_"+IdContaReceber).innerHTML;
									var totalrecebido = document.getElementById("valorrecebidosoma").innerHTML;
									var soma1 = 0;
									var soma2 = 0;
									if(vrecebido[0] == "("){
										vrecebido = vrecebido.replace(")","");
										vrecebido = vrecebido.replace("(","");
									}
									totalsoma = totalsoma.replace(/\./g, "");
									totalsoma = totalsoma.replace(/,/i, ".");
									totalsoma = parseFloat(totalsoma);
									
									vsoma = vsoma.replace(/\./g, "");
									vsoma = vsoma.replace(/,/i, ".");
									vsoma = parseFloat(vsoma);
									
									vrecebido = vrecebido.replace(/\./g, "");
									vrecebido = vrecebido.replace(/,/i, ".");
									vrecebido = parseFloat(vrecebido);
									
									totalrecebido = totalrecebido.replace(/\./g, "");
									totalrecebido = totalrecebido.replace(/,/i, ".");
									totalrecebido = parseFloat(totalrecebido);
									
									soma1 = totalsoma - vsoma;
									
									if(document.filtro.filtro_soma_todos.value == 1){
										soma2 = totalrecebido - vrecebido;
									} else{
										soma2 = totalrecebido;
									}
									document.getElementById('tableListar').deleteRow(i);
									tableMultColor('tableListar',document.filtro.corRegRand.value);
									aux = 1;
									if(aux == 1){
										soma1 = formata_float(Arredonda((soma1), 2), 2).replace(/\./, ',');
										soma2 = formata_float(Arredonda((soma2), 2), 2).replace(/\./, ',');
										
										document.getElementById("valorsoma").innerHTML = soma1;
										document.getElementById("valorrecebidosoma").innerHTML = soma2;
										
										document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
									
									}
									break;
								}
								if(IdContaReceber == temp[0]){
									document.getElementById('tableListar').rows[i].cells[11].innerHTML = document.getElementById('tableListar').rows[i].cells[11].innerHTML.replace(/ico_del.gif/g,"ico_del_c.gif");
									break;
								}
							}
							mensagens(numMsg);	
						}else{
							if(numMsg == 68){ // erro ao cancelar registro
								mensagens(numMsg);
							}
						}
					}//if
					// Fim de Carregando
					carregando(false);
				}//if
				return true;
				
			}
			xmlhttp.send(null);
		}
	}
	function calcular_multa(valor){
		document.formulario.ValorDescontoRecebimento.value	=	'0,00';
		document.formulario.ValorMoraMulta.value			=	'0,00';
		document.formulario.ValorOutrasDespesas.value		=	'0,00';
		
		calculaValor('ContaRecebimento');
		
		if((document.formulario.Acao.value == 'receber' ||  document.formulario.Acao.value == 'imprimir') && (document.formulario.IdLocalRecebimento.value != '' || document.formulario.DataRecebimento.value != '')){
			if(document.formulario.CalcularMulta.value == 1){
				document.getElementById('cpValorMoraMulta').style.color		=	'#000';
				document.formulario.ValorMoraMulta.readOnly					=	true;
			}else{
				document.getElementById('cpValorMoraMulta').style.color		=	'#C10000';
				document.formulario.ValorMoraMulta.readOnly					=	false;
			}
		}else{
			document.getElementById('cpValorMoraMulta').style.color		=	'#000';
			document.formulario.ValorMoraMulta.readOnly					=	false;
		}
		calculaValor('ContaReceberRecebimento');
	}
	
	function calculaValor(conta,nome){
		if(conta == 'ContaReceber'){
			var desc, desp, valor, receb, mora, moraC;
	
			if(document.formulario.ValorDesconto.value == '')				document.formulario.ValorDesconto.value = 0;
			if(document.formulario.ValorDespesas.value == '')				document.formulario.ValorDespesas.value = 0;
			if(document.formulario.ValorContaReceber.value == '')			document.formulario.ValorContaReceber.value = 0;
			if(document.formulario.ValorDescontoRecebimento.value == '')	document.formulario.ValorDescontoRecebimento.value = 0;
			if(document.formulario.ValorOutrasDespesas.value == '')			document.formulario.ValorOutrasDespesas.value = 0;
			if(document.formulario.ValorMoraMulta.value == '')				document.formulario.ValorMoraMulta.value = 0;
	
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
	
			descR	=	document.formulario.ValorDescontoRecebimento.value;
			descR	=	new String(descR);
			descR	=	descR.replace('.','');
			descR	=	descR.replace('.','');
			descR	=	descR.replace(',','.');
			
			despO	=	document.formulario.ValorOutrasDespesas.value;
			despO	=	new String(despO);
			despO	=	despO.replace('.','');
			despO	=	despO.replace('.','');
			despO	=	despO.replace(',','.');
			
			mora	=	document.formulario.ValorMoraMulta.value;
			mora	=	new String(mora);
			mora	=	mora.replace('.','');
			mora	=	mora.replace('.','');
			mora	=	mora.replace(',','.');
			
			valor	=	parseFloat(valor) + parseFloat(desp) - parseFloat(desc);
			receb	=	parseFloat(valor);
			
			if(descR > 0){
				receb	=	parseFloat(receb) - parseFloat(descR);
			}
			
			if(despO > 0){
				receb	=	parseFloat(receb) + parseFloat(despO);
			}
			
			if(mora > 0){
				receb	=	parseFloat(receb) + parseFloat(mora);
			}
			
			valor	=	Arredonda(valor,2);
			valor	=	formata_float(valor,2);
			
			receb	=	Arredonda(receb,2);
			receb	=	formata_float(receb,2);
			
			document.formulario.ValorFinal.value		=	valor.replace('.',',');
			document.formulario.ValorRecebimento.value	=	valor.replace('.',',');
			document.formulario.ValorReceber.value		=	receb.replace('.',',');
		}else{
			var desc, desp, valor, valorFinal, moraC, mora;
			
			if(document.formulario.ValorDescontoRecebimento.value == '')	document.formulario.ValorDescontoRecebimento.value = 0;
			if(document.formulario.ValorOutrasDespesas.value == '')			document.formulario.ValorOutrasDespesas.value = 0;
			if(document.formulario.ValorMoraMulta.value == '')				document.formulario.ValorMoraMulta.value = 0;
				
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
			
			if(document.formulario.CalcularMulta.value == 1){
				var DataRecebimentoTemp = document.formulario.DataRecebimento.value.split('/');
				var DataRecebimento = (DataRecebimentoTemp[2]+DataRecebimentoTemp[1]+DataRecebimentoTemp[0]).replace(/NaN/gi, '');
				
				var DataVencimentoTemp = document.formulario.DataVencimento.value.split('/');
				var DataVencimento = (DataVencimentoTemp[2]+DataVencimentoTemp[1]+DataVencimentoTemp[0]).replace(/NaN/gi, '');
				
//				if(parseFloat(document.formulario.BaseVencimento.value) > 0){
				if(DataVencimento < DataRecebimento){
//					moraC	= 	(parseFloat(valor) * parseFloat(document.formulario.PercentualMulta.value) / 100) + (parseFloat(valor) * parseFloat(document.formulario.PercentualJurosDiarios.value) / 100 * parseFloat(document.formulario.BaseVencimento.value));
					moraC	= 	(parseFloat(valor) * parseFloat(document.formulario.PercentualMulta.value) / 100) + (parseFloat(valor) * parseFloat(document.formulario.PercentualJurosDiarios.value) / 100 * parseFloat(difDias(document.formulario.DataRecebimento.value, document.formulario.DataVencimento.value)));
					valor	=	parseFloat(valor) +	parseFloat(moraC);
					mora	=	0;
					
					document.formulario.ValorMoraMulta.value	=	formata_float(Arredonda(moraC,2),2).replace(".",",");
					document.formulario.ValorMoraMulta.readOnly	=	true;
				}
			}else{
				document.formulario.ValorMoraMulta.readOnly	=	false;	
			}
			
			valor	=	parseFloat(valor) + parseFloat(desp) + parseFloat(mora) - parseFloat(desc);
			valor	=	Arredonda(valor,2);
			valor	=	formata_float(valor,2);
				
			document.formulario.ValorReceber.value	=	valor.replace('.',',');
		}
	}
	
	function listar_filtro(e){
		var e = e || event;
		var k = e.keyCode || e.which;
		if (k==13){
			if(validar_filtro() == true){
			 	document.filtro.submit();
			}
		}
	}
	
	function validar_filtro(){
		if(document.filtro.chNome.checked  == true)		document.filtro.chNome.value  = 1; 		else	document.filtro.chNome.value	=	0;
		if(document.filtro.chRazao.checked == true) 	document.filtro.chRazao.value = 1; 		else  	document.filtro.chRazao.value	=	0;
		if(document.filtro.chFone1.checked == true) 	document.filtro.chFone1.value = 1; 		else  	document.filtro.chFone1.value	=	0;
		if(document.filtro.chFone2.checked == true) 	document.filtro.chFone2.value = 1; 		else  	document.filtro.chFone2.value	=	0;
		if(document.filtro.chFone3.checked == true) 	document.filtro.chFone3.value = 1; 		else  	document.filtro.chFone3.value	=	0;
		if(document.filtro.chCel.checked   == true)		document.filtro.chCel.value   = 1; 		else  	document.filtro.chCel.value		=	0;
		if(document.filtro.chFax.checked   == true)	 	document.filtro.chFax.value   = 1; 		else  	document.filtro.chFax.value		=	0;
		if(document.filtro.chCompF.checked == true) 	document.filtro.chCompF.value = 1; 		else  	document.filtro.chCompF.value	=	0;
		if(document.filtro.chEmail.checked == true)		document.filtro.chEmail.value = 1; 		else  	document.filtro.chEmail.value	=	0;
		if(document.filtro.chNumD.checked  == true)	 	document.filtro.chNumD.value  = 1; 		else  	document.filtro.chNumD.value	=	0;
		if(document.filtro.chNumNF.checked == true) 	document.filtro.chNumNF.value = 1; 		else  	document.filtro.chNumNF.value	=	0;
		if(document.filtro.chDataF.checked == true) 	document.filtro.chDataF.value = 1; 		else  	document.filtro.chDataF.value	=	0;
		if(document.filtro.chLCob.checked  == true) 	document.filtro.chLCob.value  = 1; 		else  	document.filtro.chLCob.value	=	0;
		if(document.filtro.chDataL.checked == true)		document.filtro.chDataL.value = 1; 		else  	document.filtro.chDataL.value	=	0;
		if(document.filtro.chDataV.checked == true)	 	document.filtro.chDataV.value = 1;		else  	document.filtro.chDataV.value	=	0;
		if(document.filtro.chDataP.checked == true) 	document.filtro.chDataP.value = 1; 		else  	document.filtro.chDataP.value	=	0;
		if(document.filtro.chValor.checked == true) 	document.filtro.chValor.value = 1;		else  	document.filtro.chValor.value	=	0;
		if(document.filtro.chValDp.checked == true)		document.filtro.chValDp.value = 1; 		else  	document.filtro.chValDp.value	=	0;
		if(document.filtro.chValDe.checked == true)	 	document.filtro.chValDe.value = 1;		else  	document.filtro.chValDe.value	=	0;
		if(document.filtro.chValF.checked  == true)	 	document.filtro.chValF.value  = 1; 		else  	document.filtro.chValF.value	=	0;
		if(document.filtro.chLRec.checked  == true)		document.filtro.chLRec.value  = 1; 		else  	document.filtro.chLRec.value	=	0;
		if(document.filtro.chStat.checked  == true)	 	document.filtro.chStat.value  = 1;		else  	document.filtro.chStat.value	=	0;
		if(document.filtro.chObs.checked   == true)	 	document.filtro.chObs.value   = 1; 		else  	document.filtro.chObs.value		=	0;
		
		ativaNome('Contas a Receber');
		
		return true;
	}
	
	function filtro_ordenar_filtro(valor,typeDate){
		if(typeDate == undefined){
			typeDate = 'text';
		}
		
		if(document.filtro.filtro_tipoDado != undefined){
			document.filtro.filtro_tipoDado.value = typeDate;
		}
	
		if(document.filtro.filtro_ordem.value == valor){
			if(document.filtro.filtro_ordem_direcao.value == "ascending"){
				document.filtro.filtro_ordem_direcao.value = "descending";
			}else{
				document.filtro.filtro_ordem_direcao.value = "ascending";
			}
		}else{
			document.filtro.filtro_ordem.value = valor;
		}
		if(validar_filtro() == true){
			document.filtro.submit();
		}
	}
	
	function selecionaTodos(campo){
		for (i=0;i<document.filtro.length;i++){
			if(document.filtro[i].name.substr(0,2) == 'ch' && document.filtro[i].name != 'chTodos'){
				if(campo.checked == true){
					if(document.filtro[i].checked == false){
						document.filtro[i].checked = true;	
					}
				}else{
					if(document.filtro[i].checked == true){
						document.filtro[i].checked = false;	
					}
				}
			}
		}
	}
	
	function visualizar_boleto(IdContaReceber){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;			
		}
		
		var xmlhttp = false;
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
	    
	    url = "xml/conta_receber_visualizar_boleto.php?IdContaReceber="+IdContaReceber;

		xmlhttp.open("GET", url,true);
	    	
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("Url")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Url = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Erro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Erro = nameTextNode.nodeValue;
						
						if(Erro == 0){
							window.open(Url);
						} else{
							document.formulario.Erro.value = Erro;
							verificaErro();
						}
					}
				}		
			}
			return true;
		}
		xmlhttp.send(null);	
   	}
	function busca_pessoa_aproximada(campo,event){
		var url = "xml/pessoa_nome.php?Nome="+campo.value;
		
		call_ajax(url,function (xmlhttp){
			var NomeDefault = new Array(), nameNode, nameTextNode;
			
			if(campo.value != '' && xmlhttp.responseText != "false"){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("NomeDefault").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeDefault[i] = nameTextNode.nodeValue;
				}
			}
			
			busca_aproximada('filtro',campo,event,NomeDefault,22,5);
		},false);
	}