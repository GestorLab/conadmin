	function excluir(IdContaReceber,IdStatus){
		if(IdStatus != 0){
			var url = 'cadastro_cancelar_conta_receber.php?IdContaReceber='+IdContaReceber;
			window.location.replace(url);
		}
	}
	function cadastrar(acao){
		document.formulario.Acao.value	=	acao;
		if(acao == 'alterar' || acao == 'receber'){
			if(validar(acao)==true){
				document.formulario.submit();
			}
		}else{
			document.formulario.submit();
		}
	}
	function validar(){
		if(document.formulario.DataVencimento.value==''){
			mensagens(1);
			document.formulario.DataVencimento.focus();
			return false;
		}else{
			if(isData(document.formulario.DataVencimento.value) == false){
				mensagens(27);
				document.formulario.DataVencimento.focus();
				return false;
			}
		}
		if(document.formulario.ValorDesconto.value==''){
			mensagens(1);
			document.formulario.ValorDesconto.focus();
			return false;
		}
		if(document.formulario.ValorDespesas.value==''){
			mensagens(1);
			document.formulario.ValorDespesas.focus();
			return false;
		}
		if(document.formulario.NumeroNF.value!=''){
			if(document.formulario.DataNF.value==''){	
				mensagens(1);
				document.formulario.DataNF.focus();
				return false;
			}else{
				if(isData(document.formulario.DataNF.value) == false){
					mensagens(27);
					document.formulario.DataNF.focus();
					return false;
				}	
			}
		}
		if(document.formulario.IdTipoLocalCobranca.value=='3'){
			if(document.formulario.IdPosicaoCobranca.value==''){
				mensagens(1);
				document.formulario.IdPosicaoCobranca.focus();
				return false;
			}
		}
		if(document.formulario.Acao.value == 'receber'){
			if(document.formulario.IdLocalRecebimento.value == ''){
				mensagens(1);
				document.formulario.IdLocalRecebimento.focus();
				return false;
			}
			if(document.formulario.DataRecebimento.value==''){
				mensagens(1);
				document.formulario.DataRecebimento.focus();
				return false;
			}else{
				if(isData(document.formulario.DataRecebimento.value) == false){
					mensagens(27);
					document.formulario.DataRecebimento.focus();
					return false;
				}
			}
			if(document.formulario.ValorDescontoRecebimento.value == ''){
				mensagens(1);
				document.formulario.ValorDescontoRecebimento.focus();
				return false;
			}
			if(document.formulario.ValorMoraMulta.value == ''){
				mensagens(1);
				document.formulario.ValorMoraMulta.focus();
				return false;
			}
			if(document.formulario.ValorOutrasDespesas.value == ''){
				mensagens(1);
				document.formulario.ValorOutrasDespesas.focus();
				return false;
			}
			var posInicial=0,posFinal=0,temp=0;
			if(document.formulario.IdLocalRecebimento.value!=''){
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(temp == 0 && document.formulario[i].name.substring(0,6) == 'Valor^'){
							posInicial = i;
							temp = 1;
						}
						if(document.formulario[i].name.substring(0,3) == 'Obs'){
							posFinal = i;
						}
					}
				}
				if(posInicial != 0){
					for(i = posInicial; i<posFinal; i=i+2){
						if(document.formulario[i+1].value == '1' && document.formulario[i].value == ''){
							mensagens(1);
							document.formulario[i].focus();
							return false;
						}
					}
				}
			}
		}
		mensagens(0);
		return true;
	}
	
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			if(id == 'DataNF'){
				document.getElementById(id).style.color='#000';
			}else{
				document.getElementById(id).style.color='#C10000';
			}
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			if(id == 'DataNF'){
				document.getElementById(id).style.color='#000';
			}else{
				document.getElementById(id).style.color='#C10000';
			}
			mensagens(0);
			return true;
		}	
	}
	
	function verificaAcao(){
		if(document.formulario.IdLocalRecebimento.value == '' && document.formulario.DataRecebimento.value == ''){
			document.getElementById('DataRecebimento').style.color			=	'#000';
			document.getElementById('cpLocalRecebimento').style.color		=	'#000';
			document.getElementById('cpValorDesconto').style.color			=	'#000';
			document.getElementById('cpValorMoraMulta').style.color			=	'#000';
			document.getElementById('cpValorOutrasDespesas').style.color	=	'#000';
			
			document.formulario.ValorMoraMulta.readOnly 			= true;
			document.formulario.ValorDescontoRecebimento.readOnly	= true;
			document.formulario.ValorOutrasDespesas.readOnly		= true;
			
			if(document.formulario.IdStatus.value == '0'){
				document.formulario.bt_alterar.disabled		=	false;
				document.formulario.bt_imprimir1.disabled	=	true;
				document.formulario.bt_imprimir2.disabled	=	true;
				document.formulario.bt_receber.disabled		=	true;
				document.formulario.bt_cancelar.disabled	=	true;
			}else{
				document.formulario.Acao.value				=	'alterar';
				document.formulario.bt_alterar.disabled		=	false;
				document.formulario.bt_imprimir1.disabled	=	false;
				document.formulario.bt_imprimir2.disabled	=	false;
				document.formulario.bt_receber.disabled		=	true;
			}
			
			switch(document.formulario.IdStatus.value){
				case '0':
					document.formulario.ValorMoraMulta.readOnly 			= true;
					document.formulario.ValorDescontoRecebimento.readOnly	= true;
					document.formulario.ValorOutrasDespesas.readOnly		= true;
					
					document.formulario.bt_enviar.disabled		=	true;
					document.formulario.bt_alterar.disabled		=	false;
					document.formulario.bt_imprimir1.disabled	=	true;
					document.formulario.bt_imprimir2.disabled	=	true;
					document.formulario.bt_receber.disabled		=	true;
					document.formulario.bt_cancelar.disabled	=	true;
					break;
				case '1':
					document.formulario.ValorMoraMulta.readOnly 			= false;
					document.formulario.ValorDescontoRecebimento.readOnly	= false;
					document.formulario.ValorOutrasDespesas.readOnly		= false;
	
					document.formulario.Acao.value				=	'receber';
					document.formulario.bt_enviar.disabled		=	false;
					document.formulario.bt_alterar.disabled		=	false;
					document.formulario.bt_imprimir1.disabled	=	false;
					document.formulario.bt_imprimir2.disabled	=	false;
					document.formulario.bt_receber.disabled		=	false;
					document.formulario.bt_cancelar.disabled	=	false;
					break;
				case '2':
					document.formulario.ValorMoraMulta.readOnly 			= true;
					document.formulario.ValorDescontoRecebimento.readOnly	= true;
					document.formulario.ValorOutrasDespesas.readOnly		= true;
					
					document.formulario.Acao.value				=	'imprimir';
					document.formulario.bt_enviar.disabled		=	false;
					document.formulario.bt_alterar.disabled		=	false;
					document.formulario.bt_imprimir1.disabled	=	false;
					document.formulario.bt_imprimir2.disabled	=	false;
					document.formulario.bt_receber.disabled		=	false;
					document.formulario.bt_cancelar.disabled	=	false;
					break;
			}
		}else{
			if((document.formulario.Acao.value == 'receber' ||  document.formulario.Acao.value == 'imprimir') && (document.formulario.IdLocalRecebimento.value != '' || document.formulario.DataRecebimento.value != '')){
				document.getElementById('DataRecebimento').style.color			=	'#C10000';
				document.getElementById('cpLocalRecebimento').style.color		=	'#C10000';
				document.getElementById('cpValorDesconto').style.color			=	'#C10000';
				document.getElementById('cpValorMoraMulta').style.color			=	'#C10000';
				document.getElementById('cpValorOutrasDespesas').style.color	=	'#C10000';
			}
		}
		/*if(document.formulario.DataRecebimento.value != ''){
			validar_Data('DataRecebimento',document.formulario.DataRecebimento);
		}*/
	}
	function statusInicial(){
		if(document.formulario.ValorContaReceber.value == ''){
			document.formulario.ValorContaReceber.value	=	'0,00';
		}
		if(document.formulario.ValorDesconto.value == ''){
			document.formulario.ValorDesconto.value	=	'0,00';
		}
		if(document.formulario.ValorDespesas.value == ''){
			document.formulario.ValorDespesas.value	=	'0,00';
		}
		if(document.formulario.ValorFinal.value == ''){
			document.formulario.ValorFinal.value	=	'0,00';
		}
		if(document.formulario.ValorRecebimento.value == ''){
			document.formulario.ValorRecebimento.value	=	'0,00';
		}
		if(document.formulario.ValorDescontoRecebimento.value == ''){
			document.formulario.ValorDescontoRecebimento.value	=	'0,00';
		}
		if(document.formulario.ValorMoraMulta.value == ''){
			document.formulario.ValorMoraMulta.value	=	'0,00';
		}
		if(document.formulario.ValorOutrasDespesas.value == ''){
			document.formulario.ValorOutrasDespesas.value	=	'0,00';
		}
		if(document.formulario.ValorReceber.value == ''){
			document.formulario.ValorReceber.value	=	'0,00';
		}
	}
	
	function inicia(){
		document.formulario.IdContaReceber.focus();
		statusInicial();
	}
	function calculaValor(conta,nome){
		if(conta == 'ContaReceber'){
			var desc, desp, valor, receb, mora;
	
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
			var desc, desp, valor, valorFinal;
			
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
	    
		var posInicial,posFinal;
		for(i=0;i<document.formulario.length;i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name == 'ValorReceber'){
					posInicial = i;
				}
			}
		}

		
		url = "xml/conta_receber_recebimento_parametro.php?IdContaReceber="+IdContaReceber+"&IdLocalRecebimento="+IdLocalRecebimento;
 		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){		
					if(xmlhttp.responseText == 'false'){
						document.getElementById('cp_parametros').style.display				=	'none';	
						
						for(ii=posInicial;ii<posFinal;ii++){
							if(document.formulario[ii].name != undefined){
								document.formulario[ii].value	=	"";
							}
						}
					}else{
						document.getElementById('cp_parametros').style.display				=	'block';	
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroRecebimento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroRecebimento = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorParametro = nameTextNode.nodeValue;
							
							for(ii=posInicial;ii<document.formulario.length;ii++){
								if(document.formulario[ii].name != undefined){
									if(document.formulario[ii].name == 'Valor^'+IdParametroRecebimento){
										document.formulario[ii].value	=	ValorParametro;
										break;
									}
								}
							}	
						}
					}	
					
				}	
			} 
			return true;
		}
		xmlhttp.send(null);
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
		if(document.filtro.chValDeC.checked == true)	document.filtro.chValDeC.value = 1; 	else  	document.filtro.chValDeC.value	=	0;
		if(document.filtro.chValDe.checked == true)	 	document.filtro.chValDe.value = 1;		else  	document.filtro.chValDe.value	=	0;
		if(document.filtro.chPDesc.checked == true)		document.filtro.chPDesc.value = 1; 		else  	document.filtro.chPDesc.value	=	0;
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
	
	function listarRecebimento(IdContaReceber,Erro,IdContaReceberRecebimentoTemp){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		if(IdContaReceberRecebimentoTemp == undefined){
			IdContaReceberRecebimentoTemp = '';
		}
		
		var nameNode, nameTextNode, url, Condicao;
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
	    
	   	url = "xml/conta_receber_recebimento.php?IdContaReceber="+IdContaReceber;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
						document.getElementById('totalValorRecebido').innerHTML				=	"0,00";	
						document.getElementById('totalRecebimentos').innerHTML				=	"Total: 0";	
						
						// Fim de Carregando
						carregando(false);
					}else{
						while(document.getElementById('tabelaRecebimentos').rows.length > 2){
							document.getElementById('tabelaRecebimentos').deleteRow(1);
						}
						
						var tam, linha, c0, c1, c2, c3, c4;
						var IdContaReceberRecebimento,DataRecebimento,ValorDesconto,ValorRecebido,Valor,DescricaoLocalRecebimento,TotalDesc=0,IdRecibo,TotalReceb=0,ValorContaReceber;
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorContaReceber = nameTextNode.nodeValue;
							
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
							
							if(IdStatus == 0){ /*Cancelado*/
								linha.style.backgroundColor = "#FFD2D2";
							}
							
							if(ValorContaReceber > ValorRecebido){
								linha.style.backgroundColor = document.formulario.CorRecebidoDesc.value;
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
							
							if(IdStatus == 1){
								c5.innerHTML = "<a href='recibo.php?IdLoja="+IdLoja+"&IdRecibo="+IdRecibo+"' target='_blank'>"+IdRecibo+"</a>";
							}else{
								c5.innerHTML = "Canc.";
							}
							c5.style.cursor = "pointer";
							
							if(IdStatus == 1){
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
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	} 	
	function chama_mascara(campo,event){
		switch(document.filtro.filtro_campo.value){
			case 'DataLancamento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataVencimento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'MesLancamento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesVencimento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'IdProcessoFinanceiro':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			default:
				campo.maxLength	=	100;
		}
	}
